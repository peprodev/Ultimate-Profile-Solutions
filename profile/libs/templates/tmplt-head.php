<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/03 15:46:06

global $PeproDevUPS_Profile, $rtl, $current_profile_url, $wp;
$current_profile_url = home_url( $wp->request );
wp_enqueue_style( "peporups_fa-pro",                    plugins_url("/fonts/fa-pro/css/all.css", __FILE__), array(), "1.5.5");
wp_enqueue_style( "peporups_mdi-font",                  plugins_url("/fonts/mdi-font/css/material-design-iconic-font.min.css", __FILE__), array(), "1.5.5");
if (is_rtl()){
  wp_enqueue_style( "peporups_bootstrap",               "//cdn.jsdelivr.net/npm/bootstrap@latest/dist/css/bootstrap.rtl.min.css");
}
else{
  wp_enqueue_style( "peporups_bootstrap",               "//cdn.jsdelivr.net/npm/bootstrap@latest/dist/css/bootstrap.min.css");
}
wp_enqueue_style( "peporups_hamburgers",                plugins_url("/css/hamburgers.min.css", __FILE__), array(), "1.5.5");
wp_enqueue_style( "peporups_datepicker",                "{$PeproDevUPS_Profile->assets_url_as}css/persian-datepicker.min.css", array(), "1.5.5");
wp_enqueue_style( "peporups_theme",                     plugins_url("/css/theme{$rtl}.css", __FILE__), array(), "1.5.5");
wp_enqueue_style( "peporups_custom",                    plugins_url("/css/custom-style.css", __FILE__), array(), "1.5.5");
if (is_rtl()){ wp_enqueue_style( "peporups_custom-rtl", plugins_url("/css/custom-style-rtl.css", __FILE__), array(), "1.5.5"); }
wp_add_inline_style( "peporups_custom",                 '/*'.PHP_EOL.'* Global CSS @ PeproDev Ultimate Profile Solutions (https://pepro.dev/ups)'.PHP_EOL.'*/'.PHP_EOL.wp_unslash(get_option("{$PeproDevUPS_Profile->activation_status}-css")));
