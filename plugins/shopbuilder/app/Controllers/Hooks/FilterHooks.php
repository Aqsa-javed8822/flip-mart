<?php
/**
 * Main FilterHooks class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\Hooks;

use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Helpers\ElementorDataMap;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Models\GeneralList;

defined( 'ABSPATH' ) || exit();

/**
 * Main FilterHooks class.
 */
class FilterHooks {
	/**
	 * Init Hooks.
	 *
	 * @return void
	 */
	public static function init_hooks() {
		add_filter( 'wp_kses_allowed_html', [ __CLASS__, 'custom_wpkses_post_tags' ], 10, 2 );

		add_filter( 'woocommerce_get_country_locale', [ __CLASS__, 'change_country_locale' ] );
		add_filter( 'woocommerce_checkout_fields', [ __CLASS__, 'woocommerce_checkout_fields' ], 50 );
		add_filter( 'woocommerce_default_address_fields', [ __CLASS__, 'default_address_fields' ] );

		add_filter( 'woocommerce_add_to_cart_form_action', [ __CLASS__, 'preview_page_product_permalink' ] );
		add_filter( 'woocommerce_add_to_cart_redirect', [ __CLASS__, 'preview_page_product_add_to_cart_redirect' ], 10 );
		add_filter( 'woocommerce_product_get_rating_html', [ __CLASS__, 'get_star_rating_html' ], 11, 3 );
		add_filter( 'woocommerce_product_data_store_cpt_get_products_query', [ __CLASS__, 'custom_query_keys' ], 10, 2 );
		// Overriding Notice Template.
		add_filter( 'wc_get_template', [ __CLASS__, 'get_notice_template' ], 15, 2 );
		add_filter( 'rtsb/optimizer/theme_stylesheet_handle', [ __CLASS__, 'theme_stylesheet_handle' ], 99 );

		// Flag invalid postcode in checkout update fragments.
		add_filter( 'woocommerce_update_order_review_fragments', [ __CLASS__, 'validate_postcode_fragment' ], 100 );

		// Prevent WordPress canonical redirect from sending shop pages with
		// a single product_cat/product_tag/filter_* query param to the
		// matching term archive (which would discard other filters).
		add_filter( 'redirect_canonical', [ __CLASS__, 'preserve_filter_query_canonical' ], 10, 2 );
	}

	/**
	 * Prevent WordPress canonical redirects from stripping our filter query
	 * parameters. When a visitor submits the Product Filters form from the
	 * shop page with ?product_cat=X, WP recognises X as a category and
	 * redirects to /product-category/X/, which loses the multi-filter state
	 * and breaks the active-filter bar.
	 *
	 * @param string $redirect_url  Proposed canonical URL.
	 * @param string $requested_url Originally requested URL.
	 *
	 * @return string|false Original redirect URL, or false to abort redirect.
	 */
	public static function preserve_filter_query_canonical( $redirect_url, $requested_url ) {
		unset( $requested_url );

		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Read-only query string check.
		if ( empty( $_GET ) ) {
			return $redirect_url;
		}

		$tracked_keys   = [ 'product_cat', 'product_tag', 'product_brand' ];
		$active_filters = [];

		foreach ( $_GET as $key => $value ) {
			$value = is_array( $value ) ? implode( ',', $value ) : (string) $value;
			if ( '' === $value ) {
				continue;
			}

			$key = sanitize_key( $key );

			if (
				in_array( $key, $tracked_keys, true )
				|| 0 === strpos( $key, 'filter_' )
				|| in_array( $key, [ 'min_price', 'max_price', 'rating_filter', 'sale_filter' ], true )
			) {
				$active_filters[ $key ] = $value;
			}
		}
		// phpcs:enable

		// No filter params at all - leave canonical behaviour untouched.
		if ( empty( $active_filters ) ) {
			return $redirect_url;
		}

		// A single, single-valued taxonomy param (e.g. ?product_cat=child) is
		// plain category/tag/brand navigation, not a multi-filter state. Let
		// WordPress redirect it to the pretty archive URL (/product-category/child/)
		// instead of pinning it to the shop page.
		if ( 1 === count( $active_filters ) ) {
			foreach ( $active_filters as $only_key => $only_value ) {
				if ( in_array( $only_key, $tracked_keys, true ) && false === strpos( $only_value, ',' ) ) {
					return $redirect_url;
				}
			}
		}

		// Otherwise it is a real multi-filter combination that cannot be
		// represented by a single canonical URL - keep it on the shop URL so the
		// active filters are preserved.
		return false;
	}

