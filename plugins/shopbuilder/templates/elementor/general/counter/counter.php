<?php
/**
 * Template: Counter Box
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $counter_count                   integer
 * @var $counter_transition              integer
 * @var $counter_title                   string
 * @var $icon_position                   string
 * @var $counter_title_html_tag          string
 * @var $icon_html                       string
 * @var $icon_type                       string
 * @var $icon_bg_type                    string
 * @var $prefix                          string
 * @var $suffix                          string
 * @var $has_prefix                      bool
 * @var $has_suffix                      bool
 */

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Widgets\General\Counter\Render;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
?>
<div class="rtsb-counter-wrapper">
	<div class="counter-inner <?php echo esc_attr( $icon_position ); ?>">
		<?php if ( ! empty( $icon_html ) ) { ?>
			<div class="counter-icon-holder <?php echo esc_attr( $icon_type ); ?>">
				<div class="icon-wrap">
					<?php Fns::print_html( $icon_html ); ?>
				</div>
			</div>
		<?php } ?>
		<div class="rtsb-counter-content">
			<?php
			/**
			 * Number.
			 */
			?>
			<?php

			if ( ! empty( $counter_count ) ) {
				Fns::print_html( Render::render_count_number( $counter_count, $has_prefix, $prefix, $has_suffix, $suffix, $counter_transition ) );
			}

			?>
			<?php
			/**
			 * Title.
			 */
			?>
			<<?php Fns::print_validated_html_tag( $counter_title_html_tag ); ?> class="rtsb-counter-title">
			<?php Fns::print_html( $counter_title ); ?>
			</<?php Fns::print_validated_html_tag( $counter_title_html_tag ); ?>>
		</div>
	</div>
</div>
