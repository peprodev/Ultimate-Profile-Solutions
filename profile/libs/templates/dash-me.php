<?php
# @Author: Amirhosseinhpv
# @Date:   2021/08/28 00:07:32
# @Email:  its@hpv.im
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/01 22:38:53
# @License: GPLv2
# @Copyright: Copyright © Amirhosseinhpv (https://hpv.im), all rights reserved.


global $PeproDevUPS_Profile;
$current_user = wp_get_current_user();
$avatar_url = get_avatar_url( get_current_user_id(), array("size"=> 250,));
$PeproDevUPS_Profile->change_dashboard_title(_x("Profile","user-dashboard",$PeproDevUPS_Profile->td));
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?php echo _x("You","user-dashboard",$PeproDevUPS_Profile->td);?></h2>
        </div>
    </div>
  </div>
  <div class="row m-t-25">
    <div class="col-6">
        <div class="card">
            <div class="card-header"><?php echo _x("Personal Info","user-dashboard",$PeproDevUPS_Profile->td);?></div>
            <div class="card-body">
                <form action="" method="post" novalidate="novalidate">
                    <div class="row">
                          <div class="col-6">
                            <img src="<?php echo $avatar_url;?>" alt="<?php echo esc_html( $current_user->display_name );?>" />
                          </div>
                          <div class="col-6">
                            <div class="form-group"><span class="control-label mb-1"><?php echo _x("Name","edit-profile",$PeproDevUPS_Profile->td)?>: </span><strong><?php echo esc_html( $current_user->user_firstname );?></strong></div>
                            <div class="form-group"><span class="control-label mb-1"><?php echo _x("Last Name","edit-profile",$PeproDevUPS_Profile->td)?>: </span><strong><?php echo esc_html( $current_user->user_lastname );?></strong></div>
                            <div class="form-group"><span class="control-label mb-1"><?php echo _x("Publicly Known as","edit-profile",$PeproDevUPS_Profile->td)?>: </span><strong><?php echo esc_html( $current_user->display_name );?></strong></div>
                            <div class="form-group"><span class="control-label mb-1"><?php echo _x("Registered on","edit-profile",$PeproDevUPS_Profile->td)?>: </span><strong><?php echo esc_html( $current_user->user_registered );?></strong></div>
                            <div class="form-group"><span class="control-label mb-1"><?php echo _x("Email","edit-profile",$PeproDevUPS_Profile->td)?>: </span><strong><?php echo esc_html( $current_user->user_email );?></strong></div>
                            <div class="form-group"><span class="control-label mb-1"><?php echo _x("Username","edit-profile",$PeproDevUPS_Profile->td)?>: </span><strong><?php echo esc_html( $current_user->user_login  );?></strong></div>
                          </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

  </div>
</div>
