<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Archive;

use WC_Query;
use WP_Query;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Elementor\Helper\RenderHelpers;
use RadiusTheme\SB\Abstracts\LoopWithProductSlider;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ProductsArchive extends LoopWithProductSlider {

	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->has_slider     = false;
		$this->has_title      = false;
		$this->has_pagination = true;
		$this->rtsb_name      = esc_html__( 'Product Archive (Default)', 'shopbuilder' );
		$this->rtsb_base      = 'rtsb-products-archive';
		parent::__construct( $data, $args );
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function general_section() {
		$fields = parent::general_section();

		// Remove posts_per_page from parent — not applicable for default archive widget.
		unset( $fields['posts_per_page'] );

		$new_fields = [
			'important_note' => [
				'type'      => 'html',
				'separator' => 'after',
				'raw'       => '<p class="rtsb-el-notice">' . esc_html__( 'Some of the controls will not work if your theme overwrites the default markup. In this case, you need to add some support.', 'shopbuilder' ) . '</p>',
			],
			'view_mode'      => [
				'label'       => esc_html__( 'Default View', 'shopbuilder' ),
				'type'        => 'choose',
				'options'     => [
					'grid' => [
						'title' => esc_html__( 'Grid', 'shopbuilder' ),
						'icon'  => 'eicon-posts-grid',
					],
					'list' => [
						'title' => esc_html__( 'List', 'shopbuilder' ),
						'icon'  => 'eicon-post-list',
					],
				],
				'default'     => 'grid',
				'separator'   => 'default',
				'description' => esc_html__( 'If this setting doesn\'t work in your themes, contact us for future theme support.', 'shopbuilder' ),
			],
		];
		$fields     = Fns::insert_controls( 'sec_general', $fields, $new_fields, true );
		$new_fields = [
			'show_pagination'        => [
				'label'       => esc_html__( 'Show Pagination', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show pagination.', 'shopbuilder' ),
				'default'     => 'yes',
			],
			'pagination_ajax_notice' => [
				'type'      => 'html',
				'raw'       => sprintf(
					'<span style="display: block; background: #fffbf1; padding: 10px; line-height: 1.4; color: #bd3a3a;border: 1px solid #bd3a3a30;">%s</span>',
					esc_html__( 'Please note that, if you use the \'Ajax Product Filters\' widget, \'Ajax Load More\' pagination will be used. For other pagination types, use the \'Products - Custom Layouts\' widget.', 'shopbuilder' )
				),
				'separator' => 'default',
				'condition' => [
					'show_pagination' => 'yes',
				],
			],
			'column_per_row'         => [
				'mode'            => 'responsive',
				'label'           => esc_html__( 'Products Grid Column', 'shopbuilder' ),
				'description'     => esc_html__( 'The control only work for grid view. This field not effect for product per page.', 'shopbuilder' ),
				'type'            => 'number',
				'min'             => 2,
				'max'             => 10,
				'step'            => 1,
				'default'         => wc_get_default_products_per_row(),
				'desktop_default' => wc_get_default_products_per_row(),
				'tablet_default'  => 2,
				'mobile_default'  => 1,
				'selectors'       => [
					$this->selectors['column_per_row'] => 'width: calc(100%/{{VALUE}} - ( {{column_gap.size}}{{column_gap.unit}} / {{VALUE}} ) *  ({{VALUE}} - 1 ) ) !important;flex: 0 0 auto;max-width: initial;',
				],
			],
			'column_gap'             => [
				'mode'            => 'responsive',
				'label'           => esc_html__( 'Grid Column gap (px)', 'shopbuilder' ),
				'type'            => 'slider',
				'description'     => esc_html__( 'The control only work for grid view.', 'shopbuilder' ),
				'size_units'      => [ 'px' ],
				'range'           => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'default'         => [
					'size' => 15,
				],
				'desktop_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => 15,
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => 15,
					'unit' => 'px',
				],
				'selectors'       => [
					$this->selectors['column_gap'] => 'column-gap:{{SIZE}}{{UNIT}};',
				],
			],
			'row_gap'                => [
				'mode'       => 'responsive',
				'label'      => esc_html__( 'Row gap (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => 15,
				],
				'selectors'  => [
					$this->selectors['row_gap'] => 'row-gap:{{SIZE}}{{UNIT}};',
				],
			],
			'list_image_width'       => [
				'label'      => esc_html__( 'Image Width (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 250,
						'max'  => 2000,
						'step' => 1,
					],
				],
				'condition'  => [
					'view_mode' => 'list',
				],
				'selectors'  => [
					$this->selectors['list_image_width'] => 'flex:0 0 {{SIZE}}{{UNIT}};',
				],
			],
			'image_gap'              => [
				'mode'       => 'responsive',
				'label'      => esc_html__( 'Image Gap (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 20,
				],
				'selectors'  => [
					$this->selectors['image_gap']['margin-right'] => 'margin-right: {{SIZE}}{{UNIT}};',
					$this->selectors['image_gap']['margin-bottom'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			],

		];
		$fields = Fns::insert_controls( 'general_style_end', $fields, $new_fields, false );
		return $fields;
	}

	/**
	 * Widget Field.
	 *
	 * @return void
	 */
	public function apply_hooks() {
		parent::apply_hooks();
		$controllers = $this->get_settings_for_display();

		add_filter(
			'woocommerce_pagination_args',
			function ( $args ) use ( $controllers ) {
				if ( ! empty( $controllers['prev_icon']['value'] ) ) {
					$args['prev_text'] = Fns::icons_manager( $controllers['prev_icon'] );
				}
				if ( ! empty( $controllers['next_icon']['value'] ) ) {
					$args['next_text'] = Fns::icons_manager( $controllers['next_icon'] );
				}
				return $args;
			}
		);
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

		if ( ! empty( $controllers['show_pagination'] ) ) {
			if ( Fns::product_filters_has_ajax( apply_filters( 'rtsb/builder/set/current/page/type', '' ) ) ) {
				// Keep the default WooCommerce pagination (theme pagination).
				// Only wrap it so the Ajax Product Filters JS can target and re-render it.
				add_action( 'woocommerce_after_shop_loop', [ __CLASS__, 'pagination_wrapper_start' ], 0 );
				add_action( 'woocommerce_after_shop_loop', [ __CLASS__, 'pagination_wrapper_end' ], 99 );
			}
		} else {
			remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination' );
		}

		if (
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			( ! isset( $_GET['displayview'] ) && ! empty( $controllers['view_mode'] ) && 'list' === $controllers['view_mode'] ) ||
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			( isset( $_GET['displayview'] ) && 'list' === $_GET['displayview'] )
		) {
			add_filter(
				'woocommerce_post_class',
				function ( $classes ) {
					$classes[] = 'rtsb-product-list-view';
					return $classes;
				},
				10,
				1
			);
		}
	}

	/**
	 * Widget Selector
	 *
	 * @return array
	 */
	public function template_data_arg() {
		$controllers  = $this->get_settings_for_display();
		$parent_class = 'product-catalog-grid-view';

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$display_list_view = ( ! isset( $_GET['displayview'] ) && ! empty( $controllers['view_mode'] ) && 'list' === $controllers['view_mode'] ) ||
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			( isset( $_GET['displayview'] ) && 'list' === $_GET['displayview'] );

		if ( $display_list_view ) {
			$parent_class = ' product-catalog-list-view';
		}

		$parent_class = apply_filters( 'rtsb/archive/display/views/class', $parent_class );

		$pagination_icon = '';
		if ( ! empty( $controllers['prev_icon'] ) && Fns::icons_manager( $controllers['prev_icon'] ) ) {
			$pagination_icon .= ' rtsb-pagination-icon-prev';
		}
		if ( ! empty( $controllers['next_icon'] ) && Fns::icons_manager( $controllers['next_icon'] ) ) {
			$pagination_icon .= ' rtsb-pagination-icon-next';
		}
		if ( ! empty( $pagination_icon ) ) {
			$pagination_icon .= ' rtsb-pagination-icon';
			$parent_class    .= $pagination_icon;
		}

		$cart_icon = '';
		if ( ! empty( $controllers['cart_icon'] ) && Fns::icons_manager( $controllers['cart_icon'] ) ) {
			$cart_icon .= Fns::icons_manager( $controllers['cart_icon'] );
		}

		global $wp_query;
		$order                       = strtoupper( $wp_query->query_vars['order'] ?? 'ASC' );
		$controllers['rtsb_orderby'] = RenderHelpers::escaping_product_orderby( $wp_query->query_vars['orderby'] ?? 'menu_order' );
		$controllers['rtsb_order']   = in_array( $order, [ 'ASC','DESC' ], true ) ? $order : 'ASC';

		return [
			'template'     => 'elementor/archive/archive-product',
			'icon'         => $cart_icon,
			'parent_class' => $parent_class,
			'controllers'  => $controllers,
			'archive_data' => RenderHelpers::archive_data( $controllers, 'elementor/archive/archive-product' ),
		];
	}

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		$data = $this->template_data_arg();

		if ( empty( $data['template'] ) ) {
			return;
		}

		$this->apply_hooks();
		$this->theme_support();

		$this->action_button_icon_modify();

		$controllers = $this->get_settings_for_display();
		$page_type   = BuilderFns::builder_type( get_the_ID() );
		$is_preview  = BuilderFns::is_builder_preview() && array_key_exists( $page_type, BuilderFns::builder_page_types() );

		if ( $is_preview ) {
			global $wp_query, $post;

			$main_query = clone $wp_query;
			$main_post  = clone $post;

			$posts_per_page = wc_get_default_products_per_row() * wc_get_default_product_rows_per_page();

			$ordering      = ( new WC_Query() )->get_catalog_ordering_args();
			$wp_query_args = [
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => $posts_per_page,
			];

			if ( ! empty( $ordering['orderby'] ) ) {
				$wp_query_args['orderby'] = $ordering['orderby'];
			}

			if ( ! empty( $ordering['order'] ) ) {
				$wp_query_args['order'] = $ordering['order'];
			}

			$wp_query = new WP_Query( $wp_query_args ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

			wc_set_loop_prop( 'total', $wp_query->found_posts );
			wc_set_loop_prop( 'total_pages', $wp_query->max_num_pages );
			wc_set_loop_prop( 'per_page', $posts_per_page );
		}

		Fns::load_template( $data['template'], $data );

		if ( $is_preview ) {
			$wp_query = $main_query; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$post     = $main_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

			wp_reset_query(); // phpcs:ignore WordPress.WP.DiscouragedFunctions.wp_reset_query_wp_reset_query
			wp_reset_postdata();
		}

		$this->action_button_icon_set_default();
		$this->apply_hooks_set_default();
		$this->editor_cart_icon_script();
		$this->editor_script();
		$this->theme_support( 'render_reset' );
	}

	/**
	 * Load more pagination.
	 *
	 * @return void
	 */
	public static function load_more_pagination() {
		global $wp_query;

		if ( $wp_query->max_num_pages < 2 ) {
			return;
		}

		$paginationData = "
			 <div class='rtsb-load-more rtsb-pos-r'>
			    <button data-paged='2' data-max-page='{$wp_query->max_num_pages}'><span>" . esc_html__( 'Load More', 'shopbuilder' ) . "</span></button>
			    <div class='rtsb-loadmore-loading rtsb-ball-clip-rotate'><div></div></div>
			 </div>
		 ";

		Fns::print_html( $paginationData );
	}

	/**
	 * @return void
	 */
	public static function pagination_wrapper_start() {
		echo '<div class="rtsb-archive-pagination-wrap">';
	}

	/**
	 * @return void
	 */
	public static function pagination_wrapper_end() {
		echo '</div>';
	}
}
