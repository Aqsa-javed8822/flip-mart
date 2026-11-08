<?php

namespace Aepro\Base;

use Elementor\Controls_Manager;
use Aepro\Aepro;
use Aepro\Classes\AcfMaster;
use Aepro\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class RuleBase {


	protected static $_instances = [];

	public static function instance() {
		if ( empty( static::$_instances[ static::class_name() ] ) ) {
			static::$_instances[ static::class_name() ] = new static();
		}

		return static::$_instances[ static::class_name() ];
	}

	private function __construct() {
		$this->add_actions();
	}

	public static function class_name() {
		return get_called_class();
	}

	public static function is_supported() {
		return true;
	}

	public function get_group() {   }

	public function get_name() {    }

	public function get_title() {   }

	public function get_name_control() {
		return false;
	}

	public function get_value_control() {
		return false;
	}

	public function get_multiple_name_control() {
		return false;
	}

	public function get_multiple_value_control() {
		return false;
	}

	protected function get_rule_operators() {
		$rule_operators = [];

		$rule_operators = [
			'equal'        => __( 'Is Equal', 'ae-pro' ),
			'not_equal'    => __( 'Is Not Equal', 'ae-pro' ),
			'contains'     => __( 'Contains', 'ae-pro' ),
			'not_contains' => __( 'Does Not Contains', 'ae-pro' ),
			'empty'        => __( 'Is Empty', 'ae-pro' ),
			'not_empty'    => __( 'Is Not Empty', 'ae-pro' ),
			'less'         => __( 'Less Than', 'ae-pro' ),
			'greater'      => __( 'Greater Than', 'ae-pro' ),
		];

		return $rule_operators;
	}

	protected function add_operator_control() {

		return [
			'label'   => __( 'Operator', 'ae-pro' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'equal',
			'options' => $this->get_rule_operators(),
		];
	}

	protected function add_actions() {  }

	public function check( $operator, $value, $name = null ) {
	}

	//Left Field is from elementor controls and Right Field is from the current User/Post/Taxonomy/ACF object.
	public function compare( $left_value, $right_value, $operator ) {
		$flag = false;
		switch ( $operator ) {
			case 'equal':
				if ( is_numeric( $left_value ) ) {
					if ( is_null( $left_value ) ) {
						$flag = false;
						break;
					}
				} else {
					if ( empty( $left_value ) ) {
						$flag = false;
						break;
					}
				}

				if ( is_array( $left_value ) ) {
					$count = count( $left_value );
					//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
					if ( $count == 1 ) {
						$left_value = $left_value[0];
					}
				}
				if ( is_array( $right_value ) ) {
					$count = count( $right_value );
					//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
					if ( $count == 1 ) {
						$right_value = $right_value[0];
					}
				}
				//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
				$flag = ( $left_value == $right_value ) ? true : false;
				break;
			case 'not_equal':
				if ( is_numeric( $left_value ) ) {
					if ( is_null( $left_value ) ) {
						$flag = false;
						break;
					}
				} else {
					if ( empty( $left_value ) ) {
						$flag = false;
						break;
					}
				}
				if ( is_array( $left_value ) ) {
					$count = count( $left_value );
					//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
					if ( $count == 1 ) {
						$left_value = $left_value[0];
					}
				}
				if ( is_array( $right_value ) ) {
					$count = count( $right_value );
					//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
					if ( $count == 1 ) {
						$right_value = $right_value[0];
					}
				}
				//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
				$flag = ( $left_value != $right_value ) ? true : false;
				break;
			case 'contains':
				if ( empty( $left_value ) ) {
					$flag = false;
					break;
				}
				if ( is_array( $left_value ) ) {
					if ( is_array( $right_value ) ) {
						if ( count( $left_value ) > count( $right_value ) ) {
							//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
							$flag = ( array_intersect( $right_value, $left_value ) == $right_value ) ? true : false;
						} else {
							//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
							$flag = ( array_intersect( $left_value, $right_value ) == $left_value ) ? true : false;
						}
						break;
					}
					$flag = ( in_array( $right_value, $left_value ) ) ? true : false;
				} else {

					if ( is_array( $right_value ) ) {
						$flag = in_array( $left_value, $right_value ) ? true : false;
						break;
					}
					$flag = is_numeric( ( stripos( strval( $left_value ), strval( $right_value ) ) ) ) ? true : false;
				}
				break;
			case 'not_contains':
				if ( empty( $left_value ) ) {
					$flag = false;
					break;
				}
				if ( is_array( $left_value ) ) {
					if ( is_array( $right_value ) ) {
						if ( count( $left_value ) > count( $right_value ) ) {
							//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
							$flag = ( array_intersect( $right_value, $left_value ) != $right_value ) ? true : false;
						} else {
							//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
							$flag = ( array_intersect( $left_value, $right_value ) != $left_value ) ? true : false;
						}
						break;
					}
					$flag = ( ! in_array( $right_value, $left_value ) ) ? true : false;
				} else {
					if ( is_array( $right_value ) ) {
						$flag = ! in_array( $left_value, $right_value ) ? true : false;
						break;
					}
					$position = stripos( strval( $left_value ), strval( $right_value ) );
					$flag     = is_bool( $position ) ? true : false;
				}
				break;
			case 'empty':
				$flag = ( empty( $left_value ) ) ? true : false;
				break;
			case 'not_empty':
				$flag = ( ! empty( $left_value ) ) ? true : false;
				break;
			case 'less':
				if ( is_numeric( $left_value ) ) {
					if ( is_null( $left_value ) ) {
						$flag = false;
						break;
					}
				} else {
					if ( empty( $left_value ) ) {
						$flag = false;
						break;
					}
				}
				$flag = ( $left_value < $right_value ) ? true : false;
				break;
			case 'greater':
				if ( is_numeric( $left_value ) ) {
					if ( is_null( $left_value ) ) {
						$flag = false;
						break;
					}
				} else {
					if ( empty( $left_value ) ) {
						$flag = false;
						break;
					}
				}
				$flag = ( $left_value > $right_value ) ? true : false;
				break;
			default:
				$flag = $left_value === $right_value;
		}

		$this->rule_log( $left_value, $right_value, $operator, $flag );
		return $flag;
	}

	public function rule_log( $left_value, $right_value, $operator, $result ) {
		return;
		$log_string = $this->get_name() . ' -> ';

		if ( is_array( $left_value ) ) {
			$log_string .= '[ ' . implode( ',', $left_value ) . ' ]';
		} else {
			$log_string .= $left_value;
		}

		$log_string .= ' ' . $operator . ' ';

		if ( is_array( $right_value ) ) {
			$log_string .= '[ ' . implode( ',', $right_value ) . ' ]';
		} else {
			$log_string .= $right_value;
		}

		$log_string .= ' => ' . ( ( $result ) ? ' True' : ' False' );
		//phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		error_log( $log_string );
	}

	public function ae_get_acf_field_value( $name, $value, $parent = null ) {
		global $post;
		$field_value = '';
		if ( is_array( $name ) && array_key_exists( 'parent_type', $name ) ) {
			$parent = $name['parent_type'];
		} else {
			$parent = 'none';
		}
		switch ( $parent ) {
			case 'repeater':
				if ( empty( $name['parent_type'] ) || empty( $name['sub_field'] ) ) {
									return;
				}
				//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
				if ( Frontend::$_in_repeater_block == true ) {
					$field_value = get_sub_field( $name['sub_field'] );
				}
				break;

			case 'group':
				if ( empty( $name['parent_type'] ) || empty( $name['sub_field'] ) ) {
									return;
				}
				$field_data = explode( ':', $name['parent_field'] );
				//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
				if ( isset( $field_data[0] ) && $field_data[0] == 'option' ) {
					$data            = 'option';
					$grp_field_value = get_field( $field_data[1], $data );
					if ( ! empty( $grp_field_value ) ) {
						$field_value = $grp_field_value[ $name['sub_field'] ];
					}
				} else {
					$post            = Aepro::$_helper->get_demo_post_data();
					$data            = $post->ID;
					$grp_field_value = get_field( $name['parent_field'], $data );
					if ( ! empty( $grp_field_value ) ) {
						$field_value = $grp_field_value[ $name['sub_field'] ];
					}
				}
				break;
			case 'none':
				if ( empty( $name ) ) {
									return;
				}
				$field_data = explode( ':', $name );
				//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
				if ( isset( $field_data[0] ) && $field_data[0] == 'options' ) {
					$data        = 'option';
					$field_value = get_field( $field_data[1], $data );
				} else {
					$post        = Aepro::$_helper->get_demo_post_data();
					$data        = $post->ID;
					$field_value = get_field( $name, $data );
				}
				break;
		}
		return $field_value;
	}


}
