<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/02 16:06:49

global $PeproDevUPS_Profile, $current_profile_url;
wp_enqueue_script( "jquery" );
wp_enqueue_script( "peprodev-popper",    "//unpkg.com/@popperjs/core@latest", array("jquery"));
wp_enqueue_script( "peprodev-bootstrap", "//unpkg.com/bootstrap@latest", array("jquery"));
wp_enqueue_script( "peprodev-main",      plugins_url("/js/main.js", __FILE__), array("jquery"));
wp_register_script( "peprodev--custom",  plugins_url("/js/custom-js.js", __FILE__), array("jquery"), current_time( "timestamp" ), true);
wp_localize_script( "peprodev--custom",  "_i18n", array(
  "td" => "peprocoreprofile",
  "ajax" => admin_url( "admin-ajax.php"),
  "prductnames" => __("Product name","pepro"),
  "wishlistempty" => __("No products added to the wishlist","pepro"),
  "fillreq" => __("Please fill out all required fields.","pepro"),
  "max_size_err" => sprintf(__("Error, File is too large. Maximum file size is %s MB",$PeproDevUPS_Profile->td), "2"),
  "nonce" => wp_create_nonce( "pepro_profile" ),
  "current_profile_url" => $current_profile_url,
) );
wp_enqueue_script("peprodev--custom");
$js1 = wp_unslash(get_option("{$PeproDevUPS_Profile->activation_status}-js"));
$js2 = wp_unslash($PeproDevUPS_ProfileStripslashesNotifsJs);
wp_add_inline_script("peprodev--custom", '(function ($) {"use strict";
  /*
   *  Global JS @ PeproDev Ultimate Profile Solutions (https://pepro.dev/ups)
  */
  '.$js1.'
  '.$js2.'
})(jQuery);', "after");
