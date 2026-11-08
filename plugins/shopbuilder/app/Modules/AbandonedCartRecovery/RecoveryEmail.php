<?php
/**
 * Sticky Abandoned Cart Recovery Module Class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\AbandonedCartRecovery;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;
use WC_Order;


defined( 'ABSPATH' ) || exit();

/**
 * Sticky add-to-cart Module Class.
 */
class RecoveryEmail {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Send email using WooCommerce mailer.
	 *
	 * @param string      $to      Recipient email(s), comma-separated or array.
	 * @param string      $subject Email subject.
	 * @param string      $message Email HTML body.
	 * @param string|null $from_name Optional from name.
	 * @param string|null $from_email Optional from email.
	 * @param string|null $reply_to Optional reply-to email.
	 * @return bool True if sent, false otherwise.
	 */
	protected function send_wc_email( $to, $subject, $message, $from_name = null, $from_email = null, $reply_to = null ) {
		if ( empty( $to ) || empty( $subject ) || empty( $message ) ) {
			return false;
		}
		$mailer = WC()->mailer();
		// Wrap in WooCommerce email template.
		$wrapped_message = $mailer->wrap_message( $subject, $message );
		// Default headers.
		$from_name  = $from_name ?? CartRecoveryFns::get_options( 'from_name', get_bloginfo( 'name' ) );
		$from_email = $from_email ?? CartRecoveryFns::get_options( 'from_address', get_option( 'admin_email' ) );
		$reply_to   = $reply_to ?? CartRecoveryFns::get_options( 'reply_to_address', get_option( 'admin_email' ) );
		$headers    = [
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . $from_name . ' <' . $from_email . '>',
			'Reply-To: ' . $reply_to,
		];
		return $mailer->send( $to, $subject, $wrapped_message, $headers );
	}
	/**
	 * @param array $email_history email send history.
	 * @return false
	 */
	public function send_recovery_email( $email_history ) {
		$abandonment_id = $email_history['abandonment_id'] ?? 0;
		$template_id    = $email_history['template_id'] ?? 0;
		if ( ! ( $abandonment_id && $template_id ) ) {
			return false;
		}
		$template = CartRecoveryDB::getTemplateById( $template_id );

		if ( empty( $template ) ) {
			return false;
		}
		$abandonment = CartRecoveryDB::getCaAbandonmentByID( $abandonment_id );
		if ( empty( $abandonment ) ) {
			return false;
		}
		if ( 'completed' === $abandonment['order_status'] ) {
			return false;
		}
		$coupon_code = CartRecoveryFns::create_wc_coupon_from_template( $template, $abandonment );
		if ( ! empty( $coupon_code ) ) {
			$abandonment['coupon_code'] = $coupon_code;
			CartRecoveryDB::updateCaAbandonmentById( $abandonment['id'], [ 'coupon_code' => $coupon_code ] );
			CartRecoveryDB::update_scheduled_email_history( $email_history['id'], [ 'coupon_code' => $coupon_code ] );
		}
		$send             = $this->send_template_to_email( $abandonment, $template, $email_history );
		$key              = 'email_send_total';
		$email_send_count = CartRecoveryDB::get_email_template_meta( $template_id, $key, 0 );
		$count            = absint( $email_send_count ) + 1;
		CartRecoveryDB::update_email_template_meta( $template_id, $key, $count );
		return ! empty( $email_history['id'] );
	}
	/**
	 * @param array $abandonment abandonment.
	 * @param array $template Template.
	 * @param array $email_history email history.
	 * @return bool
	 */
	public function send_template_to_email( $abandonment, $template, $email_history ) {
		if ( empty( $template ) || empty( $abandonment ) ) {
			return false;
		}
		if ( 'completed' === $abandonment['order_status'] ) {
			return false;
		}
		$history_id     = $email_history['id'] ?? 0;
		$abandonment_id = $abandonment['id'];
		$other_fields   = $abandonment['other_fields'] ?? [];
		// Generate URLs.
		$checkout_url    = Fns::generate_signed_url(
			[
				'restore_id'  => $abandonment_id,
				'template_id' => $template['id'],
				'history_id'  => $history_id,
			],
			wc_get_checkout_url(),
			'rtsbRestoreCart'
		);
		$unsubscribe_url = Fns::generate_signed_url(
			[
				'unsubscribe' => $abandonment_id,
				'template_id' => $template['id'],
				'history_id'  => $history_id,
			],
			get_permalink( CartRecoveryFns::get_options( 'unsubscribe_page', 0 ) ) ?: home_url(),
			'rtsbUnsubscribe'
		);
		$administrator   = get_users(
			[
				'role'   => 'Administrator',
				'number' => 1,
			]
		);
		// Admin info.
		$admin_user       = reset( $administrator );
		$admin_first_name = $admin_user->user_firstname ?: __( 'Admin', 'shopbuilder' );
		$admin_last_name  = $admin_user->user_lastname ?? '';
		$admin_full_name  = trim( $admin_first_name . ' ' . $admin_last_name );

		$replacements = [
			'{{store.name}}'          => get_bloginfo( 'name' ),
			'{{store.url}}'           => home_url(),
			'{{cart.item_count}}'     => $other_fields['item_count'] ?? '',
			'{{customer.first_name}}' => $other_fields['first_name'] ?? '',
			'{{customer.last_name}}'  => $other_fields['last_name'] ?? '',
			'{{customer.full_name}}'  => $other_fields['full_name'] ?? '',
			'{{admin.firstname}}'     => $admin_first_name,
			'{{admin.lastname}}'      => $admin_last_name,
			'{{admin.full_name}}'     => $admin_full_name,
			'{{cart.total}}'          => $abandonment['cart_total'] ?? '',
			'{{cart.checkout_url}}'   => esc_url( $checkout_url ),
			'{{cart.unsubscribe}}'    => esc_url( $unsubscribe_url ),
			'{{cart.coupon_code}}'    => $abandonment['coupon_code'] ?? '',
			'{{cart.abandoned_date}}' => $abandonment['time'] ?? '',
			'{{customer.email}}'      => $abandonment['email'] ?? '',
		];

		$message = strtr( $template['email_body'], $replacements );

		$tracking_url = add_query_arg(
			[
				'template_id'    => $template['id'] ,
				'abandonment_id' => $abandonment_id,
				'history_id'     => $history_id,
			],
			home_url( '/rtsb-ca-email-open/' )
		);
		$message     .= '<img src="' . esc_url( $tracking_url ) . '" width="1" height="1" style="display:none;">';

		$to      = $abandonment['email'];
		$subject = $template['email_subject'];

		return $this->send_wc_email( $to, $subject, $message );
	}
	/**
	 * Send a recovery notice email to the store administrator(s) with recovered products list.
	 *
	 * @param \WC_Order $order WooCommerce order object associated with the recovery notice.
	 *
	 * @return bool True on success, false on failure.
	 *
	 * @since 1.0.0
	 */
	public function send_recovery_notice_to_admin( $order ) {
		if ( ! $order instanceof WC_Order ) {
			return false;
		}
		// Get admin emails from options.
		$to_admin_email = CartRecoveryFns::get_options( 'recovery_admin_email', '' );
		if ( empty( $to_admin_email ) ) {
			return false;
		}
		$to_admin_email = implode( ',', array_map( 'trim', explode( ',', $to_admin_email ) ) );
		$subject        = CartRecoveryFns::get_options(
			'recovery_notify_title',
			/* translators: %s: Order number */
			sprintf( esc_html__( 'Cart Recovery Success - Order #%s', 'shopbuilder' ), $order->get_order_number() )
		);
		// Get order items.
		$items = $order->get_items();
		if ( empty( $items ) ) {
			return false;
		}
		// Build email message with template override support.
		$message = $this->get_recovery_email_template( $order, $items );
		// Send email.
		return $this->send_wc_email( $to_admin_email, $subject, $message );
	}

