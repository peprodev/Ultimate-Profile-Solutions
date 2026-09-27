<?php
/*
 * "Sign in with Google" (OAuth 2.0 / OpenID Connect, no SDK).
 *
 * Flow: a button links to home_url("/?pepro_social=google&_wpnonce=...") which
 * stores a random state (transient + browser cookie) and redirects to Google.
 * Google sends the visitor back to the same URL with ?code&state; the code is
 * exchanged for tokens server side, the ID token claims (aud, iss, exp, nonce)
 * and the userinfo (sub, verified email) are checked, then the user with that
 * email is logged in, or created when registration is allowed. The redirect
 * target follows the plugin rules (explicit redirect_to, redirection rules,
 * profile dashboard). Errors bring the visitor back with a friendly notice.
 *
 * Settings (Login/Register > Social Login): google_login_enabled,
 * google_client_id, google_client_secret, google_button_text,
 * google_create_users. Secrets are never printed or logged.
 */

namespace PeproDev\PeproCore\RegLogin;

use PeproDevUPS;

defined("ABSPATH") or die("PeproDev Ultimate Profile Solutions :: Unauthorized Access! (https://pepro.dev/)");

final class PeproSocial_Google extends PeproDevUPS {
  public $td      = "peprodev-ups";
  public $db_slug = "peprodev-ups";
  const AUTH_URL     = "https://accounts.google.com/o/oauth2/v2/auth";
  const TOKEN_URL    = "https://oauth2.googleapis.com/token";
  const USERINFO_URL = "https://openidconnect.googleapis.com/v1/userinfo";
  const COOKIE       = "pepro_google_state";
  const STATE_TTL    = 600;
  protected static $notice_printed = false;

  public function __construct() {
    add_action("init", array($this, "handle_request"), 20);
    add_shortcode("pepro-google-login", array($this, "shortcode"));
    add_action("pepro_reglogin_form_top", array($this, "print_notice"));
    add_action("pepro_reglogin_form_bottom", array($this, "print_form_button"));
    add_action("login_form", array($this, "print_wp_login_button"));
    add_action("register_form", array($this, "print_wp_login_button"));
    add_filter("login_message", array($this, "wp_login_message"));
    add_action("pepro_reglogin_settings_tabs_nav", array($this, "settings_tab_nav"));
    add_action("pepro_reglogin_settings_tabs_content", array($this, "settings_tab_content"));
    add_action("pepro_reglogin_save_settings", array($this, "save_settings"));
    add_filter("peprodev-ups/wpml/strings", array($this, "wpml_strings"));
  }

