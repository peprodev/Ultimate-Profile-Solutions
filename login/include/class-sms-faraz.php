<?php
/*
 * @Author: Amirhossein Hosseinpour <https://amirhp.com>
 * @Date Created: 2023/02/12 16:39:06
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2024/08/24 02:40:35
 */

namespace PeproDev\PeproCore\RegLogin;

defined("ABSPATH") or die("PeproDev Ultimate Profile Solutions :: Unauthorized Access! (https://pepro.dev/)");

final class PeproSMS_Faraz_Gateway {
  protected $td;
  protected $setting_slug;
  protected $activation_status;
  protected $save_prefix;
  protected $server_url = "ippanel.com";
  protected $faraz_username;
  protected $faraz_password;
  protected $faraz_message;
  protected $faraz_sendernumber;
  protected $farazlookup_api_key;
  protected $farazlookup_password;
  protected $farazlookup_sender = "3000505";
  protected $farazlookup_code;
  protected $farazlookup_template_id;
  public function __construct() {
    $this->td                = "peprodev-ups";
    $this->setting_slug      = "loginregister";
    $this->activation_status = "PeproDevUPS_Core___{$this->setting_slug}";
    $this->save_prefix       = $this->activation_status;

    $this->faraz_username          = get_option("{$this->save_prefix}-faraz_username");
    $this->faraz_password          = get_option("{$this->save_prefix}-faraz_password");
    $this->faraz_message           = get_option("{$this->save_prefix}-faraz_message");

    $this->faraz_sendernumber      = get_option("{$this->save_prefix}-faraz_sendernumber");
    $this->farazlookup_sender      = get_option("{$this->save_prefix}-farazlookup_sender");
    $this->farazlookup_api_key     = get_option("{$this->save_prefix}-farazlookup_api_key");
    $this->farazlookup_password    = get_option("{$this->save_prefix}-farazlookup_password");
    $this->farazlookup_code        = get_option("{$this->save_prefix}-farazlookup_code");
    $this->farazlookup_template_id = get_option("{$this->save_prefix}-farazlookup_template_id");

    add_filter("pepro_reglogin_sms_verification_gateways", array($this, "sms_verification_gateways"));
    add_filter("pepro_reglogin_save_text_fields", array($this, "save_text_fields"));
    add_filter("pepro_reglogin_save_raw_fields", array($this, "save_textarea_fields"));
  }
  public function sms_verification_gateways($gateways = array()) {
    $gateways["FarazSMS"] = array(
      "name"       => _x("FarazSMS", "gateway", $this->td),
      "fn_send"    => array($this, "class_faraz"),
      "fn_setting" => array($this, "setting_faraz"),
    );
    $gateways["FarazSMSPattern"] = array(
      "name"       => _x("FarazSMS Pattern", "gateway", $this->td),
      "fn_send"    => array($this, "class_pattern"),
      "fn_setting" => array($this, "setting_pattern"),
    );
    return $gateways;
  }
  public function save_text_fields($prev = array()) {
    $prev[] = "faraz_username";
    $prev[] = "faraz_sendernumber";
    $prev[] = "farazlookup_sender";
    $prev[] = "farazlookup_api_key";
    $prev[] = "farazlookup_password";
    $prev[] = "farazlookup_code";
    $prev[] = "farazlookup_template_id";
    return $prev;
  }
  public function save_textarea_fields($prev = array()) {
    $prev[] = "faraz_password";
    $prev[] = "faraz_message";
    return $prev;
  }
  public function setting_faraz() {
    ob_start();
    ?>
    <p class="font-weight-bold p-3"><?php esc_html_e("FarazSMS Setting", "peprodev-ups"); ?></p>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-faraz_username'>
      <div class="col-lg-6 label"><span><?php esc_html_e("Username", "peprodev-ups"); ?></span><?php echo $this->external_link("https://sms.farazsms.com/"); ?></div>
      <div class="col-lg-6"><input name="faraz_username" value="<?php echo esc_attr($this->faraz_username); ?>" dir="ltr" class='form-input single-required mr-2' autocomplete="off" type="text" /></div>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-faraz_username'>
      <div class="col-lg-6 label"><span><?php esc_html_e("Password", "peprodev-ups"); ?></span></div>
      <div class="col-lg-6"><input name="faraz_password" value="<?php echo esc_attr($this->faraz_password); ?>" dir="ltr" class='form-input single-required mr-2' autocomplete="off" type="password" /></div>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-faraz_sendernumber'>
      <div class="col-lg-6 label"><span><?php esc_html_e("Sender number", "peprodev-ups"); ?></span></div>
      <div class="col-lg-6"><input name="faraz_sendernumber" value="<?php echo esc_attr($this->faraz_sendernumber); ?>" dir="ltr" class='form-input single-required mr-2' autocomplete="off" type="text" /></div>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-faraz_message'>
      <div class="col-lg-6 label"><span><?php esc_html_e("Message containing [OTP]", "peprodev-ups"); ?></span></div>
      <div class="col-lg-6"><textarea name="faraz_message" autocomplete="off" class='form-input single-required mr-2' placeholder="e.g: Your Code: [OTP]" /><?php echo wp_strip_all_tags($this->faraz_message); ?></textarea></div>
    </div>
    <?php
    $htmloutput = ob_get_contents();
    ob_end_clean();
    return $htmloutput;
  }
  private function _execute($url, $postData) {
    $userAgent = sprintf("IPPanel/ApiClient/%s PHP/%s", "2.0.0", phpversion());
    $args = array(
      "method"       => 'POST',
      "body"         => $postData,
      "httpversion"  => '1.0',
      "headers"      => array(
        "apikey"     => $this->faraz_username,
        "User-Agent" => $userAgent,
      )
    );
    return wp_remote_retrieve_body(wp_remote_request($url, $args));
  }
  public function class_faraz($numbers = "", $message = "", $otp_code = 0) {
    $response = false;
    $message  = str_replace("[OTP]", $otp_code, $message);
    $message  = str_replace("{OTP}", $otp_code, $message);
    if (empty($this->faraz_username) || empty($this->faraz_password)) {
      return $response;
    }

    $url = "https://ippanel.com/services.jspd";
    $param = array(
      "uname"   => $this->faraz_username,
      "pass"    => $this->faraz_password,
      "from"    => $this->faraz_sendernumber,
      "message" => $message,
      "to"      => json_encode($numbers),
      "op"      => 'send'
    );
    $handler = curl_init($url);
    curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($handler, CURLOPT_POSTFIELDS, $param);
    curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
    $response2 = curl_exec($handler);
    $response2 = json_decode($response2);
    $res_code = $response2[0];
    $response = $response2[1];
    return $response;
  }
  public function setting_pattern() {
    ob_start();
    ?>
    <p class="font-weight-bold p-3"><?php esc_html_e("FarazSMS (Lookup) Setting", "peprodev-ups"); ?></p>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-farazlookup_api_key'>
      <div class="col-lg-6 label"><span><?php esc_html_e("Username", "peprodev-ups"); ?></span><?php echo $this->external_link("https://sms.farazsms.com/"); ?></div>
      <div class="col-lg-6"><input name="farazlookup_api_key" value="<?php echo esc_attr($this->farazlookup_api_key); ?>" dir="ltr" class='form-input single-required mr-2' autocomplete="off" type="text" /></div>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-farazlookup_password'>
      <div class="col-lg-6 label"><span><?php esc_html_e("Password", "peprodev-ups"); ?></span></div>
      <div class="col-lg-6"><input name="farazlookup_password" value="<?php echo esc_attr($this->farazlookup_password); ?>" dir="ltr" class='form-input single-required mr-2' autocomplete="off" type="password" /></div>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-farazlookup_sender'>
      <div class="col-lg-6 label"><span><?php esc_html_e("Sender Number", "peprodev-ups"); ?></span></div>
      <div class="col-lg-6"><input name="farazlookup_sender" value="<?php echo esc_attr($this->farazlookup_sender); ?>" dir="ltr" placeholder="e.g. 3000505" class='form-input single-required mr-2' autocomplete="off" type="text" /></div>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-farazlookup_code'>
      <div class="col-lg-6 label"><span><?php esc_html_e("Template Variable", "peprodev-ups"); ?></span></div>
      <div class="col-lg-6"><input name="farazlookup_code" value="<?php echo esc_attr($this->farazlookup_code); ?>" dir="ltr" class='form-input single-required mr-2' autocomplete="off" type="text" /></div>
    </div>
    <div class='col-lg-12 row justify-content-between mb-3 field-opt-farazlookup_template_id'>
      <div class="col-lg-6 label"><span><?php esc_html_e("Template Code", "peprodev-ups"); ?></span></div>
      <div class="col-lg-6"><input name="farazlookup_template_id" value="<?php echo esc_attr($this->farazlookup_template_id); ?>" dir="ltr" class='form-input single-required mr-2' autocomplete="off" type="text" /></div>
    </div>
    <?php
    $htmloutput = ob_get_contents();
    ob_end_clean();
    return $htmloutput;
  }
  public function external_link($url = '#') {
    return ' <a href="' . esc_url($url) . '" class="btn btn-sm btn-round btn-group btn-info float-left m-0" target="_blank"><i class="fas fa-external-link-alt"></i></a>';
  }
  public function class_pattern($numbers = "", $message = "", $otp_code = 0) {
    $response    = false;
    $code        = $this->farazlookup_code;
    $username    = $this->farazlookup_api_key;
    $password    = $this->farazlookup_password;
    $sender      = $this->farazlookup_sender;
    $template_id = $this->farazlookup_template_id;
    if (empty($username)) { return $response; }
    $message = "patterncode:{$template_id}\n{$code}:{$otp_code}";
    return $this->send_faraz_normal_pattern($numbers, $message, $username, $password, $sender, "");
  }
  public function send_faraz_normal_pattern($numbers, $message, $username, $password, $from, $domain) {
    $response = false;
    
    if(!$username || empty($username)) $username = trim($this->faraz_username);
    if(!$password || empty($password)) $password = trim($this->faraz_password);
    if(!$from || empty($from        )) $from     = trim($this->faraz_sendernumber);
    if(!$domain || empty($domain    )) $domain   = trim($this->server_url);

    if (empty($username) || empty($password) || empty($from)) {
      return new \WP_Error("empty-user-pass", _x("SMS Provider credentials is not filled completely.", "sms-error", $this->td));
    }
    $massage = trim(wp_strip_all_tags($message));
    if (is_array($numbers)) $to = $numbers;
    else $to = explode(',', $numbers);
    $massage = str_replace('pcode:', 'patterncode:', wp_strip_all_tags(trim($massage)));
    if (substr($massage, 0, 11) === "patterncode") {
      $massage = str_replace("\r\n", ";", $massage);
      $massage = str_replace("\n", ";", $massage);
      $splited = explode(';', $massage);
      $patterncodeArray = explode(':', $splited[0]);
      $patterncode = trim($patterncodeArray[1]);
      unset($splited[0]);
      $input_data = array();
      foreach ($splited as $parm) {
        $splited_parm = explode(':', $parm, 2);
        $input_data[$splited_parm[0]] = trim($splited_parm[1]);
      }
      foreach ($to as $toNum) {
        $url = "/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode(array($toNum)) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=" . $patterncode;
        $result = $this->cUrl($url, array(), 'GET');
      }
      $resultarray = json_decode($result);
      if (is_array($resultarray) && $resultarray[0] != 'sent') {
        $res_code = $resultarray[0];
        $res_data = $resultarray[1];
        return $this->getErrors($res_code);
      }
      $response = true;
      return $response;
    } else {
      $url = "/services.jspd";
      $params = array('uname' => $username, 'pass' => $password, 'from' => $from, 'message' => $massage, 'to' => json_encode($to), 'op' => 'send');
      $result = $this->cUrl($url, $params, 'POST');
      $result2 = json_decode($result);
      $res_code = $result2[0];
      $res_data = $result2[1];
      if ($res_code == '0') {
        $response = true;
      } else {
        $response = $this->getErrors($res_code);
      }
      return $response === true ? "successfully sent" : $response;
    }
  }
  private function cUrl($url, $params = array(), $method = 'POST') {
    $handler = curl_init($this->server_url . $url);
    curl_setopt($handler, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($handler, CURLOPT_TIMEOUT, 30);
    curl_setopt($handler, CURLOPT_CUSTOMREQUEST, $method);
    if ($method == 'POST') curl_setopt($handler, CURLOPT_POSTFIELDS, $params);
    curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handler, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($handler);
    if (curl_errno($handler)) {
      $result = curl_error($handler);
      return json_encode(array('-1', $result));
    }
    return $result;
  }
  private function getErrors($error) {
    $errorCodes = array(
      '-1'   => 'ارتباط با سامانه پیامک انجام نشد.',
      '0'    => 'عملیات با موفقیت انجام شده است.',
      '1'    => 'متن پیام خالی می باشد.',
      '2'    => 'کاربر محدود گردیده است.',
      '3'    => 'خط به شما تعلق ندارد.',
      '4'    => 'گیرندگان خالی است.',
      '5'    => 'اعتبار کافی نیست.',
      '7'    => 'خط مورد نظر برای ارسال انبوه مناسب نمی باشد.',
      '9'    => 'خط مورد نظر در این ساعت امکان ارسال ندارد. برای ارسال پیامک در ۲۴ ساعت شبانه روز از وب سرویس پترن استفاده نمایید',
      '98'   => 'حداکثر تعداد گیرنده رعایت نشده است.',
      '99'   => 'اپراتور خط ارسالی قطع می باشد.',
      '21'   => 'پسوند فایل صوتی نامعتبر است.',
      '22'   => 'سایز فایل صوتی نامعتبر است.',
      '23'   => 'تعداد تالش در پیام صوتی نامعتبر است.',
      '100'  => 'شماره مخاطب دفترچه تلفن نامعتبر می باشد.',
      '101'  => 'شماره مخاطب در دفترچه تلفن وجود دارد.',
      '102'  => 'شماره مخاطب با موفقیت در دفترچه تلفن ذخیره گردید.',
      '111'  => 'حداکثر تعداد گیرنده برای ارسال پیام صوتی رعایت نشده است.',
      '131'  => 'تعداد تالش در پیام صوتی باید یکبار باشد.',
      '132'  => 'آدرس فایل صوتی وارد نگردیده است.',
      '301'  => 'از حرف ویژه در نام کاربری استفاده گردیده است.',
      '302'  => 'قیمت گذاری انجام نگردیده است.',
      '303'  => 'نام کاربری وارد نگردیده است.',
      '304'  => 'نام کاربری قبال انتخاب گردیده است.',
      '305'  => 'نام کاربری وارد نگردیده است.',
      '306'  => 'کد ملی وارد نگردیده است.',
      '307'  => 'کد ملی به خطا وارد شده است.',
      '308'  => 'شماره شناسنامه نا معتبر است.',
      '309'  => 'شماره شناسنامه وارد نگردیده است.',
      '310'  => 'ایمیل کاربر وارد نگردیده است.',
      '311'  => 'شماره تلفن وارد نگردیده است.',
      '312'  => 'تلفن به درستی وارد نگردیده است.',
      '313'  => 'آدرس شما وارد نگردیده است.',
      '314'  => 'شماره موبایل را وارد نکرده اید.',
      '315'  => 'شماره موبایل به نادرستی وارد گردیده است.',
      '316'  => 'سطح دسترسی به نادرستی وارد گردیده است.',
      '317'  => 'کلمه عبور وارد نگردیده است.',
      '455'  => 'ارسال در آینده برای کد بالک ارسالی لغو شد.',
      '456'  => 'کد بالک ارسالی نامعتبر است.',
      '458'  => 'کد تیکت نامعتبر است.',
      '964'  => 'شما دسترسی نمایندگی ندارید.',
      '962'  => 'نام کاربری یا کلمه عبور نادرست می باشد.',
      '963'  => 'دسترسی نامعتبر می باشد.',
      '971'  => 'پترن ارسالی نامعتبر است.',
      '970'  => 'پارامتر های ارسالی برای پترن نامعتبر است.',
      '972'  => 'دریافت کننده برای ارسال پترن نامعتبر می باشد.',
      '992'  => 'ارسال پیام از ساعت 8 تا 23 می باشد. برای ارسال پیامک در ۲۴ ساعت شبانه روز از وب سرویس پترن استفاده نمایید',
      '993'  => 'دفترچه تلفن باید یک آرایه باشد',
      '994'  => 'لطفا تصویری از کارت بانکی خود را از منو مدارک ارسال کنید',
      '995'  => 'جهت ارسال با خطوط اشتراکی سامانه، لطفا شماره کارت بانکی خود را به دلیل تکمیل فرایند احراز هویت از بخش ارسال مدارک ثبت نمایید.',
      '996'  => 'پترن فعال نیست.',
      '997'  => 'شما اجازه ارسال از این پترن را ندارید.',
      '998'  => 'کارت ملی یا کارت بانکی شما تایید نشده است.',
      '1001' => 'فرمت نام کاربری درست نمی باشد)حداقل ۵ کاراکتر، فقط حروف و اعداد(.',
      '1002' => 'گذرواژه خیلی ساده می باشد)حداقل ۸ کاراکتر بوده و نام کاربری، ایمیل و شماره موبایل در آن وجود نداشته باشد(.',
      '1003' => 'مشکل در ثبت، با پشتیبانی تماس بگیرید.',
      '1004' => 'مشکل در ثبت، با پشتیبانی تماس بگیرید.',
      '1005' => 'مشکل در ثبت، با پشتیبانی تماس بگیرید.',
      '1006' => 'تاریخ ارسال پیام برای گذشته می باشد، لطفا تاریخ ارسال پیام را به درستی وارد نمایید.',
      );
    return (isset($errorCodes[$error])) ? $errorCodes[$error] : 'اشکال تعریف نشده با کد :' . $error;
  }
}

return new PeproSMS_Faraz_Gateway;
