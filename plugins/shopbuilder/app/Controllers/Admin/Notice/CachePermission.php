<?php
/**
 * Cache Permission Notice class.
 *
 * Displays an admin notice when the asset optimization cache directory
 * is not writable, and disables optimization until resolved.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\Admin\Notice;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Cache Permission Notice class.
 */
class CachePermission {

	use SingletonTrait;

	/**
	 * Class constructor.
	 */
	private function __construct() {
		add_action( 'admin_init', [ $this, 'check_cache_dir_permission' ] );
	}

	/**
	 * Check if the cache directory is writable.
	 *
	 * @return void
	 */
	public function check_cache_dir_permission() {
		if ( ! Fns::is_optimization_setting_on() ) {
			return;
		}

		$upload    = wp_upload_dir();
		$cache_dir = trailingslashit( $upload['basedir'] ) . 'shopbuilder_uploads/cache/';

		if ( ! file_exists( $cache_dir ) ) {
			wp_mkdir_p( $cache_dir );
		}

		if ( ! wp_is_writable( $cache_dir ) ) {
			add_action( 'admin_notices', [ $this, 'display_notice' ] );
		}
	}

	/**
	 * Display the admin notice.
	 *
	 * @return void
	 */
	public function display_notice() {
		$upload    = wp_upload_dir();
		$cache_dir = trailingslashit( $upload['basedir'] ) . 'shopbuilder_uploads/cache/';
		?>
		<div class="notice notice-error">
			<p>
				<strong><?php esc_html_e( 'ShopBuilder:', 'shopbuilder' ); ?></strong>
				<?php
				printf(
					/* translators: %s: cache directory path */
					esc_html__( 'The cache directory %s is not writable. The Asset Optimization setting will not work until the server has write permission to this directory. Please contact your hosting provider to fix the file permissions.', 'shopbuilder' ),
					'<code>' . esc_html( $cache_dir ) . '</code>'
				);
				?>
			</p>
		</div>
		<?php
	}
}
