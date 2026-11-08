<?php
/**
 * RadiusTheme Variation Swatches support.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\PluginsSupport;

use RadiusTheme\SB\Traits\SingletonTrait;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class RtwpvsSupport {
	/**
	 * SingletonTrait.
	 */
	use SingletonTrait;

	/**
	 * Construct function
	 */
	private function __construct() {
		add_filter( 'rtsb/elements/elementor/visibility_control', [ $this, 'general_content_visibility' ] );
	}

	/**
	 * Content Visibility
	 *
	 * @param array $fields Fields.
	 *
	 * @return array
	 */
	public function general_content_visibility( $fields ) {
		$extra_controls['show_swatches'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Variation Swatches?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show variation swatches.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'condition'   => [ 'layout!' => [ 'grid-layout2', 'slider-layout2' ] ],
		];

		$extra_controls['show_vs_clear_btn'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Swatches Reset?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show swatches clear button.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'separator'   => 'default',
			'condition'   => [
				'show_swatches' => [ 'yes' ],
				'layout!'       => [ 'grid-layout2', 'slider-layout2' ],
			],
		];

		return Fns::insert_controls( 'single_category', $fields, $extra_controls, true );
	}
}
