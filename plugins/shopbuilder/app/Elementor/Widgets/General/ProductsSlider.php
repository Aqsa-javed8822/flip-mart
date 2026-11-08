<?php
/**
 * ProductsSlider class.
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
 * ProductsSlider class.
 */
class ProductsSlider extends ElementorWidgetBase {
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
		$this->rtsb_name = esc_html__( 'Products - Slider Layouts', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-products-slider';

		parent::__construct( $data, $args );

		$this->pro_tab       = 'layout';
		$this->rtsb_category = 'rtsb-shopbuilder-general';
	}

	/**
	 * Script dependencies.
	 *
	 * @return array
	 */
	public function get_script_depends(): array {
		if ( ! $this->is_edit_mode() ) {
			return [];
		}

		return [ 'swiper' ];
	}

	/**
	 * Style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends(): array {
		if ( ! $this->is_edit_mode() ) {
			return [];
		}

		return [
			'elementor-icons-shared-0',
			'elementor-icons-fa-solid',
		];
	}

	/**
	 * Controls for layout tab
	 *
	 * @return $this
	 */
	protected function layout_tab() {
		$sections = apply_filters(
			'rtsb/elements/elementor/slider_layout_tab',
			array_merge(
				Controls\LayoutFields::slider_layout( $this ),
				Controls\LayoutFields::query( $this ),
				Controls\LayoutFields::filter( $this )
			)
		);

		$this->control_fields = array_merge( $this->control_fields, $sections );

		return $this;
	}

	/**
	 * Controls for settings tab
	 *
	 * @return $this
	 */
	protected function settings_tab() {
		$sections = apply_filters(
			'rtsb/elements/elementor/slider_settings_tab',
			array_merge(
				Controls\SettingsFields::content_visibility( $this ),
				Controls\SettingsFields::slider_settings( $this ),
				Controls\SettingsFields::content_ordering( $this ),
				Controls\SettingsFields::image( $this ),
				Controls\SettingsFields::action_buttons( $this ),
				Controls\SettingsFields::product_title( $this ),
				Controls\SettingsFields::product_excerpt( $this ),
				Controls\SettingsFields::badges( $this ),
				$this->variation_swatch_conditionaly(),
				Controls\SettingsFields::links( $this )
			),
			$this
		);

		$this->control_fields = array_merge( $this->control_fields, $sections );

		return $this;
	}

	/**
	 * @return array
	 */
	public function variation_swatch_conditionaly() {
		if ( ! function_exists( 'rtwpvsp' ) && ! rtsb()->has_pro() ) {
			return [];
		}
		$swatches_controls = Controls\SettingsFields::variation_swatch( $this );
		if ( ! empty( $swatches_controls['swatch_position'] ) ) {
			$swatches_controls['swatch_position']['condition'] = [
				'layout' => [ 'slider-layout1', 'slider-layout2' ],
			];
		}
		return $swatches_controls;
	}

	/**
	 * Controls for style tab
	 *
	 * @return $this
	 */
	protected function style_tab() {
		$sections = apply_filters(
			'rtsb/elementor/slider_style_tab',
			array_merge(
				Controls\StyleFields::color_scheme( $this ),
				Controls\StyleFields::layout_design( $this ),
				Controls\StyleFields::product_image( $this ),
				Controls\StyleFields::product_title( $this ),
				Controls\StyleFields::short_description( $this ),
				Controls\StyleFields::product_price( $this ),
				Controls\StyleFields::product_categories( $this ),
				Controls\StyleFields::product_brands( $this ),
				Controls\StyleFields::product_rating( $this ),
				Controls\StyleFields::product_add_to_cart( $this ),
				Controls\StyleFields::product_wishlist( $this ),
				Controls\StyleFields::product_quick_view( $this ),
				Controls\StyleFields::product_compare( $this ),
				Controls\StyleFields::action_buttons( $this ),
				Controls\StyleFields::product_badges( $this ),
				Controls\StyleFields::slider_buttons( $this ),
				Controls\StyleFields::hover_icon_button( $this ),
				Controls\StyleFields::not_found_notice( $this ),
			),
			$this
		);

		$this->control_fields = array_merge( $this->control_fields, $sections );

		return $this;
	}

	/**
	 * Widget Field
	 *
	 * @return array
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
		$template = 'elementor/general/slider/';

		// Render init.
		$this->render_start();

		// Call template.
		Fns::print_html( Render::instance()->product_view( $template, $settings ), true );

		// Ending the render.
		$this->render_end();
	}
}
