<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/15 14:50:35


global $PeproDevUPS_Profile;
$PeproDevUPS_Profile->change_dashboard_title(_x("Wallet", "user-dashboard", "peprodev-ups"));
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="overview-wrap">
        <h2 class="title-1"><?php echo esc_html_x("Wallet", "user-dashboard", "peprodev-ups");?></h2>
      </div>
    </div>
  </div>

  <?php
    do_action('wp_head');
    wp_enqueue_style('woo-wallet-payment-jquery-ui');
    wp_enqueue_style('dashicons');
    wp_enqueue_style('select2');
    wp_enqueue_style('jquery-datatables-style');
    wp_enqueue_style('jquery-datatables-responsive-style');
    wp_enqueue_script('jquery-datatables-script');
    wp_enqueue_script('jquery-datatables-responsive-script');
    wp_enqueue_script('selectWoo');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('wc-endpoint-wallet');
    $PeproDevUPS_Profile->dashboard_add_css_inline(".woo-wallet-content-heading, hr, .woo-wallet-sidebar { display: none; }
    .woo-wallet-my-wallet-container .woo-wallet-content { width: 100%; }
    .woo-wallet-add-amount > * { display: inline-block !important; float: unset; margin: 0; }
    .woo-wallet-add-credit{padding: 0 1rem 1.3rem 0;border-bottom: 1px solid #8080801a;}
    #woo_wallet_balance_to_add { border: 1px solid rgba(0,0,0,0.2); border-radius: 4px; padding: 0.3rem; width: 220px; margin: 0 1rem; }
    .woo-wallet-my-wallet-container {border: none;}");

    add_filter(
        'woo_wallet_nav_menu_items', function () {
            return array();
        }
    );
    add_filter(
        'woo_wallet_transactions_count', function () {
            return 1000;
        }
    );


    ob_start();
    if (apply_filters('woo_wallet_is_enable_top_up', true)) {
        ?>
          <form method="post" action="">
            <div class="woo-wallet-add-amount">
              <label for="woo_wallet_balance_to_add"><?php _e('Enter amount', 'woo-wallet'); ?></label>
              <?php
              $min_amount = woo_wallet()->settings_api->get_option('min_topup_amount', '_wallet_settings_general', 0);
              $max_amount = woo_wallet()->settings_api->get_option('max_topup_amount', '_wallet_settings_general', '');
              ?>
              <input type="number" step="0.01" min="<?php echo $min_amount; ?>" max="<?php echo $max_amount; ?>" name="woo_wallet_balance_to_add" id="woo_wallet_balance_to_add" class="text woo-wallet-balance-to-add" required="" />
              <?php wp_nonce_field('woo_wallet_topup', 'woo_wallet_topup'); ?>
              <input style="width:150px" type="submit" name="woo_add_to_wallet" class="woo-add-to-wallet btn btn-primary" value="<?php _e('Add', 'woo-wallet'); ?>" />
            </div>
          </form>
      <?php
    }
    $tconaqw = ob_get_contents();
    ob_end_clean();

    ob_start();
    echo apply_filters( "woo_wallet_is_enable_buy_credit", "<div class='woo-wallet-add-credit'>$tconaqw</div>" , $tconaqw);
    woo_wallet()->get_template('wc-endpoint-wallet.php');
    $tcona = ob_get_contents();
    ob_end_clean();


    $url1 = esc_url(add_query_arg('wallet_action', 'add'));
    $url2 = esc_url(add_query_arg('wallet_action', 'view_transactions'));

    echo do_shortcode("[profile-card-3 title='".__('Balance', 'woo-wallet').": ". wp_kses(woo_wallet()->wallet->get_wallet_balance(get_current_user_id()), array()) ."' icon='fa fa-wallet']".$tcona."[/profile-card-3]");
