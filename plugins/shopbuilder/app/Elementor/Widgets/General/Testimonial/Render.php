<?php
/**
 * Render class for Advanced Heading widget.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\Testimonial;

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
		$data            = apply_filters( 'rtsb/general/testimonial/args/' . $data['unique_name'], $data );
		if ( ! empty( $data['testimonial_items'] ) ) {
			foreach ( $data['testimonial_items'] as  $testimonial ) {
				$data['testimonial']        = $testimonial;
				$data['author_designation'] = $testimonial['author_designation'] ?? '';
				$data['author_name']        = $testimonial['author_name'] ?? '';
				$data['author_description'] = $testimonial['author_description'] ?? '';
				$data['author_rating']      = $testimonial['author_rating'] ?? '';
				$data['grid_classes']       = $this->content_classes;
				$data['content']           .= Fns::load_template( $data['template'], $data, true );
			}

			return $this->addon_view( $data, $settings );
		} else {
			return '<p>' . esc_html__( 'Please add a testimonial.', 'shopbuilder' ) . '</p>';
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
					'rtsb-testimonial',
					'rtsb-slide-item',
					'swiper-slide',
					'animated',
					'rtFadeIn',
				]
			);
		}

		$this->content_classes = is_array( $this->content_classes ) ? implode( ' ', $this->content_classes ) : $this->content_classes;

		return [
			'testimonial_items'    => $this->settings['testimonial_items'] ?? [],
			'author_name_html_tag' => $this->settings['author_name_html_tag'] ?? 'h2',
			'display_quote_icon'   => ! empty( $this->settings['display_quote_icon'] ),
			'has_designation'      => ! empty( $this->settings['display_author_designation'] ),
			'display_author_image' => ! empty( $this->settings['display_author_image'] ),
			'has_rating'           => ! empty( $this->settings['display_author_rating'] ),
			'instance'             => $this,
		];
	}
	/**
	 * Function to render author image.
	 *
	 * @param array $author_image_attr author image attributes.
	 *
	 * @return string
	 */
	public function render_author_image( $author_image_attr ) {
		$img_html = '';
		if ( ! empty( $author_image_attr['author_image'] ) ) {
			$c_image_size         = RenderHelpers::get_data( $this->settings, 'author_img_dimension', [] );
			$c_image_size['crop'] = RenderHelpers::get_data( $this->settings, 'author_img_crop', [] );
			$c_image_size         = ! empty( $c_image_size ) && is_array( $c_image_size ) ? $c_image_size : [];
			$image_id             = ! empty( $author_image_attr['author_image']['id'] ) ? $author_image_attr['author_image']['id'] : $author_image_attr['author_image'];
			$img_html            .= Fns::get_product_image_html(
				'',
				null,
				RenderHelpers::get_data( $this->settings, 'author_image_size', 'full' ),
				$image_id,
				$c_image_size
			);
		}

		return $img_html;
	}
}
