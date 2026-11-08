<?php

namespace RadiusTheme\SB\AI\AIServices\Interface;

/**
 * Interface AIServiceInterface
 *
 * Defines the contract for AI service adapters. Any AI service implementation
 * (e.g., OpenAI, Gemini, DeepSeek) must implement this interface to ensure
 * a consistent method for generating AI responses.
 */
interface AIServiceInterface {
	/**
	 * Generates an AI-based response using the provided input and system prompt.
	 *
	 * @param string $content_type         The user input text for the AI to process.
	 * @param string $instruction The system prompt providing context or instruction for the AI.
	 *
	 * @return string The AI-generated response as a string.
	 */
	public function generateResponse( string $content_type, string $instruction ): string;
}
