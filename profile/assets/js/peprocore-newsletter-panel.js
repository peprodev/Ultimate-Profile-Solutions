/**
 * @Author: Amirhosseinhpv
 * @Date:   2021/12/31 02:35:45
 * @Email:  its@hpv.im
 * @Last modified by:   Amirhosseinhpv
 * @Last modified time: 2021/12/31 03:09:42
 * @License: GPLv2
 * @Copyright: Copyright © Amirhosseinhpv (https://hpv.im), all rights reserved.
 */

(function($) {
  $(document).ready(function() {
    $(document).on("click tap", ".remove_notif_modal", function(e) {
      e.preventDefault();
      var me = $(this);
      let nonce = $(this).data("integrity"),
        wparam = $(this).data("wparam"),
        lparam = $(this).data("lparam"),
        dparam = $(this).data("id");
      if (confirm("Are you sure you want to delete this?\nThere's no Undo function.")) {
        $(".lds-ring2").show();
        $(`tr[data-notif-tr=${dparam}] td.td-actions *`).prop("disabled", true);
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
              $(".totaltcountspana").text(parseInt($(".totaltcountspana").text()) - 1);
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
            $.notify({
              icon: "error",
              message: peprofile.error_unknown_error
            }, {
              type: 'danger',
              timer: 100,
              delay: 4000,
              placement: {
                from: "top",
                align: "right"
              }
            });
          },
          complete: function(e) {
            $(".lds-ring,.lds-ring2").hide();
            $('#del_notifications').modal("hide");
            $("div#del_notifications *").prop("disabled", false);
          },
        });
      }
    });

    $(document).on("click tap", "#export_all", function(e) {
      e.preventDefault();
      var me = $(this);
      let nonce = $(this).data("integrity"),
        wparam = $(this).data("wparam"),
        lparam = $(this).data("lparam"),
        dparam = $(this).data("id");
      if (confirm("Are you sure you want to delete this? there's no Undo function.")) {
        $(".lds-ring2").show();
        $(`tr[data-notif-tr=${dparam}] td.td-actions *`).prop("disabled", true);
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
            $.notify({
              icon: "error",
              message: peprofile.error_unknown_error
            }, {
              type: 'danger',
              timer: 100,
              delay: 4000,
              placement: {
                from: "top",
                align: "right"
              }
            });
          },
          complete: function(e) {
            $(".lds-ring,.lds-ring2").hide();
            $('#del_notifications').modal("hide");
            $("div#del_notifications *").prop("disabled", false);
          },
        });
      }
    });

    $(document).on("click tap", "#clear_database", function(e) {
      e.preventDefault();
      var me = $(this);
      let nonce = $(this).data("integrity"),
        wparam = $(this).data("wparam"),
        lparam = $(this).data("lparam"),
        dparam = $(this).data("id");
      if (confirm("Are you sure you want to permanently remove all data from database?\nThere's no Undo function.")) {
        $(".lds-ring2").show();
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
              $(`tbody tr:not([data-notif-tr='empty'])`).remove();
              $("tr[data-notif-tr='empty']").show();
              $(".totaltcountspana").text("0")
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
            $.notify({
              icon: "error",
              message: peprofile.error_unknown_error
            }, {
              type: 'danger',
              timer: 100,
              delay: 4000,
              placement: {
                from: "top",
                align: "right"
              }
            });
          },
          complete: function(e) {
            $(".lds-ring,.lds-ring2").hide();
            $('#del_notifications').modal("hide");
            $("div#del_notifications *").prop("disabled", false);
          },
        });
      }
    });

  });
})(jQuery);
