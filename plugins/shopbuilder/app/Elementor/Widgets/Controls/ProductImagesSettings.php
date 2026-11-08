<?php
/**
 * Main ProductImagesSettings class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Controls;

use Elementor\Controls_Manager;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}


/**
 * Product Image Settings class
 */
class ProductImagesSettings {

	/**
	 * Widget Control Selectors
	 *
	 * @var array
	 */
	private static $selectors = [];
	/**
	 * Widget Field
	 *
	 * @param object $widget Widget.
	 * @return array
	 */
	public static function widget_fields( $widget ) {
		self::$selectors = $widget->selectors;
		return self::general_section() +
			self::image() +
			self::flash_sale( $widget ) +
			self::lightbox_icon();
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function general_section() {
		$fields = [
			'image_general_section_start' => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'General', 'shopbuilder' ),
			],

			'sale_flash_badge'            => [
				'label'       => esc_html__( 'Show Flash Sale?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show Flash Sale.', 'shopbuilder' ),
				'default'     => 'yes',
			],

			'show_module_badges'          => [
				'label'       => esc_html__( 'Show Badges?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show the Badges form from the Module..', 'shopbuilder' ),
				'default'     => 'yes',
			],
			'show_thumbnails'             => [
				'label'       => esc_html__( 'Show Thumbnails?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show Thumbnails.', 'shopbuilder' ),
				'default'     => 'yes',
			],
			'show_zoom'                   => [
				'label'       => esc_html__( 'Show Lightbox Icon?', 'shopbuilder' ),
				'type'        => 'switch',
				'description' => esc_html__( 'Switch on to show Zoom Icon.', 'shopbuilder' ),
				'default'     => 'yes',
			],
			'lightbox_icon'               => [
				'label'            => esc_html__( 'Icon', 'shopbuilder' ),
				'type'             => 'icons',
				'fa4compatibility' => 'icon',
				'default'          => [
					'value'   => 'fas fa-expand-alt',
					'library' => 'fa-solid',
				],
				'condition'        => [
					'show_zoom' => 'yes',
				],
			],
			'image_general_section_end'   => [
				'mode' => 'section_end',
			],
		];

