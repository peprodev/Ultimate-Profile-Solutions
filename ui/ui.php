<?php
/**
 * Modern UI layer for PeproDev UPS: restyled login/register form and user
 * dashboard, OTP code boxes, enrolled-courses views and a configurable
 * "continue / start learning" button.
 *
 * Both modules are ON by default (since 8.2.0). Turn them off on
 * PeproDev Profile > Login/Register > Login & Registration (login form) and
 * PeproDev Profile > Profile (dashboard), or force them in wp-config.php
 * (a defined constant always wins over the setting):
 *   define( 'PEPRODEV_UPS_UI_LOGIN', false );      // classic login/register form
 *   define( 'PEPRODEV_UPS_UI_DASHBOARD', false );  // classic user dashboard
 *
 * The [peprodev_learning_button] and [peprodev_my_courses] shortcodes are
 * always available; their styles are only loaded when a module is enabled
 * or when one of the shortcodes is rendered.
 */

defined( 'ABSPATH' ) || exit;

define( 'PEPRODEV_UPS_UI_DIR', __DIR__ . '/' );
define( 'PEPRODEV_UPS_UI_URL', plugins_url( '/', __FILE__ ) );
define( 'PEPRODEV_UPS_UI_TEXTS', 'peprodev_ups_ui_texts' ); // legacy 8.1.0 option, migrated once
define( 'PEPRODEV_UPS_UI_OPTION', 'peprodev_ups_profile' ); // main plugin option
define( 'PEPRODEV_UPS_UI_KEY', 'modern_ui' ); // key of the settings inside the main option

/**
 * Whether a modern UI module is enabled.
 *
 * A PEPRODEV_UPS_UI_LOGIN / PEPRODEV_UPS_UI_DASHBOARD constant, when defined,
 * wins; otherwise the "Modern UI" setting is used (default: on, an explicitly
 * saved "0" turns a module off). Reads the option directly (no gettext) so it
 * is safe to call before "init".
 *
 * @param string $module "login" or "dashboard".
 * @return bool
 */
function peprodev_ui_module_enabled( $module ) {
	$constant = 'login' === $module ? 'PEPRODEV_UPS_UI_LOGIN' : 'PEPRODEV_UPS_UI_DASHBOARD';
	if ( defined( $constant ) ) {
		$on = (bool) constant( $constant );
	} else {
		$saved = peprodev_ui_texts_saved();
		$on    = ! isset( $saved[ 'ui_' . $module ] ) || '1' === (string) $saved[ 'ui_' . $module ];
	}
	return (bool) apply_filters( 'peprodev_ui_module_enabled', $on, $module );
}

/**
 * Whether the module toggle is forced by a wp-config.php constant.
 *
 * @param string $module "login" or "dashboard".
 * @return string Constant name when defined, empty string otherwise.
 */
function peprodev_ui_module_constant( $module ) {
	$constant = 'login' === $module ? 'PEPRODEV_UPS_UI_LOGIN' : 'PEPRODEV_UPS_UI_DASHBOARD';
	return defined( $constant ) ? $constant : '';
}

/**
 * URL and cache-busting version of a file inside ui/.
 *
 * @param string $rel Path relative to ui/.
 * @return array{0:string,1:string}
 */
function peprodev_ui_asset( $rel ) {
	$path = PEPRODEV_UPS_UI_DIR . ltrim( $rel, '/' );
	$ver  = defined( 'PEPRODEVUPS' ) ? PEPRODEVUPS : '1';
	return array( PEPRODEV_UPS_UI_URL . ltrim( $rel, '/' ), file_exists( $path ) ? $ver . '.' . filemtime( $path ) : $ver );
}

/**
 * Shared styles/scripts (design tokens, OTP boxes). Registered only.
 */
function peprodev_ui_register_assets() {
	list( $tokens, $tv ) = peprodev_ui_asset( 'assets/css/tokens.css' );
	list( $otpc, $ocv )  = peprodev_ui_asset( 'assets/css/otp.css' );
	list( $otpj, $ojv )  = peprodev_ui_asset( 'assets/js/otp.js' );
	wp_register_style( 'peprodev-ui-tokens', $tokens, array(), $tv );
	$accent = peprodev_ui_accent_css();
	if ( '' !== $accent ) {
		wp_add_inline_style( 'peprodev-ui-tokens', $accent );
	}
	wp_register_style( 'peprodev-ui-otp', $otpc, array( 'peprodev-ui-tokens' ), $ocv );
	wp_register_script( 'peprodev-ui-otp', $otpj, array( 'jquery' ), $ojv, true );
}
add_action( 'wp_enqueue_scripts', 'peprodev_ui_register_assets', 5 );

/**
 * Saved button accent color of the modern UI as #rrggbb, empty when not set.
 *
 * @return string
 */
