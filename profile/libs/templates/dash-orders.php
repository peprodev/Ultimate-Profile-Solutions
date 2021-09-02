<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/08/29 00:00:26

global $PeproDevUPS_Profile;
global $current_profile_url;
$PeproDevUPS_Profile->change_dashboard_title(_x("Orders","user-dashboard",$PeproDevUPS_Profile->td));
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?php echo _x("Orders","user-dashboard",$PeproDevUPS_Profile->td);?></h2>
        </div>
    </div>
  </div>
  <div class="row m-t-25">
    <div class="col-lg-12">
      <?php
        $PeproDevUPS_Profile->peprofile_get_template_part("wc/orders");
      ?>
    </div>
  </div>
</div>
