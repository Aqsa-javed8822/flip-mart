<?php

namespace Aepro\Admin;

use Aepro\Helper;
use Aepro\Admin\AdminHelper;

class TemplateConfig {

	private $fields = [];

	public function __construct() {

		add_action( 'add_meta_boxes', [ $this, 'config_box' ], 10 );

		add_action( 'save_post', [ $this, 'save_config' ] );

		$this->field_keys();
	}

	public function config_box() {

		add_meta_box(
			'aep_config_box',
				__( 'Dynific Template Settings', 'ae-pro' ),
			[ $this, 'render_config_box' ],
			'ae_global_templates',
			'normal',
			'high'
		);
	}

	public function field_keys() {

		$this->fields = [

			'ae_render_mode' => [
				'multi' => false,
			],
			'ae_usage' => [
				'multi' => false,
			],
			'ae_custom_usage_area' => [
				'multi' => false,
			],
			'ae_rule_post_type' => [
				'multi' => false,
			],
			'ae_preview_post_ID' => [
				'multi' => false,
			],
			'ae_rule_taxonomy' => [
				'multi' => false,
			],
			'ae_preview_term' => [
				'multi' => false,
			],
			'ae_preview_author' => [
				'multi' => false,
			],
			'ae_rule_post_type_archive' => [
				'multi' => false,
			],
			'ae_apply_global' => [
				'multi' => false,
			],
			'ae_full_override' => [
				'multi' => false,
			],
			'ae_elementor_template' => [
				'multi' => false,
			],
			'ae_acf_repeater_name' => [
				'multi' => false,
			],
			'ae_acf_flexible_name' => [
				'multi' => false,
			],

			'ae_hook_apply_on' => [
				'multi' => true,
			],
			'ae_hook_post_types' => [
				'multi' => true,
			],
			'ae_hook_taxonomies' => [
				'multi' => true,
			],
			'ae_hook_posts_selected' => [
				'multi' => false,
			],
			'ae_hook_posts_excluded' => [
				'multi' => false,
			],
			'ae_hook_terms_selected' => [
				'multi' => false,
			],
			'ae_hook_terms_excluded' => [
				'multi' => false,
			],
			'ae_repeater_loc' => [
				'multi' => false,
			],
			'ae_acf_field_type' => [
				'multi' => false,
			],

		];
	}

	protected function render_help_label( $for, $label, $tooltip = '' ) {
		$learn_more_url = admin_url( 'admin.php?page=aepro-settings' );
		?>
		<label for="<?php echo esc_attr( $for ); ?>">
			<?php echo esc_html( $label ); ?>
			<?php if ( $tooltip ) : ?>
				<span
					class="aep-help-dot"
					tabindex="0"
					role="button"
					data-tooltip="<?php echo esc_attr( $tooltip ); ?>"
					data-tooltip-link="<?php echo esc_url( $learn_more_url ); ?>"
					data-tooltip-link-label="<?php echo esc_attr__( 'Learn More', 'ae-pro' ); ?>"
					aria-label="<?php echo esc_attr( $tooltip ); ?>"
				>?</span>
			<?php endif; ?>
		</label>
		<?php
	}

