<?php

namespace RadiusTheme\SB\Modules\CheckoutEditor;

// Do not allow directly accessing this file.
use RadiusTheme\SB\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * CheckoutEditorInit
 */
class CheckoutEditorBase {

	/**
	 * @var array
	 */
	protected $cache = [];

	/**
	 * Billing Fields Settings
	 */
	protected $options = [];

	/**
	 * Billing Fields Settings
	 */
	protected $billing_settings = [];

	/**
	 * All Custom Fields Here
	 */
	protected $checkout_custom_fields = [];

	/**
	 * Billing Custom Fields
	 */
	protected $billing_custom_field = [];

	/**
	 * Billing Custom Fields
	 */
	protected $shipping_custom_field = [];

	/**
	 * Shippping Field Settings
	 */
	protected $shipping_settings = [];

	/**
	 * Additional Field Settings
	 */
	protected $additional_settings = [];
	/**
	 * Billing Custom Fields
	 */
	protected $additional_custom_field = [];

	/**
	 * Notifications hooks.
	 */
	public function __construct() {
		global $checkout_editor_settings; // Define global variable.
		$this->options = $checkout_editor_settings;
		// If the cached result doesn't exist, fetch it from the database.
		$this->billing_settings    = $this->fields_generator( 'billing' );
		$this->shipping_settings   = $this->fields_generator( 'shipping' );
		$this->additional_settings = $this->fields_generator( 'additional' );
	}
	/**
	 * @param array $settings  Field generator settings.
	 * @param array $address_fields Previous Field.
	 * @return array
	 */
	protected function render_fields( $settings, $address_fields ) {
		$modified_fields = [];
		if ( empty( $settings ) || empty( $address_fields ) ) {
			return $address_fields;
		}
		$item = 1;
		foreach ( $settings as $key => $field ) {
			$field['priority'] = absint( $item ) * 10;
			if ( ! empty( $address_fields[ $key ] ) ) {
				$modified_fields[ $key ] = wp_parse_args( $field, $address_fields[ $key ] );
			} else {
				$modified_fields[ $key ] = $field;
			}
			$item++;
		}
		return $modified_fields;
	}
	/**
	 * Billing Custom Field.
	 *
	 * @return array|mixed
	 */
	private function get_multiselect_fields_value( $data ) {
		if ( empty( $data ) ) {
			return [];
		}
		return array_column( $data, 'value' );
	}
	/**
	 * @param string $group group name.
	 * @return array
	 */
	private function fields_generator( $group ) {
		$cache_key = 'checkout_' . $group . '_fields';
		if ( isset( $this->cache[ $cache_key ] ) ) {
			return $this->cache[ $cache_key ];
		}
		$customize_fields = $this->options[ 'checkout_' . $group . '_fields' ] ?? [];
		$fields           = [];
		if ( ! empty( $customize_fields ) ) {
			$fields = json_decode( stripslashes( $customize_fields ), true );
		}
		$generated_fields = [];
		foreach ( $fields as $field ) {
			if ( empty( $field['name'] ) || 'on' !== ( $field['isEnable'] ?? '' ) ) {
				continue;
			}
			if ( rtsb()->has_pro() && 'specific' === ( $field['fieldVisibility'] ?? '' ) && ! empty( $field['fieldVisibilityRoles'] ) ) {
				$roles = $this->get_multiselect_fields_value( $field['fieldVisibilityRoles'] );
				if ( ! array_intersect( $roles, Fns::get_current_user_roles() ) ) {
					continue;
				}
			}

			$field['required'] = 'on' === ( $field['isRequired'] ?? '' );
			$field['class']    = ! empty( $field['custom_class'] ) ? explode( ',', $field['custom_class'] ) : [];
			$field['class'][]  = 'rtsb-input-field';
			$field['class'][]  = 'rtsb-form-field-type-' . $field['type'];
			if ( ! empty( $field['validation'] ) && 'default' !== $field['validation'] ) {
				$field['validate'] = [ $field['validation'] ];
			}
			if ( 'phone' === $field['name'] ) {
				$field['type'] = 'tel';
			} elseif ( 'country' === $field['name'] ) {
				$field['type']  = 'country';
				$field['value'] = $field['default'] ?? null;
			} elseif ( 'state' === $field['name'] ) {
				$field['type'] = 'state';
			} elseif ( 'email' === $field['name'] ) {
				$field['type'] = 'email';
			}

			if ( rtsb()->has_pro() && 'custom_field' === $field['name'] ) {
				$field_name     = $group . '_' . ( $field['custom_key'] ?? 'custom_field_' . $field['id'] );
				$selected_value = [];
				if ( in_array( $field['type'], [ 'select', 'radio', 'checkboxgroup', 'multiselect' ], true ) ) {
					$options = $field['options'] ?? [];
					unset( $field['options'] );
					$is_multiple = in_array( $field['type'], [ 'checkboxgroup', 'multiselect' ], true );
					foreach ( $options as $option ) {
						$field['options'][ $option['value'] ] = $option['label'];
						if ( $is_multiple ) {
							if ( 'on' === ( $option['isDefault'] ?? 'off' ) ) {
								$selected_value[] = $option['value'];
							}
						} elseif ( empty( $selected_value ) ) {
							$selected_value = 'on' === ( $option['isDefault'] ?? 'off' ) ? $option['value'] : '';
						}
					}
					$field['default'] = $selected_value;
				}
				if ( in_array( $field['type'], [ 'checkbox' ], true ) ) {
					$field['checked_value'] = $field['default'] ?? null;
					$field['value']         = 'on' === $field['isChecked'] ? $field['checked_value'] : 'N/A';
					if ( 'off' === $field['isChecked'] ) {
						$field['default'] = 'N/A';
					}
				}
				if ( in_array( $field['type'], [ 'heading' ], true ) ) {
					unset(
						$field['options'],
						$field['isRequired'],
						$field['required'],
					);
				}

				if ( 'billing' === $group ) {
					$this->billing_custom_field[ $field_name ] = $this->remeove_unused_attr( $field );
				} elseif ( 'shipping' === $group ) {
					$this->shipping_custom_field[ $field_name ] = $this->remeove_unused_attr( $field );
				} elseif ( 'additional' === $group ) {
					$this->additional_custom_field[ $field_name ] = $this->remeove_unused_attr( $field );
				}
				$this->checkout_custom_fields[ $field_name ] = $this->remeove_unused_attr( $field );

			} else {
					$prefix     = 'additional' === $group ? 'order' : $group;
					$field_name = $prefix . '_' . $field['name'];
				unset(
					$field['options']
				);
			}
			$generated_fields[ $field_name ] = $this->remeove_unused_attr( $field );
		}
		$this->cache[ $cache_key ] = $generated_fields;
		return $generated_fields;
	}

	/**
	 * Billing Custom Field.
	 *
	 * @return array|mixed
	 */
	private function remeove_unused_attr( $field ) {
		unset(
			$field['isEnable'],
			$field['id'],
			$field['deletable'],
			$field['validation'],
			$field['custom_key'],
			$field['custom_class'],
			$field['isRequired'],
			$field['name'],
		);
		return $field;
	}
}
