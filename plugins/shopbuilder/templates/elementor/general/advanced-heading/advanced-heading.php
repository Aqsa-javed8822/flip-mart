<?php
/**
 * Template: Advanced Heading Layout 1
 *
 * @package RadiusTheme\SB
 */

/**
 * Template variables:
 *
 * @var $sub_heading                   string
 * @var $sub_heading_tag               string
 * @var $has_sub_heading               bool
 * @var $sub_heading_position          string
 * @var $display_left_bar              bool
 * @var $display_right_bar             bool
 * @var $title                         string
 * @var $title_tag                     string
 * @var $has_title                     bool
 * @var $has_separator                 bool
 * @var $separator_position            string
 * @var $description                   string
 * @var $has_description               bool
 */

use RadiusTheme\SB\Helpers\Fns;
use RadiusTheme\SB\Elementor\Widgets\General\AdvancedHeading\Render;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

$sub_heading_left_bar  = Render::render_title_bar( 'left', $display_left_bar, $display_right_bar );
$sub_heading_right_bar = Render::render_title_bar( 'right', $display_left_bar, $display_right_bar );
$sub_heading           = Render::render_subheading( $sub_heading, $sub_heading_tag, $sub_heading_left_bar, $sub_heading_right_bar );

?>
<div class="rtsb-advanced-heading">
	<?php
	/**
	 * Top Separator.
	 */
	Fns::print_html( Render::render_separator( 'top', $has_separator, $separator_position ) );

	/**
	 * Top Sub-Heading.
	 */
	if ( $has_sub_heading && 'top' === $sub_heading_position ) {
		Fns::print_html( $sub_heading );
	}

	/**
	 * Title.
	 */
	if ( $has_title ) {
		?>
		<div class="rtsb-advanced-heading-wrap">
			<<?php Fns::print_validated_html_tag( $title_tag ); ?> class="advanced-heading-text">
				<?php Fns::print_html( $title ); ?>
			</<?php Fns::print_validated_html_tag( $title_tag ); ?>>
		</div>
		<?php
	}

	/**
	 * Bottom Sub-Heading.
	 */
	if ( $has_sub_heading && 'bottom' === $sub_heading_position ) {
		Fns::print_html( $sub_heading );
	}

	/**
	 * Bottom Separator.
	 */
	Fns::print_html( Render::render_separator( 'bottom', $has_separator, $separator_position ) );

	/**
	 * Description.
	 */
	if ( $has_description ) {
		?>
		<div class="rtsb-advanced-heading-desc">
			<?php Fns::print_html( $description ); ?>
		</div>
		<?php
	}
	?>
</div>
