<?php
/**
 * WPML String Translation (and Polylang) support for admin-configurable, visitor-facing texts.
 * Every call is a no-op pass-through when no translation plugin is active.
 */
defined("ABSPATH") or die("PeproDev Ultimate Profile Solutions :: Unauthorized Access!");

if (!class_exists("PeproDevUPS_WPML")) {
  class PeproDevUPS_WPML {
    const CONTEXT     = "peprodev-ups";
    const OPTION      = "peprodev_ups_profile";
    const HASH_OPTION = "peprodev_ups_wpml_strings_hash";
    protected static $plugin_file     = "";
    protected static $builder_strings = null;

    public static function init($plugin_file = "") {
      self::$plugin_file = $plugin_file;
      add_action("admin_init", array(__CLASS__, "maybe_register_all"), 99);
      if (!empty($plugin_file)) {
        add_filter("plugin_action_links_" . plugin_basename($plugin_file), array(__CLASS__, "plugin_action_links"));
      }
    }

    #region detection
    /** WPML String Translation is active */
    public static function is_st_active() {
      return defined("WPML_ST_VERSION") || class_exists("WPML_String_Translation");
    }
    /** Polylang (or Polylang Pro) string translation is available */
    public static function is_pll_active() {
      return function_exists("pll_register_string") && function_exists("pll__");
    }
    /** Any supported string translation plugin is active */
    public static function is_active() {
      return self::is_st_active() || self::is_pll_active() || has_filter("wpml_translate_single_string");
    }
    public static function st_url() {
      return admin_url("admin.php?page=wpml-string-translation/menu/string-translation.php&context=" . self::CONTEXT);
    }
    #endregion

    #region register / translate
    /**
     * Register a single string for translation.
     * @param string $name  stable, human readable string name
     * @param string $value original (default language) value
     */
    public static function register($name, $value) {
      if (!is_string($value) || "" === trim($value) || empty($name)) return;
      do_action("wpml_register_single_string", self::CONTEXT, $name, $value);
      if (self::is_pll_active()) {
        pll_register_string($name, $value, self::CONTEXT, (false !== strpos($value, "\n") || false !== strpos($value, "<")));
      }
    }
    /**
     * Translate a registered string into the current language (returns $value unchanged when nothing is active).
     * @param string $name  string name used on register()
     * @param string $value original value
     * @return string
     */
    public static function translate($name, $value) {
      if (!is_string($value) || "" === trim($value) || empty($name)) return $value;
      // current language is only reliable once WordPress init has started
      if (!did_action("init")) return $value;
      if (has_filter("wpml_translate_single_string")) {
        return apply_filters("wpml_translate_single_string", $value, self::CONTEXT, $name);
      }
      if (self::is_pll_active()) return pll__($value);
      return $value;
    }
    #endregion

    #region strings collection
    /**
     * Raw plugin settings array, read in the default language so WPML admin-texts
     * (wpml-config.xml) never hand back a translated value as the original.
     */
    protected static function options() {
      $current = apply_filters("wpml_current_language", null);
      $default = apply_filters("wpml_default_language", null);
      $switch  = !empty($current) && !empty($default) && $current !== $default;
      if ($switch) do_action("wpml_switch_language", $default);
      $opt = get_option(self::OPTION, array());
      if ($switch) do_action("wpml_switch_language", $current);
      return is_array($opt) ? $opt : array();
    }
    protected static function opt($opt, $key) {
      return isset($opt[$key]) && is_scalar($opt[$key]) ? (string) $opt[$key] : "";
    }
    /**
     * Strings of the register-fields builder (option "fileds_register"), parsed exactly like
     * PeproDevUPS_Login::get_register_fields() does, keyed by string name.
     * @param bool $refresh re-read the option
     * @return array name => original value
     */
    public static function builder_strings($refresh = false) {
      if (null !== self::$builder_strings && !$refresh) return self::$builder_strings;
      $strings = array();
      $opt     = self::options();
      $json    = wp_unslash(self::opt($opt, "fileds_register"));
      $fields  = !empty($json) ? json_decode($json, true) : array();
      if (is_array($fields)) {
        foreach ($fields as $field) {
          if (!is_array($field) || empty($field["meta_name"]) || !is_scalar($field["meta_name"])) continue;
          $meta = str_replace("-", "_", sanitize_title($field["meta_name"]));
          if (isset($field["title"]) && is_string($field["title"])) $strings["register field: {$meta} label"] = $field["title"];
          if (isset($field["placeholder"]) && is_string($field["placeholder"])) $strings["register field: {$meta} placeholder"] = $field["placeholder"];
          if (!empty($field["attributes"]) && is_string($field["attributes"]) && preg_match('/data-error-text\s*=\s*(["\'])(.*?)\1/s', $field["attributes"], $m)) {
            $strings["register field: {$meta} error text"] = $m[2];
          }
          if (isset($field["type"]) && "select" == $field["type"] && !empty($field["options"]) && is_string($field["options"])) {
            foreach (explode("\n", $field["options"]) as $line) {
              $tmp = explode(":", $line);
              if (!isset($tmp[1])) continue;
              $strings["register field: {$meta} option " . trim($tmp[0])] = trim($tmp[1]);
            }
          }
        }
      }
      return self::$builder_strings = array_filter($strings, function ($v) { return is_string($v) && "" !== trim($v); });
    }
    /**
     * Translate label, placeholder, error text and select option labels of one processed register field.
     * Only values equal to the registered originals are translated, so built-in fields pass through untouched.
     * @param array $field field array (options already parsed into key => title)
     * @return array
     */
    public static function translate_register_field($field) {
      if (!is_array($field) || empty($field["meta_name"]) || !is_scalar($field["meta_name"]) || !self::is_active()) return $field;
      $src  = self::builder_strings();
      if (empty($src)) return $field;
      $meta = (string) $field["meta_name"];
      foreach (array("title" => "label", "placeholder" => "placeholder") as $key => $label) {
        $name = "register field: {$meta} {$label}";
        if (isset($field[$key], $src[$name]) && $field[$key] === $src[$name]) $field[$key] = self::translate($name, $field[$key]);
      }
      $name = "register field: {$meta} error text";
      if (isset($src[$name]) && !empty($field["attributes"]) && is_string($field["attributes"]) && preg_match('/data-error-text\s*=\s*(["\'])(.*?)\1/s', $field["attributes"], $m) && $m[2] === $src[$name]) {
        $tr = self::translate($name, $m[2]);
        if ($tr !== $m[2]) {
          $tr = str_replace($m[1], ('"' === $m[1] ? "&quot;" : "&#039;"), $tr);
          $field["attributes"] = str_replace($m[0], "data-error-text={$m[1]}{$tr}{$m[1]}", $field["attributes"]);
        }
      }
      if (!empty($field["options"]) && is_array($field["options"])) {
        foreach ($field["options"] as $key => $title) {
          $name = "register field: {$meta} option {$key}";
          if (isset($src[$name]) && $title === $src[$name]) $field["options"][$key] = self::translate($name, $title);
        }
      }
      return $field;
    }
    /**
     * Name of a redirection rule string (option "fileds_redirect", JSON rows)
     * @param int    $index zero-based row index
     * @param array  $row   rule row
     * @param string $what  "url" or "popup button text"
     */
    public static function redirect_rule_name($index, $row, $what) {
      $role = isset($row["role"]) && is_scalar($row["role"]) ? sanitize_key($row["role"]) : "";
      return sprintf("redirect rule %d (%s): %s", ((int) $index) + 1, $role, $what);
    }
    /** Cheap marker of the custom dashboard sections table (count + last change) */
    protected static function sections_marker() {
      global $wpdb;
      $wpdb->suppress_errors(true);
      $row = $wpdb->get_row("SELECT COUNT(1) AS c, MAX(`date_modified`) AS m FROM `{$wpdb->prefix}pepro_core_profile_sections`", ARRAY_A);
      $wpdb->suppress_errors(false);
      return is_array($row) ? implode("|", $row) : "";
    }
    /**
     * Strings of one custom dashboard section
     * @param object|array $section row with slug, title, subject, content
     * @return array name => value
     */
    public static function section_strings($section) {
      $section = (array) $section;
      if (empty($section["slug"])) return array();
      $slug = (string) $section["slug"];
      return array(
        "section: {$slug} title"   => isset($section["title"]) ? (string) $section["title"] : "",
        "section: {$slug} heading" => isset($section["subject"]) ? (string) $section["subject"] : "",
        "section: {$slug} content" => isset($section["content"]) ? (string) $section["content"] : "",
      );
    }
    /**
     * Every translatable string (name => original value), built from the stored settings.
     * @param bool $with_sections include custom dashboard sections (one DB query)
     * @return array
     */
    public static function get_strings($with_sections = true) {
      global $PeproDevUPS_Login;
      $opt     = self::options();
      $strings = array();

      // profile dashboard
      $strings["profile: dashboard custom html"] = self::opt($opt, "custom_html");
      $strings["dashboard: title"]               = self::opt($opt, "dashboard_title");

      // login / register form and WordPress built-in login page
      $strings["login: header html"]      = self::opt($opt, "login_header_html");
      $strings["login: footer html"]      = self::opt($opt, "login_footer_html");
      $strings["wp login: logo title"]    = self::opt($opt, "login_logo_title");
      $strings["wp login: logo link url"] = self::opt($opt, "login_logo_href");

      // verification e-mail (effective template, the same value the login class sends)
      $template = html_entity_decode(stripslashes(self::opt($opt, "verify_mail_template")));
      if (is_object($PeproDevUPS_Login) && method_exists($PeproDevUPS_Login, "effective_mail_template")) {
        $template = (string) $PeproDevUPS_Login->effective_mail_template($template);
      }
      $strings["email: verification email template"] = $template;
      // an empty subject uses the built-in default, which is translated with the plugin's language files
      $strings["email: verification email subject"]  = trim(self::opt($opt, "verify_mail_subject"));
      $strings["email: sender name"]                  = trim(self::opt($opt, "verify_mail_sender_name"));

      // sms message templates
      $strings["sms: sms.ir message"]    = self::opt($opt, "smsir_message");
      $strings["sms: sms.ir v2 message"] = self::opt($opt, "smsir2_message");
      $strings["sms: kavenegar message"] = self::opt($opt, "kavenegar_message");

      // redirection rules
      $json = wp_unslash(self::opt($opt, "fileds_redirect"));
      $rows = !empty($json) ? json_decode($json, true) : array();
      if (is_array($rows)) {
        foreach ($rows as $index => $row) {
          if (!is_array($row)) continue;
          if (isset($row["url"]) && is_string($row["url"])) $strings[self::redirect_rule_name($index, $row, "url")] = $row["url"];
          if (isset($row["text"]) && is_string($row["text"])) $strings[self::redirect_rule_name($index, $row, "popup button text")] = $row["text"];
        }
      }

      // register-fields builder
      $strings = array_merge($strings, self::builder_strings(true));

      // custom dashboard sections
      if ($with_sections) {
        global $wpdb;
        $wpdb->suppress_errors(true);
        $sections = $wpdb->get_results("SELECT `slug`, `title`, `subject`, `content` FROM `{$wpdb->prefix}pepro_core_profile_sections`");
        $wpdb->suppress_errors(false);
        foreach ((array) $sections as $section) $strings = array_merge($strings, self::section_strings($section));
      }

      $strings = (array) apply_filters("peprodev-ups/wpml/strings", $strings);
      return array_filter($strings, function ($v) { return is_string($v) && "" !== trim($v); });
    }
    /**
     * Register every string (called after each settings save and lazily on admin_init).
     */
    public static function register_all() {
      if (!self::is_active()) return;
      foreach (self::get_strings(true) as $name => $value) self::register($name, $value);
      if (self::is_st_active()) update_option(self::HASH_OPTION, self::strings_hash(), false);
    }
    protected static function strings_hash() {
      return md5((defined("PEPRODEVUPS") ? PEPRODEVUPS : "") . "|" . serialize(self::get_strings(false)) . "|" . self::sections_marker());
    }
    /**
     * Register existing values once without a re-save: WPML keeps registered strings, so only run when the
     * stored settings (or plugin version) changed; Polylang needs registration on every admin request.
     */
    public static function maybe_register_all() {
      if (wp_doing_ajax() || !current_user_can("manage_options")) return;
      if (self::is_pll_active() && !self::is_st_active()) {
        foreach (self::get_strings(true) as $name => $value) self::register($name, $value);
        return;
      }
      if (!self::is_st_active()) return;
      if (get_option(self::HASH_OPTION, "") !== self::strings_hash()) self::register_all();
    }
    #endregion

    #region admin UI
    /** "Translate texts with WPML" link for the settings screens (empty when WPML ST is not active) */
    public static function admin_link_html($class = "btn btn-link") {
      if (!self::is_st_active()) return "";
      return sprintf(
        '<a href="%s" class="pepro-wpml-st-link %s" target="_blank" rel="noopener"><i class="material-icons" style="vertical-align: middle;">translate</i> %s</a>',
        esc_url(self::st_url()),
        esc_attr($class),
        esc_html__("Translate texts with WPML", "peprodev-ups")
      );
    }
    public static function plugin_action_links($actions) {
      if (self::is_st_active()) {
        $actions["peprodev-wpml"] = '<a href="' . esc_url(self::st_url()) . '">' . esc_html__("Translate with WPML", "peprodev-ups") . '</a>';
      }
      return $actions;
    }
    #endregion
  }
}
