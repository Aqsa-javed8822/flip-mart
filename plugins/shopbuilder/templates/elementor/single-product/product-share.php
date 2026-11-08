<?php
/**
 * Template variables:
 *
 * @var $controllers  array settings as array
 * @var $content  string
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use RadiusTheme\SB\Helpers\Fns;

global $product, $post;

if ( empty( $product ) ) {
	return;
}
?>

<div class="rtsb-product-share">
	<?php Fns::print_html( $content , true ); //  phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
