<?php
/**
 * Main FilterHooks class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\Badges;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit();

/**
 * Main FilterHooks class.
 */
class Badges {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * @var array|mixed
	 */
	private array $options;

	/**
	 * Module Class Constructor.
	 */
	private function __construct() {
		$this->options = Fns::get_options( 'modules', 'product_badges' );
		$this->theme_support();
		BadgesFrontEnd::instance();
	}

	/**
	 * @return void
	 */
	private function theme_support() {
		// Astra Theme Support.
		if ( ! defined( 'ASTRA_EXT_VER' ) || ( defined( 'ASTRA_EXT_VER' ) && ! \Astra_Ext_Extension::is_active( 'woocommerce' ) ) ) {
			add_filter( 'astra_addon_shop_cards_buttons_html', [ $this, 'astra_flash_sale_html_remove' ], 50 );
		}
	}


	/**
	 * @param $html
	 *
	 * @return string
	 */
	public function astra_flash_sale_html_remove( $html ) {
		$hide_default_badge = $this->options['hide_woocommerce_badge'] ?? false;

		if ( ! $hide_default_badge ) {
			return $html;
		}
		global $product;
		$hide_on_sale = ( $this->options['hide_on_sale'] ?? 'all_products' );
		switch ( $hide_on_sale ) {
			case 'where_custom_badge_applied':
				if ( BadgesFns::is_allowed_badge_showing() ) {
					$html = '';
				}
				break;
			case 'all_products':
				$html = '';
				break;
			default:
		}
		return $html;
	}
}
