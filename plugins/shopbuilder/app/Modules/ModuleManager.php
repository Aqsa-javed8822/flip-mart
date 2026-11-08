<?php

namespace RadiusTheme\SB\Modules;

defined( 'ABSPATH' ) || exit();

use RadiusTheme\SB\Models\ModuleList;
use RadiusTheme\SB\Traits\SingletonTrait;

/**
 * Module Manager class.
 *
 * Handles loading and instantiation of all plugin modules.
 */
class ModuleManager {

	use SingletonTrait;

	/**
	 * Module list.
	 *
	 * @var array|null
	 */
	private $module_list;

	/**
	 * Module instances.
	 *
	 * @var array
	 */
	private $module_instances = [];

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'init', [ $this, 'load_modules' ], 15 );
	}

	public function load_modules() {
		if ( ! is_null( $this->module_list ) ) {
			return;
		}
		$this->module_list = ModuleList::instance()->get_list( true, 'active' );
		foreach ( $this->module_list as $module ) {
			if ( isset( $module['active'] ) && $module['active'] !== 'on' ) {
				continue;
			}

			if ( ! empty( $module['package'] ) && $module['package'] === 'pro-disabled' ) {
				continue;
			}

			if ( ! isset( $module['base_class'] ) ) {
				continue;
			}

			if ( isset( $this->module_instances[ $module['base_class'] ] ) ) {
				continue;
			}

			if ( ! class_exists( $module['base_class'] ) ) {
				continue;
			}

			try {
				$module['base_class']::instance();
			} catch ( \Throwable $e ) {
				error_log( 'ShopBuilder module error (' . $module['base_class'] . '): ' . $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				continue;
			}

		}
	}
}
