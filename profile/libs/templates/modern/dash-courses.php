<?php
/**
 * Modern UI: "My courses" dashboard section.
 *
 * Modern variant of profile/libs/templates/dash-courses.php.
 */

defined( 'ABSPATH' ) || exit;

global $PeproDevUPS_Profile;
$PeproDevUPS_Profile->change_dashboard_title( _x( 'My Courses', 'user-dashboard', 'peprodev-ups' ) );

do_action( 'peprodev_profile_course_welcome' );
?>
<div class="mj-courses-page">
	<h3 class="mj-page-title"><?php echo esc_html_x( 'My Courses', 'user-dashboard', 'peprodev-ups' ); ?></h3>
	<?php echo peprodev_ui_my_courses( array( 'layout' => 'full' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
