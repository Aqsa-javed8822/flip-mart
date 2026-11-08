<?php
/**
 * BuilderFns class
 *
 * The  builder.
 *
 * @package  RadiusTheme\SB
 * @since    1.0.0
 */

namespace RadiusTheme\SB\Elementor\Helper;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * BuilderFns class
 */
class ControlHelper {
	/**
	 * Widget alignment.
	 *
	 * @return array
	 */
	public static function alignment() {
		return [
			'left'    => [
				'title' => esc_html__( 'Left', 'shopbuilder' ),
				'icon'  => 'eicon-text-align-left',
			],
			'center'  => [
				'title' => esc_html__( 'Center', 'shopbuilder' ),
				'icon'  => 'eicon-text-align-center',
			],
			'right'   => [
				'title' => esc_html__( 'Right', 'shopbuilder' ),
				'icon'  => 'eicon-text-align-right',
			],
			'justify' => [
				'title' => esc_html__( 'Justified', 'shopbuilder' ),
				'icon'  => 'eicon-text-align-justify',
			],
		];
	}
	/**
	 * Widget alignment.
	 *
	 * @return array
	 */
	public static function flex_alignment() {
		return [
			'start'  => [
				'title' => esc_html__( 'Start', 'shopbuilder' ),
				'icon'  => 'eicon-text-align-left',
			],
			'center' => [
				'title' => esc_html__( 'Center', 'shopbuilder' ),
				'icon'  => 'eicon-text-align-center',
			],
			'end'    => [
				'title' => esc_html__( 'End', 'shopbuilder' ),
				'icon'  => 'eicon-text-align-right',
			],
		];
	}
	/**
	 * Grid Layout Columns
	 *
	 * @return array
	 */
	public static function layout_columns() {
		return [
			0 => esc_html__( 'Layout Default', 'shopbuilder' ),
			1 => esc_html__( '1 Column', 'shopbuilder' ),
			2 => esc_html__( '2 Columns', 'shopbuilder' ),
			3 => esc_html__( '3 Columns', 'shopbuilder' ),
			4 => esc_html__( '4 Columns', 'shopbuilder' ),
			5 => esc_html__( '5 Columns', 'shopbuilder' ),
			6 => esc_html__( '6 Columns', 'shopbuilder' ),
			7 => esc_html__( '7 Columns', 'shopbuilder' ),
			8 => esc_html__( '8 Columns', 'shopbuilder' ),
		];
	}

