<?php
/**
 * Controls class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Archive\ProductFilters;

use Elementor\Controls_Manager;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Class Controls
 *
 * @package RadiusTheme\SB\Elementor\Widgets\Archive\ProductFilters
 */
class Controls {
	/**
	 * Widget Field
	 *
	 * @param object $widget Widget object.
	 *
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		return self::filters( $widget )
			   + self::filter_settings( $widget )
			   + self::search_settings( $widget )
			   + self::apply_filters_btn_settings( $widget )
			   + self::filter_scrolling( $widget )
			   + self::reset_btn_settings( $widget )
			   + self::filter_styles( $widget );
	}

	/**
	 * Filters repeater section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function filters( $obj ) {
		$fields['filters_section'] = $obj->start_section(
			esc_html__( 'Add Filters', 'shopbuilder' ),
			'layout'
		);

		$fields['add_filter_note'] = $obj->el_heading(
			esc_html__( 'Filters', 'shopbuilder' ),
			'default'
		);

		$filter_options = [
			'product_cat'   => esc_html__( 'Categories', 'shopbuilder' ),
			'product_brand' => esc_html__( 'Brands', 'shopbuilder' ),
			'product_tag'   => esc_html__( 'Tags', 'shopbuilder' ),
			'product_attr'  => esc_html__( 'Attributes', 'shopbuilder' ),
			'rating_filter' => esc_html__( 'Rating', 'shopbuilder' ),
			'price_filter'  => esc_html__( 'Price', 'shopbuilder' ),
			'sale_filter'   => esc_html__( 'On Sale', 'shopbuilder' ),
		];

		$input_options = [
			'checkbox' => esc_html__( 'Checkbox', 'shopbuilder' ),
			'radio'    => esc_html__( 'Radio', 'shopbuilder' ),
		];

		if ( function_exists( 'rtwpvs' ) ) {
			$input_options['color']  = esc_html__( 'Color', 'shopbuilder' );
			$input_options['button'] = esc_html__( 'Button', 'shopbuilder' );
			$input_options['image']  = esc_html__( 'Image', 'shopbuilder' );
		}

		$attributes = Fns::get_all_attributes_name();

		if ( empty( $attributes ) ) {
			$attributes = [
				'nothing' => esc_html__( 'No attributes found', 'shopbuilder' ),
			];
		}

		$fields['filter_types'] = [
			'type'        => 'repeater',
			'mode'        => 'repeater',
			'label'       => esc_html__( 'Please add your product filters below', 'shopbuilder' ),
			'separator'   => 'default',
			'title_field' => '{{{ filter_title }}}',
			'fields'      => [
				'filter_title'       => [
					'label'       => esc_html__( 'Filter Title', 'shopbuilder' ),
					'type'        => 'text',
					'description' => esc_html__( 'Enter the filter title.', 'shopbuilder' ),
					'label_block' => true,
					'default'     => esc_html__( 'Filter by Categories', 'shopbuilder' ),
				],
				'filter_items'       => [
					'label'       => esc_html__( 'Select Filter Query', 'shopbuilder' ),
					'type'        => 'select',
					'description' => esc_html__( 'Select the query you want to display.', 'shopbuilder' ),
					'options'     => $filter_options,
					'default'     => 'product_cat',
					'label_block' => true,
					'separator'   => 'before-short',
				],
				'filter_attr'        => [
					'label'       => esc_html__( 'Select Attribute', 'shopbuilder' ),
					'type'        => 'select',
					'description' => esc_html__( 'Select the product attribute.', 'shopbuilder' ),
					'options'     => $attributes,
					'default'     => array_key_first( $attributes ),
					'label_block' => true,
					'condition'   => [ 'filter_items' => 'product_attr' ],
				],
				'input_type'         => [
					'label'       => esc_html__( 'Input Type', 'shopbuilder' ),
					'type'        => 'select',
					'description' => esc_html__( 'Select the filter input type.', 'shopbuilder' ),
					'label_block' => true,
					'options'     => [
						'checkbox' => esc_html__( 'Checkbox', 'shopbuilder' ),
						'radio'    => esc_html__( 'Radio', 'shopbuilder' ),
					],
					'default'     => 'checkbox',
					'condition'   => [
						'filter_items!' => [ 'product_attr', 'rating_filter', 'price_filter' ],
					],
				],
				'input_type_all'     => [
					'label'       => esc_html__( 'Input Type', 'shopbuilder' ),
					'type'        => 'select',
					'description' => esc_html__( 'Select the filter input type.', 'shopbuilder' ),
					'label_block' => true,
					'options'     => $input_options,
					'default'     => 'checkbox',
					'condition'   => [ 'filter_items' => 'product_attr' ],
				],
				'rating_icon'        => [
					'type'        => 'icons',
					'label'       => esc_html__( 'Choose Rating Icon', 'shopbuilder' ),
					'description' => esc_html__( 'Please choose the rating icon.', 'shopbuilder' ),
					'default'     => [
						'value'   => 'fas fa-star',
						'library' => 'fa-solid',
					],
					'condition'   => [ 'filter_items' => [ 'rating_filter' ] ],
				],
				'onsale_title'       => [
					'label'       => esc_html__( 'On Sale Input Label', 'shopbuilder' ),
					'type'        => 'text',
					'description' => esc_html__( 'Enter the on sale filter input title.', 'shopbuilder' ),
					'label_block' => true,
					'default'     => esc_html__( 'On Sale', 'shopbuilder' ),
					'condition'   => [ 'filter_items' => 'sale_filter' ],
					'separator'   => 'before-short',
				],
				'regular_title'      => [
					'label'       => esc_html__( 'Regular Input Label', 'shopbuilder' ),
					'type'        => 'text',
					'description' => esc_html__( 'Enter the on regular filter input title.', 'shopbuilder' ),
					'label_block' => true,
					'default'     => esc_html__( 'Regular', 'shopbuilder' ),
					'condition'   => [ 'filter_items' => 'sale_filter' ],
				],
				'show_label'         => [
					'type'        => 'switch',
					'label'       => esc_html__( 'Show Term Label?', 'shopbuilder' ),
					'description' => esc_html__( 'Switch on to display term label.', 'shopbuilder' ),
					'label_on'    => esc_html__( 'On', 'shopbuilder' ),
					'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
					'separator'   => 'before-short',
					'condition'   => [
						'filter_items'   => [ 'product_attr' ],
						'input_type_all' => [ 'color' ],
					],
				],
				'show_empty'         => [
					'type'        => 'switch',
					'label'       => esc_html__( 'Show Empty Terms?', 'shopbuilder' ),
					'description' => esc_html__( 'Switch on to display empty terms.', 'shopbuilder' ),
					'label_on'    => esc_html__( 'On', 'shopbuilder' ),
					'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
					'separator'   => 'before-short',
					'condition'   => [ 'filter_items' => [ 'product_cat', 'product_brand', 'product_tag', 'product_attr' ] ],
				],
				'include_child_cats' => [
					'label'       => esc_html__( 'Show Child?', 'shopbuilder' ),
					'type'        => 'switch',
					'description' => esc_html__( 'Switch on to include child.', 'shopbuilder' ),
					'label_on'    => esc_html__( 'On', 'shopbuilder' ),
					'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
					'default'     => 'yes',
					'condition'   => [ 'filter_items' => [ 'product_cat','product_brand' ] ],
				],
				'show_tooltips'      => [
					'label'       => esc_html__( 'Show Tooltips?', 'shopbuilder' ),
					'type'        => 'switch',
					'description' => esc_html__( 'Switch on to enable tooltips.', 'shopbuilder' ),
					'label_on'    => esc_html__( 'On', 'shopbuilder' ),
					'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
					'default'     => 'yes',
					'condition'   => [
						'filter_items'   => [ 'product_attr' ],
						'input_type_all' => [ 'color', 'button', 'image' ],
					],
				],
				'min_price_label'    => [
					'type'        => 'text',
					'label'       => esc_html__( 'Min Price Label', 'shopbuilder' ),
					'description' => esc_html__( 'Please enter the min price label.', 'shopbuilder' ),
					'label_block' => true,
					'default'     => esc_html__( 'Min Price:', 'shopbuilder' ),
					'condition'   => [ 'filter_items' => [ 'price_filter' ] ],
				],
				'max_price_label'    => [
					'type'        => 'text',
					'label'       => esc_html__( 'Max Price Label', 'shopbuilder' ),
					'description' => esc_html__( 'Please enter the max price label.', 'shopbuilder' ),
					'label_block' => true,
					'default'     => esc_html__( 'Max Price:', 'shopbuilder' ),
					'condition'   => [ 'filter_items' => [ 'price_filter' ] ],
				],

			],
			'default'     => [
				[
					'filter_title'       => esc_html__( 'Filter by Categories', 'shopbuilder' ),
					'filter_items'       => 'product_cat',
					'input_type'         => 'checkbox',
					'multiple_selection' => 'yes',
					'include_child_cats' => 'yes',
				],
				[
					'filter_title' => esc_html__( 'Filter by Tags', 'shopbuilder' ),
					'filter_items' => 'product_tag',
					'input_type'   => 'radio',
				],
				[
					'filter_title' => esc_html__( 'Filter by Prices', 'shopbuilder' ),
					'filter_items' => 'price_filter',
					'price_label'  => esc_html__( 'Price', 'shopbuilder' ),
				],
				[
					'filter_title'       => esc_html__( 'Filter by Ratings', 'shopbuilder' ),
					'filter_items'       => 'rating_filter',
					'rating_icon'        => [
						'value'   => 'fas fa-star',
						'library' => 'fa-solid',
					],
					'multiple_selection' => 'yes',
				],
				[
					'filter_title'  => esc_html__( 'Filter by Sale', 'shopbuilder' ),
					'filter_items'  => 'sale_filter',
					'onsale_title'  => esc_html__( 'On Sale', 'shopbuilder' ),
					'regular_title' => esc_html__( 'Regular', 'shopbuilder' ),
				],
			],
		];

		$fields['filters_section_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Filters Layout section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function filter_settings( $obj ) {
		$fields['filter_settings_section'] = $obj->start_section(
			esc_html__( 'Filter Settings', 'shopbuilder' ),
			'settings'
		);

		$fields['show_filter_header'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Filter Header Text?', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to filter header text at the beginning.', 'shopbuilder' ),
		];

		$fields['filter_header_text']   = [
			'type'        => 'text',
			'label'       => esc_html__( 'Filter Header Text', 'shopbuilder' ),
			'description' => esc_html__( 'Enter the filter header text.', 'shopbuilder' ),
			'default'     => esc_html__( 'Filter', 'shopbuilder' ),
			'label_block' => true,
			'condition'   => [ 'show_filter_header' => 'yes' ],
		];
		$fields['filter_mobile_toggle'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Toggle Button in Mobile?', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable filter toggle button in mobile.', 'shopbuilder' ),
		];

		$fields['filter_mobile_toggle_text'] = [
			'type'        => 'text',
			'label'       => esc_html__( 'Mobile Filter Button Text', 'shopbuilder' ),
			'description' => esc_html__( 'Enter the mobile toggle filter button text.', 'shopbuilder' ),
			'label_block' => true,
			'default'     => esc_html__( 'Click to Filter', 'shopbuilder' ),
			'condition'   => [ 'filter_mobile_toggle' => 'yes' ],
		];

		$fields['filter_settings_section_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Search section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function search_settings( $obj ) {
		$fields['search_settings_section'] = $obj->start_section(
			esc_html__( 'Search Form Settings', 'shopbuilder' ),
			'settings'
		);

		$fields['search_form'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Top Search Form?', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable search form at the top.', 'shopbuilder' ),
		];

		$fields['search_title']       = [
			'label'       => esc_html__( 'Search Title', 'shopbuilder' ),
			'type'        => 'text',
			'description' => esc_html__( 'Enter the search title.', 'shopbuilder' ),
			'label_block' => true,
			'condition'   => [ 'search_form' => [ 'yes' ] ],
			'separator'   => 'before-short',
		];
		$fields['search_placeholder'] = [
			'label'       => esc_html__( 'Search Form Placeholder', 'shopbuilder' ),
			'type'        => 'text',
			'description' => esc_html__( 'Enter the search form placeholder text.', 'shopbuilder' ),
			'label_block' => true,
			'default'     => esc_html__( 'Search Products...', 'shopbuilder' ),
			'condition'   => [ 'search_form' => [ 'yes' ] ],
			'separator'   => 'before-short',
		];
		$fields['search_title_icon']  = [
			'type'        => 'icons',
			'label'       => esc_html__( 'Choose Search Button Icon', 'shopbuilder' ),
			'description' => esc_html__( 'Please choose the search button icon.', 'shopbuilder' ),
			'default'     => [
				'value'   => 'rtsb-icon rtsb-icon-search',
				'library' => 'rtsb-fonts',
			],
			'condition'   => [
				'search_form' => [ 'yes' ],
			],
		];

		$fields['search_settings_section_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Toggle button section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function apply_filters_btn_settings( $obj ) {
		$fields['apply_filters_btn_settings_section'] = $obj->start_section(
			esc_html__( 'Apply Filters Button', 'shopbuilder' ),
			'settings'
		);
		$fields['show_filter_btn']                    = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Apply Filter Button?', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show apply filters button.', 'shopbuilder' ),
			'separator'   => 'default',
			'default'     => 'yes',
		];
		$fields['apply_filters_btn_text']             = [
			'label'       => esc_html__( 'Apply Filters Button Text', 'shopbuilder' ),
			'type'        => 'text',
			'description' => esc_html__( 'Enter the apply filters button text.', 'shopbuilder' ),
			'label_block' => true,
			'default'     => esc_html__( 'Apply Filters', 'shopbuilder' ),
			'separator'   => 'default',
			'condition'   => [ 'show_filter_btn' => 'yes' ],
		];

		$fields['apply_filters_btn_icon'] = [
			'type'        => 'icons',
			'label'       => esc_html__( 'Choose Button Icon', 'shopbuilder' ),
			'description' => esc_html__( 'Please choose the button icon.', 'shopbuilder' ),
			'default'     => [
				'value'   => 'fas fa-filter',
				'library' => 'fa-solid',
			],
			'condition'   => [ 'show_filter_btn' => 'yes' ],
			'separator'   => 'default',
		];

		$fields['apply_filters_btn_settings_section_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Filters scrolling section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function filter_scrolling( $obj ) {
		$fields['filter_scrolling_section'] = $obj->start_section(
			esc_html__( 'Filter Scrolling', 'shopbuilder' ),
			'settings',
			[],
		);

		$fields['enable_scroll'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Items Scrolling?', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable filter items scrolling (Only works with checkbox and radio).', 'shopbuilder' ),
			'separator'   => 'default',
		];

		$fields['scroll_height'] = [
			'type'        => 'slider',
			'label'       => esc_html__( 'Scroll Height', 'shopbuilder' ),
			'description' => esc_html__( 'Please specify the filter height for scrolling.', 'shopbuilder' ),
			'size_units'  => [ 'px' ],
			'range'       => [
				'px' => [
					'min'  => 0,
					'max'  => 600,
					'step' => 1,
				],
			],
			'default'     => [
				'unit' => 'px',
				'size' => 300,
			],
			'separator'   => 'default',
			'condition'   => [
				'enable_scroll' => [ 'yes' ],
			],
		];

		$fields['filter_scrolling_section_end'] = $obj->end_section();

		return $fields;
	}

	/**
	 * Reset button section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function reset_btn_settings( $obj ) {
		$fields['reset_btn_settings_section'] = $obj->start_section(
			esc_html__( 'Reset Button', 'shopbuilder' ),
			'settings'
		);

		$fields['reset_btn'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Reset Filter Button?', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable reset filter button.', 'shopbuilder' ),
			'separator'   => 'default',
			'default'     => 'yes',
		];

		$fields['reset_btn_behavior'] = [
			'type'        => 'select',
			'label'       => esc_html__( 'Reset Button Behavior', 'shopbuilder' ),
			'options'     => [
				'show' => esc_html__( 'Always Show', 'shopbuilder' ),
				'hide' => esc_html__( 'Only When Filter Applied', 'shopbuilder' ),
			],
			'label_block' => true,
			'description' => esc_html__( 'Specify the reset button behavior.', 'shopbuilder' ),
			'default'     => 'hide',
			'separator'   => 'default',
			'condition'   => [ 'reset_btn' => [ 'yes' ] ],
		];

		$fields['reset_btn_text'] = [
			'label'       => esc_html__( 'Reset Filter Button Text', 'shopbuilder' ),
			'type'        => 'text',
			'description' => esc_html__( 'Enter the reset filter button text.', 'shopbuilder' ),
			'label_block' => true,
			'default'     => esc_html__( 'Reset Filter', 'shopbuilder' ),
			'separator'   => 'default',
			'condition'   => [ 'reset_btn' => [ 'yes' ] ],
		];

		$fields['reset_btn_settings_section_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Filters styles section
	 *
	 * @param object $obj Reference object.
	 * @return array
	 */
	public static function filter_styles( $obj ) {
		return self::filter_header( $obj )
			+ self::filter_title( $obj )
			+ self::search_form( $obj )
			+ self::filter_items( $obj )
			+ self::rating( $obj )
			+ self::apply_btn( $obj )
			+ self::reset_btn( $obj )
			+ self::mobile_toggle_btn( $obj );
	}
	/**
	 * Filter Title Section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function filter_header( $obj ) {
		$css_selectors = $obj->selectors['filter_header'];
		$title         = esc_html__( 'Filter Header', 'shopbuilder' );
		$condition     = [
			'show_filter_header' => [ 'yes' ],
		];
		$selectors     = [
			'typography' => $css_selectors['typography'],
			'color'      => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'bg_color'   => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'border'     => $css_selectors['border'],
			'padding'    => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'margin'     => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'filter_header', $title, $obj, $condition, $selectors );

		unset(
			$fields['filter_header_alignment'],
			$fields['filter_header_color_tabs'],
			$fields['filter_header_color_tab'],
			$fields['filter_header_color_tab_end'],
			$fields['filter_header_hover_color_tab'],
			$fields['filter_header_hover_color'],
			$fields['filter_header_hover_bg_color'],
			$fields['filter_header_hover_color_tab_end'],
			$fields['filter_header_color_tabs_end'],
			$fields['filter_header_border_hover_color']
		);

		return $fields;
	}
	/**
	 * Filter Title Section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function filter_title( $obj ) {
		$css_selectors = $obj->selectors['filter_title'];
		$title         = esc_html__( 'Filter Title', 'shopbuilder' );

		$selectors = [
			'typography' => $css_selectors['typography'],
			'color'      => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'bg_color'   => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'border'     => $css_selectors['border'],
			'padding'    => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
			'margin'     => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'filter_title', $title, $obj, [], $selectors );

		unset(
			$fields['filter_title_alignment'],
			$fields['filter_title_color_tabs'],
			$fields['filter_title_color_tab'],
			$fields['filter_title_color_tab_end'],
			$fields['filter_title_hover_color_tab'],
			$fields['filter_title_hover_color'],
			$fields['filter_title_hover_bg_color'],
			$fields['filter_title_hover_color_tab_end'],
			$fields['filter_title_color_tabs_end'],
			$fields['filter_title_border_hover_color']
		);
		return $fields;
	}
	/**
	 * Search Form Style Section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function search_form( $obj ) {
		$css_selectors = $obj->selectors['search_styles'];
		$title         = esc_html__( 'Search Form', 'shopbuilder' );
		$condition     = [ 'search_form' => 'yes' ];
		$selectors     = [
			'typography'                => $css_selectors['typography'],
			'icon_typography'           => $css_selectors['icon_typography'],
			'search_input_height'       => [ $css_selectors['search_input_height'] => 'height: {{SIZE}}{{UNIT}};' ],
			'search_button_width'       => [ $css_selectors['search_button_width'] => 'width: {{SIZE}}{{UNIT}};' ],
			'search_icon_width'         => [ $css_selectors['search_icon_width'] => 'width: {{SIZE}}{{UNIT}};' ],
			'search_icon_height'        => [ $css_selectors['search_icon_height'] => 'height: {{SIZE}}{{UNIT}};' ],
			'color'                     => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'bg_color'                  => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'button_color'              => [ $css_selectors['button_color'] => 'color: {{VALUE}};' ],
			'button_bg_color'           => [ $css_selectors['button_bg_color'] => 'background-color: {{VALUE}};' ],
			'placeholder_color'         => [ $css_selectors['placeholder_color'] => 'color: {{VALUE}};' ],
			'hover_color'               => [ $css_selectors['hover_color'] => 'color: {{VALUE}};' ],
			'hover_bg_color'            => [ $css_selectors['hover_bg_color'] => 'background-color: {{VALUE}};' ],
			'hover_button_color'        => [ $css_selectors['hover_button_color'] => 'color: {{VALUE}};' ],
			'hover_button_bg_color'     => [ $css_selectors['hover_button_bg_color'] => 'background-color: {{VALUE}};' ],
			'border'                    => $css_selectors['border'],
			'button_border'             => $css_selectors['button_border'],
			'button_border_hover_color' => [ $css_selectors['button_border_hover_color'] => 'border-color: {{VALUE}};' ],
			'input_border_radius'       => [ $css_selectors['input_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'button_border_radius'      => [ $css_selectors['button_border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'padding'                   => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'button_margin'             => [ $css_selectors['button_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'wrapper_margin'            => [ $css_selectors['wrapper_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		];

		$fields = ControlHelper::general_elementor_style( 'search_styles', $title, $obj, $condition, $selectors );

		unset(
			$fields['search_styles_alignment'],
			$fields['search_styles_border_hover_color'],
			$fields['search_styles_margin']
		);

		$fields['rtsb_el_search_styles_typography']['label'] = esc_html__( 'Input Typography', 'shopbuilder' );
		$fields['search_styles_color']['label']              = esc_html__( 'Input Color', 'shopbuilder' );
		$fields['search_styles_bg_color']['label']           = esc_html__( 'Input Background Color', 'shopbuilder' );
		$fields['search_styles_hover_color']['label']        = esc_html__( 'Input Hover Color', 'shopbuilder' );
		$fields['search_styles_hover_bg_color']['label']     = esc_html__( 'Input Hover Background Color', 'shopbuilder' );
		$fields['search_styles_padding']['label']            = esc_html__( 'Input Padding', 'shopbuilder' );

		$fields['rtsb_el_search_styles_border']['fields_options'] = [
			'border' => [
				'label'       => esc_html__( 'Input Border Type', 'shopbuilder' ),
				'label_block' => true,
			],
			'width'  => [
				'label' => esc_html__( 'Input Border Width', 'shopbuilder' ),
			],
			'color'  => [
				'label' => esc_html__( 'Input Border Color', 'shopbuilder' ),
			],
		];

		$extra_controls = [];

		$extra_controls['rtsb_el_search_icon_typography'] = [
			'mode'     => 'group',
			'label'    => esc_html__( 'Icon Typography', 'shopbuilder' ),
			'type'     => 'typography',
			'selector' => ! empty( $selectors['icon_typography'] ) ? $selectors['icon_typography'] : [],
		];

		$extra_controls['search_dimension_note'] = $obj->el_heading(
			esc_html__( 'Dimensions', 'shopbuilder' ),
			'before-short'
		);

		$extra_controls['search_input_height'] = [
			'type'       => 'slider',
			'label'      => esc_html__( 'Input Height', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'selectors'  => $selectors['search_input_height'],
		];

		$extra_controls['search_button_width'] = [
			'type'       => 'slider',
			'label'      => esc_html__( 'Button Width', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 300,
					'step' => 1,
				],
			],
			'selectors'  => $selectors['search_button_width'],
		];

		$extra_controls['search_icon_width'] = [
			'type'       => 'slider',
			'label'      => esc_html__( 'Icon Width (for SVG Icons)', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 300,
					'step' => 1,
				],
			],
			'selectors'  => $selectors['search_icon_width'],
		];

		$extra_controls['search_icon_height'] = [
			'type'       => 'slider',
			'label'      => esc_html__( 'Icon Height (for SVG Icons)', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'selectors'  => $selectors['search_icon_height'],

		];

		$fields = Fns::insert_controls( 'rtsb_el_search_styles_typography', $fields, $extra_controls, true );

		$extra_controls['search_styles_button_color'] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Button/Icon Color', 'shopbuilder' ),
			'selectors' => $selectors['button_color'],
		];

		$extra_controls['search_styles_button_bg_color'] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Button/Icon Background Color', 'shopbuilder' ),
			'selectors' => $selectors['button_bg_color'],
		];

		$extra_controls['search_styles_placeholder_color'] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Placeholder Color', 'shopbuilder' ),
			'selectors' => $selectors['placeholder_color'],
		];

		$fields = Fns::insert_controls( 'search_styles_bg_color', $fields, $extra_controls, true );

		$extra_controls['search_styles_hover_button_color'] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Button/Icon Hover Color', 'shopbuilder' ),
			'selectors' => $selectors['hover_button_color'],
		];

		$extra_controls['search_styles_hover_button_bg_color'] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Button/Icon Hover Background Color', 'shopbuilder' ),
			'selectors' => $selectors['hover_button_bg_color'],
		];

		$fields = Fns::insert_controls( 'search_styles_hover_bg_color', $fields, $extra_controls, true );

		$extra_controls['search_input_radius'] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Input Border Radius', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['input_border_radius'],
		];

		$extra_controls['rtsb_el_search_styles_button_border'] = [
			'mode'           => 'group',
			'type'           => 'border',
			'label'          => esc_html__( 'Border', 'shopbuilder' ),
			'selector'       => $selectors['button_border'],
			'fields_options' => [
				'border' => [
					'label'       => esc_html__( 'Button Border Type', 'shopbuilder' ),
					'label_block' => true,
				],
				'width'  => [
					'label' => esc_html__( 'Button Border Width', 'shopbuilder' ),
				],
				'color'  => [
					'label' => esc_html__( 'Button Border Color', 'shopbuilder' ),
				],
			],
			'separator'      => 'before-short',
		];

		$extra_controls['search_styles_button_border_hover_color'] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Button Hover Border Color', 'shopbuilder' ),
			'condition' => [ 'rtsb_el_search_styles_button_border_border!' => [ '' ] ],
			'selectors' => $selectors['button_border_hover_color'],
		];

		$extra_controls['search_button_radius'] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Button Border Radius', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['button_border_radius'],
		];

		$fields = Fns::insert_controls( 'search_styles_spacing_note', $fields, $extra_controls );

		$extra_controls['search_styles_button_margin'] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Button Margin', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['button_margin'],
		];

		$extra_controls['search_styles_wrapper_margin'] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Wrapper Margin', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['wrapper_margin'],
		];

		return Fns::insert_controls( 'search_styles_padding', $fields, $extra_controls, true );
	}
	/**
	 * Rating Style Section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function rating( $obj ) {
		$css_selectors = $obj->selectors['rating'];
		$selectors     = [
			'typography'            => $css_selectors['typography'],
			'rating_color'          => [ $css_selectors['rating_color'] => 'color: {{VALUE}};' ],
			'count_position'        => [ $css_selectors['count_position'] => 'padding-left: {{SIZE}}{{UNIT}};' ],
			'rating_active_color'   => [ $css_selectors['rating_active_color'] => 'color: {{VALUE}};' ],
			'rating_selected_color' => [ $css_selectors['rating_selected_color'] => 'color: {{VALUE}};' ],
			'rating_margin'         => [ $css_selectors['rating_margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'rating_padding'        => [ $css_selectors['rating_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		];

		$fields['rating_section'] = $obj->start_section(
			esc_html__( 'Rating Styles', 'shopbuilder' ),
			'style'
		);

		$fields['rtsb_el_rating_typography'] = [
			'mode'     => 'group',
			'label'    => esc_html__( 'Icon Typography', 'shopbuilder' ),
			'type'     => 'typography',
			'exclude'  => [ 'font_family', 'font_weight', 'text_transform', 'text_decoration', 'font_style', 'word_spacing', 'line_height' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'selector' => $selectors['typography'],
		];
		$fields['rating_count_position']     = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Count Text Alignment', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 500,
					'step' => 1,
				],
			],
			'separator'  => 'default',
			'selectors'  => $selectors['count_position'],
		];
		$fields['rating_color']              = [
			'type'      => 'color',
			'label'     => esc_html__( 'Star Inactive Color', 'shopbuilder' ),
			'separator' => 'default',
			'selectors' => $selectors['rating_color'],
		];

		$fields['rating_active_color'] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Star Active Color', 'shopbuilder' ),
			'separator' => 'default',
			'selectors' => $selectors['rating_active_color'],
		];

		$fields['rating_selected_color'] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Star Selected Color', 'shopbuilder' ),
			'separator' => 'default',
			'selectors' => $selectors['rating_selected_color'],
		];

		$fields['rating_spacing_note'] = $obj->el_heading( esc_html__( 'Spacing', 'shopbuilder' ), 'before' );

		$fields['rating_padding'] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Padding', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'separator'  => 'default',
			'selectors'  => $selectors['rating_padding'],
		];

		$fields['rating_margin'] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Margin', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['rating_margin'],
		];

		$fields['rating_section_end'] = $obj->end_section();

		return $fields;
	}
	/**
	 * Apply Filters Button Style Section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function apply_btn( $obj ) {
		$css_selectors = $obj->selectors['apply_btn'];
		$title         = esc_html__( 'Apply Filters Button Styles', 'shopbuilder' );
		$selectors     = [
			'typography'         => $css_selectors['typography'],
			'apply_btn_width'    => [ $css_selectors['apply_btn_width'] => 'width: {{SIZE}}{{UNIT}};' ],
			'icon_display'       => [ $css_selectors['icon_display'] => 'display: {{VALUE}};' ],
			'apply_icon_size'    => [ $css_selectors['apply_icon_size'] => 'font-size: {{SIZE}}{{UNIT}};' ],
			'color'              => [ $css_selectors['color'] => 'color: {{VALUE}} !important;' ],
			'icon_color'         => [ $css_selectors['icon_color'] => 'color: {{VALUE}} !important;' ],
			'bg_color'           => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}} !important;' ],
			'hover_color'        => [ $css_selectors['hover_color'] => 'color: {{VALUE}} !important;' ],
			'hover_icon_color'   => [ $css_selectors['hover_icon_color'] => 'color: {{VALUE}} !important;' ],
			'hover_bg_color'     => [ $css_selectors['hover_bg_color'] => 'background-color: {{VALUE}} !important;' ],
			'border'             => $css_selectors['border'],
			'border_hover_color' => [ $css_selectors['border_hover_color'] => 'border-color: {{VALUE}};' ],
			'border_radius'      => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'padding'            => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'margin'             => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		];

		$fields = ControlHelper::general_elementor_style(
			'apply_btn',
			$title,
			$obj,
			[],
			$selectors
		);

		unset( $fields['apply_btn_alignment'] );

		$extra_controls = [];

		$extra_controls['apply_btn_width'] = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Button Width', 'shopbuilder' ),
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 1500,
					'step' => 1,
				],
				'%'  => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'separator'  => 'default',
			'selectors'  => $selectors['apply_btn_width'],
		];

		$extra_controls['apply_icon_display'] = [
			'type'      => 'choose',
			'mode'      => 'responsive',
			'label'     => esc_html__( 'Icon Display', 'shopbuilder' ),
			'options'   => [
				'inline-block' => [
					'title' => esc_html__( 'Show', 'shopbuilder' ),
					'icon'  => 'eicon-check',
				],
				'none'         => [
					'title' => esc_html__( 'Hide', 'shopbuilder' ),
					'icon'  => 'eicon-close',
				],
			],
			'default'   => 'none',
			'selectors' => $selectors['icon_display'],
			'separator' => 'default',
		];

		$extra_controls['apply_icon_size'] = [
			'type'       => 'slider',
			'label'      => esc_html__( 'Icon Size', 'shopbuilder' ),
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'selectors'  => $selectors['apply_icon_size'],
			'separator'  => 'default',
		];

		$fields = Fns::insert_controls( 'apply_btn_color_note', $fields, $extra_controls );

		$extra_controls['apply_icon_color'] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Icon Color', 'shopbuilder' ),
			'selectors' => $selectors['icon_color'],
			'separator' => 'default',
		];

		$fields = Fns::insert_controls( 'apply_btn_color', $fields, $extra_controls, true );

		$extra_controls['apply_hover_icon_color'] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Hover Icon Color', 'shopbuilder' ),
			'selectors' => $selectors['hover_icon_color'],
			'separator' => 'default',
		];

		$fields = Fns::insert_controls( 'apply_btn_hover_color', $fields, $extra_controls, true );

		$extra_controls['apply_btn_radius'] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['border_radius'],
		];

		return Fns::insert_controls( 'apply_btn_border_hover_color', $fields, $extra_controls, true );
	}
	/**
	 * Reset Button Style Section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function reset_btn( $obj ) {
		$css_selectors = $obj->selectors['reset_btn'];
		$title         = esc_html__( 'Reset Button Styles', 'shopbuilder' );
		$selectors     = [
			'typography'         => $css_selectors['typography'],
			'btn_width'          => [ $css_selectors['btn_width'] => 'width: {{SIZE}}{{UNIT}};' ],
			'color'              => [ $css_selectors['color'] => 'color: {{VALUE}} !important;' ],
			'bg_color'           => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'hover_color'        => [ $css_selectors['hover_color'] => 'color: {{VALUE}} !important;' ],
			'hover_bg_color'     => [ $css_selectors['hover_bg_color'] => 'background-color: {{VALUE}};' ],
			'border'             => $css_selectors['border'],
			'border_hover_color' => [ $css_selectors['border_hover_color'] => 'border-color: {{VALUE}};' ],
			'border_radius'      => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'padding'            => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'margin'             => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		];

		$fields = ControlHelper::general_elementor_style( 'reset_btn', $title, $obj, [ 'reset_btn' => 'yes' ], $selectors );
		unset( $fields['reset_btn_alignment'] );
		$extra_controls = [];

		$extra_controls['btn_width'] = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Button Width', 'shopbuilder' ),
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 1500,
					'step' => 1,
				],
				'%'  => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'separator'  => 'default',
			'selectors'  => $selectors['btn_width'],
		];

		$fields = Fns::insert_controls( 'reset_btn_color_note', $fields, $extra_controls );

		$extra_controls['reset_btn_radius'] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['border_radius'],
		];

		return Fns::insert_controls( 'reset_btn_border_hover_color', $fields, $extra_controls, true );
	}

	/**
	 * Reset Button Style Section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function mobile_toggle_btn( $obj ) {
		$css_selectors = $obj->selectors['mobile_toggle_btn'];
		$title         = esc_html__( 'Mobile Toggle Button', 'shopbuilder' );
		$selectors     = [
			'typography'         => $css_selectors['typography'],
			'btn_width'          => [ $css_selectors['btn_width'] => 'width: {{SIZE}}{{UNIT}};' ],
			'color'              => [ $css_selectors['color'] => 'color: {{VALUE}} !important;' ],
			'bg_color'           => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'hover_color'        => [ $css_selectors['hover_color'] => 'color: {{VALUE}} !important;' ],
			'hover_bg_color'     => [ $css_selectors['hover_bg_color'] => 'background-color: {{VALUE}};' ],
			'border'             => $css_selectors['border'],
			'border_hover_color' => [ $css_selectors['border_hover_color'] => 'border-color: {{VALUE}};' ],
			'border_radius'      => [ $css_selectors['border_radius'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'padding'            => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'margin'             => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		];

		$fields = ControlHelper::general_elementor_style( 'mobile_toggle_btn', $title, $obj, [ 'filter_mobile_toggle' => 'yes' ], $selectors );
		unset( $fields['mobile_toggle_btn_alignment'] );
		$extra_controls = [];

		$extra_controls['btn_width'] = [
			'type'       => 'slider',
			'mode'       => 'responsive',
			'label'      => esc_html__( 'Button Width', 'shopbuilder' ),
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 1500,
					'step' => 1,
				],
				'%'  => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'separator'  => 'default',
			'selectors'  => $selectors['btn_width'],
		];

		$fields = Fns::insert_controls( 'mobile_toggle_btn_color_note', $fields, $extra_controls );

		$extra_controls['mobile_toggle_btn_radius'] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['border_radius'],
		];

		return Fns::insert_controls( 'mobile_toggle_btn_border_hover_color', $fields, $extra_controls, true );
	}

	/**
	 * Filter Items Style Section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function filter_items( $obj ) {
		$css_selectors = $obj->selectors['filter_items'];
		$title         = esc_html__( 'Filter Items', 'shopbuilder' );
		$selectors     = [
			'typography'                => $css_selectors['typography'],
			'count_display'             => [ $css_selectors['count_display'] => 'display: {{VALUE}};' ],
			'color'                     => [ $css_selectors['color'] => 'color: {{VALUE}};' ],
			'input_fields_color'        => [ $css_selectors['input_fields_color'] => 'border-color: {{VALUE}};' ],
			'active_input_fields_color' => [ $css_selectors['active_input_fields_color'] => 'border-color: {{VALUE}};' ],
			'bg_color'                  => [ $css_selectors['bg_color'] => 'background-color: {{VALUE}};' ],
			'count_color'               => [ $css_selectors['count_color'] => 'color: {{VALUE}};' ],
			'border'                    => $css_selectors['border'],
			'padding'                   => [ $css_selectors['padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'margin'                    => [ $css_selectors['margin'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'wrapper_padding'           => [ $css_selectors['wrapper_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'wrapper_margin'            => [ $css_selectors['wrapper_margin'] => 'margin-top: {{TOP}}{{UNIT}} !important; margin-bottom: {{BOTTOM}}{{UNIT}} !important;' ],
		];

		$fields = ControlHelper::general_elementor_style( 'filter_items', $title, $obj, [], $selectors );

		unset(
			$fields['filter_items_alignment'],
			$fields['filter_items_border_hover_color'],
			$fields['filter_items_color_tabs'],
			$fields['filter_items_color_tab'],
			$fields['filter_items_hover_color'],
			$fields['filter_items_hover_bg_color'],
			$fields['filter_items_hover_bg_color'],
			$fields['filter_items_color_tab_end'],
			$fields['filter_items_hover_color_tab'],
			$fields['filter_items_hover_color_tab_end'],
			$fields['filter_items_color_tabs_end'],
		);

		$fields['filter_items_typo_note']['raw']    = '<h3 class="rtsb-elementor-group-heading">' . esc_html__( 'Label Typography', 'shopbuilder' ) . '</h3>';
		$fields['filter_items_color_note']['raw']   = '<h3 class="rtsb-elementor-group-heading">' . esc_html__( 'Label Colors', 'shopbuilder' ) . '</h3>';
		$fields['filter_items_border_note']['raw']  = '<h3 class="rtsb-elementor-group-heading">' . esc_html__( 'Item Border', 'shopbuilder' ) . '</h3>';
		$fields['filter_items_spacing_note']['raw'] = '<h3 class="rtsb-elementor-group-heading">' . esc_html__( 'Item Spacing', 'shopbuilder' ) . '</h3>';

		$extra_controls = [];

		$extra_controls['count_note'] = $obj->el_heading( esc_html__( 'Product Count', 'shopbuilder' ), 'before' );

		$extra_controls['count_display'] = [
			'mode'        => 'responsive',
			'type'        => 'choose',
			'label'       => esc_html__( 'Show Product Count', 'shopbuilder' ),
			'description' => esc_html__( 'If none is selected, the product count will be shown by default.', 'shopbuilder' ),
			'toggle'      => false,
			'options'     => [
				'block' => [
					'title' => esc_html__( 'Show', 'shopbuilder' ),
					'icon'  => 'eicon-check',
				],
				'none'  => [
					'title' => esc_html__( 'Hide', 'shopbuilder' ),
					'icon'  => 'eicon-close',
				],
			],
			'separator'   => 'default',
			'default'     => 'block',
			'selectors'   => ! empty( $selectors['count_display'] ) ? $selectors['count_display'] : [],
		];

		$fields = Fns::insert_controls( 'filter_items_color_note', $fields, $extra_controls );

		$extra_controls['count_color']               = [
			'type'      => 'color',
			'label'     => esc_html__( 'Count Color', 'shopbuilder' ),
			'separator' => 'default',
			'selectors' => $selectors['count_color'],
		];
		$extra_controls['input_fields_color']        = [
			'type'      => 'color',
			'label'     => esc_html__( 'Fields Color', 'shopbuilder' ),
			'separator' => 'default',
			'selectors' => $selectors['input_fields_color'],
		];
		$extra_controls['active_input_fields_color'] = [
			'type'      => 'color',
			'label'     => esc_html__( 'Active Fields Color', 'shopbuilder' ),
			'separator' => 'default',
			'selectors' => $selectors['active_input_fields_color'],
		];

		$fields = Fns::insert_controls( 'filter_items_bg_color', $fields, $extra_controls, true );

		$extra_controls['wrapper_padding'] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Wrapper Padding', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['wrapper_padding'],
		];

		$extra_controls['wrapper_margin'] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'label'      => esc_html__( 'Wrapper Margin', 'shopbuilder' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => $selectors['wrapper_margin'],
		];

		$fields = Fns::insert_controls( 'filter_items_margin', $fields, $extra_controls, true );

		return $fields;
	}
}
