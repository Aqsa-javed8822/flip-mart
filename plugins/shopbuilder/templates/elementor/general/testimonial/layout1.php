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
$quote_icon = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 512 512" width="1em" height="1em" fill="currentColor"><path d="M464 32H336c-26.5 0-48 21.5-48 48v128c0 26.5 21.5 48 48 48h80v64c0 35.3-28.7 64-64 64h-8c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24h8c88.4 0 160-71.6 160-160V80c0-26.5-21.5-48-48-48zm-288 0H48C21.5 32 0 53.5 0 80v128c0 26.5 21.5 48 48 48h80v64c0 35.3-28.7 64-64 64h-8c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24h8c88.4 0 160-71.6 160-160V80c0-26.5-21.5-48-48-48z"></path></svg>';
?>

<div class="<?php echo esc_attr( $grid_classes ); ?>">
	<div class="rtsb-testimonial-item">
		<div class="rtsb-testimonial-author">
			<?php if ( $display_author_image ) { ?>
				<div class="rtsb-testimonial-author-img">
					<?php Fns::print_html( $instance->render_author_image( $testimonial ) ); ?>
				</div>
			<?php } ?>
			<div class="rtsb-testimonial-author-content">
				<?php if ( $author_name ) { ?>
				<<?php Fns::print_validated_html_tag( $author_name_html_tag ); ?> class="rtsb-testimonial-author-name rtsb-tag">
					<?php Fns::print_html( $author_name ); ?>
				</<?php Fns::print_validated_html_tag( $author_name_html_tag ); ?>>
				<?php } ?>

				<?php if ( $has_designation ) { ?>
					<span class="rtsb-testimonial-author-designation"><?php Fns::print_html( $author_designation ); ?></span>
				<?php } ?>

				<?php if ( $has_rating ) { ?>
					<div class="rtsb-rating">
						<div class="rtsb-rating-inner">
							<div class="rtsb-star-rating">
								<span style="--rtsb-testimonial-rating:<?php echo esc_attr( $author_rating * 20 ); ?>%"></span>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			<?php
			if ( $display_quote_icon ) {
				?>
					<span class="rtsb-quote-icon">
						<?php Fns::print_html( $quote_icon ); ?>
					</span>
				<?php
			}
			?>
		</div>
		<?php if ( $author_description ) { ?>
			<div class="rtsb-testimonial-description">
                <?php Fns::print_html( $author_description ); ?>
			</div>
		<?php } ?>
	</div>
</div>




