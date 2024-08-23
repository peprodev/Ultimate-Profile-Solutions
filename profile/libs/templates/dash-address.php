<?php
/*
 * @Author: Amirhossein Hosseinpour <https://amirhp.com>
 * @Date Created: 2023/06/27 02:45:26
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2024/05/21 14:46:29
 */

global $PeproDevUPS_Profile, $PeproDevUPS_Login;
$current_user = wp_get_current_user();
$PeproDevUPS_Profile->change_dashboard_title(_x("Edit Address", "user-dashboard", "peprodev-ups"));
if ($PeproDevUPS_Profile->_wc_activated()) {
  $address = "billing";
  if (isset($_GET["part"]) && "shipping" == $_GET["part"]) $address = "shipping";
  ?>
  <div class="woocommerce">
    <?php 
      wc_print_notices();
      (new \WC_Admin_Profile)->save_customer_meta_fields(get_current_user_id());
      \WC_Shortcode_My_Account::edit_address($address);
    ?>
  </div>
  <?php 
}
?>