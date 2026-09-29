<?php
/**
 * PeproDev UPS: "Translate & Replace" screen and runtime.
 *
 * 1. Text translation (gettext): changes translatable texts of WordPress,
 *    LearnDash, WooCommerce or any plugin/theme, e.g. "Course" -> "Class".
 * 2. Page text replace (HTML): replaces text in the final HTML of the post
 *    content, the whole front-end page or WooCommerce emails, for texts that do
 *    not pass through translations.
 *
 * Stored in the "peprodev_ups_text_replace" option:
 *   enabled / rules           gettext rules: find, replace, match (whole|part), domain, lang, active
 *   html_enabled / html_rules HTML rules: find, replace, where (content|page|email), active
 *
 * @package PeproDev_UPS
 */

defined( 'ABSPATH' ) || exit;

define( 'PEPRODEV_UPS_TEXT_REPLACE_OPTION', 'peprodev_ups_text_replace' );

/**
 * Saved settings.
 *
 * @return array
 */
function peprodev_ui_text_replace_settings() {
	$opt = get_option( PEPRODEV_UPS_TEXT_REPLACE_OPTION, array() );
	$opt = is_array( $opt ) ? $opt : array();
	return array(
		'enabled'      => ! empty( $opt['enabled'] ),
		'rules'        => isset( $opt['rules'] ) && is_array( $opt['rules'] ) ? $opt['rules'] : array(),
		'html_enabled' => ! empty( $opt['html_enabled'] ),
		'html_rules'   => isset( $opt['html_rules'] ) && is_array( $opt['html_rules'] ) ? $opt['html_rules'] : array(),
	);
}

/**
 * A posted "yes" value.
 *
 * @param mixed $v Value.
 * @return bool
 */
function peprodev_ui_text_replace_bool( $v ) {
	return in_array( (string) $v, array( '1', 'true', 'yes', 'on' ), true );
}

/**
 * Clean gettext rules.
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
			'active'  => ! isset( $rule['active'] ) || peprodev_ui_text_replace_bool( $rule['active'] ),
		);
		if ( count( $out ) >= 500 ) {
			break;
		}
	}
	return $out;
}

/**
 * Clean HTML replace rules.
 *
 * @param mixed $rules Raw rows.
 * @return array
 */
