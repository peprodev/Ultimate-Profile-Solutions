<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/08/28 23:59:30

global $PeproDevUPS_Profile;
$PeproDevUPS_Profile->change_dashboard_title(_x("My Courses","user-dashboard",$PeproDevUPS_Profile->td));
do_action( 'wp_head' );
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?=_x("My Courses","user-dashboard",$PeproDevUPS_Profile->td);?></h2>
        </div>
    </div>
  </div>
  <?=do_shortcode("[ld_profile]");?>
</div>
