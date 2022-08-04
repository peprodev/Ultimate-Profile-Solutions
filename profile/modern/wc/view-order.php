<?php
/**
* View Order
*
* Shows the details of a particular order on the account page.
*
* This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
*
* HOWEVER, on occasion WooCommerce will need to update template files and you
* (the theme developer) will need to copy the new files to your theme to
* maintain compatibility. We try to do that as little as possible, but it does
* happen. When that occurs the version of the template file will be bumped and
* the readme will list any important changes.
*
* @see     https://docs.woocommerce.com/document/template-structure/
* @package WooCommerce\Templates
* @version 3.0.0
# @Author: amirhp-com
# @Date:   2022/03/10 10:13:12
# @Email:  its@amirhp.com
# @Last modified by:   amirhp-com
# @Last modified time: 2022/08/05 00:50:19
# @License: GPLv2
 */

 defined( 'ABSPATH' ) || exit;
 global $current_page, $PeproDevUPS_Profile;
 $that = $PeproDevUPS_Profile;
$order_id = (int) sanitize_text_field( trim($_GET['view']) );
$order = wc_get_order( $order_id );
$notes = $order->get_customer_order_notes();
?>

<div class="view-order-container">
	<div class="voc-head">
		<div class="voc-head-left">
			<a href="<?=$that->get_profile_page(["section"=>"orders"]);?>" class="button-procceed-arrow gobackbtn"></a>
			<div class="voc-order-resnum"><?=esc_html( sprintf("%06d",$order->get_order_number()) );?></div>
		</div>
		<div class="voc-head-right">
			<a class="give-feedback" href="#"><?=__("Feedback", $that->td);?></a>
			<a class="print-order" href="#"><svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
			<rect width="34" height="34" rx="17" fill="#5946B0" fill-opacity="0.1"/>
			<rect opacity="0.01" x="5" y="5" width="24" height="24" fill="white"/>
			<path d="M22.8887 15.2C22.4745 15.2 22.1387 14.8642 22.1387 14.45V8.2H11.6387V14.45C11.6387 14.8642 11.3029 15.2 10.8887 15.2C10.4745 15.2 10.1387 14.8642 10.1387 14.45V7.45C10.1387 7.03578 10.4745 6.7 10.8887 6.7H22.8887C23.3029 6.7 23.6387 7.03578 23.6387 7.45V14.45C23.6387 14.8642 23.3029 15.2 22.8887 15.2Z" fill="#5946B0" stroke="white" stroke-width="0.5"/>
			<path d="M24.8887 24.2H22.8887C22.4745 24.2 22.1387 23.8642 22.1387 23.45C22.1387 23.0358 22.4745 22.7 22.8887 22.7H24.8887C25.579 22.7 26.1387 22.1404 26.1387 21.45V16.45C26.1381 15.7599 25.5788 15.2005 24.8887 15.2H8.88867C8.19877 15.2011 7.63977 15.7601 7.63867 16.45V21.45C7.63922 22.1401 8.19854 22.6994 8.88867 22.7H10.8887C11.3029 22.7 11.6387 23.0358 11.6387 23.45C11.6387 23.8642 11.3029 24.2 10.8887 24.2H8.88867C7.37057 24.1983 6.14033 22.9681 6.13867 21.45V16.45C6.14087 14.9321 7.3708 13.7022 8.88867 13.7H24.8887C26.4068 13.7017 27.637 14.9319 27.6387 16.45V21.45C27.6376 22.9683 26.407 24.1989 24.8887 24.2Z" fill="#5946B0" stroke="white" stroke-width="0.5"/>
			<path fill-rule="evenodd" clip-rule="evenodd" d="M10.8887 28.2H22.8887C23.3029 28.2 23.6387 27.8642 23.6387 27.45V19.45C23.6387 19.0358 23.3029 18.7 22.8887 18.7H10.8887C10.4745 18.7 10.1387 19.0358 10.1387 19.45V27.45C10.1387 27.8642 10.4745 28.2 10.8887 28.2ZM22.1387 26.7H11.6387V20.2H22.1387V26.7Z" fill="#5946B0"/>
			<path d="M11.6387 26.7H11.3887V26.95H11.6387V26.7ZM22.1387 26.7V26.95H22.3887V26.7H22.1387ZM11.6387 20.2V19.95H11.3887V20.2H11.6387ZM22.1387 20.2H22.3887V19.95H22.1387V20.2ZM22.8887 27.95H10.8887V28.45H22.8887V27.95ZM23.3887 27.45C23.3887 27.7261 23.1648 27.95 22.8887 27.95V28.45C23.441 28.45 23.8887 28.0023 23.8887 27.45H23.3887ZM23.3887 19.45V27.45H23.8887V19.45H23.3887ZM22.8887 18.95C23.1648 18.95 23.3887 19.1739 23.3887 19.45H23.8887C23.8887 18.8977 23.441 18.45 22.8887 18.45V18.95ZM10.8887 18.95H22.8887V18.45H10.8887V18.95ZM10.3887 19.45C10.3887 19.1739 10.6125 18.95 10.8887 18.95V18.45C10.3364 18.45 9.88867 18.8977 9.88867 19.45H10.3887ZM10.3887 27.45V19.45H9.88867V27.45H10.3887ZM10.8887 27.95C10.6125 27.95 10.3887 27.7261 10.3887 27.45H9.88867C9.88867 28.0023 10.3364 28.45 10.8887 28.45V27.95ZM11.6387 26.95H22.1387V26.45H11.6387V26.95ZM11.3887 20.2V26.7H11.8887V20.2H11.3887ZM22.1387 19.95H11.6387V20.45H22.1387V19.95ZM22.3887 26.7V20.2H21.8887V26.7H22.3887Z" fill="white"/>
			</svg></a>
			<a class="go-to-ticket us-btn-style_3" href="#"><?=__("Go to Ticket", $that->td);?></a>
		</div>
	</div>
	<div class="voc-head-divider"></div>
	<div class="voc-subhead">
		<div class="status">
			<span class='voct'><?=__("Status", $that->td);?></span>
			<span class='vocv wc-<?php echo esc_attr( $order->get_status() ); ?>'><?=esc_html( wc_get_order_status_name( $order->get_status() ) );?></span>
		</div>
		<div class="date">
			<span class='voct'><?=__("Date of Order", $that->td);?></span>
			<span class='vocv'><?=esc_html( wc_format_datetime( $order->get_date_created() ) );?></span>
		</div>
		<div class="total">
			<span class='voct'><?=__("Total Amount", $that->td);?></span>
			<span class='vocv'><?=$order->get_formatted_order_total(true);?></span>
		</div>
	</div>
