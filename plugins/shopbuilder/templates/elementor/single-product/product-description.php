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

global $post;
if ( empty( $post ) ) {
	return;
}

?>
<div class="rtsb-description">
	<?php
	echo '<div class="woocommerce_product_description">';
	$the_content = apply_filters( 'the_content', get_the_content( '', '', $post->ID ) );
	if ( ! empty( $the_content ) ) {
		echo wp_kses_post( $the_content );
	}
	echo '</div>';
	?>
</div>
