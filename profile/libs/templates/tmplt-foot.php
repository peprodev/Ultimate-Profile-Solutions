<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/08/31 20:24:26

global $PeproDevUPS_Profile, $current_profile_url;
wp_enqueue_script( "jquery" );
wp_enqueue_script( "peprodev-popper",    plugins_url("/vendas/bootstrap-4.1/popper.min.js", __FILE__), array("jquery"));
wp_enqueue_script( "peprodev-bootstrap", plugins_url("/vendas/bootstrap-4.1/bootstrap.min.js", __FILE__), array("jquery"));
wp_enqueue_script( "peprodev-main",      plugins_url("/js/main.js", __FILE__), array("jquery"));
wp_register_script( "custom-js.js",      plugins_url("/js/custom-js.js", __FILE__), array("jquery"), current_time( "timestamp" ), true);
wp_localize_script( "custom-js.js",      "_i18n", array(
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
