<?php
/**
 * AdvancedHeading class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\LogoSliderAndGrid;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Dropcaps class.
 *
 * @package RadiusTheme\SB
 */
class LogoSliderAndGrid extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Logo Slider And Grid', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-logo-slider-and-grid';

		parent::__construct( $data, $args );

		$this->rtsb_category = 'rtsb-shopbuilder-general';
	}
	/**
	 * Whether the element returns dynamic content.
	 *
	 * @return bool
	 */
	protected function is_dynamic_content(): bool {
		return false;
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return array_merge(
			Controls::content( $this ),
			Controls::style( $this )
		);
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'Logo Slider And Grid','Logo Slider','Logo Grid' ] + parent::get_keywords();
	}

	/**
	 * Style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends(): array {
		return [
			'rtsb-general-addons',
			'elementor-icons-shared-0',
			'elementor-icons-fa-solid',
		];
	}

	/**
	 * Script dependencies.
	 *
	 * @return array
	 */
	public function get_script_depends(): array {
		return [ 'swiper' ];
	}

	/**
	 * Addon Render.
	 *
	 * @return void
	 */
	protected function render() {
		$settings         = $this->get_settings_for_display();
		$is_carousel      = ! empty( $settings['activate_slider_item'] );
		$container_class  = ! empty( $settings['activate_slider_item'] ) ? 'logo-slider' : 'logo-grid';
		$container_class .= ! empty( $settings['logo_gray_scale'] ) ? ' grayscale-logo' : '';
		$template         = 'slider-and-grid-layout';
		$template         = apply_filters( 'rtsb/general_widget/logo_slider_and_grid/template', $template, $settings );
		$data             = [
			'template'        => 'elementor/general/logo-slider-and-grid/' . $template,
			'id'              => $this->get_id(),
			'unique_name'     => $this->get_unique_name(),
			'layout'          => $settings['layout_style'],
			'settings'        => $settings,
			'is_carousel'     => $is_carousel,
			'is_grid'         => true,
			'container_class' => $container_class,
			'content_class'   => 'rtsb-logo-item',
		];

		// Render initialization.
		$this->theme_support();

		// Call the template rendering method.
		Fns::print_html( ( new Render() )->display_content( $data, $settings ), true );
		$this->edit_mode_script();
		$this->theme_support( 'render_reset' );
	}
}