	/**
	 * Grid Layouts
	 *
	 * @return array
	 */
	public static function grid_layouts() {
		$status = ! rtsb()->has_pro();

		return apply_filters(
			'rtsb/elements/elementor/grid_layouts',
			[
				'grid-layout1' => [
					'title' => esc_html__( 'Grid Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/grid-Layout-1.png' ) ),
				],

				'grid-layout2' => [
					'title' => esc_html__( 'Grid Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/grid-Layout-2.png' ) ),
				],

				'grid-layout3' => [
					'title'  => esc_html__( 'Grid Layout 3', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/grid-style-3.png' ) ),
					'is_pro' => $status,
				],
				'grid-layout4' => [
					'title'  => esc_html__( 'Grid Layout 4', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/grid-style-4.png' ) ),
					'is_pro' => $status,
				],
				'grid-layout5' => [
					'title'  => esc_html__( 'Grid Layout 5', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/grid-style-5.png' ) ),
					'is_pro' => $status,
				],
				'grid-layout6' => [
					'title'  => esc_html__( 'Grid Layout 6', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/grid-style-6.png' ) ),
					'is_pro' => $status,
				],
				'grid-layout7' => [
					'title'  => esc_html__( 'Grid Layout 7', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/grid-style-7.png' ) ),
					'is_pro' => $status,
				],
				'grid-layout8' => [
					'title'  => esc_html__( 'Grid Layout 8', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/grid-style-8.png' ) ),
					'is_pro' => $status,
				],
				'grid-layout9' => [
					'title'  => esc_html__( 'Special Layout 1', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/grid-style-9a.png' ) ),
					'is_pro' => $status,
				],
			]
		);
	}

	/**
	 * Single Product Tab Layouts
	 *
	 * @return array
	 */
	public static function single_product_tab_layouts() {
		$status = ! rtsb()->has_pro();

		return apply_filters(
			'rtsb/elements/elementor/single_product_tab_layouts',
			[
				'default'        => [
					'title' => esc_html__( 'Default Layout', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/tab-01.png' ) ),
				],
				'custom-layout1' => [
					'title'  => esc_html__( 'Accordion Layout', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/tab-02.png' ) ),
					'is_pro' => $status,
				],
				'custom-layout2' => [
					'title'  => esc_html__( 'Tab Layout', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/tab-03.png' ) ),
					'is_pro' => $status,
				],
			]
		);
	}


	/**
	 * General Widgets Advanced Title Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_advanced_title_layouts() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_advanced_title_layouts',
			[
				'rtsb-advanced-heading-layout1' => [
					'title' => esc_html__( 'Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/advance-heading-layout-01.png' ) ),
				],
			]
		);
	}
	/**
	 * General Widgets Advanced Title Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_button_layouts() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_button_layouts',
			[
				'rtsb-sb-button-layout1' => [
					'title' => esc_html__( 'Single Button', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/advanced-button-layout-01.png' ) ),
				],
				'rtsb-sb-button-layout2' => [
					'title' => esc_html__( 'Dual Button', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/advanced-button-layout-02.png' ) ),
				],
			]
		);
	}
	/**
	 * General Widgets FlipBox Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_flip_box_layouts() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_flip_box_layouts',
			[
				'rtsb-flip-box-layout1' => [
					'title' => esc_html__( 'Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/flip-box-layout-01.png' ) ),
				],
			]
		);
	}
	/**
	 * General Widgets Counter Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_counter_layouts() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_counter_layouts',
			[
				'rtsb-counter-layout1' => [
					'title' => esc_html__( 'Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/fun-facts-layout-01.png' ) ),
				],
				'rtsb-counter-layout2' => [
					'title' => esc_html__( 'Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/fun-facts-layout-02.png' ) ),
				],

			]
		);
	}
	/**
	 * General Widgets Countdown Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_countdown_layouts() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_countdown_layouts',
			[
				'rtsb-countdown-layout1' => [
					'title' => esc_html__( 'Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/countdown-layout-01.png' ) ),
				],
				'rtsb-countdown-layout2' => [
					'title' => esc_html__( 'Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/countdown-layout-02.png' ) ),
				],
				'rtsb-countdown-layout3' => [
					'title' => esc_html__( 'Layout 3', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/countdown-layout-03.png' ) ),
				],
				'rtsb-countdown-layout4' => [
					'title' => esc_html__( 'Layout 4', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/countdown-layout-04.png' ) ),
				],
				'rtsb-countdown-layout5' => [
					'title' => esc_html__( 'Layout 5', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/countdown-layout-05.png' ) ),
				],
				'rtsb-countdown-layout6' => [
					'title' => esc_html__( 'Layout 6', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/countdown-layout-06.png' ) ),
				],
			]
		);
	}

	/**
	 * General Widgets Progress Bar Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_progress_bar_layouts() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_progress_bar_layouts',
			[
				'rtsb-progress-bar-layout1' => [
					'title' => esc_html__( 'Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/progressbar-layout-01.png' ) ),
				],
				'rtsb-progress-bar-layout4' => [
					'title' => esc_html__( 'Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/progressbar-layout-02.png' ) ),
				],
				'rtsb-progress-bar-layout5' => [
					'title' => esc_html__( 'Layout 3', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/progressbar-layout-03.png' ) ),
				],
				'rtsb-progress-bar-layout2' => [
					'title' => esc_html__( 'Layout 4', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/progressbar-layout-04.png' ) ),
				],
				'rtsb-progress-bar-layout3' => [
					'title' => esc_html__( 'Layout 5', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/progressbar-layout-05.png' ) ),
				],
			]
		);
	}

	/**
	 * General Widgets ShopBuilder Accordion Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_sb_faq_layouts() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_sb_faq_layouts',
			[
				'rtsb-sb-faq-layout1' => [
					'title' => esc_html__( 'Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/faqs-layout-01.png' ) ),
				],
				'rtsb-sb-faq-layout3' => [
					'title' => esc_html__( 'Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/faqs-layout-02.png' ) ),
				],
				'rtsb-sb-faq-layout5' => [
					'title' => esc_html__( 'Layout 3', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/faqs-layout-03.png' ) ),
				],
				'rtsb-sb-faq-layout2' => [
					'title' => esc_html__( 'Layout 4', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/faqs-layout-04.png' ) ),
				],
				'rtsb-sb-faq-layout4' => [
					'title' => esc_html__( 'Layout 5', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/faqs-layout-05.png' ) ),
				],
				'rtsb-sb-faq-layout6' => [
					'title' => esc_html__( 'Layout 6', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/faqs-layout-06.png' ) ),
				],

			]
		);
	}
	/**
	 * General Widgets Logo Slider And Grid Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_logo_slider_grid_layouts() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_logo_slider_and_grid_layouts',
			[
				'rtsb-logo-layout1' => [
					'title' => esc_html__( 'Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/logo-slider-layout-01.png' ) ),
				],
				'rtsb-logo-layout2' => [
					'title' => esc_html__( 'Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/logo-slider-layout-02.png' ) ),
				],
			]
		);
	}
	/**
	 * General Widgets Logo Slider And Grid Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_testimonial() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_testimonial_layouts',
			[
				'rtsb-testimonial-layout1' => [
					'title' => esc_html__( 'Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/testimonial-layout-01.png' ) ),
				],
				'rtsb-testimonial-layout2' => [
					'title' => esc_html__( 'Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/testimonial-layout-02.png' ) ),
				],
				'rtsb-testimonial-layout3' => [
					'title' => esc_html__( 'Layout 3', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/testimonial-layout-03.png' ) ),
				],
				'rtsb-testimonial-layout4' => [
					'title' => esc_html__( 'Layout 4', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/testimonial-layout-04.png' ) ),
				],
				'rtsb-testimonial-layout5' => [
					'title' => esc_html__( 'Layout 5', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/testimonial-layout-05.png' ) ),
				],
			]
		);
	}
	/**
	 * General Widgets Logo Slider And Grid Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_team_member() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_team_member_layouts',
			[
				'rtsb-team-layout1' => [
					'title' => esc_html__( 'Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/team-layout-01.png' ) ),
				],
				'rtsb-team-layout2' => [
					'title' => esc_html__( 'Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/team-layout-02.png' ) ),
				],
				'rtsb-team-layout3' => [
					'title' => esc_html__( 'Layout 3', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/team-layout-03.png' ) ),
				],
				'rtsb-team-layout4' => [
					'title' => esc_html__( 'Layout 4', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/team-layout-04.png' ) ),
				],
			]
		);
	}
	/**
	 * General Widgets Post List Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_post_list() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_post_lists_layouts',
			[
				'rtsb-post-list-layout1' => [
					'title' => esc_html__( 'Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/post-list-layout-01.png' ) ),
				],
				'rtsb-post-list-layout2' => [
					'title' => esc_html__( 'Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/post-list-layout-02.png' ) ),
				],
			]
		);
	}
	/**
	 * General Widgets Post Grid Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_post_grid() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_post_grid_layouts',
			[
				'rtsb-post-grid-layout1' => [
					'title' => esc_html__( 'Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/post-grid-layout-01.png' ) ),
				],
				'rtsb-post-grid-layout2' => [
					'title' => esc_html__( 'Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/post-grid-layout-02.png' ) ),
				],
				'rtsb-post-grid-layout3' => [
					'title' => esc_html__( 'Layout 3', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/post-grid-layout-03.png' ) ),
				],
			]
		);
	}

	/**
	 * General Widgets ShopBuilder Accordion Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_image_accordion_layouts() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_image_accordion_layouts',
			[
				'rtsb-image-accordion-layout1' => [
					'title' => esc_html__( 'Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/image-accordion-layout-01.png' ) ),
				],
				'rtsb-image-accordion-layout2' => [
					'title' => esc_html__( 'Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/image-accordion-layout-02.png' ) ),
				],
			]
		);
	}


	/**
	 * General Widgets FlipBox Widget Flip Direction
	 *
	 * @return array
	 */
	public static function flip_box_widget_flip_direction() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_flip_box_flip_direction',
			[
				'sb-flip-left'      => esc_html__( 'Flip Left', 'shopbuilder' ),
				'sb-flip-right'     => esc_html__( 'Flip Right', 'shopbuilder' ),
				'sb-flip-up'        => esc_html__( 'Flip Up', 'shopbuilder' ),
				'sb-flip-bottom'    => esc_html__( 'Flip Bottom', 'shopbuilder' ),
				'sb-flip-fade-in'   => esc_html__( 'Fade In', 'shopbuilder' ),
				'sb-flip-zoom-in'   => esc_html__( 'Zoom In', 'shopbuilder' ),
				'sb-flip-zoom-out'  => esc_html__( 'Zoom Out', 'shopbuilder' ),
				'sb-flip-3d-left'   => esc_html__( 'Flip 3D Left', 'shopbuilder' ),
				'sb-flip-3d-right'  => esc_html__( 'Flip 3D Right', 'shopbuilder' ),
				'sb-flip-3d-up'     => esc_html__( 'Flip 3D Up', 'shopbuilder' ),
				'sb-flip-3d-bottom' => esc_html__( 'Flip 3D Bottom', 'shopbuilder' ),
			]
		);
	}

	/**
	 * General Widgets Button Hover Effects Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_button_hover_effect_preset() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_button_hover_effects_preset',
			[
				'rtsb-sb-button-hover-effect-default' => [
					'title' => esc_html__( 'Default Hover Effect', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/button-hover-effect-01.gif' ) ),
				],
				'rtsb-sb-button-hover-effect-preset2' => [
					'title' => esc_html__( 'Hover Effect 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/button-hover-effect-02.gif' ) ),
				],
				'rtsb-sb-button-hover-effect-preset3' => [
					'title' => esc_html__( 'Hover Effect 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/button-hover-effect-03.gif' ) ),
				],
			]
		);
	}
	/**
	 * General Widgets Call To Action Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_cta_layouts() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_cta_layouts',
			[
				'rtsb-cta-layout1' => [
					'title' => esc_html__( 'Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/call-to-action-layout-01.png' ) ),
				],
				'rtsb-cta-layout2' => [
					'title' => esc_html__( 'Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/call-to-action-layout-02.png' ) ),
				],
				'rtsb-cta-layout3' => [
					'title' => esc_html__( 'Layout 3', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/call-to-action-layout-03.png' ) ),
				],
			]
		);
	}
	/**
	 * General Widgets Info Box Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_info_box_layouts() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_info_box_layouts',
			[
				'rtsb-info-box-layout1' => [
					'title' => esc_html__( 'Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/info-box-layout-01.png' ) ),
				],
				'rtsb-info-box-layout3' => [
					'title' => esc_html__( 'Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/info-box-layout-02.png' ) ),
				],
				'rtsb-info-box-layout4' => [
					'title' => esc_html__( 'Layout 3', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/info-box-layout-03.png' ) ),
				],
			]
		);
	}
	/**
	 * General Widgets Pricing Table Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_pricing_table_layouts() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_pricing_table_layouts',
			[
				'rtsb-pricing-table-layout1' => [
					'title' => esc_html__( 'Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/pricing-table-layout-01.png' ) ),
				],
				'rtsb-pricing-table-layout2' => [
					'title' => esc_html__( 'Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/pricing-table-layout-02.png' ) ),
				],
				'rtsb-pricing-table-layout3' => [
					'title' => esc_html__( 'Layout 3', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/pricing-table-layout-03.png' ) ),
				],
			]
		);
	}
	/**
	 * General Widgets Pricing Table Badge Preset
	 *
	 * @return array
	 */
	public static function general_widgets_pricing_table_badge_preset() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_pricing_table_badge_preset',
			[
				'rtsb-pricing-table-badge-preset1' => [
					'title' => esc_html__( 'Preset 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/pricing-badge-layout-01.png' ) ),
				],
				'rtsb-pricing-table-badge-preset2' => [
					'title' => esc_html__( 'Preset 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/pricing-badge-layout-02.png' ) ),
				],
				'rtsb-pricing-table-badge-preset3' => [
					'title' => esc_html__( 'Preset 3', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/pricing-badge-layout-03.png' ) ),
				],
				'rtsb-pricing-table-badge-preset4' => [
					'title' => esc_html__( 'Preset 4', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/pricing-badge-layout-04.png' ) ),
				],
				'rtsb-pricing-table-badge-preset5' => [
					'title' => esc_html__( 'Preset 5', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/pricing-badge-layout-04.png' ) ),
				],
			]
		);
	}
	/**
	 * General Widgets Dropcaps Layouts
	 *
	 * @return array
	 */
	public static function general_widgets_dropcaps_layouts() {

		return apply_filters(
			'rtsb/elements/elementor/general_widgets_dropcaps_layouts',
			[
				'rtsb-dropcaps-layout1' => [
					'title' => esc_html__( 'Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/dropcaps-layout-01.png' ) ),
				],
				'rtsb-dropcaps-layout2' => [
					'title' => esc_html__( 'Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/dropcaps-layout-02.png' ) ),
				],
			]
		);
	}

	/**
	 * Multi Step Checkout Layouts
	 *
	 * @return array
	 */
	public static function multi_step_checkout_layouts() {

		return apply_filters(
			'rtsb/elements/elementor/single_product_tab_layouts',
			[
				'layout1' => [
					'title' => esc_html__( 'Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout-preview/multistep-checkout-layout-1.jpg' ) ),
				],
				'layout2' => [
					'title' => esc_html__( 'Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout-preview/multistep-checkout-lauout-2.jpg' ) ),
				],

			]
		);
	}

	/**
	 * Single Advanced Product Tab Layouts
	 *
	 * @return array
	 */
	public static function single_advanced_product_tab_layouts() {
		// TODO:: Check If the FUnction Used Or Not.
		return apply_filters(
			'rtsb/elements/elementor/single_advanced_product_tab_layouts',
			[
				'default'        => [
					'title' => esc_html__( 'Default Layout', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/tab-01.png' ) ),
				],
				'custom-layout1' => [
					'title' => esc_html__( 'Accordion Layout', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/tab-02.png' ) ),
				],
				'custom-layout2' => [
					'title' => esc_html__( 'Tab Layout', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/tab-03.png' ) ),
				],
			]
		);
	}

	/**
	 * Slider Layouts
	 *
	 * @return array
	 */
	public static function slider_layouts() {
		$status = ! rtsb()->has_pro();

		return apply_filters(
			'rtsb/elements/elementor/slider_layouts',
			[
				'slider-layout1' => [
					'title' => esc_html__( 'Slider Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/grid-Layout-1.png' ) ),
				],

				'slider-layout2' => [
					'title' => esc_html__( 'Slider Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/grid-Layout-2.png' ) ),
				],

				'slider-layout3' => [
					'title'  => esc_html__( 'Slider Layout 3', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/slider-style-3.png' ) ),
					'is_pro' => $status,
				],

				'slider-layout4' => [
					'title'  => esc_html__( 'Slider Layout 4', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/slider-style-4.png' ) ),
					'is_pro' => $status,
				],
				'slider-layout5' => [
					'title'  => esc_html__( 'Slider Layout 5', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/slider-style-5.png' ) ),
					'is_pro' => $status,
				],
				'slider-layout6' => [
					'title'  => esc_html__( 'Slider Layout 6', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/slider-style-6.png' ) ),
					'is_pro' => $status,
				],
				'slider-layout7' => [
					'title'  => esc_html__( 'Slider Layout 7', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/slider-style-7.png' ) ),
					'is_pro' => $status,
				],
				'slider-layout8' => [
					'title'  => esc_html__( 'Slider Layout 8', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/list-style-6.png' ) ),
					'is_pro' => $status,
				],
				'slider-layout9' => [
					'title'  => esc_html__( 'Slider Layout 9', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/grid-style-8.png' ) ),
					'is_pro' => $status,
				],
			]
		);
	}

	/**
	 * List Layouts
	 *
	 * @return array
	 */
	public static function list_layouts() {
		$status = ! rtsb()->has_pro();

		return apply_filters(
			'rtsb/elements/elementor/list_layouts',
			[
				'list-layout1' => [
					'title' => esc_html__( 'List Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/list-Layout-1.png' ) ),
				],
				'list-layout2' => [
					'title' => esc_html__( 'List Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/list-Layout-2.png' ) ),
				],
				'list-layout3' => [
					'title'  => esc_html__( 'List Layout 3', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/list-style-3.png' ) ),
					'is_pro' => $status,
				],
				'list-layout4' => [
					'title'  => esc_html__( 'List Layout 4', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/list-style-4.png' ) ),
					'is_pro' => $status,
				],
				'list-layout5' => [
					'title'  => esc_html__( 'List Layout 5', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/list-style-5.png' ) ),
					'is_pro' => $status,
				],
				'list-layout6' => [
					'title'  => esc_html__( 'List Layout 6', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/list-style-6.png' ) ),
					'is_pro' => $status,
				],
				'list-layout7' => [
					'title'  => esc_html__( 'List Layout 7', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/list-style-7.png' ) ),
					'is_pro' => $status,
				],
			]
		);
	}

	/**
	 * Category Layouts
	 *
	 * @return array
	 */
	public static function category_layouts() {
		$status = ! rtsb()->has_pro();

		return apply_filters(
			'rtsb/elements/elementor/category_layouts',
			[
				'category-layout1' => [
					'title' => esc_html__( 'Category Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/categories-1.png' ) ),
				],

				'category-layout2' => [
					'title' => esc_html__( 'Category Layout 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/categories-2.png' ) ),
				],
				'category-layout3' => [
					'title'  => esc_html__( 'Category Layout 3', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/categories-3.png' ) ),
					'is_pro' => $status,
				],
				'category-layout4' => [
					'title'  => esc_html__( 'Category Layout 4', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/categories-3.png' ) ),
					'is_pro' => $status,
				],

			]
		);
	}

	/**
	 * Category Layouts
	 *
	 * @return array
	 */
	public static function share_layouts() {
		$status = ! rtsb()->has_pro();

		return apply_filters(
			'rtsb/elements/elementor/share_layouts',
			[
				'share-layout1' => [
					'title' => esc_html__( 'Social Share Preset 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/share-preset-1.png' ) ),
				],

				'share-layout2' => [
					'title' => esc_html__( 'Social Share Preset 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/share-preset-2.png' ) ),
				],

				// TODO: Will be activated later.
				// 'share-layout3' => [
				// 'title'  => esc_html__( 'Social Share Preset 3', 'shopbuilder' ),
				// 'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/share-preset-1.png' ) ),
				// 'is_pro' => $status,
				// ],
			]
		);
	}

	/**
	 * Action Button Presets
	 *
	 * @return array
	 */
	public static function action_btn_presets() {

		return apply_filters(
			'rtsb/elements/elementor/action_btn_presets',
			[
				'preset1' => [
					'title' => esc_html__( 'Preset 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/action-preset-1.jpg' ) ),
				],

				'preset2' => [
					'title' => esc_html__( 'Preset 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/action-preset-2.jpg' ) ),
				],

				'preset3' => [
					'title' => esc_html__( 'Preset 3', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/action-preset-3.jpg' ) ),
				],
				'preset4' => [
					'title' => esc_html__( 'Preset 4', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/action-preset-4.jpg' ) ),
				],
				'preset5' => [
					'title'  => esc_html__( 'Preset 5', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/action-preset-5.jpg' ) ),
					'is_pro' => ! rtsb()->has_pro(),
				],
				'preset6' => [
					'title'  => esc_html__( 'Preset 6', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/action-preset-6.jpg' ) ),
					'is_pro' => ! rtsb()->has_pro(),
				],
			]
		);
	}

	/**
	 * Badge Presets
	 *
	 * @return array
	 */
	public static function badge_presets() {
		$status = ! rtsb()->has_pro();

		return apply_filters(
			'rtsb/elements/elementor/badge_presets',
			[
				'preset1' => [
					'title' => esc_html__( 'Preset 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/badge-preset-1.png' ) ),
				],

				'preset2' => [
					'title' => esc_html__( 'Preset 2', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/badge-preset-2.png' ) ),
				],

				'preset3' => [
					'title' => esc_html__( 'Preset 3', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/badge-preset-3.png' ) ),
				],

				'preset4' => [
					'title' => esc_html__( 'Preset 4', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/badge-preset-4.png' ) ),
				],
			]
		);
	}

	/**
	 * Single Category Layouts
	 *
	 * @return array
	 */
	public static function single_category_layouts() {
		$status = ! rtsb()->has_pro();

		return apply_filters(
			'rtsb/elements/elementor/category_single_layouts',
			[
				'category-single-layout1' => [
					'title' => esc_html__( 'Single Category Layout 1', 'shopbuilder' ),
					'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/single-category-1.png' ) ),
				],

				'category-single-layout2' => [
					'title'  => esc_html__( 'Single Category Layout 2', 'shopbuilder' ),
					'url'    => esc_url( rtsb()->get_assets_uri( 'images/layout/single-category-2.png' ) ),
					'is_pro' => $status,
				],
			]
		);
	}


	/**
	 * Posts Order By.
	 *
	 * @return array
	 */
	public static function posts_order_by() {
		return [
			'ID'         => esc_html__( 'Product ID', 'shopbuilder' ),
			'title'      => esc_html__( 'Product Title', 'shopbuilder' ),
			'price'      => esc_html__( 'Product Price', 'shopbuilder' ),
			'date'       => esc_html__( 'Date', 'shopbuilder' ),
			'menu_order' => esc_html__( 'Menu Order', 'shopbuilder' ),
			'rand'       => esc_html__( 'Random', 'shopbuilder' ),
		];
	}

	/**
	 * Categories Order By.
	 *
	 * @return array
	 */
	public static function cats_order_by() {
		return [
			'id'         => esc_html__( 'Category IDs', 'shopbuilder' ),
			'name'       => esc_html__( 'Category Name', 'shopbuilder' ),
			'count'      => esc_html__( 'Product Count', 'shopbuilder' ),
			'menu_order' => esc_html__( 'Menu Order', 'shopbuilder' ),
		];
	}

	/**
	 * Posts Order By.
	 *
	 * @return array
	 */
	public static function posts_order() {
		return [
			'ASC'  => esc_html__( 'Ascending', 'shopbuilder' ),
			'DESC' => esc_html__( 'Descending', 'shopbuilder' ),
		];
	}

	/**
	 * Filter products
	 *
	 * @return array
	 */
	public static function filter_products() {
		return [
			'recent'          => esc_html__( 'Default', 'shopbuilder' ),
			'featured'        => esc_html__( 'Featured Products', 'shopbuilder' ),
			'best-selling'    => esc_html__( 'Best Selling Products', 'shopbuilder' ),
			'sale'            => esc_html__( 'On Sale Products', 'shopbuilder' ),
			'top-rated'       => esc_html__( 'Top Rated Products', 'shopbuilder' ),
			'recently-viewed' => esc_html__( 'Recently Viewed Products', 'shopbuilder' ),
		];
	}

	/**
	 * Pagination options
	 *
	 * @return array
	 */
	public static function pagination_options() {
		return apply_filters(
			'rtsb/elementor/pagination_options',
			[
				'pagination' => esc_html__( 'Number Pagination', 'shopbuilder' ),
			]
		);
	}

	/**
	 * Heading Tags
	 *
	 * @return array
	 */
	public static function heading_tags() {
		return [
			'h1'   => esc_html__( 'H1', 'shopbuilder' ),
			'h2'   => esc_html__( 'H2', 'shopbuilder' ),
			'h3'   => esc_html__( 'H3', 'shopbuilder' ),
			'h4'   => esc_html__( 'H4', 'shopbuilder' ),
			'h5'   => esc_html__( 'H5', 'shopbuilder' ),
			'h6'   => esc_html__( 'H6', 'shopbuilder' ),
			'p'    => esc_html__( 'p', 'shopbuilder' ),
			'div'  => esc_html__( 'div', 'shopbuilder' ),
			'span' => esc_html__( 'span', 'shopbuilder' ),
		];
	}

	/**
	 * General Style Section
	 *
	 * @param string $id_prefix Section id prefix.
	 * @param string $title Section title.
	 * @param object $obj Reference object.
	 * @param array  $condition Condition.
	 * @param array  $selectors CSS electors.
	 * @param array  $conditions Conditions.
	 *
	 * @return array
	 */
	public static function general_elementor_style( $id_prefix, $title, $obj, $condition, $selectors, $conditions = [] ) {
		$prefix = str_replace( ' ', '_', strtolower( $id_prefix ) ) . '_';

		$fields[ $prefix . 'style_section' ] = $obj->start_section(
			esc_html( $title ),
			'style',
			$conditions,
			$condition
		);

		$fields[ $prefix . 'typo_note' ] = $obj->el_heading( esc_html__( 'Typography', 'shopbuilder' ), 'default' );

		$fields[ 'rtsb_el_' . $prefix . 'typography' ] = [
			'mode'     => 'group',
			'type'     => 'typography',
			'selector' => ! empty( $selectors['typography'] ) ? $selectors['typography'] : [],
		];

		$fields[ $prefix . 'alignment' ] = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'label'     => esc_html__( 'Alignment', 'shopbuilder' ),
			'options'   => [
				'left'   => [
					'title' => esc_html__( 'Left', 'shopbuilder' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'shopbuilder' ),
					'icon'  => 'eicon-text-align-center',
				],
				'right'  => [
					'title' => esc_html__( 'Right', 'shopbuilder' ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'selectors' => ! empty( $selectors['alignment'] ) ? $selectors['alignment'] : [],
		];

		$fields[ $prefix . 'color_note' ] = $obj->el_heading( esc_html__( 'Colors', 'shopbuilder' ), 'before' );

		$fields[ $prefix . 'color_tabs' ] = $obj->start_tab_group();
		$fields[ $prefix . 'color_tab' ]  = $obj->start_tab( esc_html__( 'Normal', 'shopbuilder' ) );

		$fields[ $prefix . 'color' ] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Color', 'shopbuilder' ),
			'selectors' => ! empty( $selectors['color'] ) ? $selectors['color'] : [],
			'separator' => 'default',
		];

		$fields[ $prefix . 'bg_color' ] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
			'selectors' => ! empty( $selectors['bg_color'] ) ? $selectors['bg_color'] : [],
			'separator' => 'default',
		];

		$fields[ $prefix . 'color_tab_end' ]   = $obj->end_tab();
		$fields[ $prefix . 'hover_color_tab' ] = $obj->start_tab( esc_html__( 'Hover', 'shopbuilder' ) );

		$fields[ $prefix . 'hover_color' ] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Hover Color', 'shopbuilder' ),
			'selectors' => ! empty( $selectors['hover_color'] ) ? $selectors['hover_color'] : [],
			'separator' => 'default',
		];

		$fields[ $prefix . 'hover_bg_color' ] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Hover Background Color', 'shopbuilder' ),
			'selectors' => ! empty( $selectors['hover_bg_color'] ) ? $selectors['hover_bg_color'] : [],
			'separator' => 'default',
		];

		$fields[ $prefix . 'hover_color_tab_end' ] = $obj->end_tab();
		$fields[ $prefix . 'color_tabs_end' ]      = $obj->end_tab_group();

		$fields[ $prefix . 'border_note' ] = $obj->el_heading( esc_html__( 'Border', 'shopbuilder' ), 'before' );

		$fields[ 'rtsb_el_' . $prefix . 'border' ] = [
			'mode'           => 'group',
			'type'           => 'border',
			'label'          => esc_html__( 'Border', 'shopbuilder' ),
			'selector'       => ! empty( $selectors['border'] ) ? $selectors['border'] : [],
			'fields_options' => [
				'color' => [
					'label' => esc_html__( 'Border Color', 'shopbuilder' ),
				],
			],
			'separator'      => 'default',
		];

		$fields[ $prefix . 'border_hover_color' ] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Hover Border Color', 'shopbuilder' ),
			'condition' => [ 'rtsb_el_' . $prefix . 'border_border!' => [ '' ] ],
			'selectors' => ! empty( $selectors['border_hover_color'] ) ? $selectors['border_hover_color'] : [],
			'separator' => 'default',
		];

		$fields[ $prefix . 'spacing_note' ] = $obj->el_heading( esc_html__( 'Spacing', 'shopbuilder' ), 'before' );

		$fields[ $prefix . 'padding' ] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Padding', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => ! empty( $selectors['padding'] ) ? $selectors['padding'] : [],
			'separator'  => 'default',
		];

		$fields[ $prefix . 'margin' ] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Margin', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => ! empty( $selectors['margin'] ) ? $selectors['margin'] : [],
		];

		$fields[ $prefix . 'style_section_end' ] = $obj->end_section();

		return $fields;
	}

	/**
	 * Sharing Settings.
	 *
	 * @return array
	 */
	public static function sharing_settings() {
		$settings       = get_option( 'rtsb_settings' );
		$share_settings = ! empty( $settings['general']['social_share']['share_platforms'] ) ? $settings['general']['social_share']['share_platforms'] : [];

		return ! empty( $share_settings ) && is_array( $share_settings ) ? $share_settings : [ 'facebook', 'twitter' ];
	}
	/**
	 * General Widgets Social Media List
	 *
	 * @return array
	 */
	public static function social_media_field() {
		$social_media_lists = self::social_media_profile_list();
		$fields             = [];
		foreach ( $social_media_lists as $key => $social_media_list ) {
			$fields[ $key . '_url' ] = [
				'label'       => $social_media_list['name'],
				'type'        => 'url',
				'label_block' => true,
			];
			switch ( $key ) {
				case 'facebook':
					$fields[ $key . '_url' ]['default'] = [
						'url'         => 'https://www.facebook.com/',
						'is_external' => true,
						'nofollow'    => true,
					];
					break;
				case 'twitter':
					$fields[ $key . '_url' ]['default'] = [
						'url'         => 'https://www.x.com/',
						'is_external' => true,
						'nofollow'    => true,
					];
					break;
				case 'youtube':
					$fields[ $key . '_url' ]['default'] = [
						'url'         => 'https://www.youtube.com/',
						'is_external' => true,
						'nofollow'    => true,
					];
					break;
				default:
					break;
			}
		}
		return $fields;
	}
	/**
	 * General Widgets Social Media Profile List
	 *
	 * @return array
	 */
	public static function social_media_profile_list() {
		return apply_filters(
			'rtsb/general/widgets/social_media_lists',
			[
				'facebook'  => [
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="20" viewBox="0 0 602 1024"><path d="M548 6.857v150.857h-89.714q-49.143 0-66.286 20.571t-17.143 61.714v108h167.429l-22.286 169.143h-145.143v433.714h-174.857v-433.714h-145.714v-169.143h145.714v-124.571q0-106.286 59.429-164.857t158.286-58.571q84 0 130.286 6.857z"></path></svg>',
					'name' => esc_html__( 'Facebook', 'shopbuilder' ),
				],
				'twitter'   => [
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg>',
					'name' => esc_html__( 'Twitter', 'shopbuilder' ),
				],
				'linkedin'  => [
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 878 1024"><path d="M199.429 357.143v566.286h-188.571v-566.286h188.571zM211.429 182.286q0.571 41.714-28.857 69.714t-77.429 28h-1.143q-46.857 0-75.429-28t-28.571-69.714q0-42.286 29.429-70t76.857-27.714 76 27.714 29.143 70zM877.714 598.857v324.571h-188v-302.857q0-60-23.143-94t-72.286-34q-36 0-60.286 19.714t-36.286 48.857q-6.286 17.143-6.286 46.286v316h-188q1.143-228 1.143-369.714t-0.571-169.143l-0.571-27.429h188v82.286h-1.143q11.429-18.286 23.429-32t32.286-29.714 49.714-24.857 65.429-8.857q97.714 0 157.143 64.857t59.429 190z"></path></svg>',
					'name' => esc_html__( 'Linkedin', 'shopbuilder' ),
				],
				'instagram' => [
					'icon' => '<svg viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
                                <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/>
                                </svg>',
					'name' => esc_html__( 'Instagram', 'shopbuilder' ),
				],
				'tiktok'    => [
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20"><path d="M448 209.9a210.1 210.1 0 0 1 -122.8-39.3V349.4A162.6 162.6 0 1 1 185 188.3V278.2a74.6 74.6 0 1 0 52.2 71.2V0l88 0a121.2 121.2 0 0 0 1.9 22.2h0A122.2 122.2 0 0 0 381 102.4a121.4 121.4 0 0 0 67 20.1z"/></svg>',
					'name' => esc_html__( 'Tiktok', 'shopbuilder' ),
				],
				'youtube'   => [
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="22" height="24"><path d="M549.7 124.1c-6.3-23.7-24.8-42.3-48.3-48.6C458.8 64 288 64 288 64S117.2 64 74.6 75.5c-23.5 6.3-42 24.9-48.3 48.6-11.4 42.9-11.4 132.3-11.4 132.3s0 89.4 11.4 132.3c6.3 23.7 24.8 41.5 48.3 47.8C117.2 448 288 448 288 448s170.8 0 213.4-11.5c23.5-6.3 42-24.2 48.3-47.8 11.4-42.9 11.4-132.3 11.4-132.3s0-89.4-11.4-132.3zm-317.5 213.5V175.2l142.7 81.2-142.7 81.2z"/></svg>',
					'name' => esc_html__( 'Youtube', 'shopbuilder' ),
				],
				'skype'     => [
					'icon' => '<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="24px" viewBox="0 0 24 24" enable-background="new 0 0 24 24" xml:space="preserve" class="eapps-social-share-buttons-item-icon"> <path d="M23.016,13.971c0.111-0.638,0.173-1.293,0.173-1.963 c0-6.213-5.014-11.249-11.199-11.249c-0.704,0-1.393,0.068-2.061,0.193C8.939,0.348,7.779,0,6.536,0C2.926,0,0,2.939,0,6.565 c0,1.264,0.357,2.443,0.973,3.445c-0.116,0.649-0.18,1.316-0.18,1.999c0,6.212,5.014,11.25,11.198,11.25 c0.719,0,1.419-0.071,2.099-0.201C15.075,23.656,16.229,24,17.465,24C21.074,24,24,21.061,24,17.435 C24,16.163,23.639,14.976,23.016,13.971z M12.386,19.88c-3.19,0-6.395-1.453-6.378-3.953c0.005-0.754,0.565-1.446,1.312-1.446 c1.877,0,1.86,2.803,4.85,2.803c2.098,0,2.814-1.15,2.814-1.95c0-2.894-9.068-1.12-9.068-6.563c0-2.945,2.409-4.977,6.196-4.753 c3.61,0.213,5.727,1.808,5.932,3.299c0.102,0.973-0.543,1.731-1.662,1.731c-1.633,0-1.8-2.188-4.613-2.188 c-1.269,0-2.341,0.53-2.341,1.679c0,2.402,9.014,1.008,9.014,6.295C18.441,17.882,16.012,19.88,12.386,19.88z"></path> </svg>',
					'name' => esc_html__( 'Skype', 'shopbuilder' ),
				],
				'behance'   => [
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20"><path d="M155.3 318.4c17.2 0 31.2-6.1 31.2-25.4c0-19.7-11.7-27.4-30.3-27.5h-46v52.9h45.1zm-5.4-129.6H110.3v44.8H153c15.1 0 25.8-6.6 25.8-22.9c0-17.7-13.7-21.9-28.9-21.9zm129.5 74.8h62.2c-1.7-18.5-11.3-29.7-30.5-29.7c-18.3 0-30.5 11.4-31.7 29.7zM384 32H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64zM349.5 185H271.7V166.1h77.8V185zM193.7 243.7c23.6 6.7 35 27.5 35 51.6c0 39-32.7 55.7-67.6 55.9H68v-192h90.5c32.9 0 61.4 9.3 61.4 47.5c0 19.3-9 28.8-26.2 37zm118.7-38.6c43.5 0 67.6 34.3 67.6 75.4c0 1.6-.1 3.3-.2 5c0 .8-.1 1.5-.1 2.2H279.5c0 22.2 11.7 35.3 34.1 35.3c11.6 0 26.5-6.2 30.2-18.1h33.7c-10.4 31.9-31.9 46.8-65.1 46.8c-43.8 0-71.1-29.7-71.1-73c0-41.8 28.7-73.6 71.1-73.6z"/></svg>',
					'name' => esc_html__( 'Behance', 'shopbuilder' ),
				],
				'pinterest' => [
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 731 1024"><path d="M0 341.143q0-61.714 21.429-116.286t59.143-95.143 86.857-70.286 105.714-44.571 115.429-14.857q90.286 0 168 38t126.286 110.571 48.571 164q0 54.857-10.857 107.429t-34.286 101.143-57.143 85.429-82.857 58.857-108 22q-38.857 0-77.143-18.286t-54.857-50.286q-5.714 22.286-16 64.286t-13.429 54.286-11.714 40.571-14.857 40.571-18.286 35.714-26.286 44.286-35.429 49.429l-8 2.857-5.143-5.714q-8.571-89.714-8.571-107.429 0-52.571 12.286-118t38-164.286 29.714-116q-18.286-37.143-18.286-96.571 0-47.429 29.714-89.143t75.429-41.714q34.857 0 54.286 23.143t19.429 58.571q0 37.714-25.143 109.143t-25.143 106.857q0 36 25.714 59.714t62.286 23.714q31.429 0 58.286-14.286t44.857-38.857 32-54.286 21.714-63.143 11.429-63.429 3.714-56.857q0-98.857-62.571-154t-163.143-55.143q-114.286 0-190.857 74t-76.571 187.714q0 25.143 7.143 48.571t15.429 37.143 15.429 26 7.143 17.429q0 16-8.571 41.714t-21.143 25.714q-1.143 0-9.714-1.714-29.143-8.571-51.714-32t-34.857-54-18.571-61.714-6.286-60.857z"></path></svg>',
					'name' => esc_html__( 'Pinterest', 'shopbuilder' ),
				],
				'whatsapp'  => [
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16"> <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/> </svg>',
					'name' => esc_html__( 'Whatsapp', 'shopbuilder' ),
				],
				'dribble'   => [
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="M86.6 64l85.2 85.2C194.5 121.7 208 86.4 208 48c0-14.7-2-28.9-5.7-42.4C158.6 15 119 35.5 86.6 64zM64 86.6C35.5 119 15 158.6 5.6 202.3C19.1 206 33.3 208 48 208c38.4 0 73.7-13.5 101.3-36.1L64 86.6zM256 0c-7.3 0-14.6 .3-21.8 .9C238 16 240 31.8 240 48c0 47.3-17.1 90.5-45.4 124L256 233.4 425.4 64C380.2 24.2 320.9 0 256 0zM48 240c-16.2 0-32-2-47.1-5.8C.3 241.4 0 248.7 0 256c0 64.9 24.2 124.2 64 169.4L233.4 256 172 194.6C138.5 222.9 95.3 240 48 240zm463.1 37.8c.6-7.2 .9-14.5 .9-21.8c0-64.9-24.2-124.2-64-169.4L278.6 256 340 317.4c33.4-28.3 76.7-45.4 124-45.4c16.2 0 32 2 47.1 5.8zm-4.7 31.9C492.9 306 478.7 304 464 304c-38.4 0-73.7 13.5-101.3 36.1L448 425.4c28.5-32.3 49.1-71.9 58.4-115.7zM340.1 362.7C317.5 390.3 304 425.6 304 464c0 14.7 2 28.9 5.7 42.4C353.4 497 393 476.5 425.4 448l-85.2-85.2zM317.4 340L256 278.6 86.6 448c45.1 39.8 104.4 64 169.4 64c7.3 0 14.6-.3 21.8-.9C274 496 272 480.2 272 464c0-47.3 17.1-90.5 45.4-124z"/></svg>',
					'name' => esc_html__( 'Dribble', 'shopbuilder' ),
				],
				'reddit'    => [
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"><path d="M324,256a36,36,0,1,0,36,36A36,36,0,0,0,324,256Z"/><circle cx="188" cy="292" r="36" transform="translate(-97.43 94.17) rotate(-22.5)"/><path d="M496,253.77c0-31.19-25.14-56.56-56-56.56a55.72,55.72,0,0,0-35.61,12.86c-35-23.77-80.78-38.32-129.65-41.27l22-79L363.15,103c1.9,26.48,24,47.49,50.65,47.49,28,0,50.78-23,50.78-51.21S441,48,413,48c-19.53,0-36.31,11.19-44.85,28.77l-90-17.89L247.05,168.4l-4.63.13c-50.63,2.21-98.34,16.93-134.77,41.53A55.38,55.38,0,0,0,72,197.21c-30.89,0-56,25.37-56,56.56a56.43,56.43,0,0,0,28.11,49.06,98.65,98.65,0,0,0-.89,13.34c.11,39.74,22.49,77,63,105C146.36,448.77,199.51,464,256,464s109.76-15.23,149.83-42.89c40.53-28,62.85-65.27,62.85-105.06a109.32,109.32,0,0,0-.84-13.3A56.32,56.32,0,0,0,496,253.77ZM414,75a24,24,0,1,1-24,24A24,24,0,0,1,414,75ZM42.72,253.77a29.6,29.6,0,0,1,29.42-29.71,29,29,0,0,1,13.62,3.43c-15.5,14.41-26.93,30.41-34.07,47.68A30.23,30.23,0,0,1,42.72,253.77ZM390.82,399c-35.74,24.59-83.6,38.14-134.77,38.14S157,423.61,121.29,399c-33-22.79-51.24-52.26-51.24-83A78.5,78.5,0,0,1,75,288.72c5.68-15.74,16.16-30.48,31.15-43.79a155.17,155.17,0,0,1,14.76-11.53l.3-.21,0,0,.24-.17c35.72-24.52,83.52-38,134.61-38s98.9,13.51,134.62,38l.23.17.34.25A156.57,156.57,0,0,1,406,244.92c15,13.32,25.48,28.05,31.16,43.81a85.44,85.44,0,0,1,4.31,17.67,77.29,77.29,0,0,1,.6,9.65C442.06,346.77,423.86,376.24,390.82,399Zm69.6-123.92c-7.13-17.28-18.56-33.29-34.07-47.72A29.09,29.09,0,0,1,440,224a29.59,29.59,0,0,1,29.41,29.71A30.07,30.07,0,0,1,460.42,275.1Z"/><path d="M323.23,362.22c-.25.25-25.56,26.07-67.15,26.27-42-.2-66.28-25.23-67.31-26.27h0a4.14,4.14,0,0,0-5.83,0l-13.7,13.47a4.15,4.15,0,0,0,0,5.89h0c3.4,3.4,34.7,34.23,86.78,34.45,51.94-.22,83.38-31.05,86.78-34.45h0a4.16,4.16,0,0,0,0-5.9l-13.71-13.47a4.13,4.13,0,0,0-5.81,0Z"/></svg>',
					'name' => esc_html__( 'Reddit', 'shopbuilder' ),
				],

			]
		);
	}
}
