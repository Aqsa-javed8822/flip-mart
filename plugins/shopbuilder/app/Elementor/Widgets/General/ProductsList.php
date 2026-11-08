<?php
/**
 * ProductsList class.
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
 * ProductsList class.
 */
class ProductsList extends ElementorWidgetBase {
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
		$this->rtsb_name = esc_html__( 'Products - List Layouts', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-products-list';

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
	 * @return object
	 */
	protected function layout_tab() {
		$sections = apply_filters(
			'rtsb/elements/elementor/list_layout_tab',
			array_merge(
				Controls\LayoutFields::list_layout( $this ),
				Controls\LayoutFields::query( $this ),
				Controls\LayoutFields::filter( $this ),
				Controls\LayoutFields::pagination( $this ),
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
			'rtsb/elements/elementor/list_settings_tab',
			array_merge(
				Controls\SettingsFields::content_visibility( $this ),
				Controls\SettingsFields::content_ordering( $this ),
				Controls\SettingsFields::image( $this ),
				Controls\SettingsFields::action_buttons( $this ),
				Controls\SettingsFields::product_title( $this ),
				Controls\SettingsFields::product_excerpt( $this ),
				Controls\SettingsFields::badges( $this ),
				Controls\SettingsFields::variation_swatch( $this ),
				Controls\SettingsFields::links( $this )
			),
			$this
		);

		unset( $sections['swatch_position'] );

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
			'rtsb/elementor/list_style_tab',
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
				Controls\StyleFields::pagination( $this ),
				Controls\StyleFields::hover_icon_button( $this ),
				Controls\StyleFields::not_found_notice( $this ),
			),
			$this
		);

		$sections['product_title_alignment']['condition'] = [ 'layout' => [ 'list-layout1', 'list-layout3' ] ];
		$sections['product_price_alignment']['condition'] = [ 'layout' => [ 'list-layout1', 'list-layout3' ] ];

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
	 * Widget Field
	 *
	 * @param string|array $thumbnail_size Current thumbnail size.
	 *
	 * @return array|string
	 */
	public function thumbnail_size( $thumbnail_size ) {
		$settings = $this->get_settings_for_display();
		if ( 'rtsb_custom' === $settings['image'] ) {
			// Custom image size is not supported. It may be implemented later.
			return $thumbnail_size;
		}
		return $settings['image'] ?? $thumbnail_size;
	}
	/**
	 * Init render hooks & functions.
	 *
	 * @return void
	 */
	protected function render_start() {
		parent::render_start();
		add_filter( 'woocommerce_thumbnail_size', [ $this, 'thumbnail_size' ], 15 );
	}
	/**
	 * Init render hooks & functions.
	 *
	 * @return void
	 */
	protected function render_end() {
		parent::render_end();
		remove_filter( 'woocommerce_thumbnail_size', [ $this, 'thumbnail_size' ], 15 );
	}
	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$template = 'elementor/general/list/';

		// Render init.
		$this->render_start();

		// Call template.
		Fns::print_html( Render::instance()->product_view( $template, $settings ), true );

		// Ending the render.
		$this->render_end();
	}
}
