<?php

# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/08/31 19:43:40

namespace PeproDev\PeproCore\RegLogin;
class SendSMS
{
    private $api_url;
    private $api_key;
    private $secret_key;
    private $line_number;
    public function __construct($api_url, $api_key, $secret_key, $line_number)
    {
      $this->api_url = untrailingslashit($api_url);
      $this->api_key = $api_key;
      $this->secret_key = $secret_key;
      $this->line_number = $line_number;
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
