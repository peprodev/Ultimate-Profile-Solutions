/*
 * @Author: Amirhossein Hosseinpour <https://amirhp.com>
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2024/08/21 00:50:20
 */

(function($) {
  var editor;
  $(document).ready(function() {
    $("[date-picker-area], [user-select-area]").hide();
    $("select.select2").select2({
      width: "100%"
    });
    if ($("#notifaddedit-schedule-check").prop("checked")) {
      $("[date-picker-area]").show()
    }
    if ($("#notifaddedit-users-check").prop("checked")) {
      $("[user-select-area]").hide()
    }
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    var hh = String(today.getHours()).padStart(2, '0');
    var mm = String(today.getMinutes()).padStart(2, '0');
    var ss = String(today.getSeconds()).padStart(2, '0');
    var today = `${yyyy}-${mm}-${dd}`;
    var prevdata = $("#notifaddedit-schedule").val();
    var todayfull = `${yyyy}-${mm}-${dd} ${hh}:${mm}:${ss}`;
    window.formatPersian = false;
    var day = new persianDate();
    var persianDatepicker;
    $("#notifaddedit-scheduleFA").val(day.toCalendar('persian').toLocale('en').format("YYYY-MM-DD HH:mm:ss"));
    $("#notifaddedit-schedule").val(day.toCalendar('gregorian').toLocale('en').format("YYYY-MM-DD HH:mm:ss"));
    var $_railalign = $("body").is(".rtl") ? "left" : "right";
    persianDatepicker = $("#calenderContainer").persianDatepicker({
      inline: 1,
      // format: "LLLL",
      viewMode: "day",
      initialValue: true,
      initialValueType: "gregorian",
      minDate: null,
      maxDate: null,
      autoClose: false,
      position: "auto",
      format: 'YYYY-MM-DD HH:mm:ss',
      onlyTimePicker: false,
      onlySelectOnDate: true,
      calendarType: "persian",
      inputDelay: 800,
      observer: true,
      calendar: {
        persian: {
          locale: "fa",
          showHint: true,
          leapYearMode: "algorithmic"
        },
        gregorian: {
          locale: "en",
          showHint: true
        }
      },
      navigator: {
        enabled: true,
        scroll: {
          enabled: false
        },
        text: {
          btnNextText: ">",
          btnPrevText: "<"
        }
      },
      toolbox: {
        enabled: true,
        calendarSwitch: {
          enabled: true,
          format: "MMMM"
        },
        todayButton: {
          enabled: true,
          text: {
            fa: "امروز",
            en: "Today"
          }
        },
        submitButton: {
          enabled: true,
          text: {
            fa: "تایید",
            en: "Submit"
          }
        },
        text: {
          btnToday: "امروز"
        }
      },
      timePicker: {
        enabled: true,
        meridian: {
          enabled: false
        }
      },
      dayPicker: {
        enabled: true,
        titleFormat: "YYYY MMMM"
      },
      monthPicker: {
        enabled: true,
        titleFormat: "YYYY"
      },
      yearPicker: {
        enabled: true,
        titleFormat: "YYYY"
      },
      persianDigit: false,
      responsive: true,
      onSelect: function(unix) {
        change__calendar_date(unix);
      }
    });

    $(".icon-picker").qlIconPicker({ 'save': 'class' });
    $(document).on('iconselected.queryloop', function(e, icon) { /*console.log(icon);*/ });
    $(document).on("keyup", "#search-here", function(q) {
      q.preventDefault();
      if (q.keyCode === 13) {
        if ($.trim($(this).val()) !== "") {
          $(this).trigger("search");
        }
      }

    });
    $(document).on("search", "#search-here", function(q) {
      q.preventDefault();
      var me = $(this);
      let nonce = me.attr("integrity"),
        wparam = me.attr("wparam"),
        lparam = me.attr("lparam"),
        dparam = me.val();
      $(".is-focused").removeClass("is-focused");
      $(".card-header.card-header-primary .lds-ring2").show();
      $("#notifications_list_table tr td.td-actions *, input#search-here").prop("disabled", true);
      window.history.pushState("", document.title, replaceUrlParam(location.href, "s", dparam));
      $.ajax({
        url: pepc.ajax,
        type: 'POST',
        data: {
          action: 'peprodev-ups',
          integrity: nonce,
          wparam: wparam,
          lparam: lparam,
          dparam: dparam,
          urlz: location.href,
        },
        success: function(e) {
          if (e.success === true) {
            $("#notifications_list_table tbody").html(e.data.html);
          }
        },
        error: function(e) {
          $.notify({ icon: "error", message: peprofile.error_unknown_error }, { type: 'danger', timer: 100, delay: 4000, placement: { from: "top", align: "right" } });
        },
        complete: function(e) {
          $(".lds-ring2").hide();
          $("#notifications_list_table tr td.td-actions *, input#search-here").prop("disabled", false);
        },
      });
    });
    $(document).on("hidden.bs.modal", "#del_notifications", function() {
      let dparam = $(this).data('id');
      $(`tr[data-notif-tr=${dparam}]`).removeClass("removing");
      $(`tr[data-notif-tr=${dparam}] td.td-actions *`).prop("disabled", false);
    });
    $(document).on("change click", "#notifaddedit-schedule-check", function() {
      var state = $(this).prop("checked");
      if (state) {
        $("[date-picker-area]").show()
      } else {
        $("[date-picker-area]").hide()
      }
      update_scrolbar();
    });
    $(document).on("change click", "#notifaddedit-users-check", function() {
      var state = $(this).prop("checked");
      if (state) {
        $("[user-select-area]").hide()
      } else {
        $("[user-select-area]").show()
      }
      update_scrolbar();
    });
    $(document).on("click keyup change paste cut","#searchfontawesome",function(e){
      e.preventDefault();
      var searchString = $(this).val();
      $(".fontawesome-icon-picker .tab-content .tab-pane.active li").each(function(index, value) {
          currentName = $(value).text()
          currentName += "" + $(value).attr("class")
          if( currentName.toUpperCase().indexOf(searchString.toUpperCase()) > -1) {
            $(value).show();
          } else {
            $(value).hide();
          }
      });
    });
    $(document).on("click tap", ".clear_search", function(e) {
      e.preventDefault();
      $("input#search-here").val("").trigger("search");
    });
    $(document).on("click tap", ".remove_notif_modal", function(e) {
      e.preventDefault();
      var me = $(this);
      let nonce = me.attr('integrity'),
        wparam = me.attr('wparam'),
        lparam = me.attr('lparam'),
        dparam = me.data('id');
      $(`tr[data-notif-tr=${dparam}]`).addClass("removing");
      $('#del_notifications').data("integrity", nonce);
      $('#del_notifications').data("wparam", wparam);
      $('#del_notifications').data("lparam", lparam);
      $('#del_notifications').data("dparam", dparam);
      $('#del_notifications').data("id", dparam);
      $('#del_notifications').modal();
    });
    $(document).on("click tap", ".add_edit_save_notification", function(e) {
      e.preventDefault();
      var breakme = false; var contentHTML = "";
      if (tinymce.activeEditor){ tinymce.activeEditor.save(); }
      if ($("#notifaddedit-content").hasClass("tmce-active")){
        contentHTML = tinymce.activeEditor.getContent();
      }
      else{
        contentHTML = $("#notifaddedit-content").val();
      }

      user_roles = $("select[name='user_roles[]']").val().join(",");
      groups     = $("select[name='access_groups[]']").val().join(",");
      learn_dash = $("select[name='learn_dash[]']").val().join(",");
      
      datatosave = {
        "id"            : parseInt($("#current_edit_notif_id").val()),
        "title"         : $("#notifaddedit-title").val(),
        "content"       : contentHTML,
        "icon"          : $("#notifaddedit-icon").val(),
        "color"         : $("input[name=notifaddedit-color]:checked").val(),
        "priority"      : $("input[name=notifaddedit-priority]:checked").val(),
        "act1"          : $("#notifaddedit-act1").val(),
        "act1url"       : $("#notifaddedit-act1url").val(),
        "act2"          : $("#notifaddedit-act2").val(),
        "act2url"       : $("#notifaddedit-act2url").val(),
        "usersList"     : $("#notifaddedit-usersList").val(),
        "schedule"      : $("#notifaddedit-schedule").val(),
        "scheduleFA"    : $("#notifaddedit-scheduleFA").val(),
        "users-check"   : $("#notifaddedit-users-check").prop("checked") ? "1"   : "0",
        "schedule-check": $("#notifaddedit-schedule-check").prop("checked") ? "1": "0",
        "user-roles"    : user_roles,
        "access_groups" : groups,
        "learn_dash"    : learn_dash,
        "url"           : location.href,
      };
      $.each(datatosave, function(i, x) {
        switch (i) {
          case "usersList":
          case "users-check":
            if ($("#notifaddedit-users-check").prop("checked") == false) {
              if ($.trim($("#notifaddedit-usersList").val()) === "") {
                $(`#notifaddedit-${i}`).parents("tr").first().find("td:first-child>label").first().removeAttr("class").addClass("text-danger");
                breakme = true;
              } else {
                $(`#notifaddedit-${i}`).parents("tr").first().find("td:first-child>label").first().removeAttr("class").addClass("text-primary");
              }
            } else {
              $(`#notifaddedit-${i}`).parents("tr").first().find("td:first-child>label").first().removeAttr("class").addClass("text-primary");
            }
            break;

          case "schedule-check":
          case "schedule":
          case "scheduleFA":
            if ($("#notifaddedit-schedule-check").prop("checked")) {
              if ($.trim($("#notifaddedit-schedule").val()) === "" || $.trim($("#notifaddedit-scheduleFA").val()) === "") {
                $(`#notifaddedit-${i}`).parents("tr").first().find("td:first-child>label").first().removeAttr("class").addClass("text-danger");
                breakme = true;
              } else {
                $(`#notifaddedit-${i}`).parents("tr").first().find("td:first-child>label").first().removeAttr("class").addClass("text-primary");
              }
            } else {
              $(`#notifaddedit-${i}`).parents("tr").first().find("td:first-child>label").first().removeAttr("class").addClass("text-primary");
            }
            break;

          case "id":
          case "act1":
          case "act1url":
          case "act2":
          case "act2url":
          case "user-roles":
          case "access_groups":    
          case "learn_dash":    
          case "icon":
          case "url":
            break;

          default:
            if ($.trim(x) === "") {
              $(`#notifaddedit-${i}`).parents("tr").first().find("td:first-child>label").first().removeAttr("class").addClass("text-danger");
              breakme = true;
            } else {
              $(`#notifaddedit-${i}`).parents("tr").first().find("td:first-child>label").first().removeAttr("class").addClass("text-primary");
            }
        }
      });
      if (breakme) {
        $.notify({ icon: "error", message: peprofile.error_validate_form }, { type: 'danger', timer: 100, delay: 4000, placement: { from: "top", align: "right" } });
        $("table#add_new label.text-danger").first().parents("tr").find("td:nth-child(2)>*:first-child>*:first-child").focus();
        return false;
      }
      $(".lds-ring").css("display", "flex");
      $(".select2-container").css("z-index", "0");
      $(".lds-ring2").show();
      $("#modal_add_notif *").prop("disabled", true);
      var me = $(this);
      let nonce = me.attr('integrity'), wparam = me.attr('wparam'), lparam = me.attr('lparam');
      $.ajax({
        url: pepc.ajax,
        type: 'POST',
        data: {
          action: 'peprodev-ups',
          integrity: nonce,
          wparam: wparam,
          lparam: lparam,
          dparam: datatosave,
        },
        success: function(e) {
          if (e.success === true) {
            switch (e.data.type) {
              case "update":
                $("#notifications_list_table tbody").html(e.data.htmlupdate);
                // $(`#notifications_list_table tr[data-notif-tr=${e.data.id}]`);
                break;
              case "add":
                $("#notifications_list_table tbody").html(e.data.htmlupdate);
                // $(`#notifications_list_table tbody`).prepend(e.data.htmlupdate);
                break;
              default:
                // nothing !
            }
            $.notify({ icon: "thumb_up", message: e.data.msg }, { type: 'success', timer: 100, delay: 5000, placement: { from: "top", align: "right" } });
          } else {
            $.notify({ icon: "error", message: e.data.msg }, { type: 'danger', timer: 100, delay: 5000, placement: { from: "top", align: "right" } });
          }
          setTimeout(function () { $("button.btn[data-dismiss='modal']").click(); }, 100);
        },
        error: function(e) {
          $.notify({ icon: "error", message: peprofile.error_unknown_error }, { type: 'danger', timer: 100, delay: 5000, placement: { from: "top", align: "right" } });
        },
        complete: function(e) {
          $("button.btn[data-dismiss='modal']").click();
          $("tr[data-notif-tr='empty']").hide();
          $("#modal_add_notif *").prop("disabled", false);
          $(".lds-ring,.lds-ring2").hide();
          $(".select2-container").css("z-index", "");
        },
      });
    });
    $(document).on("click tap", ".edit_notif_modal", function(e) {
      e.preventDefault();
      var me = $(this);
      let nonce = me.attr('integrity'), wparam = me.attr('wparam'), lparam = me.attr('lparam'), dparam = me.data('id');
      try {
        $data = $.parseJSON($(`tr[data-notif-tr="${me.data("id")}"]`).first().attr("data-json"));
      } catch (e) {
        $data = null;
      } finally {
        if (null === $data){
          $.notify({ icon: "error", message: peprofile.error_parsing_data }, { type: 'danger', timer: 100, delay: 4000, placement: { from: "top", align: "right" } });
        }else{
          CLEAR_NOTIF_FORM();
          $("h5.modal-title.editmode span").text($data.title);
          $("#current_edit_notif_id").val(me.data("id"));
          $("#add_new_notifications").addClass("editmode").slideDown("fast", function() {
            $(".toggle_view_form").toggle("fast");


            $("#notifaddedit-content-html").trigger("click");
            $("#add_notifpopup").prop("disabled",true).addClass("disabled btn-gray").removeClass("btn-primary");
            if ($("#notifaddedit-content").hasClass("tmce-active")){
              contentHTML = tinyMCE.activeEditor.setContent($data.content, {format: 'html'});
            }else{
              contentHTML = $("#notifaddedit-content").val($data.content).trigger("change");
            }

            $("#notifaddedit-icon").val($data.icon).trigger("change");
            if ("" !== $data.icon){
              $(".icon-preview.icon-preview-fa").removeClass("icon-preview-on").addClass("icon-preview-on");

              $(".icon-preview.icon-preview-fa.icon-preview-on > button > i").removeAttr("class").addClass("material-icons");
              $(".icon-preview.icon-preview-fa.icon-preview-on > i").removeAttr("class").addClass($data.icon);
            }
            $("#notifaddedit-title").val($data.title).trigger("change");
            $(`input[name="notifaddedit-color"][value="${$data.color}"]`).prop("checked",true).trigger("click change");
            $(`input[name="notifaddedit-priority"][value="${$data.priority}"]`).prop("checked",true).trigger("click change");
            $("#notifaddedit-act1").val($data.action_title_1).trigger("change");
            $("#notifaddedit-act2").val($data.action_title_2).trigger("change");
            $("#notifaddedit-act1url").val($data.action_url_1).trigger("change");
            $("#notifaddedit-act2url").val($data.action_url_2).trigger("change");

            if ("all" == $data.users_list){
              $("#notifaddedit-users-check").prop("checked",true).trigger("change");
            }
            else{
              $("#notifaddedit-users-check").prop("checked",false).trigger("change");
              $("#notifaddedit-usersList").val($data.userz).trigger("change");
            }

            if ("" == $data.date_scheduledFA){ //Published at
              $("#notifaddedit-schedule-check").prop("checked",false).trigger("change");
              unix = parseInt((new Date().getTime()).toFixed(0));
              persianDatepicker.setDate(unix);
              change__calendar_date(unix);
            }
            else{ // Scheduled
              $("#notifaddedit-schedule-check").prop("checked",true).trigger("change");
              unix = parseInt((new Date($data.date_scheduled).getTime()).toFixed(0));
              persianDatepicker.setDate(unix);
              change__calendar_date(unix);
            }

            $("#notifaddedit-act2url").val($data.action_url_2).trigger("change");

            if ($data.user_roles && "" !== $.trim($data.user_roles)) {
              $.each($data.user_roles.toString().split(","), function(index, val) {
              $(`select[name='user_roles[]']`).val($data.user_roles.toString().split(",")).trigger("change");
              });
            }

            if ($data.access_groups && "" !== $.trim($data.access_groups)) {
              $(`select[name='access_groups[]']`).val($data.access_groups.toString().split(",")).trigger("change");
            }
            if ($data.learn_dash && "" !== $.trim($data.learn_dash)) {
              $(`select[name='learn_dash[]']`).val($data.learn_dash.toString().split(",")).trigger("change");
            }


            update_scrolbar();

          });
        }
      }
    });
    $(document).on("click tap", ".view_notif_modal", function(e) {
      e.preventDefault();
      var me = $(this);
      let nonce = me.attr('integrity'), wparam = me.attr('wparam'), lparam = me.attr('lparam'), dparam = me.data('id');

      try {
        $data = $.parseJSON($(`tr[data-notif-tr="${me.data("id")}"]`).first().attr("data-json"));
      } catch (e) {
        $data = null;
      } finally {
        console.log($data);
      }
    });
    $(document).on("click tap", ".fontawesome-icon-picker .nav-tabs a.btn",function(e){
      var me = $(this);
      $("#searchfontawesome").trigger("change").focus();
    });
    $(document).on("click tap", "#remove_notif", function(e) {
      e.preventDefault();
      var me = $(this);
      let nonce = $('#del_notifications').data("integrity"),
        wparam = $('#del_notifications').data("wparam"),
        lparam = $('#del_notifications').data("lparam"),
        dparam = $('#del_notifications').data("id");

      // $(".lds-ring").css("display", "flex");
      $(".lds-ring2").show();
      $(`tr[data-notif-tr=${dparam}] td.td-actions *`).prop("disabled", true);
      $("div#del_notifications *").prop("disabled", true);
      $.ajax({
        url: pepc.ajax,
        type: 'POST',
        data: {
          action: 'peprodev-ups',
          integrity: nonce,
          wparam: wparam,
          lparam: lparam,
          dparam: dparam,
        },
        success: function(e) {
          if (e.success === true) {
            $(`tr[data-notif-tr=${dparam}]`).remove();
            $.notify({
              icon: "thumb_up",
              message: e.data.msg
            }, {
              type: 'success',
              timer: 100,
              delay: 2000,
              placement: {
                from: "top",
                align: "right"
              }
            });
            if ($(`tr[data-notif-tr]`).length < 2) {
              $("tr[data-notif-tr='empty']").show();
            }
          } else {
            $.notify({
              icon: "error",
              message: e.data.msg
            }, {
              type: 'danger',
              timer: 100,
              delay: 2000,
              placement: {
                from: "top",
                align: "right"
              }
            });
          }
        },
        error: function(e) {
          $.notify({ icon: "error", message: peprofile.error_unknown_error }, { type: 'danger', timer: 100, delay: 4000, placement: { from: "top", align: "right" } });
        },
        complete: function(e) {
          $(".lds-ring,.lds-ring2").hide();
          $('#del_notifications').modal("hide");
          $("div#del_notifications *").prop("disabled", false);
        },
      });
    });
    $(document).on("click tap", "#add_notifpopup", function(e) {
      CLEAR_NOTIF_FORM();
      $("#add_new_notifications").removeClass("editmode").slideToggle("fast", function() {
        $(".toggle_view_form").toggle("fast");
      });
      setTimeout(function () { update_scrolbar(); $(window).trigger("resize") }, 500);
    });
    $(document).on("click tap", "#toggle_search", function(e) {
      $("#toggle_search_container").slideToggle("fast", function() {
        update_scrolbar();
      })
    });
    $(document).on("click tap", "#close_add_new_notifications", function(e) {
      CLEAR_NOTIF_FORM();
      $("#add_new_notifications").removeClass("editmode").slideUp("fast", function() {
        $(".toggle_view_form").show();
        $("#add_notifpopup").prop("disabled",false).removeClass("disabled btn-gray").addClass("btn-primary");
        update_scrolbar();
      });
    });
    $(document).on("click tap", "#clear_notif_form", function(e) {
      e.preventDefault();
      CLEAR_NOTIF_FORM();
    });
    function replaceUrlParam(url, paramName, paramValue) {
      if (paramValue == null) {
        paramValue = '';
      }
      var pattern = new RegExp('\\b(' + paramName + '=).*?(&|#|$)');
      if (url.search(pattern) >= 0) {
        return url.replace(pattern, '$1' + paramValue + '$2');
      }
      url = url.replace(/[?#]$/, '');
      return url + (url.indexOf('?') > 0 ? '&' : '?') + paramName + '=' + paramValue;
    }
    function fixNumbers(str) {
      var persianNumbers = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g],
        arabicNumbers = [/٠/g, /١/g, /٢/g, /٣/g, /٤/g, /٥/g, /٦/g, /٧/g, /٨/g, /٩/g];

      if (typeof str === 'string') {
        for (var i = 0; i < 10; i++) {
          str = str.replace(persianNumbers[i], i).replace(arabicNumbers[i], i);
        }
      }
      return str;
    };
    function update_scrolbar() {

    }
    function CLEAR_NOTIF_FORM() {
      if (tinyMCE.activeEditor){ tinyMCE.activeEditor.setContent(""); }
      $("#notifaddedit-title, #notifaddedit-content, #notifaddedit-icon, #current_edit_notif_id, #notifaddedit-act1, #notifaddedit-act1url, #notifaddedit-act2, #notifaddedit-act2url").val("");
      $("#notifaddedit-color-bg-c4").click().trigger("change");
      $("#notifaddedit-priority-5").click().trigger("change");
      $(".remove-icon.btn-sm-sqc").click().trigger("change");
      $("#notifaddedit-usersList").val("").trigger("change");
      $("input[name='user_roles[]']").prop("checked", false).trigger("change");
      $("select[name='access_groups[]']").val("").trigger("change");
      $("select[name='learn_dash[]']").val("").trigger("change");
      $("#notifaddedit-users-check").prop("checked", true).trigger("change");
      $("#notifaddedit-schedule-check").prop("checked", false).trigger("change");
      $("table#add_new tr td:first-child>label:first-of-type").removeAttr("class").addClass("text-primary");
      $("h5.modal-title.editmode span").text("");
      $(".lds-ring").hide();
      unix = parseInt((new Date().getTime()).toFixed(0));
      persianDatepicker.setDate(unix);
      change__calendar_date(unix);
    }
    function change__calendar_date(unix) {
      var day = new persianDate(unix);
      var hour = fixNumbers(String($("#calenderContainer input.hour-input").val()));
      var minute = fixNumbers(String($("#calenderContainer input.minute-input").val()));
      var second = fixNumbers(String($("#calenderContainer input.second-input").val()));
      var date = day.toCalendar('persian').toLocale('en').format("YYYY-MM-DD") + ` ${hour}:${minute}:${second}`;
      $("#notifaddedit-scheduleFA").val(date);
      var date = day.toCalendar('gregorian').toLocale('en').format("YYYY-MM-DD") + ` ${hour}:${minute}:${second}`;
      $("#notifaddedit-schedule").val(date);
    }
  });
})(jQuery);
