/**
 * profile-ui: dashboard enhancements (password reveal/strength, avatar
 * preview state, mobile nav). The plugin re-triggers synthetic clicks on
 * every form child after saving, so interactive controls ignore events
 * that did not come from the user.
 */
(function ($) {
  "use strict";

  var cfg = window.mjProfileUi || {};
  var t = cfg.i18n || {};
  var $doc = $(document);

  function fromUser(e) {
    return !!(e.originalEvent && e.originalEvent.isTrusted !== false);
  }

  $doc.on("click", ".edit-profile-form summary, .edit-profile-form .mj-field__reveal, .edit-profile-form .mj-avatar-picker, .edit-profile-form .mj-avatar-remove", function (e) {
    if (!fromUser(e)) { e.preventDefault(); e.stopImmediatePropagation(); }
  });

  $doc.on("click", ".mj-field__reveal", function (e) {
    if (!fromUser(e)) { return; }
    var $btn = $(this);
    var $input = $("#" + $btn.data("target"));
    var show = $input.attr("type") === "password";
    $input.attr("type", show ? "text" : "password");
    $btn.attr({ "aria-pressed": show ? "true" : "false", "aria-label": show ? t.hide : t.show }).toggleClass("is-on", show);
  });

  function score(pw) {
    if (!pw) { return 0; }
    var s = 0;
    if (pw.length >= 8) { s++; }
    if (pw.length >= 12) { s++; }
    if (/[a-z]/i.test(pw) && /\d/.test(pw)) { s++; }
    if (/[^a-z0-9]/i.test(pw) || (/[a-z]/.test(pw) && /[A-Z]/.test(pw))) { s++; }
    return Math.max(1, s);
  }

  $doc.on("input", "#password_new, #password_confirm", function () {
    var $form = $(this).closest("form");
    var pw = $form.find("#password_new").val() || "";
    var confirm = $form.find("#password_confirm").val() || "";
    var $meter = $form.find(".mj-strength");
    var s = score(pw);
    var labels = ["", t.weak, t.fair, t.good, t.strong];
    $meter.prop("hidden", !pw).attr("data-score", s);
    $meter.find(".mj-strength__label").text(confirm && confirm !== pw ? t.nomatch : labels[s]);
    $meter.toggleClass("is-mismatch", !!confirm && confirm !== pw);
  });

  $doc.on("click", ".mj-avatar-remove", function (e) {
    if (!fromUser(e)) { return; }
    var $btn = $(this);
    if (!window.confirm(t.removeConfirm)) { return; }
    $btn.prop("disabled", true).addClass("is-busy");
    $.post(cfg.ajax, { action: "peprodev_ui_profile_remove_avatar", nonce: cfg.nonce })
      .done(function (res) {
        if (!res || !res.success) { window.alert(t.removeError); return; }
        $("#avatar").val("");
        $("#avatar_b, .user-icon img.avatar, .peprodev-smart-btn img.avatar").attr({ src: res.data.avatar, srcset: "" });
        $btn.closest(".mj-identity").removeClass("is-changed");
        $btn.remove();
      })
      .fail(function () { window.alert(t.removeError); })
      .always(function () { $btn.prop("disabled", false).removeClass("is-busy"); });
  });

  $doc.on("click", ".mj-addresses .mj-segment__btn", function () {
    var $btn = $(this);
    var $card = $btn.closest(".mj-addresses");
    $card.find(".mj-segment__btn").removeClass("is-active").attr("aria-selected", "false");
    $btn.addClass("is-active").attr("aria-selected", "true");
    $card.find(".mj-address-form").prop("hidden", true);
    $("#" + $btn.data("target")).prop("hidden", false);
  });

  $doc.on("submit", ".mj-address-form", function (e) {
    e.preventDefault();
    var $form = $(this);
    var $status = $form.find(".mj-address-status");
    var $submit = $form.find("button[type=submit]");
    var data = $form.serializeArray();
    data.push({ name: "action", value: "peprodev_ui_profile_save_address" }, { name: "nonce", value: cfg.addressNonce }, { name: "type", value: $form.data("type") });
    $form.find(".woocommerce-invalid").removeClass("woocommerce-invalid woocommerce-invalid-required-field");
    $status.removeClass("success error").text(t.saving);
    $submit.prop("disabled", true).addClass("is-busy");
    $.post(cfg.ajax, $.param(data))
      .done(function (res) {
        var d = (res && res.data) || {};
        $status.addClass(res && res.success ? "success" : "error").html(d.msg || t.saveError);
        $.each(d.fields || [], function (i, key) {
          $form.find("#" + key + "_field").addClass("woocommerce-invalid woocommerce-invalid-required-field");
        });
        if (d.fields && d.fields.length) { $form.find("#" + d.fields[0]).trigger("focus"); }
      })
      .fail(function () { $status.addClass("error").text(t.saveError); })
      .always(function () { $submit.prop("disabled", false).removeClass("is-busy"); });
  });

  $doc.on("change", "input#avatar[type='file']", function () {
    $(this).closest(".mj-identity").toggleClass("is-changed", !!(this.files && this.files.length));
  });

  $(function () {
    var list = document.querySelector(".profile-page-wrapper .navbar__list");
    var active = list && list.querySelector("li.active, li[data-ref].active");
    if (active && list.scrollWidth > list.clientWidth) {
      list.scrollLeft += active.getBoundingClientRect().left - list.getBoundingClientRect().left - (list.clientWidth - active.clientWidth) / 2;
    }
  });
})(jQuery);
