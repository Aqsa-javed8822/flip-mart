<?php
/**
 * Class DataEmbedding
 *
 * Handles the generation, storage, and retrieval of AI-powered text embeddings
 * for WooCommerce products using supported AI services like OpenAI, Gemini,
 * or DeepSeek. Also provides semantic search capabilities using cosine similarity.
 *
 * @package RadiusTheme\SB\AI\Embedding
 * @since 1.0.0
 */

namespace RadiusTheme\SB\AI\Embedding;

use RadiusTheme\SB\AI\DB\AIDB;
use RadiusTheme\SB\AI\AIFns;
use RadiusTheme\SB\Traits\SingletonTrait;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Class DataEmbedding
 *
 * @package RadiusTheme\SB\AI\Embedding
 * @since 1.0.0
 */
class DataEmbedding {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Generate and store an AI embedding for a product.
	 *
	 * @param int    $product_id The product ID.
	 * @param string $title      The product title.
	 * @param string $content    The product content or description.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function generate_and_store( $product_id, $title, $content ) {
		$ai_data = AIFns::activated_ai_data();
		if ( empty( $ai_data['api_key'] ) ) {
			return false;
		}

		$text       = $title . ' ' . wp_strip_all_tags( $content );
		$ai_service = AIFns::initializeAIService();
		$embedding  = $ai_service->generateEmbedding( $text );
		if ( empty( $embedding ) || ! is_array( $embedding ) ) {
			return false;
		}
		$info   = [
			'word_count' => str_word_count( $text ),
			'source'     => 'product',
		];
		$result = AIDB::upsert_embeding( $product_id, $title, $embedding, $info );
		return ! empty( $result );
	}

	/**
	 * Perform a semantic search based on a given query.
	 *
	 * @param string $query The user search query.
	 * @param int    $limit Optional. Number of results to return. Default 0 (all).
	 *
	 * @return array List of matching product titles.
	 */
	public function search( $query, $limit = 5 ) {
		$ai_service      = AIFns::initializeAIService();
		$query_embedding = $ai_service->generateEmbedding( $query );
		if ( empty( $query_embedding ) || ! is_array( $query_embedding ) ) {
			return [];
		}
		// Log query embedding.
		$rows = AIDB::get_all();
		if ( empty( $rows ) ) {
			return [];
		}
		$results = $this->find_similar( $query_embedding, $rows, $limit );
		return wp_list_pluck( $results, 'post_id' );
	}

	/**
	 * Find the most semantically similar embeddings using cosine similarity.
	 *
	 * Optimized to reduce redundant computations and use a dedicated
	 * score calculation method for better maintainability.
	 *
	 * @param array $embedding The query embedding vector to compare.
	 * @param array $rows      The stored embedding records from the database.
	 * @param int   $limit     Optional. Number of top matches to return. Default 5.
	 *
	 * @return array List of matched items with product ID, title, and similarity score.
	 */
	public function find_similar( array $embedding, array $rows, int $limit = 5 ): array {
		$minimum_match = AIFns::get_embedding_minimum_accuracy();
		$matches       = [];
		foreach ( $rows as $row ) {
			if ( empty( $row['embedding'] ) ) {
				continue;
			}
			$vector = maybe_unserialize( $row['embedding'] );
			if ( ! is_array( $vector ) ) {
				continue;
			}
			// Avoid redundant norm computation if similarity calc includes it.
			$score = $this->calculate_similarity_score( $embedding, $vector );
			if ( $score >= $minimum_match ) {
				$matches[] = [
					'post_id'    => isset( $row['product_id'] ) ? (int) $row['product_id'] : 0,
					'post_title' => isset( $row['title'] ) ? sanitize_text_field( $row['title'] ) : '',
					'score'      => round( $score, 4 ),
				];
			}
		}
		if ( empty( $matches ) ) {
			return [];
		}
		// Use array_multisort for faster sorting on large datasets.
		array_multisort( array_column( $matches, 'score' ), SORT_DESC, $matches );
		return $limit > 0 ? array_slice( $matches, 0, $limit ) : $matches;
	}


	/**
	 * Calculate cosine similarity score between query and stored embedding.
	 *
	 * This version avoids redundant normalization by reusing the precomputed
	 * query norm and computes the dot product and target norm in one pass.
	 *
	 * @param array $query_vec  The query embedding vector.
	 * @param array $target_vec The stored embedding vector.
	 *
	 * @return float Cosine similarity score (0.0–1.0).
	 */
	protected function calculate_similarity_score( array $query_vec, array $target_vec ): float {
		$dot   = 0.0;
		$normA = 0.0;
		$normB = 0.0;
		$count = min( count( $query_vec ), count( $target_vec ) );
		for ( $i = 0; $i < $count; $i++ ) {
			$dot   += $query_vec[ $i ] * $target_vec[ $i ];
			$normA += $query_vec[ $i ] ** 2;
			$normB += $target_vec[ $i ] ** 2;
		}
		return $dot / ( sqrt( $normA ) * sqrt( $normB ) );
	}
}
