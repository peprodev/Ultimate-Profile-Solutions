<?php
/**
 * Modern dashboard UI
 *
 * Restyles the logged-in PeproDev UPS dashboard (sidebar, cards, stats,
 * orders, courses) and replaces its "Edit profile" section template.
 * Loaded when enabled (on by default; Profile settings > Modern UI, or the
 * PEPRODEV_UPS_UI_DASHBOARD constant).
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the current request is the plugin's profile/dashboard page.
 *
 * @return bool
 */
function peprodev_ui_dashboard_is_profile_page() {
	global $PeproDevUPS_Profile;
	$is = false;
	if ( is_singular() && $PeproDevUPS_Profile && method_exists( $PeproDevUPS_Profile, 'get_profile_page' ) ) {
		// Compare page IDs (works with plain permalinks too); WPML maps the page to the current language.
		$page_id = method_exists( $PeproDevUPS_Profile, 'read' ) ? absint( $PeproDevUPS_Profile->read( 'profile_page', 0 ) ) : 0;
		if ( $page_id ) {
			$page_id = absint( apply_filters( 'wpml_object_id', $page_id, 'page', true ) );
			$is      = (int) get_queried_object_id() === $page_id;
		}
		if ( ! $is ) {
			$is = untrailingslashit( get_permalink() ) === untrailingslashit( strtok( $PeproDevUPS_Profile->get_profile_page( true ), '?' ) );
		}
	}
	return (bool) apply_filters( 'peprodev_ui_dashboard_is_profile_page', $is );
}

/**
 * Enqueue assets for logged-in users on the dashboard page.
 */
