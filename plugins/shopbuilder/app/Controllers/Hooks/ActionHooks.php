<?php
/**
 * Main ActionHooks class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\Hooks;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Helpers\Cache;
use RadiusTheme\SB\Models\GeneralList;
use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Models\TemplateSettings;
use RadiusTheme\SB\Elementor\Render\Render;
use RadiusTheme\SB\Elementor\Helper\RenderHelpers;
use RadiusTheme\SB\Modules\AbandonedCartRecovery\CartRecoveryCron;

defined( 'ABSPATH' ) || exit();

/**
 * Main ActionHooks class.
 */
class ActionHooks {
	/**
	 * Init Hooks.
	 *
	 * @return void
	 */
	public static function init_hooks() {
		add_action( 'rtsb/before/save/options', [ __CLASS__, 'clear_cron_schedule' ], 10, 3 );
		// Always register custom cron schedules so already-scheduled rtsb_* events
		// can reschedule even when the Abandoned Cart Recovery module is inactive,
		// preventing the "invalid_schedule" (Event schedule does not exist) error.
		add_filter( 'cron_schedules', [ CartRecoveryCron::instance(), 'abandoned_cart_cron' ] ); // phpcs:ignore WordPress.WP.CronInterval.ChangeDetected
		add_action( 'wp_head', [ __CLASS__, 'og_metatags_for_sharing' ], 1 );
		add_action( 'wp_head', [ __CLASS__, 'reveal_builder_content_css' ], 2 );
		add_filter( 'style_loader_tag', [ __CLASS__, 'defer_non_critical_styles' ], 10, 4 );
		add_action( 'woocommerce_share', [ __CLASS__, 'shopbuilder_share' ], 20 );
		add_action( 'rtsb/wcqv/product/summary', [ __CLASS__, 'shopbuilder_share' ], 40 );
		add_action( 'rtsb/shop/product/image', [ __CLASS__, 'render_image' ], 10, 2 );

		add_action(
			'admin_init',
			function () {
				remove_post_type_support( BuilderFns::$post_type_tb, 'revisions' );
			}
		);
		add_action( 'deleted_post', [ __CLASS__, 'deleted_post' ], 10, 2 );
		add_action( 'rtsb/elements/render/before_query', [ __CLASS__, 'modify_wc_query_args' ] );
		add_action( 'rtsb/elements/render/after_query', [ __CLASS__, 'reset_wc_query_args' ] );
		// Cart Table.
		remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
		add_action( 'woocommerce_cart_is_empty', [ __CLASS__, 'cart_is_empty' ], 10 );
		/**
		 * Notices.
		 */
		remove_action( 'woocommerce_cart_is_empty', 'woocommerce_output_all_notices', 5 );
		remove_action( 'woocommerce_shortcode_before_product_cat_loop', 'woocommerce_output_all_notices', 10 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
		remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
		remove_action( 'woocommerce_before_cart', 'woocommerce_output_all_notices', 10 );
		remove_action( 'woocommerce_before_checkout_form_cart_notices', 'woocommerce_output_all_notices', 10 );
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10 );
		remove_action( 'woocommerce_account_content', 'woocommerce_output_all_notices', 5 );
		remove_action( 'woocommerce_before_customer_login_form', 'woocommerce_output_all_notices', 10 );
		remove_action( 'woocommerce_before_lost_password_form', 'woocommerce_output_all_notices', 10 );
		remove_action( 'before_woocommerce_pay', 'woocommerce_output_all_notices', 10 );
		remove_action( 'woocommerce_before_reset_password_form', 'woocommerce_output_all_notices', 10 );

		/**
		 * Notices.
		 */
		add_action( 'woocommerce_shortcode_before_product_cat_loop', [ Fns::class, 'woocommerce_output_all_notices' ], 10 );
		add_action( 'woocommerce_before_shop_loop', [ Fns::class, 'woocommerce_output_all_notices' ], 10 );
		add_action( 'woocommerce_before_single_product', [ Fns::class, 'woocommerce_output_all_notices' ], 10 );
		add_action( 'woocommerce_before_cart', [ Fns::class, 'woocommerce_output_all_notices' ], 10 );
		add_action( 'woocommerce_before_checkout_form_cart_notices', [ Fns::class, 'woocommerce_output_all_notices' ], 10 );
		add_action( 'woocommerce_before_checkout_form', [ Fns::class, 'woocommerce_output_all_notices' ], 10 );
		add_action( 'woocommerce_account_content', [ Fns::class, 'woocommerce_output_all_notices' ], 5 );
		add_action( 'woocommerce_before_customer_login_form', [ Fns::class, 'woocommerce_output_all_notices' ], 10 );
		add_action( 'woocommerce_before_lost_password_form', [ Fns::class, 'woocommerce_output_all_notices' ], 10 );
		add_action( 'before_woocommerce_pay', [ Fns::class, 'woocommerce_output_all_notices' ], 10 );
		add_action( 'woocommerce_before_reset_password_form', [ Fns::class, 'woocommerce_output_all_notices' ], 10 );

		// Checkout Page.
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
		add_action( 'woocommerce_before_checkout_form', [ __CLASS__, 'woocommerce_checkout_login_form' ], 10 );
		add_action( 'woocommerce_before_checkout_form', [ __CLASS__, 'woocommerce_checkout_coupon_form' ], 10 );
		add_action( 'woocommerce_after_checkout_form', [ __CLASS__, 'postcode_validation_placeholder' ] );
		// My Account Order Page.
		add_action( 'woocommerce_before_account_orders', [ __CLASS__, 'before_account_orders' ], 10 );
		add_action( 'woocommerce_after_account_orders', [ __CLASS__, 'after_account_orders' ], 10 );

		// Product Filter Widget Hooks.
		add_action( 'rtsb/before/archive/default/filter/items', [ __CLASS__,'default_filter_header' ], 10 );
		add_action( 'rtsb/before/archive/default/filter/items', [ __CLASS__,'default_filter_search' ], 15 );

		// WC Query modifier. Always registered so the free `rtsb-product-filters`
		// widget's URL params (product_cat, product_tag, product_brand, rating_filter,
		// sale_filter) are applied to the main WooCommerce product query regardless
		// of whether the Pro add-on is active.
		add_action( 'woocommerce_product_query', [ __CLASS__, 'query_modifier' ], 15 );

		add_action( 'switch_theme', [ __CLASS__, 'clear_cache' ] );
	}
	/**
	 * Clear scheduled cron events when a module is deactivated.
	 *
	 * Checks the given block ID against predefined schedules and clears
	 * related cron hooks if the corresponding module is turned off.
	 *
	 * @param string $section_id Section identifier (not used in this function).
	 * @param string $block_id   Module/block identifier to check scheduled hooks.
	 * @param array  $rawOptions Module options array containing 'active' status.
	 *
	 * @return void
	 */
	public static function clear_cron_schedule( $section_id, $block_id, $rawOptions ) {
		$schedule = [
			'abandoned_cart_recovery' => [
				'rtsb_abandoned_cart_recovery_action',
				'rtsb_send_abandoned_cart_emails',
			],
			'currency_switcher'       => [
				'update_exchange_rates_hourly',
			],
		];
		if ( ! isset( $schedule[ $block_id ] ) ) {
			return;
		}
		if ( ! isset( $rawOptions['active'] ) ) {
			return;
		}
		if ( 'on' === $rawOptions['active'] ) {
			return;
		}
		// CLear All Custom Schedule If module disabled.
		foreach ( $schedule[ $block_id ] as $hook ) {
			 wp_clear_scheduled_hook( $hook );
		}
	}

