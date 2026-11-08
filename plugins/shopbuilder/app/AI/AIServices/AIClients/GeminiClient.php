<?php

namespace RadiusTheme\SB\AI\AIServices\AIClients;

use JsonException;
use RadiusTheme\SB\AI\AIFns;

/**
 * Class GeminiClient
 *
 * Handles communication with Google's Gemini API for AI-generated responses.
 */
class GeminiClient {

	/**
	 * AI model name.
	 *
	 * @var string
	 */
	protected $model = '';

	/**
	 * Maximum token limit.
	 *
	 * @var string
	 */
	protected $token = '200';
	/**
	 * Maximum token limit.
	 *
	 * @var string
	 */
	protected $apiKey = '';
	/**
	 * Maximum token limit.
	 *
	 * @var string
	 */
	protected $generateContent_url = '';
	/**
	 * Maximum token limit.
	 *
	 * @var string
	 */
	protected $embedContent_url = '';
	/**
	 * Maximum token limit.
	 *
	 * @var string
	 */
	protected $root_url = '';
	/**
	 * Constructor.
	 *
	 * Loads Gemini settings and validates API credentials.
	 *
	 * @throws \Exception If API key is missing.
	 */
	public function __construct() {
		$this->apiKey = AIFns::get_options( 'gemini_api_key' );
		/**
		 * Hard-coded model to avoid confusion.
		 * $this->model = AIFns::get_options( 'gemini_models', 'gemini-2.5-flash' );
		 */
		$this->model               = 'gemini-2.5-flash';
		$this->token               = AIFns::get_options( 'gemini_max_token' );
		$this->root_url            = 'https://generativelanguage.googleapis.com/v1beta/models/';
		$this->generateContent_url = $this->root_url . $this->model . ':generateContent?key=' . $this->apiKey;
		$this->embedContent_url    = $this->root_url . 'gemini-embedding-001:embedContent';
		if ( empty( $this->apiKey ) ) {
			throw new \Exception( 'Gemini API key is not properly configured.' );
		}
	}

	/**
	 * Get a general response from Gemini.
	 *
	 * @param string $prompt         Input text.
	 * @param string $system_prompt  Context for the model.
	 *
	 * @return string AI-generated response or error message.
	 */
	public function ask( string $prompt, string $system_prompt = '' ): string {
		$contents  = [
			[
				'role'  => 'user',
				'parts' => [
					[ 'text' => $system_prompt . 'give me plain text no markdown and no html' ],
				],
			],
			[
				'role'  => 'user',
				'parts' => [
					[ 'text' => $prompt ],
				],
			],
		];
		$body_data = [
			'contents'         => $contents,
			'generationConfig' => [ 'temperature' => 0.7 ],
		];

		$response = wp_remote_post(
			$this->generateContent_url,
			[
				'headers' => [ 'Content-Type' => 'application/json' ],
				'body'    => wp_json_encode( $body_data ),
				'timeout' => 12000,
			]
		);

		if ( is_wp_error( $response ) ) {
			$error = $response->get_error_message();
			return 'Error: ' . $error;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );

		if ( 200 !== $code ) {
			return 'Error: HTTP ' . $code;
		}

		$result = json_decode( $body, true );
		if ( isset( $result['error'] ) ) {
			return 'Error: ' . wp_json_encode( $result['error'] );
		}

		$content = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
		$json    = json_decode( $content, true );

		if ( json_last_error() === JSON_ERROR_NONE && is_array( $json ) ) {
			return implode( "\n", array_map( fn( $item ) => '- ' . $item, $json ) );
		}

		return $content ?: 'Error: Unexpected response format';
	}

	/**
	 * Generate keyword-based AI response.
	 *
	 * @param mixed $data Input data.
	 *
	 * @return mixed AI keyword response.
	 */
	public function askKeyword( $data ) {
		return $this->callGeminiForKeyword( $data );
	}

	/**
	 * Call the Gemini API with a given prompt and instruction.
	 *
	 * @param string $prompt       User input.
	 * @param string $instruction  Model instruction.
	 * @param float  $temperature  Randomness level.
	 * @param string $for          Request type.
	 *
	 * @return mixed API response.
	 */
	public function callGemini( $prompt, $instruction, $temperature = 0.7, $for = 'keyword' ) {
		$contents  = [
			[
				'role'  => 'user',
				'parts' => [ [ 'text' => $instruction ] ],
			],
			[
				'role'  => 'user',
				'parts' => [ [ 'text' => $prompt ] ],
			],
		];
		$body_data = [
			'contents'         => $contents,
			'generationConfig' => [ 'temperature' => $temperature ],
		];

		$response = wp_remote_post(
			$this->generateContent_url,
			[
				'headers' => [ 'Content-Type' => 'application/json' ],
				'body'    => wp_json_encode( $body_data ),
				'timeout' => 12000,
			]
		);

		if ( is_wp_error( $response ) ) {
			return 'Error: ' . $response->get_error_message();
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( ! empty( $data['error'] ) ) {
			return $data;
		}

		$content = $data['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
		$clean   = trim( $content, "```json\n \t\r\0\x0B" );

		try {
			$json = json_decode( $clean, true, 512, JSON_THROW_ON_ERROR );
		} catch ( JsonException $e ) {
			return 'Error: Invalid JSON response';
		}

		$key = ( 'keyword' === $for ) ? 'data' : 'response';
		wp_send_json_success( [ $key => json_decode( $json, true ) ] );
		exit;
	}

	/**
	 * Generate relevant keywords via Gemini.
	 *
	 * @param string $prompt Input prompt.
	 *
	 * @return mixed JSON array of keywords.
	 */
	public function callGeminiForKeyword( $prompt ) {
		$data   = json_decode( $prompt, true );
		$prompt = $data['prompt'] ?? '';

		return $this->callGemini(
			$prompt,
			'Generate a JSON array of exactly 10 relevant keywords based on the user input.'
		);
	}
	/**
	 * Generate a semantic embedding for the given text via Google Gemini API.
	 *
	 * @param string $text Text to embed.
	 * @return array|false Embedding vector or false on failure.
	 */
	public function generateEmbedding( string $text ) {
		// Prepare request body structure.
		$body     = [
			'content' => [
				'parts' => [
					[
						'text' => $text,
					],
				],
			],
		];
		$response = wp_remote_post(
			$this->embedContent_url,
			[
				'headers' => [
					'Content-Type'   => 'application/json',
					'x-goog-api-key' => $this->apiKey,
				],
				'body'    => wp_json_encode( $body ),
				'timeout' => 12000,
			]
		);
		if ( is_wp_error( $response ) ) {
			return false;
		}
		$body_raw = wp_remote_retrieve_body( $response );
		$data     = json_decode( $body_raw, true );
		// The embedding vector is returned under `embeddings` in results.
		if ( isset( $data['embedding']['values'] ) && is_array( $data['embedding']['values'] ) ) {
			return $data['embedding']['values'];
		}
		return false;
	}
}
