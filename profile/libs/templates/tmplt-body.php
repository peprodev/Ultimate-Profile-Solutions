<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/02 16:24:47

global $PeproDevUPS_Profile, $wp;
if (class_exists("WPSEO_Options")){
  $front_end = YoastSEO()->classes->get( Yoast\WP\SEO\Integrations\Front_End_Integration::class );
  remove_action( 'wpseo_head', [ $front_end, 'present_head' ], -9999 );
}
?>
<div class="page-wrapper">
  <!-- HEADER MOBILE-->
  <header class="header-mobile d-block d-lg-none">
      <div class="header-mobile__bar">
          <div class="container-fluid">
              <div class="header-mobile-inner">
                  <a class="logo" href="<?php echo home_url();?>" style="height: 100%;">
                      <img style="max-height: 100%;" src="<?php echo !empty(get_option("{$PeproDevUPS_Profile->activation_status}-logo")) ? esc_url( get_option("{$PeproDevUPS_Profile->activation_status}-logo")) : $PeproDevUPS_Profile->icon ;?>" alt="<?php echo get_bloginfo("name");?>" />
                  </a>
                  <button class="hamburger hamburger--slider" type="button">
                      <span class="hamburger-box">
                          <span class="hamburger-inner"></span>
                      </span>
                  </button>
              </div>
          </div>
      </div>
      <nav class="navbar-mobile">
          <div class="container-fluid">
              <ul class="navbar-mobile__list list-unstyled">
                <?php $PeproDevUPS_Profile->peprofile_get_template_part("nav","bar"); ?>
              </ul>
          </div>
      </nav>
  </header>
  <!-- END HEADER MOBILE-->

  <!-- MENU SIDEBAR-->
  <aside class="menu-sidebar d-none d-lg-block">
      <div class="logo">
          <a href="<?php echo home_url();?>" style="position: relative;">
              <img style="margin: auto;" src="<?php echo !empty(get_option("{$PeproDevUPS_Profile->activation_status}-logo")) ? esc_url( get_option("{$PeproDevUPS_Profile->activation_status}-logo")) : $PeproDevUPS_Profile->icon;?>" alt="<?php echo get_bloginfo("name");?>" />
          </a>
      </div>
      <div class="menu-sidebar__content js-scrollbar1">
        <nav class="navbar-sidebar">
            <ul class="list-unstyled navbar__list">
              <?php
                $PeproDevUPS_Profile->peprofile_get_template_part("nav","bar");
              ?>
            </ul>
        </nav>
      </div>
  </aside>
  <!-- END MENU SIDEBAR-->

  <!-- PAGE CONTAINER-->
  <div class="page-container">
      <!-- HEADER DESKTOP-->
      <header class="header-desktop">
          <div class="section__content section__content--p30">
              <div class="container-fluid">
                  <div class="header-wrap">
                      <form class="form-header" action="" method="POST"></form>
                      <div class="header-button">
                          <div class="noti-wrap">
                            <?php
                              $PeproDevUPS_Profile->peprofile_get_template_part("tmplt","top-notif");
                            ?>
                          </div>
                          <div class="account-wrap">
                            <?php $PeproDevUPS_Profile->peprofile_get_template_part("tmplt","topbar"); ?>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </header>
      <!-- HEADER DESKTOP-->

      <!-- MAIN CONTENT-->
      <div class="main-content">
          <div class="section__content section__content--p30">
            <?php
              $allowed_slugs_whitelist = array_unique(apply_filters( "peprofile_dashboard_slugs", array("edit","me")));

              $current_requested_slug = isset($_GET['section']) ? sanitize_text_field(trim($_GET['section'])) : "";

              if (empty($current_requested_slug) || !in_array($current_requested_slug, $allowed_slugs_whitelist)){
                $PeproDevUPS_Profile->peprofile_get_template_part("dash","home");
              }else{
                if (has_action("peprofile_dashboard_content_{$current_requested_slug}_force")){
                  do_action("peprofile_dashboard_content_{$current_requested_slug}_force");
                }else{

                  if ( "orders" == $current_requested_slug && isset($_GET['view']) && !empty(trim($_GET['view']))){
                    $located_requested_slug_template = $PeproDevUPS_Profile->peprofile_get_template_part("dash", "orders-view");
                  }else{
                    $located_requested_slug_template = $PeproDevUPS_Profile->peprofile_get_template_part("dash", $current_requested_slug);
                  }

                  if (!$located_requested_slug_template){
                    if (has_action("peprofile_dashboard_content_{$current_requested_slug}")){
                        do_action("peprofile_dashboard_content_{$current_requested_slug}");
                    }else{
                      $PeproDevUPS_Profile->peprofile_get_template_part("dash","home");
                    }
                  }
                }
              }


            ?>
          </div>
      </div>
      <!-- END MAIN CONTENT-->
  </div>
  <!-- END PAGE CONTAINER-->
</div>
