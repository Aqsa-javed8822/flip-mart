<?php
/**
 * The template to display the Builder content
 *
 * @author  RadiousTheme
 * @package RadiusTheme\SB
 */

use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'rtsb/builder/before/header' );

if ( Fns::check_is_block_theme() ) { ?>
	<!doctype html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div class="wp-site-blocks">
	<?php
	Fns::print_html( do_blocks( '<!-- wp:template-part {"slug":"header","theme":"' . esc_attr( rtsb()->current_theme ) . '","tagName":"header","className":"site-header"} /-->' ), true );
} else {
	get_header( 'shop' );
}

$parent_class = apply_filters( 'rtsb/builder/wrapper/parent_class', [] );
$type         = apply_filters( 'rtsb/builder/set/current/page/type', '' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

do_action( 'rtsb/builder/after/header' );
if ( Fns::content_invisible() ) {
	$parent_class[] = 'content-invisible';
}
?>
<div id="rtsb-builder-content" class="rtsb-builder-content <?php echo esc_attr( implode( ' ', $parent_class ) ); ?>">
	<?php
	do_action( 'rtsb/builder/template/before/content' );
	if ( is_singular( BuilderFns::$post_type_tb ) && 'elementor' === Fns::page_edit_with( get_the_ID() ) ) {
		the_content();
	} else {
		do_action( 'rtsb/builder/template/main/content' );
	}
	do_action( 'rtsb/builder/template/after/content' );
	?>
</div>
<?php
do_action( 'rtsb/builder/before/footer' );

if ( Fns::check_is_block_theme() ) {
	Fns::print_html( do_blocks( '<!-- wp:template-part {"slug":"footer","theme":"' . esc_attr( rtsb()->current_theme ) . '","tagName":"footer","className":"site-footer"} /-->' ), true );
	echo '</div>';
	wp_footer();
	echo '</body>';
	echo '</html>';
} else {
	get_footer( 'shop' );
}
