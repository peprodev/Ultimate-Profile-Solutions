<?php

# @Last modified by:   amirhp-com <its@amirhp.com>
# @Last modified time: 2022/08/30 20:28:52

/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
global $current_page, $PeproDevUPS_Profile;
$current_page = empty( $current_page ) ? 1 : absint( $current_page );
$customer_orders = wc_get_orders( apply_filters(
		'woocommerce_my_account_my_orders_query',
			array(
				'customer' => get_current_user_id(),
				'page'     => $current_page,
				'paginate' => true,
				'limit' => -1,
			)
	)
);

function rtc_get_order_service($order)
{
	$order_items  = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
	$service_type = "NO SERVICE";

	foreach ( $order_items as $item_id => $item ) {
		$product = $item->get_product();
		if ($service_type == "NO SERVICE") {
			if (!has_term("services", 'product_cat', $product->get_id())) continue;
			$service_type = $item->get_name();
		}
		continue;
	}
 	return $service_type;
}


$has_orders	= 0 < $customer_orders->total;
// add_filter( "woocommerce_price_format", function(){return '%2$s %1$s';},10000,2);
do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>
	<div class="table-responsive table--no-card m-b-40">
		<table class="table table-borderless table-striped table-earning wc-orders-listing desktop-view">
			<thead>
				<tr>
					<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
						<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ( $customer_orders->orders as $customer_order ) {
					$order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
					$item_count = $order->get_item_count() - $order->get_item_count_refunded();
					?>
					<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> wc-<?php echo esc_attr( $order->get_status() ); ?> order">
						<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
							<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
								<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
									<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>
								<?php elseif ( 'order-number' === $column_id ) : ?>
										<span><?php echo esc_html( sprintf("%06d",$order->get_order_number()) ); ?></span>
								<?php elseif ( 'order-date' === $column_id ) : ?>
									<span><time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( $order->get_date_created()->date("Y.m.d") ); ?></time></span>
								<?php elseif ( 'order-status' === $column_id ) : ?>
									<span><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></span>
								<?php elseif ( 'order-total' === $column_id ) : ?>
									<span><?php
									/* translators: 1: formatted order total 2: total order items */
									echo wp_kses_post( $order->get_formatted_order_total() );
									?></span>
								<?php elseif ( 'order-actions' === $column_id ) : ?>
									<span><?php
											$action['name'] = "View";
											$action['url'] = $PeproDevUPS_Profile->get_profile_page(["section"=>"orders", "view"=>$order->get_id()]);
											echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button btn btn-outline-primary mr-1 ml-1 ' . sanitize_html_class( $key ) . "\">" . esc_html( $action['name'] ) . '</a>';
									?></span>
								<?php endif; ?>
							</td>
						<?php endforeach; ?>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
		<div class="table table-borderless wc-orders-listing mobile-view">
				<?php
				foreach ( $customer_orders->orders as $customer_order ) {
					$order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
					$item_count = $order->get_item_count() - $order->get_item_count_refunded();
					?>
					<div class="woocommerce-orders-table__row mobile-view woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> wc-<?php echo esc_attr( $order->get_status() ); ?> order">
							<div class="order-item-head">
								<div class="order-item-id-num">
									<span>ORDER ID</span>
									<span><?php echo esc_html( sprintf("%06d",$order->get_order_number()) ); ?></span>
								</div>
								<div class="order-item-action">
									<span><?php
											$action_url = $PeproDevUPS_Profile->get_profile_page([ "section" => "orders", "view" => $order->get_id() ]);
											echo '<a href="' . esc_url( $action_url ) . '" class="button-procceed-arrow-right"></a>';
									?></span>
								</div>
							</div>
							<div class="order-item-details">
								<div class="order-item-details-type">
									<span class="order-item-details-head">SERVICE TYPE</span>
									<span class="order-item-details-body"><?=rtc_get_order_service($order);?></span>
								</div>
								<div class="order-item-details-date">
									<span class="order-item-details-head">DATE OF ORDER</span>
									<span class="order-item-details-body"><?=wc_format_datetime( $order->get_date_created() );?></span>
								</div>
								<div class="order-item-details-status">
									<span class="order-item-details-head">STATUS</span>
									<span class="order-item-details-body"><span class="vocv wc-<?php echo esc_attr( $order->get_status() ); ?>"><?=wc_get_order_status_name( $order->get_status() );?></span></span>
								</div>
							</div>
					</div>
					<?php
				}
				?>
		</div>
	</div>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="col-12 wc-order-pagination">
				<div class="card">
						<div class="card-body">
							<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
								<?php if ( 1 !== $current_page ) : ?>
									<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
								<?php endif; ?>

								<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
									<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
								<?php endif; ?>
							</div>
						</div>
				</div>
		</div>

	<?php endif; ?>

<?php else : ?>
	<div class="col-12">
			<div class="card">
					<div class="card-body">
						<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
							<?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
							<a class="woocommerce-Button button btn btn-outline-primary <?php echo is_rtl() ? "float-right" : "float-left";?>" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
								<?php esc_html_e( 'Browse products', 'woocommerce' ); ?>
							</a>
						</div>
					</div>
			</div>
	</div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