	public function render_config_box( $post ) {

		$helper = new Helper();

		$admin_helper = AdminHelper::instance();
		$render_mode_choices = TemplateSettingsSchema::get_render_mode_choices();
		$usage_areas         = TemplateSettingsSchema::get_usage_area_choices();
		$post_types          = TemplateSettingsSchema::get_post_type_choices();
		$taxonomies          = TemplateSettingsSchema::get_taxonomy_choices();

		$preview_post = $helper->get_saved_preview_post();
		$preview_term = $helper->get_saved_preview_term();

		$count_users = count_users();
		if($count_users['total_users'] > 100){
			$authors = [];
		}else{
			$authors = $helper->get_author_list();
		}

		$post_type_archives = TemplateSettingsSchema::get_post_type_archive_choices();
		$page_layouts       = TemplateSettingsSchema::get_page_layout_choices();

		$saved_meta = $this->get_saved_meta( $post->ID );

		// Rules Section Data
		$apply_on = [
			'single'  => 'Single Post',
			'archive' => 'Archive',
			'search'  => 'Search',
			'404'     => '404',
			'home'    => 'Home',
		];

		$repeater_locations = TemplateSettingsSchema::get_repeater_location_choices();
		$acf_field_type     = TemplateSettingsSchema::get_acf_field_type_choices();

		$repeater_loc = ( ! isset( $saved_meta['ae_repeater_loc'] ) || $saved_meta['ae_repeater_loc'] === '' ) ? 'post' : $saved_meta['ae_repeater_loc'];

		$saved_acf_field_type = ( ! isset( $saved_meta['ae_acf_field_type'] ) || $saved_meta['ae_acf_field_type'] === '' ) ? 'repeater' : $saved_meta['ae_acf_field_type'];

		?>

		<div class="ae-config-wrapper">
			<ul class="ae-config-nav">
				<li aria-selected="true">
					<a href="#ae-config-general">General</a>
				</li>
				<li class="ae-rules" aria-selected="false">
					<a href="#ae-config-rules">Rules</a>
				</li>
			</ul>
			<div class="ae-config-content-wrapper">
				<div id="ae-config-general" class="ae-config-content" aria-hidden="true">

					<div class="f-row">
						<div class="ae-desc">
							<?php $this->render_help_label( 'ae_render_mode', __( 'Template Type', 'ae-pro' ), __( 'Select where and how this template should be used.', 'ae-pro' ) ); ?>
						</div>
						<div class="ae-control">
							<select name="ae_render_mode" id="ae_render_mode">
								<?php $admin_helper->render_dropdown( $render_mode_choices, $saved_meta['ae_render_mode'] ); ?>
							</select>
							<div id="aep-render-mode-pro-notice" class="aep-pro-notice" hidden>
								<p class="aep-pro-notice__title"><?php echo esc_html__( 'This render mode is available in Pro.', 'ae-pro' ); ?></p>
								<p class="aep-pro-notice__desc"><?php echo esc_html__( 'Upgrade to Pro to unlock advanced render modes and features.', 'ae-pro' ); ?></p>
								<p class="aep-pro-notice__cta">
									<a class="button button-primary" href="<?php echo esc_url( aepro_get_upgrade_url() ); ?>" target="_blank" rel="noopener">
										<?php echo esc_html__( 'Upgrade to Pro', 'ae-pro' ); ?>
									</a>
								</p>
							</div>
						</div>
					</div>

					<div class="f-row">
						<div class="ae-desc">
							<label for="ae_usage"><?php echo esc_html__( 'Usage Area', 'ae-pro' ); ?></label>
						</div>
						<div class="ae-control">
							<select name="ae_usage" id="ae_usage">
								<?php $admin_helper->render_dropdown( $usage_areas, $saved_meta['ae_usage'] ); ?>
							</select>
						</div>
					</div>

					<div class="f-row">
						<div class="ae-desc">
							<label for="ae_custom_usage_area"><?php echo esc_html__( 'Custom Usage Area', 'ae-pro' ); ?></label>
						</div>
						<div class="ae-control">
							<input type="text" name="ae_custom_usage_area" id="ae_custom_usage_area" value="<?php echo esc_attr( $saved_meta['ae_custom_usage_area'] ); ?>" />
						</div>
					</div>

					<div class="f-row">
						<div class="ae-desc">
							<?php $this->render_help_label( 'ae_rule_post_type', __( 'Post Type', 'ae-pro' ), __( 'Choose the post type this template should target.', 'ae-pro' ) ); ?>
						</div>
						<div class="ae-control">
							<?php
							// Free version: disable CPTs — only 'post' and 'page' are allowed.
							$free_post_types      = [ 'post', 'page' ];
							$is_pro_active        = aepro_is_pro_active();
							$post_types_for_metabox = [];
							foreach ( $post_types as $pt_key => $pt_label ) {
								if ( ! $is_pro_active && ! in_array( $pt_key, $free_post_types, true ) ) {
									$post_types_for_metabox[ $pt_key ] = [
										'label'    => $pt_label . ' — Pro',
										'disabled' => true,
									];
								} else {
									$post_types_for_metabox[ $pt_key ] = $pt_label;
								}
							}
							?>
							<select name="ae_rule_post_type" id="ae_rule_post_type">
								<?php $admin_helper->render_dropdown( $post_types_for_metabox, $saved_meta['ae_rule_post_type'] ); ?>
							</select>
							<?php if ( ! $is_pro_active ) : ?>
								<p class="description">
									<?php echo esc_html__( 'Custom post types require Pro.', 'ae-pro' ); ?>
									<a href="<?php echo esc_url( aepro_get_upgrade_url() ); ?>" target="_blank" rel="noopener"><?php echo esc_html__( 'Upgrade', 'ae-pro' ); ?></a>
								</p>
							<?php endif; ?>
						</div>
					</div>

					<div class="f-row">
						<div class="ae-desc">
							<?php $this->render_help_label( 'ae_repeater_loc', __( 'Field Location', 'ae-pro' ), __( 'Choose where ACF fields are read from.', 'ae-pro' ) ); ?>
						</div>
						<div class="ae-control">
							<select name="ae_repeater_loc" id="ae_repeater_loc">
								<?php $admin_helper->render_dropdown( $repeater_locations, $repeater_loc ); ?>
							</select>
						</div>
					</div>

					<div class="f-row">
						<div class="ae-desc">
							<?php $this->render_help_label( 'ae_acf_field_type', __( 'Field Type', 'ae-pro' ), __( 'Choose Repeater or Flexible Content field mode.', 'ae-pro' ) ); ?>
						</div>
						<div class="ae-control">
							<select name="ae_acf_field_type" id="ae_acf_field_type">
								<?php $admin_helper->render_dropdown( $acf_field_type, $saved_acf_field_type ); ?>
							</select>
						</div>
					</div>

					<div class="f-row">
						<div class="ae-desc">
							<?php $this->render_help_label( 'ae_preview_post_ID', __( 'Preview Post', 'ae-pro' ), __( 'Used only for editor preview context.', 'ae-pro' ) ); ?>
						</div>
						<div class="ae-control">
							<select name="ae_preview_post_ID" id="ae_preview_post_ID">
								<?php $admin_helper->render_dropdown( $preview_post, $saved_meta['ae_preview_post_ID'] ); ?>
							</select>
							<p class="aep-field-note"><?php echo esc_html__( 'Choose a post to preview while editing template.', 'ae-pro' ); ?></p>
						</div>
					</div>

					<div class="f-row">
						<div class="ae-desc">
							<?php $this->render_help_label( 'ae_rule_taxonomy', __( 'Taxonomy', 'ae-pro' ), __( 'Choose the taxonomy this archive template applies to.', 'ae-pro' ) ); ?>
						</div>
						<div class="ae-control">
							<select name="ae_rule_taxonomy" id="ae_rule_taxonomy">
								<?php $admin_helper->render_dropdown( $taxonomies, $saved_meta['ae_rule_taxonomy'] ); ?>
							</select>
						</div>
					</div>

					<div class="f-row">
						<div class="ae-desc">
							<?php $this->render_help_label( 'ae_preview_term', __( 'Preview Term', 'ae-pro' ), __( 'Used only for archive preview context in the editor.', 'ae-pro' ) ); ?>
						</div>
						<div class="ae-control">
							<select name="ae_preview_term" id="ae_preview_term">
								<?php $admin_helper->render_dropdown( $preview_term, $saved_meta['ae_preview_term'] ); ?>
							</select>
						</div>
					</div>

					<div class="f-row">
						<div class="ae-desc">
							<?php $this->render_help_label( 'ae_preview_author', __( 'Preview Author', 'ae-pro' ), __( 'Used only for author archive preview while editing.', 'ae-pro' ) ); ?>
						</div>
						<div class="ae-control">
							<?php if($count_users['total_users'] > 100): ?>
								<input type="text" name="ae_preview_author" id="ae_preview_author" value="<?php echo $saved_meta['ae_preview_author']; ?>" />
								<p><?php echo esc_html__( 'Too many users to display. Please put user id.', 'ae-pro' ); ?></p>
							<?php else: ?>
							<select name="ae_preview_author" id="ae_preview_author">
								<?php $admin_helper->render_dropdown( $authors, $saved_meta['ae_preview_author'] ); ?>
							</select>
							<?php endif; ?>
						</div>
					</div>

					<div class="f-row">
						<div class="ae-desc">
							<?php $this->render_help_label( 'ae_rule_post_type_archive', __( 'Post Type Archive', 'ae-pro' ), __( 'Select a post type that has archive enabled.', 'ae-pro' ) ); ?>
						</div>
						<div class="ae-control">
							<select name="ae_rule_post_type_archive" id="ae_rule_post_type_archive">
								<?php $admin_helper->render_dropdown( $post_type_archives, $saved_meta['ae_rule_post_type_archive'] ); ?>
							</select>
						</div>
					</div>

					<div class="f-row">
						<div class="ae-desc">
							<?php $this->render_help_label( 'ae_apply_global', __( 'Global Template', 'ae-pro' ), __( 'Use this template globally where applicable.', 'ae-pro' ) ); ?>
						</div>
						<div class="ae-control">
							<input type="hidden" name="ae_apply_global" value="" />
							<label class="aep-toggle-switch" for="ae_apply_global">
								<?php //phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison ?>
								<input type="checkbox" value="true" <?php echo ( $saved_meta['ae_apply_global'] == 'true' ) ? 'checked' : ''; ?> id="ae_apply_global" name="ae_apply_global" />
								<span class="aep-toggle-slider" aria-hidden="true"></span>
							</label>
							<span class="aep-switch-label"><?php echo esc_html__( 'Use this as the global template', 'ae-pro' ); ?></span>
						</div>
					</div>

					<!-- Removed option.. only hidden field for backward compatibility. -->
					<input type="hidden" value="<?php echo ( ! isset( $saved_meta['ae_full_override'] ) || $saved_meta['ae_full_override'] !== 'true' ) ? '' : 'true'; ?>" id="ae_full_override" name="ae_full_override" />

						<div class="f-row">
							<div class="ae-desc">
								<?php $this->render_help_label( 'ae_elementor_template', __( 'Page Layout', 'ae-pro' ), __( 'Select the layout wrapper used by Elementor.', 'ae-pro' ) ); ?>
							</div>
						<div class="ae-control">
								<select name="ae_elementor_template" id="ae_elementor_template">
									<?php
									$admin_helper->render_dropdown( $page_layouts, $saved_meta['ae_elementor_template'] );
									?>
									</select>
								</div>
						</div>

						<div class="f-row">
							<div class="ae-desc">
								<label for="ae_hook_post_types">Post Type</label>
								<span class="aep-desc">Select specific post type on which template has to be display</span>
							</div>
							<div class="ae-control">
								<?php $admin_helper->render_checkbox( 'ae_hook_post_types[]', $post_types, $saved_meta['ae_hook_post_types'] ); ?>
							</div>
						</div>

						<div class="f-row">
							<div class="ae-desc">
								<label for="ae_hook_posts_selected"><?php echo esc_html__( 'Selected Posts', 'ae-pro' ); ?></label>
								<span class="aep-desc"><?php echo esc_html__( 'Comma separated specific post ids on which this template has to be appeared', 'ae-pro' ); ?></span>
							</div>
							<div class="ae-control">
								<input type="text" name="ae_hook_posts_selected" id="ae_hook_posts_selected" value="<?php echo esc_html( $saved_meta['ae_hook_posts_selected'] ); ?>" />
							</div>
						</div>

						<div class="f-row">
							<div class="ae-desc">
								<label for="ae_hook_posts_excluded"><?php echo esc_html__( 'Excluding Posts', 'ae-pro' ); ?></label>
								<span class="aep-desc"><?php echo esc_html__( 'Comma separated specific post ids on which this template should not to appeared', 'ae-pro' ); ?></span>
							</div>
							<div class="ae-control">
								<input type="text" name="ae_hook_posts_excluded" id="ae_hook_posts_excluded" value="<?php echo esc_html( $saved_meta['ae_hook_posts_excluded'] ); ?>" />
							</div>
						</div>

						<div class="f-row">
							<div class="ae-desc">
								<label for="ae_hook_taxonomies"><?php echo esc_html__( 'Taxonomies', 'ae-pro' ); ?></label>
								<span class="aep-desc"><?php echo esc_html__( 'Select specific Taxonomies on which template will appear', 'ae-pro' ); ?></span>
							</div>
							<div class="ae-control">
								<?php $admin_helper->render_checkbox( 'ae_hook_taxonomies[]', $taxonomies, $saved_meta['ae_hook_taxonomies'] ); ?>
							</div>
						</div>

						<div class="f-row">
							<div class="ae-desc">
								<label for="ae_hook_terms_selected"><?php echo esc_html__( 'Selected Terms', 'ae-pro' ); ?></label>
								<span class="aep-desc"><?php echo esc_html__( 'Comma separated specific term ids on which this template will appear', 'ae-pro' ); ?></span>
							</div>
							<div class="ae-control">
								<input type="text" name="ae_hook_terms_selected" id="ae_hook_terms_selected" value="<?php echo esc_html( $saved_meta['ae_hook_terms_selected'] ); ?>" />
							</div>
						</div>

						<div class="f-row">
							<div class="ae-desc">
								<label for="ae_hook_terms_excluded"><?php echo esc_html__( 'Excluding Terms', 'ae-pro' ); ?></label>
								<span class="aep-desc"><?php echo esc_html__( 'Comma separated specific term ids on which this template will not appeared', 'ae-pro' ); ?></span>
							</div>
							<div class="ae-control">
								<input type="text" name="ae_hook_terms_excluded" id="ae_hook_terms_excluded" value="<?php echo esc_html( $saved_meta['ae_hook_terms_excluded'] ); ?>" />
							</div>
						</div>

						<?php
						if ( \Aepro\Plugin::show_acf( true ) ) {
						// phpcs:ignore WordPress.Security.NonceVerification.Recommended
						if ( isset( $_GET['post'] ) ) {
							if ( $repeater_loc === 'post' ) {
								// phpcs:ignore WordPress.Security.NonceVerification.Recommended
								$preview_post_id = get_post_meta( $_GET['post'], 'ae_preview_post_ID', true );
								$repeater_fields = $admin_helper->get_ae_acf_repeater_fields( $preview_post_id );
							} else {
								$repeater_fields = $admin_helper->get_ae_acf_option_repeater_fields();
							}
						} else {
							$repeater_fields = [];
						}

						?>

						<div class="f-row">
							<div class="ae-desc">
								<label for="ae_acf_repeater_name"><?php echo esc_html__( 'Repeater Field (ACF)', 'ae-pro' ); ?></label>
							</div>

							<div class="ae-control">
								<select name="ae_acf_repeater_name" id="ae_acf_repeater_name">
									<?php $admin_helper->render_dropdown( $repeater_fields, $saved_meta['ae_acf_repeater_name'] ); ?>
								</select>
							</div>
						</div>

						<?php
						// phpcs:ignore WordPress.Security.NonceVerification.Recommended
						if ( isset( $_GET['post'] ) ) {
							if ( $repeater_loc === 'post' ) {
								// phpcs:ignore WordPress.Security.NonceVerification.Recommended
								$preview_post_id  = get_post_meta( $_GET['post'], 'ae_preview_post_ID', true );
								$flexible_content = $admin_helper->get_ae_acf_flexible_fields( $preview_post_id );
							} else {
								$flexible_content = $admin_helper->get_ae_acf_option_flexible_fields();
							}
						} else {
							$flexible_content = [];
						}

						?>

						<div class="f-row">
							<div class="ae-desc">
								<label for="ae_acf_flexible_name"><?php echo esc_html__( 'Field', 'ae-pro' ); ?></label>
								<span class="aep-desc">Name of Flexible content Field (as in ACF).</span>
							</div>

							<div class="ae-control">
								<select name="ae_acf_flexible_name" id="ae_acf_flexible_name">
									<?php $admin_helper->render_dropdown( $flexible_content, $saved_meta['ae_acf_flexible_name'] ); ?>
								</select>
							</div>
						</div>
						<?php
					}
					?>

				</div>

				<div id="ae-config-rules" class="ae-config-content" aria-hidden="true">

						<div class="f-row">
							<div class="ae-desc">
								<label for="ae_hook_apply_on">Apply To</label>
								<span class="aep-desc">Select the pages on which you want your template to be displayed</span>
							</div>
						<div class="ae-control">

								<?php $admin_helper->render_checkbox( 'ae_hook_apply_on[]', $apply_on, $saved_meta['ae_hook_apply_on'] ); ?>

							</div>
						</div>
					</div>
				</div>
			</div>



		<?php
	}

