/**
 * @Author: Amirhosseinhpv
 * @Date:   2020/09/28 17:58:05
 * @Email:  its@hpv.im
 * @Last modified by:   amirhp-com <its@amirhp.com>
 * @Last modified time: 2022/08/31 16:34:37
 * @License: GPLv2
 * @Copyright: Copyright © 2020 Amirhosseinhpv, All rights reserved.
 */

(function($) {
  $.extend({
    replaceTag: function(currentElem, newTagObj, keepProps) {
      var $currentElem = $(currentElem);
      var i,
        $newTag = $(newTagObj).clone();
      if (keepProps) {
        newTag = $newTag[0];
        newTag.className = currentElem.className;
        $.extend(newTag.classList, currentElem.classList);
        $.extend(newTag.attributes, currentElem.attributes);
      }
      $currentElem.wrapAll($newTag);
      $currentElem.contents().unwrap();
      return this;
    },
  });
  $.fn.extend({
    replaceTag: function(newTagObj, keepProps) {
      return this.each(function() {
        jQuery.replaceTag(this, newTagObj, keepProps);
      });
    },
  });
  var $success_color = "rgba(21, 139, 2, 0.8)";
  var $error_color = "rgba(139, 2, 2, 0.8)";
  var $info_color = "rgba(2, 133, 139, 0.8)";
  var _pepro_ajax_request = null;
  $(document).ready(function() {
    $("#us-font-awesome-duotone-css").remove();
    $("#font-awesome-css").remove();
    $("#tp-material-icons-css").remove();
    $("#rs-roboto-css").remove();
    $("#revslider-material-icons-css").remove();
    if ($(".col-lg-12.view-wc-orders").length) {
      $("table").addClass("table-earning table table-borderless table-striped");
      $(".button").addClass("woocommerce-button button btn btn-outline-primary");
      $(".puiw_orders_invoice_btn_container .button[href*='invoice']").addClass("getinvoice");
      $(".puiw_orders_invoice_btn_container .button[href*='invoice-pdf']").addClass("getinvoice-pdf");
      $(".puiw_orders_invoice_btn_container .button.getinvoice").prepend(`<i class="fa fa-file-alt"></i> `);
      $(".puiw_orders_invoice_btn_container .button.getinvoice-pdf").find("i").remove();
      $(".puiw_orders_invoice_btn_container .button.getinvoice-pdf").prepend(`<i class="fa fa-file-pdf"></i> `);
    }
    if ($("input#birthday").length) {
      $("#birthday").parent().find("label").append("<span id='birthday_warp' style='font-size: smaller;'></span>");
      $("#birthday").persianDatepicker({
        onSelect: function(unix) {
          var day = new persianDate(unix);
          var date = day
            .toCalendar("persian")
            .toLocale("en")
            .format("YYYY-MM-DD");
          $("#birthday_warp").text(`( ${date} )`);
          var date = day
            .toCalendar("gregorian")
            .toLocale("en")
            .format("YYYY-MM-DD");
          $("input#birthday").val(date).trigger("change");
        },
        inline: 0,
        viewMode: "day",
        initialValue: 1,
        initialValueType: "gregorian",
        minDate: null,
        maxDate: null,
        autoClose: true,
        format: "YYYY-MM-DD",
        onlySelectOnDate: true,
        calendarType: "gregorian",
        inputDelay: 800,
        observer: true,
        calendar: {
          persian: {
            locale: "fa",
            showHint: 0,
            leapYearMode: "algorithmic",
          },
          gregorian: {
            locale: "en",
            showHint: 0,
          },
        },
        navigator: {
          enabled: true,
          scroll: {
            enabled: false,
          },
          text: {
            btnNextText: ">",
            btnPrevText: "<",
          },
        },
        toolbox: {
          enabled: true,
          calendarSwitch: {
            enabled: true,
            format: "MMMM",
          },
          todayButton: {
            enabled: true,
            text: {
              fa: "امروز",
              en: "Today",
            },
          },
          submitButton: {
            enabled: true,
            text: {
              fa: "تایید",
              en: "Submit",
            },
          },
          text: {
            btnToday: "امروز",
          },
        },
        timePicker: {
          enabled: false,
        },
        dayPicker: {
          enabled: true,
          titleFormat: "YYYY MMMM",
        },
        monthPicker: {
          enabled: true,
          titleFormat: "YYYY",
        },
        yearPicker: {
          enabled: true,
          titleFormat: "YYYY",
        },
        persianDigit: false,
        responsive: true,
      });
    }
    if ($("table.wishlist_table").length) {
      $(".yith-wcwl-share .share-button .fa").removeClass("fa").addClass("fab");
      $(".yith-wcwl-share .fa-envelope-o").removeClass("fab fa-envelope-o").addClass("fa-envelope fas");
      $("form").addClass("view-wc-orders");
      $("table").addClass("table table-borderless table-striped table-earning");
      $(".yith-wcwl-share ul li a").addClass("btn btn-primary");
      $("th.product-name span").text(_i18n.prductnames);
      if ($(".wishlist-empty").length) {
        $("th.product-name span").text("");
        $(".wishlist-empty").text(_i18n.wishlistempty);
      }
    }
    setTimeout(function() {
      $(`pdmain form :input`).trigger("refresh");
    }, 200);

    $(document).on("change keyup focus refresh", `pdmain form :input`, function(e) {
      val = $(this).val();
      if ($.trim(val) !== "") {
        $(this).addClass("filled");
        $(this).parent().addClass("filled");
      } else {
        $(this).removeClass("filled");
        $(this).parent().removeClass("filled");
      }
    });
    $(document).on("change",    "input[type='file']#avatar.form-control.primary.bg-light", function(e) {
      e.preventDefault();
      var file_data = $(this).prop("files")[0];
      if (!file_data) {
        return;
      }
      const size = (this.files[0].size / 1024 / 1024).toFixed(0);
      $(".alert-box").html("").removeClass("error success info");
      if (size > 2) {
        $(".alert-box")
          .html(_i18n.max_size_err.replace(/#fs#/gi, `${size} MB`))
          .addClass("error");
        $("#attachment").val("");
        return false;
      }
    });
    $(document).on("focusout",  "pdmain form :input", function(e) {
      $(this).removeClass("focused");
      $(this).parent().removeClass("focused");
    });
    $(document).on("focusin",   "pdmain form :input", function(e) {
      $(this).addClass("focused");
      $(this).parent().addClass("focused");
    });
    $(document).on("click tap", ".wc-btn-download.multipledl", function(e) {
      e.preventDefault();
      scroll_element($(".woocommerce-order-downloads"));
    });
    $(document).on("click tap", ".overly-clickable, .overly-close", function(e) {
      e.preventDefault();
      $(".overly-clickable, .order-details-overly").removeClass("active")
    });
    $(document).on("click tap", ".show-extra-info", function(e) {
      e.preventDefault();
      $(this).siblings(".overly-clickable, .order-details-overly").addClass("active")
    });
    $(document).on("click tap", ".au-message__item.unread", function(e) {
      e.preventDefault();
      var me = $(this);
      var nID = me.data("ref");
      _pepro_ajax_request = $.ajax({
        type: "POST",
        dataType: "json",
        url: _i18n.ajax,
        data: {
          action: _i18n.td,
          integrity: _i18n.nonce,
          wparam: "profile",
          lparam: "read-notif",
          dparam: nID,
        },
        success: function(e) {
          if (e.success === true) {
            $(`.au-message__item.unread[data-ref="${nID}"]`).removeClass("unread");
            if (me.is(".notifications")) {
              if (e.data.number > 0) {
                $(`.header-wrap .noti__item.notifications .quantity`).text(e.data.number);
              } else {
                $(`.header-wrap .noti__item.notifications .quantity`).hide();
              }
              $(`.au-message__noti.notifications p, .header-wrap .noti__item.notifications .mess__title p`).html(e.data.count);
              $(".au-message-list.notifications").html(e.data.html.titles);
              $(".noti__item.notifications .mess-dropdown .notifi__item").remove();
              $(".noti__item.notifications .mess-dropdown .mess__title").after(e.data.tiny);
            }
            if (me.is(".announcements")) {
              if (e.data.Anumber > 0) {
                $(`.header-wrap .noti__item.announcements .quantity`).text(
                  e.data.Anumber
                );
              } else {
                $(`.header-wrap .noti__item.announcements .quantity`).hide();
              }
              $(`.au-message__noti.announcements p,
                .header-wrap .noti__item.announcements .mess__title p`).html(
                e.data.Acount
              );
              $(".au-message-list.announcements").html(e.data.Ahtml.titles);
              $(
                ".noti__item.announcements .mess-dropdown .notifi__item"
              ).remove();
              $(".noti__item.announcements .mess-dropdown .mess__title").after(
                e.data.Atiny
              );
            }
          }
        },
        error: function(e) {
          console.error(e.data.msg);
        },
        complete: function(e) {},
      });
    });
    $(document).on("click tap", "#submit-profile-changes", function(e) {
      if (e.isDefaultPrevented()) {
        e.preventDefault();
        return;
      }
      e.preventDefault();
      var stop = false;
      $.each($(".edit-profile-form :required"), function(index, val) {
        if ($.trim($(val).val()) === "") {
          stop = true;
        }
      });
      if (stop) {
        $(".edit-profile-form :required:invalid").first().focus();
        $(".save-user-details")
          .slideUp()
          .html(_i18n.fillreq)
          .removeClass("error info success")
          .addClass("error")
          .slideDown();
        return false;
      }
      if (_pepro_ajax_request != null) {
        _pepro_ajax_request.abort();
      }
      var me = $(this);
      if (typeof tinyMCE != "undefined") {
        tinyMCE.editors.forEach(function(i, j) {
          i.targetElm.value = i.getContent();
        });
      }
      var nID = $(".edit-profile-form").serializeArray();
      var $ld = $("body").data("loading-class");
      $("body").addClass($ld);
      $(".save-user-details").slideUp("fast", function() {
        $(".save-user-details").html("");
      });
      $(".edit-profile-form *").prop("disabled", true);
      var file_data = $("#avatar").length ? $("#avatar").prop("files")[0] : null;
      if (!file_data) {
        file_data = "";
      }
      var form_data = new FormData();
      form_data.append("file", file_data);
      form_data.append("action", _i18n.td);
      form_data.append("integrity", _i18n.nonce);
      form_data.append("wparam", "profile");
      form_data.append("lparam", "edit-profile");
      form_data.append("dparam", JSON.stringify(nID));
      _pepro_ajax_request = $.ajax({
        type: "POST",
        dataType: "json",
        url: _i18n.ajax,
        data: form_data,
        processData: false,
        contentType: false,
        success: function(e) {
          if (e.success === true) {
            show_toast(e.data.msg, $success_color);
            if (e.data.e) {
              if (e.data.e.display_name) {
                $(".account-item .js-acc-btn, .account-dropdown .content h5.name").text(e.data.e.display_name);
                $(".account-item .image img, .account-dropdown .image img").attr("alt", e.data.e.display_name);
              }
              if (e.data.e.user_email) {
                $(".account-dropdown .content .email").text(e.data.e.user_email);
              }
              if (e.data.e.avatar) {
                $("#avatar").val("").trigger("change");
                $(".account-item .image img, .account-dropdown .image img, #avatar_b").attr("src", e.data.e.avatar).trigger("load");
              }
              if (e.data.refresh == true) {
                setTimeout(function() {
                  window.location.href = window.location.href;
                }, 500);
              }
            }
          } else {
            show_toast(e.data.msg, $error_color);
          }
        },
        error: function(e) {
          console.error(e);
        },
        complete: function(e) {
          $(".edit-profile-form *").prop("disabled", false).trigger("click change load init");
          $("body").removeClass($ld);
        },
      });
    });
    $(document).on("click tap", ".noti__item.show-dropdown .mess-dropdown a.notifi__item", function(e) {
      e.preventDefault();
      var me = $(this);
      $(`.au-message-list .au-message__item[data-ref="${me.attr("data-id")}"]`).click();
    });
    $(document).on("click tap", ".copy_input_btn", function(e) {
      e.preventDefault();
      var me = $(this);
      copy_clipboard($(".promo-code-wrapper.copy_input").text());
      show_toast(_i18n.copied, $success_color);
    });
    $(document).on("click tap", ".toggleshowpswd", function(e) {
      e.preventDefault();
      var me = $(this);
      pswd = me.parent(".password").find("input");
      type = pswd.attr("type");
      pswd.attr("type", type == "password" ? "text" : "password");
    });
    $(document).on("click tap", "pdwrapper .nav-item.has-subitem>a, topdrp .nav-item.has-subitem>a", function(e) {
      e.preventDefault();
      $(this).parents(".has-subitem").toggleClass("active");
    });

    $(document).on("click tap", ".topmenu-dropdown-toggle, topdrp .topmenu-bg-overly", function(e) {
      e.preventDefault();
      $("topdrp .heading-li-item").toggleClass("active")
    });

    function copy_clipboard(data) {
      var $temp = $("<textarea>");
      $("body").append($temp);
      $temp.val(data).select();
      document.execCommand("copy");
      $temp.remove();
    }

    function show_toast(data = "Sample Toast!", bg = "", delay = 4500) {
      if (!$("pdtoast").length) {
        $(document.body).append($("<pdtoast>"));
      } else {
        $("pdtoast").removeClass("active");
      }
      setTimeout(function() {
        $("pdtoast").css("--toast-bg", bg).html(data).stop().addClass("active").delay(delay).queue(function() {
          $(this).removeClass("active").dequeue().off("click tap");
        }).on("click tap", function(e) {
          e.preventDefault();
          $(this).stop().removeClass("active");
        });
      }, 200);
    }

    function scroll_element(element, offset = 0) {
      $("html, body").animate({
        scrollTop: element.offset().top - offset
      }, 500);
    }
  });
})(jQuery);
