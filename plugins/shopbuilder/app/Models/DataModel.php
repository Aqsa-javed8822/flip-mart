<?php
/**
 * DataModel class file.
 *
 * Provides a simple option storage abstraction with
 * source-based singleton instances and optional caching.
 *
 * @package RadiusTheme\SB\Models
 */

namespace RadiusTheme\SB\Models;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Class DataModel
 *
 * Handles grouped WordPress options using a prefixed database key.
 * Supports multiple data sources via singleton instances.
 */
class DataModel {

	/**
	 * Option key used in the WordPress options table.
	 *
	 * @var string
	 */
	private $db_key;

	/**
	 * Cached instances per data source.
	 *
	 * @var array<string, self>
	 */
	private static $instances = [];

	/**
	 * DataModel constructor.
	 *
	 * Private to enforce singleton usage via source().
	 *
	 * @param string $source Data source identifier.
	 */
	private function __construct( $source ) {
		$this->set_db_key( $source );
	}

	/**
	 * Get DataModel instance for a specific source.
	 *
	 * @param string $source Data source name.
	 * @return self
	 */
	public static function source( $source = 'settings' ) {
		if ( ! isset( self::$instances[ $source ] ) ) {
			self::$instances[ $source ] = new self( $source );
		}

		return self::$instances[ $source ];
	}

	/**
	 * Build and set the database option key.
	 *
	 * @param string $source Data source identifier.
	 * @return void
	 */
	private function set_db_key( $source ) {
		$this->db_key = 'rtsb_' . $source;
	}

	/**
	 * Retrieve an option value by key.
	 *
	 * @param string $key     Option key.
	 * @param mixed  $default Default value if option does not exist.
	 * @param bool   $cache   Whether to use global cache.
	 * @return mixed
	 */
	public function get_option( $key, $default = null, $cache = true ) {
		if ( $cache && isset( $GLOBALS[ $this->db_key ] ) ) {
			$db = $GLOBALS[ $this->db_key ];
		} else {
			$db                       = get_option( $this->db_key, [] );
			$GLOBALS[ $this->db_key ] = $db; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		}
		return $db[ $key ] ?? $default;
	}

	/**
	 * Update or create an option value.
	 *
	 * @param string $key   Option key.
	 * @param mixed  $value Option value.
	 * @return bool True on success, false on failure.
	 */
	public function set_option( $key, $value ) {
		$db = get_option( $this->db_key, [] );

		if ( is_object( $db ) ) {
			$db = (array) $db;
		}

		if ( ! is_array( $db ) ) {
			$db = [];
		}

		$db[ $key ] = $value;

		return update_option( $this->db_key, $db );
	}

	/**
	 * Delete an option value by key.
	 *
	 * @param string $key Option key.
	 * @return bool True on success, false on failure.
	 */
	public function delete_option( $key ) {
		$db = get_option( $this->db_key, [] );
		if ( isset( $db[ $key ] ) ) {
			unset( $db[ $key ] );
		}
		return update_option( $this->db_key, $db );
	}
}
