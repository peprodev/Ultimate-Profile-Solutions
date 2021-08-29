<?php
/**
 * Order Downloads.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-downloads.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$downloads     = WC()->customer->get_downloadable_products();
$has_downloads = (bool) $downloads;
?>
<div class="col-lg-12">
	<div class="table-responsive table--no-card m-b-40">
		<table class="table table-borderless table-striped table-earning">
			<thead>
				<tr>
					<?php foreach ( wc_get_account_downloads_columns() as $column_id => $column_name ) : ?>
					<th class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $downloads as $download ) : ?>
					<tr>
						<?php foreach ( wc_get_account_downloads_columns() as $column_id => $column_name ) : ?>
							<td class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
								<?php
								if ( has_action( 'woocommerce_account_downloads_column_' . $column_id ) ) {
									do_action( 'woocommerce_account_downloads_column_' . $column_id, $download );
								} else {
									switch ( $column_id ) {
										case 'download-product':
											if ( $download['product_url'] ) {
												echo '<a href="' . esc_url( $download['product_url'] ) . '">' . esc_html( $download['product_name'] ) . '</a>';
											} else {
												echo esc_html( $download['product_name'] );
											}
											break;
										case 'download-file':
											echo '<a href="' . esc_url( $download['download_url'] ) . '" class="woocommerce-MyAccount-downloads-file button alt btn btn-sm btn-outline-primary btn-block '.("text-left").'"><i class="fa fa-download mr-10 ml-10"></i> ' . esc_html( $download['download_name'] ) . '</a>';
											break;
										case 'download-remaining':
											echo is_numeric( $download['downloads_remaining'] ) ? esc_html( $download['downloads_remaining'] ) : esc_html__( '&infin;', 'woocommerce' );
											break;
										case 'download-expires':
											if ( ! empty( $download['access_expires'] ) ) {
												echo '<time datetime="' . esc_attr( date( 'Y-m-d', strtotime( $download['access_expires'] ) ) ) . '" title="' . esc_attr( strtotime( $download['access_expires'] ) ) . '">' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $download['access_expires'] ) ) ) . '</time>';
											} else {
												esc_html_e( 'Never', 'woocommerce' );
											}
											break;
									}
								}
								?>
							</td>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
