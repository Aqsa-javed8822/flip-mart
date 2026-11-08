<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Single;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\TextStyleSettings;
use RadiusTheme\SB\Helpers\Fns;

/**
 * Product Description class
 */
class ShortDescription extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Short Description', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-short-description';
		parent::__construct( $data, $args );
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return TextStyleSettings::widget_fields( $this );
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'Excerpt' ] + parent::get_keywords();
	}
	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		global $post;
		$_post       = $post;
		$controllers = $this->get_settings_for_display();
        $this->theme_support();
		if ( $this->is_builder_mode() ) {
			// Overriding Global.
			$post = get_post( Fns::get_prepared_product_id() ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
		$data = [
			'template'    => 'elementor/single-product/short-description',
			'controllers' => $controllers,
		];
		Fns::load_template( $data['template'], $data );
        $this->theme_support( 'render_reset' );
		$post = $_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}

}
