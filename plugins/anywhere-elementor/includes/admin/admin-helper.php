<?php
namespace Aepro\Admin;

use Aepro\Helper;

class AdminHelper {
	private static $_instance;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {
		$this->register_ajax_function();
		add_action( 'do_meta_boxes', [ $this, 'aep_remove_metabox' ], 11 );
		add_filter( 'manage_edit-ae_global_templates_columns', [ $this, 'yoast_seo_admin_remove_columns' ], 10, 1 );
	}

	public function aep_remove_metabox() {
		remove_post_type_support( 'ae_global_templates', 'editor' );
		remove_meta_box( 'wpseo_meta', 'ae_global_templates', 'normal' );
		remove_meta_box( 'rank_math_metabox', 'ae_global_templates', 'normal' );
		remove_meta_box( 'slugdiv', 'ae_global_templates', 'normal' );
		remove_meta_box( 'authordiv', 'ae_global_templates', 'normal' );
		remove_meta_box( 'revisionsdiv', 'ae_global_templates', 'normal' );
		remove_meta_box( 'trackbacksdiv', 'ae_global_templates', 'normal' );
		remove_meta_box( 'commentstatusdiv', 'ae_global_templates', 'normal' );
	}

	public function yoast_seo_admin_remove_columns( $columns ) {
		//RankMath Seo Columns
		unset( $columns['rank_math_title'] );
		unset( $columns['rank_math_seo_details'] );
		unset( $columns['rank_math_description'] );

		//Yoast Seo Columns
		unset( $columns['wpseo-score'] );
		unset( $columns['wpseo-score-readability'] );
		unset( $columns['wpseo-title'] );
		unset( $columns['wpseo-metadesc'] );
		unset( $columns['wpseo-focuskw'] );
		unset( $columns['wpseo-links'] );
		unset( $columns['wpseo-linked'] );
		return $columns;
	}

	private function register_ajax_function() {
		add_action( 'wp_ajax_ae_prev_post', [ $this, 'ae_preview_post' ] );
		add_action( 'wp_ajax_ae_prev_term', [ $this, 'ae_preview_term' ] );
		add_action( 'wp_ajax_ae_acf_repeater_fields', [ $this, 'ae_acf_repeater_fields' ] );
		add_action( 'wp_ajax_ae_acf_flexible_fields', [ $this, 'ae_acf_flexible_fields' ] );
	}


