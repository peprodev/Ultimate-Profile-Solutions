<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/09/02 14:30:52

namespace PeproDev;
use PeproDev;
class peproSendSMS
{
    public function __construct()
    {
      $this->td                = "peprodev-ups";
      $this->setting_slug      = "loginregister";
      $this->activation_status = "PeproDevUPS_Core___{$this->setting_slug}";
      $this->save_prefix       = $this->activation_status;
      $this->api_url           = untrailingslashit("https://ws.sms.ir/");
      $this->api_key           = get_option("{$this->save_prefix}-sms_api_key");
      $this->secret_key        = get_option("{$this->save_prefix}-sms_secret_key");
      $this->line_number       = get_option("{$this->save_prefix}-sms_api_url");
      $this->sms_text          = get_option("{$this->save_prefix}-smsir_message");
      add_filter( "pepro_reglogin_sms_verification_gateways", array( $this, "sms_verification_gateways"), 10, 1);
    }
    public function sms_verification_gateways($gateways=array())
    {
      $gateways["smsir"] = array(
        "name"       => _x("SMS.ir", "gateway", $this->td),
        "fn_send"    => array( $this, "CLASS_SMSIR"),
        "fn_setting" => array( $this, "setting_smsir"),
      );
      return $gateways;
    }
    public function setting_smsir()
    {
      ob_start();
      ?>
        <p class="font-weight-bold p-3"><?php esc_html_e("SMS.ir Setting", "peprodev-ups");?></p>
        <div class='col-lg-12 row justify-content-between mb-3 field-opt-sms_api_key'>
          <div class="col-lg-6 label"><span><?php esc_html_e("API Key","peprodev-ups");?></span></div>
          <div class="col-lg-6"><input name="sms_api_key" value="<?php echo esc_attr( $this->api_key );?>" autocomplete="off" type="text" dir="ltr" class='form-input single-required mr-2' /></div>
        </div>
        <div class='col-lg-12 row justify-content-between mb-3 field-opt-sms_secret_key'>
          <div class="col-lg-6 label"><span><?php esc_html_e("Security code","peprodev-ups");?></span><?=$this->external_link("https://ip.sms.ir/#/UserApiKey");?></div>
          <div class="col-lg-6"><input name="sms_secret_key" value="<?php echo esc_attr( $this->secret_key );?>" autocomplete="off" type="text" dir="ltr" class='form-input single-required mr-2' /></div>
        </div>
        <div class='col-lg-12 row justify-content-between mb-3 field-opt-sms_api_url'>
          <div class="col-lg-6 label"><span><?php esc_html_e("Sender Number","peprodev-ups");?></span><?=$this->external_link("https://ip.sms.ir/#/UserSetting");?></div>
          <div class="col-lg-6"><input name="sms_api_url" value="<?php echo esc_attr( $this->line_number );?>" autocomplete="off" type="text" dir="ltr" class='form-input single-required mr-2' /></div>
        </div>
        <div class='col-lg-12 row justify-content-between mb-3 field-opt-smsir_message'>
            <div class="col-lg-6 label"><span><?php esc_html_e("Template ID / Message containing [OTP]","peprodev-ups");?></span><?=$this->external_link("https://ip.sms.ir/#/User/UltraFastSendSetting");?></div>
            <div class="col-lg-6"><textarea name="smsir_message" autocomplete="off" class='form-input single-required mr-2' placeholder="UltraFastSend Template ID / Message containing [OTP]" /><?php echo strip_tags($this->sms_text);?></textarea></div>
        </div>
      <?php
      $htmloutput = ob_get_contents();
      ob_end_clean();
      return $htmloutput;
    }
    public function CLASS_SMSIR($numbers=array(), $message="", $otp_code=0)
    {
      $message = str_replace("[OTP]", $otp_code, $this->sms_text);
      if (is_numeric(trim($message))){
        $ParameterArray = array(array( "Parameter" => "OTP", "ParameterValue" => $otp_code));
        return $this->ultraFastSend(array("ParameterArray" => $ParameterArray, "Mobile" => $numbers, "TemplateId" => trim($message)));
      }
      else{
        return $this->send_normal_sms($numbers, $message);
      }
    }
    public function external_link($url='#')
    {
      return ' <a href="'.esc_url($url).'" class="btn btn-sm btn-round btn-group btn-info float-left m-0" target="_blank"><i class="fas fa-external-link-alt"></i></a>';
    }
    public function send_normal_sms($MobileNumbers=array(), $Messages="")
    {
      $SendMessage = false;
      try {
        @$SendDateTime = date("Y-m-d")."T".date("H:i:s");
        $SendMessage = $this->sendMessage($MobileNumbers, $Messages, $SendDateTime);
        return $SendMessage;
      }
      catch (Exeption $e) {
        error_log('Error SMS Send : '.$e->getMessage());
      }
      return $SendMessage;
    }
    public function check()
    {
      echo "<pre style='text-align: left; direction: ltr; border:1px solid indianred; padding: 1rem; color: indianred;
      display: block;z-index: 77777777777 !important;position: relative;background: white;'>".print_r([
        '$this->api_url' => $this->api_url,
        '$this->api_key' => $this->api_key,
        '$this->secret_key' => $this->secret_key,
        '$this->line_number' => $this->line_number,
      ],1)."</pre>";
    }
    public function ultraFastSend($data)
    {
      $token = $this->_getToken($this->api_key, $this->secret_key);
      if ($token != false) {
        $postData = $data;
        $url = $this->getAPIUltraFastSendUrl();
        $UltraFastSend = $this->_execute($postData, $url, $token);
        $object = json_decode($UltraFastSend);
        $result = false;
        if (is_object($object)) {
          $result = $object->Message;
        } else {
          $result = false;
        }
      } else {
        $result = false;
      }
      return $result;
    }
    public function sendMessage($MobileNumbers, $Messages, $SendDateTime = '')
    {
      $token = $this->_getToken($this->api_key, $this->secret_key);
      if ($token != false) {
        $postData = array(
          'Messages'                 => $Messages,
          'MobileNumbers'            => $MobileNumbers,
          'LineNumber'               => $this->line_number,
          'SendDateTime'             => $SendDateTime,
          'CanContinueInCaseOfError' => 'false'
        );
        $url = $this->getAPIMessageSendUrl();
        $SendMessage = $this->_execute($postData, $url, $token);
        $object = json_decode($SendMessage);
        $result = false;
        if (is_object($object)) {
          $result = $object->Message;
        } else {
          $result = false;
        }
      } else {
        $result = false;
      }
      return $result;
    }
    private function _getToken()
    {
      $args = array(
        'method'      => 'POST',
        'body'        => array(
          'UserApiKey' => $this->api_key,
          'SecretKey'  => $this->secret_key,
          'System'     => 'php_rest_v_2_0'
        ),
        'httpversion' => '1.0',
        'headers'     => array(
          'Content-Type: application/json',
          )
      );
      $result = wp_remote_retrieve_body(wp_remote_request($this->getApiTokenUrl(), $args));
      $response = json_decode($result);
      $resp = false;
      $IsSuccessful = '';
      $TokenKey = '';
      if (is_object($response)) {
          $IsSuccessful = $response->IsSuccessful;
          if ($IsSuccessful == true) {
              $TokenKey = $response->TokenKey;
              $resp = $TokenKey;
          } else {
              $resp = false;
          }
      }
      return $resp;
    }
    private function _execute($postData, $url, $token)
    {
      $args = array(
        "method"      => 'POST',
        "body"        => $postData,
        "httpversion" => '1.0',
        "headers"     => array(
          "x-sms-ir-secure-token" => $token,
        )
      );
      return wp_remote_retrieve_body(wp_remote_request($url, $args));
    }
    protected function getAPIUltraFastSendUrl()
    {
        return "{$this->api_url}/api/UltraFastSend";
    }
    protected function getAPIMessageSendUrl()
    {
        return "{$this->api_url}/api/MessageSend";
    }
    protected function getApiTokenUrl()
    {
        return "{$this->api_url}/api/Token";
    }
}
