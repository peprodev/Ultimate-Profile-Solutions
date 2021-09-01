<!DOCTYPE html>
<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/01 15:25:05

global $PeproDevUPS_Profile, $rtl, $wp, $PeproDevUPS_ProfileStripslashesNotifsJs;
$current_section = isset($_GET["section"]) ? trim($_GET["section"]) : "";
$loadingCLASS = "";
// $loadingCLASS = "lds-circle";
$loadingCLASSrainbow = "color-ring";
$loadingCLASSrainbow = "color-ring-gray";
$classes = apply_filters("peprofile_body_class", "$loadingCLASS section-$current_section ");
$rtl = is_rtl() ? "-rtl" : "";
if (isset($_GET["dir"])) { $rtl = $_GET["dir"] === "rtl" ? "-rtl" : ""; }
if ("true" === get_option("{$PeproDevUPS_Profile->activation_status}-headerhook")){
  do_action("wp_head");
}
if ("true" === get_option("{$PeproDevUPS_Profile->activation_status}-footerhook")){
  do_action("wp_footer");
}
?>
<html lang="en" dir="<?php echo is_rtl() ? "rtl" : "ltr";?>">
<head>
  <title><?=$title;?></title>
  <base href="<?=home_url($wp->request);?>">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <?php $PeproDevUPS_Profile->peprofile_get_template_part("tmplt", "head"); ?>
  <style media="screen">
  /*
   * Global CSS @ PeproDev Ultimate Profile Solutions (https://pepro.dev/ups)
  */
  <?php echo wp_unslash(get_option("{$PeproDevUPS_Profile->activation_status}-css")); ?>
  </style>
  <style media="screen">.wc-order-pagination {display: none;}</style>
</head>
<body data-loading-class="<?=$loadingCLASS;?>" class="<?=$classes;?>">
  <?php $PeproDevUPS_Profile->peprofile_get_template_part("tmplt", "body"); ?>
</body>
<?php

$PeproDevUPS_Profile->peprofile_get_template_part("tmplt", "foot");
?>
<script type="text/javascript">
(function ($) {
  "use strict";
  /*
   *  Global JS @ PeproDev Ultimate Profile Solutions (https://pepro.dev/ups)
  */
  <?=wp_unslash(get_option("{$PeproDevUPS_Profile->activation_status}-js",""));?>
  <?=wp_unslash($PeproDevUPS_ProfileStripslashesNotifsJs);?>
})(jQuery);
</script>
<?php
add_action( "wp_footer", array( $PeproDevUPS_Profile, "remove_us_css"), PHP_INT_MAX);
get_footer();