function peprodev_ui_accent_color() {
	$saved = peprodev_ui_texts_saved();
	$color = isset( $saved['ui_accent'] ) ? sanitize_hex_color( (string) $saved['ui_accent'] ) : '';
	return (string) apply_filters( 'peprodev_ui_accent_color', $color ? strtolower( $color ) : '' );
}

/**
 * Design token overrides for the saved accent color (buttons, focus rings, links of the
 * modern login form and dashboard). "html:root" wins over a theme's plain ":root" tokens.
 *
 * @return string CSS, empty when no accent is set.
 */
function peprodev_ui_accent_css() {
	$decl  = array();
	$color = peprodev_ui_accent_color();
	if ( '' !== $color ) {
		$on     = function_exists( 'peprodev_ui_contrast_color' ) ? peprodev_ui_contrast_color( $color ) : '#ffffff';
		$decl[] = "--mj-primary:{$color}";
		$decl[] = "--mj-primary-hover:color-mix(in srgb,{$color} 82%,#000)";
		$decl[] = "--mj-primary-soft:color-mix(in srgb,{$color} 12%,#fff)";
		$decl[] = "--mj-on-primary:{$on}";
	}
	// colors of the "Modern UI Design" screen (ui/design.php)
	if ( function_exists( 'peprodev_ui_design_declarations' ) ) {
		$decl = array_merge( $decl, peprodev_ui_design_declarations() );
	}
	return $decl ? 'html:root{' . implode( ';', $decl ) . ';}' : '';
}

/**
 * Published LearnDash courses linked to a WooCommerce product (learndash-woocommerce).
 *
 * @param int $product_id Product ID.
 * @return int[]
 */
function peprodev_ui_product_courses( $product_id ) {
	$ids = maybe_unserialize( get_post_meta( $product_id, '_related_course', true ) );
	$ids = is_array( $ids ) ? array_filter( array_map( 'absint', $ids ) ) : array();
	return array_values(
		array_filter(
			$ids,
			function ( $id ) {
				return 'sfwd-courses' === get_post_type( $id ) && 'publish' === get_post_status( $id );
			}
		)
	);
}

/**
 * Dashboard view of a course.
 *
 * @param int $course_id Course ID.
 * @return string
 */
function peprodev_ui_course_url( $course_id ) {
	global $PeproDevUPS_Profile;
	$url = get_permalink( $course_id );
	if ( $PeproDevUPS_Profile && method_exists( $PeproDevUPS_Profile, 'get_profile_page' ) ) {
		$url = $PeproDevUPS_Profile->get_profile_page( array( 'section' => 'courses', 'view' => $course_id ) );
	}
	return (string) apply_filters( 'peprodev_ui_course_url', $url, $course_id );
}

/**
 * Whether the user can access at least one LearnDash course.
 *
 * @param int $user_id User ID.
 * @return bool
 */
function peprodev_ui_has_any_course( $user_id ) {
	static $cache = array();
	if ( ! $user_id ) {
		return false;
	}
	if ( ! isset( $cache[ $user_id ] ) ) {
		$courses           = function_exists( 'learndash_user_get_enrolled_courses' ) ? learndash_user_get_enrolled_courses( $user_id, array(), true ) : array();
		$cache[ $user_id ] = ! empty( $courses );
	}
	return $cache[ $user_id ];
}

/* ---------------------------------------------------------------------
 * Settings + editable texts, WPML aware.
 *
 * Since 8.2.0 the values live in the main "peprodev_ups_profile" option
 * (key "modern_ui") and are edited on the plugin's own settings screens:
 *   - Login/Register > Login & Registration: modern login/register form;
 *   - Profile: modern dashboard, learning button and "My courses" texts.
 * The 8.1.0 option "peprodev_ups_ui_texts" is migrated once and left in
 * place (untouched) so a downgrade keeps working.
 * ------------------------------------------------------------------- */

/**
 * Field definitions: key => [group, label, default, type].
 *
 * Defaults are translatable (gettext). Only values an admin has saved are
 * registered with WPML / Polylang string translation.
 *
 * @return array<string,array{0:string,1:string,2:string,3:string}>
 */
