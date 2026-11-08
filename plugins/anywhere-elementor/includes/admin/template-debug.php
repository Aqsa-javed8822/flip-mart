<?php

namespace Aepro\Admin;

class TemplateDebug {

	public function __construct() {

		add_action( 'add_meta_boxes', [ $this, 'debug_box' ], 10 );
		
	}

	public function debug_box() {
		$template_debug = get_option( 'ae_pro_template_debug', '' ) === '1';
		$template_debug = apply_filters( 'aepro/template_debug', $template_debug );

		if ( ! $template_debug ) {
			return;
		}
		
		add_meta_box(
			'aep_template_debug',
			__( 'Dynific Template Debug', 'ae-pro' ),
			[ $this, 'render_config_box' ],
			'ae_global_templates',
			'normal',
			'high'
		);
	}

	public function render_config_box($post){
		$elementor_data = get_post_meta($post->ID,'_elementor_data',true);	
		?>
		<div class="ae-debug-wrapper">
			<div class="ae-debug-content-wrapper">
				<div class="ae-debug-content" aria-hidden="true">
					<div class="f-row">
						<div class="ae-control">
							<textarea 
							type="text" 
							readonly 
							name="ae_debug_area" 
							id="ae_debug_area" 
							rows="10" 
							style="width:100%"><?php print_r( json_decode($elementor_data) );?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
New TemplateDebug();
