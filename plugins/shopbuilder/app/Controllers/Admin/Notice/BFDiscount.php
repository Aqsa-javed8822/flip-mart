<?php
/**
 * Special Offer.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\Admin\Notice;

// Do not allow directly accessing this file.
use RadiusTheme\SB\Abstracts\Discount;
use RadiusTheme\SB\Traits\SingletonTrait;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Black Friday Offer.
 */
class BFDiscount extends Discount {

	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * @return array
	 */
	public function the_options(): array {
		return [
			'option_name'    => 'woobundle_eid_ul_fitr_offer_2025',
			'global_check'   => 'woobundle_notice' ,
			'plugin_name'    => 'ShopBuilder',
			'notice_for'     => 'WooCommerce Bundle - 🌙 Eid Special Offer  <img style="width: 60px;position: relative;" src="' . rtsb()->get_assets_uri( 'images/deal.gif' ) . '" />',
			'download_link'  => 'https://www.radiustheme.com/downloads/woocommerce-bundle/',
			'start_date'     => '17 March 2025',
			'end_date'       => '07 April 2025',
			'notice_message' => ' <strong>Eid Special:</strong> Celebrate Eid with exclusive discounts on <strong>ShopBuilder Bundle</strong>. Save <b style="display:inline-block;color: white;background:red;padding: 3px 8px;border-radius:3px; transform: skewX(-10deg);">UP TO 40%</b> </strong>for a limited time! 🎁🌙✨',
		];
		/**
		return [
			'option_name'    => 'woobundle_eid_ul_fitr_offer_2025',
			'global_check'   => 'woobundle_notice' ,
			'plugin_name'    => 'ShopBuilder',
			'notice_for'     => 'WooCommerce Bundle [Black Friday <img style="width: 60px;position: relative;" src="' . rtsb()->get_assets_uri( 'images/deal.gif' ) . '" />]',
			'download_link'  => 'https://www.radiustheme.com/downloads/woocommerce-bundle/',
			'start_date'     => '17 March 2025',
			'end_date'       => '07 April 2025',
			'notice_message' => 'Enjoy savings of <strong style="font-size: 20px; color:red;"> up to 50% </strong> with our <b>ShopBuilder Elementor Addon</b>, <b>Variation Swatches</b>, <b>Variation Gallery</b>, and <b>Themes</b>!',
		];
		*/
	}
}
