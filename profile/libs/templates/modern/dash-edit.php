<?php
/**
 * Modern UI: "Edit profile" dashboard section.
 *
 * Modern variant of profile/libs/templates/dash-edit.php. Field ids,
 * names and wrapper classes are kept so the plugin's save/avatar JS
 * (custom-js.js) keeps working unchanged.
 */

defined( 'ABSPATH' ) || exit;

global $PeproDevUPS_Profile, $PeproDevUPS_Login;

$mj_user      = wp_get_current_user();
$mj_avatar    = get_avatar_url( $mj_user->ID, array( 'size' => 192 ) );
$mj_name      = trim( $mj_user->first_name . ' ' . $mj_user->last_name );
$mj_name      = '' !== $mj_name ? $mj_name : $mj_user->display_name;
$mj_mobile    = (string) get_user_meta( $mj_user->ID, 'user_mobile', true );
$mj_custom_av = '' !== (string) get_user_meta( $mj_user->ID, 'profile_image', true );
$mj_has_pass  = ! wp_check_password( '', $mj_user->user_pass, $mj_user->ID );
$mj_since     = date_i18n( 'j F Y', strtotime( $mj_user->user_registered ) );
$mj_eye       = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>';

$PeproDevUPS_Profile->change_dashboard_title( _x( 'Edit', 'user-dashboard', 'peprodev-ups' ) );

/**
 * Text/password input compatible with PeproDevUPS_Profile::add_input().
 */
