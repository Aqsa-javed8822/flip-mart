<?php

namespace RadiusTheme\SB\Abstracts;

// Do not allow directly accessing this file.
use RadiusTheme\SB\Models\DataModel;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Template Related Settings
 */
abstract class AbsSettings {
	/**
	 * @var
	 */
	private $source;

	/**
	 * Set Source
	 */
	public function __construct() {
		$this->source = DataModel::source( $this->source() );
	}

	/**
	 * @return string
	 */
	abstract public function source();

	/**
	 * @param $key
	 * @param $value
	 *
	 * @return void
	 */
	public function set_option( $key, $value ) {
		$this->source->set_option( $key, $value );
	}

	/**
	 * @param $key
	 * @param $default
	 *
	 * @return mixed|null
	 */
	public function get_option( $key, $default = null ) {
		return $this->source->get_option( $key, $default);
	}

	/**
	 * @param $key
	 *
	 * @return true
	 */
	public function delete_option( $key ) {
		return $this->source->delete_option( $key);
	}

}
