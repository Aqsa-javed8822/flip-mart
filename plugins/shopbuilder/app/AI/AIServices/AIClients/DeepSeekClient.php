<?php

namespace RadiusTheme\SB\AI\AIServices\AIClients;

use RadiusTheme\SB\AI\AIFns;

/**
 * Class DeepSeekClient
 *
 * Handles communication with the DeepSeek API for AI-generated responses.
 */
class DeepSeekClient {

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
	 * Constructor.
	 *
	 * Loads DeepSeek settings and validates API credentials.
	 *
	 * @throws \Exception If API key or model is missing.
	 */
	public function __construct() {
		$apiKey      = AIFns::get_options( 'deepseek_api_key' );
		$this->model = AIFns::get_options( 'deepseek_models' );
		$this->token = AIFns::get_options( 'deepseek_max_token' );
		if ( empty( $apiKey ) || empty( $this->model ) ) {
			throw new \Exception( 'AI settings are not properly configured.' );
		}
	}

	/**
	 * Get a general response from DeepSeek.
	 *
	 * @param string $prompt         The input prompt.
	 * @param string $system_prompt  System context for the model.
	 *
	 * @return string AI-generated response or error message.
	 */
	public function ask( string $prompt, string $system_prompt ) {
		$apiKey = AIFns::get_options( 'deepseek_api_key' );
		$url    = 'https://api.deepseek.com/chat/completions';

		if ( empty( $apiKey ) ) {
			return 'Error: DeepSeek API key is missing.';
		}

		$response = wp_remote_post(
			$url,
			[
				'headers' => [
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $apiKey,
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

		if ( isset( $data['error'] ) ) {
			return 'Error: ' . $data['error']['message'];
		}

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
	 * Generate keyword-based AI response.
	 *
	 * @param mixed $data Input data.
	 *
	 * @return mixed AI keyword response.
	 */
	public function askKeyword( $data ) {
		return $this->callDeepSeekForKeyword( $data );
	}

	/**
	 * Call the DeepSeek API with a given prompt and instruction.
	 *
	 * @param string $prompt       User input.
	 * @param string $instruction  Model instruction.
	 * @param float  $temperature  Randomness level.
	 * @param string $model        Model name.
	 * @param string $for          Request type.
	 *
	 * @return mixed API response.
	 */
	public function callDeepSeek( $prompt, $instruction, $temperature = 0.7, $model = null, $for = 'keyword' ) {
		$apiKey = AIFns::get_options( 'deepseek_api_key' );
		$url    = 'https://api.deepseek.com/v1/chat/completions';

		$response = wp_remote_post(
			$url,
			[
				'headers' => [
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $apiKey,
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

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( ! empty( $data['error'] ) ) {
			return $data;
		}

		$aiResponse    = $data['choices'][0]['message']['content'] ?? '[]';
		$cleanResponse = trim( $aiResponse, "```json\n \t\r\0\x0B" );

		$key = ( 'keyword' === $for ) ? 'data' : 'response';
		wp_send_json_success( [ $key => json_decode( $cleanResponse, true ) ] );
		exit;
	}
	/**
	 * Generate relevant keywords via DeepSeek.
	 *
	 * @param string $prompt The input context.
	 *
	 * @return mixed JSON array of keywords.
	 */
	public function callDeepSeekForKeyword( $prompt ) {
		$data   = json_decode( $prompt, true );
		$prompt = $data['prompt'] ?? '';

		return $this->callDeepSeek(
			$prompt,
			'You are an AI that generates relevant keywords based on user prompts. Return only a JSON array of 10 keywords.'
		);
	}
}
