<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/08/31 19:56:05
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

      $this->priority          = 3;
      $this->assets_url        = plugins_url("/", __FILE__);
      $this->assets_dir        = plugin_dir_path(__FILE__);
      $this->instance          = $this;
      $this->file              = plugin_basename(__FILE__);
      $this->hwnd              = __CLASS__;
      $this->setting_slug      = "loginregister";
      $this->id                = "loginregister";
      $this->td                = "pepro";
      $this->title             = __("PeproDev Ultimate Profile Solutions — Login","$this->td");
      $this->menu_label        = __("Login/Register","$this->td");
      $this->page_label        = __("Login Setting","$this->td");
      $this->developer         = __("Pepro Dev. Group",$this->td);
      $this->author            = __("Pepro Dev. Group",$this->td);
      $this->license           = __("Pepro Dev License",$this->td);
      $this->icon_html         = "<i class=\"material-icons\">fingerprint</i>";
      $this->current_version   = "3.5.0";
      $this->date_last_edit    = "1400/06/03";
      $this->wp_tested         = "5.8";
      $this->wp_minimum        = "5.0";
      $this->wc_tested         = "5.5.2";
      $this->wc_minimum        = "5.0";
      $this->php_minimum       = "5.6";
      $this->php_recomonded    = "7.3";
      $this->pepc_tested       = "1.7.0";
      $this->pepc_minimum      = "1.7.0";
      $this->activation_status = "PeproDevUPS_Core___{$this->setting_slug}";
      $this->html_wrapper      = array($this,"htmlwrapper");
      $this->ajax_hndlr        = array($this,"ajaxhandler");
      $this->developerURI      = "https://pepro.dev";
      $this->authorURI         = "https://pepro.dev";
      $this->licenseURI        = "https://pepro.dev/license";
      $this->pluginURI         = "https://pepro.dev/ups";
      $this->lang              = dirname(plugin_basename(__FILE__))."/languages/";
      $this->copyright         = sprintf(__("Copyright (c) %s Pepro Dev, All rights reserved",$this->td),date("Y"));

      $this->auto_login_after_reg   = "yes" == get_option("{$this->activation_status}-auto_login_after_reg");
      $this->verify_email           = "yes" == get_option("{$this->activation_status}-verify_email");
      $this->verify_mobile          = "yes" == get_option("{$this->activation_status}-verify_mobile");
      $this->use_mobile_as_username = "yes" == get_option("{$this->activation_status}-use_mobile_as_username");
      $this->use_email_as_username  = "yes" == get_option("{$this->activation_status}-use_email_as_username");
      $this->login_mobile_otp       = "mobile" == get_option("{$this->activation_status}-reglogin_type");
      $this->reglogin_type          = get_option("{$this->activation_status}-reglogin_type");

      $this->show_password_field    = "yes" == get_option("{$this->activation_status}-_regdef_passwords");
      $this->is_password_field_req  = "yes" == get_option("{$this->activation_status}-_regdef_passwords-req");
      $this->reg_add_firstname      = "yes" == get_option("{$this->activation_status}-_regdef_firstname");
      $this->is_add_firstname_req   = "yes" == get_option("{$this->activation_status}-_regdef_firstname-req");
      $this->reg_add_lastname       = "yes" == get_option("{$this->activation_status}-_regdef_lastname");
      $this->is_add_lastname_req    = "yes" == get_option("{$this->activation_status}-_regdef_lastname-req");
      $this->reg_add_displayname    = "yes" == get_option("{$this->activation_status}-_regdef_displayname");
      $this->is_add_displayname_req = "yes" == get_option("{$this->activation_status}-_regdef_displayname-req");
      $this->reg_add_mobile         = "yes" == get_option("{$this->activation_status}-_regdef_mobile");
      $this->is_add_mobile_req      = "yes" == get_option("{$this->activation_status}-_regdef_mobile-req");
      $this->hide_email_field       = "yes" !== get_option("{$this->activation_status}-_regdef_email");
      $this->is_email_field_req     = "yes" == get_option("{$this->activation_status}-_regdef_email-req");
      $this->hide_username_field    = "yes" !== get_option("{$this->activation_status}-_regdef_username");
      $this->is_username_field_req  = "yes" == get_option("{$this->activation_status}-_regdef_username-req");

      $this->sms_api_url                    = get_option("{$this->activation_status}-sms_api_url");
      $this->sms_secret_key                 = get_option("{$this->activation_status}-sms_secret_key");
      $this->sms_api_key                    = get_option("{$this->activation_status}-sms_api_key");
      $this->sms_ultrafastsend              = get_option("{$this->activation_status}-sms_ultrafastsend_id");
      $this->sms_expiration                 = get_option("{$this->activation_status}-sms_expiration", "90");
      $this->email_expiration               = get_option("{$this->activation_status}-email_expiration", "120");
      $this->verification_digits            = get_option("{$this->activation_status}-verification_digits", "5");
      $this->verification_email_digits      = get_option("{$this->activation_status}-verification_email_digits", "8");
      $this->verification_email_sender      = get_option("{$this->activation_status}-verification_email_sender", "noreply");
      $this->verification_email_sender_name = get_option("{$this->activation_status}-verification_email_sender_name", get_bloginfo('name','display'));
      $this->verification_email_template    = get_option("{$this->activation_status}-verification_email_template");
      $this->def_mail_body                  = ['<!DOCTYPE html>',
        '<html>',
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
        '       <small style="color: #717171;">Copyright © 2021, all rights reserved.</small>',
        '    </p>',
        '  </body>',
        '</html>'];
        $this->def_mail_body             = implode(PHP_EOL, $this->def_mail_body);
      $this->from_name                      = !empty($this->verification_email_sender_name) ? trim($this->verification_email_sender_name) : get_bloginfo('name','display');
      $this->from_address                   = (!empty($this->verification_email_sender) ? trim($this->verification_email_sender) : "wordpress") . "@" . parse_url(get_bloginfo('url'), PHP_URL_HOST);

      add_action("init",                              array( $this, "admin_init" ));
      add_action("wp_ajax_pepro_reglogin",            array( $this, "handel_ajax_req"));
      add_action("wp_ajax_nopriv_pepro_reglogin",     array( $this, "handel_ajax_req"));
      add_action("register_form",                     array( $this, "register_form" ));
      add_action("user_new_form",                     array( $this, "register_form_admin" ));
      add_action("user_register",                     array( $this, "user_register" ));
      add_action("edit_user_created_user",            array( $this, "user_register" ));
      add_action("show_user_profile",                 array( $this, "show_profile_custom_fields" ), 10, 3);
      add_action("edit_user_profile",                 array( $this, "show_profile_custom_fields" ), 10, 3);
      add_action("personal_options_update",           array( $this, "update_profile_custom_fields" ));
      add_action("edit_user_profile_update",          array( $this, "update_profile_custom_fields" ));
      add_action("registration_errors",               array( $this, "registration_errors" ), 10, 3);
      add_action("user_profile_update_errors",        array( $this, "registration_errors_admin" ), 10, 3);
      add_action("manage_users_columns",              array( $this, "manage_users_columns" ));
      add_action("manage_users_custom_column",        array( $this, "manage_users_custom_column" ), 100, 3);
      add_action("wp_logout",                         array( $this, "redirect_after_logout" ), 10, 2);
      add_action("admin_enqueue_scripts",             array( $this, "admin_enqueue_scripts" ));
      add_action("login_form_register",               array( $this, "login_form_register"));
      add_filter("login_redirect",                    array( $this, "redirect_after_login_register" ), 10, 3);
      add_filter("registration_redirect",             array( $this, "redirect_after_login_register" ), 10, 3);
      add_filter("woocommerce_login_redirect",        array( $this, "redirect_after_login_register" ), 10, 3);
      add_filter("woocommerce_registration_redirect", array( $this, "redirect_after_login_register" ), 10, 3);
      add_filter("peprofile_shortcodes",              array( $this, "add_peprofile_shortcodes" ), 11000);
      add_filter("teeny_mce_plugins",                 array( $this, "teeny_mce_plugins" ), 10, 2 );
      add_shortcode("pepro-login-form",               array( $this, "shortcode__pepro_login_form"));
      add_shortcode("pepro-login-popup",              array( $this, "shortcode__pepro_login_popup"));
      add_shortcode("logout-url",                     array( $this, "shortcode__logout_url"));
      add_shortcode("verified-mobile",                array( $this, "shortcode__user_verified_mobile"));
      add_shortcode("verified-email",                 array( $this, "shortcode__user_verified_email"));

      add_filter("pepro_reglogin_get_register_fields",                   array( $this, "pepro_reglogin_get_register_fields" ), 1000);
      add_action("pepro_reglogin_show_hide_defaul_registeration_fields", array( $this, "form_defaul_registeration_fields" ), 1000);

      $this->register_fileds             = $this->get_register_fields();
      $this->form_register_fields        = $this->get_form_register_fields();
      $this->form_resetpass_fields       = $this->get_form_resetpass_fields();
      $this->login_fields                = $this->get_login_fields();
      $this->verify_email_fields         = $this->get_verify_email_fields();
      $this->verify_mobile_fields        = $this->get_verify_mobile_fields();

      if (is_user_logged_in()){
        if($this->verify_mobile){
          if ("yes" != get_the_author_meta("pepro_user_is_sms_verified", get_current_user_id())){
            add_action("the_content", array($this, "verify_mobile_user_form"));
          }
        }
        if ($this->verify_email){
          if ("yes" != get_the_author_meta("pepro_user_is_email_verified", get_current_user_id())){
            add_action("the_content", array($this, "verify_email_user_form"));
          }
        }
      }

      require_once plugin_dir_path(__FILE__) . "/include/class-sms.php";
      $this->sms = new \PeproDev\PeproCore\RegLogin\SendSMS("https://ws.sms.ir/", $this->sms_api_key, $this->sms_secret_key, $this->sms_api_url);

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
        "_regdef_email"           => __("Email Field", $this->td),
        "_regdef_username"        => __("Username Field", $this->td),
        "_regdef_mobile"          => __("Mobile Field", $this->td),
        "_regdef_passwords"       => __("Password Fields", $this->td),
        "_regdef_firstname"       => __("User First name", $this->td),
        "_regdef_lastname"        => __("User Last name", $this->td),
        "_regdef_displayname"     => __("User Display name", $this->td),
      );
      if ($this->_wc_activated()){
        $_wc_active = array(
          "_wc_billing_country" => __("WooCommerce Billing Country", $this->td),
          "_wc_billing_state"   => __("WooCommerce Billing State", $this->td),
          "_wc_billing_city"    => __("WooCommerce Billing City", $this->td),

          // '_wc_billing_company'     => __("WooCommerce Billing Company",$this->td),
          // '_wc_billing_address_1'   => __("WooCommerce Billing Address 1",$this->td),
          // '_wc_billing_address_2'   => __("WooCommerce Billing Address 2",$this->td),
          // '_wc_billing_country'     => __("WooCommerce Billing Country",$this->td),
          // '_wc_billing_state'       => __("WooCommerce Billing State",$this->td),
          // '_wc_billing_city'        => __("WooCommerce Billing City",$this->td),
          // '_wc_billing_postcode'    => __("WooCommerce Billing Postcode",$this->td),
          // '_wc_billing_phone'       => __("WooCommerce Billing Phone",$this->td),
          // '_wc_billing_email'       => __("WooCommerce Billing Email",$this->td),
          // '_wc_shipping_first_name' => __("WooCommerce Shipping First Name",$this->td),
          // '_wc_shipping_last_name'  => __("WooCommerce Shipping Last Name",$this->td),
          // '_wc_shipping_company'    => __("WooCommerce Shipping Company",$this->td),
          // '_wc_shipping_address_1'  => __("WooCommerce Shipping Address 1",$this->td),
          // '_wc_shipping_address_2'  => __("WooCommerce Shipping Address 2",$this->td),
          // '_wc_shipping_country'    => __("WooCommerce Shipping Country",$this->td),
          // '_wc_shipping_state'      => __("WooCommerce Shipping State",$this->td),
          // '_wc_shipping_city'       => __("WooCommerce Shipping City",$this->td),
          // '_wc_shipping_postcode'   => __("WooCommerce Shipping Postcode",$this->td),

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
        <div class="register-field-single p-2 mb-2 border _no_opt_fields <?=esc_attr($key);?> ">
          <div class="register-field-single-title">
            <div class="field-opt-<?=esc_attr($key);?> checkbox-wrapper">
              <div class="row justify-content-between align-items-center">
              <div class="col-8">
                <label class="row w-100 align-items-center m-0">
                  <input autocomplete="off" type="checkbox" class='form-checkbox iostoggle single-required mr-2 main_checkbox <?=esc_attr($key);?>' <?=checked(get_option("{$this->activation_status}-{$key}") === "yes", true);?> name="<?=esc_attr($key);?>" /> <?=esc_html($value);?>
                  <input name="type" value="<?=esc_attr($key);?>" autocomplete="off" type="hidden" class="form-input meta-name" />
                </label>
              </div>
              <div class="col-4">
                <label class="row w-100 align-items-center m-0">
                  <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2 is_required' <?=checked(get_option("{$this->activation_status}-{$key}-req") === "yes", true);?> name="<?=esc_attr($key);?>-req" /> <?=__("Required?", $this->td);?>
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
        "title"        => __("First Name",$this->td),
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
        "title"        => __("Last Name",$this->td),
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
        "title"        => __("Display Name",$this->td),
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
          "title"       => __("Mobile",$this->td),
          "default"     => "",
          "is-required" => $this->is_add_mobile_req ? "yes" : "no",
          "is-public"   => "yes",
          "is-editable" => "yes",
          "in-column"   => "no",
          "placeholder" => "",
          "classes"     => "mobile-verification force-ltr",
          "attributes"  => "pattern=".esc_attr("^(\+98|0098|98|0)?9\d{9}$")." data-error-text=\"".esc_attr__("Enter mobile number with English numbers,<br>e.g. 09123456789", $this->td)."\" maxlength=14",
        )
      );
      }

      if ($this->_wc_activated()){
        if ("yes" == get_option("{$this->activation_status}-_wc_billing_city")){
          $reg_add_city = array(
            "meta_name"   => "billing_city",
            "type"        => "text",
            "title"       => __("City",$this->td),
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
            "title"       => __("State / County",$this->td),
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
            "title"       => __("Country / Region",$this->td),
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
      wp_enqueue_style("pepro-login-reg-formaction",    "{$this->assets_url}/assets/main-form.css", array(), current_time("timestamp"));
      wp_enqueue_style("pepro-login-reg-formconfirm",   "{$this->assets_url}/assets/jquery-confirm.css", array(), current_time("timestamp"));
      wp_enqueue_script("pepro-login-reg-formconfirm",  "{$this->assets_url}/assets/jquery-confirm.js", array("jquery"), current_time("timestamp"), true);
      wp_enqueue_script("pepro-login-reg-popper",       "{$this->assets_url}/assets/popper.min.js", array("jquery"), current_time("timestamp"), true);
      wp_enqueue_script("pepro-login-reg-tippy-bundle", "{$this->assets_url}/assets/tippy-bundle.umd.min.js", array("jquery"), current_time("timestamp"), true);
      wp_enqueue_script("pepro-login-reg-formaction",   "{$this->assets_url}/assets/main-form-ajax.js", array("jquery"), current_time("timestamp"), true);
      wp_localize_script("pepro-login-reg-formaction",  $args["uniqd"], array(
        "instance"          => $args["uniqd"],
        "trigger"           => $args["trigger"]??"",
        "ajaxurl"           => admin_url('admin-ajax.php'),
        "nonce"             => wp_create_nonce($this->td),
        "loading"           => _x("Please wait ...", "js-translate", $this->td),
        "error"             => _x("An unknown error occured.", "js-translate", $this->td),
        "errorTxt"          => _x("Error", "js-translate", $this->td),
        "gohome_txt"        => _x("Go Home", "js-translate", $this->td),
        "gohome_url"        => home_url(),
        "countries"         => $this->get_wc_countries(),
        "iran_cities"       => $this->get_wc_iran_cities(),
        'select_state_text' => esc_attr__("Select an option&hellip;", $this->td ),
        'placeholder_state' => esc_attr__("Enter State / County &hellip;", $this->td ),
        'placeholder_city'  => esc_attr__("Enter City &hellip;", $this->td ),
        "confirmTxt"        => _x("Confirm", "js-translate", $this->td),
        "successTtl"        => _x("Success", "js-translate", $this->td),
        "submitTxt"         => _x("Submit", "js-translate", $this->td),
        "loginTxt"          => _x("Login", "js-translate", $this->td),
        "okTxt"             => _x("Okay", "js-translate", $this->td),
        "txtYes"            => _x("Yes", "js-translate", $this->td),
        "txtNop"            => _x("No", "js-translate", $this->td),
        "cancelbTn"         => _x("Cancel", "js-translate", $this->td),
        "fixerr"            => _x("One or more fields have an error. Please check and try again.", "js-translate", $this->td),
        "closeTxt"          => _x("Close", "js-translate", $this->td),
        "check_validity"    => _x("Please check this field validity", "js-translate", $this->td),
        "check_required"    => _x("This field is required, Please check its validity", "js-translate", $this->td),

        "catpcha"    => _x("<strong>Error:</strong> Please check the reCAPTCHA challenge.", "reg-form-error", $this->td),
        ));
      wp_add_inline_style("pepro-login-reg-formaction", get_option("{$this->activation_status}-customcss"));
      wp_enqueue_script( "pepro_reglogin_recaptcha",    "https://www.google.com/recaptcha/api.js", array(), current_time("timestamp"), true);
    }
    public function verify_email_user_form($content='')
    {
      if (has_shortcode($content,"pepro-profile"))return $content;
      ob_start();
      $uniqd = uniqid("pepro_verify_");
      echo '<div class="pepro-verify-container" id="'.esc_attr($uniqd).'" data-pepro-reglogin="'.esc_attr($uniqd).'">';
      $this->enqueue_shortcode_styles(array(
        "uniqd"   => $uniqd,
      ));
      echo do_shortcode(wp_unslash(get_option("{$this->activation_status}-headerhtml")));
      if (get_current_user_id()){
        ?>
          <h6 style="margin-bottom: 1rem; border-bottom: 1px solid #ccc;padding: 0 0 1rem 0;"><?=__("Verify your account", $this->td);?></h6>
          <form class="pepro-verify inline" id="pepro-verify-inline" method="post">
            <div id="login_error"></div>
            <?php
              $this->printout_fields(array(
                "style"           => "div",
                "row_class"       => "pepro-login-reg-field",
                "item_class"      => "form-text",
                "loop_fields"     => apply_filters("pepro_reglogin_shortcode_verify_email_fields", $this->verify_email_fields),
                "skip_not_public" => true,
                "skip_recaptcha"  => false,
                "echo"            => true,
              ));
            ?>
          </form>
        <?php
      }
      echo do_shortcode(wp_unslash(get_option("{$this->activation_status}-footerhtml")));
      echo '</div>';
      $htmloutput = ob_get_contents();
      ob_end_clean();
      return $htmloutput;
    }
    public function verify_mobile_user_form($content='')
    {
      if (has_shortcode($content,"pepro-profile")) return $content;
      ob_start();
      $uniqd = uniqid("pepro_verify_");
      echo '<div class="pepro-verify-container" id="'.esc_attr($uniqd).'" data-pepro-reglogin="'.esc_attr($uniqd).'">';
      $this->enqueue_shortcode_styles(array(
        "uniqd"   => $uniqd,
      ));
      echo do_shortcode(wp_unslash(get_option("{$this->activation_status}-headerhtml")));
      if (get_current_user_id()){
        ?>
          <h6 style="margin-bottom: 1rem; border-bottom: 1px solid #ccc;padding: 0 0 1rem 0;"><?=__("Verify your account", $this->td);?></h6>
          <form class="pepro-verify inline" id="pepro-verify-inline" method="post">
            <div id="login_error"></div>
            <?php
              $this->printout_fields(array(
                "style"           => "div",
                "row_class"       => "pepro-login-reg-field",
                "item_class"      => "form-text",
                "loop_fields"     => apply_filters("pepro_reglogin_shortcode_verify_mobile_fields", $this->verify_mobile_fields),
                "skip_not_public" => true,
                "skip_recaptcha"  => false,
                "echo"            => true,
              ));
            ?>
          </form>
        <?php
      }
      echo do_shortcode(wp_unslash(get_option("{$this->activation_status}-footerhtml")));
      echo '</div>';
      $htmloutput = ob_get_contents();
      ob_end_clean();
      return $htmloutput;
    }
    public function verify_mobile_user_form_inline()
    {
      ob_start();
      if (!$this->reg_add_mobile)return;
      $uniqd = uniqid("pepro_verify_");
      $this->enqueue_shortcode_styles(array(
        "uniqd"   => $uniqd,
      ));
      if (get_current_user_id()){
        ?>
        <div class="col-lg-4">
          <div class="card">
            <div class="card-header"><?=__("Verify/Change your mobile number", $this->td);?></div>
            <div class="card-body">
              <?php echo '<div class="pepro-verify-container" id="'.esc_attr($uniqd).'" data-pepro-reglogin="'.esc_attr($uniqd).'">'; ?>
                <form class="pepro-verify inline" id="pepro-verify-inline-force" method="post">
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
    public function get_user_by_mobile($mobile='')
    {
      $users = get_users(
        array(
          "meta_key"     => 'user_mobile',
          "meta_value"   => $mobile,
          "meta_compare" => '=',
          "order"        => 'ASC',
          "orderby"      => 'registered',
          "fields"       => "ID",
          "number"       => "1",
        )
      );
      return $users ? $users[0] : false;
    }
    public function get_users_by_mobile($mobile='')
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
        $preg_match = preg_match('/^(\+98|0098|98|0)?9\d{9}$/', $mobile);
      }
      return apply_filters( "pepro_reglogin_mobile_is_valid", $preg_match, $mobile, $field_id);
    }
    public function clean_mobile_number($mobile="", $field_id="CLEAN_MOBILE_NUMBER")
    {
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
            "title"  => __("Login/Register Inline form",$this->td),
            "syntax" => array(
              "before"       => __("Content before form",$this->td),
              "after"        => __("Content after form",$this->td),
              "title"        => __("Login Form title",$this->td),
              'active'       => __("Default active form: login/register/resetpass",$this->td),
              "reg_title"    => __("Register Form title",$this->td),
              "reset_title"  => __("Reset Password Form title",$this->td),
              __("content between shortcode tags",$this->td) => __("Content to show to logged in users",$this->td),
            ),
          );
      $array["pepro-login-popup"] = array(
            "sample" => "[pepro-login-popup button='Open Login']".PHP_EOL."[pepro-login-popup trigger='#popup']<a id='popup' href='#'>Login/Register</a>",
            "title"  => __("Login/Register Popup form",$this->td),
            "syntax" => array(
              "trigger"      => __("A jQuery selector to trigger popup",$this->td),
              "button"       => __("Trigger call-to-action anchor text, leave empty to use jQuery trigger",$this->td),
              "class"        => __("Trigger call-to-action anchor class",$this->td),
              "before"       => __("Content before form",$this->td),
              "after"        => __("Content after form",$this->td),
              "before_popup" => __("Content before popup form",$this->td),
              "after_popup"  => __("Content after popup form",$this->td),
              "title"        => __("Login Form title",$this->td),
              'active'       => __("Default active form: login/register/resetpass",$this->td),
              "reg_title"    => __("Register Form title",$this->td),
              "reset_title"  => __("Reset Password Form title",$this->td),
              "extras"       => __("Content instead of trigger call-to-action",$this->td),
              __("content between shortcode tags",$this->td) => __("Content instead of trigger call-to-action",$this->td),
            ),
          );
      $array["logout-url"] = array(
            "sample" => "[logout-url]",
            "title"  => __("Returns Logout URL/Link",$this->td),
            "syntax" => array(
              "redirect" => __("URL to redirect on logout",$this->td),
              "button"  => __("Logout call-to-action anchor text, leave empty to return url",$this->td),
              "class"   => __("Logout call-to-action anchor class",$this->td),
              "extras"  => __("Content instead of Logout call-to-action, use {url} to replace with logout link",$this->td),
              __("content between shortcode tags",$this->td)  => __("Content instead of Logout call-to-action, use {url} to replace with logout link",$this->td),
            ),
          );
      $array["verified-email"] = array(
            "sample" => "[verified-email]Content for users with verified email[/verified-email]",
            "title"  => __("Show content if user has verified Email",$this->td),
            "syntax" => array(
              "reverse=1" => __("Show Content between tags if opposite of the condition is true",$this->td),
              __("content between shortcode tags",$this->td)  => __("Content to show if condition were true, can use other shortcodes too",$this->td),
            ),
          );
      $array["verified-mobile"] = array(
            "sample" => "[verified-mobile]Content for users with verified mobile[/verified-mobile]",
            "title"  => __("Show content if user has verified Mobile",$this->td),
            "syntax" => array(
              "reverse=1" => __("Show Content between tags if opposite of the condition is true",$this->td),
              __("content between shortcode tags",$this->td)  => __("Content to show if condition were true, can use other shortcodes too",$this->td),
            ),
          );
      return $array;
    }
    public function shortcode__pepro_login_form($atts=array(), $contnent="")
    {
      extract(
        shortcode_atts(array(
        'before'    => '',
        'after'     => '',
        'title'     => '',
        'active'    => 'login', /*login register resetpass*/
        'reg_title' => '',
        'reset_title' => '',
        'trigger'   => '',
        'loggedout' => 'yes',
        ), $atts));
      ob_start();
      $uniqd = uniqid("pepro_reg_login_");
      $this->enqueue_shortcode_styles(array("uniqd" => $uniqd, "trigger" => $trigger, ));
      echo '<div class="pepro-login-reg-container" id="'.esc_attr($uniqd).'" data-pepro-reglogin="'.esc_attr($uniqd).'">';
      echo wp_unslash(get_option("{$this->activation_status}-headerhtml"));
      if (!is_user_logged_in()){
        echo "$before";
        ?>
          <form novalidate class="pepro-login-reg <?=("login" === $active?"inline":"");?>" id="pepro-login-inline" method="post">
            <h6 style="margin-bottom: 1rem; border-bottom: 1px solid #ccc;padding: 0 0 1rem 0;"><?=(!empty($title) ? $title : __("Login", $this->td));?></h6>
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
              <?php
              if (get_option('users_can_register')){
                ?>
                  <a class="switch-form-register"  href="javascript:;"><?=__("Register", $this->td);?></a>
                <?php
              }
              if (!$this->login_mobile_otp){
                ?>
                  <a class="switch-form-lost-pass" href="javascript:;"><?=__("Lost Password?", $this->td);?></a>
                <?php
              }
              ?>
            </div>
          </form>
          <?php
          if (get_option('users_can_register')){
            ?>
            <form novalidate class="pepro-login-reg <?=("register" === $active?"inline":"");?>" id="pepro-reg-inline" method="post">
              <h6 style="margin-bottom: 1rem; border-bottom: 1px solid #ccc;padding: 0 0 1rem 0;"><?=(!empty($reg_title) ? $reg_title : __("Register", $this->td));?></h6>
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
                <a class="switch-form-login" href="javascript:;"><?=__("Login", $this->td);?></a>
              </div>
            </form>
            <?php
          }
          if (!$this->login_mobile_otp){
            ?>
            <form novalidate class="pepro-login-reg <?=("resetpass" === $active?"inline":"");?>" id="pepro-pass-inline" method="post">
              <h6 style="margin-bottom: 1rem; border-bottom: 1px solid #ccc;padding: 0 0 1rem 0;"><?=(!empty($reset_title) ? $reset_title : __("Reset Password", $this->td));?></h6>
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
                <a class="switch-form-login" href="javascript:;"><?=__("Login", $this->td);?></a>
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
        }else{
          if ("yes" == $loggedout){
            ?><a href="<?=wp_logout_url();?>" class="button button-primary"><?=__("Logout", $this->td);?></a><?php
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
        if (!isset($_POST["nonce"]) || !wp_verify_nonce($_POST["nonce"]??null, $this->td)){wp_send_json_error(array("msg"=>__("Unauthorized Access!", $this->td)));}
        switch ($_POST["order"]) {

          case 'login':
            if (is_user_logged_in()){ wp_send_json_error(array("msg" => __("You are already logged-in.", $this->td))); }
            $param = sanitize_post( $_POST["param"] );
            parse_str(stripslashes_deep( $param ), $param);
            if (empty($param)){ wp_send_json_error(array("msg" => __("Source data is not valid.", $this->td))); }

            foreach ($this->login_fields as $field) {
              if ("recaptcha" == $field["type"]){
                if(!isset($param['g-recaptcha-response']) || empty($param['g-recaptcha-response'])){
                  wp_send_json_error(array("msg" => __("Please check the reCAPTCHA challenge.", $this->td),));
                  return false;
                }
              }
            }

            if (!filter_var($param["username"], FILTER_VALIDATE_EMAIL)) {
              // login by mobile
              if (isset($param["checkmobile"])){
                $valid_mobile = $this->clean_mobile_number($param["username"]);
                if ($valid_mobile){
                  $user_id = $this->get_user_by_mobile($valid_mobile);
                  if (!$user_id){ wp_send_json_error(array(
                    "msg"    => __("The mobile number is not currently in use by any account.", $this->td),
                    "is_otp" => true,
                    "focus"  => ".mobile-verification",
                    "select" => ".mobile-verification",
                  )); }
                  // verify sms if OTP passed
                  if (isset($param["optverify"]) && !empty($param["optverify"])){
                    $verified = $this->check_verification_sms($user_id, $param["optverify"]);
                    if ($verified){
                      wp_clear_auth_cookie();
                      $user = new \WP_User($user_id);
                      wp_set_current_user($user->ID);
                      wp_set_auth_cookie($user->ID);
                      $username = get_the_author_meta("display_name", $user->ID);

                      if ($this->verify_mobile && "yes" !== get_the_author_meta("pepro_user_is_sms_verified", $user->ID)){
                        update_user_meta($user->ID, "pepro_user_is_sms_verified", "yes");
                      }

                      wp_send_json_success(array(
                        "msg"           => sprintf(__("Hi %s, You have successfully logged in!", $this->td), $username),
                        "redirect"      => apply_filters("login_redirect", true, "", $user),
                        "redirect_text" => $this->redirect_after_login_register("", "ajax_text", $user),
                        "logout_txt"    => __("Logout",$this->td),
                        "logout_url"    => wp_logout_url(),
                      ));
                    }else{
                      wp_send_json_error(array(
                        "msg"    => __("Login verification code is incorrect/expired!", $this->td),
                        "is_otp" => true,
                        "focus"  => ".otp-verification",
                        "select" => ".otp-verification",
                      ));
                    }
                  }
                  else{
                    // send verification
                    $_otp_date = get_the_author_meta("_sms_otp_date", $user_id);
                    $_otp_now = date_i18n("Y/m/d H:i:s", current_time("timestamp"));
                    if ($_otp_date){
                      $today = strtotime($_otp_now);
                      $expire = strtotime($_otp_date . " + $this->sms_expiration sec");
                      if($today >= $expire){
                        $sms = $this->send_verification_sms($user_id);
                        if ($sms){
                          wp_send_json_success(array(
                            "msg"    => __("Verification code sent, Enter in field below.$test", $this->td),
                            "is_otp" => true,
                            "focus"  => ".otp-verification",
                          ));
                        }else{
                          wp_send_json_error(array(
                            "msg"    => sprintf(__("Error Sending Verification to %s. Try again.", $this->td), $valid_mobile),
                            "is_otp" => true,
                            "focus"  => ".mobile-verification",
                          ));
                        }
                      } else {
                        wp_send_json_error(array(
                          "msg"    => sprintf(__("Error Sending Verification, you can request one code every %s seconds.", $this->td), $this->sms_expiration),
                          "is_otp" => true,
                          "focus"  => ".mobile-verification",
                        ));
                      }
                    }
                    else{
                      $sms = $this->send_verification_sms($user_id);
                      if ($sms){
                        wp_send_json_success(array(
                          "msg"    => sprintf(__("Verification code sent to %s.", $this->td), $valid_mobile),
                          "is_otp" => true,
                          "focus"  => ".otp-verification",
                        ));
                      }else{
                        wp_send_json_error(array(
                          "msg"    => __("Error Sending Verification code. Try again.", $this->td),
                          "is_otp" => true,
                          "focus"  => ".mobile-verification",
                        ));
                      }
                    }

                  }

                }
                else{
                  wp_send_json_error(array("msg" => __("Please enter a valid mobile number.", $this->td), ));
                }
              }
              // login by username
              else{
                $user = get_user_by('login', $param["username"] );
              }
            }
            // login by email
            else{
              $user = get_user_by('email', $param["username"] );
            }

            if ( $user && wp_check_password( $param["password"], $user->data->user_pass, $user->ID )){
              wp_clear_auth_cookie();
              wp_set_current_user($user->ID);
              wp_set_auth_cookie($user->ID);
              $username = get_the_author_meta("display_name", $user->ID);
              wp_send_json_success(array(
                "msg"           => sprintf(__("Hi %s, You have successfully logged in!", $this->td), $username),
                "redirect"      => apply_filters("login_redirect", true, "", $user),
                "redirect_text" => $this->redirect_after_login_register("", "ajax_text", $user),
                "logout_txt"    => __("Logout",$this->td),
                "logout_url"    => wp_logout_url(),
              ));
            }
            else {
              wp_send_json_error(array("msg" => __("Password does not match!", $this->td), ));
            }
          break;

          case 'verify':
            $param = sanitize_post( $_POST["param"] );
            parse_str(stripslashes_deep( $param ), $param);
            if (empty($param)){ wp_send_json_error(array("msg" => __("Source data is not valid.", $this->td))); }
            $user = false;
            $user = wp_get_current_user();
            $user_id = $user->ID;
            foreach ($this->login_fields as $field) {
              if ("recaptcha" == $field["type"]){
                if(!isset($param['g-recaptcha-response']) || empty($param['g-recaptcha-response'])){
                  wp_send_json_error(array("msg" => __("Please check the reCAPTCHA challenge.", $this->td),));
                  return false;
                }
              }
            }

            // VERIFY MOBILE SMSM
            if (isset($param["checkemobile"])){
              if ("yes" == get_the_author_meta( "pepro_user_is_sms_verified", get_current_user_id())){ wp_send_json_error(array("msg" => __("You mobile is already verified!", $this->td))); }
              $valid_mobile = $this->clean_mobile_number($param["username"]);
              if ($valid_mobile){
                $user_id = $this->get_user_by_mobile($valid_mobile);
                if ($user_id && (int) $user_id !== (int) $user->ID){
                  wp_send_json_error(array(
                    "msg"    => __("The mobile number is currently in use by another account.", $this->td),
                    "is_otp" => true,
                    "focus"  => ".mobile-verification",
                    "select" => ".mobile-verification",
                  ));
                  return false;
                }
                // verify sms if OTP passed
                if (isset($param["verification"]) && !empty($param["verification"])){
                  $verified = $this->check_verification_sms($user->ID, $param["verification"]);
                  if ($verified){
                    update_user_meta($user->ID, "user_mobile", $valid_mobile);
                    update_user_meta($user->ID, "pepro_user_is_sms_verified", "yes");
                    $username = get_the_author_meta("display_name", $user->ID);
                    wp_send_json_success(array( "msg" => sprintf(__("Hi %s, You have successfully verified your mobile number!", $this->td), $username),
                      "redirect"      => home_url(),
                      "redirect_text" => __("Go Home",$this->td),
                      ));
                  }
                  else{
                    wp_send_json_error(array( "msg" => __("Mobile verification code is incorrect/expired!", $this->td),
                      "is_otp" => true,
                      "focus"  => ".code-verification",
                      "select" => ".code-verification",
                      ));
                  }
                }
                else{
                  // send verification
                  $_otp_date = get_the_author_meta("_sms_otp_date", $user->ID);
                  $_otp_now = date_i18n("Y/m/d H:i:s", current_time("timestamp"));
                  if ($_otp_date){
                    $today = strtotime($_otp_now);
                    $expire = strtotime($_otp_date . " + $this->sms_expiration sec");
                    if($today >= $expire){
                      $sms = $this->send_verification_sms($user->ID, $valid_mobile);
                      if ($sms){
                        wp_send_json_success(array(
                          "msg"    => __("Verification code sent, Enter in field below.", $this->td),
                          "is_otp" => true,
                          "focus"  => ".code-verification",
                        ));
                      }
                      else{
                        wp_send_json_error(array(
                          "msg"    => sprintf(__("Error Sending Verification to %s. Try again.", $this->td), $valid_mobile),
                          "is_otp" => true,
                          "focus"  => ".mobile-verification",
                        ));
                      }
                    }
                    else{
                      wp_send_json_error(array(
                        "msg"    => sprintf(__("Error Sending Verification, you can request one code every %s seconds.", $this->td), $this->sms_expiration),
                        "is_otp" => true,
                        "focus"  => ".mobile-verification",
                      ));
                    }
                  }
                  else{
                    $sms = $this->send_verification_sms($user->ID, $valid_mobile);
                    if ($sms){
                      wp_send_json_success(array(
                        "msg"    => sprintf(__("Verification code sent to %s.", $this->td), $valid_mobile),
                        "is_otp" => true,
                        "focus"  => ".code-verification",
                      ));
                    }
                    else{
                      wp_send_json_error(array(
                        "msg"    => __("Error Sending Verification code. Try again.", $this->td),
                        "is_otp" => true,
                        "focus"  => ".mobile-verification",
                      ));
                    }
                  }
                }
              }
              else{
                wp_send_json_error(array("msg" => __("Please enter a valid mobile number.", $this->td), ));
              }
            }
            // VERIFY EMAIL
            else{
              if ("yes" == get_the_author_meta( "pepro_user_is_email_verified", get_current_user_id())){ wp_send_json_error(array("msg" => __("You Email address is already verified!", $this->td))); }
              if (!filter_var($param["username"], FILTER_VALIDATE_EMAIL)) {
                wp_send_json_error(array("msg" => __("E-mail address could not be validated!", $this->td), ));
              }
              else{
                // verify email if OTP passed
                if (isset($param["verification"]) && !empty($param["verification"])){

                  $verified = $this->check_verification_email($user_id, $param["verification"]);
                  if ($verified){

                    $finduser = get_user_by('email', $param["username"]);
                    if ($finduser && $finduser->ID == $user_id){
                      // already a member! we check verification, if verified ~> verify
                      if ($this->verify_email){ update_user_meta($user_id, "pepro_user_is_email_verified", "yes"); }
                    }else{
                      // we check verification, if verified ~> verify AND change user email
                      wp_update_user(array('ID' => $user_id, 'user_email' => esc_attr($param["username"])));
                      if ($this->verify_email){update_user_meta($user_id, "pepro_user_is_email_verified", "yes");}
                    }

                    $username = get_the_author_meta("display_name", $user_id);
                    wp_send_json_success(array(
                      "msg"           => sprintf(__("Hi %s, You have successfully verified your email address!", $this->td), $username),
                      "redirect"      => home_url(),
                      "redirect_text" => __("Go Home",$this->td),
                    ));

                  }
                  else{
                    wp_send_json_error(array(
                      "msg"    => __("Email verification code is incorrect/expired!", $this->td),
                      "is_otp" => true,
                      "focus"  => ".code-verification",
                      "select" => ".code-verification",
                    ));
                  }

                }
                // send verficitation mail
                else{

                  $_otp_date = get_the_author_meta("_email_otp_date", $user_id);
                  $_otp_now = date_i18n("Y/m/d H:i:s", current_time("timestamp"));
                  if ($_otp_date){
                    $today = strtotime($_otp_now);
                    $expire = strtotime($_otp_date . " + $this->email_expiration sec");
                    if($today >= $expire){
                      $email = $this->send_verification_email($param["username"]);
                      if ($email){
                        wp_send_json_success(array(
                          "msg"    => sprintf(__("Verification code sent to %s.", $this->td), $param["username"]),
                          "is_otp" => true,
                          "focus"  => ".code-verification",
                        ));
                      }else{
                        wp_send_json_error(array(
                          "msg"    => __("Error Sending Verification code. Try again.", $this->td),
                          "is_otp" => true,
                          "focus"  => ".email-verification",
                          "select"  => ".email-verification",
                        ));
                      }
                    } else {
                      wp_send_json_error(array(
                        "msg"    => sprintf(__("Error Sending Verification, you can request one code every %s seconds.", $this->td), $this->email_expiration),
                        "is_otp" => true,
                        "focus"  => ".email-verification",
                      ));
                    }
                  }else{
                    $email = $this->send_verification_email($param["username"]);
                    if ($email){
                      wp_send_json_success(array(
                        "msg"    => sprintf(__("Verification code sent to %s.", $this->td), $param["username"]),
                        "is_otp" => true,
                        "focus"  => ".code-verification",
                      ));
                    }else{
                      wp_send_json_error(array(
                        "msg"    => __("Error Sending Verification code. Try again.", $this->td),
                        "is_otp" => true,
                        "focus"  => ".email-verification",
                        "select"  => ".email-verification",
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
            if (empty($param)){ wp_send_json_error(array("msg" => __("Source data is not valid.", $this->td))); }
            $user = false;
            $user = wp_get_current_user();
            $user_id = $user->ID;
            foreach ($this->login_fields as $field) {
              if ("recaptcha" == $field["type"]){
                if(!isset($param['g-recaptcha-response']) || empty($param['g-recaptcha-response'])){
                  wp_send_json_error(array("msg" => __("Please check the reCAPTCHA challenge.", $this->td),));
                  return false;
                }
              }
            }

            // VERIFY MOBILE SMSM
            if (isset($param["checkemobile"])){
              $valid_mobile = $this->clean_mobile_number($param["username"]);
              if ($valid_mobile == get_the_author_meta("user_mobile", get_current_user_id())){
                wp_send_json_error(array("msg" => __("You mobile is already verified!", $this->td)));
              }
              if ($valid_mobile){
                $user_id = $this->get_user_by_mobile($valid_mobile);
                if ($user_id && (int) $user_id !== (int) $user->ID){
                  wp_send_json_error(array(
                    "msg"    => __("The mobile number is currently in use by another account.", $this->td),
                    "is_otp" => true,
                    "focus"  => ".mobile-verification",
                    "select" => ".mobile-verification",
                  ));
                  return false;
                }
                // verify sms if OTP passed
                if (isset($param["verification"]) && !empty($param["verification"])){
                  $verified = $this->check_verification_sms($user->ID, $param["verification"]);
                  if ($verified){
                    update_user_meta($user->ID, "user_mobile", $valid_mobile);
                    update_user_meta($user->ID, "pepro_user_is_sms_verified", "yes");
                    $username = get_the_author_meta("display_name", $user->ID);
                    wp_send_json_success(array( "msg" => sprintf(__("Hi %s, You have successfully verified your mobile number!", $this->td), $username),
                      "redirect"      => home_url(),
                      "redirect_text" => __("Go Home",$this->td),
                      ));
                  }
                  else{
                    wp_send_json_error(array( "msg" => __("Mobile verification code is incorrect/expired!", $this->td),
                      "is_otp" => true,
                      "focus"  => ".code-verification",
                      "select" => ".code-verification",
                      ));
                  }
                }
                else{
                  // send verification
                  $_otp_date = get_the_author_meta("_sms_otp_date", $user->ID);
                  $_otp_now = date_i18n("Y/m/d H:i:s", current_time("timestamp"));
                  if ($_otp_date){
                    $today = strtotime($_otp_now);
                    $expire = strtotime($_otp_date . " + $this->sms_expiration sec");
                    if($today >= $expire){
                      $sms = $this->send_verification_sms($user->ID, $valid_mobile);
                      if ($sms){
                        wp_send_json_success(array(
                          "msg"    => __("Verification code sent, Enter in field below.", $this->td),
                          "is_otp" => true,
                          "focus"  => ".code-verification",
                        ));
                      }
                      else{
                        wp_send_json_error(array(
                          "msg"    => sprintf(__("Error Sending Verification to %s. Try again.", $this->td), $valid_mobile),
                          "is_otp" => true,
                          "focus"  => ".mobile-verification",
                        ));
                      }
                    }
                    else{
                      wp_send_json_error(array(
                        "msg"    => sprintf(__("Error Sending Verification, you can request one code every %s seconds.", $this->td), $this->sms_expiration),
                        "is_otp" => true,
                        "focus"  => ".mobile-verification",
                      ));
                    }
                  }
                  else{
                    $sms = $this->send_verification_sms($user->ID, $valid_mobile);
                    if ($sms){
                      wp_send_json_success(array(
                        "msg"    => sprintf(__("Verification code sent to %s.", $this->td), $valid_mobile),
                        "is_otp" => true,
                        "focus"  => ".code-verification",
                      ));
                    }
                    else{
                      wp_send_json_error(array(
                        "msg"    => __("Error Sending Verification code. Try again.", $this->td),
                        "is_otp" => true,
                        "focus"  => ".mobile-verification",
                      ));
                    }
                  }
                }
              }
              else{
                wp_send_json_error(array("msg" => __("Please enter a valid mobile number.", $this->td), ));
              }
            }
          break;

          case 'register':
            $param = sanitize_post( $_POST["param"] );
            parse_str(stripslashes_deep( $param ), $param);
            if (empty($param)){ wp_send_json_error(array("msg" => __("<strong>Error:</strong> Source data is not valid.", $this->td))); }
            if (!get_option('users_can_register')){
              wp_send_json_error(array("msg" => __("<strong>Error:</strong> User registration is not allowed!", $this->td)));
            }
            $error_array = array();
            $username    = false;
            $email       = false;

            if (!$this->login_mobile_otp){
              if (!$this->hide_username_field){
                if (empty($param["username"])){
                  $error_array['pepro_dev_login_username'] = _x("<strong>Error:</strong> Please enter a username.", "reg-form-error", $this->td);
                }else{
                  if (username_exists($param["username"])){
                    $error_array['pepro_dev_login_username_exists'] = _x("<strong>Error</strong>: This username is already registered. Please choose another one.", "reg-form-error", $this->td);
                  }
                  if (!validate_username($param["username"])) {
                    $error_array['pepro_dev_login_invalid_username'] = _x("<strong>Error</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.", "reg-form-error", $this->td);
                  }
                  $illegal_logins = (array) apply_filters( 'illegal_user_logins', array() );
                  if (in_array(strtolower($param["username"]), array_map('strtolower', $illegal_logins ), true )) {
                    $error_array['pepro_dev_login_invalid_username'] = _x("<strong>Error</strong>: Sorry, that username is not allowed.", "reg-form-error", $this->td);
                  }
                  $username = sanitize_user(wp_unslash(strtolower($param["username"])), true);
                }
              }
              if (!$this->hide_email_field || ($this->hide_email_field && $this->hide_username_field)){
                if (empty($param["email"])){
                  $error_array['pepro_dev_login_invalid_email'] = _x("<strong>Error</strong>: Please enter an email address.", "reg-form-error", $this->td);
                }else{
                  if (!filter_var($param["email"], FILTER_VALIDATE_EMAIL)){
                    $error_array['pepro_dev_login_invalid_email'] = _x("<strong>Error</strong>: The given email address is not valid.", "reg-form-error", $this->td);
                  }
                  if (!is_email($param["email"])){
                    $error_array['pepro_dev_login_invalid_email'] = _x("<strong>Error</strong>: The given email address is not valid.", "reg-form-error", $this->td);
                  }
                  if (email_exists($param["email"])){
                    $error_array['pepro_dev_login_email_exists'] = _x("<strong>Error</strong>: This email is already registered. Please choose another one.", "reg-form-error", $this->td);
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
                    $error_array["pepro_dev_login_{$field["meta_name"]}"] = _x("<strong>Error:</strong> Please check the reCAPTCHA challenge.", "reg-form-error", $this->td);
                  }
                break;
                case 'button':
                  // skip
                break;
                case 'tel':
                case 'mobile':
                  if ("yes" == $field["is-required"] && empty(trim($param[$field["meta_name"]])) ){
                    $error_array["pepro_dev_login_{$field["meta_name"]}"] = sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", $this->td), $field["title"]) ;
                  }
                  $valid_mobile = $this->clean_mobile_number($param[$field["meta_name"]], $field["meta_name"]);
                  if(!empty(trim($param[$field["meta_name"]])) && !$valid_mobile){
                    $error_array["pepro_dev_login_{$field["meta_name"]}_invalid"] = _x("<strong>Error:</strong> Please enter a valid mobile number.", "reg-form-error", $this->td);
                  }
                  $get_user_by_mobile = $this->get_user_by_mobile($param[$field["meta_name"]]);
                  if($get_user_by_mobile){
                    $error_array["pepro_dev_login_{$field["meta_name"]}_exist"] = _x("<strong>Error:</strong> This mobile number is already registered. Please choose another one.", "reg-form-error", $this->td);
                  }
                break;
                default:
                  if ("yes" == $field["is-required"] && empty(trim($param[$field["meta_name"]])) ){
                    $error_array["pepro_dev_login_{$field["meta_name"]}"] = sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", $this->td), $field["title"]);
                  }
                break;
              }
            }

            if ($this->show_password_field && $this->is_password_field_req) {
              if (empty(trim($param['password1']))){
                $error_array['pepro_dev_login_password1_error'] = _x("<strong>ERROR</strong>: Password field is required.", "reg-form-error", $this->td);
              }
              if (empty(trim($param['password2']))){
                $error_array['pepro_dev_login_password2_error'] = _x("<strong>ERROR</strong>: Confirm Password field is required.", "reg-form-error", $this->td);
              }
            }
            if ($this->show_password_field){
            	if (false !== strpos(wp_unslash($param['password1']), '\\')) {
            		$error_array['pepro_dev_login_password_error'] = _x('<strong>Error</strong>: Passwords may not contain the character "\\".', "reg-form-error", $this->td);
            	}
              if ( $param['password1'] != $param['password2'] ) {
                $error_array['pepro_dev_login_password12_error'] = _x("<strong>ERROR</strong>: Password field and Confirm Password field do not match.", "reg-form-error", $this->td);
              }
            }

            if (!empty($error_array)){
              $error_msg = [];
              foreach ($error_array as $key => $value) {
                $error_msg[]= $value;
              }
              wp_send_json_error(array( "msg" => implode("<br />", $error_msg), ));
            }

            if ($this->login_mobile_otp){

              if (isset($param["checkmobile"])){
                $valid_mobile = $this->clean_mobile_number($param["user_mobile"]);
                if ($valid_mobile){
                  $user_id = $this->get_user_by_mobile($valid_mobile);
                  if ($user_id){
                    wp_send_json_error(array(
                    "msg"    => __("<strong>Error:</strong> The mobile number is currently in use by another account.", $this->td),
                    "is_otp" => true,
                    "focus"  => ".mobile-verification",
                    "select" => ".mobile-verification",
                    ));
                  }
                  // verify sms if OTP passed
                  if (isset($param["optverify"]) && !empty($param["optverify"])){
                    $verified = $this->check_dummyuser_verification_sms($valid_mobile, $param["optverify"]);
                    if ($verified){

                      // register new user
                      $newUser = $this->register_new_user(false, false, $valid_mobile, $param);

                      if ($newUser){
                        $username = get_the_author_meta("display_name", $newUser->ID);
                        wp_send_json_success(array(
                          "msg"           => sprintf(__("Hi %s, You have successfully registered!", $this->td), $username),
                          "redirect"      => apply_filters("login_redirect", true, "", $newUser),
                          "redirect_text" => $this->redirect_after_login_register("", "ajax_text", $newUser),
                          "logout_txt"    => __("Logout",$this->td),
                          "logout_url"    => wp_logout_url(),
                        ));
                      }else{
                        wp_send_json_error(array("msg"=> __("<strong>Error:</strong> There was an error processing your request!", $this->td),));
                      }


                    }
                    else{
                      wp_send_json_error(array(
                        "msg"    => __("<strong>Error:</strong> Verification code is incorrect/expired!", $this->td),
                        "is_otp" => true,
                        "focus"  => ".otp-verification",
                        "select" => ".otp-verification",
                      ));
                    }
                  }
                  else{
                    // send verification
                    $_otp_date = get_option("_sms_otp_{$valid_mobile}_date", "");
                    $_otp_now = date_i18n("Y/m/d H:i:s", current_time("timestamp"));
                    if ($_otp_date){
                      $today = strtotime($_otp_now);
                      $expire = strtotime($_otp_date . " + $this->sms_expiration sec");
                      if($today >= $expire){
                        $sms = $this->send_dummyuser_verification_sms($valid_mobile);
                        if ($sms){
                          wp_send_json_success(array(
                            "msg"    => __("Verification code sent, Enter in field below.", $this->td),
                            "is_otp" => true,
                            "focus"  => ".otp-verification",
                          ));
                        }
                        else{
                          wp_send_json_error(array(
                            "msg"    => sprintf(__("<strong>Error:</strong> Sending Verification to %s failed. Try again.", $this->td), $valid_mobile),
                            "is_otp" => true,
                            "focus"  => ".mobile-verification",
                          ));
                        }
                      }
                      else {
                        wp_send_json_error(array(
                          "msg"    => sprintf(__("<strong>Error:</strong> Sending Verification failed, you can request one code every %s seconds.", $this->td), $this->sms_expiration),
                          "is_otp" => true,
                          "focus"  => ".mobile-verification",
                        ));
                      }
                    }
                    else{
                      $sms = $this->send_dummyuser_verification_sms($valid_mobile);
                      if ($sms){
                        wp_send_json_success(array(
                          "msg"    => sprintf(__("Verification code sent to %s.", $this->td), $valid_mobile),
                          "is_otp" => true,
                          "focus"  => ".otp-verification",
                        ));
                      }
                      else{
                        wp_send_json_error(array(
                          "msg"    => __("<strong>Error:</strong> Sending Verification code failed. Try again.", $this->td),
                          "is_otp" => true,
                          "focus"  => ".mobile-verification",
                        ));
                      }
                    }
                  }

                }
                else{
                  wp_send_json_error(array(
                    "msg"    => sprintf(__("<strong>Error:</strong> Please enter a valid mobile number.", $this->td), $valid_mobile),
                    "is_otp" => true,
                    "focus"  => ".mobile-verification",
                  ));
                }
              }

            }

            // register new user
            $newUser = $this->register_new_user($username, $email, false, $param);

            if ($newUser){
              $username = get_the_author_meta("display_name", $newUser->ID);
              wp_send_json_success(array(
                "msg"           => sprintf(__("Hi %s, You have successfully registered!", $this->td), $username),
                "redirect"      => apply_filters("login_redirect", true, "", $newUser),
                "redirect_text" => $this->redirect_after_login_register("", "ajax_text", $newUser),
                "logout_txt"    => __("Logout",$this->td),
                "logout_url"    => wp_logout_url(),
              ));
            }

            wp_send_json_error(array("msg"=> __("<strong>Error:</strong> There was an error processing your request!", $this->td),));
          break;

          case 'resetpass':
            $param = sanitize_post( $_POST["param"] );
            parse_str(stripslashes_deep( $param ), $param);
            if (empty($param)){ wp_send_json_error(array("msg" => __("Source data is not valid.", $this->td))); }
            $error_array = array();
            $user_id     = false;
            $user_login  = false;
            if (empty($param["username"])){
              $error_array['pepro_dev_login_invalid_email'] = _x("<strong>Error</strong>: Please enter a username or email address.", "reg-form-error", $this->td);
            }else{
              $user_login = sanitize_text_field(wp_unslash(strtolower($param["username"])));
              if ( empty( $user_login ) ) {
                $error_array['pepro_dev_login_empty_username'] = _x("<strong>Error</strong>: Please enter a username or email address.", "reg-form-error", $this->td);
              } elseif ( strpos( $user_login, '@' ) ) {
                $user_data = get_user_by( 'email', trim( wp_unslash( $user_login ) ) );
                if ( empty( $user_data ) ) {
                  $error_array['pepro_dev_login_invalid_email'] = _x("<strong>Error</strong>: There is no account with that username or email address.", "reg-form-error", $this->td);
                }
              } else {
                $user_data = get_user_by( 'login', trim( wp_unslash( $user_login ) ) );
              }

              if ( $user_data ) {
                if (empty($user_data->user_email)){
                  $error_array['pepro_dev_login_email_exists'] = _x("<strong>Error</strong>: There is no email address linked to username.", "reg-form-error", $this->td);
                }
              } else {
                $error_array['pepro_dev_login_email_exists'] = _x("<strong>Error</strong>: The given email address is not registered.", "reg-form-error", $this->td);
              }
            }

            foreach ($this->form_resetpass_fields as $field) {
              if (in_array($field["meta_name"], ["username", "email", "password1", "password2"])) continue;
              switch ($field["type"]) {
                case 'recaptcha':
                  if(!isset($param['g-recaptcha-response']) || empty($param['g-recaptcha-response'])){
                    $error_array["pepro_dev_login_{$field["meta_name"]}"] = _x("<strong>Error:</strong> Please check the reCAPTCHA challenge.", "reg-form-error", $this->td);
                  }
                break;
                case 'button':
                break;
                default:
                  if ("yes" == $field["is-required"] && empty(trim($param[$field["meta_name"]])) ){
                    $error_array["pepro_dev_login_{$field["meta_name"]}"] = sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", $this->td), $field["title"]);
                  }
                break;
              }
            }

            if (!empty($error_array)){
              $error_msg = [];
              foreach ($error_array as $key => $value) {
                $error_msg[]= $value;
              }
              wp_send_json_error(array( "msg" => implode("<br />", $error_msg), ));
            }

            if ($user_data){
              $rp = retrieve_password($user_login);
              if ($rp){
                wp_send_json_success(array(
                  "msg" => sprintf(__("A password-reset mail sent to you, Check your inbox.", $this->td)),
                ));
              }
            }
            wp_send_json_error(array("msg"=> __("<strong>Error:</strong> There was an error processing your request!", $this->td),));

          break;

          case 'change_user_meta':
            $user_id = sanitize_text_field($_POST["lparam"] ?? false);
            $sparam = sanitize_text_field($_POST["sparam"] ?? "");
            $dparam = sanitize_text_field($_POST["dparam"] ?? "no");
            if (!$user_id){
              wp_send_json_error(array("msg" => __("An unknown error occured.", $this->td)));
            }
            update_user_meta($user_id, $sparam, $dparam);
            wp_send_json_success(array("msg" => __("User details successfully changed.",$this->td)));
          break;

          default:
            wp_send_json_error(array("msg" => __("An unknown error occured.", $this->td)));
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

      if (isset($params["username"]) && !empty($params["username"])){
        $userdata['user_login'] = $params["username"];
      }
      if ($email) {
        $userdata['user_email'] = $email;
      }
      if ($this->show_password_field && isset($params["password1"])) {
        $userdata['user_pass']  = $params["password1"];
      }
      if ($email && $this->use_email_as_username){
        $userdata['user_login'] = $params["email"];
      }
      if ($mobile && $this->use_mobile_as_username){
        $userdata['user_login'] = $mobile;
      }

      $userdata['first_name']     = "";
      $userdata['last_name']      = "";
      $userdata['display_name']   = __("Dear user",$this->td);

      if ($this->reg_add_firstname){
        $userdata['first_name'] = $params["first_name"];
      }
      if ($this->reg_add_lastname){
        $userdata['last_name'] = $params["last_name"];
      }
      if ($this->reg_add_displayname){
        $userdata['display_name'] = $params["display_name"];
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
              $valid_mobile = $this->clean_mobile_number($params[$field["meta_name"]], $field["meta_name"]);
              update_user_meta($user_id, $field["meta_name"], sanitize_text_field($valid_mobile));
            break;
            default:
              update_user_meta($user_id, $field["meta_name"], sanitize_text_field($params[$field["meta_name"]]));
            break;
          }
        }

        if ($this->reg_add_firstname){
          update_user_meta( $user_id, 'first_name', sanitize_text_field($userdata["first_name"]) );
          update_user_meta( $user_id, 'billing_first_name', sanitize_text_field($userdata["first_name"]));
        }
        if ($this->reg_add_lastname){
          update_user_meta( $user_id, 'last_name', sanitize_text_field($userdata["last_name"]) );
          update_user_meta( $user_id, 'billing_last_name', sanitize_text_field($userdata["last_name"]));
        }

        if ($this->auto_login_after_reg){
          wp_clear_auth_cookie();
          wp_set_current_user($user_id);
          wp_set_auth_cookie($user_id);
        }
        if ($mobile){
          update_user_meta($user_id, "user_mobile", sanitize_text_field($mobile));
          update_user_meta($user_id, "billing_phone", sanitize_text_field($mobile));
          update_user_meta($user_id, "pepro_user_is_sms_verified", "yes");
        }
        do_action( "pepro_reglogin_register_new_user", $username, $email, $mobile, $params);
        return new \WP_User($user_id);
      }
      return false;
    }
    public function make_otp($user_id=0, $otp_digits=5)
    {
      $generator = "1357902468";
      $otp_code = "";
      for ($i = 1; $i <= $otp_digits; $i++) {
        $otp_code .= substr($generator, (rand()%(strlen($generator))), 1);
      }
      update_user_meta($user_id, "_sms_otp_code", $otp_code);
      update_user_meta($user_id, "_sms_otp_date", date_i18n("Y/m/d H:i:s", current_time("timestamp")) );
      do_action( "pepro_reglogin_make_otp", $otp_code, $user_id, $otp_digits );
      return apply_filters("pepro_reglogin_make_otp", $otp_code, $user_id, $otp_digits );
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
      $_otp_now  = date_i18n("Y/m/d H:i:s", current_time("timestamp"));
      if (!$user_id || !$verification || !$_otp_date || !$_otp_code) return false;
      $today  = strtotime($_otp_now);
      $expire = strtotime("$_otp_date + $this->sms_expiration sec");
      if($today >= $expire){
        // expired
      } else {
        if (trim($verification) == $_otp_code){
          return true;
        }
        // else ~> expired
      }
      return false;
    }
    public function make_dummyuser_otp($mobile="", $otp_digits=5)
    {
      $generator = "1357902468";
      $otp_code = "";
      for ($i = 1; $i <= $otp_digits; $i++) {
        $otp_code .= substr($generator, (rand()%(strlen($generator))), 1);
      }
      update_option("_sms_otp_{$mobile}_code", $otp_code);
      update_option("_sms_otp_{$mobile}_date", date_i18n("Y/m/d H:i:s", current_time("timestamp")) );
      do_action( "pepro_reglogin_make_dummyuser_otp", $otp_code, $mobile, $otp_digits );
      return apply_filters("pepro_reglogin_make_dummyuser_otp", $otp_code, $mobile, $otp_digits );
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
    public function check_dummyuser_verification_sms($mobile="", $verification="")
    {
      $_otp_code = get_option("_sms_otp_{$mobile}_code","");
      $_otp_date = get_option("_sms_otp_{$mobile}_date","");
      if (!$mobile || !$verification || !$_otp_date || !$_otp_code) return false;
      $_otp_now  = date_i18n("Y/m/d H:i:s", current_time("timestamp"));
      $today  = strtotime($_otp_now);
      $expire = strtotime("$_otp_date + $this->sms_expiration sec");
      if($today >= $expire){
        // expired
      } else {
        if (trim($verification) == $_otp_code){
          return true;
        }
        // else ~> expired
      }
      return false;
    }
    public function make_otp_email($otp_digits=8)
    {
      $generator = "1357902468";
      $otp_code = ""; $user_id = get_current_user_id();
      for ($i = 1; $i <= $otp_digits; $i++) {
        $otp_code .= substr($generator, (rand()%(strlen($generator))), 1);
      }
      update_user_meta($user_id, "_email_otp_code", $otp_code);
      update_user_meta($user_id, "_email_otp_date", date_i18n("Y/m/d H:i:s", current_time("timestamp")) );
      do_action( "pepro_reglogin_make_otp_email", $otp_code, $user_id, $otp_digits );
      return apply_filters("pepro_reglogin_make_otp_email", $otp_code, $user_id, $otp_digits );
    }
    public function send_verification_email($email="")
    {
      $otp_code = $this->make_otp_email($this->verification_email_digits);
      $current_user = wp_get_current_user();
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
      $mail = $this->send_mail($email, "$this->from_name [".__("VERIFY",$this->td)."]", $email_content);
      return $this->is_localhost() ? true : $mail;
    }
    public function check_verification_email($user_id=0, $verification="")
    {
      $_otp_code = get_the_author_meta("_email_otp_code", $user_id);
      $_otp_date = get_the_author_meta("_email_otp_date", $user_id);
      $_otp_now  = date_i18n("Y/m/d H:i:s", current_time("timestamp"));
      if (!$user_id || !$verification || !$_otp_date || !$_otp_code) return false;
      $today  = strtotime($_otp_now);
      $expire = strtotime("$_otp_date + $this->email_expiration sec");

      if($today >= $expire){
        // expired
      } else {
        if (trim($verification) == $_otp_code){
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
      $login_fields = array(
        array(
          "meta_name"   => "username",
          "type"        => "text",
          "title"       => $this->use_email_as_username ? __("Username/Email",$this->td) : __("Username",$this->td),
          "default"     => isset($_GET["username"]) && !empty($_GET["username"]) ? sanitize_text_field( esc_html( trim($_GET["username"]) ) ) : "",
          "is-required" => "yes",
          "is-public"   => "yes",
          "is-editable" => "no",
          "in-column"   => "no",
          "placeholder" => "",
          "classes"     => "",
          "attributes"  => "tabindex=1 autocapitalize=none size=20 data-error-text=\"".esc_attr__("Enter username or email address correctly", $this->td)."\" ",
        ),
        array(
          "meta_name"   => "password",
          "type"        => "password",
          "title"       => __("Password",$this->td),
          "placeholder" => "",
          "classes"     => "password-input",
          "attributes"  => "tabindex=2 autocomplete=off size=20 data-error-text=\"".esc_attr__("You have to enter a password", $this->td)."\"",
          "default"     => "",
          "is-required" => "yes",
          "is-public"   => "yes",
          "is-editable" => "no",
          "in-column"   => "no"
        ));
      // default otp-login
      if ($this->login_mobile_otp){
        $login_fields = array(
          array(
            "meta_name"   => "username",
            "type"        => "mobile",
            "title"       => __("Mobile",$this->td),
            "default"     => isset($_GET["username"]) && !empty($_GET["username"]) ? sanitize_text_field( esc_html( trim($_GET["username"]) ) ) : "",
            "is-required" => "yes",
            "is-public"   => "yes",
            "is-editable" => "no",
            "in-column"   => "no",
            "placeholder" => "",
            "classes"     => "mobile-verification force-ltr",
            "attributes"  => "tabindex=1 data-error-text=\"".esc_attr__("Enter mobile number with English numbers,<br>e.g. 09123456789", $this->td)."\" pattern=".esc_attr("^(\+98|0098|98|0)?9\d{9}$")." maxlength=14",
          ),
          array(
            "meta_name"   => "optverify",
            "type"        => "text",
            "title"       => __("OTP Code",$this->td),
            "row-class"   => "hide",
            "is-public"   => "yes",
            "is-required" => "no",
            "is-editable" => "no",
            "in-column"   => "no",
            "no-label"    => "no",
            "placeholder" => "",
            "classes"     => "otp-verification force-ltr",
            "attributes"  => "autocomplete=off tabindex=2 data-error-text=\"".esc_attr__("You have to enter an OTP code or leave it empty and press Enter to receive a new one", $this->td)."\" pattern=".esc_attr("^\d{{$this->verification_digits}}$")." maxlength=$this->verification_digits minlength=$this->verification_digits",
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
      }
      $num = 2;
      // add reCaptcha
      foreach ($this->register_fileds as $field) {
        if ("recaptcha" == $field["type"] && "yes" == $field["login"]){
          $num++;
          array_push($login_fields, $field);
        }
      }

      $textSend   = __("Receive OTP Code",$this->td);
      $textVerify = __("Verify OTP Code",$this->td);
      $num++;
      array_push($login_fields, array(
          "meta_name"   => "submit",
          "type"        => "button",
          "btn-type"    => "submit",
          "title"       => $this->login_mobile_otp ? $textSend : __("Login",$this->td),
          "classes"     => "button button-primary",
          "attributes"  => "tabindex=$num " . ($this->login_mobile_otp ? "data-send=\"".esc_attr($textSend)."\" data-verify=\"".esc_attr($textVerify)."\"" : ""),
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
            "title"       => __("Username",$this->td),
            "type"        => "text",
            "meta_name"   => "username",
            "classes"     => "",
            "attributes"  => "tabindex=$num data-error-text=\"".esc_attr__("Enter username correctly", $this->td)."\"",
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
            "title"       => __("Email",$this->td),
            "type"        => "email",
            "meta_name"   => "email",
            "classes"     => "",
            "attributes"  => "tabindex=$num data-error-text=\"".esc_attr__("Enter email address correctly", $this->td)."\"",
            "is-required" => $this->is_email_field_req ? "yes" : "no",
            "is-public"   => "yes",
            "no-label"    => "no",
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
            "title"       => __("Mobile",$this->td),
            "default"     => "",
            "is-required" => "yes",
            "is-public"   => "yes",
            "is-editable" => "no",
            "in-column"   => "no",
            "placeholder" => "",
            "classes"     => "mobile-verification force-ltr",
            "attributes"  => "tabindex=$num pattern=".esc_attr("^(\+98|0098|98|0)?9\d{9}$")." data-error-text=\"".esc_attr__("Enter mobile number with English numbers,<br>e.g. 09123456789", $this->td)."\" maxlength=14",
          ),
          array(
            "meta_name"   => "optverify",
            "type"        => "text",
            "title"       => __("OTP Code",$this->td),
            "row-class"   => "hide",
            "is-public"   => "yes",
            "is-required" => "no",
            "is-editable" => "no",
            "in-column"   => "no",
            "no-label"    => "no",
            "placeholder" => "",
            "classes"     => "otp-verification force-ltr",
            "attributes"  => "autocomplete=off tabindex=".($num+1)." data-error-text=\"".esc_attr__("You have to enter an OTP code or leave it empty and press Enter to receive a new one", $this->td)."\" pattern=".esc_attr("^\d{{$this->verification_digits}}$")." maxlength=$this->verification_digits minlength=$this->verification_digits",
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
              "title"       => __("Mobile",$this->td),
              "default"     => "",
              "is-required" => $this->is_add_mobile_req ? "yes" : "no",
              "is-public"   => "yes",
              "is-editable" => "no",
              "in-column"   => "no",
              "placeholder" => "",
              "classes"     => "mobile-verification force-ltr",
              "attributes"  => "tabindex=$num pattern=".esc_attr("^(\+98|0098|98|0)?9\d{9}$")." data-error-text=\"".esc_attr__("Enter mobile number with English numbers,<br>e.g. 09123456789", $this->td)."\" maxlength=14",
            )
          );
        }
      }


      foreach ((array)$this->register_fileds as $field) {
        if ("yes" == $field["is-public"] && "recaptcha" !== $field["type"]){
          $num++;
          if ($this->login_mobile_otp){
            if ("user_mobile" != $field["meta_name"] && ("tel" == $field["type"] || "mobile" == $field["type"]) ){
              $field["attributes"] = " data-error-text=\"".esc_attr__("Enter mobile number with English numbers,<br>e.g. 09123456789", $this->td)."\" tabindex=$num " . $field["attributes"];
              array_push($login_fields, $field);
            }
          }else{
            if ("user_mobile" == $field["meta_name"] && ("tel" == $field["type"] || "mobile" == $field["type"]) ){
              $field["classes"]    = "mobile-verification force-ltr";
              $field["attributes"] = " data-error-text=\"".esc_attr__("Enter mobile number with English numbers,<br>e.g. 09123456789", $this->td)."\" tabindex=$num pattern=".esc_attr("^(\+98|0098|98|0)?9\d{9}$")." maxlength=14";
            }
            array_push($login_fields, $field);
          }
        }
      }

      if ($this->show_password_field){
        $num++;
        array_push($login_fields,
        array(
          "title"       => __("Password",$this->td),
          "type"        => "password",
          "meta_name"   => "password1",
          "classes"     => "",
          "attributes"  => "tabindex=$num ",
          "is-required" => $this->is_password_field_req ? "yes" : "no",
          "is-public"   => "yes",
          "no-label"    => "no",
        ),
        array(
          "title"       => __("Confirm Password",$this->td),
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
          $field["attributes"] = "tabindex=$num " . $field["attributes"];
          array_push($login_fields, $field);
        }
      }
      $textSend   = __("Receive OTP & Register",$this->td);
      $textVerify = __("Verify OTP & Register",$this->td);
      $num++;
      array_push($login_fields, array(
          "meta_name"   => "submit",
          "type"        => "button",
          "btn-type"    => "submit",
          "title"       => $this->login_mobile_otp ? $textSend : __("Register",$this->td),
          "classes"     => "button button-primary",
          "attributes"  => "tabindex=$num " . ($this->login_mobile_otp ? "data-send=\"".esc_attr($textSend)."\" data-verify=\"".esc_attr($textVerify)."\"" : ""),
          "is-required" => "yes",
          "no-label"    => "yes",
          "is-public"   => "yes",
        ));
      return apply_filters("pepro_reglogin_get_login_fields", $login_fields);
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
        "skip_profile"    => false,
        ));
      extract($config);
      $printr = "table" === $style;
      foreach ( $loop_fields as $field) {
        if ($skip_not_public && "yes" !== $field["is-public"]){ continue; }
        if ($skip_profile && "yes" !== $field["is-editable"]){ continue; }
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
    public function get_verify_email_fields()
    {
      // default login inputs
      $current_user = wp_get_current_user();
      $login_fields = array(
        array(
          "meta_name"   => "username",
          "type"        => "email",
          "title"       => __("Email",$this->td),
          "default"     => (string) $current_user->user_email,
          "is-required" => "yes",
          "is-public"   => "yes",
          "is-editable" => "no",
          "in-column"   => "no",
          "placeholder" => "",
          "classes"     => "email-verification force-ltr",
          "attributes"  => "tabindex=1 data-error-text=\"".esc_attr__("Enter username or email address correctly", $this->td)."\" ",
        ),
        array(
          "meta_name"   => "verification",
          "type"        => "text",
          "title"       => __("Verification Code",$this->td),
          "row-class"   => "hide",
          "is-public"   => "yes",
          "is-required" => "no",
          "is-editable" => "no",
          "in-column"   => "no",
          "no-label"    => "no",
          "placeholder" => "",
          "classes"     => "code-verification force-ltr",
          "attributes"  => "autocomplete=off tabindex=2 data-error-text=\"".esc_attr__("You have to enter a Verification code or leave it empty and press Enter to receive a new one", $this->td)."\" pattern=".esc_attr("^\d{{$this->verification_email_digits}}$")." maxlength=$this->verification_email_digits minlength=$this->verification_email_digits",
          "default"     => "",
        ),
      );

      // add reCaptcha
      foreach ($this->register_fileds as $value) {
        if ("recaptcha" == $value["type"] && "yes" == $value["verification"]){
          array_push($login_fields, $value);
        }
      }

      $textSend   = __("Receive Code",$this->td);
      $textVerify = __("Verify Code",$this->td);

      array_push($login_fields, array(
          "meta_name"   => "submit",
          "type"        => "button",
          "btn-type"    => "submit",
          "title"       => $textSend,
          "classes"     => "button button-primary",
          "attributes"  => "tabindex=3 data-send=\"".esc_attr($textSend)."\" data-verify=\"".esc_attr($textVerify)."\"",
          "is-required" => "yes",
          "no-label"    => "yes",
          "is-public"   => "yes",
        ));

      return apply_filters("pepro_reglogin_get_login_fields", $login_fields);
    }
    public function get_form_resetpass_fields()
    {
      // default login inputs
      $login_fields = array(
        array(
          "meta_name"   => "username",
          "type"        => "text",
          "title"       => __("Username/Email",$this->td),
          "default"     => "",
          "is-required" => "yes",
          "is-public"   => "yes",
          "is-editable" => "no",
          "in-column"   => "no",
          "placeholder" => "",
          "classes"     => "email-verification force-ltr",
          "attributes"  => "tabindex=1 data-error-text=\"".esc_attr__("Enter username or email address correctly", $this->td)."\"",
        ),
      );
      // add reCaptcha
      foreach ($this->register_fileds as $value) {
        if ("recaptcha" == $value["type"] && "yes" == $value["verification"]){
          array_push($login_fields, $value);
        }
      }
      $textSend   = __("Send reset password Email",$this->td);
      array_push($login_fields, array(
          "meta_name"   => "submit",
          "type"        => "button",
          "btn-type"    => "submit",
          "title"       => $textSend,
          "classes"     => "button button-primary",
          "attributes"  => "tabindex=3",
          "is-required" => "yes",
          "no-label"    => "yes",
          "is-public"   => "yes",
        ));

      return apply_filters("pepro_reglogin_get_login_fields", $login_fields);
    }
    public function get_verify_mobile_fields($class="button button-primary", $force=false)
    {
      $current_user = wp_get_current_user();
      $login_fields = array(
        array(
          "meta_name"   => "username",
          "type"        => "mobile",
          "title"       => __("Mobile",$this->td),
          "default"     => get_the_author_meta("user_mobile", $current_user->ID),
          "is-required" => "yes",
          "is-public"   => "yes",
          "is-editable" => "no",
          "in-column"   => "no",
          "placeholder" => "",
          "classes"     => "mobile-verification force-ltr",
          "attributes"  => "tabindex=1 autocomplete=off data-error-text=\"".esc_attr__("Enter mobile number with English numbers,<br>e.g. 09123456789", $this->td)."\" pattern=".esc_attr("^(\+98|0098|98|0)?9\d{9}$")." maxlength=14",
        ),
        array(
          "meta_name"   => "verification",
          "type"        => "text",
          "title"       => __("OTP Code",$this->td),
          "row-class"   => "hide",
          "is-public"   => "yes",
          "is-required" => "no",
          "is-editable" => "no",
          "in-column"   => "no",
          "no-label"    => "no",
          "placeholder" => "",
          "classes"     => "code-verification force-ltr",
          "attributes"  => "autocomplete=off data-error-text=\"".esc_attr__("You have to enter an OTP code or leave it empty and press Enter to receive a new one", $this->td)."\" tabindex=2 pattern=".esc_attr("^\d{{$this->verification_digits}}$")." maxlength=$this->verification_digits minlength=$this->verification_digits",
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

      // add reCaptcha
      foreach ($this->register_fileds as $value) {
        if ("recaptcha" == $value["type"] && "yes" == $value["login"]){
          array_push($login_fields, $value);
        }
      }

      $textSend   = __("Receive OTP Code",$this->td);
      $textVerify = __("Verify OTP Code",$this->td);

      array_push($login_fields, array(
          "meta_name"   => "submit",
          "type"        => "button",
          "btn-type"    => "submit",
          "title"       => $this->login_mobile_otp||$force ? $textSend : __("Login",$this->td),
          "classes"     => $class,
          "attributes"  => "tabindex=3 " . ($this->login_mobile_otp||$force ? "data-send=\"".esc_attr($textSend)."\" data-verify=\"".esc_attr($textVerify)."\"" : ""),
          "is-required" => "yes",
          "no-label"    => "yes",
          "is-public"   => "yes",
        ));

      return apply_filters("pepro_reglogin_get_login_fields", $login_fields);
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
            $redirect_to = $this->parse_redirection_url($value["url"]);
            return $redirect_to;
          }else{
            $user = wp_get_current_user();
            if (in_array($value["role"], $user->roles)) {
              $redirect_to = $this->parse_redirection_url($value["url"]);
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
      foreach ((array) $this->get_redirection_fields() as $key => $value) {
        if ("everyone" == $value["role"] && "yes" == $value["logout"]){
          $redirect_to = $this->parse_redirection_url($value["url"]);
          if ($return){ return $redirect_to; }
          wp_redirect($redirect_to);
          exit();
        }else{
          if ("yes" == $value["logout"]){
            $user = new \WP_User($user_id);
            if (is_a($user, 'WP_User' ) && $user->exists()){
              if (in_array($value["role"], $user->roles) || "everyone" == $value["role"] ) {
                $redirect_to = $this->parse_redirection_url($value["url"]);
                if ($return){ return $redirect_to; }
                wp_redirect($redirect_to);
                exit();
              }
            }
          }
        }
      }
    }
    public function parse_redirection_url($url="")
    {
      #page_id / @page_slug / {special_pages} / Full URL
      $url = trim($url);
      if ($this->startsWith($url, "#")){
        return get_permalink(ltrim($url, "#"));
      }
      if ($this->startsWith($url, "@")){
        return get_permalink(get_page_by_path(ltrim($url, "@")));
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
      $user_columns['_email_verification'] = _x("Email Verified", "metabox", $this->td);
      $user_columns['_sms_verification']   = _x("SMS Verified", "metabox", $this->td);
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
              <input class="pepro-reg-login-checkbox edit-user" style="transform: scale(0.75);" data-id="<?=$user_id;?>" data-param="pepro_user_is_email_verified" data-nonce="<?=wp_create_nonce($this->td);?>" type="checkbox" autocomplete="off" value="yes" <?=checked(true, $verfied, false);?> >
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
              <?=$mobile;?>
              <input class="pepro-reg-login-checkbox edit-user" style="transform: scale(0.75);" data-id="<?=$user_id;?>" data-param="pepro_user_is_sms_verified" data-nonce="<?=wp_create_nonce($this->td);?>" type="checkbox" autocomplete="off" value="yes" <?=checked(true, $verfied, false);?> >
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
            $value = empty($value) ? "" : "<div id='{$field["meta_name"]}__{$user_id}' style='display:none;'>$value</div><a class='thickbox' title='{$field["title"]}' href='#TB_inline?width=900&height=600&inlineId={$field["meta_name"]}__{$user_id}'>".__("Read value",$this->td)."</a>";
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
          "loading" => _x("Please wait ...", "js-translate", $this->td),
          "success" => _x("Operation done successfully", "wc-setting-js", $this->td),
          "error"   => _x("An unknown error occured", "js-translate", $this->td),
          "fixerr" => _x("Plese fix errors in form", "js-translate", $this->td),
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
        echo "<h3>".__("Personal Information",$this->td)."</h3><table class='form-table'>$fields</table>";
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
    public function error_log_dump($value='', $extas='')
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
          $user = get_user_by('email', $_POST['user_email'] );
          if (!$user){
            $_POST['user_login'] = $_POST['user_email'];
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
                $error_array["pepro_dev_login_{$field["meta_name"]}"] = _x("<strong>Error:</strong> Please check the reCAPTCHA challenge.", "reg-form-error", $this->td);
              }else{
                $errors->add("pepro_dev_login_{$field["meta_name"]}", _x("<strong>Error:</strong> Please check the reCAPTCHA challenge.", "reg-form-error", $this->td));
              }
            }
          break;
          case 'tel':
          case 'mobile':
            if ("yes" == $field["is-required"] && empty(trim($_POST[$field["meta_name"]])) ){
              if ($return_array){
                $error_array["pepro_dev_login_{$field["meta_name"]}"] = sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", $this->td), $field["title"]) ;
              }else{
                $errors->add("pepro_dev_login_{$field["meta_name"]}", sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", $this->td), $field["title"]) );
              }
            }

            $valid_mobile = $this->clean_mobile_number($_POST[$field["meta_name"]], $field["meta_name"]);
            if(!$valid_mobile){
              if ($return_array){
                $error_array["pepro_dev_login_{$field["meta_name"]}"] = _x("<strong>Error:</strong> Please enter a valid mobile number.", "reg-form-error", $this->td);
              }else{
                $errors->add("pepro_dev_login_{$field["meta_name"]}", _x("<strong>Error:</strong> Please enter a valid mobile number.", "reg-form-error", $this->td));
              }
            }
            else{
              $found_prev_user = $this->get_user_by_mobile($valid_mobile);
              if ($found_prev_user && $found_prev_user > 0){
                if ($return_array){
                  $error_array["pepro_dev_login_{$field["meta_name"]}-duplicate"] = _x("<strong>Error:</strong> This mobile number is currently in use.", "reg-form-error", $this->td);
                }else{
                  $errors->add("pepro_dev_login_{$field["meta_name"]}-duplicate", _x("<strong>Error:</strong> This mobile number is currently in use.", "reg-form-error", $this->td));
                }
              }
            }
          break;
          default:
            if ("yes" == $field["is-required"] && empty(trim($_POST[$field["meta_name"]])) ){
              if ($return_array){
                $error_array["pepro_dev_login_{$field["meta_name"]}"] = sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", $this->td), $field["title"]);
              }else{
                $errors->add("pepro_dev_login_{$field["meta_name"]}", sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", $this->td), $field["title"]) );
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
            $error_array['pepro_dev_login_password1_error'] = _x("<strong>ERROR</strong>: Password field is required.","reg-form-error", $this->td);
          }else{
            $errors->add('pepro_dev_login_password1_error',  _x("<strong>ERROR</strong>: Password field is required.","reg-form-error", $this->td) );
          }
        }
        if (empty(trim($_POST['password2']))){
          if ($return_array){
            $error_array['pepro_dev_login_password2_error'] = _x("<strong>ERROR</strong>: Confirm Password field is required.","reg-form-error", $this->td);
          }else{
            $errors->add('pepro_dev_login_password2_error',  _x("<strong>ERROR</strong>: Confirm Password field is required.","reg-form-error", $this->td) );
          }
        }
      }
      if ($this->show_password_field) {
        if ( $_POST['password1'] != $_POST['password2'] ) {
          if ($return_array){
            $error_array['pepro_dev_login_password12_error'] = _x("<strong>ERROR</strong>: Password field and Confirm Password field do not match.","reg-form-error", $this->td);
          }else{
            $errors->add('pepro_dev_login_password12_error', _x("<strong>ERROR</strong>: Password field and Confirm Password field do not match.","reg-form-error", $this->td) );
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
              $errors->add("pepro_dev_login_{$field["meta_name"]}", sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", $this->td), $field["title"]) );
            }

            $valid_mobile = $this->clean_mobile_number($_POST[$field["meta_name"]], $field["meta_name"]);

            // $this->error_log_dump($valid_mobile, "validate_before_save ~> start");

            if(!empty(trim($_POST[$field["meta_name"]])) && false == $valid_mobile){

              // $this->error_log_dump($valid_mobile, "validate_before_save ~> not valid");

              $errors->add("pepro_dev_login_{$field["meta_name"]}--invalid", _x("<strong>Error:</strong> Please enter a valid mobile number.", "reg-form-error", $this->td));
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

                  $err = sprintf(_x("<strong>Error:</strong> This mobile number is currently in use by %s.", "reg-form-error", $this->td), implode(" / ", $foundusers));
                  $errors->add("pepro_dev_login_{$field["meta_name"]}-duplicate", $err);


                }
              }
            }

          break;
          default:
            if ("yes" == $field["is-required"] && empty(trim($_POST[$field["meta_name"]])) ){
                $errors->add("pepro_dev_login_{$field["meta_name"]}", sprintf(_x("<strong>Error:</strong> Please enter %s.", "reg-form-error", $this->td), $field["title"]) );
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
          <label for="password1"><?=__('Password',$this->td);?></label>
          <input type="password" name="password1" id="password1" class="input" value="<?php echo esc_attr( wp_unslash( $password1 ) ); ?>" size="25" />
        </p>
        <p>
          <label for="password2"><?=__('Confirm Password',$this->td);?></label>
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
          "name"       => __("Home page",$this->td),
          "url"        => $this->special_pages_to_url("home")
        ),
        "profile"      => array(
          "name"       => __("Pepro Profile page",$this->td),
          "url"        => $this->special_pages_to_url("profile")
        ),
        "profile_edit" => array(
          "name"       => __("Pepro Profile Edit page",$this->td),
          "url"        => $this->special_pages_to_url("profile_edit")
        ),
        "admin"        => array(
          "name"       => __("WordPress Admin",$this->td),
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
            global $PeproDevUPS_Profile; $special_url = $PeproDevUPS_Profile->get_profile_page(["i"=>current_time("timestamp")]);
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
          "name" => __("Everyone",$this->td),
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
        "text"      => __("Text field",     $this->td),
        "textarea"  => __("Textarea field", $this->td),
        "number"    => __("Number field",   $this->td),
        "email"     => __("Email field",    $this->td),
        "mobile"    => __("Mobile field",   $this->td),
        "editor"    => __("TinyMCE Editor", $this->td),
        "select"    => __("Dropdown list",  $this->td),
        // "date"      => __("Date picker",    $this->td),
        // "color"     => __("Color selector", $this->td),
        "recaptcha" => __("reCaptcha",      $this->td),
      );
      return apply_filters( "pepro_reglogin_get_fileds_types", $fields_array);
    }
    public function pepro_profile_sections()
    {
      ob_start();
      echo "<div class='row'>";
      $this->printout_fields(array(
        "style"           => "div",
        "row_class"       => "col-6 form-group",
        "item_class"      => "form-control",
        "label_class"     => "control-label mb-1",
        "loop_fields"     => $this->register_fileds,
        "echo"            => true,
        "skip_not_public" => true,
        "skip_profile"    => true,
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
        echo "<h3>".__("Personal Information",$this->td)."</h3><table class='form-table'>$fields</table>";
      }
    }
    public function admin_init()
    {
      $get_setting_options = array(
        // peproticket_general
        array(
          "name"                                                   => "{$this->td}_general",
          "data"                                                   => array(
            "{$this->activation_status}-show_password_field"            => "yes",
            "{$this->activation_status}-auto_login_after_reg"           => "no",
            "{$this->activation_status}-verify_email"                   => "no",
            "{$this->activation_status}-verify_mobile"                  => "no",
            "{$this->activation_status}-use_mobile_as_username"         => "no",
            "{$this->activation_status}-use_email_as_username"          => "no",
            "{$this->activation_status}-hide_email_field"               => "no",
            "{$this->activation_status}-hide_username_field"            => "no",
            "{$this->activation_status}-reg_add_mobile"                 => "yes",
            "{$this->activation_status}-reg_add_firstname"              => "no",
            "{$this->activation_status}-reg_add_lastname"               => "no",
            "{$this->activation_status}-reg_add_displayname"            => "yes",
            "{$this->activation_status}-login_mobile_otp"               => "no",
            "{$this->activation_status}-sms_ultrafastsend_id"           => sprintf(__("Your verification Code [OTP] — %s",$this->td), get_bloginfo("name")),
            "{$this->activation_status}-sms_expiration"                 => "90",
            "{$this->activation_status}-email_expiration"               => "120",
            "{$this->activation_status}-verification_digits"            => "5",
            "{$this->activation_status}-verification_email_digits"      => "8",
            "{$this->activation_status}-verification_email_sender"      => "noreply",
            "{$this->activation_status}-verification_email_sender_name" => get_bloginfo('name','display'),
            "{$this->activation_status}-verification_email_template"    => $this->def_mail_body,
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
    }
    public function plugins_row_links($links)
    {
      array_push($links, "<a href='".admin_url("?page=pepro&section={$this->setting_slug}")."' target='_blank'><b>"._x("Manage Login/Register","loginregister","$this->td")."</b></a>");
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
        "catpcha"   => _x("<strong>Error:</strong> Please check the reCAPTCHA challenge.", "reg-form-error", $this->td),
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
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "force-style"; $slutter = "wp";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$slutter}", $_POST["dparam"][$data]); }

              $data    = "showlogo";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "logo";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "logo-id";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "logo-w";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "logo-h";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "logo-title";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "logo-href";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "shake";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "spb";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "b2b";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "privacy";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "nav";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "rmc";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "msg";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "error";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "forcebg";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "bgtype";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "bg-solid";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "bg-gradient1";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "bg-gradient2";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "bg-gradient3";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "bg-img";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "bg-img-id";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "bg-video";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "bg-video-id";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "bg-video-autoplay";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "bg-video-muted";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "bg-video-loop";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "link-separator";
              if(isset($_POST["dparam"][$data]) && !empty($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data    = "html-header"; $slutter = "headerhtml";
              if(isset($_POST["dparam"][$data])) { update_option("{$this->activation_status}-{$slutter}", $_POST["dparam"][$data]);}

              $data    = "html-footer"; $slutter = "footerhtml";
              if(isset($_POST["dparam"][$data])) { update_option("{$this->activation_status}-{$slutter}", $_POST["dparam"][$data]);}

              $data = "customcss";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]); }

              $data = "show_password_field";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", ($_POST["dparam"][$data])); }

              $data = "auto_login_after_reg";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", ($_POST["dparam"][$data])); }

              $data = "verify_email";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", ($_POST["dparam"][$data])); }

              $data = "verify_mobile";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", ($_POST["dparam"][$data])); }

              $data = "use_mobile_as_username";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", ($_POST["dparam"][$data])); }

              $data = "use_email_as_username";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", ($_POST["dparam"][$data])); }

              $data = "hide_email_field";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", ($_POST["dparam"][$data])); }

              $data = "hide_username_field";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", ($_POST["dparam"][$data])); }

              $data = "reg_add_mobile";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", ($_POST["dparam"][$data])); }

              $data = "reg_add_firstname";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", ($_POST["dparam"][$data])); }

              $data = "reg_add_lastname";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", ($_POST["dparam"][$data])); }

              $data = "reg_add_displayname";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", ($_POST["dparam"][$data])); }

              $data = "login_mobile_otp";
              if(isset($_POST["dparam"][$data])){ update_option("{$this->activation_status}-{$data}", ($_POST["dparam"][$data])); }

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

              $data = "verification_email_template";
              if(isset($_POST["dparam"][$data])){
                update_option("{$this->activation_status}-{$data}", $_POST["dparam"][$data]);
                if (empty($_POST["dparam"][$data])){
                  update_option("{$this->activation_status}-{$data}", $this->def_mail_body);
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
                  "msg"      => __("Settings Successfully Saved.",$this->td),
                  "dparam"   => $_POST["dparam"],
                  "login"    => untrailingslashit(wp_login_url()),
                  "loginStr" => sprintf(_x('%1$s Your login page is now here: %2$s. Bookmark this page!',"login-section",$this->td),
                  "<strong>"._x("Warning!","login-section",$this->td)."</strong>",
                  "<strong><u><a href='".untrailingslashit(wp_login_url())."' target='_blank'>".untrailingslashit(wp_login_url())."</a></u></strong>"
                ),
                  "register" => wp_registration_url(),
                  "lostpass" => wp_lostpassword_url(),
                )
              );
            break;
          default:
            wp_send_json_error(__("{$this->title} :: Incorrect Data Supplied.",$this->td));
            break;
        }
      }
    }
    public function read_opt($mc, $def="")
    {
        return get_option($mc) <> "" ? get_option($mc) : $def;
    }
    private function parseTemplate($contents){
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
  }
}
