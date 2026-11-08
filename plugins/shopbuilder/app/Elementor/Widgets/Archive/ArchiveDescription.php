<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Archive;

use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\ArchiveDescSettings;
use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ArchiveDescription extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Archive Description', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-archive-description';
		parent::__construct( $data, $args );
	}
	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return ArchiveDescSettings::widget_fields( $this );
	}
	/**
	 * Set Widget Keyword.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'Archive' ] + parent::get_keywords();
	}
	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		$controllers = $this->get_settings_for_display();
        $this->theme_support();
		ob_start();
		if ( BuilderFns::is_builder_preview() ) {
			?>
			<p> <?php echo esc_html__( 'The dummy text based paragraph. It only appears on Elementor editor and demo page help you to customize your description', 'shopbuilder' ); ?> </p>
			<?php
		} else {
			/**
			 * Hook: woocommerce_archive_description.
			 *
			 * @hooked woocommerce_taxonomy_archive_description - 10
			 * @hooked woocommerce_product_archive_description - 10
			 */
			echo get_the_archive_description(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		$content = ob_get_clean();
		$data    = [
			'template'    => 'elementor/archive/archive-description',
			'controllers' => $controllers,
			'content'     => $content,
		];
		Fns::load_template( $data['template'], $data );
        $this->theme_support( 'render_reset' );

	}

}
