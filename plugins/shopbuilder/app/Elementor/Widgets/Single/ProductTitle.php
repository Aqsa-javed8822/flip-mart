<?php
/**
 * Main ProductTitle class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Single;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\TitleSettings;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Title class
 */
class ProductTitle extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Product Title', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-product-title';
		parent::__construct( $data, $args );
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		$fields = self::general_settings() + TitleSettings::title_settings( $this );
		unset(
			$fields['title_style_start']['condition'],
		);
		return $fields;
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function general_settings() {
		$fields = TitleSettings::general_settings();
		$fields['general_style']['label'] = esc_html__( 'Options', 'shopbuilder' );
		unset( $fields['show_title'] );
		$new_fields = [
			'product_title_html_tag' => [
				'label'     => esc_html__( 'Title HTML Tag', 'shopbuilder' ),
				'type'      => 'select',
				'options'   => ControlHelper::heading_tags(),
				'default'   => 'h2',
				'separator' => 'default',
			],
		];
		$fields     = Fns::insert_controls( 'general_style', $fields, $new_fields, true );
		return $fields;
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'Heading', 'Name' ] + parent::get_keywords();
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
			'template'    => 'elementor/single-product/title',
			'controllers' => $controllers,
		];
		Fns::load_template( $data['template'], $data );
        $this->theme_support( 'render_reset' );
		$post = $_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}

}
