<?php
global $PeproDevUPS_Profile;
$current_user = wp_get_current_user();
$PeproDevUPS_Profile->change_dashboard_title(_x("Password & 2FA","user-dashboard",$PeproDevUPS_Profile->td));
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?=_x("Password & Two-factor authentication","user-dashboard",$PeproDevUPS_Profile->td);?></h2>
        </div>
    </div>
  </div>
  <div class="row m-t-25">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><?=_x("Change Password","user-dashboard",$PeproDevUPS_Profile->td);?></div>
            <div class="card-body">
                <form action="" method="post" novalidate="novalidate">

                    <?php
                      // $PeproDevUPS_Profile->add_input($title='',$id='',$val='',$extrahtml='',$class='',$type='text');
                      $PeproDevUPS_Profile->add_input( _x("Previous Password","edit-user",$PeproDevUPS_Profile->td), "prevpass", "", 'autocomplete="off"', "","password");
                    ?>
                    <div class="row">
                        <div class="col-6">
                          <?php
                            $PeproDevUPS_Profile->add_input( _x("New Password","edit-user",$PeproDevUPS_Profile->td), "newpass", "", 'autocomplete="off"', "","password");
                          ?>
                        </div>
                        <div class="col-6">
                          <?php
                            $PeproDevUPS_Profile->add_input( _x("Confirm New Password","edit-user",$PeproDevUPS_Profile->td), "confirmnewpass", "", 'autocomplete="off"', "","password");
                          ?>
                        </div>
                    </div>
                    <div>
                        <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                            <span id="payment-button-amount"><?=_x("Change password","user-dashboard",$PeproDevUPS_Profile->td);?></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><?=_x("Two-factor authentication","user-dashboard",$PeproDevUPS_Profile->td);?></div>
            <div class="card-body">
                <form action="" method="post" novalidate="novalidate">
                    <div class="row">
                      <div class="col-6">
                          <div class="form-group">
                            <label for="2facset" class="control-label mb-1"><?=_x("Activation Setting","user-dashboard",$PeproDevUPS_Profile->td);?></label>
                            <div class="form-check">
                              <div class="checkbox">
                                <label for="radio1" class="form-check-label">
                                  <input type="checkbox" id="2facset" name="radios" checked value="option1" class="form-check-input"><?=_x("Enable 2FA","user-dashboard",$PeproDevUPS_Profile->td);?>
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      <div class="col-6">
                      <?php
                        $PeproDevUPS_Profile->add_input( _x("Mobile Number","user-dashboard",$PeproDevUPS_Profile->td), "mobile", "");
                      ?>
                      </div>
                    </div>

                    <div>
                        <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                            <span id="payment-button-amount"><?=_x("Save 2FA Setting","user-dashboard",$PeproDevUPS_Profile->td);?></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>
</div>
