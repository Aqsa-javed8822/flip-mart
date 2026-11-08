<?php
/**
 * AI Initialization Class.
 *
 * Handles AI-related module setup, including meta boxes, assets, and AJAX actions.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\AI;

use RadiusTheme\SB\AI\Ajax\AjaxAI;
use RadiusTheme\SB\AI\DB\CreateTableForAi;
use RadiusTheme\SB\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit();

/**
 * Initializes AI module components.
 */
class AIinit {
	/**
	 * Use Singleton trait.
	 */
	use SingletonTrait;

	/**
	 * @var array|false
	 */
	private $ai_data;
	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->ai_data = AIFns::activated_ai_data();
		$this->initialize();
		$this->metabox_and_assets();
		$this->embedding();
		if ( $this->ai_data ) {
			$this->ajax_action();
		}
	}

	/**
	 * @return void
	 */
	public function initialize() {
		add_action( 'init', [ CreateTableForAi::instance(), 'create_ai_embeddings_table' ] );
		add_action( 'init', [ AiController::instance(), 'start_cron' ] );
		add_action( 'rtsb_embedding_cron_run', [ AiController::instance(), 'process_batch' ] );
	}
	/**
	 * @return void
	 */
	public function embedding() {
		add_action( 'save_post_product', [ AiController::instance(), 'handle_product_save' ], 10, 2 );
	}
	/**
	 * Registers meta boxes and enqueues admin assets.
	 */
	public function metabox_and_assets() {
		add_action( 'admin_enqueue_scripts', [ MetaBoxScripts::instance(), 'admin_enqueue_scripts' ] );
		add_action( 'edit_form_after_title', [ MetaBoxScripts::instance(), 'render_meta_box' ] );
	}

	/**
	 * Registers AJAX actions for AI operations.
	 */
	public function ajax_action() {
		add_action( 'wp_ajax_rtsb_ai_generate_product_content', [ AjaxAI::instance(), 'generate_product_content' ] );
		add_action( 'wp_ajax_rtsb_ai_semantic_search_suggestions', [ AjaxAI::instance(), 'semantic_search_suggestions' ] );
		add_action( 'wp_ajax_nopriv_rtsb_ai_semantic_search_suggestions', [ AjaxAI::instance(), 'semantic_search_suggestions' ] );
	}
}
