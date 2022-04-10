<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/15 14:49:01
global $PeproDevUPS_Profile;
$that   = $PeproDevUPS_Profile;
$cpos   = get_option("{$that->activation_status}-customposition");
$showct = get_option("{$that->activation_status}-showcustomtext");

/* welcome */
if ("p1" == $cpos && "true" == $showct){ ?><pdcart><?=wp_kses_post($that->get_promotion_data());?></pdcart><?php }
/* before stats */
if ("p2" == $cpos && "true" == $showct){ ?><pdcart><?=wp_kses_post($that->get_promotion_data());?></pdcart><?php }
/* after stats */
if ("p3" == $cpos && "true" == $showct){ ?><pdcart><?=wp_kses_post($that->get_promotion_data());?></pdcart><?php }
/* after orders */
if ("p4" == $cpos && "true" == $showct){ ?><pdcart><?=wp_kses_post($that->get_promotion_data());?></pdcart><?php }

?>
<div class="pdorder-pdpromos">
  <pdorders>
    <div class="pdorder-title-wrap">
      <span class="pdorder-title">NEW ORDERS STATUS</span>
      <span class="pdorder-title-cta"><a class="button-procceed-arrow" href="#"></a></span>
    </div>
    <div class="pdorder-stats">
      <div class="pdorder-stats-entry">
        <span class="pdorder-stats-entry-text">COMPLETED</span>
        <span class="pdorder-stats-entry-count">1</span>
      </div>
      <div class="pdorder-stats-entry">
        <span class="pdorder-stats-entry-text">PENDING</span>
        <span class="pdorder-stats-entry-count">2</span>
      </div>
      <div class="pdorder-stats-entry">
        <span class="pdorder-stats-entry-text">PROCESSING</span>
        <span class="pdorder-stats-entry-count">3</span>
      </div>
    </div>
  </pdorders>
  <pdpromos>
    <div class="pdpromos-title-wrap">
      <span class="pdorder-title">PROMOS</span>
    </div>
    <div class="pdpromos-subtitle">HERE IS YOUR CUSTOM-MADE PROMOTION CODE TO USE</div>
    <div class="pdpromos-code-ontainer">
      <div class="promo-code-wrapper copy_input">AA37103BJWU03B</div>
      <a href="#" class="copy_input_btn"><svg width="19" height="22" viewBox="0 0 19 22" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="0.5" y="3.5" width="15" height="18" rx="2.5" stroke="black"></rect><path d="M5.49219 1L14.9993 1C16.6562 1 17.9993 2.34315 17.9993 4V15.8979" stroke="black"></path></svg></a>
    </div>
  </pdpromos>
</div>
<div class="pdtickets-pdbalance">
  <pdtickets>
    <div class="pdorder-title-wrap">
      <span class="pdorder-title">LASTEST 5 TICKETS</span>
      <span class="pdorder-title-cta"><a href="#">VIEW ALL</a></span>
    </div>
    <div class="pdtickets-list">
      <table>
        <thead>
          <th>TITLE</th>
          <th>DATE OF TICKET</th>
          <th>STATE OF TICKET</th>
          <th></th>
        </thead>
        <tbody>
          <?php
          for ($i=0; $i < 6; $i++) {
            ?>
            <tr>
              <td>LOW QUALITY PICS</td>
              <td>(19:30) 2019.08.09</td>
              <td><span>OPEN</span></td>
              <td><a class="button-procceed-arrow" href="#"></a></td>
            </tr>
            <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </pdtickets>
  <pdbalace>
    <div class="pdbalace-title-wrap">
      <span class="pdbalace-title">WALLET BALLANE</span>
      <span class="pdbalace-subtitle">
        <span class="pdbalace-amount">300<span class='pdbalance-dlr'>$</span></span>
      </span>
      <div class="pdbalace-note">
        HOW DO RATE YOUR OVERALL SATISFACTION SERVICE PROVIDED
      </div>
      <div class="pdbalace-cta-wrap">
        <a class="pdbalace-cta button-simple-gray" href="#">INCREASE</a>
      </div>
    </div>
  </pdbalace>
</div>
