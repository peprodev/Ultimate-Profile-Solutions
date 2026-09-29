<?php
/**
 * PeproDev UPS: text replacement (gettext).
 *
 * Replaces translatable texts of WordPress, LearnDash, WooCommerce or any other
 * plugin/theme, e.g. "Course" -> "Class". Rules are edited on the LearnDash
 * screen of the admin panel and stored in the "peprodev_ups_text_replace" option:
 *
 *   array( 'enabled' => bool, 'rules' => array( array(
 *     'find'    => text to find,
 *     'replace' => new text,
 *     'match'   => 'whole' (the whole text is equal to "find", original or translated) | 'part' (replace inside the shown text),
 *     'domain'  => text domain, empty = all (e.g. learndash, woocommerce),
 *     'lang'    => language code, empty = all (WPML / Polylang),
 *   ) ) )
 *
 * @package PeproDev_UPS
 */

defined( 'ABSPATH' ) || exit;

define( 'PEPRODEV_UPS_TEXT_REPLACE_OPTION', 'peprodev_ups_text_replace' );

/**
 * Saved settings.
 *
 * @return array{enabled:bool,rules:array}
 */
function peprodev_ui_text_replace_settings() {
	$opt = get_option( PEPRODEV_UPS_TEXT_REPLACE_OPTION, array() );
	$opt = is_array( $opt ) ? $opt : array();
	return array(
		'enabled' => ! empty( $opt['enabled'] ),
		'rules'   => isset( $opt['rules'] ) && is_array( $opt['rules'] ) ? $opt['rules'] : array(),
	);
}

/**
 * Clean rules posted by the settings screen.
 *
 * @param mixed $rules Raw rows.
 * @return array
 */
function peprodev_ui_text_replace_sanitize_rules( $rules ) {
	$out = array();
	foreach ( (array) $rules as $rule ) {
		if ( ! is_array( $rule ) ) {
			continue;
		}
		$find = isset( $rule['find'] ) ? trim( wp_kses_post( (string) $rule['find'] ) ) : '';
		if ( '' === $find ) {
			continue;
		}
		$out[] = array(
			'find'    => $find,
			'replace' => isset( $rule['replace'] ) ? wp_kses_post( (string) $rule['replace'] ) : '',
			'match'   => isset( $rule['match'] ) && 'part' === $rule['match'] ? 'part' : 'whole',
			'domain'  => isset( $rule['domain'] ) ? sanitize_key( (string) $rule['domain'] ) : '',
			'lang'    => isset( $rule['lang'] ) ? sanitize_key( (string) $rule['lang'] ) : '',
		);
		if ( count( $out ) >= 500 ) {
			break;
		}
	}
	return $out;
}

/**
 * Current language code of WPML / Polylang, empty without a multilingual plugin.
 *
 * @return string
 */
function peprodev_ui_text_replace_lang() {
	// not cached: the language is known only after WPML / Polylang set it up (only rules with a language call this)
	$lang = (string) apply_filters( 'wpml_current_language', null );
	if ( '' === $lang && function_exists( 'pll_current_language' ) ) {
		$lang = (string) pll_current_language();
	}
	return $lang;
}

/**
 * Runtime of the replacement: rules grouped once per request so the gettext
 * filters (called thousands of times) only do array lookups.
 */
final class PeproDevUPS_Text_Replace {
	/** @var array domain ('' = any) => text => array( replace, lang ) */
	private $whole = array();
	/** @var array list of array( find, replace, domain, lang ) */
	private $part = array();

	public function __construct( array $rules ) {
		foreach ( $rules as $rule ) {
			if ( 'part' === $rule['match'] ) {
				$this->part[] = array( $rule['find'], $rule['replace'], $rule['domain'], $rule['lang'] );
			} else {
				$this->whole[ $rule['domain'] ][ $rule['find'] ][] = array( $rule['replace'], $rule['lang'] );
			}
		}
		add_filter( 'gettext', array( $this, 'gettext' ), 999999, 3 );
		add_filter( 'gettext_with_context', array( $this, 'gettext_with_context' ), 999999, 4 );
		add_filter( 'ngettext', array( $this, 'ngettext' ), 999999, 5 );
		add_filter( 'ngettext_with_context', array( $this, 'ngettext_with_context' ), 999999, 6 );
	}

