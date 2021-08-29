<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/08/29 00:00:22

global $PeproDevUPS_Profile,$current_profile_url;
$PeproDevUPS_Profile->change_dashboard_title(_x("Notifications","user-dashboard",$PeproDevUPS_Profile->td));

$html = $PeproDevUPS_Profile->get_user_notifications(get_current_user_id());
$titles = $html["titles"];
$notifs = $html["notifs"];
$number = $PeproDevUPS_Profile->get_user_notification_count(get_current_user_id());
$notif_unread_count = __("You have no new notification.", $PeproDevUPS_Profile->td);
if ($number > 0) {
  $notif_unread_count = sprintf(__("You have %s unread messages.", $PeproDevUPS_Profile->td), "<span class='nunread'>$number</span>");
}

?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?=_x("Notifications","user-dashboard",$PeproDevUPS_Profile->td);?></h2>
        </div>
    </div>
  </div>
  <div class="row m-t-25">
    <div class="col-lg-12">
        <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
            <div class="au-card-title" style="background-image:url('<?=plugins_url('images/bg-title-01.jpg',__FILE__);?>');">
                <div class="bg-overlay bg-overlay--blue"></div>
                <h3> <i class="zmdi zmdi-comment-text"></i><?=_x("Your Latest Notifications","user-dashboard",$PeproDevUPS_Profile->td);?></h3>
                <button class="au-btn-plus backtonotifs"> <i class="far fa-chevron-left"></i> </button>
            </div>
            <div class="au-inbox-wrap js-inbox-wrap">
              <div class="au-message js-list-load">
                  <div class="au-message__noti notifications">
                      <p><?=$notif_unread_count;?></p>
                  </div>
                  <div class="au-message-list notifications">
                      <?=$titles;?>
                  </div>
              </div>

              <?=$notifs;?>
            </div>
        </div>
    </div>
  </div>
</div>
