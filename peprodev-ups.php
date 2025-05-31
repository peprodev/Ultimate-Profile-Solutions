<?php
/*
Plugin Name: PeproDev Ultimate Profile Solutions
Description: Advanced and fully customizable profile, login, registration, and user dashboard solution for WordPress. Includes WooCommerce, LearnDash, and SMS gateway integrations. 100% free and open-source.
Contributors: amirhpcom, peprodev, blackswanlab
Tags: profile, dashboard, login-registration
Author: Pepro Dev. Group
Author URI: https://peprodev.com/pepro-ultimate-profile-solution/
Plugin URI: https://wordpress.org/plugins/peprodev-ups/
Version: 8.0.4
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.2
WC tested up to: 9.8
Text Domain: peprodev-ups
Domain Path: /languages
Copyright: (c) Pepro Dev. Group, All rights reserved.
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2025/05/31 17:01:28
*/

defined("ABSPATH") or die("PeproDev Ultimate Profile Solutions :: Unauthorized Access!");

if (!class_exists("PeproDevUPS")) {
  class PeproDevUPS {
    public $td          = "peprodev-ups";
    public $db_slug     = "peprodev-ups";
    public $version     = "8.0.4";
    public $db_version  = "8.0.4";
    public $setting_key = "peprodev_ups_profile";
    public $title = "PeproDev Profile";
    public $title_w = "PeproDev Ultimate Profile Solutions";
    public $db_table;
    public $tbl_subscribers;
    public $plugin_dir;
    public $assets_url;
    public $assets_url_raw;
    public $tbl_notif;
    public $tbl_notif_list;
    public $tbl_sections;
    public function __construct($full_load=true) {
      global $wpdb;
      $this->assets_url      = plugins_url("/core/assets/", __FILE__);
      $this->assets_url_raw  = plugins_url("/core/assets/", __FILE__);
      $this->db_table        = "{$wpdb->prefix}pepro_core_profile";
      $this->tbl_subscribers = "{$wpdb->prefix}pepro_core_profile";
      $this->tbl_notif       = "{$wpdb->prefix}pepro_core_profile_notif";
      $this->tbl_notif_list  = "{$wpdb->prefix}pepro_core_profile_notif_list";
      $this->tbl_sections    = "{$wpdb->prefix}pepro_core_profile_sections";
      $this->plugin_dir      = plugin_dir_path(__FILE__);
      if (!defined("PEPRODEVUPS")) {
        define("PEPRODEVUPS", $this->version);
        define("PEPRODEVUPS_ASSETS_URL", $this->assets_url);
      }
      add_action("init", function () {
        $this->title = __("PeproDev Ultimate Profile Solutions", "peprodev-ups");
        $this->title_w = sprintf(__("%2\$s ver. %1\$s", "peprodev-ups"), $this->version, $this->title);
      });
      if ($full_load) {
        add_filter("load_textdomain_mofile", function ($m, $d) {
          if ($this->td == $d && "fa_IR" == get_locale()) {
            return plugin_dir_path(__FILE__) . "languages/{$d}-fa_IR.mo";
          }
          return $m;
        }, 10, 2);

        add_action("init", function(){ load_plugin_textdomain("peprodev-ups", false, dirname(plugin_basename(__FILE__)) . "/languages/"); });
        add_action("admin_init", array($this, "admin_init"), 20);
        $cur_version = $this->read("profile_db_version", get_option("peprodev_ups_profile_db", NULL));

        if (!is_null($cur_version) && version_compare($cur_version, "8.0", "ge")) {
          $this->migrate_v760_to_800();
        }

        if (current_user_can("manage_options") && is_admin() && isset($_GET["test_settings_raw"]) && !empty($_GET["test_settings_raw"])) {
          $options = (array) get_option($this->setting_key);
          echo "<pre style='text-align: left; direction: ltr; border:1px solid gray; padding: 1rem; overflow: auto;'>NEW JSON:". print_r($options,1) ."</pre>";
          exit;
        }

        require_once plugin_dir_path(__FILE__) . "core/main.php";
        require_once plugin_dir_path(__FILE__) . "profile/profile.php";
        require_once plugin_dir_path(__FILE__) . "login/login.php";

        add_action("plugin_row_meta", array($this, "plugin_row_meta"), 10, 4);
        add_filter("plugin_action_links", array($this, "plugin_action_links"), 10, 2);

        add_action("before_woocommerce_init", [$this, "add_hpos_support"]);
        add_filter("rocket_cache_reject_uri", array($this, "rocket_add_profile_exclude_pages"));

        register_deactivation_hook(__FILE__, function () { $this->set("alert_viewed_yet", ""); });
        register_activation_hook(__FILE__, function () {
          add_filter("rocket_cache_reject_uri", array($this, "rocket_add_profile_exclude_pages"));
          if (function_exists("flush_rocket_htaccess")) {
            flush_rocket_htaccess(); // Update the WP Rocket rules on the .htaccess.
            rocket_generate_config_file(); // Regenerate the config file.
          }
        });

        if (current_user_can("manage_options") && isset($_GET["peprodevups_force_db_create"]) && !empty($_GET["peprodevups_force_db_create"])) {
          $this->create_database(true);
          wp_redirect(admin_url("admin.php?page=peprodev-ups&section=home"));
        }
      }
    }
    // wp-rocket
    public function rocket_add_profile_exclude_pages($urls) {
      $profile_page = $this->read("profile_page", "profile");
      if ($profile_page) $urls[] = wp_parse_url(get_permalink($profile_page), PHP_URL_PATH);
      return $urls;
    }
    public function add_hpos_support() {
      if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
      }
    }
    public function plugin_row_meta($links_array, $plugin_file_name, $plugin_data, $status) {
      if (strpos($plugin_file_name, basename(__FILE__))) {
        $links_array["peprodev_link_1"] = "<a href='https://wordpress.org/support/plugin/peprodev-ups/'>" . __("Support Forum", "peprodev-ups") . "</a>";
        $links_array["peprodev_link_2"] = "<a href='https://github.com/peprodev/Ultimate-Profile-Solutions'>" . __('Documentation', "peprodev-ups") . "</a>";
        $links_array["peprodev_link_3"] = "<a href='https://peprodev.com/pepro-ultimate-profile-solution/'>" . __('Pepro Dev. Group', "peprodev-ups") . "</a>";
        $links_array["peprodev_link_4"] = "<a href='mailto:support@peprodev.com?subject=PeproProfile-v.{$this->version}'>" . __("Email Developer", "peprodev-ups") . "</a>";
        $links_array["peprodev_link_5"] = "<a href='" . admin_url("?peprodevups_force_db_create=1") . "'>" . __('Update Database Structure', "peprodev-ups") . "</a>";
      }
      return $links_array;
    }
    public function plugin_action_links($actions, $plugin_file) {
      if (plugin_basename(__FILE__) == $plugin_file) {
        $actions["peprodev-profile"] = "<a href='" . admin_url("admin.php?page=peprodev-ups&section=home") . "'>" . __("Settings", "peprodev-ups") . "</a>";
      }
      return $actions;
    }
    public function create_database($force = false) {
      global $wpdb;
      $charset_collate = $wpdb->get_charset_collate();
      if (!function_exists('dbDelta')) include_once ABSPATH . 'wp-admin/includes/upgrade.php';
      if ($wpdb->get_var("SHOW TABLES LIKE '{$this->tbl_notif}'") != $this->tbl_notif || $force) {
        dbDelta("CREATE TABLE `{$this->tbl_notif}` (
          `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
          `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          `date_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          `date_scheduled` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          `date_scheduledFA` TINYTEXT,
          `title` TINYTEXT,
          `content` TEXT,
          `icon` TINYTEXT,
          `color` TINYTEXT,
          `priority` ENUM( '1', '2', '3', '4', '5' ) DEFAULT '5',
          `action_title_1` TINYTEXT,
          `action_title_2` TINYTEXT,
          `action_url_1` TINYTEXT,
          `action_url_2` TINYTEXT,
          `users_list` LONGTEXT,
          `learn_dash` LONGTEXT,
          `access_groups` LONGTEXT,
          `user_roles` TEXT,
          PRIMARY KEY id (id) ) $charset_collate;"
        );
      }
      if ($wpdb->get_var("SHOW TABLES LIKE '{$this->tbl_notif_list}'") != $this->tbl_notif_list || $force) {
        dbDelta("CREATE TABLE `{$this->tbl_notif_list}` (
          `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
          `user_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
          `notif_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
          `has_seen` ENUM( '0', '1' ) DEFAULT '0',
          `seen_first_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          `seen_last_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY id (id) ) $charset_collate;"
        );
      }
      if ($wpdb->get_var("SHOW TABLES LIKE '{$this->tbl_sections}'") != $this->tbl_sections || $force) {
        dbDelta("CREATE TABLE `{$this->tbl_sections}` (
          `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
          `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          `date_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          `title` TINYTEXT,
          `slug` TINYTEXT,
          `img` TINYTEXT,
          `subject` TINYTEXT,
          `content` LONGTEXT,
          `js` LONGTEXT,
          `css` LONGTEXT,
          `icon` TINYTEXT,
          `access` LONGTEXT,
          `ld_lms` INT(10),
          `is_active` VARCHAR(10) DEFAULT 'yes',
          `priority` INT(10) UNSIGNED NOT NULL DEFAULT 1000,
          PRIMARY KEY (`id`) ) $charset_collate;"
        );
      }
      if ($wpdb->get_var("SHOW TABLES LIKE '{$this->tbl_subscribers}'") != $this->tbl_subscribers || $force) {
        dbDelta("CREATE TABLE `{$this->tbl_subscribers}` (
          `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
          `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `user` VARCHAR(512),
          `name` VARCHAR(512),
          `mobile` VARCHAR(20),
          `email` VARCHAR(320),
          `extra_info` TEXT,
          PRIMARY KEY id (id) ) $charset_collate;"
        );
      }
    }
    #region settings
    public function read($slug = "", $default = NULL) {
      $opt = get_option($this->setting_key, $default);
      $slug = str_replace("-", "_", $slug);
      return isset($opt[$slug]) ? $opt[$slug] : $default;
    }
    public function get($slug = "", $default = "") {
      return $this->read($slug, $default);
    }
    public function set($slug = "", $value = "") {
      $options = (array) get_option($this->setting_key);
      $slug = str_replace("-", "_", $slug);
      $options[$slug] = $value;
      update_option($this->setting_key, $options, "no");
    }
    public function read_opt($slug = "", $default = "") {
      return $this->read($slug, $default);
    }
    public function setting_defaults(){
      $def_mail_body = [
        '<!DOCTYPE html>', '<html>',
        '  <head>',
        '    <meta charset="utf-8">',
        '  </head>',
        '  <body>',
        '    <div style="display:block; width:450px; border-radius:0.5rem; margin: 1rem auto; text-align: center; color: #2b2b2b; padding: 1rem; box-shadow: 0 2px 5px 1px #0003; border: 1px solid #ccc;">',
        '      <h2>Verify your account</h2>',
        '      <h3>Use code below to verify your account:</h3>',
        '      <h1>',
        '        <strong>[OTP]</strong>',
        '      </h1>',
        '      <br>',
        '    </div>',
        '    <p style="text-align: center;">',
        '       <small style="color: #717171;">Copyright &copy; ' . date("Y") . ', all rights reserved.</small>', '    </p>',
        '  </body>',
        '</html>'
      ];
      $array = [
        "new_setting"                    => "1.0.0",
        "custom_css"                     => "",
        "custom_js"                      => "",
        "custom_logo"                    => "",
        "custom_logo_id"                 => "",
        "show_welcome"                   => "true",
        "woocommerce_stats"              => "true",
        "woocommerce_orders"             => "true",
        "show_custom_text"               => "true",
        "custom_html"                    => "",
        "auth_expire"                    => "0",
        "no_popup_alert"                 => "yes",
        "header_hook"                    => "no",
        "regdef_firstname"               => "yes",
        "regdef_firstname_req"           => "yes",
        "regdef_lastname"                => "no",
        "regdef_lastname_req"            => "no",
        "regdef_displayname"             => "no",
        "regdef_displayname_req"         => "no",
        "regdef_mobile"                  => "no",
        "regdef_mobile_req"              => "no",
        "regdef_username"                => "no",
        "regdef_username_req"            => "no",
        "regdef_email"                   => "yes",
        "regdef_email_req"               => "yes",
        "regdef_passwords"               => "yes",
        "regdef_passwords_req"           => "yes",
        "footer_hook"                    => "no",
        "custom_position"                => "p2",
        "profile_page"                   => "profile",
        "sms_expiration"                 => "60",
        "email_expiration"               => "60",
        "verification_digits"            => "5",
        "verification_email_digits"      => "5",
        "login_verify_email"             => "no",
        "login_verify_mobile"            => "no",
        "use_mobile_as_username"         => "no",
        "use_email_as_username"          => "no",
        "hide_email_field"               => "no",
        "hide_username_field"            => "no",
        "sms_ultrafastsend_id"           => sprintf(__("Verification Code: [OTP] — %s", "peprodev-ups"), get_bloginfo("name")),
        "verification_email_sender"      => "wordpress@" . wp_parse_url(get_bloginfo('url'), PHP_URL_HOST),
        "verification_email_sender_name" => get_bloginfo('name', 'display'),
        "verification_email_template"    => htmlentities(implode(PHP_EOL, $def_mail_body)),
        "fileds_redirect"                => '[{"role": "everyone", "url": "{profile}", "text": "' . __("Profile", "peprodev-ups") . '", "login": "yes", "register": "yes", "logout": "no" }]',
        "login_register_type"            => "email",
        "login_pro_verify"               => "both",
        "auto_login_after_reg"           => "yes",
        "show_email_login_form"          => "yes",
        "show_mobile_login_form"         => "yes",
        "active_mobile_login_form"       => "no",
        "force_register_form"            => "",
        "no_popup_alert"                 => "no",
      ];
      return apply_filters("peprodev-ups/setting_defaults", $array);
    }
    public function admin_init($hook) {
      add_option($this->setting_key, $this->setting_defaults(), "", "no");
      register_setting($this->setting_key, $this->setting_key);
    }
    public function translate_option_slug($new2prev="", $prev2new=""){
      $prev_settings = [
        "custom_css"                  => "PeproDevUPS_Core___profile-css",
        "custom_js"                   => "PeproDevUPS_Core___profile-js",
        "custom_logo"                 => "PeproDevUPS_Core___profile-logo",
        "custom_logo_id"              => "PeproDevUPS_Core___profile-logo-id",
        "show_welcome"                => "PeproDevUPS_Core___profile-showwelcome",
        "woocommerce_stats"           => "PeproDevUPS_Core___profile-woocommercestats",
        "woocommerce_orders"          => "PeproDevUPS_Core___profile-woocommerceorders",
        "show_custom_text"            => "PeproDevUPS_Core___profile-showcustomtext",
        "custom_html"                 => "PeproDevUPS_Core___profile-customhtml",
        "header_hook"                 => "PeproDevUPS_Core___profile-headerhook",
        "footer_hook"                 => "PeproDevUPS_Core___profile-footerhook",
        "custom_position"             => "PeproDevUPS_Core___profile-customposition",
        "profile_page"                => "PeproDevUPS_Core___profile-profile-dash-page",
        "page_created"                => "PeproDevUPS_Core___profile-dash-page-created",
        "theme_scheme"                => "PeproDevUPS_Core___theme-scheme",
        "theme_color"                 => "PeproDevUPS_Core___theme-color",
        "theme_img"                   => "PeproDevUPS_Core___theme-sidebar-image",
        "theme_img_url"               => "PeproDevUPS_Core___theme-sidebar-image-custom",
        "theme_img_id"                => "PeproDevUPS_Core___theme-sidebar-image-custom-id",
        "dashboard_title"             => "PeproDevUPS_Core___dashboard-title",
        "login_use_wp_core"           => "PeproDevUPS_Core___loginregister-wp",
        "login_header_html"           => "PeproDevUPS_Core___loginregister-headerhtml",
        "login_footer_html"           => "PeproDevUPS_Core___loginregister-footerhtml",
        "fileds_redirect"             => "pepro-profile-redirection-fileds",
        "fileds_register"             => "pepro-profile-register-fileds",
        "sms_method"                  => "PeproDevUPS_Core___loginregister-sms_method",
        "sms_api_url"                 => "PeproDevUPS_Core___loginregister-sms_api_url",
        "sms_api2_url"                => "PeproDevUPS_Core___loginregister-sms_api2_url",
        "sms_secret_key"              => "PeproDevUPS_Core___loginregister-sms_secret_key",
        "sms_api_key"                 => "PeproDevUPS_Core___loginregister-sms_api_key",
        "sms_api2_key"                => "PeproDevUPS_Core___loginregister-sms_api2_key",
        "auth_expire"                 => "PeproDevUPS_Core___loginregister-auth_expire",
        "sms_expiration"              => "PeproDevUPS_Core___loginregister-sms_expiration",
        "email_expiration"            => "PeproDevUPS_Core___loginregister-email_expiration",
        "sms_ultrafastsend_id"        => "PeproDevUPS_Core___loginregister-sms_ultrafastsend_id",
        "verification_digits"         => "PeproDevUPS_Core___loginregister-verification_digits",
        "verification_email_digits"   => "PeproDevUPS_Core___loginregister-verification_email_digits",
        "login_custom_css"            => "PeproDevUPS_Core___loginregister-customcss",
        "show_password_field"         => "PeproDevUPS_Core___loginregister-show_password_field",
        "auto_login_after_reg"        => "PeproDevUPS_Core___loginregister-auto_login_after_reg",
        "show_email_login_form"       => "PeproDevUPS_Core___loginregister-show_email_login_form",
        "show_mobile_login_form"      => "PeproDevUPS_Core___loginregister-show_mobile_login_form",
        "active_mobile_login_form"    => "PeproDevUPS_Core___loginregister-active_mobile_login_form",
        "force_register_form"         => "PeproDevUPS_Core___loginregister-force_register_form",
        "no_popup_alert"              => "PeproDevUPS_Core___loginregister-no_popup_alert",
        "smsir_message"               => "PeproDevUPS_Core___loginregister-smsir_message",
        "smsir2_message"              => "PeproDevUPS_Core___loginregister-smsir2_message",
        "login_verify_email"          => "PeproDevUPS_Core___loginregister-verify_email",
        "login_verify_mobile"         => "PeproDevUPS_Core___loginregister-verify_mobile",
        "use_mobile_as_username"      => "PeproDevUPS_Core___loginregister-use_mobile_as_username",
        "use_email_as_username"       => "PeproDevUPS_Core___loginregister-use_email_as_username",
        "hide_email_field"            => "PeproDevUPS_Core___loginregister-hide_email_field",
        "hide_username_field"         => "PeproDevUPS_Core___loginregister-hide_username_field",
        "reg_add_mobile"              => "PeproDevUPS_Core___loginregister-reg_add_mobile",
        "reg_add_firstname"           => "PeproDevUPS_Core___loginregister-reg_add_firstname",
        "reg_add_lastname"            => "PeproDevUPS_Core___loginregister-reg_add_lastname",
        "reg_add_displayname"         => "PeproDevUPS_Core___loginregister-reg_add_displayname",
        "login_mobile_otp"            => "PeproDevUPS_Core___loginregister-login_mobile_otp",
        "activesecurity"              => "PeproDevUPS_Core___loginregister-activesecurity",
        "login_style"                 => "PeproDevUPS_Core___loginregister-style",
        "login_showlogo"              => "PeproDevUPS_Core___loginregister-showlogo",
        "kavenegar_username"          => "PeproDevUPS_Core___loginregister-kavenegar_username",
        "kavenegar_message"           => "PeproDevUPS_Core___loginregister-kavenegar_message",
        "kavenegar_sendernumber"      => "PeproDevUPS_Core___loginregister-kavenegar_sendernumber",
        "kavenegarlookup_api_key"     => "PeproDevUPS_Core___loginregister-kavenegarlookup_api_key",
        "kavenegarlookup_template_id" => "PeproDevUPS_Core___loginregister-kavenegarlookup_template_id",
        "sms_api_key"                 => "PeproDevUPS_Core___loginregister-sms_api_key",
        "sms_api2_key"                => "PeproDevUPS_Core___loginregister-sms_api2_key",
        "sms_secret_key"              => "PeproDevUPS_Core___loginregister-sms_secret_key",
        "sms_api_url"                 => "PeproDevUPS_Core___loginregister-sms_api_url",
        "sms_api2_url"                => "PeproDevUPS_Core___loginregister-sms_api2_url",
        "smsir_message"               => "PeproDevUPS_Core___loginregister-smsir_message",
        "smsir2_message"              => "PeproDevUPS_Core___loginregister-smsir2_message",
        "pars_green_api_key"          => "PeproDevUPS_Core___loginregister-pars_green_api_key",
        "pars_green_template_id"      => "PeproDevUPS_Core___loginregister-pars_green_template_id",
        "pars_green_add_name"         => "PeproDevUPS_Core___loginregister-pars_green_add_name",
        "pars_green_sms_number"       => "PeproDevUPS_Core___loginregister-pars_green_sms_number",
        "faraz_username"              => "PeproDevUPS_Core___loginregister-faraz_username",
        "faraz_password"              => "PeproDevUPS_Core___loginregister-faraz_password",
        "faraz_message"               => "PeproDevUPS_Core___loginregister-faraz_message",
        "faraz_sendernumber"          => "PeproDevUPS_Core___loginregister-faraz_sendernumber",
        "farazlookup_sender"          => "PeproDevUPS_Core___loginregister-farazlookup_sender",
        "farazlookup_api_key"         => "PeproDevUPS_Core___loginregister-farazlookup_api_key",
        "farazlookup_password"        => "PeproDevUPS_Core___loginregister-farazlookup_password",
        "farazlookup_code"            => "PeproDevUPS_Core___loginregister-farazlookup_code",
        "farazlookup_template_id"     => "PeproDevUPS_Core___loginregister-farazlookup_template_id",
        "login_logo"                  => "PeproDevUPS_Core___loginregister-logo",
        "login_logo_id"               => "PeproDevUPS_Core___loginregister-logo-id",
        "login_logo_w"                => "PeproDevUPS_Core___loginregister-logo-w",
        "login_logo_h"                => "PeproDevUPS_Core___loginregister-logo-h",
        "login_logo_title"            => "PeproDevUPS_Core___loginregister-logo-title",
        "login_logo_href"             => "PeproDevUPS_Core___loginregister-logo-href",
        "login_shake"                 => "PeproDevUPS_Core___loginregister-shake",
        "login_spb"                   => "PeproDevUPS_Core___loginregister-spb",
        "login_b2b"                   => "PeproDevUPS_Core___loginregister-b2b",
        "login_privacy"               => "PeproDevUPS_Core___loginregister-privacy",
        "login_nav"                   => "PeproDevUPS_Core___loginregister-nav",
        "login_rmc"                   => "PeproDevUPS_Core___loginregister-rmc",
        "login_msg"                   => "PeproDevUPS_Core___loginregister-msg",
        "login_error"                 => "PeproDevUPS_Core___loginregister-error",
        "login_forcebg"               => "PeproDevUPS_Core___loginregister-forcebg",
        "login_bgtype"                => "PeproDevUPS_Core___loginregister-bgtype",
        "login_bg_solid"              => "PeproDevUPS_Core___loginregister-bg-solid",
        "login_bg_gradient1"          => "PeproDevUPS_Core___loginregister-bg-gradient1",
        "login_bg_gradient2"          => "PeproDevUPS_Core___loginregister-bg-gradient2",
        "login_bg_gradient3"          => "PeproDevUPS_Core___loginregister-bg-gradient3",
        "login_bg_img"                => "PeproDevUPS_Core___loginregister-bg-img",
        "login_bg_img_id"             => "PeproDevUPS_Core___loginregister-bg-img-id",
        "login_bg_video"              => "PeproDevUPS_Core___loginregister-bg-video",
        "login_bg_video_id"           => "PeproDevUPS_Core___loginregister-bg-video-id",
        "login_bg_video_autoplay"     => "PeproDevUPS_Core___loginregister-bg-video-autoplay",
        "login_bg_video_muted"        => "PeproDevUPS_Core___loginregister-bg-video-muted",
        "login_bg_video_loop"         => "PeproDevUPS_Core___loginregister-bg-video-loop",
        "login_link_separator"        => "PeproDevUPS_Core___loginregister-link-separator",
        "verify_mail_sender_name"     => "PeproDevUPS_Core___loginregister-verification_email_sender_name",
        "verify_mail_sender"          => "PeproDevUPS_Core___loginregister-verification_email_sender",
        "verify_mail_template"        => "PeproDevUPS_Core___loginregister-verification_email_template",
        "login_register_type"         => "PeproDevUPS_Core___loginregister-reglogin_type",
        "login_pro_verify"            => "PeproDevUPS_Core___loginregister-pro_verify",
        "regdef_firstname"            => "PeproDevUPS_Core___loginregister-_regdef_firstname",
        "regdef_firstname_req"        => "PeproDevUPS_Core___loginregister-_regdef_firstname-req",
        "regdef_lastname"             => "PeproDevUPS_Core___loginregister-_regdef_lastname",
        "regdef_lastname_req"         => "PeproDevUPS_Core___loginregister-_regdef_lastname-req",
        "regdef_displayname"          => "PeproDevUPS_Core___loginregister-_regdef_displayname",
        "regdef_displayname_req"      => "PeproDevUPS_Core___loginregister-_regdef_displayname-req",
        "regdef_mobile"               => "PeproDevUPS_Core___loginregister-_regdef_mobile",
        "regdef_mobile_req"           => "PeproDevUPS_Core___loginregister-_regdef_mobile-req",
        "regdef_email"                => "PeproDevUPS_Core___loginregister-_regdef_email",
        "regdef_email_req"            => "PeproDevUPS_Core___loginregister-_regdef_email-req",
        "regdef_username"             => "PeproDevUPS_Core___loginregister-_regdef_username",
        "regdef_username_req"         => "PeproDevUPS_Core___loginregister-_regdef_username-req",
        "regdef_passwords"            => "PeproDevUPS_Core___loginregister-_regdef_passwords",
        "regdef_passwords_req"        => "PeproDevUPS_Core___loginregister-_regdef_passwords-req",
        "regdef_wc_country"           => "PeproDevUPS_Core___loginregister-_wc_billing_country",
        "regdef_wc_country_req"       => "PeproDevUPS_Core___loginregister-_wc_billing_country-req",
        "regdef_wc_state"             => "PeproDevUPS_Core___loginregister-_wc_billing_state",
        "regdef_wc_state_req"         => "PeproDevUPS_Core___loginregister-_wc_billing_state-req",
        "regdef_wc_city"              => "PeproDevUPS_Core___loginregister-_wc_billing_city",
        "regdef_wc_city_req"          => "PeproDevUPS_Core___loginregister-_wc_billing_city-req",
        "subscribers_db_version"      => "peprodev_profile_subscribers_db_version",
        "profile_db_version"          => "peprodev_ups_profile_db",

      ];
      if (empty($prev2new) && empty($new2prev)) {
        return $prev_settings;
      }
      if (!empty($prev2new)) {
        $new_from_prev = array_search($prev2new, $prev_settings);
        return $new_from_prev === false ? $prev2new : $new_from_prev;
      }
      if (!empty($new2prev)) {
        return isset($prev_settings[$new2prev]) ? $prev_settings[$new2prev] : $new2prev;
      }
    }
    public function migrate_v760_to_800(){
      $prev_settings = $this->translate_option_slug();
      if ($this->read("new_setting") != $this->version) {
        $this->set("new_setting", $this->version);
        foreach ($prev_settings as $new => $prev) {
          if (get_option($prev, NULL) !== NULL && $this->read($new, NULL) === NULL) {
            $this->set($new, get_option($prev, ""));
            delete_option($prev);
          }
        }
      }
      $this->set("profile_db_version", $this->version);
    }
    #endregion
    #region global fns
      public function _wc_activated() {
      if (!function_exists('is_woocommerce') || !class_exists('woocommerce')) return false;
      return true;
    }
    public function _ld_activated() {
      return defined('LEARNDASH_LMS_PLUGIN_DIR') && function_exists("learndash_user_get_enrolled_courses");
    }
    public function _vc_activated() {
      if (defined('WPB_VC_VERSION')) return true;
      return false;
    }
    public function print_setting_input($SLUG = "", $CAPTION = "", $extraHtml = "", $type = "text", $extraClass = "") {
      $ON = sprintf(_x("Enter %s", "setting-page", "peprodev-ups"), $CAPTION);
      echo "<tr>
            <th scope='row'><label for='$SLUG'>$CAPTION</label></th>
            <td><input name='$SLUG' $extraHtml type='$type' id='$SLUG' placeholder='$CAPTION' title='$ON' value='" . $this->read($SLUG) . "' class='regular-text $extraClass' /></td>
          </tr>";
    }
    public function print_setting_select($SLUG, $CAPTION, $dataArray = array()) {
      $ON = sprintf(_x("Choose %s", "setting-page", "peprodev-ups"), $CAPTION);
      $OPTS = "";
      foreach ($dataArray as $key => $value) {
        if ($key == "EMPTY") {
          $key = "";
        }
        $OPTS .= "<option value='$key' " . selected($this->read($SLUG), $key, false) . ">$value</option>";
      }
      echo "<tr>
      			<th scope='row'>
      				<label for='$SLUG'>$CAPTION</label>
      			</th>
      			<td><select name='$SLUG' id='$SLUG' title='$ON' class='regular-text'>
            " . $OPTS . "
            </select>
            </td>
      		</tr>";
    }
    public function print_setting_editor($SLUG, $CAPTION, $re = "") {
      echo "<tr><th><label for='$SLUG'>$CAPTION</label></th><td>";
      wp_editor(
        $this->read($SLUG, ''),
        strtolower(str_replace(array('-', '_', ' ', '*'), '', $SLUG)),
        array(
          'textarea_name' => $SLUG
        )
      );
      echo "<p class='$SLUG'>$re</p></td></tr>";
    }
    #endregion
  }
  add_action("plugins_loaded", function () {
    global $PeproDevUPS;
    $PeproDevUPS = new PeproDevUPS;
  });
}
/*##################################################
Lead Developer: [amirhp-com](https://amirhp.com/)
##################################################*/