<?php
/**
 * Template variables:
 *
 * @var $controllers  array settings as array
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

global $product, $post;
if ( empty( $product ) ) {
	return;
}

$layout = ! empty( $controllers['layout_style'] ) ? $controllers['layout_style'] : '';


?>

<div class="rtsb-product-tabs tabs-<?php echo esc_attr( $layout ); ?>">
    <?php woocommerce_output_product_data_tabs(); ?>
</div>
<!--    single2_rt_product_tab -->
<?php

