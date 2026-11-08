<?php
/**
 * Template variables:
 *
 * @var $tax_type          string        Taxonomy type.
 * @var $attr_type         string        Attribute type.
 * @var $input             string        Input type.
 * @var $title             array         Filter title.
 * @var $template          string        Filter template.
 * @var $raw_settings      array         Widgets/Addons Settings
 * @var $repeater_settings array         Repeater Settings
 */

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Helper\RenderHelpers;


// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
$min_price_label   = $repeater_settings['min_price_label'];
$max_price_label   = $repeater_settings['max_price_label'];
$default_min_price = ! empty( $_GET['min_price'] ) ? sanitize_text_field( wp_unslash( $_GET['min_price'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$default_max_price = ! empty( $_GET['max_price'] ) ? sanitize_text_field( wp_unslash( $_GET['max_price'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
?>

<div class="rtsb-product-default-filters rtsb-price-filter">
	<?php
	/**
	 * Filter title.
	 */
	Fns::print_html( RenderHelpers::get_default_filter_title( $title ) );
	?>
	<div class="default-filter-content">
		<div class="price-inputs">
			<div class="min-price-wrapper">
				<label for="default-filter-min-price"><?php Fns::print_html( $min_price_label ); ?></label>
				<input class="filter-price-field min-price" min="0" id="default-filter-min-price" type="number" value="<?php echo esc_attr( $default_min_price ); ?>" name="rtsb-price-filter-min" />
			</div>
			<div class="max-price-wrapper">
				<label for="default-filter-max-price"><?php Fns::print_html( $max_price_label ); ?></label>
				<input class="filter-price-field max-price" id="default-filter-max-price" value="<?php echo esc_attr( $default_max_price ); ?>" type="number" name="rtsb-price-filter-max"/>
			</div>
		</div>
	</div>
</div>
