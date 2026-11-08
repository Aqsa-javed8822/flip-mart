<?php

namespace RadiusTheme\SB\AI\AIServices;

use RadiusTheme\SB\AI\AIFns;
use RadiusTheme\SB\AI\AIServices\Interface\AIServiceInterface;

/**
 * Class OpenAIAdapter
 *
 * Providing standardized methods for generating AI-based responses.
 */
class OpenAIAdapter implements AIServiceInterface {

	/**
	 * The OpenAI client instance.
	 *
	 * @var object
	 */
	private $openAIClient;

	/**
	 * Constructor.
	 *
	 * Initializes the adapter with the provided OpenAI client instance.
	 *
	 * @param object $openAIClient An instance of the OpenAI client.
	 */
	public function __construct( $openAIClient ) {
		$this->openAIClient = $openAIClient;
	}

	/**
	 * Generate a general AI response using the OpenAI client.
	 *
	 * @param string $content_type The type of content to generate (title, description, short_description).
	 * @param string $instruction The product information or user instruction.
	 *
	 * @return string The generated AI response.
	 */
	public function generateResponse( string $content_type, string $instruction ): string {
		$system_prompt = AIFns::build_prompt( $content_type, $instruction );
		return $this->openAIClient->ask( $instruction, $system_prompt );
	}

	/**
	 * Generate keyword suggestions or responses using the OpenAI client.
	 *
	 * @param mixed $data Input data used to generate keyword-based output.
	 *
	 * @return mixed The AI-generated keyword response.
	 */
	public function generateKeywordResponse( $data ) {
		return $this->openAIClient->askKeyword( $data );
	}

	/**
	 * Generate text embeddings using the OpenAI client.
	 *
	 * @param mixed $data Input text or data for which to generate embeddings.
	 *
	 * @return mixed The embedding vector or API response from OpenAI.
	 */
	public function generateEmbedding( $data ) {
		return $this->openAIClient->generateEmbedding( $data );
	}
}
