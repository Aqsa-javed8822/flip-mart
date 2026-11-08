<?php
/**
 * Template variables:
 *
 * @var $controllers  array settings as array
 * @var $left_arrow   string Arrow icon html
 * @var $right_arrow  string Arrow icon html
 */

use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

if ( ! empty( $controllers['show_arrows'] ) ) {  ?>
	<!-- If we need navigation buttons -->
	<span class="rtsb-slider-btn button-left rtsb-icon-angle-left">
		<?php Fns::print_icon( $left_arrow ); ?>
	</span>
	<span class="rtsb-slider-btn button-right rtsb-icon-angle-right">
		<?php Fns::print_icon( $right_arrow ); ?>
	</span>
<?php } ?>
<?php if ( ! empty( $controllers['show_dots'] ) ) { ?>
	<!-- If we need pagination -->
	<div class="rtsb-slider-pagination"></div>
<?php } ?>

</div>
