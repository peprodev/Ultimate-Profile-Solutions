<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2022/02/20 02:03:15

global $PeproDevUPS_Profile, $PeproDevUPS_Login;
$current_user = wp_get_current_user();
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?php echo esc_html_x("Edit", "edit-user", "peprodev-ups");?></h2>
        </div>
    </div>
  </div>
  <div class="row m-t-25">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header"><?php echo esc_html_x("Edit Personal Info", "edit-user", "peprodev-ups");?></div>
            <div class="card-body">
                <form class="edit-profile-form" method="post">
                    <div class="row mt-3">
                      <?php
                        $saved     = get_avatar_url( get_current_user_id(), array( "size" => 96));
                        $val       = "";
                        $type      = "file";
                        $id        = "avatar";
                        $extrahtml = "accept='image/jpeg, image/png' ";
                        $class     = "form-control primary bg-light";
                        $title     = _x("Avatar", "edit-user", "peprodev-ups");
                      ?>
                      <div class="col-lg-12 col-md-12">
                        <?php echo "
                        <div class='form-group'>
                          <input style='display: none;' id='$id' name='$id' type='$type' class='form-control $class' $extrahtml value='".esc_attr($val)."' />
                          <label for='$id'>
                            <img src='$saved' width='64' style='border-radius: 4px;' id='avatar_b'/>
                            <span style='margin-inline-start: 0.5rem;'>$title</span>
                          </label>
                        </div>"; ?>
                      </div>
                      <div class="col-lg-6 col-md-12 mt-3">
                        <?php
                          $PeproDevUPS_Profile->add_input(_x("First Name", "edit-user", "peprodev-ups"), "firstname", "$current_user->user_firstname", "required ", "");
                        ?>
                      </div>
                      <div class="col-lg-6 col-md-12 mt-3">
                        <?php
                          $PeproDevUPS_Profile->add_input(_x("Last Name", "edit-user", "peprodev-ups"), "lastname", "$current_user->user_lastname", "required ", "");
                        ?>
                      </div>
                      <?php
                        // show current password if there's any
                        if (!wp_check_password("", $current_user->user_pass, $current_user->ID )) {
                          ?>
                          <div class="col-lg-12 col-md-12 mt-3">
                            <?php
                              $PeproDevUPS_Profile->add_input(_x("Current Password", "edit-user", "peprodev-ups"), "password_current", "", 'autocomplete="off" ', "", "password");
                              ?>
                          </div>
                          <?php
                        }
                      ?>
                      <div class="col-lg-6 col-md-12 mt-3">
                        <?php
                          $PeproDevUPS_Profile->add_input(_x("New Password", "edit-user", "peprodev-ups"), "password_new", "", 'autocomplete="off" ', "", "password");
                          ?>
                      </div>
                      <div class="col-lg-6 col-md-12 mt-3">
                        <?php
                          $PeproDevUPS_Profile->add_input(_x("Confirm Password", "edit-user", "peprodev-ups"), "password_confirm", "", 'autocomplete="off" ', "", "password");
                          ?>
                      </div>
                      <?php
                      if (class_exists("PeproDevUPS_Login")){
                        do_action("peprofile_user_details_before_custom_fields");
                        global $PeproDevUPS_Login; $PeproDevUPS_Login->pepro_profile_sections();
                        do_action("peprofile_user_details_after_custom_fields");
                      }
                      ?>
                    </div>
                    <?php do_action("peprofile_user_details_edit_form_end"); ?>
                    <div class=" mt-3">
                      <button id="submit-profile-changes" href="#" class="btn btn-lg btn-info btn-block loadingRings" type="submit">
                          <?php echo esc_html_x("Save Edit", "edit-user", "peprodev-ups");?>
                      </button>
                    </div>
                </form>
                <div class="save-user-details alert-box"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
      <div class="row">
        <?php
          if (class_exists("PeproDevUPS_Login")){
            do_action("peprofile_user_details_before_verify_mobile");
            global $PeproDevUPS_Login;
            echo $PeproDevUPS_Login->verify_user_mobile_email_inline();
            do_action("peprofile_user_details_after_verify_mobile");
          }
        ?>
      </div>
    </div>
  </div>
  <div class="m-t-25">
    <?php
    if ($PeproDevUPS_Profile->_wc_activated()) {
      echo "<div class='overview-wrap'><h2 class='title-1'>".__("E-commerce settings", "peprodev-ups")."</h2></div><div class='row m-t-25'>";
      $PeproDevUPS_Profile->peprofile_get_template_part("wc/my-address");
      echo "</div>";
    }
    ?>
  </div>
</div>
