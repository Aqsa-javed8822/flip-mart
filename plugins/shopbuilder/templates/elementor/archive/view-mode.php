<?php
/**
 * Template variables:
 *
 * @var $controllers  array Widgets/Addons Settings
 * @var $grid  string Grid view url
 * @var $list  string List View Url
 */

use Elementor\Icons_Manager;
use RadiusTheme\SB\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

$view_mode   = ! empty( $controllers['view_mode'] ) ? $controllers['view_mode'] : 'grid';
$view        = ! empty( $_GET['displayview'] ) ? sanitize_text_field( wp_unslash( $_GET['displayview'] ) ) : $view_mode; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$ajax_filter = Fns::product_filters_has_ajax( apply_filters( 'rtsb/builder/set/current/page/type', '' ) );
$class       = rtsb()->has_pro() && $ajax_filter ? ' has-ajax-filter' : ' no-ajax-filter';
$grid_view   = 'displayview=grid';
$list_view   = 'displayview=list';
?>

<div class="rtsb-archive-view-mode<?php echo esc_attr( $class ); ?>">
	<a class="rtsb-mode-switcher<?php echo esc_attr( 'grid' === $view ? ' active' : '' ); ?>" href="<?php echo esc_url( $grid ); ?>" data-mode="<?php echo esc_js( wp_json_encode( $grid_view ) ); ?>">
		<?php
		$grid_icon = ! empty( $controllers['mode_button_grid_icon'] ) ? $controllers['mode_button_grid_icon'] : [];
		Fns::print_html( Fns::icons_manager( $grid_icon ), true );
		?>
	</a>
	<a class="rtsb-mode-switcher<?php echo esc_attr( 'list' === $view ? ' active' : '' ); ?>" href="<?php echo esc_url( $list ); ?>" data-mode="<?php echo esc_js( wp_json_encode( $list_view ) ); ?>">
		<?php
		$list_icon = ! empty( $controllers['mode_button_list_icon'] ) ? $controllers['mode_button_list_icon'] : [];
		Fns::print_html( Fns::icons_manager( $list_icon ), true );
		?>
	</a>
</div>
