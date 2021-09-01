<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/08/31 20:25:45


global $PeproDevUPS_Profile,$rtl,$current_profile_url, $wp;
$current_profile_url = home_url( $wp->request );
wp_enqueue_script( "jquery" );

?>
<!-- Fontfaces CSS-->
<link href="<?=plugins_url("/vendas/fa-pro/css/all.css", __FILE__);?>" rel="stylesheet" media="all">
<link href="<?=plugins_url("/vendas/mdi-font/css/material-design-iconic-font.min.css", __FILE__);?>" rel="stylesheet" media="all">
<!-- Bootstrap CSS-->
<link href="<?=plugins_url("/vendas/bootstrap-4.1/bootstrap${rtl}.min.css", __FILE__);?>" rel="stylesheet" media="all">
<!-- vendas CSS-->
<link href="<?=plugins_url("/vendas/css-hamburgers/hamburgers.min.css", __FILE__);?>" rel="stylesheet" media="all">
<link href="<?="{$PeproDevUPS_Profile->assets_url_as}css/persian-datepicker.min.css"?>" rel="stylesheet" media="all">
<!-- Main CSS-->
<link href="<?=plugins_url("/css/theme{$rtl}.css", __FILE__);?>" rel="stylesheet" media="all">
<link href="<?=plugins_url("/css/custom-style.css", __FILE__);?>" rel="stylesheet" media="all">
<?php
if (is_rtl()){ echo "<link href='". plugins_url("/css/custom-style-rtl.css", __FILE__) ."' rel='stylesheet' media='all'>";}

?>
