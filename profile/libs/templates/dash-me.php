<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/15 14:49:17

global $PeproDevUPS_Profile;
$current_user = wp_get_current_user();
$avatar_url = get_avatar_url( get_current_user_id(), array("size"=> 250,));
$PeproDevUPS_Profile->change_dashboard_title(_x("Profile","user-dashboard","peprodev-ups"));
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?php echo esc_html_x("You","user-dashboard","peprodev-ups");?></h2>
        </div>
    </div>
  </div>
  <div class="row m-t-25">
    <div class="col-lg-6 col-md-12">
        <div class="card">
            <div class="card-header"><?php echo esc_html_x("Personal Info","user-dashboard","peprodev-ups");?></div>
            <div class="card-body">
                <form action="" method="post" novalidate="novalidate">
                    <div class="row">
                          <div class="col-lg-6 col-md-12">
                            <img class="mb-3" src="<?php echo $avatar_url;?>" alt="<?php echo esc_html( $current_user->display_name );?>" />
                          </div>
                          <div class="col-lg-6 col-md-12">
                            <div class="form-group"><span class="control-label mb-1"><?php echo esc_html_x("Name","edit-profile","peprodev-ups")?>: </span><strong><?php echo esc_html( $current_user->user_firstname );?></strong></div>
                            <div class="form-group"><span class="control-label mb-1"><?php echo esc_html_x("Last Name","edit-profile","peprodev-ups")?>: </span><strong><?php echo esc_html( $current_user->user_lastname );?></strong></div>
                            <div class="form-group"><span class="control-label mb-1"><?php echo esc_html_x("Publicly Known as","edit-profile","peprodev-ups")?>: </span><strong><?php echo esc_html( $current_user->display_name );?></strong></div>
                            <div class="form-group"><span class="control-label mb-1"><?php echo esc_html_x("Registered on","edit-profile","peprodev-ups")?>: </span><strong><?php echo esc_html( $current_user->user_registered );?></strong></div>
                            <div class="form-group"><span class="control-label mb-1"><?php echo esc_html_x("Email","edit-profile","peprodev-ups")?>: </span><strong><?php echo esc_html( $current_user->user_email );?></strong></div>
                            <div class="form-group"><span class="control-label mb-1"><?php echo esc_html_x("Username","edit-profile","peprodev-ups")?>: </span><strong><?php echo esc_html( $current_user->user_login  );?></strong></div>
                          </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

  </div>
</div>
