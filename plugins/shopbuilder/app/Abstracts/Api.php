<?php

namespace RadiusTheme\SB\Abstracts;

use WP_Error;
use WP_HTTP_Response;
use WP_REST_Response;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

abstract class Api {
	const API_PREFIX = 'rtsb';
	protected $version = 'v1';

	abstract public function config();

	abstract public function init();

	public function __construct() {
		$this->config();
		$this->init();
	}

	public function getNamespace() {
		$version = empty( $this->version ) ? 'v1' : $this->version;

		return self::API_PREFIX . '/' . $version;
	}

	/**
	 * @param       $message
	 * @param array $data
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	protected function sendResponse( $data = [], $message = '' ) {
		$resData  = [
			'success' => true,
			'data'    => $data,
			'message' => $message,
		];
		$response = new WP_REST_Response( $resData );

		return rest_ensure_response( $response );
	}

	/**
	 * @param string $error
	 * @param int $code
	 * @param array $errorData
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	protected function sendError( $error, $errorData = [], $code = 200 ) {
		$resData = [
			'success' => false,
			'message' => $error,
		];
		if ( ! empty( $errorData ) ) {
			$resData['data'] = $errorData;
		}
		$response = new WP_REST_Response( $resData );
		$response->set_status( $code );

		return rest_ensure_response( $response );
	}

}