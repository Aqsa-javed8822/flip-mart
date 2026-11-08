<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Archive;

use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\ArchiveTitleSettings;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ArchiveTitle extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Archive Title', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-archive-title';
		parent::__construct( $data, $args );
	}
	/**
	 * Keywords
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'title', 'archive', 'archive title', 'page title' ] + parent::get_keywords();
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return ArchiveTitleSettings::widget_fields( $this );
	}

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		$controllers = $this->get_settings_for_display();
        $this->theme_support();
		$data        = [
			'template'    => 'elementor/archive/title',
			'controllers' => $controllers,
		];
		Fns::load_template( $data['template'], $data );
        $this->theme_support( 'render_reset' );
	}

}
