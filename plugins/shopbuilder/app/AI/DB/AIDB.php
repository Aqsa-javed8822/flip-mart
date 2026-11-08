<?php
/**
 * AI Database Management Class.
 *
 * Handles database operations for AI-related embeddings,
 * including fetching and upserting (insert or update) data in the embeddings table.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\AI\DB;

use RadiusTheme\SB\AI\AIFns;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit();

/**
 * Class AIDB
 *
 * Provides methods to interact with the AI embeddings database table.
 */
class AIDB {
	/**
	 * Use Singleton trait to ensure a single instance of this class.
	 */
	use SingletonTrait;

	/**
	 * Retrieve all AI embeddings from the database.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of all embedding records with properly unserialized data.
	 */
	public static function get_all() {
		$results = Fns::DB()::select( '*' )
			->from( AIFns::$ai_embeddings_table )
			->get();
		if ( empty( $results ) ) {
			return [];
		}
		// Process results to ensure proper data types.
		$processed = [];
		foreach ( $results as $row ) {
			$row_array = (array) $row;
			// Unserialize embedding and convert to floats.
			if ( isset( $row_array['embedding'] ) ) {
				$embedding = maybe_unserialize( $row_array['embedding'] );
				if ( is_array( $embedding ) ) {
					// Critical: Ensure all values are floats for cosine similarity.
					$row_array['embedding'] = array_map( 'floatval', $embedding );
				} else {
					continue; // Skip invalid embeddings.
				}
			}
			// Unserialize info data.
			if ( isset( $row_array['info'] ) ) {
				$row_array['info'] = maybe_unserialize( $row_array['info'] );
			}
			$processed[] = $row_array;
		}
		return $processed;
	}

	/**
	 * Insert or update embedding data for a specific product.
	 *
	 * Checks if a record already exists for the given product ID.
	 * If it exists, the record is updated; otherwise, a new record is inserted.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $product_id Product ID to associate with the embedding.
	 * @param string $title      Product title or reference title for the embedding.
	 * @param array  $embedding  Embedding vector data (array of floats).
	 * @param array  $info       Additional metadata or information related to the embedding.
	 *
	 * @return bool True on successful insert or update, false on failure.
	 */
	public static function upsert_embeding( $product_id, $title, $embedding, $info ) {
		// Validate inputs.
		if ( ! is_array( $embedding ) || empty( $embedding ) ) {
			return false;
		}
		// Critical: Ensure all embedding values are floats before serialization.
		$embedding = array_map( 'floatval', $embedding );
		$exists    = Fns::DB()::select( 'id' )
			->from( AIFns::$ai_embeddings_table )
			->where( 'product_id', '=', absint( $product_id ) )
			->get();
		$data      = [
			'title'     => sanitize_text_field( $title ),
			'embedding' => maybe_serialize( $embedding ),
			'info'      => maybe_serialize( $info ),
		];
		if ( ! empty( $exists[0] ) ) {
			$data['updated_at'] = current_time( 'mysql' );
			// Update existing record.
			Fns::DB()::update( AIFns::$ai_embeddings_table, $data )
				->where( 'product_id', '=', absint( $product_id ) )
				->execute();
			return true;
		} else {
			// Insert new record.
			$data['product_id'] = absint( $product_id );
			$data['created_at'] = current_time( 'mysql' );
			Fns::DB()::insert( AIFns::$ai_embeddings_table, [ $data ] )->execute();
			return true;
		}
	}
}
