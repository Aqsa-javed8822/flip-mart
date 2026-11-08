<?php

use Elementor\Utils;
use RadiusTheme\SB\Helpers\BuilderFns;
use RadiusTheme\SB\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'rtsb/builder/before/header' );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
		<title><?php echo wp_get_document_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></title>
	<?php endif; ?>
	<?php wp_head(); ?>
	<?php
	// Keep the following line after `wp_head()` call, to ensure it's not overridden by another templates.
	Utils::print_unescaped_internal_string( Utils::get_meta_viewport( 'canvas' ) );
	?>
</head>
<body <?php body_class(); ?>>
	<?php
		wp_body_open();
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
	wp_footer();
	?>
	</body>
</html>
