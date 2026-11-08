<?php
/**
 * Template: ShopBuilder Faq
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $display_tab_icon             bool
 * @var $accordion_type              string
 * @var $title_html_tag              string
 * @var $tab_icon                    array
 * @var $expanded_icon               array
 * @var $faq_items             array
 * @var $tab_icon_position           string
 */

// Do not allow directly accessing this file.
use RadiusTheme\SB\Elementor\Widgets\General\ShopBuilderAccordion\Render;
use RadiusTheme\SB\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
?>
<div class="rtsb-faq-wrapper rtsb-sb-accordion-wrapper">
	<?php
	if ( ! empty( $faq_items ) ) {
		?>
	<div class="rtsb-accordion-list <?php echo esc_attr( $accordion_type ); ?>" data-accordionType="<?php echo esc_attr( $accordion_type ); ?>">
		<?php
		$i = 1;
		foreach ( $faq_items as $key => $item ) {
			$exp_tab = 1 == $i && 'sb-accordion' === $accordion_type ? 'rtsb-expand-tab' : '';
			?>
			<div class="rtsb-accordion-item <?php echo esc_attr( $exp_tab ); ?> rtsb-faq-item">
				<div class="rtsb-accordion-header <?php echo esc_attr( $tab_icon_position ); ?>">
					<div class="rtsb-accordion-title-wrap">
						<?php if ( ! empty( $item['sb_faq_title'] ) ) { ?>
						<<?php Fns::print_validated_html_tag( $title_html_tag ); ?> class="rtsb-accordion-title rtsb-faq-title">
							<?php Fns::print_html( $item['sb_faq_title'] ); ?>
					</<?php Fns::print_validated_html_tag( $title_html_tag ); ?>>
					<?php } ?>
				</div>
				<?php if ( $display_tab_icon && ( ! empty( $tab_icon['value'] ) || ! empty( $expanded_icon['value'] ) ) ) : ?>
					<div class="rtsb-accordion-icon-wrap">
						<?php if ( ! empty( $tab_icon['value'] ) ) { ?>
							<span class="rtsb-accordion-icon rtsb-expand-icon">
								<?php Fns::print_html( Fns::icons_manager( $tab_icon ) ); ?>
							</span>
						<?php } ?>
						<?php if ( ! empty( $expanded_icon['value'] ) ) { ?>
							<span class="rtsb-accordion-icon rtsb-tab-icon">
								<?php Fns::print_html( Fns::icons_manager( $expanded_icon ) ); ?>
							</span>
						<?php } ?>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $item['sb_faq_content'] ) ) : ?>
				<div class="rtsb-accordion-content">
					<div class="rtsb-widget">
						<div class="rtsb-content ">
							<?php Fns::print_html( $item['sb_faq_content'] ); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
			<?php
			$i++;
		}
		?>
	</div>
		<?php
	}
	?>
</div>
