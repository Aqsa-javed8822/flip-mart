<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ProductShareSettings {
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		$fields = [
			'sec_general'        => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'General', 'shopbuilder' ),
			],
			'product_share_note' => [
				'type'            => 'html',
				'raw'             => sprintf(
					/* translators: %s: Hook name */
					esc_html__( 'Woocommerce Supported Sharing plugins hook into here or you can add your own code directly. Hook: %s', 'shopbuilder' ),
					'<a target="_blank" style="color: rgb(6, 74, 203);" href="https://woocommerce.github.io/code-reference/files/woocommerce-templates-single-product-share.html"> woocommerce_share </a>'
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'separator'       => 'default',
			],
			'general_style_end'  => [
				'mode' => 'section_end',
			],
		];
		return $fields;
	}
}
