<?php
/**
 *  WishlistSettings class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use Elementor\Controls_Manager;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class WishlistSettings {
	/**
	 * Widget Field
	 *
	 * @param object $widget object.
	 * @return array
	 */
	public static function settings_field( $widget ) {
		$fields = TableSettings::table_settings( $widget );
		// Single "Column Width" control in the Table section. An HTML table column shares its width
		// between header ( thead th ) and body ( tbody td ) cells, so one min-width control governs
		// the whole column; separate header/item sliders only fought over the same value.
		$insert_array = [
			'column_width' => [
				'label'     => esc_html__( 'Column Width', 'shopbuilder' ),
				'type'      => 'slider',
				'separator' => 'default',
				'range'     => [
					'px' => [
						'min' => 200,
						'max' => 500,
					],
				],
				'selectors' => [
					$widget->selectors['column_width'] => 'min-width: {{SIZE}}{{UNIT}};',
				],
			],
		];
		$fields       = Fns::insert_controls( 'table_heading_cell_padding', $fields, $insert_array, true );

		// Re-emit the Table Items text-decoration with !important so it beats the framework
		// link reset ( .rtsb-elementor-container a { text-decoration:none !important } ) and the
		// per-module shields, making it apply to the Add to Cart, Buy Now and Partial Payment buttons.
		if ( isset( $fields['table_item_typo'] ) ) {
			$fields['table_item_typo']['fields_options'] = [
				'text_decoration' => [
					'selectors' => [
						$widget->selectors['table_item_text_decoration'] => 'text-decoration: {{VALUE}} !important;',
					],
				],
			];
		}

		return $fields;
	}
}
