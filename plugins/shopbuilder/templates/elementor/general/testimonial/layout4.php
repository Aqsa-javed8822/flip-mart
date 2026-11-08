<?php
/**
 * Template: Testimonial
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $testimonial               array
 * @var $instance                  object
 * @var $has_designation           boolean
 * @var $display_author_image      boolean
 * @var $display_quote_icon        boolean
 * @var $has_rating                boolean
 * @var $display_author_rating     boolean
 * @var $author_designation        string
 * @var $author_name               string
 * @var $author_description        string
 * @var $author_name_html_tag       string
 * @var $author_rating             string
 * @var $grid_classes              string
 * array
 */

// Do not allow directly accessing this file.

use RadiusTheme\SB\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
?>
<div class="<?php echo esc_attr( $grid_classes ); ?>">
	<div class="rtsb-testimonial-item">
		<div class="rtsb-testimonial-content">
			<?php if ( $has_rating ) { ?>
				<div class="rtsb-rating">
					<div class="rtsb-rating-inner">
						<div class="rtsb-star-rating">
							<span style="--rtsb-testimonial-rating:<?php echo esc_attr( $author_rating * 20 ); ?>%"></span>
						</div>
					</div>
				</div>
			<?php } ?>
			<?php if ( $author_description ) { ?>
				<div class="rtsb-testimonial-description">
					<?php Fns::print_html( $author_description ); ?>
				</div>
			<?php } ?>
		</div>
		<div class="rtsb-testimonial-author">
			<?php if ( $display_author_image ) { ?>
				<div class="rtsb-testimonial-author-img">
					<?php Fns::print_html( $instance->render_author_image( $testimonial ) ); ?>
				</div>
			<?php } ?>
			<?php if ( $author_name ) { ?>
			<<?php Fns::print_validated_html_tag( $author_name_html_tag ); ?> class="rtsb-testimonial-author-name rtsb-tag">
				<?php Fns::print_html( $author_name ); ?>
			</<?php Fns::print_validated_html_tag( $author_name_html_tag ); ?>>
			<?php } ?>
			<?php if ( $has_designation ) { ?>
				<span class="rtsb-testimonial-author-designation"><?php Fns::print_html( $author_designation ); ?></span>
			<?php } ?>
		</div>
	</div>
</div>

