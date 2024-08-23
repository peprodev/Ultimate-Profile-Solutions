<?php
/*
 * @Author: Amirhossein Hosseinpour <https://amirhp.com>
 * @Date Created: 2023/02/06 01:05:22
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2024/08/24 02:40:35
 */

namespace PeproDev\PeproCore\RegLogin;

defined("ABSPATH") or die("PeproDev Ultimate Profile Solutions :: Unauthorized Access! (https://pepro.dev/)");

final class PeproSMS_Green_Gateway {
  protected $td;
  protected $setting_slug;
  protected $activation_status;
  protected $save_prefix;
  protected $api_url;
  protected $api_key;
  protected $TemplateId;
  protected $AddName;
  protected $sms_number;
  protected $sms_text;
  public function __construct() {
    $this->td                = "peprodev-ups";
    $this->setting_slug      = "loginregister";
    $this->activation_status = "PeproDevUPS_Core___{$this->setting_slug}";
    $this->save_prefix       = $this->activation_status;
    $this->api_url           = untrailingslashit(get_option("{$this->save_prefix}-pars_green_api_url", "http://sms.parsgreen.ir"));
    $this->api_key           = get_option("{$this->save_prefix}-pars_green_api_key");
    $this->TemplateId        = get_option("{$this->save_prefix}-pars_green_template_id");
    $this->AddName           = get_option("{$this->save_prefix}-pars_green_add_name");
    $this->sms_number        = get_option("{$this->save_prefix}-pars_green_sms_number");
    add_filter("pepro_reglogin_sms_verification_gateways", array($this, "sms_verification_gateways"), 10, 1);
    add_filter("pepro_reglogin_save_text_fields",          array($this, "save_text_fields"));
    add_filter("pepro_reglogin_save_raw_fields",           array($this, "save_textarea_fields"));
  }
  public function sms_verification_gateways($gateways = array()) {
    $gateways["pars-green"] = array(
      "name"       => _x("Pars Green", "gateway", $this->td),
      "fn_send"    => array($this, "send_sms_fn"),
      "fn_setting" => array($this, "setting_wrapper"),
    );
    return $gateways;
  }
  public function save_text_fields($prev = array()) {
    $prev[] = "pars_green_api_url";
    $prev[] = "pars_green_api_key";
    $prev[] = "pars_green_add_name";
    $prev[] = "pars_green_sms_number";
    return $prev;
  }
  public function save_textarea_fields($prev = array()) {
    $prev[] = "pars_green_template_id";
    return $prev;
  }
  public function Exec($url_path, $req) {
    try {
      $this->api_url = $this->api_url . '/Apiv2/' . $url_path;
      $ch = curl_init($this->api_url);
      $jsonDataEncoded = json_encode($req);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      $header = array('authorization: BASIC APIKEY:' . $this->api_key, 'Content-Type: application/json;charset=utf-8');
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      $result = curl_exec($ch);
      $res = json_decode($result);
      curl_close($ch);
      return  $res;
    } catch (\Exception $ex) {
      return  '';
    }
  }
  public function send_sms_fn($numbers = "", $message = "", $otp_code = 0) {
    $message = str_replace("[OTP]", $otp_code, $this->TemplateId);
    if (is_numeric(trim($message))) {
      $req = new SendOtp_Req();
      $req->Mobile  = $numbers;
      $req->SmsCode = $otp_code;
      $req->TemplateId = $this->TemplateId;
      $req->AddName = 1 === (int) $this->AddName ? true : false;
      $res =  $this->Exec("Message/SendOtp", $req);
      return isset($res->R_Success) ? (isset($res->R_Message) ? $res->R_Message : $res->R_Success) : (isset($res->R_Code) ? $res->R_Code : false);   
    }
    else {
      $req= new SendSms_Req();
      $req->SmsBody = $message;
      $req->Mobiles = (array) $numbers;
      if (!empty($this->sms_number)) {
        $req->SmsNumber = $this->sms_number;
      }
      $res = $this->Exec("Message/SendSms",$req) ;
      return isset($res->R_Success) ? (isset($res->R_Message) ? $res->R_Message : $res->R_Success) : (isset($res->R_Code) ? $res->R_Code : false);
    }
  }
  public function setting_wrapper() {
    ob_start();
    ?>
    <p class="font-weight-bold p-3"><?php esc_html_e("SMS.ir Setting", "peprodev-ups"); ?></p>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-pars_green_api_url'>
      <div class="col-lg-6 label"><span><?php esc_html_e("API URL", "peprodev-ups"); ?></span></div>
      <div class="col-lg-6"><input name="pars_green_api_url" placeholder="<?php esc_html_e("API URL", "peprodev-ups"); ?>" value="<?php echo esc_attr($this->api_url); ?>" autocomplete="off" type="text" dir="ltr" class='form-input single-required mr-2' /></div>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-pars_green_api_key'>
      <div class="col-lg-6 label"><span><?php esc_html_e("API Key", "peprodev-ups"); ?></span><?php echo $this->external_link("https://login.parsgreen.com/?Module=DigitalSignature"); ?></div>
      <div class="col-lg-6"><input name="pars_green_api_key" placeholder="<?php esc_html_e("API Key", "peprodev-ups"); ?>" value="<?php echo esc_attr($this->api_key); ?>" autocomplete="off" type="text" dir="ltr" class='form-input single-required mr-2' /></div>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-pars_green_add_name'>
      <div class="col-lg-6 label"><span><?php esc_html_e("Append Name?", "peprodev-ups"); ?></span></div>
      <div class="col-lg-6"><select name="pars_green_add_name" autocomplete="off" class='form-input single-required mr-2' ><option <?php echo selected($this->AddName, 1);?> value="1"><?php echo __("Yes", $this->td);?></option><option <?php echo selected($this->AddName, 0);?> value="0"><?php echo __("No", $this->td);?></option></select></div>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-pars_green_sms_number'>
      <div class="col-lg-6 label"><span><?php esc_html_e("SMS Number", "peprodev-ups"); ?></span></div>
      <div class="col-lg-6"><input name="pars_green_sms_number" placeholder="<?php esc_html_e("SMS Number", "peprodev-ups"); ?>" value="<?php echo esc_attr($this->sms_number); ?>" autocomplete="off" type="text" dir="ltr" class='form-input single-required mr-2' /></div>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-pars_green_template_id'>
    <div class="col-lg-6 label"><span><?php esc_html_e("OTP Template (0-6) or WhiteList Message containing [OTP]", "peprodev-ups"); ?><?php echo $this->external_link("https://login.parsgreen.com/?Module=WhiteText"); ?></span><small class="d-block"><?php echo __("(Enter 0-6 for OTP Send, Text for WhiteList Send)", $this->td);?></small></div>
      <div class="col-lg-6"><textarea name="pars_green_template_id" autocomplete="off" class='form-input single-required mr-2' placeholder="OTP Template (0-6) or WhiteList Message containing [OTP]" /><?php echo wp_strip_all_tags($this->TemplateId); ?></textarea></div>
    </div>
    <?php
    $htmloutput = ob_get_contents();
    ob_end_clean();
    return $htmloutput;
  }
  public function external_link($url = '#') {
    return ' <a href="' . esc_url($url) . '" class="btn btn-sm btn-round btn-group btn-info float-left m-0" target="_blank"><i class="fas fa-external-link-alt"></i></a>';
  }
  public function check() {
    echo "<pre style='text-align: left; direction: ltr; border:1px solid indianred; padding: 1rem; color: indianred; display: block;z-index: 77777777777 !important;position: relative;background: white;'>" . print_r([ '$this->api_url' => $this->api_url, '$this->api_key' => $this->api_key, ], 1) . "</pre>";
  }
}
class SendOtp_Req {
    public $Mobile;
    public $SmsCode;
    public $TemplateId;
    public $AddName;
}
class SendSms_Req {
   public $SmsBody;
   public $Mobiles;
   public $SmsNumber;
}
return new PeproSMS_Green_Gateway;
