<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/04 14:32:59
namespace PeproDev;
use PeproDev;
class header extends PeproDevUPS_Profile
{

  function __construct()
  {
    parent::__construct();

    echo "<pre style='text-align: left; direction: ltr; border:1px solid indianred; padding: 1rem; color: indianred;
    display: block;z-index: 77777777777 !important;position: relative;background: white;'>".print_r($this->get_profile_page(["i"=>current_time("timestamp")]),1)."</pre>";

    wp_enqueue_style("peprodev-theme", "$this->modern_assets/css/modern.css", [], $this->script_version);
    wp_enqueue_style("peprodev-fafa", "$this->modern_assets/css/all.min.css", [], $this->script_version);

    if (!empty($this->custom_css)) {
      wp_add_inline_style("peprodev-theme", '/*'.PHP_EOL.
      '* Global CSS @ PeproDev Ultimate Profile Solutions (https://pepro.dev/ups)'.PHP_EOL.
      '*/'.PHP_EOL. wp_unslash($this->custom_css) );
    }

    if (is_rtl()){
      wp_enqueue_style("peprodev-theme-rtl", "$this->modern_assets/css/modern-rtl.css", [], $this->script_version);
    }

  }
}
new header;
