/**
 * @Last modified by:   Amirhosseinhpv
 * @Last modified time: 2021/12/31 01:58:41
 */

(function($) {
  var __pdssrequest = null;
  $(document).ready(function() {

    $(document.body).append($("<subtoast>"));

    $(document).on("submit", `form.pepro-sms-subscription`, function(e) {
      e.preventDefault(); login_form = $(this);
      if ($.trim($(`form.pepro-sms-subscription #pssname`).val()) === "") {
        show_toast(_pdss.your_name, "rgba(139, 2, 2, 0.8)");
        return false;
      }
      if ($.trim($(`form.pepro-sms-subscription #pssmobile`).val()) === "") {
        show_toast(_pdss.your_mobile, "rgba(139, 2, 2, 0.8)");
        return false;
      }
			if (__pdssrequest != null) {
        __pdssrequest.abort();
      }
      $(login_form).addClass("loading");
      $(login_form).find(":input").prop("disabled", true)
      show_toast(_pdss.loading, "rgba(2, 133, 139, 0.8)");
      form_params = $(`form.pepro-sms-subscription`).serialize();
      __pdssrequest = $.ajax({
        type: "POST",
        dataType: "json",
        url: _pdss.ajaxurl,
        data: {
          order: "smsnewsletter",
          action: "pepro_reglogin",
          nonce: _pdss.nonce,
          pssname: $.trim($(login_form).find("#pssname").val()),
          pssmobile: $.trim($(login_form).find("#pssmobile").val()),
          pssverify: $(".pss-input-container.pssverify").is(".hide") ? false : $.trim($(login_form).find("#pssverify").val()),
        },
        success: function(e) {
					$(login_form).find(":input").prop("disabled", false)
					$(login_form).find(".otp-resend,.otp-changenum").hide();
					if (e.success === true) {
						if (e.data.is_otp){
							show_toast(e.data.msg, "rgba(2, 133, 139, 0.8)");
							$(login_form).find(".pss-input-container").addClass("hide");
							$(login_form).find(".pss-input-container.pssverify").removeClass("hide");
							if (e.data.focus){ setTimeout(function () { $(login_form).find(e.data.focus).first().focus(); }, 100); }
							if (e.data.select){ setTimeout(function () { $(login_form).find(e.data.focus).first().select(); }, 100); }
							if (e.data.show){ $(login_form).find(e.data.show).show(); }
							$(login_form).find("#pssmobile").prop("disabled", true).prop("readonly", true).addClass("disabled");
							resend_counndown(e, login_form, _pdss);
						}
						else{
							$(login_form).find(".pss-input-container").removeClass("hide");
							$(login_form).find(".pss-input-container.pssverify").addClass("hide");
							$(login_form).find(".pss-input-container.pssverify input").val("").trigger("change");
							$submitBtn = $(login_form).find(".submit-wrap #submit[type=submit]");
							show_toast(e.data.msg, "rgba(21, 139, 2, 0.8)");
						}
					}
					else {
						if (e.data.is_otp){
							if (e.data.focus){ setTimeout(function () { $(login_form).find(e.data.focus).first().focus(); }, 100); }
							if (e.data.select){ setTimeout(function () { $(login_form).find(e.data.focus).first().select(); }, 100); }
							if (e.data.show){ $(login_form).find(e.data.show).show(); }
							resend_counndown(e, login_form, _pdss);
						}
						show_toast(e.data.msg, "rgba(139, 2, 2, 0.8)");
					}
        },
        error: function(e) {
          console.error(e);
          show_toast(_pdss.error, "rgba(139, 2, 2, 0.8)");
        },
        complete: function(e) {
          $(login_form).removeClass("loading");
          $(login_form).find(":input").prop("disabled", false)
        },
      });
    });

		$(document).on("input", "form.pepro-sms-subscription input", function(e){
			$(this).val(toEnglishDigits($(this).val()));
		});

		$(document).on("click tap", ".otp-resend", function(e){
      e.preventDefault();
      var me = $(this);
			me.parents("form").find(".pss-input-container").removeClass("hide");
			me.parents("form").find(".pss-input-container.pssverify").addClass("hide");
			me.parents("form").find(".pss-input-container.pssverify input").val("").trigger("change");
      me.parents("form").find(":input").prop("disabled", false);
      me.parents("form").find("#pssverifysms").trigger("click");
    });
    $(document).on("click tap", ".otp-changenum", function(e){
      e.preventDefault();
      var me = $(this);
			me.parents("form").find(".otp-resend").countdown('stop');
			me.parents("form").find(".otp-resend").html(_pdss.resendnow);
			me.parents("form").find(".pss-input-container").removeClass("hide");
			me.parents("form").find(".pss-input-container.pssverify").addClass("hide");
			me.parents("form").find(".pss-input-container.pssverify input").val("").trigger("change");
      me.parents("form").find(":input").show().prop("disabled", false).prop("readonly", false).removeClass("disabled");
      me.parents("form").find(".otp-resend").hide();
      me.hide();
      setTimeout(function () { $(login_form).find("#pssname").first().focus(); }, 100);
    });

    function show_toast(data = "Sample Toast!", bg = "", delay = 6000) {
      if (!$("subtoast").length) {
        $(document.body).append($("<subtoast>"));
      } else {
        $("subtoast").removeClass("active");
      }
      setTimeout(function() {
        $("subtoast").css("--toast-bg", bg).html(data).stop().addClass("active").delay(delay).queue(function() {
          $(this).removeClass("active").dequeue().off("click tap");
        }).on("click tap", function(e) {
          e.preventDefault();
          $(this).stop().removeClass("active");
        });
      }, 200);
    }
		function toEnglishDigits(str){
				// https://stackoverflow.com/a/51113170
				// convert persian digits [۰۱۲۳۴۵۶۷۸۹]
				var e = '۰'.charCodeAt(0);
				str = str.replace(/[۰-۹]/g, function(t) {
						return t.charCodeAt(0) - e;
				});
				// convert arabic indic digits [٠١٢٣٤٥٦٧٨٩]
				e = '٠'.charCodeAt(0);
				str = str.replace(/[٠-٩]/g, function(t) {
						return t.charCodeAt(0) - e;
				});
				return str;
		}
		function resend_counndown(e, login_form, _pepro_dev) {
			if (".otp-resend,.otp-changenum" == e.data.show && e.data.timerdown){
				if (0 == e.data.timerdown){
					$(login_form).find(".otp-resend").html(_pepro_dev.resendnow);
					$(login_form).find("#pssmobile").prop("disabled", false).prop("readonly", false).removeClass("disabled");
				}
				else{
					$(login_form).find(".otp-resend").prop("disabled", true).addClass("disabled").countdown(e.data.timerdown).on('update.countdown', function(qd) {
						$(this).html(_pepro_dev.resendtime.replace('%s',qd.strftime('%M:%S')));
					}).on('finish.countdown', function(qd) {
						$(this).html(_pepro_dev.resendnow).prop("disabled", false).removeClass("disabled");
						$(login_form).find("#pssmobile").prop("disabled", false).prop("readonly", false).removeClass("disabled");
					}).on('stop.countdown', function(qd) {
						$(this).html(_pepro_dev.resendnow).prop("disabled", false).removeClass("disabled");
						$(login_form).find("#pssmobile").prop("disabled", false).prop("readonly", false).removeClass("disabled");
					});
				}
			}
		}

  });
})(jQuery);
