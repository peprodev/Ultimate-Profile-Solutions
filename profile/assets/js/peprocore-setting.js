/**
 * @Author: Amirhosseinhpv
 * @Date:   2020/05/03 13:33:07
 * @Email:  its@hpv.im
 * @Last modified by:   Amirhosseinhpv
 * @Last modified time: 2022/01/11 20:13:11
 * @License: GPLv2
 * @Copyright: Copyright © 2020 Amirhosseinhpv, All rights reserved.
 */


(function($) {
  $(document).ready(function() {
    $('input[colorpicker]').each(function(){
      $(this).wpColorPicker({ palettes: pepc._palett});
    });

    if ($("#csseditor").length){
      editor = ace.edit("csseditor");
      editor.setTheme("ace/theme/dracula");
      editor.session.setMode("ace/mode/css");
      editor.setShowPrintMargin(false);
      ace.config.loadModule('ace/ext/language_tools', function() {
        editor.setOptions({
          enableBasicAutocompletion: true,
          enableSnippets: true,
          enableLiveAutocompletion: true,
        })
      });
      var text = editor.getSession().getValue();
      $('#css').val(text);
      editor.getSession().on('change', function() {
        var text = editor.getSession().getValue();
        $('#css').val(text);
      });
    }

    if ($("#jseditor").length){
      editor2 = ace.edit("jseditor");
      editor2.setTheme("ace/theme/dracula");
      editor2.session.setMode("ace/mode/javascript");
      editor2.setShowPrintMargin(false);
      ace.config.loadModule('ace/ext/language_tools', function() {
        editor2.setOptions({
          enableBasicAutocompletion: true,
          enableSnippets: true,
          enableLiveAutocompletion: true,
        })
      });
      var text = editor2.getSession().getValue();
      $('#js').val(text);
      editor2.getSession().on('change', function() {
        var text = editor2.getSession().getValue();
        $('#js').val(text);
      });
    }


    $(document).on("click tap", "#profile-section-save", function(e) {
      e.preventDefault();
      var elID = `#${pepc.customhtml_tad}`;
      var contentHTML = $(elID).val();
      try {
        if (tinymce.activeEditor){ tinymce.activeEditor.save(); }
        if ($(elID).hasClass("tmce-active")){ contentHTML = tinymce.activeEditor.getContent(); }
      } catch (e) { }
      var datatosave = {
        "css":               $("#css").val(),
        "js":                $("#js").val(),
        "logo":              $("#profile-section-logo").val(),
        "logo-id":           $("#profile-section-logo").attr("data-id"),
        "showwelcome":       $("#showwelcome").attr("data-checked"),
        "headerhook":        $("#headerhook").attr("data-checked"),
        "footerhook":        $("#footerhook").attr("data-checked"),
        "woocommerceorders": $("#woocommerceorders").attr("data-checked"),
        "woocommercestats":  $("#woocommercestats").attr("data-checked"),
        "showcustomtext":    $("#showcustomtext").attr("data-checked"),
        "customhtml":        contentHTML,
        "customposition":    $("#customposition").val(),
        "profile-dash-page": $("#profile_dash_page").val(),
      };
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
            if (e.data.notice){
              $(".dashpagetemplatenotice").html(e.data.notice_html).show();
            }else{
              $(".dashpagetemplatenotice").html(e.data.notice_html).hide();
            }
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
    $(document).on("click tap", "#profile-section-logo-new",function(e){
      e.preventDefault();
      var image_frame, me = $(this);
      if (image_frame) {image_frame.open();}
      image_frame = wp.media({title: me.attr("data-title"),multiple: false,library: {type: 'image',}});
      image_frame.on('select', function() {
        var selection = image_frame.state().get('selection').first().toJSON();
        $(`${me.attr("data-ref")}`).val(selection.url);
        $(`${me.attr("data-ref2")}`).attr("data-image",`${selection.url}`);
        $(`${me.attr("data-ref3")}`).css("background-image",`url("${selection.url}")`);
        $(`${me.attr("data-ref4")}`).attr("src",`${selection.url}`);
      });
      image_frame.on('open', function() {
        var selection = image_frame.state().get('selection');
        var ids = jQuery('input#pepc-settings-theme-sidebar-image-custom').val().split(',');
        ids.forEach(function(id) {
          var attachment = wp.media.attachment(id);
          attachment.fetch();
          selection.add(attachment ? [attachment] : []);
        });
      });
      image_frame.open();
    });

    $(document).on("click tap", ".peprodev-ups_shortcodehandler.peprofile-open-box",function(e){
      e.preventDefault();
      $(".popup-shortcode-select").toggleClass("hide");
    });
    $(document).on("click tap", ".peprodev-ups_shortcodehandler._shortcodehandlerwhitecard",function(e){
      e.preventDefault();
      var me = $(this);
      window.send_to_editor('[profile-card-1]\r\nSample content\r\n[/profile-card-1]');
    });
    $(document).on("click tap", ".peprodev-ups_shortcodehandler._shortcodehandleruser",function(e){
      e.preventDefault();
      var me = $(this);
      window.send_to_editor('[user meta="first_name" default="Dear Guest"]');
    });
    $(document).on("click tap", ".peprodev-ups_shortcodehandler._shortcodehandlercard",function(e){
      e.preventDefault();
      var me = $(this);
      window.send_to_editor('[profile-card-2 title="Sample Title"]\r\nSample content\r\n[/profile-card-2]');
    });
    $(document).on("click tap", ".peprodev-ups_shortcodehandler._shortcodehandlerbigcard",function(e){
      e.preventDefault();
      var me = $(this);
      window.send_to_editor('[profile-card-3 title="Sample Title" icon="fas fa-newspaper" bg_color="#fc3144" padding="1rem 1.5rem 0.5rem"]\r\nSample content\r\n[/profile-card-3]');
    });
    $(document).on("click tap", ".peprodev-ups_shortcodehandler._shortcodehandlerblackcard",function(e){
      e.preventDefault();
      var me = $(this);
      window.send_to_editor('[profile-card-4 title="Sample Title"]\r\nSample content\r\n[/profile-card-4]');
    });
    $(document).on("click tap", ".peprodev-ups_shortcodehandler._shortcodehandlerstats",function(e){
      e.preventDefault();
      var me = $(this);
      window.send_to_editor('[profile-wc-stats]');
    });
    $(document).on("click tap", ".peprodev-ups_shortcodehandler._shortcodehandlerorders",function(e){
      e.preventDefault();
      var me = $(this);
      window.send_to_editor('[profile-wc-orders limit="10"]');
    });
    $(document).on("click tap", ".peprodev-ups_shortcodehandler._shortcodehandlerdownloads",function(e){
      e.preventDefault();
      var me = $(this);
      window.send_to_editor('[profile-wc-downloads category=nID]');
    });
    $(document).on("click tap", ".peprodev-ups_shortcodehandler._shortcodehandlerldenrolled",function(e){
      e.preventDefault();
      var me = $(this);
      window.send_to_editor(
        '[profile-ld-enrolled category=nID]\r\n'+
            '\t[profile-card-3 title="خطایی رخ داد" icon="fas fa-exclamation-triangle" bg_color="#fc3144" padding="1rem 1.5rem 0.5rem"]\r\n'+
              '\t\tدوره ای یافت نشد یا شما در هیچ دوره ای ثبت نام نیستید!\r\n'+
            '\t[/profile-card-3]\r\n'+
        '[/profile-ld-enrolled]');
    });
    $(document).on("click tap", ".copy-shortcode code",function(e){
      e.preventDefault();
      var me = $(this);
      copyToClipboard(me.attr("data-copy"));
      show_toast(pepc._copy, "rgba(21, 139, 2, 0.8)");
    });
    function show_toast(data = "Sample Toast!", bg="", delay = 4500) {
    	if (!$("toast").length) {$(document.body).append($("<toast>"));}else{$("toast").removeClass("active");}
    	setTimeout(function () {
    		$("toast").css("--toast-bg", bg).html(data).stop().addClass("active").delay(delay).queue(function () {
    			$(this).removeClass("active").dequeue().off("click tap");
    		}).on("click tap", function (e) {e.preventDefault();$(this).stop().removeClass("active");});
    	}, 200);
    }
    function copyToClipboard(element) {
      var $temp = $("<textarea>");
      $("body").append($temp);
      $temp.val(element).select();
      document.execCommand("copy");
      $temp.remove();
    }

    function animate_save_btn(save=true) {
      if (save){
        var savebtn = $("button.btn.btn-primary.icn-btn[integrity][wparam][lparam]");
        if (savebtn.length > 0) {
          savebtn.find("i.material-icons").html("settings").addClass("heartbeatcss");
        }
      }
      else{
        var savebtn = $("button.btn.btn-primary.icn-btn[integrity][wparam][lparam]");
        if (savebtn.length > 0) {
          savebtn.find("i.material-icons").html("save").removeClass("heartbeatcss");
        }
      }
    }

  });
})(jQuery);

profile = function(e) {
  if(e.success === true && e.data.wparam === "profile" && e.data.lparam === "activatemodule" && e.data.dparam === "1"){
    window.location = window.location;
  }
}
