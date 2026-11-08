<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Archive;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\ArchiveViewModeSettings;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}


/**
 * Product Description class
 */
class ArchiveProductMode extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'View Mode', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-archive-product-mode';
		parent::__construct( $data, $args );
	}
	/**
	 * Keywords
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'archive' ] + parent::get_keywords();
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return ArchiveViewModeSettings::widget_fields( $this );
	}

	/**
	 * Widget Field
	 *
	 * @param string $mode Mode.
	 *
	 * @return string
	 */
	public function view_mode_url( $mode ) {
		global $wp;
		$url = home_url(
			add_query_arg(
				array_merge( $_GET, [ 'displayview' => $mode ] ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$wp->request
			)
		);
		return $url;
	}

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		$controllers = $this->get_settings_for_display();
        $this->theme_support();

		$controllers['grid_icon'] = Fns::icons_manager( $controllers['mode_button_grid_icon'] );
		$controllers['list_icon'] = Fns::icons_manager( $controllers['mode_button_list_icon'] );

		$data = [
			'template'    => 'elementor/archive/view-mode',
			'grid'        => $this->view_mode_url( 'grid' ),
			'list'        => $this->view_mode_url( 'list' ),
			'controllers' => $controllers,
		];
		Fns::load_template( $data['template'], $data );
        $this->theme_support( 'render_reset' );
	}

}
