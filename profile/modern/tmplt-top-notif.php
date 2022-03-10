<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/15 14:51:30


global $PeproDevUPS_Profile , $wp;
$current_page = home_url($wp->request);

$number = $PeproDevUPS_Profile->get_user_notification_count(get_current_user_id());
$notif = __("You have no new notification.", "peprodev-ups");
if ($number > 0) {  $notif = sprintf(__("You have %s unread notifications.", "peprodev-ups"), "<span class='nunread'>$number</span>");}

$Anumber = $PeproDevUPS_Profile->get_user_announcements_count(get_current_user_id());
$Anotif = __("You have no new announcement.", "peprodev-ups");
if ($Anumber > 0) {  $Anotif = sprintf(__("You have %s unread announcements.", "peprodev-ups"), "<span class='nunread'>$Anumber</span>");}

?>
<div class="noti__item notifications js-item-menu">
    <i class="fas fa-bell" style="font-size: 24px;"></i>
    <?php
    if ($number > 0){
      echo "<span class=\"quantity\">$number</span>";
    }
    ?>
    <div class="mess-dropdown js-dropdown" style="transform: scale(0);">
        <div class="mess__title">
            <p><?php echo $notif;?></p>
        </div>
        <?php if ($number > 0){
          echo $PeproDevUPS_Profile->get_user_notifications_short(get_current_user_id(),4);
        } ?>

        <div class="notifi__footer">
            <a href="<?php echo "{$current_page}/?section=notifications";?>"><?php esc_html_e("All notifications","peprodev-ups");?></a>
        </div>
    </div>
</div>
<div class="noti__item announcements js-item-menu">
    <i class="fa fa-bullhorn" style="font-size: 24px;"></i>
    <?php
    if ($Anumber > 0){
      echo "<span class=\"quantity\">$Anumber</span>";
    }
    ?>
    <div class="mess-dropdown js-dropdown" style="transform: scale(0);">
        <div class="mess__title">
            <p><?php echo $Anotif;?></p>
        </div>
        <?php if ($Anumber > 0){
          echo $PeproDevUPS_Profile->get_user_announcements_short(get_current_user_id(),4);
        } ?>

        <div class="notifi__footer">
            <a href="<?php echo "{$current_page}/?section=announcements";?>"><?php esc_html_e("All announcements","peprodev-ups");?></a>
        </div>
    </div>
</div>
