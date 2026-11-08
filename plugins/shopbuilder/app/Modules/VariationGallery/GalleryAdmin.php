<?php
/**
 * Main FilterHooks class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\VariationGallery;

use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Helpers\Cache;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit();

/**
 * Main FilterHooks class.
 */
class GalleryAdmin {

	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Module Class Constructor.
	 */
	private function __construct() {
		add_action( 'admin_footer', [ $this, 'admin_template_js' ] );
		add_action( 'woocommerce_product_after_variable_attributes', [ $this, 'gallery_admin_html' ], 10, 3 );
		add_action( 'woocommerce_save_product_variation', [ $this, 'save_variation_gallery' ] );
		add_action( 'add_meta_boxes', [ $this, 'add_metabox' ] );
		add_action( 'save_post', [ $this, 'save_metabox' ] );

		// Late enough that WooCommerce's variation gallery package has registered its
		// own field, so the duplicate control can be taken back off.
		add_action( 'admin_init', [ $this, 'remove_native_variation_gallery_field' ], 20 );

		add_filter( 'woocommerce_product_export_meta_value', [ $this, 'product_export_meta_value' ], 15, 3 );

		// Invalidate cached variation galleries when product data or images change.
		add_action( 'woocommerce_update_product', [ $this, 'flush_product_gallery_cache' ] );
		add_action( 'woocommerce_save_product_variation', [ $this, 'flush_variation_parent_cache' ] );
		add_action( 'delete_attachment', [ $this, 'flush_attachment_gallery_cache' ] );
	}

	/**
	 * Flush all cached gallery transients for a saved product.
	 *
	 * @param int $product_id Product ID.
	 *
	 * @return void
	 */
	public function flush_product_gallery_cache( $product_id ) {
		GalleryFns::flush_product_gallery_transients( $product_id );
	}

	/**
	 * Flush the parent product default-gallery transient when a variation is saved.
	 *
	 * The variation's own transient is already cleared in save_variation_gallery();
	 * this also clears the parent product default-images cache.
	 *
	 * @param int $variation_id Variation ID.
	 *
	 * @return void
	 */
	public function flush_variation_parent_cache( $variation_id ) {
		$parent_id = wp_get_post_parent_id( $variation_id );
		if ( $parent_id ) {
			GalleryFns::delete_transients( $parent_id, 'default-images' );
		}
	}

