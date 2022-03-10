<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/15 14:47:43

global $PeproDevUPS_Profile;
do_action( 'wp_head' );
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?php echo esc_html_x("My Courses","user-dashboard","peprodev-ups");?></h2>
        </div>
    </div>
  </div>
  <?php echo do_shortcode("[ld_profile]");?>
</div>
