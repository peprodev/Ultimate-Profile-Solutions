/**
 * @Author: Amirhosseinhpv
 * @Date:   2020/05/03 13:33:07
 * @Email:  its@hpv.im
 * @Last modified by:   Amirhosseinhpv
 * @Last modified time: 2021/09/11 11:33:12
 * @License: GPLv2
 * @Copyright: Copyright © 2020 Amirhosseinhpv, All rights reserved.
 */


(function($) {
  $(document).ready(function() {
    var $_railalign = $("body").is(".rtl") ? "left" : "right";
    $("#notifaddedit-access, #notifaddedit-ld_lms").select2({"width":"100%"});
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
    $(document).on("click tap", ".clear_search", function(e) {
      e.preventDefault();
      $("input#search-here").val("").trigger("search");
    });
    $(document).on("click tap", ".add_edit_save_notification", function(e) {
      e.preventDefault();
      var me = $(this);
      var break_me = false; var contentHTML = "";
      if (tinymce.activeEditor){ tinymce.activeEditor.save(); }
      if ($("#notifaddedit-content").hasClass("tmce-active")){
        contentHTML = tinymce.activeEditor.getContent();
      }
      else{
        contentHTML = $("#notifaddedit-content").val();
      }

      $("#notifaddedit-access, #notifaddedit-ld_lms").trigger("change");

      var data_to_save = {
        "id"      : parseInt($("#current_edit_notif_id").val()),
        "title"   : $("#notifaddedit-title").val(),
        "subject" : $("#notifaddedit-subject").val(),
        "slug"    : $("#notifaddedit-slug").val(),
        "css"     : $("#css").val(),
        "js"      : $("#js").val(),
        "icon"    : $("#notifaddedit-icon").val(),
        "img"     : $("#notifaddedit-img").val(),
        "access"  : (("" != $("#notifaddedit-access").val() && null !== $("#notifaddedit-access").val()) ? $("#notifaddedit-access").val().join(",") : ""),
        "ld_lms"  : (("" != $("#notifaddedit-ld_lms").val() && null !== $("#notifaddedit-ld_lms").val()) ? $("#notifaddedit-ld_lms").val() : ""),
        "priority": parseInt($("#notifaddedit-priority").val()),
        "active"  : $("#notifaddedit-active-check").prop("checked") ? "yes" : "no",
        "content" : contentHTML,
        "url"     : location.href,
      };
      $.each(data_to_save, function(i, x) {
        switch (i) {
          case "priority":
            if (!isNumber( $.trim(x) ) ){
              $(`#notifaddedit-${i}`).parents("tr").first().find("td:first-child>label").first().removeAttr("class").addClass("text-danger");
              break_me = true;
            }
            break;
          case "id":
          case "css":
          case "img":
          case "subject":
          case "js":
          case "active":
          case "ld_lms":
          case "content":
          case "access":
            break;
          default:
            if ($.trim(x) === "") {
              $(`#notifaddedit-${i}`).parents("tr").first().find("td:first-child>label").first().removeAttr("class").addClass("text-danger");
              break_me = true;
            } else {
              $(`#notifaddedit-${i}`).parents("tr").first().find("td:first-child>label").first().removeAttr("class").addClass("text-primary");
            }
        }
      });
      if (break_me) {
        $.notify({ icon: "error", message: peprofile.error_validate_form }, { type: 'danger', timer: 100, delay: 4000, placement: { from: "top", align: "right" } });
        $("table#add_new label.text-danger").first().parents("tr").find("td:nth-child(2)>*:first-child>*:first-child").focus();
        return false;
      }
      $(".lds-ring").css("display", "flex");
      $(".select2-container").css("z-index", "0");
      $(".lds-ring2").show();
      $("#modal_add_notif *").prop("disabled", true);
      let nonce = me.attr('integrity'), wparam = me.attr('wparam'), lparam = me.attr('lparam');
      $.ajax({
        url: pepc.ajax,
        type: 'POST',
        data: {
          action: 'peprodev-ups',
          integrity: nonce,
          wparam: wparam,
          lparam: lparam,
          dparam: data_to_save,
        },
        success: function(e) {
          if (e.success === true) {
            switch (e.data.type) {
              case "update":
                $("#notifications_list_table tbody").html(e.data.htmlupdate);
              break;
              case "add":
                $("#notifications_list_table tbody").html(e.data.htmlupdate);
              break;
            }
            $.notify({ icon: "thumb_up", message: e.data.msg }, { type: 'success', timer: 100, delay: 5000, placement: { from: "top", align: "right" } });
          }
          else {
            $.notify({ icon: "error", message: e.data.msg }, { type: 'danger', timer: 100, delay: 5000, placement: { from: "top", align: "right" } });
          }
          setTimeout(function () { $("button.btn[data-dismiss='modal']").click(); }, 100);
        },
        error: function(e) {
          $.notify({ icon: "error", message: peprofile.error_unknown_error }, { type: 'danger', timer: 100, delay: 5000, placement: { from: "top", align: "right" } });
        },
        complete: function(e) {
          $("tr[data-notif-tr='empty']").hide();
          $("#modal_add_notif *").prop("disabled", false);
          $(".lds-ring,.lds-ring2").hide();
          $(".select2-container").css("z-index", "");
        },
      });
    });
    $(document).on("hidden.bs.modal", "#del_notifications", function() {
      let dparam = $(this).data('id');
      $(`tr[data-notif-tr=${dparam}]`).removeClass("removing");
      $(`tr[data-notif-tr=${dparam}] td.td-actions *`).prop("disabled", false);
    });
    $(document).on("hidden.bs.modal", "#edit_section_builtin", function() {
      let dparam = $(this).data('id');
      $(`tr[data-notif-tr=${dparam}]`).removeClass("removing");
      $(`tr[data-notif-tr=${dparam}] td.td-actions *`).prop("disabled", false);
      $("#edit_section_builtin_title span").html("");
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
    $(document).on("click tap", ".edit_notif_builtin", function(e) {
      e.preventDefault();
      var me = $(this);
      let nonce = me.attr('integrity'),
        wparam = me.attr('wparam'),
        lparam = me.attr('lparam'),
        dparam = me.data('id');
      $(`tr[data-notif-tr=${dparam}]`).addClass("removing");
      $('#edit_section_builtin').data("integrity", nonce);
      $('#edit_section_builtin').data("wparam", wparam);
      $('#edit_section_builtin').data("lparam", lparam);
      $('#edit_section_builtin').data("dparam", dparam);
      $('#edit_section_builtin').data("id", dparam);
      $("#section_builtin_active_check").prop("checked", $(`tr[data-notif-tr=${dparam}]`).attr("data-active") == "true").trigger("change");
      $("#section_builtin_priority").val($(`tr[data-notif-tr=${dparam}]`).attr("data-priority"));
      $("#edit_section_builtin_title span").html($(`tr[data-notif-tr=${dparam}]`).attr("data-title"));
      $('#edit_section_builtin').modal();
    });
    $(document).on("click tap", "#save_built_in_edit", function(e) {
      e.preventDefault();
      var me = $(this);
      let nonce = $('#edit_section_builtin').data("integrity"),
      wparam = $('#edit_section_builtin').data("wparam"),
      lparam = $('#edit_section_builtin').data("lparam"),
      dparam = $('#edit_section_builtin').data("id");
      aparam = $("#section_builtin_active_check").prop("checked") ? "yes" : "no";
      pparam = $("#section_builtin_priority").val();

      $(".lds-ring2").show();
      $(`tr[data-notif-tr=${dparam}] td.td-actions .edit_notif_builtin`).prop("disabled", true);
      $("div#edit_section_builtin .edit_notif_builtin").prop("disabled", true);
      $.ajax({
        url: pepc.ajax,
        type: 'POST',
        data: {
          action: 'peprodev-ups',
          integrity: nonce,
          wparam: wparam,
          lparam: lparam,
          dparam: dparam,
          aparam: aparam,
          pparam: pparam,
        },
        success: function(e) {
          if (e.success === true) {
            $.notify({ icon: "thumb_up", message: e.data.msg }, { type: 'success', timer: 100, delay: 2000, placement: { from: "top", align: "right" } });
            $(`tr[data-notif-tr=${dparam}] td.priority`).text(pparam);
            $(`tr[data-notif-tr=${dparam}] td.title>div`).removeClass("bg-c1 bg-c3").addClass(aparam=="yes"?"bg-c1":"bg-c3");

            $(`tr[data-notif-tr=${dparam}]`).attr("data-active",(aparam=="yes"?"true":"false"));
            $(`tr[data-notif-tr=${dparam}]`).attr("data-priority",pparam);

          } else {
            $.notify({ icon: "error", message: e.data.msg }, { type: 'danger', timer: 100, delay: 2000, placement: { from: "top", align: "right" } });
          }
        },
        error: function(e) {
          $.notify({ icon: "error", message: peprofile.error_unknown_error }, { type: 'danger', timer: 100, delay: 4000, placement: { from: "top", align: "right" } });
        },
        complete: function(e) {
          $(".lds-ring,.lds-ring2").hide();
          $("div#edit_section_builtin *").prop("disabled", false);
          $(`tr[data-notif-tr=${dparam}] td.td-actions .edit_notif_builtin`).prop("disabled", false);
          $("div#edit_section_builtin .edit_notif_builtin").prop("disabled", false);
          $('#edit_section_builtin').modal("hide");
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

            $("#notifaddedit-img").val($data.img).trigger("change");

            $("#notifaddedit-icon").val($data.icon).trigger("change");
            if ("" !== $data.icon){
              $(".icon-preview.icon-preview-fa").removeClass("icon-preview-on").addClass("icon-preview-on");
              $(".icon-preview.icon-preview-fa.icon-preview-on > button > i").removeAttr("class").addClass("material-icons");
              $(".icon-preview.icon-preview-fa.icon-preview-on > i").removeAttr("class").addClass($data.icon);
            }

            $("#notifaddedit-title").val($data.title).trigger("change");

            $("#notifaddedit-slug").val($data.slug).trigger("change");

            $("#css").val($data.css).trigger("change");
            editor.getSession().setValue($data.css)

            $("#js").val($data.js).trigger("change");
            editor2.getSession().setValue($data.js)

            $("#notifaddedit-subject").val($data.subject).trigger("change");

            $("#notifaddedit-access").val($data.access.split(",")).trigger("change");

            $("#notifaddedit-ld_lms").val($data.ld_lms).trigger("change");

            $("#notifaddedit-priority").val($data.priority).trigger("change");

            if("yes" == $data.is_active){
              $(`input#notifaddedit-active-check`).prop("checked",true).trigger("click change");
            }else{
              $(`input#notifaddedit-active-check`).prop("checked",false).trigger("click change");
            }

            update_scrolbar();

          });
        }
      }
    });
    $(document).on("click tap",".fontawesome-icon-picker .nav-tabs a.btn",function(e){
      var me = $(this);
      $("#searchfontawesome").trigger("change").focus();
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
    $(document).on("click tap", "#remove_section", function(e) {
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
    $(document).on("click tap", "#toggle_builtin",function(e){
      e.preventDefault();
      var me = $(this);
      $("#notifications_list_table tr.builtin, #notifications_list_table tr.resttr").toggle();
    });


    $(document).on("click tap focus","label[for*='notifaddedit-icon']",function(e){
      e.preventDefault();
      $("button.btn.launch-icons").trigger("click");
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
    function update_scrolbar() {
    }
    function CLEAR_NOTIF_FORM() {
      if (tinyMCE.activeEditor){ tinyMCE.activeEditor.setContent(""); }
      $("#notifaddedit-title, #notifaddedit-access, #notifaddedit-ld_lms, #notifaddedit-slug, #notifaddedit-priority, #notifaddedit-subject, #notifaddedit-content, #notifaddedit-icon, #notifaddedit-img, #current_edit_notif_id").val("").trigger("change");
      $("#notifaddedit-priority").val(300).trigger("change");
      $(".remove-icon.btn-sm-sqc").click().trigger("change");
      $("#notifaddedit-active-check").prop("checked", true).trigger("change");
      $("table#add_new tr td:first-child>label:first-of-type").removeAttr("class").addClass("text-primary");
      $("h5.modal-title.editmode span").text("");
      if(editor){editor.setValue("");}
      if(editor2){editor2.setValue("");}
      $(".lds-ring").hide();
    }
    function isNumber(n) { return !isNaN(parseFloat(n)) && !isNaN(n - 0) }
  });
})(jQuery);
