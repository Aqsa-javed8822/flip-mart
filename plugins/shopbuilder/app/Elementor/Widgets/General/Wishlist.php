<?php
/**
 * Wishlist class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\WishlistSettings;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * ProductsList class.
 */
class Wishlist extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Wishlist Table', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-wishlist';

		parent::__construct( $data, $args );

		$this->rtsb_category = 'rtsb-shopbuilder-general';
	}


	/**
	 * Widget Field
	 *
	 * @return void|array
	 */
	public function widget_fields() {
		return WishlistSettings::settings_field( $this );
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
			'template'    => 'elementor/general/wishlist',
			'controllers' => $controllers,
		];

		Fns::load_template( $data['template'], $data );

		$this->theme_support( 'render_reset' );
	}

}
