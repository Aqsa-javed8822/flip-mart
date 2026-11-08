<?php

namespace RadiusTheme\SB\Controllers\Admin;

use RadiusTheme\SB\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Settings Page.
 */
class PluginRow {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * Template builder post type
	 *
	 * @var string
	 */
	public string $textdomain = 'shopbuilder';

	/**
	 * Construct function
	 */
	private function __construct() {
		// Plugins Setting Page.
		add_filter( 'plugin_action_links_' . plugin_basename( RTSB_FILE ), [ $this, 'plugins_setting_links' ] );
		add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
		add_action( 'admin_footer', [ $this, 'deactivation_popup' ], 99 );
	}

	/**
	 * @param array $links default plugin action link.
	 *
	 * @return array [array] plugin action link
	 */
	public function plugins_setting_links( $links ) {
		$new_links   = [];
		$demo_url    = 'https://shopbuilderwp.com/';
		$new_links[] = '<a href="' . admin_url( 'admin.php?page=rtsb-settings' ) . '">' . esc_html__( 'Settings', 'shopbuilder' ) . '</a>';
		$new_links[] = '<a target="_blank" href="' . esc_url( $demo_url ) . '">' . esc_html__( 'Demo', 'shopbuilder' ) . '</a>';
		$new_links[] = '<a target="_blank" href="' . esc_url( 'https://shopbuilderwp.com/docs/' ) . '">' . esc_html__( 'Documentation', 'shopbuilder' ) . '</a>';

		$links = array_merge( $new_links, $links );

		$current = time();
		// Check if current date is within promotional period (Sept 19 - Jan 5).
		$start_date      = strtotime( '2025-11-12 00:00:00' );
		$end_date        = strtotime( '2025-12-30 23:59:59' );
		$is_promo_period = $start_date <= $current && $current <= $end_date;
		$text            = esc_html__( 'Get Pro', 'shopbuilder' );
		if ( $is_promo_period ) {
			$text = esc_html__( 'Get 60% off', 'shopbuilder' );
		}

		if ( ! rtsb()->has_pro() ) {
			$links['shopbuilder_pro'] = '<a href="' . esc_url( rtsb()->pro_version_link() ) . '" target="_blank" style="color: #39b54a; font-weight: bold;">' . $text . '</a>';
		}

		return $links;
	}