	public function gettext( $translation, $text, $domain ) {
		return $this->apply( $translation, array( $text ), $domain );
	}
	public function gettext_with_context( $translation, $text, $context, $domain ) {
		return $this->apply( $translation, array( $text ), $domain );
	}
	public function ngettext( $translation, $single, $plural, $number, $domain ) {
		return $this->apply( $translation, array( $single, $plural ), $domain );
	}
	public function ngettext_with_context( $translation, $single, $plural, $number, $context, $domain ) {
		return $this->apply( $translation, array( $single, $plural ), $domain );
	}

	private function lang_ok( $lang ) {
		return '' === $lang || peprodev_ui_text_replace_lang() === $lang;
	}

	private function apply( $translation, array $originals, $domain ) {
		if ( ! is_string( $translation ) ) {
			return $translation;
		}
		// whole text: the original (English) text or the shown translation
		foreach ( array( (string) $domain, '' ) as $d ) {
			if ( empty( $this->whole[ $d ] ) ) {
				continue;
			}
			foreach ( array_merge( $originals, array( $translation ) ) as $candidate ) {
				if ( isset( $this->whole[ $d ][ $candidate ] ) ) {
					foreach ( $this->whole[ $d ][ $candidate ] as $hit ) {
						if ( $this->lang_ok( $hit[1] ) ) {
							$translation = $hit[0];
							break 3;
						}
					}
				}
			}
		}
		// part of the shown text
		foreach ( $this->part as $rule ) {
			if ( '' !== $rule[2] && $rule[2] !== $domain ) {
				continue;
			}
			if ( false !== strpos( $translation, $rule[0] ) && $this->lang_ok( $rule[3] ) ) {
				$translation = str_replace( $rule[0], $rule[1], $translation );
			}
		}
		return $translation;
	}
}

