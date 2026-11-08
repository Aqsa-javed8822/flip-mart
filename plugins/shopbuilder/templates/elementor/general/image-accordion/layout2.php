<?php
/**
 * Template: Image Accordion
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $active_item              bool
 * @var $wrapper_class            string
 * @var $accordion_active_type    string
 * @var $active_item_number       string
 * @var $accordion_title_html_tag string
 * @var $wrapper_class            string
 * @var $accordion_items array
 * @var $instance object,
 * array
 */

// Do not allow directly accessing this file.
use RadiusTheme\SB\Elementor\Widgets\General\ImageAccordion\Render;
use RadiusTheme\SB\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
?>

<div class="<?php echo esc_attr( $wrapper_class ); ?>">
	<?php
	if ( ! empty( $accordion_items ) ) {
		$i = 1;
		foreach ( $accordion_items as $index => $accordion ) {
			$default_active_item = $active_item && $i == $active_item_number ? 'checkedItem' : '';
			?>
		<div class="item <?php echo esc_attr( $default_active_item ); ?>" data-active-type="<?php echo esc_attr( $accordion_active_type ); ?>" style="background-image:<?php echo ! empty( $accordion['accordion_image']['url'] ) ? 'url(' . esc_url( $accordion['accordion_image']['url'] ) . ')' : ''; ?>">
			<?php if ( ! empty( $accordion['accordion_title'] ) ) { ?>
				<div class="title-wrap">
					<<?php Fns::print_validated_html_tag( $accordion_title_html_tag ); ?> class="title">
					<?php
					if ( ! empty( $accordion['popup_icon']['value'] ) ) {
						Fns::print_html( Fns::icons_manager( $accordion['popup_icon'] ) );
					}
					?>
					<?php Fns::print_html( $accordion['accordion_title'] ); ?>
					</<?php Fns::print_validated_html_tag( $accordion_title_html_tag ); ?>>
					<span class="number"><?php echo sprintf( '%02d', esc_html( $index + 1 ) ); ?></span>
				</div>
			<?php } ?>
			<div class="content">
				<div class="inner">
				<?php if ( ! empty( $accordion['accordion_content'] ) ) { ?>
					<div class="desc"><?php Fns::print_html( $accordion['accordion_content'] ); ?></div>
				<?php } ?>
				<?php
				if ( $accordion['enable_project_link'] || $accordion['enable_popup'] ) {
					?>
					<ul class="link-list">
						<?php if ( $accordion['enable_popup'] ) { ?>
							<li>
								<a href="<?php echo esc_url( $accordion['accordion_image']['url'] ); ?>" class="link rtsb-accordion-popup" data-size="800x600" data-caption="<?php echo esc_attr( $accordion['accordion_title'] ); ?>">
									<?php
									if ( ! empty( $accordion['popup_icon']['value'] ) ) {
										Fns::print_html( Fns::icons_manager( $accordion['popup_icon'] ) );
									}
									?>
								</a>
							</li>
						<?php } ?>
						<?php if ( $accordion['enable_project_link'] ) { ?>
							<li>
								<a <?php Fns::print_html( $instance->render_button_attributes( 'rtsb_link_button_' . $i, $accordion['project_link'] ) ); ?>>
									<?php
									if ( ! empty( $accordion['project_link_icon']['value'] ) ) {
										Fns::print_html( Fns::icons_manager( $accordion['project_link_icon'] ) );
									}
									?>
								</a>
							</li>
						<?php } ?>
					</ul>
				<?php } ?>
				</div>
			</div>
		</div>
			<?php
			$i++;
		}
	}
	?>
</div>

