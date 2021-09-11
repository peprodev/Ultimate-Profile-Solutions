<?php
global $PeproDevUPS_Profile;
$PeproDevUPS_Profile->change_dashboard_title(_x("View Order","user-dashboard",$PeproDevUPS_Profile->td));
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?php echo esc_html_x("View Order","user-dashboard",$PeproDevUPS_Profile->td);?></h2>
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
