<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package GeneratePress
 */

// No direct access, please
if (! defined('ABSPATH') ) {
    exit;
}

get_header();
?>
	<?php //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?php echo do_action('aepro_archive_data', ''); ?>

<?php
get_footer();