function peprodev_ui_texts_fields() {
	return apply_filters(
		'peprodev_ui_texts_fields',
		array(
			'ui_login'          => array( 'modern', __( 'Modern login/register form', 'peprodev-ups' ), '1', 'checkbox' ),
			'ui_dashboard'      => array( 'modern', __( 'Modern user dashboard', 'peprodev-ups' ), '1', 'checkbox' ),
			'ui_accent'         => array( 'modern', __( 'Button accent color', 'peprodev-ups' ), '', 'color' ),
			'learn_enabled'     => array( 'learn', __( 'Show the learning button', 'peprodev-ups' ), '1', 'checkbox' ),
			'learn_has_label'   => array( 'learn', __( 'Button text for users who have access to a course', 'peprodev-ups' ), __( 'Continue learning', 'peprodev-ups' ), 'text' ),
			'learn_has_url'     => array( 'learn', __( 'Button link for users who have access to a course', 'peprodev-ups' ), '{my_courses}', 'url' ),
			'learn_none_label'  => array( 'learn', __( 'Button text for users without access to any course', 'peprodev-ups' ), __( 'Start learning', 'peprodev-ups' ), 'text' ),
			'learn_none_url'    => array( 'learn', __( 'Button link for users without access to any course', 'peprodev-ups' ), '{courses_page}', 'url' ),
			'learn_guest_show'  => array( 'learn', __( 'Show the button to logged-out visitors', 'peprodev-ups' ), '1', 'checkbox' ),
			'learn_guest_label' => array( 'learn', __( 'Button text for logged-out visitors', 'peprodev-ups' ), __( 'Start learning', 'peprodev-ups' ), 'text' ),
			'learn_guest_url'   => array( 'learn', __( 'Button link for logged-out visitors', 'peprodev-ups' ), '{courses_page}', 'url' ),
			'mc_empty_note'     => array( 'courses', __( '"My courses" message when there are no courses', 'peprodev-ups' ), __( 'You have not enrolled in any course yet. Choose one of the courses on the site.', 'peprodev-ups' ), 'textarea' ),
			'mc_view_label'     => array( 'courses', __( 'Course card button', 'peprodev-ups' ), __( 'View course', 'peprodev-ups' ), 'text' ),
			'mc_start_label'    => array( 'courses', __( 'Continue card button when no lesson has been viewed yet', 'peprodev-ups' ), __( 'Start learning', 'peprodev-ups' ), 'text' ),
			'course_numbering'  => array( 'courses', __( 'Number lessons and topics on the course page', 'peprodev-ups' ), '1', 'checkbox' ),
			'course_expanded'   => array( 'courses', __( 'Show the topics of each lesson expanded (not collapsed)', 'peprodev-ups' ), '1', 'checkbox' ),
		)
	);
}

/**
 * Groups shown on the Profile settings screen.
 *
 * @return array<string,string>
 */
function peprodev_ui_texts_groups() {
	return array(
		'modern'  => __( 'Modern UI', 'peprodev-ups' ),
		'learn'   => __( 'Learning button', 'peprodev-ups' ),
		'courses' => __( 'My courses', 'peprodev-ups' ),
	);
}

/**
 * WPML string name.
 *
 * @param string $key Field key.
 * @return string
 */
function peprodev_ui_texts_wpml_name( $key ) {
	$fields = peprodev_ui_texts_fields();
	return 'dashboard ' . ( isset( $fields[ $key ] ) ? $fields[ $key ][0] : 'ui' ) . ": {$key}";
}

/**
 * Main plugin option as an array (reads the option directly, safe before "init").
 *
 * @return array
 */
function peprodev_ui_main_option() {
	$opt = get_option( PEPRODEV_UPS_UI_OPTION, array() );
	return is_array( $opt ) ? $opt : array();
}

/**
 * Saved settings array.
 *
 * Reads the "modern_ui" key of the main option. On the first call after
 * updating from 8.1.x the values of the legacy "peprodev_ups_ui_texts"
 * option are copied there once (a saved "0" keeps a module off).
 *
 * @return array
 */
function peprodev_ui_texts_saved() {
	$opt = peprodev_ui_main_option();
	if ( isset( $opt[ PEPRODEV_UPS_UI_KEY ] ) && is_array( $opt[ PEPRODEV_UPS_UI_KEY ] ) ) {
		return $opt[ PEPRODEV_UPS_UI_KEY ];
	}
	$legacy = get_option( PEPRODEV_UPS_UI_TEXTS, array() );
	$legacy = is_array( $legacy ) ? array_map( 'strval', array_filter( $legacy, 'is_scalar' ) ) : array();
	// Migrate only into an existing main option: creating it here would skip the plugin's defaults (add_option).
	if ( $legacy && $opt ) {
		$opt[ PEPRODEV_UPS_UI_KEY ] = $legacy;
		update_option( PEPRODEV_UPS_UI_OPTION, $opt, 'no' );
	}
	return $legacy;
}

/**
 * Store the settings array in the main option.
 *
 * @param array $data Sanitized key => value pairs.
 */
function peprodev_ui_texts_update( $data ) {
	$opt                        = peprodev_ui_main_option();
	$opt[ PEPRODEV_UPS_UI_KEY ] = (array) $data;
	update_option( PEPRODEV_UPS_UI_OPTION, $opt, 'no' );
}

/**
 * Stored (untranslated) value, or the default when nothing was saved.
 *
 * @param string $key Field key.
 * @return string
 */
