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

?>
<div class="rtsb-product-comment">
	<?php comments_template(); ?>
</div>
