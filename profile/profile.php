<?php
# @Last modified by:   amirhp-com
# @Last modified time: 2022/05/12 00:54:45
namespace PeproDev;
use PeproDev;

if (!class_exists("PeproDevUPS_Profile")) {
    class PeproDevUPS_Profile
    {
        public $parent;
        public $td;
        public $priority;
        public $id;
        public $hwnd;
        public $instance;
        public $menu_label;
        public $page_label;
        public $icon_html;
        public $current_version;
        public $date_last_edit;
        public $wp_tested;
        public $wp_minimum;
        public $wc_tested;
        public $wc_minimum;
        public $php_minimum;
        public $php_recomonded;
        public $pepc_tested;
        public $pepc_minimum;
        public $setting_slug;
        public $activation_status;
        public $html_wrapper;
        public $ajax_hndlr;
        public $developer;
        public $developerURI;
        public $author;
        public $authorURI;
        public $user_modern;
        public $copyright;
        public $license;
        public $licenseURI;
        public $pluginURI;
        public $lang;
        public $db_slug;
        public $db_table;
        public $icon;
        public $url;
        public $description;
        public $file;
        public $assets_url;
        public $assets_url_as;
        public $assets_dir;
        private $setting_options;
        public function __construct()
        {
            global $wpdb, $wp;
            $this->id                = "peprocoreprofile";
            $this->td                = "peprodev-ups";
            $this->priority          = 4;
            $this->hwnd              = __CLASS__;
            $this->current_version   = "4.3.0";
            $this->assets_url        = plugins_url("/", __FILE__);
            $this->assets_url_as     = plugins_url("/assets/", __FILE__);
            $this->script_version    = current_time("timestamp");
            $this->assets_dir        = plugin_dir_path(__FILE__);
            $this->instance          = $this;
            $this->file              = plugin_basename(__FILE__);
            $this->icon              = "{$this->assets_url}/legacy/images/logo.png";
            $this->setting_slug      = "profile";
            $this->db_slug           = "pc_profile";
            $this->db_table          = "{$wpdb->prefix}pepro_core_profile";
            $this->tbl_subscribers   = "{$wpdb->prefix}peprofile_subscribers";
            $this->tbl_notif         = "{$this->db_table}_notif";
            $this->tbl_sections      = "{$this->db_table}_sections";
            $this->menu_label        = __("Profile Design", "peprodev-ups");
            $this->page_label        = __("Profile Setting", "peprodev-ups");
            $this->title             = __("PeproDev Ultimate Profile Solutions — Profile", "peprodev-ups");
            $this->icon_html         = "<i class=\"material-icons\">account_circle</i>";
            $this->activation_status = "PeproDevUPS_Core___{$this->setting_slug}";
            $this->save_prefix       = "PeproDevUPS_Core___loginregister";
            $this->html_wrapper      = array($this,"htmlwrapper");
            $this->ajax_hndlr        = array($this,"admin_side_ajax_handler");
            $this->useLD             = function_exists("sfwd_lms_has_access");
            $this->lang              = dirname(plugin_basename(__FILE__))."/languages/";
            $this->notifs_js         = "";
            $this->user_modern       = "yes" == get_option("{$this->save_prefix}-use_modern");
            $this->modern_dir        = plugin_dir_path(__FILE__) . "/modern/";
            $this->modern_assets     = apply_filters("peprofile_get_modern_assets_url", plugins_url("/modern", __FILE__));
            $this->use_front_fa      = "yes" == get_option("{$this->save_prefix}-use_fa"); // use font-awesome font in front-end
            $this->use_front_fo      = "yes" == get_option("{$this->save_prefix}-use_irfo"); // use farsi-iranyekan font in front-end
            $this->custom_css        = get_option("{$this->activation_status}-css", "");
            $this->custom_js         = get_option("{$this->activation_status}-js", "");
            $this->copyright         = sprintf(__("Copyright (c) %s Pepro Dev, All rights reserved", "peprodev-ups"), date("Y"));
            $this->setting_options   = array(
                array(
                  "name" => "{$this->db_slug}_general",
                  "data" => array(
                      "{$this->activation_status}"                   => "0",
                      "{$this->db_slug}-clearunistall"               => "no",
                      "{$this->db_slug}-cleardbunistall"             => "no",
                      "{$this->activation_status}-css"               => "",
                      "{$this->activation_status}-js"                => "",
                      "{$this->activation_status}-logo"              => "",
                      "{$this->activation_status}-profile-dash-page" => "profile",
                      "{$this->activation_status}-logo-id"           => "",
                      "{$this->activation_status}-showwelcome"       => "true",
                      "{$this->activation_status}-woocommercestats"  => "true",
                      "{$this->activation_status}-woocommerceorders" => "true",
                      "{$this->activation_status}-showcustomtext"    => "true",
                      "{$this->activation_status}-customhtml"        => "",
                      "{$this->activation_status}-headerhook"        => "no",
                      "{$this->activation_status}-footerhook"        => "no",
                      "{$this->activation_status}-customposition"    => "p2",
                  )
              ),
            );

            if (isset($_GET["peprodev_subscribers"]) && "export_csv" === trim($_GET["peprodev_subscribers"])){
              if (current_user_can("administrator")) {
                $data = array();
                $notifs = $wpdb->get_results($wpdb->prepare("SELECT * FROM `$this->tbl_subscribers` ORDER BY `date_created` DESC"));
                if (false !== $notifs && 0 !== $notifs && !empty($notifs)) {
                  foreach ($notifs as $notif) {
                    $data[] = array(
                      "date"   => $notif->date_created,
                      "name"   => $notif->name,
                      "mobile" => $notif->mobile,
                      "email"  => $notif->email,
                      "uid"    => $notif->user,
                    );
                  }
                }
                else{
                  wp_die(__("No subscriber found!", "notifications-priority", $this->td));
                }
                $filename = "PeproDevUPS Subscribers " . current_time("timestamp").".csv";
                header('Content-Description: File Transfer');
                header("Content-Type: application/vnd.ms-excel; charset=utf-8");
                header('Content-Transfer-Encoding: binary');
                header("Content-Disposition: attachment; filename=\"$filename\"");
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: no-cache');
                header('Pragma: public');
                echo "\xEF\xBB\xBF"; // UTF-8 BOM
                $this->ExportFileAsExcel($data);
              }
            }

            add_action("init", array( $this, "init_plugin" ));
            if (!current_user_can('edit_posts') && !is_admin()) {
              show_admin_bar(false);
              add_filter('show_admin_bar', '__return_false');
            }
            add_action("template_redirect", array( $this, "remove_yoast_wpseo") );
            add_action("admin_bar_menu",  array( $this, "admin_bar_menu_items"), 31);
            add_filter("get_avatar_url",  array( $this, "change_avatar_url"), 1, 3);
        }
        public function ExportFileAsExcel($records = array(), $delimiter = "," )
        {
          if(!empty($records)){
            $heading = false;
            foreach($records as $row) {
              if(!$heading) {
                echo implode($delimiter, array_keys($row)) . "\n";
                $heading = true;
              }
              echo implode($delimiter, array_values($row)) . "\n";
            }
            exit;
          }
        }
        public function get_profile_page($queryVar=null)
        {
          $profile_page = get_option("{$this->activation_status}-profile-dash-page", false);
          if (!get_post($profile_page)) {
            $profile_page = false;
          }
          if ($queryVar && null !== $queryVar && is_array($queryVar)){
            $url          = $profile_page ? get_permalink($profile_page) : home_url();
            $lang         = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : "en";
            $url          = apply_filters( 'wpml_permalink', $url , $lang);
            $profile_page = add_query_arg( $queryVar, $url);
          }
          return apply_filters( "peprofile_get_profile_page", $profile_page, $queryVar);
        }
        public function admin_bar_menu_items($wp_admin_bar)
        {
          wp_register_style("{$this->id}-adminbar_styles", false, [], false, "all");
          wp_add_inline_style( "{$this->id}-adminbar_styles", '#wpadminbar #wp-admin-bar-peprocoreprofile .ab-icon::before {content: "\f110";top: 2px;}');
          wp_enqueue_style("{$this->id}-adminbar_styles");
          $profile_page = $this->get_profile_page(["i"=>current_time("timestamp")]);
          $wp_admin_bar->add_node(array(
            'id'    => $this->id,
            'title' => '<span class="ab-icon" aria-hidden="true"></span>' . __("User Dashboard","peprodev-ups"),
            'href'  => $profile_page,
            'meta'  => array( 'class' => 'custom-node-class' ),
          ));
          foreach ($this->peprofile_get_nav_items_array() as $key => $value) {
            $wp_admin_bar->add_menu(array(
              'id'     => "{$this->id}{$key}",
              'parent' => $this->id,
              'title'  => wp_kses_data($value["title"]),
              'href'   => $value["url"],
            ));
          }
        }
        public function remove_yoast_wpseo()
        {
          /** Removes output from Yoast SEO on the frontend */
          $dashpage = get_option("{$this->activation_status}-profile-dash-page","");
          if ( !empty($dashpage) && is_single($dashpage)) {
            if (class_exists("\WPSEO_Options")){
              $front_end = YoastSEO()->classes->get( Yoast\WP\SEO\Integrations\Front_End_Integration::class );
              remove_action( 'wpseo_head', [ $front_end, 'present_head' ], -9999 );
            }
          }
        }
        public function init_plugin()
        {
          $this->url = $this->get_profile_page(["i"=>current_time("timestamp")]);
          add_filter( "peprocore_{$this->id}_dashboard_nav_menuitems", function(){
            return array(
                      array(
                        "title"    => $this->menu_label,
                        "titleW"   => $this->page_label,
                        "icon"     => $this->icon_html,
                        "link"     => "@{$this->setting_slug}",
                        "fn"       => $this->html_wrapper,
                        "id"       => $this->id,
                        "priority" => $this->priority,
                        "activation_status"=> $this->activation_status,
                      ),
                      array(
                        "title"    => __("Sections", "peprodev-ups"),
                        "titleW"   => __("Manage Sections", "peprodev-ups"),
                        "icon"     => "<i class=\"material-icons\">manage_accounts</i>",
                        "link"     => "@sections",
                        "fn"       => array($this,"htmlwrapper_sections"),
                        "id"       => "{$this->id}_sections",
                        "priority" => $this->priority+0.1,
                        "activation_status"=> $this->activation_status,
                      ),
                      array(
                        "title"    => __("Notifications", "peprodev-ups"),
                        "titleW"   => __("Manage Notifications", "peprodev-ups"),
                        "icon"     => "<i class=\"material-icons\">notifications</i>",
                        "link"     => "@notifications",
                        "fn"       => array($this,"htmlwrapper_notifs"),
                        "id"       => "{$this->id}_notifs",
                        "priority" => $this->priority+0.2,
                        "activation_status"=> $this->activation_status,
                      ),
                      array(
                        "title"    => __("Shortcodes", "peprodev-ups"),
                        "titleW"   => __("Manage Shortcodes", "peprodev-ups"),
                        "icon"     => "<i class=\"material-icons\">auto_fix_high</i>",
                        "link"     => "@shortcodes",
                        "fn"       => array($this,"htmlwrapper_shortcodes"),
                        "id"       => "{$this->id}_shortcodes",
                        "priority" => $this->priority+0.1,
                        "activation_status"=> $this->activation_status,
                      ),
                      array(
                        "title"    => __("Newsletter", "peprodev-ups"),
                        "titleW"   => __("Manage Newsletter Subscriber", "peprodev-ups"),
                        "icon"     => "<i class=\"material-icons\">mail</i>",
                        "link"     => "@newsletter",
                        "fn"       => array($this,"htmlwrapper_newsletter"),
                        "id"       => "{$this->id}_newsletter",
                        "priority" => $this->priority+0.2,
                        "activation_status" => $this->activation_status,
                      ),
                    );
          });
          add_filter( "peprocore_dashboard_nav_menuitems", function ($s) {
            $d = apply_filters( "peprocore_{$this->id}_dashboard_nav_menuitems", array() ); return array_merge( $s, $d ); }, 11
          );
          add_action( "peprocore_handle_ajaxrequests",       $this->ajax_hndlr, 11);
          add_action( "delete_user",                         array($this, "after_delete_user"));
          add_action( "admin_init",                          array($this, "admin_init"));
          add_action( "wp_ajax_nopriv_{$this->id}",          array($this, "front_side_ajax_handler"));
          add_action( "wp_ajax_{$this->id}",                 array($this, "front_side_ajax_handler"));
          if (!is_admin()){
            add_filter("the_content",                        array($this, "the_content"), 1);
            add_shortcode("pepro-profile",                   array($this, "peprofile_shortcode_main"));
            add_shortcode("user",                            array($this, "peprofile_shortcode_user"));
            add_shortcode("pepro-profile-url",               array($this, "peprofile_shortcode_profile_url"));
            add_shortcode("profile-card-1",                  array($this, "peprofile_shortcode_card_1"));
            add_shortcode("profile-card-2",                  array($this, "peprofile_shortcode_card_2"));
            add_shortcode("profile-card-3",                  array($this, "peprofile_shortcode_card_3"));
            add_shortcode("profile-card-4",                  array($this, "peprofile_shortcode_card_4"));
            add_shortcode("profile-wc-stats",                array($this, "peprofile_shortcode_wc_stats"));
            add_shortcode("profile-wc-orders",               array($this, "peprofile_shortcode_wc_orders"));
            add_shortcode("profile-wc-downloads",            array($this, "peprofile_shortcode_wc_downloads"));
          }
          add_filter( "peprofile_shortcodes",                array($this, "peprofile_shortcodes_list"), 10, 1);
          add_filter( "media_buttons",                       array($this, "media_buttons_add_new"), PHP_INT_MAX );
          if ($this->_ld_activated()) {
            add_shortcode("profile-ld-enrolled",             array($this, "peprofile_shortcode_ld_enrolled"));
          }
          if (get_current_user_id()){
            add_filter( "template_include",                  array($this, "template_include"));
            add_filter( "theme_page_templates",              array($this, "theme_page_templates"), 10, 4);
          }
          add_filter( "body_class",                          array($this, "body_class"), 1, 1);
          add_filter( "peprofile_dashboard_slugs",           array($this, "peprofile_dashboard_slugs"), 10, 1);
          add_filter( "display_post_states",                 array($this, "display_post_states"), 10, 2);
          add_filter( "peprofile_get_nav_items",             array($this, "peprofile_get_nav_items"),10,1);
          add_filter( "peprofile_get_nav_items",             array($this, "peprofile_get_custom_user_nav_items"),11,1);
          add_action( "peprofile_get_template_part_nav-bar", array($this, "peprofile_get_template_part_nav"));
          add_filter( "pre_get_document_title",              array($this, "change_wp_title"));

          $this->peprofile_custom_user_nav_items_hndlr();
          $this->CreateDatabase();
          $this->add_special_post();

          if ($this->_wc_activated()) {
              add_action( 'woocommerce_available_downloads', array($this, 'woocommerce_order_downloads_table'), 20);
          }
          if ($this->_vc_activated()) {
              add_action("vc_before_init", array($this,"integrate_With_VC"));
              if (function_exists('vc_add_shortcode_param')) {
                  vc_add_shortcode_param("{$this->id}_custom", array($this,'vc_add_custom_element_param'), plugins_url("/assets/js/vc.init.js", __FILE__));
                  vc_add_shortcode_param("{$this->id}_about", array($this,'vc_add_pepro_about'), plugins_url("/assets/js/vc.init.js", __FILE__));
              }
          }
          add_action( 'admin_init',        array( $this, "redirect_url_if_no_access"), 1 );
          add_action( 'after_setup_theme', array( $this, "after_setup_theme"));

        }
        public function change_wp_title()
        {
          $title = $this->get_title();
          return !empty($title) ? $title . " &#8211; " . get_the_title() : get_the_title();
        }
        public function get_title()
        {
          $this->cur_slug = isset($_GET['section']) ? sanitize_text_field(trim($_GET['section'])) : "home";
          foreach ($this->peprofile_get_nav_items_array() as $key => $value) {
            if (isset($value["subitems"]) && !empty($value["subitems"])) {
                foreach ($value["subitems"] as $tq => $child) {
                  if ($this->cur_slug == $child["slug"]) return wp_kses($child["title"], "", "");
                }
            }
            if ($this->cur_slug == $key) return wp_kses($value["raw_title"], "", "");
          }
        }
        public function redirect_url_if_no_access()
        {
          if (!current_user_can('edit_posts') && ( !wp_doing_ajax() ) ) {
            $url = apply_filters("peprofile_admin_redirect_url_if_no_access", home_url());
            wp_safe_redirect( $url );
            exit;
          }
        }
        public function after_setup_theme()
        {
          if (!current_user_can('edit_posts')  && !is_admin()) {
            show_admin_bar(false);
            add_filter( 'show_admin_bar', '__return_false');
          }
        }
        public function body_class( $classes )
        {
          global $post;
          if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'pepro-profile') ) {
            wp_enqueue_script( 'wpdocs-script');
            $current = isset($_GET["section"]) ? "section-" . sanitize_text_field(trim($_GET["section"])) : "";
            $classez = (array) apply_filters("peprofile_body_class", ["peprodev-ups", $current]);
            $classes = array_merge( $classes, $classez);
          }
          return $classes;
        }
        public function the_content ( $content )
        {
          return $content;
        }
        public function peprofile_shortcodes_list($array)
        {
          $array = array(
            "user" => array (
              "sample" => "[user meta='' default='']".PHP_EOL."DEFAULT CONTENT".PHP_EOL."[/user]",
              "title"  => __("Get User info","peprodev-ups"),
              "syntax" => array(
                "meta"    => __("The meta key to retrieve","peprodev-ups"),
                "default" => __("Default data if meta does not exist or is empty","peprodev-ups"),
              )
            ),
            "pepro-profile" => array (
              "sample" => "[pepro-profile]",
              "title"  => __("Display Pepro Profile on front-end, requires it's special page template","peprodev-ups"),
            ),
            "pepro-profile-url" => array(
              "sample" => "[pepro-profile-url]",
              "title"  => __("Returns Profile's Dashboard URL/Link","peprodev-ups"),
              "syntax" => array(
                "button"  => __("Dashboard URL call-to-action anchor text, leave empty to return url","peprodev-ups"),
                "class"   => __("Dashboard URL call-to-action anchor class","peprodev-ups"),
                "section" => __("Dashboard Section, leave empty to use dashboard home","peprodev-ups"),
                "extras"  => __("Content instead of Dashboard URL call-to-action anchor, use {url} to replace with anchor link","peprodev-ups"),
                __("content between shortcode tags","peprodev-ups") => __("Content instead of Dashboard URL call-to-action, use {url} to replace with anchor link","peprodev-ups"),
              )
            ),
            "profile-card-1" => array (
              "sample" => "[profile-card-1]".PHP_EOL."EXTRA CONTENT".PHP_EOL."[/profile-card-1]",
              "title"  => __("Profile Card 1","peprodev-ups"),
            ),
            "profile-card-2" => array (
              "sample" => "[profile-card-2 title='']".PHP_EOL."EXTRA CONTENT".PHP_EOL."[/profile-card-2]",
              "title"  => __("Profile Card 2","peprodev-ups"),
              "syntax" => array(
                "title" => __("Title","peprodev-ups"),
              )
            ),
            "profile-card-3" => array (
              "sample" => "[profile-card-3 title='' icon='' bg_color='' padding='']".PHP_EOL."EXTRA CONTENT".PHP_EOL."[/profile-card-3]",
              "title"  => __("Profile Card 3","peprodev-ups"),
              "syntax" => array(
                "title"    => __("Title","peprodev-ups"),
                "icon"     => __("Icon class","peprodev-ups"),
                "bg_color" => __("Background Color (CSS)","peprodev-ups"),
                "padding"  => __("Padding (CSS)","peprodev-ups"),
              )
            ),
            "profile-card-4" => array (
              "sample" => "[profile-card-4 title='' icon='']".PHP_EOL."EXTRA CONTENT".PHP_EOL."[/profile-card-4]",
              "title"  => __("Profile Card 4","peprodev-ups"),
              "syntax" => array(
                "title" => __("Title","peprodev-ups"),
                "icon"  => __("Icon class","peprodev-ups"),
              )
            ),
            "profile-wc-stats" => array(
              "sample" => "[profile-wc-stats]",
              "title"  => __("WooCommerce Stats","peprodev-ups"),
            ),
            "profile-wc-orders" => array(
              "sample" => "[profile-wc-orders]",
              "title"  => __("WooCommerce Orders","peprodev-ups"),
              "syntax" => array(
                "limit" => __("Limit items to show","peprodev-ups"),
              )
            ),
            "profile-wc-downloads" => array (
              "sample" => "[profile-wc-downloads category='nID']",
              "title"  => __("WooCommerce Downloads","peprodev-ups"),
              "syntax" => array(
                "category" => __("WooCommerce Category ID","peprodev-ups"),
              )
            ),
            "profile-ld-enrolled" => array (
              "sample" => "[profile-ld-enrolled category='nID']",
              "title"  => __("LearnDash Enrolled Courses","peprodev-ups"),
              "syntax" => array(
                "category" => __("LearnDash Category ID","peprodev-ups"),
              )
            ),
            "pepro-sms-subscription" => array (
              "sample" => "[pepro-sms-subscription]",
              "title"  => __("A form for Mobile Newsletter subscription (SMS Verified Memebers)","peprodev-ups"),
              "syntax" => array(
                "btnclass"  => __("Submit button classes","peprodev-ups"),
                "subscribe" => __("Translation for Subscribe","peprodev-ups"),
                "mobile"    => __("Translation for Mobile","peprodev-ups"),
                "name"      => __("Translation for Name","peprodev-ups"),
                "verify"    => __("Translation for Verify","peprodev-ups"),
              )
            ),
          );
          return $array;
        }
        public function remove_us_css()
        {
          wp_dequeue_style("us-core");
          wp_dequeue_script("us-core");
          wp_dequeue_style("us-font-awesome-duotone");
          wp_dequeue_style("font-awesome");
          wp_dequeue_style("tp-material-icons");
          wp_dequeue_style("rs-roboto");
          wp_dequeue_style("revslider-material-icons");

        }
        public function change_avatar_url($url, $id_or_email, $args)
        {
          $user = false;
          $local = esc_url("{$this->assets_url}assets/img/account.png");
          if (is_numeric($id_or_email)) {
            $user = get_user_by('id', absint( $id_or_email ));
          }
          if (is_string($id_or_email)) {
            $user = get_user_by('email', $id_or_email);
          }
          if ($user){
            $saved = get_the_author_meta("profile_image", $user->ID);
            $url = !empty($saved) ? $saved : $url;
          }
          return $url;
        }
        public function add_private_notification($args=array())
        {
          $args = wp_parse_args( $args, array(
            "title"          => "",
            "content"        => "",
            "icon"           => "fas fa-comment-alt-lines",
            "color"          => "green", // green red orange blue dark
            "priority"       => "5",
            "action_title_1" => "",
            "action_title_2" => "",
            "action_url_1"   => "",
            "action_url_2"   => "",
            "users_list"     => "", // comma-seperated users ids | all
            "schedule"       => "", // 2021-08-06 15:25:45
          ));

          $usersListArray = explode(",", $args["users_list"]);
          array_walk($usersListArray, "trim");

          switch ($args["color"]) {
            case "green":
              $args["color"] = "bg-c1";
              break;
            case "red":
              $args["color"] = "bg-c2";
              break;
            case "orange":
              $args["color"] = "bg-c3";
              break;
            case "blue":
              $args["color"] = "bg-c4";
              break;
            case "dark":
              $args["color"] = "bg-c5";
              break;
            default:
              $args["color"] = $args["color"];
              break;
          }

          $dataArray = array(
            'title'            => $args["title"],
            'content'          => $args["content"],
            'icon'             => $args["icon"],
            'color'            => $args["color"],
            'priority'         => $args["priority"],
            'action_title_1'   => $args["action_title_1"],
            'action_url_1'     => $args["action_url_1"],
            'action_title_2'   => $args["action_title_2"],
            'action_url_2'     => $args["action_url_2"],
            'users_list'       => $args["users_list"],
            'date_scheduled'   => $args["schedule"],
          );
          $dataArrayTypes = array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', );
          if (empty(trim($args["schedule"]))) { unset($dataArray["date_scheduled"]); unset($dataArrayTypes[0]); }
          global $wpdb;
          $add = $this->AddnewNotification2Db($dataArray, $dataArrayTypes);
          if ($add) {
            $id = $wpdb->insert_id;
            if (!empty($usersListArray) && $args["users_list"] != "all" ) {
              $this->AssignNotification2Users($usersListArray, $id);
            }
          }

        }
        public function media_buttons_add_new()
        {
          $current_screen = get_current_screen();
          if ( $current_screen && 'toplevel_page_peprodev-ups' === $current_screen->base ){
            ?>
            <button href="#" class="button <?php echo "peprodev-ups";?>_shortcodehandler peprofile-open-box">
              <i class='fa fa-external-link'></i>
              <?php echo $this->title;?>
            </button>
            <div class="popup-shortcode-select hide">
              <button href="#" class="button <?php echo "peprodev-ups";?>_shortcodehandler _shortcodehandleruser">
                <i class='fa fa-'></i>
                <?php esc_html_e("User info","peprodev-ups");?>
              </button>
              <button href="#" class="button <?php echo "peprodev-ups";?>_shortcodehandler _shortcodehandlerwhitecard">
                <i class='fa fa-'></i>
                <?php esc_html_e("White Card","peprodev-ups");?>
              </button>
              <button href="#" class="button <?php echo "peprodev-ups";?>_shortcodehandler _shortcodehandlercard">
                <i class='fa fa-'></i>
                <?php esc_html_e("Grey Card","peprodev-ups");?>
              </button>
              <button href="#" class="button <?php echo "peprodev-ups";?>_shortcodehandler _shortcodehandlerbigcard">
                <i class='fa fa-'></i>
                <?php esc_html_e("Colored Card","peprodev-ups");?>
              </button>
              <button href="#" class="button <?php echo "peprodev-ups";?>_shortcodehandler _shortcodehandlerblackcard">
                <i class='fa fa-'></i>
                <?php esc_html_e("Black Card","peprodev-ups");?>
              </button>
              <?php
              if ($this->_wc_activated()) {
                ?>
                  <button href="#" class="button <?php echo "peprodev-ups";?>_shortcodehandler _shortcodehandlerstats">
                    <i class='fa fa-'></i>
                    <?php esc_html_e("WC Stats","peprodev-ups");?>
                  </button>
                  <button href="#" class="button <?php echo "peprodev-ups";?>_shortcodehandler _shortcodehandlerorders">
                    <i class='fa fa-'></i>
                    <?php esc_html_e("WC Orders","peprodev-ups");?>
                  </button>
                  <button href="#" class="button <?php echo "peprodev-ups";?>_shortcodehandler _shortcodehandlerdownloads">
                    <i class='fa fa-'></i>
                    <?php esc_html_e("WC Downloads","peprodev-ups");?>
                  </button>
                <?php
              }
              if ($this->_ld_activated()) {
                ?>
                <button href="#" class="button <?php echo "peprodev-ups";?>_shortcodehandler _shortcodehandlerldenrolled">
                    <i class='fa fa-'></i>
                    <?php esc_html_e("Learndash Enrolled Courses","peprodev-ups");?>
                </button>
                <?php
              }
              ?>
            </div>
            <?php
          }
        }
        public function peprofile_shortcode_main($atts=array(),$content="")
        {
          global $wp;
          do_action("peprofile_before_shortcode_print", $atts, $content);
          wp_enqueue_script("jquery");
          if ($this->user_modern) {
            $this->peprofile_get_template_part("dash-index");
            return (new \PeproDev\dashboard)->get_body();
          }
          else{
            add_filter( "the_content", function ( $content ) { $this->peprofile_get_template_part("dash-index"); return; }, 999 );
          }
        }
        public function peprofile_shortcode_wc_downloads($atts=array(),$content="")
        {
          $atts = extract(shortcode_atts(array('category'=>''), $atts));
          if (!get_current_user_id())
            return;
          ob_start();
          $downloads     = WC()->customer->get_downloadable_products();
          $has_downloads = (bool) $downloads;

          if ($has_downloads ){

              $array_categories = array();

              foreach ( $downloads as $download ){
                $terms = wp_get_post_terms( $download["product_id"], 'product_cat' );
                foreach( $terms as $cat ){
                  if( 0 == $cat->parent ){
                    if (!empty($category)){
                      if ($cat->term_id == $category)
                        $array_categories[$cat->term_id] = $cat->name;
                    }else{
                      $array_categories[$cat->term_id] = $cat->name;
                    }
                  }
                }
              }

              foreach ($array_categories as $cat_id => $catName) {
                ?>
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-md-12"><div class="overview-wrap"><h2 class="title-2 m-b-25"><?php echo $catName?></h2></div></div>
                  </div>
                  <div class="row m-t-25">
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
                            <?php
                              foreach ( $downloads as $download ) {
                                $array_current_categories = array();

                                $terms = wp_get_post_terms( $download["product_id"], 'product_cat' );
                                foreach( $terms as $cat ){
                                  if( 0 == $cat->parent ){
                                    $array_current_categories[] = $cat->term_id;
                                  }
                                }

                                if (!empty($category)){
                                  $check_id = $category;
                                }else{
                                  $check_id = $cat_id;
                                }

                                $array_current_categories = array_unique($array_current_categories);

                                if (!in_array($check_id, $array_current_categories))
                                  continue;

                                ?>
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
                                <?php
                              }
                            ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
              }

          } else {
              ?>
              <div class="col-12">
                  <div class="card">
                      <div class="card-body">
                        <div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
                          <?php esc_html_e('No downloads available yet.', 'woocommerce'); ?>
                          <a class="woocommerce-Button button btn btn-outline-primary <?php echo is_rtl() ? "float-right" : "float-left";?>" href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
                            <?php esc_html_e('Browse products', 'woocommerce'); ?>
                          </a>
                        </div>
                      </div>
                  </div>
              </div>
              <?php
          }
          $tcona = ob_get_contents();
          ob_end_clean();
          return $tcona;
        }
        public function peprofile_shortcode_ld_enrolled($atts=array(),$content="")
        {
          $atts = extract(shortcode_atts(array(
            "user_id" => "",
            "category" => "",
          ),$atts));
          if (empty($user_id)) $user_id = get_current_user_id();
          if( empty( $user_id ) ) return array();
          $courses = learndash_user_get_enrolled_courses( $user_id, array(), true );
          $printesomething = false;
          $array_categories = array();
          if ($courses && !empty($courses)){
            ob_start();
            $columns_array = apply_filters( "peprofile_ld_courses_column", array(
              "name" => __("Name","peprodev-ups"),
              "view" => __("View","peprodev-ups"),
            ));
            foreach ( $courses as $course ){
              $terms = wp_get_post_terms( $course, 'ld_course_category' );
              foreach( $terms as $cat ){
                if( 0 == $cat->parent ){
                  if (!empty($category)){
                    if ($cat->term_id == $category)
                      $array_categories[$cat->term_id] = $cat->name;
                  }else{
                    $array_categories[$cat->term_id] = $cat->name;
                  }
                }
              }
            }
            foreach ( $array_categories as $cat_id => $catName) {
              ?>
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12"><div class="overview-wrap"><h2 class="title-2 m-b-25"><?php echo $catName?></h2></div></div>
                </div>
                <div class="row m-t-25">
                  <div class="col-lg-12">
                    <div class="table-responsive table--no-card m-b-40">
                      <table class="table table-borderless table-striped table-earning">
                        <thead>
                          <tr>
                            <?php foreach ( $columns_array as $column_id => $column_name ) : ?>
                            <th class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
                            <?php endforeach; ?>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            foreach ( $courses as $course ) {
                              $array_current_categories = array();

                              $terms = wp_get_post_terms( $course, 'ld_course_category' );
                              foreach( $terms as $cat ){
                                if( 0 == $cat->parent ){
                                  $array_current_categories[] = $cat->term_id;
                                }
                              }

                              $array_current_categories = array_unique($array_current_categories);

                              if (!empty($category)){
                                $check_id = $category;
                              }else{
                                $check_id = $cat_id;
                              }

                              if (!in_array($check_id, $array_current_categories))
                                continue;

                              $printesomething = true;
                              ?>
                              <tr>
                                <?php foreach ( $columns_array as $column_id => $column_name ) : ?>
                                  <td class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
                                    <?php
                                      switch ( $column_id ) {
                                        case 'name':
                                          echo esc_html( get_the_title( $course ) );
                                          break;
                                        case 'view':
                                          echo '<a href="' . esc_url( get_permalink( $course ) ) . '" class="woocommerce-MyAccount-downloads-file button alt btn btn-sm btn-outline-primary btn-block '.("text-left").'">
                                          <i class="fas fa-user-graduate mr-10 ml-10"></i> ' . esc_html( get_the_title( $course ) ) . '</a>';
                                          break;
                                      }
                                    ?>
                                  </td>
                                <?php endforeach; ?>
                              </tr>
                              <?php
                            }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <?php
            }

            $tcona = ob_get_contents();
            ob_end_clean();

            if (!$printesomething || empty($array_categories) ){
              return do_shortcode( $content );
            }

            return $tcona;
          }else{
            return do_shortcode( $content );
          }
          if (!$printesomething || empty($array_categories) ){
            return do_shortcode( $content );
          }
        }
        public function peprofile_shortcode_card_1($atts=array(),$content="")
        {
          $atts = extract(shortcode_atts(array('class'=>'', 'style'=>""),$atts));
          return "<div class=\"row ".esc_attr( $class )."\"><div class=\"col-12 alert-primary-top\"><div class=\"au-card recent-report\" style='padding-bottom: 40px;margin-bottom: 30px; $style'><div class=\"au-card-inner\">".$this->filter_content($content)."</div></div></div></div>";
        }
        public function peprofile_shortcode_card_2($atts=array(),$content="")
        {
          $atts = extract(shortcode_atts(array('title'=>'', 'class'=>''),$atts));
          return '<div class="row '.esc_attr( $class ).'">
                            <div class="col-12">
                              <div class="card">
                                <div class="card-header">'.esc_html( $title ).'</div>
                                <div class="card-body">'.$this->filter_content($content).'</div>
                              </div>
                            </div>
                          </div>';
        }
        public function peprofile_shortcode_card_3($atts=array(),$content="")
        {
          $atts = extract(shortcode_atts(array('title'=>'','icon'=>'zmdi zmdi-comment-text', "bg_color" => "", "padding" => "", "class"=>"" ),$atts));
          return '<div class="row '.esc_attr( $class ).'">
            <div class="col-lg-12">
              <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
                <div class="au-card-title" style="padding: '.$padding.';">
                  <div class="bg-overlay bg-overlay--blue" style="background: '.$bg_color.';"></div>
                  <h3 style="margin: 0 !important;padding: 0 !important;"><i class="'.esc_attr( $icon ).'"></i>'.esc_html( $title ).'</h3>
                </div>
                <div class="au-inbox-wrap js-inbox-wrap">
                  <div class="au-message js-list-load">
                    <div class="au-message-list" style="height: auto;padding: 2rem 1rem;">'.$this->filter_content($content).'</div>
                  </div>
                </div>
              </div>
            </div>
          </div>';
        }
        public function peprofile_shortcode_card_4($atts=array(),$content="")
        {
          $atts = extract(shortcode_atts(array('title'=>'', 'icon'=>'zmdi zmdi-comment-text', "class"=>""),$atts));
          return '<div class="row '.esc_attr( $class ).'">
            <div class="col-lg-12">
              <div class="table-responsive table--no-card m-b-40">
                <table class="table table-borderless table-striped table-earning">
                  <thead>
                    <tr><th><span class="nobr">'.esc_html( $title ).'</span></th></tr>
                  </thead>
                  <tbody><tr><td style="white-space: normal;">'.$this->filter_content($content).'</td></tr></tbody>
                </table>
              </div>
            </div>
          </div>';
        }
        public function peprofile_shortcode_wc_stats($atts=array(),$content="")
        {
          if (!$this->_wc_activated()) return "";
          $atts = extract(shortcode_atts(array("class"=>""),$atts));
          ob_start();
          ?>
            <div class="row <?php echo esc_attr( $class ); ?>">
              <div class="<?php echo (!class_exists('\Woo_Wallet_Wallet')) ? "col-sm-6 col-lg-4" : "col-sm-6 col-lg-3";?>">
                  <div class="overview-item overview-item--c3">
                      <div class="overview__inner">
                          <div class="overview-box clearfix">
                              <div class="icon">
                                  <i class="fa fa-box-open"></i>
                              </div>
                              <div class="text">
                                <h2><?php echo $this->get_customer_total_orders_by_status("wc-on-hold");?></h2>
                                <span><?php esc_html_e("on-hold orders","peprodev-ups");?></span>
                              </div>
                          </div>
                          <div class="overview-chart">
                          </div>
                      </div>
                  </div>
              </div>
              <div class="<?php echo (!class_exists('\Woo_Wallet_Wallet')) ? "col-sm-6 col-lg-4" : "col-sm-6 col-lg-3";?>">
                  <div class="overview-item overview-item--c1">
                      <div class="overview__inner">
                          <div class="overview-box clearfix">
                              <div class="icon">
                                  <i class="fas fa-shipping-fast"></i>
                              </div>
                              <div class="text">
                                <h2><?php echo $this->get_customer_total_orders_by_status("wc-processing");?></h2>
                                <span><?php esc_html_e("processing orders","peprodev-ups");?></span>
                              </div>
                          </div>
                          <div class="overview-chart">
                          </div>
                      </div>
                  </div>
              </div>
              <div class="<?php echo (!class_exists('\Woo_Wallet_Wallet')) ? "col-sm-6 col-lg-4" : "col-sm-6 col-lg-3";?>">
                  <div class="overview-item overview-item--c2">
                      <div class="overview__inner">
                          <div class="overview-box clearfix">
                              <div class="icon">
                                  <i class="fa fa-shopping-bag"></i>
                              </div>
                              <div class="text">
                                <h2><?php echo $this->get_customer_total_orders_by_status("wc-complete");?></h2>
                                <span><?php esc_html_e("completed orders","peprodev-ups");?></span>
                              </div>
                          </div>
                          <div class="overview-chart">
                          </div>
                      </div>
                  </div>
              </div>
              <?php
              if  (class_exists('\Woo_Wallet_Wallet')){
                ?>
                <div class="<?php echo (!class_exists('\Woo_Wallet_Wallet')) ? "" : "col-sm-6 col-lg-3";?>" >
                  <div class="overview-item overview-item--c4">
                    <div class="overview__inner">
                      <div class="overview-box clearfix">
                        <div class="icon">
                          <i class="fa fa-wallet"></i>
                        </div>
                        <div class="text">
                          <h2><?php echo $this->get_customer_get_credit_balance();?></h2>
                          <span><?php esc_html_e("your wallet balance","peprodev-ups");?></span>
                        </div>
                      </div>
                      <div class="overview-chart">
                      </div>
                    </div>
                  </div>
                </div>
                <?php
              }
              ?>

            </div>
          <?php
          $tcona = ob_get_contents();
          ob_end_clean();
          return $tcona;
        }
        public function peprofile_shortcode_wc_orders($atts=array(),$content="")
        {
          if (!$this->_wc_activated()) return "";
          $atts = extract(shortcode_atts(array('limit'=>'10'),$atts));
          ob_start();
          global $gloabal_woocommerce_my_account_my_orders_query_limit;
          $gloabal_woocommerce_my_account_my_orders_query_limit = $limit;
          add_filter( 'woocommerce_my_account_my_orders_query', function($a){ global $gloabal_woocommerce_my_account_my_orders_query_limit; $a["limit"] = $gloabal_woocommerce_my_account_my_orders_query_limit; return $a;},10,1);
          $this->peprofile_get_template_part("wc/orders");
          $tcona = ob_get_contents();
          ob_end_clean();
          return $tcona;
        }
        public function peprofile_shortcode_user($atts=array(),$content="")
        {
          $atts = extract(shortcode_atts(array('meta'=>'','default'=>''),$atts));
          if (!get_current_user_id()){
            if (!empty($default)){ return $default; }
            return do_shortcode($content);
          }
          if (empty($meta)){ $meta = "first_name" ; }

          return get_the_author_meta($meta, get_current_user_id());
        }
        public function peprofile_shortcode_profile_url($atts=array(),$content="")
        {
          $atts = extract(shortcode_atts(array(
            'button'  => '',
            'class'   => '',
            'section' => '',
            'extras'  => '',
          ),$atts));
          $link = $this->get_profile_page(["i"=>current_time("timestamp")]);
          if (!empty($section)){ $link = $this->get_profile_page(["section"=>$section]); }
          if (!empty($button)){
            return "<a href='$link' class='".esc_attr( $class )."' >$button</a>";
          }
          if (!empty($extras)){
            return str_replace("{url}", $link, $extras);
          }
          if (!empty($contnent)){
            return str_replace("{url}", $link, $contnent);
          }
          return $link;
        }
        public function peprofile_shortcode_sample($atts=array(),$content="")
        {
          $atts = extract(shortcode_atts(array(''=>''),$atts));
          ob_start();
          $tcona = ob_get_contents();
          ob_end_clean();
          return $tcona;
        }
        /* Template loader functions
        */
        public function peprofile_get_template_part( $slug, $name = null, $load = true )
        {
            do_action("peprofile_get_template_part_{$slug}", $slug, $name);
            do_action("peprofile_get_template_part_{$slug}-{$name}", $slug, $name);
            // fix for zephyr theme!
            wp_dequeue_style( "us-core" );
            wp_dequeue_style( "us-font-awesome-duotone" );
            wp_dequeue_style( "font-awesome" );
            wp_dequeue_script( "us-core" );

            // Setup possible parts
            $templates = array();
            if (isset($name) ) {
                $templates[] = "{$slug}-{$name}.php";
            }
            $templates[] = "$slug.php";
            // Allow template parts to be filtered
            $templates = apply_filters("peprofile_get_template_part", $templates, $slug, $name);
            // Return the part that is found
            return $this->peprofile_locate_template($templates, $load, false);
        }
        public function peprofile_locate_template( $template_names, $load = false, $require_once = true )
        {
            // No file found yet
            $located = false;

            // Try to find a template file
            foreach ( (array) $template_names as $template_name ) {

                // Continue if template is empty
                if (empty($template_name) ) continue;

                // Trim off any slashes from the template name
                $template_name = ltrim($template_name, '/');

                // Check child theme first
                if (file_exists(trailingslashit(get_stylesheet_directory()) . 'peprofile/' . $template_name) ) {
                    $located = trailingslashit(get_stylesheet_directory()) . 'peprofile/' . $template_name;
                    break;

                // Check parent theme next
                } elseif (file_exists(trailingslashit(get_template_directory()) . 'peprofile/' . $template_name) ) {
                    $located = trailingslashit(get_template_directory()) . 'peprofile/' . $template_name;
                    break;

                // Check modern theme location
                } elseif ($this->user_modern && file_exists(trailingslashit($this->modern_dir) . $template_name) ) {
                    $located = trailingslashit($this->modern_dir) . $template_name;
                    break;

                // Check default theme in profile
                } elseif (!$this->user_modern && file_exists(trailingslashit("$this->assets_dir/legacy") . $template_name) ) {
                    $located = trailingslashit("$this->assets_dir/legacy") . $template_name;
                    break;
                }
            }

            if (( true == $load ) && ! empty($located) ) {
                load_template($located, $require_once);
            }

            return $located;
        }
        public function theme_page_templates( $post_templates, $wp_theme, $post, $post_type )
        {
            $post_templates['peprofile-template.php'] = __("Pepro Profile - Legacy", "peprodev-ups");
            return $post_templates;
        }
        public function template_include( $template )
        {
          $slug = get_page_template_slug();
          $len = strlen("peprofile-");
          if((substr("$slug", 0, $len) === "peprofile-")) {
              if ($theme_file = locate_template(array($slug)) ) {
                $template = $theme_file;
              } else {
                $template = plugin_dir_path(__FILE__) . "/libs/general/$slug";
              }
          }
          if($template == '') {
            throw new \Exception('No template found');
          }
          return $template;
        }
        public function change_dashboard_title($title="")
        {
          $title = apply_filters( "peprofile_default_title", sprintf( ("%s — %s"), (!empty(trim($title)) ? trim($title) : _x("Dashboard", "user-dashboard", "peprodev-ups")), get_bloginfo("name") ) );
          echo "<script>document.title = '".esc_html( $title )."';</script>";
        }
        /* Woocommerce tempalate overwrite hooks
        */
        public function woocommerce_order_downloads_table( $downloads )
        {
            if (! $downloads ) { return; }
            /**
           * Get other templates (e.g. product attributes) passing attributes and including the file.
           *
           * @param string $template_name Template name.
           * @param array  $args          Arguments. (default: array).
           * @param string $template_path Template path. (default: '').
           * @param string $default_path  Default path. (default: '').
           */
            wc_get_template('order-downloads.php', array('downloads' => $downloads), "$this->assets_dir/libs/wc/");
        }
        public static function get_wc_asset_url( $path )
        {
            return apply_filters('woocommerce_get_asset_url', plugins_url($path, WC_PLUGIN_FILE), $path);
        }
        public function dashboard_add_css($src)
        {
            echo "<link href=\"$src\" rel=\"stylesheet\" media=\"all\">";
        }
        public function dashboard_add_css_inline($src)
        {
            echo "<style>$src</style>";
        }
        /* Wodpress Hooks
        */
        public function admin_init($hook)
        {
            /* register admin settings
            */
            foreach ($this->setting_options as $sections) {
                foreach ($sections["data"] as $id=>$def) {
                    add_option($id, $def);
                    register_setting($sections["name"], $id);
                }
            }
        }
        public function htmlwrapper()
        {
            $this->remove_us_css();
            $this->enqueue_scripts_and_styles();
            include plugin_dir_path(__FILE__) . "/libs/general/activated.php";
        }
        public function htmlwrapper_shortcodes()
        {
            $this->remove_us_css();
            $this->enqueue_scripts_and_styles();
            include plugin_dir_path(__FILE__) . "/libs/general/shortcodes_panel.php";
        }
        public function htmlwrapper_sections()
        {
          $this->remove_us_css();
          $this->enqueue_scripts_and_styles();
          include plugin_dir_path(__FILE__) . "/libs/general/sections_panel.php";
          wp_enqueue_script(__CLASS__."date",              plugins_url("/assets/js/persian-date.min.js", __FILE__), array("jquery"), "3.0.0", true);
          wp_enqueue_script(__CLASS__."datepicker",        plugins_url("/assets/js/persian-datepicker.min.js", __FILE__), array("jquery"), "3.0.0", true);
          wp_enqueue_style(__CLASS__."datepicker",         plugins_url("/assets/css/persian-datepicker.min.css", __FILE__));
          wp_enqueue_script(__CLASS__."simple-iconpicker", plugins_url("/assets/js/simple-iconpicker.min.js", __FILE__), array("jquery"), "3.0.0", true);
          wp_enqueue_style(__CLASS__."simple-iconpicker",  plugins_url("/assets/css/simple-iconpicker.min.css", __FILE__));
          wp_enqueue_script(__CLASS__."notifs_panel",      plugins_url("/assets/js/peprocore-sections-panel.js", __FILE__), array("jquery"), $this->current_version);
          wp_localize_script(__CLASS__."notifs_panel",     "peprofile", array(
            "ajax"                => admin_url('admin-ajax.php'),
            "wparam"              => $this->setting_slug,
            "error_validate_form" => __("Error validating form, please check marked fields.","peprodev-ups"),
            "error_parsing_data"  => __("Error parsing item data.","peprodev-ups"),
            "error_unknown_error" => __("An unknown error occurred.","peprodev-ups"),
          ));
        }
        public function htmlwrapper_notifs()
        {
          $this->remove_us_css();
          $this->enqueue_scripts_and_styles();
          include plugin_dir_path(__FILE__) . "/libs/general/notifs_panel.php";
          wp_enqueue_script(__CLASS__."date",              plugins_url("/assets/js/persian-date.min.js", __FILE__), array("jquery"), "3.0.0", true);
          wp_enqueue_script(__CLASS__."datepicker",        plugins_url("/assets/js/persian-datepicker.min.js", __FILE__), array("jquery"), "3.0.0", true);
          wp_enqueue_style(__CLASS__."datepicker",         plugins_url("/assets/css/persian-datepicker.min.css", __FILE__));
          wp_enqueue_script(__CLASS__."simple-iconpicker", plugins_url("/assets/js/simple-iconpicker.min.js", __FILE__), array("jquery"), "3.0.0", true);
          wp_enqueue_style(__CLASS__."simple-iconpicker",  plugins_url("/assets/css/simple-iconpicker.min.css", __FILE__));
          wp_enqueue_script(__CLASS__."notifs_panel",      plugins_url("/assets/js/peprocore-notifs-panel.js", __FILE__), array("jquery"), $this->current_version);
          wp_localize_script(__CLASS__."notifs_panel",     "peprofile", array(
            "ajax" => admin_url('admin-ajax.php'),
            "wparam"=>$this->setting_slug,
            "error_validate_form"=> __("Error validating form, please check marked fields.","peprodev-ups"),
            "error_parsing_data"=> __("Error parsing item data.","peprodev-ups"),
            "error_unknown_error"=> __("An unknown error occurred.","peprodev-ups"),
          ));

        }
        public function htmlwrapper_newsletter()
        {
          $this->remove_us_css();
          $this->enqueue_scripts_and_styles();
          include plugin_dir_path(__FILE__) . "/libs/general/newsletter_panel.php";
          wp_enqueue_script(__CLASS__."notifs_panel",  plugins_url("/assets/js/peprocore-newsletter-panel.js", __FILE__), array("jquery"), $this->current_version);
          wp_localize_script(__CLASS__."notifs_panel", "peprofile", array(
            "ajax"                => admin_url('admin-ajax.php'),
            "wparam"              => $this->setting_slug,
            "error_validate_form" => __("Error validating form, please check marked fields.","peprodev-ups"),
            "error_parsing_data"  => __("Error parsing item data.","peprodev-ups"),
            "error_unknown_error" => __("An unknown error occurred.","peprodev-ups"),
            ));
        }
        public function notif_exist($id)
        {
          global $wpdb;
          $query = $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$this->tbl_notif}` WHERE `id`=%d", sanitize_text_field($id)));
          return null !== $query;
        }
        public function user_avatar_upload_dir( $dirs )
        {
          $dirs['subdir'] = '/profile';
          $dirs['path'] = $dirs['basedir'] . '/profile';
          $dirs['url'] = $dirs['baseurl'] . '/profile';
          return $dirs;
        }
        public function user_avatar_hash_filename( $filename )
        {
          $info = pathinfo( $filename );
          $ext  = empty( $info['extension'] ) ? '' : '.' . $info['extension'];
          $name = basename( $filename, $ext );
          return get_current_user_id() . "_avatar_" . date_i18n("YmdHis") . $ext;
        }
        public function after_delete_user( $user_id )
        {
          $saved = get_user_meta( $user_id, 'profile_ifile', true );
          if (!empty($saved) && file_exists(wp_upload_dir()["basedir"] . "/profile/$saved")){
            unlink(wp_upload_dir()["basedir"] . "/profile/$saved");
          }
        }
        public function front_side_ajax_handler()
        {
            check_ajax_referer('pepro_profile', 'integrity');
            // handle front-end profile ajax requests
            if (wp_doing_ajax() && $_POST['action'] == $this->id ) {
              if ("profile" == $_POST['wparam']){
                switch ($_POST['lparam']) {
                  case 'read-notif':

                      (int) $id = ( isset($_POST["dparam"]) && !empty(trim($_POST["dparam"])) && is_numeric(trim($_POST["dparam"])) ) ? trim($_POST["dparam"]) : "-1";
                      global $wpdb;

                      $query = $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$this->tbl_notif}_list` WHERE `user_id`=%d AND `notif_id`=%d", get_current_user_id(), sanitize_text_field($id)));
                      if (null !== $query) {
                          // already exists!
                          $db = $wpdb->update( "{$this->tbl_notif}_list",
                              array( 'has_seen' => '1'),
                              array( 'notif_id' => sanitize_text_field($id), 'user_id' => get_current_user_id()),
                              array( '%s'),
                              array( '%d', '%d' )
                          );
                      }
                      else{
                        if ($this->notif_exist($id)){
                          // add to users
                          $db = $wpdb->insert(
                            "{$this->tbl_notif}_list",
                            array(
                              'user_id' => get_current_user_id(),
                              'notif_id' => sanitize_text_field($id),
                              'has_seen' => "1",
                            ),
                            array(
                              '%d',
                              '%d',
                              '%s',
                            )
                          );
                        }else{
                          wp_send_json_error(array( "msg"=>__("There was a problem with your request. Error 0x10041", "peprodev-ups")));
                          break;
                        }
                      }

                      $shorts = ""; $notif = __("You have no new notification.", "peprodev-ups");
                      $number = $this->get_user_notification_count(get_current_user_id());
                      if ($number > 0){ $notif = sprintf(__("You have %s unread notifications.", "peprodev-ups"), "<span class='nunread'>$number</span>"); }
                      if ($number > 0){ $shorts = $this->get_user_notifications_short(get_current_user_id(),4); }
                      $htmldata = $this->get_user_notifications(get_current_user_id());

                      $Ashorts = ""; $Anotif = __("You have no new announcement.", "peprodev-ups");
                      $Anumber = $this->get_user_announcements_count(get_current_user_id());
                      if ($Anumber > 0){ $Anotif = sprintf(__("You have %s unread announcements.", "peprodev-ups"), "<span class='nunread'>$Anumber</span>"); }
                      if ($Anumber > 0){ $Ashorts = $this->get_user_announcements_short(get_current_user_id(),4); }
                      $Ahtmldata = $this->get_user_announcements(get_current_user_id());

                      wp_send_json_success( array(
                          "msg"     => __("Done","peprodev-ups"),
                          "count"   => $notif,
                          "number"  => $number,
                          "tiny"    => $shorts,
                          "html"    => $htmldata,
                          "Acount"  => $Anotif,
                          "Anumber" => $Anumber,
                          "Atiny"   => $Ashorts,
                          "Ahtml"   => $Ahtmldata)
                        );
                  break;
                  case 'edit-profile':
                    if (empty($_POST["dparam"]))
                      wp_send_json_error(array( "msg"=>__("There was a problem with your request. Error 0x10027", "peprodev-ups")));

                    $user_id = get_current_user_id();
                    if ( $user_id <= 0 ) { wp_send_json_error(array( "msg"=>__("There was a problem with your request. Error 0x00010", "peprodev-ups"))); return; }

                		$current_user       = get_user_by( 'id', $user_id );
                		$current_first_name = $current_user->first_name;
                		$current_last_name  = $current_user->last_name;
                		$current_email      = $current_user->user_email;
                    $save_pass          = true;
                    $pass_cur           = "";
                    $pass1              = "";
                		$user               = new \stdClass();
                		$user->ID           = $user_id;
                    $retuen             = array();

                    if (isset($_FILES['file']['size']) && $_FILES['file']['size'] > 0){
                        $wp_filetype = wp_check_filetype( $_FILES['file']['name'], null );
                        if(in_array($wp_filetype["ext"], array("jpg","jpeg","png")) && $_FILES['file']['size'] <= 2 * 1024 * 1024 ){
                          if (!function_exists('wp_handle_upload')){require_once(ABSPATH.'wp-admin/includes/file.php');}

                          add_filter( "upload_dir",             array( $this, "user_avatar_upload_dir"));
                          add_filter( "sanitize_file_name",     array( $this, "user_avatar_hash_filename"), 10);
                          $movefile = wp_handle_upload( $_FILES['file'], array('test_form' => false) );
                          remove_filter( "sanitize_file_name",  array( $this, "user_avatar_hash_filename"), 10);
                          remove_filter( "upload_dir",          array( $this, "user_avatar_upload_dir"));

                          if ( $movefile && ! isset( $movefile['error'] ) ) {
                            $saved = get_user_meta( get_current_user_id(), 'profile_ifile', true );
                            if (!empty($saved) && file_exists(wp_upload_dir()["basedir"] . "/profile/$saved")){
                              // remove prev-image
                              unlink(wp_upload_dir()["basedir"] . "/profile/$saved");
                            }
                            $newimg = wp_upload_dir()["basedir"] . "/profile/" . basename($movefile["file"]);
                            $this->make_thumb($newimg, $newimg, 250);
                            update_user_meta( get_current_user_id(), 'profile_ifile', basename($movefile["file"]));
                            update_user_meta( get_current_user_id(), 'profile_image', $movefile["url"]);
                            do_action( "poprofile_user_avatar_upload_success");
                          }
                          else{
                            do_action( "poprofile_user_avatar_upload_failed");
                            wp_send_json_error(array( "msg" =>  __("There was an error uploading your file. Operation Aborted, Error 0x100953.","peprodev-ups")));
                            return;
                          }

                        }
                        else{
                          wp_send_json_error(array( "msg" =>  __("There was an error uploading your file. Please check file type and size. Operation Aborted, Error 0x100954.","peprodev-ups")));
                          return;
                        }
                    }
                    $required_fields = apply_filters( 'peprofile_save_account_details_required_fields_label', array(
                				'firstname'     => __( 'First name', "peprodev-ups" ),
                				'lastname'      => __( 'Last name', "peprodev-ups" ),
                				'email'         => __( 'Email address', "peprodev-ups" ),
                			)
                		);
                    $show_email_field   = "yes" == get_option("PeproDevUPS_Core___loginregister-_regdef_email");
                    $is_email_field_req = "yes" == get_option("PeproDevUPS_Core___loginregister-_regdef_email-req");

                    if (!$show_email_field || ($show_email_field && !$is_email_field_req)){
                      unset($required_fields["email"]);
                    }

                    foreach (json_decode(stripslashes($_POST["dparam"]),true) as $index) {

                      $index["name"]  = sanitize_key($index["name"]);
                      $index["value"] = sanitize_textarea_field($index["value"]);

                      foreach ($required_fields as $key => $value) {
                        if ( $index["name"] == $key && empty($index['value']) ){
                          wp_send_json_error(array("msg" => sprintf( __( '%s is a required field.', "peprodev-ups" ), '<strong>' . esc_html( $value ) . '</strong>' )));
                          return;
                        }
                      }

                      if (class_exists("\PeproDev\PeproDevUPS_Login")){
                        global $PeproDevUPS_Login;
                        foreach ($PeproDevUPS_Login->get_editprofile_fields() as $field) {
                          if ("yes" == $field["is-editable"]){
                            update_user_meta( $user_id, $index["name"], sanitize_text_field(trim($index['value'])));
                          }
                        }
                      }
                      switch (sanitize_key($index["name"])) {
                          case 'firstname':
                            $user->first_name = wp_unslash( $index['value'] );
                            break;
                          case 'lastname':
                            $user->last_name = wp_unslash( $index['value'] );
                            break;
                          case 'email':
                              $account_email = sanitize_email($index['value']);
                              if (!$is_email_field_req && (!empty($index['value']) && !filter_var($account_email, FILTER_VALIDATE_EMAIL)) ) {
                                wp_send_json_error(array( "msg" => __('Please provide a valid email address.', "peprodev-ups")));
                                return;
                              }
                              if ($is_email_field_req && (empty($account_email) || !filter_var($account_email, FILTER_VALIDATE_EMAIL)) ) {
                                wp_send_json_error(array( "msg" => __('Please provide a valid email address.', "peprodev-ups")));
                                return;
                              }
                              if ((!empty($account_email) && email_exists( $account_email ) && $account_email !== $current_user->user_email )) {
                                wp_send_json_error(array( "msg" => __('This email address is already registered.', "peprodev-ups")));
                                return;
                              }

                              if (empty($account_email) || empty($index['value'])) {
                                $account_email = $current_user->user_email;
                              }
                              $user->user_email = $account_email;
                              $retuen["user_email"] = $user->user_email;

                            break;
                          case 'password_current':
                              $pass_cur = $index['value'];
                              break;
                          case 'password_new':
                              $pass1 = $index['value'];
                            break;
                          case 'password_confirm':
                              $pass2 = $index['value'];
                            break;
                          default:
                            do_action( "peprofile_user_details_edit_loop_details", $_POST, $index);
                            break;
                      }

                    }
                		if ( !empty( $pass_cur ) && (empty( $pass1 ) || empty( $pass2 )) ) {
                      wp_send_json_error(array( "msg"=>__("Please fill out all password fields.", "peprodev-ups")));
                			$save_pass = false;
                      return;
                		}
                    elseif (empty( $pass_cur ) && (!empty( $pass1 ) || !empty( $pass2 )) ){
                      if (!wp_check_password($pass_cur, $current_user->user_pass, $current_user->ID )) {
                        wp_send_json_error(array( "msg"=>__("Please enter your current password.", "peprodev-ups")));
                        $save_pass = false;
                        return;
                      }
                		}
                    elseif (!empty( $pass_cur ) && ($pass1 != $pass2)){
                      wp_send_json_error(array( "msg"=>__("New password and Confirm password does not match.", "peprodev-ups")));
                			$save_pass = false;
                      return;
                		}
                    elseif ( ! empty( $pass1 ) && ! wp_check_password( $pass_cur, $current_user->user_pass, $current_user->ID ) ) {
                      wp_send_json_error(array( "msg"=>__("Your current password is incorrect.", "peprodev-ups")));
                			$save_pass = false;
                      return;
                		}
                		if ( $pass1 && $save_pass ) { $user->user_pass = $pass1; }

                    $user->display_name = "{$user->first_name} {$user->last_name}";
                    $retuen["display_name"] = "{$user->first_name} {$user->last_name}";

                    do_action( "peprofile_user_details_edit_save_details", $_POST, $user_id);

                    wp_update_user( $user );
                    $avatar_url = get_avatar_url($user_id, array("size" => "96"));
                    $retuen["avatar"] = $avatar_url;
                    $upload_dir   = wp_upload_dir();

                    do_action( "peprofile_user_details_edited", $user_id);

                    if ($save_pass) {
                      wp_clear_auth_cookie();
                      $user = new \WP_User($user_id);
                      wp_set_current_user($user->ID);
                      wp_set_auth_cookie($user->ID);
                    }
                    $saved = get_user_meta( get_current_user_id(), 'profile_ifile', true );
                    wp_send_json_success( array(
                      "e"       => $retuen,
                      "fileds"  => json_decode(stripslashes($_POST["dparam"])),
                      'msg'     => __("Profile Updated successfully.", "peprodev-ups"),
                      'img'     => wp_upload_dir()["basedir"] . "/profile/$saved",
                      "refresh" => $save_pass?true:false,
                      ));
                  break;
                  default:
                    wp_send_json_error(array( "msg"=>__("There was a problem with your request. Error 0x10030", "peprodev-ups")));
                  break;
                }
                wp_send_json_error(array( "msg"=>__("There was a problem with your request. Error 0x10028", "peprodev-ups")));
              }
              else{
                wp_send_json_error(array( "msg"=>__("There was a problem with your request. Error 0x10034", "peprodev-ups")));
              }
              die();
            }
        }
        public function make_thumb($src, $dest, $desired_width, $degrees=false)
        {

          /* read the source image */
          $source_image = imagecreatefromjpeg($src);
          $width = imagesx($source_image);
          $height = imagesy($source_image);

          /* find the "desired height" of this thumbnail, relative to the desired width  */
          $desired_height = floor($height * ($desired_width / $width));

          /* create a new, "virtual" image */
          $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

          /* copy source image at a resized size */
          imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

          if ($degrees){
            $virtual_image = imagerotate($virtual_image, $degrees, 0);
          }

          /* create the physical thumbnail image to its destination */
          imagejpeg($virtual_image, $dest);
        }
        public function admin_side_ajax_handler($r)
        {
          // security and wp nounce checked before by PeproDev Ultimate Profile Solutions
            if ($r["wparam"] === $this->setting_slug) {
                global $wpdb, $wp;
                switch ($r["lparam"]) {
                  case 'activatemodule':
                    $links = apply_filters( "peprocore_{$this->id}_dashboard_nav_menuitems", array() ); $ids = array();
                    foreach ($links as $link) { $ids[] = $link["id"]; }
                    if ($r["dparam"] === "1") {
                        update_option($this->activation_status, "1");
                        wp_send_json_success(
                            array(
                            "msg"     =>  sprintf(__("%s Successfully Activated.", "peprodev-ups"), $this->menu_label),
                            "id"      =>  $ids,
                            "lparam"  =>  $r["lparam"],
                            "wparam"  =>  $r["wparam"],
                            "dparam"  =>  $r["dparam"])
                        );
                    }
                    elseif ($r["dparam"] === "0") {
                        update_option($this->activation_status, "0");
                        wp_send_json_success(
                            array(
                            "msg"     =>  sprintf(__("%s Successfully Deactivated.", "peprodev-ups"), $this->menu_label),
                            "id"      =>  $ids,
                            "lparam"  =>  $r["lparam"],
                            "wparam"  =>  $r["wparam"],
                            "dparam"  =>  $r["dparam"])
                        );
                    }
                    break;
                  case 'save_setting':

                      $setting_slug = "css";
                      if(isset($_POST["dparam"][$setting_slug])){
                        update_option("{$this->activation_status}-{$setting_slug}", htmlentities($_POST["dparam"][$setting_slug]));
                      }
                      $setting_slug = "js";
                      if(isset($_POST["dparam"][$setting_slug])){
                        update_option("{$this->activation_status}-{$setting_slug}", htmlentities($_POST["dparam"][$setting_slug]));
                      }
                      $setting_slug = "logo";
                      if(isset($_POST["dparam"][$setting_slug]) && !empty($_POST["dparam"][$setting_slug])){
                        update_option("{$this->activation_status}-{$setting_slug}", sanitize_text_field($_POST["dparam"][$setting_slug]));
                      }
                      $setting_slug = "logo-id";
                      if(isset($_POST["dparam"][$setting_slug]) && !empty($_POST["dparam"][$setting_slug])){
                        update_option("{$this->activation_status}-{$setting_slug}", sanitize_text_field($_POST["dparam"][$setting_slug]));
                      }
                      $setting_slug = "showwelcome";
                      if(isset($_POST["dparam"][$setting_slug]) && !empty($_POST["dparam"][$setting_slug])){
                        update_option("{$this->activation_status}-{$setting_slug}", sanitize_text_field($_POST["dparam"][$setting_slug]));
                      }
                      $setting_slug = "headerhook";
                      if(isset($_POST["dparam"][$setting_slug]) && !empty($_POST["dparam"][$setting_slug])){
                        update_option("{$this->activation_status}-{$setting_slug}", sanitize_text_field($_POST["dparam"][$setting_slug]));
                      }
                      $setting_slug = "footerhook";
                      if(isset($_POST["dparam"][$setting_slug]) && !empty($_POST["dparam"][$setting_slug])){
                        update_option("{$this->activation_status}-{$setting_slug}", sanitize_text_field($_POST["dparam"][$setting_slug]));
                      }
                      $setting_slug = "profile-dash-page";
                      if(isset($_POST["dparam"][$setting_slug]) && !empty($_POST["dparam"][$setting_slug])){
                        update_option("{$this->activation_status}-{$setting_slug}", sanitize_text_field($_POST["dparam"][$setting_slug]));
                      }
                      $setting_slug = "woocommercestats";
                      if(isset($_POST["dparam"][$setting_slug]) && !empty($_POST["dparam"][$setting_slug])){
                        update_option("{$this->activation_status}-{$setting_slug}", sanitize_text_field($_POST["dparam"][$setting_slug]));
                      }
                      $setting_slug = "showcustomtext";
                      if(isset($_POST["dparam"][$setting_slug]) && !empty($_POST["dparam"][$setting_slug])){
                        update_option("{$this->activation_status}-{$setting_slug}", sanitize_text_field($_POST["dparam"][$setting_slug]));
                      }
                      $setting_slug = "woocommerceorders";
                      if(isset($_POST["dparam"][$setting_slug]) && !empty($_POST["dparam"][$setting_slug])){
                        update_option("{$this->activation_status}-{$setting_slug}", sanitize_text_field($_POST["dparam"][$setting_slug]));
                      }
                      $setting_slug = "customhtml";
                      if(isset($_POST["dparam"][$setting_slug]) && !empty($_POST["dparam"][$setting_slug])){
                        update_option("{$this->activation_status}-{$setting_slug}", stripslashes($_POST["dparam"][$setting_slug]));
                      }
                      $setting_slug = "customposition";
                      if(isset($_POST["dparam"][$setting_slug]) && !empty($_POST["dparam"][$setting_slug])){
                        update_option("{$this->activation_status}-{$setting_slug}", sanitize_text_field($_POST["dparam"][$setting_slug]));
                      }

                      $dashpage = get_option("{$this->activation_status}-profile-dash-page");
                      $slug = get_page_template_slug($dashpage);
                      $notify_user_of_page_template = sprintf(
                        __("Current selected dashboard page's template should be 'PeproDev Ultimate Profile Solutions — Profile'. %s","peprodev-ups"),
                        "<a class='btm btn btn-sm btn-danger' href='".
                        admin_url("post.php?post=$dashpage&action=edit#page_template")."' target='_blank'>".
                        __("Edit","peprodev-ups")."</a>"
                      );
                      $len = strlen("peprofile-");
                      $notify_user = false;
                      if((substr("$slug", 0, $len) !== "peprofile-")) {
                        $notify_user = true;
                      }

                      wp_send_json_success( array(
                          "notice" => $notify_user,
                          "notice_html" => $notify_user_of_page_template,
                          "msg"=>__("Settings Successfully Saved.","peprodev-ups"),
                        ) );
                    break;
                  case 'add_new_section':

                    (int) $id = ( isset($_POST["dparam"]["id"]) && !empty(trim($_POST["dparam"]["id"])) && is_numeric(trim($_POST["dparam"]["id"])) ) ? trim($_POST["dparam"]["id"]) : "-1";

                    $query                                  = $wpdb->get_row($wpdb->prepare("SELECT id FROM {$this->tbl_sections} WHERE `id`=%d", $id));
                    $title                                  = isset($_POST["dparam"]["title"]) ? sanitize_text_field(trim($_POST["dparam"]["title"])) : null;
                    if (empty(trim($title))) {wp_send_json_error(array( "msg"=>__("There was a problem with your request. Title field is required.", "peprodev-ups")));return false; }
                    $slug                                   = isset($_POST["dparam"]["slug"]) ? sanitize_title(trim($_POST["dparam"]["slug"])) : null;
                    if (empty(trim($slug))) {wp_send_json_error(array( "msg"=>__("There was a problem with your request. Unique Slug field is required.", "peprodev-ups")));return false; }
                    $subject                                = isset($_POST["dparam"]["subject"]) ? sanitize_text_field(trim($_POST["dparam"]["subject"])) : null;
                    if (empty(trim($subject))) {wp_send_json_error(array( "msg"=>__("There was a problem with your request. Unique Slug field is required.", "peprodev-ups")));return false; }
                    $content                                = isset($_POST["dparam"]["content"]) ? (trim($_POST["dparam"]["content"])) : null;
                    if (empty(trim($content))) {wp_send_json_error(array( "msg"=>__("There was a problem with your request. Content field is required.", "peprodev-ups")));return false; }
                    $icon                                   = isset($_POST["dparam"]["icon"]) ? sanitize_text_field(trim($_POST["dparam"]["icon"])) : null;
                    if (empty(trim($icon))) {$icon          = "zmdi zmdi-email"; }
                    $priority                               = isset($_POST["dparam"]["priority"]) ? sanitize_text_field(trim($_POST["dparam"]["priority"])) : null;
                    if (empty(trim($priority))) { $priority = 1000; }
                    $active                                 = isset($_POST["dparam"]["active"]) ? sanitize_text_field(trim($_POST["dparam"]["active"])) : null;
                    if (empty(trim($active))) { $active     = "no"; }
                    $access                                 = isset($_POST["dparam"]["access"]) ? sanitize_text_field(trim($_POST["dparam"]["access"])) : null;
                    $ld_lms                                 = isset($_POST["dparam"]["ld_lms"]) ? sanitize_text_field(trim($_POST["dparam"]["ld_lms"])) : "";
                    $css                                    = isset($_POST["dparam"]["css"]) ? (trim($_POST["dparam"]["css"])) : null;
                    $js                                     = isset($_POST["dparam"]["js"]) ? (trim($_POST["dparam"]["js"])) : null;

                    $dataArray = array(
                      'content'     => $content,
                      'css'         => $css,
                      'js'          => $js,
                      'title'       => sanitize_text_field($title),
                      'slug'        => sanitize_title($slug),
                      'subject'     => sanitize_text_field($subject),
                      'icon'        => sanitize_text_field($icon),
                      'is_active'   => sanitize_text_field($active),
                      'priority'    => sanitize_text_field($priority),
                      'access'      => $access,
                      'ld_lms'      => $ld_lms,
                    );
                    $dataArrayTypes = array(
                      '%s',
                      '%s',
                      '%s',
                      '%s',
                      '%s',
                      '%s',
                      '%s',
                      '%s',
                      '%s',
                    );

                    $update = false;
                    $add = false;

                    if (null !== $query) {
                      $update = $wpdb->update($this->tbl_sections, $dataArray, array('id' => sanitize_text_field($id)), $dataArrayTypes, array('%d') );
                    }
                    else {
                      $add = $wpdb->insert($this->tbl_sections, $dataArray, $dataArrayTypes );
                    }

                    $pageNum = 1; $pageSrch = "";

                    $reqURL = sanitize_url($_POST["dparam"]["url"]);
                    $parts = parse_url($reqURL);
                    parse_str($parts['query'], $pageURLquery);

                    if (isset($pageURLquery['cpage']) && !empty($pageURLquery['cpage']) && 0 < (int) $pageURLquery['cpage'])
                      $pageNum = (int) $pageURLquery['cpage'];

                    if (isset($pageURLquery['s']) && !empty($pageURLquery['s']))
                      $pageSrch = $pageURLquery['s'];

                    $htmltabledata = $this->show_sections_edit_panel($pageNum, $pageSrch, $reqURL);

                    if (false !== $update)
                      wp_send_json_success(array( "msg"=>__("Notification edited successfully.", "peprodev-ups"), "type"=> "update", "htmlupdate" => $htmltabledata, "status"=> $update));

                    if ($add)
                      wp_send_json_success(array( "msg"=>__("New notification created successfully.", "peprodev-ups"), "type"=> "add", "htmlupdate" => $htmltabledata, "status"=> $add));

                    wp_send_json_error(array( "msg"=>__("There was a problem with your request. Error 0x1001", "peprodev-ups")));

                    break;
                  case 'edit_section_builtin':
                    $id       = ( isset($_POST["dparam"]) && !empty(trim($_POST["dparam"])) ) ? sanitize_text_field(trim($_POST["dparam"])) : "";
                    $active   = ( isset($_POST["aparam"]) && !empty(trim($_POST["aparam"])) ) ? sanitize_text_field(trim($_POST["aparam"])) : "";
                    $priority = (int) ( isset($_POST["pparam"]) && !empty(trim($_POST["pparam"])) ) ? sanitize_text_field(trim($_POST["pparam"])) : "";

                    if (empty($id))
                      wp_send_json_error(array( "msg"=>__("There was a problem with your request.", "peprodev-ups")));

                    add_option( "peprofile_builtin_{$id}_priority",      ($priority?$priority:""));
                    add_option( "peprofile_builtin_{$id}_is_enabled",    ($active=="yes"?true:false));
                    update_option( "peprofile_builtin_{$id}_priority",   ($priority?$priority:"") );
                    update_option( "peprofile_builtin_{$id}_is_enabled", ($active=="yes"?true:false) );

                    wp_send_json_success(array( "msg"=>__("Section edited successfully.", "peprodev-ups")));

                    break;
                  case 'search_section':
                    $data = $this->show_sections_edit_panel(1, sanitize_text_field(esc_html(trim($_POST["dparam"]))), $_POST["urlz"]);
                    wp_send_json_success(array( "msg"=>__("Notification searched successfully.", "peprodev-ups"), "html" => $data , "s" => sanitize_text_field(esc_html(trim($_POST["dparam"])))));
                    break;
                  case 'remove_section':
                    (int) $id = ( isset($_POST["dparam"]) && !empty(trim($_POST["dparam"])) && is_numeric(trim($_POST["dparam"])) ) ? trim($_POST["dparam"]) : "-1";
                    $st = $wpdb->delete("{$this->tbl_sections}", array( 'id' => $id ));
                    if ($st != false) {
                        wp_send_json_success(array( "msg"=>__("Section removed successfully.", "peprodev-ups"), "dparam" => $st));
                    }else{
                        wp_send_json_error(array( "msg"=>__("There was a problem with your request.", "peprodev-ups"), "dparam" => $st));
                    }
                    break;
                  case 'remove_subscriber':
                    (int) $id = ( isset($_POST["dparam"]) && !empty(trim($_POST["dparam"])) && is_numeric(trim($_POST["dparam"])) ) ? trim($_POST["dparam"]) : "-1";
                    $st = $wpdb->delete("{$this->tbl_subscribers}", array( 'id' => $id ));
                    if ($st != false) {
                        wp_send_json_success(array( "msg"=>__("Subscriber removed successfully.", "peprodev-ups"), "dparam" => $st));
                    }else{
                        wp_send_json_error(array( "msg"=>__("There was a problem with your request.", "peprodev-ups"), "dparam" => $st));
                    }
                    break;
                  case 'clear_newsletterdb':
                    global $wpdb;
                    $del = $wpdb->query("TRUNCATE TABLE `$this->tbl_subscribers`");
                    if (false !== $del){
                      wp_send_json_success( array( "msg" => sprintf(__("Database Successfully Cleared.",$this->td),$id ) ) );
                    }else{
                      wp_send_json_error( array( "msg" => sprintf(__("Error Clearing Database.",$this->td),$id ) ) );
                    }
                    break;
                  case 'excel_newsletter':
                    global $wpdb;
                    wp_send_json_success( array( "msg" => sprintf(__("File is loaded to your pc!",$this->td),$id ) ) );
                    break;
                  case 'add_new':

                    (int) $id = ( isset($_POST["dparam"]["id"]) && !empty(trim($_POST["dparam"]["id"])) && is_numeric(trim($_POST["dparam"]["id"])) ) ? trim($_POST["dparam"]["id"]) : "-1";


                    $title = isset($_POST["dparam"]["title"]) ? sanitize_text_field(trim($_POST["dparam"]["title"])) : null;
                    if (empty(trim($title))) {
                      wp_send_json_error( array( "msg"=>__("There was a problem with your request. Title field is required.", "peprodev-ups")) );
                      return false;
                    }
                    $content = isset($_POST["dparam"]["content"]) ? sanitize_text_field(trim($_POST["dparam"]["content"])) : null;
                    if (empty(trim($content))) {
                      wp_send_json_error(
                        array( "msg"=>__("There was a problem with your request. Content field is required.", "peprodev-ups"))
                      );
                      return false;
                    }
                    $icon = isset($_POST["dparam"]["icon"]) ? sanitize_text_field(trim($_POST["dparam"]["icon"])) : null;
                    if (empty(trim($icon))) {$icon    = "zmdi zmdi-email"; }
                    $color = isset($_POST["dparam"]["color"]) ? sanitize_text_field(trim($_POST["dparam"]["color"])) : null;
                    if (empty(trim($color))) { $color = "bg-c1"; }
                    $priority = isset($_POST["dparam"]["priority"]) ? sanitize_text_field(trim($_POST["dparam"]["priority"])) : null;
                    if (empty(trim($priority))) {
                      wp_send_json_error(
                        array( "msg" => __("There was a problem with your request. Priority field is required.", "peprodev-ups"))
                      );
                      return false; }
                    $act1       = isset($_POST["dparam"]["act1"])         ? sanitize_text_field(trim($_POST["dparam"]["act1"])) : null;
                    $act1url    = isset($_POST["dparam"]["act1url"])      ? sanitize_text_field(trim($_POST["dparam"]["act1url"])) : null;
                    $act2       = isset($_POST["dparam"]["act2"])         ? sanitize_text_field(trim($_POST["dparam"]["act2"])) : null;
                    $act2url    = isset($_POST["dparam"]["act2url"])      ? sanitize_text_field(trim($_POST["dparam"]["act2url"])) : null;
                    $usersCheck = isset($_POST["dparam"]["users-check"])  ? sanitize_text_field(trim($_POST["dparam"]["users-check"])) : null;
                    $usersList  = isset($_POST["dparam"]["usersList"])    ? $_POST["dparam"]["usersList"] : null;
                    if ($usersCheck == "0") {
                        if (empty($usersList)) {
                          wp_send_json_error( array( "msg"=>__("There was a problem with your request. Users List is required.", "peprodev-ups"), ) );
                          return false;
                        }
                        $usersListArray = $usersList;
                        $usersList = implode(",", $usersList);
                    }
                    else{
                        $usersListArray = array();
                        $usersList = "all";
                    }

                    $scheduleCheck = isset($_POST["dparam"]["schedule-check"]) ? sanitize_text_field(trim($_POST["dparam"]["schedule-check"])) : null;
                    $schedule      = isset($_POST["dparam"]["schedule"]) ? sanitize_text_field(trim($_POST["dparam"]["schedule"])) : null;
                    $scheduleFA    = isset($_POST["dparam"]["scheduleFA"]) ? sanitize_text_field(trim($_POST["dparam"]["scheduleFA"])) : null;

                    if ($scheduleCheck == "1") {
                        if (empty(trim($schedule))) {
                            wp_send_json_error(array( "msg"=>__("There was a problem with your request. Schedule date is required.", "peprodev-ups")));
                            return false;
                        }
                    }else{
                        $schedule = "";
                        $scheduleFA = "";
                    }

                    $dataArray = array(
                      'date_scheduled'   => sanitize_text_field($schedule),
                      'date_scheduledFA' => sanitize_text_field($scheduleFA),
                      'title'            => sanitize_text_field($title),
                      'content'          => $content,
                      'icon'             => sanitize_text_field($icon),
                      'color'            => sanitize_text_field($color),
                      'priority'         => sanitize_text_field($priority),
                      'action_title_1'   => sanitize_text_field($act1),
                      'action_title_2'   => sanitize_text_field($act2),
                      'action_url_1'     => sanitize_text_field($act1url),
                      'action_url_2'     => sanitize_text_field($act2url),
                      'users_list'       => sanitize_text_field($usersList)
                    );
                    $dataArrayTypes = array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' );

                    if (empty(trim($schedule))) {
                        unset($dataArray["date_scheduled"]);
                        unset($dataArrayTypes[0]);
                    }

                    $update = false;
                    $add    = false;
                    $query  = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tbl_notif} WHERE `id`=%d", $id));

                    if (null !== $query) {
                        $update = $this->EditnewNotificationFromDb($dataArray, $dataArrayTypes, $id);
                    }
                    else {
                        $add = $this->AddnewNotification2Db($dataArray, $dataArrayTypes);
                        $id  = $wpdb->insert_id;
                    }

                    $this->AssignNotification2Users($usersListArray, $id);

                    $pageNum = 1; $pageSrch = "";
                    $parts = parse_url($_POST["dparam"]["url"]);
                    parse_str($parts['query'], $pageURLquery);
                    if (isset($pageURLquery['cpage']) && !empty($pageURLquery['cpage']) && 0 < (int) $pageURLquery['cpage']){
                      $pageNum = (int) $pageURLquery['cpage'];
                    }
                    if (isset($pageURLquery['s']) && !empty($pageURLquery['s'])){
                      $pageSrch = $pageURLquery['s'];
                    }
                    $htmltabledata = $this->show_notifications_edit_panel($pageNum, $pageSrch, sanitize_text_field($_POST["dparam"]["url"]));

                    if (false !== $update) {
                        wp_send_json_success(array( "msg"=>__("Notification edited successfully.", "peprodev-ups"), "type"=> "update", "htmlupdate" => $htmltabledata, "status"=> $update));
                    }

                    if ($add) {
                        wp_send_json_success(array( "msg"=>__("New notification created successfully.", "peprodev-ups"), "type"=> "add", "htmlupdate" => $htmltabledata, "status"=> $add));
                    }

                    wp_send_json_error(array( "msg"=>__("There was a problem with your request. Error 0x1001", "peprodev-ups")));

                    break;
                  case 'remove_notif':
                    (int) $id = ( isset($_POST["dparam"]) && !empty(trim($_POST["dparam"])) && is_numeric(trim($_POST["dparam"])) ) ? trim($_POST["dparam"]) : "-1";
                    $wpdb->delete("{$this->tbl_notif}_list", array( 'notif_id' => $id ));
                    $st = $wpdb->delete($this->tbl_notif, array( 'id' => $id ));
                    if ($st != false) {
                        wp_send_json_success(array( "msg"=>__("Notification removed successfully.", "peprodev-ups"), "dparam" => $st));
                    }else{
                        wp_send_json_error(array( "msg"=>__("There was a problem with your request.", "peprodev-ups"), "dparam" => $st));
                    }
                    break;
                  case 'search':
                    $data = $this->show_notifications_edit_panel(1, sanitize_text_field(esc_html(trim($_POST["dparam"]))), $_POST["urlz"]);
                    wp_send_json_success(array( "msg"=>__("Notification searched successfully.", "peprodev-ups"), "html" => $data , "s" => sanitize_text_field(esc_html(trim($_POST["dparam"])))));
                    break;
                  default:
                      wp_send_json_error(__("{$this->title} :: Incorrect Data Supplied.", "peprodev-ups"));
                      break;
                  }
            }
        }
        /**
         * Add notification to users (a non global notification)
         *
         * @method AssignNotification2Users
         * @param  array  $usersListArray List of users ID array
         * @param  string $id             ID of generated notification before
         */
        public function AssignNotification2Users($usersListArray=array(), $id=0)
        {
          global $wpdb;
          if (empty($id)) { return false; }
          if (empty($usersListArray)){
            // now all records for this notif
            $wpdb->delete("{$this->tbl_notif}_list", array( 'notif_id' => $id ));
          }
          foreach ($usersListArray as $user) {
            $query = $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$this->tbl_notif}_list` WHERE `user_id`=%d AND `notif_id`=%d", $user, $id));
            if (null !== $query) {
                // already exists!
            }else{
                // add to users
                $wpdb->insert(
                    "{$this->tbl_notif}_list",
                    array(
                      'user_id' => sanitize_text_field($user),
                      'notif_id' => sanitize_text_field($id),
                      'has_seen' => "0",
                    ),
                    array(
                      '%s',
                      '%s',
                      '%s',
                    )
                );
            }
            // now remove from extra users
            $notifs = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$this->tbl_notif}_list` WHERE `notif_id`=%d", $id));
            if ($notifs !== null) {
                foreach ($notifs as $notif) {
                    if (in_array($notif->user_id, $usersListArray)) {
                        // keep it, we need it
                    }else{
                        // remove it
                        $wpdb->delete("{$this->tbl_notif}_list", array( 'ID' => $notif->id ));
                    }
                }
            }
          }
        }
        public function AddnewNotification2Db($dataArray=array(), $dataArrayTypes=array())
        {
            global $wpdb;
            return $wpdb->insert(
                $this->tbl_notif,
                $dataArray,
                $dataArrayTypes
            );
        }
        public function EditnewNotificationFromDb($dataArray=array(), $dataArrayTypes=array(), $id=0)
        {
            global $wpdb;
            return $wpdb->update(
                $this->tbl_notif,
                $dataArray,
                array('id' => sanitize_text_field($id)),
                $dataArrayTypes,
                array('%d')
            );
        }
        public function get_user_notification_count($user_id)
        {
            global $wpdb;

            $private_msgs_unseen = $wpdb->get_results($wpdb->prepare(
              "SELECT a.*
              FROM `{$this->tbl_notif}` AS a
              INNER JOIN `{$this->tbl_notif}_list` AS b
              ON (b.user_id = '%d') and (a.id = b.notif_id) and (b.has_seen = '0')
              WHERE a.users_list <> 'all' AND a.date_scheduled <= NOW()", $user_id));

            if ($private_msgs_unseen !== null) {
                return count($private_msgs_unseen);
            }
            return 0;
        }
        public function get_user_notifications_short($user_id, $limit=4)
        {
            global $wpdb;
            $notifs = "";
            $private_msgs_unseen = $wpdb->get_results($wpdb->prepare(
              "SELECT a.*, b.has_seen
              FROM `{$this->tbl_notif}` AS a
              INNER JOIN `{$this->tbl_notif}_list` AS b
              ON (b.user_id = '%d') and (a.id = b.notif_id) and (b.has_seen = '0')
              WHERE a.users_list <> 'all' AND a.date_scheduled <= NOW() ORDER BY b.has_seen, a.date_scheduled DESC LIMIT %d", $user_id, $limit));

            if ($private_msgs_unseen !== null) {
                foreach ($private_msgs_unseen as $notif) {
                    $url = $this->get_profile_page(["section"=>"notifications#view-$notif->id"]);
                    $notifs .= "<a href=\"$url\" data-id=\"$notif->id\" class=\"notifi__item\">
                    <div class=\"$notif->color img-cir img-40\">
                    <i class=\"$notif->icon\"></i>
                    </div>
                    <div class=\"content\">
                    <p>$notif->title</p>
                    <span class=\"date\">". __("Priority: ", "peprodev-ups") . $this->translate_priority($notif->priority) ." / ".sprintf(esc_html__("%s ago", "peprodev-ups"), human_time_diff(strtotime("$notif->date_scheduled"), current_time('timestamp')))."</span>
                    </div>
                    </a>";
                }
            }
            return $notifs;
        }
        public function get_user_notifications($user_id)
        {
          global $wpdb;
          $private_msgs = $wpdb->get_results($wpdb->prepare(
            "SELECT a.*, b.has_seen, b.seen_first_date, b.seen_last_date
            FROM `{$this->tbl_notif}` AS a
            INNER JOIN `{$this->tbl_notif}_list` AS b
            ON (b.user_id = '%d') and (a.id = b.notif_id)
            WHERE a.users_list <> 'all' AND a.date_scheduled <= NOW() ORDER BY b.has_seen, a.date_scheduled DESC",$user_id ));

            $titles = ""; $notifs = "";

            if ($private_msgs !== null) {
              foreach ($private_msgs as $notif) {
                $unread = $notif->has_seen == 1 ? "" : "unread";
                $extas = "<br><br>";

                if (!empty($notif->action_title_1) && !empty($notif->action_url_1)) {
                  $extas .= "<a href='$notif->action_url_1' class='btn btn-primary'>$notif->action_title_1</a> ";
                }
                if (!empty($notif->action_title_2) && !empty($notif->action_url_2)) {
                  $extas .= "<a href='$notif->action_url_2' class='btn btn-primary'>$notif->action_title_2</a>";
                }
                $titles .= "
                <div data-ref=\"$notif->id\" class=\"au-message__item notifications $unread\">
                <a href=\"#$notif->id\">
                <div class=\"au-message__item-inner\">
                <div class=\"au-message__item-text\">
                <div class=\"avatar-wrap\">
                <div class='$notif->color img-cir img-40'>
                <i class='$notif->icon'></i>
                </div>
                </div>
                <div class=\"text\">
                <h5 class=\"name\">$notif->title</h5>
                <p>". __("Priority: ", "peprodev-ups") . $this->translate_priority($notif->priority)."</p>
                </div>
                </div>
                <div class=\"au-message__item-time\">
                <span>".sprintf(esc_html__("%s ago", "peprodev-ups"), human_time_diff(strtotime("$notif->date_scheduled"), current_time('timestamp')))."</span>
                </div>
                </div>
                </a>
                </div>";
                $notifs .= "
                <div data-ref=\"$notif->id\" class=\"au-chat\">
                <div class=\"au-chat__title\" style=\"margin-top: 10px;\">
                <div class=\"au-chat-info\" >
                <div class='$notif->color img-cir img-40 nick' style='cursor: pointer;'>
                <i class='$notif->icon'></i>
                </div>
                <span class=\"nick\">
                <strong style='cursor: pointer;'>$notif->title</strong>
                </span>
                </div>
                </div>
                <div class=\"au-chat__content\">
                <div class=\"recei-mess-wrap\">
                <span class=\"mess-time\">". date_i18n("Y/m/d H:m", strtotime($notif->date_scheduled)) ." (".sprintf(esc_html__("%s ago", "peprodev-ups"), human_time_diff(strtotime("$notif->date_scheduled"), current_time('timestamp'))).")</span>
                <div class=\"recei-mess__inner\">
                <div class=\"recei-mess-list\">
                <div class=\"recei-mess\">" . $this->filter_content($notif->content, $notif) . $extas . "</div>
                </div>
                </div>
                </div>
                </div>
                </div>";
              }
            }
            return array(
              "titles" => $titles,
              "notifs" => $notifs,
            );

          }
        public function get_user_announcements_count($user_id)
        {
          global $wpdb;

          $private_msgs = $wpdb->get_results("SELECT id FROM `{$this->tbl_notif}` WHERE users_list = 'all' and date_scheduled <= NOW()", ARRAY_A);
            if ($private_msgs !== null && count($private_msgs)>0) {
              $arrayIDs = []; foreach ($private_msgs as $key => $value) { $arrayIDs[] = $value["id"]; } array_unique($arrayIDs);
              $isDs = implode(',', $arrayIDs);
              if (empty($arrayIDs)){ return 0; }
              $private_msgs_unseen = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$this->tbl_notif}_list` WHERE notif_id IN ($isDs) AND user_id='%d' AND has_seen='1'", $user_id ),ARRAY_A);
              return count($arrayIDs) - count($private_msgs_unseen) < 0 ? 0 : count($arrayIDs) - count($private_msgs_unseen);
            }
            return 0;
          }
        public function get_user_announcements_short($user_id, $limit=4)
        {
          global $wpdb; $notifs = "";
          $private_msgs = $wpdb->get_results("SELECT * FROM `{$this->tbl_notif}` WHERE users_list = 'all' and date_scheduled <= NOW() ORDER BY date_scheduled DESC");
          if ($private_msgs !== null) {
            $arrayIDs = []; foreach ($private_msgs as $key => $value) { $arrayIDs[] = $value->id; }
            array_unique($arrayIDs);
            $isDs = implode(',', $arrayIDs);
            if (empty($arrayIDs)){ return ""; }
            $st = $wpdb->prepare("SELECT * FROM `{$this->tbl_notif}_list` WHERE notif_id IN ($isDs) AND user_id='%d' AND has_seen='1'", $user_id );
            $private_msgs_unseen = $wpdb->get_results($st);
          }
          $arrayIDsSeen = [];
          foreach ($private_msgs_unseen as $key => $value) { $arrayIDsSeen[] = $value->notif_id; }
          if ($private_msgs !== null) {
            $cur = 0;
            foreach ($private_msgs as $notif) {
              if (!in_array($notif->id, $arrayIDsSeen) && $cur <= $limit ){
                $cur ++;
                $url = $this->get_profile_page(["section"=>"announcements#view-$notif->id"]);
                $notifs .= "<a href=\"$url\" data-id=\"$notif->id\" class=\"notifi__item\">
                <div class=\"$notif->color img-cir img-40\">
                <i class=\"$notif->icon\"></i>
                </div>
                <div class=\"content\">
                <p>$notif->title</p>
                <span class=\"date\">". __("Priority: ", "peprodev-ups") . $this->translate_priority($notif->priority) ." / ".sprintf(esc_html__("%s ago", "peprodev-ups"), human_time_diff(strtotime("$notif->date_scheduled"), current_time('timestamp')))."</span>
                </div>
                </a>";
              }
            }
          }
          return $notifs;
        }
        public function get_user_announcements($user_id)
        {
          global $wpdb;

          $private_msgs = $wpdb->get_results("SELECT * FROM `{$this->tbl_notif}` WHERE users_list = 'all' and date_scheduled <= NOW() ORDER BY date_scheduled DESC");
          if ($private_msgs !== null) {
            $arrayIDs = []; foreach ($private_msgs as $key => $value) { $arrayIDs[] = $value->id; }
            array_unique($arrayIDs);
            $isDs = implode(',', $arrayIDs);
            if (empty($arrayIDs)){ return array( "titles" => "", "notifs" => "", ); }
            $private_msgs_unseen = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$this->tbl_notif}_list` WHERE notif_id IN ($isDs) AND user_id='%d' AND has_seen='1'", $user_id ));
          }
          $arrayIDsSeen = []; foreach ($private_msgs_unseen as $key => $value) { $arrayIDsSeen[] = $value->notif_id; }
          $titles = ""; $notifs = "";
          if ($private_msgs !== null) {
              foreach ($private_msgs as $notif) {
                if (!in_array($notif->id, $arrayIDsSeen)){
                    $notifhas_seen = false; $seen_first_date = ""; $seen_last_date = "";
                    $unread = "unread";
                    $extas = "<br><br>";
                    if (!empty($notif->action_title_1) && !empty($notif->action_url_1)) {
                      $extas .= "<a href='$notif->action_url_1' class='btn btn-primary'>$notif->action_title_1</a> ";
                    }
                    if (!empty($notif->action_title_2) && !empty($notif->action_url_2)) {
                      $extas .= "<a href='$notif->action_url_2' class='btn btn-primary'>$notif->action_title_2</a>";
                    }
                    $titles .= "
                    <div data-ref=\"$notif->id\" class=\"au-message__item announcements $unread\">
                    <a href=\"#$notif->id\">
                    <div class=\"au-message__item-inner\">
                    <div class=\"au-message__item-text\">
                    <div class=\"avatar-wrap\">
                    <div class='$notif->color img-cir img-40'>
                    <i class='$notif->icon'></i>
                    </div>
                    </div>
                    <div class=\"text\">
                    <h5 class=\"name\">$notif->title</h5>
                    <p>". __("Priority: ", "peprodev-ups") . $this->translate_priority($notif->priority)."</p>
                    </div>
                    </div>
                    <div class=\"au-message__item-time\">
                    <span>".sprintf(esc_html__("%s ago", "peprodev-ups"), human_time_diff(strtotime("$notif->date_scheduled"), current_time('timestamp')))."</span>
                    </div>
                    </div>
                    </a>
                    </div>";
                    $notifs .= "
                    <div data-ref=\"$notif->id\" class=\"au-chat\">
                    <div class=\"au-chat__title\" style=\"margin-top: 10px;\">
                    <div class=\"au-chat-info\" >
                    <div class='$notif->color img-cir img-40 nick' style='cursor: pointer;'>
                    <i class='$notif->icon'></i>
                    </div>
                    <span class=\"nick\">
                    <strong style='cursor: pointer;'>$notif->title</strong>
                    </span>
                    </div>
                    </div>
                    <div class=\"au-chat__content\">
                    <div class=\"recei-mess-wrap\">
                    <span class=\"mess-time\">". date_i18n("Y/m/d H:m", strtotime($notif->date_scheduled)) ." (".sprintf(esc_html__("%s ago", "peprodev-ups"), human_time_diff(strtotime("$notif->date_scheduled"), current_time('timestamp'))).")</span>
                    <div class=\"recei-mess__inner\">
                    <div class=\"recei-mess-list\">
                    <div class=\"recei-mess\">" . $this->filter_content($notif->content, $notif) . $extas . "</div>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>";
                }
              }
              foreach ($private_msgs as $notif) {
                if (in_array($notif->id, $arrayIDsSeen)){
                    $notifhas_seen = false; $seen_first_date = ""; $seen_last_date = "";
                    $unread = "";
                    $extas = "<br><br>";

                    if (!empty($notif->action_title_1) && !empty($notif->action_url_1)) {
                      $extas .= "<a href='$notif->action_url_1' class='btn btn-primary'>$notif->action_title_1</a> ";
                    }
                    if (!empty($notif->action_title_2) && !empty($notif->action_url_2)) {
                      $extas .= "<a href='$notif->action_url_2' class='btn btn-primary'>$notif->action_title_2</a>";
                    }
                    $titles .= "
                    <div data-ref=\"$notif->id\" class=\"au-message__item announcements $unread\">
                    <a href=\"#$notif->id\">
                    <div class=\"au-message__item-inner\">
                    <div class=\"au-message__item-text\">
                    <div class=\"avatar-wrap\">
                    <div class='$notif->color img-cir img-40'>
                    <i class='$notif->icon'></i>
                    </div>
                    </div>
                    <div class=\"text\">
                    <h5 class=\"name\">$notif->title</h5>
                    <p>". __("Priority: ", "peprodev-ups") . $this->translate_priority($notif->priority)."</p>
                    </div>
                    </div>
                    <div class=\"au-message__item-time\">
                    <span>".sprintf(esc_html__("%s ago", "peprodev-ups"), human_time_diff(strtotime("$notif->date_scheduled"), current_time('timestamp')))."</span>
                    </div>
                    </div>
                    </a>
                    </div>";
                    $notifs .= "
                    <div data-ref=\"$notif->id\" class=\"au-chat\">
                    <div class=\"au-chat__title\" style=\"margin-top: 10px;\">
                    <div class=\"au-chat-info\" >
                    <div class='$notif->color img-cir img-40 nick' style='cursor: pointer;'>
                    <i class='$notif->icon'></i>
                    </div>
                    <span class=\"nick\">
                    <strong style='cursor: pointer;'>$notif->title</strong>
                    </span>
                    </div>
                    </div>
                    <div class=\"au-chat__content\">
                    <div class=\"recei-mess-wrap\">
                    <span class=\"mess-time\">". date_i18n("Y/m/d H:m", strtotime($notif->date_scheduled)) ." (".sprintf(esc_html__("%s ago", "peprodev-ups"), human_time_diff(strtotime("$notif->date_scheduled"), current_time('timestamp'))).")</span>
                    <div class=\"recei-mess__inner\">
                    <div class=\"recei-mess-list\">
                    <div class=\"recei-mess\">" . $this->filter_content($notif->content, $notif) . $extas . "</div>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>";
                }
              }
            }
            return array(
              "titles" => $titles,
              "notifs" => $notifs,
            );
        }
        public function filter_content($content = "", $obj = null)
        {
          return apply_filters( "peprofile_get_notifications_content", do_shortcode(stripcslashes($content)), $content, $obj);
        }
        private function enqueue_scripts_and_styles()
        {
          wp_enqueue_style('wp-color-picker');
          wp_enqueue_script('wp-color-picker');
          wp_enqueue_script("select2", "{$this->assets_url}/assets/js/select2.min.js", array("jquery"), $this->current_version);
          wp_enqueue_style("select2", "{$this->assets_url}/assets/css/select2.min.css");
          add_filter("peprocore_dashboard_localize", function($ar){$ar["customhtml_tad"] = "{$this->activation_status}-customhtml";return $ar;});
          wp_enqueue_script("wp-color-picker-alpha", plugins_url("/assets/js/wp-color-picker-alpha.min.js", __FILE__), array("jquery"), $this->current_version);
          wp_enqueue_script("peprodev-ups", plugins_url("/assets/js/peprocore-setting.js", __FILE__), array("jquery"), $this->current_version);
          wp_enqueue_script("peprodev-ide", plugins_url("/assets/ide/ace.js" , __FILE__), array('jquery'), $this->current_version, true);
          wp_enqueue_style("peprodev-ide", plugins_url("/assets/ide/ace.css" , __FILE__));
          wp_localize_script("peprodev-ups", "pepc", array("ajax"=>admin_url('admin-ajax.php'),"_copy"=>__("Copied!", "peprodev-ups")));
          wp_enqueue_style("peprodev-ups", plugins_url("/assets/css/peprocore-backend-style.css", __FILE__), [], $this->current_version);
          is_rtl() AND wp_enqueue_style("peprodev-ups-rtl", plugins_url("/assets/css/peprocore-backend-style.rtl.css", __FILE__), [], $this->current_version);
        }
        public function display_post_states( $post_states, $post )
        {
            $slug = get_page_template_slug($post);
            $len = strlen("peprofile-");

            if((substr("$slug", 0, $len) === "peprofile-")) {
              $post_states["peprodev_ups"] = "<span class='pepro-profile-legacy-dashboard'>".__("Pepro Profile - Legacy Dashboard", "peprodev-ups")."</span>";
            }

            if (get_option("{$this->activation_status}-profile-dash-page","") == $post->ID){
              $post_states["peprodev_ups"] = "<span class='pepro-profile-modern-dashboard'>".__("Pepro Profile - Modern Dashboard", "peprodev-ups")."</span>";
            }

            return $post_states;
        }
        /* Filters */
        public function peprofile_get_nav_items($arr)
        {
            global $wp;
            $arr = array_merge( $arr,
            array(
              "home"        => array(
                "title"     => "<i class='fas fa-tachometer-alt'></i> ".__("Dashboard", "peprodev-ups"),
                "url"       => $this->get_profile_page(["i"=>current_time("timestamp")]),
                "priority"  => 10,
                "built_in"  => true,
              ),
              "information" => array(
                "title"     => "<i class='fas fa-tachometer-alt'></i> ".__("Information", "peprodev-ups"),
                "url"       => $this->get_profile_page(["section"=>"edit"]),
                "priority"  => 10,
                "built_in"  => true,
                "subitems"  => array(
                  array(
                    "slug"     => "edit",
                    "priority" => 1,
                    "title"    => __("Edit Profile", "peprodev-ups"),
                    "url"      => $this->get_profile_page(["section"=>"edit"]),
                  ),
                  array(
                    "slug"     => "pswd",
                    "priority" => 2,
                    "title"    => __("Change Password", "peprodev-ups"),
                    "url"      => $this->get_profile_page(["section"=>"pswd"]),
                  ),
                )
              ),

            )
          );
            if ($this->_wc_activated()) {
              $arr = array_merge( $arr,
                array(
                  "orders"        =>  array(
                    "title"      => '<i class="fa fa-shopping-bag"></i> ' .__("Orders", "peprodev-ups"),
                    "url"        => $this->get_profile_page(["section"=> "orders"]),
                    "built_in"   => true,
                    "priority"   => 701,
                  ),
                  "downloads"     =>  array(
                    "title"      => '<i class="fa fa-download"></i> ' .__("Downloads", "peprodev-ups"),
                    "url"        => $this->get_profile_page(["section"=> "downloads"]),
                    "built_in"   => true,
                    "priority"   => 703,
                  ),
                  "track"         =>  array(
                    "title"      => '<i class="fa fa-truck"></i> ' .__("Order Tracking", "peprodev-ups"),
                    "url"        => $this->get_profile_page(["section"=> "track"]),
                    "built_in"   => true,
                    "priority"   => 704,
                  ),
                ));
              if (class_exists( '\Woo_Wallet_Wallet' )){
                $arr = array_merge( $arr, array(
                  "wallet" =>
                  array(
                    "title"      => '<i class="fa fa-wallet"></i> ' .__("Wallet", "peprodev-ups"),
                    "url"        => $this->get_profile_page(["section"=> "wallet"]),
                    "built_in"   => true,
                    "priority"   => 700.5
                  )
                ));
              }
            }
            $arr = array_merge($arr, array(
                  "notifications" =>  array(
                    "title"       => '<i class="fa fa-bell"></i> ' .__("Notifications", "peprodev-ups"),
                    "url"         => $this->get_profile_page(["section"=> "notifications"]),
                    "built_in"    => true,
                    "priority"    => 900,
                  )
                )
            );
            if ($this->_ld_activated()) {
                $arr = array_merge( $arr,
                  array(
                    "courses" => array(
                                    "title"       => '<i class="fas fa-user-graduate"></i> ' .__("My Courses", "peprodev-ups"),
                                    "url"         => $this->get_profile_page(["section"=> "courses"]),
                                    "built_in"    => true,
                                    "priority"    => 222,
                                )
                  )
                );
            }
            return $arr;
        }
        public function peprofile_get_custom_user_nav_items($arr)
        {
            global $wp, $wpdb;
            $profile_url = $this->get_profile_page(["i"=>current_time("timestamp")]);
            $sections = $wpdb->get_results("SELECT * FROM `$this->tbl_sections` ORDER BY `date_created` DESC");
            if ($sections && !empty($sections)){
              foreach ($sections as $section) {

                if (empty($section->title) || empty($section->slug) || empty($section->icon) || "yes" != $section->is_active){
                  continue;
                }
                $add_item = false;
                if (!empty($section->access)){
                  $user = wp_get_current_user();
                  $notif_access = !empty(trim($section->access)) ? explode(",", $section->access) : array();
                  if(count(array_intersect($notif_access, (array) $user->roles )) > 0 ){
                    // has desired role!
                    if ($this->useLD && !is_null($section->ld_lms) && !empty($section->ld_lms)){
                      if (sfwd_lms_has_access( $section->ld_lms, get_current_user_id())){
                        $add_item = 1;
                      }
                      else{
                        $add_item = false;
                      }
                    }
                    else{
                      $add_item = 2;
                    }
                  }
                }
                else{
                  // it's a public section, show it
                  if ($this->useLD && !is_null($section->ld_lms) && !empty($section->ld_lms)){
                    if (sfwd_lms_has_access( $section->ld_lms, get_current_user_id())){
                      $add_item = 3;
                    }
                    else{
                      $add_item = false;
                    }
                  }
                  else{
                    $add_item = 4;
                  }
                }
                if ($add_item){
                  $arr = array_merge($arr,
                            array( "$section->slug" => array(
                                                  "title"       => "<i class=\"$section->icon\"></i> $section->title",
                                                  "url"         => $this->get_profile_page(["section"=> $section->slug]),
                                                  "priority"    => $section->priority,
                                                  "built_in"    => "db",
                                                )
                            )
                      );
                }

              }
            }
            return $arr;
        }
        public function peprofile_custom_user_nav_items_hndlr()
        {
            global $wp, $wpdb;
            $sections = $wpdb->get_results("SELECT * FROM `$this->tbl_sections` ORDER BY `date_created` DESC");
            if ($sections && !empty($sections)){
              foreach ($sections as $section) {
                if (empty($section->title) || empty($section->slug) || empty($section->icon) || "yes" != $section->is_active)
                  continue;

                add_action( "peprofile_dashboard_content_{$section->slug}", array( $this, "peprofile_dashboard_content_read_from_db") );
              }
            }
        }
        public function peprofile_dashboard_content_read_from_db()
        {
          global $wpdb;
          $allowed_slugs_whitelist = array_unique(apply_filters( "peprofile_dashboard_slugs", array("edit","me")));
          $current_requested_slug = isset($_GET['section']) ? sanitize_text_field(trim($_GET['section'])) : "";

          if (!empty($current_requested_slug) && in_array($current_requested_slug, $allowed_slugs_whitelist)){
            $notifs = $wpdb->get_row($wpdb->prepare("SELECT * FROM `$this->tbl_sections` WHERE `slug` = '%s' ORDER BY `date_created` DESC LIMIT 1", $current_requested_slug));
            if ($notifs && !empty($notifs)){
              // fix for wp_enqueue_style and wp_enqueue_script
              do_action( 'wp_head' );
              // fix for zephyr theme!
              wp_dequeue_style( "us-core" );
              wp_dequeue_script( "us-core" );
              wp_dequeue_style( "us-font-awesome-duotone" );
              wp_dequeue_style( "font-awesome" );

              global $PeproDevUPS_ProfileStripslashesNotifsJs;
              $this->notifs_js = stripslashes($notifs->js);
              $PeproDevUPS_ProfileStripslashesNotifsJs = $this->notifs_js;
              if (!$this->user_modern) $this->change_dashboard_title($notifs->title);
              ?>
              <style media="screen">
                /* Inline CSS @ Pepro Profile // https://pepro.dev/ */
                <?php echo stripslashes($notifs->css);?>
              </style>
              <div class="container-fluid">
                <div class="row"><div class="col-md-12"><div class="overview-wrap"><h2 class="title-1 m-b-25"><?php echo $notifs->subject;?></h2></div></div></div>
                <?php echo $this->filter_content($notifs->content);?>
              </div>

              <?php
            }
          }
        }
        public function peprofile_get_nav_items_array()
        {
          global $wp;
          $array = apply_filters( "peprofile_get_nav_items", array());
          // sort navigation items based on priority
          foreach ($array as $id => $notif) {
            $array[$id]["raw_title"] = strip_tags($array[$id]["title"]);
            if (isset($notif["built_in"]) && true == $notif["built_in"]){
              $priority = get_option( "peprofile_builtin_{$id}_priority", false );
              $array[$id]["priority"] = ($priority && !empty($priority)) ? $priority : $notif["priority"];
            }
            if (false == get_option( "peprofile_builtin_{$id}_is_enabled", true )){
              unset($array[$id]);
            }
          }

          $navs = array_column($array, "priority");
          array_multisort($navs, SORT_ASC, $array);
          return $array;
        }
        public function peprofile_get_template_part_nav()
        {
          global $wp;
          $ssection = isset($_GET['section']) ? sanitize_text_field(trim($_GET['section'])) : "home";
          foreach ($this->peprofile_get_nav_items_array() as $key => $value) {
              echo "<li data-ref='".esc_attr($key)."' class='item-priority-".esc_attr($value["priority"])."'><a href='".esc_attr($value["url"])."'>".wp_kses_post($value["title"])."</a></li>";
          }
          echo "<script>function makeactiveli(e){var divs = document.querySelectorAll(`[data-ref='".esc_html($ssection)."']`), i;for (i = 0; i < divs.length; ++i) {divs[i].className = divs[i].className + ' active';}} window.onload = makeactiveli; makeactiveli(); setTimeout(function () { makeactiveli() }, 500);</script>";
        }
        public function peprofile_dashboard_slugs($oldArray=array())
        {
            $newSlugs = array();
            foreach ($this->peprofile_get_nav_items_array() as $key => $value) { $newSlugs[] = $key; }
            return array_merge($oldArray, $newSlugs);
        }
        /* wc and profile dashboard helpers */
        public function get_customer_total_order()
        {
          $customer_orders = get_posts( array(
            'numberposts' => - 1,
            'meta_key'    => '_customer_user',
            'meta_value'  => get_current_user_id(),
            'post_type'   => array( 'shop_order' ),
            'post_status' => array( 'wc-completed' )
          ) );

          $total = 0;
          foreach ( $customer_orders as $customer_order ) {
            $order = wc_get_order( $customer_order );
            $total += $order->get_total();
          }

          return number_format($total, wc_get_price_decimals(),wc_get_price_decimal_separator(), wc_get_price_thousand_separator()) . " " . get_woocommerce_currency_symbol();
        }
        public function get_customer_total_orders_by_status($statss)
        {
          $customer_orders = get_posts( array(
            'numberposts' => - 1,
            'meta_key'    => '_customer_user',
            'meta_value'  => get_current_user_id(),
            'post_type'   => array( 'shop_order' ),
            'post_status' => array( $statss )
          ) );
          return count($customer_orders);
        }
        public function get_promotion_data()
        {
          return $this->filter_content(get_option("{$this->activation_status}-customhtml"));
        }
        public function get_customer_get_credit_balance()
        {
          $wallet = 00;
          if (class_exists( '\Woo_Wallet_Wallet' )){
            $wallet = woo_wallet()->wallet->get_wallet_balance( get_current_user_id() , '' );
            $wallet = wp_kses($wallet,array());
          }
          return number_format((int) $wallet, wc_get_price_decimals(),wc_get_price_decimal_separator(), wc_get_price_thousand_separator()) . "<span class='supper'>".get_woocommerce_currency_symbol()."</span>";
        }
        public function get_customer_total_order_count()
        {
          $customer_orders = get_posts( array(
            'numberposts' => - 1,
            'meta_key'    => '_customer_user',
            'meta_value'  => get_current_user_id(),
            'post_type'   => array( 'shop_order' ),
            'post_status' => array( 'wc-completed' )
          ) );

          return count($customer_orders);
        }
        /* front end functions
        */
        public function add_input($title='',$id='',$val='',$extrahtml='',$class='',$type='text')
        {
            echo "<div class='form-group pepro-login-reg-field $type $id-wrap'>
                <label for='$id' class='control-label mb-1'>$title</label>
                <input id='$id' name='$id' type='$type' class='form-control $class' $extrahtml value='".esc_attr($val)."' />
              </div>";
        }
        public function parse_date($str)
        {
            return date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($str));// get format from WordPress settings.
        }
        public function translate_priority($p)
        {
            $trUrgent = _x("Urgent", "notifications-priority", "peprodev-ups");
            $trHigh = _x("High", "notifications-priority", "peprodev-ups");
            $trMedium = _x("Medium", "notifications-priority", "peprodev-ups");
            $trLow = _x("Low", "notifications-priority", "peprodev-ups");
            $trNormal = _x("Normal", "notifications-priority", "peprodev-ups");
            switch ($p) {
            case '1':
                return $trUrgent;
                break;
            case '2':
                return $trHigh;
                break;
            case '3':
                return $trMedium;
                break;
            case '4':
                return $trLow;
                break;
            default:
                return $trNormal;
                break;
            }
        }
        public function show_notifications_edit_panel($page=1, $search="", $url="")
        {
            global $wpdb;
            ob_start();
            $td = $this->td;
            $notifs = false;
            $PeproDevUPS_Profile = $this;
            $post_per_page = 50;
            $offset = ( $page * $post_per_page ) - $post_per_page;
            $otif404 = sprintf(
                _x("No notification found! please consider %s.", "notifications-priority", $td),
                '<a id="add_notifpopup" href="#">'._x("adding new one", "notifications-section", $td).'</a>'
            );

            if (!empty(trim($search))) {
                $s = sanitize_text_field(esc_html(trim($search)));
                $total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(1) FROM (SELECT id FROM `$PeproDevUPS_Profile->tbl_notif` WHERE title LIKE %s OR content LIKE %s OR date_scheduled LIKE %s OR date_scheduledFA LIKE %s OR icon LIKE %s OR action_title_1 LIKE %s OR action_title_2 LIKE %s OR action_url_1 LIKE %s OR action_url_2 LIKE %s) AS combined_table", "%{$s}%","%{$s}%","%{$s}%","%{$s}%","%{$s}%","%{$s}%","%{$s}%","%{$s}%","%{$s}%"));
                $notifs = $wpdb->get_results($wpdb->prepare("SELECT * FROM `$PeproDevUPS_Profile->tbl_notif` WHERE title LIKE %s OR content LIKE %s OR date_scheduled LIKE %s OR date_scheduledFA LIKE %s OR icon LIKE %s OR action_title_1 LIKE %s OR action_title_2 LIKE %s OR action_url_1 LIKE %s OR action_url_2 LIKE %s ORDER BY `date_created` DESC LIMIT %d, %d", "%{$s}%","%{$s}%","%{$s}%","%{$s}%","%{$s}%","%{$s}%","%{$s}%","%{$s}%","%{$s}%", $offset, $post_per_page));

                $otif404 = sprintf(_x("Sorry, your search for \"%s\" didn't match any notification!", "notifications-priority", $td), $s);
            }else{
                $total = $wpdb->get_var("SELECT COUNT(1) FROM $PeproDevUPS_Profile->tbl_notif AS combined_table");
                $notifs = $wpdb->get_results($wpdb->prepare("SELECT * FROM `$PeproDevUPS_Profile->tbl_notif` ORDER BY `date_created` DESC LIMIT %d, %d", $offset, $post_per_page));
            }
            $integrity = wp_create_nonce('peprocorenounce');

            if (false !== $notifs && 0 !== $notifs && !empty($notifs)) {
                foreach ( $notifs as $notif ){
                  $dataNotifExtraDetails = []; $usrs = "";
                  if (empty(trim($notif->date_scheduledFA))) {
                      $publishStatus = sprintf(_x("Published at %s", "notification-list-publishdate", "$td"), $this->parse_date($notif->date_created));
                  }else{
                      // $publishStatus = sprintf(_x("Scheduled for %s (%s)","notification-list-publishdate","$td"),$this->parse_date($notif->date_scheduled), $PeproDevUPS_Profile->parse_date($notif->date_scheduledFA));
                      $publishStatus = sprintf(_x("Scheduled for %s", "notification-list-publishdate", "$td"), $this->parse_date($notif->date_scheduled));
                  }
                  if ($notif->users_list == "all") {
                      $userRange = _x("Current and Future users", "notification-list-publishdate", "$td");
                  }else{
                      $usrs = explode(",", $notif->users_list);
                      $userRange = sprintf(_x("For %s users", "notification-list-publishdate", "$td"), ($usrs > 0 ? count($usrs) : 0));
                  }
                  $priority = $this->translate_priority($notif->priority);
                  $dataNotifExtraDetails = array(
                    "icon"              => $notif->icon ,
                    "users"             => ($usrs > 0 ? count($usrs) : 0) ,
                    "userz"             => $usrs,
                    "title"             => $notif->title ,
                    "color"             => $notif->color ,
                    "content"           => stripslashes($notif->content),
                    "priority"          => $notif->priority ,
                    "priority2"         => $priority ,
                    "users_list"        => $notif->users_list ,
                    "users_list2"       => $userRange ,
                    "action_url_1"      => $notif->action_url_1 ,
                    "action_url_2"      => $notif->action_url_2 ,
                    "action_title_1"    => $notif->action_title_1 ,
                    "action_title_2"    => $notif->action_title_2 ,
                    "publish_status"    => $publishStatus ,
                    "date_created"      => $notif->date_created ,
                    "date_scheduled"    => $notif->date_scheduled ,
                    "date_scheduledFA"  => $notif->date_scheduledFA ,
                    "date_created2"     => $this->parse_date($notif->date_created) ,
                    "date_scheduled2"   => $this->parse_date($notif->date_scheduled) ,
                    "date_scheduledFA2" => $this->parse_date($notif->date_scheduledFA) ,
                  );
                  $notif_title_icon_set = "<div class='{$notif->color} img-cir img-40'><i class='{$notif->icon}'></i></div>";
                  $dataNotifExtraDetails = esc_attr(json_encode($dataNotifExtraDetails, JSON_NUMERIC_CHECK|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
                  echo "<tr data-nofit-tr=\"{$notif->id}\" data-json=\"$dataNotifExtraDetails\" >
                            <td style=\"direction: ltr;\">{$PeproDevUPS_Profile->parse_date($notif->date_created)}</td>
                            <td>{$notif_title_icon_set}{$notif->title}</td>
                            <td>{$publishStatus}</td>
                            <td>{$userRange}</td>
                            <td>{$priority}</td>
                            <td class=\"td-actions\">
                              <!-- button type=\"button\" title=\"".esc_attr_x("View", "action-title", "peprodev-ups") ."\" class=\"btn btn-primary btn-sm view_notif_modal\" data-id=\"{$notif->id}\" integrity=\"".esc_attr($integrity)."\" wparam=\"".esc_attr($this->setting_slug)."\" lparam=\"view_notif\"><i class=\"fa fa-eye\"></i></button -->
                              <button type=\"button\" title=\"".esc_attr_x("Edit", "action-title", "peprodev-ups") ."\" class=\"btn btn-primary btn-sm edit_notif_modal\" data-id=\"{$notif->id}\" integrity=\"".esc_attr($integrity)."\" wparam=\"".esc_attr($this->setting_slug)."\" lparam=\"edit_notif\"><i class=\"fa fa-pencil\"></i></button>
                              <button type=\"button\" title=\"".esc_attr_x("Remove", "action-title", "peprodev-ups") ."\" class=\"btn btn-primary btn-sm remove_notif_modal\" data-id=\"{$notif->id}\" integrity=\"".esc_attr($integrity)."\" wparam=\"".esc_attr($PeproDevUPS_Profile->setting_slug)."\" lparam=\"remove_notif\"><i class=\"fa fa-trash-alt\"></i></button>
                            </td>
                          </tr>";
                }
                echo "<tr data-nofit-tr=\"empty\" style=\"display:none;\"><td colspan=\"10\" style=\"text-align: center;\">$otif404</td></tr>";
                echo '<tr><td colspan="6" style="text-align: center;" class="pagination">';
                $url = !$url ? $_SERVER['REQUEST_URI'] : $url;
                echo paginate_links(
                    array(
                    'base' => add_query_arg(array('cpage' => '%#%'), $url),
                    'format' => '',
                    'prev_text' => __('&laquo;'),
                    'next_text' => __('&raquo;'),
                    'before_page_number' => "<span class='btn btn-action no-ripple btn-sm'>",
                    'after_page_number' => "</span>",
                    'total' => ceil($total / $post_per_page),
                    'current' => $page,
                    'prev_next' => false,
                    'type' => 'list'
                    )
                );
                echo '</td></tr>';
            }
            else{
                echo "<tr data-nofit-tr=\"empty\"><td colspan=\"10\" style=\"text-align: center;\">$otif404</td></tr>";
            }
            $tcona = ob_get_contents();
            ob_end_clean();
            return $tcona;
        }
        public function show_sections_edit_panel($page=1, $search="", $url="")
        {
            global $wpdb;
            ob_start();
            $td = $this->td;
            $notifs = false;
            $PeproDevUPS_Profile = $this;
            $post_per_page = 50;
            $offset = ( $page * $post_per_page ) - $post_per_page;
            $otif404 = sprintf( _x("No section found! please consider %s.", "notifications-priority", $td), '<a id="add_notifpopup" href="#">'._x("adding new section", "notifications-section", $td).'</a>' );

            if (!empty(trim($search))) {
                $s = sanitize_text_field(esc_html(trim($search)));
                $total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(1) FROM (SELECT id FROM `$this->tbl_sections` WHERE title LIKE %s OR content LIKE %s OR icon LIKE %s) AS combined_table", "%{$s}%","%{$s}%","%{$s}%"));
                $notifs = $wpdb->get_results($wpdb->prepare("SELECT * FROM `$this->tbl_sections` WHERE title LIKE %s OR content LIKE %s OR icon LIKE %s ORDER BY `date_created` DESC LIMIT %d, %d", "%{$s}%","%{$s}%","%{$s}%", $offset, $post_per_page));
                $otif404 = sprintf(_x("Sorry, your search for \"%s\" didn't match any notification!", "notifications-priority", $td), $s);
            }else{
                $total = $wpdb->get_var("SELECT COUNT(1) FROM $this->tbl_sections AS combined_table");
                $notifs = $wpdb->get_results($wpdb->prepare("SELECT * FROM `$this->tbl_sections` ORDER BY `date_created` DESC LIMIT %d, %d", $offset, $post_per_page));
            }
            $integrity = wp_create_nonce('peprocorenounce');

            if (false !== $notifs && 0 !== $notifs && !empty($notifs)) {

                echo $this->get_built_in_sections_trs();

                foreach ( $notifs as $notif ){
                  $dataNotifExtraDetails = [];
                  $dataNotifExtraDetails = array(
                    "title"             => $notif->title,
                    "icon"              => $notif->icon,
                    "slug"              => $notif->slug,
                    "subject"           => $notif->subject,
                    "access"            => $notif->access,
                    "ld_lms"            => $notif->ld_lms,
                    "css"               => stripslashes($notif->css),
                    "js"                => stripslashes($notif->js),
                    "content"           => stripslashes($notif->content),
                    "priority"          => $notif->priority,
                    "is_active"         => $notif->is_active,
                    "date_created"      => $notif->date_created,
                    "date_modified"     => $notif->date_modified,
                    "jdate_created"     => $this->parse_date($notif->date_created),
                    "jdate_modified"    => $this->parse_date($notif->date_modified),
                  );
                  $color_icon = "yes" == $notif->is_active ? "bg-c1" : "bg-c3" ;
                  $notif_title_icon_set = "<div class='$color_icon img-cir img-40'><i class='{$notif->icon}'></i></div>";
                  $notif_access = !empty(trim($notif->access)) ? explode(",", $notif->access) : array(); $notif_access_str = array();
                  global $wp_roles;
                  foreach ($notif_access as $key => $value) {
                      if (isset($wp_roles->roles[$value])){
                        if (isset($wp_roles->roles[$value]['name'])){
                          $value = $wp_roles->roles[$value]['name'];
                        }
                      }
                      $notif_access_str[] = translate_user_role($value);
                  }
                  if (!is_null($notif->ld_lms) && !empty($notif->ld_lms)){
                    $notif_access_str[] = sprintf(__("Enrolled users of Ld-course: %s","peprodev-ups"),"<strong>".get_the_title( $notif->ld_lms )." (#<a target='_blank' href='".admin_url("post.php?post=$notif->ld_lms&action=edit")."'>$notif->ld_lms</a>)</strong>");
                  }

                  $dataNotifExtraDetails = esc_attr(json_encode($dataNotifExtraDetails, JSON_NUMERIC_CHECK|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
                  $urlprofile = $this->get_profile_page(["section"=>$notif->slug]);
                  echo "<tr data-nofit-tr=\"{$notif->id}\" data-json=\"$dataNotifExtraDetails\" >
                            <td style=\"direction: ltr;\">{$PeproDevUPS_Profile->parse_date($notif->date_created)}</td>
                            <td>{$notif_title_icon_set}{$notif->title}</td>
                            <td>{$notif->subject}</td>
                            <td><a href='$urlprofile' target='_blank'>{$notif->slug}</a></td>
                            <td>". (empty($notif_access_str) ? __("— Public —","peprodev-ups") : implode(" / ", $notif_access_str)) ."</td>
                            <td>{$notif->priority}</td>
                            <td class=\"td-actions\">
                              <!-- button type=\"button\" title=\"".esc_attr_x("View","action-title","peprodev-ups")."\" class=\"btn btn-primary btn-sm view_notif_modal\" data-id=\"{$notif->id}\" integrity=\"".esc_attr($integrity)."\" wparam=\"".esc_attr($this->setting_slug)."\" lparam=\"view_section\"><i class=\"fa fa-eye\"></i></button -->
                              <button type=\"button\" title=\"".esc_attr_x("Edit","action-title","peprodev-ups")."\" class=\"btn btn-primary btn-sm edit_notif_modal\" data-id=\"{$notif->id}\" integrity=\"".esc_attr($integrity)."\" wparam=\"".esc_attr($this->setting_slug)."\" lparam=\"edit_section\"><i class=\"fa fa-pencil\"></i></button>
                              <button type=\"button\" title=\"".esc_attr_x("Remove","action-title","peprodev-ups")."\" class=\"btn btn-primary btn-sm remove_notif_modal\" data-id=\"{$notif->id}\" integrity=\"".esc_attr($integrity)."\" wparam=\"".esc_attr($PeproDevUPS_Profile->setting_slug)."\" lparam=\"remove_section\"><i class=\"fa fa-trash-alt\"></i></button>
                            </td>
                          </tr>";
                }

                echo "<tr data-nofit-tr=\"empty\" style=\"display:none;\"><td colspan=\"5\" style=\"text-align: center;\">$otif404</td></tr>";
                echo '<tr><td colspan="5" style="text-align: center;" class="pagination">';
                $url = !$url ? $_SERVER['REQUEST_URI'] : $url;
                echo paginate_links(
                    array(
                    'base' => add_query_arg(array('cpage' => '%#%'), $url),
                    'format' => '',
                    'prev_text' => __('&laquo;'),
                    'next_text' => __('&raquo;'),
                    'before_page_number' => "<span class='btn btn-action no-ripple btn-sm'>",
                    'after_page_number' => "</span>",
                    'total' => ceil($total / $post_per_page),
                    'current' => $page,
                    'prev_next' => false,
                    'type' => 'list'
                    )
                );
                echo '</td></tr>';
            }
            else{
              $bi = $this->get_built_in_sections_trs();
              if ( $bi && !empty($bi)){
                echo $bi;
              }else{
                echo "<tr data-nofit-tr=\"empty\"><td colspan=\"7\" style=\"text-align: center;\">$otif404</td></tr>";
              }
            }
            $tcona = ob_get_contents();
            ob_end_clean();
            return $tcona;
        }
        public function show_newsletter_edit_panel($page=1, $search="", $url="", $retTotal=false)
        {
          global $wpdb;
          ob_start();
          $td            = $this->td;
          $post_per_page = (int) isset($_GET["per_page"]) ? sanitize_text_field($_GET["per_page"]) : 30;
          $notifs        = false;
          $offset        = ( $page * $post_per_page ) - $post_per_page;
          $otif404       = __("No subscriber found!", "notifications-priority", $td);
          $integrity     = wp_create_nonce('peprocorenounce');
          $total         = $wpdb->get_var("SELECT COUNT(1) FROM $this->tbl_subscribers AS combined_table");

          if ($retTotal) return $total;

          $notifs        = $wpdb->get_results($wpdb->prepare("SELECT * FROM `$this->tbl_subscribers` ORDER BY `date_created` DESC LIMIT %d, %d", $offset, $post_per_page));

          if (false !== $notifs && 0 !== $notifs && !empty($notifs)) {

              foreach ( $notifs as $notif ){
                $dataNotifExtraDetails = [];
                $dataNotifExtraDetails = array(
                  "date_created" => $notif->date_created,
                  "user"         => $notif->user,
                  "name"         => $notif->name,
                  "mobile"       => $notif->mobile,
                  "email"        => $notif->email,
                  "extra_info"   => $notif->extra_info
                );
                $dataNotifExtraDetails = esc_attr(json_encode($dataNotifExtraDetails, JSON_NUMERIC_CHECK|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
                echo "<tr data-nofit-tr=\"{$notif->id}\" data-json=\"$dataNotifExtraDetails\" >
                  <td style=\"direction: ltr;\">{$this->parse_date($notif->date_created)}</td>
                  <td>{$notif->name}</td>
                  <td>{$notif->mobile}</td>
                  <td>{$notif->email}</td>
                  <td><a href='".admin_url("user-edit.php?user_id={$notif->user}")."' target='_blank'>UID#{$notif->user}</a></td>
                  <td class=\"td-actions\"><button  type=\"button\"
                                                    title=\"".esc_attr_x("Remove","action-title","peprodev-ups")."\"
                                                    class=\"btn btn-primary btn-sm remove_notif_modal\"
                                                    data-id=\"{$notif->id}\"
                                                    data-integrity=\"".esc_attr($integrity)."\"
                                                    data-wparam=\"".esc_attr($this->setting_slug)."\"
                                                    data-lparam=\"remove_subscriber\"><i class=\"fa fa-trash-alt\"></i></button></td>
                </tr>";
              }

              echo "<tr data-nofit-tr=\"empty\" style=\"display:none;\"><td colspan=\"8\" style=\"text-align: center;\">$otif404</td></tr>";
              echo '<tr><td colspan="5" style="text-align: center;" class="pagination">';
              $url = !$url ? $_SERVER['REQUEST_URI'] : $url;
              echo paginate_links(array(
                  'before_page_number' => "<span class='btn btn-action no-ripple btn-sm'>",
                  'after_page_number'  => "</span>",
                  'base'      => add_query_arg(array('cpage' => '%#%'), $url),
                  'format'    => '',
                  'prev_text' => __('&laquo;'),
                  'next_text' => __('&raquo;'),
                  'total'     => ceil($total / $post_per_page),
                  'current'   => $page,
                  'prev_next' => false,
                  'type'      => 'list'
                ));
              echo '</td></tr>';
          }
          else{
              echo "<tr data-nofit-tr=\"empty\"><td colspan=\"8\" style=\"text-align: center;\">$otif404</td></tr>";
          }
          $tcona = ob_get_contents();
          ob_end_clean();
          return $tcona;
        }
        public function get_built_in_sections_trs()
        {
          ob_start();
          $built_in = apply_filters( "peprofile_get_nav_items", array());
          $integrity = wp_create_nonce('peprocorenounce');
          foreach ($built_in as $notif_id => $notif) {
            $notif["icon"] = str_replace(array(esc_html("<i class="),esc_html(">"),esc_html("'"),esc_html('"')),array("","","",""),explode(esc_html("</i>"),esc_html($notif["title"]))[0]);
            $notif["title"] = trim(explode(esc_html("</i>"),esc_html($notif["title"]))[1]);
            $notif["is_active"] = get_option( "peprofile_builtin_{$notif_id}_is_enabled", true );
            $priority = get_option( "peprofile_builtin_{$notif_id}_priority", false );
            $notif["priority"] = ($priority && !empty($priority)) ? $priority : $notif["priority"];

            if (!isset($notif["built_in"]) || true !== $notif["built_in"])
                continue;

            $color_icon = (true == $notif["is_active"]) ? "bg-c1" : "bg-c3" ;
            $is_active = $notif["is_active"] ? "true" : "false" ;
            $notif_title_icon_set = "<div class='$color_icon img-cir img-40'><i class='{$notif["icon"]}'></i></div>";
            $url = $this->get_profile_page(["section"=>$notif_id]);
            echo "<tr class='builtin' data-nofit-tr=\"{$notif_id}\" data-active=\"{$is_active}\" data-priority=\"{$notif["priority"]}\" data-title=\"{$notif["title"]}\" >
                      <td style=\"direction: ltr;\">".__("— Built-in Section —","peprodev-ups")."</td>
                      <td class='title'>{$notif_title_icon_set}{$notif["title"]}</td>
                      <td>".__("— Built-in Section —","peprodev-ups")."</td>
                      <td class='href'><a href='$url' target='_blank'>$notif_id</a></td>
                      <td>".__("— Public —","peprodev-ups")."</td>
                      <td class='priority'>{$notif["priority"]}</td>
                      <td class=\"td-actions\">
                        <button type=\"button\" title=\"".esc_attr_x("Edit","action-title","peprodev-ups")."\" class=\"btn btn-primary btn-sm edit_notif_builtin\" data-id=\"{$notif_id}\" integrity=\"".esc_attr($integrity)."\" wparam=\"".esc_attr($this->setting_slug)."\" lparam=\"edit_section_builtin\"><i class=\"fa fa-pencil\"></i></button>
                        <button type=\"button\" disabled title=\"".esc_attr_x("Remove","action-title","peprodev-ups")."\" class=\"btn btn-gray btn-sm disabled\"><i class=\"fa fa-trash-alt\"></i></button>
                      </td>
                    </tr>";

          }
          echo "<tr class='resttr'><td colspan='7'></td></tr>";
          $tr = ob_get_contents();
          ob_end_clean();
          return $tr;
        }
        public function get_fontawesomepro_class_list()
        {
            include_once "$this->assets_dir/libs/extras/fas.php"; // FA Solid
            include_once "$this->assets_dir/libs/extras/far.php"; // FA Regular
            include_once "$this->assets_dir/libs/extras/fal.php"; // FA Light
            include_once "$this->assets_dir/libs/extras/fab.php"; // FA Brands
            $list = ""; $icons = array_merge($fas, $far, $fal, $fab);
            $list .= '
              <div class="faselectorsticky">
                <div class="nav nav-tabs" data-tabs="tabs" style="float: left;">
                  <a class="btn btn-primary btn-sm no-ripple active show" title="Font Awesome Pro Solid" href="#navSolid" data-toggle="tab">Solid</a></li>
                  <a class="btn btn-primary btn-sm no-ripple" title="Font Awesome Pro Regular" href="#navRegular" data-toggle="tab">Regular</a></li>
                  <a class="btn btn-primary btn-sm no-ripple" title="Font Awesome Pro Light" href="#navLight" data-toggle="tab">Light</a></li>
                  <a class="btn btn-primary btn-sm no-ripple" title="Font Awesome Pro Brands" href="#navBrands" data-toggle="tab">Brands</a></li>
                </div>
                <div class="nav nav-search" data-tabs="tabs" style="width: auto;">
                  <input id="searchfontawesome" class="form-control search-fa" placeholder="'.__("Search here ...","peprodev-ups").'" />
                </div>
              </div>
              <div class="tab-content">';
              $list .= "<div class='tab-pane active' id='navSolid'>";
                foreach ($fas as $key) {$list .= "<li data-class='$key' class='$key'></li>"; }
              $list .= "</div>";
              $list .= "<div class='tab-pane' id='navRegular'>";
                foreach ($far as $key) {$list .= "<li data-class='$key' class='$key'></li>"; }
              $list .= "</div>";
              $list .= "<div class='tab-pane' id='navLight'>";
                foreach ($fal as $key) {$list .= "<li data-class='$key' class='$key'></li>"; }
              $list .= "</div>";
              $list .= "<div class='tab-pane' id='navBrands'>";
                foreach ($fab as $key) {$list .= "<li data-class='$key' class='$key'></li>"; }
              $list .= "</div>";
              $list .= "</div>";

            return $list;
        }
        /* wp based setting style
        */
        public function help_container($hook)
        {
            ob_start();
            $this->update_footer_info();
            $tcona = ob_get_contents();
            ob_end_clean();
            print $tcona;
        }
        /* notif panel */
        public function add_notif_input($req="",$id="",$title="",$placeholder="",$type="text",$class="",$value="",$extra_html = "")
        {
            echo "<tr $req><td ><label class=\"text-primary\" for=\"$id\">$title</label></td>
          <td><input id=\"$id\" type=\"$type\" name=\"$id\" required class=\"form-control primary $class\" $extra_html placeholder=\"$placeholder\" value=\"$value\"></td></tr>";
        }
        public function add_notif_textarea($req="",$id="",$title="",$placeholder="",$class="",$value="")
        {
            echo "<tr $req><td ><label class=\"text-primary\" for=\"$id\">$title</label></td>
          <td><textarea col=\"40\" row=\"5\" id=\"$id\" name=\"$id\" class=\"form-control textarea $class\" placeholder=\"$placeholder\">$value</textarea></td></tr>";
        }
        public function add_notif_editor($req="",$id="",$title="",$placeholder="",$class="",$value="")
        {
          echo "<tr $req><td><label class=\"text-primary\" for=\"$id\">$title</label></td><td>";
          wp_editor( $value, $id,
            array(
              'textarea_name'    => $id,
              'editor_height'    => 350,
              'editor_css'       => '',     // Additional CSS styling applied for both visual and HTML editors buttons, needs to include <style> tags, can use "scoped"
              'editor_class'     => '',     // Any extra CSS Classes to append to the Editor textarea
              'teeny'            => false,  // Whether to output the minimal editor configuration used in PressThis
              'dfw'              => false,  // Whether to replace the default fullscreen editor with DFW (needs specific DOM elements and CSS)
              'media_buttons'    => true,   // Whether to display media insert/upload buttons
              'wpautop'          => true,   // Whether to use wpautop for adding in paragraphs. Note that the paragraphs are added automatically when wpautop is false.
              'tinymce'          => true,   // Load TinyMCE, can be used to pass settings directly to TinyMCE using an array
              'quicktags'        => true,   // Load Quicktags, can be used to pass settings directly to Quicktags using an array. Set to false to remove your editor's Visual and Text tabs.
              'drag_drop_upload' => true,   // Enable Drag & Drop Upload Support (since WordPress 3.9)
            )
          );
          echo "</td></tr>";
        }
        public function add_notif_opt($title="",$html="")
        {
            echo "<table class=\"table table-hover\">
                  <thead class=\"text-primary\"><tr><th>$title</th></tr></thead>
                  <tbody><tr><td>$html</td></tr></tbody>
                </table>";
        }
        /* database creation and destruction
        */
        public function CreateDatabase($force = false)
        {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();
            if(!function_exists('dbDelta')) {include_once ABSPATH . 'wp-admin/includes/upgrade.php'; }

            $tbl = $this->tbl_notif;
            if ($wpdb->get_var("SHOW TABLES LIKE '". $tbl ."'") != $tbl || $force) {
              $sql = "CREATE TABLE `$tbl` (
              `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              `date_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              `date_scheduled` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              `date_scheduledFA` TINYTEXT,
              `title` TINYTEXT,
              `content` LONGTEXT,
              `icon` TINYTEXT,
              `color` TINYTEXT,
              `priority` ENUM( '1', '2', '3', '4', '5' ) DEFAULT '5',
              `action_title_1` TINYTEXT,
              `action_title_2` TINYTEXT,
              `action_url_1` TINYTEXT,
              `action_url_2` TINYTEXT,
              `users_list` LONGTEXT,
              PRIMARY KEY id (id) ) $charset_collate;";
              dbDelta($sql);
            }

            $tbl = "{$this->tbl_notif}_list";
            if ($wpdb->get_var("SHOW TABLES LIKE '". $tbl ."'") != $tbl || $force) {
              $sql = "CREATE TABLE `$tbl` (
              `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              `user_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
              `notif_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
              `has_seen` ENUM( '0', '1' ) DEFAULT '0',
              `seen_first_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              `seen_last_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY id (id) ) $charset_collate;";
              dbDelta($sql);
            }

            $tbl = $this->tbl_sections;
            if ($wpdb->get_var("SHOW TABLES LIKE '". $tbl ."'") != $tbl || $force) {
              $sql = "CREATE TABLE `$tbl` (
              `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              `date_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              `title` TINYTEXT,
              `slug` TINYTEXT,
              `subject` TINYTEXT,
              `content` LONGTEXT,
              `js` LONGTEXT,
              `css` LONGTEXT,
              `icon` TINYTEXT,
              `access` LONGTEXT,
              `ld_lms` INT(10),
              `is_active` VARCHAR(10) DEFAULT 'yes',
              `priority` INT(10) UNSIGNED NOT NULL DEFAULT '1000',
              PRIMARY KEY id (id) ) $charset_collate;";
              dbDelta($sql);
            }

        }
        public function DropDatabase()
        {
            global $wpdb;
            $wpdb->query("DROP TABLE IF EXISTS {$this->tbl_notif}");
            $wpdb->query("DROP TABLE IF EXISTS {$this->tbl_notif}_list");
            $wpdb->query("DROP TABLE IF EXISTS {$this->tbl_sections}");
        }
        public function integrate_With_VC()
        {
            vc_map(
                array(
                'base' => "pepro-profile",
                'name' => $this->title,
                'description' => __('Adds Pepro Profile Dashoard to page', "peprodev-ups"),
                'class' => "{$this->td}__class",
                'icon' => "{$this->assets_url}/assets/img/peprodev.svg",
                'show_settings_on_create' => false,
                'admin_enqueue_css' => array("{$this->assets_url}/assets/css/vc.init.css","{$this->assets_url}/assets/css/select2.min.css"),
                'admin_enqueue_js' => array("{$this->assets_url}/assets/js/select2.min.js"),
                'category' => __('Pepro Elements', "peprodev-ups"),
                'params' => false
                )
            );
        }
        public function vc_add_pepro_about($settings, $value)
        {
            ob_start();
            echo "<div style='display: flex;align-items: center;justify-content: flex-start;flex-direction: row-reverse;'>
                    <p style='margin-right: 1rem;'><img src='".plugins_url("/assets/img/pepro.png", __FILE__)."' width='55px' /></p>
                    <p>Proudly Developed by <a target='_blank' href='https://pepro.dev/'>Pepro Dev. Group</a></p>
                  </div>";
            $tcona = ob_get_contents();
            ob_end_clean();
            return $tcona;
        }
        public function add_special_post($force = false)
        {
          if (false === $this->get_profile_page() || "yes" != get_option("{$this->activation_status}-profile-dash-page-created","")){
            $profile_template = array(
              'post_type'     => 'page',
              'post_title'    => __("User Dashboard","peprodev-ups"),
              'post_content'  => '[pepro-profile]',
              'post_name'     => 'profile',
              'post_status'   => 'publish',
              'page_template' => '',
              'comment_status'=> 'closed',
            );
            $post_id = wp_insert_post( $profile_template );
            if(!is_wp_error($post_id)){
              update_option("{$this->activation_status}-profile-dash-page", $post_id);
              update_option("{$this->activation_status}-profile-dash-page-created", "yes");
            }
          }
        }
        /* common functions
        */
        private function update_footer_info()
        {
            add_filter(
                'admin_footer_text', function () {
                    return sprintf(_x("Thanks for using %s products", "footer-copyright", "peprodev-ups"), "<b><a href='https://pepro.dev/' target='_blank' >".__("Pepro Dev. Group", "peprodev-ups")."</a></b>");
                }, 11
            );
            add_filter(
                'update_footer', function () {
                    return sprintf(_x("Version %s", "footer-copyright", "peprodev-ups"), $this->version);
                }, 11
            );
        }
        private function hide_me()
        {
            add_filter(
                'all_plugins', function ($plugins) {
                    if(in_array(plugin_basename(__FILE__), array_keys($plugins))) {unset($plugins[plugin_basename(__FILE__)]);
                    }
                    return $plugins;
                }
            );
            add_action(
                'pre_current_active_plugins', function () {
                    global $wp_list_table;
                    foreach ($wp_list_table->items as $key => $val) {
                        if(in_array($key, array(plugin_basename(__FILE__)))) {unset($wp_list_table->items[$key]);
                        }
                    }
                }
            );
        }
        public function _wc_activated()
        {
          if (!is_plugin_active('woocommerce/woocommerce.php') || !function_exists('is_woocommerce') || !class_exists('\woocommerce') ) { return false; }
          return true;
        }
        public function _ld_activated()
        {
          return defined('LEARNDASH_LMS_PLUGIN_DIR');
        }
        public function _vc_activated()
        {
          if (!is_plugin_active('js_composer/js_composer.php') || !defined('WPB_VC_VERSION')) { return false; }
          return true;
        }
        public function read_opt($mc, $def="")
        {
            return get_option($mc, $def);
        }
        public static function do_icon($icon, $attr = array(), $content = '')
        {
            $class = '';
            if (false === strpos($icon, '/') && 0 !== strpos($icon, 'data:') && 0 !== strpos($icon, 'http')) {
                // It's an icon class.
                $class .= ' dashicons ' . $icon;
            } else {
                // It's a Base64 encoded string or file URL.
                $class .= ' vaa-icon-image';
                $attr   = self::merge_attr(
                    $attr, array(
                    'style' => array( 'background-image: url("' . $icon . '") !important' ),
                    )
                );
            }

            if (! empty($attr['class'])) {
                $class .= ' ' . (string) $attr['class'];
            }
            $attr['class']       = $class;
            $attr['aria-hidden'] = 'true';

            $attr = self::parse_to_html_attr($attr);
            return '<span ' . $attr . '>' . $content . '</span>';
        }
        public static function parse_to_html_attr($array)
        {
            $str = '';
            if (is_array($array) && ! empty($array)) {
                foreach ($array as $attr => $value) {
                    if (is_array($value)) {
                        $value = implode(' ', $value);
                    }
                    $array[ $attr ] = esc_attr($attr) . '="' . esc_attr($value) . '"';
                }
                $str = implode(' ', $array);
            }
            return $str;
        }
        public function print_setting_input($SLUG="", $CAPTION="", $extraHtml="", $type="text",$extraClass="")
        {
            $ON = sprintf(_x("Enter %s", "setting-page", "peprodev-ups"), $CAPTION);
            echo "<tr>
    			<th scope='row'>
    				<label for='$SLUG'>$CAPTION</label>
    			</th>
    			<td><input name='$SLUG' $extraHtml type='$type' id='$SLUG' placeholder='$CAPTION' title='$ON' value='" . $this->read_opt($SLUG) . "' class='regular-text $extraClass' /></td>
    		</tr>";
        }
        public function print_setting_select($SLUG, $CAPTION, $dataArray=array())
        {
            $ON = sprintf(_x("Choose %s", "setting-page", "peprodev-ups"), $CAPTION);
            $OPTS = "";
            foreach ($dataArray as $key => $value) {
                if ($key == "EMPTY") {
                    $key = "";
                }
                $OPTS .= "<option value='$key' ". selected($this->read_opt($SLUG), $key, false) .">$value</option>";
            }
            echo "<tr>
      			<th scope='row'>
      				<label for='$SLUG'>$CAPTION</label>
      			</th>
      			<td><select name='$SLUG' id='$SLUG' title='$ON' class='regular-text'>
            ".$OPTS."
            </select>
            </td>
      		</tr>";
        }
        public function print_setting_editor($SLUG, $CAPTION, $re="")
        {
            echo "<tr><th><label for='$SLUG'>$CAPTION</label></th><td>";
            wp_editor(
                $this->read_opt($SLUG, ''), strtolower(str_replace(array('-', '_', ' ', '*'), '', $SLUG)), array(
                'textarea_name' => $SLUG
                )
            );
            echo "<p class='$SLUG'>$re</p></td></tr>";
        }
        public function _callback($a)
        {
            return $a;
        }
    }
}