function peprodev_ui_dashboard_enqueue() {
	if ( ! is_user_logged_in() || ! peprodev_ui_dashboard_is_profile_page() ) {
		return;
	}
	list( $css, $css_ver ) = peprodev_ui_asset( 'dashboard/dashboard.css' );
	list( $js, $js_ver )   = peprodev_ui_asset( 'dashboard/dashboard.js' );
	wp_enqueue_style( 'peprodev-ui-dashboard', $css, array( 'peprodev-ui-tokens', 'peprodev-ui-otp' ), $css_ver );
	$deps = array( 'jquery', 'peprodev-ui-otp' );
	if ( function_exists( 'WC' ) ) {
		foreach ( array( 'wc-country-select', 'wc-address-i18n' ) as $handle ) {
			if ( wp_script_is( $handle, 'registered' ) ) {
				$deps[] = $handle;
			}
		}
	}
	wp_enqueue_script( 'peprodev-ui-dashboard', $js, $deps, $js_ver, true );
	wp_localize_script(
		'peprodev-ui-dashboard',
		'mjProfileUi',
		array(
			'ajax'   => admin_url( 'admin-ajax.php' ),
			'nonce'  => wp_create_nonce( 'peprodev_ui_profile_avatar' ),
			'addressNonce' => wp_create_nonce( 'peprodev_ui_profile_address' ),
			'i18n' => apply_filters(
				'peprodev_ui_dashboard_strings',
				array(
					'show'          => __( 'Show password', 'peprodev-ups' ),
					'hide'          => __( 'Hide password', 'peprodev-ups' ),
					'weak'          => _x( 'Weak', 'password strength', 'peprodev-ups' ),
					'fair'          => _x( 'Fair', 'password strength', 'peprodev-ups' ),
					'good'          => _x( 'Good', 'password strength', 'peprodev-ups' ),
					'strong'        => _x( 'Strong', 'password strength', 'peprodev-ups' ),
					'nomatch'       => __( 'The password confirmation does not match.', 'peprodev-ups' ),
					'removeConfirm' => __( 'Remove your profile picture and show your Gravatar instead?', 'peprodev-ups' ),
					'removeError'   => __( 'The picture could not be removed. Please try again.', 'peprodev-ups' ),
					'saving'        => __( 'Saving…', 'peprodev-ups' ),
					'saveError'     => __( 'Saving failed. Please try again.', 'peprodev-ups' ),
				)
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'peprodev_ui_dashboard_enqueue', 10020 );

/**
 * Body class for scoping.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function peprodev_ui_dashboard_body_class( $classes ) {
	if ( is_user_logged_in() && peprodev_ui_dashboard_is_profile_page() ) {
		$classes[] = 'mj-profile-ui-on';
	}
	return $classes;
}
add_filter( 'body_class', 'peprodev_ui_dashboard_body_class' );

/**
 * Serve the modern templates instead of the classic ones.
 *
 * Mapped files live in profile/libs/templates/modern/. A theme can still
 * override them by adding peprofile/modern/<file> to the (child) theme.
 *
 * @param string[] $templates Candidate file names.
 * @return string[]
 */
function peprodev_ui_dashboard_templates( $templates ) {
	$map = apply_filters( 'peprodev_ui_dashboard_template_map', array(
		'dash-edit.php'        => 'modern/dash-edit.php',
		'dash-orders-view.php' => 'modern/dash-orders-view.php',
		'dash-course-view.php' => 'modern/dash-course-view.php',
		'dash-courses.php'     => 'modern/dash-courses.php',
		'wc/orders.php'        => 'modern/wc-orders.php',
	) );
	foreach ( $templates as $i => $name ) {
		if ( isset( $map[ $name ] ) && file_exists( PEPRODEV_UPS_UI_DIR . '../profile/libs/templates/' . $map[ $name ] ) ) {
			$templates[ $i ] = $map[ $name ];
		}
	}
	return $templates;
}
add_filter( 'peprofile_get_template_part', 'peprodev_ui_dashboard_templates' );

/**
 * The dashboard icons are Phosphor SVG masks (profile-ui.css), so the
 * plugin's Font Awesome Pro stylesheet is not needed on this page.
 */
function peprodev_ui_dashboard_dequeue_icon_font() {
	if ( is_user_logged_in() && peprodev_ui_dashboard_is_profile_page() && apply_filters( 'peprodev_ui_dashboard_dequeue_font_awesome', true ) ) {
		wp_dequeue_style( 'pepro-font-awesome' );
	}
}
add_action( 'wp_footer', 'peprodev_ui_dashboard_dequeue_icon_font', 1 );

/**
 * Remove the uploaded profile image so the Gravatar (or the default) is used.
 * PeproDev stores the uploaded image URL in the "profile_image" user meta.
 */
function peprodev_ui_dashboard_remove_avatar() {
	check_ajax_referer( 'peprodev_ui_profile_avatar', 'nonce' );
	$user_id = get_current_user_id();
	if ( ! $user_id ) {
		wp_send_json_error( null, 403 );
	}

	$saved = (string) get_user_meta( $user_id, 'profile_image', true );
	delete_user_meta( $user_id, 'profile_image' );

	// Only delete a stand-alone uploaded file, never a Media Library attachment that may be used elsewhere.
	if ( $saved && function_exists( 'wp_get_upload_dir' ) && ! attachment_url_to_postid( $saved ) ) {
		$uploads = wp_get_upload_dir();
		if ( empty( $uploads['error'] ) && 0 === strpos( $saved, $uploads['baseurl'] . '/' ) ) {
			$file = realpath( $uploads['basedir'] . substr( $saved, strlen( $uploads['baseurl'] ) ) );
			$base = realpath( $uploads['basedir'] );
			if ( $file && $base && 0 === strpos( $file, $base . DIRECTORY_SEPARATOR ) && is_file( $file ) && wp_check_filetype( $file )['type'] && 0 === strpos( (string) wp_check_filetype( $file )['type'], 'image/' ) ) {
				wp_delete_file( $file );
			}
		}
	}

	wp_send_json_success( array( 'avatar' => get_avatar_url( $user_id, array( 'size' => 192 ) ) ) );
}
add_action( 'wp_ajax_peprodev_ui_profile_remove_avatar', 'peprodev_ui_dashboard_remove_avatar' );

require_once __DIR__ . '/address.php';
