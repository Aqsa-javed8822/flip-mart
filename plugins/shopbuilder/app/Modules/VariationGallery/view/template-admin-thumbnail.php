<?php
/**
 * Admin Thumbnail js template
 *
 * This template can be overridden by copying it to
 * yourtheme/woo-product-variation-gallery-pro/template-admin-thumbnail.php
 */

defined( 'ABSPATH' ) || exit;
?>
<script type="text/html" id="tmpl-rtsb-vg-image">
	<# hasVideo = ( typeof data.rtsb_vg_video_link !== 'undefined' && data.rtsb_vg_video_link != ''  ) ? 'video' : '';  #>
	<li class="image {{hasVideo}}">
		<input type="hidden" name="rtsb_vg[{{data.product_variation_id}}][]" value="{{data.id}}">
		<img src="{{data.url}}">
		<div class="rtsb-vg-action-button">
			<span data-tip="<?php esc_attr_e( 'Add Video', 'shopbuilder' ); ?>" class="rtsb-vg-media-video-popup woocommerce-help-tip dashicons dashicons-video-alt3"></span>
			<span data-tip="<?php esc_attr_e( 'Edit Image', 'shopbuilder' ); ?>" class="rtsb-vg-gallery-edit woocommerce-help-tip dashicons dashicons-edit"></span>
			<a href="#" class="delete rtsb-vg-remove-image woocommerce-help-tip" data-tip="<?php esc_attr_e( 'Remove', 'shopbuilder' ); ?>"><span class="dashicons dashicons-no"></span></a>
		</div>
	</li>
</script>