  #region settings
  public function is_enabled() {
    return "yes" === $this->read("google_login_enabled", "no") && "" !== trim((string) $this->read("google_client_id", "")) && "" !== trim((string) $this->read("google_client_secret", ""));
  }
  public static function redirect_uri() {
    return add_query_arg("pepro_social", "google", home_url("/"));
  }
  public function button_text() {
    $text = trim((string) $this->read("google_button_text", ""));
    if ("" === $text) return __("Sign in with Google", "peprodev-ups");
    return \PeproDevUPS_WPML::translate("social: google button text", $text);
  }
  public function wpml_strings($strings = array()) {
    $strings["social: google button text"] = trim((string) $this->read("google_button_text", ""));
    return $strings;
  }
  /**
   * Save handler, called from the Login/Register "savelogin" admin AJAX action
   * (nonce and manage_options already checked there).
   */
  public function save_settings($dparam) {
    if (!is_array($dparam) || !current_user_can("manage_options")) return;
    foreach (array("google_login_enabled", "google_create_users") as $key) {
      if (isset($dparam[$key])) $this->set($key, "yes" === $dparam[$key] ? "yes" : "no");
    }
    if (isset($dparam["google_client_id"])) $this->set("google_client_id", sanitize_text_field(wp_unslash($dparam["google_client_id"])));
    if (isset($dparam["google_button_text"])) $this->set("google_button_text", sanitize_text_field(wp_unslash($dparam["google_button_text"])));
    // the saved secret is never sent to the browser: an empty field keeps it, "clear" checkbox removes it
    if (isset($dparam["google_client_secret_clear"]) && "yes" === $dparam["google_client_secret_clear"]) {
      $this->set("google_client_secret", "");
    } elseif (isset($dparam["google_client_secret"]) && "" !== trim((string) $dparam["google_client_secret"])) {
      $this->set("google_client_secret", sanitize_text_field(wp_unslash($dparam["google_client_secret"])));
    }
  }
  public function settings_tab_nav() {
    ?>
    <li class="nav-item tab_social">
      <a class="nav-link" href="#tab_social"><i class="material-icons">group_add</i> <?php esc_html_e("Social Login", "peprodev-ups"); ?></a>
    </li>
    <?php
  }
  public function settings_tab_content() {
    $has_secret = "" !== trim((string) $this->read("google_client_secret", ""));
    ?>
    <div class="tab-pane" id="tab_social">
      <div class="card">
        <div class="card-header card-header-primary">
          <h4 class="card-title"><?php esc_html_e("Sign in with Google", "peprodev-ups"); ?></h4>
          <p class="card-category"><?php esc_html_e("Let visitors log in or register with their Google account (OAuth 2.0 / OpenID Connect).", "peprodev-ups"); ?></p>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-7">
              <div class="save_checkboxes">
                <label class="w-100 row align-items-center m-0 mb-2">
                  <input autocomplete="off" type="checkbox" class="form-checkbox iostoggle mr-2" name="google_login_enabled" <?php checked("yes", $this->read("google_login_enabled", "no")); ?> /> <?php esc_html_e("Enable Sign in with Google", "peprodev-ups"); ?>
                </label>
                <label class="w-100 row align-items-center m-0 mb-2">
                  <input autocomplete="off" type="checkbox" class="form-checkbox iostoggle mr-2" name="google_create_users" <?php checked("yes", $this->read("google_create_users", "no")); ?> /> <?php esc_html_e("Create new users when registration is open", "peprodev-ups"); ?>
                </label>
                <p class="small text-muted mb-3">
                  <?php
                  echo esc_html(get_option("users_can_register") ? __("Registration is open (Settings > General > Membership).", "peprodev-ups") : __("Registration is closed (Settings > General > Membership): only existing users can sign in with Google.", "peprodev-ups"));
                  ?>
                </p>
                <?php if ($has_secret) : ?>
                  <label class="w-100 row align-items-center m-0 mb-2">
                    <input autocomplete="off" type="checkbox" class="form-checkbox mr-2" name="google_client_secret_clear" /> <?php esc_html_e("Remove the saved Client Secret", "peprodev-ups"); ?>
                  </label>
                <?php endif; ?>
              </div>
              <div class="save_sms_settings">
                <div class="row justify-content-between align-items-center mb-3">
                  <div class="col-lg-4 label"><span><?php esc_html_e("Client ID", "peprodev-ups"); ?></span></div>
                  <div class="col-lg-8"><input autocomplete="off" type="text" dir="ltr" class="form-input w-100" name="google_client_id" value="<?php echo esc_attr($this->read("google_client_id", "")); ?>" placeholder="xxxxxxxx.apps.googleusercontent.com" /></div>
                </div>
                <div class="row justify-content-between align-items-center mb-3">
                  <div class="col-lg-4 label"><span><?php esc_html_e("Client Secret", "peprodev-ups"); ?></span></div>
                  <div class="col-lg-8"><input autocomplete="new-password" type="password" dir="ltr" class="form-input w-100" name="google_client_secret" value="" placeholder="<?php echo esc_attr($has_secret ? __("Saved. Leave empty to keep it.", "peprodev-ups") : __("Paste the client secret", "peprodev-ups")); ?>" /></div>
                </div>
                <div class="row justify-content-between align-items-center mb-3">
                  <div class="col-lg-4 label"><span><?php esc_html_e("Button text", "peprodev-ups"); ?></span></div>
                  <div class="col-lg-8"><input autocomplete="off" type="text" class="form-input w-100" name="google_button_text" value="<?php echo esc_attr($this->read("google_button_text", "")); ?>" placeholder="<?php echo esc_attr__("Sign in with Google", "peprodev-ups"); ?>" /></div>
                </div>
                <div class="row justify-content-between align-items-center mb-3">
                  <div class="col-lg-4 label"><span><?php esc_html_e("Authorized redirect URI", "peprodev-ups"); ?></span></div>
                  <div class="col-lg-8">
                    <input type="text" readonly dir="ltr" class="form-input w-100" id="pepro_google_redirect_uri" value="<?php echo esc_attr(self::redirect_uri()); ?>" onclick="this.select();" />
                    <button type="button" class="btn btn-sm btn-primary mt-2" onclick="var i=document.getElementById('pepro_google_redirect_uri');i.select();try{navigator.clipboard.writeText(i.value);}catch(e){document.execCommand('copy');}"><span class="material-icons">content_copy</span> <?php esc_html_e("Copy", "peprodev-ups"); ?></button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-5">
              <p class="text-bold"><?php esc_html_e("Setup", "peprodev-ups"); ?></p>
              <ol class="small pl-3 pr-3">
                <li><?php echo wp_kses(sprintf(__("Open the <a href=\"%s\" target=\"_blank\" rel=\"noopener\">Google Cloud Console credentials</a> page and create an OAuth client ID of type Web application.", "peprodev-ups"), "https://console.cloud.google.com/apis/credentials"), array("a" => array("href" => array(), "target" => array(), "rel" => array()))); ?></li>
                <li><?php esc_html_e("Add the Authorized redirect URI shown here, exactly as it is.", "peprodev-ups"); ?></li>
                <li><?php esc_html_e("Copy the Client ID and Client Secret here, enable the option and save.", "peprodev-ups"); ?></li>
              </ol>
              <p class="small"><?php esc_html_e("The button is shown under the login and register forms. Shortcode:", "peprodev-ups"); ?> <code dir="ltr">[pepro-google-login]</code> <code dir="ltr">[pepro-google-login text="" redirect_to=""]</code></p>
            </div>
          </div>
          <button class="login-section-save btn btn-success btn-primary icn-btn btn-wide" integrity="<?php echo esc_attr(wp_create_nonce("peprocorenounce")); ?>" wparam="loginregister" lparam="savelogin" dparam="" fn=""><i class="material-icons">save</i> <?php echo esc_html_x("Save Settings", "login-section", "peprodev-ups"); ?></button>
        </div>
      </div>
    </div>
    <?php
  }
  #endregion

