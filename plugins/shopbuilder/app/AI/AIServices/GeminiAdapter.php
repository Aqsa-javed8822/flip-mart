<?php

namespace RadiusTheme\SB\AI\AIServices;

use RadiusTheme\SB\AI\AIFns;
use RadiusTheme\SB\AI\AIServices\Interface\AIServiceInterface;

/**
 * Class GeminiAdapter
 *
 * Providing a standardized interface for generating AI-based responses,
 * keyword suggestions, and form field data.
 */
class GeminiAdapter implements AIServiceInterface {

	/**
	 * The Gemini client instance.
	 *
	 * @var object
	 */
	private $geminiClient;

	/**
	 * Constructor.
	 *
	 * Initializes the adapter with a Gemini client instance.
	 *
	 * @param object $geminiClient An instance of the Gemini client.
	 */
	public function __construct( $geminiClient ) {
		$this->geminiClient = $geminiClient;
	}

	/**
	 * Generate a general AI response using the Gemini client.
	 *
	 * @param string $content_type The type of content to generate (title, description, short_description).
	 * @param string $instruction The product information or user instruction.
	 *
	 * @return string The AI-generated response text.
	 */
	public function generateResponse( string $content_type, string $instruction ): string {
		$system_prompt = AIFns::build_prompt( $content_type, $instruction );
		return $this->geminiClient->ask( $instruction, $system_prompt );
	}

	/**
	 * Generate keyword-based AI suggestions or responses using the Gemini client.
	 *
	 * @param mixed $data Input data for keyword generation.
	 *
	 * @return mixed The AI-generated keyword response.
	 */
	public function generateKeywordResponse( $data ) {
		return $this->geminiClient->askKeyword( $data );
	}
	/**
	 * Generate text embeddings using the Google Gemini API.
	 *
	 * @param mixed $data Input text or data to generate semantic embeddings for.
	 *
	 * @return mixed The embedding vector or API response from Gemini.
	 */
	public function generateEmbedding( $data ) {
		return $this->geminiClient->generateEmbedding( $data );
	}
}
