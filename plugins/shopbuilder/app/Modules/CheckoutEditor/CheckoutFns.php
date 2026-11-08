<?php
namespace RadiusTheme\SB\Modules\CheckoutEditor;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
/**
 * CheckoutEditorInit
 */
class CheckoutFns {
	/**
	 * Checkout Billing Field.
	 *
	 * @return array[]
	 */
	public static function default_billing_fields() {
		return [
			[
				'id'           => 1,
				'deletable'    => 'no',
				'isEnable'     => 'on',
				'label'        => esc_html__( 'First Name', 'shopbuilder' ),
				'type'         => 'text',
				'options'      => [],
				'name'         => 'first_name',
				'validation'   => 'default',
				'placeholder'  => esc_html__( 'Enter your first name.', 'shopbuilder' ),
				'isRequired'   => 'on',
				'default'      => '',
				'custom_class' => 'form-row-first',
			],
			[
				'id'           => 2,
				'deletable'    => 'no',
				'isEnable'     => 'on',
				'label'        => esc_html__( 'Last Name', 'shopbuilder' ),
				'type'         => 'text',
				'options'      => [],
				'name'         => 'last_name',
				'isRequired'   => 'on',
				'placeholder'  => esc_html__( 'Last Name', 'shopbuilder' ),
				'default'      => '',
				'validation'   => 'default',
				'custom_class' => 'form-row-last',
			],
			[
				'id'           => 3,
				'deletable'    => 'no',
				'label'        => esc_html__( 'Company Name', 'shopbuilder' ),
				'placeholder'  => '',
				'type'         => 'text',
				'options'      => [],
				'isEnable'     => 'on',
				'name'         => 'company',
				'custom_class' => 'form-row-wide',
			],
			[
				'id'           => 4,
				'deletable'    => 'no',
				'label'        => esc_html__( 'Country', 'shopbuilder' ),
				'name'         => 'country',
				'placeholder'  => '',
				'type'         => 'text',
				'options'      => [],
				'isEnable'     => 'on',
				'custom_class' => 'form-row-wide,address-field,update_totals_on_change',
			],
			[
				'id'           => 5,
				'deletable'    => 'no',
				'label'        => esc_html__( 'Street address', 'shopbuilder' ),
				'type'         => 'text',
				'placeholder'  => esc_html__( 'House number and street name', 'shopbuilder' ),
				'options'      => [],
				'name'         => 'address_1',
				'custom_class' => 'form-row-wide,address-field',
				'isEnable'     => 'on',
			],
			[
				'id'           => 6,
				'deletable'    => 'no',
				'label'        => esc_html__( 'Apartment Address', 'shopbuilder' ),
				'placeholder'  => esc_html__( 'Apartment, suite, unit, etc. (optional)', 'shopbuilder' ),
				'custom_class' => 'form-row-wide,address-field',
				'type'         => 'text',
				'options'      => [],
				'isEnable'     => 'on',
				'name'         => 'address_2',
			],
			[
				'id'           => 7,
				'deletable'    => 'no',
				'label'        => esc_html__( 'City / Town', 'shopbuilder' ),
				'type'         => 'text',
				'placeholder'  => '',
				'options'      => [],
				'isEnable'     => 'on',
				'name'         => 'city',
				'validation'   => 'default',
				'isRequired'   => 'on',
				'custom_class' => 'form-row-wide,address-field',
			],
			[
				'id'           => 8,
				'isEnable'     => 'on',
				'deletable'    => 'no',
				'label'        => esc_html__( 'State/Country', 'shopbuilder' ),
				'type'         => 'text',
				'placeholder'  => '',
				'options'      => [],
				'name'         => 'state',
				'validation'   => 'state',
				'custom_class' => 'form-row-wide,address-field',
			],
			[
				'id'           => 9,
				'deletable'    => 'no',
				'label'        => esc_html__( 'Postcode / ZIP', 'shopbuilder' ),
				'type'         => 'text',
				'placeholder'  => '',
				'options'      => [],
				'isEnable'     => 'on',
				'name'         => 'postcode',
				'validation'   => 'postcode',
				'isRequired'   => 'on',
				'custom_class' => 'form-row-wide,address-field',
			],
			[
				'id'           => 10,
				'deletable'    => 'no',
				'label'        => esc_html__( 'Phone', 'shopbuilder' ),
				'placeholder'  => '',
				'type'         => 'text',
				'options'      => [],
				'isEnable'     => 'on',
				'name'         => 'phone',
				'validation'   => 'phone',
				'isRequired'   => 'on',
				'custom_class' => 'form-row-wide',
			],
			[
				'id'           => 11,
				'deletable'    => 'no',
				'label'        => esc_html__( 'Email Address', 'shopbuilder' ),
				'placeholder'  => '',
				'type'         => 'text',
				'options'      => [],
				'isEnable'     => 'on',
				'name'         => 'email',
				'validation'   => 'email',
				'isRequired'   => 'on',
				'custom_class' => 'form-row-wide',
			],
		];
	}

	/**
	 * Checkout Shipping Field.
	 *
	 * @return array[]
	 */
	public static function default_shipping_fields() {
		$fields = self::default_billing_fields();
		array_splice( $fields, -2 );
		return $fields;
	}

	/**
	 * Checkout Additional Field.
	 *
	 * @return array[]
	 */
	public static function checkout_additional_fields() {
		return [
			[
				'id'           => 1,
				'deletable'    => 'no',
				'isEnable'     => 'on',
				'label'        => esc_html__( 'Order Note', 'shopbuilder' ),
				'type'         => 'textarea',
				'options'      => [],
				'name'         => 'comments',
				'validation'   => 'default',
				'placeholder'  => '',
				'isRequired'   => '',
				'default'      => '',
				'custom_class' => '',
			],
		];
	}
}