	// phpcs:disable WordPress.Security.NonceVerification.Missing
	public function save_config( $post_id ) {

		$post_type = get_post_type( $post_id );

		if ( 'ae_global_templates' !== $post_type ) {
			return;
		}

		if ( ! isset( $_POST['ae_render_mode'] ) ) {
			return;
		}

		if ( ! aepro_is_pro_active() ) {
			$allowed_modes = [ 'normal', 'post_template' ];
			if ( ! in_array( $_POST['ae_render_mode'], $allowed_modes, true ) ) {
				$_POST['ae_render_mode'] = 'post_template';
			}

			// Free version: restrict Post Type to built-in types only.
			// Custom post types (CPTs) require Pro.
			$free_post_types = [ 'post', 'page' ];
			if ( isset( $_POST['ae_rule_post_type'] ) && ! in_array( $_POST['ae_rule_post_type'], $free_post_types, true ) ) {
				$_POST['ae_rule_post_type'] = 'post';
			}
		}

		foreach ( $this->fields as $key => $field ) {

			if ( isset( $_POST[ $key ] ) ) {
				if ( $field['multi'] ) {
					update_post_meta( $post_id, $key, array_map( 'sanitize_text_field', $_POST[ $key ] ) );
				} else {
					update_post_meta( $post_id, $key, sanitize_text_field( $_POST[ $key ] ) );
				}
			} else {
				if ( $field['multi'] ) {
					update_post_meta( $post_id, $key, [] );
				} else {
					update_post_meta( $post_id, $key, '' );
				}
			}
		}
	}
	// phpcs:enable

	public function get_saved_meta( $post_id ) {

		$saved_meta = [];

		$post_metas = get_post_meta( $post_id );

		foreach ( $this->fields as $key => $field ) {

			if ( in_array( $key, array_keys( $post_metas ), true ) ) {

				if ( $field['multi'] ) {
					// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_unserialize
					$saved_meta[ $key ] = unserialize( $post_metas[ $key ][0] );
				} else {
					$saved_meta[ $key ] = $post_metas[ $key ][0];
				}
			} else {
				if ( $field['multi'] ) {
					$saved_meta[ $key ] = [];
				} else {
					$saved_meta[ $key ] = '';
				}
			}
		}

		return $saved_meta;
	}

}

new TemplateConfig();
