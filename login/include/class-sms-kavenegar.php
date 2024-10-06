<?php
/*
 * @Author: Amirhossein Hosseinpour <https://amirhp.com>
 * @Date Created: 2023/02/06 01:05:22
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2024/08/24 02:40:33
 */

namespace PeproDev\PeproCore\RegLogin;

defined("ABSPATH") or die("PeproDev Ultimate Profile Solutions :: Unauthorized Access! (https://pepro.dev/)");

final class PeproSMS_KavehNegar_Gateway {
  protected $td;
  protected $setting_slug;
  protected $activation_status;
  protected $save_prefix;
  protected $kavenegar_username;
  protected $kavenegar_message;
  protected $kavenegar_sendernumber;
  protected $kavenegarlookup_api_key;
  protected $kavenegarlookup_template_id;
  function __construct() {
    $this->td                          = "peprodev-ups";
    $this->setting_slug                = "loginregister";
    $this->activation_status           = "PeproDevUPS_Core___{$this->setting_slug}";
    $this->save_prefix                 = $this->activation_status;
    $this->kavenegar_username          = get_option("{$this->save_prefix}-kavenegar_username");
    $this->kavenegar_message           = get_option("{$this->save_prefix}-kavenegar_message");
    $this->kavenegar_sendernumber      = get_option("{$this->save_prefix}-kavenegar_sendernumber");
    $this->kavenegarlookup_api_key     = get_option("{$this->save_prefix}-kavenegarlookup_api_key");
    $this->kavenegarlookup_template_id = get_option("{$this->save_prefix}-kavenegarlookup_template_id");
    add_filter("pepro_reglogin_sms_verification_gateways", array($this, "sms_verification_gateways"));
    add_filter("pepro_reglogin_save_text_fields",          array($this, "save_text_fields"));
    add_filter("pepro_reglogin_save_raw_fields",           array($this, "save_textarea_fields"));
  }
  public function sms_verification_gateways($gateways = array()) {
    $gateways["kavenegar"] = array(
      "name"       => _x("Kavenegar", "gateway", $this->td),
      "fn_send"    => array($this, "CLASS_KAVENEGAR"),
      "fn_setting" => array($this, "setting_kavenegar"),
    );
    $gateways["kavenegarlookup"] = array(
      "name"       => _x("Kavenegar (Lookup)", "gateway", $this->td),
      "fn_send"    => array($this, "CLASS_KAVENEGAR_LOOKUP"),
      "fn_setting" => array($this, "setting_kavenegar_lookup"),
    );
    return $gateways;
  }
  public function save_text_fields($prev = array()) {
    $prev[] = "kavenegar_username";
    $prev[] = "kavenegar_sendernumber";
    $prev[] = "kavenegarlookup_api_key";
    return $prev;
  }
  public function save_textarea_fields($prev = array()) {
    $prev[] = "kavenegar_message";
    $prev[] = "kavenegarlookup_template_id";
    return $prev;
  }
  public function setting_kavenegar() {
    ob_start();
    ?>
    <p class="font-weight-bold p-3"><?php esc_html_e("Kavenegar Setting", "peprodev-ups"); ?></p>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-kavenegar_username'>
      <div class="col-lg-6 label"><span><?php esc_html_e("API Key", "peprodev-ups"); ?></span><?php echo $this->external_link("https://panel.kavenegar.com/Client/Setting/Account"); ?></div>
      <div class="col-lg-6"><input name="kavenegar_username" value="<?php echo esc_attr($this->kavenegar_username); ?>" dir="ltr" class='form-input single-required mr-2' autocomplete="off" type="text" /></div>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-kavenegar_sendernumber'>
      <div class="col-lg-6 label"><span><?php esc_html_e("Sender number", "peprodev-ups"); ?></span></div>
      <div class="col-lg-6"><input name="kavenegar_sendernumber" value="<?php echo esc_attr($this->kavenegar_sendernumber); ?>" dir="ltr" class='form-input single-required mr-2' autocomplete="off" type="text" /></div>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-kavenegar_message'>
      <div class="col-lg-6 label"><span><?php esc_html_e("Message containing [OTP]", "peprodev-ups"); ?></span></div>
      <div class="col-lg-6"><textarea name="kavenegar_message" autocomplete="off" class='form-input single-required mr-2' placeholder="e.g: Your Code: [OTP]" /><?php echo wp_strip_all_tags($this->kavenegar_message); ?></textarea></div>
    </div>
    <?php
    $htmloutput = ob_get_contents();
    ob_end_clean();
    return $htmloutput;
  }
  public function setting_kavenegar_lookup() {
    ob_start();
    ?>
    <p class="font-weight-bold p-3"><?php esc_html_e("Kavenegar (Lookup) Setting", "peprodev-ups"); ?></p>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-kavenegarlookup_api_key'>
      <div class="col-lg-6 label"><span><?php esc_html_e("API Key", "peprodev-ups"); ?></span><?php echo $this->external_link("https://panel.kavenegar.com/Client/Setting/Account"); ?></div>
      <div class="col-lg-6"><input name="kavenegarlookup_api_key" value="<?php echo esc_attr($this->kavenegarlookup_api_key); ?>" dir="ltr" class='form-input single-required mr-2' autocomplete="off" type="text" /></div>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-kavenegarlookup_template_id'>
      <div class="col-lg-6 label"><span><?php esc_html_e("Lookup Template", "peprodev-ups"); ?></span><?php echo $this->external_link("https://panel.kavenegar.com/client/Verification"); ?></div>
      <div class="col-lg-6"><textarea name="kavenegarlookup_template_id" autocomplete="off" class='form-input single-required mr-2' placeholder="e.g: token={OTP}template=SendActivationSMS" /><?php echo wp_strip_all_tags($this->kavenegarlookup_template_id); ?></textarea></div>
    </div>
    <?php
    $htmloutput = ob_get_contents();
    ob_end_clean();
    return $htmloutput;
  }
  public function CLASS_KAVENEGAR($numbers = "", $message = "", $otp_code = 0) {
    $response = false;
    $username = $this->kavenegar_username;
    $message  = $this->kavenegar_message;
    $from     = $this->kavenegar_sendernumber;
    $message  = str_replace("[OTP]", $otp_code, $message);
    $message  = str_replace("{OTP}", $otp_code, $message);
    $receiver = $numbers;
    if (empty($username)) {
      return $response;
    }
    $messages = urlencode($message);
    $url = "https://api.kavenegar.com/v1/$username/sms/send.json?sender=$from&receptor=$receiver&message=$messages";
    if (extension_loaded('curl')) {
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $sms_response = curl_exec($ch);
      curl_close($ch);
    } else {
      $sms_response = @file_get_contents($url);
    }
    if (false !== $sms_response) {
      $json_response = json_decode($sms_response);
      if (!empty($json_response->return->status) && $json_response->return->status == 200) {
        return $response = true;
      }
    }
    if ($response !== true) {
      $response = $sms_response;
    }
    return $response;
  }
  public function CLASS_KAVENEGAR_LOOKUP($numbers = "", $message = "", $otp_code = 0) {
    $response = false;
    $username = $this->kavenegarlookup_api_key;
    $message  = $this->kavenegarlookup_template_id;
    $message  = str_replace("[OTP]", $otp_code, $message);
    $message  = str_replace("{OTP}", $otp_code, $message);
    $receiver = $numbers;
    if (empty($username)) {
      return $response;
    }
    $regex_template = '/(?<=template=)(.*?)(?=token\d*=|$)/is';
    $regex_tokens = '/(token=|token\d=|token\d\d=)/is';
    $regex_variables = '/(?<=token=|token\d=|token\d\d=)(.*?)(?=token\d*=|$|template)/is';
    preg_match_all($regex_template, $message, $template_matches, PREG_PATTERN_ORDER, 0);
    preg_match_all($regex_tokens, $message, $tokens_matches, PREG_PATTERN_ORDER, 0);
    preg_match_all($regex_variables, $message, $variables_matches, PREG_PATTERN_ORDER, 0);
    $templateName = $template_matches[0][0];
    $tokensParam  = "";
    for ($i = 0; $i <= count($tokens_matches[0]) - 1; $i++) {
      $tokenName = $tokens_matches[0][$i];
      $lookupval = html_entity_decode($variables_matches[0][$i]);
      if ((strcasecmp($tokenName, 'token10=') != 0) && (strcasecmp($tokenName, 'token20=') != 0)) {
        $lookupval = str_replace(' ', '-', $lookupval);
      }
      $tokensParam .= "&" . $tokenName . rawurlencode(html_entity_decode($lookupval, ENT_QUOTES, 'UTF-8'));
    }
    $templateName = trim($templateName);
    $url = "http://api.kavenegar.com/v1/{$username}/verify/lookup.json?receptor={$receiver}&template={$templateName}{$tokensParam}";
    if (extension_loaded('curl')) {
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $sms_response = curl_exec($ch);
      curl_close($ch);
    } else {
      $sms_response = @file_get_contents($url);
    }
    if (false !== $sms_response) {
      $json_response = json_decode($sms_response);
      if (!empty($json_response->return->status) && $json_response->return->status == 200) {
        return $response = true;
      }
    }
    if ($response !== true) {
      $response = $sms_response;
    }
    return $response;
  }
  public function external_link($url = '#') {
    return ' <a href="' . esc_url($url) . '" class="btn btn-sm btn-round btn-group btn-info float-left m-0" target="_blank"><i class="fas fa-external-link-alt"></i></a>';
  }
}
return new PeproSMS_KavehNegar_Gateway;
