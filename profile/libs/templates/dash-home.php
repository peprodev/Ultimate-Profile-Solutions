<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/15 14:49:01

global $PeproDevUPS_Profile, $rtl, $wp;
$PeproDevUPS_Profile->change_dashboard_title();
?>
<div class="container-fluid">
    <?php

    do_action( "peprofile_homedashboard__before_start" );

    if ("p1" == get_option("{$PeproDevUPS_Profile->activation_status}-customposition") && "true" == get_option("{$PeproDevUPS_Profile->activation_status}-showcustomtext")){
      echo wp_kses_post( $PeproDevUPS_Profile->get_promotion_data() );
      do_action( "peprofile_homedashboard__after_customtext" );
    }

    do_action( "peprofile_homedashboard__before_welcome" );

    if ("true" == get_option("{$PeproDevUPS_Profile->activation_status}-showwelcome")){
       $allowed_html = array( 'a' => array( 'href' => array(), ), "div");
        echo "<div class=\"row\"><div class=\"col-lg-12 default-dash\"><div class=\"au-card recent-report\" style=''><div class=\"au-card-inner\">";
        printf(/* translators: 1: user display name 2: logout url */
          wp_kses( __( 'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', "peprodev-ups" ), $allowed_html ),
          '<strong>' . esc_html( wp_get_current_user()->display_name ) . '</strong>',
          esc_url( wp_logout_url() )
        );
        echo "<br>";
        /* translators: 1: Orders URL 2: Address URL 3: Account URL. */
        $dashboard_desc = __( 'From your account dashboard you can <a href="%1$s">view new notifications</a>, and <a href="%3$s">edit your password and account details</a>.', "peprodev-ups" );
        printf(
          "<div class='welcome-text'>$dashboard_desc</div>",
          add_query_arg( "section", "notifications", home_url($wp->request)),
          add_query_arg( "section", "announcements", home_url($wp->request)),
          add_query_arg( "section", "edit", home_url($wp->request))
        );
        echo "</div></div></div></div>";
    }
    do_action( "peprofile_homedashboard__after_welcome" );

    if ("p2" == get_option("{$PeproDevUPS_Profile->activation_status}-customposition") && "true" == get_option("{$PeproDevUPS_Profile->activation_status}-showcustomtext")){
      echo $PeproDevUPS_Profile->get_promotion_data();
      do_action( "peprofile_homedashboard__after_customtext" );
    }

    if ($PeproDevUPS_Profile->_wc_activated() && "true" == get_option("{$PeproDevUPS_Profile->activation_status}-woocommercestats")){
      ?>
      <div class="row"><div class="col-md-12"><div class="overview-wrap"><h2 class="title-1" ><?php echo esc_html_x("Overview","user-dashboard","peprodev-ups");?></h2></div></div></div>
      <?php
      echo $PeproDevUPS_Profile->peprofile_shortcode_wc_stats();
      do_action( "peprofile_homedashboard__after_woocommercestats" );
    }

    if ("p3" == get_option("{$PeproDevUPS_Profile->activation_status}-customposition") && "true" == get_option("{$PeproDevUPS_Profile->activation_status}-showcustomtext")){
      echo $PeproDevUPS_Profile->get_promotion_data();
      do_action( "peprofile_homedashboard__after_customtext" );
    }


    if ($PeproDevUPS_Profile->_wc_activated() && "true" == get_option("{$PeproDevUPS_Profile->activation_status}-woocommerceorders")){
      ?>
      <div class="row"><div class="col-lg-12"><h2 class="title-1 m-b-25"><?php echo esc_html_x("Latest 10 purchases","user-dashboard","peprodev-ups");?></h2>
        <?php echo $PeproDevUPS_Profile->peprofile_shortcode_wc_orders(); ?>
      </div></div>
      <?php
      do_action( "peprofile_homedashboard__after_woocommerceorders" );
    }



    if ("p4" == get_option("{$PeproDevUPS_Profile->activation_status}-customposition") && "true" == get_option("{$PeproDevUPS_Profile->activation_status}-showcustomtext")){
      echo $PeproDevUPS_Profile->get_promotion_data();
      do_action( "peprofile_homedashboard__after_customtext" );
    }

    do_action( "peprofile_homedashboard__before_end" );

    ?>
</div>
