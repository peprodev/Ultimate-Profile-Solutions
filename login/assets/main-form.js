/**
 * @Date:   2021/08/17 09:28:22
 * @Last modified by:   Amirhosseinhpv
 * @Last modified time: 2021/09/17 18:51:18
 * @License: GPLv2
 */
jQuery.noConflict();
(function($) {
  $(function() {
    $line_two = `background: #0C0C0D; display:block; font-family: 'Palatino Linotype', 'Constantia'; font-weight: 400; padding: 2rem; border-radius: .71rem; margin: .5rem 0; color: #709AFF; font-size: large; line-height: 1.7; text-align: left; `;
    // setTimeout(console.log.bind(console, `%cLogin/Register form Created by Pepro Profile's Login/Reg. module\nIf you have WordPress site, check it out here: https://profiles.w.org/peprodev/ 😉`, $line_two));
    if ("yes" == _i18nj.hidelogin){ $("form#registerform input#user_login").parent().remove(); }
    if ("yes" == _i18nj.hideemail){ $("form#registerform input#user_email").parent().remove(); }
    $(document).on("submit", "form#registerform", function(e) {
      $("#login_error").empty();
      if ($(".g-recaptcha-response").length){
        $(".g-recaptcha-response").each(function(index, val) {
          if ($(val).val() === "") {
            if (!$("#login_error").length) { $("#registerform").before($("<div id='login_error'></div>")); }
            $("#login_error").html(_i18nj.captcha);
            e.preventDefault();
            return false;
          }
        });
      }
      return true;
    });
  });
})(jQuery);
