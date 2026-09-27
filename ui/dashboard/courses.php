<?php
/**
 * Modern UI: enrolled LearnDash courses (dashboard home + "My courses").
 *
 * [peprodev_my_courses layout="home|full"] (alias [mj_my_courses]) renders a "continue learning" card for
 * the most recently studied course, then the courses the user can access.
 * layout="full" also lists expired courses.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Split the user's courses into accessible and expired.
 *
 * @param int $user_id User ID.
 * @return array{active:int[],expired:int[]}
 */
function peprodev_ui_courses_for_user( $user_id ) {
	$active  = array();
	$expired = array();
	if ( ! $user_id || ! function_exists( 'learndash_user_get_enrolled_courses' ) ) {
		return compact( 'active', 'expired' );
	}
	foreach ( (array) learndash_user_get_enrolled_courses( $user_id, array(), false ) as $course_id ) {
		$course_id = (int) $course_id;
		if ( 'sfwd-courses' !== get_post_type( $course_id ) || 'publish' !== get_post_status( $course_id ) ) {
			continue;
		}
		if ( function_exists( 'ld_course_access_expired' ) && true === ld_course_access_expired( $course_id, $user_id ) ) {
			$expired[] = $course_id;
		} elseif ( ! function_exists( 'sfwd_lms_has_access' ) || sfwd_lms_has_access( $course_id, $user_id ) ) {
			$active[] = $course_id;
		}
	}
	$history = (array) get_user_meta( $user_id, '_ld_course_history', true );
	foreach ( $history as $course_id ) {
		$course_id = (int) $course_id;
		if ( $course_id && 'sfwd-courses' === get_post_type( $course_id ) && ! in_array( $course_id, $active, true ) && ! in_array( $course_id, $expired, true ) ) {
			$expired[] = $course_id;
		}
	}
	return compact( 'active', 'expired' );
}

/**
 * Progress, resume step and last activity for one course.
 *
 * @param int $user_id   User ID.
 * @param int $course_id Course ID.
 * @return array{done:int,total:int,percent:int,resume:string,last:int}
 */
function peprodev_ui_course_status( $user_id, $course_id ) {
	$p       = function_exists( 'learndash_course_progress' ) ? learndash_course_progress( array( 'user_id' => $user_id, 'course_id' => $course_id, 'array' => true ) ) : array();
	$done    = isset( $p['completed'] ) ? (int) $p['completed'] : 0;
	$total   = isset( $p['total'] ) ? (int) $p['total'] : 0;
	$percent = isset( $p['percentage'] ) ? (int) $p['percentage'] : ( $total ? (int) round( $done * 100 / $total ) : 0 );

	$step = function_exists( 'learndash_user_course_last_step' ) ? (int) learndash_user_course_last_step( $user_id, $course_id ) : 0;
	if ( ! $step && function_exists( 'learndash_course_get_steps_by_type' ) ) {
		$lessons = learndash_course_get_steps_by_type( $course_id, 'sfwd-lessons' );
		$step    = $lessons ? (int) reset( $lessons ) : 0;
	}
	$resume = $step ? get_permalink( $step ) : peprodev_ui_course_url( $course_id );

	$last = 0;
	if ( function_exists( 'learndash_get_user_activity' ) ) {
		$activity = learndash_get_user_activity(
			array(
				'user_id'       => $user_id,
				'post_id'       => $course_id,
				'course_id'     => $course_id,
				'activity_type' => 'course',
			)
		);
		if ( $activity && ! empty( $activity->activity_updated ) ) {
			$last = (int) $activity->activity_updated;
		}
	}
	if ( ! $last && function_exists( 'ld_course_access_from' ) ) {
		$last = (int) ld_course_access_from( $course_id, $user_id );
	}

	return array(
		'done'    => $done,
		'total'   => $total,
		'percent' => max( 0, min( 100, $percent ) ),
		'resume'  => (string) $resume,
		'last'    => $last,
	);
}

/**
 * Course thumbnail, uncropped source so it fills the frame without zooming.
 *
 * @param int $course_id Course ID.
 * @return string
 */
