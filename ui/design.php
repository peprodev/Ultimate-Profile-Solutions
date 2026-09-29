<?php
/**
 * PeproDev UPS modern UI: "Modern UI Design" settings screen.
 *
 * Colors of the buttons, tabs, links, sidebar menu, tables, cards and fields of
 * the modern login form and user dashboard. Every value is optional: an empty
 * field keeps the built-in design (or the theme's own --mj-* tokens). Saved
 * values are printed as CSS custom properties on html:root (see
 * peprodev_ui_accent_css()); the stylesheets read them with fallbacks, e.g.
 * background: var(--mj-btn-bg, var(--mj-primary)).
 *
 * @package PeproDev_UPS
 */

defined( 'ABSPATH' ) || exit;

/**
 * Design settings: key => array( section, label, CSS custom property, type, placeholder ).
 *
 * @return array<string,array>
 */
function peprodev_ui_design_fields() {
	return apply_filters(
		'peprodev_ui_design_fields',
		array(
			'ui_accent'          => array( 'general', __( 'Button accent color', 'peprodev-ups' ), '', 'color', '#28504f' ),
			'd_heading'          => array( 'general', __( 'Titles', 'peprodev-ups' ), '--mj-heading', 'color', '#231f1a' ),
			'd_card_bg'          => array( 'general', __( 'Card background', 'peprodev-ups' ), '--mj-card-bg', 'color', '#ffffff' ),
			'd_card_border'      => array( 'general', __( 'Card border', 'peprodev-ups' ), '--mj-card-border', 'color', '#e8e1d6' ),
			'd_btn_bg'           => array( 'buttons', __( 'Button background', 'peprodev-ups' ), '--mj-btn-bg', 'color', '#28504f' ),
			'd_btn_text'         => array( 'buttons', __( 'Button text', 'peprodev-ups' ), '--mj-btn-text', 'color', '#ffffff' ),
			'd_btn_hover_bg'     => array( 'buttons', __( 'Button background on hover', 'peprodev-ups' ), '--mj-btn-hover-bg', 'color', '#1e3d3c' ),
			'd_btn_hover_text'   => array( 'buttons', __( 'Button text on hover', 'peprodev-ups' ), '--mj-btn-hover-text', 'color', '#ffffff' ),
			'd_tab_track'        => array( 'tabs', __( 'Tabs background', 'peprodev-ups' ), '--mj-tab-track', 'color', '#f5f1ea' ),
			'd_tab_text'         => array( 'tabs', __( 'Tab text', 'peprodev-ups' ), '--mj-tab-text', 'color', '#756c61' ),
			'd_tab_active_bg'    => array( 'tabs', __( 'Active tab background', 'peprodev-ups' ), '--mj-tab-active-bg', 'color', '#ffffff' ),
			'd_tab_active_text'  => array( 'tabs', __( 'Active tab text', 'peprodev-ups' ), '--mj-tab-active-text', 'color', '#231f1a' ),
			'd_link'             => array( 'links', __( 'Links', 'peprodev-ups' ), '--mj-link', 'color', '#28504f' ),
			'd_link_hover'       => array( 'links', __( 'Links on hover', 'peprodev-ups' ), '--mj-link-hover', 'color', '#1e3d3c' ),
			'd_nav_bg'           => array( 'sidebar', __( 'Sidebar background', 'peprodev-ups' ), '--mj-nav-bg', 'color', '#ffffff' ),
			'd_nav_text'         => array( 'sidebar', __( 'Menu items', 'peprodev-ups' ), '--mj-nav-text', 'color', '#756c61' ),
			'd_nav_hover_bg'     => array( 'sidebar', __( 'Menu item background on hover', 'peprodev-ups' ), '--mj-nav-hover-bg', 'color', '#f5f1ea' ),
			'd_nav_hover_text'   => array( 'sidebar', __( 'Menu item text on hover', 'peprodev-ups' ), '--mj-nav-hover-text', 'color', '#231f1a' ),
			'd_nav_active_bg'    => array( 'sidebar', __( 'Active menu item background', 'peprodev-ups' ), '--mj-nav-active-bg', 'color', '#e7efee' ),
			'd_nav_active_text'  => array( 'sidebar', __( 'Active menu item text', 'peprodev-ups' ), '--mj-nav-active-text', 'color', '#28504f' ),
			'd_nav_indicator'    => array( 'sidebar', __( 'Active menu item marker', 'peprodev-ups' ), '--mj-nav-indicator', 'color', '#28504f' ),
			'd_table_head_bg'    => array( 'table', __( 'Table header background', 'peprodev-ups' ), '--mj-table-head-bg', 'color', '#f5f1ea' ),
			'd_table_head_text'  => array( 'table', __( 'Table header text', 'peprodev-ups' ), '--mj-table-head-text', 'color', '#756c61' ),
			'd_table_text'       => array( 'table', __( 'Table text', 'peprodev-ups' ), '--mj-table-text', 'color', '#231f1a' ),
			'd_table_border'     => array( 'table', __( 'Table borders', 'peprodev-ups' ), '--mj-table-border', 'color', '#e8e1d6' ),
			'd_table_row_hover'  => array( 'table', __( 'Table row on hover', 'peprodev-ups' ), '--mj-table-row-hover', 'color', '#faf8f4' ),
			'd_label'            => array( 'fields', __( 'Field labels', 'peprodev-ups' ), '--mj-label', 'color', '#756c61' ),
			'd_input_bg'         => array( 'fields', __( 'Field background', 'peprodev-ups' ), '--mj-input-bg', 'color', '#ffffff' ),
			'd_input_border'     => array( 'fields', __( 'Field border', 'peprodev-ups' ), '--mj-input-border', 'color', '#e8e1d6' ),
			'd_input_focus'      => array( 'fields', __( 'Field border on focus', 'peprodev-ups' ), '--mj-input-focus', 'color', '#28504f' ),
			'f_base'             => array( 'fonts', __( 'Dashboard text', 'peprodev-ups' ), '--mj-fs-base', 'size', '16' ),
			'f_title'            => array( 'fonts', __( 'Page titles', 'peprodev-ups' ), '--mj-fs-title', 'size', '22' ),
			'f_login_title'      => array( 'fonts', __( 'Login form title', 'peprodev-ups' ), '--mj-fs-login-title', 'size', '28' ),
			'f_card_title'       => array( 'fonts', __( 'Card titles', 'peprodev-ups' ), '--mj-fs-card-title', 'size', '18' ),
			'f_desc'             => array( 'fonts', __( 'Descriptions under titles', 'peprodev-ups' ), '--mj-fs-desc', 'size', '14' ),
			'f_btn'              => array( 'fonts', __( 'Buttons', 'peprodev-ups' ), '--mj-fs-btn', 'size', '16' ),
			'f_tab'              => array( 'fonts', __( 'Tabs', 'peprodev-ups' ), '--mj-fs-tab', 'size', '16' ),
			'f_nav'              => array( 'fonts', __( 'Sidebar menu', 'peprodev-ups' ), '--mj-fs-nav', 'size', '16' ),
			'f_table'            => array( 'fonts', __( 'Table text', 'peprodev-ups' ), '--mj-fs-table', 'size', '15' ),
			'f_table_head'       => array( 'fonts', __( 'Table header', 'peprodev-ups' ), '--mj-fs-table-head', 'size', '15' ),
			'f_label'            => array( 'fonts', __( 'Field labels', 'peprodev-ups' ), '--mj-fs-label', 'size', '14' ),
			'f_input'            => array( 'fonts', __( 'Field text', 'peprodev-ups' ), '--mj-fs-input', 'size', '16' ),
			'f_link'             => array( 'fonts', __( 'Login form links', 'peprodev-ups' ), '--mj-fs-link', 'size', '14' ),
		)
	);
}

