<?php
/**
 * Sticky add-to-cart Functions Class.
 *
 * @package Rse\SB
 */

namespace RadiusTheme\SB\Modules\VariationGallery;

use RadiusTheme\SB\Helpers\Fns;
use WC_Product;

defined( 'ABSPATH' ) || exit();

/**
 * Sticky add-to-cart Functions Class.
 */
class GalleryFns {

	/**
	 * Legacy post meta key holding this module's own variation gallery.
	 *
	 * Kept in place forever: it keeps CSV export/import and any third-party reader
	 * working, and lets a store roll back to an older WooCommerce without data loss.
	 *
	 * @var string
	 */
	const LEGACY_GALLERY_META_KEY = 'rtsb_vg_images';

	/**
	 * Sentinel meta marking a variation whose gallery is owned by the native store.
	 *
	 * Set by the migration and by the admin save path. Its presence — not its value —
	 * is what matters: once stamped, an empty native gallery is an explicit "no
	 * images" choice, so the legacy meta must never resurrect removed images.
	 *
	 * @var string
	 */
	const NATIVE_GALLERY_SENTINEL_META_KEY = '_rtsb_vg_gallery_migrated';

	/**
	 * First WooCommerce version shipping the native variation gallery for everyone.
	 *
	 * Only used as a fallback when WooCommerce's own feature gate cannot be reached.
	 *
	 * @var string
	 */
	const NATIVE_GALLERY_WC_VERSION = '11.1.0';

	/**
	 * WooCommerce's variation gallery package, which owns the feature gate.
	 *
	 * @var string
	 */
	const NATIVE_GALLERY_PACKAGE = 'Automattic\WooCommerce\Internal\VariationGallery\Package';

	/**
	 * Whether the running WooCommerce provides the native variation gallery.
	 *
	 * WooCommerce 11.0 ships the feature behind an option/canary gate and 11.1 turns
	 * it on for everyone, so a bare version comparison would mis-read the 11.0 stores
	 * that already have it. WooCommerce's own gate is asked first and the version
	 * comparison is only the fallback for builds where that class is unavailable.
	 *
	 * This is the single decision point for the whole module — nothing else should
	 * test the WooCommerce version for this feature.
	 *
	 * @return bool
	 */
	public static function use_native_gallery() {
		static $supported = null;

		if ( null === $supported ) {
			$package = self::NATIVE_GALLERY_PACKAGE;

			if ( class_exists( $package ) && method_exists( $package, 'is_enabled' ) ) {
				$supported = (bool) $package::is_enabled();
			} else {
				$supported = defined( 'WC_VERSION' ) && version_compare( WC_VERSION, self::NATIVE_GALLERY_WC_VERSION, '>=' );
			}
		}

		return (bool) apply_filters( 'rtsb/vg/use/native/gallery', $supported );
	}

	/**
	 * Read the native variation gallery IDs straight from storage.
	 *
	 * Uses the `edit` context on purpose: the `view` context runs
	 * `woocommerce_product_variation_get_gallery_image_ids`, which this module filters
	 * on the frontend to stop WooCommerce rendering its own competing gallery markup.
	 * Reading raw keeps that suppression from feeding back into our own resolution.
	 *
	 * @param \WC_Product_Variation $variation Variation object.
	 *
	 * @return array Attachment IDs.
	 */
	public static function get_native_variation_gallery_ids( $variation ) {
		if ( ! is_object( $variation ) || ! method_exists( $variation, 'get_gallery_image_ids' ) ) {
			return [];
		}

		return array_values( array_filter( array_map( 'absint', (array) $variation->get_gallery_image_ids( 'edit' ) ) ) );
	}

	/**
	 * Whether a variation's gallery is already owned by the native store.
	 *
	 * @param int $variation_id Variation ID.
	 *
	 * @return bool
	 */
	public static function is_native_gallery_owned( $variation_id ) {
		return metadata_exists( 'post', absint( $variation_id ), self::NATIVE_GALLERY_SENTINEL_META_KEY );
	}

