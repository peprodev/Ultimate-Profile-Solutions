<?php
/*
Plugin Name: PeproDev Ultimate Profile Solutions
Description: One of the most Advanced and *The Ultimate Profile Solutions* for WordPress. FREE OF ANY CHARGE, UNLIMITED and OPEN-SOURCE FOREVER!
Contributors: amirhosseinhpv
Tags: functionality, pepro, management, administration, profile, login, register
Author: Pepro Dev. Group
Author URI: https://pepro.dev/
Plugin URI: https://pepro.dev/ups
Version: 3.1.0
Stable tag: 2.5.0
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
# @Last modified by:   amirhp-com
# @Last modified time: 2022/08/04 20:50:50
namespace PeproDev;
use PeproDev;

defined("ABSPATH") or die("<h2>Unauthorized Access!</h2><hr><small>PeproDev Ultimate Profile Solutions :: Developed by Pepro Dev. Group (<a href='https://pepro.dev/'>https://pepro.dev/</a>)</small>");

if (!class_exists("PeproDevUPS")) {
    final class PeproDevUPS
    {
        public function __construct()
        {
            define('PEPRODEVUPS', '3.1.0');
            define('PEPRODEVUPS_DIR', plugin_dir_path(__FILE__));
            define('PEPRODEVUPS_URL', plugins_url("", __FILE__));

            load_plugin_textdomain("peprodev-ups", false, dirname(plugin_basename(__FILE__))."/languages/");
            load_plugin_textdomain("wpserveur-hide-login", false, dirname(plugin_basename(__FILE__))."/languages/");

            global $PeproDevUPS_Core, $PeproDevUPS_Profile, $PeproDevUPS_Login;

            require_once plugin_dir_path(__FILE__) . "/core/main.php";
            require_once plugin_dir_path(__FILE__) . "/login/login.php";
            require_once plugin_dir_path(__FILE__) . "/profile/profile.php";

            $PeproDevUPS_Core    = new \PeproDev\PeproDevUPS_Core;
            $PeproDevUPS_Profile = new \PeproDev\PeproDevUPS_Profile;
            $PeproDevUPS_Login   = new \PeproDev\PeproDevUPS_Login;

            register_deactivation_hook( __FILE__, function () { update_option("peprodevups_alert_viewed_yet", ""); });
        }
    }
    add_action("plugins_loaded", function () {
        global $PeproDevUPS;
        $PeproDevUPS = new PeproDevUPS;
    });
}
/*##################################################
Lead Developer: [amirhosseinhpv](https://hpv.im/)
##################################################*/
