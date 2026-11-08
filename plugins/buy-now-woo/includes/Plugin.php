<?php

namespace Buy_Now_Woo;

use Buy_Now_Woo\Admin\Settings;
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
/**
 * Set up and initialize
 */
class Plugin {
	/**
	 *  The instance.
	 *
	 * @var void
	 */
	private static $instance;

	/**
	 * Status.
	 *
	 * @var string
	 */
	private $enabled = 'yes';

	/**
	 * Redirect.
	 *
	 * @var string
	 */
	private $redirect = 'popup';

	/**
	 * Position.
	 *
	 * @var string
	 */
	private $position = 'before';

	/**
	 * Returns the instance.
	 */
	public static function get_instance() {

		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Actions setup
	 */
	public function __construct() {
		if ( $this->is_enabled() ) {
			add_action( 'plugins_loaded', array( $this, 'i18n' ), 3 );
			add_action( 'wp_ajax_wsb_add_to_cart_ajax', array( $this, 'add_to_cart_ajax' ) );
			add_action( 'wp_ajax_nopriv_wsb_add_to_cart_ajax', array( $this, 'add_to_cart_ajax' ) );
			add_filter( 'body_class', array( $this, 'body_class' ) );

			if ( ! $this->is_redirect() ) {
				add_action( 'wp_footer', array( $this, 'add_checkout_template' ) );
			}

			$this->handle_button_positions();

			add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'handle_catalog_button_positions' ), 20, 3 );

			add_action( 'wsb_before_add_to_cart', array( $this, 'reset_cart' ), 10 );
			// add_filter( 'woocommerce_is_checkout', array( $this, 'woocommerce_is_checkout' ) );
			add_shortcode( 'buy_now_woo_button', array( $this, 'add_shortcode_button' ) );

			$this->handle_customize();

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 20 );
		}
		add_action( 'plugin_action_links_' . BUY_NOW_WOO_BASE_FILE, array( $this, 'plugin_row_action_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta_links' ), 999, 2 );

		add_filter( 'woocommerce_get_settings_pages', array( $this, 'settings_page' ) );
	}
	/**
	 * Add links to plugin's description in plugins table
	 *
	 * @param  array  $links Initial list of links.
	 * @param  string $file  Basename of current plugin.
	 * @return array
	 */
	public function plugin_row_meta_links( $links, $file ) {
		if ( BUY_NOW_WOO_BASE_FILE !== $file ) {
			return $links;
		}

		$cidw_doc     = '<a target="_blank" href="' .  'https://www.codeixer.com/docs/buy-now-button-for-woocommerce/' . '" title="' . __( 'Docs & FAQs', 'buy-now-woo' ) . '">' . __( 'Docs', 'buy-now-woo' ) . '</a>';
		$cidw_support = '<a style="color:#583fad;font-weight: 600;" target="_blank" href="https://codeixer.com/contact-us/" title="' . __( 'Get help', 'buy-now-woo' ) . '">' . __( 'Support', 'buy-now-woo' ) . '</a>';

		$cidw_review = '<a target="_blank" title="Click here to rate and review this plugin on WordPress.org" href="https://wordpress.org/support/plugin/buy-now-woo/reviews/?filter=5"> Rate this plugin » </a>';

		$links[] = $cidw_doc;
		$links[] = $cidw_support;
		$links[] = $cidw_review;
		return $links;
	}
	/**
	 * links in Plugin Meta
	 *
	 * @param  [array] $links
	 * @return void
	 */
	public function plugin_row_action_links( $links ) {
		$row_meta = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=wc_simple_buy_settings' ) . '">Settings</a>',

		);
		return array_merge( $links, $row_meta );
	}
	/**
	 * Add WC settings.
	 *
	 * @param  array $integrations integrations.
	 *
	 * @return array integrations
	 */
	public function settings_page( $integrations ) {
		$integrations[] = new Settings();

		return $integrations;
	}

	/**
	 * Handle button positions.
	 */
	public function handle_button_positions() {
		if ( $this->is_before_button() ) {
			add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'add_simple_buy_button' ) );
		} elseif ( $this->is_after_button() ) {
			add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'add_simple_buy_button' ), 5 );
		} elseif ( $this->is_before_quantity_input() ) {
			add_action( 'woocommerce_before_add_to_cart_quantity', array( $this, 'add_simple_buy_button' ) );
		} elseif ( $this->is_after_quantity_input() ) {
			add_action( 'woocommerce_after_add_to_cart_quantity', array( $this, 'add_simple_buy_button' ), 5 );
		} elseif ( $this->is_replace_button() ) {
			add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'add_simple_buy_button' ), 5 );
		}
	}
	/**
	 *
	 *
	 * @return void
	 */
	public function handle_catalog_button_positions( $add_to_cart_html, $product, $args ) {

		if ( $product->get_type() != 'simple' ) {
			return $add_to_cart_html;
		}

		$button_position = get_option( 'buy_now_woo_single_catelog_position', 'after' );

		if ( 'before' === $button_position ) {
			$before = $this->get_button_html();
			return $before . $add_to_cart_html;
		} elseif ( 'after' === $button_position ) {
			$after = $this->get_button_html();
			return $add_to_cart_html . $after;
		} elseif ( 'replace' === $button_position ) {
			$replace = $this->get_button_html();
			return $replace;
		}
		return $add_to_cart_html;
	}




	/**
	 * Handle customize.
	 */
	public function handle_customize() {
		new Customize();
	}

	/**
	 * Enqueue scripts.
	 */
	public function enqueue_scripts() {
		$js_file = ( BUY_NOW_DEV_MODE === true ) ? 'buy-now-woo.js' : 'buy-now-woo-min.js';
		wp_register_style( 'buy-now-woo-poppup', BUY_NOW_WOO_PLUGIN_URL . 'assets/css/jquery.modal.min.css', array(), BUY_NOW_WOO_VERSION );
		wp_register_style( 'buy-now-woo', BUY_NOW_WOO_PLUGIN_URL . 'assets/css/buy-now-woo.css', array(), BUY_NOW_WOO_VERSION );
		wp_register_script( 'buy-now-woo-poppup', BUY_NOW_WOO_PLUGIN_URL . 'assets/js/jquery.modal.min.js', array( 'jquery' ), BUY_NOW_WOO_VERSION, true );
		wp_register_script( 'buy-now-woo', BUY_NOW_WOO_PLUGIN_URL . 'assets/js/' . $js_file, array( 'jquery' ), BUY_NOW_WOO_VERSION, true );
		$button_position = get_option( 'buy_now_woo_single_catelog_position', 'after' );

		if ( is_product() || ( cdx_is_catalog() && 'none' != $button_position ) ) {

			if ( ! $this->is_redirect() ) {
				wp_enqueue_script( 'buy-now-woo-poppup' );
				wp_enqueue_style( 'buy-now-woo-poppup' );
				wp_enqueue_script( 'wc-checkout' );
			}
			wp_enqueue_style( 'buy-now-woo' );
			wp_enqueue_script( 'buy-now-woo' );

			wp_localize_script(
				'buy-now-woo',
				'buy_now_woo',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'wsb_buy_now_nonce' ),
				)
			);
		}

		/**
		 * Fires enqueue scripts.
		 *
		 * @param Plugin Plugin main class.
		 */
		do_action( 'wsb_enqueue_scripts', $this );
	}

	/**
	 * Fake woocommerce checkout page.
	 *
	 * @param bool $is_checkout Is checkout page?.
	 *
	 * @return bool
	 */
	public function woocommerce_is_checkout( $is_checkout ) {
		if ( is_product() ) {
			return true;
		}

		return $is_checkout;
	}

	/**
	 * Translations.
	 */
	public function i18n() {
		load_plugin_textdomain( 'buy-now-woo', false, 'buy-now-woo/languages' );
	}

	/**
	 * Add class to body tag with check availability page.
	 *
	 * @param  array $classes classes.
	 *
	 * @return array
	 */
	public function body_class( $classes ) {
		if ( is_product() ) {
			$button_position = get_option( 'buy_now_woo_single_product_position' );
			$classes[]       = 'buy-now-woo';
			$classes[]       = 'buy-now-woo--button-' . esc_attr( $button_position ) . '-cart';

			if ( $this->is_remove_quantity() ) {
				$classes[] = 'buy-now-woo--remove-quantity';
			}
		}

		return $classes;
	}

	/**
	 * Is enable.
	 *
	 * @return boolean
	 */
	public function is_enabled() {
		$enabled = get_option( 'buy_now_woo_single_product_enable', $this->enabled );

		return $enabled && 'no' !== $enabled;
	}

	/**
	 * Gets redirect.
	 *
	 * @return string
	 */
	public function get_redirect() {
		return get_option( 'buy_now_woo_redirect', $this->redirect );
	}

	/**
	 * Gets position button.
	 *
	 * @return string
	 */
	public function get_position() {
		return get_option( 'buy_now_woo_single_product_position', $this->position );
	}

	/**
	 * Gets button title.
	 *
	 * @return string
	 */
	public function get_button_title() {
		$title = esc_html__( 'Buy Now', 'buy-now-woo' );

		if ( get_option( 'buy_now_woo_single_product_button' ) ) {
			$title = get_option( 'buy_now_woo_single_product_button' );
		}

		return $title;
	}

	/**
	 * Is use pop-up?
	 *
	 * @return boolean
	 */
	public function is_popup() {
		return ( 'popup' === $this->get_redirect() );
	}

	/**
	 * Is redirect to the checkout page?
	 *
	 * @return boolean
	 */
	public function is_redirect() {
		return ( 'checkout' === $this->get_redirect() );
	}

	/**
	 * If button position is before `add to cart` button.
	 *
	 * @return boolean
	 */
	public function is_before_button() {
		return ( 'before' === $this->get_position() );
	}

	/**
	 * If button position is after `add to cart` button.
	 *
	 * @return boolean
	 */
	public function is_after_button() {
		return ( 'after' === $this->get_position() );
	}

	/**
	 * If `buy now` button replace `add to cart` button
	 *
	 * @return boolean
	 */
	public function is_replace_button() {
		return ( 'replace' === $this->get_position() );
	}

	/**
	 * If button position is before `quantity` input.
	 *
	 * @return boolean
	 */
	public function is_before_quantity_input() {
		return ( 'before_quantity' === $this->get_position() );
	}

	/**
	 * If button position is after `quantity` input.
	 *
	 * @return boolean
	 */
	public function is_after_quantity_input() {
		return ( 'after_quantity' === $this->get_position() );
	}

	/**
	 * If button position is after `quantity` input.
	 *
	 * @return boolean
	 */
	public function is_shortcode() {
		return ( 'shortcode' === $this->get_position() );
	}

	/**
	 * If remove quantity input.
	 *
	 * @return boolean
	 */
	public function is_remove_quantity() {
		$remove_quantity = get_option( 'buy_now_woo_single_product_remove_quantity' );

		return ( $remove_quantity && 'no' !== $remove_quantity );
	}


	/**
	 * Add popup to cart form in single product page.
	 */
	public function add_simple_buy_button() {
		$args = $this->get_button_default_args();

		$this->button_template( $args );
	}

	/**
	 * Get button HTML.
	 *
	 * @return string
	 */
	public function get_button_html() {
		ob_start();
		$this->add_simple_buy_button();
		return ob_get_clean();
	}

	/**
	 * Button template.
	 *
	 * @param  array $args arguments.
	 *
	 * @return void
	 */
	public function button_template( $args ) {
		global $product;

		$type    = isset( $args['type'] ) ? esc_attr( $args['type'] ) : 'submit';
		$classes = isset( $args['class'] ) && is_array( $args['class'] ) ? implode( ' ', array_map( 'sanitize_html_class', $args['class'] ) ) : '';
		$atts    = '';

		if ( ! empty( $args['attributes'] ) && is_array( $args['attributes'] ) ) {
			foreach ( $args['attributes'] as $attr_key => $attr_val ) {
				$atts .= sprintf( '%s="%s" ', esc_attr( $attr_key ), esc_attr( $attr_val ) );
			}
		} elseif ( ! empty( $args['attributes'] ) && is_string( $args['attributes'] ) ) {
			$atts = $args['attributes'];
		}

		$button_title = isset( $args['title'] ) ? esc_html( $args['title'] ) : '';
		$product_id   = is_object( $product ) && method_exists( $product, 'get_id' ) ? $product->get_id() : '';
		?>
		<button type="<?php echo esc_attr( $type ); ?>" name="wsb-buy-now" value="<?php echo esc_attr( $product_id ); ?>" class="<?php echo esc_attr( $classes ); ?>" <?php echo esc_attr( $atts ); ?>><?php echo esc_html( $button_title ); ?></button>
		<?php
	}

	/**
	 * Add checkout template.
	 */
	public function add_checkout_template() {
		$button_position = get_option( 'buy_now_woo_single_catelog_position', 'after' );
		if ( is_product() || ( cdx_is_catalog() && 'none' != $button_position ) ) {
			?>
			<div class="modal wsb-modal wsb-modal-content" data-modal>
				
					<?php do_action( 'wsb_modal_header_content' ); ?>

					<?php do_action( 'wsb_before_modal_body_content' ); ?>

					<?php do_action( 'wsb_after_modal_body_content' ); ?>
					
			</div>
			<?php
		}
	}

	/**
	 * Add product to cart via ajax function.
	 */
	public function add_to_cart_ajax() {
		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
			wp_die();
		}

		$nonce          = isset( $_POST['wsb-nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wsb-nonce'] ) ) : '';
		$wsb_product_id = isset( $_POST['wsb-buy-now'] ) ? absint( $_POST['wsb-buy-now'] ) : 0;
		if ( ! wp_verify_nonce( $nonce, 'wsb_buy_now_nonce' ) ) {
			wp_die( esc_html__( 'Security check failed. Please refresh the page and try again.', 'buy-now-woo' ) );
		}

		$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $wsb_product_id ) );

		/**
		 * Fires before add to cart via ajax.
		 *
		 * @param int $product_id Product ID.
		 */
		do_action( 'wsb_before_add_to_cart', $product_id );

		try {
			$_REQUEST['add-to-cart'] = $product_id;

			add_filter(
				'pre_option_woocommerce_cart_redirect_after_add',
				function ( $option ) {
					return 'no';
				}
			);

			\WC_Form_Handler::add_to_cart_action();

			/**
			 * Filters the template of checkout form after add to cart.
			 *
			 * @param array $results results.
			 */
			$results = apply_filters(
				'wsb_checkout_template',
				array(
					'redirect'     => $this->is_redirect(),
					'checkout_url' => esc_url( wc_get_checkout_url() ),
					'template'     => do_shortcode( '[woocommerce_checkout]' ),

				)
			);

			wp_send_json_success( $results );

		} catch ( \Exception $e ) {
			wp_send_json_error( array( 'message' => esc_html( $e->getMessage() ) ) );
		}
	}

	/**
	 * Reset cart before Buy Now.
	 */
	public function reset_cart() {
		$reset_cart = get_option( 'buy_now_woo_single_product_reset_cart' );

		if ( $reset_cart && 'no' !== $reset_cart ) {
			// Remove all products in cart.
			WC()->cart->empty_cart();
		}
	}

	/**
	 * Register shortcode button
	 *
	 * @param array $atts Attributes.
	 */
	public function add_shortcode_button( $atts ) {
		$atts = shortcode_atts( $this->get_button_default_args(), $atts, 'buy_now_woo_button' );

		ob_start();

		$this->button_template( $atts );

		return ob_get_clean();
	}

	/**
	 * Gets button default args
	 *
	 * @return array
	 */
	public function get_button_default_args() {
		$btn_class = apply_filters(
			'wsb_single_product_button_classes',
			array(
				'wsb-button',
				'js-wsb-add-to-cart',
			)
		);

		return apply_filters(
			'wsb_buy_now_button_args',
			array(
				'type'       => 'submit',
				'class'      => $btn_class,
				'title'      => esc_html( $this->get_button_title() ),
				'attributes' => '',
			),
			$this->get_redirect(),
			$this->get_position()
		);
	}
}