function peprodev_ui_text_raw( $key ) {
	$saved = peprodev_ui_texts_saved();
	if ( array_key_exists( $key, $saved ) ) {
		return (string) $saved[ $key ];
	}
	$fields = peprodev_ui_texts_fields();
	return isset( $fields[ $key ] ) ? (string) $fields[ $key ][2] : '';
}

/**
 * Value in the current language: saved values go through WPML / Polylang,
 * defaults are already translated by gettext.
 *
 * @param string $key Field key.
 * @return string
 */
function peprodev_ui_text( $key ) {
	$value = peprodev_ui_text_raw( $key );
	if ( array_key_exists( $key, peprodev_ui_texts_saved() ) && '' !== $value ) {
		$value = apply_filters( 'wpml_translate_single_string', $value, 'peprodev-ups', peprodev_ui_texts_wpml_name( $key ) );
		if ( function_exists( 'pll__' ) ) {
			$value = pll__( $value );
		}
	}
	return (string) apply_filters( 'peprodev_ui_text', $value, $key );
}

/**
 * Checkbox value.
 *
 * @param string $key Field key.
 * @return bool
 */
function peprodev_ui_text_on( $key ) {
	return '1' === peprodev_ui_text_raw( $key );
}

/**
 * URL value with placeholders resolved:
 * {my_courses}, {courses_page}, {profile}, {shop}, {home} and
 * {continue_learning} (handled by the separate PeproDev WP Tweaker plugin).
 *
 * @param string $key Field key.
 * @return string
 */
function peprodev_ui_text_url( $key ) {
	global $PeproDevUPS_Profile;
	$has     = $PeproDevUPS_Profile && method_exists( $PeproDevUPS_Profile, 'get_profile_page' );
	$profile = $has ? $PeproDevUPS_Profile->get_profile_page( true ) : home_url( '/' );
	$list    = get_page_by_path( 'course-list' );
	$shop    = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
	$map     = array(
		'{continue_learning}' => add_query_arg( 'continue_learning', '', $profile ),
		'{my_courses}'        => $has ? $PeproDevUPS_Profile->get_profile_page( array( 'section' => 'courses' ) ) : $profile,
		'{courses_page}'      => apply_filters( 'peprodev_ui_courses_page_url', $list ? get_permalink( $list ) : $shop ),
		'{profile}'           => $profile,
		'{shop}'              => $shop,
		'{home}'              => home_url( '/' ),
	);
	return (string) apply_filters( 'peprodev_ui_text_url', strtr( trim( peprodev_ui_text( $key ) ), $map ), $key );
}

/**
 * Register saved texts with WPML / Polylang.
 */
function peprodev_ui_texts_register() {
	$saved = peprodev_ui_texts_saved();
	foreach ( peprodev_ui_texts_fields() as $key => $field ) {
		if ( in_array( $field[3], array( 'checkbox', 'color', 'size' ), true ) || ! isset( $saved[ $key ] ) || '' === trim( (string) $saved[ $key ] ) ) {
			continue;
		}
		do_action( 'wpml_register_single_string', 'peprodev-ups', peprodev_ui_texts_wpml_name( $key ), (string) $saved[ $key ] );
		if ( function_exists( 'pll_register_string' ) ) {
			pll_register_string( peprodev_ui_texts_wpml_name( $key ), (string) $saved[ $key ], 'peprodev-ups', 'textarea' === $field[3] );
		}
	}
}

/**
 * Register once per change of the saved values.
 */
function peprodev_ui_texts_maybe_register() {
	$saved = peprodev_ui_texts_saved();
	if ( ! $saved ) {
		return;
	}
	$hash = md5( wp_json_encode( array( array_keys( peprodev_ui_texts_fields() ), $saved ) ) );
	if ( get_option( 'peprodev_ups_ui_texts_registered' ) !== $hash ) {
		peprodev_ui_texts_register();
		update_option( 'peprodev_ups_ui_texts_registered', $hash, false );
	}
}
add_action( 'admin_init', 'peprodev_ui_texts_maybe_register' );

/**
 * The 8.1.0 "Dashboard Texts" page was merged into the Profile settings
 * screen: send old bookmarks there.
 */
