<?php

namespace RadiusTheme\SB\Modules\VariationSwatches;

use RadiusTheme\SB\Helpers\Fns;

/**
 * Handles custom term meta fields for product attributes (color, image, etc.)
 * in WooCommerce taxonomies like `pa_color`, `pa_size`, etc.
 */
class TermMeta {

	/**
	 * The taxonomy to which the term meta fields apply.
	 *
	 * @var string
	 */
	private $taxonomy;

	/**
	 * Post type context (usually 'product').
	 *
	 * @var string
	 */
	private $post_type = 'product';

	/**
	 * Meta field definitions.
	 *
	 * @var array
	 */
	private $fields = [];

	/**
	 * TermMeta constructor.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param array  $fields   Array of meta field definitions.
	 */
	public function __construct( $taxonomy, $fields = [] ) {

		$this->taxonomy = $taxonomy;
		$this->fields   = $fields;

		add_action( 'delete_term', [ $this, 'delete_term' ], 5, 3 );

		// Add form.
		add_action( "{$this->taxonomy}_add_form_fields", [ $this, 'add' ] );
		add_action( "{$this->taxonomy}_edit_form_fields", [ $this, 'edit' ], 10 );
		add_action( 'created_term', [ $this, 'save' ], 10, 3 );
		add_action( 'edit_term', [ $this, 'save' ], 10, 3 );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		// Add columns.
		add_filter( "manage_edit-{$this->taxonomy}_columns", [ $this, 'taxonomy_columns' ] );
		add_filter( "manage_{$this->taxonomy}_custom_column", [ $this, 'taxonomy_column' ], 10, 3 );
	}
	/**
	 * Adds a column for previewing meta fields.
	 *
	 * @param array $columns Original taxonomy columns.
	 * @return array Modified columns.
	 */
	public function taxonomy_columns( $columns ) {
		$new_columns = [];
		if ( isset( $columns['cb'] ) ) {
			$new_columns['cb'] = $columns['cb'];
			unset( $columns['cb'] );
		}
		$new_columns['rtsb-meta-preview'] = '';

		return array_merge( $new_columns, $columns );
	}
	/**
	 * Renders the content for the custom meta preview column.
	 *
	 * @param string $columns Current output (ignored).
	 * @param string $column  Column name.
	 * @param int    $term_id Term ID.
	 * @return void
	 */
	public function taxonomy_column( $columns, $column, $term_id ) {
		$attribute = SwatchesFns::get_wc_attribute_taxonomy( $this->taxonomy );
		$fields    = SwatchesFns::get_taxonomy_meta_fields( $attribute->attribute_type );
		?>
		<style>
			#rtsb-meta-preview {
				width: 30px;
			}
			.rtsb-preview{
				border: 1px solid #000;
				height: 30px;
				width: 30px;
			}
		</style>
		<?php
		switch ( $attribute->attribute_type ) {
			case 'color':
				$key            = $fields[0]['id'];
				$is_dual_key    = isset( $fields[1]['id'] ) ? $fields[1]['id'] : '';
				$dual_color_key = isset( $fields[2]['id'] ) ? $fields[2]['id'] : '';

				$value = sanitize_hex_color( get_term_meta( $term_id, $key, true ) );

				$is_dual = (bool) ( get_term_meta( $term_id, $is_dual_key, true ) === 'yes' );
				if ( $is_dual ) {
					$secondary_color = sanitize_hex_color( get_term_meta( $term_id, $dual_color_key, true ) );
					printf( '<div class="rtsb-preview rtsb-color-preview rtsb-dual-color-preview" style="background: linear-gradient(-45deg, %1$s 0%%, %1$s 50%%, %2$s 50%%, %2$s 100%%);"></div>', esc_attr( $secondary_color ), esc_attr( $value ) );
				} else {
					printf( '<div class="rtsb-preview rtsb-color-preview" style="background-color:%s;"></div>', esc_attr( $value ) );
				}
				break;
			case 'image':
				$key           = $fields[0]['id'];
				$attachment_id = absint( get_term_meta( $term_id, $key, true ) );
				$image         = wp_get_attachment_image_url( $attachment_id );

				printf( '<img src="%s" class="rtsb-preview rtsb-image-preview" />', esc_url( $image ) );
				break;
			case 'button':
			default:
				do_action( 'rtsb/variation/attribute/preview', $term_id, $attribute, $fields );
				break;
		}
	}
	/**
	 * Delete term meta when a term is deleted.
	 *
	 * @param int    $term_id     The term ID.
	 * @param int    $tt_id       Term taxonomy ID.
	 * @param string $taxonomy    Taxonomy slug.
	 */
	public function delete_term( $term_id, $tt_id, $taxonomy ) {
		global $wpdb;

		$term_id = absint( $term_id );

		if ( $term_id && $taxonomy === $this->taxonomy ) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$wpdb->delete( $wpdb->termmeta, [ 'term_id' => $term_id ], [ '%d' ] );
			wp_cache_delete( $term_id, 'term_meta' ); // Optional, clears any remaining cache.
		}
	}
	/**
	 * Enqueues scripts and styles needed for color pickers and media uploads.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
	}
	/**
	 * Saves custom term meta on term creation or update.
	 *
	 * @param int    $term_id  Term ID.
	 * @param int    $tt_id    Term taxonomy ID (optional).
	 * @param string $taxonomy Taxonomy name (optional).
	 * @return void
	 */
	public function save( $term_id, $tt_id = '', $taxonomy = '' ) {
		if ( $taxonomy !== $this->taxonomy ) {
			return;
		}
		if ( ! wp_verify_nonce( Fns::get_nonce(), rtsb()->nonceText ) ) {
			return; // Invalid nonce.
		}
		foreach ( $this->fields as $field ) {
			$field_id   = $field['id'];
			$field_type = $field['type'];
			// If field is not submitted and is a checkbox, remove its meta.
			if ( ! isset( $_POST[ $field_id ] ) ) {
				if ( 'checkbox' === $field_type ) {
					delete_term_meta( $term_id, $field_id );
				}
				continue;
			}

			switch ( $field_type ) {
				case 'text':
				case 'color':
					$post_value = sanitize_text_field( wp_unslash( $_POST[ $field_id ] ) );
					break;
				case 'url':
					$post_value = esc_url_raw( wp_unslash( $_POST[ $field_id ] ) );
					break;
				case 'image':
					$post_value = absint( wp_unslash( $_POST[ $field_id ] ) );
					break;
				case 'textarea':
					$post_value = sanitize_textarea_field( wp_unslash( $_POST[ $field_id ] ) );
					break;
				case 'checkbox':
					$post_value = sanitize_text_field( wp_unslash( $_POST[ $field_id ] ) );
					$post_value = 'yes' === $post_value ? 'yes' : '';
					break;
				case 'editor':
					$post_value = wp_kses_post( wp_unslash( $_POST[ $field_id ] ) );
					break;
				case 'select':
				case 'select2':
					$post_value = sanitize_key( wp_unslash( $_POST[ $field_id ] ) );
					break;
				default:
					$post_value = sanitize_text_field( wp_unslash( $_POST[ $field_id ] ) );
					do_action( 'rtsb/save/term/meta', $term_id, $field, $post_value, $taxonomy );
					break;
			}

			update_term_meta( $term_id, $field_id, $post_value );
		}

		do_action( 'rtsb/after/term/meta/saved', $term_id, $taxonomy );
	}

	/**
	 * Outputs meta fields on the "Add New Term" screen.
	 *
	 * @return void
	 */
	public function add() {
		$this->generate_fields();
	}
	/**
	 * Outputs meta fields on the "Edit Term" screen.
	 *
	 * @param object $term Term object.
	 * @return void
	 */
	public function edit( $term ) {
		$this->generate_fields( $term );
	}
	/**
	 * Generates form fields for add/edit screens.
	 *
	 * @param object|false $term Optional term object for edit context.
	 * @return void
	 */
	private function generate_fields( $term = false ) {
		$screen = get_current_screen();
		if ( ( $screen->post_type == $this->post_type ) && ( $screen->taxonomy == $this->taxonomy ) ) {
			self::generate_form_fields( $this->fields, $term );
		}
	}
	/**
	 * Outputs each field's HTML markup.
	 *
	 * @param array        $fields Field definitions.
	 * @param object|false $term   Term object, or false for "Add New".
	 * @return void
	 */
	public static function generate_form_fields( $fields, $term ) {
		wp_enqueue_script( 'rtsb-variation-swatch-admin' );
		$fields = apply_filters( 'rtsb/term/meta/fields', $fields, $term );

		if ( empty( $fields ) ) {
			return;
		}
		wp_nonce_field( rtsb()->nonceText, rtsb()->nonceId );
		foreach ( $fields as $field ) {

			$field = apply_filters( 'rtsb/term/meta/field', $field, $term );

			$field['id'] = esc_html( $field['id'] );

			if ( ! $term ) {
				$field['value'] = isset( $field['default'] ) ? $field['default'] : '';
			} else {
				$field['value'] = get_term_meta( $term->term_id, $field['id'], true );
			}

			$field['size']        = isset( $field['size'] ) ? $field['size'] : '40';
			$field['required']    = ( isset( $field['required'] ) && true === $field['required'] ) ? ' aria-required="true"' : '';
			$field['placeholder'] = ( isset( $field['placeholder'] ) ) ? ' placeholder="' . $field['placeholder'] . '" data-placeholder="' . $field['placeholder'] . '"' : '';
			$field['desc']        = ( isset( $field['desc'] ) ) ? $field['desc'] : '';

			$field['dependency'] = ( isset( $field['dependency'] ) ) ? $field['dependency'] : [];

			self::field_start( $field, $term );
			switch ( $field['type'] ) {
				case 'text':
				case 'url':
					?>
					<input name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>"
						   type="<?php echo esc_attr( $field['type'] ); ?>"
						   value="<?php echo esc_attr( $field['value'] ); ?>"
						   size="<?php echo esc_attr( $field['size'] ); ?>" <?php echo esc_html( $field['required'] ) . esc_html( $field['placeholder'] ); ?>>
					<?php
					break;
				case 'color':
					?>
					<input name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" type="text"
						   class="rtsb-vs-color-picker" value="<?php echo esc_attr( $field['value'] ); ?>"
						   data-default-color="<?php echo esc_attr( $field['value'] ); ?>"
						   size="<?php echo esc_attr( $field['size'] ); ?>" <?php echo esc_html( $field['required'] ) . esc_html( $field['placeholder'] ); ?>>
					<?php
					break;
				case 'textarea':
					?>
					<textarea name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" rows="5"
							  cols="<?php echo esc_attr( $field['size'] ); ?>" <?php echo esc_attr( $field['required'] ) . esc_attr( $field['placeholder'] ); ?>><?php echo esc_textarea( $field['value'] ); ?></textarea>
					<?php
					break;
				case 'editor':
					$field['settings'] = $field['settings'] ?? [
						'textarea_rows' => 8,
						'quicktags'     => false,
						'media_buttons' => false,
					];
					wp_editor( $field['value'], $field['id'], $field['settings'] );
					break;
				case 'select':
				case 'select2':
					$field['options']  = isset( $field['options'] ) ? $field['options'] : [];
					$field['multiple'] = isset( $field['multiple'] ) ? ' multiple="multiple"' : '';
					$css_class         = ( 'select2' === $field['type'] ) ? 'rtsb-selectwoo' : '';

					?>
					<select name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>"
							class="<?php echo esc_attr( $css_class ); ?>" <?php echo esc_html( $field['multiple'] ); ?>>
						<?php
						foreach ( $field['options'] as $key => $option ) {
							echo '<option' . selected( $field['value'], $key, false ) . ' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
						}
						?>
					</select>
					<?php
					break;
				case 'image':
					?>
					<div class="rtsb-image-wrapper">
						<div class="image-preview">
							<img data-placeholder="<?php echo esc_url( self::placeholder_img_src() ); ?>"
								 src="<?php echo esc_url( self::get_img_src( $field['value'] ) ); ?>" width="60px"
								 height="60px"/>
						</div>
						<div class="button-wrapper">
							<input type="hidden" id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['id'] ); ?>"
								   value="<?php echo esc_attr( $field['value'] ); ?>"/>
							<button type="button"
									class="rtsb-vs-upload-image button button-primary button-small"><?php esc_html_e( 'Upload / Add image', 'shopbuilder' ); ?></button>
							<button type="button"
									style="<?php echo( empty( $field['value'] ) ? 'display:none' : '' ); ?>"
									class="rtsb-vs-remove-image button button-danger button-small"><?php esc_html_e( 'Remove image', 'shopbuilder' ); ?></button>
						</div>
					</div>
					<?php
					break;
				case 'checkbox':
					$label = isset( $field['trigger_label'] ) ? $field['trigger_label'] : $field['label'];
					?>
					<label for="<?php echo esc_attr( $field['id'] ); ?>">

						<input name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>"
							<?php checked( $field['value'], 'yes' ); ?>
							   type="<?php echo esc_attr( $field['type'] ); ?>"
							   value="yes" <?php echo esc_html( $field['required'] ) . esc_html( $field['placeholder'] ); ?>>

						<?php echo esc_html( $label ); ?></label>
					<?php
					break;
				default:
					do_action( 'rtsb/term/meta/field', $field, $term );
					break;

			}
			self::field_end( $field, $term );

		}
	}
	/**
	 * Outputs field start wrapper for add/edit screens.
	 *
	 * @param array        $field Field config.
	 * @param object|false $term  Term object.
	 * @return void
	 */
	private static function field_start( $field, $term ) {
		$depends = empty( $field['dependency'] ) ? '' : "data-rt-depends='" . wp_json_encode( $field['dependency'] ) . "'";
		$classes = [ $field['id'] ];
		if ( isset( $field['class'] ) ) {
			$classes[] = $field['class'];
		}
		if ( ! $term ) {
			?>
			<div <?php echo esc_attr( $depends ); ?> class="form-field <?php echo esc_attr( implode( ' ', $classes ) ); ?> <?php echo empty( $field['required'] ) ? '' : 'form-required'; ?>">
			<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
			<?php
		} else {
			?>
			<tr <?php echo esc_attr( $depends ); ?> class="form-field  <?php echo esc_attr( $field['id'] ); ?> <?php echo empty( $field['required'] ) ? '' : 'form-required'; ?>">
				<th scope="row">
					<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
				</th>
			<td>
			<?php
		}
	}
	/**
	 * Outputs the image source from attachment ID or placeholder.
	 *
	 * @param int|false $thumbnail_id Attachment ID.
	 * @return string Image URL.
	 */
	private static function get_img_src( $thumbnail_id = false ) {
		if ( ! empty( $thumbnail_id ) ) {
			$image = wp_get_attachment_thumb_url( $thumbnail_id );
		} else {
			$image = self::placeholder_img_src();
		}

		return $image;
	}
	/**
	 * Returns a fallback placeholder image URL.
	 *
	 * @return string
	 */
	private static function placeholder_img_src() {
		return 'https://placehold.co/400';
	}
	/**
	 * Outputs closing tags and descriptions for form fields.
	 *
	 * @param array        $field Field config.
	 * @param object|false $term  Term object.
	 * @return void
	 */
	private static function field_end( $field, $term ) {
		if ( ! $term ) {
			?>
			<p><?php echo wp_kses_post( $field['desc'] ); ?></p>
			</div>
			<?php
		} else {
			?>
			<p class="description"><?php echo wp_kses_post( $field['desc'] ); ?></p></td>
			</tr>
			<?php
		}
	}
}

