<?php
/**
 * Modern UI: single LearnDash course inside the dashboard.
 *
 * Modern variant of profile/libs/templates/dash-course-view.php. Access
 * rules, the "bsl_*" course meta and the welcome intro popup behave as in the
 * plugin template; only the presentation changes.
 */

defined( 'ABSPATH' ) || exit;

global $PeproDevUPS_Profile;
$PeproDevUPS_Profile->change_dashboard_title( _x( 'View Course', 'user-dashboard', 'peprodev-ups' ) );

$mj_course_id = isset( $_GET['view'] ) ? absint( wp_unslash( $_GET['view'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$mj_user_id   = get_current_user_id();
$mj_back      = $PeproDevUPS_Profile->get_profile_page( array( 'section' => 'courses' ) );

require_once dirname( __DIR__ ) . '/class-jdate.php';
// Jalali dates for Persian sites, the regular WordPress date format elsewhere.
$mj_jalali = function_exists( 'pu_jdate' ) && 0 === strpos( get_locale(), 'fa' );
$mj_date   = function ( $timestamp ) use ( $mj_jalali ) {
	return $mj_jalali ? pu_jdate( 'Y/m/d', $timestamp, '', 'local', 'en' ) : date_i18n( get_option( 'date_format' ), $timestamp );
};

$mj_valid   = $mj_course_id && 'sfwd-courses' === get_post_type( $mj_course_id );
$mj_expired = $mj_valid && function_exists( 'ld_course_access_expired' ) && true === ld_course_access_expired( $mj_course_id, $mj_user_id );
$mj_access  = $mj_valid && function_exists( 'sfwd_lms_has_access' ) && sfwd_lms_has_access( $mj_course_id, $mj_user_id );
$mj_allowed = $mj_valid && ( ( $mj_access && ! $mj_expired ) || current_user_can( 'manage_options' ) );
?>
<div class="mj-course">
	<a class="mj-back" href="<?php echo esc_url( $mj_back ); ?>">
		<svg viewBox="0 0 256 256" aria-hidden="true"><path d="M221.66,133.66l-72,72a8,8,0,0,1-11.32-11.32L196.69,136H40a8,8,0,0,1,0-16H196.69L138.34,61.66a8,8,0,0,1,11.32-11.32l72,72A8,8,0,0,1,221.66,133.66Z"/></svg>
		<?php esc_html_e( 'Back to my courses', 'peprodev-ups' ); ?>
	</a>

	<?php if ( ! $mj_allowed ) : ?>
		<section class="mj-card mj-course__locked">
			<h3 class="mj-page-title"><?php echo esc_html( $mj_valid ? get_the_title( $mj_course_id ) : _x( 'View Course', 'user-dashboard', 'peprodev-ups' ) ); ?></h3>
			<p>
				<?php
				if ( $mj_expired ) {
					$mj_course_label = class_exists( 'LearnDash_Custom_Label' ) ? LearnDash_Custom_Label::get_label( 'course' ) : __( 'course', 'peprodev-ups' );
					/* translators: %s: "course" label (LearnDash custom label). */
					echo esc_html( sprintf( __( 'Your access to this %s has expired.', 'peprodev-ups' ), $mj_course_label ) );
				} else {
					esc_html_e( 'You do not have access to this course.', 'peprodev-ups' );
				}
				?>
			</p>
			<?php if ( $mj_valid && function_exists( 'peprodev_ui_course_url' ) ) : ?>
				<a class="btn mj-btn-primary" href="<?php echo esc_url( get_permalink( $mj_course_id ) ); ?>"><?php esc_html_e( 'View the course page', 'peprodev-ups' ); ?></a>
			<?php endif; ?>
		</section>
	<?php else : ?>
		<?php
		$mj_expiration = get_post_meta( $mj_course_id, 'bsl_custom_expiration_date', true );
		$mj_intro      = get_post_meta( $mj_course_id, 'bsl_custom_intro', true );
		$mj_start_meta = get_post_meta( $mj_course_id, 'bsl_start_date', true );
		$mj_permanent  = get_post_meta( $mj_course_id, 'bsl_custom_is_permanent', true );
		$mj_watched    = get_user_meta( $mj_user_id, "_ld_intro_{$mj_course_id}", true );

		if ( empty( $mj_expiration ) && function_exists( 'ld_course_access_expires_on' ) ) {
			$mj_expires_on = ld_course_access_expires_on( $mj_course_id, $mj_user_id );
			if ( $mj_expires_on ) {
				$mj_expiration = gmdate( 'Y-m-d H:i:s', $mj_expires_on );
			}
		}
		$mj_from  = $mj_start_meta ? strtotime( $mj_start_meta ) : ( function_exists( 'ld_course_access_from' ) ? (int) ld_course_access_from( $mj_course_id, $mj_user_id ) : 0 );
		$mj_left  = __( 'Unlimited', 'peprodev-ups' );
		$mj_end   = 'no' === $mj_permanent && empty( $mj_expiration ) ? __( 'Never', 'peprodev-ups' ) : '';
		if ( $mj_expiration ) {
			$mj_end  = $mj_date( strtotime( $mj_expiration ) );
			$mj_diff = (int) floor( ( strtotime( $mj_expiration ) - time() ) / DAY_IN_SECONDS );
			/* translators: %s: number of days. */
			$mj_left = $mj_diff < 0 ? __( 'Expired', 'peprodev-ups' ) : sprintf( _n( '%s day', '%s days', $mj_diff, 'peprodev-ups' ), number_format_i18n( $mj_diff ) );
		}

		$mj_progress = function_exists( 'learndash_course_progress' ) ? learndash_course_progress( array( 'user_id' => $mj_user_id, 'course_id' => $mj_course_id, 'array' => true ) ) : array();
		$mj_done     = isset( $mj_progress['completed'] ) ? (int) $mj_progress['completed'] : 0;
		$mj_total    = isset( $mj_progress['total'] ) ? (int) $mj_progress['total'] : 0;
		$mj_percent  = isset( $mj_progress['percentage'] ) ? (int) $mj_progress['percentage'] : ( $mj_total ? (int) round( $mj_done * 100 / $mj_total ) : 0 );

		$mj_resume = 0;
		if ( function_exists( 'learndash_user_course_last_step' ) ) {
			$mj_resume = (int) learndash_user_course_last_step( $mj_user_id, $mj_course_id );
		}
		if ( ! $mj_resume && function_exists( 'learndash_course_get_steps_by_type' ) ) {
			$mj_lessons = learndash_course_get_steps_by_type( $mj_course_id, 'sfwd-lessons' );
			$mj_resume  = $mj_lessons ? (int) reset( $mj_lessons ) : 0;
		}
		$mj_short = get_post_meta( $mj_course_id, '_learndash_course_grid_short_description', true );
		?>
		<section class="mj-card mj-course__hero">
			<div class="mj-course__thumb">
				<?php echo get_the_post_thumbnail( $mj_course_id, 'full', array( 'alt' => '' ) ); ?>
			</div>
			<div class="mj-course__info">
				<h3 class="mj-page-title"><?php echo esc_html( get_the_title( $mj_course_id ) ); ?></h3>
				<?php if ( $mj_short ) : ?>
					<div class="mj-course__short"><?php echo wp_kses_post( do_shortcode( $mj_short ) ); ?></div>
				<?php endif; ?>
				<?php if ( $mj_total ) : ?>
					<div class="mj-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo esc_attr( $mj_percent ); ?>">
						<div class="mj-progress__row">
							<span><?php esc_html_e( 'Your progress', 'peprodev-ups' ); ?></span>
							<strong><?php /* translators: %s: percentage number. */ echo esc_html( sprintf( _x( '%s%%', 'percentage', 'peprodev-ups' ), number_format_i18n( $mj_percent ) ) ); ?></strong>
						</div>
						<span class="mj-progress__bar"><i style="width:<?php echo esc_attr( max( 0, min( 100, $mj_percent ) ) ); ?>%"></i></span>
						<small><?php /* translators: 1: completed steps, 2: total steps. */ echo esc_html( sprintf( __( '%1$s of %2$s steps completed', 'peprodev-ups' ), number_format_i18n( $mj_done ), number_format_i18n( $mj_total ) ) ); ?></small>
					</div>
				<?php endif; ?>
				<?php if ( $mj_resume ) : ?>
					<a class="btn mj-btn-primary mj-course__resume" href="<?php echo esc_url( get_permalink( $mj_resume ) ); ?>"><?php echo esc_html( $mj_done ? __( 'Continue learning', 'peprodev-ups' ) : __( 'Start learning', 'peprodev-ups' ) ); ?></a>
				<?php endif; ?>
			</div>
			<dl class="mj-course__facts">
				<?php if ( $mj_from ) : ?>
					<div><dt><?php esc_html_e( 'Access starts', 'peprodev-ups' ); ?></dt><dd><?php echo esc_html( $mj_date( $mj_from ) ); ?></dd></div>
				<?php endif; ?>
				<?php if ( $mj_end ) : ?>
					<div><dt><?php esc_html_e( 'Access ends', 'peprodev-ups' ); ?></dt><dd><?php echo esc_html( $mj_end ); ?></dd></div>
				<?php endif; ?>
				<div><dt><?php esc_html_e( 'Time remaining', 'peprodev-ups' ); ?></dt><dd><?php echo esc_html( $mj_left ); ?></dd></div>
			</dl>
		</section>

		<?php
		$mj_content_classes = array( 'mj-card', 'mj-course__content', 'view-ld-course' );
		if ( function_exists( 'peprodev_ui_text_on' ) && peprodev_ui_text_on( 'course_numbering' ) ) {
			$mj_content_classes[] = 'mj-course--numbered';
		}
		if ( function_exists( 'peprodev_ui_text_on' ) && peprodev_ui_text_on( 'course_expanded' ) ) {
			$mj_content_classes[] = 'mj-course--expanded';
		}
		?>
		<section class="<?php echo esc_attr( implode( ' ', $mj_content_classes ) ); ?>">
			<header class="mj-card__head">
				<h4 class="mj-card__title"><?php esc_html_e( 'Course content', 'peprodev-ups' ); ?></h4>
			</header>
			<?php
			if ( $mj_intro && '0' !== (string) $mj_intro && 'yes' !== $mj_watched ) {
				$mj_thumb = get_the_post_thumbnail_url( $mj_course_id, 'full' );
				?>
				<div class="welcome-backdrop"></div>
				<div class="welcome-popup-wrapper">
					<a href="<?php echo esc_url( home_url( "?course_welcome={$mj_course_id}" ) ); ?>" class="button-close-welcome-sticky"><img src="<?php echo esc_url( plugins_url( 'css/x.svg', __DIR__ ) ); ?>" alt="<?php esc_attr_e( 'Close', 'peprodev-ups' ); ?>"></a>
					<?php if ( $mj_thumb ) : ?>
						<img src="<?php echo esc_url( $mj_thumb ); ?>" class="welcome-image" alt="" />
					<?php endif; ?>
					<div class="welcome-text-frame"><?php echo do_shortcode( '[html_block id="' . absint( $mj_intro ) . '"]' ); ?></div>
					<a href="<?php echo esc_url( home_url( "?course_welcome={$mj_course_id}" ) ); ?>" class="button button-close-welcome"><?php esc_html_e( 'Let\'s get started!', 'peprodev-ups' ); ?></a>
				</div>
				<?php
			} else {
				echo do_shortcode( '[course_content course_id="' . absint( $mj_course_id ) . '"]' );
			}
			?>
		</section>
	<?php endif; ?>
</div>
