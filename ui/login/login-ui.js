/**
 * login-ui: tabs + UX polish for the PeproDev UPS login/register form.
 *
 * The plugin switches forms by toggling the `inline` class on
 * `form.form-login` / `form.form-register`. Tabs drive the plugin's own
 * (hidden) switch links so its handlers keep doing the real work, and a
 * MutationObserver keeps the tabs in sync with whatever the plugin does.
 */
(function ($) {
  "use strict";

  var cfg = window.mjLoginUi || {};
  var t = cfg.i18n || {};
  var CONTAINER = ".pepro-login-reg-container[data-pepro-reglogin]";
  var uid = 0;

  function toLatinDigits(value) {
    return String(value)
      .replace(/[۰-۹]/g, function (d) { return d.charCodeAt(0) - 0x06f0; })
      .replace(/[٠-٩]/g, function (d) { return d.charCodeAt(0) - 0x0660; });
  }

  function forms($c) {
    return {
      login: $c.children("form.form-login.via-sms").first().length ? $c.children("form.form-login.via-sms").first() : $c.children("form.form-login").first(),
      register: $c.children("form.form-register.via-sms").first().length ? $c.children("form.form-register.via-sms").first() : $c.children("form.form-register").first()
    };
  }

  function currentMode($c) {
    return $c.children("form.form-register.inline").length ? "register" : "login";
  }

  function isOtpStep($form) {
    var $otp = $form.find(".optverify-wrap, .verification-wrap");
    return $otp.length > 0 && $otp.is(":visible");
  }

  function switchTo($c, mode) {
    if (mode === currentMode($c)) { return; }
    var f = forms($c);
    var $trigger = mode === "register"
      ? f.login.find(".switch-form-register").first()
      : f.register.find(".switch-form-login").first();

    if ($trigger.length) {
      $trigger.trigger("click");
    } else {
      $c.children("form").removeClass("inline");
      f[mode].addClass("inline");
    }
  }

  function enhanceFields($c) {
    $c.find("input.mobile-verification").each(function () {
      var $i = $(this);
      $i.attr({ placeholder: t.mobilePlaceholder || $i.attr("placeholder"), inputmode: "numeric", autocomplete: "tel", dir: "ltr" });
      $i.siblings("label").first().text(t.mobileLabel || $i.siblings("label").first().text());
    });
    $c.find("input.otp-verification").each(function () {
      var $i = $(this);
      $i.attr({ placeholder: t.otpPlaceholder || "", inputmode: "numeric", autocomplete: "one-time-code", dir: "ltr" });
      $i.siblings("label").first().text(t.otpLabel || $i.siblings("label").first().text());
    });
    $c.find("input#first_name").attr("autocomplete", "given-name");
    $c.find("input#last_name").attr("autocomplete", "family-name");

    $c.on("input.mjLoginUi", "input.mobile-verification, input.otp-verification", function () {
      var latin = toLatinDigits(this.value);
      if (latin !== this.value) {
        var pos = this.selectionStart;
        this.value = latin;
        try { this.setSelectionRange(pos, pos); } catch (e) { /* tel inputs may not support selection */ }
        $(this).trigger("change");
      }
    });
  }

  function build($c) {
    if ($c.data("mjLoginUi")) { return; }
    $c.data("mjLoginUi", true);

    var f = forms($c);
    if (!f.login.length) { return; }

    var $switchMobile = $c.find(".switcher .switch-mobile");
    if ($switchMobile.length && !$c.hasClass("via-sms-active")) {
      $switchMobile.trigger("click");
    }

    var id = "mj-login-ui-" + (++uid);
    var hasRegister = f.register.length > 0;
    f.login.attr("id") || f.login.attr("id", id + "-login");
    if (hasRegister) { f.register.attr("id") || f.register.attr("id", id + "-register"); }

    var $tabs = $('<div class="mj-tabs" role="tablist"></div>');
    var $indicator = $('<span class="mj-tabs__indicator" aria-hidden="true"></span>');
    var $login = $('<button type="button" class="mj-tabs__tab" role="tab" data-mode="login"></button>')
      .text(t.tabLogin || "Login").attr({ id: id + "-tab-login", "aria-controls": f.login.attr("id") });
    $tabs.append($indicator, $login);

    if (hasRegister) {
      var $register = $('<button type="button" class="mj-tabs__tab" role="tab" data-mode="register"></button>')
        .text(t.tabRegister || "Register").attr({ id: id + "-tab-register", "aria-controls": f.register.attr("id") });
      $tabs.append($register);
    } else {
      $tabs.addClass("mj-tabs--single");
    }

    var $head = $('<div class="mj-head"><h2 class="mj-head__title"></h2><p class="mj-head__subtitle"></p></div>');
    var $anchor = $c.children(".switcher").length ? $c.children(".switcher") : $c.children(".pepro-login-logo");
    if ($anchor.length) { $anchor.last().after($tabs, $head); } else { $c.prepend($tabs, $head); }

    $c.addClass("mj-login-ui" + (hasRegister ? " mj-has-tabs" : ""));
    enhanceFields($c);

    $tabs.on("click", ".mj-tabs__tab", function () {
      if ($(this).is("[aria-disabled=true]")) { return; }
      switchTo($c, $(this).data("mode"));
      schedule();
    });

    $tabs.on("keydown", ".mj-tabs__tab", function (e) {
      var keys = { ArrowLeft: 1, ArrowRight: 1, Home: 1, End: 1 };
      if (!keys[e.key]) { return; }
      e.preventDefault();
      var $all = $tabs.find(".mj-tabs__tab").not("[aria-disabled=true]");
      var idx = $all.index(this);
      var next = e.key === "Home" ? 0 : e.key === "End" ? $all.length - 1 : (idx + 1) % $all.length;
      $all.eq(next).trigger("focus").trigger("click");
    });

    var state = {};
    var pending = false;

    function sync() {
      pending = false;
      var mode = currentMode($c);
      var $active = forms($c)[mode];
      var otp = $active.length ? isOtpStep($active) : false;
      var loading = $active.hasClass("loading");
      if (state.mode === mode && state.otp === otp && state.loading === loading) { return; }
      state = { mode: mode, otp: otp, loading: loading };

      $tabs.attr("data-active", mode).toggleClass("is-locked", otp || loading);
      $tabs.find(".mj-tabs__tab").each(function () {
        var $b = $(this);
        var on = $b.data("mode") === mode;
        $b.toggleClass("is-active", on)
          .attr({ "aria-selected": on ? "true" : "false", tabindex: on ? "0" : "-1" })
          .attr("aria-disabled", !on && (otp || loading) ? "true" : "false");
      });
      $c.children("form").each(function () {
        var $f = $(this);
        $f.attr({ role: "tabpanel", "aria-labelledby": id + "-tab-" + ($f.hasClass("form-register") ? "register" : "login") });
      });

      var key = otp ? "otp" : mode;
      $head.find(".mj-head__title").text(t[key + "Title"] || "");
      $head.find(".mj-head__subtitle").text(t[key + "Subtitle"] || "");
      $c.toggleClass("mj-is-otp", otp).attr("data-mj-mode", mode);
    }

    function schedule() {
      if (pending) { return; }
      pending = true;
      (window.requestAnimationFrame || setTimeout)(sync);
    }

    if ("MutationObserver" in window) {
      new MutationObserver(schedule).observe($c[0], { subtree: true, attributes: true, attributeFilter: ["class", "style"] });
    }
    sync();
  }

  function init(root) {
    $(root || document).find(CONTAINER).each(function () { build($(this)); });
  }

  $(function () {
    init();
    $(document.body).on("pepro_login_form_loaded mj_login_ui_init", function () { init(); });
  });
})(jQuery);