function peprodev_ui_course_thumb( $course_id ) {
	if ( has_post_thumbnail( $course_id ) ) {
		return get_the_post_thumbnail( $course_id, 'full', array( 'loading' => 'lazy', 'alt' => '' ) );
	}
	$products = get_posts(
		array(
			'post_type'      => 'product',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_query'     => array( array( 'key' => '_related_course', 'value' => '"' . (int) $course_id . '"', 'compare' => 'LIKE' ) ), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		)
	);
	if ( $products && has_post_thumbnail( $products[0] ) ) {
		return get_the_post_thumbnail( $products[0], 'full', array( 'loading' => 'lazy', 'alt' => '' ) );
	}
	return function_exists( 'wc_placeholder_img' ) ? wc_placeholder_img( 'full' ) : '';
}

/**
 * Shortcode renderer.
 *
 * @param array $atts Attributes.
 * @return string
 */
function peprodev_ui_my_courses( $atts = array() ) {
	$atts    = shortcode_atts( array( 'layout' => 'home' ), $atts, 'mj_my_courses' );
	$user_id = get_current_user_id();
	if ( ! $user_id ) {
		return '';
	}
	$lists = peprodev_ui_courses_for_user( $user_id );
	if ( ! $lists['active'] && ( 'full' !== $atts['layout'] || ! $lists['expired'] ) ) {
		if ( 'full' !== $atts['layout'] ) {
			return '';
		}
		peprodev_ui_enqueue_global_css();
		return '<div class="mj-mc"><div class="mj-mc__empty"><p>' . esc_html( peprodev_ui_text( 'mc_empty_note' ) ) . '</p>' . peprodev_ui_learning_button() . '</div></div>';
	}

	$status = array();
	foreach ( $lists['active'] as $course_id ) {
		$status[ $course_id ] = peprodev_ui_course_status( $user_id, $course_id );
	}

	$current = 0;
	$best    = -1;
	foreach ( $status as $course_id => $s ) {
		if ( $s['percent'] >= 100 ) {
			continue;
		}
		if ( $s['last'] > $best ) {
			$best    = $s['last'];
			$current = $course_id;
		}
	}
	if ( ! $current && $lists['active'] ) {
		$current = (int) $lists['active'][0];
	}

	peprodev_ui_enqueue_global_css();
	$continue_url = '';
	if ( $current ) {
		// With the default "{my_courses}" link the card would point at the list itself, so resume the course shown on the card instead.
		$continue_url = '{my_courses}' === trim( peprodev_ui_text_raw( 'learn_has_url' ) ) ? $status[ $current ]['resume'] : peprodev_ui_text_url( 'learn_has_url' );
		$continue_url = (string) apply_filters( 'peprodev_ui_continue_url', $continue_url, $current, $user_id );
	}

	ob_start();
	?>
	<div class="mj-mc mj-mc--<?php echo esc_attr( $atts['layout'] ); ?>">
		<?php if ( $current ) : ?>
			<?php $s = $status[ $current ]; ?>
			<section class="mj-continue">
				<span class="mj-continue__thumb"><?php echo peprodev_ui_course_thumb( $current ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<div class="mj-continue__text">
					<small><?php echo esc_html( $s['done'] ? __( 'Pick up where you left off', 'peprodev-ups' ) : __( 'Your first lesson is waiting', 'peprodev-ups' ) ); ?></small>
					<strong><?php echo esc_html( get_the_title( $current ) ); ?></strong>
					<?php if ( $s['total'] ) : ?>
						<span class="mj-mini-progress" aria-label="<?php echo esc_attr( $s['percent'] . '%' ); ?>"><i style="width:<?php echo esc_attr( $s['percent'] ); ?>%"></i></span>
					<?php endif; ?>
				</div>
				<a class="mj-continue__cta" href="<?php echo esc_url( $continue_url ); ?>">
					<?php echo esc_html( $s['done'] ? peprodev_ui_text( 'learn_has_label' ) : peprodev_ui_text( 'mc_start_label' ) ); ?>
					<svg viewBox="0 0 256 256" aria-hidden="true"><path d="M165.66,202.34a8,8,0,0,1-11.32,11.32l-80-80a8,8,0,0,1,0-11.32l80-80a8,8,0,0,1,11.32,11.32L91.31,128Z"/></svg>
				</a>
			</section>
		<?php endif; ?>

		<?php if ( $lists['active'] ) : ?>
			<?php if ( 'full' === $atts['layout'] ) : ?>
				<h3 class="mj-mc__heading"><?php esc_html_e( 'My active courses', 'peprodev-ups' ); ?></h3>
			<?php endif; ?>
			<div class="mj-mc__grid">
				<?php foreach ( $lists['active'] as $course_id ) : ?>
					<?php $s = $status[ $course_id ]; ?>
					<article class="mj-mc__card">
						<a class="mj-mc__media" href="<?php echo esc_url( peprodev_ui_course_url( $course_id ) ); ?>" tabindex="-1" aria-hidden="true"><?php echo peprodev_ui_course_thumb( $course_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
						<div class="mj-mc__body">
							<h4 class="mj-mc__title"><a href="<?php echo esc_url( peprodev_ui_course_url( $course_id ) ); ?>"><?php echo esc_html( get_the_title( $course_id ) ); ?></a></h4>
							<?php if ( $s['total'] ) : ?>
								<div class="mj-mc__progress">
									<span class="mj-mini-progress"><i style="width:<?php echo esc_attr( $s['percent'] ); ?>%"></i></span>
									<small><?php
										/* translators: %s: completion percentage. */
										echo esc_html( sprintf( __( '%s%% complete', 'peprodev-ups' ), number_format_i18n( $s['percent'] ) ) );
										?></small>
								</div>
							<?php endif; ?>
						</div>
						<div class="mj-mc__foot">
							<a class="mj-mc__btn" href="<?php echo esc_url( peprodev_ui_course_url( $course_id ) ); ?>"><?php echo esc_html( peprodev_ui_text( 'mc_view_label' ) ); ?></a>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( 'full' === $atts['layout'] && $lists['expired'] ) : ?>
			<h3 class="mj-mc__heading"><?php esc_html_e( 'Expired courses', 'peprodev-ups' ); ?></h3>
			<div class="mj-mc__grid mj-mc__grid--expired">
				<?php foreach ( $lists['expired'] as $course_id ) : ?>
					<article class="mj-mc__card is-expired">
						<span class="mj-mc__media"><?php echo peprodev_ui_course_thumb( $course_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<div class="mj-mc__body">
							<h4 class="mj-mc__title"><?php echo esc_html( get_the_title( $course_id ) ); ?></h4>
							<small class="mj-mc__expired"><?php esc_html_e( 'Your access to this course has ended.', 'peprodev-ups' ); ?></small>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'peprodev_my_courses', 'peprodev_ui_my_courses' );
add_shortcode( 'mj_my_courses', 'peprodev_ui_my_courses' );