// Rules take effect everywhere except the plugin's own settings screen (the original texts stay readable there).
( function () {
	$settings = peprodev_ui_text_replace_settings();
	if ( ! $settings['enabled'] || empty( $settings['rules'] ) ) {
		return;
	}
	if ( is_admin() && isset( $_GET['page'], $_GET['section'] ) && 'peprodev-ups' === $_GET['page'] && 'learndash' === $_GET['section'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}
	new PeproDevUPS_Text_Replace( $settings['rules'] );
} )();

/**
 * Save the rules posted by the LearnDash screen.
 *
 * @param array $dparam Posted data.
 */
function peprodev_ui_text_replace_save( $dparam ) {
	if ( ! isset( $dparam['text_replace'] ) || ! is_array( $dparam['text_replace'] ) ) {
		return;
	}
	$data = wp_unslash( $dparam['text_replace'] );
	update_option(
		PEPRODEV_UPS_TEXT_REPLACE_OPTION,
		array(
			'enabled' => ! empty( $data['enabled'] ) && in_array( (string) $data['enabled'], array( '1', 'true', 'yes' ), true ),
			'rules'   => peprodev_ui_text_replace_sanitize_rules( isset( $data['rules'] ) ? $data['rules'] : array() ),
		),
		true
	);
}
add_action( 'peprodev_ui_learndash_save', 'peprodev_ui_text_replace_save' );

/**
 * Settings box on the LearnDash screen.
 */
function peprodev_ui_text_replace_screen() {
	$settings = peprodev_ui_text_replace_settings();
	$multi    = defined( 'ICL_SITEPRESS_VERSION' ) || function_exists( 'pll_current_language' );
	$rules    = $settings['rules'] ? $settings['rules'] : array( array( 'find' => '', 'replace' => '', 'match' => 'whole', 'domain' => 'learndash', 'lang' => '' ) );
	?>
	<div class="row" id="pd-text-replace">
		<div class="col-lg-12 col-md-12">
			<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title"><?php esc_html_e( 'Text replacement', 'peprodev-ups' ); ?></h4>
					<p class="card-category"><?php esc_html_e( 'Change texts of LearnDash, WooCommerce, WordPress or any plugin and theme without editing translation files, e.g. "Course" to "Class".', 'peprodev-ups' ); ?></p>
				</div>
				<div class="card-body">
					<label class="w-100 row align-items-center m-0 mb-3">
						<input autocomplete="off" type="checkbox" class="form-checkbox iostoggle mr-2" id="pd-tr-enabled" <?php checked( $settings['enabled'] ); ?> />
						<?php esc_html_e( 'Enable text replacement', 'peprodev-ups' ); ?>
					</label>
					<ul class="small text-muted pd-tr-help">
						<li><?php esc_html_e( 'Whole text: the text must be exactly equal to "Text to find", in English (original) or as it is shown on the site (translated).', 'peprodev-ups' ); ?></li>
						<li><?php esc_html_e( 'Part of text: every occurrence of "Text to find" inside the shown text is replaced.', 'peprodev-ups' ); ?></li>
						<li><?php esc_html_e( 'Text domain limits the rule to one plugin or theme: learndash, woocommerce, peprodev-ups, default (WordPress). Empty = all.', 'peprodev-ups' ); ?></li>
					</ul>
					<div class="table-responsive">
						<table class="table pd-tr-table">
							<thead>
								<tr>
									<th><?php esc_html_e( 'Text to find', 'peprodev-ups' ); ?></th>
									<th><?php esc_html_e( 'Replace with', 'peprodev-ups' ); ?></th>
									<th><?php esc_html_e( 'Match', 'peprodev-ups' ); ?></th>
									<th><?php esc_html_e( 'Text domain', 'peprodev-ups' ); ?></th>
									<?php if ( $multi ) : ?><th><?php esc_html_e( 'Language', 'peprodev-ups' ); ?></th><?php endif; ?>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $rules as $rule ) : ?>
									<tr class="pd-tr-row">
										<td><input type="text" class="form-input" data-tr="find" dir="auto" value="<?php echo esc_attr( $rule['find'] ); ?>" /></td>
										<td><input type="text" class="form-input" data-tr="replace" dir="auto" value="<?php echo esc_attr( $rule['replace'] ); ?>" /></td>
										<td>
											<select class="form-input" data-tr="match">
												<option value="whole" <?php selected( $rule['match'], 'whole' ); ?>><?php esc_html_e( 'Whole text', 'peprodev-ups' ); ?></option>
												<option value="part" <?php selected( $rule['match'], 'part' ); ?>><?php esc_html_e( 'Part of text', 'peprodev-ups' ); ?></option>
											</select>
										</td>
										<td><input type="text" class="form-input" data-tr="domain" dir="ltr" list="pd-tr-domains" placeholder="<?php esc_attr_e( 'all', 'peprodev-ups' ); ?>" value="<?php echo esc_attr( $rule['domain'] ); ?>" /></td>
										<?php if ( $multi ) : ?><td><input type="text" class="form-input" data-tr="lang" dir="ltr" maxlength="10" placeholder="<?php esc_attr_e( 'all', 'peprodev-ups' ); ?>" value="<?php echo esc_attr( $rule['lang'] ); ?>" /></td><?php endif; ?>
										<td><a href="#" class="pd-icon-btn pd-icon-danger pd-tr-remove" title="<?php echo esc_attr_x( 'Delete', 'field-actions', 'peprodev-ups' ); ?>"><span class="material-icons">delete</span></a></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<datalist id="pd-tr-domains"><option value="learndash"></option><option value="woocommerce"></option><option value="peprodev-ups"></option><option value="default"></option></datalist>
					</div>
					<button type="button" class="btn btn-sm btn-info m-0" id="pd-tr-add"><i class="material-icons">add_circle</i> <?php esc_html_e( 'Add rule', 'peprodev-ups' ); ?></button>
				</div>
			</div>
		</div>
	</div>
	<style>
		#pd-text-replace .pd-tr-table td{vertical-align:middle;padding:6px}
		#pd-text-replace .pd-tr-table .form-input{margin:0;min-width:120px}
		#pd-text-replace .pd-tr-help{margin:0 0 12px;padding-inline-start:18px;line-height:1.9}
	</style>
	<script type="text/javascript">
		(function ($) {
			var $box = $("#pd-text-replace");
			$box.on("click", "#pd-tr-add", function (e) {
				e.preventDefault();
				var $row = $box.find(".pd-tr-row").first().clone();
				$row.find("input").val("");
				$row.find("select").val("whole");
				$box.find(".pd-tr-table tbody").append($row);
				$row.find("input").first().focus();
			});
			$box.on("click", ".pd-tr-remove", function (e) {
				e.preventDefault();
				var $rows = $box.find(".pd-tr-row");
				if ($rows.length > 1) { $(this).closest(".pd-tr-row").remove(); }
				else { $rows.find("input").val(""); }
			});
			$(document).on("pd_learndash_collect", function (e, extra) {
				var rules = [];
				$box.find(".pd-tr-row").each(function () {
					var r = {};
					$(this).find("[data-tr]").each(function () { r[$(this).attr("data-tr")] = $(this).val(); });
					if ($.trim(r.find || "") !== "") { rules.push(r); }
				});
				extra.text_replace = { enabled: $("#pd-tr-enabled").prop("checked") ? "1" : "0", rules: rules };
			});
		})(jQuery);
	</script>
	<?php
}
add_action( 'peprodev_ui_learndash_screen', 'peprodev_ui_text_replace_screen' );
