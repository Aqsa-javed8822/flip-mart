<?php
/**
 * Show error messages
 *
 * @version 15.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $notices ) || ! is_array( $notices ) ) {
	return;
}

$multiple = count( $notices ) > 1;

?>
	<div class="wc-block-components-notice-banner is-error" role="alert" <?php echo $multiple ? '' : wc_get_notice_data_attr( $notices[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
			<path d="M12 3.2c-4.8 0-8.8 3.9-8.8 8.8 0 4.8 3.9 8.8 8.8 8.8 4.8 0 8.8-3.9 8.8-8.8 0-4.8-4-8.8-8.8-8.8zm0 16c-4 0-7.2-3.3-7.2-7.2C4.8 8 8 4.8 12 4.8s7.2 3.3 7.2 7.2c0 4-3.2 7.2-7.2 7.2zM11 17h2v-6h-2v6zm0-8h2V7h-2v2z"></path>
		</svg>
		<div class="wc-block-components-notice-banner__content">
			<?php if ( $multiple ) { ?>
				<p class="wc-block-components-notice-banner__summary"><?php esc_html_e( 'The following problems were found:', 'shopbuilder' ); ?></p>
				<ul>
				<?php foreach ( $notices as $notice ) : ?>
					<li<?php echo wc_get_notice_data_attr( $notice ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php echo wc_kses_notice( $notice['notice'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</li>
				<?php endforeach; ?>
				</ul>
				<?php
			} else {
				echo wc_kses_notice( $notices[0]['notice'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</div>
	</div>
<?php
