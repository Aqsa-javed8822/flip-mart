<?php
/**
 * Main FilterHooks class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\VariationGallery;

use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit();

/**
 * Main FilterHooks class.
 */
class GalleryFrontEnd {

	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Module Class Constructor.
	 */
	private function __construct() {
		add_filter( 'woocommerce_locate_template', [ $this, 'override_templates' ], 10, 2 );
		add_action( 'wp_footer', [ $this, 'slider_and_thumbnail_template_js' ] );

		// Side (left/right) thumbnail layouts are free. Pro re-filters both of these at
		// its own priority and wins where the two overlap, so pro behaviour is unchanged.
		add_filter( 'rtsb/vg/slider/options', [ $this, 'side_thumbnail_slider_options' ] );
		add_filter( 'rtsb/vg/thumbnails/slider/options', [ $this, 'side_thumbnails_slider_options' ], 10, 2 );
		add_filter( 'woocommerce_available_variation', [ $this, 'available_variation_gallery' ], 90, 3 );
		add_filter( 'woocommerce_product_variation_get_gallery_image_ids', [ $this, 'suppress_native_variation_gallery' ], 20, 2 );

		// Track the variation form so its embedded "default gallery" snapshot can be
		// skipped. `wc_get_template` (not `woocommerce_locate_template`) is what the
		// stub swaps on, because WooCommerce caches the located path and skips the
		// locate filter on repeat renders within the same request.
		add_action( 'woocommerce_before_template_part', [ $this, 'mark_variation_form_open' ] );
		add_action( 'woocommerce_after_template_part', [ $this, 'mark_variation_form_closed' ] );
		add_filter( 'wc_get_template', [ $this, 'skip_native_gallery_snapshot' ], 60, 2 );

		add_action( 'wp_ajax_rtsb_vg_get_default_gallery_images', [ $this, 'get_default_gallery_images' ] );
		add_action( 'wp_ajax_nopriv_rtsb_vg_get_default_gallery_images', [ $this, 'get_default_gallery_images' ] );

		// On-demand single-variation gallery. Nonce-less / read-only by design,
		// mirroring WooCommerce core's own `woocommerce_get_variation` AJAX: a
		// required nonce breaks on full-page-cached pages because the embedded
		// nonce goes stale, and the payload is public, read-only product images.
		add_action( 'wp_ajax_rtsb_get_variation_gallery', [ $this, 'get_variation_gallery' ] );
		add_action( 'wp_ajax_nopriv_rtsb_get_variation_gallery', [ $this, 'get_variation_gallery' ] );
	}
	/**
	 * @param array  $available_variation Available variation.
	 * @param object $variationProductObject object.
	 * @param object $variation variation.
	 *
	 * @return array
	 */
	public function available_variation_gallery( $available_variation, $variationProductObject, $variation ) {
		$variation_id = absint( $variation->get_id() );

		if ( $this->is_single_product_context() ) {
			// On single product pages the Variation Gallery replaces the WooCommerce
			// product gallery (see override_templates()) and controls the image swap
			// itself.
			//
			// We strip WooCommerce core's own variation `image` data so its variation
			// form does not ALSO try to swap `.wp-post-image`. Otherwise the two handlers
			// fight over the same element — causing flicker, stuck navigation, and the
			// gallery getting stuck on the featured Product Image (WC core's fallback
			// when a variation has no image of its own).
			if ( apply_filters( 'rtsb/vg/disable_wc_variation_image', true, $available_variation, $variation ) ) {
				unset( $available_variation['image'], $available_variation['image_id'] );
			}

			/*
			 * WooCommerce 11.1+ also ships gallery markup in `gallery_images_html` and
			 * swaps it in from `add-to-cart-variation.js`, replacing the whole
			 * `.woocommerce-product-gallery` element. Our gallery owns that DOM, so the
			 * payload is dropped to keep core's swap from competing with ours — and to
			 * keep a duplicate copy of the gallery out of `data-product_variations`.
			 *
			 * suppress_native_variation_gallery() normally stops this being built at
			 * all; blanking it here is the belt-and-braces for any request where the
			 * gallery ids were re-added by a third party after that filter ran.
			 */
			if ( GalleryFns::use_native_gallery() && isset( $available_variation['gallery_images_html'] ) ) {
				$available_variation['gallery_images_html'] = '';
			}

			// Inline the gallery for small products so variation swaps are instant with no
			// per-click AJAX (the frontend renders variation_gallery_images directly). For
			// large products we omit it and fall back to the lazy AJAX path
			// (get_variation_gallery()) to keep the page lean. WooCommerce itself only
			// embeds variations inline below woocommerce_ajax_variation_threshold, so
			// inlining above the threshold would bloat the page with no benefit.
			$children  = ( $variationProductObject instanceof \WC_Product ) ? $variationProductObject->get_children() : [];
			$threshold = absint( apply_filters( 'rtsb/vg/inline_gallery_threshold', 20 ) );

			if ( $threshold && count( $children ) <= $threshold ) {
				$available_variation['variation_gallery_images'] = GalleryFns::get_variation_gallery( $variation->get_parent_id(), $variation_id );
			}

			return apply_filters( 'rtsb/vg/available/variation/gallery', $available_variation, $variation, $variation_id );
		}

		// Other contexts (e.g. the archive/shop variation image swap, handled by the
		// Variation Swatches showcase which still needs WC's `image`) consume the
		// inline data, so expose an already-cached gallery if one exists. This is a
		// cheap get_transient() — no prop building.
		$transient_name = GalleryFns::get_transient_name( $variation_id, 'variation-images' );
		$images         = $transient_name ? get_transient( $transient_name ) : false;
		if ( ! empty( $images ) && is_array( $images ) ) {
			$available_variation['variation_gallery_images'] = array_values( $images );
		}

		return apply_filters( 'rtsb/vg/available/variation/gallery', $available_variation, $variation, $variation_id );
	}

