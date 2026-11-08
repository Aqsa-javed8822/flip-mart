<?php
/**
 * Main ProductDescription class.
 *
 * @package RadiusTheme\SB
 */

namespace RadiusTheme\SB\Elementor\Widgets\General;

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Abstracts\ElementorWidgetBase;
use RadiusTheme\SB\Elementor\Widgets\Controls\BreadcrumbsSettings;
use RadiusTheme\SB\Helpers\BuilderFns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Product Description class
 */
class ProductBreadcrumbs extends ElementorWidgetBase {
	/**
	 * Construct function
	 *
	 * @param array $data default array.
	 * @param mixed $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->rtsb_name = esc_html__( 'Breadcrumbs', 'shopbuilder' );
		$this->rtsb_base = 'rtsb-product-breadcrumbs';
		parent::__construct( $data, $args );
		$this->rtsb_category = 'rtsb-shopbuilder-general';
	}

	/**
	 * Widget Field
	 *
	 * @return array
	 */
	public function widget_fields() {
		return BreadcrumbsSettings::widget_fields( $this );
	}

	/**
	 * Render Function
	 *
	 * @return void
	 */
	protected function render() {
		global  $post;

		$_post       = $post;
		$controllers = $this->get_settings_for_display();
		$this->theme_support();
		$controllers['breadcrumbs_icon'] = Fns::icons_manager( $controllers['breadcrumbs_icon'] );
		$args                            = [
			'delimiter'   => ! empty( $controllers['breadcrumbs_icon'] ) ? '<span class="breadcrumb-separator">' . $controllers['breadcrumbs_icon'] . '</span>' : '<span class="breadcrumb-separator">&nbsp;&#47;&nbsp;</span>',
			'wrap_before' => '<nav class="woocommerce-breadcrumb">',
			'wrap_after'  => '</nav>',
		];
		$data                            = [
			'template'    => 'elementor/general/breadcrumbs',
			'controllers' => $controllers,
			'args'        => $args,
		];

		if ( BuilderFns::is_builder_preview() ) {
			// Overriding Global.
			$post = get_post( Fns::get_prepared_product_id() ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}

		Fns::load_template( $data['template'], $data );
		$this->theme_support( 'render_reset' );
		$post = $_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}
}