	/**
	 * Mark a variation's gallery as owned by the native store.
	 *
	 * @param int $variation_id Variation ID.
	 *
	 * @return void
	 */
	public static function mark_native_gallery_owned( $variation_id ) {
		update_post_meta( absint( $variation_id ), self::NATIVE_GALLERY_SENTINEL_META_KEY, 'yes' );
	}

	/**
	 * Read the legacy gallery IDs stored by this module.
	 *
	 * @param int $variation_id Variation ID.
	 *
	 * @return array Attachment IDs.
	 */
	public static function get_legacy_variation_gallery_ids( $variation_id ) {
		$legacy_ids = get_post_meta( absint( $variation_id ), self::LEGACY_GALLERY_META_KEY, true );

		return array_values( array_filter( array_map( 'absint', (array) $legacy_ids ) ) );
	}

	/**
	 * Resolve the ordered gallery attachment IDs for a variation.
	 *
	 * With the native gallery available it is authoritative, and the legacy meta is
	 * only a fallback for variations the migration has not processed yet. Without it
	 * the legacy meta stays authoritative, exactly as before. In both cases the parent
	 * product gallery remains the last resort, so a variation with no gallery of its
	 * own keeps showing the product's thumbnail strip instead of collapsing to a
	 * single image.
	 *
	 * The variation's featured image is not part of this list; the caller prepends it,
	 * matching WooCommerce's own featured/gallery split.
	 *
	 * @param \WC_Product $variation      Variation object.
	 * @param \WC_Product $parent_product Parent product object, or false.
	 *
	 * @return array Attachment IDs.
	 */
	protected static function resolve_variation_gallery_image_ids( $variation, $parent_product ) {
		$variation_id = absint( $variation->get_id() );

		if ( self::use_native_gallery() ) {
			$native_ids = self::get_native_variation_gallery_ids( $variation );

			// Migrated variation: the native gallery is the source of truth and the
			// legacy meta is never consulted — it does not need to exist at all.
			if ( ! empty( $native_ids ) ) {
				return $native_ids;
			}

			// Pre-migration variation: keep serving the legacy meta until the batch
			// runner has copied it across. Once the sentinel is set, an empty native
			// gallery is a deliberate "no images" choice and must be respected.
			if ( ! self::is_native_gallery_owned( $variation_id ) ) {
				$legacy_ids = self::get_legacy_variation_gallery_ids( $variation_id );

				if ( ! empty( $legacy_ids ) ) {
					return $legacy_ids;
				}
			}
		} else {
			$legacy_ids = self::get_legacy_variation_gallery_ids( $variation_id );

			if ( ! empty( $legacy_ids ) ) {
				return $legacy_ids;
			}
		}

		if ( $parent_product ) {
			return (array) $parent_product->get_gallery_image_ids();
		}

		return (array) $variation->get_gallery_image_ids();
	}

	/**
	 * Resolve the saved thumbnail position / gallery style.
	 *
	 * Bottom, left and right ship with the free version. The grid layout needs the pro
	 * templates, so a stored `grid` value falls back to the bottom position whenever
	 * pro is not active — otherwise a downgraded site would try to render a layout it
	 * has no template for.
	 *
	 * Single decision point: every caller resolves the style through here rather than
	 * reading the raw option, so the free/pro split cannot drift between the template,
	 * the template override and the pro slider options.
	 *
	 * @return string One of `bottom`, `left`, `right` or `grid`.
	 */
	public static function get_gallery_style() {
		$saved = self::get_options( 'gallery_style' );

		if ( ! $saved ) {
			$saved = 'bottom';
		}

		if ( 'grid' === $saved && ! rtsb()->has_pro() ) {
			$saved = 'bottom';
		}

		return apply_filters( 'rtsb/vg/gallery/style', $saved );
	}