	/**
	 * Turn the main slider into a thumbnail-linked Swiper for side layouts.
	 *
	 * The bottom layout is deliberately left alone: its thumbnails are plain stacked
	 * items (`thumbsSelectorNoSlider`), and switching it to a Swiper would change how
	 * every existing free site renders. Only left/right — which need a vertical,
	 * linked thumbnail strip — are adjusted here.
	 *
	 * @param array $slider_options Main slider options.
	 *
	 * @return array
	 */
	public function side_thumbnail_slider_options( $slider_options ) {
		if ( ! $this->is_side_thumbnail_layout( GalleryFns::get_gallery_style() ) ) {
			return $slider_options;
		}

		// Side layouts always track the active image's height: the two columns sit
		// next to each other, so sizing to the tallest slide would leave a blank gap
		// under a short image.
		$slider_options['autoHeight']     = true;
		$slider_options['observer']       = true;
		$slider_options['observeParents'] = true;
		$slider_options['thumbsSelector'] = '.rtsb-vg-thumb-slider';

		unset( $slider_options['thumbsSelectorNoSlider'] );

		return $slider_options;
	}

	/**
	 * Supply the vertical thumbnail Swiper options for side layouts.
	 *
	 * @param array $options Thumbnail slider options.
	 * @param array $args    Render args (thumbnailsPosition, randDom).
	 *
	 * @return array
	 */
	public function side_thumbnails_slider_options( $options, $args ) {
		$position = $args['thumbnailsPosition'] ?? 'bottom';

		if ( ! $this->is_side_thumbnail_layout( $position ) ) {
			return $options;
		}

		$col = GalleryFns::get_options( 'thumbnails_columns' );
		$gap = GalleryFns::get_options( 'thumbnails_gap' );

		return [
			'slidesPerView' => absint( $col ?: 4 ),
			'spaceBetween'  => ( null === $gap || '' === $gap ) ? 10 : absint( $gap ),
			'direction'     => 'vertical',
			'speed'         => 500,
			'loop'          => false,
			'navigation'    => [
				'nextEl' => '.swiper-gallery-next.rtsb-random-id-' . esc_attr( $args['randDom'] ?? '' ),
				'prevEl' => '.swiper-gallery-prev.rtsb-random-id-' . esc_attr( $args['randDom'] ?? '' ),
			],
		] + $options;
	}

