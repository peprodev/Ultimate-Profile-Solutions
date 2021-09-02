<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/01 22:40:07

global $PeproDevUPS_Profile,$wp;
$current_user = wp_get_current_user();
$avatar_url = get_avatar_url( get_current_user_id(), array("size"=> "96",));

?>


<div class="account-item clearfix js-item-menu">
    <div class="image">
        <img src="<?php echo $avatar_url;?>" alt="<?php echo esc_html( $current_user->display_name );?>" />
    </div>
    <div class="content">
        <a class="js-acc-btn" href="<?php echo home_url( $wp->request ) . "?section=me";?>"><?php echo esc_html( $current_user->display_name );?></a>
    </div>
    <div class="account-dropdown js-dropdown">
        <div class="info clearfix">
            <div class="image">
                <a href="<?php echo home_url( $wp->request ) . "?section=me";?>">
                    <img src="<?php echo $avatar_url;?>" alt="<?php echo esc_html( $current_user->display_name );?>" />
                </a>
            </div>
            <div class="content">
                <h5 class="name">
                    <a href="<?php echo home_url( $wp->request ) . "?section=me";?>"><?php echo esc_html( $current_user->display_name );?></a>
                </h5>
                <span class="email"><?php echo esc_html( !empty($current_user->user_email) ? $current_user->user_email : (!empty($current_user->user_login) ? $current_user->user_login : $current_user->display_name) );?></span>
            </div>
        </div>
        <div class="account-dropdown__body">
            <div class="account-dropdown__item"> <a href="<?php echo home_url();?>"><i class="zmdi zmdi-home"></i><?php echo __("Home",$PeproDevUPS_Profile->td);?></a> </div>
            <?php if ($PeproDevUPS_Profile->_wc_activated()){ ?>
            <div class="account-dropdown__item"> <a href="<?php echo wc_get_page_permalink('shop');?>"><i class="zmdi zmdi-store"></i><?php echo __("Shop",$PeproDevUPS_Profile->td);?></a> </div>
            <?php } ?>
            <div class="account-dropdown__item"> <a href="<?php echo home_url( $wp->request ) . "?section=me";?>"><i class="zmdi zmdi-account"></i><?php echo __("Account",$PeproDevUPS_Profile->td);?></a> </div>
            <div class="account-dropdown__item"> <a href="<?php echo home_url( $wp->request ) . "?section=edit";?>"><i class="zmdi zmdi-edit"></i><?php echo __("Edit Details",$PeproDevUPS_Profile->td);?></a> </div>
            <!-- <div class="account-dropdown__item"> <a href="<?php echo home_url( $wp->request ) . "?section=password";?>"><i class="zmdi zmdi-shield-security"></i><?php echo __("Change password",$PeproDevUPS_Profile->td);?></a> </div> -->
        </div>
        <div class="account-dropdown__footer">
          <?php if (current_user_can("administrator")){ ?>
            <div class="account-dropdown__item"> <a href="<?php echo admin_url();?>"><i class="fab fa-wordpress"></i><?php echo __("WP Dashboard",$PeproDevUPS_Profile->td);?></a> </div>
          <?php } ?>
            <a href="<?php echo wp_logout_url(home_url());?>"><i class="zmdi zmdi-power"></i><?php echo __("Logout",$PeproDevUPS_Profile->td);?></a>
        </div>
    </div>
</div>