/**
 * Sections of the design screen.
 *
 * @return array<string,array> section => array( title, description )
 */
function peprodev_ui_design_sections() {
	return apply_filters(
		'peprodev_ui_design_sections',
		array(
			'general' => array( __( 'General', 'peprodev-ups' ), __( 'Main accent color, titles and cards. The accent is the base of the buttons, links and active items below.', 'peprodev-ups' ) ),
			'buttons' => array( __( 'Buttons', 'peprodev-ups' ), __( 'Login/register button, save buttons and the other main buttons of the dashboard.', 'peprodev-ups' ) ),
			'tabs'    => array( __( 'Tabs', 'peprodev-ups' ), __( 'Login / Register tabs, address tabs and verification tabs.', 'peprodev-ups' ) ),
			'links'   => array( __( 'Links', 'peprodev-ups' ), __( 'Text links of the forms and the dashboard.', 'peprodev-ups' ) ),
			'sidebar' => array( __( 'Sidebar menu', 'peprodev-ups' ), __( 'Menu of the user dashboard.', 'peprodev-ups' ) ),
			'table'   => array( __( 'Tables', 'peprodev-ups' ), __( 'Orders, downloads and the other lists of the dashboard.', 'peprodev-ups' ) ),
			'fields'  => array( __( 'Form fields', 'peprodev-ups' ), __( 'Labels and inputs of the login form and the dashboard forms.', 'peprodev-ups' ) ),
			'fonts'   => array( __( 'Font sizes', 'peprodev-ups' ), __( 'Sizes in pixels for the modern login form and dashboard. Empty keeps the built-in size shown in the field.', 'peprodev-ups' ) ),
		)
	);
}

