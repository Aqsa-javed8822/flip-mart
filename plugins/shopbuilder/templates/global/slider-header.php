<?php
/**
 * Template variables:
 *
 * @var $controllers  array settings as array
 * @var $swiper_json_data  array settings as array
 * @var $classes  array settings as array
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

?>
<div class="rtsb-carousel-slider <?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-options="<?php echo esc_attr( $swiper_json_data ); ?>">