function peprodev_ui_texts_legacy_page_redirect() {
	if ( isset( $_GET['page'] ) && 'peprodev-ups-ui-texts' === $_GET['page'] && current_user_can( 'manage_options' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		wp_safe_redirect( admin_url( 'admin.php?page=peprodev-ups&section=profile#peprodev-ui-settings' ) );
		exit;
	}
}
add_action( 'admin_menu', 'peprodev_ui_texts_legacy_page_redirect', 1 );

/**
 * Save the fields posted by a settings screen (only the keys present in
 * $input are changed). Capability and nonce are checked by the caller
 * (the plugin's admin AJAX endpoint).
 *
 * @param array $input Unslashed key => value pairs.
 * @return array Saved settings.
 */
function peprodev_ui_texts_save_input( $input ) {
	$data   = peprodev_ui_texts_saved();
	$fields = peprodev_ui_texts_fields();
	foreach ( $fields as $key => $field ) {
		if ( ! isset( $input[ $key ] ) || ! is_scalar( $input[ $key ] ) ) {
			continue;
		}
		// A switch forced by a wp-config.php constant is shown read-only: keep the stored value.
		if ( 'modern' === $field[0] && peprodev_ui_module_constant( substr( $key, 3 ) ) ) {
			continue;
		}
		$raw = (string) $input[ $key ];
		if ( 'checkbox' === $field[3] ) {
			$data[ $key ] = in_array( $raw, array( '1', 'yes', 'true', 'on' ), true ) ? '1' : '0';
			continue;
		}
		if ( 'size' === $field[3] ) {
			$size  = absint( $raw );
			$value = $size >= 8 && $size <= 72 ? (string) $size : '';
		} elseif ( 'color' === $field[3] ) {
			$value = (string) sanitize_hex_color( trim( $raw ) );
		} elseif ( 'url' === $field[3] ) {
			$value = preg_match( '/^\{[a-z_]+\}$/', trim( $raw ) ) ? trim( $raw ) : esc_url_raw( $raw );
		} elseif ( 'textarea' === $field[3] ) {
			$value = sanitize_textarea_field( $raw );
		} else {
			$value = sanitize_text_field( $raw );
		}
		// An unchanged default text is not stored, so it stays translatable with the language files.
		if ( $value === (string) $field[2] ) {
			unset( $data[ $key ] );
		} else {
			$data[ $key ] = $value;
		}
	}
	peprodev_ui_texts_update( $data );
	peprodev_ui_texts_register();
	return $data;
}

/**
 * Descriptions shown under some settings.
 *
 * @return array<string,string>
 */
function peprodev_ui_texts_descriptions() {
	return array(
		'ui_login'     => __( 'Restyled login and register form with Login/Register tabs and OTP code boxes.', 'peprodev-ups' ),
		'ui_dashboard' => __( 'Restyled user dashboard with new Edit profile, order, course and address views.', 'peprodev-ups' ),
		'ui_accent'    => __( 'Color of the buttons, focus rings and links of the modern login form and dashboard. Leave empty to use the theme colors.', 'peprodev-ups' ),
	);
}

/**
 * Note shown under a module switch forced by a wp-config.php constant.
 *
 * @param string $key Field key.
 * @return string Empty when not forced.
 */
function peprodev_ui_texts_constant_note( $key ) {
	$constant = 0 === strpos( $key, 'ui_' ) ? peprodev_ui_module_constant( substr( $key, 3 ) ) : '';
	if ( ! $constant ) {
		return '';
	}
	/* translators: %s: PHP constant name. */
	return sprintf( __( 'Controlled by the %s constant in wp-config.php.', 'peprodev-ups' ), $constant );
}

/**
 * "Modern login/register form" switch on Login/Register > Login & Registration.
 * Collected by login/assets/register.js ([data-ui-key]) and saved by the
 * "savelogin" admin AJAX handler.
 */
function peprodev_ui_render_login_settings() {
	$fields = peprodev_ui_texts_fields();
	$desc   = peprodev_ui_texts_descriptions();
	$note   = peprodev_ui_texts_constant_note( 'ui_login' );
	$on     = peprodev_ui_module_enabled( 'login' );
	?>
	<div class="peprodev-ui-settings" id="peprodev-ui-login-settings">
		<p class="text-bold mt-4 mb-2"><?php esc_html_e( 'Modern UI', 'peprodev-ups' ); ?></p>
		<label class="w-100 row align-items-center m-0 mb-1">
			<input autocomplete="off" type="checkbox" class="form-checkbox iostoggle mr-2" <?php echo $note ? 'disabled' : 'data-ui-key="ui_login"'; ?> value="1" <?php checked( $on ); ?> />
			<?php echo esc_html( $fields['ui_login'][1] ); ?>
		</label>
		<p class="small text-muted mb-2"><?php echo esc_html( $note ? $note : $desc['ui_login'] ); ?></p>
	</div>
	<?php
}

/**
 * Color setting of the modern UI: the text input stays the value store (empty = theme colors),
 * the swatch next to it opens the Alwan picker (ui/assets/js/pd-alwan.js).
 *
 * @param string $key         Field key.
 * @param string $id          Input id.
 * @param bool   $label       Print the title and description (off inside the settings tables, which have their own).
 * @param string $placeholder Built-in color shown while the field is empty.
 */
function peprodev_ui_render_color_field( $key, $id, $label = true, $placeholder = '#28504f' ) {
	$fields = peprodev_ui_texts_fields();
	$desc   = peprodev_ui_texts_descriptions();
	$value  = function_exists( 'peprodev_ui_color_value' ) ? peprodev_ui_color_value( $key ) : peprodev_ui_accent_color();
	peprodev_ui_enqueue_color_picker();
	?>
	<?php if ( $label ) : ?>
		<p class="text-bold mt-3 mb-2"><label class="m-0" style="color:inherit" for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $fields[ $key ][1] ); ?></label></p>
	<?php endif; ?>
	<div class="pd-color-field">
		<input type="text" class="form-input pd-color-picker" dir="ltr" maxlength="7" id="<?php echo esc_attr( $id ); ?>" data-ui-key="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" autocomplete="off" />
		<button type="button" class="btn btn-sm btn-secondary m-0 pd-color-reset" data-target="#<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Theme colors', 'peprodev-ups' ); ?></button>
	</div>
	<?php if ( $label && isset( $desc[ $key ] ) ) : ?>
		<p class="small text-muted mt-1 mb-2"><?php echo esc_html( $desc[ $key ] ); ?></p>
	<?php endif; ?>
	<?php
}

