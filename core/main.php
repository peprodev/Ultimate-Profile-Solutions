<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/15 14:45:29
if (!class_exists("PeproDevUPS_Core")){
  class PeproDevUPS_Core
  {
    private static $_instance = null;
      public $td;
      public $ns;
      public $plugin_dir;
      public $plugin_url;
      public $assets_url;
      public $plugin_basename;
      public $plugin_file;
      public $version;
      public $db_slug;
      public $title;
      public $title_w;
      public $db_table = null;
      public $manage_links = array();
      public $meta_links = array();
    public function __construct()
    {
        global $wpdb;

        $this->ns              = __CLASS__;
        $this->td              = "peprodev-ups";
        self::$_instance       = $this;
        $this->plugin_dir      = plugin_dir_path(__FILE__);
        $this->plugin_url      = plugins_url("", __FILE__);
        $this->assets_url      = plugins_url("/assets/", __FILE__);
        $this->plugin_basename = plugin_basename(__FILE__);
        $this->plugin_file     = __FILE__;
        $this->version         = defined('PeproDevUPS') ? PeproDevUPS : "1.0.0";
        $this->db_slug         = $this->td;
        $this->db_table        = $wpdb->prefix . $this->db_slug;
        $this->title           = __("PeproDev Ultimate Profile Solutions", "peprodev-ups");
        $this->title_w         = sprintf(__("%2\$s ver. %1\$s", "peprodev-ups"), $this->version, $this->title);

        if (isset($_GET['page'])){
          if($this->db_slug == sanitize_text_field($_GET['page'])){
            if (!isset($_GET['section']) || empty($_GET['section'])){
              wp_safe_redirect( admin_url("admin.php?page=$this->db_slug&section=home"), 301); die();
            }
          }
        }

        add_action("init", array($this, 'plugin_init'));
    }
    public function plugins_row_links($links)
    {
        foreach ($this->get_manage_links() as $title => $href) {
            array_push($links, "<a href='$href' target='_blank'>$title</a>");
        }
        return $links;
    }
    public function plugin_row_meta($links, $file)
    {
        if ($this->plugin_basename === $file) {
            $icon_attr = array(
                'style' => array(
                    'font-size: inherit;',
                    'line-height: inherit;',
                    'display: inline;',
                    'vertical-align: text-top;',
                ),
            );
            foreach ($this->get_meta_links() as $id => $link) {
                $title = (!empty($link['icon'])) ? self::do_icon($link['icon'], $icon_attr) . ' ' . esc_html($link['title']) : esc_html($link['title']);
                $links[ $id ] = '<a href="' . esc_url($link['url']) . '" title="'.esc_attr($link['description']).'" target="'.(empty($link['target'])?"_blank":$link['target']).'">' . $title . '</a>';
            }
        }
        return $links;
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
            $attr   = self::merge_attr($attr, array(
                'style' => array( 'background-image: url("' . $icon . '") !important' ),
            ));
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
    public function get_meta_links()
    {
        if (! empty($this->meta_links)) {
            return $this->meta_links;
        }
        $this->meta_links = array(
                'docs'      => array(
                    'title'       => __('Documentation', "peprodev-ups"),
                    'description' => __('Documentation', "peprodev-ups"),
                    'icon'        => 'dashicons-book-alt',
                    'target'      => '_self',
                    'url'         => "https://pepro.dev/ups",
                ),
            );
        return $this->meta_links;
    }
    public function get_manage_links()
    {
        if (! empty($this->manage_links)) { return $this->manage_links; }
        $this->manage_links = array( "<b>".__("Dashboard", "peprodev-ups")."</b>" => admin_url( "admin.php?page=pepro&section=home"), );
        return $this->manage_links;
    }
    public function get_setting_options()
    {
      return array(
        array(
          "name" => __CLASS__."_modules",
          "data" => array(
              __CLASS__."___loginregister"                => "0",
          )
        ),
        array(
          "name" => __CLASS__."_general",
          "data" => array(
              __CLASS__."___theme-color"                  => "azure",
              __CLASS__."___theme-scheme"                 => "",
              __CLASS__."___theme-sidebar-image"          => "$this->assets_url/img/38577539.jpg",
              __CLASS__."___theme-sidebar-image-custom"   => "",
              __CLASS__."___dashboard-title"              => _x("PeproDev UPS","peprocore-appearance-setting","peprodev-ups"),
          )
        ),
      );
    }
    public function CreateDatabase()
    {
      global $wpdb;
      $charset_collate = $wpdb->get_charset_collate();
      $tbl = $this->db_table;
      $sql = "CREATE TABLE `$tbl` (
        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `date` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
        `term` VARCHAR(500) DEFAULT '' NOT NULL,
        `count` BIGINT(20) UNSIGNED DEFAULT '1' NOT NULL,
        PRIMARY KEY id (id)
        ) $charset_collate;";
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
    }
    public function DropDatabase()
    {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS {$this->db_table}");
    }
    public function plugin_init()
    {
      add_action("peprocore_before_dashboard_call", array($this,"load_dashboard_before_initiated"),10);
      add_action("peprocore_after_dashboard_call", array($this,"load_dashboard_after_initiated"),10);
      add_filter("plugin_action_links_{$this->plugin_basename}", array($this, 'plugins_row_links'));
      add_action("plugin_row_meta", array( $this, 'plugin_row_meta' ), 10, 2);
      add_action("admin_menu", array($this, 'admin_menu'));
      add_action("admin_init", array($this, 'admin_init'));
      add_action("admin_enqueue_scripts", array($this, 'admin_enqueue_scripts'));
      add_action("admin_print_footer_scripts", array($this, 'admin_print_footer_scripts'));
      add_action("wp_ajax_nopriv_{$this->td}", array($this, 'handel_ajax_req'));
      add_action("wp_ajax_{$this->td}", array($this, 'handel_ajax_req'));
      add_action( "us_theme_icon", function(){ wp_dequeue_style( "us-font-awesome" ); });
    }
    public function load_dashboard_before_initiated()
    {
      wp_enqueue_style("RobotoSlabMaterialIcons", "//fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons", array(), '1.0', 'all');
      wp_enqueue_style("font-awesome", "{$this->assets_url}/fa-pro/css/all.min.css", array(), '1.0', 'all');
      wp_enqueue_style("material-dashboard", "{$this->assets_url}css/material-dashboard.min.css", array(), '2.1.0', 'all');
      wp_enqueue_style("dashboard-back", "{$this->assets_url}css/dashboard-backend.css", array(), '1.0', 'all');
      wp_enqueue_style("bootstrap-select", "{$this->assets_url}js/plugins/bootstrap-select.min.css", array(), '1.0', 'all');
      is_rtl() AND wp_enqueue_style("dashboard-back-rtl", "{$this->assets_url}css/rtl.css", array(), '1.0', 'all');
      add_filter( "peprocore_dashboard_nav_menuitems", array($this, "peprocore_dashboard_nav_menuitems"));
      do_action( "peprocore_dashboard_before_initiated");
    }
    function peprocore_dashboard_nav_menuitems($menuitems)
    {
      return array_merge($menuitems, array(
        array(
          "title"    => __("Dashboard","peprodev-ups"),
          "titleW"   => __("PeproDev UPS Dashboard","peprodev-ups"),
          "icon"     => '<i class="material-icons">dashboard</i>',
          "link"     => "@home",
          "id"       => "built_in_home",
          "priority" => 2,
          "fn"       => array($this,"peprocore_dashboard_homepage"),
        ),
        array(
          "title"    => __("Setting","peprodev-ups"),
          "titleW"   => __("PeproDev UPS Settings","peprodev-ups"),
          "icon"     => '<i class="material-icons">settings</i>',
          "link"     => "@setting",
          "id"       => "built_in_setting",
          "priority" => 1000,
          "fn"       => array($this,"peprocore_dashboard_setting"),
        )
      ));
    }
    public function load_dashboard_after_initiated()
    {
      global $PeproDevUPS_Login;
      // Core JS Files
      wp_enqueue_script('jquery');
      wp_enqueue_script("popper",                     "{$PeproDevUPS_Login->assets_url}assets/popper.min.js", array( 'jquery' ), "1.6.0", true); //'1.16.0'
      wp_enqueue_script("bootstrap-material-design",  "{$this->assets_url}js/core/bootstrap-material-design.min.js", array( 'jquery' ), "1.6.0", true); //'3.0.2'
      wp_enqueue_script("default-passive-events",     "{$this->assets_url}js/default-passive-events.js", array( 'jquery' ), "1.6.0", true); //'1.0.10'
      // Google Maps Plugin
      // wp_enqueue_script("google-map", "https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE", array( 'jquery' ), '1.0.0', true);
      // Chartist JS
      wp_enqueue_script("chartist",         "{$this->assets_url}js/plugins/chartist.min.js", array( 'jquery' ), '0.11.0', true);
      // Notifications Plugin
      wp_enqueue_script("bootstrap-notify", "{$this->assets_url}js/plugins/bootstrap-notify.js", array( 'jquery' ), '3.1.5', true);
      wp_enqueue_script("bootstrap-select", "{$this->assets_url}js/plugins/bootstrap-select.min.js", array( 'jquery' ), '3.1.5', true);
      // Control Center for Material Dashboard: parallax effects, scripts for the example pages etc
      // Material Dashboard DEMO methods, don't include it in your project!

      wp_enqueue_script("material-dashboard", "{$this->assets_url}js/material-dashboard.js", array( 'jquery' ), "1.6.0", true); //'2.1.0'

      wp_enqueue_script("dashboard-back", "{$this->assets_url}js/dashboard-back.js", array('jquery'), '1.0.2', true);
      wp_localize_script( "dashboard-back", "pepc",
        apply_filters(
          "peprocore_dashboard_localize",
          array(
            "ajax" => admin_url( 'admin-ajax.php' ),
            "folder" => $this->assets_url,
            "img" => "{$this->assets_url}img/",
            "_palett"  => array( '#444444', '#dd3333', '#dd9933', '#eeee22', '#81d742', '#1e73be', '#8224e3', '#ff2255', '#559999', '#99CCFF', '#00c1e8', '#F9DE0E', '#111111', '#EEEEDD' ),
            "loading" => __("Please wait ...","peprodev-ups"),
          )
        )
      );
      wp_enqueue_media();
      do_action( "peprocore_dashboard_after_initiated");

    }
    public function peprocore_modern_mother_dashboard_wrapper($hook)
    {
      ob_start();
      add_filter('admin_footer_text', function () {
          return sprintf(_x("Thanks for using %s products", "footer-copyright", "peprodev-ups"), "<b><a href='https://pepro.dev' target='_blank' >".__("Pepro Dev", "peprodev-ups")."</a></b>");
      }, 11);
      add_filter('update_footer', function () {
          return sprintf(_x("Version %s", "footer-copyright", "peprodev-ups"), $this->version);
      }, 11);
      do_action("peprocore_before_dashboard_call");
      include "{$this->plugin_dir}/apps/home.php";
      do_action("peprocore_after_dashboard_call");
      $tcona = ob_get_contents();
      ob_end_clean();
      print $tcona;
    }
    public function dashboardloadrTwo($hook)
    {
        ob_start();
        add_filter('admin_footer_text', function () {
            return sprintf(_x("Thanks for using %s products", "footer-copyright", "peprodev-ups"), "<b><a href='https://pepro.dev' target='_blank' >".__("Pepro Dev", "peprodev-ups")."</a></b>");
        }, 11);
        echo "<h1>".esc_html( $this->title_w )."</h1>";
        echo '<div class="wrap"><hr class=\'hr2\'>';
        echo '<form method="post" action="options.php">';
        settings_fields($this->td . '_general');
        $g434 = __("Shortcode and Hooks for Developers","peprodev-ups");
        $hll = "<p><span class=\"dashicons dashicons-format-quote\"></span>  <strong>$g434</strong></p><pre class='caqpde' dir='ltr' align='left' lang='en-US'><b>WordPress Shortcode</b><hr>   [pepro placeholder='Search ...']<br><br><b>WordPress Hooks</b><hr>   <i>pepro-input-classes</i><br>   <i>pepro-input-raw</i><br><br>   <i>e.g. --></i><br><br>   add_filter(\"pepro-input-classes\", function (\$HTML_classes) {<br>       return \$HTML_classes;}, 10, 1);<br>   });<br>   add_filter(\"pepro-input-raw\", function (\$HTML_input_el, \$HTML_classes, \$uniqid) {<br>      return \$HTML_input_el;}, 10, 3);<br>   });        <br><br><b>jQuery Hooks</b><hr>   <i>pepro_init</i><br>   <i>pepro_input_keyup</i><br>   <i>pepro_before_ajax_request</i><br>   <i>pepro_ajax_request_success</i><br>   <i>pepro_ajax_request_failed</i><br>   <i>pepro_input_focused</i><br>   <i>pepro_input_reset_fired</i><br><br>   <i>e.g. --></i><br><br>   $(document).on(\"pepro_ajax_request_failed\",function(e,param) {<br>      console.error(param);<br>   });        </pre>";
        echo '<table class="form-table"><tbody>';
        echo "<h4><span class=\"dashicons dashicons-format-quote\"></span>  ".__("Set maximum number of suggestion for each item. Use 0 to trun off item display.","peprodev-ups")."</h4>";
        $this->print_setting_iput($this->td . '_n_trend_days', __("Trend items age (day)", "peprodev-ups"), 'dir="ltr" lang="en-US" min="1" max="10" step="1" required', "number");
        echo '</tbody></table>';
        echo "<hr class='hr2'><div class='peprosubmtCC'>";
        submit_button(__("Save setting", "peprodev-ups"),"primary peprosubmt","submit",false);
        echo '<a href="'.admin_url("admin.php?page=pepro").'&empty=true" onclick="return confirm(\''.__("Are you sure you want to clear all searches history (trend items) from database?","peprodev-ups").'\');" class="button button-primary peprosubmt">'.__("Empty Database", "peprodev-ups").'</a>';
        echo "</div>";
        echo '</form><hr class=\'hr2\'>'. wp_kses_post( $hll ) .'</div>';
        $tcona = ob_get_contents();
        ob_end_clean();
        print $tcona;
    }
    public function handel_ajax_req()
    {
      check_ajax_referer( 'peprocorenounce', 'integrity' );
      if ( wp_doing_ajax() && $_POST['action'] == $this->td ) {
        do_action( "peprocore_handle_ajaxrequests", $_POST);

        if ($_POST["wparam"] === "peprocore"){
          switch ($_POST["lparam"]) {
            case 'savesettings':

              if (isset($_POST["dparam"]["theme-scheme"]) && !empty($_POST["dparam"]["theme-scheme"])){
                update_option( __CLASS__."___theme-scheme", sanitize_text_field( $_POST["dparam"]["theme-scheme"] ));
              }

              if (isset($_POST["dparam"]["theme-color"]) && !empty($_POST["dparam"]["theme-color"])){
                update_option( __CLASS__."___theme-color", sanitize_text_field( $_POST["dparam"]["theme-color"] ));
              }

              if (isset($_POST["dparam"]["sidebar-image"]) && !empty($_POST["dparam"]["sidebar-image"])){
                update_option( __CLASS__."___theme-sidebar-image", sanitize_text_field( $_POST["dparam"]["sidebar-image"] ));
              }

              if (isset($_POST["dparam"]["sidebar-image-custom"]) && !empty($_POST["dparam"]["sidebar-image-custom"])){
                update_option( __CLASS__."___theme-sidebar-image-custom", sanitize_text_field( $_POST["dparam"]["sidebar-image-custom"] ));
              }

              if (isset($_POST["dparam"]["sidebar-image-custom-id"]) && !empty($_POST["dparam"]["sidebar-image-custom-id"])){
                update_option( __CLASS__."___theme-sidebar-image-custom-id", sanitize_text_field( $_POST["dparam"]["sidebar-image-custom-id"] ));
              }

              if (isset($_POST["dparam"]["dashboard-title"]) && !empty($_POST["dparam"]["dashboard-title"])){
                update_option( __CLASS__."___dashboard-title", sanitize_text_field( $_POST["dparam"]["dashboard-title"] ));
              }

              wp_send_json_success(
                array(
                  "msg"=>__("Settings Successfully Saved.","peprodev-ups"),
                )
              );
              break;
            default:
              wp_send_json_error("{$this->title} -> settings :: Incorrect Data Supplied.");
              break;
          }

        }
      }
    }
    public function admin_menu()
    {
      add_menu_page(
        $this->title_w,
        __("PeproDev UPS","peprodev-ups"),
        "manage_options",
        $this->db_slug,
        array($this,'peprocore_modern_mother_dashboard_wrapper'),
        "{$this->assets_url}img/peprodev.png",
        81
      );
    }
    public function admin_init($hook)
    {
      $pepro_mega_menu_options = $this->get_setting_options();
      foreach ($pepro_mega_menu_options as $sections) {
         foreach ($sections["data"] as $id=>$def) {
           add_option($id,$def);
           register_setting($sections["name"],$id);
         }
      }
    }
    public function admin_enqueue_scripts($hook)
    {
        if ("$hook" === "toplevel_page_{$this->db_slug}"){
          wp_deregister_style("dashboard");
          wp_dequeue_style("dashboard");
          wp_deregister_style("admin-bar");
          wp_dequeue_style("admin-bar");
          wp_deregister_style("woocommerce_admin_menu_styles");
          wp_dequeue_style("woocommerce_admin_menu_styles");
          add_filter('admin_body_class',function($c){return "$c " . $this->read_opt(__CLASS__."___theme-scheme");},1000);
        }
        wp_enqueue_style("dashboard-backs", "{$this->assets_url}css/admin-back.css", array(), '1.0', 'all');
    }
    public function admin_print_footer_scripts()
    {
      echo '<script>document.querySelector("#toplevel_page_peprodev-ups>a.toplevel_page_peprodev-ups").setAttribute("href","'.admin_url("admin.php?page=peprodev-ups&section=home").'");</script>';
    }
    public function read_opt($mc, $def="")
    {
        return get_option($mc) <> "" ? get_option($mc) : $def;
    }
    public function print_setting_iput($SLUG="", $CAPTION="", $extraHtml="", $type="text",$extraClass="")
    {
      $ON = sprintf(_x("Enter %s", "setting-page", "peprodev-ups"), wp_kses_post($CAPTION));
      echo "<tr>
  			<th scope='row'><label for='".esc_attr( $SLUG )."'>".wp_kses_post($CAPTION)."</label></th>
  			<td><input name='".esc_attr( $SLUG )."' ".esc_attr( $extraHtml )." type='".esc_attr( $type )."' id='".esc_attr( $SLUG )."'
        placeholder='".esc_attr( $CAPTION )."' title='".esc_attr( $ON )."' value='" . esc_attr($this->read_opt($SLUG)) . "' class='regular-text ".esc_attr( $extraClass )."' /></td>
  		</tr>";
    }
    public function print_setting_select($SLUG, $CAPTION, $dataArray=array())
    {
        $ON = sprintf(_x("Choose %s", "setting-page", "peprodev-ups"), wp_kses_post($CAPTION));
        $OPTS = "";
        foreach ($dataArray as $key => $value) {
            if ($key == "EMPTY") {
                $key = "";
            }
            $OPTS .= "<option value='".esc_attr( $key )."' ". selected($this->read_opt($SLUG), $key, false) .">".esc_html( $value )."</option>";
        }
        echo "<tr>
    			<th scope='row'>
    				<label for='".esc_attr( $SLUG )."'>".wp_kses_post($CAPTION)."</label>
    			</th>
    			<td><select name='".esc_attr( $SLUG )."' id='".esc_attr( $SLUG )."' title='".esc_attr( $ON )."' class='regular-text'>
          ".$OPTS."
          </select>
          </td>
    		</tr>";
    }
    public function print_setting_editor($SLUG, $CAPTION, $re="")
    {
        echo "<tr><th><label for='".esc_attr( $SLUG )."'>".wp_kses_post($CAPTION)."</label></th><td>";
        wp_editor($this->read_opt($SLUG, ''), strtolower(str_replace(array('-', '_', ' ', '*'), '', $SLUG)), array(
        'textarea_name' => $SLUG
          ));
        echo "<p class='".esc_attr( $SLUG )."'>".wp_kses_post( $re )."</p></td></tr>";
    }
    public function _callback($a)
    {
        return $a;
    }
    public function getIP()
    {
        // Get server IP address
        $server_ip = (isset($_SERVER['SERVER_ADDR'])) ? $_SERVER['SERVER_ADDR'] : '';

        // If website is hosted behind CloudFlare protection.
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        }

        if (isset($_SERVER['X-Real-IP']) && filter_var($_SERVER['X-Real-IP'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $_SERVER['X-Real-IP'];
        }

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = trim(current(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])));

            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) && $ip != $server_ip) {
                return $ip;
            }
        }

        if (isset($_SERVER['DEV_MODE'])) {
            return '175.138.84.5';
        }

        return $_SERVER['REMOTE_ADDR'];
    }
    public function DummyData()
    {
      ?>
      <div class="row">
        <div class="col-lg-12 col-md-12">
          <div class="card">
            <div class="card-header card-header-primary">
              <h4 class="card-title"><?php eschtml_e("Dude !!", "peprodev-ups");?></h4>
              <p class="card-category"><?php eschtml_e("Guess what! There is nothing to show here.", "peprodev-ups");?></p>
            </div>
            <div class="card-body">
              <?php
                _e("Contact support@pepro.dev for more information", "peprodev-ups");
               ?>
            </div>
          </div>
        </div>
      </div>
      <?php
    }
    public function peprocore_dashboard_homepage()
    {
      global $PeproDevUPS_Profile;
      ?>
      <div class="content">
        <div class="container-fluid">
          <div class="row">
          <div class="col-lg-6 col-md-12">
            <div class="card card-nav-tabs text-center">
              <div class="card-header card-header-primary"><?php esc_html_e("PeproDev Ultimate Profile Solutions", "peprodev-ups");?></div>
              <div class="card-body">
                <p class="card-text">
                  <h2><?php esc_attr( sprintf(__("Welcome to v.%s", "peprodev-ups"),"<strong>$this->version</strong>") );?></h2>
                </p>
                <a href="<?php esc_attr_e(admin_url());?>" class="btn btn-primary"><?php esc_html_e("WordPress Admin Dashboard", "peprodev-ups");?></a>
                <a href="<?php esc_attr_e(admin_url("/plugins.php"));?>" class="btn btn-primary"><?php esc_html_e("WordPress Plugins", "peprodev-ups");?></a>
                <a href="<?php esc_attr_e(home_url());?>" class="btn btn-primary"><?php esc_html_e("Website Home Page", "peprodev-ups");?></a>
                <?php
                if (class_exists("PeproDevUPS_Profile")){
                  echo "<a href='".esc_attr( $PeproDevUPS_Profile->get_profile_page(["i"=>current_time("timestamp")]) )."' class='btn btn-primary'>".__("Profile Page", "peprodev-ups")."</a>";
                }
                ?>
              </div>
              <style>.navbar-wrapper{display:none !important;}.main-panel>.content{margin-top: 10px !important;}</style>
            </div>
          </div>
          </div>
        </div>
      </div>
        <?php
      }
    public function peprocore_dashboard_setting()
    {
      ?>
        <div class="row">
          <?php
          do_action( "peprocore_setting_before" );
          ?>
          <div class="col-lg-6 col-md-12">
            <?php
            do_action( "peprocore_setting_col_2_before" );
            ?>
            <div class="card">
              <div class="card-header card-header-primary">
                <h4 class="card-title flex no-margin"><i class='material-icons'>brush</i><?php echo esc_html_x("Appearance","peprocore-appearance-setting","peprodev-ups");?></h4>
                <!-- <p class="card-category">extra info</p> -->
              </div>
                <div class="card-body table-responsive">
                  <table class="table pepcappearance">
                    <thead class="">
                    </thead>
                    <tbody>
                      <tr>
                        <?php
                        do_action( "peprocore_after_first_setting_row" );
                         ?>
                        <td><?php echo esc_html_x("Theme Scheme","peprocore-appearance-setting","peprodev-ups");?></td>
                        <td>
                          <a class='btncheckbox'
                            data-text-on='<?php echo esc_html_x("Dark Mode","peprocore-appearance-setting","peprodev-ups");?>'
                            data-text-off='<?php echo esc_html_x("Light Mode","peprocore-appearance-setting","peprodev-ups");?>'
                            data-on='brightness_4'
                            data-off='brightness_low'
                            data-checked='<?php echo esc_attr( $this->read_opt(__CLASS__."___theme-scheme","dark-edition") === "dark-edition" ? "true" : "false" );?>' id="pepc-settings-theme-scheme"></a>
                        </td>
                      </tr>
                      <tr>
                        <td><?php echo esc_html_x("Theme Color","peprocore-appearance-setting","peprodev-ups");?></td>
                        <td>
                          <select id="pepc-settings-theme-color">
                              <option <?php selected( $this->read_opt(__CLASS__."___theme-color"), "purple"); ?> value="purple"><?php echo esc_html_x("Purple","peprocore-appearance-setting","peprodev-ups");?></option>
                              <option <?php selected( $this->read_opt(__CLASS__."___theme-color"), "azure"); ?> value="azure"><?php echo esc_html_x("Azure","peprocore-appearance-setting","peprodev-ups");?></option>
                              <option <?php selected( $this->read_opt(__CLASS__."___theme-color"), "green"); ?> value="green"><?php echo esc_html_x("Green","peprocore-appearance-setting","peprodev-ups");?></option>
                              <option <?php selected( $this->read_opt(__CLASS__."___theme-color"), "orange"); ?> value="orange"><?php echo esc_html_x("Orange","peprocore-appearance-setting","peprodev-ups");?></option>
                              <option <?php selected( $this->read_opt(__CLASS__."___theme-color"), "danger"); ?> value="danger"><?php echo esc_html_x("Red","peprocore-appearance-setting","peprodev-ups");?></option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td><?php echo esc_html_x("Sidebar Image","peprocore-appearance-setting","peprodev-ups");?></td>
                        <td>
                          <div class="flex flex-sb">
                            <?php
                            $image =
                            get_option(__CLASS__."___theme-sidebar-image","$this->assets_url/img/one.jpg") === "custom" ?
                            get_option(__CLASS__."___theme-sidebar-image-custom","0") :
                            get_option(__CLASS__."___theme-sidebar-image","$this->assets_url/img/one.jpg");
                            $imagew = $this->read_opt(__CLASS__."___theme-sidebar-image-custom-id","0");
                            ?><img style="border-radius: 5px;" src="<?php echo esc_attr( $image );?>" id="sidebar--img" width="86px"/>
                            <div class="" style="width: calc(100% - 100px);">
                            <select id="pepc-settings-theme-sidebar-image">
                                <option <?php selected( $this->read_opt(__CLASS__."___theme-sidebar-image"), "$this->assets_url/img/one.jpg"); ?> value="<?php esc_attr_e("$this->assets_url/img/one.jpg");?>"><?php esc_html_e("Sidebar 1","peprodev-ups");?></option>
                                <option <?php selected( $this->read_opt(__CLASS__."___theme-sidebar-image"), "$this->assets_url/img/two.jpg"); ?> value="<?php esc_attr_e("$this->assets_url/img/two.jpg");?>"><?php esc_html_e("Sidebar 2","peprodev-ups");?></option>
                                <option <?php selected( $this->read_opt(__CLASS__."___theme-sidebar-image"), "$this->assets_url/img/three.jpg"); ?> value="<?php esc_attr_e("$this->assets_url/img/three.jpg");?>"><?php esc_html_e("Sidebar 3","peprodev-ups");?></option>
                                <option <?php selected( $this->read_opt(__CLASS__."___theme-sidebar-image"), "$this->assets_url/img/four.jpg"); ?> value="<?php esc_attr_e("$this->assets_url/img/four.jpg");?>"><?php esc_html_e("Sidebar 4","peprodev-ups");?></option>
                                <option <?php selected( $this->read_opt(__CLASS__."___theme-sidebar-image"), "$this->assets_url/img/38577535.jpg"); ?> value="<?php esc_attr_e("$this->assets_url/img/38577535.jpg");?>"><?php esc_html_e("Sidebar 5","peprodev-ups");?></option>
                                <option <?php selected( $this->read_opt(__CLASS__."___theme-sidebar-image"), "$this->assets_url/img/38577536.jpg"); ?> value="<?php esc_attr_e("$this->assets_url/img/38577536.jpg");?>"><?php esc_html_e("Sidebar 6","peprodev-ups");?></option>
                                <option <?php selected( $this->read_opt(__CLASS__."___theme-sidebar-image"), "$this->assets_url/img/38577537.jpg"); ?> value="<?php esc_attr_e("$this->assets_url/img/38577537.jpg");?>"><?php esc_html_e("Sidebar 7","peprodev-ups");?></option>
                                <option <?php selected( $this->read_opt(__CLASS__."___theme-sidebar-image"), "$this->assets_url/img/38577538.jpg"); ?> value="<?php esc_attr_e("$this->assets_url/img/38577538.jpg");?>"><?php esc_html_e("Sidebar 8","peprodev-ups");?></option>
                                <option <?php selected( $this->read_opt(__CLASS__."___theme-sidebar-image"), "$this->assets_url/img/38577539.jpg"); ?> value="<?php esc_attr_e("$this->assets_url/img/38577539.jpg");?>"><?php esc_html_e("Sidebar 9","peprodev-ups");?></option>
                                <option <?php selected( $this->read_opt(__CLASS__."___theme-sidebar-image"), "$this->assets_url/img/38577534.jpg"); ?> value="<?php esc_attr_e("$this->assets_url/img/38577534.jpg");?>"><?php esc_html_e("Sidebar 10","peprodev-ups");?></option>
                                <option <?php selected( $this->read_opt(__CLASS__."___theme-sidebar-image"), "$this->assets_url/img/38577533.jpg"); ?> value="<?php esc_attr_e("$this->assets_url/img/38577533.jpg");?>"><?php esc_html_e("Sidebar 11","peprodev-ups");?></option>
                                <option <?php selected( $this->read_opt(__CLASS__."___theme-sidebar-image"), "$this->assets_url/img/3857753.jpg"); ?> value="<?php esc_attr_e("$this->assets_url/img/3857753.jpg");?>"><?php esc_html_e("Sidebar 12","peprodev-ups");?></option>
                                <option <?php selected( $this->read_opt(__CLASS__."___theme-sidebar-image"), "$this->assets_url/img/38577532.jpg"); ?> value="<?php esc_attr_e("$this->assets_url/img/38577532.jpg");?>"><?php esc_html_e("Sidebar 13","peprodev-ups");?></option>
                                <option <?php selected( $this->read_opt(__CLASS__."___theme-sidebar-image"), "custom"); ?> value="custom"><?php esc_html_e("Sidebar Custom","peprodev-ups");?></option>
                            </select>
                            <input type="hidden" id="pepc-settings-theme-sidebar-image-custom" value="<?php echo esc_attr( get_option(__CLASS__."___theme-sidebar-image-custom","0") ); ?>" data-id="<?php echo esc_attr( $imagew ); ?>" class="regular-text" />
                            <button type="button" style="padding: 12px; display: block; width: 100%;" id="pepc-settings-load-sidebar-image" class="btn btn-primary mediapicker icn-btn"
                            data-ref="#pepc-settings-theme-sidebar-image-custom"
                            <?php echo esc_attr( "custom" === get_option(__CLASS__."___theme-sidebar-image","$this->assets_url/img/one.jpg") ? "disabled=false" : "disabled=true" );?>
                            data-ref2=".sidebar[data-image]"
                            data-ref3=".sidebar .sidebar-background"
                            data-ref4="#sidebar--img"
                            data-title="<?php esc_attr_e("Select Custom Image","peprocore-appearance-setting","peprodev-ups")?>"
                            ><i class='material-icons'>cloud_upload</i> <?php esc_html_e("Select or Upload Custom Image","peprodev-ups")?></button>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td><?php echo esc_html_x("Dashboard Title","peprocore-appearance-setting","peprodev-ups")?></td>
                        <td>
                          <input type="text" id="pepc-settings-dashboard-title"
                          data-textupdatelemnt=""
                          class="form-control primary"
                          data-orig="<?php echo esc_attr( $this->read_opt(__CLASS__."___dashboard-title", _x("PeproDev UPS","peprocore-appearance-setting","peprodev-ups")) ) ;?>"
                          value="<?php echo esc_attr($this->read_opt(__CLASS__."___dashboard-title",_x("PeproDev UPS","peprocore-appearance-setting","peprodev-ups")));?>"
                          placeholder="<?php echo esc_attr("Dashboard Title","peprocore-appearance-setting","peprodev-ups")?>"/>
                        </td>
                      </tr>
                      <?php
                      do_action( "peprocore_after_final_setting_row" );
                       ?>
                    </tbody>
                  </table>

                  <?php
                  do_action( "peprocore_before_save_setting" );
                   ?>

                  <button type="button"
                  id="pepc-settings-save"
                  class="btn btn-primary icn-btn btn-wide"
                  integrity="<?php echo esc_attr( wp_create_nonce('peprocorenounce') );?>"
                  wparam="peprocore"
                  lparam="savesettings"
                  style="text-align: start; max-width: 450px;"
                  dparam=""
                  fn=""><i class='material-icons'>save</i> <?php echo esc_html_x("Save Settings","peprocore-appearance-setting","peprodev-ups");?></button>

                  <?php
                  do_action( "peprocore_after_save_setting" );
                   ?>
                </div>
            </div>
            <?php
            do_action( "peprocore_setting_col_2_after" );
            ?>
          </div>
          <?php
          do_action( "peprocore_setting_after" );
          ?>
        </div>
      <?php
    }
    public function RetriveClassName()
    {
      return __CLASS__;
    }
    public function advanced_2dArray_compare()
    {
      // obtained from :: https://stackoverflow.com/a/7443948
      $criteriaNames = func_get_args();
      $comparer = function($first, $second) use ($criteriaNames) {
          // Do we have anything to compare?
          while(!empty($criteriaNames)) {
              // What will we compare now?
              $criterion = array_shift($criteriaNames);
              // Used to reverse the sort order by multiplying
              // 1 = ascending, -1 = descending
              $sortOrder = 1;
              if (is_array($criterion)) {
                  $sortOrder = $criterion[1] == SORT_DESC ? -1 : 1;
                  $criterion = $criterion[0];
              }
              // Do the actual comparison
              if ($first[$criterion] < $second[$criterion]) {
                  return -1 * $sortOrder;
              }
              else if ($first[$criterion] > $second[$criterion]) {
                  return 1 * $sortOrder;
              }
          }
          // Nothing more to compare with, so $first == $second
          return 0;
      };

      return $comparer;
    }
    public function RetriveModules()
    {
      return apply_filters( "peprocore_modules_list",array());
    }
  }
}
