<?php defined( 'ABSPATH' ) || die( 'Keep Silent' ); ?>
<!-- Template: Main Slider Wrapper -->
<script type="text/template" id="tmpl-vg-slider-template">
	<# if( data.length ) { #>
		<#  _.each( data, function(img) {  #>
		<div class="swiper-slide rtsb-vs-main-image-{{img.image_id}}">
			<img
					class="wp-post-image"
					width="{{img.src_w}}"
					height="{{img.src_h}}"
					src="{{img.src}}"
					alt="{{img.alt}}"
					data-caption="{{img.caption}}"
					data-src="{{img.full_src}}"
					data-large_image="{{img.full_src}}"
					data-large_image_width="{{img.full_src_w}}"
					data-large_image_height="{{img.full_src_h}}"
					decoding="async"
					<# if( img.srcset ) { #>
					srcset="{{img.srcset}}"
					<# } #>
					sizes="{{data.sizes}}"
			>
		</div>
		<#  });  #>
	<# } #>
</script>
