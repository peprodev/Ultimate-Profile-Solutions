/**
 * @Last modified by:   Amirhosseinhpv
 * @Last modified time: 2022/01/18 01:03:42
 */


(function($) {
  $(document).ready(function() {
    $(document.body).append($("<toast>"));
    $(".wrapper").show();
    $(document).on("click tap", ".copyhwnd", function(e){
      e.preventDefault();
      var me = $(this);
      copy_clipboard($(me.data("copy")).text().replace(/\r/gi,""));
      show_toast(pepc._copy, "rgba(21, 139, 2, 0.8)");
    });
    $(document).on("click tap", ".copyme", function(e){
      e.preventDefault();
      copy_clipboard($(this).data("copy"));
      show_toast(pepc._copy, "rgba(21, 139, 2, 0.8)");
    });
    $(document).on("click tap", "copy", function(e){
      e.preventDefault();
      var me = $(this);
      copy_clipboard(me.text());
      show_toast(pepc._copy, "rgba(21, 139, 2, 0.8)");
    });

    function copy_clipboard(data) {
      var $temp = $("<textarea>");
      $("body").append($temp);
      $temp.val(data).select();
      document.execCommand("copy");
      $temp.remove();
    }
    function show_toast(data = "Sample Toast!", bg="", delay = 4500) {
    	if (!$("toast").length) {$(document.body).append($("<toast>"));}else{$("toast").removeClass("active");}
    	setTimeout(function () {
    		$("toast").css("--toast-bg", bg).html(data).stop().addClass("active").delay(delay).queue(function () {
    			$(this).removeClass("active").dequeue().off("click tap");
    		}).on("click tap", function (e) {e.preventDefault();$(this).stop().removeClass("active");});
    	}, 200);
    }
    $(window).on("load", function() {
      $("peploader").hide();
      var $_railalign = $("body").is(".rtl") ? "left":"right";
      $("body").attr("data-color", $(".wrapper[data-color]").attr("data-color"));
    });
    $(document).on("click tap", "textarea", function (e) {
    });
    $("a.btncheckbox[data-checked]").each(function(i, x) {
      let me = $(this),
        icnoff = me.data("off"),
        icnon = me.data("on"),
        togglel = me.data("togglel"),
        txton = me.data("text-on"),
        txtoff = me.data("text-off");
      if (me.attr("data-checked") === "true") {
        $(`${togglel}`).show();
        me.html(`<i class='material-icons'>${icnon}</i> ${txton}`);
      } else if (me.attr("data-checked") !== "true") {
        $(`${togglel}`).hide();
        me.html(`<i class='material-icons'>${icnoff}</i> ${txtoff}`);
      }
    });
    $(".wrapper select").each(function(i, x) {
      let ff = $(this);
      let ffs = ff.find("option:selected").first();
      if(ffs.attr("data-verified") === "true"){ff.addClass("vs");}
      let filter = ff.attr("data-filter");
      if (filter && "" != filter) {
        $(`.wrapper [${filter}]`).hide();
      }
      let ffss = ff.find("option:selected").first();
      $(`.wrapper [${filter}=${ffss.attr("filter-item")}]`).show();
    });
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
    $(document).on("click tap", ".pepjax", function(e) {
      e.preventDefault();
      var me = $(this);
      let nonce = me.attr('integrity'),
        wparam = me.attr('wparam'),
        lparam = me.attr('lparam'),
        fnhere = me.attr('fn'),
        dparam = me.attr('dparam');
      var datareturned = "";
      $(document).trigger("peproajax_start");

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
          dparam: dparam,
        },
        success: function(data) {
          if (e.success === true) {
            notify.update({icon: "thumb_up",message: e.data.msg, type: 'success'});
            $(document).trigger("peproajax_end");
            eval(`${fnhere}(data,me);`);
          }else{
            $(document).trigger("peproajax_err");
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
    $(document).on("click tap", ".btncheckbox", function(e) {
      e.preventDefault();
      let me = $(this),
        togglel = me.data("togglel"),
        icnoff = me.data("off"),
        icnon = me.data("on"),
        txton = me.data("text-on"),
        txtoff = me.data("text-off");
      if (me.attr("data-checked") === "true") {
        $(`${togglel}`).hide();
        me.html(`<i class='material-icons'>${icnoff}</i> ${txtoff}`);
        me.attr("data-checked", "false");
      } else {
        $(`${togglel}`).show();
        me.html(`<i class='material-icons'>${icnon}</i> ${txton}`);
        me.attr("data-checked", "true");
      }
    });
    $(document).on("click tap change", "#pepc-settings-theme-scheme", function(e) {
      e.preventDefault();
      ($(this).attr("data-checked") === "true") ? $("body").addClass("dark-edition") : $("body").removeClass("dark-edition");
    });
    $(document).on("click tap change", "#pepc-settings-theme-color", function(e) {
      e.preventDefault();
      $("[data-color]").attr("data-color",$(this).val());
    });
    $(document).on("click tap change", "#pepc-settings-theme-sidebar-image", function(e) {
      e.preventDefault();
      let img = $(this).val();

      if (img === "custom"){
        img = $("#pepc-settings-theme-sidebar-image-custom").val();
        $("#pepc-settings-load-sidebar-image").prop("disabled",false).removeClass("disabled");
      }else{
        $("#pepc-settings-load-sidebar-image").prop("disabled",true).addClass("disabled");
      }
      $("#sidebar--img").attr("src",`${img}`);
      $(".sidebar[data-image]").attr("data-image",`${img}`);
      $(".sidebar .sidebar-background").css("background-image",`url("${img}")`);

    });
    $(document).on("click tap", "#pepc-settings-save", function(e) {
      e.preventDefault();
      datatosave = {
        "theme-scheme":$("#pepc-settings-theme-scheme").attr("data-checked") === "true" ? "dark-edition" : "light-edition",
        "theme-color":$("#pepc-settings-theme-color").val(),
        "sidebar-image":$("#pepc-settings-theme-sidebar-image").val(),
        "sidebar-image-custom":$("#pepc-settings-theme-sidebar-image-custom").val(),
        "sidebar-image-custom-id":$("#pepc-settings-theme-sidebar-image-custom").attr("data-id"),
        "dashboard-title":$("#pepc-settings-dashboard-title").val(),
      };
      $("#pepc-settings-dashboard-title").attr("data-orig",$("#pepc-settings-dashboard-title").val());
      var me = $(this);
      let nonce = me.attr('integrity'),wparam=me.attr('wparam'),lparam=me.attr('lparam'),fnhere=me.attr('fn'),dparam=datatosave,datareturned="";
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
          dparam: dparam,
        },
        success: function(e) {
          if (e.success === true) {
            notify.update({icon: "thumb_up",message: e.data.msg, type: 'success'});
          }else{
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
    $(document).on("change load keyup click tap", "select", function(e) {
      e.preventDefault();
      let ff = $(this);
      ff.removeClass("vs");
      let ffs = ff.find("option:selected").first();
      if(ffs.attr("data-verified") === "true"){ff.addClass("vs");}
    });
    $(document).on("keyup", "[data-textupdatelemnt]", function(e) {
      e.preventDefault();
      if ($(this).val() !== ""){
        $(`${$(this).attr("data-textupdatelemnt")}`).html($(this).val());
      }else{
        $(`${$(this).attr("data-textupdatelemnt")}`).html($(this).attr("data-orig"));
      }
    });
    $(document).on("click tap change", "select.filteritem", function(e) {
      e.preventDefault();
      let ff = $(this);
      let filter = ff.attr("data-filter");
      $(`[${filter}]`).hide();
      let ffs = ff.find("option:selected").first();
      $(`[${filter}=${ffs.attr("filter-item")}]`).show();
    });
    $(document).on("click tap", ".mediapicker", function(e) {
      e.preventDefault();
      var image_frame, me = $(this);
      if (image_frame) {image_frame.open();}
      mediapickertype = me.attr("data-picktype") || 'image';
      image_frame = wp.media({title: me.attr("data-title"),multiple: false,library: {type: mediapickertype,}});
      image_frame.on('close', function() {
        if (image_frame.state().get('selection').first()) {
          var selection = image_frame.state().get('selection').first().toJSON();
          $(`${me.attr("data-ref")}`).val(selection.url);
          $(`${me.attr("data-ref")}`).attr("data-id",selection.id);
          $(`${me.attr("data-ref2")}`).attr("data-image",`${selection.url}`);
          $(`${me.attr("data-ref3")}`).css("background-image",`url("${selection.url}")`);
          $(`${me.attr("data-ref4")}`).attr("src",`${selection.url}`);
        }
      });
      image_frame.on('open', function() {
        var selection = image_frame.state().get('selection');
        var id = $(`${me.attr("data-ref")}`).attr("data-id");
        var attachment = wp.media.attachment(id);
        attachment.fetch();
        selection.add(attachment ? [attachment] : []);
      });
      image_frame.open();
    });
  });
})(jQuery);
