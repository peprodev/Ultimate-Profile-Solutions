<?php
/**
 * Modern UI: orders list of the dashboard (Orders section and the [profile-wc-orders] shortcode).
 *
 * Modern variant of profile/libs/templates/wc/orders.php: the same table on wide screens,
 * and on phones a card per order with its first products and a link to the full order.
 */

defined( 'ABSPATH' ) || exit;

global $current_page, $PeproDevUPS_Profile;

$current_page    = empty( $current_page ) ? 1 : absint( $current_page ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
$customer_orders = wc_get_orders(
	apply_filters(
		'woocommerce_my_account_my_orders_query',
		array(
			'customer' => get_current_user_id(),
			'page'     => $current_page,
			'paginate' => true,
			'limit'    => -1,
		)
	)
);
$has_orders = 0 < $customer_orders->total;
$mj_columns = wc_get_account_orders_columns();
$mj_preview = (int) apply_filters( 'peprodev_ui_orders_card_items', 3 );

/**
 * Order actions with the "view" action pointing to the dashboard order view.
 *
 * @param WC_Order $order Order.
 * @return array
 */
$mj_actions = function ( $order ) use ( $PeproDevUPS_Profile ) {
	$actions = wc_get_account_orders_actions( $order );
	if ( isset( $actions['view'] ) && $PeproDevUPS_Profile ) {
		$actions['view']['url'] = $PeproDevUPS_Profile->get_profile_page( array( 'section' => 'orders', 'view' => $order->get_id() ) );
	}
	return $actions;
};
$mj_icons = array(
	'view'       => '<i class="fa fa-eye"></i>',
	'puice_pdf'  => '<i class="fa fa-file-pdf"></i>',
	'puice_html' => '<i class="fa fa-file-alt"></i>',
);

do_action( 'woocommerce_before_account_orders', $has_orders );
?>

<?php if ( $has_orders ) : ?>
	<div class="mj-orders-table table-responsive table--no-card m-b-40">
		<table class="table table-borderless table-striped table-earning">
			<thead>
				<tr>
					<?php foreach ( $mj_columns as $column_id => $column_name ) : ?>
						<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ( $customer_orders->orders as $customer_order ) :
					$order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					$item_count = $order->get_item_count() - $order->get_item_count_refunded();
					?>
					<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
						<?php foreach ( $mj_columns as $column_id => $column_name ) : ?>
							<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
								<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
									<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>
								<?php elseif ( 'order-number' === $column_id ) : ?>
									<?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
								<?php elseif ( 'order-date' === $column_id ) : ?>
									<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
								<?php elseif ( 'order-status' === $column_id ) : ?>
									<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
								<?php elseif ( 'order-total' === $column_id ) : ?>
									<?php
									/* translators: 1: formatted order total 2: total order items */
									echo wp_kses_post( sprintf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) );
									?>
								<?php elseif ( 'order-actions' === $column_id ) : ?>
									<?php foreach ( $mj_actions( $order ) as $key => $action ) : ?>
										<a href="<?php echo esc_url( $action['url'] ); ?>" class="woocommerce-button button <?php echo esc_attr( sanitize_html_class( $key ) ); ?>"><?php echo isset( $mj_icons[ $key ] ) ? $mj_icons[ $key ] : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $action['name'] ); ?></a>
									<?php endforeach; ?>
								<?php endif; ?>
							</td>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<ul class="mj-orders-cards">
		<?php
		foreach ( $customer_orders->orders as $customer_order ) :
			$order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$item_count = $order->get_item_count() - $order->get_item_count_refunded();
			$actions    = $mj_actions( $order );
			$view_url   = isset( $actions['view'] ) ? $actions['view']['url'] : '';
			$items      = array_values(
				array_filter(
					$order->get_items(),
					function ( $item ) {
						return $item instanceof WC_Order_Item_Product;
					}
				)
			);
			$more       = count( $items ) - $mj_preview;
			?>
			<li class="mj-order-card mj-card">
				<div class="mj-order-card__head">
					<span class="mj-order-card__number"><?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?></span>
					<span class="mj-status mj-status--<?php echo esc_attr( $order->get_status() ); ?>"><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></span>
				</div>
				<div class="mj-order-card__meta">
					<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
					<strong class="mj-order-card__total"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
				</div>
				<?php if ( $items ) : ?>
					<ul class="mj-order-card__items">
						<?php foreach ( array_slice( $items, 0, max( 1, $mj_preview ) ) as $item ) : ?>
							<?php $product = $item->get_product(); ?>
							<li>
								<span class="mj-order-card__thumb"><?php echo $product ? wp_kses_post( $product->get_image( 'thumbnail' ) ) : wp_kses_post( wc_placeholder_img( 'thumbnail' ) ); ?></span>
								<span class="mj-order-card__name"><?php echo esc_html( $item->get_name() ); ?></span>
								<?php if ( $item->get_quantity() > 1 ) : ?>
									<span class="mj-order-card__qty">&times;<?php echo esc_html( number_format_i18n( $item->get_quantity() ) ); ?></span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php if ( $more > 0 && $view_url ) : ?>
					<a class="mj-order-card__more" href="<?php echo esc_url( $view_url ); ?>">
						<?php
						/* translators: %s: number of more products in the order. */
						echo esc_html( sprintf( _n( '+ %s more item, view the order', '+ %s more items, view the order', $more, 'peprodev-ups' ), number_format_i18n( $more ) ) );
						?>
					</a>
				<?php endif; ?>
				<?php
				// columns added by other plugins
				foreach ( $mj_columns as $column_id => $column_name ) :
					if ( in_array( $column_id, array( 'order-number', 'order-date', 'order-status', 'order-total', 'order-actions' ), true ) || ! has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) {
						continue;
					}
					?>
					<div class="mj-order-card__extra"><span><?php echo esc_html( $column_name ); ?></span> <?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?></div>
				<?php endforeach; ?>
				<?php if ( $actions ) : ?>
					<div class="mj-order-card__actions">
						<?php foreach ( $actions as $key => $action ) : ?>
							<a href="<?php echo esc_url( $action['url'] ); ?>" class="woocommerce-button button <?php echo 'view' === $key ? 'mj-btn-primary' : ''; ?> <?php echo esc_attr( sanitize_html_class( $key ) ); ?>"><?php echo isset( $mj_icons[ $key ] ) ? $mj_icons[ $key ] : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $action['name'] ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>
			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse products', 'woocommerce' ); ?></a>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