	/**
	 * Whether a gallery style places the thumbnails beside the main image.
	 *
	 * @param string $style Gallery style / thumbnail position.
	 *
	 * @return bool
	 */
	private function is_side_thumbnail_layout( $style ) {
		return in_array( $style, [ 'left', 'right' ], true );
	}

	/**
	 * Whether WooCommerce is currently rendering the variable add-to-cart template.
	 *
	 * @var bool
	 */
	private $rendering_variation_form = false;

	/**
	 * Whether this module's gallery template replaced WooCommerce's for this request.
	 *
	 * Used to decide when it is safe to stop WooCommerce building its own variation
	 * gallery output: only once our template is demonstrably the one rendering the
	 * product images. Block themes and the WooCommerce Product Gallery block never go
	 * through `single-product/product-image.php`, so they must keep reading the native
	 * gallery untouched even though `is_product()` is true there.
	 *
	 * @var bool
	 */
	private $gallery_template_rendered = false;

	/**
	 * Note that the variable add-to-cart template has started rendering.
	 *
	 * @param string $template_name Template being rendered.
	 *
	 * @return void
	 */
	public function mark_variation_form_open( $template_name ) {
		if ( 'single-product/add-to-cart/variable.php' === $template_name ) {
			$this->rendering_variation_form = true;
		}
	}

	/**
	 * Note that the variable add-to-cart template has finished rendering.
	 *
	 * @param string $template_name Template that finished rendering.
	 *
	 * @return void
	 */
	public function mark_variation_form_closed( $template_name ) {
		if ( 'single-product/add-to-cart/variable.php' === $template_name ) {
			$this->rendering_variation_form = false;
		}
	}

	/**
	 * Skip the gallery snapshot WooCommerce embeds inside the variation form.
	 *
	 * `single-product/add-to-cart/variable.php` renders the whole product gallery a
	 * second time into a hidden `<template>`, so core's variation script can restore it
	 * after swapping in per-variation markup. That swap never happens here — the
	 * payload driving it is dropped in available_variation_gallery() — so the snapshot
	 * is dead weight: it would duplicate the entire slider on every variable product
	 * page and fire the gallery's render hooks a second time.
	 *
	 * Scoped to renders that happen *inside* the variation form, so a genuine second
	 * gallery elsewhere on the page is untouched.
	 *
	 * @param string $template      Resolved template path.
	 * @param string $template_name Template name being loaded.
	 *
	 * @return string
	 */
	public function skip_native_gallery_snapshot( $template, $template_name ) {
		if ( 'single-product/product-image.php' !== $template_name ) {
			return $template;
		}

		// Re-assert ownership here as well as in override_templates(): WooCommerce
		// caches the located path, so on a store with a persistent object cache the
		// locate filter can be skipped entirely while this one always runs. The
		// snapshot render is excluded, otherwise it would claim ownership on behalf of
		// a gallery that has not actually been rendered.
		if ( ! $this->rendering_variation_form && false !== strpos( $template, 'variation-gallery' ) ) {
			$this->gallery_template_rendered = true;
		}

		if ( ! $this->rendering_variation_form ) {
			return $template;
		}

		if ( ! GalleryFns::use_native_gallery() || ! $this->gallery_template_rendered || ! $this->is_single_product_context() ) {
			return $template;
		}

		if ( ! apply_filters( 'rtsb/vg/skip/native/gallery/snapshot', true ) ) {
			return $template;
		}

		$stub = Fns::locate_template( 'variation-gallery/vg-noop' );

		return file_exists( $stub ) ? $stub : $template;
	}

