<?php
/*
 * FarazSMS (IranPayamak) SMS gateway.
 *
 * Uses the IranPayamak web service (https://api.iranpayamak.com/ws/v1) with the
 * account "Api-Key": pattern (template) sending for OTP codes, simple sending
 * for free text, and admin helpers to list patterns, show the balance and
 * create an OTP pattern.
 *
 * Gateway id "farazsms". The older "FarazSMS" / "FarazSMSPattern" gateways
 * (class-sms-faraz.php) talk to the ippanel.com API and keep their ids.
 */

namespace PeproDev\PeproCore\RegLogin;

defined("ABSPATH") or die("PeproDev Ultimate Profile Solutions :: Unauthorized Access! (https://pepro.dev/)");

final class PeproSMS_FarazSMS_Gateway extends PeproSMS_Gateway_Base {
  protected $api_base = "https://api.iranpayamak.com/ws/v1";

  public function __construct() {
    parent::__construct();
    add_action("wp_ajax_pepro_farazsms", array($this, "ajax_handler"));
  }
  public function sms_verification_gateways($gateways = array()) {
    $gateways["farazsms"] = array(
      "name"       => _x("FarazSMS (IranPayamak)", "gateway", "peprodev-ups"),
      "fn_send"    => array($this, "send"),
      "fn_setting" => array($this, "setting"),
    );
    return $gateways;
  }
  public function save_text_fields($prev = array()) {
    return array_merge((array) $prev, array("farazsms_api_key", "farazsms_line_number", "farazsms_send_mode", "farazsms_pattern_code", "farazsms_pattern_var"));
  }
  public function save_textarea_fields($prev = array()) {
    $prev[] = "farazsms_message";
    return $prev;
  }
  public function wpml_strings($strings = array()) {
    $strings["sms: farazsms message"] = $this->read_text("farazsms_message");
    return $strings;
  }
  /**
   * One IranPayamak API call.
   * @return array|\WP_Error decoded JSON
   */
  protected function api($path, $method = "GET", $body = null, $api_key = "") {
    $args = array(
      "method"  => $method,
      "timeout" => 30,
      "headers" => array(
        "Accept"       => "application/json",
        "Content-Type" => "application/json",
        "Api-Key"      => (string) $api_key,
      ),
    );
    if (null !== $body && "GET" !== $method) $args["body"] = wp_json_encode($body);
    $response = wp_remote_request($this->api_base . $path, $args);
    if (is_wp_error($response)) return $response;
    $code = (int) wp_remote_retrieve_response_code($response);
    $data = json_decode(wp_remote_retrieve_body($response), true);
    // a 2xx reply the API accepted is a success even when its body is not JSON
    if (!is_array($data) && $code >= 200 && $code < 300) return array("status" => "success");
    if (!is_array($data)) {
      /* translators: %s: HTTP status code. */
      return new \WP_Error("farazsms_bad_response", sprintf(__("Invalid response from FarazSMS (HTTP %s).", "peprodev-ups"), $code));
    }
    if ($code >= 400 || (isset($data["status"]) && "success" !== strtolower(trim((string) $data["status"])))) {
      $msg = $this->error_text($data);
      /* translators: %s: HTTP status code. */
      $msg = "" !== $msg ? $msg : sprintf(__("FarazSMS request failed (HTTP %s).", "peprodev-ups"), $code);
      return new \WP_Error("farazsms_failed", $msg);
    }
    return $data;
  }
  /**
   * Error reason of an API response. IranPayamak puts it in "messages" (string, list or
   * field => errors object, e.g. HTTP 422 validation errors), older replies in "message".
   */
  protected function error_text($data) {
    $parts = array();
    foreach (array("messages", "message", "errors") as $key) {
      if (empty($data[$key])) continue;
      if (is_array($data[$key])) {
        array_walk_recursive($data[$key], function ($v) use (&$parts) { if (is_scalar($v) && "" !== trim((string) $v)) $parts[] = trim((string) $v); });
      } elseif (is_scalar($data[$key])) {
        $parts[] = trim((string) $data[$key]);
      }
    }
    return implode(" ", array_unique($parts));
  }
  /**
   * OTP sending method: "pattern" (template) or "simple" (normal SMS with the message text).
   * Sites saved before this setting existed keep their behaviour: pattern when a pattern code is set.
   */
  protected function send_mode() {
    $mode = (string) $this->read("farazsms_send_mode", "");
    if (in_array($mode, array("pattern", "simple"), true)) return $mode;
    return "" !== trim((string) $this->read("farazsms_pattern_code")) ? "pattern" : "simple";
  }
  /**
   * Sender line as the digits-only string the API expects (Persian/Arabic digits converted).
   */
  protected function line_number() {
    $line = str_replace(array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "٠", "١", "٢", "٣", "٤", "٥", "٦", "٧", "٨", "٩"), array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"), (string) $this->read("farazsms_line_number"));
    return preg_replace('/\D+/', '', $line);
  }
  /**
   * Send an OTP (pattern when a pattern code is set, otherwise the message template) or a free text.
   */
  public function send($numbers = "", $message = "", $otp_code = 0) {
    $api_key = trim((string) $this->read("farazsms_api_key"));
    if ("" === $api_key) return $this->fail(__("FarazSMS Api-Key is not configured.", "peprodev-ups"));
    $recipients = $this->recipients($numbers);
    if (empty($recipients)) return $this->fail(__("No valid mobile number.", "peprodev-ups"));
    $line    = $this->line_number();
    if ("" === $line) return $this->fail(__("FarazSMS sender number is not configured.", "peprodev-ups"));
    $pattern = trim((string) $this->read("farazsms_pattern_code"));
    $is_otp  = !empty($otp_code);

    if ($is_otp && "pattern" === $this->send_mode()) {
      if ("" === $pattern) return $this->fail(__("FarazSMS pattern code is not set. Choose a pattern or switch the sending method to normal SMS.", "peprodev-ups"));
      $var = trim((string) $this->read("farazsms_pattern_var", "OTP"));
      $var = "" !== $var ? $var : "OTP";
      $result = false;
      foreach ($recipients as $recipient) {
        $result = $this->api("/sms/pattern", "POST", array(
          "code"          => $pattern,
          "attributes"    => array($var => (string) $otp_code),
          "recipient"     => $recipient,
          "line_number"   => $line,
          "number_format" => "english",
        ), $api_key);
        if (is_wp_error($result)) return $this->fail($result->get_error_message());
      }
      return $this->sent_result($result);
    }

    $text = $is_otp ? $this->otp_message(\PeproDevUPS_WPML::translate("sms: farazsms message", $this->read_text("farazsms_message")), $otp_code) : (string) $message;
    $result = $this->api("/sms/simple", "POST", array(
      "text"          => $text,
      "line_number"   => $line,
      "recipients"    => $recipients,
      "number_format" => "english",
    ), $api_key);
    if (is_wp_error($result)) return $this->fail($result->get_error_message());
    return $this->sent_result($result);
  }
  /**
   * Value returned for a sent message: the API text when it has one, otherwise true.
   * Never an empty string, which the login forms would read as "not sent".
   */
  protected function sent_result($result) {
    $msg = is_array($result) ? $this->error_text($result) : "";
    if ("" === $msg && is_array($result) && isset($result["data"]) && is_scalar($result["data"])) $msg = (string) $result["data"];
    return "" !== trim($msg) ? $msg : true;
  }
  /**
   * Admin helpers: pattern list, balance, create an OTP pattern.
   * Uses the Api-Key typed in the (maybe unsaved) settings field, else the saved one.
   */
  public function ajax_handler() {
    check_ajax_referer("pepro_farazsms", "integrity");
    if (!current_user_can("manage_options")) {
      wp_send_json_error(array("msg" => __("Unauthorized access is prohibited.", "peprodev-ups")));
    }
    $api_key = isset($_POST["api_key"]) ? trim(sanitize_text_field(wp_unslash($_POST["api_key"]))) : "";
    if ("" === $api_key) $api_key = trim((string) $this->read("farazsms_api_key"));
    if ("" === $api_key) wp_send_json_error(array("msg" => __("Enter the FarazSMS Api-Key first.", "peprodev-ups")));
    $do = isset($_POST["do"]) ? sanitize_key(wp_unslash($_POST["do"])) : "";
    switch ($do) {
      case "patterns":
        $resp = $this->api("/patterns?per_page=100", "GET", null, $api_key);
        if (is_wp_error($resp)) wp_send_json_error(array("msg" => $resp->get_error_message()));
        $rows = isset($resp["data"]["data"]) && is_array($resp["data"]["data"]) ? $resp["data"]["data"] : array();
        $list = array();
        foreach ($rows as $row) {
          if (!is_array($row) || empty($row["code"])) continue;
          $vars = array();
          if (isset($row["attributes"]) && is_array($row["attributes"])) {
            foreach ($row["attributes"] as $attr) {
              if (is_array($attr) && isset($attr["var"]) && is_scalar($attr["var"])) $vars[] = (string) $attr["var"];
            }
          }
          $list[] = array(
            "code"        => (string) $row["code"],
            "description" => isset($row["description"]) && is_scalar($row["description"]) ? (string) $row["description"] : "",
            "vars"        => $vars,
          );
        }
        wp_send_json_success(array("patterns" => $list));
        break;
      case "balance":
        $resp = $this->api("/account/balance", "GET", null, $api_key);
        if (is_wp_error($resp)) wp_send_json_error(array("msg" => $resp->get_error_message()));
        $amount = isset($resp["data"]["balance_amount"]) ? (float) $resp["data"]["balance_amount"] : 0;
        $count  = isset($resp["data"]["balance_count"]) ? (float) $resp["data"]["balance_count"] : 0;
        wp_send_json_success(array(
          /* translators: %s: account balance amount. */
          "amount" => sprintf(__("%s Toman", "peprodev-ups"), number_format_i18n($amount)),
          /* translators: %s: number of SMS messages the balance covers. */
          "count"  => sprintf(__("%s SMS", "peprodev-ups"), number_format_i18n(floor($count))),
        ));
        break;
      case "create_pattern":
        $text = isset($_POST["text"]) ? trim(sanitize_textarea_field(wp_unslash($_POST["text"]))) : "";
        if ("" === $text) wp_send_json_error(array("msg" => __("The pattern text is empty.", "peprodev-ups")));
        $resp = $this->api("/patterns", "POST", array(
          "text"        => $text,
          "description" => sprintf("OTP %s", wp_strip_all_tags(get_bloginfo("name"))),
          "share"       => 1,
          "website"     => home_url(),
          "category"    => 1,
          "vars"        => array(array("var" => "OTP", "length" => 12, "type" => "int")),
        ), $api_key);
        if (is_wp_error($resp)) wp_send_json_error(array("msg" => $resp->get_error_message()));
        wp_send_json_success(array(
          "code" => isset($resp["data"]["code"]) && is_scalar($resp["data"]["code"]) ? (string) $resp["data"]["code"] : "",
          "msg"  => isset($resp["message"]) && is_scalar($resp["message"]) ? (string) $resp["message"] : __("Pattern created. It may need approval by FarazSMS before use.", "peprodev-ups"),
        ));
        break;
      default:
        wp_send_json_error(array("msg" => __("Incorrect Data Supplied.", "peprodev-ups")));
    }
  }
  public function setting() {
    $api_key  = (string) $this->read("farazsms_api_key");
    $line     = (string) $this->read("farazsms_line_number");
    $mode     = $this->send_mode();
    $pattern  = (string) $this->read("farazsms_pattern_code");
    $var      = (string) $this->read("farazsms_pattern_var", "OTP");
    $message  = $this->read_text("farazsms_message");
    $template = sprintf("%s: %%OTP%%\n%s", __("Your verification code", "peprodev-ups"), wp_strip_all_tags(get_bloginfo("name")));
    $i18n     = array(
      "loading"  => __("Please wait ...", "peprodev-ups"),
      "fallback" => __("Could not load the patterns, enter the pattern code manually.", "peprodev-ups"),
      "none"     => __("- Select a pattern -", "peprodev-ups"),
    );
    ob_start();
    ?>
    <p class="font-weight-bold p-3"><?php esc_html_e("FarazSMS (IranPayamak) Setting", "peprodev-ups"); ?></p>
    <div id="farazsms-section" class="col-lg-12 p-0" data-ajax="<?php echo esc_url(admin_url("admin-ajax.php")); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce("pepro_farazsms")); ?>" data-i18n="<?php echo esc_attr(wp_json_encode($i18n)); ?>">
      <p class="pd-gw-section"><?php esc_html_e("Account", "peprodev-ups"); ?></p>
      <div class='col-lg-12 row justify-content-between mb-3 field-opt-farazsms_api_key'>
        <div class="col-lg-6 label"><span><?php esc_html_e("Api-Key", "peprodev-ups"); ?></span><br><small><a href="https://iranpayamak.com/" target="_blank" rel="noopener noreferrer"><?php esc_html_e("Get the Api-Key from your FarazSMS panel", "peprodev-ups"); ?> &#8599;</a></small></div>
        <div class="col-lg-6"><input name="farazsms_api_key" id="farazsms_api_key" value="<?php echo esc_attr($api_key); ?>" dir="ltr" class='form-input single-required mr-2' autocomplete="new-password" type="password" /></div>
      </div>
      <div class='col-lg-12 row justify-content-between mb-3 field-opt-farazsms_line_number'>
        <div class="col-lg-6 label"><span><?php esc_html_e("Sender number", "peprodev-ups"); ?></span></div>
        <div class="col-lg-6"><input name="farazsms_line_number" value="<?php echo esc_attr($line); ?>" dir="ltr" class='form-input single-required mr-2' autocomplete="off" type="text" placeholder="e.g. 3000505" /></div>
      </div>
      <div class='col-lg-12 row justify-content-between align-items-center mb-3'>
        <div class="col-lg-6 label"><span><?php esc_html_e("Account Balance", "peprodev-ups"); ?></span></div>
        <div class="col-lg-6">
          <div class="pd-balance">
            <span id="farazsms-balance" dir="auto">&mdash;</span>
            <button id="farazsms-reload" class="pd-icon-btn" type="button" title="<?php esc_attr_e("Reload", "peprodev-ups"); ?>" aria-label="<?php esc_attr_e("Reload", "peprodev-ups"); ?>"><span class="material-icons">refresh</span></button>
          </div>
        </div>
      </div>
      <p class="pd-gw-section"><?php esc_html_e("Sending the code", "peprodev-ups"); ?></p>
      <div class='col-lg-12 row justify-content-between mb-3 field-opt-farazsms_send_mode'>
        <div class="col-lg-6 label"><span><?php esc_html_e("Sending method of the code", "peprodev-ups"); ?></span><br><small class="text-muted"><?php esc_html_e("Pattern: a template approved in your FarazSMS panel. Normal SMS: the message text below, sent from the sender number.", "peprodev-ups"); ?></small></div>
        <div class="col-lg-6">
          <select name="farazsms_send_mode" id="farazsms_send_mode" class='form-input single-required mr-2' autocomplete="off">
            <option value="pattern" <?php selected($mode, "pattern"); ?>><?php esc_html_e("Pattern (template)", "peprodev-ups"); ?></option>
            <option value="simple" <?php selected($mode, "simple"); ?>><?php esc_html_e("Normal SMS (message text)", "peprodev-ups"); ?></option>
          </select>
        </div>
      </div>
      <div class="farazsms-mode" data-mode="pattern">
        <div class='col-lg-12 row justify-content-between mb-3 field-opt-farazsms_pattern_code'>
          <div class="col-lg-6 label"><span><?php esc_html_e("OTP pattern (template) code", "peprodev-ups"); ?></span><br><small class="text-muted"><?php esc_html_e("Enter the Api-Key and click Reload to choose from your approved patterns.", "peprodev-ups"); ?></small></div>
          <div class="col-lg-6" id="farazsms-pattern-wrap"><input name="farazsms_pattern_code" id="farazsms_pattern_code" value="<?php echo esc_attr($pattern); ?>" dir="ltr" class='form-input single-required mr-2' autocomplete="off" type="text" /></div>
        </div>
        <div class='col-lg-12 row justify-content-between mb-3 field-opt-farazsms_pattern_var'>
          <div class="col-lg-6 label"><span><?php esc_html_e("Pattern variable of the code", "peprodev-ups"); ?></span></div>
          <div class="col-lg-6" id="farazsms-var-wrap"><input name="farazsms_pattern_var" id="farazsms_pattern_var" value="<?php echo esc_attr($var ? $var : "OTP"); ?>" dir="ltr" class='form-input single-required mr-2' autocomplete="off" type="text" /></div>
        </div>
      </div>
      <div class="farazsms-mode" data-mode="pattern">
        <p class="pd-gw-section mt-4"><?php esc_html_e("Create an OTP pattern", "peprodev-ups"); ?></p>
        <div class="pd-gw-tool">
          <p class="small text-muted mb-2"><?php esc_html_e("No pattern yet? Write the text here and send it to FarazSMS. Use %OTP% for the code. New patterns are reviewed by FarazSMS before they can be used; then choose it above.", "peprodev-ups"); ?></p>
          <textarea id="farazsms-newpattern" class="form-input mb-2" rows="3" aria-label="<?php esc_attr_e("Create an OTP pattern", "peprodev-ups"); ?>"><?php echo esc_textarea($template); ?></textarea>
          <button id="farazsms-create" class="btn btn-sm btn-success m-0" type="button"><?php esc_html_e("Create Pattern", "peprodev-ups"); ?></button>
        </div>
      </div>
      <div class="farazsms-mode" data-mode="simple">
        <div class='col-lg-12 row justify-content-between mb-3 field-opt-farazsms_message'>
          <div class="col-lg-6 label"><span><?php esc_html_e("Message containing [OTP]", "peprodev-ups"); ?></span><br><small class="text-muted"><?php esc_html_e("Sent as a normal SMS from the sender number; [OTP] is replaced with the code.", "peprodev-ups"); ?></small></div>
          <div class="col-lg-6"><textarea name="farazsms_message" autocomplete="off" class='form-input single-required mr-2' rows="3" placeholder="<?php echo esc_attr__("e.g: Your Code: [OTP]", "peprodev-ups"); ?>"><?php echo esc_textarea($message); ?></textarea></div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      (function ($) {
        $(function () {
          var $sec = $("#farazsms-section");
          if (!$sec.length || $sec.data("ready")) { return; }
          $sec.data("ready", 1);
          var i18n = $sec.data("i18n") || {}, patterns = [];
          function esc(s) { return $("<div>").text(s == null ? "" : String(s)).html(); }
          function post(data, done, fail) {
            data.action = "pepro_farazsms"; data.integrity = $sec.data("nonce"); data.api_key = $("#farazsms_api_key").val() || "";
            $.post($sec.data("ajax"), data).done(function (r) {
              if (r && r.success) { done(r.data || {}); } else { fail(r && r.data && r.data.msg ? r.data.msg : "error"); }
            }).fail(function () { fail("network error"); });
          }
          function showMode() {
            var mode = $("#farazsms_send_mode").val();
            $sec.find(".farazsms-mode").each(function () { $(this).toggle($(this).data("mode") === mode); });
          }
          function renderVars(code) {
            var cur = $("[name=farazsms_pattern_var]").val() || "OTP";
            var p = patterns.filter(function (x) { return x.code === code; })[0];
            if (!p || !p.vars.length) { return; }
            var $s = $("<select class='form-input' dir='ltr' name='farazsms_pattern_var' id='farazsms_pattern_var'></select>");
            p.vars.forEach(function (v) { $("<option>").val(v).text(v).prop("selected", v === cur).appendTo($s); });
            $("#farazsms-var-wrap").empty().append($s);
          }
          function loadPatterns(select) {
            var cur = typeof select === "string" ? select : ($("[name=farazsms_pattern_code]").val() || "");
            post({ "do": "patterns" }, function (d) {
              patterns = d.patterns || [];
              if (!patterns.length) { return; }
              var $s = $("<select class='form-input' dir='ltr' name='farazsms_pattern_code' id='farazsms_pattern_code'></select>");
              $("<option value=''>").text(i18n.none || "-").appendTo($s);
              // code first, description isolated so mixed Persian/Latin text keeps its order
              patterns.forEach(function (p) { $("<option>").val(p.code).text(p.code + (p.description ? "  ·  ⁨" + p.description + "⁩" : "")).prop("selected", p.code === cur).appendTo($s); });
              if (cur && !$s.find("option").filter(function () { return this.value === cur; }).length) { $("<option>").val(cur).text(cur).prop("selected", true).appendTo($s); }
              $("#farazsms-pattern-wrap").empty().append($s);
              renderVars(cur);
            }, function (msg) {
              $("#farazsms-pattern-wrap small.farazsms-note").remove();
              $("#farazsms-pattern-wrap").append($("<small class='text-muted d-block farazsms-note'>").text((i18n.fallback || "") + " (" + msg + ")"));
            });
          }
          function loadBalance() {
            $("#farazsms-balance").text(i18n.loading || "...");
            post({ "do": "balance" }, function (d) { $("#farazsms-balance").html(esc(d.amount) + " &middot; " + esc(d.count)); }, function (msg) { $("#farazsms-balance").text(msg); });
          }
          $sec.on("change", "#farazsms_send_mode", showMode);
          $sec.on("change", "#farazsms_pattern_code", function () { renderVars($(this).val()); });
          $sec.on("click", "#farazsms-reload", function (e) { e.preventDefault(); loadPatterns(); loadBalance(); });
          $sec.on("click", "#farazsms-create", function (e) {
            e.preventDefault();
            var $b = $(this).prop("disabled", true);
            post({ "do": "create_pattern", text: $("#farazsms-newpattern").val() }, function (d) {
              $b.prop("disabled", false); window.alert(d.msg || ""); loadPatterns(d.code || undefined);
            }, function (msg) { $b.prop("disabled", false); window.alert(msg); });
          });
          showMode();
          // only call the API when this gateway is the selected one and a key exists
          function autoload() { if ($("#sms_method").val() === "farazsms" && $("#farazsms_api_key").val()) { loadPatterns(); loadBalance(); } }
          $(document).on("change", "#sms_method", autoload);
          autoload();
        });
      })(jQuery);
    </script>
    <?php
    return ob_get_clean();
  }
}

return new PeproSMS_FarazSMS_Gateway;