/**
 * The design fields take part in the modern UI settings (saving, sanitizing).
 */
add_filter(
	'peprodev_ui_texts_fields',
	function ( $fields ) {
		foreach ( peprodev_ui_design_fields() as $key => $field ) {
			if ( ! isset( $fields[ $key ] ) ) {
				$fields[ $key ] = array( 'design', $field[1], '', $field[3] );
			} else {
				$fields[ $key ][0] = 'design';
			}
		}
		return $fields;
	},
	5
);

/**
 * Saved color of a setting as #rrggbb, empty when not set.
 *
 * @param string $key Field key.
 * @return string
 */
function peprodev_ui_color_value( $key ) {
	$saved = peprodev_ui_texts_saved();
	$color = isset( $saved[ $key ] ) ? sanitize_hex_color( (string) $saved[ $key ] ) : '';
	return $color ? strtolower( $color ) : '';
}

/**
 * Saved font size of a setting in pixels, 0 when not set.
 *
 * @param string $key Field key.
 * @return int
 */
function peprodev_ui_size_value( $key ) {
	$saved = peprodev_ui_texts_saved();
	$size  = isset( $saved[ $key ] ) ? absint( $saved[ $key ] ) : 0;
	return $size >= 8 && $size <= 72 ? $size : 0;
}

/**
 * Text color readable on a background: dark on light colors, white on dark ones (WCAG luminance).
 *
 * @param string $color #rrggbb or #rgb.
 * @return string
 */
function peprodev_ui_contrast_color( $color ) {
	$hex = ltrim( (string) $color, '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	if ( 6 !== strlen( $hex ) ) {
		return '#ffffff';
	}
	$lum = array_map(
		function ( $c ) {
			$c = hexdec( $c ) / 255;
			return $c <= 0.03928 ? $c / 12.92 : pow( ( $c + 0.055 ) / 1.055, 2.4 );
		},
		str_split( $hex, 2 )
	);
	return ( 0.2126 * $lum[0] + 0.7152 * $lum[1] + 0.0722 * $lum[2] ) > 0.179 ? '#1f1b16' : '#ffffff';
}

/**
 * Custom properties of the saved design settings (without the accent tokens).
 *
 * @return string[] "--name:value" declarations
 */
