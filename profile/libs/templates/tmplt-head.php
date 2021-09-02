<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/02 13:14:49

global $PeproDevUPS_Profile, $rtl, $current_profile_url, $wp;
$current_profile_url = home_url( $wp->request );
wp_enqueue_script( "jquery" );
wp_enqueue_style( "peporups_fa-pro", plugins_url("/vendas/fa-pro/css/all.css", __FILE__));
wp_enqueue_style( "peporups_mdi-font", plugins_url("/vendas/mdi-font/css/material-design-iconic-font.min.css", __FILE__));
wp_enqueue_style( "peporups_bootstrap", plugins_url("/vendas/bootstrap-4.1/bootstrap${rtl}.min.css", __FILE__));
wp_enqueue_style( "peporups_hamburgers", plugins_url("/vendas/css-hamburgers/hamburgers.min.css", __FILE__));
wp_enqueue_style( "peporups_datepicker", "{$PeproDevUPS_Profile->assets_url_as}css/persian-datepicker.min.css");
wp_enqueue_style( "peporups_theme", plugins_url("/css/theme{$rtl}.css", __FILE__));
wp_enqueue_style( "peporups_custom", plugins_url("/css/custom-style.css", __FILE__));
if (is_rtl()){
  wp_enqueue_style( "peporups_custom-rtl", plugins_url("/css/custom-style-rtl.css", __FILE__));
}
wp_add_inline_style( "peporups_custom", '/*'.PHP_EOL.'* Global CSS @ PeproDev Ultimate Profile Solutions (https://pepro.dev/ups)'.PHP_EOL.'*/'.PHP_EOL.wp_unslash(get_option("{$PeproDevUPS_Profile->activation_status}-css")));
