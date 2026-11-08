<?php
/**
 * AI Initialization Class.
 *
 * Handles AI-related module setup, including meta boxes, assets, and AJAX actions.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\AI;

use RadiusTheme\SB\AI\Embedding\DataEmbedding;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit();

/**
 * Initializes AI module components.
 */
class AiController {
	/**
	 * Use Singleton trait.
	 */
	use SingletonTrait;

	/**
	 * Handle product save event and generate AI embeddings for product data.
	 *
	 * Triggers when a product is saved and published, then sends the title
	 * and content to the DataEmbedding service for embedding generation and storage.
	 *
	 * @param int      $post_id The ID of the saved product.
	 * @param \WP_Post $post    The post object of the saved product.
	 *
	 * @return void
	 */
	public function handle_product_save( $post_id, $post ) {
		if ( 'publish' !== $post->post_status ) {
			return;
		}
		$ai_data = AIFns::activated_ai_data();

		if ( empty( $ai_data['api_key'] ) || empty( $ai_data['client'] ) ) {
			return;
		}
		$title    = $post->post_title;
		$content  = $post->post_content;
		$service  = DataEmbedding::instance();
		$embedded = $service->generate_and_store( $post_id, $title, $content );
		if ( $embedded ) {
			update_post_meta( $post_id, '_has_embedding', 1 );
		}
	}

	/**
	 * Starts the AI embedding cron process.
	 *
	 * @return void
	 */
	public function start_cron() {
		if ( ! isset( $_GET['action'] ) || sanitize_text_field( wp_unslash( $_GET['action'] ) ) !== 'rtsb_start_embedding_process' ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Access denied.', 'shopbuilder' ) );
		}
		if ( ! wp_verify_nonce( Fns::get_nonce(), rtsb()->nonceText ) ) {
			wp_die( esc_html__( 'Invalid request.', 'shopbuilder' ) );
		}
		if ( ! wp_next_scheduled( 'rtsb_embedding_cron_run' ) ) {
			wp_schedule_single_event( time() + 2, 'rtsb_embedding_cron_run' );
		}
		update_option( 'rtsb_embedding_in_progress', true );
		update_option(
			'rtsb_embedding_progress',
			[
				'processed' => 0,
			]
		);
	}


	/**
	 * @return void
	 */
	public function process_batch() {
		$ai_data = AIFns::activated_ai_data();
		if ( empty( $ai_data['api_key'] ) ) {
			return;
		}
		$products = get_posts(
			[
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => 25,
				'fields'         => 'ids',
				'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery
					[
						'key'     => '_has_embedding',
						'compare' => 'NOT EXISTS',
					],
				],
			]
		);
		if ( empty( $products ) ) {
			delete_option( 'rtsb_embedding_in_progress' );
			delete_option( 'rtsb_embedding_progress' );
			update_option( 'rtsb_embedding_process_completed', time() );
			return; // all done.
		}
		$service = DataEmbedding::instance();
		foreach ( $products as $id ) {
			$title    = get_the_title( $id );
			$content  = get_post_field( 'post_content', $id );
			$embedded = $service->generate_and_store( $id, $title, $content );
			if ( $embedded ) {
				update_post_meta( $id, '_has_embedding', 1 );
			}
		}
		// Update progress.
		$progress               = get_option(
			'rtsb_embedding_progress',
			[
				'processed' => 0,
			]
		);
		$progress['processed'] += count( $products );
		update_option( 'rtsb_embedding_progress', $progress );
		// Schedule the next batch immediately.
		wp_schedule_single_event( time() + 2, 'rtsb_embedding_cron_run' );
	}
}