	/**
	 * Flush cached variation galleries referencing a deleted attachment.
	 *
	 * @param int $attachment_id Attachment ID being deleted.
	 *
	 * @return void
	 */
	public function flush_attachment_gallery_cache( $attachment_id ) {
		GalleryFns::flush_attachment_gallery_transients( $attachment_id );
	}
	/**
	 * Adds a custom meta box to the WooCommerce product edit page.
	 */
	public function add_metabox() {
		add_meta_box(
			'custom_checkbox_metabox', // Metabox ID.
			__( 'Variation gallery', 'shopbuilder' ), // Title.
			[ $this, 'metabox_callback' ], // Callback function.
			'product', // Post type (WooCommerce product).
			'side', // Position.
			'high' // Priority.
		);
	}
	/**
	 * Callback function to display the checkbox in the meta box.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function metabox_callback( $post ) {
		$value = get_post_meta( $post->ID, '_rtsb_vg_disable_valiation_gallery', true );
		?>
		<p>
			<label for="custom_checkbox">
				<input type="checkbox" id="rtsb_vg_disable_valiation_gallery" name="rtsb_vg_disable_valiation_gallery" value="yes" <?php checked( $value, 'yes' ); ?> />
				<?php esc_html_e( 'Disable Variation Gallery', 'shopbuilder' ); ?>
			</label><hr/>
			<span><?php esc_html_e( 'Disable variation gallery for this product', 'shopbuilder' ); ?> </span>
		</p>
		<?php
	}

	/**
	 * Saves the checkbox value when the product is updated.
	 *
	 * @param int $post_id The ID of the product being saved.
	 */
	public function save_metabox( $post_id ) {
		// Verify nonce for security.
		if ( empty( $_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			return;
		}
		// Prevent autosave from overwriting.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Check if the user has permission to edit the product.
		if ( ! current_user_can( 'edit_product', $post_id ) ) { // phpcs:ignore WordPress.WP.Capabilities.Unknown
			return;
		}
		// Save the checkbox value as 'yes' or 'no'.
		$value = isset( $_POST['rtsb_vg_disable_valiation_gallery'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_rtsb_vg_disable_valiation_gallery', $value );
		GalleryFns::delete_transients( $post_id, 'default-images' );
	}
	/**
	 * Saves variation gallery image IDs from the variation admin panel.
	 *
	 * @param int $variation_id The ID of the product variation.
	 *
	 * @return void
	 */
	public function save_variation_gallery( $variation_id ) {
		check_ajax_referer( 'save-variations', 'security' );
		// Check permissions again and make sure we have what we need.
		if ( ! current_user_can( 'edit_products' ) || empty( $_POST ) || empty( $_POST['product_id'] ) ) { // phpcs:ignore WordPress.WP.Capabilities.Unknown
			wp_die( -1 );
		}

		$rtsb_vg_ids = [];

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified by check_ajax_referer() above.
		if ( isset( $_POST['rtsb_vg'][ $variation_id ] ) && is_array( $_POST['rtsb_vg'][ $variation_id ] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified by check_ajax_referer() above.
			$rtsb_vg_ids = array_map( 'absint', wp_unslash( $_POST['rtsb_vg'][ $variation_id ] ) );
			$rtsb_vg_ids = array_values( array_unique( array_filter( $rtsb_vg_ids ) ) );
		}

		if ( GalleryFns::use_native_gallery() ) {
			$variation = wc_get_product( $variation_id );

			if ( $variation && $variation->is_type( 'variation' ) ) {
				$variation->set_gallery_image_ids( $rtsb_vg_ids );
				$variation->save();

				// Stamped only once the native gallery has actually been written: the
				// sentinel permanently suppresses the legacy fallback, so setting it
				// after a failed load would strand that variation's images unreachable.
				GalleryFns::mark_native_gallery_owned( $variation_id );
			}
		} elseif ( $rtsb_vg_ids ) {
			update_post_meta( $variation_id, GalleryFns::LEGACY_GALLERY_META_KEY, $rtsb_vg_ids );
		} else {
			delete_post_meta( $variation_id, GalleryFns::LEGACY_GALLERY_META_KEY );
		}

		GalleryFns::delete_transients( $variation_id, 'variation-images' );
	}

	/**
	 * Keep the stale legacy gallery meta out of CSV exports.
	 *
	 * Once a variation is native-owned the legacy meta is a snapshot, not the truth:
	 * the migration copies rather than moves it, and later saves only touch the native
	 * store. Exporting it would put outdated data in the CSV, and re-importing that
	 * file could write it back over a gallery the merchant has since changed.
	 *
	 * WooCommerce exports the native gallery itself through the standard `Images`
	 * column, so nothing is lost by omitting this key.
	 *
	 * @param mixed       $meta_value Meta value being exported.
	 * @param object      $meta       Meta object.
	 * @param \WC_Product $product    Product or variation being exported.
	 *
	 * @return mixed
	 */
	public function product_export_meta_value( $meta_value, $meta, $product ) {
		if ( ! GalleryFns::use_native_gallery() ) {
			return $meta_value;
		}

		if ( ! isset( $meta->key ) || GalleryFns::LEGACY_GALLERY_META_KEY !== $meta->key ) {
			return $meta_value;
		}

		if ( $product instanceof \WC_Product && GalleryFns::is_native_gallery_owned( $product->get_id() ) ) {
			return '';
		}

		return $meta_value;
	}

	/**
	 * Hide WooCommerce's own variation gallery field in the product editor.
	 *
	 * This module keeps its existing metabox as the single authoring UI and writes
	 * straight into the native store, so rendering both fields would give merchants two
	 * controls for one value. WooCommerce's field markup is also what its admin CSS
	 * keys off (`:has(.wc-variation-gallery-field)`), so removing the render leaves the
	 * stock variation image slot untouched and the stylesheet inert.
	 *
	 * Nothing is suppressed when this module is inactive — that code path never runs,
	 * so WooCommerce keeps full ownership of its own UI.
	 *
	 * @return void
	 */
	public function remove_native_variation_gallery_field() {
		$class = 'Automattic\WooCommerce\Internal\VariationGallery\ClassicVariationGalleryAdmin';
		$hook  = 'woocommerce_variation_after_upload_image';

		if ( ! GalleryFns::use_native_gallery() || ! class_exists( $class ) ) {
			return;
		}

		if ( ! apply_filters( 'rtsb/vg/remove/native/variation/gallery/field', true ) ) {
			return;
		}

		if ( empty( $GLOBALS['wp_filter'][ $hook ] ) ) {
			return;
		}

		// Matched on the registered object rather than resolved from WooCommerce's
		// container, so removal does not depend on the container handing back the very
		// same instance it registered the callback with.
		foreach ( $GLOBALS['wp_filter'][ $hook ]->callbacks as $priority => $callbacks ) {
			foreach ( $callbacks as $callback ) {
				if ( is_array( $callback['function'] ) && isset( $callback['function'][0] ) && $callback['function'][0] instanceof $class ) {
					remove_action( $hook, $callback['function'], $priority );
				}
			}
		}
	}
	/**
	 * @return void
	 */
	public function admin_template_js() {
		require_once RTSB_PATH . '/app/Modules/VariationGallery/view/template-admin-thumbnail.php';
	}

	/**
	 * Outputs the variation gallery image section in the product variation admin panel.
	 *
	 * Enqueues the necessary admin script and renders the gallery UI for a specific
	 * variation, allowing admin users to add, preview, and remove multiple images
	 * associated with a WooCommerce product variation.
	 *
	 * @param int     $loop            The index of the current variation loop.
	 * @param array   $variation_data  Array of variation data.
	 * @param WP_Post $variation     The variation object (as a WP_Post).
	 *
	 * @return void
	 */
	public function gallery_admin_html( $loop, $variation_data, $variation ) {
		$variation_id   = absint( $variation->ID );
		$gallery_images = null;

		// With the native gallery available it is authoritative, so the metabox shows it
		// rather than the legacy meta it mirrors — and never reads that meta, which on a
		// migrated store may no longer exist. The legacy value is only consulted while
		// this variation is still waiting on the migration; once the sentinel is set, an
		// empty native gallery is a deliberate choice and is shown as empty.
		if ( GalleryFns::use_native_gallery() ) {
			$variation_object = wc_get_product( $variation_id );

			if ( $variation_object && $variation_object->is_type( 'variation' ) ) {
				$native_images = GalleryFns::get_native_variation_gallery_ids( $variation_object );

				if ( $native_images || GalleryFns::is_native_gallery_owned( $variation_id ) ) {
					$gallery_images = $native_images;
				}
			}
		}

		if ( null === $gallery_images ) {
			$gallery_images = GalleryFns::get_legacy_variation_gallery_ids( $variation_id );
		}

		/*
		 * WooCommerce renders the variation's main image at thumbnail size (150px) in
		 * `html-variation-admin.php`, which is soft in the wider slot this module uses.
		 * Its own variation gallery hero uses `woocommerce_single`, so the matching URL
		 * is handed to the admin script here — that markup belongs to core and exposes
		 * no filter to size it directly.
		 */
		$variation_image_id = absint( get_post_thumbnail_id( $variation_id ) );
		$hero_src           = $variation_image_id ? wp_get_attachment_image_url( $variation_image_id, 'woocommerce_single' ) : '';
		?>
		<div class="form-row form-row-full rtsb-vg-gallery-wrapper" data-hero-src="<?php echo esc_url( $hero_src ); ?>">
			<h4><?php esc_html_e( 'Variation Image Gallery', 'shopbuilder' ); ?></h4>
			<div class="rtsb-vg-image-container">
				<ul class="rtsb-vg-images">
					<?php
					if ( is_array( $gallery_images ) && ! empty( $gallery_images ) ) {
						$gallery_images = array_values( array_unique( $gallery_images ) );
						foreach ( $gallery_images as $image_id ) :
							$image = wp_get_attachment_image_src( $image_id );
							/**
							 * Functions::gallery_has_video( $image_id );.
							 */
							if ( empty( $image[0] ) ) {
								continue;
							}
							$add_video_class = '';
							if ( rtsb()->has_pro() ) {
								$video           = trim( get_post_meta( $image_id, 'rtsb_vg_video_link', true ) ?? '' );
								$add_video_class = ! empty( $video ) ? ' video' : '';
							}
							?>
							<li class="image<?php echo esc_html( $add_video_class ); ?>">
								<input type="hidden" name="rtsb_vg[<?php echo absint( $variation_id ); ?>][]" value="<?php echo absint( $image_id ); ?>">
								<img src="<?php echo esc_url( $image[0] ); ?>">
								<div class="rtsb-vg-action-button">
									<span data-tip="<?php esc_attr_e( 'Add Video', 'shopbuilder' ); ?>" class="rtsb-vg-media-video-popup woocommerce-help-tip dashicons dashicons-video-alt3"></span>
									<span data-tip="<?php esc_attr_e( 'Edit Image', 'shopbuilder' ); ?>" class="rtsb-vg-gallery-edit woocommerce-help-tip dashicons dashicons-edit"></span>
									<a href="#" class="delete rtsb-vg-remove-image woocommerce-help-tip" data-tip="<?php esc_attr_e( 'Remove', 'shopbuilder' ); ?>"><span class="dashicons dashicons-no"></span></a>
								</div>
							</li>
							<?php
						endforeach;
					}
					?>
				</ul>
			</div>
			<p class="rtsb-vg-add-image-wrapper hide-if-no-js">
				<a href="#" data-product_variation_loop="<?php echo absint( $loop ); ?>"
				   data-product_variation_id="<?php echo esc_attr( $variation_id ); ?>"
				   class="button rtsb-vg-add-image">
					<?php
					esc_html_e( 'Add Gallery Images', 'shopbuilder' );
					?>
				</a>
			</p>
		</div>
		<?php
	}
}