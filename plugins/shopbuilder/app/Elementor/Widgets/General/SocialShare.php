<?php
/**
 * SocialShare class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General;

use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Render\Render;
use RadiusTheme\SB\Elementor\Widgets\Controls;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * SocialShare class.
 */
class SocialShare extends ElementorWidgetBase {
	/**
	 * Control Fields
	 *
	 * @var array
	 */
	private $control_fields = [];

	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Social Share', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-social-share';

		parent::__construct( $data, $args );

		$this->pro_tab = 'content';

		$type = BuilderFns::builder_type( get_the_ID() );
		if ( 'quick-view' !== $type ) {
			$this->rtsb_category = 'rtsb-shopbuilder-general';
		}
	}

	/**
	 * Style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends(): array {
		if ( ! $this->is_edit_mode() ) {
			return [];
		}

		return [
			'elementor-icons-shared-0',
			'elementor-icons-fa-solid',
		];
	}

	/**
	 * Controls for layout tab
	 *
	 * @return SocialShare
	 */
	protected function layout_tab() {
		$sections = apply_filters(
			'rtsb/elements/elementor/share_layout_tab',
			array_merge(
				Controls\ContentFields::share_presets( $this ),
				Controls\ContentFields::share_platforms( $this ),
			),
			$this
		);

		$this->control_fields = array_merge( $this->control_fields, $sections );

		return $this;
	}

	/**
	 * Controls for style tab
	 *
	 * @return SocialShare
	 */
	protected function style_tab() {
		$sections = apply_filters(
			'rtsb/elements/elementor/share_style_tab',
			array_merge(
				Controls\StyleFields::share_items( $this ),
				Controls\StyleFields::share_header( $this ),
				Controls\StyleFields::share_icons( $this ),
				Controls\StyleFields::share_text( $this ),
			),
			$this
		);

		$this->control_fields = array_merge( $this->control_fields, $sections );

		return $this;
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		$this->layout_tab()->style_tab();

		if ( empty( $this->control_fields ) ) {
			return [];
		}

		return $this->control_fields;
	}

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->theme_support();
		$template = 'elementor/general/social-share';

		Fns::print_html( Render::instance()->social_share_view( $template, $settings ), true );
		$this->theme_support( 'render_reset' );
	}
}
