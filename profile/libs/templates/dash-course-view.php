<?php
/*
 * @Author: Amirhossein Hosseinpour <https://amirhp.com>
 * @Date Created: 2023/03/19 14:19:50
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2024/08/24 02:38:04
 */

global $PeproDevUPS_Profile;
$PeproDevUPS_Profile->change_dashboard_title(_x("View Course", "user-dashboard", "peprodev-ups"));
$course_id = intval(isset($_GET['view']) ? $_GET['view'] : 0);

require_once plugin_dir_path(__FILE__) . "class-jdate.php";
add_filter("date_i18n", function ($date, $format, $timestamp, $gmt) { return pu_jdate($format, $timestamp); }, 10, 4); 

?>
<style> div[class*="learndash-shortcode-wrap-course_content"] .ld-section-heading { display: none !important; } </style>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="overview-wrap">
        <h2 class="title-1"><?php echo $course_id ? get_the_title($course_id) : esc_html_x("View Course", "user-dashboard", "peprodev-ups"); ?></h2>
          <?php 
            $expiration_date  = get_post_meta($course_id, "bsl_custom_expiration_date", true);
            $bsl_custom_intro = get_post_meta($course_id, "bsl_custom_intro", true);
            $bsl_start_date   = get_post_meta($course_id, "bsl_start_date", true);
            $is_permanent     = get_post_meta($course_id, "bsl_custom_is_permanent", true);
            // $has_watched      = current_user_can("administrator") ? false : get_user_meta(get_current_user_id(), "_ld_intro_{$course_id}", true);
            $has_watched      = get_user_meta(get_current_user_id(), "_ld_intro_{$course_id}", true);
            $start            = date_i18n("Y/m/d", strtotime($bsl_start_date));
            $end              = date_i18n("Y/m/d", strtotime($expiration_date));
            $start_display    = empty($bsl_start_date) ? "مشخص نشده" : date_i18n("Y/m/d", strtotime($bsl_start_date));
            $end_display      = empty($expiration_date) ? "هرگز" : date_i18n("Y/m/d", strtotime($expiration_date));
            $dateA            = new DateTime();                                                                                                          // Current date and time
            $futureDate       = new DateTime($expiration_date);                                                                                          // Replace with your desired future date
            $interval         = $dateA->diff($futureDate);
            $daysLeft         = empty($bsl_start_date) ? "نامشخص" : (empty($expiration_date) ? "نامحدود" : (max(0, $interval->format('%a')). " روز") );
            if($futureDate < $dateA) {$daysLeft = "منقضی شده";};
            if(empty($expiration_date)) {$daysLeft = "نامحدود";};
            ?>
            <div class="ld-course-info">
              <?php echo "<div>شروع دوره: $start_display</div>
              <div>اتمام دوره: $end_display</div>
              <div>تعداد روزهای باقیمانده: $daysLeft</div>";?>
          </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 view-ld-course">
      <?php
      if ($bsl_custom_intro && !empty($bsl_custom_intro) && "0" != $bsl_custom_intro && $has_watched != "yes") {
        $thumb = get_the_post_thumbnail_url($course_id, "full");
        if (!$thumb) $thumb  = "";
        ?>
        <div class="welcome-backdrop"></div>
        <div class="welcome-popup-wrapper">
          <a href="<?php echo home_url("?course_welcome=$course_id");?>" class="button-close-welcome-sticky"><img src="<?php echo plugins_url("/css/x.svg", __FILE__);?>" alt="x"></a>
          <img src="<?php echo $thumb;?>" class="welcome-image" />
          <div class="welcome-text-frame"><?php echo do_shortcode("[html_block id=\"$bsl_custom_intro\"]");?></div>
          <a href="<?php echo home_url("?course_welcome=$course_id");?>" class="button button-close-welcome">بریم که شروع کنیم!</a>
        </div>
        <?php 
      }else{
        echo do_shortcode("[course_content course_id=$course_id]");
      }
      ?>
    </div>
  </div>
</div>