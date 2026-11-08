<?php
/**
 * Template: Dropcaps
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $drop_content                   string
 */

use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
?>
<div class="rtsb-dropcaps-wrap">
	<div class="rtsb-dropcaps-des-wrap">
		<?php Fns::print_html( $drop_content ); ?>
	</div>
</div>