	/**
	 * @param boolen $has_orders Order exist or not.
	 *
	 * @return void
	 */
	public static function after_account_orders( $has_orders ) {
		if ( ! $has_orders ) {
			echo '</div>';
		}
	}

	/**
	 * @param boolen $has_orders Order exist or not.
	 *
	 * @return void
	 */
	public static function before_account_orders( $has_orders ) {
		if ( ! $has_orders ) {
			echo '<div class="rtsb-notice">';
		}
	}


	/**
	 * Render postcode validation placeholder for fragment replacement.
	 *
	 * WooCommerce fragment replacement only targets existing DOM elements.
	 * This placeholder ensures the server-side validation flag can be
	 * injected via the update_order_review AJAX response.
	 *
	 * @return void
	 */
	public static function postcode_validation_placeholder() {
		echo '<div class="rtsb-postcode-invalid" style="display:none;"></div>';
	}

	/**
	 * @return void
	 */
	public static function woocommerce_checkout_coupon_form() {
		echo '<div class="rtsb-notice">';
			woocommerce_checkout_coupon_form();
		echo '</div>';
	}

	/**
	 * @return void
	 */
	public static function woocommerce_checkout_login_form() {
		echo '<div class="rtsb-notice">';
		woocommerce_checkout_login_form();
		echo '</div>';
	}

