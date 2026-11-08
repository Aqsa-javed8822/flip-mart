<?php
/**
 * Order Review Quantity Controller.
 *
 * Renders an editable quantity selector inside the checkout "Order Review"
 * table and applies the posted quantities to the cart while WooCommerce
 * refreshes the review fragment.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\Frontend;

use WC_Product;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Order Review Quantity Controller.
 */
class OrderReviewQuantity {
	/**
	 * Singleton.
	 */
	use SingletonTrait;

	/**
	 * Hidden marker field printed by the Order Review widget.
	 *
	 * Tells the update_order_review request that the quantity selector is
	 * enabled, because the Elementor widget does not render on that request.
	 *
	 * @var string
	 */
	const MARKER_FIELD = 'rtsb_order_review_qty';

	/**
	 * Quantity input name, keyed by cart item key.
	 *
	 * @var string
	 */
	const QUANTITY_FIELD = 'rtsb_review_qty';

	/**
	 * Wrapper class discount modules use for their cart item summary.
	 *
	 * @var string
	 */
	const SUMMARY_CLASS = 'rtsb-dicount-summery';

	/**
	 * Whether the quantity selector is enabled for this request.
	 *
	 * @var bool
	 */
	private $enabled = false;

	/**
	 * Cart item key whose item data has already been printed by this class.
	 *
	 * Used once, to keep the review template from repeating the meta list.
	 *
	 * @var string
	 */
	private $printed_item_data = '';

	/**
	 * Whether the review order table is currently being rendered.
	 *
	 * @var bool
	 */
	private $in_review_table = false;

	/**
	 * Discount summaries pulled out of the product name, by cart item key.
	 *
	 * @var array
	 */
	private $discount_summary = [];

	/**
	 * Class Constructor.
	 *
	 * @return void
	 */
	private function __construct() {
		// Runs before WooCommerce recalculates shipping and totals for the fragment.
		add_action( 'woocommerce_checkout_update_order_review', [ $this, 'handle_order_review_update' ], 5 );
	}

	/**
	 * Enables the quantity selector for the current request.
	 *
	 * @return void
	 */
	public function enable() {
		if ( $this->enabled ) {
			return;
		}

		$this->enabled = true;

		add_filter( 'woocommerce_checkout_cart_item_quantity', [ $this, 'quantity_field' ], 10, 3 );
		add_filter( 'woocommerce_get_item_data', [ $this, 'maybe_skip_item_data' ], PHP_INT_MAX, 2 );

		// Scope the product name filter to the review table: the same filter also
		// feeds the cart page and the mini cart, which must stay untouched.
		add_action( 'woocommerce_review_order_before_cart_contents', [ $this, 'open_review_table' ] );
		add_action( 'woocommerce_review_order_after_cart_contents', [ $this, 'close_review_table' ] );
		add_filter( 'woocommerce_cart_item_name', [ $this, 'extract_discount_summary' ], PHP_INT_MAX, 3 );
	}

	/**
	 * Marks the start of the review order table.
	 *
	 * @return void
	 */
	public function open_review_table() {
		$this->in_review_table = true;
	}

	/**
	 * Marks the end of the review order table.
	 *
	 * @return void
	 */
	public function close_review_table() {
		$this->in_review_table  = false;
		$this->discount_summary = [];
	}

	/**
	 * Takes the discount summary out of the product name.
	 *
	 * Discount modules append their summary to the name, which pushes the "x 2"
	 * counter onto the next line. It is stored here and printed again after the
	 * counter by the quantity filter.
	 *
	 * @param string $name          Product name markup.
	 * @param array  $cart_item     Cart item data.
	 * @param string $cart_item_key Cart item key.
	 *
	 * @return string
	 */
	public function extract_discount_summary( $name, $cart_item, $cart_item_key = '' ) {
		if ( ! $this->in_review_table || '' === $cart_item_key || false === strpos( $name, self::SUMMARY_CLASS ) ) {
			return $name;
		}

		list( $name, $summary ) = $this->split_discount_summary( $name );

		if ( '' !== $summary ) {
			$this->discount_summary[ $cart_item_key ] = $summary;
		}

		return $name;
	}

	/**
	 * Checks whether the quantity selector is enabled.
	 *
	 * @return bool
	 */
	public function is_enabled() {
		return $this->enabled;
	}

	/**
	 * Applies the posted quantities before the order review fragment is rebuilt.
	 *
	 * @param string $post_data Serialized checkout form data.
	 *
	 * @return void
	 */
	public function handle_order_review_update( $post_data ) {
		if ( ! is_string( $post_data ) || '' === $post_data ) {
			return;
		}

		$data = [];

		parse_str( $post_data, $data );

		if ( empty( $data[ self::MARKER_FIELD ] ) ) {
			return;
		}

		// Keep the selector in the refreshed fragment.
		$this->enable();

		$this->update_cart_quantities( $data[ self::QUANTITY_FIELD ] ?? [] );
	}

