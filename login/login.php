<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/11/05 16:33:49
include_once plugin_dir_path(__FILE__) . "/include/class-login-permalink.php";

if (!class_exists("PeproDevUPS_Login")){
  class PeproDevUPS_Login
  {
    public $parent;
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
    public $register_fileds;
    public $login_fields;
    public $authorURI;
    public $copyright;
    public $license;
    public $licenseURI;
    public $pluginURI;
    public $lang;
    public $title;
    public $show_password_field;
    public $auto_login_after_reg;
    public $verify_email;
    public $verify_mobile;
    public $use_mobile_as_username;
    public $use_email_as_username;
    public $hide_email_field;
    public $hide_username_field;
    public $login_mobile_otp;
    public $sms_api_url;
    public $sms_secret_key;
    public $sms_api_key;
    public $sms_ultrafastsend;
    public $description;
    public $assets_url;
    public $assets_dir;
    protected $file;
    public function __construct()
    {
      if (class_exists("PeproCoreLoginSlugChangerClass")){new PeproCoreLoginSlugChangerClass;}
      $this->priority                       = 3;
      $this->assets_url                     = plugins_url("/", __FILE__);
      $this->assets_dir                     = plugin_dir_path(__FILE__);
      $this->instance                       = $this;
      $this->file                           = plugin_basename(__FILE__);
      $this->hwnd                           = __CLASS__;
      $this->setting_slug                   = "loginregister";
      $this->id                             = "loginregister";
      $this->td                             = "peprodev-ups";
      $this->title                          = __("PeproDev Ultimate Profile Solutions — Login","peprodev-ups");
      $this->menu_label                     = __("Login/Register","peprodev-ups");
      $this->page_label                     = __("Login Setting","peprodev-ups");
      $this->developer                      = __("Pepro Dev. Group","peprodev-ups");
      $this->author                         = __("Pepro Dev. Group","peprodev-ups");
      $this->license                        = __("Pepro Dev License","peprodev-ups");
      $this->icon_html                      = "<i class=\"material-icons\">fingerprint</i>";
      $this->current_version                = "3.5.0";
      $this->date_last_edit                 = "1400/06/03";
      $this->wp_tested                      = "5.8";
      $this->wp_minimum                     = "5.0";
      $this->wc_tested                      = "5.5.2";
      $this->wc_minimum                     = "5.0";
      $this->php_minimum                    = "5.6";
      $this->php_recomonded                 = "7.3";
      $this->pepc_tested                    = "1.7.0";
      $this->pepc_minimum                   = "1.7.0";
      $this->activation_status              = "PeproDevUPS_Core___{$this->setting_slug}";
      $this->html_wrapper                   = array($this,"htmlwrapper");
      $this->ajax_hndlr                     = array($this,"ajaxhandler");
      $this->developerURI                   = "https://pepro.dev";
      $this->authorURI                      = "https://pepro.dev";
      $this->licenseURI                     = "https://pepro.dev/license";
      $this->pluginURI                      = "https://pepro.dev/ups";
      $this->lang                           = dirname(plugin_basename(__FILE__))."/languages/";
      $this->copyright                      = sprintf(__("Copyright (c) %s Pepro Dev. Group, All rights reserved","peprodev-ups"), date("Y"));
      $this->reglogin_type                  = get_option("{$this->activation_status}-reglogin_type");
      $this->auto_login_after_reg           = "yes"    == get_option("{$this->activation_status}-auto_login_after_reg");
      $this->login_mobile_otp               = "mobile" == $this->reglogin_type;
      $this->login_email_otp                = "mailotp" == $this->reglogin_type;
      $this->use_mobile_as_username         = "mobile" == $this->reglogin_type;
      $this->use_email_as_username          = "email"  == $this->reglogin_type;
      $this->show_password_field            = "yes" == get_option("{$this->activation_status}-_regdef_passwords");
      $this->is_password_field_req          = "yes" == get_option("{$this->activation_status}-_regdef_passwords-req");
      $this->reg_add_firstname              = "yes" == get_option("{$this->activation_status}-_regdef_firstname");
      $this->is_add_firstname_req           = "yes" == get_option("{$this->activation_status}-_regdef_firstname-req");
      $this->reg_add_lastname               = "yes" == get_option("{$this->activation_status}-_regdef_lastname");
      $this->is_add_lastname_req            = "yes" == get_option("{$this->activation_status}-_regdef_lastname-req");
      $this->reg_add_displayname            = "yes" == get_option("{$this->activation_status}-_regdef_displayname");
      $this->is_add_displayname_req         = "yes" == get_option("{$this->activation_status}-_regdef_displayname-req");
      $this->reg_add_mobile                 = "yes" == get_option("{$this->activation_status}-_regdef_mobile");
      $this->is_add_mobile_req              = "yes" == get_option("{$this->activation_status}-_regdef_mobile-req");
      $this->hide_email_field               = "yes" !== get_option("{$this->activation_status}-_regdef_email");
      $this->show_email_field               = "yes" == get_option("{$this->activation_status}-_regdef_email");
      $this->is_email_field_req             = "yes" == get_option("{$this->activation_status}-_regdef_email-req");
      $this->hide_username_field            = "yes" !== get_option("{$this->activation_status}-_regdef_username");
      $this->is_username_field_req          = "yes" == get_option("{$this->activation_status}-_regdef_username-req");
      $this->sms_api_url                    = get_option("{$this->activation_status}-sms_api_url");
      $this->sms_secret_key                 = get_option("{$this->activation_status}-sms_secret_key");
      $this->sms_api_key                    = get_option("{$this->activation_status}-sms_api_key");
      $this->sms_ultrafastsend              = get_option("{$this->activation_status}-sms_ultrafastsend_id");
      $this->sms_expiration                 = get_option("{$this->activation_status}-sms_expiration", "90");
      $this->email_expiration               = get_option("{$this->activation_status}-email_expiration", "120");
      $this->verification_digits            = get_option("{$this->activation_status}-verification_digits", "5");
      $this->verification_email_digits      = get_option("{$this->activation_status}-verification_email_digits", "8");
      $this->verification_email_sender      = get_option("{$this->activation_status}-verification_email_sender");
      $this->verification_email_sender_name = get_option("{$this->activation_status}-verification_email_sender_name", get_bloginfo('name','display'));
      $this->verification_email_template    = html_entity_decode(stripslashes(get_option("{$this->activation_status}-verification_email_template")));
      $this->def_mail_body                  = ['<!DOCTYPE html>', '<html>', '  <head>', '    <meta charset="utf-8">', '  </head>', '  <body>',
        '    <div style="display:block; width:450px; border-radius:0.5rem; margin: 1rem auto; text-align: center; color: #2b2b2b; padding: 1rem; box-shadow: 0 2px 5px 1px #0003; border: 1px solid #ccc;">',
        '      <h2>Verify your account</h2>',
        '      <h3>Use code below to verify your account:</h3>',
        '      <h1>', '        <strong>[OTP]</strong>', '      </h1>',
        '      <br>',
        '    </div>',
        '    <p style="text-align: center;">', '       <small style="color: #717171;">Copyright &copy; '.date("Y").', all rights reserved.</small>', '    </p>', '  </body>', '</html>'];
      $this->def_mail_body                  = implode(PHP_EOL, $this->def_mail_body);
      $this->default_sender                 = "wordpress@" . parse_url(get_bloginfo('url'), PHP_URL_HOST);
      $this->from_name                      = !empty($this->verification_email_sender_name) ? trim($this->verification_email_sender_name) : get_bloginfo('name','display');
      $this->from_address                   = !empty($this->verification_email_sender) ? trim($this->verification_email_sender) : $this->default_sender;
      add_action("init",                                                 array( $this, "admin_init" ));
      add_action("wp_ajax_pepro_reglogin",                               array( $this, "handel_ajax_req"));
      add_action("wp_ajax_nopriv_pepro_reglogin",                        array( $this, "handel_ajax_req"));
      add_action("register_form",                                        array( $this, "register_form" ));
      add_action("user_new_form",                                        array( $this, "register_form_admin" ));
      add_action("user_register",                                        array( $this, "user_register" ));
      add_action("edit_user_created_user",                               array( $this, "user_register" ));
      add_action("show_user_profile",                                    array( $this, "show_profile_custom_fields" ), 10, 3);
      add_action("edit_user_profile",                                    array( $this, "show_profile_custom_fields" ), 10, 3);
      add_action("personal_options_update",                              array( $this, "update_profile_custom_fields" ));
      add_action("edit_user_profile_update",                             array( $this, "update_profile_custom_fields" ));
      add_action("registration_errors",                                  array( $this, "registration_errors" ), 10, 3);
      add_action("user_profile_update_errors",                           array( $this, "registration_errors_admin" ), 10, 3);
      add_action("manage_users_columns",                                 array( $this, "manage_users_columns" ));
      add_action("manage_users_custom_column",                           array( $this, "manage_users_custom_column" ), 100, 3);
      add_action("login_form_logout",                                    array( $this, "login_form_logout"));
      add_action("admin_enqueue_scripts",                                array( $this, "admin_enqueue_scripts" ));
      add_action("login_form_register",                                  array( $this, "login_form_register"));
      add_filter("login_redirect",                                       array( $this, "redirect_after_login_register" ), 10, 3);
      add_filter("registration_redirect",                                array( $this, "redirect_after_login_register" ), 10, 3);
      add_filter("woocommerce_login_redirect",                           array( $this, "redirect_after_login_register" ), 10, 3);
      add_filter("woocommerce_registration_redirect",                    array( $this, "redirect_after_login_register" ), 10, 3);
      add_filter("peprofile_shortcodes",                                 array( $this, "add_peprofile_shortcodes" ), 11000);
      add_filter("teeny_mce_plugins",                                    array( $this, "teeny_mce_plugins" ), 10, 2 );
      add_shortcode("pepro-login-form",                                  array( $this, "shortcode__pepro_login_form"));
      add_shortcode("pepro-login-popup",                                 array( $this, "shortcode__pepro_login_popup"));
      add_shortcode("logout-url",                                        array( $this, "shortcode__logout_url"));
      add_shortcode("verified-mobile",                                   array( $this, "shortcode__user_verified_mobile"));
      add_shortcode("verified-email",                                    array( $this, "shortcode__user_verified_email"));
      add_shortcode("loggedin",                                          array( $this, "shortcode__check_loggedin") );
      add_shortcode("loggedout",                                         array( $this, "shortcode__check_loggedout") );
      add_shortcode("pepro-smart-btn",                                   array( $this, "shortcode__smart_btn") );
      add_filter("pepro_reglogin_get_register_fields",                   array( $this, "pepro_reglogin_get_register_fields" ), 1000);
      add_action("pepro_reglogin_show_hide_defaul_registeration_fields", array( $this, "form_defaul_registeration_fields" ), 1000);
      add_action("auth_cookie_expiration",                               array( $this,"auth_cookie_expiration" ), 10, 3 );
      $this->register_fileds       = $this->get_register_fields();
      $this->form_register_fields  = $this->get_form_register_fields();
      $this->form_resetpass_fields = $this->get_form_resetpass_fields();
      $this->login_fields          = $this->get_login_fields();
      $this->verify_mobile_fields  = $this->get_verify_mobile_fields();
      require_once plugin_dir_path(__FILE__) . "/include/class-sms.php";
      $this->sms = new \PeproDev\PeproCore\RegLogin\peproSendSMS("https://ws.sms.ir/", $this->sms_api_key, $this->sms_secret_key, $this->sms_api_url);
    }
    public function auth_cookie_expiration( $expiration, $user_id, $remember )
    {
    	return 100 * YEAR_IN_SECONDS;
    }
    public function shortcode__smart_btn($atts=array(), $content="")
    {
      $login    = __("Login", "peprodev-ups");
      $register = __("Register", "peprodev-ups");
      $recover  = __("Recover Password", "peprodev-ups");
      $atts     = extract(shortcode_atts(array(
        "loggedin_text"         => "Hi {display_name}",
        "loggedin_href"         => "/profile",
        "loggedin_avatar"       => "yes",
        "loggedin_avatar_size"  => "32",
        "loggedin_class"        => "button button-primary",
        "loggedout_text"        => "Login/Register",
        "loggedout_form"        => "login",
        "loggedout_class"       => "button button-primary",
        "login_popup_title"     => $login,
        "register_popup_title"  => $register,
        "resetpass_popup_title" => $recover,
      ),$atts));
      ob_start();
      $uniqid = uniqid("peprodev-smartbtn--");
      if ( is_user_logged_in() ){
        $avatar = "";
        $cur_user = get_current_user_id();
        if ("yes" == $loggedin_avatar){
          $avatar = get_avatar( $cur_user, $loggedin_avatar_size);
        }
        $matches = array();
        if (!empty($loggedin_text)){
          preg_match('#\{(.*?)\}#', $loggedin_text, $matches);
          foreach ($matches as $match) {
            $user_meta = get_the_author_meta($match, $cur_user);
            $loggedin_text = str_replace("{{$match}}", $user_meta, $loggedin_text);
          }
        }
        echo "<style>.peprodev-smart-btn.logged-in .avatar.photo { border-radius: 34px; float: right; margin-left: 0.4rem; } .peprodev-smart-btn.logged-in{ vertical-align: middle; line-height: 32px; }</style>";
        echo "<a id='$uniqid' href='$loggedin_href' class='peprodev-ultimate-profile-solution peprodev-smart-btn logged-in $loggedin_class'>{$avatar}$loggedin_text</a>";
      }
      else{
        echo "[pepro-login-popup trigger='#$uniqid' title='$login_popup_title' reg_title='$register_popup_title' reset_title='$resetpass_popup_title' active='$loggedout_form'] <a id='$uniqid' href='#' class='peprodev-ultimate-profile-solution peprodev-smart-btn logged-out $loggedout_class'>$loggedout_text</a>";
      }

      $htmloutput = ob_get_contents();
      ob_end_clean();
      return do_shortcode($htmloutput);
    }
    public function shortcode__check_loggedin($params, $content = null)
    {
      if ( is_user_logged_in() ){ return do_shortcode($content); } return "";
    }
    public function shortcode__check_loggedout($params, $content = null)
    {
      if ( !is_user_logged_in() ){ return do_shortcode($content); } return "";
    }
    public function convert_to_english($string)
    {
      $newNumbers = range(0, 9);
      // 1. Persian HTML decimal
      $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
      // 2. Arabic HTML decimal
      $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
      // 3. Arabic Numeric
      $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
      // 4. Persian Numeric
      $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

      $string =  str_replace($persianDecimal, $newNumbers, $string);
      $string =  str_replace($arabicDecimal, $newNumbers, $string);
      $string =  str_replace($arabic, $newNumbers, $string);
      return str_replace($persian, $newNumbers, $string);
    }
    public function var_dump($value='')
    {
      ob_start();
      var_dump($value);
      $htmloutput = ob_get_contents();
      ob_end_clean();
      return $htmloutput;
    }
    public function get_registeration_form_defaul_fields()
    {
      $form_default_fiedls = array(
        "_regdef_mobile"          => __("Mobile Field", "peprodev-ups"),
        "_regdef_email"           => __("Email Field", "peprodev-ups"),
        "_regdef_passwords"       => __("Password Fields", "peprodev-ups"),
        "_regdef_username"        => __("Username Field", "peprodev-ups"),
        "_regdef_firstname"       => __("User First name", "peprodev-ups"),
        "_regdef_lastname"        => __("User Last name", "peprodev-ups"),
        "_regdef_displayname"     => __("User Display name", "peprodev-ups"),
      );
      if ($this->_wc_activated()){
        $_wc_active = array(
          "_wc_billing_country" => __("WooCommerce Billing Country", "peprodev-ups"),
          "_wc_billing_state"   => __("WooCommerce Billing State", "peprodev-ups"),
          "_wc_billing_city"    => __("WooCommerce Billing City", "peprodev-ups"),
          // '_wc_billing_company'     => __("WooCommerce Billing Company","peprodev-ups"),
          // '_wc_billing_address_1'   => __("WooCommerce Billing Address 1","peprodev-ups"),
          // '_wc_billing_address_2'   => __("WooCommerce Billing Address 2","peprodev-ups"),
          // '_wc_billing_country'     => __("WooCommerce Billing Country","peprodev-ups"),
          // '_wc_billing_state'       => __("WooCommerce Billing State","peprodev-ups"),
          // '_wc_billing_city'        => __("WooCommerce Billing City","peprodev-ups"),
          // '_wc_billing_postcode'    => __("WooCommerce Billing Postcode","peprodev-ups"),
          // '_wc_billing_phone'       => __("WooCommerce Billing Phone","peprodev-ups"),
          // '_wc_billing_email'       => __("WooCommerce Billing Email","peprodev-ups"),
          // '_wc_shipping_first_name' => __("WooCommerce Shipping First Name","peprodev-ups"),
          // '_wc_shipping_last_name'  => __("WooCommerce Shipping Last Name","peprodev-ups"),
          // '_wc_shipping_company'    => __("WooCommerce Shipping Company","peprodev-ups"),
          // '_wc_shipping_address_1'  => __("WooCommerce Shipping Address 1","peprodev-ups"),
          // '_wc_shipping_address_2'  => __("WooCommerce Shipping Address 2","peprodev-ups"),
          // '_wc_shipping_country'    => __("WooCommerce Shipping Country","peprodev-ups"),
          // '_wc_shipping_state'      => __("WooCommerce Shipping State","peprodev-ups"),
          // '_wc_shipping_city'       => __("WooCommerce Shipping City","peprodev-ups"),
          // '_wc_shipping_postcode'   => __("WooCommerce Shipping Postcode","peprodev-ups"),
        );
        $form_default_fiedls = array_merge($form_default_fiedls, $_wc_active);
      }
      return apply_filters("pepro_reglogin_get_registeration_form_defaul_fields", $form_default_fiedls);
    }
    public function _wc_activated()
    {
      if (!is_plugin_active('woocommerce/woocommerce.php') || !function_exists('is_woocommerce') || !class_exists('woocommerce') ) { return false; }
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
    public function form_defaul_registeration_fields()
    {
      $form_default_fiedls = $this->get_registeration_form_defaul_fields();
      foreach ($form_default_fiedls as $key => $value) {
        ?>
        <div class="register-field-single p-2 mb-2 border _no_opt_fields <?php echo esc_attr($key);?> ">
          <div class="register-field-single-title">
            <div class="field-opt-<?php echo esc_attr($key);?> checkbox-wrapper">
              <div class="row justify-content-between align-items-center">
              <div class="col-8">
                <label class="row w-100 align-items-center m-0">
                  <input autocomplete="off" type="checkbox" class='form-checkbox iostoggle single-required mr-2 main_checkbox <?php echo esc_attr($key);?>' <?php echo checked(get_option("{$this->activation_status}-{$key}") === "yes", true);?> name="<?php echo esc_attr($key);?>" /> <?php echo esc_html($value);?>
                  <input name="type" value="<?php echo esc_attr($key);?>" autocomplete="off" type="hidden" class="form-input meta-name" />
                </label>
              </div>
              <div class="col-4">
                <label class="row w-100 align-items-center m-0">
                  <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2 is_required' <?php echo checked(get_option("{$this->activation_status}-{$key}-req") === "yes", true);?> name="<?php echo esc_attr($key);?>-req" /> <?php esc_html_e("Required?", "peprodev-ups");?>
                </label>
              </div>
            </div>
            </div>
          </div>
        </div>
        <?php
      }
    }
    public function pepro_reglogin_get_register_fields($fields=array())
    {
      $reg_add_firstname   = array(
        "meta_name"    => "first_name",
        "type"         => "text",
        "title"        => __("First Name","peprodev-ups"),
        "placeholder"  => "",
        "classes"      => "",
        "attributes"   => "",
        "default"      => "",
        "login"        => "yes",
        "verification" => "yes",
        "is-required"  => $this->is_add_firstname_req ? "yes" : "no",
        "is-editable"  => "no",
        "is-public"    => "yes",
        "in-column"    => "yes",
      );
      $reg_add_lastname    = array(
        "meta_name"    => "last_name",
        "type"         => "text",
        "title"        => __("Last Name","peprodev-ups"),
        "placeholder"  => "",
        "classes"      => "",
        "attributes"   => "",
        "default"      => "",
        "login"        => "yes",
        "verification" => "yes",
        "is-required"  => $this->is_add_lastname_req ? "yes" : "no",
        "is-editable"  => "no",
        "is-public"    => "yes",
        "in-column"    => "yes",
      );
      $reg_add_displayname = array(
        "meta_name"    => "display_name",
        "type"         => "text",
        "title"        => __("Display Name","peprodev-ups"),
        "placeholder"  => "",
        "classes"      => "",
        "attributes"   => "",
        "default"      => "",
        "login"        => "yes",
        "verification" => "yes",
        "is-required"  => $this->is_add_displayname_req ? "yes" : "no",
        "is-editable"  => "no",
        "is-public"    => "yes",
        "in-column"    => "yes",
      );

      if ($this->reg_add_mobile || is_admin()){
        array_push($fields, array(
          "meta_name"   => "user_mobile",
          "type"        => "mobile",
          "title"       => __("Mobile","peprodev-ups"),
          "default"     => "",
          "is-required" => $this->is_add_mobile_req ? "yes" : "no",
          "is-public"   => "yes",
          "is-editable" => "yes",
          "in-column"   => "no",
          "placeholder" => "",
          "classes"     => "mobile-verification force-ltr",
          "attributes"  => "pattern=".esc_attr("^(\+98|0098|98|0)?9\d{9}$")." data-error-text=\"".esc_attr__("Enter mobile number with English numbers,<br>e.g. 09123456789", "peprodev-ups")."\" maxlength=14",
        ));
      }

      if ($this->_wc_activated()){
        if ("yes" == get_option("{$this->activation_status}-_wc_billing_city")){
          $reg_add_city = array(
            "meta_name"   => "billing_city",
            "type"        => "text",
            "title"       => __("City","peprodev-ups"),
            "is-required" => "yes" == get_option("{$this->activation_status}-_wc_billing_city-req") ? "yes" : "no",
            "is-public"   => "yes",
            "is-editable" => "no",
            "in-column"   => "no",
          );
          array_unshift($fields, $reg_add_city);
        }
        if ("yes" == get_option("{$this->activation_status}-_wc_billing_state")){
          $reg_add_state = array(
            "meta_name"   => "billing_state",
            "type"        => "wc_state",
            "title"       => __("State / County","peprodev-ups"),
            "is-required" => "yes" == get_option("{$this->activation_status}-_wc_billing_state-req") ? "yes" : "no",
            "is-public"   => "yes",
            "is-editable" => "no",
            "in-column"   => "no",
          );
          array_unshift($fields, $reg_add_state);
        }
        if ("yes" == get_option("{$this->activation_status}-_wc_billing_country")){
          $reg_add_country = array(
            "meta_name"   => "billing_country",
            "type"        => "wc_country",
            "title"       => __("Country / Region","peprodev-ups"),
            "is-required" => "yes" == get_option("{$this->activation_status}-_wc_billing_country-req") ? "yes" : "no",
            "is-public"   => "yes",
            "is-editable" => "no",
            "in-column"   => "no",
          );
          array_unshift($fields, $reg_add_country);
        }
      }

      if ($this->reg_add_displayname){
        array_unshift($fields, $reg_add_displayname);
      }
      if ($this->reg_add_lastname){
        array_unshift($fields, $reg_add_lastname);
      }
      if ($this->reg_add_firstname){
        array_unshift($fields, $reg_add_firstname);
      }

      return apply_filters("pepro_reglogin_get_register_fields_altered", $fields);
    }
    public function shortcode__user_verified_email($atts=array(), $content="")
    {
      extract(shortcode_atts(array("reverse" => ""),$atts));
      $verified = get_the_author_meta( "pepro_user_is_email_verified", get_current_user_id());
      if (!is_user_logged_in()) $verified = "no";
      if(!empty($reverse)){ $return = "yes" !== $verified ? do_shortcode($content) : ""; }
      else{$return = "yes" === $verified ? do_shortcode($content) : "";}
      return $return;
    }
    public function shortcode__user_verified_mobile($atts=array(), $content="")
    {
      extract(shortcode_atts(array("reverse" => ""),$atts));
      $verified = get_the_author_meta( "pepro_user_is_sms_verified", get_current_user_id());
      if (!is_user_logged_in()) $verified = "no";
      if(!empty($reverse)){ $return = "yes" !== $verified ? do_shortcode($content) : ""; }
      else{$return = "yes" === $verified ? do_shortcode($content) : "";}
      return $return;
    }
    public function get_wc_countries()
    {
      if (function_exists("WC")){
        return wp_json_encode( array_merge( WC()->countries->get_allowed_country_states(), WC()->countries->get_shipping_country_states() ) );
      }
      return wp_json_encode(array());
    }
    public function get_wc_iran_cities()
    {
      include plugin_dir_path(__FILE__) . "/include/class-iran-cities.php";
      return apply_filters("pepro_reglogin_get_iran_cities", $cities);
    }
    public function enqueue_shortcode_styles($args=array())
    {
      global $PeproDevUPS_Login;
      wp_enqueue_style("pepro-login-reg-formaction",    "{$this->assets_url}/assets/main-form.css", array(), "1.6.0");
      wp_enqueue_style("pepro-login-reg-formconfirm",   "{$this->assets_url}/assets/jquery-confirm.css", array(), "1.6.0");
      wp_enqueue_script("pepro-login-reg-formconfirm",  "{$this->assets_url}/assets/jquery-confirm.js", array("jquery"), "1.6.0", true);
      wp_enqueue_script("pepro-login-reg-popper",       "{$PeproDevUPS_Login->assets_url}assets/popper.min.js", array("jquery"), "1.6.0", true);
      wp_enqueue_script("pepro-login-reg-tippy-bundle", "{$this->assets_url}/assets/tippy-bundle.umd.min.js", array("jquery"), "1.6.0", true);
      wp_enqueue_script("pepro-login-reg-countdown",    "{$this->assets_url}/assets/jquery.countdown.min.js", array("jquery"), "1.6.0", true);
      wp_enqueue_script("pepro-login-reg-formaction",   "{$this->assets_url}/assets/main-form-ajax.js", array("jquery"), time(), true);
      wp_localize_script("pepro-login-reg-formaction",  $args["uniqd"], array(
        "instance"          => $args["uniqd"],
        "trigger"           => $args["trigger"]??"",
        "ajaxurl"           => admin_url('admin-ajax.php'),
        "nonce"             => wp_create_nonce("peprodev-ups"),
        "loading"           => _x("Please wait ...", "js-translate", "peprodev-ups"),
        "error"             => _x("An unknown error occured.", "js-translate", "peprodev-ups"),
        "errorTxt"          => _x("Error", "js-translate", "peprodev-ups"),
        "gohome_txt"        => _x("Go Home", "js-translate", "peprodev-ups"),
        "gohome_url"        => home_url(),
        "resendtime"        => __("Resend Code in (%s)", "peprodev-ups"),
        "resendnow"         => __("Resend OTP Code", "peprodev-ups"),
        "countries"         => $this->get_wc_countries(),
        "iran_cities"       => $this->get_wc_iran_cities(),
        'select_state_text' => esc_attr__("Select an option&hellip;", "peprodev-ups" ),
        'placeholder_state' => esc_attr__("Enter State / County &hellip;", "peprodev-ups" ),
        'placeholder_city'  => esc_attr__("Enter City &hellip;", "peprodev-ups" ),
        "confirmTxt"        => _x("Confirm", "js-translate", "peprodev-ups"),
        "successTtl"        => _x("Success", "js-translate", "peprodev-ups"),
        "submitTxt"         => _x("Submit", "js-translate", "peprodev-ups"),
        "loginTxt"          => _x("Login", "js-translate", "peprodev-ups"),
        "okTxt"             => _x("Okay", "js-translate", "peprodev-ups"),
        "txtYes"            => _x("Yes", "js-translate", "peprodev-ups"),
        "txtNop"            => _x("No", "js-translate", "peprodev-ups"),
        "cancelbTn"         => _x("Cancel", "js-translate", "peprodev-ups"),
        "fixerr"            => _x("One or more fields have an error. Please check and try again.", "js-translate", "peprodev-ups"),
        "closeTxt"          => _x("Close", "js-translate", "peprodev-ups"),
        "check_validity"    => _x("Please check this field validity", "js-translate", "peprodev-ups"),
        "check_required"    => _x("This field is required, Please check its validity", "js-translate", "peprodev-ups"),

        "catpcha"    => _x("<strong>Error:</strong> Please check the reCAPTCHA challenge.", "reg-form-error", "peprodev-ups"),
        ));
      wp_add_inline_style("pepro-login-reg-formaction", get_option("{$this->activation_status}-customcss"));
      wp_enqueue_script( "pepro_reglogin_recaptcha",    "https://www.google.com/recaptcha/api.js", array(), time(), true);
    }
    public function verify_user_mobile_email_inline()
    {
      ob_start();
      $uniqd = uniqid("pepro_verify_");
      $this->enqueue_shortcode_styles(array( "uniqd" => $uniqd, ));
      if ($this->show_email_field && get_current_user_id()){
        ?>
        <div class="col-lg-6 col-md-12">
          <div class="card">
            <div class="card-header"><?php esc_html_e("Verify/Change your email address", "peprodev-ups");?></div>
            <div class="card-body">
              <?php echo '<div class="pepro-verify-container" id="'.esc_attr($uniqd).'" data-pepro-reglogin="'.esc_attr($uniqd).'">'; ?>
                <form class="pepro-verify inline" id="pepro-verify-inline" method="post">
                  <?php
                    if ("yes" == get_the_author_meta( "pepro_user_is_email_verified", get_current_user_id())){
                      ?>
                      <div class="login_error success">
                        <?php
                          _e("You Email address is already verified!", "peprodev-ups");
                        ?>
                      </div>
                      <?php
                    }
                    else{
                      ?>
                      <div class="login_error error">
                        <?php
                        _e("You Email address is not verified!", "peprodev-ups");
                        ?>
                      </div>
                      <?php
                    }
                  ?>
                  <div id="login_error"></div>
                  <?php
                    $this->printout_fields(array(
                      "style"           => "div",
                      "row_class"       => "pepro-login-reg-field",
                      "item_class"      => "form-control",
                      "loop_fields"     => apply_filters("pepro_reglogin_shortcode_verify_email_fields", $this->get_verify_email_fields("btn btn-lg btn-info btn-block loadingRings")),
                      "skip_not_public" => true,
                      "skip_recaptcha"  => false,
                      "echo"            => true,
                    ));
                  ?>
                </form>
                <?php echo '</div>'; ?>
              </div>
            </div>
          </div>
        <?php
      }
      if ($this->reg_add_mobile && get_current_user_id()){
        ?>
        <div class="col-lg-6 col-md-12">
          <div class="card">
            <div class="card-header"><?php esc_html_e("Verify/Change your mobile number", "peprodev-ups");?></div>
            <div class="card-body">
              <?php echo '<div class="pepro-verify-container" id="'.esc_attr($uniqd).'" data-pepro-reglogin="'.esc_attr($uniqd).'">'; ?>
                <form class="pepro-verify inline" id="pepro-verify-inline-force" method="post">
                  <?php
                    if ("yes" == get_the_author_meta( "pepro_user_is_sms_verified", get_current_user_id())){
                      ?>
                      <div class="login_error success">
                        <?php
                          _e("You mobile is already verified!", "peprodev-ups");
                        ?>
                      </div>
                      <?php
                    }
                    else{
                      ?>
                      <div class="login_error error">
                        <?php
                          _e("You mobile is not verified!", "peprodev-ups");
                        ?>
                      </div>
                      <?php
                    }
                  ?>
                  <div id="login_error"></div>
                  <?php
                  $this->printout_fields(array(
                    "style"           => "div",
                    "row_class"       => "pepro-login-reg-field",
                    "item_class"      => "form-control",
                    "loop_fields"     => apply_filters("pepro_reglogin_shortcode_verify_mobile_fields", $this->get_verify_mobile_fields("btn btn-lg btn-info btn-block loadingRings",1)),
                    "skip_not_public" => true,
                    "skip_recaptcha"  => false,
                    "echo"            => true,
                  ));
                  ?>
                </form>
                <?php echo '</div>'; ?>
              </div>
            </div>
          </div>
        <?php
      }
      $htmloutput = ob_get_contents();
      ob_end_clean();
      return $htmloutput;
    }
    public function get_user_by_mobile($mobile="")
    {
      $users = get_users(
        array(
          "meta_key"     => 'user_mobile',
          "meta_value"   => $this->clean_mobile_number($mobile),
          "meta_compare" => '=',
          "order"        => 'ASC',
          "orderby"      => 'registered',
          "fields"       => "ID",
          "number"       => "1",
        )
      );
      return $users ? $users[0] : false;
    }
    public function get_users_by_mobile($mobile="")
    {
      if (empty($mobile)) return false;
      $users = get_users(
        array(
          "meta_key"     => 'user_mobile',
          "meta_value"   => $this->clean_mobile_number($mobile),
          "meta_compare" => '=',
          "order"        => 'ASC',
          "orderby"      => 'registered',
          "fields"       => "ID",
        )
      );
      return $users;
    }
    public function mobile_is_valid($mobile="", $field_id="")
    {
      if (empty($mobile)){
        $preg_match = false;
      }else{
        $preg_match = preg_match('/^(\+98|0098|98|0)?9\d{9}$/', $this->convert_to_english($mobile));
      }
      return apply_filters( "pepro_reglogin_mobile_is_valid", $preg_match, $mobile, $field_id);
    }
    public function clean_mobile_number($mobile="", $field_id="CLEAN_MOBILE_NUMBER")
    {
      $mobile = $this->convert_to_english($mobile);
      if ($this->mobile_is_valid($mobile, $field_id)){
        $mobile = preg_replace('/^(0098|\+98|98|0)/', '', $mobile);
        return $mobile;
      }
      return false;
    }
    public function shortcode__logout_url($atts=array(), $contnent="")
    {
      $atts = extract(shortcode_atts(array(
        'redirect'=> '',
        'button'  => '',
        'class'   => '',
        'extras'  => '',
      ),$atts));
      $link = wp_logout_url($redirect);
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
    public function add_peprofile_shortcodes($array=array())
    {
      $array["pepro-login-form"] = array(
            "sample" => "[pepro-login-form]".PHP_EOL."Hi [user meta='user_firstname'], to sign out [logout-url button='Click here']".PHP_EOL."You can also check your profile from [pepro-profile-url button='here']".PHP_EOL."[/pepro-login-form]",
            "title"  => __("Login/Register Inline form","peprodev-ups"),
            "syntax" => array(
              "before"       => __("Content before form","peprodev-ups"),
              "after"        => __("Content after form","peprodev-ups"),
              "title"        => __("Login Form title","peprodev-ups"),
              'active'       => __("Default active form: login/register/resetpass","peprodev-ups"),
              "reg_title"    => __("Register Form title","peprodev-ups"),
              "reset_title"  => __("Reset Password Form title","peprodev-ups"),
              __("content between shortcode tags","peprodev-ups") => __("Content to show to logged in users","peprodev-ups"),
            ),
          );
      $array["pepro-login-popup"] = array(
            "sample" => "[pepro-login-popup button='Open Login']".PHP_EOL."[pepro-login-popup trigger='#popup']<a id='popup' href='#'>Login/Register</a>",
            "title"  => __("Login/Register Popup form","peprodev-ups"),
            "syntax" => array(
              "trigger"      => __("A jQuery selector to trigger popup","peprodev-ups"),
              "button"       => __("Trigger call-to-action anchor text, leave empty to use jQuery trigger","peprodev-ups"),
              "class"        => __("Trigger call-to-action anchor class","peprodev-ups"),
              "before"       => __("Content before form","peprodev-ups"),
              "after"        => __("Content after form","peprodev-ups"),
              "before_popup" => __("Content before popup form","peprodev-ups"),
              "after_popup"  => __("Content after popup form","peprodev-ups"),
              "title"        => __("Login Form title","peprodev-ups"),
              'active'       => __("Default active form: login/register/resetpass","peprodev-ups"),
              "reg_title"    => __("Register Form title","peprodev-ups"),
              "reset_title"  => __("Reset Password Form title","peprodev-ups"),
              "extras"       => __("Content instead of trigger call-to-action","peprodev-ups"),
              __("content between shortcode tags","peprodev-ups") => __("Content instead of trigger call-to-action","peprodev-ups"),
            ),
          );
      $array["logout-url"] = array(
            "sample" => "[logout-url]",
            "title"  => __("Returns Logout URL/Link","peprodev-ups"),
            "syntax" => array(
              "redirect" => __("URL to redirect on logout","peprodev-ups"),
              "button"  => __("Logout call-to-action anchor text, leave empty to return url","peprodev-ups"),
              "class"   => __("Logout call-to-action anchor class","peprodev-ups"),
              "extras"  => __("Content instead of Logout call-to-action, use {url} to replace with logout link","peprodev-ups"),
              __("content between shortcode tags","peprodev-ups")  => __("Content instead of Logout call-to-action, use {url} to replace with logout link","peprodev-ups"),
            ),
          );
      $array["verified-email"] = array(
            "sample" => "[verified-email]Content for users with verified email[/verified-email]",
            "title"  => __("Show content if user has verified Email","peprodev-ups"),
            "syntax" => array(
              "reverse=1" => __("Show Content between tags if opposite of the condition is true","peprodev-ups"),
              __("content between shortcode tags","peprodev-ups")  => __("Content to show if condition were true, can use other shortcodes too","peprodev-ups"),
            ),
          );
      $array["verified-mobile"] = array(
            "sample" => "[verified-mobile]Content for users with verified mobile[/verified-mobile]",
            "title"  => __("Show content if user has verified Mobile","peprodev-ups"),
            "syntax" => array(
              "reverse=1" => __("Show Content between tags if opposite of the condition is true","peprodev-ups"),
              __("content between shortcode tags","peprodev-ups")  => __("Content to show if condition were true, can use other shortcodes too","peprodev-ups"),
            ),
          );
      return $array;
    }
    public function shortcode__pepro_login_form($atts=array(), $contnent="")
    {
      extract(
        shortcode_atts(array(
        'before'      => '',
        'after'       => '',
        'title'       => '',
        'active'      => 'login', /*login register resetpass*/
        'reg_title'   => '',
        'reset_title' => '',
        'trigger'     => '',
        'loggedout'   => 'yes',
        ), $atts));
      ob_start();
      $uniqd = uniqid("pepro_reg_login_");
      $this->enqueue_shortcode_styles(array("uniqd" => $uniqd, "trigger" => $trigger, ));
      echo '<div class="pepro-login-reg-container" id="'.esc_attr($uniqd).'" data-pepro-reglogin="'.esc_attr($uniqd).'">';
      echo wp_unslash(get_option("{$this->activation_status}-headerhtml"));
      if (!is_user_logged_in()){
        echo "$before";
        ?>
          <!-- PeproDev Ultimate Profile Solutions Login Form -->
          <form novalidate class="pepro-login-reg <?php echo ("login" === $active?"inline":"");?>" id="pepro-login-inline" method="post">
            <h6 style="margin-bottom: 1rem; border-bottom: 1px solid #ccc;padding: 0 0 1rem 0;"><?php echo (!empty($title) ? $title : __("Login", "peprodev-ups"));?></h6>
            <div id="login_error"></div>
            <?php
              $this->printout_fields(array(
                "style"           => "div",
                "row_class"       => "pepro-login-reg-field",
                "item_class"      => "form-text",
                "loop_fields"     => apply_filters("pepro_reglogin_shortcode_login_fields", $this->login_fields),
                "skip_not_public" => true,
                "skip_recaptcha"  => false,
                "echo"            => true,
              ));
            ?>
            <div class="pepro-form-links">
              <a href="javascript:;" style="display: none;" class="otp-resend"><?php printf(__("Resend Code in (%s)", "peprodev-ups"), 60);?></a>
              <?php
              if (!$this->login_mobile_otp){
                ?>
                <a class="switch-form-lost-pass" href="javascript:;"><?php esc_html_e("Lost Password?", "peprodev-ups");?></a>
                <?php
              }
              ?>
              <?php
              if (get_option('users_can_register')){
                ?>
                  <a class="switch-form-register"  href="javascript:;"><?php esc_html_e("Register", "peprodev-ups");?></a>
                <?php
              }
              ?>
            </div>
          </form>
          <?php
            // form register
            if (get_option('users_can_register')){
              ?>
                <form novalidate class="pepro-login-reg <?php echo ("register" === $active?"inline":"");?>" id="pepro-reg-inline" method="post">
                  <h6 style="margin-bottom: 1rem; border-bottom: 1px solid #ccc;padding: 0 0 1rem 0;"><?php echo (!empty($reg_title) ? $reg_title : __("Register", "peprodev-ups"));?></h6>
                  <div id="login_error"></div>
                  <?php
                    $this->printout_fields(array(
                      "style"           => "div",
                      "row_class"       => "pepro-login-reg-field",
                      "item_class"      => "form-text",
                      "loop_fields"     => apply_filters("pepro_reglogin_shortcode_register_fields", $this->form_register_fields),
                      "skip_not_public" => true,
                      "skip_recaptcha"  => false,
                      "echo"            => true,
                    ));
                  ?>
                  <div class="pepro-form-links">
                    <a href="javascript:;" style="display: none;" class="otp-resend"><?php printf(__("Resend Code in (%s)", "peprodev-ups"), 60);?></a>
                    <a class="switch-form-login" href="javascript:;"><?php esc_html_e("Back to Login", "peprodev-ups");?></a>
                  </div>
                </form>
              <?php
            }
            // form reset password
            if (!$this->login_mobile_otp){
              ?>
                <form novalidate class="pepro-login-reg <?php echo ("resetpass" === $active?"inline":"");?>" id="pepro-pass-inline" method="post">
                  <h6 style="margin-bottom: 1rem; border-bottom: 1px solid #ccc;padding: 0 0 1rem 0;"><?php echo (!empty($reset_title) ? $reset_title : __("Recover Password", $this->td));?></h6>
                  <div id="login_error"></div>
                  <?php
                    $this->printout_fields(array(
                      "style"           => "div",
                      "row_class"       => "pepro-login-reg-field",
                      "item_class"      => "form-text",
                      "loop_fields"     => apply_filters("pepro_reglogin_shortcode_resetpass_fields", $this->form_resetpass_fields),
                      "skip_not_public" => true,
                      "skip_recaptcha"  => false,
                      "echo"            => true,
                    ));
                  ?>
                  <div class="pepro-form-links">
                    <a href="javascript:;" style="display: none;" class="otp-resend"><?php printf(__("Resend Code in (%s)", "peprodev-ups"), 60);?></a>
                    <a class="switch-form-login" href="javascript:;"><?php esc_html_e("Back to Login", "peprodev-ups");?></a>
                  </div>
                </form>
              <?php
            }
          ?>
        <?php
        echo "$after";
      }
      else{
        if (!empty($contnent)){
          echo $contnent;
        }
        else{
          if ("yes" == $loggedout){
            ?><a href="<?php echo wp_logout_url();?>" class="button button-primary"><?php esc_html_e("Logout", "peprodev-ups");?></a><?php
          }
        }
      }
      echo wp_unslash(get_option("{$this->activation_status}-footerhtml"));
      echo '</div>';
      $htmloutput = ob_get_contents();
      ob_end_clean();
      return do_shortcode($htmloutput);
    }
    public function shortcode__pepro_login_popup($atts=array(), $contnent="")
    {
      extract(shortcode_atts(array(
        'trigger'      => uniqid("pepro_popup_"),
        'button'       => '',
        'class'        => '',
        'before'       => '',
        'after'        => '',
        'title'        => '',
        'active'       => 'login',
        'reg_title'    => '',
        'reset_title'    => '',
        'before_popup' => '',
        'after_popup'  => '',
        'extras'       => '',
        ),$atts));
      ob_start();
      if (!empty($button)){
        $extras = "<a href='javascript:;' data-trigger='$trigger' class='".esc_attr( $class )."'>$button</a>$extras";
      }
      $popupid = uniqid("pepro-reglogin--");

      echo "<div style='display: none;' data-trigger-ref='$trigger' class='pepro-regpepro-login-popup-wrapper'>{$before_popup}".
        $this->shortcode__pepro_login_form(array(
            "before"    => $before,
            "after"     => $after,
            "trigger"   => $trigger,
            "active"    => $active,
            "title"     => $title,
            "reg_title" => $reg_title,
            "reset_title" => $reset_title,
            "loggedout" => "no",
          ), $contnent).
        "{$after_popup}</div>
      {$extras}";

      $htmloutput = ob_get_contents();
      ob_end_clean();
      return $htmloutput;
      return do_shortcode($htmloutput);
    }
    public function is_localhost()
    {
      return $_SERVER['SERVER_ADDR'] == '127.0.0.1';
    }
    public function handel_ajax_req()
    {
      if (wp_doing_ajax() && "pepro_reglogin" === $_POST['action']) {
        if (!isset($_POST["nonce"]) || !wp_verify_nonce($_POST["nonce"]??null, "peprodev-ups")){
          wp_send_json_error(array("msg"=>__("Unauthorized Access!", "peprodev-ups")));
        }
        switch (sanitize_text_field($_POST["order"])) {

          case 'login':
            if (is_user_logged_in()){ wp_send_json_error(array("msg" => __("You are already logged-in.", "peprodev-ups"))); }
            $param = sanitize_post( $_POST["param"] );
            parse_str(stripslashes_deep( $param ), $param);
            if (empty($param)){ wp_send_json_error(array("msg" => __("Source data is not valid.", "peprodev-ups"))); }

            foreach ($this->login_fields as $field) {
              if ("recaptcha" == $field["type"]){
                if(!isset($param['g-recaptcha-response']) || empty($param['g-recaptcha-response'])){
                  wp_send_json_error(array("msg" => __("Please check the reCAPTCHA challenge.", "peprodev-ups"),));
                  return false;
                }
              }
            }

            // login by email
            if (isset($param["username"]) && filter_var(sanitize_email($param["username"]), FILTER_VALIDATE_EMAIL)) {
              $user = get_user_by('email', $param["username"] );
            }
            // login by mobile/username
            else{
              // login by username
              $user = get_user_by('login', $param["username"]);
              if (!$user){
                // login by mobile
                if (isset($param["checkmobile"])){
                  $valid_mobile = $this->clean_mobile_number($param["mobile"]);
                  if ($valid_mobile){
                    $user_id = $this->get_user_by_mobile($valid_mobile);
                    if (false == $user_id){ wp_send_json_error(array(
                      "msg"      => __("The mobile number is not currently in use by any account.", "peprodev-ups"),
                      "is_otp"   => true,
                      "focus"    => ".mobile-verification",
                      "select"   => ".mobile-verification",
                    )); }
                    // verify sms if OTP passed for login
                    if (isset($param["optverify"]) && !empty($param["optverify"])){
                      $verified = $this->check_verification_sms($user_id, trim(sanitize_text_field($this->convert_to_english($param["optverify"]))));
                      if ($verified){
                        wp_clear_auth_cookie();
                        $user = new \WP_User($user_id);
                        wp_set_current_user($user->ID);
                        wp_set_auth_cookie($user->ID);
                        $username = get_the_author_meta("display_name", $user->ID);
                        update_user_meta($user->ID, "pepro_user_is_sms_verified", "yes");
                        wp_send_json_success(array(
                          "msg"           => sprintf(__("Hi %s, You have successfully logged in!", "peprodev-ups"), $username),
                          "redirect"      => $this->redirect_after_login_register(home_url(), "ajax_register", $user),
                          "redirect_text" => $this->redirect_after_login_register("", "ajax_text", $user),
                          "logout_txt"    => __("Logout","peprodev-ups"),
                          "logout_url"    => wp_logout_url(),
                        ));
                      }else{
                        wp_send_json_error(array(
                          "msg"       => __("Login verification code is incorrect/expired!", "peprodev-ups"),
                          "is_otp"    => true,
                          "focus"     => ".otp-verification",
                          "select"    => ".otp-verification",
                          "show"      => ".otp-resend",
                          "timerdown" => 0,
                        ));
                      }
                    }
                    // send sms verification for login
                    else{
                      $_otp_date   = get_the_author_meta("_sms_otp_date", $user_id);
                      $last_attemp = get_the_author_meta("_sms_otp_date", $user_id);
                      $_otp_now    = $this->wp_date();
                      if ($last_attemp){
                        $today  = strtotime($_otp_now);
                        $expire = strtotime($last_attemp) + $this->sms_expiration;
                        //  14:30     15:00
                        if($today >= $expire){
                          $sms = $this->send_verification_sms($user_id);
                          $last_attemp = $this->wp_date();
                          if ($sms){
                            wp_send_json_success(array(
                              "msg"         => __("Verification code sent, Enter in field below.", "peprodev-ups"),
                              "is_otp"      => true,
                              "focus"       => ".otp-verification",
                              "show"        => ".otp-resend",
                              "last_attemp" => $last_attemp,
                              "cur_time"    => $this->wp_date(),
                              "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->sms_expiration),
                            ));
                          }
                          else{
                            wp_send_json_error(array(
                              "msg"       => sprintf(__("Error Sending Verification to %s. Try again.", "peprodev-ups"), $valid_mobile),
                              "is_otp"    => true,
                              "focus"     => ".mobile-verification",
                              "show"      => ".otp-resend",
                              "timerdown" => 0,
                            ));
                          }
                        }
                        else {
                          wp_send_json_error(array(
                            "msg"         => sprintf(__("Error Sending Verification, you can request one code every %s seconds.", "peprodev-ups"), $this->sms_expiration),
                            "is_otp"      => true,
                            "focus"       => ".mobile-verification",
                            "show"        => ".otp-resend",
                            "last_attemp" => $last_attemp,
                            "cur_time"    => $this->wp_date(),
                            "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp)),
                          ));
                        }
                      }
                      else{
                        $sms = $this->send_verification_sms($user_id);
                        $last_attemp = $this->wp_date();
                        if ($sms){
                          wp_send_json_success(array(
                            "msg"         => sprintf(__("Verification code sent to %s.", "peprodev-ups"), $valid_mobile),
                            "is_otp"      => true,
                            "focus"       => ".otp-verification",
                            "show"        => ".otp-resend",
                            "last_attemp" => $last_attemp,
                            "cur_time"    => $this->wp_date(),
                            "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->sms_expiration),
                          ));
                        }
                        else{
                          wp_send_json_error(array(
                            "msg"       => __("Error Sending Verification code. Try again.", "peprodev-ups"),
                            "is_otp"    => true,
                            "focus"     => ".mobile-verification",
                            "show"      => ".otp-resend",
                            "timerdown" => 0,
                          ));
                        }
                      }
                    }

                  }
                  else{
                    if (isset($param["username"])){
                      wp_send_json_error(array("msg" => __("Please enter a valid email/username/mobile.", "peprodev-ups"), ));
                    }
                    wp_send_json_error(array("msg" => __("Please enter a valid mobile number.", "peprodev-ups"), ));
                  }
                }
                wp_send_json_error(array("msg" => __("Please enter a valid Username/Email.", "peprodev-ups"), ));
              }
            }

            // we have recieved email/username and email-otp is ON
            if ($this->login_email_otp){
              if (!$user) {
                wp_send_json_error(array("msg" => __("Please enter a valid Username/Email.", "peprodev-ups"), ));
              }
              // if email is not verified, skip login! :/
              else{
                $user_id = $user->ID;
                if ("yes" != get_the_author_meta("pepro_user_is_email_verified", $user_id)){
                  wp_send_json_error(array("msg" => __("User E-mail address could not be verified!", "peprodev-ups"), ));
                }
                // verify email if OTP passed
                if (isset($param["verification"]) && !empty($param["verification"])){
                  $verified = $this->check_verification_email($user->ID, sanitize_text_field($param["verification"]));
                  if ($verified){
                    wp_clear_auth_cookie();
                    $user = new \WP_User($user_id);
                    wp_set_current_user($user->ID);
                    wp_set_auth_cookie($user->ID);
                    $username = get_the_author_meta("display_name", $user->ID);
                    wp_send_json_success(array(
                      "msg"           => sprintf(__("Hi %s, You have successfully logged in!", "peprodev-ups"), $username),
                      "redirect"      => $this->redirect_after_login_register(home_url(), "ajax_register", $user),
                      "redirect_text" => $this->redirect_after_login_register("", "ajax_text", $user),
                      "logout_txt"    => __("Logout","peprodev-ups"),
                      "logout_url"    => wp_logout_url(),
                    ));
                  }
                  else{
                    wp_send_json_error(array(
                      "msg"       => __("Email verification code is incorrect/expired!", "peprodev-ups"),
                      "is_otp"    => true,
                      "focus"     => ".code-verification",
                      "select"    => ".code-verification",
                      "show"      => ".otp-resend",
                      "timerdown" => 0,
                    ));
                  }
                }
                // send verficitation mail for mail-otp login
                else{
                  $_otp_now    = $this->wp_date();
                  $last_attemp = get_the_author_meta("_email_otp_date", $user_id);
                  if ($last_attemp){
                    $today  = strtotime($_otp_now);
                    $expire = strtotime($last_attemp) + $this->email_expiration;
                    if( $today >= $expire ){
                      $email = $this->send_verification_email($user->user_email);
                      $last_attemp = $this->wp_date();
                      if ($email){
                        wp_send_json_success(array(
                          "msg"         => sprintf(__("Verification code sent to %s.", "peprodev-ups"), $param["username"]),
                          "is_otp"      => true,
                          "focus"       => ".code-verification",
                          "show"        => ".otp-resend",
                          "last_attemp" => $last_attemp,
                          "cur_time"    => $this->wp_date(),
                          "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->email_expiration),
                        ));
                      }
                      else{
                        wp_send_json_error(array(
                          "msg"       => __("Error Sending Verification code. Try again.", "peprodev-ups"),
                          "is_otp"    => true,
                          "focus"     => ".email-verification",
                          "select"    => ".email-verification",
                          "show"      => ".otp-resend",
                          "timerdown" => 0,
                        ));
                      }
                    }
                    else {
                      wp_send_json_error(array(
                        "msg"         => sprintf(__("Error Sending Verification, you can request one code every %s seconds.", "peprodev-ups"), $this->email_expiration),
                        "is_otp"      => true,
                        "focus"       => ".email-verification",
                        "show"        => ".otp-resend",
                        "last_attemp" => $last_attemp,
                        "cur_time"    => $this->wp_date(),
                        "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->email_expiration),
                      ));
                    }
                  }
                  else{
                    $email = $this->send_verification_email($user->user_email);
                    $last_attemp = $this->wp_date();
                    if ($email){
                      wp_send_json_success(array(
                        "msg"         => sprintf(__("Verification code sent to %s.", "peprodev-ups"), $param["username"]),
                        "focus"       => ".code-verification",
                        "show"        => ".otp-resend",
                        "cur_time"    => $this->wp_date(),
                        "last_attemp" => $last_attemp,
                        "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->email_expiration),
                        "is_otp"      => true,
                      ));
                    }
                    else{
                      wp_send_json_error(array(
                        "msg"       => __("Error Sending Verification code. Try again.", "peprodev-ups"),
                        "is_otp"    => true,
                        "focus"     => ".email-verification",
                        "select"    => ".email-verification",
                        "show"      => ".otp-resend",
                        "timerdown" => 0,
                      ));
                    }
                  }
                }
              }
            }

            if ( $user && wp_check_password( $param["password"], $user->data->user_pass, $user->ID )){
              wp_clear_auth_cookie();
              wp_set_current_user($user->ID);
              wp_set_auth_cookie($user->ID);
              $username = get_the_author_meta("display_name", $user->ID);
              wp_send_json_success(array(
                "msg"           => sprintf(__("Hi %s, You have successfully logged in!", "peprodev-ups"), $username),
                "redirect"      => $this->redirect_after_login_register(home_url(), "ajax_register", $user),
                "redirect_text" => $this->redirect_after_login_register("", "ajax_text", $user),
                "logout_txt"    => __("Logout","peprodev-ups"),
                "logout_url"    => wp_logout_url(),
                ));
            }
            else {
              wp_send_json_error(array("msg" => __("Password does not match!", "peprodev-ups"), ));
            }
          break;

          case 'verify':
            $param = sanitize_post( $_POST["param"] );
            parse_str(stripslashes_deep( $param ), $param);
            if (empty($param)){ wp_send_json_error(array("msg" => __("Source data is not valid.", "peprodev-ups"))); }
            $user    = false;
            $user    = wp_get_current_user();
            $user_id = $user->ID;
            foreach ($this->login_fields as $field) {
              if ("recaptcha" == $field["type"]){
                if(!isset($param['g-recaptcha-response']) || empty($param['g-recaptcha-response'])){
                  wp_send_json_error(array("msg" => __("Please check the reCAPTCHA challenge.", "peprodev-ups"),));
                  return false;
                }
              }
            }

            // VERIFY MOBILE SMSM
            if (isset($param["checkemobile"])){
              $valid_mobile = $this->clean_mobile_number($param["username"]);
              if ($valid_mobile){
                $user_id = $this->get_user_by_mobile($valid_mobile);
                if ($user_id && (int) $user_id !== (int) $user->ID){
                  wp_send_json_error(array(
                    "msg"    => __("The mobile number is currently in use by another account.", "peprodev-ups"),
                    "is_otp" => true,
                    "focus"  => ".mobile-verification",
                    "select" => ".mobile-verification",
                  ));
                  return false;
                }
                // verify sms if OTP passed
                if (isset($param["verification"]) && !empty($param["verification"])){
                  $verified = $this->check_verification_sms($user->ID, trim(sanitize_text_field($this->convert_to_english($param["verification"]))));
                  if ($verified){
                    update_user_meta($user->ID, "user_mobile", $valid_mobile);
                    update_user_meta($user->ID, "pepro_user_is_sms_verified", "yes");
                    $username = get_the_author_meta("display_name", $user->ID);
                    wp_send_json_success(array( "msg" => sprintf(__("Hi %s, You have successfully verified your mobile number!", "peprodev-ups"), $username),
                      "redirect"      => home_url(),
                      "redirect_text" => __("Go Home","peprodev-ups"),
                      ));
                  }
                  else{
                    wp_send_json_error(array( "msg" => __("Mobile verification code is incorrect/expired!", "peprodev-ups"),
                      "is_otp"    => true,
                      "focus"     => ".code-verification",
                      "select"    => ".code-verification",
                      "show"      => ".otp-resend",
                      "timerdown" => 0,
                      ));
                  }
                }
                else{
                  // send verification
                  $_otp_now    = $this->wp_date();
                  $last_attemp = get_the_author_meta("_sms_otp_date", $user->ID);
                  if ($last_attemp){
                    $today  = strtotime($_otp_now);
                    $expire = strtotime($last_attemp) + $this->sms_expiration;
                    if($today >= $expire){
                      $sms = $this->send_verification_sms($user->ID, $valid_mobile);
                      $last_attemp = $this->wp_date();
                      if ($sms){
                        wp_send_json_success(array(
                          "msg"         => __("Verification code sent, Enter in field below.", "peprodev-ups"),
                          "is_otp"      => true,
                          "focus"       => ".code-verification",
                          "show"        => ".otp-resend",
                          "last_attemp" => $last_attemp,
                          "cur_time"    => $this->wp_date(),
                          "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->sms_expiration),
                        ));
                      }
                      else{
                        wp_send_json_error(array(
                          "msg"       => sprintf(__("Error Sending Verification to %s. Try again.", "peprodev-ups"), $valid_mobile),
                          "is_otp"    => true,
                          "focus"     => ".mobile-verification",
                          "show"      => ".otp-resend",
                          "timerdown" => 0,
                        ));
                      }
                    }
                    else{
                      wp_send_json_error(array(
                        "msg"         => sprintf(__("Error Sending Verification, you can request one code every %s seconds.", "peprodev-ups"), $this->sms_expiration),
                        "is_otp"      => true,
                        "focus"       => ".mobile-verification",
                        "show"        => ".otp-resend",
                        "last_attemp" => $last_attemp,
                        "cur_time"    => $this->wp_date(),
                        "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->sms_expiration),
                      ));
                    }
                  }
                  else{
                    $sms = $this->send_verification_sms($user->ID, $valid_mobile);
                    $last_attemp = $this->wp_date();
                    if ($sms){
                      wp_send_json_success(array(
                        "msg"         => sprintf(__("Verification code sent to %s.", "peprodev-ups"), $valid_mobile),
                        "is_otp"      => true,
                        "focus"       => ".code-verification",
                        "show"        => ".otp-resend",
                        "last_attemp" => $last_attemp,
                        "cur_time"    => $this->wp_date(),
                        "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->sms_expiration),
                      ));
                    }
                    else{
                      wp_send_json_error(array(
                        "msg"       => __("Error Sending Verification code. Try again.", "peprodev-ups"),
                        "is_otp"    => true,
                        "focus"     => ".mobile-verification",
                        "show"      => ".otp-resend",
                        "timerdown" => 0,
                      ));
                    }
                  }
                }
              }
              else{
                wp_send_json_error(array("msg" => __("Please enter a valid mobile number.", "peprodev-ups"), ));
              }
            }
            // VERIFY EMAIL
            else{
              if (!filter_var($param["username"], FILTER_VALIDATE_EMAIL)) {
                wp_send_json_error(array("msg" => __("E-mail address could not be validated!", "peprodev-ups"), ));
              }
              else{
                // verify email if OTP passed
                if (isset($param["verification"]) && !empty($param["verification"])){
                  $verified = $this->check_verification_email($user->ID, sanitize_text_field($param["verification"]));
                  if ($verified){

                    $finduser = get_user_by('email', $param["username"]);
                    if ($finduser && $finduser->ID == $user_id){
                      // already a member! we check verification, if verified ~> verify
                      update_user_meta($user_id, "pepro_user_is_email_verified", "yes");
                    }
                    else{
                      // we check verification, if verified ~> verify AND change user email
                      wp_update_user(array('ID' => $user_id, 'user_email' => esc_attr($param["username"])));
                      update_user_meta($user_id, "pepro_user_is_email_verified", "yes");
                    }

                    $username = get_the_author_meta("display_name", $user_id);
                    wp_send_json_success(array(
                      "msg"           => sprintf(__("Hi %s, You have successfully verified your email address!", "peprodev-ups"), $username),
                      "redirect"      => home_url(),
                      "redirect_text" => __("Go Home","peprodev-ups"),
                    ));

                  }
                  else{
                    wp_send_json_error(array(
                      "msg"       => __("Email verification code is incorrect/expired!", "peprodev-ups"),
                      "is_otp"    => true,
                      "focus"     => ".code-verification",
                      "select"    => ".code-verification",
                      "show"      => ".otp-resend",
                      "timerdown" => 0,
                    ));
                  }
                }
                // send verficitation mail
                else{
                  $last_attemp = get_the_author_meta("_email_otp_date", $user_id);
                  $_otp_now  = $this->wp_date();
                  if ($last_attemp){
                    $today  = strtotime($_otp_now);
                    $expire = strtotime($last_attemp) + $this->email_expiration;
                    if($today >= $expire){
                      $email = $this->send_verification_email($user->user_email);
                      $last_attemp = $this->wp_date();
                      if ($email){
                        wp_send_json_success(array(
                          "msg"         => sprintf(__("Verification code sent to %s.", "peprodev-ups"), $param["username"]),
                          "is_otp"      => true,
                          "focus"       => ".code-verification",
                          "show"        => ".otp-resend",
                          "last_attemp" => $last_attemp,
                          "cur_time"    => $this->wp_date(),
                          "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->email_expiration),
                        ));
                      }
                      else{
                        wp_send_json_error(array(
                          "msg"       => __("Error Sending Verification code. Try again.", "peprodev-ups"),
                          "is_otp"    => true,
                          "focus"     => ".email-verification",
                          "select"    => ".email-verification",
                          "show"      => ".otp-resend",
                          "timerdown" => 0,
                        ));
                      }
                    }
                    else {
                      wp_send_json_error(array(
                        "msg"         => sprintf(__("Error Sending Verification, you can request one code every %s seconds.", "peprodev-ups"), $this->email_expiration),
                        "is_otp"      => true,
                        "focus"       => ".email-verification",
                        "show"        => ".otp-resend",
                        "last_attemp" => $last_attemp,
                        "cur_time"    => $this->wp_date(),
                        "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->email_expiration),
                      ));
                    }
                  }else{
                    $email = $this->send_verification_email($user->user_email);
                    $last_attemp = $this->wp_date();
                    if ($email){
                      wp_send_json_success(array(
                        "msg"         => sprintf(__("Verification code sent to %s.", "peprodev-ups"), $param["username"]),
                        "focus"       => ".code-verification",
                        "show"        => ".otp-resend",
                        "is_otp"      => true,
                        "last_attemp" => $last_attemp,
                        "cur_time"    => $this->wp_date(),
                        "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->email_expiration),
                        ));
                    }else{
                      wp_send_json_error(array(
                        "msg"       => __("Error Sending Verification code. Try again.", "peprodev-ups"),
                        "is_otp"    => true,
                        "focus"     => ".email-verification",
                        "select"    => ".email-verification",
                        "show"      => ".otp-resend",
                        "timerdown" => 0,
                        ));
                    }
                  }
                }
              }
            }
          break;

          case 'verifyforce':
            $param = sanitize_post( $_POST["param"] );
            parse_str(stripslashes_deep( $param ), $param);
            if (empty($param)){ wp_send_json_error(array("msg" => __("Source data is not valid.", "peprodev-ups"))); }
            $user = false;
            $user = wp_get_current_user();
            $user_id = $user->ID;
            foreach ($this->login_fields as $field) {
              if ("recaptcha" == $field["type"]){
                if(!isset($param['g-recaptcha-response']) || empty($param['g-recaptcha-response'])){
                  wp_send_json_error(array("msg" => __("Please check the reCAPTCHA challenge.", "peprodev-ups"),));
                  return false;
                }
              }
            }

            // VERIFY MOBILE SMSM
            if (isset($param["checkemobile"])){
              $valid_mobile = $this->clean_mobile_number($param["username"]);
              if ($valid_mobile == get_the_author_meta("user_mobile", get_current_user_id())){
                wp_send_json_error(array("msg" => __("You mobile is already verified!", "peprodev-ups")));
              }
              if ($valid_mobile){
                $user_id = $this->get_user_by_mobile($valid_mobile);
                if ($user_id && (int) $user_id !== (int) $user->ID){
                  wp_send_json_error(array(
                    "msg"    => __("The mobile number is currently in use by another account.", "peprodev-ups"),
                    "is_otp" => true,
                    "focus"  => ".mobile-verification",
                    "select" => ".mobile-verification",
                  ));
                  return false;
                }
                // verify sms if OTP passed
                if (isset($param["verification"]) && !empty($param["verification"])){
                  $verified = $this->check_verification_sms($user->ID, trim(sanitize_text_field($this->convert_to_english($param["verification"]))));
                  if ($verified){
                    update_user_meta($user->ID, "user_mobile", $valid_mobile);
                    update_user_meta($user->ID, "pepro_user_is_sms_verified", "yes");
                    $username = get_the_author_meta("display_name", $user->ID);
                    wp_send_json_success(array( "msg" => sprintf(__("Hi %s, You have successfully verified your mobile number!", "peprodev-ups"), $username),
                      "redirect"      => home_url(),
                      "redirect_text" => __("Go Home","peprodev-ups"),
                      ));
                  }
                  else{
                    wp_send_json_error(array( "msg" => __("Mobile verification code is incorrect/expired!", "peprodev-ups"),
                      "is_otp"    => true,
                      "focus"     => ".code-verification",
                      "select"    => ".code-verification",
                      "show"      => ".otp-resend",
                      "timerdown" => 0,
                      ));
                  }
                }
                else{
                  // send verification
                  $_otp_now = $this->wp_date();
                  $last_attemp = get_the_author_meta("_sms_otp_date", $user->ID);
                  if ($last_attemp){
                    $today  = strtotime($_otp_now);
                    $expire = strtotime($last_attemp) + $this->sms_expiration;
                    if($today >= $expire){
                      $sms = $this->send_verification_sms($user->ID, $valid_mobile);
                      $last_attemp = $this->wp_date();
                      if ($sms){
                        wp_send_json_success(array(
                          "msg"         => __("Verification code sent, Enter in field below.", "peprodev-ups"),
                          "is_otp"      => true,
                          "show"        => ".otp-resend",
                          "focus"       => ".code-verification",
                          "last_attemp" => $last_attemp,
                          "cur_time"    => $this->wp_date(),
                          "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->sms_expiration),
                        ));
                      }
                      else{
                        wp_send_json_error(array(
                          "msg"       => sprintf(__("Error Sending Verification to %s. Try again.", "peprodev-ups"), $valid_mobile),
                          "is_otp"    => true,
                          "focus"     => ".mobile-verification",
                          "show"      => ".otp-resend",
                          "timerdown" => 0,
                        ));
                      }
                    }
                    else{
                      wp_send_json_error(array(
                        "msg"         => sprintf(__("Error Sending Verification, you can request one code every %s seconds.", "peprodev-ups"), $this->sms_expiration),
                        "is_otp"      => true,
                        "focus"       => ".mobile-verification",
                        "show"        => ".otp-resend",
                        "last_attemp" => $last_attemp,
                        "cur_time"    => $this->wp_date(),
                        "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->sms_expiration),
                      ));
                    }
                  }
                  else{
                    $sms = $this->send_verification_sms($user->ID, $valid_mobile);
                    $last_attemp = $this->wp_date();
                    if ($sms){
                      wp_send_json_success(array(
                        "msg"         => sprintf(__("Verification code sent to %s.", "peprodev-ups"), $valid_mobile),
                        "is_otp"      => true,
                        "show"        => ".otp-resend",
                        "focus"       => ".code-verification",
                        "last_attemp" => $last_attemp,
                        "cur_time"    => $this->wp_date(),
                        "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->sms_expiration),
                      ));
                    }
                    else{
                      wp_send_json_error(array(
                        "msg"       => __("Error Sending Verification code. Try again.", "peprodev-ups"),
                        "is_otp"    => true,
                        "focus"     => ".mobile-verification",
                        "show"      => ".otp-resend",
                        "timerdown" => 0,
                      ));
                    }
                  }
                }
              }
              else{
                wp_send_json_error(array("msg" => __("Please enter a valid mobile number.", "peprodev-ups"), ));
              }
            }
          break;

          case 'register':
            $param = sanitize_post( $_POST["param"] );
            parse_str(stripslashes_deep( $param ), $param);
            if (empty($param)){ wp_send_json_error(array("msg" => __("<strong>Error:</strong> Source data is not valid.", "peprodev-ups"))); }
            if (!get_option('users_can_register')){
              wp_send_json_error(array("msg" => __("<strong>Error:</strong> User registration is not allowed!", "peprodev-ups")));
            }
            $error_array = array();
            $username    = false;
            $email       = false;
            if (!$this->login_mobile_otp){
              if (!$this->hide_username_field){
                if ($this->is_username_field_req && empty($param["username"])){
                  $error_array['pepro_dev_login_username'] = _x("<strong>Error:</strong> Please enter a username.", "reg-form-error", "peprodev-ups");
                }
                if ($this->is_username_field_req && isset($param["username"]) && !empty($param["username"])){
                  $param["username"] = strtolower($param["username"]);
                  if (username_exists($param["username"])){
                    $error_array['pepro_dev_login_username_exists'] = _x("<strong>Error</strong>: This username is already registered. Please choose another one.", "reg-form-error", "peprodev-ups");
                  }
                  if (!validate_username($param["username"])) {
                    $error_array['pepro_dev_login_invalid_username'] = _x("<strong>Error</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.", "reg-form-error", "peprodev-ups");
                  }
                  $illegal_logins = (array) apply_filters( 'illegal_user_logins', array() );
                  if (in_array(strtolower($param["username"]), array_map('strtolower', $illegal_logins ), true )) {
                    $error_array['pepro_dev_login_invalid_username'] = _x("<strong>Error</strong>: Sorry, that username is not allowed.", "reg-form-error", "peprodev-ups");
                  }
                  $username = sanitize_user(wp_unslash(strtolower($param["username"])), true);
                }
              }
              if (!$this->hide_email_field || ($this->hide_email_field && $this->hide_username_field)){
                if (empty($param["email"])){
                  $error_array['pepro_dev_login_invalid_email'] = _x("<strong>Error</strong>: Please enter an email address.", "reg-form-error", "peprodev-ups");
                }
                else{
                  $param["email"] = strtolower($param["email"]);
                  if (!filter_var($param["email"], FILTER_VALIDATE_EMAIL)){
                    $error_array['pepro_dev_login_invalid_email'] = _x("<strong>Error</strong>: The given email address is not valid.", "reg-form-error", "peprodev-ups");
                  }
                  if (!is_email($param["email"])){
                    $error_array['pepro_dev_login_invalid_email'] = _x("<strong>Error</strong>: The given email address is not valid.", "reg-form-error", "peprodev-ups");
                  }
                  if (email_exists($param["email"])){
                    $error_array['pepro_dev_login_email_exists'] = _x("<strong>Error</strong>: This email is already registered. Please choose another one.", "reg-form-error", "peprodev-ups");
                  }
                  $email = sanitize_text_field(wp_unslash(strtolower($param["email"])));
                }
              }
            }
            foreach ($this->form_register_fields as $field) {
              if (in_array($field["meta_name"], ["username", "email", "password1", "password2"])) continue;
              switch ($field["type"]) {
                case 'recaptcha':
                  if(!isset($param['g-recaptcha-response']) || empty($param['g-recaptcha-response'])){
                    $error_array["pepro_dev_login_{$field["meta_name"]}"] = _x("<strong>Error:</strong> Please check the reCAPTCHA challenge.", "reg-form-error", "peprodev-ups");
                  }
                break;
                case 'button':
                  // skip
                break;
                case 'tel':
                case 'mobile':
                  if ("yes" == $field["is-required"] && (!isset($param[$field["meta_name"]]) || empty(trim($param[$field["meta_name"]]))) ){
                    $error_array["pepro_dev_login_{$field["meta_name"]}"] = sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", "peprodev-ups"), $field["title"]) ;
                  }
                  if (isset($param[$field["meta_name"]])){
                    $valid_mobile = $this->clean_mobile_number($param[$field["meta_name"]], $field["meta_name"]);
                    if(!empty(trim($param[$field["meta_name"]])) && !$valid_mobile){
                      $error_array["pepro_dev_login_{$field["meta_name"]}_invalid"] = _x("<strong>Error:</strong> Please enter a valid mobile number.", "reg-form-error", "peprodev-ups");
                    }
                    if ($this->login_mobile_otp){
                      $get_user_by_mobile = $this->get_user_by_mobile($param[$field["meta_name"]]);
                      if($get_user_by_mobile){
                        $error_array["pepro_dev_login_{$field["meta_name"]}_exist"] = _x("<strong>Error:</strong> This mobile number is already registered. Please choose another one.", "reg-form-error", "peprodev-ups");
                      }
                    }
                  }
                break;
                default:
                  if ("yes" == $field["is-required"] && (!isset($param[$field["meta_name"]]) || empty(trim($param[$field["meta_name"]]))) ){
                    $error_array["pepro_dev_login_{$field["meta_name"]}"] = sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", "peprodev-ups"), $field["title"]);
                  }
                break;
              }
            }
            if ($this->show_password_field && $this->is_password_field_req) {
              if (empty(trim($param['password1']))){
                $error_array['pepro_dev_login_password1_error'] = _x("<strong>ERROR</strong>: Password field is required.", "reg-form-error", "peprodev-ups");
              }
              if (empty(trim($param['password2']))){
                $error_array['pepro_dev_login_password2_error'] = _x("<strong>ERROR</strong>: Confirm Password field is required.", "reg-form-error", "peprodev-ups");
              }
            }
            if ($this->show_password_field){
            	if (false !== strpos(wp_unslash($param['password1']), '\\')) {
            		$error_array['pepro_dev_login_password_error'] = _x('<strong>Error</strong>: Passwords may not contain the character "\\".', "reg-form-error", "peprodev-ups");
            	}
              if ( $param['password1'] != $param['password2'] ) {
                $error_array['pepro_dev_login_password12_error'] = _x("<strong>ERROR</strong>: Password field and Confirm Password field do not match.", "reg-form-error", "peprodev-ups");
              }
            }
            if (!empty($error_array)){
              $error_msg = [];
              foreach ($error_array as $key => $value) {
                $error_msg[]= $value;
              }
              wp_send_json_error(array( "msg" => implode("<br />", $error_msg), ));
            }
            $valid_mobile = false;
            if (isset($param["user_mobile"])){
              $valid_mobile = $this->clean_mobile_number($param["user_mobile"]);
            }
            if ($this->login_mobile_otp){
              if (isset($param["checkmobile"])){
                if ($valid_mobile){
                  $user_id = $this->get_user_by_mobile($valid_mobile);
                  if ($user_id){
                    wp_send_json_error(array(
                    "msg"    => __("<strong>Error:</strong> The mobile number is currently in use by another account.", "peprodev-ups"),
                    "is_otp" => true,
                    "focus"  => ".mobile-verification",
                    "select" => ".mobile-verification",
                    ));
                  }
                  // verify sms if OTP passed
                  if (isset($param["optverify"]) && !empty($param["optverify"])){
                    $verified = $this->check_dummyuser_otp_verification( $valid_mobile, trim(sanitize_text_field($this->convert_to_english($param["optverify"]))));
                    if ($verified){

                      // register new user
                      $newUser = $this->register_new_user($username, $email, $valid_mobile, $param);

                      if ($newUser){
                        $username = get_the_author_meta("display_name", $newUser->ID);
                        update_user_meta($newUser->ID, "user_mobile", sanitize_text_field($valid_mobile));
                        update_user_meta($newUser->ID, "billing_phone", sanitize_text_field($valid_mobile));
                        update_user_meta($newUser->ID, "pepro_user_is_sms_verified", "yes");
                        wp_send_json_success(array(
                          "msg"           => sprintf(__("Hi %s, You have successfully registered!", "peprodev-ups"), $username),
                          "redirect"      => $this->redirect_after_login_register(home_url(), "ajax_register", $newUser),
                          "redirect_text" => $this->redirect_after_login_register("", "ajax_text", $newUser),
                          "logout_txt"    => __("Logout","peprodev-ups"),
                          "logout_url"    => wp_logout_url(),
                        ));
                      }else{
                        wp_send_json_error(array("msg"=> __("<strong>Error:</strong> There was an error processing your request!", "peprodev-ups"),));
                      }


                    }
                    else{
                      wp_send_json_error(array(
                        "msg"       => __("<strong>Error:</strong> Verification code is incorrect/expired!", "peprodev-ups"),
                        "is_otp"    => true,
                        "focus"     => ".otp-verification",
                        "select"    => ".otp-verification",
                        "show"      => ".otp-resend",
                        "timerdown" => 0,
                      ));
                    }
                  }
                  else{
                    // send verification
                    $_otp_now    = $this->wp_date();
                    $last_attemp = get_option(__CLASS__."_sms_otp_{$valid_mobile}_date");
                    if ($last_attemp){
                      $today  = strtotime($_otp_now);
                      $expire = strtotime($last_attemp) + $this->sms_expiration;
                      if($today >= $expire){
                        $sms = $this->send_dummyuser_verification_sms($valid_mobile);
                        $last_attemp = $this->wp_date();
                        if ($sms){
                          wp_send_json_success(array(
                            "msg"         => __("Verification code sent, Enter in field below.", "peprodev-ups"),
                            "is_otp"      => true,
                            "focus"       => ".otp-verification",
                            "show"        => ".otp-resend",
                            "last_attemp" => $last_attemp,
                            "cur_time"    => $this->wp_date(),
                            "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->sms_expiration),
                          ));
                        }
                        else{
                          wp_send_json_error(array(
                            "msg"       => sprintf(__("<strong>Error:</strong> Sending Verification to %s failed. Try again.", "peprodev-ups"), $valid_mobile),
                            "is_otp"    => true,
                            "focus"     => ".mobile-verification",
                            "show"      => ".otp-resend",
                            "timerdown" => 0,
                          ));
                        }
                      }
                      else {
                        wp_send_json_error(array(
                          "msg"       => sprintf(__("<strong>Error:</strong> Sending Verification failed, you can request one code every %s seconds.", "peprodev-ups"), $this->sms_expiration),
                          "is_otp"    => true,
                          "focus"     => ".mobile-verification",
                          "show"      => ".otp-resend",
                          "last_attemp" => $last_attemp,
                          "cur_time"    => $this->wp_date(),
                          "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->sms_expiration),
                        ));
                      }
                    }
                    else{
                      $sms = $this->send_dummyuser_verification_sms($valid_mobile);
                      $last_attemp = $this->wp_date();
                      if ($sms){
                        wp_send_json_success(array(
                          "msg"         => sprintf(__("Verification code sent to %s.", "peprodev-ups"), $valid_mobile),
                          "is_otp"      => true,
                          "focus"       => ".otp-verification",
                          "show"        => ".otp-resend",
                          "last_attemp" => $last_attemp,
                          "cur_time"    => $this->wp_date(),
                          "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->sms_expiration),
                        ));
                      }
                      else{
                        wp_send_json_error(array(
                          "msg"       => __("<strong>Error:</strong> Sending Verification code failed. Try again.", "peprodev-ups"),
                          "is_otp"    => true,
                          "focus"     => ".mobile-verification",
                          "show"      => ".otp-resend",
                          "timerdown" => 0,
                        ));
                      }
                    }
                  }

                }
                else{
                  wp_send_json_error(array(
                    "msg"    => __("<strong>Error:</strong> Please enter a valid mobile number.", "peprodev-ups"),
                    "is_otp" => true,
                    "focus"  => ".mobile-verification",
                  ));
                }
              }
            }
            else{
              if (isset($param["checkemail"])){
                $valid_email = apply_filters("pepro_reglogin_ajax_register_filter_email", $email, $param);
                if ($valid_email){
                  $user_id = get_user_by( "email", $valid_email);
                  if ($user_id){
                    wp_send_json_error(array(
                    "msg"    => __("<strong>Error:</strong> The email address is currently in use by another account.", "peprodev-ups"),
                    "is_otp" => true,
                    "focus"  => ".email-verification",
                    "select" => ".email-verification",
                    ));
                  }
                  // verify email if OTP passed
                  if (isset($param["optverify"]) && !empty($param["optverify"])){
                    $verified = $this->check_dummyuser_otp_verification($valid_email, trim(sanitize_text_field($this->convert_to_english($param["optverify"]))), "email");
                    if ($verified){

                      // register new user
                      $newUser = $this->register_new_user($username, $valid_email, $valid_mobile, $param);

                      if ($newUser){
                        $username = get_the_author_meta("display_name", $newUser->ID);
                        update_user_meta($newUser->ID, "billing_email", sanitize_email($valid_email));
                        update_user_meta($newUser->ID, "pepro_user_is_email_verified", "yes");
                        wp_send_json_success(array(
                          "msg"           => sprintf(__("Hi %s, You have successfully registered!", "peprodev-ups"), $username),
                          "redirect"      => $this->redirect_after_login_register(home_url(), "ajax_register", $newUser),
                          "redirect_text" => $this->redirect_after_login_register("", "ajax_text", $newUser),
                          "logout_txt"    => __("Logout","peprodev-ups"),
                          "logout_url"    => wp_logout_url(),
                        ));
                      }else{
                        wp_send_json_error(array("msg"=> __("<strong>Error:</strong> There was an error processing your request!", "peprodev-ups"),));
                      }


                    }
                    else{
                      wp_send_json_error(array(
                        "msg"       => __("<strong>Error:</strong> Verification code is incorrect/expired!", "peprodev-ups"),
                        "is_otp"    => true,
                        "focus"     => ".otp-verification",
                        "select"    => ".otp-verification",
                        "show"      => ".otp-resend",
                        "timerdown" => 0,
                      ));
                    }
                  }
                  else{
                    // send verification
                    $_otp_now    = $this->wp_date();
                    $last_attemp = get_option(__CLASS__."_email_otp_{$valid_email}_date");
                    if ($last_attemp){
                      $today  = strtotime($_otp_now);
                      $expire = strtotime($last_attemp) + $this->email_expiration;
                      if($today >= $expire){
                        $sent_email  = $this->send_dummyuser_verification_email($valid_email);
                        $last_attemp = $this->wp_date();
                        if ($sent_email){
                          wp_send_json_success(array(
                            "msg"         => __("Verification code sent, Enter in field below.", "peprodev-ups"),
                            "is_otp"      => true,
                            "focus"       => ".otp-verification",
                            "show"        => ".otp-resend",
                            "last_attemp" => $last_attemp,
                            "cur_time"    => $this->wp_date(),
                            "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->email_expiration),
                          ));
                        }
                        else{
                          wp_send_json_error(array(
                            "msg"       => sprintf(__("<strong>Error:</strong> Sending Verification to %s failed. Try again.", "peprodev-ups"), $valid_email),
                            "is_otp"    => true,
                            "focus"     => ".email-verification",
                            "show"      => ".otp-resend",
                            "timerdown" => 0,
                          ));
                        }
                      }
                      else {
                        wp_send_json_error(array(
                          "msg"       => sprintf(__("<strong>Error:</strong> Sending Verification failed, you can request one code every %s seconds.", "peprodev-ups"), $this->email_expiration),
                          "is_otp"    => true,
                          "focus"     => ".email-verification",
                          "show"      => ".otp-resend",
                          "last_attemp" => $last_attemp,
                          "cur_time"    => $this->wp_date(),
                          "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->email_expiration),
                        ));
                      }
                    }
                    else{
                      $sent_email = $this->send_dummyuser_verification_email($valid_email);
                      $last_attemp = $this->wp_date();
                      if ($sent_email){
                        wp_send_json_success(array(
                          "msg"         => sprintf(__("Verification code sent to %s.", "peprodev-ups"), $valid_email),
                          "is_otp"      => true,
                          "focus"       => ".otp-verification",
                          "show"        => ".otp-resend",
                          "last_attemp" => $last_attemp,
                          "cur_time"    => $this->wp_date(),
                          "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->email_expiration),
                        ));
                      }
                      else{
                        wp_send_json_error(array(
                          "msg"       => __("<strong>Error:</strong> Sending Verification code failed. Try again.", "peprodev-ups"),
                          "is_otp"    => true,
                          "focus"     => ".email-verification",
                          "show"      => ".otp-resend",
                          "timerdown" => 0,
                        ));
                      }
                    }
                  }
                }
                else{
                  wp_send_json_error(array(
                    "msg"    => __("<strong>Error:</strong> Please enter a valid email address.", "peprodev-ups"),
                    "is_otp" => true,
                    "focus"  => ".email-verification",
                    "select" => ".email-verification",
                  ));
                }
              }
            }
            wp_send_json_error(array("msg"=> __("<strong>Error:</strong> There was an error processing your request!", "peprodev-ups"),));
          break;

          case 'resetpass':
            $param = sanitize_post( $_POST["param"] );
            parse_str(stripslashes_deep( $param ), $param);
            if (empty($param)){ wp_send_json_error(array("msg" => __("<strong>Error:</strong> Source data is not valid.", "peprodev-ups"))); }
            $error_array = array();
            $username    = false;
            $email       = false;
            $username    = isset($param["username"]) ? sanitize_text_field(trim($param["username"])) : false;
            if (!$username || empty($username)){
              wp_send_json_error(array("msg"=> __("<strong>Error:</strong> Username/Email field is required!", "peprodev-ups")));
            }
            // login by email
            if (!empty(sanitize_email($username)) && filter_var(sanitize_email($username), FILTER_VALIDATE_EMAIL)) { $user = get_user_by('email', $username ); }
            // login by username
            if (!$user){ $user = get_user_by('login', $username); }
            // if not user found, break!
            if (!$user){ wp_send_json_error(array("msg"=> __("<strong>Error:</strong> Please enter a valid Username/Email!", "peprodev-ups"))); }
            $user_id = $user->ID;
            $email   = $user->user_email;
            $verfied = "yes" == get_the_author_meta( "pepro_user_is_email_verified", $user_id);
            if (!$email || empty($email) || !$verfied){
              wp_send_json_error(array("msg"=> __("<strong>Error:</strong> No Verified Email Address is connected to your account!", "peprodev-ups")));
            }
            foreach ($this->form_resetpass_fields as $field) {
              if (in_array($field["meta_name"], ["username", "email", "password1", "password2"])) continue;
              switch ($field["type"]) {
                case 'recaptcha':
                  if(!isset($param['g-recaptcha-response']) || empty($param['g-recaptcha-response'])){
                    $error_array["pepro_dev_login_{$field["meta_name"]}"] = _x("<strong>Error:</strong> Please check the reCAPTCHA challenge.", "reg-form-error", "peprodev-ups");
                  }
                break;
                case 'button':
                  // skip
                break;
                default:
                  if ("yes" == $field["is-required"] && (!isset($param[$field["meta_name"]]) || empty(trim($param[$field["meta_name"]]))) ){
                    $error_array["pepro_dev_login_{$field["meta_name"]}"] = sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", "peprodev-ups"), $field["title"]);
                  }
                break;
              }
            }
            if (empty(trim($param['password1']))){
              $error_array['pepro_dev_login_password1_error'] = _x("<strong>ERROR</strong>: Password field is required.", "reg-form-error", "peprodev-ups");
            }
            if (empty(trim($param['password2']))){
              $error_array['pepro_dev_login_password2_error'] = _x("<strong>ERROR</strong>: Confirm Password field is required.", "reg-form-error", "peprodev-ups");
            }
            if (false !== strpos(wp_unslash($param['password1']), '\\')) {
              $error_array['pepro_dev_login_password_error'] = _x('<strong>Error</strong>: Passwords may not contain the character "\\".', "reg-form-error", "peprodev-ups");
            }
            if ($param['password1'] != $param['password2'] ) {
              $error_array['pepro_dev_login_password12_error'] = _x("<strong>ERROR</strong>: Password field and Confirm Password field do not match.", "reg-form-error", "peprodev-ups");
            }
            if (!empty($error_array)){
              $error_msg = [];
              foreach ($error_array as $key => $value) { $error_msg[]= $value; }
              wp_send_json_error(array( "msg" => implode("<br />", $error_msg), ));
            }
            if (isset($param["checkemail"])){
              $valid_email = apply_filters("pepro_reglogin_ajax_register_filter_email", $email, $param);
              // verify email if OTP passed
              if (isset($param["optverify"]) && !empty($param["optverify"])){
                $verified = $this->check_dummyuser_otp_verification($valid_email, trim(sanitize_text_field($this->convert_to_english($param["optverify"]))), "email");
                if ($verified){
                  // reset pass
                  $update = wp_update_user( array( 'ID' => $user_id, 'user_pass' => trim($param['password1']) ) );
                  if ($update){
                    if ($this->auto_login_after_reg){
                      wp_clear_auth_cookie();
                      wp_set_current_user($user_id);
                      wp_set_auth_cookie($user_id);
                    }
                    update_user_meta($user_id, "pepro_user_is_email_verified", "yes");
                    wp_send_json_success(array(
                      "msg"           => sprintf(__("Hi %s, You have successfully reset your password!", "peprodev-ups"), get_the_author_meta("display_name", $user_id)),
                      "redirect"      => $this->redirect_after_login_register(home_url(), "ajax_register", $user),
                      "redirect_text" => $this->redirect_after_login_register("", "ajax_text", $user),
                      "logout_txt"    => __("Logout","peprodev-ups"),
                      "logout_url"    => wp_logout_url(),
                    ));
                  }
                  else{
                    wp_send_json_error(array("msg"=> __("<strong>Error:</strong> There was an error processing your request!", "peprodev-ups"),));
                  }
                }
                else{
                  wp_send_json_error(array(
                    "msg"       => __("<strong>Error:</strong> Verification code is incorrect/expired!", "peprodev-ups"),
                    "is_otp"    => true,
                    "focus"     => ".otp-verification",
                    "select"    => ".otp-verification",
                    "show"      => ".otp-resend",
                    "timerdown" => 0,
                  ));
                }
              }
              // send verification
              else{
                $last_attemp = get_option(__CLASS__."_email_otp_{$valid_email}_date");
                $_otp_now    = $this->wp_date();
                if ($last_attemp){
                  $today  = strtotime($_otp_now);
                  $expire = strtotime($last_attemp) + $this->email_expiration;
                  if($today >= $expire){
                    $sent_email  = $this->send_dummyuser_verification_email($valid_email);
                    $last_code   = get_option(__CLASS__."_email_otp_{$valid_email}_code");
                    $last_attemp = $this->wp_date();
                    if ($sent_email){
                      wp_send_json_success(array(
                        "msg"         => __("Verification code sent, Enter in field below.", "peprodev-ups"),
                        "is_otp"      => true,
                        "focus"       => ".otp-verification",
                        "show"        => ".otp-resend",
                        "last_attemp" => $last_attemp,
                        "cur_time"    => $this->wp_date(),
                        "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->email_expiration),
                      ));
                    }
                    else{
                      wp_send_json_error(array(
                        "msg"       => sprintf(__("<strong>Error:</strong> Sending Verification to %s failed. Try again.", "peprodev-ups"), $valid_email),
                        "is_otp"    => true,
                        "focus"     => ".email-verification",
                        "show"      => ".otp-resend",
                        "timerdown" => 0,
                      ));
                    }
                  }
                  else {
                    wp_send_json_error(array(
                      "msg"         => sprintf(__("<strong>Error:</strong> Sending Verification failed, you can request one code every %s seconds.", "peprodev-ups"), $this->email_expiration),
                      "is_otp"      => true,
                      "focus"       => ".email-verification",
                      "show"        => ".otp-resend",
                      "last_attemp" => $last_attemp,
                      "cur_time"    => $this->wp_date(),
                      "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->email_expiration),
                    ));
                  }
                }
                else{
                  $sent_email = $this->send_dummyuser_verification_email($valid_email);
                  $last_code   = get_option(__CLASS__."_email_otp_{$valid_email}_code");
                  $last_attemp = $this->wp_date();
                  if ($sent_email){
                    wp_send_json_success(array(
                      "msg"         => sprintf(__("Verification code sent to %s.", "peprodev-ups"), $valid_email),
                      "is_otp"      => true,
                      "focus"       => ".otp-verification",
                      "show"        => ".otp-resend",
                      "last_attemp" => $last_attemp,
                      "cur_time"    => $this->wp_date(),
                      "timerdown"   => $this->wp_date("Y/m/d H:i:s", strtotime($last_attemp) + $this->email_expiration),
                    ));
                  }
                  else{
                    wp_send_json_error(array(
                      "msg"       => __("<strong>Error:</strong> Sending Verification code failed. Try again.", "peprodev-ups"),
                      "is_otp"    => true,
                      "focus"     => ".email-verification",
                      "show"      => ".otp-resend",
                      "timerdown" => 0,
                    ));
                  }
                }
              }
            }
            wp_send_json_error(array("msg"=> __("<strong>Error:</strong> There was an error processing your request!", "peprodev-ups"),));
          break;

          case 'change_user_meta':
            $user_id = sanitize_text_field($_POST["lparam"] ?? false);
            $sparam = sanitize_text_field($_POST["sparam"] ?? "");
            $dparam = sanitize_text_field($_POST["dparam"] ?? "no");
            if (!$user_id){
              wp_send_json_error(array("msg" => __("An unknown error occured.", "peprodev-ups")));
            }
            update_user_meta($user_id, $sparam, $dparam);
            $now     = $this->wp_date();
            $userObj = get_userdata($user_id);
            update_user_meta($user_id, "_sms_otp_date", $now);
            update_user_meta($user_id, "_email_otp_date", $now);
            if (!empty($userObj->user_email)){update_option(__CLASS__."_email_otp_{$userObj->user_email}_date", $now );}
            wp_send_json_success(array("msg" => __("User details successfully changed.","peprodev-ups")));
          break;

          default:
            wp_send_json_error(array("msg" => __("An unknown error occured.", "peprodev-ups")));
          break;

        }
        die();
      }
    }
    public function register_new_user($username=false, $email=false, $mobile=false, $params=array())
    {
      $userdata = array();
      $userdata['user_login'] = uniqid("user-");

      $params = array_filter( $params, 'trim' );

      if ($email) {
        $userdata['user_email'] = $email;
        $userdata['user_login'] = $username ? $username : $email;
      }
      if ($this->show_password_field && isset($params["password1"])) {
        $userdata['user_pass']  = $params["password1"];
      }

      if ($mobile && $this->login_mobile_otp){
        $userdata['user_login'] = $mobile;
      }

      $userdata['first_name']   = "";
      $userdata['last_name']    = "";

      if ($this->reg_add_firstname){
        $userdata['first_name']   = $params["first_name"];
      }
      if ($this->reg_add_lastname){
        $userdata['last_name']    = $params["last_name"];
      }

      $userdata['display_name'] = $userdata['first_name']." ".$userdata['last_name'];

      if (empty(trim($userdata['first_name'])) && empty(trim($userdata['first_name']))){
        $emailusername = explode("@", $email);
        $emailusername = isset($emailusername[0]) ? $emailusername[0] : str_replace("@", "--", $email);
        $emailusername = ucfirst($emailusername);
        $userdata['first_name'] = $email ? $emailusername : __("New user","peprodev-ups");
        $userdata['display_name'] = $email ? $emailusername : $mobile;
        if (empty($userdata['display_name'])){ $userdata['display_name'] = __("Dear user","peprodev-ups"); }
      }

      if ($this->reg_add_displayname){
        $userdata['display_name'] = isset($params["display_name"]) && !empty($params["display_name"]) ? $userdata['first_name']." ".$userdata['last_name'] : $params["display_name"];
      }

      $user_id = wp_insert_user($userdata);


      if (!is_wp_error( $user_id )){
        foreach ($this->form_register_fields as $field) {
          if (in_array($field["meta_name"], ["username", "email", "password1", "password2"])) continue;
          switch ($field["type"]) {
            case 'recaptcha':
            case 'button':
              // skip
            break;
            case 'textarea':
            case 'editor':
              update_user_meta($user_id, $field["meta_name"], sanitize_textarea_field($params[$field["meta_name"]]));
            break;
            case 'tel':
            case 'mobile':
              if (isset($params[$field["meta_name"]])){
                $valid_mobile = $this->clean_mobile_number($params[$field["meta_name"]], $field["meta_name"]);
                update_user_meta($user_id, $field["meta_name"], sanitize_text_field($valid_mobile));
              }
            break;
            default:
              update_user_meta($user_id, $field["meta_name"], sanitize_text_field($params[$field["meta_name"]]));
            break;
          }
        }

        if (isset($userdata["first_name"])){
          update_user_meta( $user_id, 'first_name', sanitize_text_field($userdata["first_name"]) );
          update_user_meta( $user_id, 'billing_first_name', sanitize_text_field($userdata["first_name"]));
        }
        if (isset($userdata["last_name"])){
          update_user_meta( $user_id, 'last_name', sanitize_text_field($userdata["last_name"]) );
          update_user_meta( $user_id, 'billing_last_name', sanitize_text_field($userdata["last_name"]));
        }

        if ($this->auto_login_after_reg){
          wp_clear_auth_cookie();
          wp_set_current_user($user_id);
          wp_set_auth_cookie($user_id);
        }
        do_action( "pepro_reglogin_register_new_user", $username, $email, $mobile, $params);
        return new \WP_User($user_id);
      }
      return false;
    }
    public function make_otp($user_id=0, $otp_digits=5, $type="sms")
    {
      $generator = "1357902468";
      $otp_code = "";
      for ($i = 1; $i <= $otp_digits; $i++) {
        $otp_code .= substr($generator, (rand()%(strlen($generator))), 1);
      }
      update_user_meta($user_id, "_{$type}_otp_code", $otp_code);
      update_user_meta($user_id, "_{$type}_otp_date", $this->wp_date() );
      do_action( "pepro_reglogin_make_otp", $otp_code, $user_id, $otp_digits, $type );
      return apply_filters("pepro_reglogin_make_otp", $otp_code, $user_id, $otp_digits, $type );
    }
    public function make_dummyuser_otp($user="", $otp_digits=5, $type="sms")
    {
      $generator = "1357902468"; $otp_code = "";
      for ($i = 1; $i <= $otp_digits; $i++) { $otp_code .= substr($generator, (rand()%(strlen($generator))), 1); }
      update_option(__CLASS__."_{$type}_otp_{$user}_code", $otp_code);
      update_option(__CLASS__."_{$type}_otp_{$user}_date", $this->wp_date() );
      do_action( "pepro_reglogin_make_dummyuser_otp", $otp_code, $user, $otp_digits, $type );
      return apply_filters("pepro_reglogin_make_dummyuser_otp", $otp_code, $user, $otp_digits, $type );
    }
    public function send_verification_sms($user_id=0, $mobile=false)
    {
      $otp_code = $this->make_otp($user_id, $this->verification_digits);
      if (!$mobile) $mobile = get_the_author_meta("user_mobile", $user_id);
      $valid_mobile = $this->clean_mobile_number($mobile);
      if (!$mobile || empty($mobile) || !$valid_mobile){
        return false;
      }
      $msg = str_replace("[OTP]", $otp_code, $this->sms_ultrafastsend);
      if (is_numeric(trim($msg))){
        $ParameterArray = array(array( "Parameter" => "OTP", "ParameterValue" => $otp_code));
        return $this->sms->ultraFastSend(array("ParameterArray" => $ParameterArray, "Mobile" => $valid_mobile, "TemplateId" => trim($msg)));
      }
      else{
        return $this->sms->send_normal_sms([$valid_mobile], $msg);
      }
    }
    public function check_verification_sms($user_id=0, $verification="")
    {
      $_otp_code = get_the_author_meta("_sms_otp_code", $user_id);
      $_otp_date = get_the_author_meta("_sms_otp_date", $user_id);
      $_otp_now  = $this->wp_date();
      if (!$user_id || !$verification || !$_otp_date || !$_otp_code) return false;
      $today  = strtotime($_otp_now);
      $expire = strtotime($_otp_date) + $this->sms_expiration;
      if($today >= $expire){
        // expired
      } else {
        if ($this->convert_to_english(trim($verification)) == $_otp_code){
          return true;
        }
        // else ~> expired
      }
      return false;
    }
    public function send_verification_email($email="")
    {
      $current_user = get_user_by('email', $email);
      $otp_code = $this->make_otp($current_user->ID, $this->verification_email_digits, "email");
      $replace = apply_filters("pepro_reglogin_verification_email_replacements", array(
        "[OTP]"           => $otp_code,
        "[request_email]" => $email,
        "[username]"      => $current_user->user_login,
        "[first_name]"    => $current_user->user_firstname,
        "[last_name]"     => $current_user->user_lastname,
        "[display_name]"  => $current_user->display_name,
        "[user_email]"    => $current_user->user_email,
        ));
      $email_content = apply_filters("pepro_reglogin_verification_email_template", $this->verification_email_template);
      foreach ($replace as $key => $value) {
        $email_content = str_replace($key, $value, $email_content);
      }
      $email_content = apply_filters("pepro_reglogin_send_verification_email_content", $email_content);
      $this->is_localhost() AND error_log("MAIL OTP: $otp_code");
      $mail = $this->send_mail($email, __("Verify Email","peprodev-ups")." [{$this->from_name}]", $email_content);
      return $this->is_localhost() ? true : $mail;
    }
    public function check_verification_email($user_id=0, $verification="")
    {
      $_otp_code = get_the_author_meta("_email_otp_code", $user_id);
      $_otp_date = get_the_author_meta("_email_otp_date", $user_id);
      $_otp_now  = $this->wp_date();
      if (!$user_id || !$verification || !$_otp_date || !$_otp_code) return false;
      $today  = strtotime($_otp_now);
      $expire = strtotime($_otp_date) + $this->email_expiration;
      if($today >= $expire){
        // expired
      }
      else {
        if (trim($this->convert_to_english($verification)) == $_otp_code){
          return true;
        }
        // else ~> expired
      }
      return false;
    }
    public function send_dummyuser_verification_sms($mobile="")
    {
      $otp_code = $this->make_dummyuser_otp($mobile, $this->verification_digits);
      $valid_mobile = $this->clean_mobile_number($mobile);
      if (!$mobile || empty($mobile) || !$valid_mobile){
        return false;
      }
      $msg = str_replace("[OTP]", $otp_code, $this->sms_ultrafastsend);
      if (is_numeric(trim($msg))){
        $ParameterArray = array(array( "Parameter" => "OTP", "ParameterValue" => $otp_code));
        return $this->sms->ultraFastSend(array("ParameterArray" => $ParameterArray, "Mobile" => $valid_mobile, "TemplateId" => trim($msg)));
      }
      else{
        return $this->sms->send_normal_sms([$valid_mobile], $msg);
      }
    }
    public function send_dummyuser_verification_email($email="")
    {
      $otp_code      = $this->make_dummyuser_otp($email, $this->verification_email_digits, "email");
      $replace       = apply_filters("pepro_reglogin_verification_email_replacements", array( "[OTP]" => $otp_code, "[request_email]" => $email,));
      $email_content = apply_filters("pepro_reglogin_verification_email_template", $this->verification_email_template);
      foreach ($replace as $key => $value) { $email_content = str_replace($key, $value, $email_content); }
      $email_content = apply_filters("pepro_reglogin_send_verification_email_content", $email_content);
      $this->is_localhost() AND error_log("MAIL OTP: $otp_code");
      $mail = $this->send_mail($email, __("Verify Email","peprodev-ups")." [{$this->from_name}]", $email_content);
      return $this->is_localhost() ? true : $mail;
    }
    public function check_dummyuser_otp_verification($user="", $verification="", $type="sms")
    {
      $_otp_code = get_option(__CLASS__."_{$type}_otp_{$user}_code","");
      $_otp_date = get_option(__CLASS__."_{$type}_otp_{$user}_date","");
      if (!$user || !$verification || !$_otp_date || !$_otp_code) return false;
      $_otp_now  = $this->wp_date();
      $today  = strtotime($_otp_now);
      $expiretime = "sms" === $type ? $this->sms_expiration : $this->email_expiration;
      $expiretime = apply_filters("pepro_reglogin_check_dummyuser_otp_expiration_time", $expiretime, $_otp_date);
      $expire = strtotime($_otp_date) + $expiretime;
      if($today >= $expire){
        // expired
      } else {
        if ($this->convert_to_english(trim($verification)) == $_otp_code){
          return true;
        }
        // else ~> expired
      }
      return false;
    }
    public function send_mail($email, $subject, $mail_body)
    {
      $headers = array(
        "Content-Type: text/html; charset=UTF-8",
        "From: $this->from_name <$this->from_address>",
      );
      return wp_mail( $email, $subject, $mail_body, $headers );
    }
    public function get_register_fields()
    {
      $json = wp_unslash(get_option("pepro-profile-register-fileds"));
      if (!empty($json)){
        $_array = json_decode($json, true);
        if (!$_array || empty($_array) || json_last_error() !== 0){
          return apply_filters("pepro_reglogin_get_register_fields", array());
        }else{
          $_array_new = array();
          foreach ($_array as $value) {
            if("select" == $value["type"]){
              $_opts = array();
              $opts  = explode("\n", $value["options"]);
              foreach ($opts as $opts_value) {
                $tempdata = explode(":", $opts_value);
                $_opts[trim($tempdata[0])] = trim($tempdata[1]);
              }
              $value["options"] = $_opts;
            }
            if("recaptcha" == $value["type"]){
              $value["is-required"] = "no";
              $value["is-editable"] = "no";
              $value["is-public"]   = "yes";
              $value["in-column"]   = "no";
              $value["skip"]        = "yes";
            }
            if("mobile" == $value["type"]){
              $value["type"] = "tel";
            }
            $value["meta_name"] = str_replace("-", "_", sanitize_title($value["meta_name"]));
            $_array_new[] = $value;
          }
          return apply_filters("pepro_reglogin_get_register_fields", $_array_new);
        }
      }
      return apply_filters("pepro_reglogin_get_register_fields", array());
    }
    public function get_login_fields()
    {
      // default login inputs
      $login_fields  = array(
        array(
          "meta_name"   => "username",
          "type"        => "text",
          "title"       => __("Username/Email","peprodev-ups"),
          "default"     => isset($_GET["username"]) && !empty($_GET["username"]) ? sanitize_text_field( esc_html( trim($_GET["username"]) ) ) : "",
          "is-required" => ($this->login_mobile_otp && $this->show_email_field ? "no" : "yes"),
          "is-public"   => "yes",
          "is-editable" => "no",
          "in-column"   => "no",
          "placeholder" => "",
          "classes"     => "",
          "attributes"  => "tabindex=1 autocapitalize=none size=20 data-error-text=\"".esc_attr__("Enter username or email address correctly", "peprodev-ups")."\" ",
        ),
        array(
          "meta_name"   => "password",
          "type"        => "password",
          "title"       => __("Password","peprodev-ups"),
          "placeholder" => "",
          "classes"     => "password-input",
          "attributes"  => "tabindex=2 autocomplete=off size=20 data-error-text=\"".esc_attr__("You have to enter a password", "peprodev-ups")."\"",
          "default"     => "",
          "is-required" => $this->login_mobile_otp ? "no" : "yes",
          "is-public"   => "yes",
          "is-editable" => "no",
          "in-column"   => "no"
        ));
      $login_mailotp = array(
        array(
          "meta_name"   => "username",
          "type"        => "text",
          "title"       => __("Username/Email","peprodev-ups"),
          "default"     => isset($_GET["username"]) && !empty($_GET["username"]) ? sanitize_text_field( esc_html( trim($_GET["username"]) ) ) : "",
          "is-required" => "yes",
          "is-public"   => "yes",
          "is-editable" => "no",
          "in-column"   => "no",
          "placeholder" => "",
          "classes"     => "",
          "attributes"  => "tabindex=1 autocapitalize=none size=20 data-error-text=\"".esc_attr__("Enter username or email address correctly", "peprodev-ups")."\" ",
        ),
        array(
          "meta_name"   => "verification",
          "type"        => "number",
          "title"       => __("Verification Code","peprodev-ups"),
          "row-class"   => "hide",
          "is-public"   => "yes",
          "is-required" => "no",
          "is-editable" => "no",
          "in-column"   => "no",
          "no-label"    => "no",
          "placeholder" => "",
          "classes"     => "code-verification force-ltr",
          "attributes"  => "autocomplete=off min=0 tabindex=2 data-error-text=\"".esc_attr__("You have to enter a Verification code or leave it empty and press Enter to receive a new one", "peprodev-ups")."\" pattern=".esc_attr("^\d{{$this->verification_email_digits}}$")." maxlength=$this->verification_email_digits minlength=$this->verification_email_digits",
          "default"     => "",
        ),
        array(
          "meta_name"   => "checkmailotp",
          "type"        => "hidden",
          "title"       => "",
          "row-class"   => "hide",
          "default"     => "1",
          "is-public"   => "yes",
          "is-required" => "no",
          "is-editable" => "no",
          "in-column"   => "no",
          "placeholder" => "",
          "classes"     => "",
          "attributes"  => "",
        ));
      $mobile_fields = array(
        array(
          "meta_name"   => "mobile",
          "type"        => "mobile",
          "title"       => __("Mobile","peprodev-ups"),
          "default"     => isset($_GET["username"]) && !empty($_GET["username"]) ? sanitize_text_field( esc_html( trim($_GET["username"]) ) ) : "",
          "is-required" => ($this->login_mobile_otp && $this->show_email_field ? "no" : "yes"),
          "is-public"   => "yes",
          "is-editable" => "no",
          "in-column"   => "no",
          "placeholder" => "",
          "classes"     => "mobile-verification force-ltr",
          "attributes"  => "tabindex=1 data-error-text=\"".esc_attr__("Enter mobile number with English numbers,<br>e.g. 09123456789", "peprodev-ups")."\" pattern=".esc_attr("^(\+98|0098|98|0)?9\d{9}$")." maxlength=14",
        ),
        array(
          "meta_name"   => "optverify",
          "type"        => "number",
          "title"       => __("OTP Code","peprodev-ups"),
          "row-class"   => "hide",
          "is-public"   => "yes",
          "is-required" => "no",
          "is-editable" => "no",
          "in-column"   => "no",
          "no-label"    => "no",
          "placeholder" => "",
          "classes"     => "otp-verification force-ltr",
          "attributes"  => "autocomplete=off min=0 tabindex=2 data-error-text=\"".esc_attr__("You have to enter an OTP code or leave it empty and press Enter to receive a new one", "peprodev-ups")."\" pattern=".esc_attr("^\d{{$this->verification_digits}}$")." maxlength=$this->verification_digits minlength=$this->verification_digits",
          "default"     => "",
        ),
        array(
          "meta_name"   => "checkmobile",
          "type"        => "hidden",
          "title"       => "",
          "row-class"   => "hide",
          "default"     => "1",
          "is-public"   => "yes",
          "is-required" => "no",
          "is-editable" => "no",
          "in-column"   => "no",
          "placeholder" => "",
          "classes"     => "",
          "attributes"  => "",
        ),
      );
      // default otp-login
      if ($this->login_mobile_otp && !$this->show_email_field){$login_fields = $mobile_fields;}
      if ($this->login_mobile_otp && $this->show_email_field){
        $login_fields = array_merge($login_fields, $mobile_fields);
      }
      if ($this->login_email_otp && $this->show_email_field){$login_fields = $login_mailotp;}

      $num = 2;
      // add reCaptcha
      foreach ($this->register_fileds as $field) {
        if ("recaptcha" == $field["type"] && "yes" == $field["login"]){
          $num++;
          array_push($login_fields, $field);
        }
      }

      $textSend                                                         = __("Login","peprodev-ups");
      $textVerify                                                       = __("Verify OTP Code","peprodev-ups");
      if ($this->login_mobile_otp){$textSend                            = __("Receive OTP Code", "peprodev-ups");}
      if ($this->login_email_otp){$textSend                             = __("Receive OTP Code", "peprodev-ups");}
      if ($this->login_mobile_otp && $this->show_email_field){$textSend = __("Login via OTP/Password","peprodev-ups");}
      $num++;
      array_push($login_fields, array(
          "meta_name"   => "submit",
          "type"        => "button",
          "btn-type"    => "submit",
          "title"       => $textSend,
          "classes"     => "button button-primary",
          "attributes"  => "tabindex=$num " . ($this->login_mobile_otp||$this->login_email_otp ? "data-send=\"".esc_attr($textSend)."\" data-verify=\"".esc_attr($textVerify)."\"" : ""),
          "is-required" => "yes",
          "no-label"    => "yes",
          "is-public"   => "yes",
        ));

      return apply_filters("pepro_reglogin_get_login_fields", $login_fields);
    }
    public function get_form_register_fields()
    {
      // default login inputs
      $login_fields = array();
      $num = 0;
      if (!$this->hide_username_field){
        $num++;
        array_push($login_fields,
          array(
            "title"       => __("Username","peprodev-ups"),
            "type"        => "text",
            "meta_name"   => "username",
            "classes"     => "",
            "attributes"  => "tabindex=$num data-error-text=\"".esc_attr__("Enter username correctly", "peprodev-ups")."\"",
            "is-required" => $this->is_username_field_req ? "yes" : "no",
            "is-public"   => "yes",
            "no-label"    => "no",
          )
        );
      }
      if (!$this->hide_email_field){
        $num++;
        array_push($login_fields,
          array(
            "title"       => __("Email","peprodev-ups"),
            "type"        => "email",
            "meta_name"   => "email",
            "classes"     => "email-verification force-ltr",
            "attributes"  => "tabindex=$num data-error-text=\"".esc_attr__("Enter email address correctly", "peprodev-ups")."\"",
            "is-required" => $this->is_email_field_req ? "yes" : "no",
            "is-public"   => "yes",
            "no-label"    => "no",
          ),
          array(
            "meta_name"   => "optverify",
            "type"        => "number",
            "title"       => __("OTP Code","peprodev-ups"),
            "row-class"   => "hide",
            "is-public"   => "yes",
            "is-required" => "no",
            "is-editable" => "no",
            "in-column"   => "no",
            "no-label"    => "no",
            "placeholder" => "",
            "classes"     => "otp-verification force-ltr",
            "attributes"  => "autocomplete=off min=0 tabindex=".($num+1)." data-error-text=\"".esc_attr__("You have to enter an OTP code or leave it empty and press Enter to receive a new one", "peprodev-ups")."\" pattern=".esc_attr("^\d{{$this->verification_email_digits}}$")." maxlength=$this->verification_email_digits minlength=$this->verification_email_digits",
            "default"     => "",
          ),
          array(
            "meta_name"   => "checkemail",
            "type"        => "hidden",
            "title"       => "",
            "row-class"   => "hide",
            "default"     => "1",
            "is-public"   => "yes",
            "is-required" => "no",
            "is-editable" => "no",
            "in-column"   => "no",
            "placeholder" => "",
            "classes"     => "",
            "attributes"  => "",
          )
        );
      }
      // default otp-login
      if ($this->login_mobile_otp){
        $num++;
        array_push($login_fields,
          array(
            "meta_name"   => "user_mobile",
            "type"        => "mobile",
            "title"       => __("Mobile","peprodev-ups"),
            "default"     => "",
            "is-required" => "yes",
            "is-public"   => "yes",
            "is-editable" => "no",
            "in-column"   => "no",
            "placeholder" => "",
            "classes"     => "mobile-verification force-ltr",
            "attributes"  => "tabindex=$num pattern=".esc_attr("^(\+98|0098|98|0)?9\d{9}$")." data-error-text=\"".esc_attr__("Enter mobile number with English numbers,<br>e.g. 09123456789", "peprodev-ups")."\" maxlength=14",
          ),
          array(
            "meta_name"   => "optverify",
            "type"        => "number",
            "title"       => __("OTP Code","peprodev-ups"),
            "row-class"   => "hide",
            "is-public"   => "yes",
            "is-required" => "no",
            "is-editable" => "no",
            "in-column"   => "no",
            "no-label"    => "no",
            "placeholder" => "",
            "classes"     => "otp-verification force-ltr",
            "attributes"  => "autocomplete=off min=0 tabindex=".($num+1)." data-error-text=\"".esc_attr__("You have to enter an OTP code or leave it empty and press Enter to receive a new one", "peprodev-ups")."\" pattern=".esc_attr("^\d{{$this->verification_digits}}$")." maxlength=$this->verification_digits minlength=$this->verification_digits",
            "default"     => "",
          ),
          array(
            "meta_name"   => "checkmobile",
            "type"        => "hidden",
            "title"       => "",
            "row-class"   => "hide",
            "default"     => "1",
            "is-public"   => "yes",
            "is-required" => "no",
            "is-editable" => "no",
            "in-column"   => "no",
            "placeholder" => "",
            "classes"     => "",
            "attributes"  => "",
          )
        );
        $num++;
      }
      else{
        if ($this->reg_add_mobile){
          $num++;
          array_push($login_fields, array(
              "meta_name"   => "user_mobile",
              "type"        => "mobile",
              "title"       => __("Mobile","peprodev-ups"),
              "default"     => "",
              "is-required" => $this->is_add_mobile_req ? "yes" : "no",
              "is-public"   => "yes",
              "is-editable" => "no",
              "in-column"   => "no",
              "placeholder" => "",
              "classes"     => "mobile-verification force-ltr",
              "attributes"  => "tabindex=$num pattern=".esc_attr("^(\+98|0098|98|0)?9\d{9}$")." data-error-text=\"".esc_attr__("Enter mobile number with English numbers,<br>e.g. 09123456789", "peprodev-ups")."\" maxlength=14",
            )
          );
        }
      }
      foreach ( (array) $this->register_fileds as $field) {
        if ("tel" == $field["type"] || "mobile" == $field["type"]){
          $field["classes"]    = $field["classes"] . " mobile-verification force-ltr";
          $field["attributes"] = " data-error-text=\"".esc_attr__("Enter mobile number with English numbers,<br>e.g. 09123456789", "peprodev-ups")."\" tabindex=$num pattern=".esc_attr("^(\+98|0098|98|0)?9\d{9}$")." maxlength=14";
        }
        if (($this->login_mobile_otp || $this->reg_add_mobile) && "user_mobile" == $field["meta_name"]) continue;
        if ("recaptcha" == $field["type"] && "yes" == $field["is-public"]) continue;

        $num++;
        $field["attributes"] = "tabindex=$num " . (isset($field["attributes"]) ? $field["attributes"] : "");
        array_push($login_fields, $field);
      }
      if ($this->show_password_field){
        $num++;
        array_push($login_fields,
        array(
          "title"       => __("Password","peprodev-ups"),
          "type"        => "password",
          "meta_name"   => "password1",
          "classes"     => "",
          "attributes"  => "tabindex=$num ",
          "is-required" => $this->is_password_field_req ? "yes" : "no",
          "is-public"   => "yes",
          "no-label"    => "no",
        ),
        array(
          "title"       => __("Confirm Password","peprodev-ups"),
          "type"        => "password",
          "meta_name"   => "password2",
          "classes"     => "",
          "attributes"  => "tabindex=". ($num+1),
          "is-required" => $this->is_password_field_req ? "yes" : "no",
          "is-public"   => "yes",
          "no-label"    => "no",
          )
        );
      }
      foreach ($this->register_fileds as $field) {
        if ("recaptcha" == $field["type"] && "yes" == $field["is-public"]){
          $num++;
          $field["attributes"] = "tabindex=$num " . (isset($field["attributes"]) ? $field["attributes"] : "");
          array_push($login_fields, $field);
        }
      }
      $textSend   = __("Receive OTP & Register","peprodev-ups");
      $textVerify = __("Verify OTP & Register","peprodev-ups");
      $num++;
      array_push($login_fields, array(
          "meta_name"   => "submit",
          "type"        => "button",
          "btn-type"    => "submit",
          "title"       => $this->login_mobile_otp ? $textSend : __("Register","peprodev-ups"),
          "classes"     => "button button-primary",
          "attributes"  => "tabindex=$num data-send=\"".esc_attr($textSend)."\" data-verify=\"".esc_attr($textVerify)."\"",
          "is-required" => "yes",
          "no-label"    => "yes",
          "is-public"   => "yes",
        ));
      return apply_filters("pepro_reglogin_get_form_register_fields", $login_fields);
    }
    public function printout_fields($config)
    {
      ob_start();
      $config = wp_parse_args($config, array(
        "style"           => "table",
        "row_class"       => "",
        "item_class"      => "regular-text",
        "loop_fields"     => $this->register_fileds,
        "echo"            => false,
        "skip_not_public" => false,
        "label_class"     => "",
        "user_id"         => false,
        "skip_recaptcha"  => false,
        "isourprofile"    => false,
        "skip_profile"    => false,
        ));
      extract($config);
      $printr = "table" === $style;
      foreach ( $loop_fields as $field) {
        if ($skip_not_public && "yes" !== $field["is-public"]){ continue; }
        if ($skip_profile && "yes" !== $field["is-editable"]){ continue; }
        if ($isourprofile && "user_mobile" == $field["meta_name"]){ continue; }
        if ($skip_recaptcha && "recaptcha" == $field["type"]){ continue; }
        if ($user_id){ $field["default"] = get_the_author_meta($field["meta_name"], $user_id); }
        $no_label = isset($field["no-label"]) && "yes" == $field["no-label"];

        $row_class = $config["row_class"];
        $row_class .= isset($field["row-class"]) ? " {$field["row-class"]} " : "";

        if (empty(trim($field["title"]))){ $no_label = true; }
        if ("recaptcha" == $field["type"]){ $no_label = true; }
        if ("mobile" == $field["type"]){ $field["type"] = "tel"; }

        $field = wp_parse_args($field, array(
          "meta_name"    => "sample_meta",
          "type"         => "test",
          "is-required"  => "no",
          "is-editable"  => "no",
          "is-public"    => "no",
          "no-label"     => "no",
          "in-column"    => "no",
          "btn-type"     => "",
          "title"        => "",
          "placeholder"  => "",
          "classes"      => "",
          "attributes"   => "",
          "site-key"     => "",
          "secret-key"   => "",
          "size"         => "",
          "theme"        => "",
          "options"      => "",
          "default"      => "",
          "login"        => "",
          "verification" => "",
        ));


        switch ($field["type"]) {

          case 'select':
            $select_options = "";
            foreach ((array) $field["options"] as $key => $value) {
              $selected = selected( trim($field["default"]), trim($key), false);
              $select_options .= "<option $selected value='".esc_attr(trim($key))."'>".esc_attr(trim($value))."</option>";
            }
            echo "<".($printr?"tr":"{$style}")." class='{$row_class} {$field["meta_name"]}-wrap ".("yes" == $field["is-required"]?"form-required":"")."'>
                   ".($printr?"<th>":"").($no_label?"":"<label class=\"".esc_attr($label_class)."\" for=\"{$field["meta_name"]}\">".esc_html( $field["title"] )."</label>").($printr?"</th>":"")."
                   ".($printr?"<td>":"")."<select autocomplete='off' type=\"".esc_attr( $field["type"] )."\"
                                              class=\"$item_class ".esc_attr( $field["classes"] )."\"
                                              id=\"".esc_attr( $field["meta_name"] )."\"
                                              name=\"".esc_attr( $field["meta_name"] )."\"
                                              ".esc_attr( "yes" == $field["is-required"] ? "required" : "" )."
                                              ".$field["attributes"].">
                                            $select_options
                                           </select>".
                    ($printr?"</td>":"").
                    ($printr?"</tr>":"</{$style}>");
          break;

          case 'wc_country':
            global $woocommerce;
            echo "<".($printr?"tr":"{$style}")." class='{$row_class} {$field["meta_name"]}-wrap ".("yes" == $field["is-required"]?"form-required":"")."'>
                   ".($printr?"<th>":"").($no_label?"":"<label class=\"".esc_attr($label_class)."\" for=\"{$field["meta_name"]}\">".esc_html( $field["title"] )."</label>").($printr?"</th>":"")."
                   ".($printr?"<td>":"");
            woocommerce_form_field( 'billing_country', array( 'type' => 'country' ) );
            echo ($printr?"</td>":""). ($printr?"</tr>":"</{$style}>");
          break;

          case 'wc_state':
            global $woocommerce;
            echo "<".($printr?"tr":"{$style}")." class='{$row_class} {$field["meta_name"]}-wrap ".("yes" == $field["is-required"]?"form-required":"")."'>
                   ".($printr?"<th>":"").($no_label?"":"<label class=\"".esc_attr($label_class)."\" for=\"{$field["meta_name"]}\">".esc_html( $field["title"] )."</label>").($printr?"</th>":"")."
                   ".($printr?"<td>":"");
            woocommerce_form_field( 'billing_state', array( 'type' => 'state' ) );
            echo ($printr?"</td>":""). ($printr?"</tr>":"</{$style}>");
          break;

          case 'textarea':
            echo "<".($printr?"tr":"{$style}")." class='{$row_class} {$field["meta_name"]}-wrap ".("yes" == $field["is-required"]?"form-required":"")."'>
                   ".($printr?"<th>":"").($no_label?"":"<label class=\"".esc_attr($label_class)."\" for=\"{$field["meta_name"]}\">".esc_html( $field["title"] )."</label>").($printr?"</th>":"")."
                   ".($printr?"<td>":"")."<textarea autocomplete='off' type=\"".esc_attr( $field["type"] )."\"
                                           placeholder=\"".esc_attr( $field["placeholder"] )."\"
                                           class=\"$item_class ".esc_attr( $field["classes"] )."\"
                                           id=\"".esc_attr( $field["meta_name"] )."\"
                                           name=\"".esc_attr( $field["meta_name"] )."\"
                                           ".esc_attr( "yes" == $field["is-required"] ? "required" : "" )."
                                           ".$field["attributes"].">".esc_attr( $field["default"] )."</textarea>".
                    ($printr?"</td>":"").
                    ($printr?"</tr>":"</{$style}>");
          break;

          case 'editor':
            echo "<".($printr?"tr":"{$style}")." class='{$row_class} {$field["meta_name"]}-wrap ".("yes" == $field["is-required"]?"form-required":"")."'>
                   ".($printr?"<th>":"").($no_label?"":"<label class=\"".esc_attr($label_class)."\" for=\"{$field["meta_name"]}\">".esc_html( $field["title"] )."</label>").($printr?"</th>":"")."
                   ".($printr?"<td>":"");
                   wp_editor($field["default"], strtolower(str_replace(array('-', '_', ' ', '*'), '', $field["meta_name"])), array(
                     'editor_height' => 150,
                     'media_buttons' => false,
                     'teeny'         => true,
                     'tinymce'       => array( 'toolbar1' => 'bold,italic,underline,separator,bullist,numlist,separator,undo,redo,fullscreen',),
                     'quicktags'     => false,
                     'editor_class'  => "",
                     'textarea_name' => $field["meta_name"],
                    ));
            echo ($printr?"</td>":"").($printr?"</tr>":"</{$style}>");
          break;

          case 'recaptcha':
            $recap_id = uniqid("pepro--recaptcha-");
            echo "<".($printr?"tr":"{$style}")." class='{$row_class} {$field["meta_name"]}-wrap ".("yes" == $field["is-required"]?"form-required":"")."' data-recaptcha=\"{$recap_id}\" >
                   ".($printr?"<th>":"").($no_label?"":"<label class=\"".esc_attr($label_class)."\" for=\"{$field["meta_name"]}\">".esc_html( $field["title"] )."</label>").($printr?"</th>":"")."
                   ".($printr?"<td>":"")."<div class='g-recaptcha' id=\"{$recap_id}\" data-theme=\"".esc_attr($field["theme"])."\" data-size=\"".esc_attr($field["size"])."\" data-sitekey=\"".esc_attr($field["site-key"])."\"></div>".($printr?"</td>":"").($printr?"</tr>":"</{$style}>");
          break;

          case 'button':
            echo "<".($printr?"tr":"{$style}")." class='{$row_class} {$field["meta_name"]}-wrap ".("yes" == $field["is-required"]?"form-required":"")."'>
                    <button type=\"".esc_attr( $field["btn-type"] )."\" class=\"".esc_attr( $field["classes"] )."\" id=\"".esc_attr( $field["meta_name"] )."\" ".$field["attributes"]." >".esc_html( $field["title"] )."</button>".
                    ($printr?"</tr>":"</{$style}>");
          break;

          default:
            echo "<".($printr?"tr":"{$style}")." class='{$row_class} {$field["meta_name"]}-wrap ".("yes" == $field["is-required"]?"form-required":"")."'>
                   ".($printr?"<th>":"").($no_label?"":"<label class=\"".esc_attr($label_class)."\" for=\"{$field["meta_name"]}\">".esc_html( $field["title"] )."</label>").($printr?"</th>":"")."
                   ".($printr?"<td>":"")."<input type=\"".esc_attr( $field["type"] )."\"
                                           placeholder=\"".esc_attr( $field["placeholder"] )."\"
                                           value=\"".esc_attr( $field["default"] )."\"
                                           class=\"$item_class ".esc_attr( $field["classes"] )."\"
                                           id=\"".esc_attr( $field["meta_name"] )."\"
                                           name=\"".esc_attr( $field["meta_name"] )."\"
                                           ".esc_attr( "yes" == $field["is-required"] ? "required" : "" )."
                                           ".$field["attributes"]." />".
                    ($printr?"</td>":"").
                    ($printr?"</tr>":"</{$style}>");
          break;

        }
      }
      $content = ob_get_contents();
      $content = apply_filters("pepro_reglogin_printout_fields", $content, $config, $loop_fields);
      ob_end_clean();
      if ($echo){ echo $content; return; }
      return $content;
    }
    public function get_verify_email_fields($class="button button-primary", $force=false)
    {
      // default login inputs
      $current_user = wp_get_current_user();
      $login_fields = array(
        array(
          "meta_name"   => "username",
          "type"        => "email",
          "title"       => __("Email","peprodev-ups"),
          "default"     => (string) $current_user->user_email,
          "is-required" => "yes",
          "is-public"   => "yes",
          "is-editable" => "no",
          "in-column"   => "no",
          "placeholder" => "",
          "classes"     => "email-verification force-ltr",
          "attributes"  => "tabindex=1 data-error-text=\"".esc_attr__("Enter username or email address correctly", "peprodev-ups")."\" ",
        ),
        array(
          "meta_name"   => "verification",
          "type"        => "number",
          "title"       => __("Verification Code","peprodev-ups"),
          "row-class"   => "hide",
          "is-public"   => "yes",
          "is-required" => "no",
          "is-editable" => "no",
          "in-column"   => "no",
          "no-label"    => "no",
          "placeholder" => "",
          "classes"     => "code-verification force-ltr",
          "attributes"  => "autocomplete=off min=0 tabindex=2 data-error-text=\"".esc_attr__("You have to enter a Verification code or leave it empty and press Enter to receive a new one", "peprodev-ups")."\" pattern=".esc_attr("^\d{{$this->verification_email_digits}}$")." maxlength=$this->verification_email_digits minlength=$this->verification_email_digits",
          "default"     => "",
        ),
      );

      // add reCaptcha
      foreach ($this->register_fileds as $value) {
        if ("recaptcha" == $value["type"] && "yes" == $value["verification"]){
          array_push($login_fields, $value);
        }
      }

      $textSend   = __("Receive Code","peprodev-ups");
      $textVerify = __("Verify Code","peprodev-ups");

      array_push($login_fields, array(
          "meta_name"   => "submit",
          "type"        => "button",
          "btn-type"    => "submit",
          "title"       => $textSend,
          "classes"     => $class,
          "attributes"  => "tabindex=3 data-send=\"".esc_attr($textSend)."\" data-verify=\"".esc_attr($textVerify)."\"",
          "is-required" => "yes",
          "no-label"    => "yes",
          "is-public"   => "yes",
        ));

      return apply_filters("pepro_reglogin_get_verify_email_fields", $login_fields);
    }
    public function get_form_resetpass_fields()
    {
      // default login inputs
      $login_fields = array();
      $num = 0;
      $num++;
      $login_fields = array(
        array(
          "title"       => __("Username/Email","peprodev-ups"),
          "type"        => "text",
          "meta_name"   => "username",
          "classes"     => "email-verification force-ltr",
          "attributes"  => "tabindex=$num data-error-text=\"".esc_attr__("Enter email address correctly", "peprodev-ups")."\"",
          "is-required" => "yes",
          "is-public"   => "yes",
          "no-label"    => "no",
        ),
        array(
          "title"       => __("New Password","peprodev-ups"),
          "type"        => "password",
          "meta_name"   => "password1",
          "classes"     => "",
          "attributes"  => "tabindex=".($num+2)." ",
          "is-required" => "yes",
          "is-public"   => "yes",
          "no-label"    => "no",
        ),
        array(
          "title"       => __("Confirm New Password","peprodev-ups"),
          "type"        => "password",
          "meta_name"   => "password2",
          "classes"     => "",
          "attributes"  => "tabindex=". ($num+3),
          "is-required" => "yes",
          "is-public"   => "yes",
          "no-label"    => "no",
        ),
        array(
          "meta_name"   => "optverify",
          "type"        => "number",
          "title"       => __("Email OTP Verification","peprodev-ups"),
          "row-class"   => "hide",
          "is-public"   => "yes",
          "is-required" => "no",
          "is-editable" => "no",
          "in-column"   => "no",
          "no-label"    => "no",
          "placeholder" => "",
          "classes"     => "otp-verification force-ltr",
          "attributes"  => "autocomplete=off min=0 tabindex=".($num+1)." data-error-text=\"".esc_attr__("You have to enter an OTP code or leave it empty and press Enter to receive a new one", "peprodev-ups")."\" pattern=".esc_attr("^\d{{$this->verification_email_digits}}$")." maxlength=$this->verification_email_digits minlength=$this->verification_email_digits",
          "default"     => "",
        ),
        array(
          "meta_name"   => "checkemail",
          "type"        => "hidden",
          "title"       => "",
          "row-class"   => "hide",
          "default"     => "1",
          "is-public"   => "yes",
          "is-required" => "no",
          "is-editable" => "no",
          "in-column"   => "no",
          "placeholder" => "",
          "classes"     => "",
          "attributes"  => "",
        )
      );
      $num += 3;
      // add reCaptcha
      foreach ($this->register_fileds as $value) {
        if ("recaptcha" == $value["type"] && "yes" == $value["verification"]){
          array_push($login_fields, $value);
        }
      }
      $textSend   = __("Receive OTP & Reset Password","peprodev-ups");
      $textVerify = __("Verify OTP & Reset Password","peprodev-ups");
      $num++;
      array_push($login_fields, array(
          "meta_name"   => "submit",
          "type"        => "button",
          "btn-type"    => "submit",
          "title"       => $textSend,
          "classes"     => "button button-primary",
          "attributes"  => "tabindex=$num data-send=\"".esc_attr($textSend)."\" data-verify=\"".esc_attr($textVerify)."\"",
          "is-required" => "yes",
          "no-label"    => "yes",
          "is-public"   => "yes",
        ));
      return apply_filters("pepro_reglogin_get_form_resetpass_fields", $login_fields);
    }
    public function get_verify_mobile_fields($class="button button-primary", $force=false)
    {
      $current_user = wp_get_current_user();
      $login_fields = array(
        array(
          "meta_name"   => "username",
          "type"        => "mobile",
          "title"       => __("Mobile","peprodev-ups"),
          "default"     => get_the_author_meta("user_mobile", $current_user->ID),
          "is-required" => "yes",
          "is-public"   => "yes",
          "is-editable" => "no",
          "in-column"   => "no",
          "placeholder" => "",
          "classes"     => "mobile-verification force-ltr",
          "attributes"  => "tabindex=1 autocomplete=off data-error-text=\"".esc_attr__("Enter mobile number with English numbers,<br>e.g. 09123456789", "peprodev-ups")."\" pattern=".esc_attr("^(\+98|0098|98|0)?9\d{9}$")." maxlength=14",
        ),
        array(
          "meta_name"   => "verification",
          "type"        => "number",
          "title"       => __("OTP Code","peprodev-ups"),
          "row-class"   => "hide",
          "is-public"   => "yes",
          "is-required" => "no",
          "is-editable" => "no",
          "in-column"   => "no",
          "no-label"    => "no",
          "placeholder" => "",
          "classes"     => "code-verification force-ltr",
          "attributes"  => "autocomplete=off min=0 tabindex=2 data-error-text=\"".esc_attr__("You have to enter an OTP code or leave it empty and press Enter to receive a new one", "peprodev-ups")."\" tabindex=2 pattern=".esc_attr("^\d{{$this->verification_digits}}$")." maxlength=$this->verification_digits minlength=$this->verification_digits",
          "default"     => "",
        ),
        array(
          "meta_name"   => "checkemobile",
          "type"        => "hidden",
          "title"       => "",
          "row-class"   => "hide",
          "default"     => "1",
          "is-public"   => "yes",
          "is-required" => "no",
          "is-editable" => "no",
          "in-column"   => "no",
          "placeholder" => "",
          "classes"     => "",
          "attributes"  => "",
        ),
      );
      $num = 2;
      // add reCaptcha
      foreach ($this->register_fileds as $value) {
        if ("recaptcha" == $value["type"] && "yes" == $value["login"]){
          array_push($login_fields, $value);
        }
      }

      $textSend   = __("Receive OTP Code","peprodev-ups");
      $textVerify = __("Verify OTP Code","peprodev-ups");

      array_push($login_fields, array(
          "meta_name"   => "submit",
          "type"        => "button",
          "btn-type"    => "submit",
          "title"       => $this->login_mobile_otp||$force ? $textSend : __("Login","peprodev-ups"),
          "classes"     => $class,
          "attributes"  => "tabindex=".($num+1)." " . ($this->login_mobile_otp||$force ? "data-send=\"".esc_attr($textSend)."\" data-verify=\"".esc_attr($textVerify)."\"" : ""),
          "is-required" => "yes",
          "no-label"    => "yes",
          "is-public"   => "yes",
        ));

      return apply_filters("pepro_reglogin_get_verify_mobile_fields", $login_fields);
    }
    public function redirect_after_login_register($redirect_to="", $requested_redirect_to="", $user=0)
    {
      foreach ((array) $this->get_redirection_fields() as $key => $value) {
        if (("ajax_login" === $requested_redirect_to || "login_redirect" == current_action()) && "yes" == $value["login"] ){
          if ("everyone" == $value["role"]){
            $redirect_to = $this->parse_redirection_url($value["url"]);
            return $redirect_to;
          }else{
            if (is_a($user, 'WP_User' ) && $user->exists()){
              if (in_array($value["role"], $user->roles)) {
                $redirect_to = $this->parse_redirection_url($value["url"]);
                return $redirect_to;
              }
            }
          }
        }
        if ("ajax_text" === $requested_redirect_to && "yes" == $value["login"] ){
          if ("everyone" == $value["role"]){
            return isset($value["text"]) ? $value["text"] : false;
          }else{
            if (is_a($user, 'WP_User' ) && $user->exists()){
              if (in_array($value["role"], $user->roles)) {
                return isset($value["text"]) ? $value["text"] : false;
              }
            }
          }
        }
        if (("ajax_register" === $requested_redirect_to || "registration_redirect" == current_action()) && "yes" == $value["register"] ){
          if ("everyone" == $value["role"]){
            $redirect_to = $this->parse_redirection_url(trim($value["url"]));
            return $redirect_to;
          }else{
            $user = wp_get_current_user();
            if (in_array($value["role"], $user->roles)) {
              $redirect_to = $this->parse_redirection_url(trim($value["url"]));
              return $redirect_to;
            }
          }
        }
      }
      return $redirect_to;
    }
    public function redirect_after_logout($user_id=0, $return=false)
    {
      $redirect_to = home_url();
      $user_id = get_current_user_id();
      foreach ((array) $this->get_redirection_fields() as $key => $value) {
        if ("everyone" == $value["role"] && "yes" == $value["logout"]){
          $redirect_to = $this->parse_redirection_url($value["url"]);
          if ($return){ return $redirect_to; }
          wp_safe_redirect($redirect_to);
          exit();
        }
        else{
          if ("yes" == $value["logout"]){
            $user = new \WP_User($user_id);
            if (is_a($user, 'WP_User' ) && $user->exists()){
              if (in_array($value["role"], $user->roles) || "everyone" == $value["role"] ) {
                $redirect_to = $this->parse_redirection_url($value["url"]);
                if ($return){ return $redirect_to; }
                wp_safe_redirect($redirect_to);
                exit();
              }
            }
          }
        }
      }
      wp_safe_redirect(home_url());
      exit();
    }
    public function logout_redirect($redirect_to, $requested_redirect_to, $user)
    {
      foreach ((array) $this->get_redirection_fields() as $key => $value) {
        if ("everyone" == $value["role"] && "yes" == $value["logout"]){
          $redirect_to = $this->parse_redirection_url($value["url"]);
          return $redirect_to;
        }
        else{
          if ("yes" == $value["logout"]){
            $user = new \WP_User($user);
            if (is_a($user, 'WP_User' ) && $user->exists()){
              if (in_array($value["role"], $user->roles) || "everyone" == $value["role"] ) {
                $redirect_to = $this->parse_redirection_url($value["url"]);
                return $redirect_to;
              }
            }
          }
        }
      }
      return home_url();
    }
    public function login_form_logout($user_id=0, $return=false)
    {
      $redirect_to = home_url();
      $user_id = get_current_user_id();
      wp_logout();
      foreach ((array) $this->get_redirection_fields() as $key => $value) {
        if ("everyone" == $value["role"] && "yes" == $value["logout"]){
          $redirect_to = $this->parse_redirection_url($value["url"]);
          if ($return){ return $redirect_to; }
          wp_safe_redirect($redirect_to);
          exit();
        }
        else{
          if ("yes" == $value["logout"]){
            $user = new \WP_User($user_id);
            if (is_a($user, 'WP_User' ) && $user->exists()){
              if (in_array($value["role"], $user->roles) || "everyone" == $value["role"] ) {
                $redirect_to = $this->parse_redirection_url($value["url"]);
                if ($return){ return $redirect_to; }
                wp_safe_redirect($redirect_to);
                exit();
              }
            }
          }
        }
      }
      wp_safe_redirect(home_url());
      exit();
    }
    public function parse_redirection_url($url="")
    {
      #page_id / @page_slug / {special_pages} / Full URL
      $url = trim($url);
      if ($this->startsWith($url, "#")){
        return get_permalink(ltrim($url, "#"));
      }
      if ($this->startsWith($url, "@")){
        $slug = ltrim($url, "@");
        return home_url("/$slug");
      }
      if ($this->startsWith($url, "{") && $this->endsWith($url, "}")){
        return $this->special_pages_to_url(rtrim(ltrim($url, "{"), "}"));
      }
      return esc_url($url);
    }
    public function startsWith ($string, $startString)
    {
      $len = strlen($startString);
      return (substr($string, 0, $len) === $startString);
    }
    public function endsWith($string, $endString)
    {
      $len = strlen($endString);
      if ($len == 0) {
        return true;
      }
      return (substr($string, -$len) === $endString);
    }
    public function manage_users_columns( $user_columns )
    {
      $user_columns['_email_verification'] = _x("Email Verified", "metabox", "peprodev-ups");
      $user_columns['_sms_verification']   = _x("SMS Verified", "metabox", "peprodev-ups");
      foreach ($this->get_register_fields() as $field) {
        if ("yes" == $field["in-column"]){
          $user_columns[$field["meta_name"]] = $field["title"];
        }
      }
      return $user_columns;
    }
    public function manage_users_custom_column( $value, $column_name, $user_id )
    {
      switch ($column_name) {
        case '_email_verification':
          ob_start();
          $verfied = "yes" == get_the_author_meta( "pepro_user_is_email_verified", $user_id);
          ?>
            <div class="pepro-reg-login-checkbox-wrapper">
              <input class="pepro-reg-login-checkbox edit-user" style="transform: scale(0.75);" data-id="<?php echo $user_id;?>" data-param="pepro_user_is_email_verified" data-nonce="<?php echo esc_attr(wp_create_nonce("peprodev-ups"));?>" type="checkbox" autocomplete="off" value="yes" <?php echo checked(true, $verfied, false);?> >
            </div>
          <?php
          $return = ob_get_contents();
          ob_end_clean(); return $return;
        break;
        case '_sms_verification':
          ob_start();
          $mobile = get_the_author_meta( "user_mobile", $user_id);
          $verfied = "yes" == get_the_author_meta( "pepro_user_is_sms_verified", $user_id);
          ?>
            <div class="pepro-reg-login-checkbox-wrapper">
              <?php echo $mobile;?>
              <input class="pepro-reg-login-checkbox edit-user" style="transform: scale(0.75);" data-id="<?php echo $user_id;?>" data-param="pepro_user_is_sms_verified" data-nonce="<?php echo esc_attr(wp_create_nonce("peprodev-ups"));?>" type="checkbox" autocomplete="off" value="yes" <?php echo checked(true, $verfied, false);?> >
            </div>
          <?php
          $return = ob_get_contents();
          ob_end_clean(); return $return;
        break;
      }
      foreach ($this->get_register_fields() as $field) {
        if (isset($field["in-column"]) && "yes" == $field["in-column"] && $field["meta_name"] == $column_name){
          $value = get_the_author_meta($field["meta_name"], $user_id);
          if ("select" == $field["type"]){
            $value = $field["options"][$value] ?? $value;
          }
          if ("textarea" == $field["type"] || "editor" == $field["type"]){
            $value = empty($value) ? "" : "<div id='{$field["meta_name"]}__{$user_id}' style='display:none;'>$value</div><a class='thickbox' title='{$field["title"]}' href='#TB_inline?width=900&height=600&inlineId={$field["meta_name"]}__{$user_id}'>".__("Read value","peprodev-ups")."</a>";
          }
          return $value;
        }
      }
      return $value;
    }
    public function admin_enqueue_scripts($hook)
    {
      $screen = get_current_screen();
      if ("users.php" == $hook){
        add_thickbox();
        wp_enqueue_style("pepro_users_reg_login", "{$this->assets_url}/assets/backend-admin-edit.css", array(), $this->current_version);
        wp_register_script("pepro_users_reg_login", "{$this->assets_url}/assets/backend-admin-edit.js", array("jquery"), $this->current_version);
        wp_localize_script( "pepro_users_reg_login", "pepr_reg_login", array(
          "_td"     => $this->td,
          "_ajax"   => admin_url("admin-ajax.php"),
          "loading" => _x("Please wait ...", "js-translate", "peprodev-ups"),
          "success" => _x("Operation done successfully", "wc-setting-js", "peprodev-ups"),
          "error"   => _x("An unknown error occured", "js-translate", "peprodev-ups"),
          "fixerr" => _x("Plese fix errors in form", "js-translate", "peprodev-ups"),
        ));
        wp_enqueue_script("pepro_users_reg_login");
      }
    }
    public function show_profile_custom_fields( $user )
    {
      $fields = $this->printout_fields(array(
        "item_class"   => "regular-text",
        "skip_profile" => true,
        "user_id"      => $user->ID,
      ));
      if (!empty($fields)){
        echo "<h3>".__("Personal Information","peprodev-ups")."</h3><table class='form-table'>$fields</table>";
      }
    }
    public function update_profile_custom_fields( $user_id )
    {
      if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
      foreach ($this->get_register_fields() as $field) {
        if (isset($_POST[$field["meta_name"]])){
          $meta_value = sanitize_post( trim($_POST[$field["meta_name"]]));
          if ("mobile" == $field["type"] || "tel" == $field["type"]){
            $valid_mobile = $this->clean_mobile_number($meta_value, $field["meta_name"]);
            if(false != $valid_mobile){
              $found_prev_user = (array) $this->get_users_by_mobile($valid_mobile);
              if (!empty($found_prev_user)){
                if (count($found_prev_user) == 1 && in_array((string) $user_id, $found_prev_user) ){
                  update_user_meta( $user_id, $field["meta_name"], $valid_mobile);
                }
              }else{
                update_user_meta( $user_id, $field["meta_name"], $valid_mobile);
              }
            }

            if ("yes" != $field["is-required"]){
              update_user_meta( $user_id, $field["meta_name"], $valid_mobile?$valid_mobile:"");
            }

          }else{
            update_user_meta( $user_id, $field["meta_name"], $meta_value);
          }
        }
      }
    }
    public function error_log_dump($value="", $extas="")
    {
      ob_start();
      var_dump($value);
      $htmloutput = ob_get_contents();
      ob_end_clean();
      error_log("$extas ~> $htmloutput");
    }
    public function user_register( $user_id )
    {
      foreach ($this->get_register_fields() as $field) {
        if (isset($_POST[$field["meta_name"]])){
          $meta_value = sanitize_post( trim($_POST[$field["meta_name"]]));
          if ("mobile" == $field["type"] || "tel" == $field["type"]){
            $valid_mobile = $this->clean_mobile_number($meta_value, $field["meta_name"]);
            if(false != $valid_mobile){
              $found_prev_user = (array) $this->get_users_by_mobile($valid_mobile);
              if (!empty($found_prev_user)){
                if (count($found_prev_user) == 1 && in_array((string) $user_id, $found_prev_user) ){
                  update_user_meta( $user_id, $field["meta_name"], $valid_mobile);
                }
              }else{
                update_user_meta( $user_id, $field["meta_name"], $valid_mobile);
              }
            }
          }else{
            update_user_meta( $user_id, $field["meta_name"], $meta_value);
          }
        }
      }

      if ("user_register" == current_action()){
        global $_POST;
        if ($this->show_password_field && isset($_POST['password1'])) {
          wp_set_password( $_POST['password1'], $user_id ); //Password previously checked in add_filter > registration_errors
        }
        if ($this->auto_login_after_reg && (isset($_POST['peprologinregisterform']) && "yes" == $_POST['peprologinregisterform']) ) {
            wp_clear_auth_cookie();
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            if (isset($_POST['redirect_to']) && !empty($_POST['redirect_to'])) {
              $redirect = $_POST['redirect_to'];
            } else {
              $redirect = $this->redirect_after_login_register();
            }
            // This does the redirection if we are on default registration page. If we are on any other page, then do not redirect. This fixes WooCommerce bug.
            if (isset($_POST['wp-submit']) && $_POST['wp-submit'] == "Register") {
              wp_redirect($redirect);
              exit;
            }
            else {
              // do nothing and SKIP REDIRECTION (fixes WooCommerce bug)
            }
        }
      }
    }
    public function login_form_register ()
    {
      if ($this->hide_username_field || $this->hide_email_field){
        $_POST['user_login'] = uniqid("user-");
      }

      if ($this->use_email_as_username){
        if(isset($_POST['user_email']) && !empty($_POST['user_email'])){
          $user = get_user_by('email', sanitize_email( $_POST['user_email'] ) );
          if (!$user){
            $_POST['user_login'] = sanitize_email( $_POST['user_email'] );
          }
        }
      }

      if ($this->use_mobile_as_username){
        if(isset($_POST['user_mobile']) && !empty($_POST['user_mobile'])){
          $_POST['user_login'] = $this->clean_mobile_number($_POST['user_mobile']);
        }
      }

    }
    public function registration_errors( $errors, $sanitized_user_login, $user_email)
    {
      $return_array = false;
      $error_array  = array();
      foreach ($this->get_register_fields() as $field) {
        switch ($field["type"]) {
          case 'recaptcha':
            if(!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])){
              if ($return_array){
                $error_array["pepro_dev_login_{$field["meta_name"]}"] = _x("<strong>Error:</strong> Please check the reCAPTCHA challenge.", "reg-form-error", "peprodev-ups");
              }else{
                $errors->add("pepro_dev_login_{$field["meta_name"]}", _x("<strong>Error:</strong> Please check the reCAPTCHA challenge.", "reg-form-error", "peprodev-ups"));
              }
            }
          break;
          case 'tel':
          case 'mobile':
            if ("yes" == $field["is-required"] && empty(trim($_POST[$field["meta_name"]])) ){
              if ($return_array){
                $error_array["pepro_dev_login_{$field["meta_name"]}"] = sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", "peprodev-ups"), $field["title"]) ;
              }else{
                $errors->add("pepro_dev_login_{$field["meta_name"]}", sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", "peprodev-ups"), $field["title"]) );
              }
            }

            $valid_mobile = $this->clean_mobile_number($_POST[$field["meta_name"]], $field["meta_name"]);
            if(!$valid_mobile){
              if ($return_array){
                $error_array["pepro_dev_login_{$field["meta_name"]}"] = _x("<strong>Error:</strong> Please enter a valid mobile number.", "reg-form-error", "peprodev-ups");
              }else{
                $errors->add("pepro_dev_login_{$field["meta_name"]}", _x("<strong>Error:</strong> Please enter a valid mobile number.", "reg-form-error", "peprodev-ups"));
              }
            }
            else{
              $found_prev_user = $this->get_user_by_mobile($valid_mobile);
              if ($found_prev_user && $found_prev_user > 0){
                if ($return_array){
                  $error_array["pepro_dev_login_{$field["meta_name"]}-duplicate"] = _x("<strong>Error:</strong> This mobile number is currently in use.", "reg-form-error", "peprodev-ups");
                }else{
                  $errors->add("pepro_dev_login_{$field["meta_name"]}-duplicate", _x("<strong>Error:</strong> This mobile number is currently in use.", "reg-form-error", "peprodev-ups"));
                }
              }
            }
          break;
          default:
            if ("yes" == $field["is-required"] && empty(trim($_POST[$field["meta_name"]])) ){
              if ($return_array){
                $error_array["pepro_dev_login_{$field["meta_name"]}"] = sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", "peprodev-ups"), $field["title"]);
              }else{
                $errors->add("pepro_dev_login_{$field["meta_name"]}", sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", "peprodev-ups"), $field["title"]) );
              }
            }
          break;
        }
      }
      if ($this->hide_username_field){
        if(isset($errors->errors['empty_username'])){
          unset($errors->errors['empty_username']);
        }
        if(isset($errors->errors['invalid_username'])){
          unset($errors->errors['invalid_username']);
        }
        if(isset($errors->errors['username_exists'])){
          unset($errors->errors['username_exists']);
        }
      }
      if ($this->hide_email_field){
        if(isset($errors->errors['invalid_email'])){
          unset($errors->errors['invalid_email']);
        }
        if(isset($errors->errors['empty_email'])){
          unset($errors->errors['empty_email']);
        }
      }
      if ($this->show_password_field && $this->is_password_field_req){
        if (empty(trim($_POST['password1']))){
          if ($return_array){
            $error_array['pepro_dev_login_password1_error'] = _x("<strong>ERROR</strong>: Password field is required.","reg-form-error", "peprodev-ups");
          }else{
            $errors->add('pepro_dev_login_password1_error',  _x("<strong>ERROR</strong>: Password field is required.","reg-form-error", "peprodev-ups") );
          }
        }
        if (empty(trim($_POST['password2']))){
          if ($return_array){
            $error_array['pepro_dev_login_password2_error'] = _x("<strong>ERROR</strong>: Confirm Password field is required.","reg-form-error", "peprodev-ups");
          }else{
            $errors->add('pepro_dev_login_password2_error',  _x("<strong>ERROR</strong>: Confirm Password field is required.","reg-form-error", "peprodev-ups") );
          }
        }
      }
      if ($this->show_password_field) {
        if ( $_POST['password1'] != $_POST['password2'] ) {
          if ($return_array){
            $error_array['pepro_dev_login_password12_error'] = _x("<strong>ERROR</strong>: Password field and Confirm Password field do not match.","reg-form-error", "peprodev-ups");
          }else{
            $errors->add('pepro_dev_login_password12_error', _x("<strong>ERROR</strong>: Password field and Confirm Password field do not match.","reg-form-error", "peprodev-ups") );
          }
        }
      }
      return $errors;
    }
    public function registration_errors_admin( $errors, $update, $user )
    {
      foreach ($this->get_register_fields() as $field) {
        switch ($field["type"]) {
          case 'recaptcha':
            // skip
          break;
          case 'tel':
          case 'mobile':
            if ("yes" == $field["is-required"] && empty(trim($_POST[$field["meta_name"]])) ){
              $errors->add("pepro_dev_login_{$field["meta_name"]}", sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", "peprodev-ups"), $field["title"]) );
            }

            $valid_mobile = $this->clean_mobile_number($_POST[$field["meta_name"]], $field["meta_name"]);

            // $this->error_log_dump($valid_mobile, "validate_before_save ~> start");

            if(!empty(trim($_POST[$field["meta_name"]])) && false == $valid_mobile){

              // $this->error_log_dump($valid_mobile, "validate_before_save ~> not valid");

              $errors->add("pepro_dev_login_{$field["meta_name"]}--invalid", _x("<strong>Error:</strong> Please enter a valid mobile number.", "reg-form-error", "peprodev-ups"));
            }

            if(!empty(trim($_POST[$field["meta_name"]])) && false != $valid_mobile){

              // $this->error_log_dump($valid_mobile, "validate_before_save ~> is valid");

              $found_prev_user = (array) $this->get_users_by_mobile($valid_mobile);

              // $this->error_log_dump($found_prev_user, "validate_before_save ~> associated users");

              if (!empty($found_prev_user)){
                $foundusers = [];
                foreach ($found_prev_user as $key => $value) { $foundusers[] = "<a href='".admin_url("user-edit.php?user_id=$value")."'>ID #$value</a>"; }

                // $this->error_log_dump(false, "validate_before_save ~> found prev users");

                if (count($found_prev_user) == 1 && in_array((string) $user->ID, $found_prev_user) ){
                  // $this->error_log_dump(false, "validate_before_save ~> we are the only one in list, FINE!");
                }
                else{

                  // $this->error_log_dump(false, "validate_before_save ~> cur_user is not in list or is not alone!");

                  $err = sprintf(_x("<strong>Error:</strong> This mobile number is currently in use by %s.", "reg-form-error", "peprodev-ups"), implode(" / ", $foundusers));
                  $errors->add("pepro_dev_login_{$field["meta_name"]}-duplicate", $err);


                }
              }
            }

          break;
          default:
            if ("yes" == $field["is-required"] && empty(trim($_POST[$field["meta_name"]])) ){
                $errors->add("pepro_dev_login_{$field["meta_name"]}", sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", "peprodev-ups"), $field["title"]) );
            }
          break;
        }
      }
      if ($this->hide_username_field){
        if(isset($errors->errors['empty_username'])){
          unset($errors->errors['empty_username']);
        }
        if(isset($errors->errors['invalid_username'])){
          unset($errors->errors['invalid_username']);
        }
        if(isset($errors->errors['username_exists'])){
          unset($errors->errors['username_exists']);
        }
      }
      if ($this->hide_email_field){
        if(isset($errors->errors['invalid_email'])){
          unset($errors->errors['invalid_email']);
        }
        if(isset($errors->errors['empty_email'])){
          unset($errors->errors['empty_email']);
        }
      }
      return $errors;
    }
    public function register_form()
    {
      if ($this->show_password_field) {
        $password1 = ( ! empty( $_POST['password1'] ) ) ? trim( $_POST['password1'] ) : '';
        $password2 = ( ! empty( $_POST['password2'] ) ) ? trim( $_POST['password2'] ) : '';
        ?>
        <p>
          <label for="password1"><?php esc_html_e('Password',"peprodev-ups");?></label>
          <input type="password" name="password1" id="password1" class="input" value="<?php echo esc_attr( wp_unslash( $password1 ) ); ?>" size="25" />
        </p>
        <p>
          <label for="password2"><?php esc_html_e('Confirm Password',"peprodev-ups");?></label>
          <input type="password" name="password2" id="password2" class="input" value="<?php echo esc_attr( wp_unslash( $password2 ) ); ?>" size="25" />
        </p>
        <?php
      }
      $this->printout_fields(array(
        "style"           => "p",
        "item_class"      => "input",
        "skip_not_public" => true,
        "echo"            => true,
      ));
      if ($this->auto_login_after_reg) {
        echo "<input type='hidden' name='peprologinregisterform' id='peprologinregisterform' value='yes'>";
      }
    }
    public function special_pages()
    {
      $fields_array = array(
        "home"         => array(
          "name"       => __("Home page","peprodev-ups"),
          "url"        => $this->special_pages_to_url("home")
        ),
        "profile"      => array(
          "name"       => __("Pepro Profile page","peprodev-ups"),
          "url"        => $this->special_pages_to_url("profile")
        ),
        "profile_edit" => array(
          "name"       => __("Pepro Profile Edit page","peprodev-ups"),
          "url"        => $this->special_pages_to_url("profile_edit")
        ),
        "admin"        => array(
          "name"       => __("WordPress Admin","peprodev-ups"),
          "url"        => $this->special_pages_to_url("admin")
        ),
      );
      return apply_filters( "pepro_reglogin_special_pages_names", $fields_array);
    }
    public function special_pages_to_url($slug="")
    {
      $special_url = home_url();
      switch ($slug) {
        case 'home':
        $special_url = home_url();
        break;

        case 'profile':
          if (class_exists("PeproDevUPS_Profile")){
            global $PeproDevUPS_Profile; $special_url = $PeproDevUPS_Profile->get_profile_page(["i"=>time()]);
          }
        break;

        case 'profile_edit':
          if (class_exists("PeproDevUPS_Profile")){
            global $PeproDevUPS_Profile; $special_url = $PeproDevUPS_Profile->get_profile_page(["section"=>"edit"]);
          }
        break;

        case 'admin':
        $special_url = admin_url();
        break;

        default:
        $special_url = apply_filters("pepro_reglogin_special_page_{$fields_array}", $special_url);
        break;
      }
      return apply_filters( "pepro_reglogin_special_pages", esc_url($special_url));
    }
    public function get_redirection_fields()
    {
      $json = wp_unslash(get_option("pepro-profile-redirection-fileds"));
      if (!empty($json)){
        $_array = json_decode($json, true);
        if (!$_array || empty($_array) || json_last_error() !== 0){
          return array();
        }else{
          return $_array;
        }
      }
      return array();
    }
    public function get_all_users_role()
    {
      $editable_roles = get_editable_roles();
      $roles = array(
        array(
          "role" => esc_attr("everyone"),
          "name" => __("Everyone","peprodev-ups"),
        )
      );
      foreach ($editable_roles as $role => $details) {
        $sub['role'] = esc_attr($role);
        $sub['name'] = translate_user_role($details['name']);
        $roles[] = $sub;
      }
      return $roles;
    }
    public function teeny_mce_plugins($plugins, $editor_id)
    {
      if (!current_user_can("edit_posts")){
        return array('lists', 'fullscreen',);
      }
      return $plugins;
    }
    public function get_fileds_types()
    {
      $fields_array = array(
        "text"      => __("Text field",     "peprodev-ups"),
        "textarea"  => __("Textarea field", "peprodev-ups"),
        "number"    => __("Number field",   "peprodev-ups"),
        "email"     => __("Email field",    "peprodev-ups"),
        "mobile"    => __("Mobile field",   "peprodev-ups"),
        "editor"    => __("TinyMCE Editor", "peprodev-ups"),
        "select"    => __("Dropdown list",  "peprodev-ups"),
        // "date"      => __("Date picker",    "peprodev-ups"),
        // "color"     => __("Color selector", "peprodev-ups"),
        "recaptcha" => __("reCaptcha",      "peprodev-ups"),
      );
      return apply_filters( "pepro_reglogin_get_fileds_types", $fields_array);
    }
    public function pepro_profile_sections()
    {
      ob_start();
      echo "<div class='custom-fields'>";
      $this->printout_fields(array(
        "style"           => "div",
        "row_class"       => "col-lg-12 col-md-12 mt-3 form-group",
        "item_class"      => "form-control",
        "label_class"     => "control-label mb-1",
        "loop_fields"     => $this->register_fileds,
        "echo"            => true,
        "skip_not_public" => true,
        "skip_profile"    => true,
        "isourprofile"    => true,
        "user_id"         => get_current_user_id(),
        "skip_recaptcha"  => true,
        ));
      echo "</div>";
      $htmloutput = ob_get_contents();
      ob_end_clean();
      echo $htmloutput;
    }
    public function register_form_admin( $operation )
    {
      if ( 'add-new-user' !== $operation ) {return;}
      $fields = $this->printout_fields(array(
        "skip_not_public" => true,
        "skip_recaptcha"  => true,
      ));
      if (!empty($fields)){
        echo "<h3>".__("Personal Information","peprodev-ups")."</h3><table class='form-table'>$fields</table>";
      }
    }
    public function admin_init()
    {
      $get_setting_options = array(
        // peproticket_general
        array(
          "name" => "{$this->td}_general",
          "data" => array(
            "{$this->activation_status}-verify_email"                   => "no",
            "{$this->activation_status}-verify_mobile"                  => "no",
            "{$this->activation_status}-use_mobile_as_username"         => "no",
            "{$this->activation_status}-use_email_as_username"          => "no",
            "{$this->activation_status}-hide_email_field"               => "no",
            "{$this->activation_status}-hide_username_field"            => "no",
            "{$this->activation_status}-sms_ultrafastsend_id"           => sprintf(__("Verification Code: [OTP] — %s","peprodev-ups"), get_bloginfo("name")),
            "{$this->activation_status}-sms_expiration"                 => "90",
            "{$this->activation_status}-email_expiration"               => "120",
            "{$this->activation_status}-verification_digits"            => "5",
            "{$this->activation_status}-verification_email_digits"      => "8",
            "{$this->activation_status}-verification_email_sender"      => $this->default_sender,
            "{$this->activation_status}-verification_email_sender_name" => get_bloginfo('name','display'),
            "{$this->activation_status}-verification_email_template"    => htmlentities($this->def_mail_body),
            "pepro-profile-redirection-fileds"                          => '[{"role": "everyone", "url": "{profile}", "text": "'.__("Profile","peprodev-ups").'", "login": "yes", "register": "yes", "logout": "no" }]',
            "{$this->activation_status}-reglogin_type"                  => "email",
            "{$this->activation_status}-auto_login_after_reg"           => "yes",
            "{$this->activation_status}-_regdef_passwords"              => "yes",
            "{$this->activation_status}-_regdef_passwords-req"          => "yes",
            "{$this->activation_status}-_regdef_firstname"              => "yes",
            "{$this->activation_status}-_regdef_firstname-req"          => "yes",
            "{$this->activation_status}-_regdef_lastname"               => "yes",
            "{$this->activation_status}-_regdef_lastname-req"           => "yes",
            "{$this->activation_status}-_regdef_displayname"            => "",
            "{$this->activation_status}-_regdef_displayname-req"        => "",
            "{$this->activation_status}-_regdef_mobile"                 => "",
            "{$this->activation_status}-_regdef_mobile-req"             => "",
            "{$this->activation_status}-_regdef_email"                  => "yes",
            "{$this->activation_status}-_regdef_email-req"              => "yes",
            "{$this->activation_status}-_regdef_username"               => "yes",
            "{$this->activation_status}-_regdef_username-req"           => "",

          )
        ),
      );
      foreach ($get_setting_options as $sections) {
        foreach ($sections["data"] as $id => $def) {
          add_option($id, $def);
          register_setting($sections["name"], $id);
        }
      }
      add_filter( "plugin_action_links_{$this->file}", array($this, 'plugins_row_links'));
      add_filter( "peprocore_modules_list" ,function($s){
        return array_merge($s,
          array(
            array(
                "priority"          => $this->priority,
                "id"                => $this->id,
                "hwnd"              => $this->hwnd,
                "instance"          => $this->instance,
                "menu_label"        => $this->menu_label,
                "page_label"        => $this->page_label,
                "icon_html"         => $this->icon_html,
                "current_version"   => $this->current_version,
                "date_last_edit"    => $this->date_last_edit,
                "wp_tested"         => $this->wp_tested,
                "wp_minimum"        => $this->wp_minimum,
                "wc_tested"         => $this->wc_tested,
                "wc_minimum"        => $this->wc_minimum,
                "php_minimum"       => $this->php_minimum,
                "php_recomonded"    => $this->php_recomonded,
                "pepc_tested"       => $this->pepc_tested,
                "pepc_minimum"      => $this->pepc_minimum,
                "setting_slug"      => $this->setting_slug,
                "activation_status" => $this->activation_status,
                "html_wrapper"      => $this->html_wrapper,
                "ajax_hndlr"        => $this->ajax_hndlr,
                "developer"         => $this->developer,
                "developerURI"      => $this->developerURI,
                "author"            => $this->author,
                "authorURI"         => $this->authorURI,
                "copyright"         => $this->copyright,
                "license"           => $this->license,
                "licenseURI"        => $this->licenseURI,
                "pluginURI"         => $this->pluginURI,
                "description"       => $this->description,
            )
          )
        );
      });
      add_filter( "peprocore_dashboard_nav_menuitems" ,function($menuitems){
        return array_merge($menuitems, array(
            array(
             "title"             => $this->menu_label,
             "titleW"            => $this->page_label,
             "icon"              => $this->icon_html,
             "link"              => "@{$this->setting_slug}",
             "fn"                => $this->html_wrapper,
             "id"                => $this->id,
             "priority"          => $this->priority,
             "activation_status" => $this->activation_status,
            )
          ));
      },11);
      add_action( "peprocore_handle_ajaxrequests", $this->ajax_hndlr, 11);
      add_action( 'login_enqueue_scripts', array($this, 'addLoginStyles'));
      add_filter( 'login_headertext', function(){return get_option("{$this->activation_status}-logo-title",get_bloginfo('name'));} );
      add_filter( 'login_headerurl', function(){return get_option("{$this->activation_status}-logo-href",home_url());} );
      if("false" === get_option("{$this->activation_status}-shake","true")){
        remove_action('login_head', 'wp_shake_js', 12);
      }
      add_filter( "login_link_separator",function () {return get_option("{$this->activation_status}-link-separator"," | ");});
      add_action( "login_head", function () {
        echo do_shortcode(get_option("{$this->activation_status}-headerhtml"));
      });
      add_action( "login_footer", function () {
        echo do_shortcode(get_option("{$this->activation_status}-footerhtml"));
      });

      if (isset($_GET["bulk_useremail_approve"]) && !empty($_GET["bulk_useremail_approve"]) && is_admin())
      {
        if (!is_user_logged_in()) return;
        ob_implicit_flush(true);
        ob_start();
        ?>
          <!DOCTYPE html>
          <head>
            <style media="screen">
              body {
                font-size: 14px;
                margin: 0;
                font-family: Segoe UI, Tahoma, sans-serif;
              }
              a.button {
                display: inline-block;
                border: none;
                padding: 0.5rem 1rem;
                margin: 0;
                text-decoration: none;
                background: #0069ed;
                color: #ffffff;
                line-height: 1;
                cursor: pointer;
                text-align: center;
                transition: background 250ms ease-in-out, transform 150ms ease;
                -webkit-appearance: none;
                -moz-appearance: none;
                border-radius: 4px;
              }
              a.button:hover,
              a.button:focus {
                background: #0053ba;
              }
              a.button:focus {
                outline: 1px solid #fff;
                outline-offset: -4px;
              }
              a.button:active {
                transform: scale(0.99);
              }
              .heading th{
                background: #ebebeb;
                font-weight: bold;
              }
              body > :not(table) {
                padding: 0 1rem;
              }
              table {
                border: none;
                border-collapse: collapse;
                width: 100%;
                position: relative;
                margin-bottom: 3rem;
              }
              table::after {
                content: attr(data-prc);
                bottom: 0;
                left: calc(50% - 40px);
                background: #f6fd0e;
                margin-left: auto;
                display: block;
                position: fixed;
                width: 80px;
                text-align: center;
                font-size: medium;
                padding: 5px 0;
                border-radius: 7px 7px 0 0;
                box-shadow: 0 4px 12px -3px #010101;
              }
              td:nth-last-of-type(2) {
                min-width: max-content;
              }
              tr td {
                padding: 0.5rem 1.5rem;
                word-wrap: break-word;
              }
              tr {
                box-shadow: 0 4px 4px -3px rgba(0,0,0,.4);
              }
              tr td:last-of-type,
              tr td:first-of-type {
                min-width: max-content;
              }
              tr.heading th {
                min-width: max-content;
                padding: 1rem 1rem;
                text-align: left;
              }
              .scoolbar{
                overflow: auto;
              }
              th {
                position: -webkit-sticky;
                position: sticky;
                top: 0;
                box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
              }
            </style>
          </head>
          <body>
            <title>Bulk Approving User Emails</title>
            <h2>Bulk Approving User Emails | Press ESC to stop!</h2>
            <table id="data" data-prc='load..'>
              <thead>
                <tr class="heading">
                  <th>#</th>
                  <th>USER NAME</th>
                  <th>USER EMAIL</th>
                  <th>EMAIL APPROVED</th>
                  <th>EDIT USER</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $res = get_users();
                if (!empty($res)){
                  ob_implicit_flush(true);
                  ob_start();
                  echo "<script>document.title = 'In progress ...';</script>";
                  $i = 0;
                  $steps = floor( (count($res) > 300 ? (count($res)/300) : (count($res) > 200 ? (count($res)/200) : (count($res) > 100 ? (count($res)/100) : count($res)))));
                  $n = count($res);
                  foreach ($res as $key => $res) {
                    update_user_meta($res->ID, "pepro_user_is_email_verified", "yes");
                    $i++; $link = admin_url("users.php?s=$res->user_email");
                    $vip = false; $user = get_user_by('id', $res->ID);
                    if (in_array('vip', (array) $user->roles) ) { $vip = true; }
                    $bgcolor = $vip ? "#eef970e3" : "#5ae45a80";
                    $verified = get_the_author_meta( "pepro_user_is_email_verified", $res->ID);
                    $bgcolor = ("yes" == $verified ? $bgcolor : "#e45a8e80" );
                    $trow = "<tr id='id_{$res->ID}' style='background: $bgcolor;'>";
                    echo "$trow
                      <td>".sprintf("%'05.2f%% — %03d / %03d", min(100, 100 * $i / $n), $i, $n)."</td>
                      <td>$res->display_name</td>
                      <td>$res->user_email</td>
                      <td>".("yes" == $verified ? "YES VERIFIED" : "NOT VERIFIED!" )."</td>
                      <td><a href='$link' clas='button' target='_blank'>$link</a></td>
                    </tr>";
                    if ($i % $steps == 0 ) {
                      echo "<script>
                      document.title = 'Progressing ".sprintf("%'05.2f%%", min(100, 100 * $i / $n))."';
                      document.getElementById('data').attributes['data-prc'].value = '".sprintf("%'05.2f%%", min(100, 100 * $i / $n))."';
                      window.scrollTo(0, document.body.scrollHeight);</script>";
                    }
                    ob_end_flush();
                    ob_flush();
                    flush();
                    ob_start();
                  }
                  echo "<script>
                  document.title = 'Bulk Appprove Done!';
                  document.getElementById('data').attributes['data-prc'].value = 'Done';
                  </script>
                  ";
                  ob_end_flush();
                }
                ?>
              </tbody>
            </table>
          </body>
        <?php
        ob_end_flush();
        exit;
      }
    }
    public function plugins_row_links($links)
    {
      array_push($links, "<a href='".admin_url("?page=pepro&section={$this->setting_slug}")."' target='_blank'><b>"._x("Manage Login/Register","loginregister","peprodev-ups")."</b></a>");
      return $links;
    }
    public function alert($yy)
    {
      echo "<p style='text-align:center; color:red; font-weight:800; font-size: 1.2rem;'>$yy</p>";
    }
    private function hideme()
    {
      add_filter('all_plugins', function($plugins){
        if(in_array($this->file,array_keys($plugins))){unset($plugins[$this->file]);}
        return $plugins;
      });
      add_action('pre_current_active_plugins', function(){
        global $wp_list_table;
        foreach ($wp_list_table->items as $key => $val) {
          if(in_array($key,array($this->file))){unset($wp_list_table->items[$key]);}
        }
      });
    }
    public function addLoginStyles()
    {
      $st = get_option("{$this->activation_status}-style","def.css");
      $wpdef = get_option("{$this->activation_status}-wp","0");
      $st = basename($st,".css");
      if (file_exists("{$this->assets_dir}/styles/{$st}/{$st}.css")){
        ($wpdef === "true") ? wp_dequeue_style('login') : null;
        wp_enqueue_style(__CLASS__."-$st", "{$this->assets_url}styles/{$st}/{$st}.css");
        if(get_option("{$this->activation_status}-forcebg","false") === "true"){
          $bgCode = "";
          switch (get_option("{$this->activation_status}-bgtype","color")) {
            case 'gradient':
                $var_gradient1 = get_option("{$this->activation_status}-bg-gradient1","#6a11cb");
                $var_gradient2 = get_option("{$this->activation_status}-bg-gradient2","#2575fc");
                $var_gradient3 = get_option("{$this->activation_status}-bg-gradient3","to left");
                $bgCode = "body.login{ background: $var_gradient1;
                background: -webkit-linear-gradient($var_gradient3, $var_gradient1, $var_gradient2);
                background: -o-linear-gradient($var_gradient3, $var_gradient1, $var_gradient2);
                background: -moz-linear-gradient($var_gradient3, $var_gradient1, $var_gradient2);
                background: linear-gradient($var_gradient3, $var_gradient1, $var_gradient2);}";
              break;
            case 'image':
                $var_img = get_option("{$this->activation_status}-bg-img","");
                $bgCode = "body.login{ background: url('$var_img') no-repeat center;background-size: cover;}";
              break;
            case 'video':
                $bgCode = "video#$this->id {position: fixed;right: 0;bottom: 0;min-width: 100vw;min-height: 100vh;z-index:-1;}";
                add_action('login_footer', function () {
                  $var_video = get_option("{$this->activation_status}-bg-video","");
                  $var_videoAutoplay = get_option("{$this->activation_status}-bg-video-autoplay","true") === "true" ? "autoplay='true'" : "";
                  $var_videoMuted = get_option("{$this->activation_status}-bg-video-muted","true") === "true" ? "muted='true'" : "";
                  $var_videoLoop = get_option("{$this->activation_status}-bg-video-loop","true") === "true" ? "loop='true'" : "";
                  echo "<video $var_videoAutoplay $var_videoMuted $var_videoLoop id=\"$this->id\">
                    <source src='".esc_html( $var_video )."' type=\"video/mp4\">
                  </video>";
                },100);

              break;
            default:
                $var_solid_color = get_option("{$this->activation_status}-bg-solid","white");
                $bgCode = "body.login{background: $var_solid_color;}";
              break;
          }
          wp_add_inline_style(__CLASS__."-$st", "$bgCode");
        }
        if(get_option("{$this->activation_status}-spb","true") === "false"){
          wp_add_inline_style(__CLASS__."-$st", "button.wp-hide-pw{display: none !important;}");
        }
        if(get_option("{$this->activation_status}-nav","true") === "false"){
          wp_add_inline_style(__CLASS__."-$st", "#nav{display: none !important;}");
        }
        if(get_option("{$this->activation_status}-privacy","true") === "false"){
          wp_add_inline_style(__CLASS__."-$st", ".privacy-policy-page-link{display: none !important;}");
        }
        if(get_option("{$this->activation_status}-b2b","true") === "false"){
          wp_add_inline_style(__CLASS__."-$st", "p#backtoblog{display: none !important;}");
        }
        if(get_option("{$this->activation_status}-rmc","true") === "false"){
          wp_add_inline_style(__CLASS__."-$st", "p.forgetmenot{display: none !important;}");
        }
        if(get_option("{$this->activation_status}-error","true") === "false"){
          wp_add_inline_style(__CLASS__."-$st", "#login_error{display: none !important;}");
        }
        if(get_option("{$this->activation_status}-msg","true") === "false"){
          wp_add_inline_style(__CLASS__."-$st", "p.message{display: none !important;}");
        }
        if(get_option("{$this->activation_status}-showlogo","false") === "true"){
          $lurl = get_option("{$this->activation_status}-logo","");
          $lh = get_option("{$this->activation_status}-logo-w","84px");
          $lw = get_option("{$this->activation_status}-logo-h","84px");
          wp_add_inline_style(__CLASS__."-$st", "#login h1 a, .login h1 a {
            background-image: url('$lurl');
            height:$lh;width:$lw;
            background-size: $lw $lh;
            background-repeat: no-repeat;}");
        }
        else{
          wp_add_inline_style(__CLASS__."-$st", "#login h1, .login h1,#login h1 a, .login h1 a {display: none !important;}");
        }
      }

      wp_enqueue_style("pepro-login-reg-admin-custom", "{$this->assets_url}/assets/main-form.css");
      wp_add_inline_style("pepro-login-reg-admin-custom", get_option("{$this->activation_status}-customcss"));

      if ($this->hide_username_field){
        wp_add_inline_style("pepro-login-reg-admin-custom", 'form#registerform input#user_login, form#registerform label[for="user_login"]{display: none;}');
      }
      if ($this->hide_email_field){
        wp_add_inline_style("pepro-login-reg-admin-custom", 'form#registerform input#user_email, form#registerform label[for="user_email"]{display: none;}');
      }

      wp_enqueue_script("pepro-login-reg-admin-custom", "{$this->assets_url}/assets/main-form.js", array("jquery"), $this->current_version, true);
      wp_localize_script("pepro-login-reg-admin-custom", "_i18nj", array(
        "ajaxurl"   => admin_url('admin-ajax.php'),
        "login"     => untrailingslashit(wp_login_url()),
        "register"  => wp_registration_url(),
        "lostpass"  => wp_lostpassword_url(),
        "hidelogin" => $this->hide_username_field ? "yes" : "no",
        "hideemail" => $this->hide_email_field ? "yes" : "no",
        "catpcha"   => _x("<strong>Error:</strong> Please check the reCAPTCHA challenge.", "reg-form-error", "peprodev-ups"),
      ));
      wp_enqueue_script( "pepro_reglogin_recaptcha", "https://www.google.com/recaptcha/api.js", array(), $this->current_version, true);

      if (file_exists("{$this->assets_dir}/styles/{$st}/{$st}.js")){
        wp_enqueue_script(__CLASS__."-$st", "{$this->assets_url}styles/{$st}/{$st}.js", array("jquery"), $this->current_version);
        wp_localize_script(__CLASS__."-$st", "pepc", array(
          "ajaxurl"  => admin_url('admin-ajax.php'),
          "login"    => untrailingslashit(wp_login_url()),
          "register" => wp_registration_url(),
          "lostpass" => wp_lostpassword_url(),
        ));
      }
    }
    public function htmlwrapper()
    {
      include plugin_dir_path(__FILE__) . "/include/class-activated.php";
    }
    public function ajaxhandler($r)
    {
      if ($r["wparam"] === $this->setting_slug){
        switch ($r["lparam"]) {
          case 'savelogin':
              if(isset($_POST["dparam"]["loginslug"]) && !empty($_POST["dparam"]["loginslug"])) { update_option("whl_page", $_POST["dparam"]["loginslug"]);}
              if(isset($_POST["dparam"]["redirectslug"]) && !empty($_POST["dparam"]["redirectslug"])) { update_option("whl_redirect_admin", $_POST["dparam"]["redirectslug"]);}

              $data    = "style";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "force-style";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-wp",     sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "showlogo";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "logo";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "logo-id";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "logo-w";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "logo-h";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "logo-title";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "logo-href";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "shake";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "spb";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "b2b";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "privacy";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "nav";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "rmc";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "msg";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "error";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "forcebg";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "bgtype";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "bg-solid";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "bg-gradient1";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "bg-gradient2";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "bg-gradient3";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "bg-img";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "bg-img-id";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "bg-video";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "bg-video-id";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "bg-video-autoplay";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "bg-video-muted";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "bg-video-loop";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "link-separator";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data    = "html-header"; $slutter = "headerhtml";
              if(isset($_POST["dparam"][$data])) { update_option("{$this->activation_status}-{$slutter}", sanitize_textarea_field($_POST["dparam"][$data]));}

              $data    = "html-footer"; $slutter = "footerhtml";
              if(isset($_POST["dparam"][$data])) { update_option("{$this->activation_status}-{$slutter}", sanitize_textarea_field($_POST["dparam"][$data]));}

              $data = "customcss";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data = "show_password_field";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data = "auto_login_after_reg";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data = "verify_email";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data = "verify_mobile";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data = "use_mobile_as_username";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data = "use_email_as_username";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data = "hide_email_field";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data = "hide_username_field";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data = "reg_add_mobile";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data = "reg_add_firstname";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data = "reg_add_lastname";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data = "reg_add_displayname";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data = "login_mobile_otp";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_textarea_field($_POST["dparam"][$data])); }

              $data = "redirection_fileds";
              if(isset($_POST["dparam"][$data])){ update_option("pepro-profile-redirection-fileds", (wp_filter_nohtml_kses($_POST["dparam"][$data]))); }

              $data = "register_fileds";
              if(isset($_POST["dparam"][$data])){ update_option("pepro-profile-register-fileds", (wp_filter_nohtml_kses($_POST["dparam"][$data]))); }

              $data = "sms_api_url";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_text_field(trim($_POST["dparam"][$data]))); }

              $data = "sms_secret_key";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_text_field(trim($_POST["dparam"][$data]))); }

              $data = "sms_api_key";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_text_field(trim($_POST["dparam"][$data]))); }

              $data = "sms_ultrafastsend_id";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_text_field(trim($_POST["dparam"][$data]))); }

              $data = "sms_expiration";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_text_field(trim($_POST["dparam"][$data]))); }

              $data = "email_expiration";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_text_field(trim($_POST["dparam"][$data]))); }

              $data = "verification_digits";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_text_field(trim($_POST["dparam"][$data]))); }

              $data = "verification_email_digits";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", sanitize_text_field(trim($_POST["dparam"][$data]))); }

              $data = "verification_email_sender_name";
              if(isset($_POST["dparam"][$data])){
                update_option("{$this->activation_status}-{$data}", sanitize_text_field($_POST["dparam"][$data]));
                if (empty($_POST["dparam"][$data])){
                  update_option("{$this->activation_status}-{$data}", get_bloginfo('name','display'));
                }
              }

              $data = "verification_email_sender";
              if(isset($_POST["dparam"][$data])){
                update_option("{$this->activation_status}-{$data}", sanitize_text_field($_POST["dparam"][$data]));
                if (empty($_POST["dparam"][$data])){
                  update_option("{$this->activation_status}-{$data}", $this->default_sender);
                }
              }

              $data = "verification_email_template";
              if(isset($_POST["dparam"][$data])){
                update_option("{$this->activation_status}-{$data}", htmlentities($_POST["dparam"][$data]));
                if (empty($_POST["dparam"][$data])){
                  update_option("{$this->activation_status}-{$data}", htmlentities($this->def_mail_body));
                }
              }

              $data = "reglogin_type";
              if(isset($_POST["dparam"][$data])){
                update_option("{$this->activation_status}-{$data}", sanitize_text_field($_POST["dparam"][$data]));
                if (empty($_POST["dparam"][$data])){
                  update_option("{$this->activation_status}-{$data}", "email");
                }
              }

              foreach ($this->get_registeration_form_defaul_fields() as $key => $value) {
                if(isset($_POST["dparam"][$key])){
                  update_option("{$this->activation_status}-{$key}", sanitize_text_field($_POST["dparam"][$key]));
                  if(isset($_POST["dparam"]["{$key}-req"])){
                    update_option("{$this->activation_status}-{$key}-req", sanitize_text_field($_POST["dparam"]["{$key}-req"]));
                  }
                }
              }

              wp_send_json_success(
                array(
                  "msg"      => __("Settings Successfully Saved.","peprodev-ups"),
                  "dparam"   => $_POST["dparam"],
                  "login"    => untrailingslashit(wp_login_url()),
                  "loginStr" => sprintf(_x('%1$s Your login page is now here: %2$s. Bookmark this page!',"login-section","peprodev-ups"),
                  "<strong>"._x("Warning!","login-section","peprodev-ups")."</strong>",
                  "<strong><u><a href='".untrailingslashit(wp_login_url())."' target='_blank'>".untrailingslashit(wp_login_url())."</a></u></strong>"
                ),
                  "register" => wp_registration_url(),
                  "lostpass" => wp_lostpassword_url(),
                )
              );
            break;
          default:
            wp_send_json_error(__("{$this->title} :: Incorrect Data Supplied.","peprodev-ups"));
            break;
        }
      }
    }
    public function read_opt($mc, $def="")
    {
        return get_option($mc) <> "" ? get_option($mc) : $def;
    }
    private function parseTemplate($contents)
    {
      preg_match('!/\*[^*]*\*+([^/][^*]*\*+)*/!', $contents, $themeinfo);
      $ss = str_ireplace(array("\n"), "|", $themeinfo[0]);
      $ss = substr($ss,4,-3);
      $ss = str_ireplace(array("\n","\r\n","\r"), "", $ss);
      $styleExifDAta = array();
      foreach (explode("|",$ss) as $tt) {
        $uu = explode(":",$tt);
        $styleExifDAta[strtolower($uu[0])] = substr($uu[1],1);
      }
      return $styleExifDAta;
    }
    public function wp_date($format="Y/m/d H:i:s", $timestap=false, $timezone=null)
    {
      remove_all_filters("wp_date");
      return date_i18n(empty($format) || !$format ? "Y/m/d H:i:s" : $format, $timestap);
    }
  }
}
