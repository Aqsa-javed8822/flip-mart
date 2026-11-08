<?php

namespace Aepro\Admin;

use Aepro\Aepro;
use Aepro\Admin\AdminHelper;

class Ui {

	private static $_instance = null;

	private $screens = [];

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {
		add_action( 'in_admin_header', [ $this, 'top_bar' ] );

		add_action( 'restrict_manage_posts', [ $this, 'render_mode_filter' ] );

		add_filter( 'parse_query', [ $this, 'filter_templates' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_template_create_modal_assets' ] );
		add_action( 'admin_footer-edit.php', [ $this, 'render_template_create_modal' ] );
		add_action( 'admin_footer-post.php', [ $this, 'render_template_create_modal' ] );
		add_action( 'admin_menu', [ $this, 'override_add_new_submenu_link' ], 99 );
		add_action( 'wp_ajax_aepro_create_template', [ $this, 'ajax_create_template' ] );

		$this->set_screens();
	}

	protected function set_screens() {
		$this->screens = apply_filters( 'aepro/admin/ui/screens', [
			'ae_global_templates',
			'edit-ae_global_templates',
			'ae_global_templates_page_aepro-settings',
		] );
	}

	protected function is_template_admin_screen() {
		$current_screen = get_current_screen();

		if ( ! $current_screen ) {
			return false;
		}

		return in_array( $current_screen->id, [ 'edit-ae_global_templates', 'ae_global_templates' ], true );
	}

	protected function get_template_create_options() {
		return TemplateSettingsSchema::get_template_type_options();
	}

	protected function get_modal_post_type_choices() {
		return TemplateSettingsSchema::get_post_type_choices();
	}

	protected function get_modal_page_layout_choices() {
		return TemplateSettingsSchema::get_page_layout_choices();
	}

	protected function get_modal_taxonomy_choices() {
		return TemplateSettingsSchema::get_taxonomy_choices();
	}

	protected function get_modal_usage_area_choices() {
		return TemplateSettingsSchema::get_usage_area_choices();
	}

	protected function get_modal_apply_on_choices() {
		return TemplateSettingsSchema::get_apply_on_choices();
	}

	protected function get_tooltip_learn_more_url() {
		return admin_url( 'admin.php?page=aepro-settings' );
	}

	protected function render_field_label( $for, $text, $tooltip = '' ) {
		$learn_more_url = $this->get_tooltip_learn_more_url();
		?>
		<label for="<?php echo esc_attr( $for ); ?>">
			<?php echo esc_html( $text ); ?>
			<?php if ( $tooltip ) : ?>
				<span
					class="ae-template-create-modal__help"
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

	protected function render_modal_checkbox_list( $name, $choices ) {
		if ( empty( $choices ) ) {
			return;
		}
		?>
		<ul class="ae-template-create-modal__checkbox-list">
			<?php foreach ( $choices as $choice_key => $choice_label ) : ?>
				<li>
					<label>
						<input type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $choice_key ); ?>">
						<span><?php echo esc_html( $choice_label ); ?></span>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}

	public function enqueue_template_create_modal_assets() {
		if ( ! $this->is_template_admin_screen() ) {
			return;
		}

		$options      = $this->get_template_create_options();
		$post_types   = $this->get_modal_post_type_choices();
		$page_layouts = $this->get_modal_page_layout_choices();
		$taxonomies   = $this->get_modal_taxonomy_choices();

		wp_enqueue_style(
			'ae-template-create-modal',
			AE_PRO_URL . 'includes/admin/template-create-modal.css',
			[],
			AE_PRO_VERSION
		);

		wp_enqueue_script(
			'ae-template-create-modal',
			AE_PRO_URL . 'includes/admin/template-create-modal.js',
			[ 'jquery', 'ae-admin-js' ],
			AE_PRO_VERSION,
			true
		);

		wp_localize_script(
			'ae-template-create-modal',
			'aeTemplateCreateModal',
			[
				'post_type'        => 'ae_global_templates',
				'ajax_url'         => admin_url( 'admin-ajax.php' ),
				'create_action'    => 'aepro_create_template',
				'nonce'            => wp_create_nonce( 'aep_ajax_nonce' ),
				'new_post_base_url' => admin_url( 'post-new.php?post_type=' ),
				'new_term_base_url' => admin_url( 'edit-tags.php?taxonomy=' ),
				'upgrade_url'      => function_exists( 'aepro_get_upgrade_url' ) ? aepro_get_upgrade_url() : admin_url( 'admin.php?page=aepro-settings' ),
				'template_options' => $options,
				'post_types'       => $post_types,
				'page_layouts'     => $page_layouts,
				'taxonomies'       => $taxonomies,
				'labels'           => [
					'modal_title'       => __( 'Create Dynific Template', 'ae-pro' ),
					'modal_subtitle'    => __( 'Choose where this template will be used.', 'ae-pro' ),
					'template_name'     => __( 'Template Name', 'ae-pro' ),
					'default_title'     => __( 'No Title', 'ae-pro' ),
					'edit_with_element' => __( 'Edit with Elementor', 'ae-pro' ),
					'back'              => __( 'Back', 'ae-pro' ),
					'creating'          => __( 'Creating...', 'ae-pro' ),
					'create_failed'     => __( 'Unable to create template. Please try again.', 'ae-pro' ),
					'pro_only_feature'  => __( 'Pro-only feature', 'ae-pro' ),
					'upgrade'           => __( 'Upgrade', 'ae-pro' ),
				],
			]
		);
	}

	public function render_template_create_modal() {
		$current_screen = get_current_screen();
		if ( ! $current_screen || $current_screen->post_type !== 'ae_global_templates' ) {
			return;
		}

		if ( ! in_array( $current_screen->id, [ 'edit-ae_global_templates', 'ae_global_templates' ], true ) ) {
			return;
		}

		$options        = $this->get_template_create_options();
		$admin_helper   = AdminHelper::instance();
		$post_types     = $this->get_modal_post_type_choices();
		$taxonomies     = $this->get_modal_taxonomy_choices();
		$usage_areas    = $this->get_modal_usage_area_choices();
		$apply_on       = $this->get_modal_apply_on_choices();
		$page_layouts   = $this->get_modal_page_layout_choices();
		$repeater_locs  = TemplateSettingsSchema::get_repeater_location_choices();
		$acf_field_type = TemplateSettingsSchema::get_acf_field_type_choices();
		$learn_more_url = $this->get_tooltip_learn_more_url();
		?>
		<div id="ae-template-create-modal" class="ae-template-create-modal" aria-hidden="true">
			<div class="ae-template-create-modal__overlay" data-ae-modal-close></div>
			<div class="ae-template-create-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="ae-template-create-modal-title">
				<button type="button" class="ae-template-create-modal__close" data-ae-modal-close aria-label="<?php echo esc_attr__( 'Close', 'ae-pro' ); ?>">
					<span aria-hidden="true">&times;</span>
				</button>
				<h2 id="ae-template-create-modal-title"><?php echo esc_html__( 'Create Dynific Template', 'ae-pro' ); ?></h2>
				<p class="ae-template-create-modal__subtitle"><?php echo esc_html__( 'Choose where this template will be used.', 'ae-pro' ); ?></p>

				<div class="ae-template-create-modal__step ae-template-create-modal__step--types" data-step="types">
					<div class="ae-template-create-modal__grid">
						<?php foreach ( $options as $type_key => $option ) : ?>
							<?php $is_locked = ! empty( $option['is_locked'] ); ?>
							<button
								type="button"
								class="ae-template-create-modal__option<?php echo $is_locked ? ' is-locked' : ''; ?>"
								data-template-type="<?php echo esc_attr( $type_key ); ?>"
								data-locked="<?php echo $is_locked ? '1' : '0'; ?>"
								aria-disabled="<?php echo $is_locked ? 'true' : 'false'; ?>"
							>
								<span
									class="ae-template-create-modal__type-help"
									tabindex="0"
									role="button"
									data-tooltip="<?php echo esc_attr( $option['description'] ); ?>"
									data-tooltip-link="<?php echo esc_url( $option['learn_more'] ); ?>"
									data-tooltip-link-label="<?php echo esc_attr__( 'Learn More', 'ae-pro' ); ?>"
									aria-label="<?php echo esc_attr( $option['description'] ); ?>"
								>?</span>
								<?php if ( $is_locked ) : ?>
									<span class="ae-template-create-modal__pro-badge"><?php echo esc_html__( 'Pro', 'ae-pro' ); ?></span>
								<?php endif; ?>
								<span class="ae-template-create-modal__icon dashicons dashicons-media-document" aria-hidden="true"></span>
								<span class="ae-template-create-modal__label"><?php echo esc_html( $option['label'] ); ?></span>
							</button>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="ae-template-create-modal__step ae-template-create-modal__step--config" data-step="config" hidden>
					<div class="ae-template-create-modal__config-grid">
						<div class="ae-template-create-modal__config-left">
							<span class="ae-template-create-modal__icon dashicons dashicons-media-document" aria-hidden="true"></span>
							<h3 id="ae-template-config-title"></h3>
							<p id="ae-template-config-description"></p>
						</div>
						<div class="ae-template-create-modal__config-right">
							<div class="ae-template-create-modal__field">
								<label for="ae-modal-template-name"><?php echo esc_html__( 'Template Name', 'ae-pro' ); ?></label>
								<input type="text" id="ae-modal-template-name" value="<?php echo esc_attr__( 'No Title', 'ae-pro' ); ?>">
							</div>

							<div class="ae-template-create-modal__field" data-field="usage_area">
								<?php $this->render_field_label( 'ae-modal-usage-area', __( 'Usage Area', 'ae-pro' ), __( 'Select where this normal template should be used.', 'ae-pro' ) ); ?>
								<select id="ae-modal-usage-area">
									<?php $admin_helper->render_dropdown( $usage_areas, '' ); ?>
								</select>
							</div>

							<div class="ae-template-create-modal__field" data-field="custom_usage_area">
								<?php $this->render_field_label( 'ae-modal-custom-usage-area', __( 'Custom Usage Area', 'ae-pro' ), __( 'Provide a custom hook/usage location when Usage Area is set to custom.', 'ae-pro' ) ); ?>
								<input type="text" id="ae-modal-custom-usage-area">
							</div>

							<div class="ae-template-create-modal__field ae-template-create-modal__field--list" data-field="hook_apply_on">
								<?php $this->render_field_label( 'ae-modal-hook-apply-on', __( 'Apply To', 'ae-pro' ), __( 'Select the pages where this template should be displayed.', 'ae-pro' ) ); ?>
								<div>
									<?php $this->render_modal_checkbox_list( 'ae-modal-hook-apply-on[]', $apply_on ); ?>
								</div>
							</div>

							<div class="ae-template-create-modal__field ae-template-create-modal__field--list" data-field="hook_post_types">
								<?php $this->render_field_label( 'ae-modal-hook-post-types', __( 'Post Type', 'ae-pro' ), __( 'Select specific post types for single page conditions.', 'ae-pro' ) ); ?>
								<div>
									<?php $this->render_modal_checkbox_list( 'ae-modal-hook-post-types[]', $post_types ); ?>
								</div>
							</div>

							<div class="ae-template-create-modal__field" data-field="hook_posts_selected">
								<?php $this->render_field_label( 'ae-modal-hook-posts-selected', __( 'Selected Posts', 'ae-pro' ), __( 'Comma-separated post IDs where this template should appear.', 'ae-pro' ) ); ?>
								<div>
									<input type="text" id="ae-modal-hook-posts-selected">
								</div>
							</div>

							<div class="ae-template-create-modal__field" data-field="hook_posts_excluded">
								<?php $this->render_field_label( 'ae-modal-hook-posts-excluded', __( 'Excluding Posts', 'ae-pro' ), __( 'Comma-separated post IDs where this template should not appear.', 'ae-pro' ) ); ?>
								<div>
									<input type="text" id="ae-modal-hook-posts-excluded">
								</div>
							</div>

							<div class="ae-template-create-modal__field ae-template-create-modal__field--list" data-field="hook_taxonomies">
								<?php $this->render_field_label( 'ae-modal-hook-taxonomies', __( 'Taxonomies', 'ae-pro' ), __( 'Select specific taxonomies for archive conditions.', 'ae-pro' ) ); ?>
								<div>
									<?php $this->render_modal_checkbox_list( 'ae-modal-hook-taxonomies[]', $taxonomies ); ?>
								</div>
							</div>

							<div class="ae-template-create-modal__field" data-field="hook_terms_selected">
								<?php $this->render_field_label( 'ae-modal-hook-terms-selected', __( 'Selected Terms', 'ae-pro' ), __( 'Comma-separated term IDs where this template should appear.', 'ae-pro' ) ); ?>
								<div>
									<input type="text" id="ae-modal-hook-terms-selected">
								</div>
							</div>

							<div class="ae-template-create-modal__field" data-field="hook_terms_excluded">
								<?php $this->render_field_label( 'ae-modal-hook-terms-excluded', __( 'Excluding Terms', 'ae-pro' ), __( 'Comma-separated term IDs where this template should not appear.', 'ae-pro' ) ); ?>
								<div>
									<input type="text" id="ae-modal-hook-terms-excluded">
								</div>
							</div>

							<div class="ae-template-create-modal__field" data-field="rule_post_type">
								<?php $this->render_field_label( 'ae-modal-rule-post-type', __( 'Post Type', 'ae-pro' ), __( 'Choose the post type for this template scope.', 'ae-pro' ) ); ?>
								<?php
								$is_pro_active    = aepro_is_pro_active();
								$free_post_types  = [ 'post', 'page' ];
								?>
								<select id="ae-modal-rule-post-type">
									<?php foreach ( $post_types as $post_type_key => $post_type_label ) :
										$is_cpt_locked = ! $is_pro_active && ! in_array( $post_type_key, $free_post_types, true );
									?>
										<option
											value="<?php echo esc_attr( $post_type_key ); ?>"
											<?php disabled( $is_cpt_locked, true ); ?>
										><?php echo esc_html( $post_type_label . ( $is_cpt_locked ? ' — Pro' : '' ) ); ?></option>
									<?php endforeach; ?>
								</select>
								<?php if ( ! $is_pro_active ) : ?>
									<p class="description aep-field-note">
										<?php echo esc_html__( 'Custom post types are available in Pro.', 'ae-pro' ); ?>
										<a href="<?php echo esc_url( aepro_get_upgrade_url() ); ?>" target="_blank" rel="noopener"><?php echo esc_html__( 'Upgrade', 'ae-pro' ); ?></a>
									</p>
								<?php endif; ?>
							</div>

							<div class="ae-template-create-modal__field" data-field="preview_post">
								<?php $this->render_field_label( 'ae-modal-preview-post', __( 'Preview Post', 'ae-pro' ), __( 'Used only inside editor preview; does not affect frontend output.', 'ae-pro' ) ); ?>
								<select id="ae-modal-preview-post">
									<option value=""><?php echo esc_html__( 'Select Post', 'ae-pro' ); ?></option>
								</select>
								<p class="description">
									<?php echo esc_html__( 'Choose a post to preview while editing template.', 'ae-pro' ); ?>
								</p>
							</div>

							<div class="ae-template-create-modal__field" data-field="rule_taxonomy">
								<?php $this->render_field_label( 'ae-modal-rule-taxonomy', __( 'Taxonomy', 'ae-pro' ), __( 'Choose the taxonomy this archive template targets.', 'ae-pro' ) ); ?>
								<select id="ae-modal-rule-taxonomy">
									<?php foreach ( $taxonomies as $taxonomy_key => $taxonomy_label ) : ?>
										<option value="<?php echo esc_attr( $taxonomy_key ); ?>"><?php echo esc_html( $taxonomy_label ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="ae-template-create-modal__field" data-field="preview_term">
								<?php $this->render_field_label( 'ae-modal-preview-term', __( 'Preview Term', 'ae-pro' ), __( 'Used only inside editor preview for archive context.', 'ae-pro' ) ); ?>
								<select id="ae-modal-preview-term">
									<option value=""><?php echo esc_html__( 'Select Term', 'ae-pro' ); ?></option>
								</select>
								<p class="description">
									<?php echo esc_html__( 'Choose a term to preview while editing template.', 'ae-pro' ); ?>
									<a id="ae-modal-preview-term-dummy-link" href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=category' ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html__( 'Dummy data', 'ae-pro' ); ?></a>
								</p>
							</div>

								<div class="ae-template-create-modal__field" data-field="repeater_loc">
									<?php $this->render_field_label( 'ae-modal-repeater-loc', __( 'Field Location', 'ae-pro' ), __( 'Select where ACF fields are stored: post or options page.', 'ae-pro' ) ); ?>
									<select id="ae-modal-repeater-loc">
										<?php foreach ( $repeater_locs as $repeater_loc_key => $repeater_loc_label ) : ?>
											<option value="<?php echo esc_attr( $repeater_loc_key ); ?>"><?php echo esc_html( $repeater_loc_label ); ?></option>
										<?php endforeach; ?>
									</select>
								</div>

								<div class="ae-template-create-modal__field" data-field="acf_field_type">
									<?php $this->render_field_label( 'ae-modal-acf-field-type', __( 'Field Type', 'ae-pro' ), __( 'Choose whether this template reads repeater rows or flexible layouts.', 'ae-pro' ) ); ?>
									<select id="ae-modal-acf-field-type">
										<?php foreach ( $acf_field_type as $acf_field_type_key => $acf_field_type_label ) : ?>
											<option value="<?php echo esc_attr( $acf_field_type_key ); ?>"><?php echo esc_html( $acf_field_type_label ); ?></option>
										<?php endforeach; ?>
									</select>
								</div>

							<div class="ae-template-create-modal__field" data-field="acf_repeater">
								<?php $this->render_field_label( 'ae-modal-acf-repeater-name', __( 'Repeater Field (ACF)', 'ae-pro' ), __( 'Select the ACF repeater field key used for this template.', 'ae-pro' ) ); ?>
								<select id="ae-modal-acf-repeater-name">
									<option value=""><?php echo esc_html__( 'Select Repeater Field', 'ae-pro' ); ?></option>
								</select>
							</div>

							<div class="ae-template-create-modal__field" data-field="acf_flexible">
								<?php $this->render_field_label( 'ae-modal-acf-flexible-name', __( 'Flexible Field (ACF)', 'ae-pro' ), __( 'Select the ACF flexible content field key for this template.', 'ae-pro' ) ); ?>
								<select id="ae-modal-acf-flexible-name">
									<option value=""><?php echo esc_html__( 'Select Flexible Field', 'ae-pro' ); ?></option>
								</select>
							</div>

								<div class="ae-template-create-modal__field" data-field="page_layout">
									<?php $this->render_field_label( 'ae-modal-page-layout', __( 'Page Layout', 'ae-pro' ), __( 'Select the Elementor layout wrapper for this template output.', 'ae-pro' ) ); ?>
									<select id="ae-modal-page-layout">
										<?php foreach ( $page_layouts as $page_layout_key => $page_layout_label ) : ?>
											<option value="<?php echo esc_attr( $page_layout_key ); ?>"><?php echo esc_html( $page_layout_label ); ?></option>
										<?php endforeach; ?>
									</select>
								</div>

							<div class="ae-template-create-modal__field ae-template-create-modal__field--toggle" data-field="apply_global">
								<?php $this->render_field_label( 'ae-modal-apply-global', __( 'Global Template', 'ae-pro' ), __( 'When enabled, this template can auto-apply globally based on mode rules.', 'ae-pro' ) ); ?>
								<label class="ae-template-create-modal__switch">
									<input type="checkbox" id="ae-modal-apply-global" value="true">
									<span class="ae-template-create-modal__slider" aria-hidden="true"></span>
									<span class="ae-template-create-modal__switch-text"><?php echo esc_html__( 'Use this as the global template', 'ae-pro' ); ?></span>
								</label>
							</div>

							<div class="ae-template-create-modal__actions">
								<button type="button" class="button button-secondary" id="ae-template-modal-back"><?php echo esc_html__( 'Back', 'ae-pro' ); ?></button>
								<button type="button" class="button button-primary" id="ae-template-modal-create"><?php echo esc_html__( 'Edit with Elementor', 'ae-pro' ); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="ae-template-create-modal-tooltip" class="ae-template-create-modal__tooltip" role="tooltip" hidden>
				<p class="ae-template-create-modal__tooltip-text"></p>
				<a class="ae-template-create-modal__tooltip-link" href="#" target="_blank" rel="noopener"></a>
			</div>
		</div>
		<?php
	}

	public function override_add_new_submenu_link() {
		global $submenu;

		$parent_slug = 'edit.php?post_type=ae_global_templates';
		if ( ! isset( $submenu[ $parent_slug ] ) || ! is_array( $submenu[ $parent_slug ] ) ) {
			return;
		}

		foreach ( $submenu[ $parent_slug ] as $index => $item ) {
			if ( ! isset( $item[2] ) ) {
				continue;
			}

			if ( strpos( $item[2], 'post-new.php?post_type=ae_global_templates' ) === 0 ) {
				$submenu[ $parent_slug ][ $index ][2] = 'edit.php?post_type=ae_global_templates&ae-open-create=1';
			}
		}
	}

	public function ajax_create_template() {
		check_ajax_referer( 'aep_ajax_nonce', 'nonce' );

		$post_type = 'ae_global_templates';
		$post_type_object = get_post_type_object( $post_type );

		if ( ! $post_type_object ) {
			wp_send_json_error( [ 'message' => __( 'Invalid post type.', 'ae-pro' ) ], 400 );
		}

		$create_capability = isset( $post_type_object->cap->create_posts ) ? $post_type_object->cap->create_posts : 'edit_posts';
		if ( ! current_user_can( $create_capability ) ) {
			wp_send_json_error( [ 'message' => __( 'You are not allowed to create templates.', 'ae-pro' ) ], 403 );
		}

		$options = $this->get_template_create_options();
		$type    = isset( $_POST['template_type'] ) ? sanitize_key( wp_unslash( $_POST['template_type'] ) ) : '';

		if ( ! isset( $options[ $type ] ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid template type.', 'ae-pro' ) ], 400 );
		}

		$render_mode = $options[ $type ]['render_mode'];
		if ( ! aepro_is_pro_active() ) {
			$allowed_modes = [ 'normal', 'post_template' ];
			if ( ! in_array( $render_mode, $allowed_modes, true ) ) {
				$render_mode = 'post_template';
			}
		}

		$template_name = isset( $_POST['template_name'] ) ? sanitize_text_field( wp_unslash( $_POST['template_name'] ) ) : '';
		if ( $template_name === '' ) {
			$template_name = __( 'No Title', 'ae-pro' );
		}

		$post_id = wp_insert_post(
			[
				'post_type'   => $post_type,
				'post_title'  => $template_name,
				'post_status' => 'draft',
			],
			true
		);

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( [ 'message' => $post_id->get_error_message() ], 500 );
		}

		$meta_keys         = TemplateSettingsSchema::get_shared_meta_keys();
		$usage_area        = TemplateSettingsSchema::sanitize_usage_area( isset( $_POST['usage_area'] ) ? $_POST['usage_area'] : '', '' );
		$custom_usage_area = isset( $_POST['custom_usage_area'] ) ? sanitize_text_field( wp_unslash( $_POST['custom_usage_area'] ) ) : '';
		$rule_post_type    = TemplateSettingsSchema::sanitize_post_type( isset( $_POST['rule_post_type'] ) ? $_POST['rule_post_type'] : '', 'post' );

		// Free version: restrict to built-in post types only.
		if ( ! aepro_is_pro_active() ) {
			$free_post_types = [ 'post', 'page' ];
			if ( ! in_array( $rule_post_type, $free_post_types, true ) ) {
				$rule_post_type = 'post';
			}
		}

		$preview_post_id = isset( $_POST['preview_post_id'] ) ? absint( $_POST['preview_post_id'] ) : 0;
		if ( $preview_post_id && ! get_post( $preview_post_id ) ) {
			$preview_post_id = 0;
		}

		$rule_taxonomy = TemplateSettingsSchema::sanitize_taxonomy( isset( $_POST['rule_taxonomy'] ) ? $_POST['rule_taxonomy'] : '', '' );

		$preview_term = isset( $_POST['preview_term'] ) ? absint( $_POST['preview_term'] ) : 0;
		if ( $preview_term && ! get_term( $preview_term ) ) {
			$preview_term = 0;
		}

		$repeater_loc   = TemplateSettingsSchema::sanitize_repeater_location( isset( $_POST['repeater_loc'] ) ? $_POST['repeater_loc'] : '', 'post' );
		$acf_field_type = TemplateSettingsSchema::sanitize_acf_field_type( isset( $_POST['acf_field_type'] ) ? $_POST['acf_field_type'] : '', 'repeater' );

		$acf_repeater_name = isset( $_POST['acf_repeater_name'] ) ? sanitize_text_field( wp_unslash( $_POST['acf_repeater_name'] ) ) : '';
		$acf_flexible_name = isset( $_POST['acf_flexible_name'] ) ? sanitize_text_field( wp_unslash( $_POST['acf_flexible_name'] ) ) : '';

		$apply_global        = ! empty( $_POST['apply_global'] ) ? 'true' : '';
		$page_layout         = TemplateSettingsSchema::sanitize_page_layout( isset( $_POST['page_layout'] ) ? $_POST['page_layout'] : '', '' );
		$hook_apply_on_raw   = isset( $_POST['hook_apply_on'] ) ? (array) wp_unslash( $_POST['hook_apply_on'] ) : [];
		$hook_post_types_raw = isset( $_POST['hook_post_types'] ) ? (array) wp_unslash( $_POST['hook_post_types'] ) : [];
		$hook_taxonomies_raw = isset( $_POST['hook_taxonomies'] ) ? (array) wp_unslash( $_POST['hook_taxonomies'] ) : [];
		$hook_apply_on       = TemplateSettingsSchema::sanitize_apply_on_values( $hook_apply_on_raw );
		$hook_post_types     = TemplateSettingsSchema::sanitize_post_type_list( $hook_post_types_raw );
		$hook_taxonomies     = TemplateSettingsSchema::sanitize_taxonomy_list( $hook_taxonomies_raw );
		$hook_posts_selected = isset( $_POST['hook_posts_selected'] ) ? sanitize_text_field( wp_unslash( $_POST['hook_posts_selected'] ) ) : '';
		$hook_posts_excluded = isset( $_POST['hook_posts_excluded'] ) ? sanitize_text_field( wp_unslash( $_POST['hook_posts_excluded'] ) ) : '';
		$hook_terms_selected = isset( $_POST['hook_terms_selected'] ) ? sanitize_text_field( wp_unslash( $_POST['hook_terms_selected'] ) ) : '';
		$hook_terms_excluded = isset( $_POST['hook_terms_excluded'] ) ? sanitize_text_field( wp_unslash( $_POST['hook_terms_excluded'] ) ) : '';

		update_post_meta( $post_id, $meta_keys['render_mode'], $render_mode );
		update_post_meta( $post_id, $meta_keys['usage_area'], $usage_area );
		update_post_meta( $post_id, $meta_keys['custom_usage_area'], $custom_usage_area );
		update_post_meta( $post_id, $meta_keys['rule_post_type'], $rule_post_type );
		update_post_meta( $post_id, $meta_keys['preview_post'], $preview_post_id ? $preview_post_id : '' );
		update_post_meta( $post_id, $meta_keys['rule_taxonomy'], $rule_taxonomy );
		update_post_meta( $post_id, $meta_keys['preview_term'], $preview_term ? $preview_term : '' );
		update_post_meta( $post_id, $meta_keys['repeater_loc'], $repeater_loc );
		update_post_meta( $post_id, $meta_keys['acf_field_type'], $acf_field_type );
		update_post_meta( $post_id, $meta_keys['acf_repeater'], $acf_repeater_name );
		update_post_meta( $post_id, $meta_keys['acf_flexible'], $acf_flexible_name );
		update_post_meta( $post_id, $meta_keys['apply_global'], $apply_global );
		update_post_meta( $post_id, $meta_keys['hook_apply_on'], $hook_apply_on );
		update_post_meta( $post_id, $meta_keys['hook_post_types'], $hook_post_types );
		update_post_meta( $post_id, $meta_keys['hook_posts_selected'], $hook_posts_selected );
		update_post_meta( $post_id, $meta_keys['hook_posts_excluded'], $hook_posts_excluded );
		update_post_meta( $post_id, $meta_keys['hook_taxonomies'], $hook_taxonomies );
		update_post_meta( $post_id, $meta_keys['hook_terms_selected'], $hook_terms_selected );
		update_post_meta( $post_id, $meta_keys['hook_terms_excluded'], $hook_terms_excluded );
		update_post_meta( $post_id, $meta_keys['page_layout'], $page_layout );

		if ( $render_mode === 'post_type_archive_template' ) {
			update_post_meta( $post_id, $meta_keys['rule_post_archive'], $rule_post_type );
		}

		$redirect_url = admin_url( 'post.php?post=' . $post_id . '&action=elementor' );

		wp_send_json_success(
			[
				'post_id'      => $post_id,
				'redirect_url' => $redirect_url,
			]
		);
	}

	public function top_bar() {
		$nav_links = [
			5 => [
				'id'    => 'edit-ae_global_templates',
				'label' => __( 'Templates', 'ae-pro' ),
				'icon'  => 'eicon-archive',
				'link'  => admin_url( 'edit.php?post_type=ae_global_templates' ),
			],
			10 => [
				'id'    => 'ae_global_templates_page_aepro-settings',
				'label' => __( 'Settings', 'ae-pro' ),
				'icon'  => 'eicon-settings',
				'link'  => admin_url( 'edit.php?post_type=ae_global_templates&page=aepro-settingsedit.php?post_type=ae_global_templates&page=aepro-settings' ),
			],
			15 => [
				'id'    => 'doc',
				'label' => __( 'Documentation', 'ae-pro' ),
				'icon'  => 'eicon-document-file',
				'link'  => 'https://qikly.ink/plugin-docs',
			],
			20 => [
				'id'    => 'support',
				'label' => __( 'Get Support', 'ae-pro' ),
				'icon'  => 'eicon-help',
				'link'  => 'https://qikly.ink/dynific-addon-support',
			],
		];

		$nav_links = apply_filters( 'aepro/admin/ui/header_menu', $nav_links );

		$current_screen = get_current_screen();

		if ( ! in_array( $current_screen->id, $this->screens, true ) ) {
			return;
		}

		?>

		<div class="ae-admin-topbar">
			<div class="ae-branding">
				<svg width="50" height="50" viewBox="0 0 1900 1900" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect x="45" y="45" width="1810" height="1810" rx="355" stroke="#183A59" stroke-width="90"/>
				<path d="M768.137 689.992C804.213 689.992 837.513 695.419 867.761 706.64C898.172 717.921 924.392 734.876 946.07 757.551H946.069C953.518 765.266 960.337 773.586 966.537 782.489L918.23 901.79L918.644 901.957L902.974 939.224C902.13 914.106 898.778 892.756 893.225 874.918L893.216 874.891L893.208 874.862C886.673 853.672 877.474 837.223 866.108 824.775C854.693 812.273 840.614 802.934 823.463 796.782L823.363 796.747L823.265 796.71C805.762 790.242 785.281 786.785 761.496 786.785H716.758V1122.3H748.547C802.967 1122.3 840.25 1107.35 864.487 1080.94C871.121 1073.72 876.994 1065.4 882.033 1055.92C883.111 1054.39 886.246 1048.81 895 1028.5C906.619 1001.54 921.693 960.765 923.761 955.148L983.894 812.146C988.174 820.916 991.998 830.108 995.371 839.71L995.913 841.238C1007.2 873.394 1012.6 909.787 1012.6 950.059C1012.6 992.856 1007.05 1031.33 995.471 1065.1L995.463 1065.13L995.455 1065.15C983.911 1098.58 966.957 1127.25 944.314 1150.63L944.273 1150.68L944.232 1150.72C921.555 1173.89 893.83 1191.11 861.512 1202.54C829.506 1213.94 793.739 1219.42 754.523 1219.42H611V689.992H768.137Z" fill="#183A59"/>
				<path d="M938.871 1202.83L933.653 1217.42H881.899C882.889 1216.84 883.878 1216.25 884.869 1215.66C894.706 1210.98 906.61 1203.66 918.743 1194.51C934.572 1182.56 947.238 1170.16 953.921 1160.74L938.871 1202.83ZM1111.14 700.43L1289.77 1187.85L1300.61 1217.42H1190.47L1185.28 1202.75L1137.34 1067.01H987.442L982.936 1079.61C967.359 1059.62 919.716 1067.22 868.771 1095.74L1013.37 700.442L1018.66 686H1105.85L1111.14 700.43ZM1062.11 852.854C1061.65 854.193 1061.22 855.462 1060.8 856.657L1022.64 968.227H1102.33L1063.4 856.695L1063.35 856.549L1063.3 856.402C1062.93 855.294 1062.53 854.112 1062.11 852.854Z" fill="#183A59"/>
				<path d="M1419.85 673.113V1231.79C1419.85 1321.72 1346.62 1394.53 1256.6 1394.53H638.935H367.695H322L639.13 1705.6V1545H1256.6C1429.81 1545 1570.8 1404.65 1570.8 1231.79V673.113V597H1419.07L1419.85 673.113Z" fill="#E82A5B"/>
				<path d="M1258.74 359.657C1257.96 359.657 1257.38 359.657 1256.6 359.657H636.201C462.99 359.657 322 500.007 322 672.866V1231.54V1307.66H473.926L472.949 1231.54V672.866C472.949 582.933 546.178 510.13 636.201 510.13H1525.1H1570.8L1258.74 194V359.657Z" fill="#E82A5B"/>
				</svg>
				<!-- <img src="<?php echo esc_attr( AE_PRO_URL . 'includes/assets/images/ae-logo-color.png' ); ?>" alt="Dynific Addons for Elementor Pro (formerly Anywhere Elementor Pro)" width="50px" /> -->
				<div class="ae-branding-text">
					<div class="ae-branding-title-row">
						<h1>
							<?php echo esc_html( defined( 'AE_PLUGIN_NAME' ) ? AE_PLUGIN_NAME : 'Dynific Addons for Elementor' ); ?>
						</h1>
						<span class="ae-version"><?php echo esc_html( defined( 'AE_PLUGIN_VERSION' ) ? AE_PLUGIN_VERSION : AE_PRO_VERSION ); ?> </span>
					</div>
					<?php if ( defined( 'AE_PLUGIN_LEGACY_NAME' ) && AE_PLUGIN_LEGACY_NAME ) : ?>
						<em class="ae-legacy-name">(<?php echo esc_html( AE_PLUGIN_LEGACY_NAME ); ?>)</em>
					<?php endif; ?>
				</div>
			</div>


			<nav class="ae-nav">
				<ul>
					<?php
					if ( isset( $nav_links ) && count( $nav_links ) ) {
						ksort( $nav_links );
						foreach ( $nav_links as $id => $link ) {

							$link_screens = ! empty( $link['screens'] ) ? (array) $link['screens'] : [ $link['id'] ];
							$active       = in_array( $current_screen->id, $link_screens, true ) ? 'ae-nav-active' : '';

							$target = '';
							$class  = '';
							if ( $link['id'] === 'doc' || $id === 'support' ) {
								$target = 'target="_blank"';
							}

							if ( $link['id'] === 'import-ae_global_templates' ) {
								$class = 'ae-import-template-btn';
							}

							?>
							<li class="<?php echo esc_html( $active ); ?>">
								<!-- <a class="<?php echo esc_attr( $class ); ?>" <?php echo esc_html( $target ); ?> href="<?php echo esc_html( $link['link'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a> -->
								<a class="<?php echo esc_attr( $class ); ?>" title="<?php echo esc_html( $link['label'] ); ?>" <?php echo esc_html( $target ); ?> href="<?php echo esc_html( $link['link'] ); ?>"><i class="<?php echo esc_html( $link['icon'] ); ?>"></i></a>
							</li>
							<?php
						}
					}
					?>
				</ul>
			</nav>
		</div>

		<?php
	}

	public function render_mode_filter() {
        $current_screen = get_current_screen();
		if ( ! in_array( $current_screen->id, $this->screens, true ) ) {
			return;
		}
		$current      = '';
		$render_modes = Aepro::$_helper->get_ae_render_mode_hook();
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['ae-render-mode'] ) && ! empty( $_GET['ae-render-mode'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$current = $_GET['ae-render-mode'];
		}

		$admin_helper = AdminHelper::instance();

		?>
		<select name="ae-render-mode">
			<option value="">All Templates</option>
			<?php
			$admin_helper->render_dropdown( $render_modes, $current );
			?>
		</select>
		<?php
	}

	public function filter_templates( $query ) {
		global $pagenow;
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$post_type = ( isset( $_GET['post_type'] ) ) ? esc_attr( $_GET['post_type'] ) : 'post';
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $post_type === 'ae_global_templates' && $pagenow === 'edit.php' && isset( $_GET['ae-render-mode'] ) && ! empty( $_GET['ae-render-mode'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$query->query_vars['meta_value'] = esc_attr( $_GET['ae-render-mode'] );
			$query->query_vars['meta_key']   = 'ae_render_mode';
		}
	}
}
