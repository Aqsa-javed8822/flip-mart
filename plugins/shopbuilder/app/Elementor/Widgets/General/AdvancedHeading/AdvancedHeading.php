<?php
/**
 * AdvancedHeading class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\AdvancedHeading;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * AdvancedHeading class.
 *
 * @package RadiusTheme\SB
 */
class AdvancedHeading extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Advanced Heading', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-advanced-heading';

		parent::__construct( $data, $args );

		$this->rtsb_category = 'rtsb-shopbuilder-general';
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return array_merge(
			Controls::content( $this ),
			Controls::style( $this ),
		);
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'Heading','Advanced Heading','Title' ] + parent::get_keywords();
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
	 * Style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends(): array {
		if ( ! $this->is_edit_mode() ) {
			return [
				'rtsb-general-addons',
			];
		}

		return [
			'rtsb-general-addons',
			'elementor-icons-shared-0',
			'elementor-icons-fa-solid',
		];
	}

	/**
	 * Addon Render.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$layout_class = $settings['layout_style'] ?? 'rtsb-advanced-heading-layout1';
		$template     = 'advanced-heading';
		$template     = apply_filters( 'rtsb/general_widget/advanced_heading/template', $template, $settings );
		$data         = [
			'template'        => 'elementor/general/advanced-heading/' . $template,
			'id'              => $this->get_id(),
			'unique_name'     => $this->get_unique_name(),
			'layout'          => $layout_class,
			'settings'        => $settings,
			'is_carousel'     => false,
			'container_class' => '',
			'content_class'   => '',
		];

		// Render initialization.
		$this->theme_support();

		// Call the template rendering method.
		Fns::print_html( ( new Render() )->display_content( $data, $settings ), true );

		// Ending the render.
		$this->theme_support( 'render_reset' );
	}
}
