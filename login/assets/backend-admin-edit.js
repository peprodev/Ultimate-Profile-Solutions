/**
 * @Author: Amirhosseinhpv
 * @Date:   2021/07/26 08:27:28
 * @Email:  its@hpv.im
 * @Last modified by:   Amirhosseinhpv
 * @Last modified time: 2021/08/20 01:31:56
 * @License: GPLv2
 * @Copyright: Copyright © Amirhosseinhpv (https://hpv.im), all rights reserved.
 */
jQuery.noConflict();
(function($) {
  $(function() {
    $(document).on("change", ".pepro-reg-login-checkbox.edit-user", function(e) {
      e.preventDefault();
      var me = $(this);
      show_toast(pepr_reg_login.loading, 800)
      $.ajax({
        type: "POST",
        dataType: "json",
        url: pepr_reg_login._ajax,
        data: {
          action: "pepro_reglogin",
          order: "change_user_meta",
          nonce: me.data("nonce"),
          lparam: me.data("id"),
          sparam: me.data("param"),
          dparam: me.prop("checked") ? "yes" : "no",
        },
        success: function(result) {
          show_toast(result.data.msg)
        },
        error: function(result) {
          show_toast(pepr_reg_login.error)
        },
        complete: function(result) {},
      });
    });

    function show_toast(data = "Sample Toast!", delay = 1500) {
      if (!$("toast").length) {
        $(document.body).append($("<toast>"));
      }
      $("toast").html(data).stop().addClass("active").delay(delay).queue(function() {
        $(this).removeClass("active").dequeue().off("click tap");
      }).on("click tap", function(e) {
        e.preventDefault();
        $(this).stop().removeClass("active");
      });
    }
  });
})(jQuery);
