<?php
/**
 * Review class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Controllers\Admin\Notice;

use RadiusTheme\SB\Models\ExtraSettings;
use RadiusTheme\SB\Traits\SingletonTrait;

/**
 * Review class.
 */
class Review {
	/**
	 * Singleton
	 */
	use SingletonTrait;

	/**
	 * Class constructor.
	 */
	private function __construct() {
		add_action( 'admin_init', [ $this, 'check_installation_time' ], 10 );
		add_action( 'admin_init', [ $this, 'notice_actions' ], 5 );
	}

	/**
	 * Check if review notice should be shown or not
	 *
	 * @return void
	 */
	public function check_installation_time() {

		$nobug = get_option( 'rtsb_admin_review_spare_me' );
		$rated = get_option( 'rtsb_admin_review_rated' );

		if ( '1' == $nobug || 'yes' == $rated ) {
			return;
		}

		$install_date = ExtraSettings::instance()->get_option( 'rtsb_plugin_activation_time', false );

		// Bail until the activation time is recorded, otherwise the notice would show immediately.
		if ( ! $install_date ) {
			return;
		}

		$now         = strtotime( 'now' );
		$remind_time = get_option( 'rtsb_admin_review_remind_me' );

		if ( $remind_time ) {
			// User asked to be reminded later: show again 20 days after that click.
			if ( $now < strtotime( '+20 days', $remind_time ) ) {
				return;
			}
		} elseif ( $now < strtotime( '+15 days', $install_date ) ) {
			// First time: show 15 days after installation.
			return;
		}

		add_action( 'admin_notices', [ $this, 'display_admin_notice' ] );
	}

