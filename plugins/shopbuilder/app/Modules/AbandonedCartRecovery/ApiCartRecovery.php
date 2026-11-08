<?php
/**
 * Sticky Abandoned Cart Recovery Module Class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\AbandonedCartRecovery;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;
use WP_REST_Request;
use WP_REST_Response;

defined( 'ABSPATH' ) || exit();

/**
 * Sticky add-to-cart Module Class.
 */
class ApiCartRecovery {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Register /wp-json/email/v1/templates (POST)
	 */
	public function register_routes() {
		register_rest_route(
			'Cart/Recovery/v1',
			'/CreateTemplates',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'create_templates' ],
				'permission_callback' => function () {
					// Adjust capability as needed.
					return current_user_can( 'manage_options' );
				},
			]
		);
		register_rest_route(
			'Cart/Recovery/v1',
			'/getTemplates',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_templates' ],
				'permission_callback' => function () {
					// Adjust capability as needed.
					return current_user_can( 'manage_options' );
				},
			]
		);
		register_rest_route(
			'Cart/Recovery/v1',
			'/deleteTemplates',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'delete_templates' ],
				'permission_callback' => function () {
					// Adjust capability as needed.
					return current_user_can( 'manage_options' );
				},
			]
		);
		register_rest_route(
			'Cart/Recovery/v1',
			'/getCartAbandonment',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_cart_abandonment' ],
				'permission_callback' => function () {
					// Adjust capability as needed.
					return current_user_can( 'manage_options' );
				},
			]
		);
		register_rest_route(
			'Cart/Recovery/v1',
			'/deleteAbandonment',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'delete_cart_abandonment' ],
				'permission_callback' => function () {
					// Adjust capability as needed.
					return current_user_can( 'manage_options' );
				},
			]
		);
		register_rest_route(
			'Cart/Recovery/v1',
			'/getAbandonmentDetails',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_abandonment_details' ],
				'permission_callback' => function () {
					// Adjust capability as needed.
					return current_user_can( 'manage_options' );
				},
			]
		);

		register_rest_route(
			'Cart/Recovery/v1',
			'/unsubscribedAbandonment',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'unsubscribe_cart_abandonment' ],
				'permission_callback' => function () {
					// Adjust capability as needed.
					return current_user_can( 'manage_options' );
				},
			]
		);
		register_rest_route(
			'Cart/Recovery/v1',
			'/getOverView',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_overview' ],
				'permission_callback' => function () {
					// Adjust capability as needed.
					return current_user_can( 'manage_options' );
				},
			]
		);
		register_rest_route(
			'Cart/Recovery/v1',
			'/rescheduleEmails',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'reschedule_emails' ],
				'permission_callback' => function () {
					// Adjust capability as needed.
					return current_user_can( 'manage_options' );
				},
			]
		);
		register_rest_route(
			'Cart/Recovery/v1',
			'/deleteScheduleEmails',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'delete_scheduled_Emails' ],
				'permission_callback' => function () {
					// Adjust capability as needed.
					return current_user_can( 'manage_options' );
				},
			]
		);
	}

	/**
	 * Handle POST: creates/updates templates and returns a message string
	 *
	 * @param WP_REST_Request $request Create Template.
	 * @return WP_REST_Response
	 */
	public function delete_scheduled_Emails( WP_REST_Request $request ) {
		$body        = $request->get_json_params();
		$sheduled_id = $body['id'] ?? 0;
		$deleted     = false;
		if ( $sheduled_id ) {
			CartRecoveryDB::delete_sheduled( $sheduled_id );
			$deleted = true;
		}
		return new WP_REST_Response(
			[
				'deleted' => $deleted,
			],
			201
		);
	}

	/**
	 * Handle POST: creates/updates templates and returns a message string
	 *
	 * @param WP_REST_Request $request Create Template.
	 * @return WP_REST_Response
	 */
	public function reschedule_emails( WP_REST_Request $request ) {
		$body       = $request->get_json_params();
		$id         = $body['id'] ?? 0;
		$reschedule = false;
		if ( $id ) {
			CartRecoveryDB::reshedule_email( $id );
			$reschedule = true;
		}
		return new WP_REST_Response(
			[
				'reschedule' => $reschedule,
			],
			201
		);
	}
	/**
	 * Handle POST: creates/updates templates and returns a message string
	 *
	 * @param WP_REST_Request $request Create Template.
	 * @return WP_REST_Response
	 */
	public function get_abandonment_details( WP_REST_Request $request ) {
		$params  = $request->get_query_params();
		$id      = isset( $params['id'] ) ? absint( $params['id'] ) : 0;
		$history = CartRecoveryDB::get_abandonment_details( $id );
		return new WP_REST_Response( [ 'details' => $history ], 201 );
	}
	/**
	 * Handle POST: creates/updates templates and returns a message string
	 *
	 * @param WP_REST_Request $request Create Template.
	 * @return WP_REST_Response
	 */
	public function delete_cart_abandonment( WP_REST_Request $request ) {
		$body    = $request->get_json_params();
		$id      = absint( $body['id'] );
		$deleted = CartRecoveryDB::deleteAbandonmentByID( $id );
		return new WP_REST_Response(
			[
				'deleted' => $deleted,
			],
			201
		);
	}
	/**
	 * Handle POST: creates/updates templates and returns a message string
	 *
	 * @param WP_REST_Request $request Create Template.
	 * @return WP_REST_Response
	 */
	public function unsubscribe_cart_abandonment( WP_REST_Request $request ) {
		$body = $request->get_json_params();
		CartRecoveryFns::unsebscribe( absint( $body['id'] ) );
		return new WP_REST_Response(
			[
				'unsubscribed' => true,
			],
			201
		);
	}
	/**
	 * Handle POST: creates/updates templates and returns a message string
	 *
	 * @param WP_REST_Request $request Create Template.
	 * @return WP_REST_Response
	 */
	public function get_overview( WP_REST_Request $request ) {
		$params             = $request->get_query_params();
		$start              = $params['start'] ?? '';
		$end                = $params['end'] ?? '';
		$recoveredOrderMeta = CartRecoveryDB::getRecoveredOrderMeta( $start, $end );
		$recoveredRevenue   = CartRecoveryDB::getRecoveredRevenueByRecoveredOrderMeta( $recoveredOrderMeta );
		$lostOrder          = CartRecoveryDB::getLostOrderMeta( $start, $end );

		$recoverableOrder      = CartRecoveryDB::getRecoverableOrderData( $start, $end );
		$recoverableOrderCount = $recoverableOrder['count'] ?? 0;
		$recoveredOrderCount   = is_array( $recoveredOrderMeta ) ? count( $recoveredOrderMeta ) : 0;
		$lostOrderCount        = is_array( $lostOrder ) ? count( $lostOrder ) : 0;
		$total                 = absint( $recoveredOrderCount ) + absint( $lostOrderCount );
		$recoveryRate          = 0;
		if ( $total ) {
			$recoveryRate = 100 * absint( $recoveredOrderCount ) / $total;
		}
		$recoverableOrderData = $recoverableOrder['items'] ?? [];
		$chartData            = CartRecoveryFns::get_chart_data( $recoverableOrderData, $recoveredOrderMeta, $start, $end );
		$data                 = [
			'recoveredOrderCount'   => $recoveredOrderCount,
			'recoveredRevenue'      => $recoveredRevenue,
			'lostOrderCount'        => $lostOrderCount,
			'recoverableOrderCount' => $recoverableOrderCount,
			'recoverableRevenue'    => $recoverableOrder['revenue'] ?? '',
			'recoveryRate'          => round( $recoveryRate, 2 ),
			'chartData'             => $chartData,
		];
		return new WP_REST_Response( $data, 201 );
	}
	/**
	 * Handle GET.
	 *
	 * @param WP_REST_Request $request Create Template.
	 * @return WP_REST_Response
	 */
	public function get_cart_abandonment( $request ) {
		$params      = $request->get_query_params();
		$page        = isset( $params['paged'] ) ? intval( $params['paged'] ) : 1;
		$per_page    = isset( $params['per_page'] ) ? intval( $params['per_page'] ) : 5;
		$abandonment = CartRecoveryDB::getAllCartAbandonment( $per_page, $page );
		$count       = CartRecoveryDB::getCartAbandonmentCount();
		return new WP_REST_Response(
			[
				'message'        => 'Get Abandonment!',
				'getAbandonment' => [
					'total'       => $count,
					'abandonment' => $abandonment,
				],
			],
			201
		);
	}

	/**
	 * Handle POST: creates/updates templates and returns a message string
	 *
	 * @param WP_REST_Request $request Create Template.
	 * @return WP_REST_Response
	 */
	public function delete_templates( WP_REST_Request $request ) {
		$body      = $request->get_json_params();
		$id        = absint( $body['id'] );
		$templates = CartRecoveryDB::deleteTemplate( $id );
		return new WP_REST_Response(
			[
				'message'   => 'Templates Deleted successfully!',
				'templates' => $templates,
			],
			201
		);
	}
	/**
	 * Handle POST: creates/updates templates and returns a message string.
	 *
	 * @return WP_REST_Response
	 */
	/**
	 * Handle GET: retrieves templates with meta and caches them in a transient.
	 *
	 * @return WP_REST_Response
	 */
	public function get_templates() {
		// Build templates data if cache is not available.
		$templates = CartRecoveryDB::getAllTemplates();
		return new WP_REST_Response(
			[
				'message'   => 'Templates created successfully!',
				'templates' => $templates,
			],
			201
		);
	}
	/**
	 * Handle POST: creates/updates templates and returns a message string
	 *
	 * @param WP_REST_Request $request Create Template.
	 * @return WP_REST_Response
	 */
	public function create_templates( WP_REST_Request $request ) {
		$body      = $request->get_json_params();
		$templates = [];
		if ( is_array( $body ) && isset( $body['templates'] ) && is_array( $body['templates'] ) ) {
			$templates = $body['templates'];
		} elseif ( is_array( $body ) ) {
			$templates = $body;
		}
		// Guard: 0 or negative length (defensive).
		$length = is_array( $templates ) ? count( $templates ) : 0;
		if ( $length <= 0 ) {
			return new WP_REST_Response( [ 'message' => 'No templates to create.' ], 200 );
		}
		// Basic sanitize & normalize.
		$cleanTemplate = array_map(
			function ( $template ) {
				$other_fields = [
					'coupon_enabled'        => sanitize_text_field( $template['coupon_enabled'] ?? '' ),
					'coupon_amount'         => absint( $template['coupon_amount'] ?? 0 ),
					'coupon_prefix'         => sanitize_text_field( $template['coupon_prefix'] ?? '' ),
					'coupon_expiration'     => absint( $template['coupon_expiration'] ?? 1 ),
					'coupon_free_shipping'  => sanitize_text_field( $template['coupon_free_shipping'] ?? '' ),
					'coupon_individual_use' => sanitize_text_field( $template['coupon_individual_use'] ?? '' ),
					'coupon_auto_apply'     => sanitize_text_field( $template['coupon_auto_apply'] ?? '' ),
				];
				return [
					'id'             => isset( $template['id'] ) ? absint( $template['id'] ) : 0,
					'title'          => isset( $template['title'] ) ? sanitize_text_field( $template['title'] ) : '',
					'email_subject'  => isset( $template['email_subject'] ) ? sanitize_text_field( $template['email_subject'] ) : '',
					'email_body'     => isset( $template['email_body'] ) ? wp_kses_post( $template['email_body'] ) : '',
					'is_activated'   => isset( $template['is_activated'] ) && 'on' === $template['is_activated'] ? 'on' : 0,
					'frequency'      => isset( $template['frequency'] ) ? absint( $template['frequency'] ) : 0,
					'frequency_unit' => isset( $template['frequency_unit'] ) ? strtoupper( sanitize_text_field( $template['frequency_unit'] ) ) : 'DAY', // Normalized to match the scheduler's MINUTE|HOUR|DAY switch.
					'menu_order'     => isset( $template['menu_order'] ) ? absint( $template['menu_order'] ) : 0,
					'other_fields'   => maybe_serialize( $other_fields ),
				];
			},
			$templates
		);
		CartRecoveryDB::createTemplates( $cleanTemplate );
		$templates = CartRecoveryDB::getAllTemplates();
		return new WP_REST_Response(
			[
				'message'   => 'Templates created successfully!',
				'templates' => $templates,
			],
			201
		);
	}
}