	/**
	 * Stop WooCommerce building its own variation gallery markup on the frontend.
	 *
	 * `WC_Product_Variable::get_available_variation()` renders
	 * `single-product/product-image.php` once per variation whenever the native gallery
	 * holds images — and on a single product page that template is ours, so after
	 * migration every variation would re-render the full slider into
	 * `data-product_variations`. Returning an empty list skips that render entirely;
	 * the gallery data our script consumes is supplied separately through
	 * `variation_gallery_images` and the on-demand AJAX endpoint.
	 *
	 * Only the `view` context reaches this filter, so
	 * `GalleryFns::get_native_variation_gallery_ids()` (which reads in `edit` context)
	 * and every admin/REST/CSV reader still see the stored value.
	 *
	 * @param array       $gallery_image_ids Native gallery image IDs.
	 * @param \WC_Product $variation         Variation object.
	 *
	 * @return array
	 */
	public function suppress_native_variation_gallery( $gallery_image_ids, $variation ) {
		if ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return $gallery_image_ids;
		}

		// `gallery_template_rendered` is the load-bearing condition, not just a
		// shortcut: block themes and the WooCommerce Product Gallery block read this
		// same prop on a single product page (where is_single_product_context() is
		// true) but never render our template, so suppressing there would empty their
		// galleries instead of ours.
		if ( ! GalleryFns::use_native_gallery() || ! $this->gallery_template_rendered || ! $this->is_single_product_context() ) {
			return $gallery_image_ids;
		}

		if ( ! apply_filters( 'rtsb/vg/suppress/native/variation/gallery', true, $variation ) ) {
			return $gallery_image_ids;
		}

