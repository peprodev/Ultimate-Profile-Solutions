/*
 * @Author: Amirhossein Hosseinpour <https://amirhp.com>
 * @Date Created: 2023/06/27 02:45:26
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2024/10/06 10:58:33
 */

jQuery.noConflict();
(function ($) {
  $(function () {
    $.extend(jQuery.expr[':'], {
      invalid: function (elem, index, match) {
        var invalids = document.querySelectorAll(':invalid'), result = false, len = invalids.length;
        if (len) { for (var i = 0; i < len; i++) { if (elem === invalids[i]) { result = true; break; } } }
        return result;
      }
    });
    $.fn.isValid = function () { return this[0].checkValidity(); };

    $(document.body).append($(`<pdtoast>`));
    $(document.body).append($(`<toastwide>`));
    setTimeout(function () { resizeReCaptcha(); }, 200);
    $(window).on('resize', function () { resizeReCaptcha(); });
    $(document).on("click tap", ".pepro-form-links a", function (e) { setTimeout(function () { resizeReCaptcha(); }, 200); });
    $(".woocommerce-info .showlogin").removeClass().addClass("pepro-open-login");


    $("[data-pepro-reglogin]").each(function (index, val) {
      instance = $(val).data("pepro-reglogin");
      var _pepro_dev = window[instance];
      // _pepro_dev.nonce = $(val).parents(".login-form-container").data("nonce");
      _pepro_dev._ajax_req = null;
      jconfirm.defaults = {
        title: '',
        titleClass: '',
        type: 'blue',
        /* red green orange blue purple dark */
        typeAnimated: true,
        closeIcon: false,
        draggable: true,
        dragWindowGap: 15,
        dragWindowBorder: true,
        animateFromElement: true,
        smoothContent: true,
        content: '',
        buttons: {},
        defaultButtons: {
          ok: {
            keys: ['enter'],
            text: _pepro_dev.okTxt,
            action: function () { }
          },
          close: {
            keys: ['enter'],
            text: _pepro_dev.closeTxt,
            action: function () { }
          },
          cancel: {
            keys: ['esc'],
            text: _pepro_dev.cancelbTn,
            action: function () { }
          },
        },
        contentLoaded: function (data, status, xhr) { },
        icon: '',
        lazyOpen: false,
        bgOpacity: null,
        theme: 'modern',
        /* light dark supervan material bootstrap modern */
        animation: 'scale',
        closeAnimation: 'scale',
        animationSpeed: 400,
        animationBounce: 1,
        rtl: $("body").is(".rtl") ? true : false,
        container: 'body',
        containerFluid: false,
        backgroundDismiss: false,
        backgroundDismissAnimation: 'scale',
        autoClose: false,
        closeIcon: null,
        closeIconClass: false,
        watchInterval: 100,
        columnClass: 'm',
        boxWidth: '500px',
        scrollToPreviousElement: true,
        scrollToPreviousElementAnimate: true,
        useBootstrap: false,
        offsetTop: 40,
        offsetBottom: 40,
        bootstrapClasses: {
          container: 'container',
          containerFluid: 'container-fluid',
          row: 'row',
        },
        onContentReady: function () { },
        onOpenBefore: function () { },
        onOpen: function () { },
        onClose: function () { },
        onDestroy: function () { },
        onAction: function () { },
        escapeKey: true,
      };
      var $success_color = "rgba(21, 139, 2, 0.8)";
      var $error_color = "rgba(139, 2, 2, 0.8)";
      var $info_color = "rgba(2, 133, 139, 0.8)";
      if ("" !== _pepro_dev.trigger) {
        if ($(_pepro_dev.trigger).length) {
          $(_pepro_dev.trigger).attr("data-trigger", _pepro_dev.instance);
          $(_pepro_dev.trigger).data("trigger", _pepro_dev.instance);
          $(`[data-trigger-ref='${_pepro_dev.trigger}']`).attr("data-trigger-ref", _pepro_dev.instance);
          $(`[data-trigger-ref='${_pepro_dev.trigger}']`).data("trigger-ref", _pepro_dev.instance);
        }
      }

      // login
      $(document).on("change keyup", `#${_pepro_dev.instance} form.form-login input[name=optverify]`, function (e) {
        $submitBtn = $(this).parents("form").find(".submit-wrap #submit[type=submit]");
        if ($(this).val() !== "") {
          $submitBtn.text($submitBtn.data("verify"));
        }
        else {
          $submitBtn.text($submitBtn.data("send"));
        }
      });
      $(document).on("change keyup", `#${_pepro_dev.instance} form.form-login input[name=verification]`, function (e) {
        $submitBtn = $(this).parents("form").find(".submit-wrap #submit[type=submit]");
        if ($(this).val() !== "") {
          $submitBtn.text($submitBtn.data("verify"));
        }
        else {
          $submitBtn.text($submitBtn.data("send"));
        }
      });
      $(document).on("submit", `#${_pepro_dev.instance} form.form-login`, function (e) {
        e.preventDefault();
        login_form = $(this);
        $(login_form).find("#login_error").empty();
        scroll_element();
        $(login_form).find("error").remove();
        error_occured = false;
        if (!login_form.isValid()) {
          $(login_form).find("#login_error").removeClass("info success error").addClass("error").html(_pepro_dev.fixerr);
          show_toast(_pepro_dev.fixerr, $error_color);
          $(login_form).find(":input:visible:invalid").each(function (index, val) {
            $errortext = $(val).data("error-text");
            if (!$errortext || "" == $errortext) {
              $errortext = _pepro_dev.check_validity;
              if ($(val).attr("required") !== undefined) {
                $errortext = _pepro_dev.check_required;
              }
            }
            $(val).parent().append(`<error data-tippy-content="${$errortext}">!</error>`);
          });
          tippy('[data-tippy-content]', { allowHTML: true, theme: 'error', });
          scroll_element();
          error_occured = true;
          return false;
        }
        if (error_occured) { $(login_form).find(":input").prop("disabled", false); return false; }
        $recaps = $(login_form).find("div[data-recaptcha]");
        if ($recaps.length) {
          $.each($recaps, function (index, val) {
            if ($(val).find(".g-recaptcha-response").val() === "") {
              $(login_form).find("#login_error").append(_pepro_dev.captcha);
              show_toast(_pepro_dev.captcha, $error_color);
              error_occured = true;
              return false;
            }
          });
          if (error_occured) { return false; }
        }
        if (_pepro_dev._ajax_req != null) { _pepro_dev._ajax_req.abort(); }
        form_params = $(login_form).find(":input").serialize();
        $(login_form).addClass("loading");
        $(login_form).find(":input").prop("disabled", true);
        show_toast(_pepro_dev.loading);
        $(login_form).find("#login_error").removeClass("info success error").addClass("info").html(_pepro_dev.loading);
        show_toast(_pepro_dev.loading);
        $(login_form).find("#mobile").prop("disabled", false).prop("readonly", false).removeClass("disabled");
        $(login_form).find("#user_mobile").prop("disabled", false).prop("readonly", false).removeClass("disabled");
        _pepro_dev._ajax_req = $.ajax({
          type: "POST",
          dataType: "json",
          url: _pepro_dev.ajaxurl,
          data: {
            order: "login",
            action: "pepro_reglogin",
            nonce: _pepro_dev.nonce,
            param: form_params,
          },
          success: function (e) {
            $(login_form).find(".otp-resend,.otp-changenum,.switch-form-register").hide();
            if (e.success === true) {
              $(login_form).find("#login_error").removeClass("info success error").addClass("success").html(e.data.msg);
              show_toast(e.data.msg);
              if (e.data.is_otp) {
                $(login_form).find(".optverify-wrap, .verification-wrap").removeClass("hide").show();
                $(login_form).find("#login_error").removeClass("info success error").addClass("info").html(e.data.msg);
                if (e.data.focus) { setTimeout(function () { $(login_form).find(e.data.focus).first().focus(); }, 100); }
                if (e.data.select) { setTimeout(function () { $(login_form).find(e.data.focus).first().select(); }, 100); }
                $(login_form).find(".pepro-login-reg-field, .switch-form-register, .switch-form-login").hide();
                if (e.data.hide) { $(login_form).find(e.data.hide).hide(); }
                if (e.data.show) { $(login_form).find(e.data.show).show(); }
                $(login_form).find(".pepro-login-reg-field.mobile-wrap, .pepro-login-reg-field.email-wrap, .pepro-login-reg-field.user_mobile-wrap, .pepro-login-reg-field.verification-wrap, .pepro-login-reg-field.optverify-wrap, .pepro-login-reg-field.submit-wrap").show();
                $(login_form).find("#mobileو #user_mobile, #email").prop("disabled", true).prop("readonly", true).addClass("disabled");
                resend_counndown(e, login_form, _pepro_dev);
              }
              else {
                $(".pepro-login-reg-container *").prop("disabled", true).addClass("disabled");
                $(".popup-active").removeClass("popup-active");
                // $(login_form).find(".pepro-login-reg-field").addClass("hide");
                // $(login_form).find(".pepro-form-links").hide();
                $submitBtn = $(login_form).find(".submit-wrap #submit[type=submit]");
                // $(login_form).append(`<div class='pepro-login-reg-field'><button href='${e.data.logout_url}' class='ahrefbtn ${$submitBtn.attr("class")}' target='_self' type='submit'>${e.data.logout_txt}</button></div>`);
                if (e.data.redirect_text) {
                  obj_buttons = {
                    close: {
                      btnClass: "btn-default",
                      text: _pepro_dev.closeTxt,
                      keys: ["esc"],
                      action: function (res) {
                        window.location.reload();
                        jc.close();
                      }
                    },
                    custom: {
                      btnClass: "btn-green",
                      text: e.data.redirect_text,
                      keys: ["enter"],
                      action: function (res) {
                        if (true === e.data.redirect) {
                          window.location.reload();
                        }
                        else if (false !== e.data.redirect) {
                          window.location.href = e.data.redirect;
                        }
                        $(".popup-active").removeClass("popup-active");
                        jc.close();
                      }
                    },
                  };
                  $url = true === e.data.redirect ? window.location.href : e.data.redirect;
                  // $(login_form).append(`<div class='pepro-login-reg-field'><button href='${$url}' class='ahrefbtn ${$submitBtn.attr("class")}' target='_self' type='submit'>${e.data.redirect_text}</button></div>`);
                }
                else {
                  obj_buttons = {
                    close: {
                      btnClass: "btn-green",
                      text: _pepro_dev.closeTxt,
                      keys: ["enter", "esc"],
                      action: function (res) {
                        if (true === e.data.redirect) {
                          window.location.reload();
                        }
                        else if (false !== e.data.redirect) {
                          window.location.href = e.data.redirect;
                        }
                        $(".popup-active").removeClass("popup-active");
                        jc.close();
                      }
                    },
                  };
                }
                if ($(login_form).is(".no_popup_alert")) {
                  show_toast(e.data.msg, $success_color);
                  setTimeout(function () {
                    if (true === e.data.redirect) {
                      window.location.reload();
                    }
                    else if (false !== e.data.redirect) {
                      window.location.href = e.data.redirect;
                    }
                  }, 2000);
                }
                else {
                  jc = $.confirm({
                    title: "",
                    content: e.data.msg,
                    icon: 'fas fa-check-circle',
                    type: 'green',
                    boxWidth: "500px",
                    buttons: obj_buttons,
                  });
                }
              }
            }
            else {
              if (e.data.is_otp) {
                $(login_form).find(".optverify-wrap, .verification-wrap, .switch-form-register").removeClass("hide");
                if (e.data.focus) { setTimeout(function () { $(login_form).find(e.data.focus).first().focus(); }, 100); }
                if (e.data.select) { setTimeout(function () { $(login_form).find(e.data.focus).first().select(); }, 100); }
                if (e.data.hide) { $(login_form).find(e.data.hide).hide(); }
                if (e.data.show) { $(login_form).find(e.data.show).show(); }
                resend_counndown(e, login_form, _pepro_dev);
              }
              $(login_form).find("#login_error").removeClass("info success error").addClass("error").html(e.data.msg);
              show_toast(e.data.msg, $error_color);
            }
          },
          error: function (e) {
            console.error(e);
            $(login_form).find(".switch-form-register").hide();
            $(login_form).find("#login_error").removeClass("info success error").addClass("error").html(_pepro_dev.error);
            show_toast(_pepro_dev.error, $error_color);
          },
          complete: function (e) {
            scroll_element();
            $(login_form).removeClass("loading");
            $(login_form).find(":input").prop("disabled", false);
          },
        });
      });

      // verify/change email
      $(document).on("change keyup", `#${_pepro_dev.instance} form#pepro-verify-inline input[name=verification]`, function (e) {
        $submitBtn = $(this).parents("form").find(".submit-wrap #submit[type=submit]");
        if ($(this).val() !== "") {
          $submitBtn.text($submitBtn.data("verify"));
        } else {
          $submitBtn.text($submitBtn.data("send"));
        }
      });
      $(document).on("submit", `#${_pepro_dev.instance} form#pepro-verify-inline`, function (e) {
        e.preventDefault();
        login_form = $(this);
        $(login_form).find("#login_error").empty();
        scroll_element();
        $(login_form).find("error").remove();
        error_occured = false;
        if (!login_form.isValid()) {
          $(login_form).find("#login_error").removeClass("info success error").addClass("error").html(_pepro_dev.fixerr);
          show_toast(_pepro_dev.fixerr, $error_color);
          $(login_form).find(":input:visible:invalid").each(function (index, val) {
            $errortext = $(val).data("error-text");
            if (!$errortext || "" == $errortext) {
              $errortext = _pepro_dev.check_validity;
              if ($(val).attr("required") !== undefined) {
                $errortext = _pepro_dev.check_required;
              }
            }
            $(val).parent().append(`<error data-tippy-content="${$errortext}">!</error>`);
          });
          tippy('[data-tippy-content]', { allowHTML: true, theme: 'error', });
          scroll_element();
          error_occured = true;
          return false;
        }
        if (error_occured) { $(login_form).find(":input").prop("disabled", false); return false; }

        $recaps = $(login_form).find("div[data-recaptcha]");
        if ($recaps.length) {
          $.each($recaps, function (index, val) {
            if ($(val).find(".g-recaptcha-response").val() === "") {
              $(login_form).find("#login_error").append(_pepro_dev.captcha);
              show_toast(_pepro_dev.captcha, $error_color);
              error_occured = true;
              return false;
            }
          });
          if (error_occured) { return false; }
        }
        if (_pepro_dev._ajax_req != null) { _pepro_dev._ajax_req.abort(); }
        form_params = $(login_form).find(":input").serialize();
        $(login_form).addClass("loading");
        $(login_form).find(":input").prop("disabled", true);
        $(login_form).find("#login_error").removeClass("info success error").addClass("info").html(_pepro_dev.loading);
        show_toast(_pepro_dev.loading);
        _pepro_dev._ajax_req = $.ajax({
          type: "POST",
          dataType: "json",
          url: _pepro_dev.ajaxurl,
          data: {
            order: "verify",
            action: "pepro_reglogin",
            nonce: _pepro_dev.nonce,
            param: form_params,
          },
          success: function (e) {
            $(login_form).find(".otp-resend,.otp-changenum").hide();
            if (e.success === true) {
              $(".popup-active").removeClass("popup-active");
              $(login_form).find("#login_error").removeClass("info success error").addClass("success").html(e.data.msg);
              show_toast(e.data.msg);
              if (e.data.is_otp) {
                $(login_form).find("#login_error").removeClass("info success error").addClass("info").html(e.data.msg);
                $(login_form).find(".verification-wrap").removeClass("hide");
                if (e.data.focus) { setTimeout(function () { $(login_form).find(e.data.focus).first().focus(); }, 100); }
                if (e.data.select) { setTimeout(function () { $(login_form).find(e.data.focus).first().select(); }, 100); }
                if (e.data.hide) { $(login_form).find(e.data.hide).hide(); }
                if (e.data.show) { $(login_form).find(e.data.show).show(); }
                resend_counndown(e, login_form, _pepro_dev);
              }
              else {
                $(".popup-active").removeClass("popup-active");
                $(login_form).find(".pepro-login-reg-field").addClass("hide");
                $submitBtn = $(login_form).find(".submit-wrap #submit[type=submit]");
                obj_buttons = {
                  close: {
                    btnClass: "btn-default",
                    text: _pepro_dev.closeTxt,
                    keys: ["esc"],
                    action: function (res) {
                      window.location.reload();
                      jc.close();
                    }
                  },
                  custom: {
                    btnClass: "btn-green",
                    text: e.data.redirect_text,
                    keys: ["enter"],
                    action: function (res) {
                      if (true === e.data.redirect) {
                        window.location.reload();
                      }
                      else if (false !== e.data.redirect) {
                        window.location.href = e.data.redirect;
                      }
                      jc.close();
                    }
                  },
                };
                $url = true === e.data.redirect ? window.location.href : e.data.redirect;
                if (e.data.redirect_text) {
                  $(login_form).append(`<div class='pepro-login-reg-field'><button href='${$url}' class='ahrefbtn ${$submitBtn.attr("class")}' target='_self' type='submit'>${e.data.redirect_text}</button></div>`);
                } else {
                  // $(login_form).hide();
                  $(login_form).find(".username-wrap, .submit-wrap").removeClass("hide");
                  $(login_form).find(":input").prop("disabled", true);
                }
                if ($(login_form).is(".no_popup_alert")) {
                  show_toast(e.data.msg, $success_color);
                  setTimeout(function () {
                    if (true === e.data.redirect) {
                      window.location.reload();
                    }
                    else if (false !== e.data.redirect) {
                      window.location.href = e.data.redirect;
                    }
                  }, 2000);
                }
                else {
                  jc = $.confirm({
                    title: "",
                    content: e.data.msg,
                    icon: 'fas fa-check-circle',
                    type: 'green',
                    boxWidth: "500px",
                    buttons: obj_buttons,
                  });
                }
              }
            }
            else {
              if (e.data.is_otp) {
                if (e.data.focus) { setTimeout(function () { $(login_form).find(e.data.focus).first().focus(); }, 100); }
                if (e.data.select) { setTimeout(function () { $(login_form).find(e.data.focus).first().select(); }, 100); }
                if (e.data.hide) { $(login_form).find(e.data.hide).hide(); }
                if (e.data.show) { $(login_form).find(e.data.show).show(); }
                resend_counndown(e, login_form, _pepro_dev);

              }
              $(login_form).find("#login_error").removeClass("info success error").addClass("error").html(e.data.msg);
              show_toast(e.data.msg, $error_color);
            }
          },
          error: function (e) {
            console.error(e);
            $(login_form).find("#login_error").removeClass("info success error").addClass("error").html(_pepro_dev.error);
            show_toast(_pepro_dev.error, $error_color);
          },
          complete: function (e) {
            scroll_element();
            $(login_form).removeClass("loading");
            $(login_form).find(":input").prop("disabled", false);
          },
        });
      });

      // verify/change mobile
      $(document).on("change keyup", `#${_pepro_dev.instance} form#pepro-verify-inline-force input[name=verification]`, function (e) {
        $submitBtn = $(this).parents("form").find(".submit-wrap #submit[type=submit]");
        if ($(this).val() !== "") {
          $submitBtn.text($submitBtn.data("verify"));
        } else {
          $submitBtn.text($submitBtn.data("send"));
        }
      });
      $(document).on("submit", `#${_pepro_dev.instance} form#pepro-verify-inline-force`, function (e) {
        e.preventDefault();
        login_form = $(this);
        $(login_form).find("#login_error").empty();
        scroll_element();
        $(login_form).find("error").remove();
        error_occured = false;
        if (!login_form.isValid()) {
          $(login_form).find("#login_error").removeClass("info success error").addClass("error").html(_pepro_dev.fixerr);
          show_toast(_pepro_dev.fixerr, $error_color);
          $(login_form).find(":input:visible:invalid").each(function (index, val) {
            $errortext = $(val).data("error-text");
            if (!$errortext || "" == $errortext) {
              $errortext = _pepro_dev.check_validity;
              if ($(val).attr("required") !== undefined) {
                $errortext = _pepro_dev.check_required;
              }
            }
            $(val).parent().append(`<error data-tippy-content="${$errortext}">!</error>`);
          });
          tippy('[data-tippy-content]', { allowHTML: true, theme: 'error', });
          scroll_element();
          error_occured = true;
          return false;
        }
        if (error_occured) { $(login_form).find(":input").prop("disabled", false); return false; }

        $recaps = $(login_form).find("div[data-recaptcha]");
        if ($recaps.length) {
          $.each($recaps, function (index, val) {
            if ($(val).find(".g-recaptcha-response").val() === "") {
              $(login_form).find("#login_error").append(_pepro_dev.captcha);
              show_toast(_pepro_dev.captcha, $error_color);
              error_occured = true;
              return false;
            }
          });
          if (error_occured) { return false; }
        }
        if (_pepro_dev._ajax_req != null) { _pepro_dev._ajax_req.abort(); }
        form_params = $(login_form).find(":input").serialize();
        $(login_form).addClass("loading");
        $(login_form).find(":input").prop("disabled", true);
        $(login_form).find("#login_error").removeClass("info success error").addClass("info").html(_pepro_dev.loading);
        show_toast(_pepro_dev.loading);
        _pepro_dev._ajax_req = $.ajax({
          type: "POST",
          dataType: "json",
          url: _pepro_dev.ajaxurl,
          data: {
            order: "verifyforce",
            action: "pepro_reglogin",
            nonce: _pepro_dev.nonce,
            param: form_params,
          },
          success: function (e) {
            $(login_form).find(".otp-resend,.otp-changenum").hide();
            if (e.success === true) {
              $(".popup-active").removeClass("popup-active");
              $(login_form).find("#login_error").removeClass("info success error").addClass("success").html(e.data.msg);
              show_toast(e.data.msg);
              if (e.data.is_otp) {
                $(login_form).find("#login_error").removeClass("info success error").addClass("info").html(e.data.msg);
                $(login_form).find(".verification-wrap").removeClass("hide");
                if (e.data.focus) { setTimeout(function () { $(login_form).find(e.data.focus).first().focus(); }, 100); }
                if (e.data.select) { setTimeout(function () { $(login_form).find(e.data.focus).first().select(); }, 100); }
                if (e.data.hide) { $(login_form).find(e.data.hide).hide(); }
                if (e.data.show) { $(login_form).find(e.data.show).show(); }
                resend_counndown(e, login_form, _pepro_dev);
              }
              else {
                $(".popup-active").removeClass("popup-active");
                $(login_form).find(".pepro-login-reg-field").addClass("hide");
                $submitBtn = $(login_form).find(".submit-wrap #submit[type=submit]");
                obj_buttons = {
                  close: {
                    btnClass: "btn-green",
                    text: _pepro_dev.closeTxt,
                    keys: ["esc"],
                    action: function (res) {
                      window.location.reload();
                      jc.close();
                    }
                  },
                  custom: {
                    btnClass: "btn-green",
                    text: e.data.redirect_text,
                    keys: ["enter"],
                    action: function (res) {
                      if (true === e.data.redirect) {
                        window.location.reload();
                      }
                      else if (false !== e.data.redirect) {
                        window.location.href = e.data.redirect;
                      }
                      jc.close();
                    }
                  },
                };
                $url = true === e.data.redirect ? window.location.href : e.data.redirect;
                if (e.data.redirect_text) {
                  $(login_form).append(`<div class='pepro-login-reg-field'><button href='${$url}' class='ahrefbtn ${$submitBtn.attr("class")}' target='_self' type='submit'>${e.data.redirect_text}</button></div>`);
                } else {
                  // $(login_form).hide();
                  $(login_form).find(".username-wrap, .submit-wrap").removeClass("hide");
                  $(login_form).find(":input").prop("disabled", true);
                }
                if ($(login_form).is(".no_popup_alert")) {
                  show_toast(e.data.msg, $success_color);
                  setTimeout(function () {
                    if (true === e.data.redirect) {
                      window.location.reload();
                    }
                    else if (false !== e.data.redirect) {
                      window.location.href = e.data.redirect;
                    }
                  }, 2000);
                }
                else {
                  jc = $.confirm({
                    title: "",
                    content: e.data.msg,
                    icon: 'fas fa-check-circle',
                    type: 'green',
                    boxWidth: "500px",
                    buttons: obj_buttons,
                  });
                }
              }
            }
            else {
              if (e.data.is_otp) {
                if (e.data.focus) { setTimeout(function () { $(login_form).find(e.data.focus).first().focus(); }, 100); }
                if (e.data.select) { setTimeout(function () { $(login_form).find(e.data.focus).first().select(); }, 100); }
                if (e.data.hide) { $(login_form).find(e.data.hide).hide(); }
                if (e.data.show) { $(login_form).find(e.data.show).show(); }
                resend_counndown(e, login_form, _pepro_dev);
              }
              $(login_form).find("#login_error").removeClass("info success error").addClass("error").html(e.data.msg);
              show_toast(e.data.msg, $error_color);
            }
          },
          error: function (e) {
            console.error(e);
            $(login_form).find("#login_error").removeClass("info success error").addClass("error").html(_pepro_dev.error);
            show_toast(_pepro_dev.error, $error_color);
          },
          complete: function (e) {
            scroll_element();
            $(login_form).removeClass("loading");
            $(login_form).find(":input").prop("disabled", false);
          },
        });
      });

      $(document).on("change keyup focus", `#${_pepro_dev.instance} form :input`, function (e) {
        val = $(this).val();
        if ($.trim(val) !== "") {
          $(this).addClass("filled");
          $(this).parent().addClass("filled");
        } else {
          $(this).removeClass("filled");
          $(this).parent().removeClass("filled");
        }
      });
      $(document).on("focusout", `#${_pepro_dev.instance} form :input`, function (e) {
        $(this).removeClass("focused");
        $(this).parent().removeClass("focused");
      });
      $(document).on("focusin", `#${_pepro_dev.instance} form :input`, function (e) {
        $(this).addClass("focused");
        $(this).parent().addClass("focused");
      });

      // register
      $(document).on("change keyup", `#${_pepro_dev.instance} form.form-register input[name=optverify]`, function (e) {
        $submitBtn = $(this).parents("form").find(".submit-wrap #submit[type=submit]");
        if ($(this).val() !== "") {
          $submitBtn.text($submitBtn.data("verify"));
        } else {
          $submitBtn.text($submitBtn.data("send"));
        }
      });
      $(document).on("change keyup", `#${_pepro_dev.instance} form.form-register [name=billing_country]`, function (e) {
        e.preventDefault();
        var login_form = $(`#${_pepro_dev.instance} form.form-register`);
        var states = JSON.parse(_pepro_dev.countries.replace(/&quot;/g, '"'));
        var country = $(this).val();
        var cur_state = $(login_form).find("[name=billing_state]").val();
        if (!$.isEmptyObject(states[country])) {
          var state = states[country],
            $defaultOption = $("<option value=''></option>").text(_pepro_dev.select_state_text);
          $newstate = $("<select></select>")
            .prop("id", "billing_state")
            .prop("name", "billing_state")
            .attr("required", "")
            .addClass("js_field-state")
            .append($defaultOption);

          $.each(state, function (index) {
            var $option = $('<option></option>').prop("value", index).text(state[index]);
            $newstate.append($option);
          });

          $newstate.val("");
          $(login_form).find("[name=billing_state]").replaceWith($newstate);
          $newstate.show().trigger('change');
          $(login_form).find("[name=billing_state]").trigger('change');
        }
        else {
          $newstate = $('<input type="text" />')
            .prop('id', "billing_state")
            .prop('name', "billing_state")
            .attr('required', "")
            .prop('placeholder', _pepro_dev.placeholder_state)
            .addClass('form-text')
            .val("");
          $(login_form).find("[name=billing_state]").replaceWith($newstate);
        }

      });
      $(document).on("change keyup", `#${_pepro_dev.instance} form.form-register [name=billing_state]`, function (e) {
        e.preventDefault();
        let login_form = $(`#${_pepro_dev.instance} form.form-register`);
        let country = $(login_form).find("#billing_country").val();
        let cur_state = $(this).val();

        if ("IR" === country && !$.isEmptyObject(_pepro_dev.iran_cities[cur_state])) {
          $defaultOption = $('<option value=""></option>').text(_pepro_dev.select_state_text);
          $newstate = $('<select></select>')
            .prop('id', "billing_city")
            .prop('name', "billing_city")
            .attr('required', "")
            .append($defaultOption);
          $.each(_pepro_dev.iran_cities[cur_state], function (v, index) {
            var $option = $('<option></option>').prop('value', index).text(index);
            $newstate.append($option);
          });
          $newstate.val("");
          $(login_form).find("[name=billing_city]").replaceWith($newstate).parent().addClass("filled");
          $newstate.show().trigger('change');
        } else {
          $newstate = $('<input type="text" />')
            .prop('id', "billing_city")
            .prop('name', "billing_city")
            .attr('required', "")
            .addClass('form-text').val("");
          // .prop('placeholder',_pepro_dev.placeholder_city )
          $(login_form).find("[name=billing_city]").replaceWith($newstate);
        }

      });
      $(document).on("submit", `#${_pepro_dev.instance} form.form-register`, function (e) {
        e.preventDefault();
        login_form = $(this);
        $(login_form).find("#login_error").empty();
        scroll_element();
        $(login_form).find("error").remove();
        error_occured = false;
        if (!login_form.isValid()) {
          $(login_form).find("#login_error").removeClass("info success error").addClass("error").html(_pepro_dev.fixerr);
          show_toast(_pepro_dev.fixerr, $error_color);
          $(login_form).find(":input:visible:invalid").each(function (index, val) {
            $errortext = $(val).data("error-text");
            if (!$errortext || "" == $errortext) {
              $errortext = _pepro_dev.check_validity;
              if ($(val).attr("required") !== undefined) {
                $errortext = _pepro_dev.check_required;
              }
            }
            $(val).parent().append(`<error data-tippy-content="${$errortext}">!</error>`);
          });
          tippy('[data-tippy-content]', { allowHTML: true, theme: 'error', });
          scroll_element();
          error_occured = true;
          return false;
        }
        if (error_occured) { $(login_form).find(":input").prop("disabled", false); return false; }
        $recaps = $(login_form).find("div[data-recaptcha]");
        if ($recaps.length) {
          $.each($recaps, function (index, val) {
            if ($(val).find(".g-recaptcha-response").val() === "") {
              $(login_form).find("#login_error").append(_pepro_dev.captcha);
              show_toast(_pepro_dev.captcha, $error_color);
              error_occured = true;
              return false;
            }
          });
          if (error_occured) { return false; }
        }
        if (_pepro_dev._ajax_req != null) { _pepro_dev._ajax_req.abort(); }
        form_params = $(login_form).find(":input").serialize();
        $(login_form).addClass("loading");
        $(login_form).find(":input").prop("disabled", true);
        $(login_form).find("#login_error").removeClass("info success error").addClass("info").html(_pepro_dev.loading);
        show_toast(_pepro_dev.loading);
        _pepro_dev._ajax_req = $.ajax({
          type: "POST",
          dataType: "json",
          url: _pepro_dev.ajaxurl,
          data: {
            order: "register",
            action: "pepro_reglogin",
            nonce: _pepro_dev.nonce,
            param: form_params,
          },
          success: function (e) {
            $(login_form).find(":input").prop("disabled", false);
            $(login_form).find(".otp-resend,.otp-changenum").hide();
            if (e.success === true) {
              $(login_form).find("#login_error").removeClass("info success error").addClass("success").html(e.data.msg);
              show_toast(e.data.msg);
              if (e.data.is_otp) {
                $(login_form).find("#login_error").removeClass("info success error").addClass("info").html(e.data.msg);
                $(login_form).find(".pepro-login-reg-field").addClass("hide");
                $(login_form).find(".user_mobile-wrap, .submit-wrap, .optverify-wrap, .verification-wrap, [data-recaptcha]").removeClass("hide");
                if (e.data.focus) { setTimeout(function () { $(login_form).find(e.data.focus).first().focus(); }, 100); }
                if (e.data.select) { setTimeout(function () { $(login_form).find(e.data.focus).first().select(); }, 100); }
                $(login_form).find(".pepro-login-reg-field, .switch-form-register, .switch-form-login").hide();
                if (e.data.show) { $(login_form).find(e.data.show).show(); }
                if (e.data.hide) { $(login_form).find(e.data.hide).hide().addClass("hide"); }
                $(login_form).find("#mobile, #user_mobile, #email").prop("disabled", true).prop("readonly", true).addClass("disabled");
                $(login_form).find(".pepro-login-reg-field.mobile-wrap, .pepro-login-reg-field.email-wrap, .pepro-login-reg-field.verification-wrap, .pepro-login-reg-field.optverify-wrap, .pepro-login-reg-field.submit-wrap").show();
                $(login_form).find("#mobile, #user_mobile, #email").prop("disabled", true).prop("readonly", true).addClass("disabled");
                resend_counndown(e, login_form, _pepro_dev);
              }
              else {
                $(".popup-active").removeClass("popup-active");
                $(".pepro-login-reg-container *").prop("disabled", true).addClass("disabled");
                // $(login_form).find(".pepro-login-reg-field").addClass("hide");
                // $(login_form).find(".pepro-form-links").hide();
                $submitBtn = $(login_form).find(".submit-wrap #submit[type=submit]");
                // $(login_form).append(`<div class='pepro-login-reg-field'><button href='${_pepro_dev.gohome_url}' class='ahrefbtn ${$submitBtn.attr("class")}' target='_self' type='submit'>${_pepro_dev.gohome_txt}</button></div>`);
                // $(login_form).append(`<div class='pepro-login-reg-field'><button href='${e.data.logout_url}' class='ahrefbtn ${$submitBtn.attr("class")}' target='_self' type='submit'>${e.data.logout_txt}</button></div>`);
                if (e.data.redirect_text) {
                  obj_buttons = {
                    close: {
                      btnClass: "btn-default",
                      text: _pepro_dev.closeTxt,
                      keys: ["esc"],
                      action: function (res) {
                        $(".popup-active").removeClass("popup-active");
                        window.location.reload();
                        jc.close();
                      }
                    },
                    custom: {
                      btnClass: "btn-green",
                      text: e.data.redirect_text,
                      keys: ["enter"],
                      action: function (res) {
                        if (true === e.data.redirect) {
                          window.location.reload();
                        }
                        else if (false !== e.data.redirect) {
                          window.location.href = e.data.redirect;
                        }
                        $(".popup-active").removeClass("popup-active");
                        jc.close();
                      }
                    },
                  };
                  $url = true === e.data.redirect ? _pepro_dev.gohome_url : e.data.redirect;
                  // $(login_form).append(`<div class='pepro-login-reg-field'><button href='${$url}' class='ahrefbtn ${$submitBtn.attr("class")}' target='_self' type='submit'>${e.data.redirect_text}</button></div>`);
                }
                else {
                  obj_buttons = {
                    close: {
                      btnClass: "btn-green",
                      text: _pepro_dev.closeTxt,
                      keys: ["enter", "esc"],
                      action: function (res) {
                        if (true === e.data.redirect) {
                          window.location.reload();
                        }
                        else if (false !== e.data.redirect) {
                          window.location.href = e.data.redirect;
                        }
                        $(".popup-active").removeClass("popup-active");
                        jc.close();
                      }
                    },
                  };
                }
                if ($(login_form).is(".no_popup_alert")) {
                  show_toast(e.data.msg, $success_color);
                  setTimeout(function () {
                    if (true === e.data.redirect) {
                      window.location.reload();
                    }
                    else if (false !== e.data.redirect) {
                      window.location.href = e.data.redirect;
                    }
                  }, 2000);
                }
                else {
                  jc = $.confirm({
                    title: "",
                    content: e.data.msg,
                    icon: 'fas fa-check-circle',
                    type: 'green',
                    boxWidth: "500px",
                    buttons: obj_buttons,
                  }
                  );
                }
              }
            }
            else {
              if (e.data.is_otp) {
                if (e.data.focus) { setTimeout(function () { $(login_form).find(e.data.focus).first().focus(); }, 100); }
                if (e.data.select) { setTimeout(function () { $(login_form).find(e.data.focus).first().select(); }, 100); }
                if (e.data.show) { $(login_form).find(e.data.show).show(); }
                if (e.data.hide) { $(login_form).find(e.data.hide).hide(); }
                resend_counndown(e, login_form, _pepro_dev);
              }
              $(login_form).find("#login_error").removeClass("info success error").addClass("error").html(e.data.msg);
              show_toast(e.data.msg, $error_color);
            }
          },
          error: function (e) {
            console.error(e);
            $(login_form).find("#login_error").removeClass("info success error").addClass("error").html(_pepro_dev.error);
            show_toast(_pepro_dev.error, $error_color);
          },
          complete: function (e) {
            $(login_form).find(":input").prop("disabled", false);
            scroll_element();
            $(login_form).removeClass("loading");
          },
        });
      });

      // reset-pass
      $(document).on("submit", `#${_pepro_dev.instance} form#pepro-pass-inline`, function (e) {
        e.preventDefault();
        login_form = $(this);
        $(login_form).find("#login_error").empty();
        scroll_element();
        $(login_form).find("error").remove();
        error_occured = false;
        if (!login_form.isValid()) {
          $(login_form).find("#login_error").removeClass("info success error").addClass("error").html(_pepro_dev.fixerr);
          show_toast(_pepro_dev.fixerr, $error_color);
          $(login_form).find(":input:visible:invalid").each(function (index, val) {
            $errortext = $(val).data("error-text");
            if (!$errortext || "" == $errortext) {
              $errortext = _pepro_dev.check_validity;
              if ($(val).attr("required") !== undefined) {
                $errortext = _pepro_dev.check_required;
              }
            }
            $(val).parent().append(`<error data-tippy-content="${$errortext}">!</error>`);
          });
          tippy('[data-tippy-content]', { allowHTML: true, theme: 'error', });
          scroll_element();
          error_occured = true;
          return false;
        }
        if (error_occured) { $(login_form).find(":input").prop("disabled", false); return false; }
        $recaps = $(login_form).find("div[data-recaptcha]");
        if ($recaps.length) {
          $.each($recaps, function (index, val) {
            if ($(val).find(".g-recaptcha-response").val() === "") {
              $(login_form).find("#login_error").append(_pepro_dev.captcha);
              show_toast(_pepro_dev.captcha, $error_color);
              error_occured = true;
              return false;
            }
          });
          if (error_occured) { return false; }
        }
        if (_pepro_dev._ajax_req != null) { _pepro_dev._ajax_req.abort(); }
        form_params = $(login_form).find(":input").serialize();
        $(login_form).addClass("loading");
        $(login_form).find(":input").prop("disabled", true);
        $(login_form).find("#login_error").removeClass("info success error").addClass("info").html(_pepro_dev.loading);
        show_toast(_pepro_dev.loading);
        _pepro_dev._ajax_req = $.ajax({
          type: "POST",
          dataType: "json",
          url: _pepro_dev.ajaxurl,
          data: {
            order: "resetpass",
            action: "pepro_reglogin",
            nonce: _pepro_dev.nonce,
            param: form_params,
          },
          success: function (e) {
            $(login_form).find(":input").prop("disabled", false);
            $(login_form).find(".otp-resend,.otp-changenum").hide();
            if (e.success === true) {
              $(login_form).find("#login_error").removeClass("info success error").addClass("success").html(e.data.msg);
              show_toast(e.data.msg);
              if (e.data.is_otp) {
                $(login_form).find("#login_error").removeClass("info success error").addClass("info").html(e.data.msg);
                $(login_form).find(".user_mobile-wrap, .submit-wrap, .optverify-wrap, [data-recaptcha]").removeClass("hide");
                if (e.data.focus) { setTimeout(function () { $(login_form).find(e.data.focus).first().focus(); }, 100); }
                if (e.data.select) { setTimeout(function () { $(login_form).find(e.data.focus).first().select(); }, 100); }
                if (e.data.hide) { $(login_form).find(e.data.hide).hide(); }
                if (e.data.show) { $(login_form).find(e.data.show).show(); }
                resend_counndown(e, login_form, _pepro_dev);
              }
              else {
                $(".popup-active").removeClass("popup-active");
                $(".pepro-login-reg-container *").prop("disabled", true).addClass("disabled");
                // $(login_form).find(".pepro-login-reg-field").addClass("hide");
                // $(login_form).find(".pepro-form-links").hide();
                $submitBtn = $(login_form).find(".submit-wrap #submit[type=submit]");
                // $(login_form).append(`<div class='pepro-login-reg-field'><button href='${_pepro_dev.gohome_url}' class='ahrefbtn ${$submitBtn.attr("class")}' target='_self' type='submit'>${_pepro_dev.gohome_txt}</button></div>`);
                // $(login_form).append(`<div class='pepro-login-reg-field'><button href='${e.data.logout_url}' class='ahrefbtn ${$submitBtn.attr("class")}' target='_self' type='submit'>${e.data.logout_txt}</button></div>`);
                if (e.data.redirect_text) {
                  obj_buttons = {
                    close: {
                      btnClass: "btn-default",
                      text: _pepro_dev.closeTxt,
                      keys: ["esc"],
                      action: function (res) {
                        $(".popup-active").removeClass("popup-active");
                        window.location.reload();
                        jc.close();
                      }
                    },
                    custom: {
                      btnClass: "btn-green",
                      text: e.data.redirect_text,
                      keys: ["enter"],
                      action: function (res) {
                        if (true === e.data.redirect) {
                          window.location.reload();
                        }
                        else if (false !== e.data.redirect) {
                          window.location.href = e.data.redirect;
                        }
                        $(".popup-active").removeClass("popup-active");
                        jc.close();
                      }
                    },
                  };
                  $url = true === e.data.redirect ? _pepro_dev.gohome_url : e.data.redirect;
                  // $(login_form).append(`<div class='pepro-login-reg-field'><button href='${$url}' class='ahrefbtn ${$submitBtn.attr("class")}' target='_self' type='submit'>${e.data.redirect_text}</button></div>`);
                }
                else {
                  obj_buttons = {
                    close: {
                      btnClass: "btn-green",
                      text: _pepro_dev.closeTxt,
                      keys: ["enter", "esc"],
                      action: function (res) {
                        if (true === e.data.redirect) {
                          window.location.reload();
                        }
                        else if (false !== e.data.redirect) {
                          window.location.href = e.data.redirect;
                        }
                        $(".popup-active").removeClass("popup-active");
                        jc.close();
                      }
                    },
                  };
                }
                if ($(login_form).is(".no_popup_alert")) {
                  show_toast(e.data.msg, $success_color);
                  setTimeout(function () {
                    if (true === e.data.redirect) {
                      window.location.reload();
                    }
                    else if (false !== e.data.redirect) {
                      window.location.href = e.data.redirect;
                    }
                  }, 2000);
                }
                else {
                  jc = $.confirm({
                    title: "",
                    content: e.data.msg,
                    icon: 'fas fa-check-circle',
                    type: 'green',
                    boxWidth: "500px",
                    buttons: obj_buttons,
                  });
                }
              }
            }
            else {
              if (e.data.is_otp) {
                if (e.data.focus) { setTimeout(function () { $(login_form).find(e.data.focus).first().focus(); }, 100); }
                if (e.data.select) { setTimeout(function () { $(login_form).find(e.data.focus).first().select(); }, 100); }
                if (e.data.hide) { $(login_form).find(e.data.hide).hide(); }
                if (e.data.show) { $(login_form).find(e.data.show).show(); }
                resend_counndown(e, login_form, _pepro_dev);
              }
              $(login_form).find("#login_error").removeClass("info success error").addClass("error").html(e.data.msg);
              show_toast(e.data.msg, $error_color);
            }
          },
          error: function (e) {
            console.error(e);
            $(login_form).find("#login_error").removeClass("info success error").addClass("error").html(_pepro_dev.error);
            show_toast(_pepro_dev.error, $error_color);
          },
          complete: function (e) {
            $(login_form).find(":input").prop("disabled", false);
            scroll_element();
            $(login_form).removeClass("loading");
          },
        });
      });


      // document.querySelectorAll("[data-error-text]").forEach(function(e){
      // 	e.setCustomValidity(e.attributes["data-error-text"].value);
      // });

    });

    $(document).on("click tap", ".pepro-regpepro-login-popup-wrapper.popup-active #pepro-profile > div", function(e){
      if ($(e.target).is("div.login-form-container")) {
        e.preventDefault();
        $(e.target).parents(".popup-active").removeClass("popup-active")
      }
    });

    setTimeout(function () {
      $("[name=redirect_to]").each(function(i,x){
        if("0" == $(x).val()){ $(x).val(document.referrer).trigger("change"); }
      });
    }, 1000);

    $(".pepro-login-reg-container").find(".form-register").find("#billing_country,#billing_state,#billing_city").attr("required", "required").trigger("change");
    $(".pepro-login-reg-container").find(".form-register").find("#billing_country").trigger("change");

    $(document).on("click tap", "[data-pepro-reglogin] > form .pepro-login-reg-field label", function(e){
      e.preventDefault();
      var me = $(this);
      me.parent().find("#"+me.attr("for")).focus();
    });
    $(document).on("click tap", ".woocommerce-error .showlogin", function (e) {
      e.preventDefault();
      console.log(this);
      $(this).removeClass().addClass("pepro-open-login");
      setTimeout(function () {
        $(".peprodev-ultimate-profile-solution.peprodev-smart-btn.logged-out").first().click();
      }, 500);
    });
    $(document).on("input", "[data-pepro-reglogin]>form.inline :input", function (e) {
      $(this).val(toEnglishDigits($(this).val()));
    });
    $(document).on("click tap", "[data-pepro-reglogin]>form.inline error", function (e) {
      $(this).parent().find(":input").focus().select();
    });
    $(document).on("click tap", ".popup-active", function (e) {
      var me = $(this);
      if ($(e.target).is(".popup-active")) {
        e.preventDefault();
        $(".popup-active").removeClass("popup-active");
      }
    });
    $(document).on("click tap", ".ahrefbtn", function (e) {
      e.preventDefault();
      var me = $(this);
      if ("_blank" == me.attr("target")) {
        window.open(me.attr("href"));
      } else {
        window.location.href = me.attr("href");
      }
    });
    $(document).on("click tap", `[data-trigger]`, function (e) {
      e.preventDefault();
      $popup = $(`[data-trigger-ref='${$(this).data("trigger")}'`).addClass("popup-active");
      $username = $(".popup-active input[name='username']");
      if ($(this).is(".active-login")) {
        $(".popup-active").find("form.inline").removeClass("inline");
        $(".popup-active").find("form.form-login").addClass("inline");
      }
      if ($(this).is(".active-register")) {
        $(".popup-active").find("form.inline").removeClass("inline");
        $(".popup-active").find("form.form-register").addClass("inline");
      }
      if ($username.length) {
        $username.first().focus();
        setTimeout(function () { $username.first().focus(); }, 200);
      }
      setTimeout(function () { resizeReCaptcha(); }, 200);
    });
    $(document).on("click tap", ".switch-form-login", function (e) {
      e.preventDefault();
      $(this).parents(".pepro-login-reg-container").find("form").removeClass("inline");
      if ($(this).parents(".pepro-login-reg-container.via-email-active").length) {
        $(this).parents(".pepro-login-reg-container").find("form.form-login.via-email").addClass("inline");
      } else {
        $(this).parents(".pepro-login-reg-container").find("form.form-login.via-sms").addClass("inline");
      }
    });
    $(document).on("click tap", ".switch-form-register", function (e) {
      e.preventDefault();
      mobile = $(this).parents(".pepro-login-reg-container").find("#mobile").val();
      user_mobile = $(this).parents(".pepro-login-reg-container").find("#user_mobile").val();
      $(this).parents(".pepro-login-reg-container").find("form").removeClass("inline");
      $(this).parents(".pepro-login-reg-container").find(".form-register input").trigger("change");
      $(this).parents(".pepro-login-reg-container").find(".form-register input#user_mobile").val(mobile).trigger("change");
      $(this).parents(".pepro-login-reg-container").find(".form-register input#user_mobile").val(user_mobile).trigger("change");
      if ($(this).parents(".pepro-login-reg-container.via-email-active").length) {
        $(this).parents(".pepro-login-reg-container").find("form.form-register.via-email").addClass("inline");
        $(this).parents(".pepro-login-reg-container").find("form.form-register.via-email .pepro-login-reg-field").not(".hidden").removeClass("hide").show();
        $(this).parents(".pepro-login-reg-container").find("form.form-register.via-email").find(".optverify-wrap, .verification-wrap, .otp-resend").hide();
      } else {
        $(this).parents(".pepro-login-reg-container").find("form.form-register.via-sms").addClass("inline");
        $(this).parents(".pepro-login-reg-container").find("form.form-register.via-email .pepro-login-reg-field").not(".hidden").removeClass("hide").show();
        $(this).parents(".pepro-login-reg-container").find("form.form-register.via-email").find(".optverify-wrap, .verification-wrap, .otp-resend").hide();
      }
    });
    $(document).on("click tap", ".otp-resend", function (e) {
      e.preventDefault();
      var me = $(this);
      me.parents("form").find(".otp-verification, .code-verification").val("").trigger("change");
      me.parents("form").find(":input").prop("disabled", false);
      me.parents("form").find("#submit").trigger("click");
    });
    $(document).on("click tap", ".otp-changenum", function (e) {
      e.preventDefault();
      var me = $(this);
      me.parents("form").find("#login_error").empty();
      me.parents("form").find(".pepro-login-reg-field, .switch-form-register, .switch-form-login").show();
      me.parents("form").find("#mobile, .mobile-wrap, #user_mobile, .user_mobile-wrap, #submit, .submit-wrap, #username, #email, .email-wrap, #optverify").removeClass("hide").show().prop("disabled", false).prop("readonly", false).removeClass("disabled");
      me.parents("form").find(".optverify-wrap, .verification-wrap, .otp-resend").hide();
      me.parents("form").find(".optverify-wrap input, .verification-wrap input, .otp-resend").val("").trigger("change");
      me.hide();
      setTimeout(function () { $(login_form).find("#mobile, #user_mobile, #username, #email").first().focus(); }, 100);
    });
    $(document).on("click tap", ".switch-form-lost-pass", function (e) {
      e.preventDefault();
      $(this).parents(".pepro-login-reg-container").find("form").removeClass("inline");
      $(this).parents(".pepro-login-reg-container").find("#pepro-pass-inline").addClass("inline");
    });
    $(document).on("click tap", ".pepro-login-reg-container .switcher a", function (e) {
      e.preventDefault();
      var me = $(this);
      if (me.hasClass("switch-mobile")) {
        me.parents(".pepro-login-reg-container").removeClass("via-sms-active via-email-active").addClass("via-sms-active");
        me.parents(".pepro-login-reg-container").find("form.inline").removeClass("inline");
        me.parents(".pepro-login-reg-container").find("form.form-login").addClass("inline");
      } else {
        me.parents(".pepro-login-reg-container").removeClass("via-sms-active via-email-active").addClass("via-email-active");
        me.parents(".pepro-login-reg-container").find("form.inline").removeClass("inline");
        me.parents(".pepro-login-reg-container").find("form.form-login").addClass("inline");
      }
    });

    function resend_counndown(e, login_form, _pepro_dev) {
      if (".otp-resend,.otp-changenum" == e.data.show) {
        $submitBtn = $(login_form).find(".submit-wrap #submit[type=submit]");
        $submitBtn.text($submitBtn.data("verify"));
      }
      if (".otp-resend,.otp-changenum" == e.data.show && e.data.timerdown) {
        if (0 == e.data.timerdown) {
          $(login_form).find(".otp-resend").countdown("stop");
          $(login_form).find(".otp-resend").html(_pepro_dev.resendnow);
          $(login_form).find("#mobile").prop("disabled", false).prop("readonly", false).removeClass("disabled");
          $(login_form).find("#user_mobile").prop("disabled", false).prop("readonly", false).removeClass("disabled");
        }
        else {
          /* var v1 = moment.tz(e.data.cur_time, "Asia/Tehran").toDate(),
          v2 = moment.tz(e.data.timerdown, "Asia/Tehran").toDate(),
          v3 = moment.tz(e.data.cur_time, "Asia/Istanbul").toDate(),
          v4 = moment.tz(e.data.timerdown, "Asia/Istanbul").toDate();
          console.log(`---= DEBUG =----
          Now in Tehran: ${v1}
          TimerDown in Tehran: ${v2}
          ----------------------------
          Now in Istanbul: ${v3}
          TimerDown in Istanbul: ${v4}`); */
          let timeObject = moment.tz(e.data.timerdown, _pepro_dev.timezone);
          $(login_form).find(".otp-resend")
            .prop("disabled", true)
            .addClass("disabled")
            .countdown(timeObject.toDate())
            .on('update.countdown', function (qd) {
              // console.log(qd.strftime('%H:%M:%S'));
              $(this).html(_pepro_dev.resendtime.replace('%s', qd.strftime('%M:%S')));
            })
            .on('finish.countdown', function (qd) {
              // console.log("finish.countdown");
              $(this).html(_pepro_dev.resendnow).prop("disabled", false).removeClass("disabled");
              $(login_form).find("#mobile").prop("disabled", false).prop("readonly", false).removeClass("disabled");
              $(login_form).find("#user_mobile").prop("disabled", false).prop("readonly", false).removeClass("disabled");
            })
            .on('stoped.countdown', function (qd) {
              // console.log("stoped.countdown");
              $(this).html(_pepro_dev.resendnow).prop("disabled", false).removeClass("disabled");
              $(login_form).find("#mobile").prop("disabled", false).prop("readonly", false).removeClass("disabled");
              $(login_form).find("#user_mobile").prop("disabled", false).prop("readonly", false).removeClass("disabled");
            });
        }
      }
    }
    function scroll_element() {
      // console.trace();
      if ($(".pepro-regpepro-login-popup-wrapper.popup-active").length) {
        if ($(".pepro-regpepro-login-popup-wrapper.popup-active .pepro-login-reg-container").length) {
          var top = 0;
          if ($(".pepro-regpepro-login-popup-wrapper.popup-active .pepro-login-reg-container").first().offset()) {
            top = $(".pepro-regpepro-login-popup-wrapper.popup-active .pepro-login-reg-container").first().offset().top;
          }
          $("html, body, .pepro-login-reg-container").animate({ scrollTop: top - 100 });
        }
      }
    }
    function resizeReCaptcha() {
      var width = $('.g-recaptcha').parent().width();
      if (width < 400 && width > 100) {
        var scale = width / 302;
        $('.g-recaptcha').css('transform', 'scale(' + scale + ')');
        $('.g-recaptcha').css('-webkit-transform', 'scale(' + scale + ')');
        $('.g-recaptcha').css('transform-origin', '0 0');
        $('.g-recaptcha').css('-webkit-transform-origin', '0 0');
      }
    };
    function show_toast(data = "Sample Toast!", bg = "", delay = 10000) {
      $toast = "pdtoast"; if (bg !== "") { $toast = "toastwide"; }
      if (!$($toast).length) { $(document.body).append($(`<${$toast}>`)); } else { $($toast).removeClass("active"); }
      $("toastwide").removeClass("active");
      setTimeout(function () {
        $($toast).css("--toast-bg", bg).html(data).stop().addClass("active").delay(delay).queue(function () {
          $(this).removeClass("active").dequeue().off("click tap");
        }).on("click tap", function (e) { e.preventDefault(); $(this).stop().removeClass("active"); });
      }, 200);
    }
    function show_modal_alert(title = "", content = "", icon = "fas fa-info-circle", type = "blue", boxWidth = "500px", $fn = null, theme = "modern") {
      $.confirm({
        title: title ? "<br>" + title : "",
        content: content,
        icon: icon,
        theme: theme,
        type: type,
        boxWidth: boxWidth,
        buttons: { close: { btnClass: "btn-" + type, text: _pepro_dev.closeTxt, keys: ["enter", "esc"], action: $fn } },
      });
    }
    function toEnglishDigits(str) {
      // https://stackoverflow.com/a/51113170
      // convert persian digits [۰۱۲۳۴۵۶۷۸۹]
      var e = '۰'.charCodeAt(0);
      str = str.replace(/[۰-۹]/g, function (t) {
        return t.charCodeAt(0) - e;
      });
      // convert arabic indic digits [٠١٢٣٤٥٦٧٨٩]
      e = '٠'.charCodeAt(0);
      str = str.replace(/[٠-٩]/g, function (t) {
        return t.charCodeAt(0) - e;
      });
      return str;
    }
  });
})(jQuery);
