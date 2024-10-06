<?php
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2022/02/20 01:22:20
add_thickbox();
wp_enqueue_style("wp-color-picker");
wp_enqueue_script("wp-color-picker");
wp_enqueue_style("pepro-register-fields-ide",      "{$this->assets_url}assets/ide/ace.css", array(), "1.6.0");
wp_enqueue_style("pepro-register-fields",          "{$this->assets_url}assets/register.css", array(), "1.6.0");
wp_enqueue_style("pepro-register-jqconfirm",       "{$this->assets_url}assets/jquery-confirm.css", array(), "1.6.0");
wp_enqueue_script("pepro-register-jqconfirm",      "{$this->assets_url}assets/jquery-confirm.js", array('jquery'), "1.6.0");
wp_enqueue_script("color-picker-alpha",            "{$this->assets_url}assets/wp-color-picker-alpha.min.js", array("jquery"), "1.6.0");
wp_enqueue_script("pepro-register-fields-hotkeys", "{$this->assets_url}assets/hotkeys.min.js", array('jquery'), "1.6.0");
wp_enqueue_script("pepro-register-fields-ide",     "{$this->assets_url}assets/ide/ace.js", array('jquery'), "1.6.0");
wp_enqueue_script("pepro-register-fields",         "{$this->assets_url}assets/register.js", array("jquery"), "1.6.0");
wp_localize_script("pepro-register-fields",        "_register_fields", array(
  "_added"     => __("New Field Successfully Added", "peprodev-ups"),
  "_removed"   => __("Field Successfully Removed",   "peprodev-ups"),
  "_palett"    => array('#444444', '#dd3333', '#dd9933', '#eeee22', '#81d742', '#1e73be', '#8224e3', '#ff2255', '#559999', '#99CCFF', '#00c1e8', '#F9DE0E', '#111111', '#EEEEDD'),
  "_copy"      => __("Copied!",   "peprodev-ups"),
  "saving"     => __("Saving ...",   "peprodev-ups"),
  "loading"    => _x("Please wait ...", "js-translate", "peprodev-ups"),
  "error"      => _x("An unknown error occured.", "js-translate", "peprodev-ups"),
  "errorTxt"   => _x("Error", "js-translate", "peprodev-ups"),
  "gohome_txt" => _x("Go Home", "js-translate", "peprodev-ups"),
  "confirmTxt" => _x("Confirm", "js-translate", "peprodev-ups"),
  "confirmCap" => _x("You sure to clear out all items?<red>THIS CANNOT BE UNDONE.", "js-translate", "peprodev-ups"),
  "successTtl" => _x("Success", "js-translate", "peprodev-ups"),
  "closeTxt"   => _x("Close", "js-translate", "peprodev-ups"),
  "locked"     => _x("This item is locked! To unlock, Change Login/Registeration Type field.", "js-translate", "peprodev-ups"),
  "successCap" => _x("All items deleted successfully", "js-translate", "peprodev-ups"),
  "submitTxt"  => _x("Submit", "js-translate", "peprodev-ups"),
  "okTxt"      => _x("Okay", "js-translate", "peprodev-ups"),
  "txtYes"     => _x("Yes", "js-translate", "peprodev-ups"),
  "txtNop"     => _x("No", "js-translate", "peprodev-ups"),
  "cancelbTn"  => _x("Cancel", "js-translate", "peprodev-ups"),
));

