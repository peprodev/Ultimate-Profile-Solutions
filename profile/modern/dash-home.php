<?php
# @Last modified by:   amirhp-com <its@amirhp.com>
# @Last modified time: 2022/08/25 00:42:53
global $PeproDevUPS_Profile, $pro_ticketing;
$that   = $PeproDevUPS_Profile;
$cpos   = get_option("{$that->activation_status}-customposition");
$showct = get_option("{$that->activation_status}-showcustomtext");
$user_id = get_current_user_id();

/* welcome */
if ("p1" == $cpos && "true" == $showct){ ?><pdcart><?=wp_kses_post($that->get_promotion_data());?></pdcart><?php }
/* before stats */
if ("p2" == $cpos && "true" == $showct){ ?><pdcart><?=wp_kses_post($that->get_promotion_data());?></pdcart><?php }
/* after stats */
if ("p3" == $cpos && "true" == $showct){ ?><pdcart><?=wp_kses_post($that->get_promotion_data());?></pdcart><?php }
/* after orders */
if ("p4" == $cpos && "true" == $showct){ ?><pdcart><?=wp_kses_post($that->get_promotion_data());?></pdcart><?php }


$orders = wc_get_orders(array(
    'status'      => array('wc-processing', 'wc-completed', 'wc-pending'),
    'customer_id' => $user_id,
    'limit'       => -1,
));
$orders            = wp_list_pluck( $orders, "status");
$orders            = array_count_values($orders);
$orders_completed  = (int) (isset($orders["completed"]) ? $orders["completed"] : 0);
$orders_pending    = (int) (isset($orders["pending"]) ? $orders["pending"] : 0);
$orders_processing = (int) (isset($orders["processing"]) ? $orders["processing"] : 0);
$current_balance   = function_exists("woo_wallet") ? woo_wallet()->wallet->get_wallet_balance($user_id, 'edit') : 0;
?>
<div class="pdorder-pdpromos">
  <pdorders>
    <div class="pdorder-title-wrap">
      <span class="pdorder-title"><?=__("NEW ORDERS STATUS", $that->td);?></span>
      <span class="pdorder-title-cta"><a class="button-procceed-arrow" href="<?= $that->get_profile_page(["section"=>"orders"]); ?>"></a></span>
    </div>
    <div class="pdorder-stats">
      <div class="pdorder-stats-entry">
        <span class="pdorder-stats-entry-text"><?=__("COMPLETED", $that->td);?></span>
        <span class="pdorder-stats-entry-count"><?=$orders_completed;?></span>
      </div>
      <div class="pdorder-stats-entry">
        <span class="pdorder-stats-entry-text"><?=__("PENDING", $that->td);?></span>
        <span class="pdorder-stats-entry-count"><?=$orders_pending;?></span>
      </div>
      <div class="pdorder-stats-entry">
        <span class="pdorder-stats-entry-text"><?=__("PROCESSING", $that->td);?></span>
        <span class="pdorder-stats-entry-count"><?=$orders_processing;?></span>
      </div>
    </div>
  </pdorders>
  <pdpromos>
    <div class="pdpromos-title-wrap">
      <span class="pdorder-title"><?=__("PROMOS", $that->td);?></span>
    </div>
    <div class="pdpromos-subtitle"><?=__("HERE IS YOUR CUSTOM-MADE PROMOTION CODE TO USE", $that->td);?></div>
    <div class="pdpromos-code-ontainer">
      <?php
      $promotion = get_user_meta($user_id, 'ups_promotion_code', true);
      if (empty($promotion)) $promotion = "&nbsp;";
      ?>
      <div class="promo-code-wrapper copy_input"><?=$promotion;?></div>
      <a href="#" class="copy_input_btn"><svg width="19" height="22" viewBox="0 0 19 22" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="0.5" y="3.5" width="15" height="18" rx="2.5" stroke="black"></rect><path d="M5.49219 1L14.9993 1C16.6562 1 17.9993 2.34315 17.9993 4V15.8979" stroke="black"></path></svg></a>
    </div>
  </pdpromos>
</div>
<div class="pdtickets-pdbalance">
  <pdtickets>
    <div class="pdorder-title-wrap">
      <span class="pdorder-title"><?=__("LASTEST 5 TICKETS", $that->td);?></span>
      <span class="pdorder-title-cta"><a href="<?=$that->get_profile_page(["section"=>"tickets"])?>"><?=__("VIEW ALL", $that->td);?></a></span>
    </div>
    <div class="pdtickets-list">
      <table>
        <thead>
          <th class="col_title"><?=__("TITLE", $that->td);?></th>
          <th class="col_date"><?=__("DATE OF TICKET", $that->td);?></th>
          <th class="col_state"><?=__("STATE OF TICKET", $that->td);?></th>
          <th class="col_action"></thclass>
        </thead>
        <tbody>
          <?php
          $qmta = array(
            'posts_per_page' => 5,
            'post_status'    => array('publish'),
            'order'          => 'DESC',
            'orderby'        => 'date',
            'post_type'      => "peproticket",
            'author'         => get_current_user_id(),
            'nopaging'       => true,
          );
          if (current_user_can( "administrator")) { unset($qmta["author"]); }
          $query = query_posts($qmta);
          if ($query){
            foreach ($query as $key => $peproticket) {
              $edit_url = $that->get_profile_page(["section"=>"tickets","manage"=>"add","cpid"=>$peproticket->ID]);
              $status = get_post_meta($peproticket->ID, "ticket_status", true);
              ?>
              <tr>
                <td class="col_title"><?=strtoupper(get_the_title($peproticket->ID));?></td>
                <td class="col_date"><?=date_i18n("Y.m.d", strtotime($peproticket->post_date));?> <span class='ticket_time'><?=date_i18n("(H:i)", strtotime($peproticket->post_date));?></span></td>
                <td class="col_state"><span class="ticket-status <?=$status;?>"><?=$pro_ticketing->get_ticket_status($peproticket->ID);?></span></td>
                <td class="col_action"><a class="button-procceed-arrow" href="<?=$edit_url;?>"></a></td>
              </tr>
              <?php
            }
          }
          wp_reset_postdata();
          ?>
        </tbody>
      </table>
    </div>
  </pdtickets>
  <pdbalace>
    <div class="pdbalace-title-wrap">
      <span class="pdbalace-title"><?=__("WALLET BALLANE", $that->td);?></span>
      <span class="pdbalace-subtitle">
        <span class="pdbalace-amount"><?=$current_balance;?><span class='pdbalance-dlr'><?=function_exists("get_woocommerce_currency_symbol") ? get_woocommerce_currency_symbol() : "$";?></span></span>
      </span>
      <div class="pdbalace-note">
        HOW DO RATE YOUR OVERALL SATISFACTION SERVICE PROVIDED
      </div>
      <div class="pdbalace-cta-wrap">
        <a class="pdbalace-cta button-simple-gray" href="<?= $that->get_profile_page(["section"=>"wallet"]); ?>">INCREASE</a>
      </div>
    </div>
  </pdbalace>
</div>
