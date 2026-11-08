<?php
/**
 * Admin Init.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\Admin;

defined( 'ABSPATH' ) || exit();

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Traits\SingletonTrait;
use RadiusTheme\SB\Controllers\Admin\Ajax as Ajax;

/**
 * Admin Init.
 */
class AdminInit {
	/**
	 * Parent Menu Page Slug
	 */
	const MENU_PAGE_SLUG = 'rtsb';

	/**
	 * Menu capability
	 */
	const MENU_CAPABILITY = 'manage_options';

	/**
	 * Parent Menu Hook
	 *
	 * @var string
	 */
	public static $parent_menu_hook = '';

	use SingletonTrait;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->init();
		$this->ajax_actions();
		$this->upgrade();
	}

	/**
	 * Admin Ajax hooks.
	 *
	 * @return void
	 */
	public function ajax_actions() {
		Ajax\DefaultTemplate::instance();
		if ( defined( 'ELEMENTOR_VERSION' ) ) { // Elementor Check.
			Ajax\CreateTemplate::instance();
		}
		Ajax\ModalTemplate::instance();
		Ajax\AdminSettings::instance();
	}

	/**
	 * Upgrade Notice.
	 *
	 * @return void
	 */
	public function upgrade() {
		Notice\Upgrade::instance();
        // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
		// Notice\BFDiscount::instance();.
        // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
		// Notice\BlackFridayV2::instance();.
		Notice\Review::instance();
		Notice\CachePermission::instance();
	}

	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', [ $this, 'add_menu' ], 25 );
		add_action( 'in_admin_header', [ $this, 'in_admin_header_functionality' ], 1000 );
		// PluginRow registered earlier in ShopBuilder::init() so it survives dependency failures.
	}

	/**
	 * Add menu.
	 *
	 * @return void
	 */
	public function add_menu() {
		self::$parent_menu_hook = add_menu_page(
			esc_html__( 'ShopBuilder', 'shopbuilder' ),
			esc_html__( 'ShopBuilder', 'shopbuilder' ),
			self::MENU_CAPABILITY,
			self::MENU_PAGE_SLUG,
			null,
			RTSB_URL . '/assets/images/icon/shopbuilder-logo-white.svg',
			'55.6'
		);

		add_submenu_page(
			self::MENU_PAGE_SLUG,
			esc_html__( 'Settings', 'shopbuilder' ),
			esc_html__( 'Settings', 'shopbuilder' ),
			self::MENU_CAPABILITY,
			'rtsb-settings',
			[ $this, 'settings_page' ],
		);
		add_submenu_page(
			self::MENU_PAGE_SLUG,
			esc_html__( 'Get Help', 'shopbuilder' ),
			esc_html__( 'Get Help', 'shopbuilder' ),
			self::MENU_CAPABILITY,
			'rtsb-get-help',
			[ $this, 'get_help_page' ],
		);
		add_submenu_page(
			self::MENU_PAGE_SLUG,
			esc_html__( 'Themes', 'shopbuilder' ),
			esc_html__( 'Themes', 'shopbuilder' ),
			self::MENU_CAPABILITY,
			'rtsb-themes',
			[ $this, 'get_themes_page' ],
		);
		do_action( 'rtsb/add/more/submenu', self::MENU_PAGE_SLUG, self::MENU_CAPABILITY );

		// Remove Parent Submenu.
		remove_submenu_page( self::MENU_PAGE_SLUG, self::MENU_PAGE_SLUG );
	}

	/**
	 * Redirect to content.
	 *
	 * @return void
	 */
	public function redirect_to_content() {
		wp_redirect( admin_url( 'admin.php?page=rtsb-settings' ) ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
	}

	/**
	 * Settings Page.
	 *
	 * @return void
	 */
	public function settings_page() {
		?>
		<div class="wrap rtsb-admin-wrap">
			<div id="rtsb-admin-app"></div>
		</div>
		<?php
	}

	/**
	 * Help Page.
	 *
	 * @return void
	 */
	public function get_help_page() {
		Fns::renderView( 'help' );
	}

	/**
	 * Themes Page.
	 *
	 * @return void
	 */
	public function get_themes_page() {
		Fns::renderView( 'themes' );
	}

	/**
	 * Admin Header
	 */
	public function in_admin_header_functionality() {
		$screen            = get_current_screen();
		$isBuilderTemplate = 'edit-rtsb_builder' === $screen->id;
		$pages             = [
			'shopbuilder_page_rtsb-get-help',
			'shopbuilder_page_rtsb-settings',
			'shopbuilder_page_rtsb-license',
			'shopbuilder_page_rtsb-themes',
		];
		$isSettingsPage    = in_array( $screen->base, $pages, true );
		if ( $isBuilderTemplate || $isSettingsPage ) {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}

		$load_elementor_scripts = Fns::should_load_elementor_scripts();

		if ( $isBuilderTemplate ) {
			if ( ! defined( 'ELEMENTOR_VERSION' ) && $load_elementor_scripts ) {
				?>
				<div class='rtsb-message-for-elementor-missing'>
					<div class="rtsb-builder-notice-content">
						<?php
						echo sprintf(
							/* translators: %s: Elementor */
							esc_html__( 'To build your WooCommerce pages, please Install and Activate the %s Plugin.', 'shopbuilder' ),
							'<a href="' . esc_url( admin_url( 'plugin-install.php?s=elementor&tab=search&type=term' ) ) . '" target="_blank">' . esc_html__( 'Elementor Website Builder', 'shopbuilder' ) . '</a>'
						);
						?>
					</div>
				</div>
				<?php
			} elseif ( defined( 'ELEMENTOR_VERSION' ) && ! $load_elementor_scripts ) {
				?>
				<div class='rtsb-message-for-elementor-missing'>
					<div class="rtsb-builder-notice-content">
					<?php esc_html_e( 'Elementor is installed, but script loading is disabled. Please enable "Load Elementor Scripts" in the Advanced Optimization Settings.', 'shopbuilder' ); ?>
					</div>
				</div>
				<?php
			} elseif ( ! defined( 'ELEMENTOR_VERSION' ) && ! $load_elementor_scripts ) {
				?>
				<div class='rtsb-message-for-elementor-missing'>
					<div class="rtsb-builder-notice-content">
						<?php
						echo sprintf(
						/* translators: %s: Elementor */
							esc_html__( 'To build your WooCommerce pages, please Install and Activate the %s Plugin.', 'shopbuilder' ),
							'<a href="' . esc_url( admin_url( 'plugin-install.php?s=Elementor%2520Website%2520Builder&tab=search&type=term' ) ) . '" target="_blank">' . esc_html__( 'Elementor Website Builder', 'shopbuilder' ) . '</a>'
						);
						?>
					</div>
				</div>
				<?php
			}
		}
	}
}
