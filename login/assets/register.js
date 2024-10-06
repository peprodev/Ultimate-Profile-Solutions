/**
 * @Author: Amirhosseinhpv
 * @Date:   2021/08/02 22:04:09
 * @Email:  its@hpv.im
 * @Last modified by:   Amirhosseinhpv
 * @Last modified time: 2022/02/11 03:04:55
 * @License: GPLv2
 * @Copyright: Copyright © Amirhosseinhpv (https://hpv.im), all rights reserved.
 */
jQuery.noConflict();
(function($) {
  $(function() {
    jconfirm.defaults = {
      title: '',
      titleClass: '',
      type: 'blue',
      typeAnimated: true,
      closeIcon: 0,
      draggable: true,
      dragWindowGap: 15,
      dragWindowBorder: true,
      animateFromElement: true,
      smoothContent: true,
      content: '',
      buttons: {},
      defaultButtons: {
        ok: { keys: ['enter'], text: _register_fields.okTxt, action: function() {} },
        close: { keys: ['enter'], text: _register_fields.closeTxt, action: function() {} },
        cancel: { keys: ['esc'], text: _register_fields.cancelbTn, action: function() {} },
      },
      contentLoaded: function(data, status, xhr) {},
      icon: '',
      lazyOpen: false,
      bgOpacity: null,
      theme: 'modern', /* light dark supervan material bootstrap modern */
      animation: 'scale',
      closeAnimation: 'scale',
      animationSpeed: 400,
      animationBounce: 1,
      rtl: $("body").is(".rtl") ? true : false,
      container: 'body',
      containerFluid: false,
      backgroundDismiss: false,
      backgroundDismissAnimation: '',
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
      bootstrapClasses: { container: 'container', containerFluid: 'container-fluid', row: 'row', },
      onContentReady: function() {},
      onOpenBefore: function() {},
      onOpen: function() {},
      onClose: function() {},
      onDestroy: function() {},
      onAction: function() {},
      escapeKey: true,
    };
    setTimeout(function () { $(document).trigger("pepro_register_fields_load_json"); }, 100);
    setTimeout(function () { $(document).trigger("pepro_register_fields_make_json"); }, 200);
    setTimeout(function () { $(document).trigger("pepro_redirection_fields_load_json"); }, 100);
    setTimeout(function () { $(document).trigger("pepro_redirection_fields_make_json"); }, 200);
    setTimeout(function () { $("[name='reglogin_type']").trigger("refesh", [true]); }, 100);
    $('input[colorpicker]').each(function(){
      $(this).wpColorPicker({ palettes: _register_fields._palett});
    });
    var editor_header;
    var editor_footer;
    var editor_css;
    var editor_fields;
    var redirection_fields;
    var verification_email_template;

    if ($("#register_fileds_editor").length){
      editor_fields = ace.edit("register_fileds_editor");
      editor_fields.setTheme("ace/theme/dracula");
      editor_fields.session.setMode("ace/mode/json");
      editor_fields.setShowPrintMargin(false);
      ace.config.loadModule('ace/ext/language_tools', function() {
        editor_fields.setOptions({ enableBasicAutocompletion: true, enableSnippets: true, enableLiveAutocompletion: true, })
      });
      editor_fields.getSession().on('change', function() {$('#register_fileds').val(editor_fields.getSession().getValue());});
    }
    if ($("#verification_email_template_editor").length){
      verification_email_template = ace.edit("verification_email_template_editor");
      verification_email_template.setTheme("ace/theme/dracula");
      verification_email_template.session.setMode("ace/mode/html");
      verification_email_template.setShowPrintMargin(false);
      ace.config.loadModule('ace/ext/language_tools', function() {
        verification_email_template.setOptions({ enableBasicAutocompletion: true, enableSnippets: true, enableLiveAutocompletion: true, })
      });
      verification_email_template.getSession().on('change', function() {$('#verification_email_template').val(verification_email_template.getSession().getValue()); });
    }
    if ($("#redirection_fileds_editor").length){
      redirection_fields = ace.edit("redirection_fileds_editor");
      redirection_fields.setTheme("ace/theme/dracula");
      redirection_fields.session.setMode("ace/mode/json");
      redirection_fields.setShowPrintMargin(false);
      ace.config.loadModule('ace/ext/language_tools', function() {
        redirection_fields.setOptions({ enableBasicAutocompletion: true, enableSnippets: true, enableLiveAutocompletion: true, })
      });
      redirection_fields.getSession().on('change', function() {$('#redirection_fileds').val(redirection_fields.getSession().getValue());});
    }
    if ($("#customcss_editor").length){
      editor_css = ace.edit("customcss_editor");
      editor_css.setTheme("ace/theme/dracula");
      editor_css.session.setMode("ace/mode/css");
      editor_css.setShowPrintMargin(false);
      ace.config.loadModule('ace/ext/language_tools', function() {
        editor_css.setOptions({
          enableBasicAutocompletion: true,
          enableSnippets: true,
          enableLiveAutocompletion: true,
        })
      });
      editor_css.getSession().on('change', function() {$('#customcss').val(editor_css.getSession().getValue()); });
    }
    if ($("#headerhtml_editor").length){
      editor_header = ace.edit("headerhtml_editor");
      editor_header.setTheme("ace/theme/dracula");
      editor_header.session.setMode("ace/mode/html");
      editor_header.setShowPrintMargin(false);
      ace.config.loadModule('ace/ext/language_tools', function() {
        editor_header.setOptions({
          enableBasicAutocompletion: true,
          enableSnippets: true,
          enableLiveAutocompletion: true,
        })
      });
      var text = editor_header.getSession().getValue();
      editor_header.getSession().on('change', function() {$('#headerhtml').val(editor_header.getSession().getValue()); });
    }
    if ($("#footerhtml_editor").length){
      editor_footer = ace.edit("footerhtml_editor");
      editor_footer.setTheme("ace/theme/dracula");
      editor_footer.session.setMode("ace/mode/html");
      editor_footer.setShowPrintMargin(false);
      ace.config.loadModule('ace/ext/language_tools', function() {
        editor_footer.setOptions({
          enableBasicAutocompletion: true,
          enableSnippets: true,
          enableLiveAutocompletion: true,
        })
      });
      editor_footer.getSession().on('change', function() {$('#footerhtml').val(editor_footer.getSession().getValue()); });
    }

    shortcut("Ctrl+S",function() {
      event.preventDefault()
      // show_toast(_register_fields.saving);
      $(".login-section-save").first().trigger("click");
    });

    $(document).on("pepro_register_fields_load_json", function(e){
      if ($("#register_fileds").length){
        var data_json = [];
        var data_json_raw = $("#register_fileds").val();
        try {
          data_json = JSON.parse(data_json_raw);
        } catch (e) {
          setTimeout(console.log.bind(console, `%c Error parsing json data `, "background: #e63838; color: #fff;", ""));
        } finally {
          $.each(data_json, function(index, writer) {
            $html_data = $("template#raw_field").html();
            newWriter = $($html_data).appendTo(".register-workspace");
            $.each(writer, function(name, value) {
              $el = $(newWriter).find(`[name='${name}']`);
              is_checkbox = "checkbox" == $el.attr("type");
              if (is_checkbox){
                $value = "yes" === value;
                $el.prop("checked", $value).trigger("change", true);
              }else{
                $el.val(value).trigger("change", true);
              }
            });
            $(newWriter).find(".register-field-single-content").addClass("slide-up");
          });
        }
      }
    });
    $(document).on("pepro_register_fields_make_json", function(e){
      var $writers = [];
      $(".register-workspace .register-field-single").each(function(index, val){
        details = {};
        $(val).find(":input").each(function(index, val) {
            $id         = $(val).attr("name");
            $value      = $(val).val();
            is_checkbox = "checkbox" == $(val).attr("type");
            if (is_checkbox){
              $value = $(val).prop("checked") ? "yes" : "no";
            }
            details[$id] = $value;
          });
        if (!$.isEmptyObject(details)){ $writers.push(details) }
      });
      $("#register_fileds").val(JSON.stringify($writers));
      editor_fields.setValue(JSON.stringify($writers, null, ' '));
    });

    $(document).on("pepro_redirection_fields_load_json", function(e){
      if ($("#redirection_fileds").length){
        var data_json = [];
        var data_json_raw = $("#redirection_fileds").val();
        try {
          data_json = JSON.parse(data_json_raw);
        } catch (e) {
          setTimeout(console.log.bind(console, `%c Error parsing json data `, "background: #e63838; color: #fff;", ""));
        } finally {
          $.each(data_json, function(index, writer) {
            $html_data = $("template#redirection_raw_field").html();
            newWriter = $($html_data).appendTo(".redirection-workspace");
            $.each(writer, function(name, value) {
              $el = $(newWriter).find(`[name='${name}']`);
              is_checkbox = "checkbox" == $el.attr("type");
              if (is_checkbox){
                $value = "yes" === value;
                $el.prop("checked", $value).trigger("change", true);
              }else{
                $el.val(value).trigger("change", true);
              }
            });
            $(newWriter).find(".redirection-field-single-content").addClass("slide-up");
          });
        }
      }
    });
    $(document).on("pepro_redirection_fields_make_json", function(e){
      var $writers = [];
      $(".redirection-workspace .redirection-field-single").each(function(index, val){
        details = {};
        $(val).find(":input").each(function(index, val) {
          $id         = $(val).attr("name");
          $value      = $(val).val();
          is_checkbox = "checkbox" == $(val).attr("type");
          if (is_checkbox){ $value = $(val).prop("checked") ? "yes" : "no"; }
          details[$id] = $value;
        });
        if (!$.isEmptyObject(details)){ $writers.push(details) }
      });
      $("#redirection_fileds").val(JSON.stringify($writers));
      redirection_fields.setValue(JSON.stringify($writers, null, ' '));
    });

    $(document).on("change keyup", ".register-field-single :input, .iostoggle", function(e, muted){
      if (!muted){ $(document).trigger("pepro_register_fields_make_json"); }
    });
    $(document).on("change keyup", ".redirection-field-single :input", function(e, muted){
      if (!muted){ $(document).trigger("pepro_redirection_fields_make_json"); }
    });

    $(document).on("change keyup", ".form-input.meta-name", function(e){
      this.value = hasArabic(this.value).replace(/ /g, "_").toLowerCase();
    });
    $(document).on("change keyup refesh", "[name='reglogin_type']", function(e, mute){
      switch ($("[name='reglogin_type']:checked").val()) {
        case "mobile":
          $('._regdef_mobile [name="_regdef_mobile"]').prop("checked", true).trigger("change").prop("disabled", true);
          $('._regdef_mobile .is_required').prop("checked", true).trigger("change").prop("disabled", true);
          if (!mute){
            $('._regdef_email [name="_regdef_email"]').prop("checked", false).trigger("change").prop("disabled", false);
            $('._regdef_email .is_required').prop("checked", false).trigger("change").prop("disabled", false);
            $('._regdef_passwords [name="_regdef_passwords"]').prop("checked", false).trigger("change").prop("disabled", false);
            $('._regdef_passwords .is_required').prop("checked", false).trigger("change").prop("disabled", false);
          }
          break;
        case "email":
          if (!mute){
            $('._regdef_mobile [name="_regdef_mobile"]').prop("checked", false).trigger("change").prop("disabled", false);
            $('._regdef_mobile .is_required').prop("checked", false).trigger("change").prop("disabled", false);
          }
          $('._regdef_email [name="_regdef_email"]').prop("checked", true).trigger("change").prop("disabled", true);
          $('._regdef_email .is_required').prop("checked", true).trigger("change").prop("disabled", true);
          $('._regdef_passwords [name="_regdef_passwords"]').prop("checked", true).trigger("change").prop("disabled", true);
          $('._regdef_passwords .is_required').prop("checked", true).trigger("change").prop("disabled", true);
          break;
        case "mailotp":
          if (!mute){
            $('._regdef_mobile [name="_regdef_mobile"]').prop("checked", false).trigger("change").prop("disabled", false);
            $('._regdef_mobile .is_required').prop("checked", false).trigger("change").prop("disabled", false);
          }
          $('._regdef_email [name="_regdef_email"]').prop("checked", true).trigger("change").prop("disabled", true);
          $('._regdef_email .is_required').prop("checked", true).trigger("change").prop("disabled", true);
          $('._regdef_passwords [name="_regdef_passwords"]').prop("checked", false).trigger("change").prop("disabled", false);
          $('._regdef_passwords .is_required').prop("checked", false).trigger("change").prop("disabled", false);
          break;
        default:
      }
    });
    $(document).on("change keyup", "._no_opt_fields .is_required", function(e){
      if ($(this).prop("checked")){ $(this).parents("._no_opt_fields").find(".main_checkbox").prop("checked",true).trigger("change"); }
    });
    $(document).on("change keyup", "[name='title']", function(e){
      $title = $(this).parents(".register-field-single").find(".live-title");
      if ("" == $.trim($(this).val())){
        $title.text($title.data("default"))
      }else{
        $title.text($.trim($(this).val()));
      }
      $(document).trigger("pepro_register_fields_make_json");
    });
    $(document).on("change keyup", "[name='type']", function(e){
      type = $(this).val();
      parent = $(this).parents(".register-field-single-content");
      parent.find(`.fields-wrapper-inner`).hide();
      parent.find(`.fields-wrapper-inner[data-show*='${type}'], .fields-wrapper-inner[data-show='all']`).show();
      parent.find(`.fields-wrapper-inner[data-hide*='${type}'], .fields-wrapper-inner[data-hide='all']`).hide();
      $(document).trigger("pepro_register_fields_make_json");
    });
    $(document).on("change keyup", "[name='role']", function(e){
      $title = $(this).parents(".redirection-field-single").find(".live-title");
      $title.text( $title.data("default") + $(this).parents(".redirection-field-single").find("select[name='role'] option:selected").text() );
      $(document).trigger("pepro_redirection_fields_make_json");
    });

    $(document).on("click tap", ".register--add-field", function(e){
      e.preventDefault();
      var me = $(this);
      newfild = $($("template#raw_field").html()).appendTo(".register-workspace");
      newfild.find("select").trigger("change");
      $(newfild).glow();
      show_toast(_register_fields._added);
      $(document).trigger("pepro_register_fields_make_json");
    });
    $(document).on("click tap", ".register--clear-field", function(e){
      e.preventDefault();
      var me = $(this);
      $(this).parents(".register-field-single").remove();
      if (!$(".register-workspace.workspace .register-field-single").length){
        $(".register-workspace.workspace").html("");
      }
      show_toast(_register_fields._removed);
      $(document).trigger("pepro_register_fields_make_json");
    });
    $(document).on("click tap", ".register--clear-fields", function(e){
      e.preventDefault();
      var me = $(this);
      var jc = $.confirm({
        title: `<br>${_register_fields.confirmTxt}`, content: _register_fields.confirmCap,
        icon: 'fas fa-trash-alt', closeIcon: 0, type: "red", boxWidth: "600px",
        onContentReady: function() {},
        buttons: {
          no: { text: _register_fields.txtNop, btnClass: 'btn-default', keys: ['esc'], action: function() { return true; } },
          yes: { text: _register_fields.txtYes, btnClass: 'btn-red', keys: ['enter'], action: function() {
              jc.showLoading(true);
              $(".register-workspace").empty();

              $(document).trigger("pepro_register_fields_make_json");
              show_modal_alert(_register_fields.successTtl, _register_fields.successCap,"fas fa-check-circle", "green");
              jc.close();
              return false;
            }
          },
        }
      });
    });
    $(document).on("click tap", ".register--duplicate-field", function(e){
      e.preventDefault();
      cloned = $(this).parents(".register-field-single").clone();
      cloned.find('[name="type"]').val($(this).parents(".register-field-single").find('[name="type"]').val()).trigger("change");
      $(cloned).insertAfter($(this).parents(".register-field-single")).glow();
      $(document).trigger("pepro_register_fields_make_json");
    });

    $(document).on("click tap", ".redirection--add-field", function(e){
      e.preventDefault();
      var me = $(this);
      $($("template#redirection_raw_field").html()).appendTo($(".redirection-workspace")).glow();
      $("[name='role']").trigger("change");
      show_toast(_register_fields._added);
      $(document).trigger("pepro_redirection_fields_make_json");
    });
    $(document).on("click tap", ".redirection--clear-field", function(e){
      e.preventDefault();
      var me = $(this);
      $(this).parents(".redirection-field-single").remove();
      if (!$(".redirection-workspace.workspace .redirection-field-single").length){
        $(".redirection-workspace.workspace").html("");
      }
      show_toast(_register_fields._removed);
      $(document).trigger("pepro_redirection_fields_make_json");
    });
    $(document).on("click tap", ".redirection--clear-fields", function(e){
      e.preventDefault();
      var me = $(this);
      var jc = $.confirm({
        title: `<br>${_register_fields.confirmTxt}`, content: _register_fields.confirmCap,
        icon: 'fas fa-trash-alt', closeIcon: 0, type: "red", boxWidth: "600px",
        onContentReady: function() {},
        buttons: {
          no: { text: _register_fields.txtNop, btnClass: 'btn-default', keys: ['esc'], action: function() { return true; } },
          yes: { text: _register_fields.txtYes, btnClass: 'btn-red', keys: ['enter'], action: function() {
              jc.showLoading(true);
              $(".redirection-workspace").empty();

              $(document).trigger("pepro_redirection_fields_make_json");
              show_modal_alert(_register_fields.successTtl, _register_fields.successCap,"fas fa-check-circle", "green");
              jc.close();
              return false;
            }
          },
        }
      });
    });
    $(document).on("click tap", ".redirection--duplicate-field", function(e){
      e.preventDefault();
      cloned = $(this).parents(".redirection-field-single").clone();
      cloned.find('[name="role"]').val($(this).parents(".redirection-field-single").find('[name="role"]').val()).trigger("change");
      newitem = $(cloned).insertAfter($(this).parents(".redirection-field-single"));
      newitem.glow();
      $(document).trigger("pepro_redirection_fields_make_json");
    });

    $(document).on("click tap", ".register-field-single-title .live-title", function(e){
      e.preventDefault();
      var me = $(this);
      me.parents(".register-field-single-title").next(".register-field-single-content").toggleClass("slide-up");
    });
    $(document).on("click tap", ".redirection-field-single-title .live-title", function(e){
      e.preventDefault();
      var me = $(this);
      me.parents(".redirection-field-single-title").next(".redirection-field-single-content").toggleClass("slide-up");
    });

    $(document).on("click tap", ".register--toggle-fields", function(e){
      e.preventDefault();
      var me = $(this);
      $firstitem = $(".register-field-single-content").first().is(".slide-up");
      if ($firstitem){
        $(".register-field-single-content").removeClass("slide-up");
      }else{
        $(".register-field-single-content").addClass("slide-up");
      }
    });
    $(document).on("click tap", ".redirection--toggle-fields", function(e){
      e.preventDefault();
      var me = $(this);
      $firstitem = $(".redirection-field-single-content").first().is(".slide-up");
      if ($firstitem){
        $(".redirection-field-single-content").removeClass("slide-up");
      }else{
        $(".redirection-field-single-content").addClass("slide-up");
      }
    });

    $(document).on("click tap", ".register--arrow-up-field", function(e){
      e.preventDefault();
      var $current = $(this).closest('.register-field-single')
      var $previous = $current.prev('.register-field-single');
      if($previous.length !== 0){
        $current.insertBefore($previous);
      }
      $(document).trigger("pepro_register_fields_make_json");
      return false;
    });
    $(document).on("click tap", ".register--arrow-down-field", function(e){
      e.preventDefault();
      var $current = $(this).closest('.register-field-single')
      var $next = $current.next('.register-field-single');
      if($next.length !== 0){
        $current.insertAfter($next);
      }
      $(document).trigger("pepro_register_fields_make_json");
      return false;
    });

    $(document).on("click tap", ".redirection--arrow-up-field", function(e){
      e.preventDefault();
      var $current = $(this).closest('.redirection-field-single')
      var $previous = $current.prev('.redirection-field-single');
      if($previous.length !== 0){
        $current.insertBefore($previous);
      }
      $(document).trigger("pepro_redirection_fields_make_json");
      return false;
    });
    $(document).on("click tap", ".redirection--arrow-down-field", function(e){
      e.preventDefault();
      var $current = $(this).closest('.redirection-field-single')
      var $next = $current.next('.redirection-field-single');
      if($next.length !== 0){
        $current.insertAfter($next);
      }
      $(document).trigger("pepro_redirection_fields_make_json");
      return false;
    });

    if ($("[name=login-section-active]").prop("checked")){
      $("table.activesecurity-table, #alert-primary").show();
    }
    else{
      $("table.activesecurity-table, #alert-primary").hide();
    }

    $(".gatewayssettings .card-body").addClass("hide");
    $(".gatewayssettings .card-body."+$("[name=sms_method]").val()).removeClass("hide");
    $(document).on("click tap", ".register--import-export", function(e){
      e.preventDefault();
      $(".register-fields-import-export").toggleClass("slide-up");
    });
    $(document).on("click tap", ".redirection--import-export", function(e){
      e.preventDefault();
      $(".redirection-fields-import-export").toggleClass("slide-up");
    });
    $(document).on("change", "[name=login-section-active]", function(e){
      e.preventDefault();
      var me = $(this);
      if (me.prop("checked")){
        $("table.activesecurity-table, #alert-primary").show();
      }else{
        $("table.activesecurity-table, #alert-primary").hide();
      }
    });

    /* SAVE SETTING BTN */
    $(document).on("click tap", ".login-section-save", function(e) {
      e.preventDefault();
      datatosave           = {
        "style"                : $("#login-section-style").val(),
        "activesecurity"       : $("#login-section-active").prop("checked") ? "yes"        : "no",
        "loginslug"            : $("#login-section-loginslug").val(),
        "redirectslug"         : $("#login-section-redirectslug").val(),
        "force-style"          : $("#login-section-style-force").attr("data-checked"),
        "showlogo"             : $("#login-section-show-logo").attr("data-checked"),
        "logo"                 : $("#login-section-logo").val(),
        "logo-id"              : $("#login-section-logo").attr("data-id"),
        "logo-w"               : $("#login-section-logo-w").val(),
        "logo-h"               : $("#login-section-logo-h").val(),
        "logo-title"           : $("#login-section-logotitle").val(),
        "logo-href"            : $("#login-section-logohref").val(),
        "shake"                : $("#login-section-shake").attr("data-checked"),
        "error"                : $("#login-section-error").attr("data-checked"),
        "msg"                  : $("#login-section-msg").attr("data-checked"),
        "rmc"                  : $("#login-section-rmc").attr("data-checked"),
        "b2b"                  : $("#login-section-b2b").attr("data-checked"),
        "privacy"              : $("#login-section-privacy").attr("data-checked"),
        "nav"                  : $("#login-section-nav").attr("data-checked"),
        "spb"                  : $("#login-section-spb").attr("data-checked"),
        "forcebg"              : $("#login-section-forcebg").attr("data-checked"),
        "bgtype"               : $("#login-section-bgtype").val(),
        "bg-solid"             : $("#login-section-bg-solid").val(),
        "bg-gradient1"         : $("#login-section-bg-gradient1").val(),
        "bg-gradient2"         : $("#login-section-bg-gradient2").val(),
        "bg-gradient3"         : $("#login-section-bg-gradient3").val(),
        "bg-img"               : $("#login-section-bg-img").val(),
        "bg-img-id"            : $("#login-section-bg-img").attr("data-id"),
        "bg-video"             : $("#login-section-bg-video").val(),
        "bg-video-id"          : $("#login-section-bg-video").attr("data-id"),
        "bg-video-autoplay"    : $("#login-section-bg-video-autoplay").attr("data-checked"),
        "bg-video-muted"       : $("#login-section-bg-video-muted").attr("data-checked"),
        "bg-video-loop"        : $("#login-section-bg-video-loop").attr("data-checked"),
        "html-header"          : $("#headerhtml").val(),
        "html-footer"          : $("#footerhtml").val(),
        "reglogin_type"        : $("[name='reglogin_type']:checked").val(),
        "pro_verify"           : $("[name='pro_verify']:checked").val(),
        "force_register_form"  : $("[name='force_register_form']:checked").val(),
        "show_email_login_form": $("[name='show_email_login_form']:checked").val(),
        "register_fileds"      : $("#register_fileds").val(),
        "redirection_fileds"   : $("#redirection_fileds").val(),
        "customcss"            : $("#customcss").val(),
      };
      if($(".save_checkboxes").length){
        $(".save_checkboxes input[type=checkbox]").each(function(index, val) {
          name = $(val).attr("name");
          value = $(val).prop("checked") ? "yes" : "no";
          datatosave[name] = value;
        });
      }
      if($(".save_sms_settings").length){
        $(".save_sms_settings :input").each(function(index, val) {
          name = $(val).attr("name");
          value = $(val).val();
          datatosave[name] = value;
        });
      }
      if($(".save_email_settings").length){
        $(".save_email_settings :input").each(function(index, val) {
          name = $(val).attr("name");
          value = $(val).val();
          datatosave[name] = value;
        });
        datatosave["verification_email_template"] = $('#verification_email_template').val();
      }
      var me = $(this);
      let nonce = me.attr('integrity'),wparam = me.attr('wparam'),lparam = me.attr('lparam');
      notify = $.notify({icon: "hourglass_empty", message: pepc.loading}, {type: 'info',timer: 3000, placement: {from: "top",align: "right", allow_dismiss: false, showProgressbar: true}});
      animate_save_btn();
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
            notify.update({icon: "thumb_up",message: e.data.msg, type: 'success'});
            $("#alert-primary").html(`<div class="alert alert-warning alert-dismissible fade show" role="alert">${e.data.loginStr}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>`);
          } else {
            notify.update({icon: "error",message: e.data.msg, type: 'danger'});
          }
        },
        error: function(e) {
          console.error(e);
        },
        complete: function(e) {
          animate_save_btn(false);
        },
      });
    });

    $(document).on("click tap", '[class*="field-opt"] > div.label span', function(e){
      e.preventDefault();
      $(this).parent().next("div").find(":input").focus().select();
    });
    /* SMS OTP Check */
    $(document).on("click tap", ".btn.testotp", function(e){
      e.preventDefault();
      var me = $(this);
      let nonce = me.attr('integrity'), wparam = me.attr('wparam'), lparam = me.attr('lparam');
      notify = $.notify({icon: "hourglass_empty", message: pepc.loading}, {type: 'info',timer: 10000, placement: {from: "top",align: "right", allow_dismiss: false, showProgressbar: true}});
      animate_save_btn(true, ".btn.testotp","send");
      $.ajax({
        url: pepc.ajax,
        type: 'POST',
        data: {
          action: 'peprodev-ups',
          integrity: nonce,
          wparam: "loginregister",
          lparam: "testotp",
          dparam: $("#sms_test").val(),
        },
        success: function(e) {
          if (e.success === true) {
            notify.update({icon: "thumb_up",message: e.data.msg, type: 'success'});
          } else {
            notify.update({icon: "error",message: e.data.msg, type: 'danger'});
          }
        },
        error: function(e) {
          console.error(e);
        },
        complete: function(e) {
          animate_save_btn(false, ".btn.testotp","send");
        },
      });
    });
    $(document).on("change", "[name=sms_method]", function(e){
      e.preventDefault();
      var me = $(this);
      $(".gatewayssettings .card-body").addClass("hide");
      $(".gatewayssettings .card-body."+me.val()).removeClass("hide");
    });
    $(document).on("click tap", ".nav-tabs-wrapper .nav-link", function(e){
      e.preventDefault();
      $(".nav-link.active.show").removeClass("active show");
      $(this).addClass("active show");
      $(".tab-pane.active.show").removeClass("active show");
      $($(this).attr("href")).addClass("active show")
      history.replaceState(undefined, undefined, $(this).attr("href"))
    });
    $(document).on("click tap", "#copyshortcode", function(e){
      e.preventDefault();
      var me = $(this);
      copy_clipboard($(".smart_btn_workspace pre").html());
      show_toast(_register_fields._copy, "rgba(21, 139, 2, 0.8)");
    });
    $(document).on("click tap", ".copyhwnd", function(e){
      e.preventDefault();
      var me = $(this);
      copy_clipboard($(me.data("copy")).text().replace(/<br>/gi,"\n"));
      show_toast(_register_fields._copy, "rgba(21, 139, 2, 0.8)");
    });
    $(document).on("click tap", ".copyme", function(e){
      e.preventDefault();
      copy_clipboard($(this).data("copy"));
      show_toast(_register_fields._copy, "rgba(21, 139, 2, 0.8)");
    });
    $(document).on("click tap", "copy", function(e){
      e.preventDefault();
      var me = $(this);
      copy_clipboard(me.text());
      show_toast(_register_fields._copy, "rgba(21, 139, 2, 0.8)");
    });

    if ("" !== window.location.hash){
      $tab = $(`.tab-content>.tab-pane${window.location.hash}`);
      if ($tab.length){
        $(".nav-link.active.show, .tab-pane.active.show").removeClass("active show");
        $(`li.nav-item>.nav-link[href='${window.location.hash}'], ${window.location.hash}`).addClass("active show")
      }
    }

    function scroll_element(element, offset = 0) {
    	$("html, body").animate({ scrollTop: element.offset().top - offset }, 500);
    }
    function animate_save_btn(save=true, $btn="button.btn.btn-primary.icn-btn[integrity][wparam][lparam]", $normal="save", $loading="settings") {
      if (save){
        var savebtn = $($btn);
        if (savebtn.length > 0) {
          savebtn.find("i.material-icons").html($loading).addClass("heartbeatcss");
        }
      }
      else{
        var savebtn = $($btn);
        if (savebtn.length > 0) {
          savebtn.find("i.material-icons").html($normal).removeClass("heartbeatcss");
        }
      }
    }
    function hasArabic( character ) {
      return character.replace(/[\u0600-\u06ff]|[\u0750-\u077f]|[\ufb50-\ufc3f]|[\ufe70-\ufefc]|[\u0200]|[\u00A0]/g, "");
    }
    function isRTL( character ) {
      var arabicAlphabetDigits = /[\u0600-\u06ff]|[\u0750-\u077f]|[\ufb50-\ufc3f]|[\ufe70-\ufefc]|[\u0200]|[\u00A0]/g;
      return arabicAlphabetDigits.test(character);
    }
    function show_toast(data = "Sample Toast!", bg="", delay = 4500) {
    	if (!$("toast").length) {$(document.body).append($("<toast>"));}else{$("toast").removeClass("active");}
    	setTimeout(function () {
    		$("toast").css("--toast-bg", bg).html(data).stop().addClass("active").delay(delay).queue(function () {
    			$(this).removeClass("active").dequeue().off("click tap");
    		}).on("click tap", function (e) {e.preventDefault();$(this).stop().removeClass("active");});
    	}, 200);
    }
    function copy_clipboard(data) {
      var $temp = $("<textarea>");
      $("body").append($temp);
      $temp.val(data).select();
      document.execCommand("copy");
      $temp.remove();
    }
    function show_modal_alert(title = "", content = "", icon = "fas fa-info-circle", type = "blue", boxWidth = "600px", $fn = null, theme="modern") {
     $.confirm({
     title: `<br>${title}`,
     content: content,
     icon: icon,
     /* light dark supervan material bootstrap modern */
     theme: theme,
     type: type,
     boxWidth: boxWidth,
     buttons: { close: { btnClass: "btn-"+type, text: _register_fields.closeTxt, keys: ["enter", "esc"], action: $fn } },
     });
    }

    $.fn.glow = function(){this.each(function(){$("html, body").animate({ scrollTop: $(this).offset().top-50}, 500); $(this).stop().addClass("glow").delay(1500).queue(function() {$(this).removeClass("glow").dequeue();});});};



  });
})(jQuery);
