<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/04 14:32:59
namespace PeproDev;
use PeproDev;
class head extends PeproDevUPS_Profile
{

  function __construct()
  {
    parent::__construct();
    echo "<pre style='text-align: left; direction: ltr; border:1px solid gray; padding: 1rem; overflow: auto;'>". print_r($this->current_profile_url,1) ."</pre>";
    wp_enqueue_style("peporups_theme", "$this->profile_assets/modern/css/modern.css", [], $this->script_version);
    wp_enqueue_style("peporups_fa-pro", "$this->profile_assets/modern/css/all.min.css", [], $this->script_version);

    if (!empty($this->custom_css)) {
      wp_add_inline_style("peporups_theme", '/*'.PHP_EOL.
      '* Global CSS @ PeproDev Ultimate Profile Solutions (https://pepro.dev/ups)'.PHP_EOL.
      '*/'.PHP_EOL. wp_unslash($this->custom_css) );
    }

    if (is_rtl()){
      wp_enqueue_style("peporups_theme", "$this->profile_assets/modern/css/modern-rtl.css", [], $this->script_version);
    }

  }
}
new head;
