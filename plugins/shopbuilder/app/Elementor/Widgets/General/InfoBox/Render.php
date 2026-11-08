<?php
/**
 * Render class for Info Box widget.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\InfoBox;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Helper\RenderHelpers;
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
		$this->settings  = $settings;
		$data            = wp_parse_args( $this->get_template_args( $data['id'] ), $data );
		$data            = apply_filters( 'rtsb/general/info_box/args/' . $data['unique_name'], $data );
		$data['content'] = Fns::load_template( $data['template'], $data, true );

		return $this->addon_view( $data, $settings );
	}

	/**
	 * Retrieves template arguments based on widget settings.
	 *
	 * @param int $id Widget ID.
	 *
	 * @return array
	 */
	private function get_template_args( $id ) {
		return [
			'info_box_title_html_tag' => $this->settings['info_box_title_html_tag'] ?? 'h2',
			'info_box_title'          => $this->settings['info_box_title'],
			'has_description'         => ! empty( $this->settings['display_content'] ) && ! empty( $this->settings['info_box_content'] ),
			'description'             => $this->settings['info_box_content'] ?? '',
			'has_button'              => ! empty( $this->settings['display_button'] ),
			'has_count'               => ! empty( $this->settings['display_count'] ),
			'count'                   => $this->settings['info_box_count'] ?? '01',
			'sb_button_content'       => $this->settings['sb_button_content'],
			'sb_button_icon'          => $this->settings['sb_button_icon'] ?? '',
			'icon_border_layer'       => $this->settings['info_box_icon_border_layer'] ?? '',
			'sb_button_icon_position' => $this->settings['sb_button_icon_position'] ?? 'right',
			'button_attributes'       => $this->render_button_attributes( 'rtsb_button_' . $id, 'sb_button_link', 'primary-btn' ) ?? '',
			'icon_html'               => $this->render_icon_image(),
			'icon_type'               => $this->settings['info_box_icon_type'] ?? 'icon',
			'icon_bg_type'            => ! empty( $this->settings['info_box_icon_style_gradient_bg_background'] ) ? $this->settings['info_box_icon_style_gradient_bg_background'] : 'classic',
			'icon_hover_bg_type'      => ! empty( $this->settings['info_box_icon_style_hover_gradient_bg_background'] ) ? $this->settings['info_box_icon_style_hover_gradient_bg_background'] : 'classic',
		];
	}

	/**
	 * Function to render button attributes.
	 *
	 * @param string $id Element ID.
	 * @param string $control_name Control name.
	 * @param string $class Class.
	 * @param string $type Button type.
	 *
	 * @return string
	 */
	public function render_button_attributes( $id, $control_name, $class, $type = 'rtsb-btn' ) {
		if ( ! empty( $this->settings['hover_animation'] ) ) {
			$class .= ' ' . 'hover-' . $this->settings['hover_animation'];
		}
		$this->add_attribute( $id, 'class', $type . ' ' . $class );

		if ( ! empty( $this->settings[ $control_name ]['url'] ) ) {
			$this->add_link_attributes( $id, $this->settings[ $control_name ] );
		} else {
			$this->add_attribute( $id, 'role', 'button' );
		}

		return $this->get_attribute_string( $id );
	}
	/**
	 * Function to render icon image.
	 *
	 * @return string
	 */
	public function render_icon_image() {
		$icon_img_html = '';

		$icon = $this->settings['info_box_icon'] ?? '';

		if ( 'icon' === $this->settings['info_box_icon_type'] ) {
			$icon_img_html .= '<span class="icon">' . Fns::icons_manager( $icon ) . '</span>';
		} else {
			if ( ! empty( $this->settings['info_box_image'] ) ) {
				$c_image_size         = RenderHelpers::get_data( $this->settings, 'info_box_img_dimension', [] );
				$c_image_size['crop'] = RenderHelpers::get_data( $this->settings, 'info_box_img_crop', [] );
				$c_image_size         = ! empty( $c_image_size ) && is_array( $c_image_size ) ? $c_image_size : [];
				$image_id             = ! empty( $this->settings['info_box_image']['id'] ) ? $this->settings['info_box_image']['id'] : $this->settings['info_box_image'];
				$icon_img_html       .= Fns::get_product_image_html(
					'',
					null,
					RenderHelpers::get_data( $this->settings, 'info_box_image_size', 'full' ),
					$image_id,
					$c_image_size
				);
			}
		}

		return $icon_img_html;
	}
}
