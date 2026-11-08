<?php
/**
 * ProductCategories class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Render\Render;
use RadiusTheme\SB\Elementor\Widgets\Controls;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * ProductCategories class.
 */
class ProductCategories extends ElementorWidgetBase {
	/**
	 * Control Fields
	 *
	 * @var array
	 */
	private $control_fields = [];

	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Product Categories', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-product-categories-general';

		parent::__construct( $data, $args );

		$this->pro_tab       = 'layout';
		$this->rtsb_category = 'rtsb-shopbuilder-general';
	}

	/**
	 * Style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends(): array {
		return [
			'elementor-icons-shared-0',
			'elementor-icons-fa-solid',
		];
	}

	/**
	 * Controls for layout tab
	 *
	 * @return object
	 */
	protected function layout_tab() {
		$sections = apply_filters(
			'rtsb/elements/elementor/cat_layout_tab',
			array_merge(
				Controls\LayoutFields::category_layout( $this ),
				// TODO:: Need to implement ( get query object ) in category query for single product page.
				Controls\LayoutFields::cat_query( $this ),
			)
		);

		$this->control_fields = array_merge( $this->control_fields, $sections );

		return $this;
	}

	/**
	 * Controls for settings tab
	 *
	 * @return object
	 */
	protected function settings_tab() {
		$sections = apply_filters(
			'rtsb/elements/elementor/cat_settings_tab',
			array_merge(
				Controls\SettingsFields::cat_content_visibility( $this ),
				Controls\SettingsFields::cat_image( $this ),
				Controls\SettingsFields::cat_title( $this ),
				Controls\SettingsFields::cat_excerpt( $this ),
				Controls\SettingsFields::cat_count_settings( $this ),
				Controls\SettingsFields::badges( $this ),
				Controls\SettingsFields::links( $this ),
			)
		);
		unset( $sections['show_badges'] );
		$this->control_fields = array_merge( $this->control_fields, $sections );

		return $this;
	}

	/**
	 * Controls for style tab
	 *
	 * @return object
	 */
	protected function style_tab() {
		$sections = apply_filters(
			'rtsb/elements/elementor/cat_style_tab',
			array_merge(
				Controls\StyleFields::color_scheme( $this ),
				Controls\StyleFields::layout_design( $this ),
				Controls\StyleFields::cat_title( $this ),
				Controls\StyleFields::cat_description( $this ),
				Controls\StyleFields::category_image( $this ),
				Controls\StyleFields::count( $this ),
				Controls\StyleFields::product_badges( $this )
			)
		);

		$this->control_fields = array_merge( $this->control_fields, $sections );

		return $this;
	}

	/**
	 * Widget Field
	 *
	 * @return void|array
	 */
	public function widget_fields() {
		$this->layout_tab()->settings_tab()->style_tab();

		if ( empty( $this->control_fields ) ) {
			return [];
		}

		return $this->control_fields;
	}



	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->theme_support();
		$template = 'elementor/general/category/';

		Fns::print_html( Render::instance()->category_view( $template, $settings ), true );
		$this->theme_support( 'render_reset' );
		$this->edit_mode_script();
	}
}
