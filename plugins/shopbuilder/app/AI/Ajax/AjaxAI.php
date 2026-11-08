<?php

namespace RadiusTheme\SB\AI\Ajax;

// Do not allow directly accessing this file.
use RadiusTheme\SB\AI\AIFns;
use RadiusTheme\SB\AI\Embedding\DataEmbedding;
use RadiusTheme\SB\Traits\SingletonTrait;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * AIPower Model
 *
 * Manages AI service initialization and adapter creation for various AI providers
 * including OpenAI, Gemini, and DeepSeek.
 *
 * @since 1.0.0
 */
class AjaxAI {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * AJAX handler for generating AI content
	 *
	 * @return void
	 */
	public function generate_product_content() {
		check_ajax_referer( 'rtsb_ai_nonce', 'nonce' );
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( [ 'message' => __( 'Permission denied', 'shopbuilder' ) ] );
		}
		$content_type = sanitize_text_field( wp_unslash( $_POST['content_type'] ?? '' ) );
		$instruction  = isset( $_POST['instruction'] ) ? sanitize_text_field( wp_unslash( $_POST['instruction'] ) ) : '';

		try {
			$ai_service        = AIFns::initializeAIService();
			$generated_content = $ai_service->generateResponse( $content_type, $instruction );
			wp_send_json_success(
				[
					'content' => $generated_content,
					'type'    => $content_type,
				]
			);
		} catch ( \Exception $e ) {
			wp_send_json_error( [ 'message' => $e->getMessage() ] );
		}
	}

	/**
	 * @return void
	 */
	public function semantic_search_suggestions() {
		check_ajax_referer( 'rtsb_nonce', 'nonce' );
		$query         = sanitize_text_field( wp_unslash( $_GET['searchTerm'] ?? '' ) );
		$limit         = absint( wp_unslash( $_GET['product_limit'] ?? 5 ) );
		$service       = DataEmbedding::instance();
		$similar_posts = $service->search( $query, $limit );
		$suggestions   = ! empty( $similar_posts ) ? $similar_posts : [];
		wp_send_json_success(
			[
				'suggestions' => $suggestions,
			]
		);
	}
}
