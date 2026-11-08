<?php
/**
 * Template variables:
 *
 * @var $controllers  array Widgets/Addons Settings
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
?>
<div class="rtsb-archive-title">
	<?php
		$htmltag = ! empty( $controllers['archive_title_html_tag'] ) ? $controllers['archive_title_html_tag'] : 'h2';
		printf( '<%1$s class="archive-title">%2$s</%1$s>', esc_html( $htmltag ), woocommerce_page_title( false ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
</div>