</div>
<?php

$order = wc_get_order( $order_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

if ( ! $order ) { return; }

$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads             = $order->get_downloadable_items();
$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();

if ( $show_downloads ) {
	wc_get_template( 'order/order-downloads.php', array( 'downloads'  => $downloads, 'show_title' => true, ) );
}

?>
<section class="woocommerce-order-details">
	<table class="woocommerce-table woocommerce-table--order shop_table order_details">
		<thead>
			<tr>
				<th class="woocommerce-table__product-table product-name"><?=__("TYPE OF SERVICE", $that->td);?></th>
				<th class="woocommerce-table__product-table product-price"><?=__("PRICE", $that->td);?></th>
				<th class="woocommerce-table__product-table product-quantity"><?=__("QUANTITY", $that->td);?></th>
				<th class="woocommerce-table__product-table product-total"><?=__("TOTAL", $that->td);?></th>
				<th class="woocommerce-table__product-table product-download"><?=__("DOWNLOAD", $that->td);?></th>
				<th class="woocommerce-table__product-table product-info"><?=__("INFO", $that->td);?></th>
			</tr>
		</thead>

		<tbody>
			<?php
			do_action( 'woocommerce_order_details_before_order_table_items', $order );

			foreach ( $order_items as $item_id => $item ) {
				$product = $item->get_product();
        $purchase_note = $product ? $product->get_purchase_note() : '';

        if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) { return; }
        $is_visible        = $product && $product->is_visible();
        $product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );
        $qty          = $item->get_quantity();
        $refunded_qty = $order->get_qty_refunded_for_item( $item_id );


        ?>
        <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'woocommerce-table__line-item order_item', $item, $order ) ); ?>">

          <td class="woocommerce-table__product-table product-name ">
            <?=wp_kses_post( apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a href="%s">%s</a>', $product_permalink, $item->get_name() ) : $item->get_name(), $item, $is_visible ) );?>
          </td>
  				<td class="woocommerce-table__product-table product-price ">
            <?php
              echo wc_price($order->get_item_subtotal( $item, false, true ), array( 'currency' => $order->get_currency() ) )
             ?>
          </td>
          <td class="woocommerce-table__product-table product-quantity ">
            <?php
              if ( $refunded_qty ) {
                $qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
              } else {
                $qty_display = esc_html( $qty );
              }
              echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $qty_display ) . '</strong>', $item );
            ?>
          </td>
  				<td class="woocommerce-table__product-table product-total ">
            <?php echo $order->get_formatted_line_subtotal( $item ); ?>
          </td>
  				<td class="woocommerce-table__product-table product-download ">

          </td>
  				<td class="woocommerce-table__product-table product-info ">
            <?php
              do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false );
              // wc_display_item_meta( $item );
              do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false );
            ?>
          </td>
        </tr>
        <?php
        if ( $show_purchase_note && $purchase_note ){
          ?>
            <tr class="woocommerce-table__product-purchase-note product-purchase-note">
              <td colspan="2"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
            </tr>
          <?php
        }

      }

			do_action( 'woocommerce_order_details_after_order_table_items', $order );
			?>
		</tbody>

		<tfoot>
			<?php
			foreach ( $order->get_order_item_totals() as $key => $total ) {
				?>
<!--					<tr>
						<th scope="row"><?php echo esc_html( $total['label'] ); ?></th>
						<td><?php echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
					</tr>-->
					<?php
			}
			?>
			<?php if ( $order->get_customer_note() ) : ?>
				<tr>
					<th><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
					<td><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
				</tr>
			<?php endif; ?>
		</tfoot>
	</table>

	<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
</section>
