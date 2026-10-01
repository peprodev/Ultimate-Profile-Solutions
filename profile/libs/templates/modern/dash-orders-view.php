<?php
/**
 * Modern UI: single order view inside the dashboard.
 *
 * Modern variant of profile/libs/templates/dash-orders-view.php.
 */

defined( 'ABSPATH' ) || exit;

global $PeproDevUPS_Profile;

$PeproDevUPS_Profile->change_dashboard_title( _x( 'View Order', 'user-dashboard', 'peprodev-ups' ) );

if ( ! function_exists( 'wc_get_order' ) ) {
	return;
}

$mj_order_id = isset( $_GET['view'] ) ? absint( wp_unslash( $_GET['view'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$mj_order    = $mj_order_id ? wc_get_order( $mj_order_id ) : false;
$mj_user     = wp_get_current_user();
$mj_allowed  = $mj_order && $mj_order instanceof WC_Order && (
	(int) $mj_order->get_user_id() === (int) $mj_user->ID
	|| ( ! $mj_order->get_user_id() && $mj_user->user_email && strtolower( $mj_order->get_billing_email() ) === strtolower( $mj_user->user_email ) )
	|| current_user_can( 'manage_woocommerce' )
);
$mj_back = $PeproDevUPS_Profile->get_profile_page( array( 'section' => 'orders' ) );

if ( ! $mj_allowed ) {
	echo '<div class="woocommerce-error">' . esc_html__( "No valid Order found or you don't have access to given order.", 'peprodev-ups' ) . '</div>';
	echo '<p><a class="btn mj-btn-primary" href="' . esc_url( $mj_back ) . '">' . esc_html__( 'Back to orders', 'peprodev-ups' ) . '</a></p>';
	return;
}

$mj_status  = $mj_order->get_status();
$mj_paid    = in_array( $mj_status, array( 'processing', 'completed' ), true );
$mj_notes   = $mj_order->get_customer_order_notes();
$mj_actions = wc_get_account_orders_actions( $mj_order );
unset( $mj_actions['view'] );
$mj_billing = $mj_order->get_formatted_billing_address();
$mj_ship    = $mj_order->needs_shipping_address() ? $mj_order->get_formatted_shipping_address() : '';

/**
 * Output of WooCommerce order hooks, so plugins that add content to the order view
 * (licenses, download keys, players, tracking, ...) show it here too. WooCommerce's own
 * order table is left out of "woocommerce_view_order" because this view draws its own.
 *
 * @param string $hook Action name.
 * @param array  $args Arguments.
 * @return string
 */
$mj_hook_html = function ( $hook, array $args ) {
	$table = 'woocommerce_view_order' === $hook ? has_action( $hook, 'woocommerce_order_details_table' ) : false;
	if ( false !== $table ) {
		remove_action( $hook, 'woocommerce_order_details_table', $table );
	}
	ob_start();
	do_action_ref_array( $hook, $args );
	$html = (string) ob_get_clean();
	if ( false !== $table ) {
		add_action( $hook, 'woocommerce_order_details_table', $table );
	}
	return trim( $html );
};
$mj_before = $mj_hook_html( 'woocommerce_view_order', array( $mj_order->get_id() ) ) . $mj_hook_html( 'woocommerce_order_details_before_order_table', array( $mj_order ) );
$mj_after  = $mj_hook_html( 'woocommerce_order_details_after_order_table', array( $mj_order ) ) . $mj_hook_html( 'woocommerce_after_order_details', array( $mj_order ) );
$mj_cust   = $mj_hook_html( 'woocommerce_order_details_after_customer_details', array( $mj_order ) );
?>
<div class="mj-order">
	<a class="mj-back" href="<?php echo esc_url( $mj_back ); ?>">
		<svg viewBox="0 0 256 256" aria-hidden="true"><path d="M221.66,133.66l-72,72a8,8,0,0,1-11.32-11.32L196.69,136H40a8,8,0,0,1,0-16H196.69L138.34,61.66a8,8,0,0,1,11.32-11.32l72,72A8,8,0,0,1,221.66,133.66Z"/></svg>
		<?php esc_html_e( 'Back to orders', 'peprodev-ups' ); ?>
	</a>

	<section class="mj-card mj-order__head">
		<div class="mj-order__title-row">
			<h3 class="mj-page-title"><?php /* translators: %s: order number. */ echo esc_html( sprintf( __( 'Order #%s', 'peprodev-ups' ), $mj_order->get_order_number() ) ); ?></h3>
			<span class="mj-status mj-status--<?php echo esc_attr( $mj_status ); ?>"><?php echo esc_html( wc_get_order_status_name( $mj_status ) ); ?></span>
		</div>
		<dl class="mj-order__meta">
			<div>
				<dt><?php esc_html_e( 'Order date', 'peprodev-ups' ); ?></dt>
				<dd><?php echo esc_html( wc_format_datetime( $mj_order->get_date_created() ) ); ?></dd>
			</div>
			<div>
				<dt><?php echo esc_html_x( 'Total', 'order', 'peprodev-ups' ); ?></dt>
				<dd><?php echo wp_kses_post( $mj_order->get_formatted_order_total() ); ?></dd>
			</div>
			<?php if ( $mj_order->get_payment_method_title() ) : ?>
				<div>
					<dt><?php esc_html_e( 'Payment method', 'peprodev-ups' ); ?></dt>
					<dd><?php echo esc_html( $mj_order->get_payment_method_title() ); ?></dd>
				</div>
			<?php endif; ?>
		</dl>
		<?php if ( $mj_actions ) : ?>
			<div class="mj-order__actions">
				<?php foreach ( $mj_actions as $mj_key => $mj_action ) : ?>
					<a href="<?php echo esc_url( $mj_action['url'] ); ?>" class="btn <?php echo 'pay' === $mj_key ? 'mj-btn-primary' : 'mj-btn-secondary'; ?> mj-order__action--<?php echo esc_attr( $mj_key ); ?>"><?php echo esc_html( $mj_action['name'] ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</section>

	<?php if ( '' !== $mj_before ) : ?>
		<section class="mj-card mj-order__hooks mj-order__hooks--before woocommerce"><?php echo $mj_before; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output of other plugins' order hooks ?></section>
	<?php endif; ?>

	<section class="mj-card">
		<header class="mj-card__head">
			<h4 class="mj-card__title"><?php esc_html_e( 'Order details', 'peprodev-ups' ); ?></h4>
		</header>
		<ul class="mj-order__items">
			<?php
			foreach ( $mj_order->get_items() as $mj_item ) :
				if ( ! $mj_item instanceof WC_Order_Item_Product ) {
					continue;
				}
				$mj_product = $mj_item->get_product();
				$mj_link    = $mj_product && $mj_product->is_visible() ? $mj_product->get_permalink( $mj_item ) : '';
				$mj_courses = array();
				if ( $mj_paid && $mj_product && function_exists( 'peprodev_ui_product_courses' ) ) {
					$mj_courses = peprodev_ui_product_courses( $mj_product->get_parent_id() ? $mj_product->get_parent_id() : $mj_product->get_id() );
				}
				?>
				<li class="mj-order__item">
					<?php $mj_item_id = $mj_item->get_id(); ?>
					<span class="mj-order__thumb">
						<?php echo $mj_product ? wp_kses_post( $mj_product->get_image( 'full' ) ) : wp_kses_post( wc_placeholder_img( 'full' ) ); ?>
					</span>
					<span class="mj-order__info">
						<?php if ( $mj_link ) : ?>
							<a class="mj-order__name" href="<?php echo esc_url( $mj_link ); ?>"><?php echo esc_html( $mj_item->get_name() ); ?></a>
						<?php else : ?>
							<span class="mj-order__name"><?php echo esc_html( $mj_item->get_name() ); ?></span>
						<?php endif; ?>
						<span class="mj-order__qty"><?php /* translators: %s: item quantity. */ echo esc_html( sprintf( __( 'Quantity: %s', 'peprodev-ups' ), number_format_i18n( $mj_item->get_quantity() ) ) ); ?></span>
						<?php
						do_action( 'woocommerce_order_item_meta_start', $mj_item_id, $mj_item, $mj_order, false );
						wc_display_item_meta( $mj_item, array( 'before' => '<div class="mj-order__item-meta">', 'after' => '</div>' ) );
						do_action( 'woocommerce_order_item_meta_end', $mj_item_id, $mj_item, $mj_order, false );
						?>
					</span>
					<span class="mj-order__line"><?php echo wp_kses_post( $mj_order->get_formatted_line_subtotal( $mj_item ) ); ?></span>
					<?php foreach ( $mj_courses as $mj_course ) : ?>
						<a class="btn mj-btn-secondary mj-order__course" href="<?php echo esc_url( peprodev_ui_course_url( $mj_course ) ); ?>"><?php esc_html_e( 'View course', 'peprodev-ups' ); ?></a>
					<?php endforeach; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<dl class="mj-order__totals">
			<?php foreach ( $mj_order->get_order_item_totals() as $mj_key => $mj_total ) : ?>
				<div class="mj-order__total mj-order__total--<?php echo esc_attr( $mj_key ); ?>">
					<dt><?php echo esc_html( rtrim( wp_strip_all_tags( $mj_total['label'] ), ':' ) ); ?></dt>
					<dd><?php echo wp_kses_post( $mj_total['value'] ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
		<?php if ( $mj_order->get_customer_note() ) : ?>
			<p class="mj-order__note"><strong><?php esc_html_e( 'Note:', 'peprodev-ups' ); ?></strong> <?php echo wp_kses_post( nl2br( wptexturize( $mj_order->get_customer_note() ) ) ); ?></p>
		<?php endif; ?>
		<?php if ( '' !== $mj_after ) : ?>
			<div class="mj-order__hooks mj-order__hooks--after woocommerce"><?php echo $mj_after; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output of other plugins' order hooks ?></div>
		<?php endif; ?>
	</section>

	<?php if ( $mj_billing || $mj_ship ) : ?>
		<section class="mj-card">
			<div class="mj-order__addresses">
				<?php if ( $mj_billing ) : ?>
					<div>
						<h4 class="mj-card__title"><?php esc_html_e( 'Billing address', 'peprodev-ups' ); ?></h4>
						<address><?php echo wp_kses_post( $mj_billing ); ?>
							<?php if ( $mj_order->get_billing_phone() ) : ?>
								<br><span dir="ltr"><?php echo esc_html( $mj_order->get_billing_phone() ); ?></span>
							<?php endif; ?>
							<?php if ( $mj_order->get_billing_email() ) : ?>
								<br><span dir="ltr"><?php echo esc_html( $mj_order->get_billing_email() ); ?></span>
							<?php endif; ?>
						</address>
						<?php do_action( 'woocommerce_order_details_after_customer_address', 'billing', $mj_order ); ?>
					</div>
				<?php endif; ?>
				<?php if ( $mj_ship ) : ?>
					<div>
						<h4 class="mj-card__title"><?php esc_html_e( 'Shipping address', 'peprodev-ups' ); ?></h4>
						<address><?php echo wp_kses_post( $mj_ship ); ?></address>
						<?php do_action( 'woocommerce_order_details_after_customer_address', 'shipping', $mj_order ); ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( '' !== $mj_cust ) : ?>
		<section class="mj-card mj-order__hooks mj-order__hooks--customer woocommerce"><?php echo $mj_cust; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output of other plugins' order hooks ?></section>
	<?php endif; ?>

	<?php if ( $mj_notes ) : ?>
		<section class="mj-card">
			<header class="mj-card__head">
				<h4 class="mj-card__title"><?php esc_html_e( 'Order updates', 'peprodev-ups' ); ?></h4>
			</header>
			<ol class="mj-timeline">
				<?php foreach ( $mj_notes as $mj_note ) : ?>
					<li>
						<time><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $mj_note->comment_date ) ) ); ?></time>
						<div><?php echo wp_kses_post( wpautop( wptexturize( $mj_note->comment_content ) ) ); ?></div>
					</li>
				<?php endforeach; ?>
			</ol>
		</section>
	<?php endif; ?>
</div>
