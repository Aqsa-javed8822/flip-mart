<?php
/**
 *
 */

namespace RadiusTheme\SB\Traits;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

trait SingletonTrait {
	/**
	 * The single instance of the class.
	 *
	 * @var object
	 */
	protected static $instance = null;


	/**
	 * Constructor
	 *
	 * @return void
	 */
	//	public function __construct() {}

	/**
	 * @return self
	 */
	final public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Prevent cloning.
	 */
	final public function __clone() {
	}

	/**
	 * Prevent unserializing.
	 */
	final public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'shopbuilder' ), '1.0' );
		die();
	}
}
