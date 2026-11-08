<?php

namespace RadiusTheme\SB\AI\AIServices\AIClients;

use RadiusTheme\SB\AI\AIFns;

/**
 * Class OpenAIClient
 *
 * Handles communication with OpenAI’s GPT API for AI-generated responses.
 */
class OpenAIClient {

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
	 * Constructor.
	 *
	 * Loads GPT settings and validates API credentials.
	 *
	 * @throws \Exception If API key or model is missing.
	 */
	public function __construct() {
		$this->apiKey = AIFns::get_options( 'gpt_api_key' );
		$this->model  = AIFns::get_options( 'gpt_models' );
		$this->token  = AIFns::get_options( 'gpt_max_token' );

		if ( empty( $this->apiKey ) || empty( $this->model ) ) {
			throw new \Exception( 'AI settings are not properly configured.' );
		}
	}

	/**
	 * Generate a response from GPT.
	 *
	 * @param string $prompt        User input.
	 * @param string $system_prompt Context or instruction.
	 *
	 * @return string AI-generated response or error message.
	 */
	public function ask( string $prompt, string $system_prompt ): string {
		$url      = 'https://api.openai.com/v1/chat/completions';
		$response = wp_remote_post(
			$url,
			[
				'headers' => [
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $this->apiKey,
				],
				'body'    => wp_json_encode(
					[
						'model'       => $this->model,
						'messages'    => [
							[
								'role'    => 'system',
								'content' => $system_prompt . 'give me plain text no markdown and no html',
							],
							[
								'role'    => 'user',
								'content' => $prompt,
							],
						],
						'temperature' => 0.7,
						'max_tokens'  => 500,
					]
				),
				'timeout' => 100000,
			]
		);

		if ( is_wp_error( $response ) ) {
			return 'Error: ' . $response->get_error_message();
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( isset( $data['choices'][0]['message']['content'] ) ) {
			$content  = $data['choices'][0]['message']['content'];
			$jsonData = json_decode( $content, true );

			if ( json_last_error() === JSON_ERROR_NONE && is_array( $jsonData ) ) {
				return implode(
					"\n",
					array_map( fn( $item ) => '- ' . $item, $jsonData )
				);
			}

			return $content;
		}

		return 'No response from AI.';
	}

	/**
	 * Generate keyword suggestions via GPT.
	 *
	 * @param mixed $data Input data.
	 *
	 * @return mixed AI keyword response.
	 */
	public function askKeyword( $data ) {
		return $this->callGPT4oForKeyword( $data );
	}

	/**
	 * Call GPT API with a prompt and instruction.
	 *
	 * @param string $prompt       User input.
	 * @param string $instruction  Model instruction.
	 * @param float  $temperature  Randomness level.
	 * @param string $model        Model name.
	 * @param string $for          Request type (keyword|form).
	 *
	 * @return mixed API response.
	 */
	public function callOpenAI( $prompt, $instruction, $temperature = 0.7, $model = null, $for = 'keyword' ) {
		$url      = 'https://api.openai.com/v1/chat/completions';
		$response = wp_remote_post(
			$url,
			[
				'headers' => [
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $this->apiKey,
				],
				'body'    => wp_json_encode(
					[
						'model'       => $this->model,
						'messages'    => [
							[
								'role'    => 'system',
								'content' => $instruction,
							],
							[
								'role'    => 'user',
								'content' => $prompt,
							],
						],
						'temperature' => $temperature,
					]
				),
				'timeout' => 100000,
			]
		);

		if ( is_wp_error( $response ) ) {
			return 'Error: ' . $response->get_error_message();
		}

		$body       = wp_remote_retrieve_body( $response );
		$data       = json_decode( $body, true );
		$aiResponse = $data['choices'][0]['message']['content'] ?? '[]';
		$clean      = trim( $aiResponse, "```json\n \t\r\0\x0B" );

		if ( ! empty( $data['error'] ) ) {
			return $data;
		}
		$key = ( 'keyword' === $for ) ? 'data' : 'response';
		wp_send_json_success( [ $key => json_decode( $clean, true ) ] );
		exit;
	}

	/**
	 * Generate relevant keywords via GPT.
	 *
	 * @param string $prompt Input prompt.
	 *
	 * @return mixed JSON array of keywords.
	 */
	public function callGPT4oForKeyword( $prompt ) {
		$data   = json_decode( $prompt, true );
		$prompt = $data['prompt'] ?? '';

		return $this->callOpenAI(
			$prompt,
			'Generate a JSON array of exactly 10 relevant keywords based on the user input.'
		);
	}

	/**
	 * Generate an embedding vector for the given text using OpenAI API.
	 *
	 * @param string $text Text to embed.
	 * @return array|false Embedding vector array or false on failure.
	 */
	public function generateEmbedding( string $text ) {
		$url      = 'https://api.openai.com/v1/embeddings';
		$response = wp_remote_post(
			$url,
			[
				'headers' => [
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $this->apiKey,
				],
				'body'    => wp_json_encode(
					[
						'model' => 'text-embedding-3-small',
						'input' => $text,
					]
				),
				'timeout' => 60,
			]
		);
		if ( is_wp_error( $response ) ) {
			return false;
		}
		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );
		return $data['data'][0]['embedding'] ?? false;
	}
}