$mj_input = static function ( $label, $id, $value = '', $args = array() ) use ( $mj_eye ) {
	$args     = wp_parse_args( $args, array( 'type' => 'text', 'required' => false, 'autocomplete' => '', 'hint' => '', 'dir' => '' ) );
	$required = $args['required'] ? ' required' : '';
	$is_pass  = 'password' === $args['type'];
	?>
	<div class="form-group mj-field<?php echo $is_pass ? ' mj-field--password' : ''; ?>">
		<label for="<?php echo esc_attr( $id ); ?>" class="control-label input-wrapper<?php echo esc_attr( $required ); ?>"><?php echo esc_html( $label ); ?></label>
		<div class="mj-field__control">
			<input id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $id ); ?>" type="<?php echo esc_attr( $args['type'] ); ?>" class="form-control<?php echo esc_attr( $required ); ?>" value="<?php echo esc_attr( $value ); ?>"<?php echo $args['required'] ? ' required' : ''; ?><?php echo $args['autocomplete'] ? ' autocomplete="' . esc_attr( $args['autocomplete'] ) . '"' : ''; ?><?php echo $args['dir'] ? ' dir="' . esc_attr( $args['dir'] ) . '"' : ''; ?> />
			<?php if ( $is_pass ) : ?>
				<button type="button" class="mj-field__reveal" aria-label="<?php esc_attr_e( 'Show password', 'peprodev-ups' ); ?>" aria-pressed="false" data-target="<?php echo esc_attr( $id ); ?>"><?php echo $mj_eye; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
			<?php endif; ?>
		</div>
		<?php if ( $args['hint'] ) : ?>
			<small class="mj-field__hint"><?php echo esc_html( $args['hint'] ); ?></small>
		<?php endif; ?>
	</div>
	<?php
};
?>
<div class="mj-edit">
	<h3 class="profile-header mj-page-title"><?php echo esc_html_x( 'Edit Personal Info', 'edit-user', 'peprodev-ups' ); ?></h3>

	<form class="edit-profile-form mj-edit__form" method="post" novalidate>

		<section class="mj-card mj-identity">
			<div class="form-group mj-identity__avatar">
				<input style="display:none" id="avatar" name="avatar" type="file" class="form-control form-control primary bg-light" accept="image/jpeg, image/png" />
				<label for="avatar" class="mj-avatar-picker" title="<?php esc_attr_e( 'Change picture', 'peprodev-ups' ); ?>">
					<img src="<?php echo esc_url( $mj_avatar ); ?>" width="96" height="96" id="avatar_b" alt="" />
					<span class="mj-avatar-picker__badge" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 8h3l2-3h6l2 3h3v11H4z"/><circle cx="12" cy="13" r="3.5"/></svg></span>
					<span class="screen-reader-text"><?php echo esc_html_x( 'Avatar', 'edit-user', 'peprodev-ups' ); ?></span>
				</label>
			</div>
			<div class="mj-identity__text">
				<strong class="mj-identity__name"><?php echo esc_html( $mj_name ); ?></strong>
				<?php if ( $mj_mobile ) : ?>
					<span class="mj-identity__meta" dir="ltr"><?php echo esc_html( $mj_mobile ); ?></span>
				<?php endif; ?>
				<span class="mj-identity__meta"><?php /* translators: %s: registration date. */ echo esc_html( sprintf( __( 'Member since %s', 'peprodev-ups' ), $mj_since ) ); ?></span>
				<small class="mj-identity__hint"><?php esc_html_e( 'Tap the picture to change it. JPG or PNG, up to 2 MB.', 'peprodev-ups' ); ?></small>
				<?php if ( $mj_custom_av ) : ?>
					<button type="button" class="mj-link-btn mj-avatar-remove"><?php esc_html_e( 'Remove picture and use Gravatar', 'peprodev-ups' ); ?></button>
				<?php endif; ?>
			</div>
		</section>

		<section class="mj-card">
			<header class="mj-card__head">
				<h4 class="mj-card__title"><?php esc_html_e( 'Personal information', 'peprodev-ups' ); ?></h4>
				<p class="mj-card__desc"><?php esc_html_e( 'This name is shown on certificates, orders and site messages.', 'peprodev-ups' ); ?></p>
			</header>
			<div class="mj-grid">
				<?php
				$mj_input( _x( 'First Name', 'edit-user', 'peprodev-ups' ), 'firstname', $mj_user->first_name, array( 'required' => true, 'autocomplete' => 'given-name' ) );
				$mj_input( _x( 'Last Name', 'edit-user', 'peprodev-ups' ), 'lastname', $mj_user->last_name, array( 'required' => true, 'autocomplete' => 'family-name' ) );
				?>
			</div>
			<?php
			if ( class_exists( 'PeproDevUPS_Login' ) && $PeproDevUPS_Login ) {
				echo '<div class="mj-custom-fields">';
				do_action( 'peprofile_user_details_before_custom_fields' );
				$PeproDevUPS_Login->pepro_profile_sections();
				do_action( 'peprofile_user_details_after_custom_fields' );
				echo '</div>';
			}
			?>
		</section>

		<details class="mj-card mj-card--collapsible">
			<summary class="mj-card__head">
				<h4 class="mj-card__title"><?php echo esc_html_x( 'Password', 'modern-ui', 'peprodev-ups' ); ?></h4>
				<p class="mj-card__desc"><?php echo esc_html( $mj_has_pass ? __( 'Open this section to change your password.', 'peprodev-ups' ) : __( 'You log in with an SMS code. You can also set a password if you like.', 'peprodev-ups' ) ); ?></p>
			</summary>
			<div class="mj-grid">
				<?php
				if ( $mj_has_pass ) {
					echo '<div class="mj-grid__full">';
					$mj_input( _x( 'Current Password', 'edit-user', 'peprodev-ups' ), 'password_current', '', array( 'type' => 'password', 'autocomplete' => 'current-password', 'dir' => 'ltr' ) );
					echo '</div>';
				}
				$mj_input( _x( 'New Password', 'edit-user', 'peprodev-ups' ), 'password_new', '', array( 'type' => 'password', 'autocomplete' => 'new-password', 'dir' => 'ltr', 'hint' => __( 'At least 8 characters, a mix of letters and numbers', 'peprodev-ups' ) ) );
				$mj_input( _x( 'Confirm Password', 'edit-user', 'peprodev-ups' ), 'password_confirm', '', array( 'type' => 'password', 'autocomplete' => 'new-password', 'dir' => 'ltr' ) );
				?>
			</div>
			<div class="mj-strength" aria-live="polite" hidden><span class="mj-strength__bar"><i></i></span><span class="mj-strength__label"></span></div>
		</details>

		<?php do_action( 'peprofile_user_details_edit_form_end' ); ?>

		<div class="mj-savebar submit-wrap">
			<div class="save-user-details alert-box" role="status"></div>
			<button id="submit-profile-changes" class="btn btn-lg btn-info btn-block loadingRings mj-btn-primary" type="submit"><?php echo esc_html_x( 'Save Edit', 'edit-user', 'peprodev-ups' ); ?></button>
		</div>
	</form>

	<?php
	if ( class_exists( 'PeproDevUPS_Login' ) && $PeproDevUPS_Login ) {
		$mj_verify = $PeproDevUPS_Login->verify_user_mobile_email_inline();
		if ( '' !== trim( (string) $mj_verify ) ) {
			// title follows the verification forms shown (Login/Register > Profile Verification Form)
			$mj_verify_mode = isset( $PeproDevUPS_Login->pro_verify ) ? (string) $PeproDevUPS_Login->pro_verify : 'sms';
			if ( 'email' === $mj_verify_mode ) {
				$mj_verify_title = __( 'Verify your email address', 'peprodev-ups' );
				$mj_verify_desc  = __( 'Once verified, account messages and password recovery links are sent to this address.', 'peprodev-ups' );
			} elseif ( 'both' === $mj_verify_mode ) {
				$mj_verify_title = __( 'Verify your email and mobile', 'peprodev-ups' );
				$mj_verify_desc  = __( 'Verified contact details receive session reminders and account recovery messages.', 'peprodev-ups' );
			} else {
				$mj_verify_title = __( 'Verify your mobile number', 'peprodev-ups' );
				$mj_verify_desc  = __( 'Once verified, session reminders and account recovery messages are sent to this number.', 'peprodev-ups' );
			}
			echo '<section class="mj-card mj-verify"><header class="mj-card__head"><h4 class="mj-card__title">' . esc_html( $mj_verify_title ) . '</h4><p class="mj-card__desc">' . esc_html( $mj_verify_desc ) . '</p></header>';
			do_action( 'peprofile_user_details_before_verify_mobile' );
			echo $mj_verify; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			do_action( 'peprofile_user_details_after_verify_mobile' );
			echo '</section>';
		}
	}

	if ( $PeproDevUPS_Profile->_wc_activated() && function_exists( 'peprodev_ui_profile_render_addresses' ) ) {
		peprodev_ui_profile_render_addresses();
	}
	?>
</div>
