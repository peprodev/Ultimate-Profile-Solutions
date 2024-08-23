<?php
/*
Plugin Name: PeproDev Ultimate Profile Solutions
Description: One of the most Advanced and *The Ultimate Profile Solutions* for WordPress. FREE OF ANY CHARGE, UNLIMITED and OPEN-SOURCE FOREVER!
Contributors: amirhpcom, peprodev, blackswanlab
Tags: functionality, pepro, management, administration, profile, login, register
Author: Pepro Dev. Group
Author URI: https://pepro.dev/
Plugin URI: https://pepro.dev/ups
Version: 7.4.0
Requires at least: 5.0
Tested up to: 6.6.2
Requires PHP: 7.2
WC tested up to: 9.2.0
Text Domain: peprodev-ups
Domain Path: /languages
Copyright: (c) Pepro Dev. Group, All rights reserved.
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2024/08/24 01:45:17
*/

defined("ABSPATH") or die("PeproDev Ultimate Profile Solutions :: Unauthorized Access! (https://pepro.dev/)");

if (!class_exists("PeproDevUPS")) {
    class PeproDevUPS {
        public $td = "peprodev-ups";
        public $version = "7.4.0";
        public function __construct() {

            define("PEPRODEVUPS", $this->version);
            define("PEPRODEV_UPS_FILE", __FILE__);
            add_filter("load_textdomain_mofile", function ($m, $d) { if ($this->td == $d && "fa_IR" == get_locale()) {return plugin_dir_path(__FILE__) . "languages/{$d}-fa_IR.mo";} return $m; }, 10, 2);
            load_plugin_textdomain("peprodev-ups", false, dirname(plugin_basename(__FILE__)) . "/languages/");
            load_plugin_textdomain("wpserveur-hide-login", false, dirname(plugin_basename(__FILE__)) . "/languages/");
            
            add_action("plugin_row_meta", array($this, "plugin_row_meta"), 10, 4);
            add_filter("plugin_action_links", array($this, "plugin_action_links"), 10, 2);

            require_once plugin_dir_path(__FILE__) . "/core/main.php";
            require_once plugin_dir_path(__FILE__) . "/profile/profile.php";
            require_once plugin_dir_path(__FILE__) . "/login/login.php";

            add_action("before_woocommerce_init", [$this, "add_hpos_support"]);
            add_filter("rocket_cache_reject_uri", array($this, "rocket_add_profile_exclude_pages"));

            register_deactivation_hook(__FILE__, function () {
                update_option("peprodevups_alert_viewed_yet", "");
            });
            register_activation_hook(__FILE__, function () {
                add_filter("rocket_cache_reject_uri", array($this, "rocket_add_profile_exclude_pages"));
                if (function_exists("flush_rocket_htaccess")) {
                    // Update the WP Rocket rules on the .htaccess.
                    flush_rocket_htaccess();
                    // Regenerate the config file.
                    rocket_generate_config_file();
                }
            });
        }
        // wp-rocket
        public function rocket_add_profile_exclude_pages($urls){
            $profile_page = get_option("PeproDevUPS_Core___profile-profile-dash-page", false);
            if ($profile_page) $urls[] = wp_parse_url(get_permalink($profile_page), PHP_URL_PATH );
            return $urls;
        }
        public function add_hpos_support() {
            if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
                \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
            }
        }
        public function plugin_row_meta($links_array, $plugin_file_name, $plugin_data, $status) {
            if (strpos($plugin_file_name, basename(__FILE__))) {
                $links_array[] = "<a href='mailto:support@pepro.dev?subject=PeproProfile-v.{$this->version}'>" . __("Support", "peprodev-ups") . "</a>";
                $links_array[] = "<a href='https://pepro.dev/ups'>" . __('Documentation', "peprodev-ups") . "</a>";
                $links_array[] = "<a href='" . admin_url("?pepro_ups_force_db_create=1") . "'>" . __('Update Database Structure', "peprodev-ups") . "</a>";
            }
            return $links_array;
        }
        public function plugin_action_links($actions, $plugin_file) {
            if (plugin_basename(__FILE__) == $plugin_file) {
                $actions["peprodev-profile"] = "<a href='" . admin_url("admin.php?page=pepro&section=home") . "'>" . __("Settings", "peprodev-ups") . "</a>";
            }
            return $actions;
        }
    }
    add_action("plugins_loaded", function () {
        global $PeproDevUPS;
        $PeproDevUPS = new PeproDevUPS();
    });
}
/*##################################################
Lead Developer: [amirhp-com](https://amirhp.com/)
##################################################*/
