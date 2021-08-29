<!DOCTYPE html>
<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/08/29 00:00:17

global $PeproDevUPS_Profile, $rtl, $wp, $PeproDevUPS_ProfileStripslashesNotifsJs;

$current_section = isset($_GET["section"]) ? trim($_GET["section"]) : "";
$loadingCLASS = "";
// $loadingCLASS = "lds-circle";

$loadingCLASSrainbow = "color-ring";
$loadingCLASSrainbow = "color-ring-gray";

$classes = apply_filters("peprofile_body_class", "$loadingCLASS section-$current_section ");

$rtl = is_rtl() ? "-rtl" : "";

if (isset($_GET["dir"])) { $rtl = $_GET["dir"] === "rtl" ? "-rtl" : ""; }


?>
<html lang="en" dir="<?php echo is_rtl() ? "rtl" : "ltr";?>">
  <head>
    <title><?=$title;?></title>
    <base href="<?=home_url($wp->request);?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $PeproDevUPS_Profile->peprofile_get_template_part("tmplt", "head"); ?>
    <style media="screen">
      /* Global CSS @ Pepro Profile // https://pepro.dev/ */
      <?=stripslashes(get_option("{$PeproDevUPS_Profile->activation_status}-css","")); ?>
    </style>
    <style media="screen">
      .wc-order-pagination {display: none;}
      body.<?=$loadingCLASS;?> > .page-wrapper{
        -webkit-animation: <?=$loadingCLASSrainbow;?> 1.2s linear infinite;
        animation: <?=$loadingCLASSrainbow;?> 1.2s linear infinite;
      }
      body.<?=$loadingCLASS;?> .page-container::after{
        content: "";
        display: block;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 9999999999;
        cursor: wait;
        -webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;
      }
      body.lds-dual-ring::after {
        content: " ";
        display: block;
        width: 64px;
        height: 64px;
        margin: 8px;
        border-radius: 50%;
        border: 6px solid var(--primary);
        border-color: var(--primary) transparent var(--primary) transparent;
        -webkit-animation: lds-dual-ring 1.2s linear infinite;
        animation: lds-dual-ring 1.2s linear infinite;
        position: fixed;
        left: calc(50% - 32px);
        top: calc(50% - 32px);
        cursor: wait;
        z-index: 999999999999 !important;
        -webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;
      }
      body.lds-circle::after {
        content: " ";
        display: block;
        width: 64px;
        height: 64px;
        margin: 8px;
        border-radius: 50%;
        background: var(--primary);
        -webkit-animation: lds-circle 2.4s cubic-bezier(0, 0.2, 0.8, 1) infinite;
        animation: lds-circle 2.4s cubic-bezier(0, 0.2, 0.8, 1) infinite;
        position: fixed;
        left: calc(50% - 32px);
        top: calc(50% - 32px);
        cursor: wait;
        z-index: 999999999999 !important;
        -webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;
      }
      @-webkit-keyframes lds-dual-ring {
        0% {
          transform: rotate(0deg);
        }
        100% {
          transform: rotate(360deg);
        }
      }
      @keyframes lds-dual-ring {
        0% {
          transform: rotate(0deg);
        }
        100% {
          transform: rotate(360deg);
        }
      }
      @-webkit-keyframes color-ring {
        0% {
          -webkit-filter: blur(5px) hue-rotate(10deg);
          filter: blur(5px) hue-rotate(10deg);
        }
        100% {
          -webkit-filter: blur(5px) hue-rotate(360deg);
          filter: blur(5px) hue-rotate(360deg);
        }
      }
      @keyframes color-ring {
        0% {
          -webkit-filter: blur(5px) hue-rotate(10deg);
          filter: blur(5px) hue-rotate(10deg);
        }
        100% {
          -webkit-filter: blur(5px) hue-rotate(360deg);
          filter: blur(5px) hue-rotate(360deg);
        }
      }
      @-webkit-keyframes color-ring-gray {
        0% {
          -webkit-filter: blur(2px) grayscale(0);
          filter: blur(2px) grayscale(0);
        }
        50% {
          -webkit-filter: blur(2px) grayscale(1);
          filter: blur(2px) grayscale(1);
        }
        100% {
          -webkit-filter: blur(2px) grayscale(0);
          filter: blur(2px) grayscale(0);
        }
      }
      @keyframes color-ring-gray {
        0% {
          -webkit-filter: blur(2px) grayscale(0);
          filter: blur(2px) grayscale(0);
        }
        50% {
          -webkit-filter: blur(2px) grayscale(1);
          filter: blur(2px) grayscale(1);
        }
        100% {
          -webkit-filter: blur(2px) grayscale(0);
          filter: blur(2px) grayscale(0);
        }
      }
      @-webkit-keyframes color-ring-extra {
        0% {
          filter: blur(7px) saturate(400);
        }
        50% {
          filter: blur(6px) saturate(1000);
        }
        100% {
          filter: blur(7px) saturate(400);
        }
      }
      @keyframes color-ring-extra {
        0% {
          filter: blur(7px) saturate(400);
        }
        50% {
          filter: blur(6px) saturate(1000);
        }
        100% {
          filter: blur(7px) saturate(400);
        }
      }
      @-webkit-keyframes lds-circle {
        0%, 100% {
          animation-timing-function: cubic-bezier(0.5, 0, 1, 0.5);
        }
        0% {
          transform: rotateY(0deg);
        }
        50% {
          transform: rotateY(1800deg);
          animation-timing-function: cubic-bezier(0, 0.5, 0.5, 1);
        }
        100% {
          transform: rotateY(3600deg);
        }
      }
      @keyframes lds-circle {
        0%, 100% {
          animation-timing-function: cubic-bezier(0.5, 0, 1, 0.5);
        }
        0% {
          transform: rotateY(0deg);
        }
        50% {
          transform: rotateY(1800deg);
          animation-timing-function: cubic-bezier(0, 0.5, 0.5, 1);
        }
        100% {
          transform: rotateY(3600deg);
        }
      }
      .skeleton {
        background-image:	linear-gradient( 100deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.5) 50%, rgba(255, 255, 255, 0) 100% ),
        linear-gradient( lightgray 100%, transparent 0 );
        background-repeat: repeat-y;
        background-size:	70px 1px, 100% 100%;
        animation: shine 1s linear infinite;
        color: transparent !important;
      }
      .skeleton *{
        color: transparent !important;
        opacity: 0
      }
      tr.skeleton + tr{
        background: snow !important;
        color: transparent !important;
      }
      tr.skeleton + tr *{
        color: transparent !important;
        opacity: 0;
      }
      @-webkit-keyframes shine {
        0% {
          background-position: -20% 0, 0 0;
        }
        100% {
          background-position: 200% 0, 0 0;
        }
      }
      @keyframes shine {
        0% {
          background-position: -20% 0, 0 0;
        }
        100% {
          background-position: 200% 0, 0 0;
        }
      }
    </style>
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
          /* Global JS @ Pepro Profile // https://pepro.dev/ */
          <?=stripslashes(get_option("{$PeproDevUPS_Profile->activation_status}-js",""));?>
          <?=stripslashes($PeproDevUPS_ProfileStripslashesNotifsJs);?>
        })(jQuery);
      </script>
    <?php
    add_action( "wp_footer", array( $PeproDevUPS_Profile, "remove_us_css"), PHP_INT_MAX);
    get_footer();
