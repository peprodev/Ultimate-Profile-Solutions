<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/16 19:59:02

/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

defined('ABSPATH') || exit;
global $PeproDevUPS_Profile;
$customer_id = get_current_user_id();
if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()) {
	$get_addresses = apply_filters('woocommerce_my_account_get_addresses', array(
		'billing'  => __('Billing address', 'woocommerce'),
		'shipping' => __('Shipping address', 'woocommerce'),
	), $customer_id);
} else {
	$get_addresses = apply_filters('woocommerce_my_account_get_addresses', array(
		'billing' => __('Billing address', 'woocommerce'),
	), $customer_id);
}
wp_enqueue_script("jquery");
?>
<div class="tabbed-wrapper">
	<div class="tab-names">
		<?php foreach ($get_addresses as $name => $address_title) echo "<a href='javascript:;' class='change-tab " . ($name == "billing" ? "active" : "hide") . "' data-tab='$name'>$address_title</a>"; ?>
	</div>
	<div class="tab-content">
		<?php
		foreach ($get_addresses as $name => $address_title) {
			$address = wc_get_account_formatted_address($name);
		?>
			<div data-tab="<?php echo  $name; ?>" class="tab-entry card <?php echo  $name == "billing" ? "active" : "hide"; ?>">
				<div class="card-body" data-pepro-reglogin="false">
					<br>
					<address> <?php echo $address ? wp_kses_post($address) : esc_html_e('You have not set up this type of address yet.', 'woocommerce'); ?> </address>
					<form action="<?php echo esc_url($PeproDevUPS_Profile->get_profile_page(true)); ?>" method="get">
						<input type="hidden" name="section" value="address" />
						<input type="hidden" name="part" value="<?php echo $name;?>" />
						<button id="submit" type="submit" style="max-width: 300px;" class="btn btn-lg btn-info btn-block edit"><?php echo $address ? esc_html__('Edit', 'woocommerce') : esc_html__('Add', 'woocommerce'); ?></button>
					</form>
				</div>
			</div>
		<?php
		}
		?>
	</div>
</div>
<?php
