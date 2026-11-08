<?php
/**
 * Template variables:
 *
 * @var $controllers  array Widgets/Addons Settings
 * @var $content  string Widget/Addons Content
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use RadiusTheme\SB\Helpers\Fns;

global $product;
if ( empty( $product ) ) {
	return;
}

$view_mode = ! empty( $controllers['view_mode'] ) ? $controllers['view_mode'] : 'inline';

?>

<div class="rtsb-actions-button rtsb-actions-button-widgets action-button-wrapper button-display-<?php echo esc_attr( $view_mode ); ?>">
	<?php Fns::print_html( $content , true ); ?>
</div>
