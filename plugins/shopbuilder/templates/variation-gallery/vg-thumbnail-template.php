<?php defined( 'ABSPATH' ) || die( 'Keep Silent' ); ?>
<!-- Template: Main Slider Wrapper -->
<script type="text/template" id="tmpl-vg-thumbnail-template">
	<# if (data.length > 1) {  #>
		<#  _.each( data, function(img, index) { #>
		<# hasVideo = ( typeof img.rtsb_vg_video_link !== 'undefined' && img.rtsb_vg_video_link != ''  ) ? 'rtsb-vs-video' : '';  #>
		<div class="swiper-slide rtsb-thumb rtsb-vs-thumb-item-{{img.image_id}} " data-index="{{index}}">
			<div class="rtsb-vs-thumb-item {{hasVideo}}">
				<img
					src="{{img.src}}"
					width="{{img.src_w}}"
					height="{{img.src_h}}"
					alt="{{img.alt}}"
					decoding="async"
					<# if( img.srcset ) { #>
					srcset="{{img.srcset}}"
					<# } #>
					sizes="{{img.sizes}}"
				>
			</div>
		</div>
		<#  }); #>
	<# } #>
</script>
