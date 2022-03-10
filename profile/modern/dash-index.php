<!DOCTYPE html>
<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/15 13:51:40

global $PeproDevUPS_Profile, $rtl, $wp, $PeproDevUPS_ProfileStripslashesNotifsJs;
$current_section                = isset($_GET["section"]) ? sanitize_text_field(trim($_GET["section"])) : "";
$loadingCLASS                   = "";
$loadingCLASSrainbow            = "color-ring";
$loadingCLASSrainbow            = "color-ring-gray";
$classes                        = apply_filters("peprofile_body_class", "$loadingCLASS section-$current_section ");
$rtl                            = is_rtl() ? "-rtl" : "";
if (isset($_GET["dir"])) { $rtl = $_GET["dir"] === "rtl" ? "-rtl" : ""; }
if ("true" === get_option("{$PeproDevUPS_Profile->activation_status}-headerhook")){ do_action("wp_head"); }
if ("true" === get_option("{$PeproDevUPS_Profile->activation_status}-footerhook")){ do_action("wp_footer"); }
?>
<html lang="en" dir="<?php echo is_rtl() ? "rtl" : "ltr";?>">
<head>
  <title><?php echo esc_html( $title );?></title>
  <base href="<?php echo home_url($wp->request);?>">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <?php $PeproDevUPS_Profile->peprofile_get_template_part("tmplt", "head"); ?>
  <style media="screen">.wc-order-pagination {display: none;}</style>
</head>
<body data-loading-class="<?php echo $loadingCLASS;?>" class="<?php echo $classes;?>">
  <?php
    $PeproDevUPS_Profile->peprofile_get_template_part("tmplt", "body");
  ?>
</body>
<?php
$PeproDevUPS_Profile->peprofile_get_template_part("tmplt", "foot");
add_action( "wp_footer", array( $PeproDevUPS_Profile, "remove_us_css"), PHP_INT_MAX);
get_footer();