function peprodev_ui_text_replace_sanitize_html_rules( $rules ) {
	$out = array();
	foreach ( (array) $rules as $rule ) {
		if ( ! is_array( $rule ) ) {
			continue;
		}
		$find = isset( $rule['find'] ) ? (string) $rule['find'] : '';
		if ( '' === trim( $find ) ) {
			continue;
		}
		$where = isset( $rule['where'] ) ? (string) $rule['where'] : 'content';
		$out[] = array(
			// markup may be matched as it is in the page; the new text is limited to safe HTML
			'find'    => wp_check_invalid_utf8( $find ),
			'replace' => isset( $rule['replace'] ) ? wp_kses_post( (string) $rule['replace'] ) : '',
			'where'   => in_array( $where, array( 'content', 'page', 'email' ), true ) ? $where : 'content',
			'active'  => ! isset( $rule['active'] ) || peprodev_ui_text_replace_bool( $rule['active'] ),
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
 * gettext runtime: rules grouped once per request so the filters (called thousands
 * of times per page) only do array lookups.
 */
final class PeproDevUPS_Text_Replace {
	/** @var array domain ('' = any) => text => list of array( replace, lang ) */
	private $whole = array();
	/** @var array list of array( find, replace, domain, lang ) */
	private $part = array();

	public function __construct( array $rules ) {
		foreach ( $rules as $rule ) {
			if ( isset( $rule['active'] ) && ! $rule['active'] ) {
				continue;
			}
			if ( 'part' === $rule['match'] ) {
				$this->part[] = array( $rule['find'], $rule['replace'], $rule['domain'], $rule['lang'] );
			} else {
				$this->whole[ $rule['domain'] ][ $rule['find'] ][] = array( $rule['replace'], $rule['lang'] );
			}
		}
		if ( ! $this->whole && ! $this->part ) {
			return;
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

/**
 * HTML runtime: post content, whole front-end page (output buffer) and WooCommerce emails.
 */
final class PeproDevUPS_Html_Replace {
	/** @var array where => array( find => replace ) */
	private $map = array( 'content' => array(), 'page' => array(), 'email' => array() );

	public function __construct( array $rules ) {
		foreach ( $rules as $rule ) {
			if ( ! empty( $rule['active'] ) || ! isset( $rule['active'] ) ) {
				$this->map[ $rule['where'] ][ $rule['find'] ] = $rule['replace'];
			}
		}
		if ( $this->map['content'] ) {
			add_filter( 'the_content', array( $this, 'content' ), 999999 );
		}
		if ( $this->map['page'] ) {
			add_action( 'template_redirect', array( $this, 'start_buffer' ), 999999 );
		}
		if ( $this->map['email'] ) {
			add_filter( 'woocommerce_mail_content', array( $this, 'email' ), 999999 );
		}
	}

	private function swap( $html, $where ) {
		return is_string( $html ) && '' !== $html ? strtr( $html, $this->map[ $where ] ) : $html;
	}
	public function content( $html ) {
		return $this->swap( $html, 'content' );
	}
	public function email( $html ) {
		return $this->swap( $html, 'email' );
	}
	public function start_buffer() {
		if ( is_admin() || wp_doing_ajax() || is_feed() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || is_customize_preview() ) {
			return;
		}
		ob_start(
			function ( $html ) {
				return $this->swap( $html, 'page' );
			}
		);
	}
}

// Rules take effect everywhere except this plugin's own admin panel (original texts stay readable there).
( function () {
	$settings = peprodev_ui_text_replace_settings();
	$in_panel = is_admin() && isset( $_GET['page'] ) && 'peprodev-ups' === $_GET['page']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( $settings['enabled'] && $settings['rules'] && ! $in_panel ) {
		new PeproDevUPS_Text_Replace( $settings['rules'] );
	}
	if ( $settings['html_enabled'] && $settings['html_rules'] ) {
		new PeproDevUPS_Html_Replace( $settings['html_rules'] );
	}
} )();

/**
 * "Translate & Replace" item of the plugin's admin panel.
 */
add_filter(
	'peprocore_dashboard_nav_menuitems',
	function ( $items ) {
		$items[] = array(
			'title'    => __( 'Translate & Replace', 'peprodev-ups' ),
			'titleW'   => __( 'Text translation and replacement', 'peprodev-ups' ),
			'icon'     => '<i class="material-icons">translate</i>',
			'link'     => '@translate',
			'fn'       => 'peprodev_ui_text_replace_screen',
			'id'       => 'peprodev_ui_translate',
			'priority' => 4.47,
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
function peprodev_ui_text_replace_ajax( $request ) {
	if ( ! isset( $request['wparam'] ) || 'peprodev_ui' !== $request['wparam'] || 'save_translate' !== ( $request['lparam'] ?? '' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$data = isset( $_POST['dparam'] ) && is_array( $_POST['dparam'] ) ? wp_unslash( $_POST['dparam'] ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified by the panel endpoint
	// "import": a JSON copy of all settings replaces the tables
	if ( isset( $data['import'] ) && '' !== trim( (string) $data['import'] ) ) {
		$json = json_decode( (string) $data['import'], true );
		if ( ! is_array( $json ) ) {
			wp_send_json_error( array( 'msg' => __( 'The imported data is not valid JSON.', 'peprodev-ups' ) ) );
		}
		$data = $json;
	}
	$settings = array(
		'enabled'      => isset( $data['enabled'] ) && peprodev_ui_text_replace_bool( $data['enabled'] ),
		'rules'        => peprodev_ui_text_replace_sanitize_rules( isset( $data['rules'] ) ? $data['rules'] : array() ),
		'html_enabled' => isset( $data['html_enabled'] ) && peprodev_ui_text_replace_bool( $data['html_enabled'] ),
		'html_rules'   => peprodev_ui_text_replace_sanitize_html_rules( isset( $data['html_rules'] ) ? $data['html_rules'] : array() ),
	);
	update_option( PEPRODEV_UPS_TEXT_REPLACE_OPTION, $settings, true );
	wp_send_json_success(
		array(
			/* translators: 1: number of translation rules, 2: number of page text rules. */
			'msg'    => sprintf( __( 'Saved: %1$s translation rules, %2$s page text rules.', 'peprodev-ups' ), number_format_i18n( count( $settings['rules'] ) ), number_format_i18n( count( $settings['html_rules'] ) ) ),
			'reload' => isset( $data['import'] ),
		)
	);
}
add_action( 'peprocore_handle_ajaxrequests', 'peprodev_ui_text_replace_ajax', 20 );

/**
 * One editable row.
 *
 * @param string $type  "gettext" or "html".
 * @param array  $rule  Rule.
 * @param bool   $multi WPML / Polylang active.
 */
function peprodev_ui_text_replace_row( $type, $rule, $multi ) {
	$rule   = wp_parse_args( $rule, array( 'find' => '', 'replace' => '', 'match' => 'whole', 'domain' => '', 'lang' => '', 'where' => 'content', 'active' => true ) );
	$active = ! empty( $rule['active'] );
	?>
	<tr class="pd-tr-row<?php echo $active ? '' : ' is-off'; ?>">
		<td class="pd-tr-handle" title="<?php esc_attr_e( 'Drag to reorder', 'peprodev-ups' ); ?>"><span class="material-icons">drag_indicator</span></td>
		<td><textarea rows="1" class="form-input" data-tr="find" dir="auto" placeholder="<?php esc_attr_e( 'Text to find', 'peprodev-ups' ); ?>"><?php echo esc_textarea( $rule['find'] ); ?></textarea></td>
		<td><textarea rows="1" class="form-input" data-tr="replace" dir="auto" placeholder="<?php esc_attr_e( 'Replace with', 'peprodev-ups' ); ?>"><?php echo esc_textarea( $rule['replace'] ); ?></textarea></td>
		<?php if ( 'gettext' === $type ) : ?>
			<td>
				<select class="form-input" data-tr="match">
					<option value="whole" <?php selected( $rule['match'], 'whole' ); ?>><?php esc_html_e( 'Whole text', 'peprodev-ups' ); ?></option>
					<option value="part" <?php selected( $rule['match'], 'part' ); ?>><?php esc_html_e( 'Part of text', 'peprodev-ups' ); ?></option>
				</select>
			</td>
			<td><input type="text" class="form-input" data-tr="domain" dir="ltr" list="pd-tr-domains" placeholder="<?php esc_attr_e( 'all', 'peprodev-ups' ); ?>" value="<?php echo esc_attr( $rule['domain'] ); ?>" /></td>
			<?php if ( $multi ) : ?>
				<td><input type="text" class="form-input" data-tr="lang" dir="ltr" maxlength="10" placeholder="<?php esc_attr_e( 'all', 'peprodev-ups' ); ?>" value="<?php echo esc_attr( $rule['lang'] ); ?>" /></td>
			<?php endif; ?>
		<?php else : ?>
			<td>
				<select class="form-input" data-tr="where">
					<option value="content" <?php selected( $rule['where'], 'content' ); ?>><?php esc_html_e( 'Post content', 'peprodev-ups' ); ?></option>
					<option value="page" <?php selected( $rule['where'], 'page' ); ?>><?php esc_html_e( 'Whole page', 'peprodev-ups' ); ?></option>
					<option value="email" <?php selected( $rule['where'], 'email' ); ?>><?php esc_html_e( 'WooCommerce emails', 'peprodev-ups' ); ?></option>
				</select>
			</td>
		<?php endif; ?>
		<td class="pd-tr-cell-active">
			<input type="checkbox" class="form-checkbox iostoggle" data-tr="active" <?php checked( $active ); ?> title="<?php esc_attr_e( 'Active', 'peprodev-ups' ); ?>" aria-label="<?php esc_attr_e( 'Active', 'peprodev-ups' ); ?>" />
		</td>
		<td class="pd-tr-cell-actions">
			<a href="#" class="pd-icon-btn pd-tr-copy" title="<?php echo esc_attr_x( 'Duplicate', 'field-actions', 'peprodev-ups' ); ?>"><span class="material-icons">content_copy</span></a>
			<a href="#" class="pd-icon-btn pd-icon-danger pd-tr-remove" title="<?php echo esc_attr_x( 'Delete', 'field-actions', 'peprodev-ups' ); ?>"><span class="material-icons">delete</span></a>
		</td>
	</tr>
	<?php
}

/**
 * One rules table (gettext or html).
 *
 * @param string $type  "gettext" or "html".
 * @param array  $rules Rules.
 * @param bool   $multi WPML / Polylang active.
 */
function peprodev_ui_text_replace_table( $type, $rules, $multi ) {
	?>
	<div class="pd-tr-tools">
		<input type="search" class="form-input pd-tr-search" placeholder="<?php esc_attr_e( 'Search rules ...', 'peprodev-ups' ); ?>" />
		<span class="pd-tr-count small text-muted"></span>
	</div>
	<div class="table-responsive">
		<table class="table pd-tr-table" data-type="<?php echo esc_attr( $type ); ?>">
			<thead>
				<tr>
					<th class="pd-tr-handle"></th>
					<th><?php esc_html_e( 'Text to find', 'peprodev-ups' ); ?></th>
					<th><?php esc_html_e( 'Replace with', 'peprodev-ups' ); ?></th>
					<?php if ( 'gettext' === $type ) : ?>
						<th><?php esc_html_e( 'Match', 'peprodev-ups' ); ?></th>
						<th><?php esc_html_e( 'Text domain', 'peprodev-ups' ); ?></th>
						<?php if ( $multi ) : ?><th><?php esc_html_e( 'Language', 'peprodev-ups' ); ?></th><?php endif; ?>
					<?php else : ?>
						<th><?php esc_html_e( 'Where', 'peprodev-ups' ); ?></th>
					<?php endif; ?>
					<th><?php esc_html_e( 'Active', 'peprodev-ups' ); ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $rules as $rule ) : ?>
					<?php peprodev_ui_text_replace_row( $type, $rule, $multi ); ?>
				<?php endforeach; ?>
			</tbody>
		</table>
		<template class="pd-tr-template"><?php peprodev_ui_text_replace_row( $type, array( 'domain' => 'gettext' === $type ? '' : '' ), $multi ); ?></template>
		<p class="pd-tr-empty text-muted"><?php esc_html_e( 'No rules yet. Click "Add rule" to create one.', 'peprodev-ups' ); ?></p>
	</div>
	<button type="button" class="btn btn-sm btn-info m-0 pd-tr-add"><i class="material-icons">add_circle</i> <?php esc_html_e( 'Add rule', 'peprodev-ups' ); ?></button>
	<?php
}

/**
 * The "Translate & Replace" screen.
 */
function peprodev_ui_text_replace_screen() {
	$settings = peprodev_ui_text_replace_settings();
	$multi    = defined( 'ICL_SITEPRESS_VERSION' ) || function_exists( 'pll_current_language' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	peprodev_ui_enqueue_admin_screen_css();
	?>
	<div id="pd-text-replace" class="pd-screen" data-nonce="<?php echo esc_attr( wp_create_nonce( 'peprocorenounce' ) ); ?>">
		<div class="card">
			<div class="card-header card-header-primary">
				<h4 class="card-title"><?php esc_html_e( 'Translate & Replace', 'peprodev-ups' ); ?></h4>
				<p class="card-category"><?php esc_html_e( 'Change texts of LearnDash, WooCommerce, WordPress or any plugin and theme without editing translation files, e.g. "Course" to "Class".', 'peprodev-ups' ); ?></p>
			</div>
			<div class="card-body">
				<div class="pd-tr-tabs" role="tablist">
					<button type="button" role="tab" class="active" data-pane="gettext"><span class="material-icons">translate</span> <?php esc_html_e( 'Text translation', 'peprodev-ups' ); ?> <span class="pd-tr-badge" data-for="gettext"></span></button>
					<button type="button" role="tab" data-pane="html"><span class="material-icons">find_replace</span> <?php esc_html_e( 'Page text replace', 'peprodev-ups' ); ?> <span class="pd-tr-badge" data-for="html"></span></button>
					<button type="button" role="tab" data-pane="io"><span class="material-icons">import_export</span> <?php esc_html_e( 'Import / Export', 'peprodev-ups' ); ?></button>
					<a class="pd-tr-home" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'View site', 'peprodev-ups' ); ?> &#8599;</a>
				</div>

				<div class="pd-tr-pane active" data-pane="gettext">
					<label class="pd-tr-switch">
						<input autocomplete="off" type="checkbox" class="form-checkbox iostoggle" id="pd-tr-enabled" <?php checked( $settings['enabled'] ); ?> />
						<span><?php esc_html_e( 'Enable text translation', 'peprodev-ups' ); ?></span>
					</label>
					<ul class="pd-tr-help">
						<li><?php esc_html_e( 'Works on translatable texts (the gettext filter): menus, buttons, labels and messages of WordPress, plugins and themes.', 'peprodev-ups' ); ?></li>
						<li><?php esc_html_e( 'Whole text: the text must be exactly equal to "Text to find", in English (original) or as it is shown on the site (translated).', 'peprodev-ups' ); ?></li>
						<li><?php esc_html_e( 'Part of text: every occurrence of "Text to find" inside the shown text is replaced.', 'peprodev-ups' ); ?></li>
						<li><?php esc_html_e( 'Text domain limits the rule to one plugin or theme: learndash, woocommerce, peprodev-ups, default (WordPress). Empty = all.', 'peprodev-ups' ); ?></li>
					</ul>
					<?php peprodev_ui_text_replace_table( 'gettext', $settings['rules'], $multi ); ?>
				</div>

				<div class="pd-tr-pane" data-pane="html">
					<label class="pd-tr-switch">
						<input autocomplete="off" type="checkbox" class="form-checkbox iostoggle" id="pd-tr-html-enabled" <?php checked( $settings['html_enabled'] ); ?> />
						<span><?php esc_html_e( 'Enable page text replace', 'peprodev-ups' ); ?></span>
					</label>
					<ul class="pd-tr-help">
						<li><?php esc_html_e( 'For texts that are not translatable: written in the content, printed by a theme or builder, or in email templates. The text is matched in the HTML exactly as it is (case-sensitive).', 'peprodev-ups' ); ?></li>
						<li><?php esc_html_e( 'Post content: only the content of posts and pages. Whole page: the full HTML of front-end pages (header, footer, widgets). WooCommerce emails: the HTML of WooCommerce emails.', 'peprodev-ups' ); ?></li>
						<li><?php esc_html_e( 'Whole page reads the full page in memory; use it only when the other options cannot reach the text.', 'peprodev-ups' ); ?></li>
					</ul>
					<?php peprodev_ui_text_replace_table( 'html', $settings['html_rules'], $multi ); ?>
				</div>

				<div class="pd-tr-pane" data-pane="io">
					<p class="pd-tr-help-p"><?php esc_html_e( 'Copy this JSON to move all rules to another site, or paste a copy here and click Import. Import replaces the current rules and settings of both tables.', 'peprodev-ups' ); ?></p>
					<textarea id="pd-tr-json" class="form-input" rows="10" dir="ltr" spellcheck="false"><?php echo esc_textarea( wp_json_encode( $settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) ); ?></textarea>
					<div class="pd-tr-io-actions">
						<button type="button" class="btn btn-sm btn-info m-0" id="pd-tr-copy-json"><i class="material-icons">content_copy</i> <?php esc_html_e( 'Copy', 'peprodev-ups' ); ?></button>
						<button type="button" class="btn btn-sm btn-danger m-0" id="pd-tr-import"><i class="material-icons">file_upload</i> <?php esc_html_e( 'Import (replace current rules)', 'peprodev-ups' ); ?></button>
					</div>
				</div>

				<div class="pd-tr-save">
					<button type="button" class="btn btn-primary icn-btn m-0" id="pd-tr-save"><i class="material-icons">save</i> <?php echo esc_html_x( 'Save Settings', 'login-section', 'peprodev-ups' ); ?></button>
					<span id="pd-tr-status" class="small" role="status" aria-live="polite"></span>
				</div>
				<datalist id="pd-tr-domains"><option value="learndash"></option><option value="woocommerce"></option><option value="peprodev-ups"></option><option value="default"></option></datalist>
			</div>
		</div>
	</div>
	<style>
		#pd-text-replace .pd-tr-tabs{display:flex;flex-wrap:wrap;align-items:center;gap:4px;margin:0 0 18px;padding:4px;border-radius:10px;background:rgba(0,0,0,.05)}
		#pd-text-replace .pd-tr-tabs button{display:inline-flex;align-items:center;gap:6px;border:0;background:transparent;padding:7px 14px;border-radius:8px;font:inherit;font-size:.9rem;color:inherit;cursor:pointer}
		#pd-text-replace .pd-tr-tabs button .material-icons{font-size:18px;opacity:.7}
		#pd-text-replace .pd-tr-tabs button.active{background:#fff;box-shadow:0 1px 3px rgba(0,0,0,.15);font-weight:600}
		#pd-text-replace .pd-tr-home{margin-inline-start:auto;padding:0 10px;font-size:.85rem}
		#pd-text-replace .pd-tr-badge:not(:empty){display:inline-block;min-width:20px;padding:0 6px;border-radius:99px;background:rgba(0,0,0,.08);font-size:.75rem;line-height:20px;text-align:center}
		#pd-text-replace .pd-tr-pane{display:none}
		#pd-text-replace .pd-tr-pane.active{display:block}
		#pd-text-replace .pd-tr-switch{display:inline-flex;align-items:center;gap:10px;margin:0 0 10px;font-weight:600;color:inherit;cursor:pointer}
		#pd-text-replace .pd-tr-help{margin:0 0 14px;padding-inline-start:18px;color:#888;font-size:.8rem;line-height:1.9}
		#pd-text-replace .pd-tr-help-p{color:#888;font-size:.85rem}
		#pd-text-replace .pd-tr-tools{display:flex;align-items:center;gap:12px;margin:0 0 8px}
		#pd-text-replace .pd-tr-tools .pd-tr-search{max-width:280px;margin:0}
		#pd-text-replace .pd-tr-table{margin:0}
		#pd-text-replace .pd-tr-table th{font-size:.8rem;color:#888;font-weight:600;white-space:nowrap}
		#pd-text-replace .pd-tr-table td{vertical-align:middle;padding:6px}
		#pd-text-replace .pd-tr-table .form-input{margin:0;min-width:110px}
		#pd-text-replace .pd-tr-table textarea.form-input{min-width:180px;min-height:42px;resize:vertical;line-height:1.6}
		#pd-text-replace .pd-tr-handle{width:28px;cursor:move;color:#aaa}
		#pd-text-replace .pd-tr-cell-active{width:70px;text-align:center}
		#pd-text-replace .pd-tr-cell-actions{width:80px;white-space:nowrap}
		#pd-text-replace .pd-tr-row.is-off td:not(.pd-tr-cell-active):not(.pd-tr-cell-actions){opacity:.45}
		#pd-text-replace .pd-tr-row.ui-sortable-helper{background:#fff;box-shadow:0 6px 18px rgba(0,0,0,.15)}
		#pd-text-replace .pd-tr-empty{display:none;margin:10px 0}
		#pd-text-replace .pd-tr-save{display:flex;align-items:center;gap:12px;margin-top:24px;padding-top:16px;border-top:1px solid rgba(0,0,0,.08)}
		#pd-text-replace .pd-tr-io-actions{display:flex;gap:8px;margin-top:10px}
		#pd-text-replace #pd-tr-json{font-family:SFMono-Regular,Menlo,Consolas,monospace;font-size:.8rem}
	</style>
	<script type="text/javascript">
		(function ($) {
			var $box = $("#pd-text-replace");
			function refresh() {
				$box.find(".pd-tr-table").each(function () {
					var $t = $(this), n = $t.find("tbody .pd-tr-row").length, on = $t.find("tbody .pd-tr-row:not(.is-off)").length;
					$t.closest(".pd-tr-pane").find(".pd-tr-empty").toggle(0 === n);
					$t.closest(".pd-tr-pane").find(".pd-tr-count").text(n ? on + " / " + n : "");
					$box.find('.pd-tr-badge[data-for="' + $t.data("type") + '"]').text(n ? n : "");
				});
			}
			function rows($pane) {
				var out = [];
				$pane.find("tbody .pd-tr-row").each(function () {
					var r = {};
					$(this).find("[data-tr]").each(function () {
						var $f = $(this);
						r[$f.attr("data-tr")] = $f.is(":checkbox") ? ($f.prop("checked") ? "1" : "0") : $f.val();
					});
					if ($.trim(r.find || "") !== "") { out.push(r); }
				});
				return out;
			}
			function data() {
				return {
					enabled: $("#pd-tr-enabled").prop("checked") ? "1" : "0",
					rules: rows($box.find('.pd-tr-pane[data-pane="gettext"]')),
					html_enabled: $("#pd-tr-html-enabled").prop("checked") ? "1" : "0",
					html_rules: rows($box.find('.pd-tr-pane[data-pane="html"]'))
				};
			}
			function save(payload) {
				var $b = $("#pd-tr-save").prop("disabled", true), $s = $("#pd-tr-status").css("color", "").text("…");
				return $.post(pepc.ajax, { action: "peprodev-ups", integrity: $box.data("nonce"), wparam: "peprodev_ui", lparam: "save_translate", dparam: payload })
					.done(function (r) {
						$s.css("color", r && r.success ? "#158b02" : "#dd3333").text(r && r.data && r.data.msg ? r.data.msg : "error");
						if (r && r.success && r.data.reload) { window.location.reload(); }
						if (r && r.success) { $("#pd-tr-json").val(JSON.stringify(data(), null, 2)); }
					})
					.fail(function () { $s.css("color", "#dd3333").text("error"); })
					.always(function () { $b.prop("disabled", false); });
			}
			$box.on("click", ".pd-tr-tabs button", function (e) {
				e.preventDefault();
				var pane = $(this).data("pane");
				$(this).addClass("active").siblings("button").removeClass("active");
				$box.find(".pd-tr-pane").removeClass("active").filter('[data-pane="' + pane + '"]').addClass("active");
				if ("io" === pane) { $("#pd-tr-json").val(JSON.stringify(data(), null, 2)); }
			});
			$box.on("click", ".pd-tr-add", function (e) {
				e.preventDefault();
				var $pane = $(this).closest(".pd-tr-pane"), $row = $($pane.find("template.pd-tr-template").html());
				$pane.find("tbody").append($row);
				$row.find("textarea").first().trigger("focus");
				refresh();
			});
			$box.on("click", ".pd-tr-copy", function (e) {
				e.preventDefault();
				var $row = $(this).closest(".pd-tr-row"), $copy = $row.clone();
				$copy.find("select").each(function (i) { $(this).val($row.find("select").eq(i).val()); });
				$row.after($copy);
				refresh();
			});
			$box.on("click", ".pd-tr-remove", function (e) {
				e.preventDefault();
				$(this).closest(".pd-tr-row").remove();
				refresh();
			});
			$box.on("change", '[data-tr="active"]', function () {
				$(this).closest(".pd-tr-row").toggleClass("is-off", !$(this).prop("checked"));
				refresh();
			});
			$box.on("input", ".pd-tr-search", function () {
				var q = $.trim($(this).val()).toLowerCase();
				$(this).closest(".pd-tr-pane").find("tbody .pd-tr-row").each(function () {
					var text = $(this).find("textarea, input[type=text]").map(function () { return this.value; }).get().join(" ").toLowerCase();
					$(this).toggle("" === q || text.indexOf(q) !== -1);
				});
			});
			$box.on("click", "#pd-tr-save", function (e) { e.preventDefault(); save(data()); });
			$box.on("click", "#pd-tr-copy-json", function (e) {
				e.preventDefault();
				var el = document.getElementById("pd-tr-json");
				el.select();
				if (navigator.clipboard) { navigator.clipboard.writeText(el.value); } else { document.execCommand("copy"); }
			});
			$box.on("click", "#pd-tr-import", function (e) {
				e.preventDefault();
				var json = $("#pd-tr-json").val();
				try { JSON.parse(json); } catch (err) { $("#pd-tr-status").css("color", "#dd3333").text(err.message); return; }
				if (!window.confirm(<?php echo wp_json_encode( __( 'Replace all current rules with the imported data?', 'peprodev-ups' ) ); ?>)) { return; }
				save({ import: json });
			});
			if ($.fn.sortable) {
				$box.find(".pd-tr-table tbody").sortable({ handle: ".pd-tr-handle", axis: "y", helper: function (e, tr) { tr.children().each(function () { $(this).width($(this).width()); }); return tr; } });
			}
			refresh();
		})(jQuery);
	</script>
	<?php
}
