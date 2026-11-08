<?php
/**
 * Template variables:
 *
 * @var $controllers  array settings as array
 * @var $args  array
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}


echo '<div class="rtsb-breadcrumb">';
	woocommerce_breadcrumb( $args );
echo '</div>';
