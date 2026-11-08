<?php
/**
 * Render class for Advanced Heading widget.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\CallToAction;

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
		$data            = apply_filters( 'rtsb/general/cta/args/' . $data['unique_name'], $data );
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
			'cta_title_html_tag'      => $this->settings['cta_title_html_tag'] ?? 'h2',
			'cta_title'               => $this->settings['cta_title'],
			'has_description'         => ! empty( $this->settings['display_content'] ) && ! empty( $this->settings['cta_content'] ),
			'description'             => $this->settings['cta_content'] ?? '',
			'has_button'              => ! empty( $this->settings['display_button'] ),
			'has_button2'             => ! empty( $this->settings['display_button2'] ),
			'sb_button_content'       => $this->settings['sb_button_content'],
			'sb_button2_content'      => $this->settings['sb_button2_content'],
			'sb_button_icon'          => $this->settings['sb_button_icon'] ?? '',
			'sb_button_icon_position' => $this->settings['sb_button_icon_position'] ?? 'right',
			'button1_attributes'      => $this->render_button_attributes( 'rtsb_button_' . $id, 'sb_button_link', 'primary-btn' ) ?? '',
			'button2_attributes'      => $this->render_button_attributes( 'rtsb_button2_' . $id, 'sb_button2_link', 'secondary-btn' ) ?? '',
			'cta_img_link_attributes' => $this->render_button_attributes( 'rtsb_cta_image_' . $id, 'cta_img_link', 'cta-img-link', 'rtsb-link' ) ?? '',
			'section_parallax'        => ! empty( $this->settings['parallax_effect'] ) && 'yes' === $this->settings['parallax_effect'] ? 'has-rtsb-parallax' : '',
			'parallax_speed'          => $this->settings['parallax_speed'] ?? '0.5',
			'img_html'                => $this->render_image(),
			'has_cta_img_linkable'    => ! empty( $this->settings['cta_img_linkable'] ),
			'has_cta_img_link'        => $this->settings['cta_img_link'] ?? '',
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
	 * Function to render image.
	 *
	 * @return string
	 */
	public function render_image() {
		$img_html = '';

		if ( ! empty( $this->settings['cta_image'] ) ) {
			$c_image_size         = RenderHelpers::get_data( $this->settings, 'cta_img_dimension', [] );
			$c_image_size['crop'] = RenderHelpers::get_data( $this->settings, 'cta_img_crop', [] );
			$c_image_size         = ! empty( $c_image_size ) && is_array( $c_image_size ) ? $c_image_size : [];
			$image_id             = ! empty( $this->settings['cta_image']['id'] ) ? $this->settings['cta_image']['id'] : $this->settings['cta_image'];
			$img_html            .= Fns::get_product_image_html(
				'',
				null,
				RenderHelpers::get_data( $this->settings, 'cta_image_size', 'full' ),
				$image_id,
				$c_image_size
			);
		}
		return $img_html;
	}
}