  #region front-end
  /**
   * Start URL of the flow.
   * @param string $redirect_to optional target after login (validated later)
   */
  public function start_url($redirect_to = "") {
    $args = array("pepro_social" => "google", "_wpnonce" => wp_create_nonce("pepro_google_start"));
    if (!empty($redirect_to)) $args["redirect_to"] = rawurlencode($redirect_to);
    return add_query_arg($args, home_url("/"));
  }
  public function button_html($text = "", $redirect_to = "") {
    if (!$this->is_enabled() || is_user_logged_in()) return "";
    $this->enqueue_style();
    $text = "" !== trim((string) $text) ? $text : $this->button_text();
    $logo = '<svg class="pepro-google-btn__logo" viewBox="0 0 48 48" aria-hidden="true" focusable="false"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>';
    return '<div class="pepro-social"><a class="pepro-google-btn" rel="nofollow" href="' . esc_url($this->start_url($redirect_to)) . '">' . $logo . '<span class="pepro-google-btn__text">' . esc_html($text) . '</span></a></div>';
  }
  public function shortcode($atts = array()) {
    $atts = shortcode_atts(array("text" => "", "redirect_to" => ""), $atts, "pepro-google-login");
    return $this->notice_html() . $this->button_html($atts["text"], do_shortcode($atts["redirect_to"]));
  }
  /** Under the plugin's login/register forms (classic and modern UI). */
  public function print_form_button($args = array()) {
    $redirect = is_array($args) && !empty($args["redirect_to"]) ? $args["redirect_to"] : "";
    $html = $this->button_html("", $redirect);
    if ($html) echo '<div class="pepro-social-sep"><span>' . esc_html__("or", "peprodev-ups") . '</span></div>' . $html;
  }
  public function print_wp_login_button() {
    $redirect = isset($_REQUEST["redirect_to"]) && is_string($_REQUEST["redirect_to"]) ? wp_unslash($_REQUEST["redirect_to"]) : ""; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    echo $this->button_html("", $redirect);
  }
  public function enqueue_style() {
    static $done = false;
    if ($done) return;
    $done = true;
    $css = ".pepro-social{display:flex;justify-content:center;margin:1rem 0 0;clear:both}"
      . ".pepro-social-sep{display:flex;align-items:center;gap:.75rem;margin:1.25rem 0 0;color:#8a8f98;font-size:.85rem}.pepro-social-sep::before,.pepro-social-sep::after{content:'';flex:1;border-top:1px solid #e3e3e3}"
      . ".pepro-google-btn{box-sizing:border-box;display:inline-flex;align-items:center;justify-content:center;gap:12px;width:100%;max-width:400px;min-height:44px;padding:0 16px;border:1px solid #747775;border-radius:4px;background:#fff;color:#1f1f1f!important;font-family:Roboto,'Segoe UI',Arial,sans-serif;font-size:14px;font-weight:500;line-height:1.2;letter-spacing:.25px;text-decoration:none!important;cursor:pointer;transition:background-color .2s,box-shadow .2s}"
      . ".pepro-google-btn:hover{background:#f8f9fa;box-shadow:0 1px 2px rgba(60,64,67,.3),0 1px 3px 1px rgba(60,64,67,.15)}.pepro-google-btn:focus-visible{outline:2px solid #4285f4;outline-offset:2px}"
      . ".pepro-google-btn__logo{width:20px;height:20px;flex:0 0 20px}"
      . ".mj-is-otp .pepro-social,.mj-is-otp .pepro-social-sep{display:none}"
      . ".pepro-social-notice{box-sizing:border-box;margin:0 0 1rem;padding:.8rem 1rem;border-radius:8px;background:#fbeceb;color:#a4282a;font-size:.92rem;line-height:1.7}"
      . "#login .pepro-social{margin:0 0 16px}";
    if (did_action("wp_head") || did_action("login_head")) {
      echo "<style id='pepro-google-login-css'>" . $css . "</style>"; // static CSS, no user data
    } else {
      wp_register_style("pepro-google-login", false, array(), PEPRODEVUPS);
      wp_enqueue_style("pepro-google-login");
      wp_add_inline_style("pepro-google-login", $css);
    }
  }
  protected function error_messages() {
    return array(
      "cancelled"   => __("Google sign-in was cancelled.", "peprodev-ups"),
      "expired"     => __("The Google sign-in link has expired. Please try again.", "peprodev-ups"),
      "failed"      => __("We could not verify your Google account. Please try again.", "peprodev-ups"),
      "unverified"  => __("Your Google email address is not verified. Verify it with Google and try again.", "peprodev-ups"),
      "no_account"  => __("There is no account with this Google email address, and new registrations are not allowed.", "peprodev-ups"),
      "disabled"    => __("Sign in with Google is not available right now.", "peprodev-ups"),
      "user_failed" => __("Your account could not be created. Please contact the site administrator.", "peprodev-ups"),
    );
  }
  public function notice_html() {
    if (self::$notice_printed || empty($_GET["pepro_social_error"]) || is_user_logged_in()) return ""; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    $code     = sanitize_key(wp_unslash($_GET["pepro_social_error"])); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    $messages = $this->error_messages();
    if (!isset($messages[$code])) return "";
    self::$notice_printed = true;
    $this->enqueue_style();
    return '<div class="pepro-social-notice" role="alert">' . esc_html($messages[$code]) . '</div>';
  }
  public function print_notice() {
    echo $this->notice_html();
  }
  public function wp_login_message($message) {
    $notice = $this->notice_html();
    return $notice ? $message . $notice : $message;
  }
  #endregion

