<?php

namespace Aepro;

use Aepro\Classes\ModuleManager;

class License {


	private static $_instance;

	private static $_store_url = 'https://shop.webtechstreet.com';

	private static $_item_name = 'AnyWhere Elementor Pro';

	private static $_transient_lifetime = 43200;


	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'settings_menu' ] );

		add_action( 'admin_init', [ $this, 'register_license_option' ] );

		add_action( 'wp_ajax_ae_activate_license', [ $this, 'license_update' ] );
	}

	public function settings_menu() {
		add_submenu_page(
			'edit.php?post_type=ae_global_templates',
			__( 'Settings', 'ae-pro' ),
			__( 'Settings', 'ae-pro' ),
			'manage_options',
			'aepro-settings',
			[ $this, 'settings_page' ]
		);
	}

	public function settings_page() {
		$map_key = get_option( 'ae_pro_gmap_api' );
		$template_debug = get_option( 'ae_pro_template_debug', '' );

		$enable_generic = get_option( 'ae_pro_generic_theme' );

		$modules = Aepro::$module_manager->get_modules();

		?>
		<div class="aep-wrap">



			<div class="aep-content-wrapper">

				<div class="aep-settings-main-wrapper">

					<div class="aep-tabs tabs">
						<h3 class="aep-title aep-modules active">
							<a href="#" data-tabid="aep-module-manager">Modules</a>
						</h3>
						<h3 class="aep-title aep-config">
							<a href="#" data-tabid="aep-config">Configuration</a>
						</h3>
					</div>

					<div class="aep-settings-box aep-metabox">

						<div class="aep-metabox-content">

							<form class="aep-tab-content active" id="aep-module-manager" method="post">

								<div class="aep-bulk-action aep-module-row">
									<input type="checkbox" id="aep-select-all" />
									<select name="aep-bulk-action">
										<option value="">Bulk Action</option>
										<option value="activate">Activate</option>
										<option value="deactivate">Deactivate</option>
									</select>
									<input id="aep-apply" class="button" type="button" value="<?php echo esc_html__( 'Apply', 'ae-pro' ); ?>" />
								</div>


								<?php
								$this->core_modules( $modules['core']['modules'] );

								$locked = $this->get_locked_modules();
								if ( ! aepro_is_pro_active() && ! empty( $locked['core'] ) ) {
									$this->locked_modules_group( __( 'General (Pro)', 'ae-pro' ), $locked['core'] );
								}
								?>

								<?php
								if ( isset( $modules['acf']['modules'] ) && is_array( $modules['acf']['modules'] ) ) {
									$this->acf_modules( $modules['acf']['modules'] );
								} elseif ( ! aepro_is_pro_active() && ! empty( $locked['acf'] ) ) {
									$this->locked_modules_group( __( 'Advanced Custom Fields (Pro)', 'ae-pro' ), $locked['acf'] );
								}
								?>

								<?php
								if ( isset( $modules['pods']['modules'] ) && is_array( $modules['pods']['modules'] ) ) {
									$this->pods_module( $modules['pods']['modules'] );
								} elseif ( ! aepro_is_pro_active() && ! empty( $locked['pods'] ) ) {
									$this->locked_modules_group( __( 'Pods (Pro)', 'ae-pro' ), $locked['pods'] );
								}
								?>

								<?php
								if ( isset( $modules['woo']['modules'] ) && is_array( $modules['woo']['modules'] ) ) {
									$this->woo_module( $modules['woo']['modules'] );
								} elseif ( ! aepro_is_pro_active() && ! empty( $locked['woo'] ) ) {
									$this->locked_modules_group( __( 'WooCommerce (Pro)', 'ae-pro' ), $locked['woo'] );
								}
								?>

							</form>

									<form class="aep-tab-content" id="aep-config">
										<div class="aep-config-field">
											<label class="aep-config-field__title" for="ae_pro_gmap_api"><?php esc_html_e( 'Google Map Api Key', 'ae-pro' ); ?></label>
											<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
											<input type="text" name="ae_pro_gmap_api" id="ae_pro_gmap_api" class="regular-text" value="<?php echo $map_key; ?>">
											<p class="aep-config-description">
												<a href="https://qikly.ink/google-map-api" target="_blank" rel="noopener">
													<?php echo esc_attr__( 'Click Here', 'ae-pro' ); ?>
												</a>
												<?php esc_html_e( 'to generate API Key.', 'ae-pro' ); ?>
										</p>
									</div>

										<div class="aep-config-field aep-config-field--switch">
											<span class="aep-config-field__label"><?php esc_html_e( 'Template Debug Metabox', 'ae-pro' ); ?></span>
											<label class="aep-config-switch aep-config-switch--template-debug" for="ae_pro_template_debug">
												<input
													type="checkbox"
													name="ae_pro_template_debug"
													id="ae_pro_template_debug"
												value="1"
												<?php checked( '1', (string) $template_debug ); ?>
											/>
												<span class="aep-config-switch__slider" aria-hidden="true"></span>
												<span class="aep-config-switch__text"><?php esc_html_e( 'Enable', 'ae-pro' ); ?></span>
											</label>
											<p class="aep-config-description"><?php esc_html_e( 'Shows the Template Debug metabox on Dynific Template edit screen.', 'ae-pro' ); ?></p>
										</div>

									<button type="button" value="Save" class="button button-primary" name="save_config" id="save-config" data-action="save-config">
										<span class="aep-action-text">Save</span>
										<span class="aep-action-loading dashicons dashicons-update-alt"></span>
								</button>

							</form>

						</div>

					</div>

				</div>

				<div class="aep-settings-sidebar-wrapper">

					<?php $this->doc_box(); ?>

				</div>

			</div>

		</div>

		<?php
	}

	public function register_license_option() {
		// creates our settings in the options table
		register_setting( 'aepro_edd_license', 'ae_pro_gmap_api', [ $this, 'edd_sanitize_license' ] );
	}

	public function edd_sanitize_license( $new ) {
		return $new;
	}

	protected function license_status() {
		$licence_key = get_option( 'ae_pro_license_key' );
		if ( ! isset( $licence_key ) || empty( $licence_key ) ) {
			// license missing
			return 'missing';
		} else {
			// get transient
			$ae_license_transient = get_site_transient( 'aep_license_status' );
			//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			if ( isset( $ae_license_transient ) && $ae_license_transient != '' ) {
				return $ae_license_transient;
			}

			// check license status
			$license_status = $this->check_license();
			set_site_transient( 'aep_license_status', $license_status, self::$_transient_lifetime );

			return $license_status;
		}
	}

	protected function check_license() {
		$license = get_option( 'ae_pro_license_key' );

		$api_params = [
			'edd_action' => 'check_license',
			'license'    => $license,
			//phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.urlencode_urlencode
			'item_name'  => urlencode( self::$_item_name ),
			'url'        => home_url(),
		];

		// Call the custom API.
		$response = wp_remote_post(
			self::$_store_url,
			[
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			]
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $license_data->license == 'valid' ) {
			return 'valid';
		} else {
			return $license_data->license;
		}
	}

	public function license_update() {
		if ( isset( $_POST['aep_settings_update'] ) ) {
			if ( ! check_ajax_referer( 'aep_license_nonce', 'nonce' ) ) {
				return;
			}

			update_option( 'ae_pro_gmap_api', trim( $_POST['ae_pro_gmap_api'] ) );

			if ( isset( $_POST['enable_generic_theme_support'] ) ) {
				update_option( 'ae_pro_generic_theme', trim( $_POST['enable_generic_theme_support'] ) );
			} else {
				update_option( 'ae_pro_generic_theme', '' );
			}
		}
	}

	static function get_hidden_ae_license_key() {
		$input_string = trim( get_option( 'ae_pro_license_key' ) );

		$start  = 5;
		$length = mb_strlen( $input_string ) - $start - 5;

		$mask_string  = preg_replace( '/\S/', 'X', $input_string );
		$mask_string  = mb_substr( $mask_string, $start, $length );
		$input_string = substr_replace( $input_string, $mask_string, $start, $length );

		return $input_string;
	}

	function core_modules( $modules ) {
		?>
		<div class="aep-module-row aep-module-group">
			<h4 class="aep-group-title"><?php echo __( 'Core', 'aepro' ); ?></h4>
		</div>

		<?php
		foreach ( $modules as $module_key => $module ) {
			if ( isset( $module['hidden'] ) && $module['hidden'] ) {
				continue;
			}
			$class = 'aep-module-row';
			if ( $module['enabled'] === true ) {
				$class      .= ' aep-enabled';
				$action_text = __( 'Deactivate', 'aepro' );
				$action      = 'deactivate';
			} else {
				$class      .= ' aep-disabled';
				$action_text = __( 'Activate', 'aepro' );
				$action      = 'activate';
			}

			?>
			<div class="<?php echo $class; ?>">
				<input class="aep-module-item" type="checkbox" name="aep_modules[]" value="<?php echo $module_key; ?>" />
				<?php echo $module['label']; ?>

				<div class="aep-module-action">
					<a data-action="<?php echo $action; ?>" data-moduleId="<?php echo $module_key; ?>" href="#"> <?php echo $action_text; ?> </a>
				</div>
			</div>
			<?php
		}
	}

	function acf_modules( $modules ) {
		if ( ! is_array( $modules ) ) {
			return;
		}

		$not_available = __( 'Not Available <a title="%s">[?]</a>', 'ae-pro' );
		?>

		<div class="aep-module-row aep-module-group">
			<h4 class="aep-group-title"><?php echo __( 'Advanced Custom Fields', 'aepro' ); ?></h4>
		</div>
		<?php
		foreach ( $modules as $module_key => $module ) {
			if ( isset( $module['hidden'] ) && $module['hidden'] ) {
				continue;
			}
			$class = 'aep-module-row';
			if ( $module['enabled'] === true ) {
				$class      .= ' aep-enabled';
				$action_text = __( 'Deactivate', 'aepro' );
				$action      = 'deactivate';
			} else {
				$class      .= ' aep-disabled';
				$action_text = __( 'Activate', 'aepro' );
				$action      = 'activate';
			}

			?>
			<div class="<?php echo $class; ?>">
				<input class="aep-module-item" type="checkbox" name="aep_modules[]" value="<?php echo $module_key; ?>" />
				<?php echo $module['label']; ?>

				<div class="aep-module-action">
					<?php
					if ( AE_ACF === false ) {
						echo sprintf( $not_available, $module['not-available'] );
					} else {
						if ( ( AE_ACF_PRO === false ) && in_array( $module_key, [ 'acf-gallery', 'acf-repeater', 'acf-flexible-content' ] ) ) {
							echo sprintf( $not_available, $module['not-available'] );
						} else {
							?>
					<a data-action="<?php echo $action; ?>" data-moduleId="<?php echo $module_key; ?>" href="#"> <?php echo $action_text; ?> </a>
							<?php
						}
					}
					?>

				</div>
			</div>
			<?php
		}
	}

	function pods_module( $modules ) {
		if ( ! is_array( $modules ) ) {
			return;
		}

		$not_available = __( 'Not Available <a title="%s">[?]</a>', 'ae-pro' );
		?>

		<div class="aep-module-row aep-module-group">
			<h4 class="aep-group-title"><?php echo __( 'Pods', 'aepro' ); ?></h4>
		</div>
		<?php
		foreach ( $modules as $module_key => $module ) {

			$class = 'aep-module-row';
			if ( $module['enabled'] === true ) {
				$class      .= ' aep-enabled';
				$action_text = __( 'Deactivate', 'aepro' );
				$action      = 'deactivate';
			} else {
				$class      .= ' aep-disabled';
				$action_text = __( 'Activate', 'aepro' );
				$action      = 'activate';
			}

			?>
			<div class="<?php echo $class; ?>">
				<input class="aep-module-item" type="checkbox" name="aep_modules[]" value="<?php echo $module_key; ?>" />
				<?php echo $module['label']; ?>

				<div class="aep-module-action">
					<?php
					if ( AE_PODS === false ) {
						echo sprintf( $not_available, $module['not-available'] );
					} else {
						?>
					<a data-action="<?php echo $action; ?>" data-moduleId="<?php echo $module_key; ?>" href="#"> <?php echo $action_text; ?> </a>
						<?php
					}
					?>

				</div>
			</div>
			<?php
		}
	}

	function woo_module( $modules ) {
		if ( ! is_array( $modules ) ) {
			return;
		}

		$not_available = __( 'Not Available <a title="%s">[?]</a>', 'ae-pro' );
		?>

		<div class="aep-module-row aep-module-group">
			<h4 class="aep-group-title"><?php echo __( 'WooCommerce', 'ae-pro' ); ?></h4>
		</div>
		<?php
		foreach ( $modules as $module_key => $module ) {
			if ( isset( $module['hidden'] ) && $module['hidden'] ) {
				continue;
			}
			$class = 'aep-module-row';
			if ( $module['enabled'] === true ) {
				$class      .= ' aep-enabled';
				$action_text = __( 'Deactivate', 'ae-pro' );
				$action      = 'deactivate';
			} else {
				$class      .= ' aep-disabled';
				$action_text = __( 'Activate', 'ae-pro' );
				$action      = 'activate';
			}

			?>
			<div class="<?php echo $class; ?>">
				<input class="aep-module-item" type="checkbox" name="aep_modules[]" value="<?php echo $module_key; ?>" />
				<?php echo $module['label']; ?>

				<div class="aep-module-action">
					<?php
					if ( AE_WOO === false ) {
						echo sprintf( $not_available, $module['not-available'] );
					} else {
						?>
					<a data-action="<?php echo $action; ?>" data-moduleId="<?php echo $module_key; ?>" href="#"> <?php echo $action_text; ?> </a>
						<?php
					}
					?>

				</div>
			</div>
			<?php
		}
	}

	function misc_modules( $modules ) {

		$not_available = __( 'Not Available <a title="%s">[?]</a>', 'ae-pro' );
		?>

		<div class="aep-module-row aep-module-group">
			<h4 class="aep-group-title"><?php echo __( 'Miscellaneous', 'ae-pro' ); ?></h4>
		</div>

		<?php
		foreach ( $modules as $module_key => $module ) {

			$class = 'aep-module-row';
			if ( $module['enabled'] === true ) {
				$class      .= ' aep-enabled';
				$action_text = __( 'Deactivate', 'ae-pro' );
				$action      = 'deactivate';
			} else {
				$class      .= ' aep-disabled';
				$action_text = __( 'Activate', 'ae-pro' );
				$action      = 'activate';
			}

			?>
			<div class="<?php echo $class; ?>">
				<input class="aep-module-item" type="checkbox" name="aep_modules[]" value="<?php echo $module_key; ?>" />
				<?php echo $module['label']; ?>

				<div class="aep-module-action">
					<?php
					if ( AE_WOO === false ) {
						echo sprintf( $not_available, $module['not-available'] );
					} else {
						?>
					<a data-action="<?php echo $action; ?>" data-moduleId="<?php echo $module_key; ?>" href="#"> <?php echo $action_text; ?> </a>
						<?php
					}
					?>

				</div>
			</div>
			<?php
		}
	}

	private function get_locked_modules() {
		return [
			'core' => [
				'breadcrumb'       => __( 'Breadcrumb', 'ae-pro' ),
				'google-map'       => __( 'CF Google Map', 'ae-pro' ),
				'custom-field'     => __( 'Custom Field', 'ae-pro' ),
				'portfolio'        => __( 'Portfolio', 'ae-pro' ),
				'post-blocks'      => __( 'Post blocks', 'ae-pro' ),
				'post-blocks-adv'  => __( 'Post blocks Adv', 'ae-pro' ),
				'post-readmore'    => __( 'Post Read-More', 'ae-pro' ),
				'searchform'       => __( 'Search Form', 'ae-pro' ),
				'tax-custom-field' => __( 'Taxonomy Custom Field', 'ae-pro' ),
				'taxonomy'         => __( 'Taxonomy', 'ae-pro' ),
				'taxonomy-blocks'  => __( 'Taxonomy Blocks', 'ae-pro' ),
				'dynamic-bg'       => __( 'Dynamic Backgrounds', 'ae-pro' ),
				'dynamic-rules'    => __( 'Dynamic Rules', 'ae-pro' ),
				'bg-slider'        => __( 'Background Slider', 'ae-pro' ),
				'trigger'          => __( 'Trigger', 'ae-pro' ),
				'dynamic-map'      => __( 'Dynamic Map', 'ae-pro' ),
				'import-export'    => __( 'Import - Export <sup><i>Beta</i></sup>', 'ae-pro' ),
				'duplicate-template' => __( 'Duplicate Template <sup><i>Beta</i></sup>', 'ae-pro' ),
			],
			'acf' => [
				'acf-fields'            => __( 'ACF Fields', 'ae-pro' ),
				'acf-fields-v2'         => __( 'ACF Fields V2', 'ae-pro' ),
				'acf-gallery'           => __( 'ACF Gallery', 'ae-pro' ),
				'acf-repeater'          => __( 'ACF Repeater', 'ae-pro' ),
				'acf-flexible-content'  => __( 'ACF Flexible Content', 'ae-pro' ),
				'acf-dynamic'           => __( 'ACF Dynamic Tag', 'ae-pro' ),
			],
			'pods' => [
				'pods-fields' => __( 'Pods Fields', 'ae-pro' ),
			],
			'woo' => [
				'woo'         => __( 'WooCommerce Widgets', 'ae-pro' ),
				'woo-dynamic' => __( 'Woo Dynamic Tag', 'ae-pro' ),
			],
		];
	}

	private function locked_modules_group( $title, $modules ) {
		if ( ! is_array( $modules ) || empty( $modules ) ) {
			return;
		}
		?>
		<div class="aep-module-row aep-module-group">
			<h4 class="aep-group-title"><?php echo $title; ?></h4>
		</div>
		<?php
		foreach ( $modules as $module_key => $label ) {
			?>
			<div class="aep-module-row aep-disabled">
				<input class="aep-module-item" type="checkbox" disabled="disabled" />
				<?php echo $label; ?>
				<div class="aep-module-action">
					<button
						type="button"
						class="aep-locked"
						data-tooltip="<?php esc_attr_e( 'Pro-only feature. Upgrade to unlock.', 'ae-pro' ); ?>"
						data-tooltip-link="<?php echo esc_url( aepro_get_upgrade_url() ); ?>"
						data-tooltip-link-label="<?php esc_attr_e( 'Upgrade', 'ae-pro' ); ?>"
						aria-label="<?php esc_attr_e( 'Pro-only feature. Upgrade to unlock.', 'ae-pro' ); ?>"
					>
						<span class="dashicons dashicons-lock"></span>
					</button>
				</div>
			</div>
			<?php
		}
	}

	function doc_box() {
		$is_pro_active = aepro_is_pro_active();
		?>

		<?php if ( ! $is_pro_active ) : ?>
		<div class="aep-sidebar-box aep-metabox aep-pro-promo-box">
			<div class="aep-metabox-header">
				<div class="aep-title"><?php echo esc_html__( 'Unlock Dynific Pro', 'ae-pro' ); ?></div>
				<div class="aep-new-brand"><?php echo esc_html__( 'Premium Features', 'ae-pro' ); ?></div>
			</div>
			<div class="aep-metabox-content">
				<p>
					<?php echo esc_html__( 'Get advanced template controls, dynamic rules, ACF/PODS/Woo modules, and more to build complete dynamic layouts.', 'ae-pro' ); ?>
				</p>
				<a class="button button-primary ae-upgrade-pro" target="_blank" rel="noopener" title="Upgrade to Pro" href="https://qikly.ink/dynific-addons-landing">
					<?php echo esc_html__( 'Upgrade to Pro', 'ae-pro' ); ?>
				</a>
			</div>
		</div>
		<?php endif; ?>

<div class="aep-sidebar-box aep-metabox">
			<div class="aep-metabox-header">
				<div class="aep-old-title"><del>Anywhere Elementor Pro<del></div>
				<div class="aep-title">Dynific Addons for Elementor</div>
				<div class="aep-new-brand">🎉 New Brand Identity</div>
			</div>
			<div class="aep-metabox-content">
				<p>
					<?php echo esc_html__( "We're excited to announce our rebranding! Same great features, fresh new look and name.", 'ae-pro' ); ?>
				</p>
				<a class="button button-primary ae-rebranding" target="_blank" title="Read Full Story" href="https://qikly.ink/plugin-rebrand">
					<?php echo esc_html__( 'Read Full Story', 'ae-pro' ); ?>&nbsp;&nbsp;&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link" aria-hidden="true"><path d="M15 3h6v6"></path><path d="M10 14 21 3"></path><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path></svg>
				</a>
			</div>
			<!-- <div class="aep-metabox-content">
				<ul>
					<li><a target="_blank" href="https://wpbuilderaddons.com/docs/dynific-addons-elementor/how-to/install/">Activating License</a></li>
					<li><a target="_blank" href="https://wpbuilderaddons.com/docs/dynific-addons-elementor/how-to/">How To's</li>
				</ul>
				<a class="button button-primary ae-support" target="_blank" title="Get Support" href="https://wpvibes.link/go/ea-support/">
					Get Support
				</a>
			</div> -->
		</div>

		<div class="aep-sidebar-box aep-metabox">
			<div class="aep-metabox-content">
				<h3><?php echo esc_html__( 'Need Help?', 'ae-pro' ); ?></h3>
				<p>
					<?php echo esc_html__( "Check our documentation or visit the support forum.", 'ae-pro' ); ?>
				</p>
				<a class="button button-primary ae-documentation" target="_blank" title="Documentation" href="https://qikly.ink/plugin-docs">
					<?php echo esc_html__( 'View Documentation', 'ae-pro' ); ?>
				</a>
			</div>
		</div>

		<?php
	}
}

License::instance();
