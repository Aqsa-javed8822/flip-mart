<?php
/**
 * Template: Team Members
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $team_member               array
 * @var $instance                  object
 * @var $display_social_profile    boolean
 * @var $has_image_link            boolean
 * @var $has_member_name_link      boolean
 * @var $has_designation           boolean
 * @var $has_description           boolean
 * @var $member_designation        string
 * @var $image_link                string
 * @var $member_name_link          string
 * @var $member_name               string
 * @var $member_bio                string
 * @var $member_name_html_tag      string
 * @var $grid_classes              string
 * @var $member_item_classes       string
 * array
 */

// Do not allow directly accessing this file.

use RadiusTheme\SB\Elementor\Widgets\General\TeamMember\Render;
use RadiusTheme\SB\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
?>
<div class="<?php echo esc_attr( $grid_classes ); ?>">
	<div class="<?php echo esc_attr( $member_item_classes ); ?>">
		<div class="rtsb-team-inner">
			<div class="rtsb-team-img rtsb-img-wrap">
				<?php
				if ( $has_image_link ) {
					?>
					<a <?php Fns::print_html( $image_link ); ?>>
						<?php Fns::print_html( $instance->render_member_image( $team_member ) ); ?>
					</a>
					<?php
				} else {
					Fns::print_html( $instance->render_member_image( $team_member ) );
				}
				?>
				<?php
				if ( $display_social_profile ) {
					$instance->render_social_icon( $team_member );
				}
				?>
			</div>
			<div class="rtsb-team-content">
				<?php
				if ( $member_name ) {
					if ( $has_member_name_link ) {
						?>
					<<?php Fns::print_validated_html_tag( $member_name_html_tag ); ?> class="rtsb-team-member-name rtsb-tag">
					<a <?php Fns::print_html( $member_name_link ); ?>>
						<?php Fns::print_html( $member_name ); ?>
					</a>
					</<?php Fns::print_validated_html_tag( $member_name_html_tag ); ?>>
						<?php
					} else {
						?>
					<<?php Fns::print_validated_html_tag( $member_name_html_tag ); ?> class="rtsb-team-member-name rtsb-tag">
						<?php Fns::print_html( $member_name ); ?>
					 </<?php Fns::print_validated_html_tag( $member_name_html_tag ); ?>>
						<?php
					}
					?>
				 <?php } ?>
				<?php if ( $has_designation && $member_designation ) { ?>
					<span class="rtsb-team-member-designation">
						<?php Fns::print_html( $member_designation ); ?>
					</span>
				<?php } ?>
				<?php
				if ( $has_description && $member_bio ) {
					?>
					<div class="rtsb-content">
						<?php Fns::print_html( $member_bio ); ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

