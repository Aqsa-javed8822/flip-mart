<?php
/**
 * Render class for Advanced Heading widget.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\FlipBox;

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
		$this->settings  = $settings;
		$data            = wp_parse_args( $this->get_template_args( $data['id'] ), $data );
		$data            = apply_filters( 'rtsb/general/flip_box/args/' . $data['unique_name'], $data );
		$data['content'] = Fns::load_template( $data['template'], $data, true );

		return $this->addon_view( $data, $settings );
	}

	/**
	 * Retrieves template arguments based on widget settings.
	 *
	 * @return array
	 */
	private function get_template_args( $id ) {
		return [
			'flip_behavior'                 => $this->settings['flip_behavior'] ?? 'hover',
			'flip_direction'                => $this->settings['flip_direction'] ?? 'flip-left',
			'flip_selected_side'            => $this->settings['flip_selected_side'] ?? 'front',
			'flip_box_front_title_html_tag' => $this->settings['flip_box_front_title_html_tag'] ?? 'h2',
			'flip_box_back_title_html_tag'  => $this->settings['flip_box_back_title_html_tag'] ?? 'h2',
			'flip_box_front_title'          => $this->settings['flip_box_front_title'],
			'flip_box_back_title'           => $this->settings['flip_box_back_title'],
			'has_front_description'         => ! empty( $this->settings['display_front_content'] ) && ! empty( $this->settings['flip_box_front_content'] ),
			'has_back_description'          => ! empty( $this->settings['display_back_content'] ) && ! empty( $this->settings['flip_box_back_content'] ),
			'front_description'             => $this->settings['flip_box_front_content'] ?? '',
			'back_description'              => $this->settings['flip_box_back_content'] ?? '',
			'has_front_button'              => ! empty( $this->settings['display_front_button'] ),
			'sb_front_button_content'       => $this->settings['sb_front_button_content'],
			'sb_front_button_icon'          => $this->settings['sb_front_button_icon'] ?? '',
			'sb_front_button_icon_position' => $this->settings['sb_front_button_icon_position'] ?? 'right',
			'front_button_attributes'       => $this->render_button_attributes( 'rtsb_front_button_' . $id, 'sb_front_button_link', 'front-primary-btn' ) ?? '',
			'has_back_button'               => ! empty( $this->settings['display_back_button'] ),
			'sb_back_button_content'        => $this->settings['sb_back_button_content'],
			'sb_back_button_icon'           => $this->settings['sb_back_button_icon'] ?? '',
			'sb_back_button_icon_position'  => $this->settings['sb_back_button_icon_position'] ?? 'right',
			'back_button_attributes'        => $this->render_button_attributes( 'rtsb_back_button_' . $id, 'sb_back_button_link', 'back-primary-btn' ) ?? '',
			'front_side_icon_html'          => $this->render_icon_image( 'front' ),
			'back_side_icon_html'           => $this->render_icon_image( 'back' ),
			'front_icon_type'               => $this->settings['flip_box_front_icon_type'] ?? 'icon',
			'back_icon_type'                => $this->settings['flip_box_back_icon_type'] ?? 'icon',
			'flip_animate_class'            => $this->flip_animate_class(),
			'icon_bg_type'                  => ! empty( $this->settings['flip_box_icon_style_gradient_bg_background'] ) ? $this->settings['flip_box_icon_style_gradient_bg_background'] : 'classic',
			'icon_hover_bg_type'            => ! empty( $this->settings['flip_box_icon_style_hover_gradient_bg_background'] ) ? $this->settings['flip_box_icon_style_hover_gradient_bg_background'] : 'classic',
		];
	}
	/**
	 * Function to render button attributes.
	 *
	 * @param string $id Element ID.
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
	 * Function to add flip animate class.
	 *
	 * @return string
	 */
	public function flip_animate_class() {

		$animate_class   = '';
		$flip_directions = [ 'sb-flip-3d-left', 'sb-flip-3d-right', 'sb-flip-3d-up', 'sb-flip-3d-bottom' ];
		$animate_class  .= $this->settings['flip_direction'] ?? 'sb-flip-left';

		if ( in_array( $this->settings['flip_direction'], $flip_directions ) ) {
			$animate_class .= ' sb-3d-flip';
		}
		if ( 'back' === $this->settings['flip_selected_side'] ) {
			$animate_class .= ' flip-back-selected';
		}
		if ( 'click' === $this->settings['flip_behavior'] ) {
			$animate_class .= ' click';
		}
		return $animate_class;
	}
	/**
	 * Function to render icon.
	 *
	 * @param string $position icon position ('left' or 'right').
	 * @param array  $icon Position of the separator in settings.
	 *
	 * @return string
	 */
	public static function render_icon( $position, $icon, $icon_position ) {
		$html = '';

		// Render the left icon.
		if ( $position === $icon_position ) {
			$html .= '<span class="icon">' . Fns::icons_manager( $icon ) . '</span>';
		}

		return $html;
	}

	/**
	 * Function to render icon image.
	 *
	 * @return string
	 */
	public function render_icon_image( $prefix ) {
		$icon_img_html = '';

		$icon = $this->settings[ 'flip_box_' . $prefix . '_icon' ] ?? '';

		if ( 'icon' === $this->settings[ 'flip_box_' . $prefix . '_icon_type' ] ) {
			$icon_img_html .= '<span class="icon">' . Fns::icons_manager( $icon ) . '</span>';
		} else {
			if ( ! empty( $this->settings[ 'flip_box_' . $prefix . '_image' ] ) ) {
				$c_image_size         = RenderHelpers::get_data( $this->settings, 'flip_box_' . $prefix . '_img_dimension', [] );
				$c_image_size['crop'] = RenderHelpers::get_data( $this->settings, 'flip_box_' . $prefix . '_img_crop', [] );
				$c_image_size         = ! empty( $c_image_size ) && is_array( $c_image_size ) ? $c_image_size : [];
				$image_id             = ! empty( $this->settings[ 'flip_box_' . $prefix . '_image' ]['id'] ) ? $this->settings[ 'flip_box_' . $prefix . '_image' ]['id'] : $this->settings[ 'flip_box_' . $prefix . '_image' ];
				$icon_img_html       .= Fns::get_product_image_html(
					'',
					null,
					RenderHelpers::get_data( $this->settings, 'flip_box_' . $prefix . '_image_size', 'full' ),
					$image_id,
					$c_image_size
				);
			}
		}

		return $icon_img_html;
	}
}
