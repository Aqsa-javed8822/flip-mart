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
class CartRecoveryAssets {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Asset Handle
	 *
	 * @var string
	 */
	private $handle = 'rtsb-abandoned-cart-recovery';

	/**
	 * Assets.
	 *
	 * @return void
	 */
	public function frontend_assets() {
		$this->handle = Fns::enqueue_module_assets(
			$this->handle,
			'abandoned-cart-recovery',
			[
				'context' => rtsb(),
				'version' => RTSB_VERSION,
				'type'    => 'js',
			]
		);
		wp_localize_script(
			$this->handle,
			'rtsbCrParams',
			apply_filters(
				'rtsb/cart/recovery/js/object',
				[
					'is_checkout'   => is_checkout(),
					'is_excluded'   => CartRecoveryFns::is_tracking_excluded_for_current_user(),
					'ajax_url'      => WC()->ajax_url(),
					'hasPro'        => rtsb()->has_pro(),
					'session_id'    => Fns::getSession( 'rtsb_ca_session_id' ) ?? md5( uniqid( wp_rand(), true ) ),
					rtsb()->nonceId => wp_create_nonce( rtsb()->nonceText ),
				]
			)
		);
	}
}
