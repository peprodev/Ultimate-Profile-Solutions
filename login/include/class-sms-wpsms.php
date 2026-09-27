<?php
/*
 * "WP SMS" plugin integration (https://wordpress.org/plugins/wp-sms/).
 *
 * Sends the OTP through the SMS provider configured in WP SMS with its public
 * wp_sms_send( $to, $msg, $is_flash, $from, $mediaUrls, $messageVariables )
 * function. For providers that support templates (patterns) in WP SMS, a
 * template id turns the message into "text|template_id" with the OTP passed as
 * a message variable, the convention WP SMS gateways use.
 *
 * Gateway id "wpsms". When WP SMS is not active the gateway stays selectable,
 * shows a notice in the settings and sending returns false.
 */

namespace PeproDev\PeproCore\RegLogin;

defined("ABSPATH") or die("PeproDev Ultimate Profile Solutions :: Unauthorized Access! (https://pepro.dev/)");

final class PeproSMS_WPSMS_Gateway extends PeproSMS_Gateway_Base {
  public function is_available() {
    return function_exists("wp_sms_send");
  }
  public function sms_verification_gateways($gateways = array()) {
    $gateways["wpsms"] = array(
      "name"       => $this->is_available() ? _x("WP SMS plugin", "gateway", "peprodev-ups") : _x("WP SMS plugin (not active)", "gateway", "peprodev-ups"),
      "fn_send"    => array($this, "send"),
      "fn_setting" => array($this, "setting"),
    );
    return $gateways;
  }
  public function save_text_fields($prev = array()) {
    return array_merge((array) $prev, array("wpsms_template_id", "wpsms_template_var"));
  }
  public function save_textarea_fields($prev = array()) {
    $prev[] = "wpsms_message";
    return $prev;
  }
  public function wpml_strings($strings = array()) {
    $strings["sms: wp sms message"] = $this->read_text("wpsms_message");
    return $strings;
  }
  public function send($numbers = "", $message = "", $otp_code = 0) {
    if (!$this->is_available()) return $this->fail(__("The WP SMS plugin is not active.", "peprodev-ups"));
    $to = $this->recipients($numbers);
    if (empty($to)) return $this->fail(__("No valid mobile number.", "peprodev-ups"));
    $vars = array();
    if (!empty($otp_code)) {
      $text     = $this->otp_message(\PeproDevUPS_WPML::translate("sms: wp sms message", $this->read_text("wpsms_message")), $otp_code);
      $template = trim((string) $this->read("wpsms_template_id"));
      if ("" !== $template) {
        $var  = trim((string) $this->read("wpsms_template_var", "OTP"));
        $vars = array(("" !== $var ? $var : "OTP") => (string) $otp_code);
        $text = str_replace("|", " ", $text) . "|" . $template;
      }
    } else {
      $text = (string) $message;
    }
    try {
      $result = wp_sms_send($to, $text, false, null, array(), $vars);
    } catch (\Throwable $e) {
      return $this->fail($e->getMessage());
    }
    if (is_wp_error($result)) return $this->fail($result->get_error_message());
    if (false === $result || null === $result) return $this->fail(__("WP SMS could not send the message. Check the WP SMS outbox.", "peprodev-ups"));
    return is_scalar($result) ? (true === $result ? true : (string) $result) : true;
  }
  public function setting() {
    ob_start();
    ?>
    <p class="font-weight-bold p-3"><?php esc_html_e("WP SMS plugin Setting", "peprodev-ups"); ?></p>
    <div class='col-lg-12 mb-3'>
      <?php echo $this->integration_status_html($this->is_available(), "WP SMS", "wp-sms", admin_url("admin.php?page=wp-sms-settings")); ?>
      <p class="text-muted mb-0"><?php esc_html_e("The provider, API keys and sender number are set in the WP SMS settings; this plugin only builds the OTP message.", "peprodev-ups"); ?></p>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-wpsms_message'>
      <div class="col-lg-6 label"><span><?php esc_html_e("Message containing [OTP]", "peprodev-ups"); ?></span></div>
      <div class="col-lg-6"><textarea name="wpsms_message" autocomplete="off" class='form-input single-required mr-2' rows="3" placeholder="<?php echo esc_attr__("e.g: Your Code: [OTP]", "peprodev-ups"); ?>"><?php echo esc_textarea($this->read_text("wpsms_message")); ?></textarea></div>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-wpsms_template_id'>
      <div class="col-lg-6 label"><span><?php esc_html_e("Template (pattern) ID", "peprodev-ups"); ?></span><br><small class="text-muted"><?php esc_html_e("Optional, for WP SMS providers that support templates. Leave empty to send the message as a normal SMS.", "peprodev-ups"); ?></small></div>
      <div class="col-lg-6"><input name="wpsms_template_id" value="<?php echo esc_attr($this->read("wpsms_template_id")); ?>" dir="ltr" class='form-input single-required mr-2' autocomplete="off" type="text" /></div>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-wpsms_template_var'>
      <div class="col-lg-6 label"><span><?php esc_html_e("Template variable of the code", "peprodev-ups"); ?></span></div>
      <div class="col-lg-6"><input name="wpsms_template_var" value="<?php echo esc_attr($this->read("wpsms_template_var", "OTP")); ?>" dir="ltr" class='form-input single-required mr-2' autocomplete="off" type="text" /></div>
    </div>
    <?php
    return ob_get_clean();
  }
}

return new PeproSMS_WPSMS_Gateway;
