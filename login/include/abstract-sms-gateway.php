<?php
/*
 * Shared helpers for the SMS gateways added in 8.2 (FarazSMS / IranPayamak,
 * WP SMS and Persian WooCommerce SMS integrations).
 *
 * A gateway registers itself on the "pepro_reglogin_sms_verification_gateways"
 * filter with a "fn_send" callback that receives ($numbers, $message, $otp_code)
 * from PeproDevUPS_Login::sendmsg_sms() and returns a truthy value on success,
 * false on failure (the reason is kept for the admin "Send a Test SMS" button).
 */

namespace PeproDev\PeproCore\RegLogin;

use PeproDevUPS;

defined("ABSPATH") or die("PeproDev Ultimate Profile Solutions :: Unauthorized Access! (https://pepro.dev/)");

if (!class_exists(__NAMESPACE__ . "\\PeproSMS_Gateway_Base")) {
  abstract class PeproSMS_Gateway_Base extends PeproDevUPS {
    public $td      = "peprodev-ups";
    public $db_slug = "peprodev-ups";
    /** @var string last failure reason of this request (never contains secrets) */
    protected static $last_error = "";

    public function __construct() {
      add_filter("pepro_reglogin_sms_verification_gateways", array($this, "sms_verification_gateways"));
      add_filter("pepro_reglogin_save_text_fields", array($this, "save_text_fields"));
      add_filter("pepro_reglogin_save_raw_fields", array($this, "save_textarea_fields"));
      add_filter("pepro_reglogin_sms_last_error", array(__CLASS__, "get_last_error"));
      add_filter("peprodev-ups/wpml/strings", array($this, "wpml_strings"));
    }
    abstract public function sms_verification_gateways($gateways = array());
    public function save_text_fields($prev = array()) { return $prev; }
    public function save_textarea_fields($prev = array()) { return $prev; }
    public function wpml_strings($strings = array()) { return $strings; }

    public static function get_last_error($prev = "") {
      return "" !== self::$last_error ? self::$last_error : $prev;
    }
    /**
     * Remember why a send failed and return false.
     * @param string $message human readable reason
     * @return false
     */
    protected function fail($message = "") {
      self::$last_error = wp_strip_all_tags((string) $message);
      return false;
    }
    /**
     * Stored setting as plain text (textarea settings are stored HTML-escaped).
     */
    protected function read_text($slug, $default = "") {
      return trim(wp_specialchars_decode((string) $this->read($slug, $default), ENT_QUOTES));
    }
    /**
     * Recipients as an array of local Iranian numbers (09xxxxxxxxx) when they look Iranian, digits otherwise.
     * @param string|array $numbers comma separated or array (PeproDevUPS_Login cleans them to 9xxxxxxxxx)
     * @return string[]
     */
    protected function recipients($numbers) {
      $list = is_array($numbers) ? $numbers : explode(",", (string) $numbers);
      $out  = array();
      foreach ($list as $number) {
        $clean = preg_replace('/\D+/', '', (string) $number);
        if ("" === $clean) continue;
        if (0 === strpos($clean, "0098")) $clean = substr($clean, 4);
        elseif (0 === strpos($clean, "98") && 12 === strlen($clean)) $clean = substr($clean, 2);
        if (10 === strlen($clean) && "9" === $clean[0]) $clean = "0" . $clean;
        $out[] = $clean;
      }
      return array_values(array_unique($out));
    }
    /**
     * Replace the OTP placeholders ([OTP], {OTP}, %OTP%) of a message template.
     * An empty template falls back to the plugin's default OTP message.
     */
    protected function otp_message($template, $otp_code) {
      $template = trim((string) $template);
      if ("" === $template) {
        $template = sprintf(__("Verification Code: [OTP] — %s", "peprodev-ups"), get_bloginfo("name"));
      }
      return str_replace(array("[OTP]", "{OTP}", "%OTP%", "[otp]", "{otp}", "%otp%"), (string) $otp_code, $template);
    }
    public function external_link($url = "#") {
      return ' <a href="' . esc_url($url) . '" class="btn btn-sm btn-round btn-group btn-info float-left m-0" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt"></i></a>';
    }
    /**
     * Status line for integrations with another plugin.
     * @param bool   $active   whether the host plugin is active
     * @param string $name     host plugin name
     * @param string $slug     wordpress.org slug (install link)
     * @param string $settings admin URL of the host plugin settings
     */
    protected function integration_status_html($active, $name, $slug, $settings = "") {
      if ($active) {
        $html = "<p class='text-success font-weight-bold mb-2'>&#10004; " . esc_html(sprintf(__("%s is active. Messages are sent through the SMS provider configured there.", "peprodev-ups"), $name)) . "</p>";
        if ($settings) $html .= "<p class='mb-2'><a href='" . esc_url($settings) . "' target='_blank'>" . esc_html(sprintf(__("Open %s settings", "peprodev-ups"), $name)) . "</a></p>";
        return $html;
      }
      $install = admin_url("plugin-install.php?tab=plugin-information&plugin=" . rawurlencode($slug));
      return "<p class='text-danger font-weight-bold mb-2'>&#10008; " . esc_html(sprintf(__("%s is not installed or not active. SMS will not be sent until it is activated.", "peprodev-ups"), $name)) . "</p>"
        . "<p class='mb-2'><a href='" . esc_url($install) . "' target='_blank'>" . esc_html(sprintf(__("Install %s", "peprodev-ups"), $name)) . "</a></p>";
    }
  }
}
