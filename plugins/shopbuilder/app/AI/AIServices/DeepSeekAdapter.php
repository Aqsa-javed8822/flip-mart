<?php

namespace RadiusTheme\SB\AI\AIServices;

use RadiusTheme\SB\AI\AIFns;
use RadiusTheme\SB\AI\AIServices\Interface\AIServiceInterface;

/**
 * Class DeepSeekAdapter
 *
 * Providing standardized methods for generating AI responses,
 * keyword suggestions, and form field data.
 */
class DeepSeekAdapter implements AIServiceInterface {

	/**
	 * The DeepSeek client instance.
	 *
	 * @var object
	 */
	private $deepSeekClient;

	/**
	 * Constructor.
	 *
	 * Initializes the adapter with a DeepSeek client instance.
	 *
	 * @param object $deepSeekClient An instance of the DeepSeek client.
	 */
	public function __construct( $deepSeekClient ) {
		$this->deepSeekClient = $deepSeekClient;
	}
	/**
	 * Generate a general AI response using the DeepSeek client.
	 *
	 * @param string $content_type The type of content to generate (title, description, short_description).
	 * @param string $instruction The product information or user instruction.
	 *
	 * @return string The AI-generated response text.
	 */
	public function generateResponse( string $content_type, string $instruction ): string {
		$system_prompt = AIFns::build_prompt( $content_type, $instruction );
		return $this->deepSeekClient->ask( $instruction, $system_prompt );
	}

	/**
	 * Generate keyword-based AI suggestions or responses using the DeepSeek client.
	 *
	 * @param mixed $data Input data for keyword generation.
	 *
	 * @return mixed The AI-generated keyword response.
	 */
	public function generateKeywordResponse( $data ) {
		return $this->deepSeekClient->askKeyword( $data );
	}

	/**
	 * Generate semantic text embeddings using the DeepSeek API.
	 *
	 * @param mixed $data The input text or data used to create embeddings.
	 *
	 * @return mixed The embedding vector array or DeepSeek API response.
	 */
	public function generateEmbedding( $data ) {
		return [];
	}
}