	/**
	 * Get the recovery email template HTML.
	 * Supports template override via filter or custom template file.
	 *
	 * @param \WC_Order $order Order object.
	 * @param array     $items Order items.
	 *
	 * @return string HTML email content.
	 */
	private function get_recovery_email_template( $order, $items ) {
		// Prepare template variables.
		$store_name    = get_bloginfo( 'name' );
		$order_number  = $order->get_order_number();
		$order_total   = $order->get_formatted_order_total();
		$order_date    = $order->get_date_created()->date_i18n( wc_date_format() . ' ' . wc_time_format() );
		$customer_name = $order->get_formatted_billing_full_name();
		$order_url     = $order->get_edit_order_url();
		// Calculate recovery statistics.
		$recovered_count = 0;
		$new_count       = 0;
		foreach ( $items as $item ) {
			$restored_session = $item->get_meta( 'rtsb_restored_item', true );
			if ( ! empty( $restored_session ) ) {
				$recovered_count++;
			} else {
				$new_count++;
			}
		}
		// Template variables array for easy override.
		$template_vars = [
			'order'           => $order,
			'items'           => $items,
			'store_name'      => $store_name,
			'order_number'    => $order_number,
			'order_total'     => $order_total,
			'order_date'      => $order_date,
			'customer_name'   => $customer_name,
			'order_url'       => $order_url,
			'recovered_count' => $recovered_count,
			'new_count'       => $new_count,
		];
		$custom_html   = $this->render_default_recovery_email_template( $template_vars );
		// Allow custom template via filter.
		return apply_filters( 'rtsb/cart_recovery/admin_email_template', $custom_html, $template_vars );
	}

