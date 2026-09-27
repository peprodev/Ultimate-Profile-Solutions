<?php
/*
 * "Persian WooCommerce SMS" plugin integration
 * (https://wordpress.org/plugins/persian-woocommerce-sms/).
 *
 * Sends the OTP through the gateway configured in that plugin with its public
 * helper PWSMS()->send_sms( array( "mobile" => ..., "message" => ... ) ),
 * which returns true or an error message (and logs the SMS in its archive).
 * Its gateways understand pattern messages written as
 *   pattern:<code>
 *   <variable>:<value>
 * so the message template here can be a plain text or such a pattern block.
 *
 * Gateway id "pwsms". When the plugin is not active the gateway stays
 * selectable, shows a notice in the settings and sending returns false.
 */

namespace PeproDev\PeproCore\RegLogin;

defined("ABSPATH") or die("PeproDev Ultimate Profile Solutions :: Unauthorized Access! (https://pepro.dev/)");

final class PeproSMS_PWSMS_Gateway extends PeproSMS_Gateway_Base {
  public function is_available() {
    return function_exists("PWSMS") && is_object(PWSMS()) && method_exists(PWSMS(), "send_sms");
  }
  public function sms_verification_gateways($gateways = array()) {
    $gateways["pwsms"] = array(
      "name"       => $this->is_available() ? _x("Persian WooCommerce SMS plugin", "gateway", "peprodev-ups") : _x("Persian WooCommerce SMS plugin (not active)", "gateway", "peprodev-ups"),
      "fn_send"    => array($this, "send"),
      "fn_setting" => array($this, "setting"),
    );
    return $gateways;
  }
  public function save_textarea_fields($prev = array()) {
    $prev[] = "pwsms_message";
    return $prev;
  }
  public function wpml_strings($strings = array()) {
    $strings["sms: persian woocommerce sms message"] = $this->read_text("pwsms_message");
    return $strings;
  }
  public function send($numbers = "", $message = "", $otp_code = 0) {
    if (!$this->is_available()) return $this->fail(__("The Persian WooCommerce SMS plugin is not active.", "peprodev-ups"));
    $to = $this->recipients($numbers);
    if (empty($to)) return $this->fail(__("No valid mobile number.", "peprodev-ups"));
    $text = !empty($otp_code) ? $this->otp_message(\PeproDevUPS_WPML::translate("sms: persian woocommerce sms message", $this->read_text("pwsms_message")), $otp_code) : (string) $message;
    try {
      $result = PWSMS()->send_sms(array(
        "mobile"  => implode(",", $to),
        "message" => $text,
        "type"    => 0,
      ));
    } catch (\Throwable $e) {
      return $this->fail($e->getMessage());
    }
    if (true === $result) return true;
    return $this->fail(is_scalar($result) && "" !== (string) $result ? (string) $result : __("Persian WooCommerce SMS could not send the message.", "peprodev-ups"));
  }
  public function setting() {
    ob_start();
    ?>
    <p class="font-weight-bold p-3"><?php esc_html_e("Persian WooCommerce SMS plugin Setting", "peprodev-ups"); ?></p>
    <div class='col-lg-12 mb-3'>
      <?php echo $this->integration_status_html($this->is_available(), __("Persian WooCommerce SMS", "peprodev-ups"), "persian-woocommerce-sms", admin_url("admin.php?page=persian-woocommerce-sms-pro")); ?>
      <p class="text-muted mb-0"><?php esc_html_e("The SMS provider and its credentials are set in the Persian WooCommerce SMS settings; sent codes appear in its SMS archive.", "peprodev-ups"); ?></p>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-pwsms_message'>
      <div class="col-lg-6 label">
        <span><?php esc_html_e("Message containing [OTP]", "peprodev-ups"); ?></span><br>
        <small class="text-muted"><?php esc_html_e("For a pattern (template) SMS write one item per line, e.g.", "peprodev-ups"); ?></small>
        <pre dir="ltr" class="m-0" style="font-size:.8rem;white-space:pre-wrap;">pattern:123456
code:[OTP]</pre>
      </div>
      <div class="col-lg-6"><textarea name="pwsms_message" autocomplete="off" class='form-input single-required mr-2' rows="4" dir="auto" placeholder="<?php echo esc_attr__("e.g: Your Code: [OTP]", "peprodev-ups"); ?>"><?php echo esc_textarea($this->read_text("pwsms_message")); ?></textarea></div>
    </div>
    <?php
    return ob_get_clean();
  }
}

return new PeproSMS_PWSMS_Gateway;
