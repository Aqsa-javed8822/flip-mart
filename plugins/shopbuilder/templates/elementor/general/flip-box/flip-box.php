<?php
/**
 * Template: Flip Box
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $flip_behavior                     string
 * @var $flip_direction                    string
 * @var $flip_selected_side                string
 * @var $flip_animate_class                string
 * @var $flip_box_front_title              string
 * @var $flip_box_back_title               string
 * @var $flip_box_front_title_html_tag     string
 * @var $flip_box_back_title_html_tag      string
 * @var $has_front_button                  bool
 * @var $has_back_button                   bool
 * @var $front_description                 string
 * @var $back_description                  string
 * @var $has_front_description             bool
 * @var $has_back_description              bool
 * @var $sb_front_button_content           string
 * @var $sb_back_button_content            string
 * @var $sb_front_button_icon              array
 * @var $sb_back_button_icon               array
 * @var $sb_front_button_icon_position     string
 * @var $sb_back_button_icon_position      string
 * @var $front_button_attributes           string
 * @var $back_button_attributes            string
 * @var $front_side_icon_html              string
 * @var $back_side_icon_html               string
 * @var $front_icon_type                   string
 * @var $back_icon_type                    string
 */

use RadiusTheme\SB\Elementor\Render\GeneralAddons;
use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Widgets\General\CallToAction\Render;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
?>
<div class="rtsb-flipbox-wrapper">
	<div class="rtsb-fliper <?php echo esc_attr( $flip_animate_class ); ?>" data-flip-behavior="<?php echo esc_attr( $flip_behavior ); ?>" data-flip-view="<?php echo esc_attr( $flip_selected_side ); ?>">
		<div class="rtsb-fliper-wrapper">
			<div class="rtsb-flip-front">
				<div class="rtsb-flip-inner">
					<div class="rtsb-flipbox">
						<?php if ( ! empty( $front_side_icon_html ) ) { ?>
							<div class="flip-box-icon-holder <?php echo esc_attr( $front_icon_type ); ?>">
								<div class="icon-wrap">
									<?php Fns::print_html( $front_side_icon_html ); ?>
								</div>
							</div>
						<?php } ?>
						<div class="flip-box-content">
							<?php
							/**
							 * Title.
							 */
							if ( $flip_box_front_title ) {
								?>
								<<?php Fns::print_validated_html_tag( $flip_box_front_title_html_tag ); ?> class="rtsb-flip-box-title">
								<?php Fns::print_html( $flip_box_front_title ); ?>
								</<?php Fns::print_validated_html_tag( $flip_box_front_title_html_tag ); ?>>
							<?php } ?>
							<?php
							/**
							 * Description.
							 */
							if ( $has_front_description ) {
								?>
								<div class="rtsb-flip-box-description">
									<?php Fns::print_html( $front_description ); ?>
								</div>
								<?php
							}
							?>
							<?php
							/**
							 * Button.
							 */
							if ( $has_front_button ) {
								?>
								<div class="rtsb-shopbuilder-button">
									<?php

									GeneralAddons::render_buttom_html(
										[
											'button_attributes' => $front_button_attributes,
											'sb_button_icon' => $sb_front_button_icon,
											'sb_button_icon_position' => $sb_front_button_icon_position,
											'sb_button_content' => $sb_front_button_content,
										]
									);

									?>

								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<div class="rtsb-flip-back">
				<div class="rtsb-flip-inner">
					<div class="rtsb-flipbox">
						<?php if ( ! empty( $back_side_icon_html ) ) { ?>
							<div class="flip-box-icon-holder <?php echo esc_attr( $back_icon_type ); ?>">
								<div class="icon-wrap">
									<?php Fns::print_html( $back_side_icon_html ); ?>
								</div>
							</div>
						<?php } ?>
						<div class="flip-box-content">
							<?php
							/**
							 * Title.
							 */
							if ( $flip_box_back_title ) {
								?>
								<<?php Fns::print_validated_html_tag( $flip_box_back_title_html_tag ); ?> class="rtsb-flip-box-title">
								<?php Fns::print_html( $flip_box_back_title ); ?>
								</<?php Fns::print_validated_html_tag( $flip_box_back_title_html_tag ); ?>>
							<?php } ?>
							<?php
							/**
							 * Description.
							 */
							if ( $has_back_description ) {
								?>
								<div class="rtsb-flip-box-description">
									<?php Fns::print_html( $back_description ); ?>
								</div>
								<?php
							}
							?>
							<?php
							/**
							 * Button.
							 */
							if ( $has_back_button ) {
								?>
								<div class="rtsb-shopbuilder-button">
									<?php

									GeneralAddons::render_buttom_html(
										[
											'button_attributes' => $back_button_attributes,
											'sb_button_icon' => $sb_back_button_icon,
											'sb_button_icon_position' => $sb_back_button_icon_position,
											'sb_button_content' => $sb_back_button_content,
										]
									);

									?>

								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

