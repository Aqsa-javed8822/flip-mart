<?php
/**
 * Main BuilderController class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Models\ElementList;
use RadiusTheme\SB\Traits\SingletonTrait;
use RadiusTheme\SB\Controllers\Builder\BuilderCpt;
use RadiusTheme\SB\Controllers\Hooks\BuilderHooks;
use RadiusTheme\SB\Elementor\Controls\ImageSelectorControl;
use RadiusTheme\SB\Elementor\Controls\Select2AjaxControl;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Builder Controller
 */
class BuilderController {
	/**
	 * @var array
	 */
	private static $cache = [];

	/**
	 * Builder page id.
	 *
	 * @var integer
	 */
	private $builder_page_id = 0;

	/**
	 * Page Edit By.
	 *
	 * @var string
	 */
	private $page_edit_with = '';

	/**
	 * Singleton Trait
	 */
	use SingletonTrait;

	/**
	 * Construct function
	 */
	private function __construct() {
		$this->builder_page_id = BuilderFns::builder_page_id_by_page();
		$this->page_edit_with  = Fns::page_edit_with( $this->builder_page_id );
		if ( ! is_admin() ) {
			BuilderHooks::instance();
		}

		BuilderCpt::instance();
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$this->elementor_init();
			$this->both_builder_frontend();
			add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'editor_scripts' ] );
		}
		// RT Select2 Ajax.
		add_action( 'wp_ajax_rtsb_select2_object_search', [ $this, 'select2_ajax_posts_filter_autocomplete' ] );
		add_action( 'wp_ajax_nopriv_rtsb_select2_object_search', [ $this, 'select2_ajax_posts_filter_autocomplete' ] );
		// Select2 ajax save data.
		add_action( 'wp_ajax_rtsb_select2_get_title', [ $this, 'select2_ajax_get_posts_value_titles' ] );
		add_action( 'wp_ajax_nopriv_rtsb_select2_get_title', [ $this, 'select2_ajax_get_posts_value_titles' ] );
	}

	/**
	 * Apply for frontend.
	 *
	 * @return void
	 */
	public function both_builder_frontend() {
		add_action( 'rtsb/builder/template/before/content', [ $this, 'builder_template_before_content' ] );
		add_action( 'rtsb/builder/template/after/content', [ $this, 'builder_template_after_content' ] );
	}

	/**
	 * Register Category
	 *
	 * @return void
	 */
	public function elementor_init() {
		add_action(
			'init',
			function () {
				if ( Fns::should_load_elementor_scripts() ) {
					add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_widget_categories' ] );
					add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
					add_action( 'elementor/controls/register', [ $this, 'init_controls' ] );
					add_filter( 'elementor/editor/localize_settings', [ $this, 'promotePremiumWidgets' ] );
					add_action( 'elementor/icons_manager/additional_tabs', [ $this, 'rtsb_icons' ] );
				}
			}
		);
	}

	/**
	 * Register Category
	 *
	 * @param object $elements_manager Category hooks.
	 *
	 * @return void
	 */
	public function add_elementor_widget_categories( $elements_manager ) {
		$categories = [];
		$builder_id = get_queried_object_id();
		$id         = Fns::wpml_object_id( $builder_id, BuilderFns::$post_type_tb, 'default' );
		$type       = BuilderFns::builder_type( $id );

		if ( $type && 'popup-builder' !== $type ) {
			$builder_type                   = ! empty( BuilderFns::builder_page_types()[ $type ] ) ? BuilderFns::builder_page_types()[ $type ] : '';
			$builder_type                   = str_replace( [ 'My Account - ', 'Endpoint: ' ], '', $builder_type );
			$categories['rtsb-shopbuilder'] = [
				'title' => esc_html__( 'ShopBuilder', 'shopbuilder' ) . ' - ' . ucfirst( $builder_type ),
				'icon'  => 'fa fa-plug',
			];
		}
		if ( 'quick-view' !== $type ) {
			$categories['rtsb-shopbuilder-general'] = [
				'title' => esc_html__( 'Shopbuilder - General', 'shopbuilder' ),
				'icon'  => 'fa fa-plug',
			];
		}
		$categories = apply_filters( 'rtsb_elementor_widgets_category_lists', $categories );

		$other_categories = $elements_manager->get_categories();
		$categories       = array_merge(
			array_slice( $other_categories, 0, 1 ),
			$categories,
			array_slice( $other_categories, 1 )
		);

		$set_categories = function ( $categories ) use ( $elements_manager ) {
			$elements_manager->categories = $categories;
		};

		$set_categories->call( $elements_manager, $categories );
	}

	/**
	 * Init Controls
	 *
	 * Include controls files and register them
	 *
	 * @param object $controls_manager Controls Manager.
	 *
	 * @since  1.0.0
	 */
	public function init_controls( $controls_manager ) {
		$controls_manager->register( new ImageSelectorControl() );
		$controls_manager->register( new Select2AjaxControl() );
	}

	/**
	 * Init Widgets
	 *
	 * Include widget files and register them
	 *
	 * @param object $widgets_manager Widgets Manager.
	 *
	 * @return void
	 *
	 * @since  1.0.0
	 */
	public function init_widgets( $widgets_manager ) {

		$get_list = ElementList::instance()->get_list( true, 'active' );
		foreach ( $get_list as $element ) {
			if ( isset( $element['active'] ) && 'on' !== $element['active'] ) {
				continue;
			}

			if ( ! empty( $element['package'] ) && 'pro-disabled' === $element['package'] ) {
				continue;
			}

			if ( empty( $element['base_class'] ) || ! class_exists( $element['base_class'] ) ) {
				continue;
			}

			if ( ! empty( $element['category'] ) ) {
				try {
					$widget = false;
					$import = apply_filters( 'rtsb_import_status', false );

					if ( $this->should_register_widget( $element['category'], $import ) ) {
						$widget = new $element['base_class']();
					}

					$widget = apply_filters( 'rtsb/element/list/init/widgets', $widget, $element );

					if ( $widget && is_object( $widget ) ) {
						$widgets_manager->register( $widget );
					}
				} catch ( \Throwable $e ) {
					error_log( 'ShopBuilder widget error (' . $element['base_class'] . '): ' . $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
					continue;
				}
			}
		}
	}

	/**
	 * Determine whether a widget for the given category should be registered
	 * in the current request context.
	 *
	 * @param string $category Element category.
	 * @param bool   $import   Whether an import is in progress.
	 *
	 * @return bool
	 */
	protected function should_register_widget( $category, $import ) {
		// On the frontend the Elementor Widgets Manager can initialise at an
		// unpredictable time — e.g. Elementor Pro's Theme Builder boots it on
		// template_redirect — when WooCommerce query conditionals such as
		// is_shop()/is_archive() are not yet reliable. Widget registration is
		// cached for the whole request, so gating it by those conditionals can
		// permanently skip ShopBuilder widgets, leaving the builder template to
		// render empty container shells. Register unconditionally on the
		// frontend; keep the per-category gate only for the editor widget panel.
		if ( ! is_admin() ) {
			return true;
		}

		switch ( $category ) {
			case 'product':
				return BuilderFns::is_product() || $import;
			case 'shop':
				return BuilderFns::is_archive() || BuilderFns::is_shop() || $import;
			case 'archive':
				return BuilderFns::is_archive() || $import;
			case 'cart':
				return BuilderFns::is_cart() || $import;
			case 'checkout':
				return BuilderFns::is_checkout() || $import;
			case 'general':
				return true;
			default:
				return false;
		}
	}

	/**
	 * Adding custom icons in Elementor
	 *
	 * @param array $tabs Tabs.
	 *
	 * @return array
	 */
	public function rtsb_icons( $tabs = [] ) {
		$custom_icons = Fns::get_custom_icon_names();

		$tabs['shopbuilder-icons'] = [
			'name'          => 'rtsb-fonts',
			'label'         => esc_html__( 'ShopBuilder Icons', 'shopbuilder' ),
			'labelIcon'     => 'fab fa-elementor',
			'prefix'        => 'rtsb-icon-',
			'displayPrefix' => 'rtsb-icon',
			'url'           => rtsb()->get_assets_uri( 'css/frontend/rtsb-fonts.css' ),
			'icons'         => $custom_icons,
			'ver'           => '1.0',
		];

		return $tabs;
	}

	/**
	 * Editor JS.
	 *
	 * @return void
	 */
	public function editor_scripts() {
		$version = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? time() : RTSB_VERSION;

		wp_enqueue_script(
			'rtsb-editor-script',
			rtsb()->get_assets_uri( 'js/backend/editor.js' ),
			[
				'jquery',
				'elementor-editor',
				'jquery-elementor-select2',
			],
			$version,
			true
		);
		$params = [
			'isEditorForBuilder' => false,
		];
		if ( BuilderFns::is_builder_preview() ) {
			$params['isEditorForBuilder'] = true;
		}
		wp_localize_script( 'rtsb-editor-script', 'rtsbTemplateBuilderEditorScript', $params );
		wp_enqueue_style( 'rtsb-el-editor-style', rtsb()->get_assets_uri( 'css/backend/elementor-editor.css' ), [], $version );
	}

	/**
	 * Before Content for Elementor.
	 *
	 * @return void
	 */
	public function builder_template_before_content() {
		/**
		 * Before Header-Footer page template content.
		 *
		 * Fires before the content of Elementor Header-Footer page template.
		 *
		 * @since 2.0.0
		 */
		if ( did_action( 'elementor/loaded' ) && 'elementor' === $this->page_edit_with ) {
			do_action( 'elementor/page_templates/header-footer/before_content' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		}

		$parent_class = apply_filters( 'rtsb/builder/content/parent_class', [] );

		if ( BuilderFns::is_product() ) {
			$product_id = Fns::get_prepared_product_id();

			// Ensure the WordPress global $product is a valid WC_Product before
			// wc_get_product_class() fires the `woocommerce_post_class` filter.
			// Some themes (e.g. Blocksy) read the global $product directly inside
			// that filter and call methods like get_type() on it, which throws a
			// fatal error when the global is still an unresolved string.
			$_product = Fns::get_product();

			$parent_class = wc_get_product_class( $parent_class, $product_id );

			if ( function_exists( 'rtwpvs' ) && $_product instanceof \WC_Product ) {
				if ( $_product->is_type( 'variable' ) ) {
					$parent_class[] = 'rtwpvs-product';
					$beside_label   = function_exists( 'rtwpvsp' ) && rtwpvs()->get_option( 'attribute_on_click_behavior' );
					if ( $beside_label ) {
						$parent_class[] = 'rtwpvs-selected-term-beside-label';
					}
				}
			}
		}

		if ( BuilderFns::is_shop() ) {
			$parent_class[] = 'shop';
		}

		if ( BuilderFns::is_archive() ) {
			$parent_class[] = 'archive';
		}

		if ( BuilderFns::is_checkout() ) {
			$parent_class[] = 'checkout-page ';
		}

		?>
		<!-- Before Content start -->
		<div class="<?php echo esc_attr( implode( ' ', $parent_class ) ); ?>">
		<?php
		if ( BuilderFns::is_checkout() ) {
			if ( ! apply_filters( 'rtsb/multi/step/checkout/widget', true ) ) {
				return;
			}

			$checkout = WC()->checkout();

			// Temporarily remove callbacks that Elementor checkout
			// handles via separate widgets to prevent duplicates.
			remove_action( 'woocommerce_before_checkout_form', [ Hooks\ActionHooks::class, 'woocommerce_checkout_login_form' ], 10 );
			remove_action( 'woocommerce_before_checkout_form', [ Hooks\ActionHooks::class, 'woocommerce_checkout_coupon_form' ], 10 );
			remove_action( 'woocommerce_before_checkout_form', [ Fns::class, 'woocommerce_output_all_notices' ], 10 );

			do_action( 'woocommerce_before_checkout_form', $checkout );

			// Restore callbacks for non-Elementor contexts.
			add_action( 'woocommerce_before_checkout_form', [ Hooks\ActionHooks::class, 'woocommerce_checkout_login_form' ], 10 );
			add_action( 'woocommerce_before_checkout_form', [ Hooks\ActionHooks::class, 'woocommerce_checkout_coupon_form' ], 10 );
			add_action( 'woocommerce_before_checkout_form', [ Fns::class, 'woocommerce_output_all_notices' ], 10 );

			// If checkout registration is disabled and not logged in, the user cannot checkout.
			if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
				echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'shopbuilder' ) ) );
				return;
			}
			?>
			<!--
			woocommerce-checkout added for fix issue with payment method ( Revolut Pay )
			-->
			<form name="checkout" method="post" class="rtsb-woocommerce-checkout woocommerce-checkout checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
			<?php
		}
	}

	/**
	 * After Content for Elementor.
	 *
	 * @return void
	 */
	public function builder_template_after_content() {
		/**
		 * After Header-Footer page template content.
		 *
		 * Fires after the content of Elementor Header-Footer page template.
		 *
		 * @since 2.0.0
		 */
		if ( did_action( 'elementor/loaded' ) && 'elementor' === $this->page_edit_with ) {
			do_action( 'elementor/page_templates/header-footer/after_content' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		}

		if ( BuilderFns::is_checkout() ) {
			if ( ! apply_filters( 'rtsb/multi/step/checkout/widget', true ) ) {
				return;
			}
			?>
			</form>
			<?php
			do_action( 'woocommerce_after_checkout_form', WC()->checkout() );
		}
		?>
		</div>
		<!-- After Content end -->
		<?php
	}

	/**
	 * Ajax callback for rt-select2
	 *
	 * @return void
	 */
	public function select2_ajax_posts_filter_autocomplete() {
		if ( ! wp_verify_nonce( Fns::get_nonce(), rtsb()->nonceText ) ) {
			wp_send_json_error( [] );
		}
		$query_per_page = 15;
		$post_type      = 'post';
		$source_name    = 'post_type';
		$paged          = absint( $_POST['page'] ?? 1 );
		if ( ! empty( $_POST['post_type'] ) ) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			$post_type = sanitize_text_field( $_POST['post_type'] );
		}

		if ( ! empty( $_POST['source_name'] ) ) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			$source_name = sanitize_text_field( $_POST['source_name'] );
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$search  = ! empty( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
		$results = $post_list = []; // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found
		switch ( $source_name ) {
			case 'taxonomy':
				$args = [
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'name__like' => $search,
					'number'     => '5',
				];

				if ( 'all' !== $post_type ) {
					$args['taxonomy'] = $post_type;
				}

				$post_list = wp_list_pluck( get_terms( $args ), 'name', 'term_id' );
				break;
			case 'user':
				$users = [];

				foreach ( get_users( [ 'search' => "*{$search}*" ] ) as $user ) {
					$user_id           = $user->ID;
					$user_name         = $user->display_name;
					$users[ $user_id ] = $user_name;
				}

				$post_list = $users;
				break;
			default:
				$post_list = $this->get_query_data( $post_type, $query_per_page, $search, $paged );
		}

		$pagination = true;
		if ( count( $post_list ) < $query_per_page ) {
			$pagination = false;
		}
		if ( ! empty( $post_list ) ) {
			foreach ( $post_list as $key => $item ) {
				$results[] = [
					'text' => $item,
					'id'   => $key,
				];
			}
		}
		wp_send_json(
			[
				'results'    => $results,
				'pagination' => [ 'more' => $pagination ],
			]
		);
	}


	/**
	 * Ajax callback for rt-select2
	 *
	 * @return void
	 */
	public function select2_ajax_get_posts_value_titles() {

		if ( ! wp_verify_nonce( Fns::get_nonce(), rtsb()->nonceText ) ) {
			wp_send_json_error( [] );
		}

		if ( empty( $_POST['id'] ) ) {
			wp_send_json_error( [] );
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( empty( array_filter( $_POST['id'] ) ) ) {
			wp_send_json_error( [] );
		}
		$ids         = array_map( 'intval', $_POST['id'] );
		$source_name = ! empty( $_POST['source_name'] ) ? sanitize_text_field( $_POST['source_name'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash

		switch ( $source_name ) {
			case 'taxonomy':
				$args = [
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'include'    => implode( ',', $ids ),
				];

				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
				if ( 'all' !== $_POST['post_type'] ) {
					// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
					$args['taxonomy'] = sanitize_text_field( $_POST['post_type'] );
				}

				$response = wp_list_pluck( get_terms( $args ), 'name', 'term_id' );
				break;
			case 'user':
				$users = [];

				foreach ( get_users( [ 'include' => $ids ] ) as $user ) {
					$user_id           = $user->ID;
					$user_name         = $user->display_name . '-' . $user->ID;
					$users[ $user_id ] = $user_name;
				}

				$response = $users;
				break;
			default:
				$post_info = get_posts(
					[
						'post_type' => sanitize_text_field( $_POST['post_type'] ), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
						'include'   => implode( ',', $ids ),
					]
				);
				$response  = wp_list_pluck( $post_info, 'post_title', 'ID' );
		}

		if ( ! empty( $response ) ) {
			wp_send_json_success( [ 'results' => $response ] );
		} else {
			wp_send_json_error( [] );
		}
	}


	/**
	 * Ajax callback for rt-select2
	 *
	 * @param string $post_type Post type.
	 * @param int    $limit     Limit.
	 * @param string $search    Search.
	 * @param int    $paged     Paged.
	 *
	 * @return array
	 */
	public function get_query_data( $post_type = 'any', $limit = 10, $search = '', $paged = 1 ) {
		global $wpdb;
		$where = '';
		$data  = [];

		if ( - 1 == $limit ) {
			$limit = '';
		} elseif ( 0 == $limit ) {
			$limit = 'limit 0,1';
		} else {
			$offset = 0;
			if ( $paged ) {
				$offset = ( $paged - 1 ) * $limit;
			}
			$limit = $wpdb->prepare( ' limit %d, %d', esc_sql( $offset ), esc_sql( $limit ) );
		}

		if ( 'any' === $post_type ) {
			$in_search_post_types = get_post_types( [ 'exclude_from_search' => false ] );
			if ( empty( $in_search_post_types ) ) {
				$where .= ' AND 1=0 ';
			} else {
				$where .= " AND {$wpdb->posts}.post_type IN ('" . join(
					"', '",
					array_map( 'esc_sql', $in_search_post_types )
				) . "')";
			}
		} elseif ( ! empty( $post_type ) ) {
			$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_type = %s", esc_sql( $post_type ) );
		}

		if ( ! empty( $search ) ) {
			$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_title LIKE %s", '%' . esc_sql( $search ) . '%' );
		}

		// Add a query for shop_coupon post-type to check 'show on frontend' meta.
		if ( 'shop_coupon' === $post_type ) {
			$where .= " AND {$wpdb->posts}.ID IN (
                SELECT post_id FROM {$wpdb->postmeta} 
                WHERE meta_key = 'rtsb_show_on_frontend' 
                AND meta_value = 'on'
            )";
		}

		$query   = "select post_title,ID  from $wpdb->posts where post_status = 'publish' {$where} {$limit}";
		$results = $wpdb->get_results( $query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching

		if ( ! empty( $results ) ) {
			foreach ( $results as $row ) {
				$data[ $row->ID ] = $row->post_title . ' [#' . $row->ID . ']';
			}
		}

		return $data;
	}

	/**
	 * Promotion.
	 *
	 * @param array $config Config.
	 * @return array
	 */
	public function promotePremiumWidgets( $config ) {
		if ( rtsb()->has_pro() ) {
			return $config;
		}

		if ( ! empty( $config['initial_document']['panel']['elements_categories']['rtsb-shopbuilder']['title'] ) ) {
			$title = $config['initial_document']['panel']['elements_categories']['rtsb-shopbuilder']['title'];

			if ( 'ShopBuilder - Shop' === $title || 'ShopBuilder - Archive' === $title ) {
				if ( ! isset( $config['promotionWidgets'] ) || ! is_array( $config['promotionWidgets'] ) ) {
					$config['promotionWidgets'] = [];
				}

				$pro_widgets = [
					[
						'name'        => 'rtsb-ajax-product-filters',
						'title'       => esc_html__( 'Ajax Product Filters', 'shopbuilder' ),
						'description' => esc_html__( 'Ajax Product Filters', 'shopbuilder' ),
						'icon'        => 'rtsb-el-custom rtsb-element icon-rtsb-ajax-product-filters rtsb-promotional-element',
						'categories'  => '[ "rtsb-shopbuilder" ]',
					],
				];

				$config['promotionWidgets'] = array_merge( $config['promotionWidgets'], $pro_widgets );

				return $config;
			}
		}

		return $config;
	}

	/**
	 * Get Icons.
	 *
	 * @return array
	 */
	public function get_icons() {
		return [
			'heart-empty',
			'heart',
			'eye',
			'exchange',
			'plus',
			'minus',
			'avatar',
			'pay',
			'share',
			'clock',
			'check-alt',
			'check',
			'delete',
			'marker',
			'list',
			'list-2',
			'power',
			'cart',
			'cart-2',
			'cart-3',
			'downloads',
			'zoom',
			'user-edit',
			'grid',
			'filter',
			'billing',
			'login',
			'payment',
			'search',
			'edit',
			'coupon',
			'arrows-cw',
			'trash-empty',
		];
	}
}
