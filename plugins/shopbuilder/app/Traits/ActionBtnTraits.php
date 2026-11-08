<?php
/**
 * Trait: Elementor Action Buttons.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Traits;

use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

trait ActionBtnTraits {

	/**
	 * Icon
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'elementor-icons-fa-regular',
			'elementor-icons-fa-solid',
		];

	}

	/**
	 * Wishlist icon
	 *
	 * @param string $icon_html Icon Html.
	 *
	 * @return string
	 */
	public function wishlist_icon( $icon_html ) {
		$controllers = $this->get_settings_for_display();

		if ( ! empty( $controllers['wishlist_icon'] ) ) {
			$icon_html = Fns::icons_manager( $controllers['wishlist_icon'], 'icon-default' );
			$icon_html .= Fns::icons_manager( $controllers['wishlist_icon_added'], 'added-icon' );
		}

		return $icon_html;
	}

	/**
	 * Compare icon
	 *
	 * @param string $icon_html Icon Html.
	 *
	 * @return string
	 */
	public function compare_icon( $icon_html ) {
		$controllers = $this->get_settings_for_display();
		if ( ! empty( $controllers['comparison_icon'] ) ) {
			$icon_html = Fns::icons_manager( $controllers['comparison_icon'], 'icon-default' );
			$icon_html .= Fns::icons_manager( $controllers['comparison_icon_added'], 'added-icon' );
		}

		return $icon_html;
	}

	/**
	 * Compare icon
	 *
	 * @param string $icon_html Icon Html.
	 *
	 * @return string
	 */
	public function quick_view_icon( $icon_html ) {
		$controllers = $this->get_settings_for_display();
		if ( ! empty( $controllers['quick_view_icon'] ) ) {
			$icon_html = Fns::icons_manager( $controllers['quick_view_icon'], 'icon-default' );
		}

		return $icon_html;
	}

	/**
	 * Icon Replace
	 *
	 * @return string
	 */
	public function print_action_button( $position ) {
		$controllers = $this->get_settings_for_display();

		$wishlist_loop_btn_position   = Fns::get_option( 'modules', 'wishlist', 'loop_btn_position', 'after_add_to_cart' );
		$compare_loop_btn_position    = Fns::get_option( 'modules', 'compare', 'loop_btn_position', 'after_add_to_cart' );
		$quick_view_loop_btn_position = Fns::get_option( 'modules', 'quick_view', 'loop_btn_position', 'after_add_to_cart' );
		$action_button = [];
		global $product;
		if ( Fns::is_module_active( 'wishlist' ) && ! empty( $controllers['wishlist_button'] ) && $position == $wishlist_loop_btn_position ) {
			ob_start();
				do_action( 'rtsb/modules/wishlist/print_button', $product->get_id() );
			$action_button['wishlist'] = ob_get_clean();
		}

		if ( Fns::is_module_active( 'compare' ) && ! empty( $controllers['comparison_button'] ) && $position == $compare_loop_btn_position ) {
			ob_start();
				do_action( 'rtsb/modules/compare/print_button', $product->get_id() );
			$action_button['compare'] = ob_get_clean();
		}

		if ( Fns::is_module_active( 'quick_view' ) && ! empty( $controllers['quick_view_button'] ) && $position == $quick_view_loop_btn_position ) {
			ob_start();
				do_action( 'rtsb/modules/quick_view/print_button', $product->get_id() );
			$action_button['quick_view'] = ob_get_clean();
		}

		$action_button = apply_filters( 'rtsb/modules/actions/button/print_button', $action_button, $controllers, $position, $product );

		if( ! count( $action_button ) ){
			return ;
		}

		echo "<div class='action-button-wrapper'>";
			foreach ( $action_button as $index => $item ) {
				Fns::print_html( $item );
			}
		echo '</div>';

	}

	/**
	 * @return void
	 */
	public function before_add_to_cart() {
		$this->print_action_button( 'before_add_to_cart' );
	}

	/**
	 * @return void
	 */
	public function after_add_to_cart() {
		$this->print_action_button( 'after_add_to_cart' );
	}

	/**
	 * Icon Replace
	 *
	 * @return void
	 */
	public function action_button_icon_modify() {
		add_filter( 'rtsb/module/wishlist/icon_html', [ $this, 'wishlist_icon' ] );
		add_filter( 'rtsb/module/compare/icon_html', [ $this, 'compare_icon' ] );
		add_filter( 'rtsb/module/quick_view/icon_html', [ $this, 'quick_view_icon' ] );
		// add_filter( 'woocommerce_loop_add_to_cart_link', [ $this, 'print_action_button' ] );
		// before_add_to_cart
		// woocommerce_after_shop_loop_item
		add_action( 'woocommerce_after_shop_loop_item', [ $this, 'before_add_to_cart' ], 7 );
		// after_add_to_cart
		// woocommerce_after_shop_loop_item
		add_action( 'woocommerce_after_shop_loop_item', [ $this, 'after_add_to_cart' ], 15 );
	}

	/**
	 * Icon set to default
	 *
	 * @return void
	 */
	public function action_button_icon_set_default() {
		remove_filter( 'rtsb/module/wishlist/icon_html', [ $this, 'wishlist_icon' ] );
		remove_filter( 'rtsb/module/compare/icon_html', [ $this, 'compare_icon' ] );
		remove_filter( 'rtsb/module/quick_view/icon_html', [ $this, 'quick_view_icon' ] );
		// remove_filter( 'woocommerce_loop_add_to_cart_link', [ $this, 'print_action_button' ] );
		// woocommerce_before_shop_loop_item
		// before_add_to_cart
		remove_action( 'woocommerce_after_shop_loop_item', [ $this, 'before_add_to_cart' ], 7 );
		// woocommerce_after_shop_loop_item
		// after_add_to_cart
		remove_action( 'woocommerce_after_shop_loop_item', [ $this, 'after_add_to_cart' ], 15 );
		// woocommerce_after_shop_loop_item
	}
}
