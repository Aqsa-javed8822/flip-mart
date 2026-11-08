<?php
/**
 * AI Initialization Class.
 *
 * Handles AI-related module setup, including meta boxes, assets, and AJAX actions.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\AI\DB;

use RadiusTheme\SB\AI\AIFns;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit();

/**
 * Initializes AI module components.
 */
class CreateTableForAi {
	/**
	 * Use Singleton trait.
	 */
	use SingletonTrait;

	/**
	 * @return void
	 */
	public function create_ai_embeddings_table() {
		global $wpdb;
        $is_table_exist = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->prefix . AIFns::$ai_embeddings_table ) ); //phpcs:ignore
		if ( ! empty( $is_table_exist ) ) {
			return;
		}
		Fns::DB()::create( AIFns::$ai_embeddings_table )->column( 'id' )->bigInt( 20 )->unsigned()->autoIncrement()->primary()->required()
			->column( 'product_id' )->bigInt( 20 )->unsigned()->required()
			->column( 'title' )->text()->required()
			->column( 'embedding' )->longText()->required()
			->column( 'info' )->string()->nullable()
			->column( 'created_at' )->dateTime()
			->column( 'updated_at' )->dateTime()
			->index( [ 'product_id' ] )
			->execute();
	}
}
