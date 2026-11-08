<?php
/**
 * Template variables:
 *
 * @var $content  String
 */

// Do not allow directly accessing this file.
use RadiusTheme\SB\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

?>
<div class="rtsb-product-stock">
	<?php Fns::print_html( $content, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