/**
 * Self-hosted Alwan color picker for the admin color settings.
 */
function peprodev_ui_enqueue_color_picker() {
	static $done = false;
	if ( $done ) {
		return;
	}
	$done = true;
	list( $lib_css, $lcv ) = peprodev_ui_asset( 'assets/vendor/alwan/alwan.min.css' );
	list( $lib_js, $ljv )  = peprodev_ui_asset( 'assets/vendor/alwan/alwan.min.js' );
	list( $boot, $bv )     = peprodev_ui_asset( 'assets/js/pd-alwan.js' );
	wp_enqueue_style( 'peprodev-alwan', $lib_css, array(), $lcv );
	wp_enqueue_script( 'peprodev-alwan-lib', $lib_js, array(), $ljv, true );
	wp_enqueue_script( 'peprodev-alwan', $boot, array( 'peprodev-alwan-lib' ), $bv, true );
	wp_add_inline_style(
		'peprodev-alwan',
		'.pd-color-field{display:flex;align-items:center;gap:8px;flex-wrap:wrap}'
		. '.pd-color-field .form-input{flex:0 1 150px;margin:0}'
		. 'button.pd-alwan-swatch,button.pd-alwan-swatch.alwan__ref{width:40px !important;height:40px !important;padding:0 !important;border:1px solid rgba(0,0,0,.2) !important;border-radius:10px !important;cursor:pointer;box-shadow:inset 0 0 0 3px rgba(255,255,255,.6);flex:0 0 auto}'
		. 'button.pd-alwan-swatch.is-empty{background:repeating-linear-gradient(45deg,#eee 0 6px,#fff 6px 12px) !important}'
		. '.alwan{border-radius:12px;overflow:hidden;}'
		. '.alwan__container>button,'
		. '.alwan__input+span{display:none;}'
		. '.alwan__input{padding:5px !important;min-height:fit-content !important;border:none !important;margin:0 !important;}'
	);
}

/**
 * Settings table of the modern UI groups. On the Profile screen ("modern" group) it is
 * collected by profile/assets/js/peprocore-setting.js ([data-ui-key]) and saved by the
 * "save_setting" admin AJAX handler; the LearnDash screen (ui/learndash.php) shows the
 * "learn" and "courses" groups.
 *
 * @param string[]|null $only Groups to show, null = the Profile screen groups.
 */
