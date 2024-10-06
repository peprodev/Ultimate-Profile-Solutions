<?php
/*
 * @Author: Amirhossein Hosseinpour <https://amirhp.com>
 * @Date Created: 2023/02/06 01:05:23
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2024/08/24 02:38:04
 */

global $PeproDevUPS_Profile;
$PeproDevUPS_Profile->change_dashboard_title(_x("My Courses", "user-dashboard", "peprodev-ups"));
// global $wp_head_run;
// if (null == $wp_head_run) { get_header(); }
// $wp_head_run = true;
?>
<style>
  .ld-profile-summary {
    display: none;
  }

  .ld-section-heading h3 {
    visibility: hidden;
  }

  .learndash-wrapper .ld-item-list .ld-item-list-item .ld-item-list-item-preview {
    padding: 12px;
    font-size: 14px;
  }

  .learndash-wrapper .ld-item-list {
    margin: 0em 0 1em !important;
  }

  .ld-section-heading {
    display: none !important;
  }

</style>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 ld-course-list">
      <?php

        require_once plugin_dir_path(__FILE__) . "class-jdate.php";
        add_filter("date_i18n", function ($date, $format, $timestamp, $gmt) { return pu_jdate($format, $timestamp); }, 10, 4); 

        $user_id   = get_current_user_id();
        $courses   = learndash_user_get_enrolled_courses($user_id, array(), false);
        $access    = array();
        $no_access = array();
        $course_history = (array) get_user_meta($user_id, '_ld_course_history', true);;
        if (!empty($courses)) {
          foreach ((array) $courses as $c_id) {
            ld_course_access_expired($c_id, $user_id );
            if (ld_course_access_expired($c_id, $user_id ) === true) { $no_access[] = $c_id; } else { $access[] = $c_id; }
          }
        }
        $no_access_new = array_merge($no_access, $course_history);
        $no_access_diff = array_diff($no_access_new, $access);
        $array_no_access = array_unique($no_access_diff);
        $defaults = array(
          'user_id'            => get_current_user_id(),
          'per_page'           => false,
          'order'              => 'DESC',
          'orderby'            => 'ID',
          'course_points_user' => 'yes',
          'expand_all'         => false,
          'profile_link'       => 'yes',
          'show_header'        => 'yes',
          'show_quizzes'       => 'yes',
          'show_search'        => 'yes',
          'search'             => '',
          'quiz_num'           => false,
        );

        
        
        // echo do_shortcode("<h3>دوره های فعال</h3>[ld_profile]");
        do_action("peprodev_profile_course_welcome");
        
        if (!empty($courses) && !empty($access)) {
          echo "<h3>دوره های فعال من</h3>";
          peprodev_profile_print_ld_courses_list($access);
        }
        if (!empty($courses) && !empty($array_no_access)) {
          echo "<br><br><h3>دوره های منقضی شده</h3>";
          peprodev_profile_print_ld_courses_list($array_no_access);
        }
        if (empty($courses) || (empty($access) && empty($array_no_access))) {
          echo '<h3>دوره های من</h3>
          <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">هیچ دوره ای برای شما ثبت نشده است.</div>';
        }
        
      ?>
    </div>
  </div>
</div>


<?php 

function peprodev_profile_print_ld_courses_list($array){
  $user_id   = get_current_user_id();
  echo "<div class='course-cart-wrapper'>";
  foreach ($array as $course_id) {
          $expiration_date  = get_post_meta($course_id, "bsl_custom_expiration_date", true);
          $bsl_custom_intro = get_post_meta($course_id, "bsl_custom_intro", true);
          $bsl_start_date   = get_post_meta($course_id, "bsl_start_date", true);
          $is_permanent     = get_post_meta($course_id, "bsl_custom_is_permanent", true);
          $has_watched      = get_user_meta($user_id, "_ld_intro_{$course_id}", true);
          $start            = date_i18n("Y/m/d", strtotime($bsl_start_date));
          $end              = date_i18n("Y/m/d", strtotime($expiration_date));
          $start_display    = empty($bsl_start_date) ? "مشخص نشده" : date_i18n("Y/m/d", strtotime($bsl_start_date));
          $end_display      = "no" == $is_permanent || empty($expiration_date) ? "هرگز" : date_i18n("Y/m/d", strtotime($expiration_date));
          $dateA            = new DateTime(); // Current date and time
          $futureDate       = new DateTime($expiration_date); // Replace with your desired future date
          $interval         = $dateA->diff($futureDate);
          $daysLeft         = empty($bsl_start_date) ? "نامشخص" : (empty($expiration_date) ? "نامحدود" : (max(0, $interval->format('%a')). " روز") );
          if($futureDate < $dateA) {$daysLeft = "منقضی شده";};
          if(empty($expiration_date)) {$daysLeft = "نامحدود";};
          $thumb = get_the_post_thumbnail_url( $course_id, "full");
          if (!$thumb) $thumb  = "";
        ?>
        <div class="course-cart course-<?php echo $course_id;?>">
          <div class="course-image-wrapper"><a href='<?php echo get_the_permalink($course_id);?>'><img src="<?php echo $thumb;?>" class="course-img" /></a></div>
          <div class="course-title-wrapper"><a href='<?php echo get_the_permalink($course_id);?>'><h3><?php echo get_the_title($course_id);?></h3></a></div>
          <div class="course-info-wrapper">
            <?php
              echo "
                <div>شروع دوره: $start_display</div>
                <div>اتمام دوره: $end_display</div>
                <div>تعداد روزهای باقیمانده: $daysLeft</div>
              ";
            ?>
          </div>
        </div>
      <?php 
  }
  echo "</div>";

}


?>