<?php
/**
 * Login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     7.0.1
 * @var $controllers  array Widgets/Addons Settings
 * @var $is_builder  bool builder edit mode.
 * @var $hidden  bool.
 * @var $message  string.
 * @var $redirect  string.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RadiusTheme\SB\Helpers\Fns;

if ( ! $is_builder && ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) ) {
	return;
}

$show_info_icon =  ! empty( $controllers['show_info_icon'] ) ? $controllers['show_info_icon'] : '';
$the_icon =  $show_info_icon && ! empty( $controllers['info_icon'] ) ? Fns::icons_manager( $controllers['info_icon'] ) : '';

?>

<div class="rtsb-checkout-login-form  <?php echo ! empty( $show_info_icon ) ? 'show-info-icon-yes': ''; ?>">
    <div class="woocommerce-form-login-toggle rtsb-notice <?php echo ! empty( $the_icon ) ? 'print-custom-icon': ''; ?>">
        <?php wc_print_notice(  $the_icon . apply_filters( 'woocommerce_checkout_login_message', esc_html__( 'Returning customer?', 'shopbuilder' ) ) . ' <a href="#" class="showlogin">' . esc_html__( 'Click here to login', 'shopbuilder' ) . '</a>', 'notice' ); ?>
    </div>

    <div class="woocommerce-form woocommerce-form-login login" <?php echo esc_attr( $hidden ? 'style="display:none;"' : '' ); ?>>

        <?php do_action( 'woocommerce_login_form_start' ); ?>

        <?php if( ! empty( $controllers['show_description'] ) ) { ?>

            <?php if( ! empty( $controllers['description_text'] ) ) { ?>
            <p> <?php echo esc_html( $controllers['description_text'] ); ?>    </p>
            <?php } else { ?>
                <?php Fns::print_html( $message ? wpautop( wptexturize( $message ) ) : '', true ); // @codingStandardsIgnoreLine ?>
            <?php } ?>

        <?php } ?>


        <div class="login-form-fields">
            <div class="field-wrapper">
                <label for="username"><?php esc_html_e( 'Username or email', 'shopbuilder' ); ?>&nbsp;<span class="required">*</span></label>
                <input type="text" class="input-text" name="username" id="username" autocomplete="username" />
            </div>
            <div class="field-wrapper">
                <label for="password"><?php esc_html_e( 'Password', 'shopbuilder' ); ?>&nbsp;<span class="required">*</span></label>
                <input class="input-text woocommerce-Input" type="password" name="password" id="password" autocomplete="current-password" />
            </div>
            <?php do_action( 'woocommerce_login_form' ); ?>
        </div>

        <p class="form-row" >
            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'shopbuilder' ); ?></span>
            </label>
            <input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />
            <button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Login', 'shopbuilder' ); ?>"><?php esc_html_e( 'Login', 'shopbuilder' ); ?></button>
        </p>
        <p class="lost_password">
            <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'shopbuilder' ); ?></a>
        </p>

        <div class="clear"></div>

        <?php do_action( 'woocommerce_login_form_end' ); ?>

    </div>
</div>
