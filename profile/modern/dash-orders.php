<?php

# @Last modified by:   amirhp-com
# @Last modified time: 2022/08/04 22:34:34

global $PeproDevUPS_Profile;
$that = $PeproDevUPS_Profile;

add_filter( 'woocommerce_account_orders_columns', 'new_woocommerce_account_orders_columns' );
function new_woocommerce_account_orders_columns( $columns = array() ) {
  $columns = array(
			'order-number'  => __( 'Order', 'woocommerce' ),
			'order-date'    => __( 'Date', 'woocommerce' ),
      'order-total'   => __( 'Total', 'woocommerce' ),
			'order-status'  => __( 'Status', 'woocommerce' ),
			'order-actions' => __( 'View Order', 'woocommerce' ),
		);
  return $columns;
}
add_filter( 'wc_order_statuses', 'new_wc_order_statuses' );
function new_wc_order_statuses($order_statuses)
{
  $order_statuses['wc-pending'] = _x('Pending', $that->td);
  return $order_statuses;
}

?>
<div class="container-fluid">
  <?php
    $PeproDevUPS_Profile->peprofile_get_template_part("wc/orders");
  ?>
</div>
