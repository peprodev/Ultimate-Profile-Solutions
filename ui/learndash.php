<?php
/**
 * PeproDev UPS modern UI: "LearnDash" settings screen.
 *
 * Learning button and "My courses" texts and links (the "learn" and "courses"
 * groups of the modern UI settings), moved here from the Profile screen.
 *
 * @package PeproDev_UPS
 */

defined( 'ABSPATH' ) || exit;

/**
 * Groups of the modern UI settings shown on this screen.
 *
 * @return string[]
 */
function peprodev_ui_learndash_groups() {
	return array( 'learn', 'courses' );
}

/**
 * "LearnDash" item of the plugin's admin panel.
 */
add_filter(
	'peprocore_dashboard_nav_menuitems',
	function ( $items ) {
		$items[] = array(
			'title'    => __( 'LearnDash', 'peprodev-ups' ),
			'titleW'   => __( 'LearnDash settings', 'peprodev-ups' ),
			'icon'     => '<i class="material-icons">school</i>',
			'link'     => '@learndash',
			'fn'       => 'peprodev_ui_render_learndash_screen',
			'id'       => 'peprodev_ui_learndash',
			'priority' => 4.46,
		);
		return $items;
	},
	12
);

/**
 * Save handler (nonce and capability are checked by the panel's AJAX endpoint).
 *
 * @param array $request $_POST.
 */
function peprodev_ui_learndash_ajax( $request ) {
	if ( ! isset( $request['wparam'] ) || 'peprodev_ui' !== $request['wparam'] || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( 'save_learndash' !== ( $request['lparam'] ?? '' ) ) {
		return;
	}
	$input   = isset( $_POST['dparam']['modern_ui'] ) && is_array( $_POST['dparam']['modern_ui'] ) ? wp_unslash( $_POST['dparam']['modern_ui'] ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified by the panel endpoint
	$allowed = array();
	foreach ( peprodev_ui_texts_fields() as $key => $field ) {
		if ( in_array( $field[0], peprodev_ui_learndash_groups(), true ) ) {
			$allowed[] = $key;
		}
	}
	peprodev_ui_texts_save_input( array_intersect_key( (array) $input, array_flip( $allowed ) ) );
	do_action( 'peprodev_ui_learndash_save', $_POST['dparam'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
	wp_send_json_success( array( 'msg' => __( 'Settings saved.', 'peprodev-ups' ) ) );
}
add_action( 'peprocore_handle_ajaxrequests', 'peprodev_ui_learndash_ajax', 20 );

/**
 * The LearnDash screen.
 */
function peprodev_ui_render_learndash_screen() {
	$ld_active = defined( 'LEARNDASH_LMS_PLUGIN_DIR' ) || function_exists( 'learndash_user_get_enrolled_courses' );
	peprodev_ui_enqueue_admin_screen_css();
	?>
	<div id="pd-learndash" class="pd-screen">
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div class="card">
					<div class="card-header card-header-primary">
						<h4 class="card-title"><?php esc_html_e( 'Learning button and My courses', 'peprodev-ups' ); ?></h4>
						<p class="card-category"><?php esc_html_e( 'Texts and links of the learning button and the My courses views of the user dashboard.', 'peprodev-ups' ); ?></p>
					</div>
					<div class="card-body table-responsive">
						<?php if ( ! $ld_active ) : ?>
							<div class="alert alert-warning"><?php esc_html_e( 'LearnDash is not active. These settings are used when LearnDash is installed and active.', 'peprodev-ups' ); ?></div>
						<?php endif; ?>
						<?php peprodev_ui_render_profile_settings( peprodev_ui_learndash_groups() ); ?>
					</div>
				</div>
			</div>
		</div>
		<?php do_action( 'peprodev_ui_learndash_screen' ); ?>
		<div class="pd-ld-actions">
			<button type="button" id="pd-learndash-save" class="btn btn-primary icn-btn m-0" data-nonce="<?php echo esc_attr( wp_create_nonce( 'peprocorenounce' ) ); ?>"><i class="material-icons">save</i> <?php echo esc_html_x( 'Save Settings', 'login-section', 'peprodev-ups' ); ?></button>
			<span id="pd-learndash-status" class="small" role="status" aria-live="polite"></span>
			<?php echo class_exists( 'PeproDevUPS_WPML' ) ? PeproDevUPS_WPML::admin_link_html() : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
	<style>#pd-learndash .pd-ld-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin:0 0 30px}</style>
	<script type="text/javascript">
		(function ($) {
			$(document).on("click", "#pd-learndash-save", function (e) {
				e.preventDefault();
				var modern_ui = {}, extra = {};
				$("#pd-learndash .peprodev-ui-settings [data-ui-key]").each(function () {
					var el = $(this);
					modern_ui[el.attr("data-ui-key")] = el.is(".btncheckbox") ? (el.attr("data-checked") === "true" ? "1" : "0") : el.val();
				});
				$(document).trigger("pd_learndash_collect", [extra]);
				var $b = $(this).prop("disabled", true), $s = $("#pd-learndash-status").css("color", "").text("…");
				$.post(pepc.ajax, { action: "peprodev-ups", integrity: $b.data("nonce"), wparam: "peprodev_ui", lparam: "save_learndash", dparam: $.extend({ modern_ui: modern_ui }, extra) })
					.done(function (r) { $s.css("color", r && r.success ? "#158b02" : "#dd3333").text(r && r.data && r.data.msg ? r.data.msg : "error"); })
					.fail(function () { $s.css("color", "#dd3333").text("error"); })
					.always(function () { $b.prop("disabled", false); });
			});
		})(jQuery);
	</script>
	<?php
}