	/**
	 * Replaces the static "x quantity" markup with a quantity selector.
	 *
	 * The review template prints the item meta right after this filter, so the
	 * meta is pulled forward here and skipped afterwards. That keeps the
	 * selector below every other line of the item.
	 *
	 * @param string $html          Default quantity markup.
	 * @param array  $cart_item     Cart item data.
	 * @param string $cart_item_key Cart item key.
	 *
	 * @return string
	 */
	public function quantity_field( $html, $cart_item, $cart_item_key ) {
		$summary = '';

		if ( isset( $this->discount_summary[ $cart_item_key ] ) ) {
			$summary = wp_kses_post( $this->discount_summary[ $cart_item_key ] );

			unset( $this->discount_summary[ $cart_item_key ] );
		}

		$product = $cart_item['data'] ?? null;

		if ( ! $product instanceof WC_Product || ! Fns::is_visible_qty_input( $product ) ) {
			return $html . $summary;
		}

		$item_data               = wc_get_formatted_cart_item_data( $cart_item );
		$this->printed_item_data = $cart_item_key;

		$quantity = $cart_item['quantity'] ?? 0;
		$min      = $this->get_min_quantity( $product );
		$max      = $product->get_max_purchase_quantity();
		$step     = apply_filters( 'woocommerce_quantity_input_step', 1, $product );

		$input = sprintf(
			'<input type="number" class="rtsb-order-qty-input" name="%1$s[%2$s]" value="%3$s" min="%4$s" max="%5$s" step="%6$s" inputmode="numeric" autocomplete="off" aria-label="%7$s" />',
			esc_attr( self::QUANTITY_FIELD ),
			esc_attr( $cart_item_key ),
			esc_attr( $quantity ),
			esc_attr( $min ),
			esc_attr( $max > 0 ? $max : '' ),
			esc_attr( $step ),
			esc_attr__( 'Product quantity', 'shopbuilder' )
		);

		$field = sprintf(
			'<div class="rtsb-order-review-quantity">
				<div class="rtsb-order-qty-group">%1$s%2$s%3$s</div>
			</div>',
			$this->get_button( 'minus', $quantity <= $min ),
			$input,
			$this->get_button( 'plus', ! $this->can_increase( $product, $quantity, $step ) )
		);

		// Counter beside the product name, then the discount summary, then the meta.
		$field = $html . $summary . $item_data . $field;

		return apply_filters( 'rtsb/order_review/quantity/html', $field, $html, $cart_item, $cart_item_key );
	}

	/**
	 * Skips the item meta the review template prints after the quantity.
	 *
	 * Runs once per cart item, right after the meta was pulled forward into the
	 * quantity markup, so nothing is rendered twice.
	 *
	 * @param array $item_data Formatted item data.
	 * @param array $cart_item Cart item data.
	 *
	 * @return array
	 */
	public function maybe_skip_item_data( $item_data, $cart_item ) {
		$cart_item_key = $cart_item['key'] ?? '';

		if ( '' === $cart_item_key || $cart_item_key !== $this->printed_item_data ) {
			return $item_data;
		}

		$this->printed_item_data = '';

		return [];
	}

	/**
	 * Updates the cart with the posted quantities.
	 *
	 * @param array $quantities Posted quantities, keyed by cart item key.
	 *
	 * @return void
	 */
	private function update_cart_quantities( $quantities ) {
		if ( empty( $quantities ) || ! is_array( $quantities ) ) {
			return;
		}

		$cart = function_exists( 'WC' ) ? WC()->cart : null;

		if ( ! $cart ) {
			return;
		}

		foreach ( $quantities as $cart_item_key => $quantity ) {
			if ( ! is_scalar( $quantity ) ) {
				continue;
			}

			$cart_item_key = sanitize_text_field( wp_unslash( (string) $cart_item_key ) );
			$cart_item     = $cart->get_cart_item( $cart_item_key );
			$product       = $cart_item['data'] ?? null;

			if ( ! $product instanceof WC_Product || ! Fns::is_visible_qty_input( $product ) ) {
				continue;
			}

			$quantity = $this->clamp_quantity( wc_stock_amount( wp_unslash( $quantity ) ), $product );
			$current  = $cart_item['quantity'] ?? 0;

			if ( (string) $current === (string) $quantity ) {
				continue;
			}

			// A typed value must obey the same stock rules as the buttons.
			if ( $quantity > $current && ! $this->can_increase( $product, $current, $quantity - $current ) ) {
				continue;
			}

			// Totals are recalculated by WooCommerce right after this hook.
			$cart->set_quantity( $cart_item_key, $quantity, false );
		}
	}

