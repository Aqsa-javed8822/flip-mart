<?php
/**
 * Sticky add-to-cart Functions Class.
 *
 * @package Rse\SB
 */

namespace RadiusTheme\SB\Modules\AbandonedCartRecovery;

use RadiusTheme\SB\Helpers\Fns;

defined( 'ABSPATH' ) || exit();

/**
 * Sticky add-to-cart Functions Class.
 */
class CartRecoveryOptions {
	/**
	 * @return array[]
	 */
	private static function get_default_templates() {
			ob_start();
		?>
				<p> Few days back you left {{cart.product.names}} in your cart.</p>
				<p>To help make up your mind, we have added an exclusive 10% discount coupon {{cart.coupon_code}} to your cart.</p>
				<p><a href="{{cart.checkout_url}}" target="_blank" rel="noopener">Complete Your Purchase Now &gt;&gt;</a></p>
				<p>Hurry! This is a onetime offer and will expire in 24 Hours.</p>
				<p>In case you couldn't finish your order due to technical difficulties or because you need some help, just reply to this email we will be happy to help.</p>
				<p>Kind Regards,<br />{{admin.firstname}}<br />{{admin.company}}</p>
				<p>{{cart.unsubscribe}}</p>
			<?php
			$first_email = esc_attr( ob_get_clean() );
			ob_start();
			?>
				<p>Hi {{customer.firstname}}!</p>
				<p>I'm {{admin.firstname}}, and I help handle customer issues at {{admin.company}}.</p>
				<p>I just noticed that you tried to make a purchase, but unfortunately, there was some trouble. Is there anything I can do to help?</p>
				<p>You should be able to complete your checkout in less than a minute:<br /><a href="{{cart.checkout_url}}" target="_blank" rel="noopener"> Click here to continue your purchase </a></p>
				<p>Thanks!<br />{{admin.firstname}}<br />{{admin.company}}</p>
				<p>{{cart.unsubscribe}}</p>
			<?php
			$second_email = esc_attr( ob_get_clean() );
			ob_start();
			?>
				<p>Hi {{customer.firstname}}!</p>
				<p>I'm {{admin.firstname}}, and I help handle customer issues at {{admin.company}}.</p>
				<p>I just noticed that you tried to make a purchase, but unfortunately, there was some trouble. Is there anything I can do to help?</p>
				<p>You should be able to complete your checkout in less than a minute:<br /><a href="{{cart.checkout_url}}" target="_blank" rel="noopener"> Click here to continue your purchase </a></p>
				<p>Thanks!<br />{{admin.firstname}}<br />{{admin.company}}</p>
				<p>{{cart.unsubscribe}}</p>
			<?php
			$third_email = esc_attr( ob_get_clean() );
			return [
				[
					'template_status'      => 'on',
					'title'                => 'First Email',
					'subject'              => 'First Email',
					'email_frequency_unit' => 'days',
					'email_frequency'      => 1,
					'content'              => $first_email,
				],
				[
					'template_status'      => 'on',
					'title'                => 'Second Email',
					'subject'              => 'Second Email',
					'email_frequency_unit' => 'days',
					'email_frequency'      => 1,
					'content'              => $second_email,
				],
				[
					'template_status'      => 'on',
					'title'                => 'Third Email',
					'subject'              => 'Third Email',
					'email_frequency_unit' => 'days',
					'email_frequency'      => 1,
					'content'              => $third_email,
				],
			];
	}
	/**
	 * @return mixed|null
	 */
	public static function get_cart_recovery_fields() {
		return apply_filters(
			'rtsb/module/abandoned_cart_recovery/fields',
			[
				'abandoned_time'               => [
					'id'    => 'abandoned_time',
					'type'  => 'slider',
					'min'   => 2,
					'max'   => 60,
					'value' => 20,
					'unit'  => 'minutes',
					'label' => esc_html__( 'Time to Mark Cart Abandoned ( minutes ) ', 'shopbuilder' ),
					'help'  => esc_html__( 'When enabled, the plugin will track cart activity and mark carts as abandoned after the defined cut-off time (in minutes).', 'shopbuilder' ),
					'tab'   => 'general',
				],
				'disable_tracking_roles'       => [
					'id'       => 'disable_tracking_roles',
					'label'    => esc_html__( 'Exclude User Roles from Tracking', 'shopbuilder' ),
					'help'     => esc_html__( 'Carts belonging to users with these roles will not be tracked for abandonment. These users will not receive abandoned cart reminder emails.', 'shopbuilder' ),
					'type'     => 'checkbox',
					'value'    => [ 'editor', 'author' ],
					'options'  => array_map(
						function ( $status_key, $status_label ) {
							return [
								'value' => $status_key,
								'label' => $status_label,
							];
						},
						array_keys( Fns::get_all_user_roles() ),
						Fns::get_all_user_roles()
					) ,
					'multiple' => true,
					'tab'      => 'general',
				],
				'notify_recovery_to_admin'     => [
					'id'      => 'notify_recovery_to_admin',
					'type'    => 'switch',
					'default' => true,
					'label'   => esc_html__( 'Notify Recovery To Admin', 'shopbuilder' ),
					'help'    => esc_html__( 'Enable this option to send a notification to the site administrator whenever a cart is successfully recovered.', 'shopbuilder' ),
					'tab'     => 'general',
				],
				'recovery_notify_title'        => [
					'id'          => 'recovery_notify_title',
					'type'        => 'text',
					'label'       => esc_html__( 'Recovery Email Subject', 'shopbuilder' ),
					'help'        => esc_html__( 'Enter the subject line for recovery notification emails sent to admin.', 'shopbuilder' ),
					'placeholder' => esc_html__( 'Cart Recovered', 'shopbuilder' ),
					'value'       => esc_html__( 'Cart Recovered', 'shopbuilder' ),
					'tab'         => 'general',
					'dependency'  => [
						'rules' => [
							[
								'item'     => 'modules.abandoned_cart_recovery.notify_recovery_to_admin',
								'value'    => 'on',
								'operator' => '==',
							],
						],
					],
				],
				'recovery_admin_email'         => [
					'id'          => 'recovery_admin_email',
					'type'        => 'textarea',
					'label'       => esc_html__( 'Admin Email Address', 'shopbuilder' ),
					'help'        => esc_html__( 'Enter email addresses separated by commas to receive recovery report emails. Ex: support@yourstore.com, thesupport2@yourstore.com ', 'shopbuilder' ),
					'placeholder' => 'example@yourstore.com, example2@yourstore.com',
					'tab'         => 'general',
					'dependency'  => [
						'rules' => [
							[
								'item'     => 'modules.abandoned_cart_recovery.notify_recovery_to_admin',
								'value'    => 'on',
								'operator' => '==',
							],
						],
					],
				],
				'unsubscribe_page'             => [
					'id'      => 'unsubscribe_page',
					'type'    => 'select',
					'label'   => esc_html__( 'Unsubscribe Page', 'shopbuilder' ),
					'help'    => esc_html__( 'Choose the page where users will be redirected to unsubscribe.', 'shopbuilder' ),
					'options' => Fns::get_pages(),
					'tab'     => 'general',
				],
				// Send Recovery Report Emails.
				'email_settings'               => [
					'id'    => 'email_settings',
					'type'  => 'title',
					'label' => esc_html__( 'Email', 'shopbuilder' ),
					'tab'   => 'general',
				],
				// "From" Name
				'from_name'                    => [
					'id'          => 'from_name',
					'label'       => esc_html__( '"From" Name', 'shopbuilder' ),
					'help'        => esc_html__( 'The name that will appear as the sender in the email.', 'shopbuilder' ),
					'type'        => 'text',
					'placeholder' => esc_html__( 'John Doe', 'shopbuilder' ),
					'tab'         => 'general',
				],
				'from_address'                 => [
					'id'          => 'from_address',
					'label'       => esc_html__( '"From" Email Address', 'shopbuilder' ),
					'help'        => esc_html__( 'The email address from which recovery emails will be sent.', 'shopbuilder' ),
					'type'        => 'email',
					'placeholder' => esc_html__( 'example@yourstore.com', 'shopbuilder' ),
					'tab'         => 'general',
				],
				'reply_to_address'             => [
					'id'          => 'reply_to_address',
					'label'       => esc_html__( '"Reply-To" Email Address', 'shopbuilder' ),
					'help'        => esc_html__( 'The email address where replies from customers should be sent.', 'shopbuilder' ),
					'type'        => 'email',
					'placeholder' => esc_html__( 'support@yourstore.com', 'shopbuilder' ),
					'tab'         => 'general',
				],
				'coupon_settings'              => [
					'id'    => 'coupon_settings',
					'type'  => 'title',
					'label' => esc_html__( 'Coupon Settings', 'shopbuilder' ),
					'tab'   => 'general',
				],
				'delete_coupons_automatically' => [
					'id'      => 'delete_coupons_automatically',
					'type'    => 'switch',
					'default' => true,
					'label'   => esc_html__( 'Auto-Delete Expired Coupons', 'shopbuilder' ),
					'help'    => esc_html__( 'Enable this to automatically remove expired or used coupons once per week via a scheduled task.', 'shopbuilder' ),
					'tab'     => 'general',
				],
				'email_template'               => [
					'id'       => 'email_template',
					'type'     => 'cart_recovery_email_template',
					'label'    => esc_html__( 'Abandoned Cart Email Templates', 'shopbuilder' ),
					'tab'      => 'emailTemplates',
					'defaults' => self::get_default_templates(),
				],

			]
		);
	}
}