	public function ae_preview_term() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'aep_ajax_nonce' ) ) {
			wp_die();
		}

		$result   = [];
		$q        = $_REQUEST['q'];
		$taxonomy = $_REQUEST['taxonomy'];
		$terms    = get_terms(
			$taxonomy,
			[
				'name__like' => $q,
				'fields'     => 'id=>name',
				'hide_empty' => false,
			]
		);

		foreach ( $terms as $tid => $term ) {
			$result[] = [
				'id'   => $tid,
				'text' => $term,
			];
		}

		wp_send_json_success( $result );
	}

	public function preview_post_filter( $where, $wp_query ) {
		global $wpdb;

		//phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found
		if ( $search_term = $wp_query->get( 'search_post_title' ) ) {
			$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . $wpdb->esc_like( $search_term ) . '%\'';
		}

		return $where;
	}

	public function ae_preview_post() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'aep_ajax_nonce' ) ) {
			wp_die();
		}

		$results    = [];
		$post_types = $_REQUEST['post_type'];
		if ( $post_types === 'any' ) {
			$post_types = get_post_types( [ 'public' => true ], 'names' );

			$skip_post_types = [
				'attachment',
				'ae_global_templates',
				'elementor_library',
			];

			$post_types = array_diff( $post_types, $skip_post_types );
		}

		$query_params = [
			'post_type'         => $post_types,
			'search_post_title' => sanitize_text_field( $_REQUEST['q'] ),
		];

		$params = $query_params;

		add_filter( 'posts_where', [ $this, 'preview_post_filter' ], 10, 2 );
		$query = new \WP_Query( $params );
		remove_filter( 'posts_where', [ $this, 'preview_post_filter' ], 10 );

		foreach ( $query->posts as $post ) {
			$results[] = [
				'id'   => $post->ID,
				'text' => $post->post_title,
			];
		}

		wp_send_json_success( $results );
	}

	/**
	 * Called through ajax
	 */
	public function ae_acf_repeater_fields() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'aep_ajax_nonce' ) ) {
			wp_die();
		}

		$helper = new Helper();
		$loc    = sanitize_text_field( $_REQUEST['repeater_loc'] );

		if ( $loc === 'post' ) {

			$results = [];
			$fields  = $helper->ae_acf_get_field_objects( sanitize_text_field( $_REQUEST['post_id'] ) );

			if ( $fields ) {
				$repeater_fields = $this->get_repeater_field( $fields );

				foreach ( $repeater_fields as $key => $label ) {

					$results[] = [
						'id'   => $key,
						'text' => $label,
					];

				}
			}
		} else {
			$results = [];
			$fields  = $this->get_ae_acf_option_repeater_fields();

			if ( $fields ) {

				foreach ( $fields as $key => $label ) {
					$results[] = [
						'id'   => $key,
						'text' => $label,
					];
				}
			}
		}
		wp_send_json_success( $results );
	}

	public function ae_acf_flexible_fields() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'aep_ajax_nonce' ) ) {
			wp_die();
		}

		$helper = new Helper();
		$loc    = sanitize_text_field( $_REQUEST['repeater_loc'] );

		if ( $loc === 'post' ) {

			$results = [];
			$fields  = $helper->ae_acf_get_field_objects( sanitize_text_field( $_REQUEST['post_id'] ) );

			if ( $fields ) {
				foreach ( $fields as $field_name => $field ) {
					if ( $field['type'] === 'flexible_content' ) {
						$results[] = [
							'id'   => $field['name'],
							'text' => $field['label'],
						];
					}
				}
			}
		} else {
			$results = [];
			$fields  = $helper->get_acf_option_flexible_field();
			if ( $fields ) {
				foreach ( $fields as $field_name => $field ) {
					$results[] = [
						'id'   => $field['name'],
						'text' => $field['label'],
					];
				}
			}
		}
		wp_send_json_success( $results );
	}

	// get option fields for admin dropdown
	public function get_ae_acf_option_repeater_fields() {

		$acf_groups      = acf_get_field_groups();
		$repeater_fields = [];

		foreach ( $acf_groups as $acf_group ) {

			foreach ( $acf_group['location'] as $locations ) {
				foreach ( $locations as $location ) {
					if ( $location['param'] === 'options_page' ) {
						$fields = acf_get_fields( $acf_group );

						if ( $fields ) {
							foreach ( $fields as $field ) {

								if ( $field['type'] === 'repeater' ) {

										$repeater_fields[ $field['name'] ] = $field['label'];
										$repeater_sub_fields               = $this->mine_sub_repeaters( $field['sub_fields'], $field['name'], '- ' );

										$repeater_fields = array_merge( $repeater_fields, $repeater_sub_fields );
								}
							}
						}
					}
				}
			}
		}

		return $repeater_fields;
	}

	public function mine_sub_repeaters( $sub_fields, $parent = '', $prefix = '' ) {

		$repeater_fields          = [];
		$repeater_from_sub_fields = [];

		foreach ( $sub_fields as $field ) {

			if ( $field['type'] === 'repeater' ) {

				if ( $parent !== '' ) {

					$key   = $parent . ':' . $field['name'];
					$label = $prefix . $field['label'];

					$repeater_fields[ $key ]  = $label;
					$repeater_from_sub_fields = $this->mine_sub_repeaters( $field['sub_fields'], $key, $prefix . '- ' );
					$repeater_fields          = array_merge( $repeater_fields, $repeater_from_sub_fields );

				} else {

					$key   = $field['name'];
					$label = $prefix . $field['label'];

					$repeater_fields[ $key ] = $label;
					$repeater_from_subfield  = $this->mine_sub_repeaters(
						$field['sub_fields'],
						$key,
						$prefix
						. '- '
					);
					$repeater_fields         = array_merge( $repeater_fields, $repeater_from_subfield );
				}
			}
		}

		return $repeater_fields;
	}

	public function get_ae_acf_repeater_fields( $post_id ) {

		$helper          = new Helper();
		$repeater_fields = [];

		$fields = $helper->ae_acf_get_field_objects( $post_id );

		$repeater_fields = $this->get_repeater_field( $fields );

		return $repeater_fields;
	}

	public function get_repeater_field( $fields, $parent = '', $prefix = '' ) {

		$repeater_fields        = [];
		$repeater_from_subfield = [];

		if ( $fields ) {
			foreach ( $fields as $field_name => $field ) {

				if ( $field['type'] === 'repeater' ) {
					if ( $parent !== '' ) {
						$key   = $parent . ':' . $field['name'];
						$label = $prefix . $field['label'];
					} else {
						$key   = $field['name'];
						$label = $prefix . $field['label'];
					}

					$repeater_fields[ $key ] = $label;

					$repeater_from_subfield = $this->get_repeater_field(
						$field['sub_fields'],
						$key,
						$prefix
						. '- '
					);
					$repeater_fields        = array_merge( $repeater_fields, $repeater_from_subfield );

				}
			}
		}

		return $repeater_fields;
	}



	public function get_ae_acf_option_flexible_fields() {
		$helper          = new Helper();
		$repeater_fields = [];
		$fields          = $helper->get_acf_option_flexible_field();
		if ( $fields ) {
			foreach ( $fields as $field_name => $field ) {
				if ( $field['type'] === 'flexible_content' ) {
					$repeater_fields[ $field_name ] = $field['label'];
				}
			}
		}
		return $repeater_fields;
	}

	public function get_ae_acf_flexible_fields( $post_id ) {

		$helper          = new Helper();
		$repeater_fields = [];
		$fields          = $helper->ae_acf_get_field_objects( $post_id );

		if ( $fields ) {
			foreach ( $fields as $field_name => $field ) {
				if ( $field['type'] === 'flexible_content' ) {
					$repeater_fields[ $field['name'] ] = $field['label'];
				}
			}
		}

		return $repeater_fields;
	}

	public function render_dropdown( $choices, $selected = '' ) {

		if ( count( $choices ) ) {

			foreach ( $choices as $key => $label ) {
				$option_label = $label;
				$disabled     = false;
				if ( is_array( $label ) ) {
					$option_label = isset( $label['label'] ) ? $label['label'] : '';
					$disabled     = ! empty( $label['disabled'] );
				}
				//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
				if ( $key == $selected ) {
					?>
					<option selected value="<?php echo esc_attr( $key ); ?>"<?php echo $disabled ? ' disabled' : ''; ?>><?php echo esc_attr( $option_label ); ?></option>
					<?php
				} else {
					?>
					<option value="<?php echo esc_attr( $key ); ?>"<?php echo $disabled ? ' disabled' : ''; ?>><?php echo esc_attr( $option_label ); ?></option>
					<?php
				}
			}
		}
	}

	public function render_optgroup_dropdown( $groups, $selected = '' ) {

		if ( count( $groups ) ) {
			foreach ( $groups as $group ) {

				if ( isset( $group['options'] ) && count( $group['options'] ) ) {
					?>
					<optgroup label="<?php echo esc_attr( $group['label'] ); ?>">

						<?php
						foreach ( $group['options'] as $option => $label ) {
							//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
							if ( $option == $selected ) {
								?>
									<option selected value="<?php echo esc_attr( $option ); ?>"><?php echo esc_attr( $label ); ?></option>
									<?php
							} else {
								?>
									<option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_attr( $label ); ?></option>
									<?php
							}
						}
						?>

					</optgroup>
					<?php
				}
			}
		}
	}

	public function render_checkbox( $name, $choices, $selected ) {

		if ( count( $choices ) ) {
			?>
			<ul class="ae-checkbox-list">
			<?php
			foreach ( $choices as $key => $label ) {
				?>
				<li>
					<label>
						<?php
						if ( in_array( $key, $selected, true ) ) {
							?>
								<input type="checkbox" checked value="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>" />
								<?php
						} else {
							?>
								<input type="checkbox" value="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>" />
								<?php
						}
						?>
						<?php echo esc_attr( $label ); ?>
					</label>
				</li>
				<?php
			}
			?>
			</ul>
			<?php

		}
	}

	public function kses_svg_ruleset() {
		$kses_defaults = wp_kses_allowed_html( 'post' );

		$svg_args = [
			'svg'   => [
				'class'           => true,
				'aria-hidden'     => true,
				'aria-labelledby' => true,
				'role'            => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true, // <= Must be lower case!
			],
			'style' => [ 'type' => true ],
			'g'     => [ 'fill' => true ],
			'title' => [ 'title' => true ],
			'path'  => [
				'd'     => true,
				'fill'  => true,
				'class' => true,
			],
		];
		return array_merge( $kses_defaults, $svg_args );
	}
}

AdminHelper::instance();
