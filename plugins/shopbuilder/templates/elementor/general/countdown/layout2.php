<?php
/**
 * Template: CountDown General Widget Layout
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $countdown_expire_time                string
 * @var $rtsb_countdown_layout6_html          string

 */


// Do not allow directly accessing this file.
use RadiusTheme\SB\Helpers\Fns;


if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
?>
<div class="rtsb-countdown-wrapper">
	<div class="rtsb-countdown rtsbcd2" data-deadline-date-time="<?php echo esc_attr( $countdown_expire_time ); ?>">

		<?php
		Fns::print_html( $rtsb_countdown_layout6_html );
		?>

	</div>
</div>
