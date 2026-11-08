<?php
/**
 * AdvancedHeading class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\PricingTable;

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
class PricingTable extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Pricing Table', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-pricing-table';

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
		return [ 'Pricing Table','Pricing Box','Pricing' ] + parent::get_keywords();
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
		switch ( $settings['layout_style'] ) {
			case 'rtsb-pricing-table-layout3':
				$template = 'layout3';
				break;
			case 'rtsb-pricing-table-layout2':
				$template = 'layout2';
				break;
			default:
				$template = 'layout1';
				break;
		}
		$template = apply_filters( 'rtsb/general_widget/pricing_table/template', $template, $settings );
		$data     = [
			'template'        => 'elementor/general/pricing-table/' . $template,
			'id'              => $this->get_id(),
			'unique_name'     => $this->get_unique_name(),
			'layout'          => $settings['layout_style'],
			'settings'        => $settings,
			'is_carousel'     => false,
			'container_class' => '',
			'content_class'   => $settings['button_hover_effects'] ?? '',
		];

		// Render initialization.
		$this->theme_support();

		// Call the template rendering method.
		Fns::print_html( ( new Render() )->display_content( $data, $settings ), true );

		$this->theme_support( 'render_reset' );
	}
}
