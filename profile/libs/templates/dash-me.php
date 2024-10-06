<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/17 20:51:11

global $PeproDevUPS_Profile, $PeproDevUPS_Login;
$current_user = wp_get_current_user();
$avatar_url   = get_avatar_url( get_current_user_id(), array("size"=> 250,));
$user_id      = get_current_user_id();
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
                          <div class="col-lg-4 col-md-12">
                            <img class="mb-3" src="<?php echo $avatar_url;?>" alt="<?php echo esc_html( $current_user->display_name );?>" />
                          </div>
                          <div class="col-lg-8 col-md-12">
                            <div class="form-group"><strong><span class="control-label mb-1"><?php echo esc_html_x("Name","edit-profile","peprodev-ups")?>: </span></strong><span><?php echo esc_html( $current_user->user_firstname );?></span></div>
                            <div class="form-group"><strong><span class="control-label mb-1"><?php echo esc_html_x("Last Name","edit-profile","peprodev-ups")?>: </span></strong><span><?php echo esc_html( $current_user->user_lastname );?></span></div>
                            <div class="form-group"><strong><span class="control-label mb-1"><?php echo esc_html_x("Username","edit-profile","peprodev-ups")?>: </span></strong><span><?php echo esc_html( $current_user->user_login  );?></span></div>
                            <div class="form-group"><strong><span class="control-label mb-1"><?php echo esc_html_x("Email","edit-profile","peprodev-ups")?>: </span></strong><span><?php echo esc_html( $current_user->user_email );?></span></div>
                            <div class="form-group"><strong><span class="control-label mb-1"><?php echo esc_html_x("Mobile","edit-profile","peprodev-ups")?>: </span></strong><span class="forceltr"><?php echo esc_html( get_the_author_meta("user_mobile", $user_id) );?></span></div>
                            <div class="form-group"><strong><span class="control-label mb-1"><?php echo esc_html_x("Registered on","edit-profile","peprodev-ups")?>: </span></strong><span class="forceltr"><?php echo esc_html( date_i18n( "Y/m/d H:i", strtotime($current_user->user_registered) ) );?></span></div>
                            <?php
                            foreach ($PeproDevUPS_Login->get_register_fields() as $field) {
                              if (in_array($field["type"], ["button", "recaptcha"])) continue;
                              if (isset($field["is-editable"]) && "yes" == $field["is-editable"]){
                                $value = get_the_author_meta($field["meta_name"], $user_id);
                                if ("select" == $field["type"]){
                                  $value = $field["options"][$value] ?? $value;
                                }
                                echo "<div class='form-group'><strong><span class='control-label mb-1'>{$field["title"]}: </span></strong><span>".wp_kses_post($value)."</span></div>";
                              }
                            }
                            ?>
                          </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

  </div>
</div>
