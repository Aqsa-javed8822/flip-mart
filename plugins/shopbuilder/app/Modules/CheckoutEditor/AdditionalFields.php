<?php

namespace RadiusTheme\SB\Modules\CheckoutEditor;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use PhpParser\Node\Expr\Empty_;
use RadiusTheme\SB\Traits\SingletonTrait;

/**
 * CheckoutEditorInit
 */
class AdditionalFields extends CheckoutEditorBase {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Notifications hooks.
	 */
	private function __construct() {
		parent::__construct();
		add_filter( 'woocommerce_checkout_fields', [ $this, 'additional_fields_generator' ], 9999 );
	}

	/**
	 * @param array $fields fields.
	 * @return array
	 */
	public function additional_fields_generator( $fields ) {
		$fields['order'] = $this->render_fields( $this->additional_settings, $fields['order'] );
		return $fields;
	}
}
