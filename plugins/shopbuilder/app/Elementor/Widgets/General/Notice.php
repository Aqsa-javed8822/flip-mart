<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\NoticeSettings;
use RadiusTheme\SB\Helpers\BuilderFns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class Notice extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Notice', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-wc-notice';
		parent::__construct( $data, $args );
		$this->rtsb_category = 'rtsb-shopbuilder-general';
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return NoticeSettings::widget_fields( $this );
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'Notice' ] + parent::get_keywords();
	}

	/**
	 * @return void
	 */
	public function apply_the_hooks() {

	}

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		$this->apply_the_hooks();
		$this->theme_support();

		if ( $this->is_edit_mode() ) {
			$all_notices = [
				'success' => [
					[
						'notice' => esc_html__( 'This is demo preview for success notice.', 'shopbuilder' ) . '<a href="#" tabindex="1" class="button wc-forward">' . esc_html__( 'Demo Button', 'shopbuilder' ) . '</a>',
						'data'   => [],
					],
				],
				'error'   => [
					[
						'notice' => esc_html__( 'This is demo preview for error notice.', 'shopbuilder' ),
						'data'   => [],
					],
				],
				'notice'  => [
					[
						'notice' => esc_html__( 'This is demo preview for info notice.', 'shopbuilder' ),
						'data'   => [],
					],
				],
			];

			$notice_types = [ 'success', 'error', 'notice' ];

			 echo '<div class="rtsb-notice-widget"><div class="rtsb-notice"><div class="woocommerce-notices-wrapper">';

			foreach ( $notice_types as $notice_type ) {
				$messages = [];

				foreach ( $all_notices[ $notice_type ] as $notice ) {
					$messages[] = $notice['notice'] ?? $notice;
				}

				wc_get_template(
					"notices/{$notice_type}.php",
					[
						'messages' => $messages,
						'notices'  => $all_notices[ $notice_type ],
					]
				);
			}
			echo '</div></div>';
		} else {
			echo '<div class="rtsb-notice-widget">';
				Fns::woocommerce_output_all_notices();
			echo '</div>';
		}

		$this->theme_support( 'render_reset' );
	}
}
