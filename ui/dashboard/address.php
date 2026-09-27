<?php
/**
 * Modern UI: inline WooCommerce address editing on the "Edit profile"
 * section, saved over AJAX.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Address types the customer can edit (mirrors WooCommerce My Account).
 *
 * @return array<string,string> type => title
 */
function peprodev_ui_profile_address_types() {
	$types = array( 'billing' => __( 'Billing address', 'peprodev-ups' ) );
	if ( wc_shipping_enabled() && ! wc_ship_to_billing_address_only() ) {
		$types['shipping'] = __( 'Shipping address', 'peprodev-ups' );
	}
	return apply_filters( 'woocommerce_my_account_get_addresses', $types, get_current_user_id() );
}

/**
 * Checkout field definitions for an address type and country.
 *
 * @param string $type    billing|shipping.
 * @param string $country Country code.
 * @return array
 */
function peprodev_ui_profile_address_fields( $type, $country ) {
	return WC()->countries->get_address_fields( $country, $type . '_' );
}

/**
 * Render the address card (tabs + one AJAX form per address type).
 */
function peprodev_ui_profile_render_addresses() {
	if ( ! function_exists( 'WC' ) ) {
		return;
	}
	$user_id  = get_current_user_id();
	$customer = new WC_Customer( $user_id );
	$types    = peprodev_ui_profile_address_types();
	$multi    = count( $types ) > 1;
	?>
	<section class="mj-card mj-addresses" id="address">
		<header class="mj-card__head">
			<h4 class="mj-card__title"><?php esc_html_e( 'Addresses', 'peprodev-ups' ); ?></h4>
			<p class="mj-card__desc"><?php esc_html_e( 'The address used on your orders and invoices.', 'peprodev-ups' ); ?></p>
		</header>
		<?php if ( $multi ) : ?>
			<div class="mj-segment" role="tablist">
				<?php foreach ( array_keys( $types ) as $i => $type ) : ?>
					<button type="button" role="tab" class="mj-segment__btn<?php echo 0 === $i ? ' is-active' : ''; ?>" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-controls="mj-address-<?php echo esc_attr( $type ); ?>" data-target="mj-address-<?php echo esc_attr( $type ); ?>"><?php echo esc_html( $types[ $type ] ); ?></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php foreach ( array_keys( $types ) as $i => $type ) : ?>
			<?php
			$country = $customer->{"get_{$type}_country"}();
			$country = $country ? $country : WC()->countries->get_base_country();
			$fields  = peprodev_ui_profile_address_fields( $type, $country );
			?>
			<form class="mj-address-form woocommerce-address-fields" id="mj-address-<?php echo esc_attr( $type ); ?>" data-type="<?php echo esc_attr( $type ); ?>" role="<?php echo $multi ? 'tabpanel' : 'group'; ?>"<?php echo $i > 0 ? ' hidden' : ''; ?> novalidate>
				<div class="woocommerce-address-fields__field-wrapper mj-address-grid">
					<?php
					foreach ( $fields as $key => $field ) {
						$getter = "get_{$key}";
						$value  = is_callable( array( $customer, $getter ) ) ? $customer->$getter() : $customer->get_meta( $key );
						if ( "{$type}_country" === $key && ! $value ) {
							$value = $country;
						}
						woocommerce_form_field( $key, $field, $value );
					}
					?>
				</div>
				<div class="mj-savebar">
					<button type="submit" class="btn mj-btn-primary"><?php esc_html_e( 'Save address', 'peprodev-ups' ); ?></button>
					<div class="mj-address-status alert-box" role="status" aria-live="polite"></div>
				</div>
			</form>
		<?php endforeach; ?>
	</section>
	<?php
}

/**
 * AJAX: validate and save one address for the current customer.
 */
function peprodev_ui_profile_save_address() {
	check_ajax_referer( 'peprodev_ui_profile_address', 'nonce' );
	$user_id = get_current_user_id();
	$type    = isset( $_POST['type'] ) ? sanitize_key( wp_unslash( $_POST['type'] ) ) : '';
	if ( ! $user_id || ! function_exists( 'WC' ) || ! isset( peprodev_ui_profile_address_types()[ $type ] ) ) {
		wp_send_json_error( array( 'msg' => __( 'Invalid request.', 'peprodev-ups' ) ), 400 );
	}

	$country_key = "{$type}_country";
	$country     = isset( $_POST[ $country_key ] ) ? wc_clean( wp_unslash( $_POST[ $country_key ] ) ) : '';
	if ( ! $country || ! array_key_exists( $country, WC()->countries->get_allowed_countries() + WC()->countries->get_shipping_countries() ) ) {
		$country = WC()->countries->get_base_country();
	}

	$fields   = peprodev_ui_profile_address_fields( $type, $country );
	$customer = new WC_Customer( $user_id );
	$errors   = array();

	foreach ( $fields as $key => $field ) {
		$value = isset( $_POST[ $key ] ) ? wc_clean( wp_unslash( $_POST[ $key ] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- wc_clean.
		$label = isset( $field['label'] ) ? wp_strip_all_tags( $field['label'] ) : $key;

		if ( ! empty( $field['required'] ) && '' === $value ) {
			/* translators: %s: field name */
			$errors[ $key ] = sprintf( __( '%s is a required field.', 'peprodev-ups' ), $label );
			continue;
		}
		if ( '' !== $value && ! empty( $field['validate'] ) && is_array( $field['validate'] ) ) {
			if ( in_array( 'email', $field['validate'], true ) ) {
				$value = sanitize_email( $value );
				if ( ! is_email( $value ) ) {
					/* translators: %s: field name */
					$errors[ $key ] = sprintf( __( '%s is not a valid email address.', 'peprodev-ups' ), $label );
				}
			}
			if ( in_array( 'phone', $field['validate'], true ) && ! WC_Validation::is_phone( $value ) ) {
				/* translators: %s: field name */
				$errors[ $key ] = sprintf( __( '%s is not a valid phone number.', 'peprodev-ups' ), $label );
			}
			if ( in_array( 'postcode', $field['validate'], true ) ) {
				$value = wc_format_postcode( $value, $country );
				if ( ! WC_Validation::is_postcode( $value, $country ) ) {
					$errors[ $key ] = __( 'Please enter a valid postcode / ZIP.', 'peprodev-ups' );
				}
			}
		}

		$setter = "set_{$key}";
		if ( is_callable( array( $customer, $setter ) ) ) {
			$customer->$setter( $value );
		} else {
			$customer->update_meta_data( $key, $value );
		}
	}

	if ( $errors ) {
		wp_send_json_error( array( 'msg' => implode( '<br>', array_map( 'esc_html', $errors ) ), 'fields' => array_keys( $errors ) ) );
	}

	$customer->save();
	do_action( 'woocommerce_customer_save_address', $user_id, $type );

	wp_send_json_success( array( 'msg' => __( 'Address changed successfully.', 'peprodev-ups' ) ) );
}
add_action( 'wp_ajax_peprodev_ui_profile_save_address', 'peprodev_ui_profile_save_address' );