	/**
	 * Whether any of a variable product's variations carries its own gallery.
	 *
	 * The thumbnail wrapper must exist in the DOM when at least one variation has a
	 * gallery, so JS can populate it on variation change; otherwise it is skipped to
	 * avoid a blank column. Both stores are consulted so the answer stays correct
	 * before, during and after the migration.
	 *
	 * @param \WC_Product $product Parent product object.
	 *
	 * @return bool
	 */
	public static function product_has_variation_gallery( $product ) {
		if ( ! ( $product instanceof WC_Product ) || ! $product->is_type( 'variable' ) ) {
			return false;
		}

		$native = self::use_native_gallery();

		foreach ( $product->get_children() as $child_id ) {
			// Native first: a migrated store answers without ever reading the legacy
			// meta, which may no longer exist.
			if ( $native ) {
				$variation = wc_get_product( $child_id );

				if ( $variation && self::get_native_variation_gallery_ids( $variation ) ) {
					return true;
				}
			}

			if ( self::get_legacy_variation_gallery_ids( $child_id ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string       $key Default Attribute.
	 * @param array|string $default Default Attribute.
	 * @return array|string
	 */
	public static function get_options( $key = null, $default = '' ) {
		$options = Fns::get_options( 'modules', 'variation_gallery' );
		if ( $key ) {
			if ( isset( $options[ $key ] ) ) {
				return $options[ $key ];
			} else {
				return $default;
			}
		}
		return $options;
	}

	/**
	 * Output the HTML image tag for a product attachment.
	 *
	 * Retrieves the image properties using `product_attachment_props()` and outputs
	 * the image using `wp_get_attachment_image()`. Intended for variation galleries
	 * or product thumbnails.
	 *
	 * @param int         $image_id The attachment/image ID.
	 * @param \WC_Product $product  The WooCommerce product object.
	 * @param string      $size     The image size to output. Default 'woocommerce_thumbnail'.
	 *
	 * @return string The HTML.
	 */
	public static function get_main_attachment_html( $image_id, $product, $size = 'woocommerce_thumbnail' ) {
		$props = self::product_attachment_props( $image_id, $product );
		$html  = self::wc_get_gallery_image_html( $image_id, true );
		return apply_filters( 'rtsb/vg/get/main/attachment/html', $html, $props, $image_id, $product, $size );
	}

	/**
	 * Get HTML for a gallery image.
	 *
	 * Hooks: woocommerce_gallery_thumbnail_size, woocommerce_gallery_image_size and woocommerce_gallery_full_size accept name based image sizes, or an array of width/height values.
	 *
	 * @since 3.3.2
	 * @param int  $attachment_id Attachment ID.
	 * @param bool $main_image Is this the main image or a thumbnail?.
	 * @param int  $image_index The image index in the gallery.
	 * @return string
	 */
	public static function wc_get_gallery_image_html( $attachment_id, $main_image = false, $image_index = -1 ) {
		global $product;

		$flexslider        = (bool) apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) );
		$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
		$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', [ $gallery_thumbnail['width'], $gallery_thumbnail['height'] ] );
		$image_size        = apply_filters( 'woocommerce_gallery_image_size', $flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size );
		$full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
		$thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
		$thumbnail_srcset  = wp_get_attachment_image_srcset( $attachment_id, $thumbnail_size );
		$thumbnail_sizes   = wp_get_attachment_image_sizes( $attachment_id, $thumbnail_size );
		$full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
		$alt_text          = trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );
		$alt_text          = ( empty( $alt_text ) && ( $product instanceof WC_Product ) ) ? woocommerce_get_alt_from_product_title_and_position( $product->get_title(), $main_image, $image_index ) : $alt_text;

		if ( ! is_array( $full_src ) ) {
			$full_src = [ '', '', '' ];
		}

		/**
		 * Filters the attributes for the image markup.
		 *
		 * @since 3.3.2
		 *
		 * @param array $image_attributes Attributes for the image markup.
		 */
		$image_params = apply_filters(
			'woocommerce_gallery_image_html_attachment_image_params',
			[
				'title'                   => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
				'data-caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
				'data-src'                => esc_url( $full_src[0] ),
				'data-large_image'        => esc_url( $full_src[0] ),
				'data-large_image_width'  => esc_attr( $full_src[1] ),
				'data-large_image_height' => esc_attr( $full_src[2] ),
				'class'                   => esc_attr( $main_image ? 'wp-post-image' : '' ),
				'alt'                     => esc_attr( $alt_text ),
			],
			$attachment_id,
			$image_size,
			$main_image
		);

		if ( isset( $image_params['title'] ) ) {
			unset( $image_params['title'] );
		}

		$image = wp_get_attachment_image(
			$attachment_id,
			$image_size,
			false,
			$image_params
		);

		return $image;
	}

	/**
	 * Get the HTML for a variation gallery thumbnail image.
	 *
	 * Wraps the image in a container div and optionally adds a video-specific class
	 * if a video link is associated with the image. The final HTML is passed through
	 * a filter for customization.
	 *
	 * @param int         $image_id The attachment/image ID.
	 * @param \WC_Product $product  The WooCommerce product object.
	 * @param string      $size     The image size to output. Default 'thumbnail'.
	 *
	 * @return string Filtered HTML for the thumbnail image.
	 */
	public static function get_thumbnail_attachment_html( $image_id, $product, $size = 'thumbnail' ) {
		$props           = self::product_attachment_props( $image_id, $product );
		$add_video_class = ! empty( $props['rtsb_vg_video_link'] ) ? ' rtsb-vs-video' : '';
		ob_start();
		?>
		<div class="rtsb-vs-thumb-item <?php echo esc_attr( $add_video_class ); ?>">
			<?php Fns::print_html( wp_get_attachment_image( $image_id, $size ), true ); ?>
		</div>
		<?php
		$html = ob_get_clean();
		return apply_filters( 'rtsb/vg/get/thumbnail/attachment/html', $html, $props, $image_id, $product, $size );
	}
	/**
	 * @param int    $image_id image id.
	 * @param object $product WC_Product Object.
	 * @return array
	 */
	public static function product_attachment_props( $image_id, $product ) {
		$props             = wc_get_product_attachment_props( $image_id, $product );
		$props['image_id'] = $image_id;
		return apply_filters( 'rtsb/product/attachment/props', $props, $image_id, $product );
	}
	/**
	 * @param int $product_id product id.
	 *
	 * @return mixed|void
	 */
	public static function get_gallery_images_and_props( $product_id ) {
		$transient_name = self::get_transient_name( $product_id, 'default-images' );
		$images         = get_transient( $transient_name );
		if ( false !== $images ) {
			 return apply_filters( 'rtsb/vg/get/gallery/images', $images, $product_id );
		}
		$product           = wc_get_product( $product_id );
		$product_id        = $product->get_id();
		$attachment_ids    = $product->get_gallery_image_ids();
		$post_thumbnail_id = $product->get_image_id();
		$images            = [];
		$post_thumbnail_id = (int) apply_filters( 'rtsb/vg/post/thumbnail/id', $post_thumbnail_id, $attachment_ids, $product );
		$attachment_ids    = (array) apply_filters( 'rtsb/vg/attachment/ids', $attachment_ids, $post_thumbnail_id, $product );
		if ( ! empty( $post_thumbnail_id ) ) {
			array_unshift( $attachment_ids, $post_thumbnail_id );
		}
		if ( is_array( $attachment_ids ) && ! empty( $attachment_ids ) ) {
			foreach ( $attachment_ids as $i => $image_id ) {
				// Skip IDs whose attachment was deleted from the media library so
				// the gallery never renders an empty (bordered) slide.
				if ( $image_id && wp_attachment_is_image( $image_id ) ) {
					$images[ $i ] = self::product_attachment_props( $image_id, $product );
				}
			}
		}
		set_transient( $transient_name, $images, 12 * HOUR_IN_SECONDS );
		return apply_filters( 'rtsb/vg/get/gallery/images', $images, $product_id );
	}
	/**
	 * Build (and cache) the gallery image props for a single variation.
	 *
	 * Assembles the image-ID list for one variation only — the per-variation logic
	 * previously executed for every variation inside the
	 * `woocommerce_available_variation` filter — so galleries can be fetched
	 * on-demand over AJAX instead of being built in bulk on page load.
	 *
	 * The result is cached in a transient keyed by variation ID and is always a
	 * sequential array (built with `$images[]`) so it JSON-encodes to a JS array.
	 *
	 * @param int $product_id   Parent (variable) product ID.
	 * @param int $variation_id Variation ID whose gallery should be built.
	 *
	 * @return array Sequential array of image prop arrays.
	 */
	public static function get_variation_gallery( $product_id, $variation_id ) {
		$variation_id = absint( $variation_id );
		$product_id   = absint( $product_id );

		$transient_name = self::get_transient_name( $variation_id, 'variation-images' );
		$cached         = $transient_name ? get_transient( $transient_name ) : false;
		if ( ! empty( $cached ) && is_array( $cached ) ) {
			return apply_filters( 'rtsb/vg/get/variation/gallery', $cached, $product_id, $variation_id );
		}

		$variation = wc_get_product( $variation_id );
		if ( ! $variation ) {
			return [];
		}

		$parent_product = $product_id ? wc_get_product( $product_id ) : false;

		$gallery_images = self::resolve_variation_gallery_image_ids( $variation, $parent_product );

		$lead_image_id = absint( $variation->get_image_id() );
		if ( empty( $lead_image_id ) && $parent_product ) {
			// Variation has no featured image of its own — lead with the parent
			// product's featured image (WooCommerce-style image swap).
			$lead_image_id = absint( $parent_product->get_image_id() );
		}
		if ( ! empty( $lead_image_id ) ) {
			array_unshift( $gallery_images, $lead_image_id );
		}

		// Drop IDs whose attachment was deleted from the media library so the
		// variation gallery never renders an empty (bordered) thumbnail/main slide.
		$gallery_images = array_filter( array_unique( array_filter( array_map( 'absint', $gallery_images ) ) ), 'wp_attachment_is_image' );
		$gallery_images = array_values( $gallery_images );

		$images = [];
		foreach ( $gallery_images as $image_id ) {
			if ( $image_id ) {
				// Always append so the result stays a sequential (JS) array.
				$images[] = self::product_attachment_props( $image_id, $variation );
			}
		}

		if ( $transient_name ) {
			set_transient( $transient_name, $images, HOUR_IN_SECONDS * 12 );
		}

		return apply_filters( 'rtsb/vg/get/variation/gallery', $images, $product_id, $variation_id );
	}

	/**
	 * Resolve the default variation ID for a variable product.
	 *
	 * Returns the variation that WooCommerce's default attributes fully resolve to,
	 * so the matching gallery can be rendered server-side on initial page load.
	 *
	 * @param \WC_Product $product Variable product object.
	 *
	 * @return int Variation ID, or 0 when no single default variation resolves.
	 */
	public static function get_default_variation_id( $product ) {
		if ( ! ( $product instanceof WC_Product ) || ! $product->is_type( 'variable' ) ) {
			return 0;
		}

		$default_attributes = $product->get_default_attributes();
		if ( empty( $default_attributes ) ) {
			return 0;
		}

		$match_attributes = [];
		foreach ( $default_attributes as $key => $value ) {
			$match_attributes[ 'attribute_' . $key ] = $value;
		}

		$data_store   = \WC_Data_Store::load( 'product' );
		$variation_id = $data_store->find_matching_product_variation( $product, $match_attributes );

		return absint( $variation_id );
	}

	/**
	 * Resolve a variation ID from the URL attribute params.
	 *
	 * Reads the same `attribute_*` request params WooCommerce uses to pre-select
	 * the variation form (e.g. ?attribute_pa_color=green&attribute_pa_size=medium)
	 * so the matching variation gallery can be painted server-side on initial load.
	 * Only resolves when every variation attribute is present, otherwise returns 0.
	 *
	 * @param \WC_Product $product Variable product object.
	 *
	 * @return int Variation ID, or 0 when the params do not uniquely resolve one.
	 */
	public static function get_requested_variation_id( $product ) {
		if ( ! ( $product instanceof WC_Product ) || ! $product->is_type( 'variable' ) ) {
			return 0;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only gallery paint using the same attribute params WooCommerce itself reads to pre-select variations.
		$request = wp_unslash( $_REQUEST );

		$match_attributes = [];
		foreach ( $product->get_variation_attributes() as $attribute_name => $options ) {
			$key = 'attribute_' . sanitize_title( $attribute_name );
			if ( ! isset( $request[ $key ] ) ) {
				// A required attribute is missing, so a single variation cannot resolve.
				return 0;
			}
			$value = wc_clean( $request[ $key ] );
			if ( '' === $value ) {
				return 0;
			}
			$match_attributes[ $key ] = $value;
		}

		if ( empty( $match_attributes ) ) {
			return 0;
		}

		$data_store   = \WC_Data_Store::load( 'product' );
		$variation_id = $data_store->find_matching_product_variation( $product, $match_attributes );

		return absint( $variation_id );
	}

	/**
	 * Get the attachment IDs to render for a product's initial gallery paint.
	 *
	 * When a default variation resolves, returns that variation's gallery image IDs
	 * (and warms its transient as a side effect of get_variation_gallery()). Otherwise
	 * falls back to the parent product's featured + gallery images.
	 *
	 * @param \WC_Product $product Product object.
	 *
	 * @return array {
	 *     @type int[]       $image_ids Sequential attachment IDs to render.
	 *     @type \WC_Product $context   Product/variation object to use as render context.
	 * }
	 */
	public static function get_initial_gallery_render( $product ) {
		$context      = $product;
		$image_ids    = [];
		$variation_id = 0;

		if ( $product instanceof WC_Product ) {
			// Prefer the variation requested via URL attribute params (the same
			// params WooCommerce reads to pre-select the variation form) so the
			// correct gallery is painted server-side; fall back to the product's
			// default variation otherwise.
			$selected_variation_id = self::get_requested_variation_id( $product );
			if ( ! $selected_variation_id ) {
				$selected_variation_id = self::get_default_variation_id( $product );
			}
			if ( $selected_variation_id ) {
				$variation_props = self::get_variation_gallery( $product->get_id(), $selected_variation_id );
				foreach ( (array) $variation_props as $prop ) {
					if ( ! empty( $prop['image_id'] ) ) {
						$image_ids[] = absint( $prop['image_id'] );
					}
				}
				if ( ! empty( $image_ids ) ) {
					$variation = wc_get_product( $selected_variation_id );
					if ( $variation ) {
						$context = $variation;
					}
					// The server has painted this variation's gallery, so JS can
					// skip its redundant initial AJAX/re-render for it.
					$variation_id = $selected_variation_id;
				}
			}
		}

		if ( empty( $image_ids ) && ( $product instanceof WC_Product ) ) {
			$post_thumbnail_id = $product->get_image_id();
			$attachment_ids    = $product->get_gallery_image_ids();
			if ( $post_thumbnail_id ) {
				$image_ids[] = absint( $post_thumbnail_id );
			}
			if ( is_array( $attachment_ids ) && count( $attachment_ids ) ) {
				foreach ( $attachment_ids as $attachment_id ) {
					$image_ids[] = absint( $attachment_id );
				}
			}
		}

		// Drop IDs whose attachment was deleted from the media library so the
		// gallery never renders an empty (bordered) thumbnail/main slide.
		$image_ids = array_filter( array_unique( array_filter( $image_ids ) ), 'wp_attachment_is_image' );
		$image_ids = array_values( $image_ids );

		return [
			'image_ids'    => $image_ids,
			'context'      => $context,
			'variation_id' => $variation_id,
		];
	}

	/**
	 * Flush all gallery transients (default + every variation) for a product.
	 *
	 * Used when a product or one of its variations is saved so stale gallery
	 * props are not served from cache.
	 *
	 * @param int $product_id Parent product ID.
	 *
	 * @return void
	 */
	public static function flush_product_gallery_transients( $product_id ) {
		$product_id = absint( $product_id );
		if ( ! $product_id ) {
			return;
		}

		self::delete_transients( $product_id, 'default-images' );

		$product = wc_get_product( $product_id );
		if ( ! $product || ! $product->is_type( 'variable' ) ) {
			return;
		}

		foreach ( $product->get_children() as $variation_id ) {
			self::delete_transients( $variation_id, 'variation-images' );
		}
	}

	/**
	 * Flush variation gallery transients that reference a given attachment.
	 *
	 * Invoked when an attachment is deleted so variations whose galleries include
	 * the removed image rebuild their props on next request.
	 *
	 * @param int $attachment_id Deleted attachment ID.
	 *
	 * @return void
	 */
	public static function flush_attachment_gallery_transients( $attachment_id ) {
		global $wpdb;

		$attachment_id = absint( $attachment_id );
		if ( ! $attachment_id ) {
			return;
		}

		// Flush the owning product (featured/parent) galleries when determinable.
		$parent_id = wp_get_post_parent_id( $attachment_id );
		if ( $parent_id ) {
			self::flush_product_gallery_transients( $parent_id );
		}

		// Flush any variation whose gallery references this attachment. Both stores are
		// scanned: the legacy meta holds a serialised array, while WooCommerce's native
		// variation gallery holds a comma-separated string under `_product_image_gallery`.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key IN ( %s, %s )",
				self::LEGACY_GALLERY_META_KEY,
				'_product_image_gallery'
			)
		);

		if ( empty( $rows ) ) {
			return;
		}

		foreach ( $rows as $row ) {
			$image_ids = maybe_unserialize( $row->meta_value );
			if ( ! is_array( $image_ids ) ) {
				$image_ids = explode( ',', (string) $image_ids );
			}
			$image_ids = array_map( 'absint', $image_ids );
			if ( in_array( $attachment_id, $image_ids, true ) ) {
				self::delete_transients( $row->post_id, 'variation-images' );
			}
		}
	}

