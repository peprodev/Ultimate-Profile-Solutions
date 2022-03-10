<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/17 21:06:51
namespace PeproDev;
use PeproDev;
class footer extends PeproDevUPS_Profile
{

  function __construct()
  {
    parent::__construct();
    wp_enqueue_script( "jquery" );
    wp_enqueue_script("peprodev-main", "$this->modern_assets/js/main.js", array("jquery"));
    wp_enqueue_script("peprodev-extras", "$this->modern_assets/js/extras.js", array("jquery"), $this->script_version, true);
    wp_register_script("peprodev-custom", "$this->modern_assets/js/custom-js.js", array("jquery"), $this->script_version, true);
    wp_localize_script("peprodev-custom", "_i18n", array(
      "td"                  => "peprocoreprofile",
      "ajax"                => admin_url( "admin-ajax.php"),
      "nonce"               => wp_create_nonce( "pepro_profile" ),
      "prductnames"         => __("Product name", "peprodev-ups"),
      "wishlistempty"       => __("No products added to the wishlist", "peprodev-ups"),
      "fillreq"             => __("Please fill out all required fields.", "peprodev-ups"),
      "max_size_err"        => __("Error, file is too large. Maximum allowed file size is #fs#","peprodev-ups"),
    ));

    wp_enqueue_script("peprodev-custom");
    $js1 = wp_unslash($this->custom_js);
    $js2 = wp_unslash($this->notifs_js);
    wp_add_inline_script("peprodev-custom", '(function ($) {"use strict";
      /*
       *  Global JS @ PeproDev Ultimate Profile Solutions (https://pepro.dev/ups)
      */
      '.$js1.'
      '.$js2.'
    })(jQuery);', "after");

  }
}
new footer;
