<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Archive;

use RadiusTheme\SB\Elementor\Helper\RenderHelpers;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Render\Render;
use RadiusTheme\SB\Elementor\Widgets\Controls;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ProductsArchiveCustom extends ElementorWidgetBase {
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
		$this->rtsb_name = esc_html__( 'Product Archive (Custom)', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-products-archive-custom';
		$this->pro_tab   = 'layout';
		parent::__construct( $data, $args );
	}


	/**
	 * Controls for layout tab
	 *
	 * @return $this
	 */
	protected function layout_tab() {
		$layout                                        = Controls\LayoutFields::grid_layout( $this );
		$pagination                                    = Controls\LayoutFields::pagination( $this );
		$layout['layout_note']                         = $this->el_heading( esc_html__( 'Grid Layouts', 'shopbuilder' ) );
		$layout['cols']['label']                       = esc_html__( 'Number of Grid Columns', 'shopbuilder' );
		$layout['layout_note']['separator']            = 'before';
		$layout['action_btn_preset']['description']   .= '<span style="color: #93003c;">' . esc_html__( ' Note: This is only for grid view.', 'shopbuilder' ) . '</span>';
		$layout['action_btn_position']['description'] .= '<br /><span style="color: #93003c;">' . esc_html__( ' Note: This is only for grid view.', 'shopbuilder' ) . '</span>';
		$layout['action_btn_tooltip_position']['label']       = esc_html__( 'Grid Tooltip Position', 'shopbuilder' );
		$layout['action_btn_tooltip_position']['description'] = esc_html__( 'Please choose the grid view tooltip position', 'shopbuilder' );

		unset(
			$pagination['pagination_section']['condition'],
			$pagination['pagination_per_page'],
			$layout['image_width'],
			$layout['image_gap'],
			$layout['layout']['options']['grid-layout9'],
			$layout['full_product_col'],
			$layout['products_per_col'],
			$layout['full_product_height'],
		);

		// Add products per page at the top of the layout section.
		$per_page_field['posts_per_page'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Products Per Page', 'shopbuilder' ),
			'min'         => 1,
			'max'         => 500,
			'default'     => RenderHelpers::get_products_per_page(),
			'description' => esc_html__( 'Set the number of products to display per page.', 'shopbuilder' ),
		];
		$layout                           = Fns::insert_controls( 'layout_section', $layout, $per_page_field, true );

		$new_fields = [
			'view_mode' => [
				'label'     => esc_html__( 'Default View', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => [
					'grid' => [
						'title' => esc_html__( 'Grid', 'shopbuilder' ),
						'icon'  => 'eicon-posts-grid',
					],
					'list' => [
						'title' => esc_html__( 'List', 'shopbuilder' ),
						'icon'  => 'eicon-post-list',
					],
				],
				'default'   => 'grid',
				'separator' => 'default',
			],
		];

		$layout = Fns::insert_controls( 'layout_section', $layout, $new_fields, true );
		$layout = Fns::insert_controls( 'cols', $layout, $this->list_layout(), true );

		if ( rtsb()->has_pro() ) {
			$pagination = Fns::insert_controls( 'pagination_type', $pagination, [], true );
		}

		$new_fields['list_action_btn_tooltip_position'] = [
			'label'       => esc_html__( 'List Tooltip Position', 'shopbuilder' ),
			'type'        => 'choose',
			'description' => esc_html__( 'Please choose the list view tooltip position', 'shopbuilder' ),
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

		$layout = Fns::insert_controls( 'action_btn_tooltip_position', $layout, $new_fields, true );

		$sections = apply_filters(
			'rtsb/elements/elementor/grid_layout_tab',
			array_merge(
				$layout,
				$pagination,
			),
			$this
		);

		$this->control_fields = array_merge( $this->control_fields, $sections );

		return $this;
	}

	/**
	 * @return array
	 */
	public function list_layout() {
		$fields                     = [];
		$fields['List_layout_note'] = $this->el_heading( esc_html__( 'List Layouts', 'shopbuilder' ) );
		$fields['list_layout']      = [
			'type'    => 'rtsb-image-selector',
			'options' => ControlHelper::list_layouts(),
			'default' => 'list-layout1',
		];

		$fields['list_cols'] = [
			'type'           => 'select2',
			'mode'           => 'responsive',
			'label'          => esc_html__( 'Number of List Columns', 'shopbuilder' ),
			'description'    => esc_html__( 'Please select the number of columns to show per row for list layout.', 'shopbuilder' ),
			'options'        => ControlHelper::layout_columns(),
			'label_block'    => true,
			'render_type'    => 'template',
			'default'        => '1',
			'tablet_default' => '2',
			'mobile_default' => '1',
			'required'       => true,
			'selectors'      => [
				$this->selectors['columns']['list_cols'] => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
				$this->selectors['columns']['list_masonry'] => '--rtsb-masonry-list-columns: {{VALUE}};',
			],
		];

		$fields['image_width'] = [
			'type'        => 'slider',
			'mode'        => 'responsive',
			'label'       => esc_html__( 'List View Image Width', 'shopbuilder' ),
			'size_units'  => [ 'px', '%' ],
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
				$this->selectors['columns']['image_width']['image'] => 'flex-basis: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
			],
		];

		$fields['image_gap'] = [
			'type'        => 'slider',
			'mode'        => 'responsive',
			'label'       => esc_html__( 'List View Image Gap (px)', 'shopbuilder' ),
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
				$this->selectors['columns']['image_gap'] => 'gap: {{SIZE}}{{UNIT}};',
			],
		];

		return $fields;
	}


	/**
	 * Controls for settings tab
	 *
	 * @return $this
	 */
	protected function settings_tab() {
		$visibility = Controls\SettingsFields::content_visibility( $this );

		if ( function_exists( 'rtwpvsp' ) || rtsb()->has_pro() ) {
			unset(
				$visibility['show_swatches']['condition'],
				$visibility['show_vs_clear_btn']['condition']['layout!'],
			);
			if ( ! empty( $visibility['show_swatches'] ) ) {
				$visibility['show_swatches']['description'] .= '<br /><span style="color: #93003c;">' . esc_html__( ' Note: Variation swatch will not work on grid layout 2.', 'shopbuilder' ) . '</span>';
			}
		}

		$sections = apply_filters(
			'rtsb/elements/elementor/grid_settings_tab',
			array_merge(
				$visibility,
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

		if ( function_exists( 'rtwpvsp' ) || rtsb()->has_pro() ) {
			$sections['swatch_position']['description'] .= '<span style="color: #93003c;">' . esc_html__( ' Note: This control is specific to the grid view.', 'shopbuilder' ) . '</span>';
		}

		if ( ! empty( $sections['show_product_stock_count'] ) ) {

			unset( $sections['show_product_stock_count']['condition'] );
			$sections['show_product_stock_count']['description'] = '<span style="color: #93003c;">' . esc_html__( '  Please note that, not all layouts support product stock count.', 'shopbuilder' ) . '</span>';

		}

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
				'layout' => [ 'grid-layout1' ],
			];
		}
		return $swatches_controls;
	}

	/**
	 * Controls for style tab
	 *
	 * @return $this
	 */
	public function style_tab() {
		$sections             = array_merge(
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
		);
		$sections             = apply_filters( 'rtsb/elements/elementor/archive_styles_tab', $sections, $this );
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
	 * @return array
	 */
	public function template_data_arg() {

		$controllers = $this->get_settings_for_display();
		$view_mode   = isset( $_GET['displayview'] ) ? sanitize_text_field( wp_unslash( $_GET['displayview'] ) ) : $controllers['view_mode']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( empty( $view_mode ) ) {
			$view_mode = 'grid';
		}
		if ( 'list' === $view_mode ) {
			$controllers['layout'] = $controllers['list_layout'];
		}

		global $wp_query;

		$order                       = strtoupper( $wp_query->query_vars['order'] ?? 'ASC' );
		$controllers['rtsb_orderby'] = RenderHelpers::escaping_product_orderby( $wp_query->query_vars['orderby'] ?? 'menu_order' );
		$controllers['rtsb_order']   = in_array( $order, [ 'ASC','DESC' ], true ) ? $order : 'ASC';

		add_filter(
			'rtsb/elementor/render/meta_dataset_final',
			function ( $data, $meta ) {
				$data['tooltip_position_list'] = ! empty( $meta['list_action_btn_tooltip_position'] ) ? $meta['list_action_btn_tooltip_position'] : 'top';
				$data['view_mode']             = ! empty( $this->get_settings_for_display()['view_mode'] ) ? $this->get_settings_for_display()['view_mode'] : 'grid';
				$data['grid_layout']           = ! empty( $this->get_settings_for_display()['layout'] ) ? $this->get_settings_for_display()['layout'] : 'grid-layout1';
				$data['list_layout']           = ! empty( $this->get_settings_for_display()['list_layout'] ) ? $this->get_settings_for_display()['list_layout'] : 'list-layout1';

				return $data;
			},
			10,
			2
		);

		return [
			'template' => 'elementor/general/' . $view_mode . '/',
			'settings' => $controllers,
		];
	}

	/**
	 * Widget Field.
	 *
	 * @param string $thumbnail_size Image size.
	 * @return string
	 */
	public function thumbnail_size( $thumbnail_size ) {
		$settings = $this->get_settings_for_display();
		if ( 'rtsb_custom' === $settings['image'] ) {
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
		$data = $this->template_data_arg();

		// Render init.
		$this->render_start();

		// Call the template rendering method.
		Fns::print_html( Render::instance()->wc_loop_for_product_view( $data['template'], $data['settings'], $this ), true );

		// Ending the render.
		$this->render_end();
	}
}
