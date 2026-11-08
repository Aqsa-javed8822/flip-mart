<?php

namespace RadiusTheme\SB\Modules\Compare;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}


use RadiusTheme\SB\Models\ExtraSettings;
use RadiusTheme\SB\Traits\SingletonTrait;

final class Compare {

	/**
	 * @var array
	 */
	public $settings = [];

	private $compare_db_key = 'rtsb_compare_db_version';
	private $compare_db_version = '1.0.0';

	use SingletonTrait;

	function __construct() {

		if( ! ExtraSettings::instance()->get_option( $this->compare_db_key, get_option( $this->compare_db_key, false ) ) ){
			add_action( 'wp_loaded', [ $this, 'activate' ] );
			ExtraSettings::instance()->set_option( $this->compare_db_key, $this->compare_db_version );
		}

		//if ( ! get_option( $this->compare_db_key, '' ) ) {
		//	add_action( 'wp_loaded', [ $this, 'activate' ] );
		//	update_option( $this->compare_db_key, $this->compare_db_version );
		//}

		CompareFrontEnd::instance();

		new CompareRouteV1();

		if ( is_admin() ) {
			CompareAdmin::instance();
		}

		do_action( 'rtsb/module/compare/loaded' );
	}

	/**
	 * Do stuff upon plugin activation
	 *
	 * @return void
	 */
	public function activate() {
		( new CompareInstaller() )->run();
	}

}