  #region oauth
  /** Where the visitor lands after an error: the page the flow started from, else the profile/login page. */
  protected function error_redirect($code, $return = "") {
    global $PeproDevUPS_Profile;
    $url = "";
    if (!empty($return)) $url = wp_validate_redirect($return, "");
    if (empty($url) && $PeproDevUPS_Profile && method_exists($PeproDevUPS_Profile, "get_profile_page")) $url = (string) $PeproDevUPS_Profile->get_profile_page(true);
    if (empty($url)) $url = wp_login_url();
    wp_safe_redirect(add_query_arg("pepro_social_error", $code, remove_query_arg(array("pepro_social_error"), $url)));
    exit;
  }
  protected function set_state_cookie($value, $expire) {
    $path   = COOKIEPATH ? COOKIEPATH : "/";
    $secure = is_ssl();
    if (PHP_VERSION_ID >= 70300) {
      setcookie(self::COOKIE, $value, array("expires" => $expire, "path" => $path, "domain" => COOKIE_DOMAIN, "secure" => $secure, "httponly" => true, "samesite" => "Lax"));
    } else {
      setcookie(self::COOKIE, $value, $expire, $path . "; samesite=Lax", COOKIE_DOMAIN, $secure, true);
    }
  }
  public function handle_request() {
    if (!isset($_GET["pepro_social"]) || "google" !== $_GET["pepro_social"]) return; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    nocache_headers();
    if (isset($_GET["state"]) || isset($_GET["code"]) || isset($_GET["error"])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
      $this->callback();
    }
    $this->start();
  }
  protected function start() {
    $return = wp_get_referer();
    if (!$this->is_enabled()) $this->error_redirect("disabled", $return);
    if (is_user_logged_in()) {
      wp_safe_redirect(home_url("/"));
      exit;
    }
    if (!isset($_GET["_wpnonce"]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET["_wpnonce"])), "pepro_google_start")) {
      $this->error_redirect("expired", $return);
    }
    global $PeproDevUPS_Login;
    $redirect_to = "";
    if (!empty($_GET["redirect_to"]) && is_string($_GET["redirect_to"]) && $PeproDevUPS_Login && method_exists($PeproDevUPS_Login, "validate_redirect_target")) {
      $redirect_to = $PeproDevUPS_Login->validate_redirect_target(rawurldecode(wp_unslash($_GET["redirect_to"])));
    }
    $state = wp_generate_password(40, false, false);
    $nonce = wp_generate_password(32, false, false);
    set_transient("pepro_google_" . md5($state), array(
      "nonce"       => $nonce,
      "redirect_to" => $redirect_to,
      "return"      => $return ? $return : "",
    ), self::STATE_TTL);
    $this->set_state_cookie($state, time() + self::STATE_TTL);
    $url = add_query_arg(array(
      "client_id"     => rawurlencode(trim((string) $this->read("google_client_id"))),
      "redirect_uri"  => rawurlencode(self::redirect_uri()),
      "response_type" => "code",
      "scope"         => rawurlencode("openid email profile"),
      "state"         => rawurlencode($state),
      "nonce"         => rawurlencode($nonce),
      "prompt"        => "select_account",
    ), self::AUTH_URL);
    wp_redirect($url); // external host (Google), wp_safe_redirect would block it
    exit;
  }
  protected function callback() {
    $state  = isset($_GET["state"]) ? sanitize_text_field(wp_unslash($_GET["state"])) : ""; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    $cookie = isset($_COOKIE[self::COOKIE]) ? sanitize_text_field(wp_unslash($_COOKIE[self::COOKIE])) : "";
    $saved  = "" !== $state ? get_transient("pepro_google_" . md5($state)) : false;
    // one-time state: remove it whatever happens next
    if ("" !== $state) delete_transient("pepro_google_" . md5($state));
    $this->set_state_cookie("", time() - 3600);
    $return = is_array($saved) && !empty($saved["return"]) ? $saved["return"] : "";

    if (!is_array($saved) || "" === $cookie || !hash_equals($state, $cookie)) $this->error_redirect("expired", $return);
    if (!empty($_GET["error"])) $this->error_redirect("access_denied" === $_GET["error"] ? "cancelled" : "failed", $return); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if (!$this->is_enabled()) $this->error_redirect("disabled", $return);
    $code = isset($_GET["code"]) ? sanitize_text_field(wp_unslash($_GET["code"])) : ""; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if ("" === $code) $this->error_redirect("failed", $return);

    $client_id = trim((string) $this->read("google_client_id"));
    $token = wp_remote_post(self::TOKEN_URL, array(
      "timeout" => 20,
      "headers" => array("Accept" => "application/json"),
      "body"    => array(
        "code"          => $code,
        "client_id"     => $client_id,
        "client_secret" => trim((string) $this->read("google_client_secret")),
        "redirect_uri"  => self::redirect_uri(),
        "grant_type"    => "authorization_code",
      ),
    ));
    $token = is_wp_error($token) ? null : json_decode(wp_remote_retrieve_body($token), true);
    if (!is_array($token) || empty($token["access_token"]) || empty($token["id_token"])) {
      $this->debug_log("token exchange failed" . (is_array($token) && !empty($token["error"]) && is_scalar($token["error"]) ? ": " . $token["error"] : ""));
      $this->error_redirect("failed", $return);
    }
    $claims = $this->id_token_claims($token["id_token"]);
    $iss    = isset($claims["iss"]) ? $claims["iss"] : "";
    if (!$claims
      || !in_array($iss, array("https://accounts.google.com", "accounts.google.com"), true)
      || !isset($claims["aud"]) || $claims["aud"] !== $client_id
      || empty($claims["exp"]) || (int) $claims["exp"] < time() - 60
      || empty($claims["nonce"]) || !hash_equals((string) $saved["nonce"], (string) $claims["nonce"])
      || empty($claims["sub"])) {
      $this->debug_log("id token claims rejected");
      $this->error_redirect("failed", $return);
    }
    $info = wp_remote_get(self::USERINFO_URL, array("timeout" => 20, "headers" => array("Authorization" => "Bearer " . $token["access_token"], "Accept" => "application/json")));
    $info = is_wp_error($info) ? null : json_decode(wp_remote_retrieve_body($info), true);
    if (!is_array($info) || empty($info["sub"]) || (string) $info["sub"] !== (string) $claims["sub"] || empty($info["email"])) {
      $this->debug_log("userinfo rejected");
      $this->error_redirect("failed", $return);
    }
    $verified = isset($info["email_verified"]) ? $info["email_verified"] : false;
    if (true !== $verified && "true" !== $verified) $this->error_redirect("unverified", $return);
    $email = sanitize_email($info["email"]);
    if (!is_email($email)) $this->error_redirect("failed", $return);

    $user = $this->find_user((string) $info["sub"], $email);
    $new  = false;
    if (!$user) {
      if (!get_option("users_can_register") || "yes" !== $this->read("google_create_users", "no")) $this->error_redirect("no_account", $return);
      $user = $this->create_user($email, $info);
      if (!$user) $this->error_redirect("user_failed", $return);
      $new = true;
    }
    update_user_meta($user->ID, "pepro_google_sub", sanitize_text_field((string) $info["sub"]));
    update_user_meta($user->ID, "pepro_user_is_email_verified", "yes");
    $this->login($user);
    do_action("pepro_reglogin_social_login", $user->ID, "google", $new);

    global $PeproDevUPS_Login;
    $redirect = "";
    if ($PeproDevUPS_Login && method_exists($PeproDevUPS_Login, "redirect_after_login_register")) {
      $redirect = $PeproDevUPS_Login->redirect_after_login_register(!empty($saved["redirect_to"]) ? $saved["redirect_to"] : false, $new ? "ajax_register" : "ajax_login", $user);
    }
    if (!is_string($redirect) || "" === $redirect) $redirect = home_url("/");
    wp_safe_redirect($redirect);
    exit;
  }
  /**
   * Claims of an ID token received directly from Google's token endpoint over
   * TLS (OpenID Connect Core 3.1.3.7: signature check may be skipped then).
   */
  protected function id_token_claims($jwt) {
    $parts = explode(".", (string) $jwt);
    if (3 !== count($parts)) return array();
    $json = base64_decode(strtr($parts[1], "-_", "+/") . str_repeat("=", (4 - strlen($parts[1]) % 4) % 4), true);
    $data = $json ? json_decode($json, true) : null;
    return is_array($data) ? $data : array();
  }
  protected function find_user($sub, $email) {
    $by_sub = get_users(array("meta_key" => "pepro_google_sub", "meta_value" => $sub, "number" => 1, "fields" => "all"));
    if (!empty($by_sub)) return $by_sub[0];
    $user = get_user_by("email", $email);
    return $user ? $user : null;
  }
  protected function create_user($email, $info) {
    $base  = sanitize_user(current(explode("@", $email)), true);
    $base  = "" !== $base ? $base : "user";
    $login = $base;
    $i     = 1;
    while (username_exists($login)) $login = $base . (++$i);
    $first = isset($info["given_name"]) && is_scalar($info["given_name"]) ? sanitize_text_field($info["given_name"]) : "";
    $last  = isset($info["family_name"]) && is_scalar($info["family_name"]) ? sanitize_text_field($info["family_name"]) : "";
    $name  = isset($info["name"]) && is_scalar($info["name"]) ? sanitize_text_field($info["name"]) : trim("$first $last");
    $user_id = wp_insert_user(array(
      "user_login"   => $login,
      "user_email"   => $email,
      "user_pass"    => wp_generate_password(24, true, true),
      "first_name"   => $first,
      "last_name"    => $last,
      "display_name" => "" !== $name ? $name : $login,
      "role"         => get_option("default_role", "subscriber"),
    ));
    if (is_wp_error($user_id)) {
      $this->debug_log("user creation failed: " . $user_id->get_error_code());
      return null;
    }
    if ("" !== $first) update_user_meta($user_id, "billing_first_name", $first);
    if ("" !== $last) update_user_meta($user_id, "billing_last_name", $last);
    return get_userdata($user_id);
  }
  protected function login($user) {
    $expire   = (string) $this->read("auth_expire", "0");
    $remember = "-1" === $expire || (is_numeric($expire) && (int) $expire > 1);
    wp_clear_auth_cookie();
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID, $remember, is_ssl());
    do_action("wp_login", $user->user_login, $user);
  }
  /** Debug log without any token, code or secret. */
  protected function debug_log($message) {
    if (defined("WP_DEBUG") && WP_DEBUG && defined("WP_DEBUG_LOG") && WP_DEBUG_LOG) error_log("PeproDev UPS Google login: " . $message);
  }
  #endregion
}

return new PeproSocial_Google;
