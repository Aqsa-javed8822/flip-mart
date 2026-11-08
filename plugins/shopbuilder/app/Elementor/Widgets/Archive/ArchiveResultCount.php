<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Archive;

use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\ArchiveCountSettings;
use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}


/**
 * Product Description class
 */
class ArchiveResultCount extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Result Count', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-archive-result-count';
		parent::__construct( $data, $args );
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return ArchiveCountSettings::widget_fields( $this );
	}

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		$controllers = $this->get_settings_for_display();

		$this->theme_support();

		$data = [
			'template'    => 'elementor/archive/result-count',
			'controllers' => $controllers,
		];

		if ( BuilderFns::is_builder_preview() ) { ?>
			<div class="rtsb-archive-result-count">
				<p class="woocommerce-result-count"><?php esc_html_e( 'Showing all results', 'shopbuilder' ); ?></p>
			</div>
			<?php
		} else {
			Fns::load_template( $data['template'], $data );
		}

		$this->theme_support( 'render_reset' );
	}
}
