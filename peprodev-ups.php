<?php
/*
Plugin Name: PeproDev Ultimate Profile Solutions
Description: One of the most Advanced and *The Ultimate Profile Solutions* for WordPress. FREE OF ANY CHARGE, UNLIMITED and OPEN-SOURCE FOREVER!
Contributors: amirhosseinhpv
Tags: functionality, pepro, management, administration, profile, login, register
Author: Pepro Dev. Group
Author URI: https://pepro.dev/
Plugin URI: https://pepro.dev/ups
Version: 2.4.2
Stable tag: 2.4.2
Requires at least: 5.0
Tested up to: 5.9
Requires PHP: 7.2
WC tested up to: 6.0
Text Domain: peprodev-ups
Domain Path: /languages
Copyright: (c) 2021 Pepro Dev. Group, All rights reserved.
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2022/02/06 17:37:00

defined("ABSPATH") or die("PeproDev Ultimate Profile Solutions :: Unauthorized Access! (https://pepro.dev/)");

if (!class_exists("PeproDevUPS")) {
    final class PeproDevUPS
    {
        public function __construct()
        {
            define('PEPRODEVUPS', '2.4.2');

            load_plugin_textdomain("peprodev-ups", false, dirname(plugin_basename(__FILE__))."/languages/");
            load_plugin_textdomain("wpserveur-hide-login", false, dirname(plugin_basename(__FILE__))."/languages/");

            global $PeproDevUPS_Core, $PeproDevUPS_Profile, $PeproDevUPS_Login;

            require_once plugin_dir_path(__FILE__) . "/core/main.php";
            require_once plugin_dir_path(__FILE__) . "/profile/profile.php";
            require_once plugin_dir_path(__FILE__) . "/login/login.php";

            $PeproDevUPS_Core    = new PeproDevUPS_Core();
            $PeproDevUPS_Profile = new PeproDevUPS_Profile();
            $PeproDevUPS_Login   = new PeproDevUPS_Login();

            register_deactivation_hook( __FILE__, function () { update_option("peprodevups_alert_viewed_yet", ""); });
        }
    }
    add_action("plugins_loaded", function () {
        global $PeproDevUPS;
        $PeproDevUPS = new PeproDevUPS();
    });
}
/*##################################################
Lead Developer: [amirhosseinhpv](https://hpv.im/)
##################################################*/
