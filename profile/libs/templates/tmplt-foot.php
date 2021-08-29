<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/08/29 00:33:05

global $PeproDevUPS_Profile, $current_profile_url;
?>

<!-- Jquery JS-->
<script src="<?=plugins_url("/vendor/jquery-3.2.1.min.js", __FILE__);?>"></script>
<!-- Bootstrap JS-->
<script src="<?=plugins_url("/vendor/bootstrap-4.1/popper.min.js", __FILE__);?>"></script>
<script src="<?=plugins_url("/vendor/bootstrap-4.1/bootstrap.min.js", __FILE__);?>"></script>
<!-- Main JS -->
<script src="<?=plugins_url("/js/main.js", __FILE__);?>"></script>
<?php
  wp_register_script( "custom-js.js", plugins_url("/js/custom-js.js", __FILE__), array("jquery"), current_time( "timestamp" ), true);
  wp_localize_script( "custom-js.js", "_i18n", array(
    "td" => "peprocoreprofile",
    "ajax" => admin_url( "admin-ajax.php"),
    "prductnames" => __("Product name","pepro"),
    "wishlistempty" => __("No products added to the wishlist","pepro"),
    "fillreq" => __("Please fill out all required fields.","pepro"),
    "max_size_err" => sprintf(__("Error, File is too large. Maximum file size is %s MB",$PeproDevUPS_Profile->td), "2"),
    "nonce" => wp_create_nonce( "pepro_profile" ),
    "current_profile_url" => $current_profile_url,
  ) );
  wp_enqueue_script( "custom-js.js" );
