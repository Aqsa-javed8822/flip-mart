<?php

namespace RadiusTheme\SB\Models;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use RadiusTheme\SB\Abstracts\AbsSettings;
use RadiusTheme\SB\Traits\SingletonTrait;

/**
 * Template Related Settings
 */
class TemplateSettings extends AbsSettings {

	/**
	 * Singleton
	 */
	use SingletonTrait;

	/**
	 * @return string
	 */
	public function source() {
		return 'template_settings';
	}

}
