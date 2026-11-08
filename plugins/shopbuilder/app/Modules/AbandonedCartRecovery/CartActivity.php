<?php
/**
 * Sticky Abandoned Cart Recovery Module Class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\AbandonedCartRecovery;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;
use WC_Coupon;
use WC_Order;
use WC_Order_Item_Product;


defined( 'ABSPATH' ) || exit();

/**
 * Sticky add-to-cart Module Class.
 */
class CartActivity {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Copy custom cart item meta 'rtsb_restored_item' to order items on checkout.
	 *
	 * @param \WC_Order_Item_Product $item           The order item being created.
	 * @param string                 $cart_item_key Cart item key.
	 * @param array                  $values        Cart item values.
	 */
	public function checkout_create_order_line_item( $item, $cart_item_key, $values ) {
		if ( ! empty( $values['rtsb_restored_item'] ) ) {
			// Add meta to the order item.
			$item->add_meta_data( 'rtsb_restored_item', $values['rtsb_restored_item'], true );
		}
	}
	/**
	 * ReStored
	 */
	public function before_calculate_totals() {
		if ( did_action( 'woocommerce_before_calculate_totals' ) > 1 ) {
			return; // Skip repeated calls.
		}
		// Ensure WooCommerce cart exists.
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return;
		}
		$session_id  = Fns::getSession( 'rtsb_ca_session_id' );
		$abandonment = null;
		if ( ! empty( $session_id ) ) {
			$abandonment = CartRecoveryDB::getCaAbandonmentBySessionId( $session_id );
		}
		if ( empty( $abandonment ) ) {
			return;
		}
		// Split codes into array.
		$abandonment_coupons = array_map( 'trim', explode( ',', $abandonment['coupon_code'] ) );
		if ( empty( $abandonment_coupons ) ) {
			return;
		}
		$cart = WC()->cart;
		// Get all product IDs currently in cart.
		$cart_product_ids = [];
		foreach ( $cart->get_cart() as $cart_item ) {
			$cart_product_ids[] = $cart_item['product_id'];
		}
		foreach ( $abandonment_coupons as $coupon_code ) {
			$coupon = new WC_Coupon( $coupon_code );
			// Get all meta for the coupon.
			$coupon_id          = $coupon->get_id(); // coupon post ID.
			$coupon_abandonment = get_post_meta( $coupon_id, 'coupon_created_abandonment_id', true );
			if ( absint( $abandonment['id'] ) !== absint( $coupon_abandonment ) ) {
				continue;
			}
			// Get restricted product IDs (assigned to coupon).
			$required_products = $coupon->get_product_ids();
			if ( empty( $required_products ) ) {
				continue; // No restriction → skip.
			}
			// Check if all required products are in the cart.
			$all_present = ! array_diff( $required_products, $cart_product_ids );
			if ( ! $all_present ) {
				// Remove coupon if any required product is missing.
				$cart->remove_coupon( $coupon_code );
				wc_add_notice(
					sprintf(
						/* translators: %s: Coupon code */
						__( 'Coupon "%s" was removed because not all eligible products are in your cart.', 'shopbuilder' ),
						$coupon_code
					),
					'notice'
				);
			}
		}
	}
	/**
	 * Email Click Tracker
	 *
	 * @param array $data Tracker data.
	 *
	 * @return void
	 */
	public function email_click_tracker( $data = [] ) {
		if ( ! ( $data['click_email'] ?? false ) ) {
			return;
		}
		if ( ! isset( $data['template_id'] ) ) {
			return;
		}
		if ( ! isset( $data['abandonment_id'] ) ) {
			return;
		}
		$template_id    = absint( $data['template_id'] ?? 0 ); // phpcs:ignore WordPress.Security
		$abandonment_id = absint( $data['abandonment_id'] ?? 0 ); // phpcs:ignore WordPress.Security
		$exist          = CartRecoveryDB::count_abandonment_by_meta_value( $abandonment_id, 'clicked_email_template_id', $template_id );
		if ( ! $exist ) {
			CartRecoveryDB::add_ca_abandonment_meta( $abandonment_id, 'clicked_email_template_id', $template_id );
			$email_send_count = CartRecoveryDB::get_email_template_meta( $template_id, 'email_click_count', 0 );
			$count            = absint( $email_send_count ) + 1;
			CartRecoveryDB::update_email_template_meta( $template_id, 'email_click_count', $count );
		}
	}
	/**
	 * @return void
	 */
	public function email_open_tracker() {
		$request_uri = $_SERVER['REQUEST_URI'] ?? ''; // phpcs:ignore WordPress.Security
		if ( ! is_string( $request_uri ) || strpos( $request_uri, '/rtsb-ca-email-open/' ) === false ) {
			return;
		}
		$template_id    = absint( $_GET['template_id'] ?? 0 ); // phpcs:ignore WordPress.Security
		$abandonment_id = absint( $_GET['abandonment_id'] ?? 0 ); // phpcs:ignore WordPress.Security
		if ( ! $template_id || ! $abandonment_id ) {
			return;
		}
		$exist = CartRecoveryDB::getCaAbandonmentByID( $abandonment_id );
		if ( empty( $exist ) ) {
			return;
		}
		$history_id = absint( $_GET['history_id'] ?? 0 ); // phpcs:ignore WordPress.Security
		CartRecoveryDB::update_scheduled_email_history( $history_id, [ 'open_time' => gmdate( 'Y-m-d H:i:s', Fns::currentTimestampUTC() ) ] );
		$opened_data = CartRecoveryDB::get_abandonment_meta( $abandonment_id, 'email_opened_data', [] );
		$opened_data = maybe_unserialize( $opened_data );
		// Check if template_id exists without foreach.
		$exists = is_array( $opened_data ) && in_array( $template_id, array_column( $opened_data, 'template_id' ) ); // phpcs:ignore WordPress.PHP.StrictInArray
		if ( $exists ) {
			return;
		}
		if ( $history_id ) {
			CartRecoveryDB::update_scheduled_email_history( $history_id, [ 'action' => 'Opened' ] );
		}
		$email_open_data = [
			'time'        => gmdate( 'Y-m-d H:i:s', Fns::currentTimestampUTC() ),
			'template_id' => $template_id,
		];
		if ( $abandonment_id ) {
			if ( ! empty( $opened_data ) ) {
				$opened_data[] = $email_open_data;
			} else {
				$opened_data = [ $email_open_data ];
			}
			CartRecoveryDB::update_ca_abandonment_meta( $abandonment_id, 'email_opened_data', $opened_data );
		}

		$email_send_count = CartRecoveryDB::get_email_template_meta( $template_id, 'email_send_total', 0 );
		if ( ! $email_send_count ) {
			CartRecoveryDB::update_email_template_meta( $template_id, 'email_send_total', 1 );
		}
		$email_opened_count = CartRecoveryDB::get_email_template_meta( $template_id, 'email_opened_count', 0 );
		$count              = absint( $email_opened_count ) + 1;
		CartRecoveryDB::update_email_template_meta( $template_id, 'email_opened_count', $count );
	}
	/**
	 * Retrieves the order metadata and formats it.
	 *
	 * @param array $formatted_meta The formatted meta data.
	 *
	 * @return array
	 */
	public function get_order_meta( $formatted_meta ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		foreach ( $formatted_meta as $key => $meta ) {
			if ( 'rtsb_restored_item' === $meta->key ) {
				if ( is_admin() ) {
					$meta->display_key   = '<span class="rtsb-order-meta"><span class="wc-item-meta-label rtsb-stock-text">' . esc_html__( 'Recovered Item', 'shopbuilder' ) . '</span></span>';
					$meta->display_value = esc_html__( 'Yes', 'shopbuilder' );
				} else {
					unset( $formatted_meta[ $key ] );
				}
			}
		}
		return $formatted_meta;
	}
	/**
	 * Delete abandoned cart data when order is completed or processing
	 *
	 * @param int      $order_id   Order ID.
	 * @param string   $old_status Previous order status.
	 * @param string   $new_status New order status.
	 * @param WC_Order $order      Order object.
	 */
	public function cart_abandonment_on_status_change( $order_id, $old_status, $new_status, $order ) {
		// Only act if status changed to 'completed' or 'processing'.
		if ( ! in_array( $new_status, [ 'completed', 'processing' ], true ) ) {
			return;
		}
		// Get session ID if available.
		$session_id  = Fns::getSession( 'rtsb_ca_session_id' );
		$abandonment = null;
		if ( ! is_admin() && ! empty( $session_id ) ) {
			$abandonment = CartRecoveryDB::getCaAbandonmentBySessionId( $session_id );
		}
		$email = $order->get_billing_email();
		if ( ! $abandonment ) {
			$abandonment = CartRecoveryDB::getCaAbandonmentByEmail( $email );
		}
		if ( ! $abandonment ) {
			return;
		}
		if ( ! empty( $session_id ) ) {
			$abandonment = CartRecoveryDB::getCaAbandonmentBySessionId( $session_id );
			if ( ! empty( $abandonment['id'] ) ) {
				CartRecoveryDB::updateCaAbandonmentById( $abandonment['id'], [ 'order_status' => 'completed' ] );
				$time = gmdate( 'Y-m-d H:i:s', Fns::currentTimestampUTC() );
				CartRecoveryDB::update_ca_abandonment_meta( $abandonment['id'], 'completed_time', $time );
				CartRecoveryDB::delete_unnecessery_email_history_for_abandonment( $abandonment['id'] );
				CartRecoveryDB::delete_abandonment_meta( $abandonment['id'], 'lost_time' );
				// Reset tracking session so a returning customer starts a fresh abandonment record.
				Fns::removeSession( 'rtsb_ca_session_id' );
			}
			$notifyToAdmin = 'on' === CartRecoveryFns::get_options( 'notify_recovery_to_admin', 'on' );
			if ( ! empty( $abandonment['id'] ) && $notifyToAdmin ) {
				$recovery_email = RecoveryEmail::instance();
				$recovery_email->send_recovery_notice_to_admin( $order );
			}
		}
		// Delete normal abandoned cart records for this email.
		if ( $email ) {
			CartRecoveryDB::deleteNormalAbandonedByEmail( $email );
		}
		$history_id = Fns::getSession( 'rtsb_ca_email_history_id' );
		if ( $history_id ) {
			CartRecoveryDB::update_scheduled_email_history( $history_id, [ 'action' => 'Completed' ] );
		}
	}

	/**
	 * @return void
	 */
	public function unsubscribe_cart_from_abandonment() {
		$unsubscribe_key = wp_unslash( $_GET['rtsbUnsubscribe'] ?? '' ); // phpcs:ignore WordPress.Security
		if ( empty( $unsubscribe_key ) ) {
			return;
		}
		if ( Fns::getSession( 'unsubscribed_abandonment_unsubscribe_key' ) === $unsubscribe_key ) { // Protect against malicious or unauthorized requests.
			return;
		}
		$decoded = Fns::decode_signed_key( $unsubscribe_key );
		if ( ! $decoded ) {
			return; // Invalid or tampered key.
		}
		if ( empty( $decoded['unsubscribe'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		if ( empty( $decoded['template_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		$abandonment_id = absint( $decoded['unsubscribe'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$template_id    = absint( $decoded['template_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$unsubscribed   = CartRecoveryDB::count_email_template_by_meta_value( $template_id, 'unsubscribed_abandonment_id', $abandonment_id );
		if ( ! empty( $unsubscribed ) ) {
			return;
		}
		$this->email_click_tracker(
			[
				'click_email'    => true,
				'template_id'    => $template_id,
				'abandonment_id' => $abandonment_id,
			]
		);
		CartRecoveryFns::unsebscribe( $abandonment_id );
		$unsubscribed_count = absint( CartRecoveryDB::get_email_template_meta( $template_id, 'unsubscribed_count', 0 ) );
		$unsubscribed_count = ++$unsubscribed_count;
		CartRecoveryDB::update_email_template_meta( $template_id, 'unsubscribed_count', $unsubscribed_count );
		CartRecoveryDB::add_email_template_meta( $template_id, 'unsubscribed_abandonment_id', $abandonment_id );
		Fns::removeSession( 'unsubscribed_abandonment_unsubscribe_key' );
		Fns::setSession( 'unsubscribed_abandonment_unsubscribe_key', $unsubscribe_key );
		// ✅ Add success notice
		wc_add_notice( __( 'Your has been successfully unsubscribed.', 'shopbuilder' ), 'success' );
		$url = get_permalink( CartRecoveryFns::get_options( 'unsubscribe_page', 0 ) ) ?: home_url();
		if ( ! empty( $decoded['history_id'] ) ) {
			CartRecoveryDB::update_scheduled_email_history( $decoded['history_id'], [ 'action' => 'Unsubscribe' ] );
		}
		wp_safe_redirect( $url );
		exit;
	}

	/**
	 * @param array $abandonment Single abandonment records.
	 * @return void
	 */
	private function prepare_checkout_form_data( $abandonment ) {
		$fields = $abandonment['other_fields'] ?? [];
		if ( ! empty( $abandonment['email'] ) ) {
			WC()->customer->set_billing_email( $abandonment['email'] );
		}
		if ( ! empty( $fields['store_api_draft_order'] ) ) {
			WC()->session->set( 'store_api_draft_order', $fields['store_api_draft_order'] );
		}
		if ( ! empty( $fields['billing_first_name'] ) ) {
			WC()->customer->set_billing_first_name( $fields['billing_first_name'] );
		}
		if ( ! empty( $fields['billing_last_name'] ) ) {
			WC()->customer->set_billing_last_name( $fields['billing_last_name'] );
		}
		if ( ! empty( $fields['billing_company'] ) ) {
			WC()->customer->set_billing_company( $fields['billing_company'] );
		}
		if ( ! empty( $fields['billing_phone'] ) ) {
			WC()->customer->set_billing_phone( $fields['billing_phone'] );
		}
		if ( ! empty( $fields['billing_address_1'] ) ) {
			WC()->customer->set_billing_address_1( $fields['billing_address_1'] );
		}
		if ( ! empty( $fields['billing_address_2'] ) ) {
			WC()->customer->set_billing_address_2( $fields['billing_address_2'] );
		}
		if ( ! empty( $fields['billing_city'] ) ) {
			WC()->customer->set_billing_city( $fields['billing_city'] );
		}
		if ( ! empty( $fields['billing_postcode'] ) ) {
			WC()->customer->set_billing_postcode( $fields['billing_postcode'] );
		}
		if ( ! empty( $fields['billing_state'] ) ) {
			WC()->customer->set_billing_state( $fields['billing_state'] );
		}
		if ( ! empty( $fields['billing_country'] ) ) {
			WC()->customer->set_billing_country( $fields['billing_country'] );
		}
		// Shipping fields.
		if ( ! empty( $fields['shipping_first_name'] ) ) {
			WC()->customer->set_shipping_first_name( $fields['shipping_first_name'] );
		}
		if ( ! empty( $fields['shipping_last_name'] ) ) {
			WC()->customer->set_shipping_last_name( $fields['shipping_last_name'] );
		}
		if ( ! empty( $fields['shipping_company'] ) ) {
			WC()->customer->set_shipping_company( $fields['shipping_company'] );
		}
		if ( ! empty( $fields['shipping_address_1'] ) ) {
			WC()->customer->set_shipping_address_1( $fields['shipping_address_1'] );
		}
		if ( ! empty( $fields['shipping_address_2'] ) ) {
			WC()->customer->set_shipping_address_2( $fields['shipping_address_2'] );
		}
		if ( ! empty( $fields['shipping_city'] ) ) {
			WC()->customer->set_shipping_city( $fields['shipping_city'] );
		}
		if ( ! empty( $fields['shipping_postcode'] ) ) {
			WC()->customer->set_shipping_postcode( $fields['shipping_postcode'] );
		}
		if ( ! empty( $fields['shipping_state'] ) ) {
			WC()->customer->set_shipping_state( $fields['shipping_state'] );
		}
		if ( ! empty( $fields['shipping_country'] ) ) {
			WC()->customer->set_shipping_country( $fields['shipping_country'] );
		}
		// Payment method.
		if ( ! empty( $fields['payment_method'] ) ) {
			WC()->session->set( 'chosen_payment_method', $fields['payment_method'] );
		}
	}
	/**
	 * @return void
	 */
	public function restore_cart_from_abandonment() {
		$this->email_open_tracker();
		if ( ! is_checkout() ) {
			return;
		}
		$restore_key = wp_unslash( $_GET['rtsbRestoreCart'] ?? '' ); // phpcs:ignore WordPress.Security
		if ( empty( $restore_key ) ) {
			return;
		}
		// Clear current cart first.
		WC()->cart->empty_cart();
		$decoded = Fns::decode_signed_key( $restore_key );
		if ( ! $decoded ) {
			return;
		}
		if ( empty( $decoded['restore_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		$history_id = absint( $decoded['history_id'] ?? 0 );
		Fns::setSession( 'rtsb_ca_email_history_id', $history_id );
		$abandonment_id = absint( $decoded['restore_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$template_id    = absint( $decoded['template_id'] ?? 0 ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$abandonment    = CartRecoveryDB::getCaAbandonmentByID( $abandonment_id );
		// Get abandoned cart data (replace this with your own retrieval method).
		$abandoned_cart = maybe_unserialize( $abandonment['cart_contents'] ?? [] );
		if ( empty( $abandoned_cart ) ) {
			return;
		}
		if ( 'completed' === $abandonment['order_status'] ) {
			wc_add_notice( __( 'You have already completed this order.', 'shopbuilder' ), 'notice' );
			return;
		}
		$this->email_click_tracker(
			[
				'click_email'    => true,
				'template_id'    => $template_id,
				'abandonment_id' => $abandonment_id,
			]
		);
		Fns::removeSession( 'rtsb_ca_session_id' );
		Fns::setSession( 'rtsb_ca_session_id', $abandonment['ca_session_id'] );
		foreach ( $abandoned_cart as $cart_item ) {
			$product_id     = $cart_item['product_id'];
			$quantity       = $cart_item['quantity'];
			$variation_id   = ! empty( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : 0;
			$variation      = ! empty( $cart_item['variation'] ) ? $cart_item['variation'] : [];
			$cart_item_data = [
				'rtsb_restored_item' => $abandonment['email'],
			];
			WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $cart_item_data );
		}
		$this->prepare_checkout_form_data( $abandonment );
		$template = $template_id ? CartRecoveryDB::getTemplateById( $template_id ) : [];
		if ( ! empty( $template ) && ! empty( $template['coupon_auto_apply'] ) ) {
			$history     = CartRecoveryDB::get_history_by_id( $decoded['history_id'] );
			$coupon_code = $history['coupon_code'] ?? $abandonment['coupon_code'];
			CartRecoveryDB::updateCaAbandonmentById( $abandonment['id'], [ 'coupon_code' => $coupon_code ] );
			WC()->cart->apply_coupon( $coupon_code );
		}
		$history_id = Fns::getSession( 'rtsb_ca_email_history_id' );
		if ( $history_id ) {
			CartRecoveryDB::update_scheduled_email_history( $history_id, [ 'action' => 'Restored' ] );
		}
		// ✅ Add success notice.
		wc_add_notice( __( 'Your cart has been successfully restored.', 'shopbuilder' ), 'success' );
		wp_safe_redirect( wc_get_checkout_url() );
		exit;
	}
	/**
	 * @return void
	 */
	public function abandoned_cart_update() {
		if ( ! wp_verify_nonce( Fns::get_nonce(), rtsb()->nonceText ) ) {
			return;
		}
		$disabled_roles = CartRecoveryFns::get_options( 'disable_tracking_roles', [] );
		// Get current user.
		$current_user = wp_get_current_user();
		// If the user has any of the disabled roles, stop.
		if ( array_intersect( $current_user->roles, $disabled_roles ) ) {
			return;
		}
		// On admin-ajax requests WooCommerce does not auto-initialize the cart from the
		// customer session, so the totals would read 0 and the record would be deleted
		// instead of saved. Load the cart for this context first (plugin convention).
		if ( function_exists( 'wc_load_cart' ) && ( null === WC()->cart || WC()->cart->is_empty() ) ) {
			wc_load_cart();
		}
		if ( null === WC()->cart ) {
			return;
		}
		$cart_total  = WC()->cart->total;
		$items_count = WC()->cart->get_cart_contents_count(); // Total items including quantities.

		$session_id = isset( $_POST['sessionId'] ) ? sanitize_text_field( wp_unslash( $_POST['sessionId'] ) ) : '';
		$user_email = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
		if ( empty( $session_id ) ) {
			$return = [
				'message' => esc_html__( 'Session Missing', 'shopbuilder' ),
			];
			wp_send_json_success( $return );
		}
		Fns::removeSession( 'rtsb_ca_session_id' );
		Fns::setSession( 'rtsb_ca_session_id', $session_id );
		$draft_order_id = WC()->session->get( 'store_api_draft_order' );
		$other_fields   = [
			'first_name'     => sanitize_text_field( wp_unslash( $_POST['first_name'] ?? '' ) ),
			'last_name'      => sanitize_text_field( wp_unslash( $_POST['last_name'] ?? '' ) ),
			'item_count'     => $items_count,
			'draft_order_id' => $draft_order_id,
		];

		$order = false;
		if ( ! empty( $draft_order_id ) ) {
			$order = wc_get_order( $draft_order_id );
		}
		if ( $order ) {
			// Billing fields.
			$billing_data = [
				'billing_first_name'  => $order->get_billing_first_name(),
				'billing_last_name'   => $order->get_billing_last_name(),
				'billing_email'       => $order->get_billing_email(),
				'billing_phone'       => $order->get_billing_phone(),
				'billing_company'     => $order->get_billing_company(),
				'billing_address_1'   => $order->get_billing_address_1(),
				'billing_address_2'   => $order->get_billing_address_2(),
				'billing_city'        => $order->get_billing_city(),
				'billing_state'       => $order->get_billing_state(),
				'billing_postcode'    => $order->get_billing_postcode(),
				'billing_country'     => $order->get_billing_country(),
				'shipping_first_name' => $order->get_shipping_first_name(),
				'shipping_last_name'  => $order->get_shipping_last_name(),
				'shipping_company'    => $order->get_shipping_company(),
				'shipping_address_1'  => $order->get_shipping_address_1(),
				'shipping_address_2'  => $order->get_shipping_address_2(),
				'shipping_city'       => $order->get_shipping_city(),
				'shipping_state'      => $order->get_shipping_state(),
				'shipping_postcode'   => $order->get_shipping_postcode(),
				'shipping_country'    => $order->get_shipping_country(),
				'payment_method'      => $order->get_payment_method(),
			];
			// Merge both if needed.
			$other_fields = array_merge( $other_fields, $billing_data );
		}

		$other_fields['full_name'] = $other_fields['first_name'] . ' ' . $other_fields['last_name'];
		// Verify if email is already exists.
		$session_checkout_details = CartRecoveryDB::getCaAbandonmentBySessionId( $session_id );
		if ( empty( $session_checkout_details ) ) {
			$session_checkout_details = CartRecoveryDB::getCaAbandonmentByEmail( $user_email );
			if (
				! empty( $session_checkout_details['ca_session_id'] )
				&& in_array( $session_checkout_details['order_status'], [ 'normal', 'abandoned' ], true )
			) {
				$session_id = $session_checkout_details['ca_session_id'];
				Fns::setSession( 'rtsb_ca_session_id', $session_id );
			} else {
				// Prior cart for this email is already completed/lost: start a fresh record.
				$session_checkout_details = [];
			}
		}

		$cart_items = [];

		foreach ( WC()->cart->get_cart() as $cart_item_key => $item ) {
			$product                      = $item['data']; // WC_Product object.
			$cart_items[ $cart_item_key ] = [
				'name'         => $product->get_name(),
				'image_url'    => wp_get_attachment_url( $product->get_image_id() ),
				'product_id'   => $item['product_id'],
				'variation_id' => $item['variation_id'],
				'variation'    => $item['variation'],
				'quantity'     => $item['quantity'],
				'line_total'   => $item['line_total'],
			];
		}

		// Get applied coupons from cart.
		$applied_coupons = WC()->cart->get_applied_coupons();
		$coupon_code     = ! empty( $applied_coupons ) ? implode( ', ', $applied_coupons ) : '';

		$checkout_details  = [
			'cart_contents' => $cart_items,
			'cart_total'    => $cart_total ,
			'other_fields'  => $other_fields ,
			'order_status'  => 'normal',
			'unsubscribed'  => 0,
			'checkout_id'   => wc_get_page_id( 'checkout' ), // I don't know what is this for.
			'coupon_code'   => $coupon_code,
			'email'         => $user_email,
			'ca_session_id' => $session_id,
			'time'          => gmdate( 'Y-m-d H:i:s', Fns::currentTimestampUTC() ),
		];
		 $checkout_details = apply_filters( 'rtsb/ca/abandoned/data', $checkout_details );
		$status            = false;
		if ( $checkout_details['cart_total'] > 0 ) {
			if ( ! empty( $session_checkout_details ) && in_array( $session_checkout_details['order_status'], [ 'normal' ,'abandoned' ], true ) ) {
				unset(
					$checkout_details['ca_session_id'],
					$checkout_details['order_status'],
				);
				if ( 'normal' !== $session_checkout_details['order_status'] ) {
					unset(
						$checkout_details['cart_contents'],
						$checkout_details['time']
					);
				}
				$status = CartRecoveryDB::updateCaAbandonmentBySessionId( $session_id, $checkout_details );
			} else {
				// No trackable record (none, or previous one already completed/lost): start fresh.
				$status = CartRecoveryDB::insertCaAbandonment( $checkout_details );
			}
		} else {
			$status = CartRecoveryDB::deleteCaAbandonmentBySessionId( $session_id );
		}
		$return = [
			'message' => $status ? esc_html__( 'Abandoned cart updated successfully...', 'shopbuilder' ) : esc_html__( 'Abandoned cart updated failed...', 'shopbuilder' ),
		];
		wp_send_json_success( $return );
	}
}