function peprodev_ui_design_declarations() {
	$out    = array();
	$values = array();
	foreach ( peprodev_ui_design_fields() as $key => $field ) {
		if ( 'color' === $field[3] && '' !== $field[2] ) {
			$values[ $key ] = peprodev_ui_color_value( $key );
		}
	}
	$values += array( 'd_btn_bg' => '', 'd_btn_hover_bg' => '', 'd_btn_text' => '', 'd_btn_hover_text' => '' );
	// a button background without its own hover / text colors gets matching ones
	if ( '' !== $values['d_btn_bg'] ) {
		if ( '' === $values['d_btn_hover_bg'] ) {
			$values['d_btn_hover_bg'] = "color-mix(in srgb,{$values['d_btn_bg']} 82%,#000)";
		}
		if ( '' === $values['d_btn_text'] ) {
			$values['d_btn_text'] = peprodev_ui_contrast_color( $values['d_btn_bg'] );
		}
	}
	if ( '' === $values['d_btn_hover_text'] && '' !== $values['d_btn_text'] ) {
		$values['d_btn_hover_text'] = $values['d_btn_text'];
	}
	$fields = peprodev_ui_design_fields();
	foreach ( $values as $key => $value ) {
		if ( '' !== $value ) {
			$out[] = $fields[ $key ][2] . ':' . $value;
		}
	}
	foreach ( $fields as $key => $field ) {
		if ( 'size' === $field[3] && '' !== $field[2] ) {
			$size = peprodev_ui_size_value( $key );
			if ( $size ) {
				$out[] = $field[2] . ':' . $size . 'px';
			}
		}
	}
	return (array) apply_filters( 'peprodev_ui_design_declarations', $out );
}

/**
 * "Modern UI Design" item of the plugin's admin panel.
 */
add_filter(
	'peprocore_dashboard_nav_menuitems',
	function ( $items ) {
		$items[] = array(
			'title'    => __( 'Modern UI Design', 'peprodev-ups' ),
			'titleW'   => __( 'Colors of the modern login form and user dashboard', 'peprodev-ups' ),
			'icon'     => '<i class="material-icons">palette</i>',
			'link'     => '@uidesign',
			'fn'       => 'peprodev_ui_render_design_screen',
			'id'       => 'peprodev_ui_design',
			'priority' => 4.45,
		);
		return $items;
	},
	12
);

/**
 * Save handler of the design screen (nonce and capability are checked by the panel's AJAX endpoint).
 *
 * @param array $request $_POST.
 */