	/**
	 * Helper: Get all images transient name for specific variation/product
	 *
	 * @param int    $id unique id.
	 * @param string $type transient type.
	 *
	 * @return string
	 */
	public static function get_transient_name( $id, $type ) {
		if ( 'default-images' === $type ) {
			$id             = self::wpml_get_original_variation_id( $id );
			$transient_name = sprintf( 'rtsb_vg_default_images_%d', $id );
		} elseif ( 'variation-images' === $type ) {
			$id             = self::wpml_get_original_variation_id( $id );
			$transient_name = sprintf( 'rtsb_vg_variation_images_%d', $id );
		} elseif ( 'sizes' === $type ) {
			$transient_name = sprintf( 'rtsb_vg_variation_image_sizes_%d', $id );
		} elseif ( 'variation' === $type ) {
			$transient_name = sprintf( 'rtsb_vg_variation_%d', $id );
		} else {
			$transient_name = false;
		}
		return apply_filters( 'rtsb/vg/transient/name', $transient_name, $type, $id );
	}
	/**
	 * Helper: Delete all transient
	 *
	 * @param int    $product_id Product id.
	 * @param string $type Transient type.
	 */
	public static function delete_transients( $product_id = false, $type = '' ) {
		if ( $product_id ) {
			if ( $type ) {
				$default_transient_name = self::get_transient_name( $product_id, $type );
				delete_transient( $default_transient_name );
			} else {
				$default_transient_name = self::get_transient_name( $product_id, 'default-images' );
				delete_transient( $default_transient_name );
			}
		}
	}

	/**
	 * Helper: WPML - Get original variation ID
	 *
	 * If WPML is active and this is a translated variaition, get the original ID.
	 *
	 * @param int $id object id.
	 *
	 * @return int
	 */
	public static function wpml_get_original_variation_id( $id ) {
		$wpml_original_variation_id = get_post_meta( $id, '_wcml_duplicate_of_variation', true );
		if ( $wpml_original_variation_id ) {
			$id = $wpml_original_variation_id;
		}
		return $id;
	}
}
