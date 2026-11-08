<?php
/**
 * AIFns Helpers class
 *
 * @package  RadiusTheme\SB
 */

namespace RadiusTheme\SB\AI;

// Do not allow directly accessing this file.
use Exception;
use RadiusTheme\SB\AI\AIServices\DeepSeekAdapter;
use RadiusTheme\SB\AI\AIServices\GeminiAdapter;
use RadiusTheme\SB\AI\AIServices\OpenAIAdapter;
use RadiusTheme\SB\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Fns class
 */
class AIFns {
	/**
	 * @var array
	 */
	private static $cache = [];
	/**
	 * AI Embeddings database table name.
	 *
	 * @var string
	 */
	public static $ai_embeddings_table = 'rtsb_ai_embeddings';
	/**
	 * Retrieve the activated AI client and its API key.
	 *
	 * @return array|false {
	 *     @type string $client  Active AI provider name.
	 *     @type string $api_key Corresponding API key.
	 * }
	 */
	public static function activated_ai_data() {
		$ai_tools = self::get_options( 'ai_tools' );
		if ( empty( $ai_tools ) ) {
			return false;
		}
		$apiKey = '';
		switch ( $ai_tools ) {
			case 'OpenAI':
				$apiKey = self::get_options( 'gpt_api_key' );
				break;
			case 'Gemini':
				$apiKey = self::get_options( 'gemini_api_key' );
				break;
			case 'DeepSeek':
				$apiKey = self::get_options( 'deepseek_api_key' );
				break;
		}
		if ( empty( $apiKey ) ) {
			return false;
		}
		return [
			'client'  => $ai_tools,
			'api_key' => $apiKey,
		];
	}
	/**
	 * Get total published listings
	 */
	public static function activated_semantic_search() {
		$ai_data = self::activated_ai_data();
		if ( ! $ai_data || ! in_array( $ai_data['client'], [ 'OpenAI', 'Gemini' ], true ) ) {
			return false;
		}
		$semantic_search = self::get_options( 'enable_semantic_search' );
		return 'on' === $semantic_search;
	}

	/**
	 * Get total published listings
	 */
	public static function need_product_embedding() {
		$listings = get_posts(
			[
				'post_type'   => 'product',
				'post_status' => 'publish',
				'fields'      => 'ids',
				'meta_query'  => [ // phpcs:ignore WordPress.DB.SlowDBQuery
					[
						'key'     => '_has_embedding',
						'compare' => 'NOT EXISTS',
					],
				],
			]
		);
		return count( $listings );
	}
	/**
	 * Returns the minimum cosine similarity threshold for semantic matching.
	 *
	 * Converts the user-selected percentage (1–100%) into a realistic cosine
	 * similarity range. Semantic search typically requires a threshold between
	 * 0.55–0.90 for meaningful accuracy.
	 *
	 * @return float Cosine similarity threshold (0.55–0.90).
	 */
	public static function get_embedding_minimum_accuracy() {
		$percentage = absint( self::get_options( 'minimum_matching_percentage' ) );
		return ! empty( $percentage ) ? absint( $percentage ) / 100 : 0.4;
	}

	/**
	 * @param string       $key Default Attribute.
	 * @param array|string $default Default.
	 * @return array|string
	 */
	public static function get_options( $key = null, $default = '' ) {
		$options = Fns::get_options( 'general', 'ai_implementation' );
		if ( $key ) {
			if ( isset( $options[ $key ] ) ) {
				return $options[ $key ];
			} else {
				return $default;
			}
		}
		return $options;
	}

	/**
	 * Initializes the AI service based on the configured AI tools.
	 *
	 * This method retrieves the AI tool settings from the configuration,
	 * dynamically instantiates the appropriate AI client class, and creates
	 * the corresponding AI service adapter. It handles validation and error
	 * reporting for missing or invalid configurations.
	 *
	 * @since 1.0.0
	 *
	 * @throws Exception If the client class cannot be instantiated or an error occurs during initialization.
	 *
	 * @return bool|DeepSeekAdapter|GeminiAdapter|OpenAIAdapter The initialized AI service adapter instance.
	 */
	public static function initializeAIService() {
		$ai_data = self::activated_ai_data();
		if ( ! $ai_data ) {
			return false;
		}
		$ai_tools    = $ai_data['client'];
		$clientClass = 'RadiusTheme\\SB\\AI\\AIServices\\AIClients\\' . $ai_tools . 'Client';
		if ( ! class_exists( $clientClass ) ) {
			return false;
		}
		try {
			$client = new $clientClass();
			return self::createAIService( $ai_tools, $client );
		} catch ( \Throwable $e ) {
			return false;
		}
	}

	/**
	 * Creates an AI service adapter based on the specified AI type.
	 *
	 * This factory method instantiates and returns the appropriate AI service
	 * adapter (OpenAI, Gemini, or DeepSeek) based on the provided AI type string.
	 * Each adapter implements a common interface for interacting with different
	 * AI providers.
	 *
	 * @since 1.0.0
	 *
	 * @param string $aiType   The type of AI service ('OpenAI', 'Gemini', or 'DeepSeek').
	 * @param object $client   The AI client instance to be wrapped by the adapter.
	 *
	 * @throws Exception If the specified AI service type is not supported.
	 *
	 * @return OpenAIAdapter|GeminiAdapter|DeepSeekAdapter The created AI service adapter instance.
	 */
	public static function createAIService( string $aiType, $client ) {
		switch ( $aiType ) {
			case 'OpenAI':
				return new OpenAIAdapter( $client );
			case 'Gemini':
				return new GeminiAdapter( $client );
			case 'DeepSeek':
				return new DeepSeekAdapter( $client );
			default:
				throw new Exception( 'AI service not supported' );
		}
	}
	/**
	 * Build an AI prompt based on content type and instruction language.
	 *
	 * @param string $content_type The type of content to generate (title, description, short_description).
	 * @param string $instruction  The product information or user instruction.
	 *
	 * @return string The full AI prompt with language guidance.
	 */
	public static function build_prompt( $content_type, $instruction ) {
		// Sanitize instruction.
		$instruction = trim( preg_replace( '/\s+/', ' ', $instruction ) );
		// Truncate if needed (based on AI model token limits).
		if ( strlen( $instruction ) > 2500 ) {
			$instruction = substr( $instruction, 0, 2500 ) . '...';
		}
		$prompts       = [
			'title'             => sprintf(
				"Generate a compelling, SEO-friendly product title (50-70 chars) based on:\n\n%s\n\nCreate only the title.",
				$instruction
			),
			'description'       => sprintf(
				"Write a detailed product description (150-300 words) based on:\n\n%s\n\nInclude:\n• Key features\n• Benefits\n• Use cases\n• SEO-friendly content\n\nUse clear paragraphs.",
				$instruction
			),
			'short_description' => sprintf(
				"Write a short description (2-3 sentences, max 160 chars) based on:\n\n%s\n\nHighlight key benefits only.",
				$instruction
			),
		];
		$base_prompt   = $prompts[ $content_type ] ?? '';
		$language_hint = "\n\nIMPORTANT: Write in the same language as the input above.";
		return $base_prompt . $language_hint;
	}
}
