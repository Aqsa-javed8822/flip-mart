<?php
/**
 * ProductsGrid class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Abstracts;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Render\Render;
use RadiusTheme\SB\Elementor\Widgets\Controls;
use RadiusTheme\SB\Controllers\Hooks\FilterHooks;
use RadiusTheme\SB\Traits\ActionBtnTraits;

// Do not allow directly accessing this file.

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * ProductsGrid class.
 */
abstract class AdditionalQueryCustomLayout extends ElementorWidgetBase {
	/**
	 * Action Button Traits.
	 */
	use ActionBtnTraits;

	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_base = $this->get_the_base();
		parent::__construct( $data, $args );
		$this->pro_tab = 'layout';
	}
	/**
	 * @return string
	 */
	abstract protected function get_the_base();

	/**
	 * Output the related products.
	 *
	 * @param array $args Provided arguments.
	 */
	abstract public function get_the_products( $args = [] );

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return array_merge(
			$this->layout_tab(),
			$this->settings_tab(),
			$this->style_tab()
		);
	}

	/**
	 * Controls for layout tab
	 *
	 * @return array
	 */
	protected function layout_tab() {
		$fields = apply_filters(
			'rtsb/elements/elementor/grid_layout_tab/' . $this->rtsb_base,
			array_merge(
				$this->widget_layout(),
				$this->widget_query()
			)
		);

		return $fields;
	}

	/**
	 * Controls for settings tab
	 *
	 * @return array
	 */
	protected function settings_tab() {
		$content_visibility = Controls\SettingsFields::content_visibility( $this );

		$extra_controls['show_section_title'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Section Title?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show product title.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'separator'   => 'default',
		];

		$content_visibility = Fns::insert_controls( 'show_title', $content_visibility, $extra_controls );

		unset( $content_visibility['show_title']['separator'] );

		$sections = array_merge(
			$content_visibility,
			Controls\SettingsFields::content_ordering( $this ),
			Controls\SettingsFields::section_title( $this ),
			Controls\SettingsFields::image( $this ),
			Controls\SettingsFields::action_buttons( $this ),
			Controls\SettingsFields::product_title( $this ),
			Controls\SettingsFields::product_excerpt( $this ),
			Controls\SettingsFields::badges( $this ),
			$this->variation_swatch_conditionaly(),
			Controls\SettingsFields::links( $this )
		);
		return apply_filters( 'rtsb/elements/elementor/product_custom_settings_tab', $sections, $this );
	}
	/**
	 * Controls for layout tab
	 *
	 * @return array
	 */
	protected function widget_layout() {
		return Controls\LayoutFields::grid_layout( $this );
	}

	/**
	 * Controls for layout tab
	 *
	 * @return array
	 */
	public function variation_swatch_conditionaly() {
		if ( ! function_exists( 'rtwpvsp' ) && ! rtsb()->has_pro() ) {
			return [];
		}
		$swatches_controls = Controls\SettingsFields::variation_swatch( $this );
		if ( ! empty( $swatches_controls['swatch_position'] ) ) {
			$swatches_controls['swatch_position']['condition'] = [
				'layout' => [ 'grid-layout1' ],
			];
		}
		return $swatches_controls;
	}
	/**
	 * Controls for layout tab
	 *
	 * @return array
	 */
	protected function widget_query() {
		$additional_query                             = Controls\LayoutFields::additional_query( $this );
		$additional_query['posts_limit']['separator'] = 'default';
		return $additional_query;
	}

	/**
	 * Controls for style tab
	 *
	 * @return array
	 */
	protected function pagination_navigation() {
		return Controls\StyleFields::pagination( $this );
	}

	/**
	 * Controls for style tab
	 *
	 * @return array
	 */
	protected function style_tab() {
		return apply_filters(
			'rtsb/elementor/grid_style_tab',
			array_merge(
				Controls\StyleFields::color_scheme( $this ),
				Controls\StyleFields::layout_design( $this ),
				Controls\StyleFields::product_image( $this ),
				Controls\StyleFields::section_title( $this ),
				Controls\StyleFields::product_title( $this ),
				Controls\StyleFields::short_description( $this ),
				Controls\StyleFields::product_price( $this ),
				Controls\StyleFields::product_categories( $this ),
				Controls\StyleFields::product_rating( $this ),
				Controls\StyleFields::product_add_to_cart( $this ),
				Controls\StyleFields::product_wishlist( $this ),
				Controls\StyleFields::product_quick_view( $this ),
				Controls\StyleFields::product_compare( $this ),
				Controls\StyleFields::action_buttons( $this ),
				Controls\StyleFields::product_badges( $this ),
				$this->pagination_navigation(),
				Controls\StyleFields::hover_icon_button( $this )
			),
			$this
		);
	}

	/**
	 * Widget Field
	 *
	 * @return string
	 */
	protected function render_template_file() {
		return 'elementor/general/grid/';
	}
	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->action_button_icon_modify();

		$this->render_start();
		$args = [
			'posts_per_page' => $settings['posts_limit'],
			'columns'        => $settings['posts_limit'],
			'orderby'        => $settings['posts_order_by'],
			'order'          => $settings['posts_order'],
		];

		$settings['query_products'] = $this->get_the_products( $args );

		if ( empty( $settings['query_products'] ) ) {
			return;
		}

		if ( $this->is_builder_mode() ) {
			add_filter( 'wp_kses_allowed_html', [ FilterHooks::class, 'custom_wpkses_post_tags' ], 10, 2 );
		}

		// Call the template rendering method.
		Fns::print_html( Render::instance()->setup_loop_for_product_view( $this->render_template_file(), $settings, $this ), true );

		$this->action_button_icon_set_default();
		$this->render_end();
	}
}
