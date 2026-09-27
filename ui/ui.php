<?php
/**
 * Modern UI layer for PeproDev UPS: restyled login/register form and user
 * dashboard, OTP code boxes, enrolled-courses views and a configurable
 * "continue / start learning" button.
 *
 * Both modules are OPT-IN and off by default. Enable them on
 * PeproDev Profile > Dashboard Texts > "Modern UI", or force them in
 * wp-config.php (a defined constant always wins over the setting):
 *   define( 'PEPRODEV_UPS_UI_LOGIN', true );      // modern login/register form
 *   define( 'PEPRODEV_UPS_UI_DASHBOARD', true );  // modern user dashboard
 *
 * The [peprodev_learning_button] and [peprodev_my_courses] shortcodes are
 * always available; their styles are only loaded when a module is enabled
 * or when one of the shortcodes is rendered.
 */

defined( 'ABSPATH' ) || exit;

define( 'PEPRODEV_UPS_UI_DIR', __DIR__ . '/' );
define( 'PEPRODEV_UPS_UI_URL', plugins_url( '/', __FILE__ ) );
define( 'PEPRODEV_UPS_UI_TEXTS', 'peprodev_ups_ui_texts' );

/**
 * Whether a modern UI module is enabled.
 *
 * A PEPRODEV_UPS_UI_LOGIN / PEPRODEV_UPS_UI_DASHBOARD constant, when defined,
 * wins; otherwise the "Modern UI" setting is used (default: off). Reads the
 * option directly (no gettext) so it is safe to call before "init".
 *
 * @param string $module "login" or "dashboard".
 * @return bool
 */
