<?php
# @Last modified by:   amirhp-com
# @Last modified time: 2022/05/12 02:15:12
global $PeproDevUPS_Profile, $PeproDevUPS_Login;
$current_user = wp_get_current_user();
$current_user_id = get_current_user_id();
$html = $PeproDevUPS_Profile->get_user_notifications($current_user_id);
$number = $PeproDevUPS_Profile->get_user_notification_count($current_user_id);
$titles = $html["titles"];
$notifs = $html["notifs"];
$notif_unread_count = __("You have no new notification.", "peprodev-ups");
if ($number > 0) {
  $notif_unread_count = sprintf(__("You have %s unread messages.", "peprodev-ups"), "<span class='nunread'>$number</span>");
}
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
        <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
            <div class="au-card-title" style="background-image:url('<?php echo plugins_url('images/bg-title-01.jpg',__FILE__);?>');">
                <div class="bg-overlay bg-overlay--blue"></div>
                <h3> <i class="zmdi zmdi-comment-text"></i><?php echo esc_html_x("Your Latest Notifications","user-dashboard","peprodev-ups");?></h3>
                <button class="au-btn-plus backtonotifs"> <i class="far fa-chevron-left"></i> </button>
            </div>
            <div class="au-inbox-wrap js-inbox-wrap">
              <div class="au-message js-list-load">
                  <div class="au-message__noti notifications">
                      <p><?php echo wp_kses_post( $notif_unread_count );?></p>
                  </div>
                  <div class="au-message-list notifications">
                      <?php echo wp_kses_post( $titles );?>
                  </div>
              </div>

              <?php echo wp_kses_post( $notifs );?>
            </div>
        </div>
    </div>
  </div>
</div>
