<?php
/**
 * Elementor Layout Fields Class.
 *
 * This class contains all the common fields for Layout tab.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Elementor Layout Fields Class.
 */
class LayoutFields {
	/**
	 * Tab name.
	 *
	 * @access private
	 * @static
	 *
	 * @var array
	 */
	private static $tab = 'layout';

	/**
	 * Grid Layout section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function grid_layout( $obj ) {
		$fields['layout_section'] = $obj->start_section(
			esc_html__( 'Presentation', 'shopbuilder' ),
			self::$tab
		);

		$fields['layout_note'] = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );

		$fields['layout'] = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::grid_layouts(),
			'default' => 'grid-layout1',
		];

		$fields = array_merge( $fields, self::structure( $obj ) );
		$fields = array_merge( $fields, self::action_btn_presets( $obj ) );

		$fields['layout_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elements/elementor/grid_layout_control', $fields, $obj );
	}

	/**
	 * List Layout section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function list_layout( $obj ) {
		$fields['layout_section'] = $obj->start_section(
			esc_html__( 'Layouts', 'shopbuilder' ),
			self::$tab
		);

		$fields['layout_note'] = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );

		$fields['layout'] = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::list_layouts(),
			'default' => 'list-layout1',
		];

		$fields = array_merge( $fields, self::structure( $obj ) );
		$fields = array_merge( $fields, self::action_btn_presets( $obj ) );

		unset( $fields['action_btn_preset'] );
		unset( $fields['action_btn_position'] );

		// List layouts squash horizontal image+text when narrowed; warn before users pick the wider counts.
		if ( isset( $fields['cols'] ) ) {
			$fields['cols']['description'] = esc_html__( 'Please select the number of columns to show per row. Note: more than 3 columns may break the List layout on smaller screens; higher counts (4-8) are intended for wider screens only.', 'shopbuilder' );
		}

		$fields['layout_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elements/elementor/list_layout_control', $fields, $obj );
	}

	/**
	 * Slider Layout section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function slider_layout( $obj ) {
		$fields['layout_section'] = $obj->start_section(
			esc_html__( 'Slider Layouts', 'shopbuilder' ),
			self::$tab
		);

		$fields['layout_note'] = $obj->el_heading( esc_html__( 'Predefined Layouts', 'shopbuilder' ) );

		$fields['layout'] = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::slider_layouts(),
			'default' => 'slider-layout1',
		];

		$fields = array_merge( $fields, self::structure_slider( $obj ) );
		$fields = array_merge( $fields, self::action_btn_presets( $obj ) );

		$fields['layout_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elements/elementor/slider_layout_control', $fields, $obj );
	}

	/**
	 * Category Layout section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function category_layout( $obj ) {
		$fields['layout_section'] = $obj->start_section(
			esc_html__( 'Layouts', 'shopbuilder' ),
			self::$tab
		);

		$fields['layout'] = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::category_layouts(),
			'default' => 'category-layout1',
		];

		$fields = array_merge( $fields, self::structure( $obj ) );

		if ( 'rtsb-product-categories-general' === $obj->rtsb_base ) {
			$fields['cols']['render_type'] = 'template';
		}

		$fields['cat_height'] = [
			'type'        => 'slider',
			'mode'        => 'responsive',
			'label'       => esc_html__( 'Element Height', 'shopbuilder' ),
			'size_units'  => [ 'px', '%' ],
			'range'       => [
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
			'description' => esc_html__( 'Please select the element height.', 'shopbuilder' ),
			'selectors'   => [
				$obj->selectors['category_multi_layout']['cat_height'] => 'height: {{SIZE}}{{UNIT}};',
			],
		];

		$fields['layout_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elements/elementor/cat_layout_control', $fields, $obj );
	}

	/**
	 * Category Layout section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function category_single_layout( $obj ) {
		$fields['layout_section'] = $obj->start_section(
			esc_html__( 'Layouts', 'shopbuilder' ),
			self::$tab
		);

		$fields['layout'] = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::single_category_layouts(),
			'default' => 'category-single-layout1',
		];

		$fields['cat_alignment'] = [
			'mode'      => 'responsive',
			'type'      => 'choose',
			'label'     => esc_html__( 'Element Alignment', 'shopbuilder' ),
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
			'selectors' => [
				$obj->selectors['category_single_layout']['cat_alignment']['text_align']      => 'text-align: {{VALUE}};',
				$obj->selectors['category_single_layout']['cat_alignment']['justify_content'] => 'justify-content: {{VALUE}};',
			],
		];

		$fields['cat_height'] = [
			'type'        => 'slider',
			'mode'        => 'responsive',
			'label'       => esc_html__( 'Element Height', 'shopbuilder' ),
			'size_units'  => [ 'px', '%' ],
			'range'       => [
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
			'description' => esc_html__( 'Please select the element height.', 'shopbuilder' ),
			'selectors'   => [
				$obj->selectors['category_single_layout']['cat_height'] => 'height: {{SIZE}}{{UNIT}};',
			],
		];

		$fields['layout_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elements/elementor/cat_layout_control', $fields, $obj );
	}

	/**
	 * Action button preset section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function action_btn_presets( $obj ) {
		$fields['action_btn_note'] = $obj->el_heading( esc_html__( 'Action Button Layouts', 'shopbuilder' ), 'before' );

		$fields['action_btn_preset'] = [
			'type'        => 'rtsb-image-selector',
			'description' => esc_html__( 'Please choose your preferred action buttons preset.', 'shopbuilder' ),
			'options'     => ControlHelper::action_btn_presets(),
			'default'     => 'preset1',
		];

		$fields['action_btn_position'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Action Buttons Position', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the action buttons position.', 'shopbuilder' ),
			'options'     => [
				'above' => esc_html__( 'Floating Above Image', 'shopbuilder' ),
				'after' => esc_html__( 'After Content', 'shopbuilder' ),
			],
			'default'     => 'above',
			'label_block' => true,
			'condition'   => [
				'action_btn_preset' => [ 'preset1', 'preset5' ],
				'layout!'           => [ 'grid-layout2', 'slider-layout2' ],
			],
		];

		$fields['action_btn_gap'] = [
			'type'        => 'slider',
			'mode'        => 'responsive',
			'label'       => esc_html__( 'Action Buttons Gap / Spacing (px)', 'shopbuilder' ),
			'size_units'  => [ 'px' ],
			'range'       => [
				'px' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'description' => esc_html__( 'Please select the action buttons gap in px.', 'shopbuilder' ),
			'selectors'   => [
				$obj->selectors['action_buttons']['action_btn_gap'] => 'gap: {{SIZE}}{{UNIT}} !important;',
			],
		];

		$fields['action_btn_alignment'] = [
			'type'        => 'choose',
			'label'       => esc_html__( 'Action Buttons Alignment', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the action buttons alignment.', 'shopbuilder' ),
			'options'     => [
				'flex-start' => [
					'title' => esc_html__( 'Left', 'shopbuilder' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center'     => [
					'title' => esc_html__( 'Center', 'shopbuilder' ),
					'icon'  => 'eicon-text-align-center',
				],
				'flex-end'   => [
					'title' => esc_html__( 'Right', 'shopbuilder' ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'toggle'      => true,
			'condition'   => [
				'action_btn_position' => [ 'after' ],
				'action_btn_preset'   => [ 'preset1' ],
			],
			'selectors'   => [
				$obj->selectors['action_buttons']['action_btn_alignment'] => 'justify-content: {{VALUE}};',
			],
		];

		$fields['action_btn_tooltip_position'] = [
			'label'       => esc_html__( 'Tooltip Position', 'shopbuilder' ),
			'type'        => 'choose',
			'description' => esc_html__( 'Please choose the tooltip position', 'shopbuilder' ),
			'options'     => [
				'top'    => [
					'title' => esc_html__( 'Top', 'shopbuilder' ),
					'icon'  => ' eicon-arrow-up',
				],
				'right'  => [
					'title' => esc_html__( 'Right', 'shopbuilder' ),
					'icon'  => ' eicon-arrow-right',
				],
				'bottom' => [
					'title' => esc_html__( 'Bottom', 'shopbuilder' ),
					'icon'  => ' eicon-arrow-down',
				],
				'left'   => [
					'title' => esc_html__( 'Left', 'shopbuilder' ),
					'icon'  => ' eicon-arrow-left',
				],
			],
			'default'     => 'top',
		];

		return $fields;
	}

	/**
	 * Columns section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function structure( $obj ) {
		$fields['cols'] = [
			'type'           => 'select2',
			'mode'           => 'responsive',
			'label'          => esc_html__( 'Number of Columns', 'shopbuilder' ),
			'description'    => esc_html__( 'Please select the number of columns to show per row.', 'shopbuilder' ),
			'options'        => ControlHelper::layout_columns(),
			'label_block'    => true,
			'render_type'    => 'template',
			'default'        => '0',
			'tablet_default' => '2',
			'mobile_default' => '1',
			'required'       => true,
			'separator'      => rtsb()->has_pro() ? 'default' : 'default elementor-control-separator-after',
			'selectors'      => [
				$obj->selectors['columns']['cols']         => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
				$obj->selectors['columns']['masonry']      => '--rtsb-masonry-columns: {{VALUE}};',
				$obj->selectors['columns']['list_masonry'] => '--rtsb-masonry-list-columns: {{VALUE}};',
			],
		];

		$fields['grid_style'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Layout Grid Style', 'shopbuilder' ),
			'options'     => [
				'even'    => esc_html__( 'Default', 'shopbuilder' ),
				'masonry' => esc_html__( 'Masonry', 'shopbuilder' ),
				'equal'   => esc_html__( 'Equal Height', 'shopbuilder' ),
			],
			'label_block' => true,
			'default'     => 'even',
			'description' => esc_html__( 'Please select the layout grid style.', 'shopbuilder' ),
			'classes'     => $obj->pro_class(),
		];

		$fields['image_width'] = [
			'type'        => 'slider',
			'mode'        => 'responsive',
			'label'       => esc_html__( 'Image Width', 'shopbuilder' ),
			'size_units'  => [ 'px','%' ],
			'range'       => [
				'%' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'default'     => [
				'unit' => '%',
				'size' => 32,
			],
			'description' => esc_html__( 'Please select the image width in %.', 'shopbuilder' ),
			'separator'   => rtsb()->has_pro() ? 'default' : 'before',
			'selectors'   => [
				$obj->selectors['columns']['image_width']['image'] => 'flex-basis: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
			],
			'condition'   => [ 'layout' => [ 'list-layout1', 'list-layout2', 'list-layout3', 'list-layout4','list-layout5','list-layout6','list-layout7' ] ],
		];

		$fields['image_gap'] = [
			'type'        => 'slider',
			'mode'        => 'responsive',
			'label'       => esc_html__( 'Image Gap (px)', 'shopbuilder' ),
			'size_units'  => [ 'px' ],
			'range'       => [
				'px' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'default'     => [
				'unit' => 'px',
				'size' => 30,
			],
			'description' => esc_html__( 'Please select the image gap in px.', 'shopbuilder' ),
			'selectors'   => [
				$obj->selectors['columns']['image_gap'] => 'gap: {{SIZE}}{{UNIT}};',
			],
			'condition'   => [ 'layout' => [ 'list-layout1', 'list-layout2', 'list-layout3', 'list-layout4','list-layout5','list-layout6','list-layout7' ] ],
		];

		$fields['v_action_btn_width'] = [
			'type'        => 'slider',
			'mode'        => 'responsive',
			'label'       => esc_html__( 'Vertical Action Buttons Width (px)', 'shopbuilder' ),
			'size_units'  => [ 'px' ],
			'range'       => [
				'px' => [
					'min'  => 0,
					'max'  => 500,
					'step' => 1,
				],
			],
			'description' => esc_html__( 'Please select the vertical action buttons width in px.', 'shopbuilder' ),
			'selectors'   => [
				$obj->selectors['columns']['v_action_btn_width'] => 'flex-basis: {{SIZE}}{{UNIT}};',
			],
			'condition'   => [ 'layout' => [ 'list-layout3' ] ],
		];

		return apply_filters( 'rtsb/elements/elementor/columns_control', $fields, $obj );
	}

	/**
	 * Columns section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function structure_slider( $obj ) {
		$fields['cols'] = [
			'type'           => 'select2',
			'mode'           => 'responsive',
			'label'          => esc_html__( 'Number of Slides (Columns) Per View', 'shopbuilder' ),
			'description'    => esc_html__( 'Please select the number of slides to show per view.', 'shopbuilder' ),
			'options'        => ControlHelper::layout_columns(),
			'label_block'    => true,
			'default'        => '0',
			'tablet_default' => '2',
			'mobile_default' => '1',
			'required'       => true,
			'separator'      => 'default elementor-control-separator-after',
		];

		$fields['rows'] = [
			'type'           => 'select2',
			'label'          => esc_html__( 'Number of Product Rows', 'shopbuilder' ),
			'description'    => rtsb()->has_pro() ? esc_html__( 'Please select the number of slide rows. Slide Rows represents how many rows of slides will be displayed at once.', 'shopbuilder' ) : esc_html__( 'Please select the number of slide rows.', 'shopbuilder' ),
			'options'        => [
				1 => esc_html__( 'Layout Default (1 Row)', 'shopbuilder' ),
				2 => esc_html__( '2 Rows', 'shopbuilder' ),
				3 => esc_html__( '3 Rows', 'shopbuilder' ),
				4 => esc_html__( '4 Rows', 'shopbuilder' ),
				5 => esc_html__( '5 Rows', 'shopbuilder' ),
			],
			'label_block'    => true,
			'default'        => '1',
			'tablet_default' => '1',
			'mobile_default' => '1',
			'required'       => true,
			'classes'        => $obj->pro_class(),
		];

		$fields['cols_group'] = [
			'type'           => 'select2',
			'mode'           => 'responsive',
			'label'          => esc_html__( 'Number of Slides Per Group', 'shopbuilder' ),
			'description'    => rtsb()->has_pro() ? esc_html__( 'Please select the number of slides to show per group. Slides Per Group indicates how many slides will be transitioned at a time.', 'shopbuilder' ) : esc_html__( 'Please select the number of slides to show per group.', 'shopbuilder' ),
			'options'        => ControlHelper::layout_columns(),
			'label_block'    => true,
			'default'        => '0',
			'tablet_default' => '2',
			'mobile_default' => '1',
			'required'       => true,
			'classes'        => $obj->pro_class(),
		];

		$fields['grid_style'] = [
			'type'        => 'select',
			'label'       => esc_html__( 'Layout Grid Style', 'shopbuilder' ),
			'options'     => [
				'even' => esc_html__( 'Default', 'shopbuilder' ),
			],
			'label_block' => true,
			'default'     => 'even',
			'description' => esc_html__( 'Please select the layout grid style.', 'shopbuilder' ),
		];

		$fields['image_width'] = [
			'type'        => 'slider',
			'mode'        => 'responsive',
			'label'       => esc_html__( 'Image Width', 'shopbuilder' ),
			'size_units'  => [ 'px','%' ],
			'range'       => [
				'%' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'default'     => [
				'unit' => '%',
				'size' => 32,
			],
			'description' => esc_html__( 'Please select the image width in %.', 'shopbuilder' ),
			'selectors'   => [
				$obj->selectors['columns']['image_width']['image'] => 'flex-basis: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}} !important;',
			],
			'condition'   => [ 'layout' => [ 'slider-layout5' ] ],
		];

		$fields['image_gap'] = [
			'type'        => 'slider',
			'mode'        => 'responsive',
			'label'       => esc_html__( 'Image Gap (px)', 'shopbuilder' ),
			'size_units'  => [ 'px' ],
			'range'       => [
				'px' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'default'     => [
				'unit' => 'px',
				'size' => 30,
			],
			'description' => esc_html__( 'Please select the image gap in px.', 'shopbuilder' ),
			'selectors'   => [
				$obj->selectors['columns']['image_gap'] => 'gap: {{SIZE}}{{UNIT}};',
			],
			'condition'   => [ 'layout' => [ 'slider-layout5' ] ],
		];

		return apply_filters( 'rtsb/elements/elementor/columns_control', $fields, $obj );
	}

	/**
	 * Query section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function query( $obj ) {
		$fields['query_section'] = $obj->start_section(
			esc_html__( 'Query', 'shopbuilder' ),
			self::$tab
		);

		$fields['query_note'] = $obj->el_heading( esc_html__( 'Common Filters', 'shopbuilder' ) );

		$fields['include_posts'] = [
			'type'        => 'rt-select2',
			'label'       => esc_html__( 'Include Products', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the products to show. Leave it blank to include all products.', 'shopbuilder' ),
			'source_name' => 'post_type',
			'source_type' => 'product',
			'multiple'    => true,
			'label_block' => true,
		];

		$fields['exclude_posts'] = [
			'type'        => 'rt-select2',
			'label'       => esc_html__( 'Exclude Products', 'shopbuilder' ),
			'description' => esc_html__( 'Please select the products to show. Leave it blank to exclude none.', 'shopbuilder' ),
			'source_name' => 'post_type',
			'source_type' => 'product',
			'multiple'    => true,
			'label_block' => true,
		];

		$fields['posts_limit'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Products Limit', 'shopbuilder' ),
			'description' => esc_html__( 'The number of products to show. Set empty to show all products.', 'shopbuilder' ),
			'default'     => 12,
		];

		$fields['posts_offset'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Products Offset', 'shopbuilder' ),
			'description' => esc_html__( 'Number of products to skip.', 'shopbuilder' ),
		];

		$fields['category_note'] = $obj->el_heading( esc_html__( 'Taxonomy Filters', 'shopbuilder' ), 'before' );

		$fields['filter_categories'] = [
			'type'                 => 'rt-select2',
			'label'                => esc_html__( 'Filter By Categories', 'shopbuilder' ),
			'description'          => esc_html__( 'Select the categories you want to filter, Leave it blank for all categories.', 'shopbuilder' ),
			'source_name'          => 'taxonomy',
			'source_type'          => 'product_cat',
			'multiple'             => true,
			'label_block'          => true,
			'minimum_input_length' => 1,
		];

		$fields['filter_tags']   = [
			'type'                 => 'rt-select2',
			'label'                => esc_html__( 'Filter By Tags', 'shopbuilder' ),
			'description'          => esc_html__( 'Select the tags you want to filter, Leave it blank for all tags.', 'shopbuilder' ),
			'source_name'          => 'taxonomy',
			'source_type'          => 'product_tag',
			'multiple'             => true,
			'label_block'          => true,
			'minimum_input_length' => 1,
		];
		$fields['filter_brands'] = [
			'type'                 => 'rt-select2',
			'label'                => esc_html__( 'Filter By Brand', 'shopbuilder' ),
			'description'          => esc_html__( 'Select the tags you want to filter, Leave it blank for all brands.', 'shopbuilder' ),
			'source_name'          => 'taxonomy',
			'source_type'          => 'product_brand',
			'multiple'             => true,
			'label_block'          => true,
			'minimum_input_length' => 1,
		];

		$fields['filter_attributes'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Filter By Attributes', 'shopbuilder' ),
			'description' => esc_html__( 'Select the attributes you want to filter, Leave it blank for all attributes.', 'shopbuilder' ),
			'options'     => Fns::get_all_attributes(),
			'multiple'    => true,
			'label_block' => true,
		];

		$fields['tax_relation'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Taxonomy relation', 'shopbuilder' ),
			'options'     => [
				'OR'  => 'Show All Products (OR)',
				'AND' => 'Show Common Products (AND)',
			],
			'default'     => 'AND',
			'label_block' => true,
		];

		$fields['advanced_note'] = $obj->el_heading( esc_html__( 'Advanced Filters', 'shopbuilder' ), 'before' );

		$fields['filter_author'] = [
			'type'                 => 'rt-select2',
			'label'                => esc_html__( 'Filter By Author', 'shopbuilder' ),
			'description'          => esc_html__( 'Select the author you want to filter, Leave it blank for all author.', 'shopbuilder' ),
			'source_name'          => 'user',
			'multiple'             => true,
			'label_block'          => true,
			'minimum_input_length' => 1,
			'separator'            => rtsb()->has_pro() ? 'default' : 'default elementor-control-separator-after',
		];

		$fields['products_filter'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Display Products By:', 'shopbuilder' ),
			'options'     => ControlHelper::filter_products(),
			'default'     => rtsb()->has_pro() ? 'recent' : 'best-selling',
			'description' => esc_html__( 'Please select the conditional display of products.', 'shopbuilder' ),
			'label_block' => true,
			'classes'     => $obj->pro_class(),
		];

		$fields['sorting_note'] = $obj->el_heading( esc_html__( 'Sorting', 'shopbuilder' ), 'before' );

		$fields['posts_order_by'] = [
			'type'    => 'select2',
			'label'   => esc_html__( 'Order By', 'shopbuilder' ),
			'options' => ControlHelper::posts_order_by(),
			'default' => 'date',
		];

		if ( rtsb()->has_pro() ) {
			$fields['products_filter']['condition'] = [ 'ajax_filter_type' => [ 'taxonomy' ] ];
			$fields['posts_order_by']['condition']  = [
				'ajax_filter_type' => [ 'taxonomy' ],
				'products_filter'  => 'recent',
			];
		}

		$fields['posts_order'] = [
			'type'    => 'select2',
			'label'   => esc_html__( 'Order', 'shopbuilder' ),
			'options' => ControlHelper::posts_order(),
			'default' => 'DESC',
		];

		$fields['query_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elements/elementor/query_control', $fields, $obj );
	}

	/**
	 * Query section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function additional_query( $obj ) {
		$fields['query_section'] = $obj->start_section(
			esc_html__( 'Query', 'shopbuilder' ),
			self::$tab
		);

		$fields['query_note'] = $obj->el_heading( esc_html__( 'Common Filters', 'shopbuilder' ) );

		$fields['posts_limit'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Products Limit', 'shopbuilder' ),
			'description' => esc_html__( 'The number of products to show. Set empty to show all products.', 'shopbuilder' ),
			'default'     => 12,
		];

		$fields['sorting_note'] = $obj->el_heading( esc_html__( 'Sorting', 'shopbuilder' ), 'before' );

		$fields['posts_order_by'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Order By', 'shopbuilder' ),
			'description' => esc_html__( 'Please choose to reorder products.', 'shopbuilder' ),
			'options'     => ControlHelper::posts_order_by(),
			'default'     => 'date',
		];

		$fields['posts_order'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Order', 'shopbuilder' ),
			'description' => esc_html__( 'Please choose to reorder products.', 'shopbuilder' ),
			'options'     => ControlHelper::posts_order(),
			'default'     => 'DESC',
		];

		$fields['query_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elements/elementor/query_control', $fields, $obj );
	}

	/**
	 * Category Query section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function cat_single_query( $obj ) {
		$all_tag_list = Fns::get_tax_list();
		unset( $all_tag_list['product_attr'] );
		$fields['query_section'] = $obj->start_section(
			esc_html__( 'Query', 'shopbuilder' ),
			self::$tab
		);

		$fields['select_source'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Select Taxonomy', 'shopbuilder' ),
			'options'     => $all_tag_list,
			'description' => esc_html__( 'Please select the taxonomy.', 'shopbuilder' ),
			'label_block' => true,
			'default'     => 'product_cat',
		];

		$fields['select_cat'] = [
			'type'                 => 'rt-select2',
			'label'                => esc_html__( 'Choose Category', 'shopbuilder' ),
			'source_name'          => 'taxonomy',
			'source_type'          => 'product_cat',
			'description'          => esc_html__( 'Please select the category to show.', 'shopbuilder' ),
			'label_block'          => true,
			'minimum_input_length' => 1,
			'condition'            => [
				'select_source' => [ 'product_cat' ],
			],
		];

		$fields['select_tag']   = [
			'type'                 => 'rt-select2',
			'label'                => esc_html__( 'Choose Tag', 'shopbuilder' ),
			'source_name'          => 'taxonomy',
			'source_type'          => 'product_tag',
			'description'          => esc_html__( 'Please select the tag to show.', 'shopbuilder' ),
			'label_block'          => true,
			'minimum_input_length' => 1,
			'condition'            => [
				'select_source' => [ 'product_tag' ],
			],
		];
		$fields['select_brand'] = [
			'type'                 => 'rt-select2',
			'label'                => esc_html__( 'Choose Brand', 'shopbuilder' ),
			'source_name'          => 'taxonomy',
			'source_type'          => 'product_brand',
			'description'          => esc_html__( 'Please select the brand to show.', 'shopbuilder' ),
			'label_block'          => true,
			'minimum_input_length' => 1,
			'condition'            => [
				'select_source' => [ 'product_brand' ],
			],
		];

		$fields['query_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elements/elementor/cat_single_query_control', $fields, $obj );
	}

	/**
	 * Category Query section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function cat_query( $obj ) {
		$fields['query_section'] = $obj->start_section(
			esc_html__( 'Query', 'shopbuilder' ),
			self::$tab
		);

		$fields['query_note'] = $obj->el_heading( esc_html__( 'Filter', 'shopbuilder' ) );

		$fields['display_cat_by'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Display Categories By:', 'shopbuilder' ),
			'options'     => Fns::get_cat_list(),
			'description' => esc_html__( 'Please select what categories will be displayed.', 'shopbuilder' ),
			'label_block' => true,
			'default'     => 'all',
		];

		$fields['select_parent_cat'] = [
			'type'                 => 'rt-select2',
			'label'                => esc_html__( 'Select Parent Category', 'shopbuilder' ),
			'source_name'          => 'taxonomy',
			'source_type'          => 'product_cat',
			'minimum_input_length' => 1,
			'description'          => esc_html__( 'Please select the parent category to show.', 'shopbuilder' ),
			'label_block'          => true,
			'default'              => Fns::get_terms( 'product_cat', true, true ),
			'condition'            => [ 'display_cat_by' => [ 'specific_parent' ] ],
		];

		$fields['include_cats'] = [
			'type'                 => 'rt-select2',
			'label'                => esc_html__( 'Select Categories', 'shopbuilder' ),
			'source_name'          => 'taxonomy',
			'source_type'          => 'product_cat',
			'description'          => esc_html__( 'Please select the categories to show.', 'shopbuilder' ),
			'label_block'          => true,
			'multiple'             => true,
			'minimum_input_length' => 1,
			'condition'            => [ 'display_cat_by' => [ 'selection' ] ],
		];

		$fields['include_cat_ids'] = [
			'type'        => 'text',
			'label'       => esc_html__( 'Enter Category IDs', 'shopbuilder' ),
			'description' => esc_html__( 'Please enter the category IDs separated by comma.', 'shopbuilder' ),
			'label_block' => true,
			'condition'   => [ 'display_cat_by' => [ 'cat_ids' ] ],
		];

		$fields['exclude_cats'] = [
			'type'                 => 'rt-select2',
			'label'                => esc_html__( 'Exclude Categories', 'shopbuilder' ),
			'source_name'          => 'taxonomy',
			'source_type'          => 'product_cat',
			'description'          => esc_html__( 'Please select the categories to exclude. Leave it blank to exclude none.', 'shopbuilder' ),
			'multiple'             => true,
			'minimum_input_length' => 1,
			'label_block'          => true,
			'condition'            => [ 'display_cat_by' => [ 'all' ] ],
		];

		$fields['cats_limit'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Categories Limit', 'shopbuilder' ),
			'description' => esc_html__( 'The number of categories to show. Set empty to show all categories.', 'shopbuilder' ),
			'condition'   => [ 'display_cat_by!' => [ 'selection' ] ],
		];

		$fields['show_top_level_cats'] = [
			'type'        => 'switch',
			'label'       => __( 'Show only Top Level <br /> Sub-Categories?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to display only first level sub-categories.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'condition'   => [ 'display_cat_by' => [ 'specific_parent' ] ],
		];

		$fields['show_empty'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Empty Categories?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to display empty categories.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['show_uncategorized'] = [
			'type'        => 'switch',
			'label'       => __( 'Show Uncategorized <br />(Or Default Category)?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to display uncategorized.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'condition'   => [ 'display_cat_by' => [ 'all' ] ],
		];

		$fields['show_subcats'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Sub-Categories?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to display subcategories.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'condition'   => [ 'display_cat_by' => [ 'all' ] ],
		];

		$fields['sorting_note'] = $obj->el_heading( esc_html__( 'Sorting', 'shopbuilder' ), 'before' );

		$fields['cats_order_by'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Order By', 'shopbuilder' ),
			'description' => esc_html__( 'Please choose to reorder categories.', 'shopbuilder' ),
			'options'     => ControlHelper::cats_order_by(),
			'default'     => 'name',
		];

		$fields['cats_order'] = [
			'type'        => 'select2',
			'label'       => esc_html__( 'Order', 'shopbuilder' ),
			'description' => esc_html__( 'Please choose to reorder categories.', 'shopbuilder' ),
			'options'     => ControlHelper::posts_order(),
			'default'     => 'ASC',
		];

		$fields['query_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elements/elementor/cat_query_control', $fields, $obj );
	}

	/**
	 * Pagination section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function pagination( $obj ) {
		$fields['pagination_section'] = $obj->start_section(
			esc_html__( 'Pagination', 'shopbuilder' ),
			self::$tab,
			[],
			rtsb()->has_pro() ? [ 'tax_filter!' => [ 'yes' ] ] : []
		);

		$fields['show_pagination'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Pagination?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable pagination.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
		];

		$fields['pagination_per_page'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Number of Posts Per Page', 'shopbuilder' ),
			'default'     => 8,
			'description' => esc_html__( 'Please enter the number of products per page to show.', 'shopbuilder' ),
			'condition'   => [ 'show_pagination' => [ 'yes' ] ],
		];

		$pagination_description = esc_html__( 'Please choose the pagination type', 'shopbuilder' );

		if ( ! rtsb()->has_pro() ) {
			$upgrade_link            = '<span class="elementor-pro-notice"><a target="_blank" href="' . esc_url( rtsb()->pro_version_link() ) . '">Upgrade to PRO</a> to unlock Load More and Ajax Pagination.</span>';
			$pagination_description .= $upgrade_link;
		}

		$fields['pagination_type'] = [
			'type'        => 'select',
			'label'       => esc_html__( 'Pagination Type', 'shopbuilder' ),
			'description' => $pagination_description,
			'options'     => ControlHelper::pagination_options(),
			'default'     => 'pagination',
			'label_block' => true,
			'condition'   => [ 'show_pagination' => [ 'yes' ] ],
		];

		$fields['pagination_visible_range'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Page Number Range', 'shopbuilder' ),
			'default'     => 5,
			'min'         => 1,
			'max'         => 20,
			'description' => esc_html__( 'Number of page links to show on each side of the current page. Lower values truncate long paginations.', 'shopbuilder' ),
			'condition'   => [
				'show_pagination' => [ 'yes' ],
				'pagination_type' => [ 'pagination', 'pagination_ajax' ],
			],
		];

		$fields['pagination_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elementor/pagination_control', $fields, $obj );
	}

	/**
	 * Filter section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return array
	 */
	public static function filter( $obj ) {
		$fields['filter_section'] = $obj->start_section(
			esc_html__( 'Ajax Tab Filters', 'shopbuilder' ),
			self::$tab,
		);

		$fields['filter_note'] = [
			'type'            => 'html',
			'raw'             => '',
			'content_classes' => 'elementor-panel-heading-title',
		];

		$fields['tax_filter'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Ajax Tab Filter Buttons?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable Ajax tab filter buttons.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'separator'   => $obj->pro_class(),
			'classes'     => $obj->pro_class(),
		];

		$fields['enable_ajax_scroll'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Scroll To Top?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to scroll the page to the top when an Ajax tab filter or Ajax pagination loads products. Switch off to keep the current scroll position.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'separator'   => $obj->pro_class(),
			'classes'     => $obj->pro_class(),
		];

		$fields['filter_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elementor/filter_control', $fields, $obj );
	}
}
