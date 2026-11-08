<?php
/**
 * No-op gallery template.
 *
 * Substituted for `single-product/product-image.php` when WooCommerce renders its
 * variation-form "default gallery" snapshot. That snapshot exists so core's own
 * variation script can restore the stock gallery after swapping in per-variation
 * markup — a swap this module suppresses, because it owns the gallery DOM itself.
 *
 * Rendering the full slider a second time into a hidden <template> would only add
 * weight to every variable product page and fire the gallery's render hooks twice,
 * so nothing is output here.
 *
 * @package RadiusTheme\SB
 */

defined( 'ABSPATH' ) || exit();
