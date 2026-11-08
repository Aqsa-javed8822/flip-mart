<?php
/**
 * Elementor Content Fields Class.
 *
 * This class contains all the common fields for Content tab.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Helper\ControlHelper;
use RadiusTheme\SB\Models\GeneralList;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Elementor Content Fields Class.
 */
class ContentFields {
	/**
	 * Tab name.
	 *
	 * @access private
	 * @static
	 *
	 * @var array
	 */
	private static $tab = 'content';

	/**
	 * Social Share Layout section
	 *
	 * @param object $obj Reference object.
	 * @return array
	 */
	public static function share_presets( $obj ) {
		$fields['layout_section'] = $obj->start_section(
			esc_html__( 'Presets', 'shopbuilder' ),
			self::$tab
		);

		$fields['layout'] = [
			'type'      => 'rtsb-image-selector',
			'options'   => ControlHelper::share_layouts(),
			'default'   => 'share-layout1',
			'separator' => rtsb()->has_pro() ? 'default' : 'after',
		];

		$fields['layout_direction'] = [
			'type'      => 'choose',
			'label'     => esc_html__( 'Layout Direction', 'shopbuilder' ),
			'options'   => [
				'horizontal' => [
					'title' => esc_html__( 'Horizontal', 'shopbuilder' ),
					'icon'  => 'eicon-navigation-horizontal',
				],
				'vertical'   => [
					'title' => esc_html__( 'Vertical', 'shopbuilder' ),
					'icon'  => 'eicon-navigation-vertical',
				],
			],
			'default'   => 'horizontal',
			'toggle'    => true,
			'classes'   => $obj->pro_class(),
			'separator' => rtsb()->has_pro() ? 'before-short' : 'default',
		];

		$fields['layout_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elements/elementor/share_preset_control', $fields, $obj );
	}

	/**
	 * Social Share Platforms section
	 *
	 * @param object $obj Reference object.
	 *
	 * @return mixed|null
	 */
	public static function share_platforms( $obj ) {
		$sharing_list    = [];
		$defaults        = [];
		$share_platforms = ControlHelper::sharing_settings();

		foreach ( $share_platforms as $share_platform ) {
			switch ( $share_platform ) {
				case 'facebook':
					$share_text = esc_html__( 'Share', 'shopbuilder' );
					break;

				case 'twitter':
					$share_text = esc_html__( 'Tweet', 'shopbuilder' );
					break;

				case 'pinterest':
					$share_text = esc_html__( 'Pin It', 'shopbuilder' );
					break;

				default:
					$share_text = ucfirst( esc_html( $share_platform ) );
			}

			$defaults[]                      = [
				'share_items' => esc_html( $share_platform ),
				'share_text'  => $share_text,
			];
			$sharing_list[ $share_platform ] = ucfirst( esc_html( $share_platform ) );
		}

		$fields['platforms_section'] = $obj->start_section(
			esc_html__( 'Social Share Platforms', 'shopbuilder' ),
			self::$tab
		);

		$fields['show_share_settings_note'] = $obj->el_heading( esc_html__( 'Sharing Settings', 'shopbuilder' ), 'default' );

		$fields['show_share_pre_text'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Header Text?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show social sharing text before icons.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'separator'   => 'default',
		];

		$fields['share_pre_text'] = [
			'type'        => 'text',
			'label'       => esc_html__( 'Header Text', 'shopbuilder' ),
			'description' => esc_html__( 'Enter the text to show before icons.', 'shopbuilder' ),
			'default'     => esc_html__( 'Share:', 'shopbuilder' ),
			'label_block' => true,
			'condition'   => [ 'show_share_pre_text' => [ 'yes' ] ],
		];

		$fields['show_share_icon'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Sharing Icon?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show social sharing icon.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
		];

		$fields['show_share_text'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Show Sharing Text?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to show social sharing icon.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'default'     => 'yes',
			'separator'   => rtsb()->has_pro() ? 'before-short' : 'after elementor-control-separator-before-short',
		];

		$fields['share_toggle'] = [
			'type'        => 'switch',
			'label'       => esc_html__( 'Enable Share Toggle Icon?', 'shopbuilder' ),
			'description' => esc_html__( 'Switch on to enable share toggle icon.', 'shopbuilder' ),
			'label_on'    => esc_html__( 'On', 'shopbuilder' ),
			'label_off'   => esc_html__( 'Off', 'shopbuilder' ),
			'classes'     => $obj->pro_class(),
			'separator'   => rtsb()->has_pro() ? 'before-short' : 'default',
		];

		$fields['share_toggle_icon'] = [
			'type'        => 'icons',
			'label'       => esc_html__( 'Choose Toggle Icon', 'shopbuilder' ),
			'description' => esc_html__( 'Please choose the share toggle icon.', 'shopbuilder' ),
			'default'     => [
				'value'   => 'rtsb-icon rtsb-icon-share',
				'library' => 'rtsb-fonts',
			],
			'condition'   => [ 'share_toggle' => [ 'yes' ] ],
		];

		$fields['share_platforms_note'] = $obj->el_heading( esc_html__( 'Sharing Platforms', 'shopbuilder' ), 'before' );

		$fields['share_platforms'] = [
			'type'        => 'repeater',
			'mode'        => 'repeater',
			'label'       => esc_html__( 'Add Sharing Platforms', 'shopbuilder' ),
			'separator'   => 'default',
			'title_field' => '{{{ share_items }}}',
			'fields'      => [
				'share_items' => [
					'label'     => esc_html__( 'Platform Name', 'shopbuilder' ),
					'type'      => 'select',
					'separator' => 'default',
					'options'   => $sharing_list,
				],
				'share_text'  => [
					'label' => esc_html__( 'Sharing Text', 'shopbuilder' ),
					'type'  => 'text',
				],
			],
			'default'     => $defaults,
		];

		$fields['platforms_section_end'] = $obj->end_section();

		return apply_filters( 'rtsb/elements/elementor/platforms_control', $fields, $obj );
	}
}
