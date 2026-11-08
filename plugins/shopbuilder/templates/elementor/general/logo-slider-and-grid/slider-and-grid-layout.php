<?php
/**
 * Template: Logo Slider And Grid
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $logo                       array
 * @var $instance                   object
 * @var $enable_img_link            boolean
 * @var $has_logo_name              boolean
 * @var $img_link_attribute         string
 * @var $logo_brand_name            string
 * @var $slider_item_class          string
 * @var $grid_classes               string
 * array
 */

// Do not allow directly accessing this file.
use RadiusTheme\SB\Elementor\Widgets\General\ImageAccordion\Render;
use RadiusTheme\SB\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
?>

<div class="<?php echo esc_attr( $grid_classes ); ?>">
	<div class="logo-content-wrapper">
		<div class="rtsb-logo-img rtsb-img-wrap">
			<?php
			if ( $enable_img_link ) {
				?>
				<a <?php Fns::print_html( $img_link_attribute ); ?>>
					<?php Fns::print_html( $instance->render_logo_image( $logo ) ); ?>
				</a>
				<?php
			} else {
				Fns::print_html( $instance->render_logo_image( $logo ) );
			}
			// Layout 2 renders the brand name as a sibling of the logo wrapper (below);
			// every other layout keeps it nested here (markup unchanged).
			if ( $has_logo_name && 'rtsb-logo-layout2' !== ( $layout ?? '' ) ) {
				?>
				<h4 class="rtsb-brand-name"><?php Fns::print_html( $logo_brand_name ); ?></h4>
				<?php
			}
			?>
		</div>
		<?php
		if ( $has_logo_name && 'rtsb-logo-layout2' === ( $layout ?? '' ) ) {
			?>
			<h4 class="rtsb-brand-name"><?php Fns::print_html( $logo_brand_name ); ?></h4>
			<?php
		}
		?>
	</div>
</div>



