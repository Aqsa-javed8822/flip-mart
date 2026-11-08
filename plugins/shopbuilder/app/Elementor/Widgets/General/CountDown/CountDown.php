<?php
/**
 * AdvancedHeading class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\CountDown;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Dropcaps class.
 *
 * @package RadiusTheme\SB
 */
class CountDown extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Countdown', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-countdown-addon';

		parent::__construct( $data, $args );

		$this->rtsb_category = 'rtsb-shopbuilder-general';
	}
	/**
	 * Whether the element returns dynamic content.
	 *
	 * @return bool
	 */
	protected function is_dynamic_content(): bool {
		return false;
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return array_merge(
			Controls::content( $this ),
			Controls::style( $this )
		);
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'CountDown','Countdown' ] + parent::get_keywords();
	}

	/**
	 * Style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends(): array {
		return [
			'rtsb-general-addons',
			'elementor-icons-shared-0',
			'elementor-icons-fa-solid',
		];
	}


	/**
	 * Addon Render.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		switch ( $settings['layout_style'] ) {
			case 'rtsb-countdown-layout6':
				$template = 'layout2';
				break;
			default:
				$template = 'layout1';
				break;
		}
		$template = apply_filters( 'rtsb/general_widget/countdown/template', $template, $settings );
		$data     = [
			'template'        => 'elementor/general/countdown/' . $template,
			'id'              => $this->get_id(),
			'unique_name'     => $this->get_unique_name(),
			'layout'          => $settings['layout_style'],
			'settings'        => $settings,
			'is_carousel'     => false,
			'container_class' => '',
			'content_class'   => '',
		];

		// Render initialization.
		$this->theme_support();

		// Call the template rendering method.
		Fns::print_html( ( new Render() )->display_content( $data, $settings ), true );
		$this->edit_mode_countdown_script();
		$this->theme_support( 'render_reset' );
	}

	/**
	 * Edit mode script.
	 *
	 * @return void
	 */
	private function edit_mode_countdown_script() {
		if ( ! $this->is_edit_mode() ) {
			return;
		}
		?>
		<script>
			if (!'<?php echo esc_attr( Fns::is_optimization_enabled() ); ?>') {
				setTimeout(function() {
					rtsbCountdownAddonInit();
				}, 1000);
			} else {
				if (typeof elementorFrontend !== 'undefined') {
					elementorFrontend.hooks.addAction(
						'frontend/element_ready/rtsb-countdown-addon.default',
						() => {
							window.waitForRTSB((RTSB) => {
								RTSB.modules.get('generalCountdown')?.refresh();
							});
						}
					);
				}
			}
		</script>

		<?php
	}
}
