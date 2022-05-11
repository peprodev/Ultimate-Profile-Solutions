<?php
global $PeproDevUPS_Profile, $PeproDevUPS_Login;
$current_user = wp_get_current_user();
$form_class = $PeproDevUPS_Login->form_class;
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <form class="edit-profile-form <?=$form_class;?>" method="post">
                  <?php
                  // show current password if there's any
                  if (!wp_check_password("", $current_user->user_pass, $current_user->ID )) {
                    ?>
                    <div class="current_password">
                      <?php
                        $PeproDevUPS_Profile->add_input(_x("Current Password", "edit-user", "peprodev-ups"), "password_current", "", 'autocomplete="off" ', "", "password");
                      ?>
                    </div>
                    <?php
                  }
                  ?>
                  <div class="new_password mt-3">
                    <?php
                      $PeproDevUPS_Profile->add_input(_x("New Password", "edit-user", "peprodev-ups"), "password_new", "", 'autocomplete="off" ', "", "password");
                    ?>
                  </div>
                  <div class="confirm_password mt-3">
                    <?php
                      $PeproDevUPS_Profile->add_input(_x("Confirm Password", "edit-user", "peprodev-ups"), "password_confirm", "", 'autocomplete="off" ', "", "password");
                    ?>
                  </div>
                  <div class="submit_changes mt-3">
                    <button id="submit-profile-changes" href="#" class="btn btn-lg btn-info btn-block loadingRings" type="submit"><?php echo esc_html_x("Change Password", "edit-user", "peprodev-ups");?></button>
                  </div>
                </form>
                <div class="save-user-details alert-box"></div>
            </div>
        </div>
    </div>
  </div>
</div>
