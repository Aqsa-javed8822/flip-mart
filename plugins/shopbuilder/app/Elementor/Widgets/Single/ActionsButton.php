<?php

/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Single;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\ActionBtnTraits;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\ActionsBtnSettings;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ActionsButton extends ElementorWidgetBase {
	/**
	 * Action Button Traits.
	 */
	use ActionBtnTraits;

	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Actions Button', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-actions-button';
		parent::__construct( $data, $args );
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return ActionsBtnSettings::widget_fields( $this );
	}

	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'Compare', 'Wishlist' ] + parent::get_keywords();
	}

	/**
	 * Widget Field.
	 *
	 * @param array $controllers Control.
	 *
	 * @return void
	 */
	public function apply_hooks() {
		add_filter( 'rtsb/module/wishlist/button_params', [ $this, 'wishlist_button_params' ], 99 );
		add_filter( 'rtsb/module/compare/button_params', [ $this, 'compare_button_params' ], 99 );
	}

	/**
	 * @return void
	 */
	public function remove_hooks() {
		remove_filter( 'rtsb/module/wishlist/button_params', [ $this, 'wishlist_button_params' ], 99 );
		remove_filter( 'rtsb/module/compare/button_params', [ $this, 'compare_button_params' ], 99 );
	}

	/**
	 * @param $params
	 *
	 * @return mixed
	 */
	public function wishlist_button_params( $params ) {
		$controllers = $this->get_settings_for_display();
		if ( ! empty( $controllers['wishlist_button_text'] ) ) {
			$params['button_text']      = $controllers['wishlist_button_text'];
			$params['show_button_text'] = true;
		}

		return $params;
	}

	/**
	 * @param $params
	 *
	 * @return mixed
	 */
	public function compare_button_params( $params ) {
		$controllers = $this->get_settings_for_display();
		if ( ! empty( $controllers['comparison_button_text'] ) ) {
			$params['button_text']      = $controllers['comparison_button_text'];
			$params['show_button_text'] = true;
		}

		return $params;
	}
	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		global $post, $product;
		$_product    = $product;
		$product     = Fns::get_product();
		$controllers = $this->get_settings_for_display();
		$this->action_button_icon_modify();
		$this->apply_hooks();
		$this->theme_support();

		ob_start();
		if ( ! empty( $controllers['wishlist_button'] ) ) {
			echo '<div class="wishlist-wrapper button-item">';
			do_action( 'rtsb/modules/wishlist/frontend/display' );
			echo '</div>';
		}
		if ( ! empty( $controllers['button_separator'] ) ) {
			echo '<div class="button-item button-separator">';
			echo esc_html( $controllers['button_separator'] );
			echo '</div>';
		}
		if ( ! empty( $controllers['comparison_button'] ) ) {
			echo '<div class="compare-wrapper button-item">';
			do_action( 'rtsb/modules/compare/frontend/display' );
			echo '</div>';
		}
		$button_html = ob_get_clean();

		if ( empty( $button_html ) && $this->is_builder_mode() ) {
			$button_html = $this->rtsb_name;
		}

		$data = [
			'template'    => 'elementor/single-product/actions-button',
			'controllers' => $controllers,
			'content'     => $button_html,
		];

		Fns::load_template( $data['template'], $data );

		$this->remove_hooks();

		$this->theme_support( 'render_reset' );

		$product = $_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$this->action_button_icon_set_default();
	}
}
