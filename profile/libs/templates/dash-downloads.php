<?php
# @Author: Amirhosseinhpv
# @Date:   2021/08/28 00:07:32
# @Email:  its@hpv.im
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/15 14:47:50
# @License: GPLv2
# @Copyright: Copyright © Amirhosseinhpv (https://hpv.im), all rights reserved.


global $PeproDevUPS_Profile;
$PeproDevUPS_Profile->change_dashboard_title(_x("Downloads","user-dashboard","peprodev-ups"));
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?php echo esc_html_x("Downloads","user-dashboard","peprodev-ups");?></h2>
        </div>
    </div>
  </div>
  <div class="row m-t-25">
    <?php
    $downloads     = WC()->customer->get_downloadable_products();
    $has_downloads = (bool) $downloads;

    do_action( 'woocommerce_before_account_downloads', $has_downloads ); ?>

    <?php if ( $has_downloads ) : ?>

    	<?php do_action( 'woocommerce_before_available_downloads' ); ?>

    	<?php
      // do_action( 'woocommerce_available_downloads', $downloads );
      $PeproDevUPS_Profile->peprofile_get_template_part("wc/order-downloads");
      ?>

    	<?php do_action( 'woocommerce_after_available_downloads' ); ?>

    <?php else : ?>
      <div class="col-12">
          <div class="card">
              <div class="card-body">
                <div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
                  <?php esc_html_e( 'No downloads available yet.', 'woocommerce' ); ?>
                  <a class="woocommerce-Button button btn btn-outline-primary <?php echo is_rtl() ? "float-right" : "float-left";?>" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
                    <?php esc_html_e( 'Browse products', 'woocommerce' ); ?>
                  </a>
                </div>
              </div>
          </div>
      </div>
    <?php endif; ?>

    <?php do_action( 'woocommerce_after_account_downloads', $has_downloads ); ?>
  </div>
</div>
