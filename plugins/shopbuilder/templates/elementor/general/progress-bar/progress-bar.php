<?php
/**
 * Template: Progress Bar
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $has_button                         bool
 * @var $wrapper_class                string
 * @var $rtsb_progress_bar_html               string
 * @var $has_description                    bool
 */

use RadiusTheme\SB\Elementor\Widgets\General\ProgressBar\Render;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

?>
<div class="rtsb-progressbar-wrapper <?php echo esc_attr( $wrapper_class ); ?>">
	<?php Fns::print_html( $rtsb_progress_bar_html ); ?>
</div>
