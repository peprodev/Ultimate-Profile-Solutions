<?php

# @Last modified by:   amirhp-com
# @Last modified time: 2022/05/11 14:27:34

global $PeproDevUPS_Profile, $PeproDevUPS_Login;
$current_user = wp_get_current_user();
$form_class = $PeproDevUPS_Login->form_class;
?>
<div class="container-fluid">
  <div class="card">
    <div class="card-body">
      <form class="edit-profile-form <?=$form_class;?>" method="post">
        <div class="row">
          <?php
          $saved     = get_avatar_url( get_current_user_id(), array( "size" => 96));
          $val       = "";
          $type      = "file";
          $id        = "avatar";
          $extrahtml = "accept='image/jpeg, image/png' ";
          $class     = "form-control primary bg-light";
          $title     = _x("Change your avatar", "edit-user", "peprodev-ups");
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
            if (class_exists("\PeproDev\PeproDevUPS_Login")){
              do_action("peprofile_user_details_before_custom_fields");
              global $PeproDevUPS_Login; $PeproDevUPS_Login->pepro_profile_sections();
              do_action("peprofile_user_details_after_custom_fields");
            }
          ?>
        </div>
        <div class="mt-3">
          <button id="submit-profile-changes" href="#" class="btn btn-lg btn-info btn-block loadingRings" type="submit"><?php echo esc_html_x("Save Changes", "edit-user", "peprodev-ups");?></button>
        </div>
      </form>
      <div class="save-user-details alert-box"></div>
    </div>
  </div>
</div>
<div class="container-fluid mt-3">
  <div class="row">
    <?php
    if (class_exists("\PeproDev\PeproDevUPS_Login")){
      do_action("peprofile_user_details_before_verify_mobile");
      global $PeproDevUPS_Login;
      echo $PeproDevUPS_Login->verify_user_mobile_email_inline(false);
      do_action("peprofile_user_details_after_verify_mobile");
    }
    ?>
  </div>
</div>