	/**
	 * Remove the notice for the user if review already done or if the user does not want to
	 *
	 * @return void
	 */
	public function notice_actions() {
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'rtsb_notice_nonce' ) ) {
			return;
		}

		if ( ! empty( $_GET['rtsb_admin_review_spare_me'] ) ) {
			$spare_me = absint( $_GET['rtsb_admin_review_spare_me'] );

			if ( 1 == $spare_me ) {
				update_option( 'rtsb_admin_review_spare_me', '1' );
			}
		}

		if ( ! empty( $_GET['rtsb_admin_review_remind_me'] ) ) {
			$remind_me = absint( $_GET['rtsb_admin_review_remind_me'] );

			if ( 1 == $remind_me ) {
				$get_activation_time = strtotime( 'now' );

				update_option( 'rtsb_admin_review_remind_me', $get_activation_time );
			}
		}

		if ( ! empty( $_GET['rtsb_admin_review_rated'] ) ) {
			$rtsb_admin_review_rated = absint( $_GET['rtsb_admin_review_rated'] );

			if ( 1 == $rtsb_admin_review_rated ) {
				update_option( 'rtsb_admin_review_rated', 'yes' );
			}
		}
	}

	/**
	 * @return false|string
	 */
	protected function rtsb_current_admin_url() {
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$uri = preg_replace( '|^.*/wp-admin/|i', '', $uri );

		if ( ! $uri ) {
			return '';
		}

		return remove_query_arg(
			[
				'_wpnonce',
				'_wc_notice_nonce',
				'wc_db_update',
				'wc_db_update_nonce',
				'wc-hide-notice',
				'rtsb_admin_review_spare_me',
				'rtsb_admin_review_remind_me',
				'rtsb_admin_review_rated',
			],
			admin_url( $uri )
		);
	}
	/**
	 * Display Admin Notice, asking for a review
	 **/
	public function display_admin_notice() {
		if ( isset( $GLOBALS['woobundle_notice'] ) || isset( $GLOBALS['rtsb__notice'] ) ) {
			return;
		}
		global $pagenow;

		$exclude = [
			'themes.php',
			'users.php',
			'tools.php',
			'options-general.php',
			'options-writing.php',
			'options-reading.php',
			'options-discussion.php',
			'options-media.php',
			'options-permalink.php',
			'options-privacy.php',
			'admin.php',
			'import.php',
			'export.php',
			'site-health.php',
			'export-personal-data.php',
			'erase-personal-data.php',
		];

		if ( ! in_array( $pagenow, $exclude, true ) ) {
			$args         = [ '_wpnonce' => wp_create_nonce( 'rtsb_notice_nonce' ) ];
			$dont_disturb = add_query_arg( $args + [ 'rtsb_admin_review_spare_me' => '1' ], $this->rtsb_current_admin_url() );
			$remind_me    = add_query_arg( $args + [ 'rtsb_admin_review_remind_me' => '1' ], $this->rtsb_current_admin_url() );
			$rated        = add_query_arg( $args + [ 'rtsb_admin_review_rated' => '1' ], $this->rtsb_current_admin_url() );
			$reviewurl    = 'https://wordpress.org/support/plugin/shopbuilder/reviews/#new-post';
			$plugin_name  = 'ShopBuilder – Elementor WooCommerce Builder Addons';
			?>
			<div class="notice rtsb-review-notice rtsb-review-notice--extended">
				<div class="rtsb-review-notice_content">
					<h3>Enjoying <?php echo esc_html( $plugin_name ); ?>? </h3>
					<p>Thank you for choosing <strong>ShopBuilder</strong>. If you have found our plugin useful and makes you smile, please consider giving us a 5-star rating on WordPress.org. It will help us to grow.</p>
					<div class="rtsb-review-notice_actions">
						<a href="<?php echo esc_url( $reviewurl ); ?>"
						   class="rtsb-review-button rtsb-going-to-review rtsb-review-button--cta" target="_blank"><span>⭐ Yes, You Deserve It!</span></a>
						<a href="<?php echo esc_url( $rated ); ?>"
						   class="rtsb-review-button rtsb-review-button--cta rtsb-review-button--outline"><span>😀 Already Rated!</span></a>
						<a href="<?php echo esc_url( $remind_me ); ?>"
						   class="rtsb-review-button rtsb-review-button--cta rtsb-review-button--outline"><span>🔔 Remind Me Later</span></a>
					</div>
				</div>
			</div>
			<style>
				.rtsb-review-button--cta {
					--e-button-context-color: #4360ef;
					--e-button-context-color-dark: #1f3edc;
					--e-button-context-tint: rgb(75 47 157/4%);
					--e-focus-color: rgb(75 47 157/40%);
				}

				.rtsb-review-notice {
					position: relative;
					margin: 5px 20px 5px 2px;
					border: 1px solid #ccd0d4;
					background: #fff;
					box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
					font-family: Roboto, Arial, Helvetica, Verdana, sans-serif;
					border-inline-start-width: 4px;
				}
				.rtsb-review-notice.notice {
					padding: 0;
				}
				.rtsb-review-notice:before {
					position: absolute;
					top: -1px;
					bottom: -1px;
					left: -4px;
					display: block;
					width: 4px;
					background: -webkit-linear-gradient(0deg, #5d3dfd 0%, #6939c6 100%);
					background: linear-gradient(0deg, #5d3dfd 0%, #6939c6 100%);
					content: "";
				}

				.rtsb-review-notice_content {
					padding: 20px;
				}

				.rtsb-review-notice_actions > * + * {
					margin-inline-start: 8px;
					-webkit-margin-start: 8px;
					-moz-margin-start: 8px;
				}

				.rtsb-review-notice p {
					margin: 0;
					padding: 0;
					line-height: 1.5;
				}

				p + .rtsb-review-notice_actions {
					margin-top: 1rem;
				}

				.rtsb-review-notice h3 {
					margin: 0;
					font-size: 1.0625rem;
					line-height: 1.2;
				}

				.rtsb-review-notice h3 + p {
					margin-top: 8px;
				}

				.rtsb-review-button {
					display: inline-block;
					padding: 0.4375rem 0.75rem;
					border: 0;
					border-radius: 3px;;
					background: var(--e-button-context-color);
					vertical-align: middle;
					text-align: center;
					text-decoration: none !important;
					white-space: nowrap;
				}
				.rtsb-going-to-review {
					color: #fff !important;
				}
				.rtsb-review-button:active {
					background: var(--e-button-context-color-dark);
					color: #fff;
					text-decoration: none;
				}

				.rtsb-review-button:focus {
					outline: 0;
					background: var(--e-button-context-color-dark);
					box-shadow: 0 0 0 2px var(--e-focus-color);
					color: #fff;
					text-decoration: none;
				}

				.rtsb-review-button:hover {
					background: var(--e-button-context-color-dark);
					color: #fff;
					text-decoration: none;
				}

				.rtsb-review-button.focus {
					outline: 0;
					box-shadow: 0 0 0 2px var(--e-focus-color);
				}

				.rtsb-review-button--error {
					--e-button-context-color: #d72b3f;
					--e-button-context-color-dark: #ae2131;
					--e-button-context-tint: rgba(215, 43, 63, 0.04);
					--e-focus-color: rgba(215, 43, 63, 0.4);
				}

				.rtsb-review-button.rtsb-review-button--outline {
					border: 1px solid;
					background: 0 0;
					color: var(--e-button-context-color);
				}

				.rtsb-review-button.rtsb-review-button--outline:focus {
					background: var(--e-button-context-tint);
					color: var(--e-button-context-color-dark);
				}

				.rtsb-review-button.rtsb-review-button--outline:hover {
					background: var(--e-button-context-tint);
					color: var(--e-button-context-color-dark);
				}
			</style>
			<?php
		}
	}
}