	/**
	 * Render the default recovery email template.
	 *
	 * @param array $vars Template variables.
	 *
	 * @return string HTML email content.
	 */
	private function render_default_recovery_email_template( $vars ) {
		extract( $vars ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

		ob_start();
		?>
		<div style="font-family: Arial, sans-serif; max-width: 700px; margin: 0 auto; background-color: #f7f7f7; padding: 10px;">
			<!-- Header -->
			<div style=" padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
				<h2 style="margin: 0; font-size: 24px;">🎉 <?php esc_html_e( 'Cart Recovery Success!', 'shopbuilder' ); ?></h2>
			</div>

			<!-- Main Content -->
			<div style="background-color: white; padding: 15px; border-radius: 0 0 8px 8px;">
				<p style="font-size: 16px; color: #333; margin-bottom: 20px;">
					<?php
					echo sprintf(
					/* translators: %s: Store name */
						esc_html__( 'Great news! A previously abandoned cart has been successfully recovered at %s.', 'shopbuilder' ),
						'<strong>' . esc_html( $store_name ) . '</strong>'
					);
					?>
				</p>

				<!-- Order Summary -->
				<div style="background-color: #f9f9f9; padding: 20px; border-radius: 6px; margin-bottom: 25px;">
					<h3 style="margin-top: 0; color: #333; font-size: 18px; border-bottom: 2px solid; padding-bottom: 10px;">
						<?php esc_html_e( 'Order Summary', 'shopbuilder' ); ?>
					</h3>
					<table style="width: 100%; border-collapse: collapse;">
						<tr>
							<td style="padding: 8px 0; color: #666;"><strong><?php esc_html_e( 'Order Number:', 'shopbuilder' ); ?></strong></td>
							<td style="padding: 8px 0; color: #333;">#<?php echo esc_html( $order_number ); ?></td>
						</tr>
						<tr>
							<td style="padding: 8px 0; color: #666;"><strong><?php esc_html_e( 'Customer:', 'shopbuilder' ); ?></strong></td>
							<td style="padding: 8px 0; color: #333;"><?php echo esc_html( $customer_name ); ?></td>
						</tr>
						<tr>
							<td style="padding: 8px 0; color: #666;"><strong><?php esc_html_e( 'Order Date:', 'shopbuilder' ); ?></strong></td>
							<td style="padding: 8px 0; color: #333;"><?php echo esc_html( $order_date ); ?></td>
						</tr>
						<tr>
							<td style="padding: 8px 0; color: #666;"><strong><?php esc_html_e( 'Order Total:', 'shopbuilder' ); ?></strong></td>
							<td style="padding: 8px 0; font-size: 18px; font-weight: bold;"><?php echo wp_kses_post( $order_total ); ?></td>
						</tr>
					</table>
				</div>

				<!-- Recovery Statistics -->
				<div style="display: flex; gap: 15px; margin-bottom: 25px;">
					<div style="flex: 1; background-color: #e8f5e9; padding: 15px; border-radius: 6px; text-align: center;">
						<div style="font-size: 28px; font-weight: bold; "><?php echo absint( $recovered_count ); ?></div>
						<div style="color: #666; font-size: 14px;"><?php esc_html_e( 'Recovered Items', 'shopbuilder' ); ?></div>
					</div>
					<?php if ( $new_count > 0 ) : ?>
						<div style="flex: 1; background-color: #e3f2fd; padding: 15px; border-radius: 6px; text-align: center;">
							<div style="font-size: 28px; font-weight: bold; color: #2196F3;"><?php echo absint( $new_count ); ?></div>
							<div style="color: #666; font-size: 14px;"><?php esc_html_e( 'New Items', 'shopbuilder' ); ?></div>
						</div>
					<?php endif; ?>
				</div>

				<!-- Products Table -->
				<h3 style="color: #333; font-size: 18px; border-bottom: 2px solid; padding-bottom: 10px; margin-bottom: 15px;">
					<?php esc_html_e( 'Recovered Products', 'shopbuilder' ); ?>
				</h3>

				<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
					<thead>
					<tr style="background-color: #f5f5f5;">
						<th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd; font-size: 14px; color: #666;">
							<?php esc_html_e( 'Product', 'shopbuilder' ); ?>
						</th>
						<th style="padding: 12px; text-align: center; border-bottom: 2px solid #ddd; font-size: 14px; color: #666;">
							<?php esc_html_e( 'Qty', 'shopbuilder' ); ?>
						</th>
						<th style="padding: 12px; text-align: center; border-bottom: 2px solid #ddd; font-size: 14px; color: #666;">
							<?php esc_html_e( 'Status', 'shopbuilder' ); ?>
						</th>
						<th style="padding: 12px; text-align: right; border-bottom: 2px solid #ddd; font-size: 14px; color: #666;">
							<?php esc_html_e( 'Total', 'shopbuilder' ); ?>
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ( $items as $item ) : ?>
						<?php
						$restored_session = $item->get_meta( 'rtsb_restored_item', true );
						$is_recovered     = ! empty( $restored_session );
						$product          = $item->get_product();
						$product_name     = $item->get_name();
						$quantity         = $item->get_quantity();
						$line_total       = wc_price( $item->get_total() );
						$image_url        = $product ? wp_get_attachment_url( $product->get_image_id() ) : '';
						?>
						<tr style="border-bottom: 1px solid #eee;">
							<td style="padding: 12px;">
								<div style="display: flex; align-items: center; gap: 12px;">
									<?php if ( $image_url ) : ?>
										<img src="<?php echo esc_url( $image_url ); ?>"
											 alt="<?php echo esc_attr( $product_name ); ?>"
											 style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
									<?php endif; ?>
									<span style="color: #333; font-weight: 500;"><?php echo esc_html( $product_name ); ?></span>
								</div>
							</td>
							<td style="padding: 12px; text-align: center; color: #666;">
								<?php echo absint( $quantity ); ?>
							</td>
							<td style="padding: 12px; text-align: center;">
								<?php if ( $is_recovered ) : ?>
									<span style="padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: bold;"> <?php esc_html_e( 'Recovered', 'shopbuilder' ); ?> </span>
								<?php else : ?>
									<span style="background-color: #2196F3;padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: bold;">
									<?php esc_html_e( 'New', 'shopbuilder' ); ?>
								</span>
								<?php endif; ?>
							</td>
							<td style="padding: 12px; text-align: right; color: #333; font-weight: 500;">
								<?php echo wp_kses_post( $line_total ); ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>

				<!-- View Order Button -->
				<div style="text-align: center; margin-top: 30px;">
					<a href="<?php echo esc_url( $order_url ); ?>" style="display: inline-block; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;"> <?php esc_html_e( 'View Order Details', 'shopbuilder' ); ?> </a>
				</div>
			</div>

			<!-- Footer -->
			<div style="text-align: center; padding: 10px; color: #999; font-size: 12px;">
				<p style="margin: 0;">
					<?php
					echo sprintf(
					/* translators: %s: Store name */
						esc_html__( 'This is an automated notification from %s', 'shopbuilder' ),
						'<strong>' . esc_html( $store_name ) . '</strong>'
					);
					?>
				</p>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
