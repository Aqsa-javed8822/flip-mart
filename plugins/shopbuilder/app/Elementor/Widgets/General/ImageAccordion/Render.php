<?php
/**
 * Render class for Advanced Heading widget.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\ImageAccordion;

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
		$this->settings  = $settings;
		$data            = wp_parse_args( $this->get_template_args( $data['id'] ), $data );
		$data            = apply_filters( 'rtsb/general/image_accordion/args/' . $data['unique_name'], $data );
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
			'accordion_active_type'    => $this->settings['accordion_active_type'] ?? 'hover',
			'active_item'              => ! empty( $this->settings['active_item'] ),
			'active_item_number'       => $this->settings['active_item_number'] ?? '1',
			'accordion_items'          => $this->settings['image_accordion_items'] ?? [],
			'accordion_title_html_tag' => $this->settings['accordion_title_html_tag'] ?? 'h2',
			'wrapper_class'            => $this->generate_wrapper_class(),
			'instance'                 => $this,
		];
	}
	/**
	 * Function to render button attributes.
	 *
	 * @param string $id Element ID.
	 *
	 * @return string
	 */
	public function render_button_attributes( $id, $control_name ) {

		$this->add_attribute( $id, 'class', 'link' );
		if ( ! empty( $control_name['url'] ) ) {
			$this->add_link_attributes( $id, $control_name );
		} else {
			$this->add_attribute( $id, 'role', 'button' );
		}
		return $this->get_attribute_string( $id );
	}
	/**
	 * Function to generate wrapper class.
	 *
	 * @return string
	 */
	public function generate_wrapper_class() {
		$wrapper_class = 'rtsb-image-accordion-wrapper rtsb-image-accordion-mob-vertical rtsb-image-accordion-tab-vertical';
		if ( 'rtsb-image-accordion-layout1' === $this->settings['layout_style'] ) {
			$wrapper_class .= ' rtsb-image-accordion-style-1 ';
		} elseif ( 'rtsb-image-accordion-layout2' === $this->settings['layout_style'] ) {
			$wrapper_class .= ' rtsb-image-accordion-style-2 ';
		}
		if ( 'hover' === $this->settings['accordion_active_type'] ) {
			$wrapper_class .= ' rtsb-image-accordion-hover';
		} else {
			$wrapper_class .= ' rtsb-image-accordion-click';
		}
		if ( 'horizontal' === $this->settings['image_accordion_style'] ) {
			$wrapper_class .= ' rtsb-image-accordion-horizontal';
		} else {
			$wrapper_class .= ' rtsb-image-accordion-vertical';
		}
		return $wrapper_class;
	}
}
