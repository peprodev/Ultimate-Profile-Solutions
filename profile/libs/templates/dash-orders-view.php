<?php
# @Author: Amirhosseinhpv
# @Date:   2021/08/28 00:07:32
# @Email:  its@hpv.im
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/15 14:49:35
# @License: GPLv2
# @Copyright: Copyright © Amirhosseinhpv (https://hpv.im), all rights reserved.


global $PeproDevUPS_Profile;
$PeproDevUPS_Profile->change_dashboard_title(_x("View Order","user-dashboard","peprodev-ups"));
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?php echo esc_html_x("View Order","user-dashboard","peprodev-ups");?></h2>
        </div>
    </div>
  </div>
  <div class="row m-t-25">
    <div class="col-lg-12 view-wc-orders">
      <?php
        $PeproDevUPS_Profile->peprofile_get_template_part("wc/view-order");
      ?>
    </div>
  </div>
</div>
