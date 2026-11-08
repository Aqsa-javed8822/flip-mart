<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Cart;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\CartTableSettings;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class CartTable extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Cart Table', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-product-carttable';
		parent::__construct( $data, $args );
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return CartTableSettings::widget_fields( $this );
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
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function cart_empty_message() {
		$controllers = $this->get_settings_for_display();
		return $controllers['cart_empty_message'];
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function return_to_shop_text() {
		$controllers = $this->get_settings_for_display();
		return $controllers['return_to_shop_text'];
	}

	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function before_quantity_input_field() {
		global $product;
		$is_visible_qty = Fns::is_visible_qty_input( $product );
		$controllers    = $this->get_settings_for_display();
		$inner_border   = ! empty( $controllers['show_inner_border'] ) ? 'show-inner-border' : '';

		?>
		<!-- Quantity Wrapper Start -->
		<div class="rtsb-quantity-box-group rtsb-quantity-box-group-<?php echo esc_attr( $controllers['quantity_style'] . ' ' . $inner_border ); ?>">
		<?php
		if ( in_array( $controllers['quantity_style'], [ 'style-1', 'style-2' ] ) && $is_visible_qty ) {
			?>
				<button type="button" class="rtsb-quantity-btn rtsb-quantity-minus">
					<?php Fns::print_html( Fns::icons_manager( $controllers['decrement_icon'] ), true ); ?>
				</button>
				<?php
		}
		if ( in_array( $controllers['quantity_style'], [ 'style-3', 'style-4' ] ) && $is_visible_qty ) {
			?>
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
	}

	public function after_quantity_input_field() {

		global $product;
		$is_visible_qty = Fns::is_visible_qty_input( $product );
		$controllers    = $this->get_settings_for_display();
		if ( in_array( $controllers['quantity_style'], [ 'style-1', 'style-2' ] ) && $is_visible_qty ) {
			?>
			<button type="button" class="rtsb-quantity-btn rtsb-quantity-plus">
				<?php Fns::print_html( Fns::icons_manager( $controllers['increment_icon'] ), true ); ?>
			</button>
			<?php
		}
		?>
		</div>
		<!-- Quantity Wrapper End -->
		<?php
	}

	/**
	 * @return void
	 */
	public function apply_the_hooks() {
		add_action( 'woocommerce_before_quantity_input_field', [ $this, 'before_quantity_input_field' ] );
		add_action( 'woocommerce_after_quantity_input_field', [ $this, 'after_quantity_input_field' ] );
		remove_action( 'woocommerce_before_cart', 'woocommerce_output_all_notices', 10 );
	}

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		$controllers = $this->get_settings_for_display();
		$this->apply_the_hooks();
		do_action( 'rtsb/before/product/cart/table', $controllers, $this );
		$this->theme_support();
		$data = [
			'template'    => 'elementor/cart/cart-table',
			'controllers' => $controllers,
			'is_builder'  => $this->is_builder_mode(),
		];
		if ( $data['is_builder'] ) {
			wc_load_cart();
		}

		if ( ! empty( $controllers['cart_empty_message'] ) ) {
			add_filter( 'wc_empty_cart_message', [ $this, 'cart_empty_message' ] );
		}

		if ( ! empty( $controllers['return_to_shop_text'] ) ) {
			add_filter( 'woocommerce_return_to_shop_text', [ $this, 'return_to_shop_text' ] );
		}

		// Check cart items are valid.
		do_action( 'woocommerce_check_cart_items' );

		WC()->cart->calculate_totals();

		Fns::load_template( $data['template'], $data );
		do_action( 'rtsb/after/product/cart/table', $controllers, $this );
		$this->theme_support( 'render_reset' );
	}
}
