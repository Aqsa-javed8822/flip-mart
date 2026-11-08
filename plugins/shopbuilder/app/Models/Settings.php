<?php

namespace RadiusTheme\SB\Models;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use RadiusTheme\SB\Traits\SingletonTrait;

class Settings {

	use SingletonTrait;

	/**
	 * Public function store.
	 * store data for post
	 *
	 * @since 1.0.0
	 */
	public function get_sections() {

		$sections = [
			'general'  => GeneralList::instance()->get_section(),
			'elements' => ElementList::instance()->get_section(),
			'modules'  => ModuleList::instance()->get_section(),
		];

		return apply_filters( 'rtsb/core/settings/sections', $sections );
	}

	/**
	 * Public function store.
	 * store data for post
	 *
	 * @since 1.0.0
	 */
	public function get_data() {

		$data = [
			'widgets' => [],
			'modules' => ModuleList::instance()->get_data(),
		];

		return apply_filters( 'rtsb/core/settings/data', $data );
	}
}