	/**
	 * @return void
	 */
	public static function cart_is_empty() {
		echo '<div class="rtsb-notice">';
		wc_empty_cart_message();
		echo '</div>';
	}

	/**
	 * @param int    $post_id Post Ids.
	 * @param object $post Post Object.
	 *
	 * @return void
	 */
	public static function deleted_post( $post_id, $post ) {
		if ( BuilderFns::$post_type_tb !== $post->post_type ) {
			return;
		}

		$options = BuilderFns::option_name_for_specific_product_set_default( $post_id );
		TemplateSettings::instance()->delete_option( $options );

		$template_options = BuilderFns::option_name_by_template_id( $post_id );
		TemplateSettings::instance()->delete_option( $template_options );

		$archive_options = BuilderFns::archive_option_name_by_template_id( $post_id );
		TemplateSettings::instance()->delete_option( $archive_options );

		$category_options = BuilderFns::option_name_for_specific_category_set_default( $post_id );
		TemplateSettings::instance()->delete_option( $category_options );

		$selected_category_options = BuilderFns::archive_option_name_by_template_id( $post_id );
		TemplateSettings::instance()->delete_option( $selected_category_options );

		// Delete Data From Options.
		$options_product_selected_cat = BuilderFns::option_name_product_page_selected_cat( $post_id );
		$cat_list                     = TemplateSettings::instance()->get_option( $options_product_selected_cat );
		if ( ! empty( $cat_list ) ) {
			foreach ( $cat_list as $old_slug ) {
				$OldcatList = get_term_by( 'slug', $old_slug, 'product_cat' );
				if ( ! empty( $OldcatList ) && ! is_wp_error( $OldcatList ) ) {
					delete_term_meta( $OldcatList->term_id, BuilderFns::$categories_product_page_template, $post_id );
				}
			}
		}
		TemplateSettings::instance()->delete_option( $options_product_selected_cat );

		$set_default_option = BuilderFns::option_name_product_page_specific_cat_set_default( $post_id );
		TemplateSettings::instance()->delete_option( $set_default_option );

		// Tags Remove.
		$options_product_selected_tag = BuilderFns::option_name_product_page_selected_tag( $post_id );
		$tag_list                     = TemplateSettings::instance()->get_option( $options_product_selected_tag );
		if ( ! empty( $tag_list ) ) {
			foreach ( $tag_list as $old_slug ) {
				$OldTagList = get_term_by( 'slug', $old_slug, 'product_tag' );
				if ( ! empty( $OldTagList ) && ! is_wp_error( $OldTagList ) ) {
					delete_term_meta( $OldTagList->term_id, BuilderFns::$tags_product_page_template, $post_id );
				}
			}
		}
		TemplateSettings::instance()->delete_option( $options_product_selected_tag );

		// Tag Set Default Option.
		$tag_set_default_option = BuilderFns::option_name_product_page_specific_tag_set_default( $post_id );
		TemplateSettings::instance()->delete_option( $tag_set_default_option );

		global $wpdb;
		// Prepare and execute the SQL query.
		$sql = $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %d", BuilderFns::$view_template_for_products_meta, $post_id );
		$wpdb->query( $sql ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching

		// Prepare and execute the SQL query.
		$sql = $wpdb->prepare( "DELETE FROM $wpdb->termmeta WHERE meta_key = %s AND meta_value = %d", BuilderFns::$view_template_for_archive_meta, $post_id );
		$wpdb->query( $sql ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching
	}
	/**
	 * Meta tags for social sharing.
	 *
	 * @return void
	 */
	public static function shopbuilder_share() {
		$generalList = GeneralList::instance()->get_data();

		if (
			empty( $generalList['social_share']['share_platforms_to_product_page'] ) ||
			'on' !== $generalList['social_share']['share_platforms_to_product_page'] ||
			empty( $generalList['social_share']['share_platforms'] )
		) {
			return;
		}

		$share_platforms = [];

		foreach ( $generalList['social_share']['share_platforms'] as $value ) {
			$share_platforms[ $value ] = [
				'share_items' => $value,
				'share_text'  => ucwords( $value ),
			];
		}

		$settings['share_platforms'] = $share_platforms;
		$settings['layout']          = ! empty( $generalList['social_share']['share_icon_layout'] ) ? $generalList['social_share']['share_icon_layout'] : 1;
		$settings['show_share_icon'] = 1;

		/**
		 * Implement Later ! empty( $generalList['social_share']['share_icon_show_to_product_page'] ) && 'on' === $generalList['social_share']['share_icon_show_to_product_page'];
		 */
		Fns::print_html( Render::instance()->social_share_view( 'elementor/general/social-share', $settings ), true );
	}

	/**
	 * Meta tags for social sharing.
	 *
	 * @return void
	 */
	public static function og_metatags_for_sharing() {
		global $post;

		if ( ! isset( $post ) ) {
			return;
		}

		if ( ! is_singular( 'product' ) ) {
			return;
		}

		Fns::print_html( '<meta property="og:url" content="' . get_the_permalink() . '" />', true );
		Fns::print_html( '<meta property="og:type" content="article" />', true );
		Fns::print_html( '<meta property="og:title" content="' . $post->post_title . '" />', true );
		Fns::print_html( '<meta property="og:description" content="' . wp_trim_words( $post->post_content, 150 ) . '" />' );

		$attachment = get_the_post_thumbnail_url();

		if ( ! empty( $attachment ) ) {
			Fns::print_html( '<meta property="og:image" content="' . $attachment . '" />', true );
		}

		Fns::print_html( '<meta property="og:site_name" content="' . get_bloginfo( 'name' ) . '" />', true );
		Fns::print_html( '<meta name="twitter:card" content="summary" />', true );
	}

	/**
	 * Reveal the builder content via a CSS-only failsafe.
	 *
	 * The builder wrapper is hidden by `.content-invisible`
	 * (visibility:hidden;opacity:0) to avoid a flash of unstyled content, and is
	 * normally un-hidden by JavaScript on DOMContentLoaded. On mobile that event
	 * waits for the ShopBuilder JS bundle to download+parse, so the already-loaded
	 * LCP product image cannot paint until the script runs — inflating LCP
	 * "Element Render Delay".
	 *
	 * This inlines a tiny keyframe in the <head> that reveals the content from
	 * CSS alone, capping the hidden window (default 200ms) regardless of when the
	 * JS runs. First paint is therefore driven by HTML + CSS, not JavaScript. The
	 * existing JS reveal still fires and simply removes the class earlier on fast
	 * connections. Only emitted while the gate is active.
	 *
	 * @return void
	 */
	public static function reveal_builder_content_css() {
		if ( is_admin() || ! Fns::content_invisible() ) {
			return;
		}

		/**
		 * APPLY_FILTERS: rtsb/builder/content_reveal_delay
		 *
		 * Maximum time (ms) the builder content may stay hidden before the CSS
		 * failsafe reveals it, independent of JavaScript. Lower = earlier paint,
		 * higher = longer anti-FOUC window.
		 *
		 * @param int $delay Delay in milliseconds. Default 200.
		 */
		$delay = (int) apply_filters( 'rtsb/builder/content_reveal_delay', 200 );

		$css = '.rtsb-builder-content.content-invisible{animation:rtsb-reveal-builder-content 1ms linear ' . absint( $delay ) . 'ms forwards}'
			. '@keyframes rtsb-reveal-builder-content{to{visibility:visible;opacity:1}}';

		Fns::print_html( '<style id="rtsb-content-reveal">' . $css . '</style>', true );
	}

	/**
	 * Make non-critical ShopBuilder stylesheets non-render-blocking.
	 *
	 * Certain stylesheets (e.g. the icon font) are not required to paint the
	 * first viewport, yet they sit in the <head> as render-blocking <link>s and
	 * delay FCP. This loads them with `media="print"` and swaps to `all` on load,
	 * with a <noscript> fallback so nothing breaks without JavaScript.
	 *
	 * @param string $tag    The <link> tag for the enqueued style.
	 * @param string $handle The style handle.
	 * @param string $href   The stylesheet source URL.
	 * @param string $media  The media attribute.
	 *
	 * @return string
	 */
	public static function defer_non_critical_styles( $tag, $handle, $href, $media ) {
		if ( is_admin() ) {
			return $tag;
		}

		/**
		 * APPLY_FILTERS: rtsb/frontend/non_critical_styles
		 *
		 * Handles of ShopBuilder stylesheets to load non-render-blocking. Only
		 * include styles that are NOT needed to paint the first viewport.
		 *
		 * @param array $handles Style handles. Default: icon font.
		 */
		$handles = (array) apply_filters( 'rtsb/frontend/non_critical_styles', [ 'rtsb-fonts' ] );

		if ( ! in_array( $handle, $handles, true ) || false !== strpos( $tag, "media='print'" ) ) {
			return $tag;
		}

		$deferred = preg_replace(
			"/media=(['\"])" . preg_quote( $media, '/' ) . "\\1/",
			'media="print" onload="this.media=\'all\';this.onload=null;"',
			$tag
		);

		// Fallback to the original blocking tag when JavaScript is unavailable.
		return $deferred . '<noscript>' . $tag . '</noscript>';
	}

	/**
	 * Render image.
	 *
	 * @param array $args Image args.
	 *
	 * @return void
	 */
	public static function render_image( $args ) {
		if ( $args['image_link'] ) {
			$aria_label = esc_attr(
			/* translators: Product Name */
				sprintf( __( 'Image link for Product: %s', 'shopbuilder' ), $args['title'] )
			);
			?>
			<figure>
				<a class="woocommerce-LoopProduct-link rtsb-img-link" href="<?php echo esc_url( $args['p_link'] ); ?>" aria-label="<?php echo esc_attr( $aria_label ); ?>">
					<?php
					Fns::get_product_image( $args['img_html'], $args['hover_img_html'] );
					?>
				</a>
			</figure>
			<?php
		} else {
			echo '<figure class="rtsb-img-link">';
			Fns::get_product_image( $args['img_html'], $args['hover_img_html'] );
			echo '</figure>';
		}
	}

	/**
	 * Modify WooCommerce query arguments for product retrieval.
	 *
	 * @param array $settings The settings array.
	 *
	 * @return void
	 */
	public static function modify_wc_query_args( $settings ) {
		if ( ( isset( $settings['tax_relation'] ) && 'OR' === $settings['tax_relation'] ) ||
			( isset( $settings['relation'] ) && 'OR' === $settings['relation'] ) ) {
			add_filter( 'woocommerce_product_data_store_cpt_get_products_query', [ FilterHooks::class, 'extra_query_args_or' ], 10, 2 );
		}
		if ( ( isset( $settings['tax_relation'] ) && 'AND' === $settings['tax_relation'] ) ||
			( isset( $settings['relation'] ) && 'AND' === $settings['relation'] ) ) {
			add_filter( 'woocommerce_product_data_store_cpt_get_products_query', [ FilterHooks::class, 'extra_query_args_and' ], 10, 2 );
		}

		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			add_filter( 'woocommerce_product_data_store_cpt_get_products_query', [ FilterHooks::class, 'extra_query_args_outofstock' ] );
		}
	}

	/**
	 * Reset WooCommerce query arguments for product retrieval.
	 *
	 * @param array $settings The settings array.
	 *
	 * @return void
	 */
	public static function reset_wc_query_args( $settings ) {
		if ( ( isset( $settings['tax_relation'] ) && 'OR' === $settings['tax_relation'] ) ||
			( isset( $settings['relation'] ) && 'OR' === $settings['relation'] ) ) {
			remove_filter( 'woocommerce_product_data_store_cpt_get_products_query', [ FilterHooks::class, 'extra_query_args_or' ] );
		}
		if ( ( isset( $settings['tax_relation'] ) && 'AND' === $settings['tax_relation'] ) ||
			( isset( $settings['relation'] ) && 'AND' === $settings['relation'] ) ) {
			remove_filter( 'woocommerce_product_data_store_cpt_get_products_query', [ FilterHooks::class, 'extra_query_args_and' ] );
		}

		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			remove_filter( 'woocommerce_product_data_store_cpt_get_products_query', [ FilterHooks::class, 'extra_query_args_outofstock' ] );
		}
	}
	/**
	 * Display a filter header.
	 *
	 * @param array $settings The settings.
	 *
	 * @return void
	 */
	public static function default_filter_header( $settings ) {
		if ( empty( $settings['show_filter_header'] ) && empty( $settings['filter_header_text'] ) ) {
			return;
		}

		$wrapper_class = 'rtsb-default-filter-header ';

		?>

		<div class="<?php echo esc_attr( $wrapper_class ); ?>">
			<h3><?php echo esc_html( $settings['filter_header_text'] ); ?></h3>
		</div>

		<?php
	}
	/**
	 * Display a filter search form.
	 *
	 * @param array $settings The settings.
	 *
	 * @return void
	 */
	public static function default_filter_search( $settings ) {
		if ( empty( $settings['search_form'] ) ) {
			return;
		}

		$title         = [
			'title'     => ! empty( $settings['search_title'] ) ? $settings['search_title'] : '',
			'show_icon' => ! empty( $settings['show_search_title_icon'] ),
			'icon'      => ! empty( $settings['filter_search_title_icon'] ) ? $settings['filter_search_title_icon'] : [],
		];
		$placeholder   = ! empty( $settings['search_placeholder'] ) ? $settings['search_placeholder'] : '';
		$btn_icon      = ! empty( $settings['search_title_icon'] ) ? $settings['search_title_icon'] : [];
		$wrapper_class = 'rtsb-default-filter-search rtsb-default-product-search';
		$unique_id     = substr( uniqid(), -6 );
		$search_term   = isset( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		?>

		<div class="<?php echo esc_attr( $wrapper_class ); ?>">
			<?php
			Fns::print_html( RenderHelpers::get_default_filter_title( $title ) );
			?>
			<div class="default-search-content">
				<div class="woocommerce-product-search<?php echo ! empty( $search_term ) ? ' active' : ''; ?>">
					<div class="search-input">
						<label class="screen-reader-text" for="rtsb-product-default-search-form-<?php echo esc_attr( $unique_id ); ?>"><?php echo esc_html( $placeholder ); ?></label>
						<input type="search" id="rtsb-product-default-search-form-<?php echo esc_attr( $unique_id ); ?>" class="rtsb-default-search-field" placeholder="<?php echo esc_html( $placeholder ); ?>" value="<?php echo esc_attr( $search_term ); ?>" name="s" autocomplete="off">
					</div>
					<button class="rtsb-search-submit">
						<?php

							Fns::print_html( Fns::icons_manager( $btn_icon, 'search-icon' ) );

						?>
						<span></span>
					</button>
				</div>
			</div>
		</div>

		<?php
	}
	/**
	 * Query modifier.
	 *
	 * @param object $query Query.
	 * @return object
	 */
	public static function query_modifier( $query ) {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		// Sale filter.
		$onsale_filter    = isset( $_GET['sale_filter'] ) ? sanitize_text_field( wp_unslash( $_GET['sale_filter'] ) ) : '';
		$on_sale_products = wc_get_product_ids_on_sale();

		if ( 'onsale' === $onsale_filter ) {
			$query->set( 'post__in', ! empty( $on_sale_products ) ? $on_sale_products : [ 0 ] );
		} elseif ( 'regular' === $onsale_filter ) {
			$query->set( 'post__not_in', $on_sale_products );
		}

		// Taxonomy filters (product_cat, product_tag, product_brand) from URL.
		$tax_query = (array) $query->get( 'tax_query' );

		$taxonomy_map = [
			'product_cat'   => 'product_cat',
			'product_tag'   => 'product_tag',
			'product_brand' => 'product_brand',
		];

		foreach ( $taxonomy_map as $param => $taxonomy ) {
			if ( empty( $_GET[ $param ] ) || ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}

			$raw_values = wc_clean( wp_unslash( $_GET[ $param ] ) );
			$slugs      = array_filter( array_map( 'urldecode', explode( ',', $raw_values ) ) );

			if ( empty( $slugs ) ) {
				continue;
			}

			$tax_query[] = [
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $slugs,
				'operator' => 'IN',
			];
		}

		// Rating filter (rating_filter=4,5).
		if ( ! empty( $_GET['rating_filter'] ) ) {
			$raw_ratings = wc_clean( wp_unslash( $_GET['rating_filter'] ) );
			$ratings     = array_filter( array_map( 'intval', explode( ',', $raw_ratings ) ) );

			if ( ! empty( $ratings ) ) {
				$rating_terms = [];
				foreach ( $ratings as $rating ) {
					$rating_terms[] = 'rated-' . $rating;
				}

				$tax_query[] = [
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => $rating_terms,
					'operator' => 'IN',
				];
			}
		}

		if ( ! empty( $tax_query ) ) {
			$query->set( 'tax_query', $tax_query );
		}

		// phpcs:enable WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		return $query;
	}

	/**
	 * Delete cache when switching to another theme.
	 *
	 * @return void
	 */
	public static function clear_cache() {
		Cache::clear_all_cache();
	}
}