$styles = "";
$styleFiles = glob(dirname(plugin_dir_path(__FILE__)) . "/styles/*/*.css");
foreach ($styleFiles as $style) {
  $contents = '';
  $file = file($style);
  foreach ($file as $lines => $line) {
    $contents .= $line;
  }
  $badge = "";
  $styleExifDAta = $this->parseTemplate($contents);
  $theme = sprintf(_x('%1$s theme by %2$s', "theme-name", "peprodev-ups"), $styleExifDAta["name"], $styleExifDAta["designer"]);
  if (isset($styleExifDAta["original"]) && !empty($styleExifDAta["original"])  && strtolower($styleExifDAta["original"]) === "yes") {
    $badge = "data-verified='true'";
  }
  $styles .= "<option " . selected(get_option("{$this->activation_status}-style", "default.css"), basename($style), false) . " $badge value='" . esc_attr(basename($style)) . "'>" . esc_html($theme) . "</option>";
}
?>
<div class="row">
  <div class="col-lg-12 col-md-12">
    <div class="card mb-2">
      <div class="nav-tabs-navigation card-header-primary m-0">
        <div class="nav-tabs-wrapper">
          <ul class="nav nav-tabs">
            <li class="nav-item tab_smart_button">
              <a class="nav-link active show" href="#tab_smart_button"><i class="material-icons">auto_fix_high</i> <?php echo esc_html_x("Samrt Button", "login-section", "peprodev-ups"); ?></a>
            </li>
            <li class="nav-item tab_registration">
              <a class="nav-link" href="#tab_registration"><i class="material-icons">app_registration</i> <?php echo esc_html_x("Login & Registration", "login-section", "peprodev-ups"); ?></a>
            </li>
            <li class="nav-item tab_verification">
              <a class="nav-link" href="#tab_verification"><i class="material-icons">how_to_reg</i> <?php echo esc_html_x("Verification", "login-section", "peprodev-ups"); ?></a>
            </li>
            <li class="nav-item tab_redirection">
              <a class="nav-link" href="#tab_redirection"><i class="material-icons">call_split</i> <?php echo esc_html_x("Redirection", "login-section", "peprodev-ups"); ?></a>
            </li>
            <li class="nav-item tab_wp_login">
              <a class="nav-link" href="#tab_wp_login"><i class="material-icons">login</i> <?php echo esc_html_x("WordPress Built-in Login", "login-section", "peprodev-ups"); ?></a>
            </li>
            <li class="nav-item tab_advanced">
              <a class="nav-link" href="#tab_advanced"><i class="material-icons">developer_board</i> <?php echo esc_html_x("Advanced", "login-section", "peprodev-ups"); ?></a>
            </li>
            <li class="nav-item tab_migrate">
              <a class="nav-link" href="#tab_migrate"><i class="material-icons">cloud_done</i> <?php echo esc_html_x("Import/Export", "login-section", "peprodev-ups"); ?></a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <br>
    <div class="tab-content">
      <div class="tab-pane active show" id="tab_smart_button">
        <div class="row">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header card-header-primary">
                <h4 class="card-title"><?php echo esc_html_x("Samrt Button", "login-section", "peprodev-ups"); ?></h4>
                <p class="card-category"><?php echo esc_html_x("Use this button to show login/register popup to guests and welcome logged in users", "login-section", "peprodev-ups"); ?></p>
              </div>
              <div class="card-body table-responsive">
                <div class="smart_btn_workspace">
                  <p class="text-bold">
                    <?php echo esc_html_x("To set-up you just need to copy shortcode below and use it in everywhere you want e.g. header.", "login-section", "peprodev-ups"); ?>
                  </p>
                  <pre class="border p-3 text-left copymedata" style="direction: ltr"><?php echo str_replace("  ", "", '[pepro-smart-btn
                    loggedin_href="/profile" trigger=".openlogin, .openregister"
                    loggedin_class="" loggedout_class="w-btn us-btn-style_1 ush_btn_1"
                    loggedin_text="' . __("Hi {display_name}", $this->td) . '" loggedout_text="' . __("Login/Register",   $this->td) . '"
                    login_popup_title="' . __("Login",         $this->td) . '" register_popup_title="' . __("Register",   $this->td) . '"]'); ?></pre>
                  <pre class="border p-3 text-left copymedata" style="direction: ltr"><?php echo esc_html(str_replace("  ", "", '[loggedin]
                  [pepro-smart-btn
                    loggedin_href="/profile" trigger=".openlogin, .openregister, .woocommerce-info a.showlogin"
                    loggedin_class="" loggedout_class="w-btn us-btn-style_1 ush_btn_1"
                    loggedin_text="' . __("Hi {display_name}", $this->td) . '" loggedout_text="' . __("Login/Register",   $this->td) . '"
                    login_popup_title="' . __("Login",         $this->td) . '" register_popup_title="' . __("Register",   $this->td) . '"]
                  [/loggedin]
                  [guest]<a href="' . home_url("/profile?redirect_to=[current_url]") . '">ورود/ثبت نام</a>[/guest]')); ?></pre>
                  <button type="button" id="copyshortcode" class="btn btn-primary copyhwnd" data-copy=".copymedata"><span class="material-icons">content_copy</span> <?php echo __("Copy", "peprodev-ups"); ?></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_registration">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><?php echo esc_html_x("Popup or Inline Login/Registration Configuration", "login-section", "peprodev-ups"); ?></h4>
            <p class="card-category"><?php echo esc_html_x("You can control our Popup/Inline registeration form fields and configurations from this section.", "login-section", "peprodev-ups"); ?></p>
          </div>
          <div class="card-body">
            <div class="register-fields">
              <template id="raw_field">
                <div class="register-field-single p-2 mb-2 border">
                  <div class="register-field-single-title row justify-content-between align-items-center">
                    <div class="col-lg-9 ">
                      <h5 class='live-title m-0 p-2 text-primary text-bold' data-default="<?php esc_html_e("New Field", "peprodev-ups"); ?>"><?php esc_html_e("New Field", "peprodev-ups"); ?></h5>
                    </div>
                    <div class="col-lg-3 <?php echo esc_attr(is_rtl() ? "text-left" : "text-right"); ?>">
                      <div class="btn-group m-0 p-0 ">
                        <a href="javascript:;" class='btn btn-sm btn-primary register--duplicate-field'><span class="material-icons">content_copy</span></a>
                        <a href="javascript:;" class='btn btn-sm btn-primary register--clear-field'><span class="material-icons">delete</span></a>
                        <a href="javascript:;" class='btn btn-sm btn-primary register--arrow-down-field'><span class="material-icons">arrow_downward</span></a>
                        <a href="javascript:;" class='btn btn-sm btn-primary register--arrow-up-field'><span class="material-icons">arrow_upward</span></a>
                      </div>
                    </div>
                  </div>
                  <div class="register-field-single-content slide-up">
                    <!-- text|textarea|number|email|mobile|editor|date|select|color|recaptcha -->
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mt-3 field-opt-meta_name mb-2" data-show="all">
                      <div class="col-lg-4"><?php esc_html_e("Field Name (meta_name)", "peprodev-ups"); ?></div>
                      <div class="col-lg-8"><input autocomplete="off" type="text" placeholder="<?php esc_html_e("Field Name (meta_name)", "peprodev-ups"); ?>" class='form-input meta-name' name="meta_name" /></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-type" data-show="all">
                      <div class="col-lg-4"><?php esc_html_e("Field Type", "peprodev-ups"); ?></div>
                      <div class="col-lg-8">
                        <select autocomplete="off" class="filteritem single-field-type" name="type">
                          <?php
                          $get_fileds_types = $this->get_fileds_types();
                          array_walk($get_fileds_types, function ($val, $key) {
                            echo "<option value=\"$key\">$val</option>";
                          });
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-title" data-show="all">
                      <div class="col-lg-4"><?php esc_html_e("Field Title", "peprodev-ups"); ?></div>
                      <div class="col-lg-8"><input autocomplete="off" type="text" placeholder="<?php esc_html_e("Field Title", "peprodev-ups"); ?>" class='form-input single-title' name="title" /></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-placeholder" data-show="text|textarea|number|email|mobile|date">
                      <div class="col-lg-4"><?php esc_html_e("Field Placeholder", "peprodev-ups"); ?></div>
                      <div class="col-lg-8"><input autocomplete="off" type="text" placeholder="<?php esc_html_e("Field Placeholder", "peprodev-ups"); ?>" class='form-input single-placeholder' name="placeholder" /></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-classes" data-show="all">
                      <div class="col-lg-4"><?php esc_html_e("Field Classes", "peprodev-ups"); ?></div>
                      <div class="col-lg-8"><input autocomplete="off" type="text" placeholder="<?php esc_html_e("Field Classes", "peprodev-ups"); ?>" class='form-input single-classes' name="classes" /></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-attributes" data-show="text|textarea|number|email|mobile|date|select|color">
                      <div class="col-lg-4"><?php esc_html_e("Field HTML attributes", "peprodev-ups"); ?></div>
                      <div class="col-lg-8"><input autocomplete="off" type="text" placeholder="<?php esc_html_e("Field HTML attributes", "peprodev-ups"); ?>" class='form-input single-html-attributes' name="attributes" /></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-site-key" data-show="recaptcha">
                      <div class="col-lg-4"><?php esc_html_e("Site key", "peprodev-ups"); ?> <a href="https://www.google.com/recaptcha/admin/" target="_blank" class="btn btn-sm btn-round btn-group btn-info float-left m-0"><i class="fas fa-external-link-alt"></i></a></div>
                      <div class="col-lg-8"><input autocomplete="off" type="text" placeholder="<?php esc_html_e("Site key", "peprodev-ups"); ?>" class='form-input single-html-attributes' name="site-key" /></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-secret-key" data-show="recaptcha">
                      <div class="col-lg-4"><?php esc_html_e("Secret key", "peprodev-ups"); ?> <a href="https://www.google.com/recaptcha/admin/" target="_blank" class="btn btn-sm btn-round btn-group btn-info float-left m-0"><i class="fas fa-external-link-alt"></i></a></div>
                      <div class="col-lg-8"><input autocomplete="off" type="text" placeholder="<?php esc_html_e("Secret key", "peprodev-ups"); ?>" class='form-input single-html-attributes' name="secret-key" /></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-size" data-show="recaptcha">
                      <div class="col-lg-4"><?php esc_html_e("Theme Color", "peprodev-ups"); ?></div>
                      <div class="col-lg-8"><select autocomplete="off" class='form-input single-html-attributes' name="size">
                          <option value="normal"><?php esc_html_e("Normal",   "peprodev-ups"); ?></option>
                          <option value="compact"><?php esc_html_e("Compact", "peprodev-ups"); ?></option>
                        </select></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-theme" data-show="recaptcha">
                      <div class="col-lg-4"><?php esc_html_e("Widget Size", "peprodev-ups"); ?></div>
                      <div class="col-lg-8"><select autocomplete="off" class='form-input single-html-attributes' name="theme">
                          <option value="light"><?php esc_html_e("Light", "peprodev-ups"); ?></option>
                          <option value="dark"><?php esc_html_e("Dark",   "peprodev-ups"); ?></option>
                        </select></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-select-options" data-show="select">
                      <div class="col-lg-4"><?php esc_html_e("Select Field Options", "peprodev-ups"); ?></div>
                      <div class="col-lg-8"><textarea autocomplete="off" type="text" placeholder="<?php esc_html_e("Field Options per line, e.g. value:Title", "peprodev-ups"); ?>" class='form-input single-options' rows="4" name="options"></textarea></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-default" data-show="text|textarea|number|email|mobile|editor|date|select|color">
                      <div class="col-lg-4"><?php esc_html_e("Field Default Value", "peprodev-ups"); ?></div>
                      <div class="col-lg-8"><input autocomplete="off" type="text" placeholder="<?php esc_html_e("Field Default Value", "peprodev-ups"); ?>" class='form-input single-default-value' name="default" /></div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-login" data-show="recaptcha">
                      <div class="col-lg-4"><?php esc_html_e("Use in Login form?", "peprodev-ups"); ?></div>
                      <div class="col-lg-8">
                        <label class="row w-100 align-items-center pl-3 pr-1">
                          <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' checked name="login" /> <?php esc_html_e("Check to Use in Login form", "peprodev-ups"); ?>
                        </label>
                      </div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-verification" data-show="recaptcha">
                      <div class="col-lg-4"><?php esc_html_e("Use in Verification form?", "peprodev-ups"); ?></div>
                      <div class="col-lg-8">
                        <label class="row w-100 align-items-center pl-3 pr-1">
                          <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' checked name="verification" /> <?php esc_html_e("Check to Use in Verification form", "peprodev-ups"); ?>
                        </label>
                      </div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-is-required" data-show="all" data-hide="recaptcha">
                      <div class="col-lg-4"><?php esc_html_e("Is Required field?", "peprodev-ups"); ?></div>
                      <div class="col-lg-8">
                        <label class="row w-100 align-items-center pl-3 pr-1">
                          <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' name="is-required" />
                          <?php esc_html_e("Check to mark field as Required", "peprodev-ups"); ?>
                        </label>
                      </div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-is-editable" data-show="all" data-hide="recaptcha">
                      <div class="col-lg-4"><?php esc_html_e("Editable on Profile?", "peprodev-ups"); ?></div>
                      <div class="col-lg-8">
                        <label class="row w-100 align-items-center pl-3 pr-1">
                          <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' checked name="is-editable" />
                          <?php esc_html_e("Check to make field Editable on Profile", "peprodev-ups"); ?>
                        </label>
                      </div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-is-public" data-show="all" data-hide="recaptcha">
                      <div class="col-lg-4"><?php esc_html_e("Show in Registeration form?", "peprodev-ups"); ?></div>
                      <div class="col-lg-8">
                        <label class="row w-100 align-items-center pl-3 pr-1">
                          <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' checked name="is-public" />
                          <?php esc_html_e("Check to Show field in Registeration form", "peprodev-ups"); ?>
                        </label>
                      </div>
                    </div>
                    <div class="fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-in-column" data-show="all" data-hide="recaptcha">
                      <div class="col-lg-4"><?php esc_html_e("Show in Admin Column?", "peprodev-ups"); ?></div>
                      <div class="col-lg-8">
                        <label class="row w-100 align-items-center pl-3 pr-1">
                          <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' checked name="in-column" />
                          <?php esc_html_e("Check to Show field in Admin Column", "peprodev-ups"); ?>
                        </label>
                      </div>
                    </div>
                    <?php do_action("pepro_reglogin_register_fields_inner_options"); ?>
                  </div>
                </div>
              </template>
              <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <div class="card m mb-0">
                    <div class="card-body">
                      <div class="fields--tools">
                        <p class="text-bold"><?php esc_html_e("Registeration Type", "peprodev-ups"); ?></p>
                        <div class="save_checkboxes">
                          <label class="w-100 row align-items-center m-0 mb-2">
                            <input autocomplete="off" type="radio" class='form-checkbox iostoggle single-required mr-2 reglogin_type' <?php checked($this->reglogin_type === "mobile", true); ?> name="reglogin_type" value="mobile" /><?php esc_html_e("Using Mobile OTP", "peprodev-ups"); ?>
                          </label>
                          <label class="w-100 row align-items-center m-0 mb-2">
                            <input autocomplete="off" type="radio" class='form-checkbox iostoggle single-required mr-2 reglogin_type' <?php checked($this->reglogin_type === "mailotp", true); ?> name="reglogin_type" value="mailotp" /><?php esc_html_e("Using Email OTP", "peprodev-ups"); ?>
                          </label>
                          <label class="w-100 row align-items-center m-0 mb-2">
                            <input autocomplete="off" type="radio" class='form-checkbox iostoggle single-required mr-2 reglogin_type' <?php checked($this->reglogin_type === "email", true); ?> name="reglogin_type" value="email" /><?php esc_html_e("Using Email/Username & Password", "peprodev-ups"); ?>
                          </label>
                          <p class="text-bold mt-4 mb-2"><?php esc_html_e("Profile Verification Form", "peprodev-ups"); ?></p>
                          <label class="w-100 row align-items-center m-0 mb-2">
                            <input autocomplete="off" type="radio" class='form-checkbox iostoggle single-required mr-2 pro_verify' <?php checked($this->pro_verify === "none", true); ?> name="pro_verify" value="none" /><?php esc_html_e("None of Email & Mobile forms", "peprodev-ups"); ?>
                          </label>
                          <label class="w-100 row align-items-center m-0 mb-2">
                            <input autocomplete="off" type="radio" class='form-checkbox iostoggle single-required mr-2 pro_verify' <?php checked($this->pro_verify === "both", true); ?> name="pro_verify" value="both" /><?php esc_html_e("Both Email & Mobile forms", "peprodev-ups"); ?>
                          </label>
                          <label class="w-100 row align-items-center m-0 mb-2">
                            <input autocomplete="off" type="radio" class='form-checkbox iostoggle single-required mr-2 pro_verify' <?php checked($this->pro_verify === "sms", true); ?> name="pro_verify" value="sms" /><?php esc_html_e("Only SMS form", "peprodev-ups"); ?>
                          </label>
                          <label class="w-100 row align-items-center m-0 mb-2">
                            <input autocomplete="off" type="radio" class='form-checkbox iostoggle single-required mr-2 pro_verify' <?php checked($this->pro_verify === "email", true); ?> name="pro_verify" value="email" /><?php esc_html_e("Only Email form", "peprodev-ups"); ?>
                          </label>
                          <p class="text-bold mt-4 mb-2"><?php esc_html_e("Registeration Form", "peprodev-ups"); ?></p>
                          <label class="w-100 row align-items-center m-0 mb-2">
                            <input autocomplete="off" type="radio" class='form-checkbox iostoggle single-required mr-2 force_register_form' <?php checked($this->force_register_form === "none", true); ?> name="force_register_form" value="none" /><?php esc_html_e("Auto based on Active Form", "peprodev-ups"); ?>
                          </label>
                          <label class="w-100 row align-items-center m-0 mb-2">
                            <input autocomplete="off" type="radio" class='form-checkbox iostoggle single-required mr-2 force_register_form' <?php checked($this->force_register_form === "sms", true); ?> name="force_register_form" value="sms" /><?php esc_html_e("Force Mobile Registration form", "peprodev-ups"); ?>
                          </label>
                          <label class="w-100 row align-items-center m-0 mb-2">
                            <input autocomplete="off" type="radio" class='form-checkbox iostoggle single-required mr-2 force_register_form' <?php checked($this->force_register_form === "email", true); ?> name="force_register_form" value="email" /><?php esc_html_e("Force Email Registration form", "peprodev-ups"); ?>
                          </label>
                          <p class="text-bold mt-4 mb-2"><?php esc_html_e("Login/Register Form", "peprodev-ups"); ?></p>
                          <label class="w-100 row align-items-center m-0 mb-2">
                            <input autocomplete="off" type="checkbox" class='form-checkbox iostoggle single-required mr-2 show_mobile_login_form' <?php checked($this->show_mobile_login_form, true); ?> name="show_mobile_login_form" /> <?php esc_html_e("Show Mobile Login/Registration form", "peprodev-ups"); ?>
                          </label>
                          <label class="w-100 row align-items-center m-0 mb-2 mt-0">
                            <input autocomplete="off" type="checkbox" class='form-checkbox iostoggle single-required mr-2 show_email_login_form' <?php checked($this->show_email_login_form, true); ?> name="show_email_login_form" /> <?php esc_html_e("Show Email Login/Registration form", "peprodev-ups"); ?>
                          </label>
                          <label class="w-100 row align-items-center m-0 mb-2">
                            <input autocomplete="off" type="checkbox" class='form-checkbox iostoggle single-required mr-2 active_mobile_login_form' <?php checked($this->active_mobile_login_form, true); ?> name="active_mobile_login_form" /> <?php esc_html_e("Make Mobile Login/Registration Activated by Default", "peprodev-ups"); ?>
                          </label>
                          <p class="text-bold mt-4 mb-2"><?php esc_html_e("Extras", "peprodev-ups"); ?></p>
                          <label class="w-100 row align-items-center m-0 mb-2 mt-2">
                            <input autocomplete="off" type="checkbox" class='form-checkbox iostoggle single-required mr-2 auto_login_after_reg' <?php checked($this->auto_login_after_reg, true); ?> name="auto_login_after_reg" /> <?php esc_html_e("Auto-login After Registeration", "peprodev-ups"); ?>
                          </label>
                          <label class="w-100 row align-items-center m-0 mb-2">
                            <input autocomplete="off" type="checkbox" class='form-checkbox iostoggle single-required mr-2 no_popup_alert' <?php checked($this->no_popup_alert, true); ?> name="no_popup_alert" /> <?php esc_html_e("Don't use Popup after Login/Register", "peprodev-ups"); ?>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <div class="card m mb-0">
                    <div class="card-body">
                      <div class="workspace">
                        <p class="text-bold"><?php esc_html_e("Registeration Default Fields", "peprodev-ups"); ?></p>
                        <p><?php esc_html_e("To activate and show a field, click on its name and check it.", "peprodev-ups"); ?></p>
                        <div class="save_checkboxes">
                          <?php do_action("pepro_reglogin_show_hide_defaul_registeration_fields"); ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <div class="card m mb-0">
                    <div class="card-body">
                      <div class="fields--tools">
                        <p class="text-bold"><?php esc_html_e("Registeration Additional Fields", "peprodev-ups"); ?></p>
                        <a href="javascript:;" class='btn btn-sm btn-primary register--add-field'><span class="material-icons">add_circle</span> <?php esc_html_e("Add Field", "peprodev-ups"); ?></a>
                        <a href="javascript:;" class='btn btn-sm btn-primary register--toggle-fields'><span class="material-icons">expand</span> <?php esc_html_e("Collapse / Expand", "peprodev-ups"); ?></a>
                        <a href="javascript:;" class='btn btn-sm btn-danger ml-4 mr-4 register--clear-fields'><span class="material-icons">delete_sweep</span> <?php esc_html_e("Clear Fields", "peprodev-ups"); ?></a>
                      </div>
                      <div class="register-workspace workspace" data-empty="<?php esc_html_e("No registerations field found.", "peprodev-ups"); ?>"></div>
                    </div>
                  </div>
                </div>
              </div>
              <br>
              <button class="login-section-save btn btn-success btn-primary icn-btn btn-wide" integrity="<?php echo esc_attr(wp_create_nonce('peprocorenounce')); ?>" wparam="loginregister" lparam="savelogin" dparam="" fn="">
                <i class='material-icons'>save</i> <?php echo esc_html_x("Save Settings", "login-section", "peprodev-ups"); ?>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_redirection">
        <div class="row">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header card-header-primary">
                <h4 class="card-title"><?php echo esc_html_x("Redirection", "login-section", "peprodev-ups"); ?></h4>
                <p class="card-category"><?php echo esc_html_x("You can control wordpress redirection hooks from this section.", "login-section", "peprodev-ups"); ?></p>
              </div>
              <div class="card-body">
                <div class="redirection-fields">
                  <template id="redirection_raw_field">
                    <div class="redirection-field-single p-2 mb-2 border">
                      <div class="redirection-field-single-title row justify-content-between align-items-center">
                        <div class="col-lg-9 ">
                          <h5 class='live-title m-0 p-2 text-primary text-bold' data-default="<?php esc_html_e("Redirection for: ", "peprodev-ups"); ?>"><?php esc_html_e("New Redirection", "peprodev-ups"); ?></h5>
                        </div>
                        <div class="col-lg-3 <?php echo esc_attr(is_rtl() ? "text-left" : "text-right"); ?>">
                          <div class="btn-group m-0 p-0 ">
                            <a href="javascript:;" class='btn btn-sm btn-primary redirection--duplicate-field'><span class="material-icons">content_copy</span></a>
                            <a href="javascript:;" class='btn btn-sm btn-primary redirection--clear-field'><span class="material-icons">delete</span></a>
                            <a href="javascript:;" class='btn btn-sm btn-primary redirection--arrow-down-field'><span class="material-icons">arrow_downward</span></a>
                            <a href="javascript:;" class='btn btn-sm btn-primary redirection--arrow-up-field'><span class="material-icons">arrow_upward</span></a>
                          </div>
                        </div>
                      </div>
                      <div class="redirection-field-single-content">
                        <div class='fields-wrapper-inner row justify-content-between align-items-center mb-3 mt-3 field-opt-role' data-show="all">
                          <div class="col-lg-4"><?php esc_html_e("User Role", "peprodev-ups"); ?></div>
                          <div class="col-lg-8">
                            <select autocomplete="off" class="filteritem single-field-role" name="role">
                              <?php
                              $roles = $this->get_all_users_role();
                              array_walk($roles, function ($val, $key) {
                                echo "<option value=\"{$val["role"]}\">{$val["name"]}</option>";
                              }); ?>
                            </select>
                          </div>
                        </div>
                        <div class='fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-url' data-show="all">
                          <?php
                          $special_pages = $this->special_pages();
                          $newarray = $newarray2 = array();
                          foreach ($special_pages as $key => $value) {
                            $newarray[] = "{{$key}}: {$value["name"]}";
                            $newarray2[] = "<div class='mt-2 " . esc_attr(is_rtl() ? "text-right" : "text-left") . " small'><strong><copy>{{$key}}</copy></strong> : {$value["name"]}</div>";
                          }
                          ?>
                          <div id="speciallinks" class="hide">
                            <div class="mt-2 <?php echo esc_attr(is_rtl() ? "text-right" : "text-left"); ?> small"><?php _e("You can use page ID as <b>#page_id</b>, e.g. for ID 275 use <b>#275</b>", "peprodev-ups"); ?></div>
                            <div class="mt-2 <?php echo esc_attr(is_rtl() ? "text-right" : "text-left"); ?> small"><?php _e("You can use page slug as <b>@page_slug</b>, e.g. for slug about-us use <b>@about-us</b>", "peprodev-ups"); ?></div>
                            <div class="mt-2 <?php echo esc_attr(is_rtl() ? "text-right" : "text-left"); ?> small"><?php esc_html_e("You can also enter full URL for external addresses, e.g. https://google.com", "peprodev-ups"); ?></div>
                            <div class="mt-2 mb-2 <?php echo esc_attr(is_rtl() ? "text-right" : "text-left"); ?> small"><?php esc_html_e("And use following macros too:", "peprodev-ups"); ?></div>
                            <?php echo implode("", $newarray2); ?>
                            <div class='mt-2 <?php echo  esc_attr(is_rtl() ? "text-right" : "text-left"); ?> small'><strong>
                                <copy>{profile}?section=courses</copy>
                              </strong> : <?php _e("You can use page sections too, e.g. for LearnDash Courses page in profile", "peprodev-ups"); ?></div>
                          </div>
                          <div class="col-lg-4"><?php esc_html_e("Redirect to", "peprodev-ups"); ?> <a href="#TB_inline?width=800&height=500&inlineId=speciallinks" class="thickbox btn btn-sm btn-round btn-group btn-info float-left m-0"><i class="fas fa-exclamation-circle"></i></a></div>
                          <div class="col-lg-8">
                            <input autocomplete="off" title="<?php echo implode("\n", $newarray); ?>" type="url" dir="ltr" required placeholder="<?php esc_html_e('#page_id / @page_slug / {special_pages} / Full URL', "peprodev-ups"); ?>" class='form-input redirect-url' name="url" />
                          </div>
                        </div>
                        <div class='fields-wrapper-inner row justify-content-between align-items-center mb-3 field-opt-text' data-show="all">
                          <div class="col-lg-4"><?php esc_html_e("Ajax Popup Button text", "peprodev-ups"); ?></div>
                          <div class="col-lg-8">
                            <input autocomplete="off" type="text" value="<?php esc_html_e("Let's go", "peprodev-ups"); ?>" required placeholder="<?php esc_html_e('Ajax Popup Button text', "peprodev-ups"); ?>" class='form-input redirect-url' name="text" />
                          </div>
                        </div>
                        <div class='fields-wrapper-inner row justify-content-between align-items-center mb-3 mt-3 field-opt-usedfor' data-show="all">
                          <div class="col-lg-4"><?php esc_html_e("Used for", "peprodev-ups"); ?></div>
                          <div class="col-lg-8">
                            <div class='justify-content-between align-items-center mb-3 field-opt-login '>
                              <div class="col-lg-12">
                                <label class="row w-100 align-items-center">
                                  <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' checked name="login" /> <?php esc_html_e("Login Redirect", "peprodev-ups"); ?>
                                </label>
                              </div>
                            </div>
                            <div class='justify-content-between align-items-center mb-3 field-opt-register '>
                              <div class="col-lg-12">
                                <label class="row w-100 align-items-center">
                                  <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' name="register" /> <?php esc_html_e("Registeration Redirect", "peprodev-ups"); ?>
                                </label>
                              </div>
                            </div>
                            <div class='justify-content-between align-items-center mb-3 field-opt-logout '>
                              <div class="col-lg-12">
                                <label class="row w-100 align-items-center">
                                  <input autocomplete="off" type="checkbox" class='form-checkbox single-required mr-2' name="logout" /> <?php esc_html_e("Logout Redirect", "peprodev-ups"); ?>
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php do_action("pepro_reglogin_redirection_fields_inner_options"); ?>
                      </div>
                    </div>
                  </template>
                  <div class="mt-3 mb-3">
                    <a href="javascript:;" class='btn btn-sm btn-primary redirection--add-field'><span class="material-icons">add_circle</span> <?php esc_html_e("Add Rule", "peprodev-ups"); ?></a>
                    <a href="javascript:;" class='btn btn-sm btn-primary redirection--toggle-fields'><span class="material-icons">expand</span> <?php esc_html_e("Collapse / Expand", "peprodev-ups"); ?></a>
                    <a href="javascript:;" class='btn btn-sm btn-danger ml-4 mr-4 redirection--clear-fields'><span class="material-icons">delete_sweep</span> <?php esc_html_e("Clear Rules", "peprodev-ups"); ?></a>
                  </div>
                  <div class="redirection-workspace workspace" data-empty="<?php esc_html_e("No redirections rule found.", "peprodev-ups"); ?>"></div>
                </div>
                <br>
                <button class="login-section-save btn btn-success btn-primary icn-btn btn-wide" integrity="<?php echo esc_attr(wp_create_nonce('peprocorenounce')); ?>" wparam="loginregister" lparam="savelogin" dparam="" fn="">
                  <i class='material-icons'>save</i> <?php echo esc_html_x("Save Settings", "login-section", "peprodev-ups"); ?>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_verification">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><?php echo esc_html_x("Verification & Extras", "login-section", "peprodev-ups"); ?></h4>
            <p class="card-category"><?php echo esc_html_x("You can control how verification and some extra feature works from here", "login-section", "peprodev-ups"); ?></p>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-lg-6">
                <div class="card sms-general-settings">
                  <div class="card-body">
                    <div class="row justify-content-between save_sms_settings">
                      <p class="text-bold p-3"><?php esc_html_e("Config SMS Verification Codes", "peprodev-ups"); ?></p>
                      <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-sms_expiration '>
                        <div class="col-lg-6 label">
                          <span><?php esc_html_e("Verification expire duration (in seconds)", "peprodev-ups"); ?></span>
                        </div>
                        <div class="col-lg-6">
                          <input autocomplete="off" type="text" id="sms_expiration" placeholder="<?php esc_html_e("Verification expire duration", "peprodev-ups"); ?>" dir="ltr" class='form-input single-required mr-2' name="sms_expiration" value="<?php echo esc_attr($this->sms_expiration); ?>" />
                        </div>
                      </div>
                      <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-verification_digits '>
                        <div class="col-lg-6 label">
                          <span><?php esc_html_e("Verification Code length", "peprodev-ups"); ?></span>
                        </div>
                        <div class="col-lg-6">
                          <input autocomplete="off" type="text" id="verification_digits" placeholder="<?php esc_html_e("Verification Code length", "peprodev-ups"); ?>" dir="ltr" class='form-input single-required mr-2' name="verification_digits" value="<?php echo esc_attr($this->verification_digits); ?>" />
                        </div>
                      </div>
                      <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-sms_method '>
                        <div class="col-lg-6 label">
                          <span><?php esc_html_e("SMS Provider", "peprodev-ups"); ?></span>
                        </div>
                        <div class="col-lg-6">
                          <select autocomplete="off" class="form-input single-required sms_method" id="sms_method" name="sms_method">
                            <?php
                            $method   = get_option("{$this->activation_status}-sms_method", "smsir");
                            $gateways = (array) apply_filters("pepro_reglogin_sms_verification_gateways", array());
                            foreach ($gateways as $i => $g) {
                              echo "<option value=\"$i\" " . selected($method, $i, false) . ">{$g["name"]}</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card sms-gateways-settings gatewayssettings">
                  <?php
                  foreach ($gateways as $i => $g) {
                    if (is_callable($g["fn_setting"])) {
                      $setting = call_user_func($g["fn_setting"]);
                      echo "<div class='card-body $i hide'><div class='row justify-content-between save_sms_settings $i'>$setting</div></div>";
                    }
                  }
                  ?>
                </div>
                <div class="card login-authexpire save_sms_settings">
                  <div class='card-body'>
                    <p class="text-bold"><?php echo esc_html_x("Auth. Expiration", "login-section", "peprodev-ups"); ?></p>
                    <input class="form-input single-required mb-3" name="auth_expire" type="number" min="-1" step="1" lang="en_US" dir="ltr" value="<?php echo esc_attr(get_option("{$this->activation_status}-auth_expire", "0")); ?>" placeholder="<?php echo esc_html_x("Auth. Expiration", "login-section", "peprodev-ups"); ?>" />
                    <p><?php echo __("How long a user stay logged in? Enter time in hour format (<ltr>1</ltr>: one hour | <ltr>24</ltr>: one day | <ltr>168</ltr>: one week | <ltr>0</ltr>: Default | <ltr>-1</ltr>: Forevr)", "peprodev-ups"); ?></p>
                  </div>
                </div>
                <div class="card login-test-sms testotp">
                  <div class='card-body'>
                    <div class='checkotp-test'>
                      <p class="pt-3"><strong><?php esc_html_e("TEST Sending OTP SMS", "peprodev-ups"); ?></strong> <span>(<?php echo __("First Save changes (Ctrl+S), then test SMS Sending", "peprodev-ups"); ?>)</span></p>
                      <div class="row justify-content-between align-items-center">
                        <div class="col-8"><input id="sms_test" placeholder="<?php echo __("Test mobile number", $this->td); ?>" value="<?php echo esc_attr(get_the_author_meta("user_mobile", get_current_user_id())); ?>" autocomplete="off" type="text" dir="ltr" class='form-input single-required' /></div>
                        <div class="col-4"><button integrity="<?php echo esc_attr(wp_create_nonce('peprocorenounce')); ?>" class="btn btn-success btn-primary icn-btn btn-wide testotp"><i class="material-icons">send</i> <?php echo __("Send a Test SMS", $this->td); ?></button></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <div class="row justify-content-between save_email_settings ">
                      <p class="text-bold p-3"><?php esc_html_e("Config Email Settings to send Verification Codes", "peprodev-ups"); ?></p>
                      <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-email_expiration '>
                        <div class="col-lg-6 label">
                          <span><?php esc_html_e("Verification expire duration (in seconds)", "peprodev-ups"); ?></span>
                        </div>
                        <div class="col-lg-6">
                          <input autocomplete="off" type="text" id="email_expiration" placeholder="<?php esc_html_e("Verification expire duration", "peprodev-ups"); ?>" dir="ltr" class='form-input single-required mr-2' name="email_expiration" value="<?php echo esc_attr($this->email_expiration); ?>" />
                        </div>
                      </div>
                      <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-verification_email_digits '>
                        <div class="col-lg-6 label">
                          <span><?php esc_html_e("Verification Code length", "peprodev-ups"); ?></span>
                        </div>
                        <div class="col-lg-6">
                          <input autocomplete="off" type="text" id="verification_email_digits" placeholder="<?php esc_html_e("Verification Code length", "peprodev-ups"); ?>" dir="ltr" class='form-input single-required mr-2' name="verification_email_digits" value="<?php echo esc_attr($this->verification_email_digits); ?>" />
                        </div>
                      </div>
                      <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-verification_email_sender_name '>
                        <div class="col-lg-6 label">
                          <span><?php esc_html_e("Verification Email Sender name", "peprodev-ups"); ?></span>
                        </div>
                        <div class="col-lg-6">
                          <input autocomplete="off" type="text" id="verification_email_sender_name" placeholder="<?php esc_html_e("Verification Email Sender name", "peprodev-ups"); ?>" dir="ltr" class='form-input single-required mr-2' name="verification_email_sender_name" value="<?php echo esc_attr($this->verification_email_sender_name); ?>" />
                        </div>
                      </div>
                      <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-verification_email_sender '>
                        <div class="col-lg-6 label">
                          <span><?php esc_html_e("Verification Email Sender address", "peprodev-ups"); ?></span>
                        </div>
                        <div class="col-lg-6">
                          <input autocomplete="off" type="text" id="verification_email_sender" title="<?php echo "e.g. Enter noreply to send mail from noreply@" . wp_parse_url(get_bloginfo('url'), PHP_URL_HOST); ?>" placeholder="<?php echo "e.g. Enter noreply to send mail from noreply@" . wp_parse_url(get_bloginfo('url'), PHP_URL_HOST); ?>" dir="ltr" class='form-input single-required mr-2' name="verification_email_sender" value="<?php echo esc_attr($this->verification_email_sender); ?>" />
                        </div>
                      </div>
                      <div class='col-lg-12 row justify-content-between align-items-center mb-3 field-opt-verification_email_template '>
                        <div class="col-lg-12">
                          <p class="text-bold"><?php esc_html_e("Verification Email Template", "peprodev-ups"); ?></p>
                          <textarea class="codeditor" id="verification_email_template_editor" autocomplete="off" spellcheck="false" dir="ltr" rows="8" cols="80"><?php echo $this->verification_email_template; ?></textarea>
                          <textarea class="codeditor" id="verification_email_template" autocomplete="off" name="verification_email_template" spellcheck="false" dir="ltr" rows="8" cols="80" style="display:none !important;"><?php echo $this->verification_email_template; ?></textarea>
                          <p class="text-bold"><?php esc_html_e("Available tags: ", "peprodev-ups"); ?></p>
                          <?php
                          $tags = (array) apply_filters("pepro_reglogin_verification_email_replacements", array(
                            "[OTP]",
                            "[request_email]",
                            "[username]",
                            "[first_name]",
                            "[last_name]",
                            "[display_name]",
                            "[user_email]"
                          ));
                          foreach ($tags as $key) {
                            echo "<copy>$key</copy> ";
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <button class="login-section-save btn btn-success btn-primary icn-btn btn-wide" integrity="<?php echo esc_attr(wp_create_nonce('peprocorenounce')); ?>" wparam="loginregister" lparam="savelogin" dparam="" fn="">
              <i class='material-icons'>save</i> <?php echo esc_html_x("Save Settings", "login-section", "peprodev-ups"); ?>
            </button>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_wp_login">
        <div class="row">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header card-header-primary">
                <h4 class="card-title"><?php echo esc_html_x("WordPress Built-in Login", "login-section", "peprodev-ups"); ?></h4>
                <p class="card-category"><?php echo esc_html_x("You can control WordPress Built-in login screen appearance from here.", "login-section", "peprodev-ups"); ?></p>
              </div>
              <div class="card-body table-responsive">
                <table class="table pepcappearance">
                  <tbody>
                    <tr>
                      <td><?php echo esc_html_x("Login Theme", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <select id="login-section-style">
                          <?php echo $styles; ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td><?php echo esc_html_x("WordPress Style", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <a class='btncheckbox' data-text-on='<?php echo esc_html_x("Overwite WordPress Style with Theme", "login-section", "peprodev-ups"); ?>' data-text-off='<?php echo esc_html_x("Load with WordPress Style with Theme", "login-section", "peprodev-ups"); ?>' data-on='invert_colors_off' data-off='invert_colors' data-checked='<?php echo esc_attr(get_option("{$this->activation_status}-wp", "false") === "true" ? "true" : "false"); ?>' id="login-section-style-force"></a>
                      </td>
                    </tr>
                    <tr>
                      <td><?php echo esc_html_x("Show Logo", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <a class='btncheckbox' data-text-on='<?php echo esc_html_x("Yes, Show it", "login-section", "peprodev-ups"); ?>' data-text-off='<?php echo esc_html_x("No, Hide it", "login-section", "peprodev-ups"); ?>' data-on='visibility' data-togglel='[showonlogoshow]' data-off='visibility_off' data-checked='<?php echo esc_attr(get_option("{$this->activation_status}-showlogo", "false") === "true" ? "true" : "false"); ?>' id="login-section-show-logo"></a>
                      </td>
                    </tr>
                    <tr showonlogoshow="true">
                      <td><?php echo esc_html_x("Logo Image", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <input type="hidden" data-id="<?php echo esc_attr(get_option("{$this->activation_status}-logo-id", "")); ?>" id="login-section-logo" value="<?php echo esc_attr(get_option("{$this->activation_status}-logo", "")); ?>" class="form-control primary" placeholder="<?php echo esc_html_x("Logo URL", "login-section", "peprodev-ups"); ?>" />
                        <div class="flex">
                          <img style="border-radius: 5px;" src="<?php echo esc_attr(get_option("{$this->activation_status}-logo", "")); ?>" id="profile-img" width="86px" />
                          <button type="button" style="padding: 12px; display: block;" class="btn btn-primary mediapicker icn-btn mr-4 ml-4" data-ref="#login-section-logo" data-ref4="#profile-img" data-title="<?php echo esc_html_x("Select Custom Image", "login-section", "peprodev-ups"); ?>"><i class='material-icons'>cloud_upload</i> <?php echo esc_html_x("Select or Upload Custom Image", "login-section", "peprodev-ups"); ?></button>
                        </div>
                      </td>
                    </tr>
                    <tr showonlogoshow="true">
                      <td><?php echo esc_html_x("Logo Dimensions", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <div class="flex">
                          <input type="text" id="login-section-logo-w" title="<?php echo esc_html_x("Logo Width (e.g. 84px)", "login-section", "peprodev-ups"); ?>" value="<?php echo esc_attr(get_option("{$this->activation_status}-logo-w", "84px")); ?>" class="text-center form-control primary" placeholder="<?php echo esc_html_x("Logo Width (e.g. 84px)", "login-section", "peprodev-ups"); ?>" />
                          &times;
                          <input type="text" id="login-section-logo-h" title="<?php echo esc_html_x("Logo Height (e.g. 84px)", "login-section", "peprodev-ups"); ?>" value="<?php echo esc_attr(get_option("{$this->activation_status}-logo-h", "84px")); ?>" class="text-center form-control primary" placeholder="<?php echo esc_html_x("Logo Height (e.g. 84px)", "login-section", "peprodev-ups"); ?>" />
                        </div>
                      </td>
                    </tr>
                    <tr showonlogoshow="true">
                      <td><?php echo esc_html_x("Logo Href", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <input type="text" id="login-section-logohref" value="<?php echo esc_attr(get_option("{$this->activation_status}-logo-href", home_url())); ?>" class="form-control primary" placeholder="<?php echo esc_html_x("Login slug", "login-section", "peprodev-ups"); ?>" />
                      </td>
                    </tr>
                    <tr showonlogoshow="true">
                      <td><?php echo esc_html_x("Logo Title", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <input type="text" id="login-section-logotitle" value="<?php echo esc_attr(get_option("{$this->activation_status}-logo-title", get_bloginfo('name'))); ?>" class="form-control primary" placeholder="<?php echo esc_html_x("Login slug", "login-section", "peprodev-ups"); ?>" />
                      </td>
                    </tr>
                    <tr>
                      <td><?php echo esc_html_x("'Remeber Me' Checkbox", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <a class='btncheckbox' data-text-on='<?php echo esc_html_x("Yes, Show it", "login-section", "peprodev-ups"); ?>' data-text-off='<?php echo esc_html_x("No, Hide it", "login-section", "peprodev-ups"); ?>' data-on='visibility' data-off='visibility_off' data-checked='<?php echo esc_attr(get_option("{$this->activation_status}-rmc", "true") === "true" ? "true" : "false"); ?>' id="login-section-rmc"></a>
                      </td>
                    </tr>
                    <tr>
                      <td><?php echo esc_html_x("Back to Blog Link", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <a class='btncheckbox' data-text-on='<?php echo esc_html_x("Yes, Show it", "login-section", "peprodev-ups"); ?>' data-text-off='<?php echo esc_html_x("No, Hide it", "login-section", "peprodev-ups"); ?>' data-on='visibility' data-off='visibility_off' data-checked='<?php echo esc_attr(get_option("{$this->activation_status}-b2b", "true") === "true" ? "true" : "false"); ?>' id="login-section-b2b"></a>
                      </td>
                    </tr>
                    <tr>
                      <td><?php echo esc_html_x("Privacy Link", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <a class='btncheckbox' data-text-on='<?php echo esc_html_x("Yes, Show it", "login-section", "peprodev-ups"); ?>' data-text-off='<?php echo esc_html_x("No, Hide it", "login-section", "peprodev-ups"); ?>' data-on='visibility' data-off='visibility_off' data-checked='<?php echo esc_attr(get_option("{$this->activation_status}-privacy", "true") === "true" ? "true" : "false"); ?>' id="login-section-privacy"></a>
                      </td>
                    </tr>
                    <tr>
                      <td><?php echo esc_html_x("Show Password Button", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <a class='btncheckbox' data-text-on='<?php echo esc_html_x("Yes, Show it", "login-section", "peprodev-ups"); ?>' data-text-off='<?php echo esc_html_x("No, Hide it", "login-section", "peprodev-ups"); ?>' data-on='visibility' data-off='visibility_off' data-checked='<?php echo esc_attr(get_option("{$this->activation_status}-spb", "true") === "true" ? "true" : "false"); ?>' id="login-section-spb"></a>
                      </td>
                    </tr>
                    <tr>
                      <td><?php echo esc_html_x("Force Background?", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <a class='btncheckbox' data-text-on='<?php echo esc_html_x("Yes, Apply My Setting for Background", "login-section", "peprodev-ups"); ?>' data-text-off='<?php echo esc_html_x("No, Use Default Login Theme's Background setting", "login-section", "peprodev-ups"); ?>' data-on='visibility' data-togglel='[onlyforcedbg]' data-off='visibility_off' data-checked='<?php echo esc_attr(get_option("{$this->activation_status}-forcebg", "false") === "true" ? "true" : "false"); ?>' id="login-section-forcebg"></a>
                      </td>
                    </tr>
                    <tr onlyforcedbg="true">
                      <td><?php echo esc_html_x("Background Type", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <select id="login-section-bgtype" class="filteritem" data-filter='showfilteredbybgtype'>
                          <?php echo "<option filter-item='solid'" . selected(get_option("{$this->activation_status}-bgtype", "color"), "color", false) . " value='color'>" . esc_html_x("Solid Color", "login-section", "peprodev-ups") . "</option>"; ?>
                          <?php echo "<option filter-item='gradient'" . selected(get_option("{$this->activation_status}-bgtype", "color"), "gradient", false) . " value='gradient'>" . esc_html_x("Gradient Color", "login-section", "peprodev-ups") . "</option>"; ?>
                          <?php echo "<option filter-item='image'" . selected(get_option("{$this->activation_status}-bgtype", "color"), "image", false) . " value='image'>" . esc_html_x("Image", "login-section", "peprodev-ups") . "</option>"; ?>
                          <?php echo "<option filter-item='video'" . selected(get_option("{$this->activation_status}-bgtype", "color"), "video", false) . " value='video'>" . esc_html_x("Video", "login-section", "peprodev-ups") . "</option>"; ?>
                        </select>
                        <div showfilteredbybgtype="solid">
                          <input type="text" colorpicker="true" data-alpha="true" id="login-section-bg-solid" value="<?php echo esc_attr(get_option("{$this->activation_status}-bg-solid", "white")); ?>" class="form-control primary" placeholder="<?php echo esc_html_x("Background Solid Color", "login-section", "peprodev-ups"); ?>" />
                        </div>
                        <div showfilteredbybgtype="gradient">
                          <input type="text" autocomplete="off" data-alpha="true" colorpicker="true" id="login-section-bg-gradient1" value="<?php echo esc_attr(get_option("{$this->activation_status}-bg-gradient1", "#6a11cb")); ?>" class="form-control primary" placeholder="<?php echo esc_html_x("Background Solid Color", "login-section", "peprodev-ups"); ?>" />
                          <input type="text" autocomplete="off" data-alpha="true" colorpicker="true" id="login-section-bg-gradient2" value="<?php echo esc_attr(get_option("{$this->activation_status}-bg-gradient2", "#2575fc")); ?>" class="form-control primary float-right" placeholder="<?php echo esc_html_x("Background Solid Color", "login-section", "peprodev-ups"); ?>" />
                          <div style="margin: 1rem 0 0 0;" class="text-center">👇 <info><?php echo esc_html_x("Linear Gradient direction or Angels", "login-section", "peprodev-ups"); ?></info> 👇</div>
                          <input type="text" id="login-section-bg-gradient3" value="<?php echo esc_attr(get_option("{$this->activation_status}-bg-gradient3", "to left")); ?>" class="form-control primary" title="<?php echo esc_attr(sprintf(_x("Linear Gradient direction or Angels (and extra color steps)%sExamples: to left | to top | 65deg | 90deg, blue, green, rgba(203, 24, 201, 0.86), #13c253", "login-section", "peprodev-ups"), PHP_EOL)); ?>" placeholder="<?php echo esc_html_x("e.g. to left | to top | 65deg | 90deg, blue, green, rgba(203, 24, 201, 0.86), #13c253", "login-section", "peprodev-ups"); ?>" />
                        </div>
                        <div showfilteredbybgtype="image">
                          <input type="hidden" data-id="<?php echo esc_attr(get_option("{$this->activation_status}-bg-img-id", "")); ?>" id="login-section-bg-img" value="<?php echo esc_attr(get_option("{$this->activation_status}-bg-img", "")); ?>" class="form-control primary" />
                          <div class="flex">
                            <img style="border-radius: 5px;" src="<?php echo esc_attr(get_option("{$this->activation_status}-bg-img", "")); ?>" id="login-section-bg-img-preview" width="86px" />
                            <button type="button" style="padding: 12px; display: block;" class="btn btn-primary mediapicker icn-btn mr-4 ml-4" data-ref="#login-section-bg-img" data-ref4="#login-section-bg-img-preview" data-title="<?php echo esc_html_x("Select Custom Background Image", "login-section", "peprodev-ups"); ?>"><i class='material-icons'>cloud_upload</i> <?php echo esc_html_x("Select or Upload Background Image", "login-section", "peprodev-ups"); ?></button>
                          </div>
                        </div>
                        <div showfilteredbybgtype="video">
                          <input type="hidden" data-id="<?php echo esc_attr(get_option("{$this->activation_status}-bg-video-id", "")); ?>" id="login-section-bg-video" value="<?php echo esc_attr(get_option("{$this->activation_status}-bg-video", "")); ?>" class="form-control primary" />
                          <div class="flex">
                            <video width="200" controls>
                              <source style="border-radius: 5px;" id="login-section-bg-video-preview" src="<?php echo esc_attr(get_option("{$this->activation_status}-bg-video", "")); ?>" type="video/mp4" />
                              Your browser does not support HTML5 video.
                            </video>
                            <button type="button" style="padding: 12px; display: block;" class="btn btn-primary mediapicker icn-btn mr-4 ml-4" data-picktype="video/mp4" data-ref="#login-section-bg-video" data-ref4="#login-section-bg-video-preview" data-title="<?php echo esc_html_x("Select Custom Background Video", "login-section", "peprodev-ups"); ?>"><i class='material-icons'>cloud_upload</i> <?php echo esc_html_x("Upload Video", "login-section", "peprodev-ups"); ?></button>
                          </div>
                          <div class="flex margin-top">
                            <a class='mr-2 ml-2 btncheckbox' data-text-on='<?php echo esc_html_x("Autoplay: ON", "login-section", "peprodev-ups"); ?>' data-text-off='<?php echo esc_html_x("Autoplay: OFF", "login-section", "peprodev-ups"); ?>' data-on='play_circle_outline' data-off='pause_circle_outline' data-checked='<?php echo esc_attr(get_option("{$this->activation_status}-bg-video-autoplay", "true") === "true" ? "true" : "false"); ?>' id="login-section-bg-video-autoplay"></a>
                            <a class='mr-2 ml-2 btncheckbox' data-text-on='<?php echo esc_html_x("Muted", "login-section", "peprodev-ups"); ?>' data-text-off='<?php echo esc_html_x("Sound", "login-section", "peprodev-ups"); ?>' data-on='volume_off' data-off='volume_up' data-checked='<?php echo esc_attr(get_option("{$this->activation_status}-bg-video-muted", "true") === "true" ? "true" : "false"); ?>' id="login-section-bg-video-muted"></a>
                            <a class='mr-2 ml-2 btncheckbox' data-text-on='<?php echo esc_html_x("Loop: ON", "login-section", "peprodev-ups"); ?>' data-text-off='<?php echo esc_html_x("Loop: OFF", "login-section", "peprodev-ups"); ?>' data-on='sync' data-off='sync_disabled' data-checked='<?php echo esc_attr(get_option("{$this->activation_status}-bg-video-loop", "true") === "true" ? "true" : "false"); ?>' id="login-section-bg-video-loop"></a>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <br>
                <button class="login-section-save btn btn-success btn-primary icn-btn btn-wide" integrity="<?php echo esc_attr(wp_create_nonce('peprocorenounce')); ?>" wparam="loginregister" lparam="savelogin" dparam="" fn="">
                  <i class='material-icons'>save</i> <?php echo esc_html_x("Save Settings", "login-section", "peprodev-ups"); ?>
                </button>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header card-header-primary">
                <h4 class="card-title"><?php echo esc_html_x("Security & Permalinks", "login-section", "peprodev-ups"); ?></h4>
                <p class="card-category"><?php echo esc_html_x("You can control WordPress Built-in Login screen permalinks and Auth. Expiration from here", "login-section", "peprodev-ups"); ?></p>
              </div>
              <div class="card-body table-responsive">
                <div class="mt-2 mb-4">
                  <label class="row w-100 align-items-center m-0 mb-2" for="login-section-active">
                    <input autocomplete="off" type="checkbox" <?php checked(get_option("{$this->activation_status}-activesecurity", ""), "yes", true); ?> class="form-checkbox iostoggle single-required mr-2 login-section-active" id="login-section-active" name="login-section-active" value="yes">
                    <?php echo esc_html_x("Activate Permalink section?", "login-section", "peprodev-ups"); ?>
                  </label>
                </div>
                <div id="alert-primary">
                  <div class="alert alert-success alert-dismissible fade show" role="alert"> <?php printf(_x('%1$s Your login page is: %2$s. Bookmark this page!', "login-section", "peprodev-ups"), "<strong>" . _x("Attention!", "login-section", "peprodev-ups") . "</strong>", "<strong><u><a href='" . wp_login_url() . "' target='_blank'>" . untrailingslashit(wp_login_url()) . "</a></u></strong>"); ?></div>
                </div>
                <table class="table pepcappearance activesecurity-table">
                  <tbody>
                    <tr>
                      <td><?php echo esc_html_x("Login Base Slug", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <input type="text" id="login-section-loginslug" value="<?php echo esc_attr(get_option("whl_page", "login")); ?>" class="form-control primary" placeholder="<?php echo esc_html_x("Login slug", "login-section", "peprodev-ups"); ?>" />
                      </td>
                    </tr>
                    <tr>
                      <td><?php echo esc_html_x("Redirect Slug", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <input type="text" id="login-section-redirectslug" value="<?php echo esc_attr(get_option("whl_redirect_admin", "403")); ?>" class="form-control primary" placeholder="<?php echo esc_html_x("Redirect slug", "login-section", "peprodev-ups"); ?>" />
                      </td>
                    </tr>
                    <tr>
                      <td><?php echo esc_html_x("Shake Effect", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <a class='btncheckbox' data-text-on='<?php echo esc_html_x("Yes, Keep it", "login-section", "peprodev-ups"); ?>' data-text-off='<?php echo esc_html_x("No, Remove it", "login-section", "peprodev-ups"); ?>' data-on='visibility' data-off='visibility_off' data-checked='<?php echo esc_attr(get_option("{$this->activation_status}-shake", "true") === "true" ? "true" : "false"); ?>' id="login-section-shake"></a>
                      </td>
                    </tr>
                    <tr>
                      <td><?php echo esc_html_x("Login Errors", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <a class='btncheckbox' data-text-on='<?php echo esc_html_x("Yes, Show them", "login-section", "peprodev-ups"); ?>' data-text-off='<?php echo esc_html_x("No, Hide them", "login-section", "peprodev-ups"); ?>' data-on='visibility' data-off='visibility_off' data-checked='<?php echo esc_attr(get_option("{$this->activation_status}-error", "true") === "true" ? "true" : "false"); ?>' id="login-section-error"></a>
                      </td>
                    </tr>
                    <tr>
                      <td><?php echo esc_html_x("Login Messages", "login-section", "peprodev-ups"); ?></td>
                      <td>
                        <a class='btncheckbox' data-text-on='<?php echo esc_html_x("Yes, Show them", "login-section", "peprodev-ups"); ?>' data-text-off='<?php echo esc_html_x("No, Hide them", "login-section", "peprodev-ups"); ?>' data-on='visibility' data-off='visibility_off' data-checked='<?php echo esc_attr(get_option("{$this->activation_status}-msg", "true") === "true" ? "true" : "false"); ?>' id="login-section-msg"></a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_advanced">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><?php echo esc_html_x("Custom Codes", "login-section", "peprodev-ups"); ?></h4>
            <p class="card-category"><?php echo esc_html_x("Add extra CSS or Custom Heading/Footer (supports shortcodes)", "login-section", "peprodev-ups"); ?></p>
          </div>
          <div class="card-body table-responsive">

            <div class="row">
              <div class="col-lg-6">
                <p class="text-bold"><?php echo esc_html_x("Login/Register Heading Content (html, shortcode)", "login-section", "peprodev-ups"); ?></p>
                <textarea autocomplete="off" name="headerhtml" spellcheck="false" dir="ltr" rows="8" cols="80" style="display:none !important;" id="headerhtml"><?php echo stripslashes(get_option("{$this->activation_status}-headerhtml", "")); ?></textarea>
                <textarea autocomplete="off" spellcheck="false" dir="ltr" rows="8" cols="80" id="headerhtml_editor"><?php echo stripslashes(get_option("{$this->activation_status}-headerhtml", "")); ?></textarea>
              </div>
              <div class="col-lg-6">
                <p class="text-bold"><?php echo esc_html_x("Login/Register Footer Content (html, shortcode)", "login-section", "peprodev-ups"); ?></p>
                <textarea autocomplete="off" name="footerhtml" spellcheck="false" dir="ltr" rows="8" cols="80" style="display:none !important;" id="footerhtml"><?php echo stripslashes(get_option("{$this->activation_status}-footerhtml", "")); ?></textarea>
                <textarea autocomplete="off" spellcheck="false" dir="ltr" rows="8" cols="80" id="footerhtml_editor"><?php echo stripslashes(get_option("{$this->activation_status}-footerhtml", "")); ?></textarea>
              </div>
              <div class="col-12">
                <p class="text-bold"><?php echo esc_html_x("Login/Register Custom CSS Codes", "login-section", "peprodev-ups"); ?></p>
                <textarea autocomplete="off" name="customcss" spellcheck="false" dir="ltr" rows="8" cols="80" id="customcss" style="display:none !important;"><?php echo stripslashes(get_option("{$this->activation_status}-customcss", "")); ?></textarea>
                <textarea autocomplete="off" spellcheck="false" dir="ltr" rows="8" cols="80" id="customcss_editor"><?php echo stripslashes(get_option("{$this->activation_status}-customcss", "")); ?></textarea>
              </div>
            </div>

            <br>
            <button class="login-section-save btn btn-success btn-primary icn-btn btn-wide" integrity="<?php echo esc_attr(wp_create_nonce('peprocorenounce')); ?>" wparam="loginregister" lparam="savelogin" dparam="" fn="">
              <i class='material-icons'>save</i> <?php echo esc_html_x("Save Settings", "login-section", "peprodev-ups"); ?>
            </button>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_migrate">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><?php echo esc_html_x("Import/Export", "login-section", "peprodev-ups"); ?></h4>
            <p class="card-category"><?php echo esc_html_x("Sync settings between your sites or make a backup for future", "login-section", "peprodev-ups"); ?></p>
          </div>
          <div class="card-body table-responsive">
            <div class="row">
              <div class="col-lg-6 register-fields-import-export">
                <p class="text-bold"><?php esc_html_e("Import/Export Registeration-Additional fields as JSON data", "peprodev-ups"); ?></p>
                <textarea class="codeditor" id="register_fileds_editor" autocomplete="off" spellcheck="false" dir="ltr" rows="8" cols="80"></textarea>
                <textarea class="codeditor" id="register_fileds" autocomplete="off" spellcheck="false" dir="ltr" rows="8" cols="80" style="display:none !important;"><?php echo wp_unslash(get_option("pepro-profile-register-fileds")); ?></textarea>
              </div>
              <div class="col-lg-6 redirection-fields-import-export">
                <p class="text-bold"><?php esc_html_e("Import/Export Redirection-rules as JSON data", "peprodev-ups"); ?></p>
                <textarea class="codeditor" id="redirection_fileds_editor" autocomplete="off" spellcheck="false" dir="ltr" rows="8" cols="80"></textarea>
                <textarea class="codeditor" id="redirection_fileds" autocomplete="off" spellcheck="false" dir="ltr" rows="8" cols="80" style="display:none !important;"><?php echo wp_unslash(get_option("pepro-profile-redirection-fileds")); ?></textarea>
              </div>
            </div>

            <br>
            <button class="login-section-save btn btn-success btn-primary icn-btn btn-wide" integrity="<?php echo esc_attr(wp_create_nonce('peprocorenounce')); ?>" wparam="loginregister" lparam="savelogin" dparam="" fn="">
              <i class='material-icons'>save</i> <?php echo esc_html_x("Import (Overwrite) Settings", "login-section", "peprodev-ups"); ?>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>