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
?>
<div class="rtsb-product-title">
	<?php
		$htmltag = ! empty( $controllers['product_title_html_tag'] ) ? $controllers['product_title_html_tag'] : 'h2';
		printf( '<%1$s class="product_title entry-title">%2$s</%1$s>', esc_html( $htmltag ), get_the_title() ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
</div>