	/**
	 * Plugin links row.
	 *
	 * @param array  $links Links.
	 * @param string $file File.
	 *
	 * @return array
	 */
	public function plugin_row_meta( $links, $file ) {

		if ( RTSB_ACTIVE_FILE_NAME === $file ) {
			$report_url         = 'https://www.radiustheme.com/contact/';
			$row_meta['issues'] = sprintf(
					/* translators: %1$s: Report URL, %2$s: Facing issue?, %3$s: Please open a support ticket. */
				'%2$s <a target="_blank" href="%1$s"><span style="color: red">%3$s</span></a>',
				esc_url( $report_url ),
				esc_html__( 'Facing issue?', 'shopbuilder' ),
				esc_html__( 'Please open a support ticket.', 'shopbuilder' )
			);

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}

	/***
	 * @return mixed
	 */
	public function deactivation_popup() {
		global $pagenow;
		if ( 'plugins.php' !== $pagenow ) {
			return;
		}

		$this->dialog_box_style();
		$this->deactivation_scripts();
		?>
		<div id="deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?>" title="Quick Feedback">
			<!-- Modal content -->
			<div class="modal-content">
				<div id="feedback-form-body-<?php echo esc_attr( $this->textdomain ); ?>">
					<p class="rtsb-deactivate-intro" style="margin: 0 0 15px 0;">
						Having trouble? <br/>
						For faster and more accurate support, please
						<a target="_blank" href="https://www.radiustheme.com/contact/" class="rtsb-support-link">open a support ticket</a>
						— our support agent will personally review and resolve your issue.
					</p>

					<div class="feedback-input-header">
						<?php echo esc_html__( 'If you’d prefer not to open a support ticket, please take a moment to share why you’re deactivating ShopBuilder:', 'shopbuilder' ); ?>
					</div>

					<div class="feedback-input-wrapper">
						<input id="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-bug_issue_detected" class="feedback-input"
							   type="radio" name="reason_key" value="bug_issue_detected">
						<label for="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-bug_issue_detected" class="feedback-label">Bug Or Issue detected.</label>
					</div>

					<div class="feedback-input-wrapper">
						<input id="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-no_longer_needed" class="feedback-input" type="radio"
							   name="reason_key" value="no_longer_needed">
						<label for="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-no_longer_needed" class="feedback-label">I no longer
							need the plugin</label>
					</div>
					<div class="feedback-input-wrapper conditional">
						<input id="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-found_a_better_plugin" class="feedback-input"
							   type="radio" name="reason_key" value="found_a_better_plugin">
						<label for="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-found_a_better_plugin" class="feedback-label">I found a
							better plugin</label>
						<input class="feedback-feedback-text" type="text" name="reason_found_a_better_plugin"
							   placeholder="Please share the plugin name">
					</div>
					<div class="feedback-input-wrapper">
						<input id="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-couldnt_get_the_plugin_to_work" class="feedback-input"
							   type="radio" name="reason_key" value="couldnt_get_the_plugin_to_work">
						<label for="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-couldnt_get_the_plugin_to_work" class="feedback-label">I
							couldn't get the plugin to work</label>
					</div>

					<div class="feedback-input-wrapper">
						<input id="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-temporary_deactivation" class="feedback-input"
							   type="radio" name="reason_key" value="temporary_deactivation">
						<label for="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-temporary_deactivation" class="feedback-label">It's a
							temporary deactivation</label>
					</div>
					<span style="color:red;font-size: 13px;"></span>
				</div>
				<div class="rtsb-feedback-extra" style="display:none;">
					<p style="margin: 10px 0 15px 0;">
						Please let us know about any issues you are facing with the plugin.
						How can we improve the plugin?
					</p>
					<div class="feedback-text-wrapper-<?php echo esc_attr( $this->textdomain ); ?>">
						<textarea id="deactivation-feedback-<?php echo esc_attr( $this->textdomain ); ?>" rows="2" cols="40"
								  placeholder=" Write something here. How can we improve the plugin?"></textarea>
						<span style="display: block;color:red;font-size: 13px;margin-top: 5px;"></span>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/***
	 * @return mixed
	 */
	public function dialog_box_style() {
		?>
		<style>
			/* Add Animation */
			@-webkit-keyframes animatetop {
				from {
					top: -300px;
					opacity: 0
				}
				to {
					top: 0;
					opacity: 1
				}
			}

			@keyframes animatetop {
				from {
					top: -300px;
					opacity: 0
				}
				to {
					top: 0;
					opacity: 1
				}
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> {
				display: none;
			}

			.ui-dialog-titlebar-close {
				display: none;
			}

			/* The Modal (background) */
			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal {
				display: none; /* Hidden by default */
				position: fixed; /* Stay in place */
				z-index: 1; /* Sit on top */
				padding-top: 100px; /* Location of the box */
				left: 0;
				top: 0;
				width: 100%; /* Full width */
				height: 100%; /* Full height */
				overflow: auto; /* Enable scroll if needed */
			}

			/* Modal Content */
			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content {
				position: relative;
				margin: auto;
				padding: 0;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content > * {
				width: 100%;
				overflow: hidden;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content input.feedback-feedback-text {
				border: 1px solid #cbd5e1;
				min-width: 250px;
				border-radius: 6px;
			}

			.ui-dialog-title {
				font-size: 18px;
				font-weight: 600;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-body {
				padding: 2px 16px;
			}

			.ui-dialog-buttonset {
				background-color: #fefefe;
				padding: 0 17px 25px;
				display: flex;
				justify-content: space-between;
				gap: 10px;
			}

			.ui-dialog-buttonset button {
				min-width: 110px;
				text-align: center;
				border: 1px solid rgba(0, 0, 0, 0.1);
				padding: 0 15px;
				border-radius: 5px;
				height: 40px;
				font-size: 15px;
				font-weight: 600;
				display: inline-flex;
				align-items: center;
				justify-content: center;
				cursor: pointer;
				transition: 0.3s all;
				background: rgba(0, 0, 0, 0.02);
				margin: 0;
			}

			.ui-dialog-buttonset button:nth-child(2) {
				background: transparent;
			}

			.ui-dialog-buttonset button:hover {
				background: #2271b1;
				color: #fff;
			}

			.ui-dialog[aria-describedby="deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?>"] {
				background-color: #fefefe;
				box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
				z-index: 9999;
				position: fixed !important;
				top: 50% !important;
				left: 50% !important;
				transform: translate(-50%, -50%) !important;
				max-height: 90vh;
				overflow-y: auto;
			}

			.ui-dialog[aria-describedby="deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?>"] .ui-dialog-title {
				text-transform: uppercase;
				font-weight: 700;
				font-size: 16px;
				padding-left: 15px;
				padding-right: 15px;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> {
				padding: 30px !important;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .rtsb-support-link {
				display: inline-block;
				padding: 2px 8px;
				margin: 0 2px;
				background: rgba(216, 14, 14, 0.08);
				color: #d80e0e;
				font-weight: 600;
				border-radius: 4px;
				text-decoration: none;
				border: 1px solid rgba(216, 14, 14, 0.25);
				transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .rtsb-support-link:hover,
			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .rtsb-support-link:focus {
				background: #d80e0e;
				color: #fff;
				border-color: #d80e0e;
				text-decoration: none;
				outline: none;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .feedback-input-header {
				font-weight: 600;
				font-size: 14px;
				line-height: 1.5;
				margin-bottom: 16px;
				color: #1f2937;
			}

			div#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> p {
				font-size: 14px;
				color: #4b5563;
				line-height: 1.55;
			}

			div#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?>,
			.ui-draggable .ui-dialog-titlebar {
				padding: 18px 15px;
				box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
				text-align: left;
			}

			div#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content .feedback-input-wrapper {
				margin-bottom: 6px;
				display: flex;
				align-items: center;
				gap: 10px;
				padding: 10px 12px;
				font-size: 14px;
				border: 1px solid #e5e7eb;
				border-radius: 8px;
				background: #fff;
				cursor: pointer;
				transition: border-color 0.15s ease, background 0.15s ease, box-shadow 0.15s ease;
			}

			div#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content .feedback-input-wrapper:hover {
				border-color: #4360ef;
				background: rgba(67, 96, 239, 0.04);
			}

			div#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content .feedback-input-wrapper:has(input[type="radio"]:checked) {
				border-color: #4360ef;
				background: rgba(67, 96, 239, 0.06);
			}

			div#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content .feedback-input-wrapper .feedback-label {
				flex: 1;
				cursor: pointer;
				user-select: none;
				color: #1f2937;
				font-weight: 500;
				line-height: 1.4;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> input[type="radio"] {
				appearance: none;
				-webkit-appearance: none;
				width: 18px;
				height: 18px;
				border: 1.5px solid #cbd5e1;
				border-radius: 50%;
				background: #fff;
				cursor: pointer;
				position: relative;
				flex: 0 0 auto;
				box-sizing: border-box;
				outline: none;
				box-shadow: none;
				transition: border-color 0.15s ease;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> input[type="radio"]:hover,
			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> input[type="radio"]:focus,
			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> input[type="radio"]:focus-visible,
			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> input[type="radio"]:active {
				outline: none;
				box-shadow: none;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> input[type="radio"]:hover {
				border-color: #94a3b8;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> input[type="radio"]:checked {
				border-color: #4360ef;
				outline: none;
				box-shadow: none;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> input[type="radio"]:checked::after {
				content: '';
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				width: 8px;
				height: 8px;
				border-radius: 50%;
				background: #4360ef;
			}

			div#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content .feedback-input-wrapper.conditional {
				flex-wrap: wrap;
			}

			div#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content .feedback-input-wrapper.conditional .feedback-feedback-text {
				flex: 0 0 calc(100% - 28px);
				margin: 8px 0 0 28px;
				min-height: 38px;
				border: 1px solid #cbd5e1;
				border-radius: 6px;
				padding: 0 12px;
				font-size: 13px;
				transition: border-color 0.15s ease, box-shadow 0.15s ease;
			}

			div#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content .feedback-input-wrapper.conditional .feedback-feedback-text:focus {
				border-color: #4360ef;
				outline: none;
				box-shadow: 0 0 0 3px rgba(67, 96, 239, 0.15);
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .rtsb-feedback-extra {
				margin: 6px 0 10px;
				padding: 12px 14px;
				background: #f8fafc;
				border: 1px dashed #cbd5e1;
				border-radius: 8px;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .rtsb-feedback-extra p {
				margin: 0 0 8px !important;
				font-size: 13px !important;
				font-weight: 500;
				color: #4b5563;
				line-height: 1.4;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content textarea {
				border: 1px solid #cbd5e1;
				border-radius: 6px;
				padding: 8px 10px;
				width: 100%;
				min-height: 56px;
				max-height: 120px;
				font-size: 13px;
				line-height: 1.45;
				resize: vertical;
				transition: border-color 0.15s ease, box-shadow 0.15s ease;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content textarea:focus {
				border-color: #4360ef;
				outline: none;
				box-shadow: 0 0 0 3px rgba(67, 96, 239, 0.15);
			}

			.ui-widget-overlay.ui-front {
				position: fixed;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				z-index: 999;
				background-color: rgba(0, 0, 0, 0.5);
			}

			.ui-dialog[aria-describedby="deactivation-dialog-shopbuilder"] .ui-dialog-buttonset {
				background-color: #fefefe;
				box-shadow: none;
				z-index: 99;
				padding-left: 30px;
				padding-right: 30px;
			}

			.ui-dialog[aria-describedby="deactivation-dialog-shopbuilder"] .ui-dialog-buttonpane,
			.ui-dialog[aria-describedby="deactivation-dialog-shopbuilder"] .ui-widget-content {
				border: 0;
			}

			.ui-dialog[aria-describedby="deactivation-dialog-shopbuilder"] .ui-resizable-handle {
				display: none !important;
			}

			.ui-dialog[aria-describedby="deactivation-dialog-shopbuilder"] .ui-dialog-buttonset .ui-button {
				font-size: 12px;
				font-weight: 500;
				line-height: 1.2;
				padding: 8px 16px;
				outline: none;
				border: none;
			}

			.ui-dialog[aria-describedby="deactivation-dialog-shopbuilder"] .ui-dialog-buttonset .ui-button:first-child {
				background: #4360ef;
				color: #fff;
			}

			.ui-dialog[aria-describedby="deactivation-dialog-shopbuilder"] .ui-dialog-buttonset .ui-button:first-child:hover {
				background: #1f3edc;
			}

			.ui-dialog[aria-describedby="deactivation-dialog-shopbuilder"] .ui-dialog-buttonset .ui-button:last-child {
				background: none;
			}

			.ui-dialog[aria-describedby="deactivation-dialog-shopbuilder"] .ui-dialog-buttonset .ui-button:last-child:hover {
				background: #d80e0e;
				color: #fff;
			}

			/* Loading overlay shown while submitting feedback / deactivating. */
			.rtsb-deactivate-loader {
				position: absolute;
				inset: 0;
				display: flex;
				align-items: center;
				justify-content: center;
				background: rgba(255, 255, 255, 0.72);
				z-index: 100;
				border-radius: inherit;
			}

			.rtsb-deactivate-loader .rtsb-deactivate-spinner {
				width: 40px;
				height: 40px;
				border: 3px solid rgba(67, 96, 239, 0.2);
				border-top-color: #4360ef;
				border-radius: 50%;
				animation: rtsb-deactivate-spin 0.8s linear infinite;
			}

			@keyframes rtsb-deactivate-spin {
				to {
					transform: rotate(360deg);
				}
			}
		</style>

		<?php
	}

	/***
	 * @return void
	 */
	public function deactivation_scripts() {
		wp_enqueue_script( 'jquery-ui-dialog' );
		?>
		<script>
			jQuery(document).ready(function ($) {

				// Open the deactivation dialog when the 'Deactivate' link is clicked
				$('.deactivate #deactivate-shopbuilder').on('click', function (e) {
					e.preventDefault();
					var href = $('.deactivate #deactivate-shopbuilder').attr('href');
					$('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content input[name="reason_found_a_better_plugin"]').hide();
					var dialogbox = $('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?>').dialog({
						modal: true,
						width: 550,
						position: { my: "center", at: "center", of: window },
						show: {
							effect: "fadeIn",
							duration: 400
						},
						hide: {
							effect: "fadeOut",
							duration: 100
						},

						buttons: {
							Submit: function () {
								submitFeedback();
							},
							Cancel: function () {
								showDeactivateLoader();
								window.location.href = href;
							}
						}
					});

					// Keep the dialog centered on window resize.
					$(window).off('resize.rtsbDeactivate').on('resize.rtsbDeactivate', function () {
						if ( dialogbox.dialog('instance') ) {
							dialogbox.dialog('option', 'position', { my: 'center', at: 'center', of: window });
						}
					});


					// Make the entire feedback-input-wrapper card clickable to select its radio.
					dialogbox.on('click', '.feedback-input-wrapper', function (event) {
						// Ignore clicks on the inner radio/label (native behavior handles them) and the
						// "Found a better plugin" name input so typing inside it doesn't re-toggle.
						if ( $(event.target).is('input, label') ) {
							return;
						}
						var $radio = $(this).find('input[type="radio"]');
						if ( $radio.length && ! $radio.prop('checked') ) {
							$radio.prop('checked', true).trigger('change');
						}
					});

					// Close the dialog when clicking outside of it
					dialogbox.on('change', 'input[type="radio"]', function (event) {
						var $extra        = $('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .rtsb-feedback-extra');
						var $selectedRow  = $(this).closest('.feedback-input-wrapper');
						var reasons       = $('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> input[type="radio"]:checked').val();
						// Temporary deactivation does not need a feedback message — keep the textarea hidden.
						if ( 'temporary_deactivation' === reasons ) {
							$extra.stop( true, true ).slideUp(200);
						} else if ( $selectedRow.length ) {
							// Move the feedback textarea so it appears directly below the selected radio.
							$extra.hide().insertAfter( $selectedRow ).slideDown(200);
						} else {
							$extra.slideDown(200);
						}
						if( 'found_a_better_plugin' === reasons ){
							$('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content input[name="reason_found_a_better_plugin"]').show();
						} else {
							$('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content input[name="reason_found_a_better_plugin"]').hide();
						}
					});

					// Close the dialog when clicking outside of it
					$(document).on('click', '.ui-widget-overlay.ui-front', function (event) {
						if ($(event.target).closest(dialogbox.parent()).length === 0) {
							dialogbox.dialog('close');
						}
					});

					// Customize the button text
					$('.ui-dialog-buttonpane button:contains("Submit")').text('Submit & Deactivate');
					$('.ui-dialog-buttonpane button:contains("Cancel")').text('Skip & Deactivate');
				});

				// Show a loading spinner over the dialog while submitting /
				// deactivating, and lock the footer buttons to avoid double clicks.
				function showDeactivateLoader() {
					var $content = $('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?>');
					if ( ! $content.length || ! $content.hasClass('ui-dialog-content') ) {
						return;
					}
					var $widget = $content.dialog('widget');
					if ( ! $widget.find('.rtsb-deactivate-loader').length ) {
						$widget.append('<div class="rtsb-deactivate-loader"><span class="rtsb-deactivate-spinner"></span></div>');
					}
					$widget.find('.ui-dialog-buttonpane button').prop('disabled', true);
				}

				// Submit the feedback
				function submitFeedback() {
					var href = $('.deactivate #deactivate-shopbuilder').attr('href');
					var reasons = $('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> input[type="radio"]:checked').val();
					var feedback = $('#deactivation-feedback-<?php echo esc_attr( $this->textdomain ); ?>').val();
					var better_plugin = $('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content input[name="reason_found_a_better_plugin"]').val();
					// Perform AJAX request to submit feedback
					if (!reasons && !feedback && !better_plugin) {
						// Define flag variables
						$('#feedback-form-body-<?php echo esc_attr( $this->textdomain ); ?> span').text('Choose The Reason');
						$('.feedback-text-wrapper-<?php echo esc_attr( $this->textdomain ); ?> span').text('Please provide more details regarding the issue so we can address it in future updates.');
						return;
					}

					if (!reasons ) {
						// Define flag variables
						$('#feedback-form-body-<?php echo esc_attr( $this->textdomain ); ?> span').text('Choose The Reason');
						$('.feedback-text-wrapper-<?php echo esc_attr( $this->textdomain ); ?> span').text('Please provide more details regarding the issue so we can address it in future updates.');
						return;
					}

					if ( 'bug_issue_detected' === reasons && !feedback ) {
						// Define flag variables
						$('.feedback-text-wrapper-<?php echo esc_attr( $this->textdomain ); ?> span').text('Please provide more details regarding the issue so we can address it in future updates.');
						return;
					}

					if ('temporary_deactivation' === reasons || ! feedback ) {
						showDeactivateLoader();
						window.location.href = href;
						return;
					}

					if ( ! feedback.length > 0 ) {
						showDeactivateLoader();
						window.location.href = href;
						return;
					}
					var websiteUrl = '<?php echo esc_url( home_url() ); ?>';
					if ( ! websiteUrl.length > 0 ) {
						showDeactivateLoader();
						window.location.href = href;
						return;
					}
					showDeactivateLoader();
					$.ajax({
						url: 'https://shopbuilderwp.com/wp-json/RadiusTheme/pluginSurvey/v1/Survey/appendToSheet',
						method: 'GET',
						dataType: 'json',
						// Feedback is best-effort: if the endpoint is slow/unreachable,
						// fail fast so the user isn't stuck on the loader. `complete`
						// runs on success, error and timeout, so deactivation always
						// proceeds (redirects to the deactivate link) regardless.
						timeout: 5000,
						data: {
							website: websiteUrl,
							reasons: reasons ? reasons : '',
							better_plugin: better_plugin,
							feedback: feedback,
							wpplugin: 'ShopBuilder',
							version: '<?php echo esc_js( defined( 'RTSB_VERSION' ) ? RTSB_VERSION : '' ); ?>',
							date: '<?php echo esc_js( gmdate( 'M j, Y' ) ); ?>',
						},
						success: function (response) {
						},
						error: function (xhr, status, error) {
							// Handle the error response
							console.error('Error', error);
						},
						complete: function (xhr, status) {
							$('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?>').dialog('close');
							window.location.href = href;
						}

					});
				}

			});

		</script>

		<?php
	}
}
