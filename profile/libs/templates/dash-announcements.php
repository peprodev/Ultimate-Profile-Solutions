<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/15 14:47:26

global $PeproDevUPS_Profile,$current_profile_url;
$PeproDevUPS_Profile->change_dashboard_title(_x("Announcements","user-dashboard","peprodev-ups"));

$html = $PeproDevUPS_Profile->get_user_announcements(get_current_user_id());
$titles = $html["titles"];
$notifs = $html["notifs"];
$number = $PeproDevUPS_Profile->get_user_announcements_count(get_current_user_id());
$notif_unread_count = __("You have no new announcement.", "peprodev-ups");
if ($number > 0) {
  $notif_unread_count = sprintf(__("You have %s unread announcements.", "peprodev-ups"), "<span class='nunread'>$number</span>");
}

?>
<div class="container-fluid">
  <div class="row m-t-25">
    <div class="col-lg-12">
        <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
            <div class="au-card-title" style="background-image:url('<?php echo plugins_url('images/bg-title-01.jpg',__FILE__);?>');">
                <div class="bg-overlay bg-overlay--blue"></div>
                <h3> <i class="zmdi zmdi-comment-text"></i><?php echo esc_html_x("Latest Announcements for you","user-dashboard","peprodev-ups");?></h3>
                <button class="au-btn-plus backtonotifs"> <i class="far fa-chevron-left"></i> </button>
            </div>
            <div class="au-inbox-wrap js-inbox-wrap">
              <div class="au-message js-list-load">
                  <div class="au-message__noti announcements">
                      <p><?php echo $notif_unread_count;?></p>
                  </div>
                  <div class="au-message-list announcements">
                      <?php echo $titles;?>
                  </div>
              </div>

              <?php echo $notifs;?>
            </div>
        </div>
    </div>
  </div>
</div>