function peprodev_ui_design_ajax( $request ) {
	if ( ! isset( $request['wparam'] ) || 'peprodev_ui' !== $request['wparam'] || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( 'save_design' === ( $request['lparam'] ?? '' ) ) {
		$input = isset( $_POST['dparam']['modern_ui'] ) && is_array( $_POST['dparam']['modern_ui'] ) ? wp_unslash( $_POST['dparam']['modern_ui'] ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified by the panel endpoint
		$allowed = array_keys( peprodev_ui_design_fields() );
		peprodev_ui_texts_save_input( array_intersect_key( (array) $input, array_flip( $allowed ) ) );
		wp_send_json_success( array( 'msg' => __( 'Settings saved.', 'peprodev-ups' ) ) );
	}
}
add_action( 'peprocore_handle_ajaxrequests', 'peprodev_ui_design_ajax', 20 );

/**
 * The design screen.
 */
function peprodev_ui_render_design_screen() {
	$fields   = peprodev_ui_design_fields();
	$sections = peprodev_ui_design_sections();
	peprodev_ui_enqueue_color_picker();
	peprodev_ui_enqueue_admin_screen_css();
	?>
	<div class="row pd-screen" id="pd-design">
		<div class="col-lg-12 col-md-12">
			<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title"><?php esc_html_e( 'Modern UI Design', 'peprodev-ups' ); ?></h4>
					<p class="card-category"><?php esc_html_e( 'Colors and font sizes of the modern login form and user dashboard. Leave a field empty to keep the built-in design (or the colors of your theme).', 'peprodev-ups' ); ?></p>
				</div>
				<div class="card-body">
					<div class="row">
						<?php foreach ( $sections as $section => $info ) : ?>
							<?php
							$rows = array_filter(
								$fields,
								function ( $f ) use ( $section ) {
									return $f[0] === $section;
								}
							);
							if ( ! $rows ) {
								continue;
							}
							?>
							<div class="col-xl-6 mb-4">
								<div class="pd-design-box">
									<p class="pd-design-title"><?php echo esc_html( $info[0] ); ?></p>
									<p class="pd-design-desc"><?php echo esc_html( $info[1] ); ?></p>
									<?php foreach ( $rows as $key => $field ) : ?>
										<div class="pd-design-row">
											<label for="pd_design_<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field[1] ); ?></label>
											<div>
												<?php if ( 'size' === $field[3] ) : ?>
													<div class="pd-size-field">
														<input type="number" min="8" max="72" step="1" class="form-input" dir="ltr" id="pd_design_<?php echo esc_attr( $key ); ?>" data-ui-key="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( peprodev_ui_size_value( $key ) ? peprodev_ui_size_value( $key ) : '' ); ?>" placeholder="<?php echo esc_attr( $field[4] ); ?>" /> <span>px</span>
													</div>
												<?php else : ?>
													<?php peprodev_ui_render_color_field( $key, 'pd_design_' . $key, false, $field[4] ); ?>
												<?php endif; ?>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="pd-design-actions">
						<button type="button" id="pd-design-save" class="btn btn-primary icn-btn m-0" data-nonce="<?php echo esc_attr( wp_create_nonce( 'peprocorenounce' ) ); ?>"><i class="material-icons">save</i> <?php echo esc_html_x( 'Save Settings', 'login-section', 'peprodev-ups' ); ?></button>
						<button type="button" id="pd-design-clear" class="btn btn-secondary icn-btn m-0"><i class="material-icons">restart_alt</i> <?php esc_html_e( 'Clear all (built-in design)', 'peprodev-ups' ); ?></button>
						<span id="pd-design-status" class="small" role="status" aria-live="polite"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<style>
		#pd-design .pd-design-box{height:100%;padding:16px 18px;border:1px solid rgba(0,0,0,.08);border-radius:10px}
		#pd-design .pd-design-title{margin:0 0 2px;font-weight:700;font-size:1rem}
		#pd-design .pd-design-desc{margin:0 0 12px;color:#888;font-size:.8rem;line-height:1.7}
		#pd-design .pd-design-row{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:8px 0;border-top:1px solid rgba(0,0,0,.06)}
		#pd-design .pd-design-row>label{margin:0;color:inherit;font-weight:500}
		#pd-design .pd-design-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
		#pd-design .pd-color-field .form-input{flex-basis:110px}
		#pd-design .pd-size-field{display:flex;align-items:center;gap:6px}
		#pd-design .pd-size-field .form-input{width:90px;margin:0}
	</style>
	<script type="text/javascript">
		(function ($) {
			function collect() {
				var data = {};
				$("#pd-design [data-ui-key]").each(function () { data[$(this).attr("data-ui-key")] = $(this).val(); });
				return data;
			}
			$(document).on("click", "#pd-design-save", function (e) {
				e.preventDefault();
				var $b = $(this).prop("disabled", true), $s = $("#pd-design-status").css("color", "").text("…");
				$.post(pepc.ajax, { action: "peprodev-ups", integrity: $b.data("nonce"), wparam: "peprodev_ui", lparam: "save_design", dparam: { modern_ui: collect() } })
					.done(function (r) { $s.css("color", r && r.success ? "#158b02" : "#dd3333").text(r && r.data && r.data.msg ? r.data.msg : "error"); })
					.fail(function () { $s.css("color", "#dd3333").text("error"); })
					.always(function () { $b.prop("disabled", false); });
			});
			$(document).on("click", "#pd-design-clear", function (e) {
				e.preventDefault();
				$("#pd-design [data-ui-key]").each(function () { this.value = ""; this.dispatchEvent(new Event("change", { bubbles: true })); });
			});
		})(jQuery);
	</script>
	<?php
}