	/**
	 * Keeps a quantity within the purchasable range of the product.
	 *
	 * @param int|float  $quantity Requested quantity.
	 * @param WC_Product $product  Product object.
	 *
	 * @return int|float
	 */
	private function clamp_quantity( $quantity, $product ) {
		$min = $this->get_min_quantity( $product );
		$max = $product->get_max_purchase_quantity();

		$quantity = max( $min, $quantity );

		if ( $max > 0 ) {
			$quantity = min( $max, $quantity );
		}

		return $quantity;
	}

	/**
	 * Returns the minimum quantity allowed in the order review.
	 *
	 * Never lower than 1, so a customer cannot empty the cart mid-checkout.
	 *
	 * @param WC_Product $product Product object.
	 *
	 * @return int|float
	 */
	private function get_min_quantity( $product ) {
		$min = apply_filters( 'woocommerce_quantity_input_min', 1, $product );

		return max( 1, $min );
	}

	/**
	 * Splits the discount summary wrapper out of a product name.
	 *
	 * Walks the markup counting nested divs, because the summary of some
	 * discount types wraps further divs and a plain match would cut it short.
	 *
	 * @param string $name Product name markup.
	 *
	 * @return array Name without the summary, and the summary markup.
	 */
	private function split_discount_summary( $name ) {
		$class_position = strpos( $name, self::SUMMARY_CLASS );

		if ( false === $class_position ) {
			return [ $name, '' ];
		}

		$start = strrpos( substr( $name, 0, $class_position ), '<div' );

		if ( false === $start ) {
			return [ $name, '' ];
		}

		$length = strlen( $name );
		$offset = $start;
		$depth  = 0;

		while ( $offset < $length ) {
			$open  = stripos( $name, '<div', $offset );
			$close = stripos( $name, '</div', $offset );

			if ( false === $close ) {
				break;
			}

			if ( false !== $open && $open < $close ) {
				++$depth;
				$offset = $open + 4;
				continue;
			}

			$tag_end = strpos( $name, '>', $close );

			if ( false === $tag_end ) {
				break;
			}

			--$depth;
			$offset = $tag_end + 1;

			if ( 0 === $depth ) {
				return [
					substr( $name, 0, $start ) . substr( $name, $offset ),
					substr( $name, $start, $offset - $start ),
				];
			}
		}

		return [ $name, '' ];
	}

	/**
	 * Checks whether one more step can still be added to the cart.
	 *
	 * Covers every stock situation WooCommerce knows about: out of stock,
	 * managed stock without backorders, and the max purchase quantity.
	 *
	 * @param WC_Product $product  Product object.
	 * @param int|float  $quantity Current cart quantity.
	 * @param int|float  $step     Increment step.
	 *
	 * @return bool
	 */
	private function can_increase( $product, $quantity, $step ) {
		$next = $quantity + max( 1, (float) $step );
		$max  = $product->get_max_purchase_quantity();

		$can_increase = $product->is_in_stock()
			&& $product->is_purchasable()
			&& ( $max <= 0 || $next <= $max )
			&& $product->has_enough_stock( $next );

		return (bool) apply_filters( 'rtsb/order_review/quantity/can_increase', $can_increase, $product, $quantity, $step );
	}

	/**
	 * Returns the increment/decrement button markup.
	 *
	 * @param string $type     Button type: plus or minus.
	 * @param bool   $disabled Whether the button cannot change the quantity.
	 *
	 * @return string
	 */
	private function get_button( $type, $disabled ) {
		$label = 'plus' === $type
			? esc_attr__( 'Increase quantity', 'shopbuilder' )
			: esc_attr__( 'Decrease quantity', 'shopbuilder' );

		return sprintf(
			'<button type="button" class="rtsb-order-qty-btn rtsb-order-qty-%1$s%2$s" aria-label="%3$s"%4$s>%5$s</button>',
			esc_attr( $type ),
			$disabled ? ' disabled' : '',
			$label,
			// The flag lets the script keep the button disabled until the
			// quantity drops back below the one the cart accepted.
			$disabled ? ' disabled="disabled" data-rtsb-limit="1"' : '',
			$this->get_icon( $type )
		);
	}

	/**
	 * Returns the increment/decrement icon markup.
	 *
	 * @param string $type Icon type: plus or minus.
	 *
	 * @return string
	 */
	private function get_icon( $type ) {
		if ( 'plus' === $type ) {
			return '<svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M4.25 0h1.5v4.25H10v1.5H5.75V10h-1.5V5.75H0v-1.5h4.25V0z" fill="currentColor"/></svg>';
		}

		return '<svg width="10" height="2" viewBox="0 0 10 2" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M0 0h10v2H0z" fill="currentColor"/></svg>';
	}
}
