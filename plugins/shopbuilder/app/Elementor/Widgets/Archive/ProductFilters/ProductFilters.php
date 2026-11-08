<?php
/**
 * Product Filters class
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\Archive\ProductFilters;

// Do not allow directly accessing this file.
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Helper\RenderHelpers;
use RadiusTheme\SB\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Filters class
 */
class ProductFilters extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Product Filters', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-product-filters';
		parent::__construct( $data, $args );
	}

	/**
	 * Keywords
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'filters', 'product filters', 'archive filters', 'shop filters' ] + parent::get_keywords();
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return Controls::widget_fields( $this );
	}

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->theme_support();

		$layout    = 'products-filters';
		$templates = [
			'layout'   => 'elementor/archive/default-product-filters/' . $layout,
			'checkbox' => 'elementor/archive/default-product-filters/checkbox',
			'radio'    => 'elementor/archive/default-product-filters/radio',
			'color'    => 'elementor/archive/default-product-filters/color',
			'button'   => 'elementor/archive/default-product-filters/button',
			'image'    => 'elementor/archive/default-product-filters/image',
			'rating'   => 'elementor/archive/default-product-filters/rating',
			'price'    => 'elementor/archive/default-product-filters/price',
		];

		Fns::print_html( RenderHelpers::filters_view( $templates, $settings ), true );
		$this->edit_mode_default_filter_script();
		$this->theme_support( 'render_reset' );
	}

	/**
	 * Edit Mode Default Filter Script
	 *
	 * @return void
	 */
	private function edit_mode_default_filter_script() {
		if ( ! $this->is_edit_mode() ) {
			return;
		}
		?>
		<script type="text/javascript">
			if (!'<?php echo esc_attr( Fns::is_optimization_enabled() ); ?>') {
				setTimeout(function() {
					rtsbProductDefaultFilterInit();
				}, 1000);
			} else {
				if (typeof elementorFrontend !== 'undefined') {
					elementorFrontend.hooks.addAction(
						'frontend/element_ready/rtsb-product-filters.default',
						() => {
							window.waitForRTSB((RTSB) => {
								RTSB.modules.get('productFilters')?.refresh();
							});
						}
					);
				}
			}
		</script>
		<?php
	}
}
