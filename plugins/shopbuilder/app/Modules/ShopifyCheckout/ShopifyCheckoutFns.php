<?php
/**
 * Sticky add-to-cart Functions Class.
 *
 * @package Rse\SB
 */

namespace RadiusTheme\SB\Modules\ShopifyCheckout;

use RadiusTheme\SB\Helpers\Fns;
use function WooCommerce\PayPalCommerce\OrderTracking\tr;

defined( 'ABSPATH' ) || exit();

/**
 * Sticky add-to-cart Functions Class.
 */
class ShopifyCheckoutFns {

	/**
	 * Is Multistep
	 *
	 * @return string
	 */
	public static function is_maltistep() {
		global $shopify_checkout_options;
		return rtsb()->has_pro() && 'on' === ( $shopify_checkout_options['enable_multi_step'] ?? 'off' );
	}
	/**
	 * Is Clickable
	 *
	 * @return bool
	 */
	public static function is_review_item_clickble() {
		global $shopify_checkout_options;
		return 'on' === ( $shopify_checkout_options['order_review_item_link'] ?? 'off' );
	}


	/**
	 * Generates HTML markup for displaying an endpoint icon.
	 *
	 * @return string
	 */
	public static function print_text_setting( $field_key, $default = '' ) {
		global $shopify_checkout_options;
		$text = do_shortcode( $shopify_checkout_options[ $field_key ] ?? $default );
		if ( empty( $text ) ) {
			return;
		}
		$text = str_replace( '{year}', gmdate( 'Y' ), $text );
		Fns::print_html( $text );
	}

	/**
	 * Generates HTML markup for displaying an endpoint icon.
	 *
	 * @return string
	 */
	public static function print_footer_menu() {
		global $shopify_checkout_options;
		$menu_slug = $shopify_checkout_options['footer_menu'] ?? false;
		if ( ! $menu_slug ) {
			return;
		}
		// Get the menu object by slug.
		$menu = wp_get_nav_menu_object( $menu_slug );
		if ( ! $menu ) {
			return;
		}
		// Get menu items by menu ID.
		$menu_items = wp_get_nav_menu_items( $menu->term_id );
		if ( empty( $menu_items ) ) {
			return;
		}
		$menu_html = '<ul class="shopify-footer-menu rtsb-d-flex">';
		foreach ( $menu_items as $item ) {
			$menu_html .= "<li><a href='{$item->url}'>{$item->title}</a></li>";
		}
		$menu_html .= '</ul>';
		Fns::print_html( $menu_html, true );
	}

	/**
	 * Generates HTML markup for displaying an endpoint icon.
	 *
	 * @return string
	 */
	public static function get_shopify_page_logo( $isReturn = false ) {
		global $shopify_checkout_options;
		$cart_image_icon = ! empty( $shopify_checkout_options['shopify_page_logo'] ) ? json_decode( stripslashes( $shopify_checkout_options['shopify_page_logo'] ), true ) : false;
		if ( empty( $cart_image_icon ) ) {
			return;
		}
		$style = '';
		if ( ! empty( $shopify_checkout_options['shopify_logo_height'] ) ) {
			$style .= 'height:' . $shopify_checkout_options['shopify_logo_height'] . 'px;';
		}
		$icon_id = $cart_image_icon['id'] ?? 0;

		if ( ! wp_attachment_is_image( $icon_id ) ) {
			return;
		}
		$html  = '<div class="rtsb-shopify-logo" style="' . esc_attr( $style ) . '" >';
		$html .= wp_get_attachment_image( absint( $icon_id ), 'full' );

		$html .= '</div>';
		if ( $isReturn ) {
			return $html;
		}
		Fns::print_html( $html );
	}
	/**
	 * Generates HTML markup for displaying an endpoint icon.
	 *
	 * @return string
	 */
	public static function get_cart_icon_html( $isReturn = false ) {
		$data = [];
		$html = '';
		global $shopify_checkout_options;
		if ( 'none' === ( $shopify_checkout_options['cart_icon_source'] ?? '' ) ) {
			$data['icon_source'] = 'none';
		} elseif ( 'select_icon' === ( $shopify_checkout_options['cart_icon_source'] ?? '' ) ) {
			$data['icon_source'] = 'select_icon';
			$data['custom_icon'] = ( $shopify_checkout_options['cart_icon'] ?? '' );
		} elseif ( 'upload_icon' === ( $shopify_checkout_options['cart_icon_source'] ?? '' ) ) {
			$data['icon_source'] = 'upload_icon';
			$cart_image_icon     = ! empty( $shopify_checkout_options['cart_image_icon'] ) ? json_decode( stripslashes( $shopify_checkout_options['cart_image_icon'] ), true ) : false;
			if ( ! empty( $cart_image_icon['id'] ) && ! empty( $cart_image_icon['source'] ) ) {
				$data['image_icon'] = [
					'id'     => $cart_image_icon['id'],
					'source' => $cart_image_icon['source'],
				];
			}
		} else {
			$html = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" focusable="false" aria-hidden="true" class="rtsb-shopify-header-cart-icon">
                        <path d="M2.675 10.037 3.072 4.2h7.856l.397 5.837A2.4 2.4 0 0 1 8.932 12.6H5.069a2.4 2.4 0 0 1-2.394-2.563"></path>
                        <path d="M4.9 3.5a2.1 2.1 0 1 1 4.2 0v1.4a2.1 2.1 0 0 1-4.2 0z"></path>
                    </svg>';
		}
		if ( empty( $data ) ) {
			return $html;
		}
		$html = Fns::get_icon_html( $data, false );
		if ( $isReturn ) {
			return $html;
		}
		Fns::print_html( $html, true );
	}
}
