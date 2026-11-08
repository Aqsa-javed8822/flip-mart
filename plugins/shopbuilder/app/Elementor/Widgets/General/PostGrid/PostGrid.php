<?php
/**
 * AdvancedHeading class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\PostGrid;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Dropcaps class.
 *
 * @package RadiusTheme\SB
 */
class PostGrid extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Post Grid', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-post-grid';

		parent::__construct( $data, $args );

		$this->rtsb_category = 'rtsb-shopbuilder-general';
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return array_merge(
			Controls::content( $this ),
			Controls::style( $this )
		);
	}
	/**
	 * Style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends(): array {
		if ( ! $this->is_edit_mode() ) {
			return [
				'rtsb-general-addons',
			];
		}

		return [
			'rtsb-general-addons',
			'elementor-icons-shared-0',
			'elementor-icons-fa-solid',
		];
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'Post Grid','Blog Post','Post' ] + parent::get_keywords();
	}



	/**
	 * Addon Render.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		switch ( $settings['layout_style'] ) {
			case 'rtsb-post-grid-layout3':
				$template = 'layout3';
				break;
			case 'rtsb-post-grid-layout2':
				$template = 'layout2';
				break;
			default:
				$template = 'layout1';
				break;
		}
		$template = apply_filters( 'rtsb/general_widget/post_grid/template', $template, $settings );
		$data     = [
			'template'           => 'elementor/general/post-grid/' . $template,
			'id'                 => $this->get_id(),
			'unique_name'        => $this->get_unique_name(),
			'layout'             => $settings['layout_style'],
			'settings'           => $settings,
			'is_carousel'        => false,
			'is_post_query'      => true,
			'is_grid'            => true,
			'is_post_pagination' => ! empty( $settings['show_pagination'] ),
			'container_class'    => 'rtsb-blog-post',
			'content_class'      => 'rtsb-post-grid',
		];

		// Render initialization.
		$this->theme_support();

		// Call the template rendering method.
		Fns::print_html( ( new Render() )->display_content( $data, $settings ), true );
		$this->edit_mode_script();
		$this->theme_support( 'render_reset' );
	}
}
