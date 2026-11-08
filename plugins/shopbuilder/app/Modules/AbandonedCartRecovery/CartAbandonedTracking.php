<?php
/**
 * Sticky Abandoned Cart Recovery Module Class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Modules\AbandonedCartRecovery;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;


defined( 'ABSPATH' ) || exit();

/**
 * Sticky add-to-cart Module Class.
 */
class CartAbandonedTracking {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Add submenu to admin menu.
	 *
	 * @since 1.1.5
	 */
	public function abandoned_cart_tracking_menu(): void {
		$capability = current_user_can( 'manage_woocommerce' ) ? 'manage_woocommerce' : 'manage_options'; // phpcs:ignore WordPress.WP.Capabilities.Unknown
		add_submenu_page(
			'woocommerce',
			__( 'Cart Abandonment', 'shopbuilder' ),
			__( 'Cart Abandonment', 'shopbuilder' ),
			$capability,
			'rtsb-cart-abandonment',
			[ $this, 'render_abandoned_cart_tracking' ]
		);
	}

	/**
	 * Render table view for cart abandonment tracking.
	 *
	 * @since 1.1.5
	 */
	public function render_abandoned_cart_tracking(): void {
		wp_enqueue_script( 'rtsb-admin-app' );
		/**
		 * Styles.
		 */
		wp_enqueue_style( 'rtsb-admin-app' );
		wp_enqueue_style( 'rtsb-fonts' );
		?>
		<div id="rtsb-abandoned-cart-tracking"></div>
		<?php
	}
}
