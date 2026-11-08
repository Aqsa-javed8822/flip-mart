<?php
/**
 * Template variables:
 *
 * @var $controllers  array Widgets/Addons Settings
 * @var $is_builder  boolean Is Cart Edit page
 */

use RadiusTheme\SB\Helpers\Fns;

defined( 'ABSPATH' ) || exit;

$horizontal_scroll = ! empty( $controllers['table_horizontal_scroll_on_mobile'] );
$wrapper_classes   = $horizontal_scroll ? ' rtsb-table-horizontal-scroll-on-mobile' : '';
$wrapper_classes  .= ! empty( $controllers['ajax_on_qty_change'] ) && rtsb()->has_pro() ? ' has-ajax-update' : ' no-ajax-update';
?>
<div class="rtsb-cart-table woocommerce <?php echo esc_attr( $wrapper_classes ); ?>">
	<?php
	if ( WC()->cart->is_empty() ) {
		wc_get_template( 'cart/cart-empty.php' );
	} else {
		?>
		<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<table class="shop_table cart woocommerce-cart-form__contents <?php echo esc_attr( $horizontal_scroll ? '' : 'shop_table_responsive' ); ?>" cellspacing="0">
				<thead>
					<tr>
						<?php
						foreach ( $controllers['cart_table'] as $heading ) {
							printf(
								'<th class="product-%s elementor-repeater-item-%s"> <div class="table-column-wrapper"> %s </div> </th>',
								esc_attr( $heading['cart_table_items'] ),
								esc_attr( $heading['_id'] ),
								esc_html( $heading['cart_table_heading_title'] )
							);
						}
						?>
					</tr>
				</thead>
				<tbody>
					<?php do_action( 'woocommerce_before_cart_contents' ); ?>

					<?php
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						$GLOBALS['product'] = $_product;


						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

							$show_image_on_mobile = ( $horizontal_scroll || $controllers['show_image_on_mobile'] ) ? ' show-image-on-mobile' : ' hide-image-on-mobile';
							?>
							<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
								<?php
								foreach ( $controllers['cart_table'] as $table ) {
									$cart_item['custom_table_data'] = $table;
									$product_name                   = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
									switch ( $table['cart_table_items'] ) {
										case 'remove':
											echo '<td class="product-remove elementor-repeater-item-' . esc_attr( $table['_id'] ) . '"><div class="table-column-wrapper">';
											echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												'woocommerce_cart_item_remove_link',
												sprintf(
													'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
													esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
													esc_attr__( 'Remove this item', 'shopbuilder' ),
													esc_attr( $product_id ),
													esc_attr( $_product->get_sku() ),
													! empty( Fns::icons_manager( $table['cart_table_remove_icon'] ) ) ? Fns::icons_manager( $table['cart_table_remove_icon'] ) : ''
												),
												$cart_item_key
											);

											echo '</div></td>';
											break;
										case 'products':
											echo '<td class="product-data elementor-repeater-item-' . esc_attr( $table['_id'] ) . esc_attr( $show_image_on_mobile ) . '" data-title="' . esc_attr( $table['cart_table_heading_title'] ) . '"> <div class="table-column-wrapper rtsb-product-column"> ';
											do_action( 'rtsb/cart/table/products/column', $cart_item_key, $_product, $cart_item, $table, $controllers );
											echo '</div></td>';
											break;
										case 'thumbnail':
											echo '<td class="product-thumbnail elementor-repeater-item-' . esc_attr( $table['_id'] ) . esc_attr( $show_image_on_mobile ) . '"><div class="table-column-wrapper">';

											$thumbnail_size = isset( $table['cart_table_thumbnail_size'] ) ? $table['cart_table_thumbnail_size'] : 'thumbnail';
											$thumbnail      = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( $thumbnail_size ), $cart_item, $cart_item_key );

											if ( ! $product_permalink ) {
												Fns::print_html( $thumbnail, true ); //  phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											} else {
												printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											}

											echo '</div></td>';

											break;

										case 'name':
											echo '<td class="product-name elementor-repeater-item-' . esc_attr( $table['_id'] ) . '" data-title="' . esc_attr( $table['cart_table_heading_title'] ) . '"><div class="table-column-wrapper rtsb-product-content">';

											if ( ! $product_permalink ) {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
											} else {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
											}

											do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

											if ( ! empty( $table['show_variation_data'] ) ) {
												Fns::print_html( wc_get_formatted_cart_item_data( $cart_item ), true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											}

											// Backorder notification.
											if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'shopbuilder' ) . '</p>', $product_id ) );
											}

											echo '</div></td>';

											break;

										case 'price':
											echo '<td class="product-price elementor-repeater-item-' . esc_attr( $table['_id'] ) . '" data-title="' . esc_attr( $table['cart_table_heading_title'] ) . '"><div class="table-column-wrapper">';
											echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											echo '</div></td>';

											break;

										case 'quantity':
											echo '<td class="product-quantity elementor-repeater-item-' . esc_attr( $table['_id'] ) . '" data-title="' . esc_attr( $table['cart_table_heading_title'] ) . '"><div class="table-column-wrapper">';

											if ( $_product->is_sold_individually() ) {
												$min_quantity = 1;
												$max_quantity = 1;
											} else {
												$min_quantity = 0;
												$max_quantity = $_product->get_max_purchase_quantity();
											}

												$product_quantity = woocommerce_quantity_input(
													[
														'input_name'   => "cart[{$cart_item_key}][qty]",
														'input_value'  => $cart_item['quantity'],
														'max_value'    => $max_quantity,
														'min_value'    => $min_quantity,
														'product_name' => $product_name,
													],
													$_product,
													false
												);

												echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item, $table ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

											echo '</div></td>';

											break;

										case 'subtotal':
											echo '<td class="product-subtotal elementor-repeater-item-' . esc_attr( $table['_id'] ) . '" data-title="' . esc_html( $table['cart_table_heading_title'] ) . '"><div class="table-column-wrapper">';
											echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key, $table ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											echo '</div></td>';
											break;

										case 'custom_field':
											echo '<td class="product-subtotal elementor-repeater-item-' . esc_attr( $table['_id'] ) . '" data-title="' . esc_html( $table['cart_table_heading_title'] ) . '"><div class="table-column-wrapper">';
											echo Fns::get_post_custom_field_value( $product_id, $table['cart_table_custom_field'], $table['cart_table_custom_field_fallback'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											echo '</div></td>';

											break;

										default:
											break;
									}
								}
								?>
							</tr>
							<?php
						}

						unset( $GLOBALS['product'] );

					}
					?>

					<?php do_action( 'woocommerce_cart_contents' ); ?>

				</tbody>
				<tfoot>
					<tr>
						<td colspan="6" class="actions">
							<div class="actions-button-wrapper">

								<?php if ( ! empty( $controllers['show_coupon_field'] ) && wc_coupons_enabled() ) { ?>
									<div class="coupon">
										<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'shopbuilder' ); ?></label>
										<input type="text" name="coupon_code" class="input-text" id="coupon_code" value=""
											<?php if ( ! empty( $controllers['cart_table_coupon_placeholder_text'] ) ) { ?>
												placeholder="<?php echo esc_attr( $controllers['cart_table_coupon_placeholder_text'] ); ?>"
											<?php } ?>
										/>
										<button type="submit" class="button" name="apply_coupon"
											<?php if ( ! empty( $controllers['cart_table_coupon_button_text'] ) ) { ?>
												value="<?php echo esc_attr( $controllers['cart_table_coupon_button_text'] ); ?>"
											<?php } ?>
										>
											<?php if ( ! empty( $controllers['cart_table_coupon_button_text'] ) ) { ?>
												<?php echo esc_html( $controllers['cart_table_coupon_button_text'] ); ?>
											<?php } ?>
										</button>
										<?php do_action( 'woocommerce_cart_coupon' ); ?>
									</div>
								<?php } ?>
								<?php do_action( 'rtsb/cart/table/before/update/cart' ); ?>
								<button type="submit" class="button" name="update_cart"
									<?php if ( ! empty( $controllers['cart_table_update_button_text'] ) ) { ?>
										value="<?php esc_attr( $controllers['cart_table_update_button_text'] ); ?>"
									<?php } ?>
								>
									<?php if ( ! empty( $controllers['cart_table_update_button_text'] ) ) { ?>
										<?php echo esc_html( $controllers['cart_table_update_button_text'] ); ?>
									<?php } ?>
								</button>
								<?php do_action( 'woocommerce_cart_actions' ); ?>
								<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
							</div>
						</td>
					</tr>

					<?php do_action( 'woocommerce_after_cart_contents' ); ?>
				</tfoot>
			</table>
		</form>
	<?php } ?>
</div>
