<?php
/**
 * Render class for Advanced Heading widget.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\LogoSliderAndGrid;

use DateTime;
use DateTimeZone;
use RadiusTheme\SB\Elementor\Helper\RenderHelpers;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Render\GeneralAddons;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Render class.
 *
 * @package RadiusTheme\SB
 */
class Render extends GeneralAddons {
	/**
	 * Main render function for displaying content.
	 *
	 * @param array $data     Data to be passed to the template.
	 * @param array $settings Widget settings.
	 *
	 * @return string
	 */
	public function display_content( $data, $settings ) {
		$data['content'] = '';
		$this->settings  = $settings;
		$data            = wp_parse_args( $this->get_template_args( $data ), $data );
		$data            = apply_filters( 'rtsb/general/logo_slider_and_grid/args/' . $data['unique_name'], $data );

		if ( ! empty( $data['logo_items'] ) ) {
			foreach ( $data['logo_items'] as  $logo ) {
				$data['logo']               = $logo;
				$data['logo_brand_name']    = $logo['logo_name'] ?? '';
				$data['enable_img_link']    = ! empty( $logo['enable_img_link'] );
				$data['img_link_attribute'] = $this->render_link_attributes( $logo['_id'], $logo['img_link'] );
				$data['grid_classes']       = $this->content_classes;
				$data['content']           .= Fns::load_template( $data['template'], $data, true );
			}

			return $this->addon_view( $data, $settings );
		} else {
			return '<p>' . esc_html__( 'Please add a logo image.', 'shopbuilder' ) . '</p>';
		}
	}
	/**
	 * Retrieves template arguments based on widget settings.
	 *
	 * @param array $data Data to be passed to the template.
	 *
	 * @return array
	 */
	private function get_template_args( $data ) {
		if ( ! empty( $data['content_class'] ) ) {
			$this->content_classes[] = $data['content_class'];
		}

		if ( ! empty( $this->settings['activate_slider_item'] ) ) {
			$this->content_classes = array_merge(
				$this->content_classes,
				[
					'rtsb-slide-item',
					'swiper-slide',
					'animated',
					'rtFadeIn',
				]
			);
		}

		$this->content_classes = is_array( $this->content_classes ) ? implode( ' ', $this->content_classes ) : $this->content_classes;

		return [
			'has_logo_name' => ! empty( $this->settings['display_logo_name'] ),
			'logo_items'    => $this->settings['sb_logo_items'] ?? [],
			'instance'      => $this,
		];
	}
	/**
	 * Function to render icon image.
	 *
	 * @param array $logo_image_attr Logo image attributes.
	 *
	 * @return string
	 */
	public function render_logo_image( $logo_image_attr ) {
		$img_html = '';
		if ( ! empty( $logo_image_attr['logo_image'] ) ) {
			$c_image_size         = RenderHelpers::get_data( $this->settings, 'logo_img_dimension', [] );
			$c_image_size['crop'] = RenderHelpers::get_data( $this->settings, 'logo_img_crop', [] );
			$c_image_size         = ! empty( $c_image_size ) && is_array( $c_image_size ) ? $c_image_size : [];
			$image_id             = ! empty( $logo_image_attr['logo_image']['id'] ) ? $logo_image_attr['logo_image']['id'] : $logo_image_attr['logo_image'];
			$img_html            .= Fns::get_product_image_html(
				'',
				null,
				RenderHelpers::get_data( $this->settings, 'logo_image_size', 'full' ),
				$image_id,
				$c_image_size
			);
		}

		return $img_html;
	}
}