		// The Image Layout (bottom/left/right/grid) is rendered by the Variation Gallery
		// pipeline. Without it, WooCommerce's default gallery ignores the selection, so
		// only expose the control when a Variation Gallery renderer is active — the
		// standalone WPVG Pro plugin or ShopBuilder's Variation Gallery module.
		if ( ( function_exists( 'rtwpvg' ) && rtwpvg()->active_pro() ) || Fns::is_module_active( 'variation_gallery' ) ) {
			$image_layout                           = [
				'image_layout' => [
					'type'        => 'rtsb-image-selector',
					'label'       => esc_html__( 'Image Layout', 'shopbuilder' ),
					'description' => esc_html__( 'Please choose image layout. The layout display the change after reload.', 'shopbuilder' ),
					'options'     => [
						'bottom' => [
							'title' => esc_html__( 'Gallery Bottom', 'shopbuilder' ),
							'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/image-layout-gallery-bottom-2.png' ) ),
						],
						'left'   => [
							'title' => esc_html__( 'Gallery left', 'shopbuilder' ),
							'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/image-layout-gallery-left-2.png' ) ),
						],
						'right'  => [
							'title' => esc_html__( 'Gallery Right', 'shopbuilder' ),
							'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/image-layout-gallery-right-2.png' ) ),
						],
						'grid'   => [
							'title' => esc_html__( 'Gallery Grid', 'shopbuilder' ),
							'url'   => esc_url( rtsb()->get_assets_uri( 'images/layout/image-layout-gallery-grid-2.png' ) ),
						],
					],
					'default'     => 'bottom',
				],
			];
			$fields['show_thumbnails']['condition'] = [
				'image_layout!' => 'grid',
			];
			$fields                                 = Fns::insert_controls( 'image_general_section_start', $fields, $image_layout, true );

		}

		return $fields;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function image() {
		$fields = [
			'image_section_start'         => [
				'mode'  => 'section_start',
				'label' => esc_html__( 'Product Image', 'shopbuilder' ),
			],
			'image_width'                 => [
				'label'      => esc_html__( 'Image Width', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ '%', 'px' ],
				'range'      => [
					'%'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'px' => [
						'min'  => 0,
						'max'  => 2000,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 100,
				],
				'separator'  => 'default',
				'selectors'  => [
					self::$selectors['image_width'] => 'width: {{SIZE}}{{UNIT}} !important;',
					':root'                         => '--vg-image-width: {{SIZE}}{{UNIT}} !important;',
				],
			],
			'image_border_radius'         => [
				'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					self::$selectors['image_border_radius'] => 'border-radius: {{SIZE}}{{UNIT}} !important;',
					':root' => '--vg-image-border-radius: {{SIZE}}{{UNIT}} !important;',
				],
			],
			'gallery_thumbs_style'        => [
				'type'            => 'html',
				'raw'             => sprintf(
					'<h3 class="rtsb-elementor-group-heading">%s</h3>',
					esc_html__( 'Gallery Thumbnails', 'shopbuilder' )
				),
				'content_classes' => 'elementor-panel-heading-title',
			],

			'gallery_thumbs_column_gap'   => [
				'mode'            => 'responsive',
				'label'           => esc_html__( 'Thumbnail Gap', 'shopbuilder' ),
				'description'     => esc_html__( 'Gallery Thumbnails Gap Is required.', 'shopbuilder' ),
				'render_type'     => 'template',
				'type'            => 'slider',
				'range'           => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'         => [
					'unit' => 'px',
					'size' => 10,
				],
				'desktop_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => 10,
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors'       => [
					':root' => '--rtwpvg-thumbnail-gap:{{SIZE}}{{UNIT}};--vg-thumb-gap:{{SIZE}}{{UNIT}};',
				],
			],
			'gallery_thumbs_column'       => [
				'mode'            => 'responsive',
				'label'           => esc_html__( 'Column', 'shopbuilder' ),
				'description'     => esc_html__( 'Above Control the Gallery Thumbnails Gap is required.', 'shopbuilder' ),
				'type'            => 'slider',
				'render_type'     => 'template',
				'range'           => [
					'px' => [
						'min'  => 2,
						'max'  => 8,
						'step' => 1,
					],
				],
				'default'         => [
					'unit' => 'px',
					'size' => 4,
				],
				'desktop_default' => [
					'size' => 4,
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => 4,
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => 3,
					'unit' => 'px',
				],
				'selectors'       => [
					':root' => '--vg-thumbnail-column:{{SIZE}};',
					self::$selectors['gallery_thumbs_column'] => 'width: calc(100%/{{SIZE}} - ( {{gallery_thumbs_column_gap.size}}{{gallery_thumbs_column_gap.unit}} / {{SIZE}} ) *  ({{SIZE}} - 1 ) );flex: 0 0 auto;max-width: initial;',
				],
			],
			'gallery_image_gap_with_main' => [
				'mode'      => 'responsive',
				'label'     => esc_html__( 'Gap with main image', 'shopbuilder' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					':root' => '--rtwpvg-thumbnail-gap-with-main:{{SIZE}}{{UNIT}};--vg-main-slider-thumb-gap:{{SIZE}}{{UNIT}};',
				],
			],
			'thumb_border'                => [
				'mode'       => 'group',
				'type'       => 'border',
				'selector'   => self::$selectors['thumb_border'],
				'size_units' => [ 'px' ],
			],
			'thumbs_border_radius'        => [
				'label'      => esc_html__( 'Border Radius', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					':root' => '--vg-thumb-border-radius:{{SIZE}}{{UNIT}};',
					self::$selectors['thumbs_border_radius'] => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			],
			'gallery_thumbs_padding'      => [
				'label'      => esc_html__( 'Item Inner Padding', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					self::$selectors['gallery_thumbs_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'image_section_end'           => [
				'mode' => 'section_end',
			],
		];
		if ( function_exists( 'rtwpvg' ) ) {
			unset( $fields['gallery_thumbs_column']['selectors'] );
		}
		return $fields;
	}
	/**
	 * Widget Field
	 *
	 * @param object $widget widget.
	 * @return array
	 */
	public static function flash_sale( $widget ) {
		$fields = ProductFlashSale::flash_sale( $widget );

		$fields['flash_sale_section_start']['condition'] = [
			'sale_flash_badge' => 'yes',
		];

		$extra_controls = [];

		$extra_controls['flash_sale_position'] = [
			'label'     => esc_html__( 'Position', 'shopbuilder' ),
			'type'      => 'choose',
			'options'   => [
				'left'  => [
					'title' => esc_html__( 'Left', 'shopbuilder' ),
					'icon'  => 'eicon-h-align-left',
				],
				'right' => [
					'title' => esc_html__( 'Right', 'shopbuilder' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'default'   => 'left',
			'separator' => 'before',
		];

		$extra_controls['flash_sale_position_horizontal'] = [
			'label'      => esc_html__( 'Horizontal', 'shopbuilder' ),
			'type'       => 'slider',
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'px' => [
					'min'  => -100,
					'max'  => 100,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 0,
			],
			'selectors'  => [
				$widget->selectors['flash_sale_position_horizontal'] => '{{flash_sale_position.VALUE}}: {{SIZE}}{{UNIT}};position: absolute;z-index: 2;',
			],
		];
		$extra_controls['flash_sale_position_vertical']   = [
			'label'      => esc_html__( 'Vertical', 'shopbuilder' ),
			'type'       => 'slider',
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'px' => [
					'min'  => -100,
					'max'  => 100,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 0,
			],
			'selectors'  => [
				$widget->selectors['flash_sale_position_vertical'] => 'top:  {{SIZE}}{{UNIT}};position: absolute;z-index: 2;',
			],
		];

		$fields = Fns::insert_controls( 'flash_sale_badge_border_radius', $fields, $extra_controls );

		return $fields;
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public static function lightbox_icon() {
		$fields = [
			'zoom_section_start'       => [
				'mode'      => 'section_start',
				'label'     => esc_html__( 'Image Lightbox', 'shopbuilder' ),
				'tab'       => 'style',
				'condition' => [
					'show_zoom' => 'yes',
				],
			],
			'zoom_color'               => [
				'label'     => esc_html__( 'Color', 'shopbuilder' ),
				'type'      => 'color',

				'separator' => 'default',
				'selectors' => [
					':root'                        => '--vg-lightbox-icon-color: {{VALUE}};',
					self::$selectors['zoom_color'] => 'color: {{VALUE}};',
				],
			],
			'zoom_bg_color'            => [
				'label'     => esc_html__( 'Background Color', 'shopbuilder' ),
				'type'      => 'color',
				'selectors' => [
					':root'                           => '--vg-lightbox-icon-bg-color: {{VALUE}};',
					self::$selectors['zoom_bg_color'] => 'background-color: {{VALUE}};',
				],
			],
			'zoom_icon_size'           => [
				'label'      => esc_html__( 'Icon Size (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors'  => [
					':root' => '--vg-lightbox-icon-size: {{SIZE}}{{UNIT}}',
					self::$selectors['zoom_icon_size']['icon'] => 'font-size: {{SIZE}}{{UNIT}};',
					self::$selectors['zoom_icon_size']['svg'] => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
			],
			'zoom_badge_width'         => [
				'label'      => esc_html__( 'Icon Area Width (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 15,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					':root'                              => '--vg-lightbox-area-width: {{SIZE}}{{UNIT}}',
					self::$selectors['zoom_badge_width'] => 'width: {{SIZE}}{{UNIT}};min-width: initial;',
				],
			],
			'zoom_badge_height'        => [
				'label'      => esc_html__( 'Icon Area Height (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 15,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					':root'                               => '--vg-lightbox-area-height: {{SIZE}}{{UNIT}}',
					self::$selectors['zoom_badge_height'] => 'height: {{SIZE}}{{UNIT}};min-height: initial;',
				],
			],
			'zoom_badge_border_radius' => [
				'label'      => esc_html__( 'Icon Area Border Radius (px)', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					':root' => '--vg-lightbox-area-radius: {{SIZE}}{{UNIT}}',
					self::$selectors['zoom_badge_border_radius'] => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			],
			'zoom_padding'             => [
				'label'      => esc_html__( 'Padding (px)', 'shopbuilder' ),
				'type'       => 'dimensions',
				'mode'       => 'responsive',
				'size_units' => [ 'px' ],
				'default'    => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					':root'                          => '--vg-lightbox-area-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					self::$selectors['zoom_padding'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			],
			'zoom_position'            => [
				'label'     => esc_html__( 'Position', 'shopbuilder' ),
				'type'      => 'choose',
				'options'   => [
					'left'  => [
						'title' => esc_html__( 'Left', 'shopbuilder' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'shopbuilder' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'   => 'right',
				'separator' => 'before',
			],
			'zoom_position_horizontal' => [
				'label'      => esc_html__( 'Horizontal', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => -200,
						'max'  => 200,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],

				'selectors'  => [
					self::$selectors['zoom_position_horizontal']['zoom_position'] => '{{zoom_position.VALUE}}: {{SIZE}}{{UNIT}};',
					self::$selectors['zoom_position_horizontal']['rtwpvg-tr-br'] => 'right: {{SIZE}}{{UNIT}};',
					self::$selectors['zoom_position_horizontal']['rtwpvg-tl-bl'] => 'left: {{SIZE}}{{UNIT}};',
				],
			],
			'zoom_position_vertical'   => [
				'label'      => esc_html__( 'Vertical', 'shopbuilder' ),
				'type'       => 'slider',
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => -200,
						'max'  => 200,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					self::$selectors['zoom_position_vertical']['zoom-icon-top'] => 'top:  {{SIZE}}{{UNIT}};',
					self::$selectors['zoom_position_vertical']['zoom-icon-bottom'] . ':is(.rtwpvg-trigger-position-bottom-right, .rtwpvg-trigger-position-bottom-left)' => 'bottom:  {{SIZE}}{{UNIT}};',
				],
			],
		];
		if ( Fns::is_module_active( 'variation_gallery' ) ) {
			unset(
				$fields['zoom_position'],
				$fields['zoom_position_horizontal'],
				$fields['zoom_position_vertical']
			);
			$fields['vg_lightbox_position'] = [
				'label'     => esc_html__( 'Lightbox Icon Position', 'shopbuilder' ),
				'type'      => 'select',
				'options'   => [
					'top-right'    => esc_html__( 'Top Right', 'shopbuilder' ),
					'top-left'     => esc_html__( 'Top Left', 'shopbuilder' ),
					'bottom-right' => esc_html__( 'Bottom Right', 'shopbuilder' ),
					'bottom-left'  => esc_html__( 'Bottom Left', 'shopbuilder' ),
				],
				'default'   => 'top-right',
				'separator' => 'default',
			];
		}
		$fields['zoom_section_end'] = [
			'mode' => 'section_end',
		];

		if ( function_exists( 'rtwpvg' ) ) {
			unset( $fields['zoom_position'] );
		}
		return $fields;
	}
}