		return [];
	}

	/**
	 * Whether the current request is rendering a single product page (where this
	 * module owns the gallery), as opposed to an archive/shop context.
	 *
	 * On page render `is_product()` / BuilderFns::is_product() are reliable. During
	 * WooCommerce's `get_variation` AJAX (used when the variation threshold is low,
	 * e.g. with Variation Swatches active) those conditionals are false, so we fall
	 * back to the referring URL to tell single-product requests apart from
	 * archive/shop requests. Result is cached for the duration of the request.
	 *
	 * @return bool
	 */
	private function is_single_product_context() {
		static $is_single = null;

		if ( null !== $is_single ) {
			return $is_single;
		}

		if ( is_product() || BuilderFns::is_product() ) {
			$is_single = true;

			return $is_single;
		}

		$is_single = false;

		if ( wp_doing_ajax() ) {
			$referer = wp_get_referer();
			if ( $referer ) {
				$referer_id = url_to_postid( $referer );
				if ( $referer_id ) {
					$referer_type = get_post_type( $referer_id );

					if ( 'product' === $referer_type ) {
						$is_single = true;
					} elseif ( BuilderFns::$post_type_tb === $referer_type && 'product' === BuilderFns::builder_type( $referer_id ) ) {
						/*
						 * A ShopBuilder single-product template rendered on its own URL
						 * (the template-builder preview). The referring post is a
						 * `rtsb_builder` template rather than a product, so the check
						 * above misses it — but that page renders this module's gallery
						 * exactly like a product page does.
						 *
						 * Without this, WooCommerce's `get_variation` AJAX keeps its own
						 * `gallery_images_html`, and `wc_variations_image_update()`
						 * replaceWith()s our entire gallery on every variation change.
						 */
						$is_single = true;
					}
				}
			}
		}

		return $is_single;
	}
	/**
	 * @return void
	 */
	public function get_default_gallery_images() {
		if ( ! wp_verify_nonce( Fns::get_nonce(), rtsb()->nonceText ) ) {
			wp_send_json_error( esc_html__( 'Something Went Wrong', 'shopbuilder' ) );
		}
		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

		if ( ! $product_id ) {
			wp_send_json_error( esc_html__( 'Invalid product ID.', 'shopbuilder' ) );
		}

		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			wp_send_json_error( esc_html__( 'Product not found.', 'shopbuilder' ) );
		}

		if ( ! $this->can_user_view_product( $product ) ) {
			wp_send_json_error( esc_html__( 'You do not have permission to view this product.', 'shopbuilder' ) );
		}

		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		$images     = GalleryFns::get_gallery_images_and_props( $product_id );
		wp_send_json_success( $images );
	}

	/**
	 * AJAX: Return the gallery image props for a single selected variation.
	 *
	 * Read-only / nonce-less by design, mirroring WooCommerce core's own
	 * `woocommerce_get_variation` endpoint. A required nonce would break on
	 * full-page-cached pages (stale embedded nonce); the response is public,
	 * read-only product image data, so there is no CSRF concern.
	 *
	 * @return void
	 */
	public function get_variation_gallery() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$variation_id = isset( $_POST['variation_id'] ) ? absint( wp_unslash( $_POST['variation_id'] ) ) : 0;
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$product_id = isset( $_POST['product_id'] ) ? absint( wp_unslash( $_POST['product_id'] ) ) : 0;

		if ( ! $variation_id ) {
			wp_send_json_error( esc_html__( 'Invalid variation ID.', 'shopbuilder' ) );
		}

		$variation = wc_get_product( $variation_id );

		if ( ! $variation || 'variation' !== $variation->get_type() ) {
			wp_send_json_error( esc_html__( 'Variation not found.', 'shopbuilder' ) );
		}

		if ( ! $product_id ) {
			$product_id = absint( $variation->get_parent_id() );
		}

		$product = $product_id ? wc_get_product( $product_id ) : false;

		if ( ! $product || ! $this->can_user_view_product( $product ) ) {
			wp_send_json_error( esc_html__( 'You do not have permission to view this product.', 'shopbuilder' ) );
		}

		$images = GalleryFns::get_variation_gallery( $product_id, $variation_id );
		wp_send_json_success( $images );
	}

	/**
	 * Determines if the current user has permission to view a product.
	 *
	 * Guests may only access products with a `publish` status. Logged-in users
	 * are checked against the `read_post` meta capability, which resolves to the
	 * correct primitive capability for the product's status (published, private,
	 * draft, etc.) and ownership, preventing exposure of unpublished products.
	 *
	 * @param \WC_Product $product The product to check access for.
	 * @return bool True if user can view the product, false otherwise.
	 */
	private function can_user_view_product( $product ) {
		$product_id = $product->get_id();

		if ( ! is_user_logged_in() ) {
			return 'publish' === get_post_status( $product_id );
		}

		return current_user_can( 'read_post', $product_id );
	}
	/**
	 * @param string $template template.
	 * @param string $template_name template name.
	 * @return mixed|null
	 */
	public function override_templates( $template, $template_name ) {
		// List of templates you want to override.
		if ( 'single-product/product-image.php' === $template_name ) {
			$galleryStyle    = GalleryFns::get_gallery_style();
			$galleryStyle    = apply_filters( 'rtsb/vg/thumbnails/position', $galleryStyle );
			$temp            = rtsb()->has_pro() && 'grid' === $galleryStyle ? 'variation-gallery/vg-grid-view' : 'variation-gallery/product-image';
			$custom_template = Fns::locate_template( $temp );
			if ( file_exists( $custom_template ) ) {
				// Record that our gallery — not WooCommerce's — is what this request
				// renders, so the native-output suppression below can safely engage.
				$this->gallery_template_rendered = true;

				return $custom_template;
			}
		}
		return $template;
	}
	/**
	 * @return void
	 */
	public function slider_and_thumbnail_template_js() {
		wp_enqueue_script( 'underscore' );
		Fns::load_template( 'variation-gallery/vg-slider-template', [] );
		Fns::load_template( 'variation-gallery/vg-thumbnail-template', [] );
	}
}