function peprodev_ui_render_profile_settings( $only = null ) {
	$only         = null === $only ? array( 'modern' ) : (array) $only;
	$desc         = peprodev_ui_texts_descriptions();
	$placeholders = array(
		'{my_courses}'        => __( 'the "My courses" section of the user dashboard', 'peprodev-ups' ),
		'{courses_page}'      => __( 'the page with the "course-list" slug, or the shop page', 'peprodev-ups' ),
		'{profile}'           => __( 'the user dashboard', 'peprodev-ups' ),
		'{shop}'              => __( 'the WooCommerce shop page', 'peprodev-ups' ),
		'{home}'              => __( 'the home page', 'peprodev-ups' ),
		'{continue_learning}' => __( 'resume the last lesson; requires the PeproDev WP Tweaker plugin', 'peprodev-ups' ),
	);
	$login_url    = admin_url( 'admin.php?page=peprodev-ups&section=loginregister#tab_registration' );
	?>
	<table class="table pepcappearance table-striped peprodev-ui-settings" id="peprodev-ui-settings">
		<tbody>
			<?php
			foreach ( peprodev_ui_texts_groups() as $group => $title ) :
				if ( ! in_array( $group, $only, true ) ) {
					continue;
				}
				?>
				<tr><th colspan="2"><strong><?php echo esc_html( $title ); ?></strong></th></tr>
				<?php
				if ( 'learn' === $group ) :
					?>
					<tr>
						<td colspan="2">
							<small>
								<?php esc_html_e( 'You can use these placeholders in the links, or a full URL:', 'peprodev-ups' ); ?>
								<?php foreach ( $placeholders as $placeholder => $help ) : ?>
									<br><code dir="ltr"><?php echo esc_html( $placeholder ); ?></code> &ndash; <?php echo esc_html( $help ); ?>
								<?php endforeach; ?>
								<br><?php esc_html_e( 'Shortcodes:', 'peprodev-ups' ); ?> <code dir="ltr">[peprodev_learning_button]</code> <code dir="ltr">[peprodev_my_courses layout="home|full"]</code>
							</small>
						</td>
					</tr>
					<?php
				endif;
				foreach ( peprodev_ui_texts_fields() as $key => $field ) :
					if ( $field[0] !== $group ) {
						continue;
					}
					if ( 'ui_login' === $key ) :
						?>
						<tr>
							<td><?php echo esc_html( $field[1] ); ?></td>
							<td>
								<?php echo esc_html( peprodev_ui_module_enabled( 'login' ) ? __( 'Enabled', 'peprodev-ups' ) : __( 'Disabled', 'peprodev-ups' ) ); ?>
								&mdash; <a href="<?php echo esc_url( $login_url ); ?>"><?php esc_html_e( 'Change it in Login/Register settings', 'peprodev-ups' ); ?></a>
							</td>
						</tr>
						<?php
						continue;
					endif;
					$id    = 'peprodev_ui_' . $key;
					$value = peprodev_ui_text_raw( $key );
					$note  = peprodev_ui_texts_constant_note( $key );
					if ( 'ui_dashboard' === $key ) {
						$value = peprodev_ui_module_enabled( 'dashboard' ) ? '1' : '0';
					}
					?>
					<tr>
						<td><label class="m-0" style="color:inherit" for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $field[1] ); ?></label></td>
						<td>
							<?php if ( 'checkbox' === $field[3] && $note ) : ?>
								<?php echo esc_html( '1' === $value ? __( 'Enabled', 'peprodev-ups' ) : __( 'Disabled', 'peprodev-ups' ) ); ?>
							<?php elseif ( 'checkbox' === $field[3] ) : ?>
								<a class="btncheckbox" id="<?php echo esc_attr( $id ); ?>" href="#" data-ui-key="<?php echo esc_attr( $key ); ?>"
									data-text-on="<?php esc_attr_e( 'Enabled', 'peprodev-ups' ); ?>"
									data-text-off="<?php esc_attr_e( 'Disabled', 'peprodev-ups' ); ?>"
									data-on="check_box" data-off="check_box_outline_blank"
									data-checked="<?php echo esc_attr( '1' === $value ? 'true' : 'false' ); ?>"></a>
							<?php elseif ( 'color' === $field[3] ) : ?>
								<?php peprodev_ui_render_color_field( $key, $id, false ); ?>
							<?php elseif ( 'textarea' === $field[3] ) : ?>
								<textarea class="form-control" rows="3" id="<?php echo esc_attr( $id ); ?>" data-ui-key="<?php echo esc_attr( $key ); ?>" data-default="<?php echo esc_attr( $field[2] ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
							<?php else : ?>
								<input type="text" class="form-control" dir="<?php echo 'url' === $field[3] ? 'ltr' : 'auto'; ?>" id="<?php echo esc_attr( $id ); ?>" data-ui-key="<?php echo esc_attr( $key ); ?>" data-default="<?php echo esc_attr( $field[2] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
							<?php endif; ?>
							<?php if ( $note || isset( $desc[ $key ] ) ) : ?>
								<br><small class="text-muted"><?php echo esc_html( $note ? $note : $desc[ $key ] ); ?></small>
							<?php endif; ?>
							<?php if ( ! in_array( $field[3], array( 'checkbox', 'color' ), true ) ) : ?>
								<br><small class="text-muted">
									<?php
									/* translators: %s: default value of the setting. */
									echo esc_html( sprintf( __( 'Default: %s', 'peprodev-ups' ), $field[2] ) );
									?>
								</small>
							<?php endif; ?>
						</td>
					</tr>
					<?php
				endforeach;
			endforeach;
			?>
		</tbody>
	</table>
	<?php
}

/* ---------------------------------------------------------------------
 * Learning button + course-access body classes.
 * ------------------------------------------------------------------- */

/**
 * Tokens stylesheet plus the visibility rules and learning button style.
 * Enqueued when a modern module is on, or on demand by the shortcodes.
 */