function peprodev_ui_module_enabled( $module ) {
	$constant = 'login' === $module ? 'PEPRODEV_UPS_UI_LOGIN' : 'PEPRODEV_UPS_UI_DASHBOARD';
	if ( defined( $constant ) ) {
		$on = (bool) constant( $constant );
	} else {
		$saved = get_option( PEPRODEV_UPS_UI_TEXTS, array() );
		$on    = is_array( $saved ) && isset( $saved[ 'ui_' . $module ] ) && '1' === (string) $saved[ 'ui_' . $module ];
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
	wp_register_style( 'peprodev-ui-otp', $otpc, array( 'peprodev-ui-tokens' ), $ocv );
	wp_register_script( 'peprodev-ui-otp', $otpj, array( 'jquery' ), $ojv, true );
}
add_action( 'wp_enqueue_scripts', 'peprodev_ui_register_assets', 5 );

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
 * Settings + editable texts (PeproDev Profile > "Dashboard Texts"), WPML aware.
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
			'ui_login'          => array( 'modern', __( 'Modern login/register form', 'peprodev-ups' ), '0', 'checkbox' ),
			'ui_dashboard'      => array( 'modern', __( 'Modern user dashboard', 'peprodev-ups' ), '0', 'checkbox' ),
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
 * Groups shown on the admin page.
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
 * Saved settings array.
 *
 * @return array
 */
function peprodev_ui_texts_saved() {
	$saved = get_option( PEPRODEV_UPS_UI_TEXTS, array() );
	return is_array( $saved ) ? $saved : array();
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
		if ( 'checkbox' === $field[3] || ! isset( $saved[ $key ] ) || '' === trim( (string) $saved[ $key ] ) ) {
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
 * Admin page under the plugin menu.
 */
function peprodev_ui_texts_menu() {
	$parent = isset( $GLOBALS['admin_page_hooks']['peprodev-ups'] ) ? 'peprodev-ups' : 'options-general.php';
	add_submenu_page( $parent, __( 'Dashboard Texts', 'peprodev-ups' ), __( 'Dashboard Texts', 'peprodev-ups' ), 'manage_options', 'peprodev-ups-ui-texts', 'peprodev_ui_texts_page' );
}
add_action( 'admin_menu', 'peprodev_ui_texts_menu', 99 );

/**
 * Save / reset handler.
 */
function peprodev_ui_texts_save() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Sorry, you are not allowed to do that.', 'peprodev-ups' ), 403 );
	}
	check_admin_referer( 'peprodev_ui_texts_save' );
	$input  = isset( $_POST['peprodev_ui_texts'] ) && is_array( $_POST['peprodev_ui_texts'] ) ? wp_unslash( $_POST['peprodev_ui_texts'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized per field below.
	$reset  = ! empty( $_POST['peprodev_ui_texts_reset'] );
	$old    = peprodev_ui_texts_saved();
	$fields = peprodev_ui_texts_fields();
	$data   = array();
	foreach ( $fields as $key => $field ) {
		// "Reset" restores texts and links only; the Modern UI switches keep their state.
		if ( $reset && 'modern' !== $field[0] ) {
			continue;
		}
		// A switch forced by a wp-config.php constant is shown disabled: keep the stored value.
		if ( 'modern' === $field[0] && peprodev_ui_module_constant( substr( $key, 3 ) ) ) {
			if ( isset( $old[ $key ] ) ) {
				$data[ $key ] = '1' === (string) $old[ $key ] ? '1' : '0';
			}
			continue;
		}
		$raw = isset( $input[ $key ] ) && is_scalar( $input[ $key ] ) ? (string) $input[ $key ] : '';
		if ( 'checkbox' === $field[3] ) {
			$data[ $key ] = '' !== $raw ? '1' : '0';
		} elseif ( 'url' === $field[3] ) {
			$data[ $key ] = preg_match( '/^\{[a-z_]+\}$/', trim( $raw ) ) ? trim( $raw ) : esc_url_raw( $raw );
		} elseif ( 'textarea' === $field[3] ) {
			$data[ $key ] = sanitize_textarea_field( $raw );
		} else {
			$data[ $key ] = sanitize_text_field( $raw );
		}
		// An unchanged default text is not stored, so it stays translatable with the language files.
		if ( 'checkbox' !== $field[3] && $data[ $key ] === (string) $field[2] ) {
			unset( $data[ $key ] );
		}
	}
	update_option( PEPRODEV_UPS_UI_TEXTS, $data, false );
	peprodev_ui_texts_register();
	// admin.php?page= resolves the page under either parent menu (menus are not registered on admin-post.php).
	wp_safe_redirect(
		add_query_arg(
			array(
				'page'    => 'peprodev-ups-ui-texts',
				'updated' => $reset ? 'reset' : '1',
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}
add_action( 'admin_post_peprodev_ui_texts_save', 'peprodev_ui_texts_save' );

/**
 * Admin page markup.
 */
function peprodev_ui_texts_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$updated      = isset( $_GET['updated'] ) ? sanitize_key( wp_unslash( $_GET['updated'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$descriptions = array(
		'ui_login'     => __( 'Restyled mobile/OTP login and register form with Login/Register tabs and OTP code boxes.', 'peprodev-ups' ),
		'ui_dashboard' => __( 'Restyled user dashboard with new Edit profile, order, course and address views.', 'peprodev-ups' ),
	);
	$placeholders = array(
		'{my_courses}'        => __( 'the "My courses" section of the user dashboard', 'peprodev-ups' ),
		'{courses_page}'      => __( 'the page with the "course-list" slug, or the shop page', 'peprodev-ups' ),
		'{profile}'           => __( 'the user dashboard', 'peprodev-ups' ),
		'{shop}'              => __( 'the WooCommerce shop page', 'peprodev-ups' ),
		'{home}'              => __( 'the home page', 'peprodev-ups' ),
		'{continue_learning}' => __( 'resume the last lesson; requires the PeproDev WP Tweaker plugin', 'peprodev-ups' ),
	);
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Dashboard texts and buttons', 'peprodev-ups' ); ?></h1>
		<?php if ( $updated ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php echo esc_html( 'reset' === $updated ? __( 'Settings were reset to their defaults.', 'peprodev-ups' ) : __( 'Settings saved.', 'peprodev-ups' ) ); ?></p></div>
		<?php endif; ?>
		<p><?php esc_html_e( 'You can use these placeholders in the links, or a full URL:', 'peprodev-ups' ); ?></p>
		<ul style="list-style:disc;padding-inline-start:20px;">
			<?php foreach ( $placeholders as $placeholder => $help ) : ?>
				<li><code><?php echo esc_html( $placeholder ); ?></code> &ndash; <?php echo esc_html( $help ); ?></li>
			<?php endforeach; ?>
		</ul>
		<p><?php esc_html_e( 'Shortcodes:', 'peprodev-ups' ); ?> <code>[peprodev_learning_button]</code> <code>[peprodev_my_courses layout="home|full"]</code></p>
		<?php if ( defined( 'WPML_ST_VERSION' ) ) : ?>
			<p><a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=wpml-string-translation/menu/string-translation.php&context=peprodev-ups' ) ); ?>"><?php esc_html_e( 'Translate these texts with WPML', 'peprodev-ups' ); ?></a></p>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="peprodev_ui_texts_save" />
			<?php wp_nonce_field( 'peprodev_ui_texts_save' ); ?>
			<?php foreach ( peprodev_ui_texts_groups() as $group => $title ) : ?>
				<h2><?php echo esc_html( $title ); ?></h2>
				<?php if ( 'modern' === $group ) : ?>
					<p class="description"><?php esc_html_e( 'Off by default: nothing changes on your site until you enable a module here.', 'peprodev-ups' ); ?></p>
				<?php endif; ?>
				<table class="form-table" role="presentation">
					<?php
					foreach ( peprodev_ui_texts_fields() as $key => $field ) :
						if ( $field[0] !== $group ) {
							continue;
						}
						$id       = 'peprodev_ui_texts_' . $key;
						$name     = 'peprodev_ui_texts[' . $key . ']';
						$value    = peprodev_ui_text_raw( $key );
						$constant = 'modern' === $group ? peprodev_ui_module_constant( substr( $key, 3 ) ) : '';
						if ( $constant ) {
							$value = peprodev_ui_module_enabled( substr( $key, 3 ) ) ? '1' : '0';
						}
						?>
						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $field[1] ); ?></label></th>
							<td>
								<?php if ( 'checkbox' === $field[3] ) : ?>
									<label><input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="1" <?php checked( '1', $value ); ?> <?php disabled( '' !== $constant ); ?> /> <?php esc_html_e( 'Enabled', 'peprodev-ups' ); ?></label>
								<?php elseif ( 'textarea' === $field[3] ) : ?>
									<textarea class="large-text" rows="3" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
								<?php else : ?>
									<input type="text" class="regular-text<?php echo 'url' === $field[3] ? ' code' : ''; ?>" dir="<?php echo 'url' === $field[3] ? 'ltr' : 'auto'; ?>" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
								<?php endif; ?>
								<?php if ( isset( $descriptions[ $key ] ) ) : ?>
									<p class="description"><?php echo esc_html( $descriptions[ $key ] ); ?></p>
								<?php endif; ?>
								<?php if ( $constant ) : ?>
									<p class="description">
										<?php
										/* translators: %s: PHP constant name. */
										echo esc_html( sprintf( __( 'Controlled by the %s constant in wp-config.php.', 'peprodev-ups' ), $constant ) );
										?>
									</p>
								<?php endif; ?>
								<p class="description">
									<?php
									$default_text = 'checkbox' === $field[3] ? ( '1' === $field[2] ? __( 'Enabled', 'peprodev-ups' ) : __( 'Disabled', 'peprodev-ups' ) ) : $field[2];
									/* translators: %s: default value of the setting. */
									echo esc_html( sprintf( __( 'Default: %s', 'peprodev-ups' ), $default_text ) );
									?>
								</p>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endforeach; ?>
			<p class="submit">
				<?php submit_button( __( 'Save Settings', 'peprodev-ups' ), 'primary', 'submit', false ); ?>
				<button type="submit" name="peprodev_ui_texts_reset" value="1" class="button" onclick="return confirm('<?php echo esc_js( __( 'Reset all texts and links to their defaults?', 'peprodev-ups' ) ); ?>');"><?php esc_html_e( 'Reset to defaults', 'peprodev-ups' ); ?></button>
			</p>
		</form>
	</div>
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
