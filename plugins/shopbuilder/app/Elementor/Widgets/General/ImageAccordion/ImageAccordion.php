<?php
/**
 * AdvancedHeading class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General\ImageAccordion;

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
class ImageAccordion extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Image Accordion', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-image-accordion-addon';

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
		return [ 'Image Accordion','Accordion' ] + parent::get_keywords();
	}

	/**
	 * Style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends(): array {
		return [
			'elementor-icons-shared-0',
			'elementor-icons-fa-solid',
			'photoswipe-default-skin',
			'photoswipe',
			'rtsb-general-addons',
		];
	}
	/**
	 * Scripts dependencies.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [
			'photoswipe',
			'wc-photoswipe-ui-default',
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
			case 'rtsb-image-accordion-layout2':
				$template = 'layout2';
				break;
			default:
				$template = 'layout1';
				break;
		}
		$template = apply_filters( 'rtsb/general_widget/image_accordion/template', $template, $settings );
		$data     = [
			'template'        => 'elementor/general/image-accordion/' . $template,
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
		$this->edit_mode_image_accordion_script();
		$this->theme_support( 'render_reset' );
	}

	/**
	 * Edit mode image accordion script.
	 *
	 * @return void
	 */
	private function edit_mode_image_accordion_script() {
		if ( ! $this->is_edit_mode() ) {
			return;
		}
		?>
		<script type="text/javascript">
			if (!'<?php echo esc_attr( Fns::is_optimization_enabled() ); ?>') {
				setTimeout(function() {
					rtsbImageAccordionInit();
				}, 1000);
			} else {
				if (typeof elementorFrontend !== 'undefined') {
					elementorFrontend.hooks.addAction(
						'frontend/element_ready/rtsb-image-accordion-addon.default',
						() => {
							window.waitForRTSB((RTSB) => {
								RTSB.modules.get('imageAccordion')?.refresh();
							});
						}
					);
				}
			}
		</script>

		<?php
	}
}