function peprodev_ui_enqueue_global_css() {
	static $done = false;
	if ( $done ) {
		return;
	}
	if ( ! wp_style_is( 'peprodev-ui-tokens', 'registered' ) ) {
		peprodev_ui_register_assets();
	}
	$done = true;
	wp_enqueue_style( 'peprodev-ui-tokens' );
	wp_add_inline_style(
		'peprodev-ui-tokens',
		'body:not(.mj-no-courses) .mj-no-courses-only,body:not(.mj-has-courses) .mj-has-courses-only{display:none!important}'
		. '.mj-learn-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:48px;padding:10px 22px;border-radius:var(--mj-radius);background:var(--mj-primary);box-shadow:0 10px 22px -12px color-mix(in srgb,var(--mj-primary) 85%,transparent);color:var(--mj-on-primary)!important;font-family:var(--mj-font);font-weight:700;text-decoration:none!important;transition:background-color .2s ease}'
		. '.mj-learn-btn:hover{background:var(--mj-primary-hover)}.mj-learn-btn svg{width:18px;height:18px;fill:currentColor}[dir=ltr] .mj-learn-btn svg{transform:scaleX(-1)}'
	);
}

/**
 * [peprodev_learning_button class=""] (alias [mj_learning_button]).
 *
 * @param array $atts Attributes.
 * @return string
 */
function peprodev_ui_learning_button( $atts = array() ) {
	$atts = shortcode_atts( array( 'class' => '' ), $atts, 'peprodev_learning_button' );
	if ( ! peprodev_ui_text_on( 'learn_enabled' ) ) {
		return '';
	}
	$user_id = get_current_user_id();
	if ( ! $user_id ) {
		if ( ! peprodev_ui_text_on( 'learn_guest_show' ) ) {
			return '';
		}
		$state = 'guest';
	} else {
		$state = peprodev_ui_has_any_course( $user_id ) ? 'has' : 'none';
	}
	$label = peprodev_ui_text( "learn_{$state}_label" );
	$url   = peprodev_ui_text_url( "learn_{$state}_url" );
	if ( '' === $label || '' === $url ) {
		return '';
	}
	peprodev_ui_enqueue_global_css();
	return sprintf(
		'<a class="mj-learn-btn mj-learn-btn--%1$s %2$s" href="%3$s"><span>%4$s</span><svg viewBox="0 0 256 256" aria-hidden="true"><path d="M165.66,202.34a8,8,0,0,1-11.32,11.32l-80-80a8,8,0,0,1,0-11.32l80-80a8,8,0,0,1,11.32,11.32L91.31,128Z"/></svg></a>',
		esc_attr( $state ),
		esc_attr( $atts['class'] ),
		esc_url( $url ),
		esc_html( $label )
	);
}
add_shortcode( 'peprodev_learning_button', 'peprodev_ui_learning_button' );
add_shortcode( 'mj_learning_button', 'peprodev_ui_learning_button' );

/**
 * Body classes so page builders can show blocks only to users with/without
 * course access: add "mj-no-courses-only" or "mj-has-courses-only" to a section.
 * Only added when LearnDash is active.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function peprodev_ui_course_body_class( $classes ) {
	if ( is_user_logged_in() && function_exists( 'learndash_user_get_enrolled_courses' ) ) {
		$classes[] = peprodev_ui_has_any_course( get_current_user_id() ) ? 'mj-has-courses' : 'mj-no-courses';
	}
	return $classes;
}
add_filter( 'body_class', 'peprodev_ui_course_body_class' );

/**
 * Site-wide tokens and visibility CSS: only when a modern module is enabled
 * (or when forced with the "peprodev_ui_load_global_css" filter).
 */
function peprodev_ui_global_css() {
	$load = peprodev_ui_module_enabled( 'login' ) || peprodev_ui_module_enabled( 'dashboard' );
	if ( apply_filters( 'peprodev_ui_load_global_css', $load ) ) {
		peprodev_ui_enqueue_global_css();
	}
}
add_action( 'wp_enqueue_scripts', 'peprodev_ui_global_css', 20 );

/**
 * Load the enabled modules. Runs on after_setup_theme so plugins and the
 * theme can use the "peprodev_ui_module_enabled" filter.
 */
function peprodev_ui_load_modules() {
	if ( peprodev_ui_module_enabled( 'login' ) ) {
		require_once PEPRODEV_UPS_UI_DIR . 'login/login-ui.php';
	}
	if ( peprodev_ui_module_enabled( 'dashboard' ) ) {
		require_once PEPRODEV_UPS_UI_DIR . 'dashboard/dashboard.php';
	}
}
add_action( 'after_setup_theme', 'peprodev_ui_load_modules', 20 );

// The [peprodev_my_courses] shortcode is available even when the modern dashboard is off.
require_once PEPRODEV_UPS_UI_DIR . 'dashboard/courses.php';
// "Modern UI Design" screen: colors of the modern login form and dashboard.
require_once PEPRODEV_UPS_UI_DIR . 'design.php';
// "LearnDash" screen: learning button and "My courses" settings.
require_once PEPRODEV_UPS_UI_DIR . 'learndash.php';
// Text replacement (gettext) rules, edited on the LearnDash screen.
require_once PEPRODEV_UPS_UI_DIR . 'text-replace.php';
