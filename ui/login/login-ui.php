<?php
/**
 * Modern login / register UI
 *
 * Restyles the PeproDev UPS login/register form (mobile/OTP flow) and
 * replaces the "no account? register" footer links with Login/Register tabs.
 * Loaded when enabled (on by default; Login/Register settings > Login & Registration, or the
 * PEPRODEV_UPS_UI_LOGIN constant).
 */

defined( 'ABSPATH' ) || exit;

/**
 * Front-end strings; filterable so copy can change without touching JS.
 *
 * @return array<string,string>
 */
function peprodev_ui_login_strings() {
	global $PeproDevUPS_Login;
	$digits = is_object( $PeproDevUPS_Login ) && ! empty( $PeproDevUPS_Login->verification_digits ) ? absint( $PeproDevUPS_Login->verification_digits ) : 5;
	return apply_filters(
		'peprodev_ui_login_strings',
		array(
			'tabLogin'          => _x( 'Log in', 'modern-ui tab', 'peprodev-ups' ),
			'tabRegister'       => _x( 'Register', 'modern-ui tab', 'peprodev-ups' ),
			'loginTitle'        => __( 'Welcome', 'peprodev-ups' ),
			'loginSubtitle'     => __( 'Log in to access your courses, sessions and orders.', 'peprodev-ups' ),
			'registerTitle'     => __( 'Create an account', 'peprodev-ups' ),
			'registerSubtitle'  => __( 'Sign up with your mobile number in less than a minute.', 'peprodev-ups' ),
			'otpTitle'          => __( 'Verify your mobile number', 'peprodev-ups' ),
			/* translators: %s: number of digits of the verification code. */
			'otpSubtitle'       => sprintf( __( 'Enter the %s-digit code sent to you by SMS.', 'peprodev-ups' ), number_format_i18n( $digits ) ),
			'mobileLabel'       => __( 'Mobile number', 'peprodev-ups' ),
			'mobilePlaceholder' => _x( '09123456789', 'mobile number placeholder', 'peprodev-ups' ),
			'otpLabel'          => __( 'Verification code', 'peprodev-ups' ),
			'otpPlaceholder'    => '',
			'firstName'         => _x( 'First name', 'modern-ui', 'peprodev-ups' ),
			'lastName'          => _x( 'Last name', 'modern-ui', 'peprodev-ups' ),
		)
	);
}

/**
 * Enqueue assets for logged-out visitors (the form may be inline or a popup).
 */
function peprodev_ui_login_enqueue() {
	if ( is_user_logged_in() || is_admin() ) {
		return;
	}
	list( $css, $css_ver ) = peprodev_ui_asset( 'login/login-ui.css' );
	list( $js, $js_ver )   = peprodev_ui_asset( 'login/login-ui.js' );

	wp_enqueue_style( 'peprodev-ui-login', $css, array( 'peprodev-ui-tokens', 'peprodev-ui-otp' ), $css_ver );
	wp_enqueue_script( 'peprodev-ui-login', $js, array( 'jquery', 'peprodev-ui-otp' ), $js_ver, true );
	wp_localize_script( 'peprodev-ui-login', 'mjLoginUi', array( 'i18n' => peprodev_ui_login_strings() ) );
}
add_action( 'wp_enqueue_scripts', 'peprodev_ui_login_enqueue', 10020 );

/**
 * Scope class so every override is opt-in and removable.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function peprodev_ui_login_body_class( $classes ) {
	if ( ! is_user_logged_in() ) {
		$classes[] = 'mj-login-ui-on';
	}
	return $classes;
}
add_filter( 'body_class', 'peprodev_ui_login_body_class' );
