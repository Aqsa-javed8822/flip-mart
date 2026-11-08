<?php

namespace RadiusTheme\SB\Modules\WishList;

use RadiusTheme\SB\Abstracts\Api;
use RadiusTheme\SB\Helpers\Fns;
use WP_Error;
use WP_HTTP_Response;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

class WishlistRouteV1 extends Api {


	public function config() {
	}


	public function init() {
		add_action(
			'rest_api_init',
			function () {
				register_rest_route(
					$this->getNamespace(),
					'/wishlist/add_to_list',
					[
						'methods'             => WP_REST_Server::CREATABLE,
						'callback'            => [ $this, 'add_to_list_callback' ],
						'permission_callback' => [ $this, 'wishlist_permission_callback' ] , // '__return_true'
					]
				);
				register_rest_route(
					$this->getNamespace(),
					'/wishlist/remove_from_list',
					[
						'methods'             => WP_REST_Server::CREATABLE,
						'callback'            => [ $this, 'remove_from_list_callback' ],
						'permission_callback' => [ $this, 'wishlist_permission_callback' ],
					]
				);
			}
		);
	}
	public function wishlist_permission_callback( $request ) {
		$has_permition = ! get_current_user_id() && Fns::get_option( 'modules', 'wishlist', 'enable_login_limit', false, 'checkbox' );

		if ( $has_permition ) {
			return new WP_Error(
				'authentication_error',
				__( 'Sorry, you are not allowed to do that!. Only Logged-in user can use this feature', 'shopbuilder' ),
				[ 'status' => rest_authorization_required_code() ]
			);
		}
		return true;
	}
	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function add_to_list_callback( $request ) {
		$product_id = absint( $request->get_param( 'product_id' ) );

		if ( ! $product_id || 'publish' !== get_post_status( $product_id ) ) {
			return $this->sendError( esc_html__( 'Product id not found.', 'shopbuilder' ) );
		}

		$inserted = WishlistFns::instance()->add_product( $product_id );
		if ( $inserted === 0 ) {
			return $this->sendError(
				esc_html__( 'Product already in wishlist!', 'shopbuilder' ),
				[
					'action' => 'exist',
				]
			);
		}

		return $this->sendResponse(
			[
				'item_count' => count( WishlistFns::instance()->get_wishlist_ids() ),
				'product_id' => $product_id,
			]
		);
	}


	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function remove_from_list_callback( $request ) {
		$product_id = absint( $request->get_param( 'product_id' ) );

		if ( ! $product_id ) {
			return $this->sendError( esc_html__( 'Product id not found.', 'shopbuilder' ) );
		}

		$deleted = WishlistFns::instance()->remove_product( $product_id );
		if ( ! $deleted ) {
			if ( $deleted === 0 ) {
				return $this->sendError( esc_html__( 'The product is not exist at the list!', 'shopbuilder' ) );
			}

			return $this->sendError( esc_html__( 'The product does not deleted!', 'shopbuilder' ) );
		}

		return $this->sendResponse(
			[
				'product_id' => $product_id,
				'item_count' => count( WishlistFns::instance()->get_wishlist_ids() ),
			]
		);
	}
}
