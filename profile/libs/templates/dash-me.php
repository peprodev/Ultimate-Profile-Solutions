<?php
global $PeproDevUPS_Profile;
$current_user = wp_get_current_user();
$avatar_url = get_avatar_url( get_current_user_id(), array(
  "size"=> 250,
  "default"=> "images/icon/avatar-01.jpg",
));
$PeproDevUPS_Profile->change_dashboard_title(_x("Profile","user-dashboard",$PeproDevUPS_Profile->td));
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?=_x("You","user-dashboard",$PeproDevUPS_Profile->td);?></h2>
        </div>
    </div>
  </div>
  <div class="row m-t-25">
    <div class="col-6">
        <div class="card">
            <div class="card-header"><?=_x("Personal Info","user-dashboard",$PeproDevUPS_Profile->td);?></div>
            <div class="card-body">
                <form action="" method="post" novalidate="novalidate">
                    <div class="row">
                          <div class="col-6">
                            <img src="<?=$avatar_url;?>" alt="<?=esc_html( $current_user->display_name );?>" />
                          </div>
                          <div class="col-6">
                            <div class="form-group"><span class="control-label mb-1"><?=_x("Name","edit-profile",$PeproDevUPS_Profile->td)?>: </span><strong><?=esc_html( $current_user->user_firstname );?></strong></div>
                            <div class="form-group"><span class="control-label mb-1"><?=_x("Last Name","edit-profile",$PeproDevUPS_Profile->td)?>: </span><strong><?=esc_html( $current_user->user_lastname );?></strong></div>
                            <div class="form-group"><span class="control-label mb-1"><?=_x("Publicly Known as","edit-profile",$PeproDevUPS_Profile->td)?>: </span><strong><?=esc_html( $current_user->display_name );?></strong></div>
                            <div class="form-group"><span class="control-label mb-1"><?=_x("Registered on","edit-profile",$PeproDevUPS_Profile->td)?>: </span><strong><?=esc_html( $current_user->user_registered );?></strong></div>
                            <div class="form-group"><span class="control-label mb-1"><?=_x("Email","edit-profile",$PeproDevUPS_Profile->td)?>: </span><strong><?=esc_html( $current_user->user_email );?></strong></div>
                            <div class="form-group"><span class="control-label mb-1"><?=_x("Username","edit-profile",$PeproDevUPS_Profile->td)?>: </span><strong><?=esc_html( $current_user->user_login  );?></strong></div>
                          </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

  </div>
</div>
