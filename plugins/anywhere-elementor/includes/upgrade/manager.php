<?php
namespace Aepro\Upgrade;

use Elementor\Core\Upgrade\Manager as Upgrades_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Manager extends Upgrades_Manager {

	public function get_action() {
		return 'aepro_updater';
	}

	public function get_plugin_name() {
		return 'ae-pro';
	}

	public function get_plugin_label() {
		return __( 'Dynific Addons for Elementor Pro', 'ae-pro' );
	}

	public function get_new_version() {
		return AE_PRO_VERSION;
	}

	public function get_updater_label() {
		return esc_html__( 'Dynific Addons for Elementor Pro Data Updater', 'ae-pro' );
	}

	public function get_version_option_name() {
		return 'aepro_version';
	}

	public function get_upgrades_class() {
		return 'Aepro\Upgrade\Upgrades';
	}

	public function should_upgrade() {

		$current_version = $this->get_current_version();

		// It's a new install or first time upgrade
		if ( ! $current_version ) {
			update_option( $this->get_version_option_name(), 0.1 );
		}

		return version_compare( $this->get_new_version(), $current_version, '>' );
	}

	protected function update_db_version() {
		update_option( $this->get_version_option_name(), $this->get_new_version() );
	}
}
