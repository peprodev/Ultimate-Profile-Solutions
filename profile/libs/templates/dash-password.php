<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/15 14:49:55

global $PeproDevUPS_Profile;
$current_user = wp_get_current_user();
$PeproDevUPS_Profile->change_dashboard_title(_x("Password & 2FA","user-dashboard","peprodev-ups"));
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?php echo esc_html_x("Password & Two-factor authentication","user-dashboard","peprodev-ups");?></h2>
        </div>
    </div>
  </div>
  <div class="row m-t-25">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><?php echo esc_html_x("Change Password","user-dashboard","peprodev-ups");?></div>
            <div class="card-body">
                <form action="" method="post" novalidate="novalidate">

                    <?php
                      // $PeproDevUPS_Profile->add_input($title='',$id='',$val='',$extrahtml='',$class='',$type='text');
                      $PeproDevUPS_Profile->add_input( _x("Previous Password","edit-user","peprodev-ups"), "prevpass", "", 'autocomplete="off"', "","password");
                    ?>
                    <div class="row">
                        <div class="col-6">
                          <?php
                            $PeproDevUPS_Profile->add_input( _x("New Password","edit-user","peprodev-ups"), "newpass", "", 'autocomplete="off"', "","password");
                          ?>
                        </div>
                        <div class="col-6">
                          <?php
                            $PeproDevUPS_Profile->add_input( _x("Confirm New Password","edit-user","peprodev-ups"), "confirmnewpass", "", 'autocomplete="off"', "","password");
                          ?>
                        </div>
                    </div>
                    <div>
                        <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                            <span id="payment-button-amount"><?php echo esc_html_x("Change password","user-dashboard","peprodev-ups");?></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><?php echo esc_html_x("Two-factor authentication","user-dashboard","peprodev-ups");?></div>
            <div class="card-body">
                <form action="" method="post" novalidate="novalidate">
                    <div class="row">
                      <div class="col-6">
                          <div class="form-group">
                            <label for="2facset" class="control-label mb-1"><?php echo esc_html_x("Activation Setting","user-dashboard","peprodev-ups");?></label>
                            <div class="form-check">
                              <div class="checkbox">
                                <label for="radio1" class="form-check-label">
                                  <input type="checkbox" id="2facset" name="radios" checked value="option1" class="form-check-input"><?php echo esc_html_x("Enable 2FA","user-dashboard","peprodev-ups");?>
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      <div class="col-6">
                      <?php
                        $PeproDevUPS_Profile->add_input( _x("Mobile Number","user-dashboard","peprodev-ups"), "mobile", "");
                      ?>
                      </div>
                    </div>

                    <div>
                        <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                            <span id="payment-button-amount"><?php echo esc_html_x("Save 2FA Setting","user-dashboard","peprodev-ups");?></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>
</div>
