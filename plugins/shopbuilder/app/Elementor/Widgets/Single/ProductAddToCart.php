<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Single;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\AddToCartSettings;
use RadiusTheme\SBPRO\Elementor\Widgets\Controls\CatalogButtonSettings;
use RadiusTheme\SBPRO\Modules\CatalogMode\CatalogModeFns;
use RadiusTheme\SBPRO\Modules\CatalogMode\CatalogModeFrontEnd;
use RadiusTheme\SBPRO\Modules\BackInStockNotifier\BackInStockNotifierFrontEnd;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ProductAddToCart extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Product add to cart', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-product-add-to-cart';
		parent::__construct( $data, $args );
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		if ( Fns::is_catalog_mode() ) {
			return CatalogButtonSettings::settings( $this );
		} else {
			return AddToCartSettings::widget_fields( $this );
		}
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'Cart' ] + parent::get_keywords();
	}
	/**
	 * Widget Field.
	 *
	 * @return void
	 */
	public function the_hooks() {
		$controllers = $this->get_settings_for_display();
		add_filter( 'rtsb/module/flash_sale_countdown/show_counter', '__return_false', 99 );
		$product        = Fns::get_product();
		$is_visible_qty = Fns::is_visible_qty_input( $product );

		if ( ! empty( $controllers['quantity_style'] ) && $is_visible_qty ) {
			if ( in_array( $controllers['quantity_style'], [ 'style-1', 'style-2' ], true ) ) {
				add_action( 'woocommerce_before_quantity_input_field', [ $this, 'quantity_wrapper_start' ] );
				add_action( 'woocommerce_after_quantity_input_field', [ $this, 'quantity_wrapper_end' ] );
			}

			if ( in_array( $controllers['quantity_style'], [ 'style-3', 'style-4' ], true ) && $is_visible_qty ) {
				add_action( 'woocommerce_before_quantity_input_field', [ $this, 'quantity_wrapper_with_inner_border' ] );
				add_action( 'woocommerce_after_quantity_input_field', [ $this, 'close_quantity_wrapper' ] );
			}
		}

		if ( $this->is_edit_mode() ) {
			if ( class_exists( Rtwpvs\Controllers\Hooks::class ) ) {
				remove_filter( 'woocommerce_ajax_variationX_threshold', [ Rtwpvs\Controllers\Hooks::class, 'ajax_variation_threshold' ], 99 );
			}

			add_filter( 'woocommerce_ajax_variation_threshold', [ $this, 'override_ajax_variation_threshold' ], 99 );
		}
	}
	/**
	 * Start quantity wrapper.
	 *
	 * @return void
	 */
	public function reset_hooks() {
		$controllers    = $this->get_settings_for_display();
		$product        = Fns::get_product();
		$is_visible_qty = Fns::is_visible_qty_input( $product );
		if ( ! empty( $controllers['quantity_style'] ) && $is_visible_qty ) {
			if ( in_array( $controllers['quantity_style'], [ 'style-1', 'style-2' ], true ) ) {
				remove_action( 'woocommerce_before_quantity_input_field', [ $this, 'quantity_wrapper_start' ] );
				remove_action( 'woocommerce_after_quantity_input_field', [ $this, 'quantity_wrapper_end' ] );
			}

			if ( in_array( $controllers['quantity_style'], [ 'style-3', 'style-4' ], true ) && $is_visible_qty ) {
				remove_action( 'woocommerce_before_quantity_input_field', [ $this, 'quantity_wrapper_with_inner_border' ] );
				remove_action( 'woocommerce_after_quantity_input_field', [ $this, 'close_quantity_wrapper' ] );
			}
		}
	}
	/**
	 * Start quantity wrapper.
	 *
	 * @return void
	 */
	public function quantity_wrapper_start() {
		$controllers = $this->get_settings_for_display();
		?>
		<!-- Quantity Wrapper Start -->
		<div class="rtsb-quantity-box-group rtsb-quantity-box-group-<?php echo esc_attr( $controllers['quantity_style'] ); ?>"">
			<button type="button" class="rtsb-quantity-btn rtsb-quantity-minus">
				<?php Fns::print_html( Fns::icons_manager( $controllers['decrement_icon'] ), true ); ?>
			</button>
		<?php
	}

	/**
	 * End quantity wrapper.
	 *
	 * @return void
	 */
	public function quantity_wrapper_end() {
		$controllers = $this->get_settings_for_display();
		?>
			<button type="button" class="rtsb-quantity-btn rtsb-quantity-plus">
				<?php Fns::print_html( Fns::icons_manager( $controllers['increment_icon'] ), true ); ?>
			</button>
		</div>
		<!-- Quantity Wrapper End -->
		<?php
	}

	/**
	 * Start quantity wrapper with inner border.
	 *
	 * @return void
	 */
	public function quantity_wrapper_with_inner_border() {
		$controllers  = $this->get_settings_for_display();
		$inner_border = ! empty( $controllers['show_inner_border'] ) ? 'show-inner-border' : '';
		?>
		<!-- Quantity Wrapper Start -->
		<div class="rtsb-quantity-box-group rtsb-quantity-box-group-<?php echo esc_attr( $controllers['quantity_style'] . ' ' . $inner_border ); ?>">
		<div class="rtsb-qty-btns-group">
			<button type="button" class="rtsb-quantity-btn rtsb-quantity-plus">
				<?php Fns::print_html( Fns::icons_manager( $controllers['increment_icon'] ), true ); ?>
			</button>
			<button type="button" class="rtsb-quantity-btn rtsb-quantity-minus">
				<?php Fns::print_html( Fns::icons_manager( $controllers['decrement_icon'] ), true ); ?>
			</button>
		</div>
		<?php
	}

	/**
	 * Close quantity wrapper.
	 *
	 * @return void
	 */
	public function close_quantity_wrapper() {
		?>
		</div>
		<!-- Quantity Wrapper End -->
		<?php
	}
	/**
	 * Render catalog mode contact buttons
	 *
	 * @param int $product_id Product ID.
	 * @return void
	 */
	public function catalog_mode_buttons( $product_id ) {
		$settings        = CatalogModeFns::get_options();
		$contact_buttons = $settings['contact_buttons'] ?? [];
		if ( empty( $contact_buttons ) || ! is_array( $contact_buttons ) ) {
			return;
		}

		echo '<div class="rtsb-product-add-to-cart">';
		echo '<div class="rtsb-catalog-mode-product-page-buttons">';

		foreach ( $contact_buttons as $button_type ) {
			$this->render_contact_button( $button_type, $product_id, $settings );
		}
		echo '</div>';
		echo '</div>';
	}
	/**
	 * Render specific contact button
	 *
	 * @param string $button_type Button type.
	 * @param int    $product_id Product ID.
	 * @param array  $settings Plugin settings.
	 * @return void
	 */
	public function render_contact_button( $button_type, $product_id, $settings ) {
		switch ( $button_type ) {
			case 'custom':
				CatalogModeFrontEnd::render_custom_button( $product_id, $settings );
				break;
			case 'whatsapp':
				CatalogModeFrontEnd::render_whatsapp_button( $product_id, $settings );
				break;
			case 'call':
				CatalogModeFrontEnd::render_call_button( $settings );
				break;
			case 'email':
				CatalogModeFrontEnd::render_email_button( $settings );
				break;
			case 'inquiry':
				CatalogModeFrontEnd::render_inquiry_button( $product_id, $settings );
				break;
		}
	}

	/**
	 * Show an editor-only notice when the add to cart button is hidden
	 * because the product is out of stock.
	 *
	 * @param \WC_Product|null $product Current product.
	 * @return void
	 */
	public function out_of_stock_editor_notice( $product ) {
		if ( ! $product instanceof \WC_Product || $product->is_in_stock() ) {
			return;
		}
		$this->editor_notice(
			esc_html__( 'The add to cart button and quantity are hidden because this product is out of stock.', 'shopbuilder' ),
			'rtsb-add-to-cart-editor-notice'
		);
	}

	/**
	 * Override WooCommerce AJAX variation threshold.
	 *
	 * @return int
	 */
	public function override_ajax_variation_threshold() {
		return 1;
	}

	/**
	 * Elementor Edit mode need some extra js for isotop reinitialize
	 *
	 * @return void
	 */
	public function editor_script() {
		if ( $this->is_edit_mode() ) {
			?>
			<script type="text/javascript">
				function addToCartAction() {
					var singleAddToCart = jQuery('.rtsb-product-add-to-cart');

					if (singleAddToCart.length > 0) {
						singleAddToCart
							.find(
								'.stock.available-on-pre-order, .stock.available-on-backorder'
							)
							.remove();
					}
				}
				if (typeof elementorFrontend !== 'undefined') {
					elementorFrontend.hooks.addAction(
						'frontend/element_ready/rtsb-product-add-to-cart.default',
						() => {
							addToCartAction();
						}
					);
				}
			</script>
			<?php
		}
	}

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		global $product;
		$_product                      = $product;
		$product                       = Fns::get_product();
		$controllers                   = $this->get_settings_for_display();
		$controllers['cart_icon_html'] = ! empty( $controllers['cart_icon'] ) ? Fns::icons_manager( $controllers['cart_icon'] ) : '';
		$add_to_cart_visibility        = rtsb()->has_pro() && Fns::is_guest_feature_disabled( 'hide_add_to_cart', '' );
		$controllers['visibility']     = $add_to_cart_visibility ? ' rtsb-not-visible' : ' rtsb-is-visible';

		$this->the_hooks();
		$this->theme_support();

		if ( rtsb()->has_pro() && ! empty( $controllers['enable_single_ajax_add_to_cart'] ) ) {
			wp_enqueue_script( 'wc-add-to-cart' );
		}
		if ( Fns::is_back_in_stock_notifier_enable() ) {
			remove_filter( 'woocommerce_get_stock_html', [ BackInStockNotifierFrontEnd::instance(),'add_waitlist_to_stock_html' ], 20, 2 );
		}
		$data = [
			'template'     => 'elementor/single-product/add-to-cart',
			'controllers'  => $controllers,
			'product_type' => $product ? $product->get_type() : '',
		];
		if ( $this->is_builder_mode() && Fns::is_catalog_mode() ) {
			$this->catalog_mode_buttons( $product->get_id() );
		} else {
			Fns::load_template( $data['template'], $data );
			$this->out_of_stock_editor_notice( $product );
		}

		$this->editor_cart_icon_script();
		add_filter( 'rtsb/module/flash_sale_countdown/show_counter', '__return_true', 99 );
		$this->reset_hooks();
		$this->theme_support( 'render_reset' );
		$this->editor_script();
		$product = $_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}
}
