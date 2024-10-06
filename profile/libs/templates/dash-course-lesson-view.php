<?php
/*
 * @Author: Amirhossein Hosseinpour <https://amirhp.com>
 * @Date Created: 2023/03/19 14:19:50
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2024/08/24 02:38:04
 */

global $PeproDevUPS_Profile;
$course_id = intval(isset($_GET['view']) ? $_GET['view'] : 0);
$lesson_id = intval(isset($_GET['lesson']) ? $_GET['lesson'] : 0);
$lesson    = get_post($lesson_id);
global $post;
$post = $lesson;
remove_filter("post_type_link", "amirhp_com__course_link", 10, 4);
$cur_user  = wp_get_current_user();
$user_id   = (is_user_logged_in() ? $cur_user->ID : false);
$logged_in = (is_user_logged_in() ? true : false);
$PeproDevUPS_Profile->change_dashboard_title(get_the_title($course_id) . " - " . get_the_title($lesson));
?>
<style>
  .learndash-wrapper .ld-focus .ld-focus-sidebar {
    position: absolute;
  }

  .learndash-wrapper .ld-focus .ld-focus-header {
    position: absolute;
  }

  .learndash-wrapper .ld-focus .ld-focus-header .ld-brand-logo {
    position: relative;
  }

  .learndash-wrapper .ld-focus .ld-focus-header .ld-brand-logo a {
    height: 100%;
    margin: auto;
    display: block;
  }

  .learndash-wrapper .ld-focus .ld-focus-header .ld-brand-logo a img {
    margin: auto;
    display: block;
  }
</style>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="overview-wrap">
        <h2 class="title-1"><?php echo "<a href='" . remove_query_arg("lesson") . "'>" . ($course_id ? get_the_title($course_id) : esc_html_x("View Course", "user-dashboard", "peprodev-ups")) . "</a>"; ?> / <?php echo $lesson_id ? get_the_title($lesson_id) : esc_html_x("View Lesson", "user-dashboard", "peprodev-ups"); ?></h2>
      </div>
    </div>
  </div>
  <div class="row mt-2">
    <div class="col-lg-12 view-ld-course">
      <iframe src="<?php echo get_the_permalink($lesson_id)?>" frameborder="0">loading ...</iframe>
    </div>
  </div>
</div>