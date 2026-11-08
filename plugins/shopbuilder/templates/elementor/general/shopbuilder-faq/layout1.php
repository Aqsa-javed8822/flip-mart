<?php
/**
 * Template: ShopBuilder FAQ
 *
 * @package RadiusTheme\SB
 */
/**
 * Template variables:
 *
 * @var $display_count               bool
 * @var $title_html_tag              string
 * @var $faq_items                   array
 */

// Do not allow directly accessing this file.
use RadiusTheme\SB\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
?>
<div class="rtsb-faq-wrapper">
	<div class="rtsb-faq-list">
		<?php
		if ( ! empty( $faq_items ) ) {
			foreach ( $faq_items as $index => $faq ) {
				?>
				<div class="rtsb-faq-list-item">
					<div class="rtsb-faq">
						<div class="rtsb-faq-inner">
							<?php if ( $display_count ) { ?>
								<span class="rtsb-faq-count"><?php echo ! empty( $faq['sb_faq_count'] ) ? esc_html( $faq['sb_faq_count'] ) : sprintf( '%02d', esc_html( $index + 1 ) ); ?></span>
							<?php } ?>
							<div class="rtsb-faq-content">
								<?php if ( ! empty( $faq['sb_faq_title'] ) ) { ?>
									<<?php Fns::print_validated_html_tag( $title_html_tag ); ?> class="rtsb-faq-title rtsb-tag">
									<?php Fns::print_html( $faq['sb_faq_title'] ); ?>
									</<?php Fns::print_validated_html_tag( $title_html_tag ); ?>>
								 <?php } ?>

								<?php if ( ! empty( $faq['sb_faq_content'] ) ) : ?>
									<div class="rtsb-faq-desc">
										<?php Fns::print_html( $faq['sb_faq_content'] ); ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
		}
		?>
	</div>
</div>
