<?php

namespace RadiusTheme\SB\AI;

// Do not allow directly accessing this file.
use Exception;
use RadiusTheme\SB\AI\AIServices\DeepSeekAdapter;
use RadiusTheme\SB\AI\AIServices\GeminiAdapter;
use RadiusTheme\SB\AI\AIServices\OpenAIAdapter;
use RadiusTheme\SB\Traits\SingletonTrait;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * AIPower Model
 *
 * Manages AI service initialization and adapter creation for various AI providers
 * including OpenAI, Gemini, and DeepSeek.
 *
 * @since 1.0.0
 */
class MetaBoxScripts {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Enqueue scripts and styles for product edit page.
	 *
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook ) {
		// Only load on product edit page.
		if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
			return;
		}
		$screen = get_current_screen();
		if ( ! $screen || 'product' !== $screen->post_type ) {
			return;
		}
		$ai_data = AIFns::activated_ai_data();
		$version = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? time() : RTSB_VERSION;
        // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		if ( in_array( 'sitepress-multilingual-cms/sitepress.php', get_option( 'active_plugins' ) ) ) {
			$ajaxurl = admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
		} else {
			$ajaxurl = admin_url( 'admin-ajax.php' );
		}
		wp_enqueue_script( 'rtsb-ai-product-helper', rtsb()->get_assets_uri( 'js/backend/product-ai-init.js' ), [ 'wp-element' ], $version, true );
		wp_enqueue_style( 'rtsb-ai-product-ai-init', rtsb()->get_assets_uri( 'css/backend/product-ai-init.css' ), '', $version );

		// Localize script with data.
		wp_localize_script(
			'rtsb-ai-product-helper',
			'rtsbAIHelper',
			[
				'ajaxUrl'     => $ajaxurl,
				'settingsUrl' => admin_url( 'admin.php?page=rtsb-settings' ),
				'aiActivated' => ! empty( $ai_data ),
				'nonce'       => wp_create_nonce( 'rtsb_ai_nonce' ),
				'i18n'        => [
					'chars'                       => esc_html__( 'chars', 'shopbuilder' ),
					'aiConfigError'               => esc_html__( 'AI Configuration mismatch', 'shopbuilder' ),
					'writePrompt'                 => esc_html__( 'Write a prompt for AI', 'shopbuilder' ),
					'regenerateBtnTxt'            => esc_html__( 'Regenerate', 'shopbuilder' ),
					'promptPlaceholder'           => esc_html__( 'e.g., Premium handmade cotton t-shirt for men, made from 100% organic cotton with unique printed designs, perfect for casual wear', 'shopbuilder' ),
					// Title Generator Modal.
					'generateTitle'               => esc_html__( 'Generate Product Title with AI', 'shopbuilder' ),
					'applyTitleBtnTxt'            => esc_html__( 'Apply Title', 'shopbuilder' ),
					'cancelBtnTxt'                => esc_html__( 'Cancel', 'shopbuilder' ),
					'generateTitleBtnTxt'         => esc_html__( 'Generate Titles', 'shopbuilder' ),
					'promptTitleHelperText'       => esc_html__( 'AI will use this info to generate optimized title ideas.', 'shopbuilder' ),
					'generatedTitles'             => esc_html__( 'Generated Titles', 'shopbuilder' ),
					// Description Generator Modal.
					'aiDescriptionGenerator'      => esc_html__( 'Generate Full Description with AI', 'shopbuilder' ),
					'descPromptPlaceholder'       => esc_html__( 'e.g., Premium handmade cotton t-shirt for men, made from 100% organic cotton with unique printed designs, perfect for casual wear. Available in multiple sizes and colors.', 'shopbuilder' ),
					'promptDescHelperText'        => esc_html__( 'AI will use this info to generate a comprehensive product description.', 'shopbuilder' ),
					'generatedDescription'        => esc_html__( 'Generated Description', 'shopbuilder' ),
					'applyDescriptionBtnTxt'      => esc_html__( 'Apply Description', 'shopbuilder' ),
					'generateDescriptionBtnTxt'   => esc_html__( 'Generate Description', 'shopbuilder' ),
					// Short Description Generator Modal.
					'generateShortDesc'           => esc_html__( 'Generate Short Description with AI', 'shopbuilder' ),
					'aiShortDescGenerator'        => esc_html__( 'AI Short Description Generator', 'shopbuilder' ),
					'shortDescPromptPlaceholder'  => esc_html__( 'e.g., Premium handmade cotton t-shirt for men, made from 100% organic cotton with unique printed designs, perfect for casual wear.', 'shopbuilder' ),
					'promptShortDescHelperText'   => esc_html__( 'Short descriptions are usually 1–2 sentences long and appear in product listings or summaries.', 'shopbuilder' ),
					'generatedShortDescription'   => esc_html__( 'Generated Short Description', 'shopbuilder' ),
					'applyShortDescriptionBtnTxt' => esc_html__( 'Apply Short Description', 'shopbuilder' ),
					'generateShortDescBtnTxt'     => esc_html__( 'Generate Short Description', 'shopbuilder' ),
					// Notifications.
					'provideInfoFirst'            => esc_html__( 'Please provide product information first.', 'shopbuilder' ),
					'descGeneratedSuccess'        => esc_html__( 'Description generated successfully!', 'shopbuilder' ),
					'shortDescGeneratedSuccess'   => esc_html__( 'Short description generated successfully!', 'shopbuilder' ),
					'descGenerateFailed'          => esc_html__( 'Failed to generate description.', 'shopbuilder' ),
					'shortDescGenerateFailed'     => esc_html__( 'Failed to generate short description.', 'shopbuilder' ),
					'somethingWentWrong'          => esc_html__( 'Something went wrong.', 'shopbuilder' ),
					'descAppliedSuccess'          => esc_html__( 'Description applied successfully!', 'shopbuilder' ),
					'shortDescAppliedSuccess'     => esc_html__( 'Short description applied successfully!', 'shopbuilder' ),
					'descFieldNotFound'           => esc_html__( 'Product description field not found!', 'shopbuilder' ),
					'shortDescFieldNotFound'      => esc_html__( 'Product short description field not found!', 'shopbuilder' ),
					'selectDescriptionFirst'      => esc_html__( 'Please select a description first.', 'shopbuilder' ),
					'selectShortDescFirst'        => esc_html__( 'Please select a short description first.', 'shopbuilder' ),
				],
			]
		);
	}

	/**
	 * Render meta box content
	 *
	 * @return void
	 */
	public function render_meta_box() {
		?>
		<div id="rtsb-ai-product-helper-root"></div>
		<?php
	}
}