	/***
	 * @param string $template Template Path.
	 * @param string $template_name Template Name.
	 *
	 * @return mixed
	 */
	public static function get_notice_template( $template, $template_name ) {
		switch ( $template_name ) {
			case 'notices/success.php':
			case 'notices/error.php':
			case 'notices/notice.php':
				$rtsb_template = 'elementor/general/' . str_replace( '.php', '', $template_name );
				$template      = Fns::locate_template( $rtsb_template ) ?? $template;
				break;
		}
		return $template;
	}

	/**
	 * Get Star Rating Html.
	 *
	 * @param string $html Html.
	 * @param string $rating Rating.
	 * @param string $count Count.
	 *
	 * @return string
	 */
	public static function get_star_rating_html( $html, $rating, $count ) {
		if ( '0' === $rating ) {
			$html .= '<div class="star-rating empty">';
			$html .= wc_get_star_rating_html( $rating, $count );
			$html .= '</div>';
		}

		return '<div class="rtsb-star-rating">' . $html . '</div>';
	}

	/**
	 * @param string $url Cart Page Url.
	 * @return false|mixed|string
	 */
	public static function preview_page_product_add_to_cart_redirect( $url ) {
		if ( ! empty( $_GET['demopage'] ) ) {  // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$demo_id = absint( $_GET['demopage'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( get_post_type( $demo_id ) === BuilderFns::$post_type_tb && 'product' === BuilderFns::builder_type( $demo_id ) ) {
				 $url = get_the_permalink( $demo_id );
			}
		}
		return $url;
	}

	/**
	 * @param string $permalink Permalink.
	 * @return string
	 */
	public static function preview_page_product_permalink( $permalink ) {
		$global_query_id = get_queried_object_id();
		if ( BuilderFns::is_builder_preview() && 'product' === BuilderFns::builder_type( $global_query_id ) ) {
			$permalink = esc_url(
				add_query_arg(
					[
						'demopage' => $global_query_id,
					],
					$permalink
				)
			);
		}
		return $permalink;
	}

	/**
	 * Localize Scripts
	 *
	 * @param array $locale locale variable.
	 *
	 * @return array
	 */
	public static function change_country_locale( $locale ) {
		if ( Fns::is_module_active( 'checkout_fields_editor' ) ) {
			return $locale;
		}
		$elementor_list = GeneralList::instance()->get_data();

		if ( ! array_key_exists( 'billing_form', $elementor_list ) ) {
			return $locale;
		}

		$billing_settings = $elementor_list['billing_form'];

		if ( empty( $billing_settings['active'] ) ) {
			return $locale;
		}

		if ( ! empty( $billing_settings['billing_state_label'] ) ) {
			$locale[ WC()->countries->get_base_country() ]['state']['label'] = $billing_settings['billing_state_label'];
		}

		if ( ! empty( $billing_settings['billing_postcode_label'] ) ) {
			$locale[ WC()->countries->get_base_country() ]['postcode']['label'] = $billing_settings['billing_postcode_label'];
		}

		return $locale;
	}
	/**
	 * Add exceptions in wp_kses_post tags.
	 *
	 * @param array  $tags Allowed tags, attributes, and/or entities.
	 * @param string $context Context to judge allowed tags by. Allowed values are 'post'.
	 *
	 * @return array
	 */
	public static function custom_wpkses_post_tags( $tags, $context ) {
		if ( 'post' === $context ) {
			$tags['iframe'] = [
				'src'             => true,
				'height'          => true,
				'width'           => true,
				'frameborder'     => true,
				'allowfullscreen' => true,
			];

			$tags['svg'] = [
				'class'           => true,
				'aria-hidden'     => true,
				'aria-labelledby' => true,
				'role'            => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true,
				'stroke'          => true,
				'fill'            => true,
			];

			$tags['g']      = [ 'fill' => true ];
			$tags['title']  = [ 'title' => true ];
			$tags['path']   = [
				'd'               => true,
				'fill'            => true,
				'stroke-width'    => true,
				'stroke-linecap'  => true,
				'stroke-linejoin' => true,
				'fill-rule'       => true,
				'clip-rule'       => true,
				'stroke'          => true,
			];
			$tags['circle'] = [
				'r'     => true,
				'cx'    => true,
				'cy'    => true,
				'class' => true,
				'style' => true,
			];
		}

		return $tags;
	}

	/**
	 * Set field Keyword.
	 *
	 * @param string $form_name Form Name.
	 * @param array  $billing_settings Billing Settings.
	 * @param array  $fields Fields.
	 * @param string $field_id Field ID.
	 *
	 * @return array
	 */
	private static function checkout_field( $form_name, $billing_settings, $fields, $field_id = 'billing_field_name' ) {

		if ( ! isset( $billing_settings[ $field_id ] ) ) {
			return $fields;
		}

		$settings_value = $billing_settings[ $field_id ];
		if ( ! in_array( 'show', $settings_value, true ) ) {
			unset( $fields[ $form_name ][ $field_id ] );
			if ( $form_name . '_address_1' === $field_id ) {
				unset( $fields[ $form_name ][ $form_name . '_address_2' ] );
			}
			return $fields;
		}

		if ( $form_name . '_address_2' !== $field_id ) {
			$fields[ $form_name ][ $field_id ]['required'] = in_array( 'required', $settings_value, true );
		}

		$labels = [
			'label'       => $field_id . '_label',
			'placeholder' => $field_id . '_placeholder',
		];

		foreach ( $labels as $key => $value ) {
			if ( ! empty( $billing_settings[ $value ] ) ) {
				$fields[ $form_name ][ $field_id ][ $key ] = $billing_settings[ $value ];
			}
		}

		return $fields;
	}


	/**
	 * Set Widget Keyword.
	 *
	 * @param array $fields Fields.
	 *
	 * @return array
	 */
	public static function woocommerce_checkout_fields( $fields ) {
		// Only on checkout page.
		if ( Fns::is_module_active( 'checkout_fields_editor' ) ) {
			return $fields;
		}
		$elementor_list = GeneralList::instance()->get_data();
		$fields_key     = [];

		$forms = [ 'billing_form', 'shipping_form' ];

		foreach ( $forms as $form ) {
			if ( ! array_key_exists( $form, $elementor_list ) || empty( $elementor_list[ $form ]['active'] ) ) {
				continue;
			}
			$form_name                = str_replace( '_form', '', $form );
			$fields_key[ $form_name ] = [
				$form_name . '_first_name',
				$form_name . '_last_name',
				$form_name . '_company',
				$form_name . '_country',
				$form_name . '_postcode',
				$form_name . '_address_2',
				$form_name . '_address_1',
				$form_name . '_state',
				$form_name . '_city',
			];

			if ( 'billing_form' === $form ) {
				$fields_key[ $form_name ][] = $form_name . '_phone';
				$fields_key[ $form_name ][] = $form_name . '_email';
			}
		}
		foreach ( $fields_key as $form_name => $form_fields ) {
			foreach ( $form_fields as $field_id ) {
				$fields = self::checkout_field( $form_name, $elementor_list[ $form_name . '_form' ], $fields, $field_id );
			}
		}

		return $fields;
	}

	/**
	 * Set Widget Keyword.
	 *
	 * @param array $fields Fields.
	 *
	 * @return array
	 */
	public static function default_address_fields( $fields ) {
		// Only on checkout page.
		if ( Fns::is_module_active( 'checkout_fields_editor' ) ) {
			return $fields;
		}
		$elementor_list = GeneralList::instance()->get_data();

		if ( ! array_key_exists( 'billing_form', $elementor_list ) ) {
			return $fields;
		}

		$billing_settings = $elementor_list['billing_form'];

		if ( empty( $billing_settings['active'] ) ) {
			return $fields;
		}

		$fields_key = [
			'address_2',
			'address_1',
			'state',
			'city',
			'postcode',
		];
		foreach ( $fields_key as $value ) {
			$fields = self::checkout_billing_address_field( $billing_settings, $fields, $value );
		}
		return $fields;
	}

	/**
	 * Set field Keyword.
	 *
	 * @param array  $billing_settings Billing Settings.
	 * @param array  $fields Fields.
	 * @param string $field_id Field ID.
	 *
	 * @return array
	 */
	private static function checkout_billing_address_field( $billing_settings, $fields, $field_id = '' ) {
		$settings_field_id = 'billing_' . $field_id;
		if ( ! isset( $billing_settings[ $settings_field_id ] ) ) {
			return $fields;
		}

		$settings_value = $billing_settings[ $settings_field_id ];

		$fields[ $field_id ]['required'] = in_array( 'required', $settings_value, true );

		$labels = [
			'label'       => $settings_field_id . '_label',
			'placeholder' => $settings_field_id . '_placeholder',
		];

		foreach ( $labels as $key => $value ) {
			if ( ! empty( $billing_settings[ $value ] ) ) {
				$fields[ $field_id ][ $key ] = $billing_settings[ $value ];
			}
		}

		return $fields;
	}

	/**
	 * Handle a custom query var.
	 *
	 * @param array $wp_query_args - Args for WP_Query.
	 * @param array $query_vars - Query vars from WC_Product_Query.
	 *
	 * @return array
	 */
	public static function custom_query_keys( $wp_query_args, $query_vars ) {
		$tax_key    = 'product_attribute_id';
		$brand_key  = 'product_brand_id';
		$rating_key = 'product_rating';
		if ( ! empty( $query_vars[ $tax_key ] ) ) {
			/**
			Foreach ( $query_vars[ $tax_key ] as $atts ) {
				$attribute_tax = get_term( $atts )->taxonomy;

				$wp_query_args['tax_query'][] = [
					'taxonomy' => $attribute_tax,
					'field'    => 'term_id',
					'terms'    => [ $atts ],
					'operator' => 'IN',
				];
			}
			*/
			// added new code.
			$tax_groups = [];

			foreach ( $query_vars[ $tax_key ] as $term_id ) {
				$term = get_term( $term_id );
				if ( ! is_wp_error( $term ) && $term ) {
					$tax                  = $term->taxonomy;
					$tax_groups[ $tax ][] = $term_id;
				}
			}

			foreach ( $tax_groups as $taxonomy => $term_ids ) {
				$wp_query_args['tax_query'][] = [
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $term_ids,
					'operator' => 'IN',
				];
			}
		}

		if ( ! empty( $query_vars[ $brand_key ] ) ) {
			$brand_ids = is_array( $query_vars[ $brand_key ] ) ? $query_vars[ $brand_key ] : [ $query_vars[ $brand_key ] ];

			$wp_query_args['tax_query'][] = [
				'taxonomy' => 'product_brand',
				'field'    => 'term_id',
				'terms'    => $brand_ids,
				'operator' => 'IN',
			];
		}

		if ( ! empty( $query_vars[ $rating_key ] ) ) {
			$wp_query_args['meta_query'][] = [
				'key'     => '_wc_average_rating',
				'value'   => esc_attr( $query_vars[ $rating_key ] ),
				'type'    => 'numeric',
				'compare' => 'IN',
			];
		}
		return $wp_query_args;
	}
	/**
	 * Add OR relation support for taxonomy queries.
	 *
	 * Processes the incoming WP_Query arguments and modifies the tax_query
	 * relation to use "OR" instead of the default "AND" when needed.
	 *
	 * @param array $wp_query_args Existing WP_Query arguments.
	 * @param array $query_vars    Query variables passed by WP.
	 *
	 * @return array Modified query arguments with OR relation applied.
	 */
	public static function extra_query_args_or( $wp_query_args, $query_vars ) {
		return self::process_tax_query_relation( $wp_query_args, $query_vars, 'OR' );
	}

	/**
	 * Apply AND relation to taxonomy queries.
	 *
	 * Modifies WP_Query arguments so that multiple taxonomy terms
	 * are matched using an "AND" relation.
	 *
	 * @param array $wp_query_args Existing WP_Query arguments.
	 * @param array $query_vars    Query variables passed by WordPress.
	 *
	 * @return array Modified query arguments with AND relation applied.
	 */
	public static function extra_query_args_and( $wp_query_args, $query_vars ) {
		return self::process_tax_query_relation( $wp_query_args, $query_vars, 'AND' );
	}
	/**
	 * Process taxonomy query relation (AND / OR) for WC_Product_Query.
	 *
	 * @param array  $wp_query_args Existing WP_Query arguments.
	 * @param array  $query_vars    Query vars passed by WooCommerce.
	 * @param string $relation      Relation type: AND | OR.
	 *
	 * @return array Modified WP_Query args.
	 */
	public static function process_tax_query_relation( $wp_query_args, $query_vars, $relation ) {

		$type_condition = $query_vars['type'] ?? '';

		foreach ( $wp_query_args['tax_query'] as $key => $tax_query ) {
			if (
				isset( $tax_query['taxonomy'], $tax_query['field'], $tax_query['terms'] ) &&
				'product_type' === $tax_query['taxonomy'] &&
				'slug' === $tax_query['field'] &&
				$tax_query['terms'] === $type_condition
			) {
				unset( $wp_query_args['tax_query'][ $key ] );
				break;
			}
		}

		$wp_query_args['tax_query'] = array_values( $wp_query_args['tax_query'] ); // phpcs:ignore

		if ( empty( $wp_query_args['tax_query'] ) || count( $wp_query_args['tax_query'] ) < 2 ) {
			return $wp_query_args;
		}

		$visibility_queries = [];
		$user_queries       = [];

		foreach ( $wp_query_args['tax_query'] as $tax_query ) {

			if ( isset( $tax_query['taxonomy'] ) && 'product_visibility' === $tax_query['taxonomy'] ) {
				$visibility_queries[] = $tax_query;
				continue;
			}

			$user_queries[] = $tax_query;
		}

		if ( empty( $user_queries ) ) {
			return $wp_query_args;
		}

		$final_tax_query = [
			'relation' => 'AND',
		];

		if ( 'AND' === $relation ) {

			foreach ( $user_queries as $query ) {

				if ( isset( $query['terms'] ) && is_array( $query['terms'] ) && count( $query['terms'] ) > 1 ) {

					foreach ( $query['terms'] as $single_term ) {
						$final_tax_query[] = [
							'taxonomy' => $query['taxonomy'],
							'field'    => $query['field'],
							'terms'    => [ $single_term ],
							'operator' => $query['operator'] ?? 'IN',
						];
					}
				} else {
					$final_tax_query[] = $query;
				}
			}
		} else {

			$or_group = [
				'relation' => 'OR',
			];

			foreach ( $user_queries as $query ) {
				$or_group[] = $query;
			}

			$final_tax_query[] = $or_group;
		}

		foreach ( $visibility_queries as $vis_query ) {
			$final_tax_query[] = $vis_query;
		}

		$wp_query_args['tax_query'] = $final_tax_query; // phpcs:ignore

		return $wp_query_args;
	}



	/**
	 * Define extra query arguments for out of stock visibility.
	 *
	 * @param array $wp_query_args The original WooCommerce product query arguments.
	 *
	 * @return array
	 */
	public static function extra_query_args_outofstock( $wp_query_args ) {
		$product_visibility_terms     = wc_get_product_visibility_term_ids();
		$wp_query_args['tax_query'][] = [
			'taxonomy' => 'product_visibility',
			'field'    => 'term_taxonomy_id',
			'terms'    => [ $product_visibility_terms['outofstock'] ],
			'operator' => 'NOT IN',
		];

		return $wp_query_args;
	}

	/**
	 * Flags invalid postcode in checkout update fragments.
	 *
	 * Validates billing/shipping postcode against the selected country
	 * and adds a hidden flag element so the JS can clear the field once.
	 *
	 * @param array $fragments Checkout AJAX fragments.
	 *
	 * @return array
	 */
	public static function validate_postcode_fragment( $fragments ) {
		$customer = WC()->customer;

		if ( ! $customer ) {
			return $fragments;
		}

		$invalid_fields = [];

		$billing_postcode = $customer->get_billing_postcode();
		$billing_country  = $customer->get_billing_country();

		if ( $billing_postcode && $billing_country && ! \WC_Validation::is_postcode( $billing_postcode, $billing_country ) ) {
			$invalid_fields[] = 'billing';
		}

		$shipping_postcode = $customer->get_shipping_postcode();
		$shipping_country  = $customer->get_shipping_country();

		if ( $shipping_postcode && $shipping_country && ! \WC_Validation::is_postcode( $shipping_postcode, $shipping_country ) ) {
			$invalid_fields[] = 'shipping';
		}

		// Always return the fragment so the placeholder stays in the DOM.
		// When valid, an empty placeholder replaces the previous flag.
		if ( ! empty( $invalid_fields ) ) {
			$fragments['.rtsb-postcode-invalid'] = '<div class="rtsb-postcode-invalid" data-fields="' . esc_attr( implode( ',', $invalid_fields ) ) . '" style="display:none;"></div>';
		} else {
			$fragments['.rtsb-postcode-invalid'] = '<div class="rtsb-postcode-invalid" style="display:none;"></div>';
		}

		return $fragments;
	}

	/**
	 * Theme stylesheet handle.
	 *
	 * @return string
	 */
	public static function theme_stylesheet_handle() {
		$theme_slug = get_template();

		switch ( $theme_slug ) {
			case 'hello-elementor':
				return 'hello-elementor';

			case 'astra':
				return 'astra-theme-css';

			case 'generatepress':
				return 'generate-style';

			case 'neve':
				return 'neve-style';

			case 'kadence':
				return 'kadence-style';

			case 'storefront':
				return 'storefront-style';

			case 'oceanwp':
				return 'oceanwp-style';

			case 'twentytwentyfour':
				return 'twentytwentyfour-style';

			case 'twentytwentyone':
				return 'twentytwentyone-style';

			default:
				return $theme_slug . '-main';
		}
	}
}
