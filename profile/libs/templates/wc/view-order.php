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
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;
$order_id = (int) sanitize_text_field( trim($_GET['view']) );
$order = wc_get_order( $order_id );
if (!$order || !peprodev_user_has_access_wc_order($order)) { 
	wc_add_notice(__("No valid Order found or you don't have access to given order.", "peprodev-ups"), "error" );
	wc_print_notices();
	return; 
}

function peprodev_user_has_access_wc_order($order){
	if (!$order) {
		return false; // If the order doesn't exist, return false
	}
	
	// Get the order's customer ID and email
	$customer_id = $order->get_user_id();
	$customer_email = $order->get_billing_email();
	
	// Get the user's email
	$user       = wp_get_current_user();
	$user_id    = $user->ID;
	$user_email = $user ? $user->user_email : "";
	
	// Check if the user is the one who placed the order
	if ($user_id == $customer_id || $user_email == $customer_email) {
		return true; // The user is the customer or has the same email, so they can view the order
	}
	
	// Check if the user has the 'manage_woocommerce' capability (admin/shop manager)
	if (user_can($user_id, 'manage_woocommerce')) {
		return true; // The user has permission to manage WooCommerce, so they can view the order
	}
	
	// If the user is neither the customer nor has permissions, they cannot view the order
	return false;
}

$notes = $order->get_customer_order_notes();
?>
<p>
<?php
printf(
	/* translators: 1: order number 2: order date 3: order status */
	esc_html__( 'Order #%1$s was placed on %2$s and is currently %3$s.', 'woocommerce' ),
	'<mark class="order-number">' . $order->get_order_number() . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'<mark class="order-date">' . wc_format_datetime( $order->get_date_created() ) . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'<mark class="order-status">' . wc_get_order_status_name( $order->get_status() ) . '</mark>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
);
?>
</p>

<?php if ( $notes ) : ?>
	<h2><?php esc_html_e( 'Order updates', 'woocommerce' ); ?></h2>
	<ol class="woocommerce-OrderUpdates commentlist notes">
		<?php foreach ( $notes as $note ) : ?>
		<li class="woocommerce-OrderUpdate comment note">
			<div class="woocommerce-OrderUpdate-inner comment_container">
				<div class="woocommerce-OrderUpdate-text comment-text">
					<p class="woocommerce-OrderUpdate-meta meta"><?php echo date_i18n( esc_html__( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					<div class="woocommerce-OrderUpdate-description description">
						<?php echo wpautop( wptexturize( $note->comment_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		</li>
		<?php endforeach; ?>
	</ol>
<?php endif; ?>

<?php do_action( 'woocommerce_view_order', $order_id ); ?>
