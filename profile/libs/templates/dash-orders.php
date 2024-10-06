<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/15 14:49:39

global $PeproDevUPS_Profile;
global $current_profile_url;
$PeproDevUPS_Profile->change_dashboard_title(_x("Orders","user-dashboard","peprodev-ups"));
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?php echo esc_html_x("Orders","user-dashboard","peprodev-ups");?></h2>
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
