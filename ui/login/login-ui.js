/**
 * login-ui: tabs + UX polish for the PeproDev UPS login/register form.
 *
 * The plugin switches forms by toggling the `inline` class on
 * `form.form-login` / `form.form-register`. Tabs drive the plugin's own
 * (hidden) switch links so its handlers keep doing the real work, and a
 * MutationObserver keeps the tabs in sync with whatever the plugin does.
 *
 * When both the mobile and the email forms are enabled, the plugin prints a
 * `.switcher` (hidden here) and marks the container `via-sms-active` or
 * `via-email-active` following the admin's default. A link under the forms
 * clicks that switcher, so the whole widget (Login and Register tabs) moves
 * between the mobile (`form.via-sms`) and email (`form.via-email`) forms.
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

  /** "email" or "sms": the login method the plugin currently shows. */
  function currentVia($c) {
    return $c.hasClass("via-email-active") ? "email" : "sms";
  }

  function pick($c, type, via) {
    var $f = $c.children("form." + type + ".via-" + via).first();
    return $f.length ? $f : $c.children("form." + type).first();
  }

  function forms($c) {
    var via = currentVia($c);
    return { login: pick($c, "form-login", via), register: pick($c, "form-register", via) };
  }

  function currentMode($c) {
    var via = currentVia($c);
    if ($c.children("form.form-register.via-" + via + ".inline").length) { return "register"; }
    // "Lost password" form of the email login (no form-login / form-register class)
    if ($c.children("form.inline").not(".form-login, .form-register").filter(".via-" + via).length) { return "reset"; }
    return "login";
  }

  function isOtpStep($form) {
    var $otp = $form.find(".optverify-wrap, .verification-wrap");
    return $otp.length > 0 && $otp.is(":visible");
  }

  function switchTo($c, mode) {
    var current = currentMode($c);
    if (mode === current) { return; }
    if (current === "reset") {
      // back to the login form first, like the plugin's own "Back to Login" link
      var $back = $c.children("form.inline").not(".form-login, .form-register").find(".switch-form-login").first();
      if ($back.length) { $back.trigger("click"); } else { $c.children("form").removeClass("inline"); forms($c).login.addClass("inline"); }
      if (mode === "login") { return; }
    }
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
    // Only the main mobile inputs (login username / register user_mobile), not extra "mobile" fields of the builder.
    $c.find("input.mobile-verification#username, input.mobile-verification#user_mobile").each(function () {
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
    $c.find("input[type=email]").attr("autocomplete", "email");

    // Editing a field clears its validation message.
    $c.on("input.mjLoginUi change.mjLoginUi", ".pepro-login-reg-field :input", function () {
      var $row = $(this).closest(".pepro-login-reg-field");
      if ($row.find("error, .mj-field-error").length) {
        $row.removeClass("mj-has-error").find("error, .mj-field-error").remove();
      }
    });

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

  /**
   * The plugin marks invalid fields with an <error data-tippy-content> badge
   * (tooltip on hover); show that message as text under the field instead.
   */
  function syncErrors($c) {
    $c.find(".pepro-login-reg-field").each(function () {
      var $row = $(this);
      var $err = $row.find("error[data-tippy-content]").first();
      var $msg = $row.children(".mj-field-error");
      if (!$err.length) {
        if ($msg.length) { $msg.remove(); $row.removeClass("mj-has-error"); }
        return;
      }
      var text = $err.attr("data-tippy-content") || "";
      if ($msg.length && $msg.attr("data-text") === text) { return; }
      $msg.remove();
      // Same (admin-defined) content the plugin shows in its tooltip, rendered as HTML there too.
      $('<div class="mj-field-error" role="alert"></div>').attr("data-text", text).html(text).appendTo($row);
      $row.addClass("mj-has-error");
    });
  }

  function build($c) {
    if ($c.data("mjLoginUi")) { return; }
    $c.data("mjLoginUi", true);

    var f = forms($c);
    if (!f.login.length) { return; }

    var id = "mj-login-ui-" + (++uid);
    var hasRegister = f.register.length > 0;

    var $tabs = $('<div class="mj-tabs" role="tablist"></div>');
    var $indicator = $('<span class="mj-tabs__indicator" aria-hidden="true"></span>');
    var $login = $('<button type="button" class="mj-tabs__tab" role="tab" data-mode="login"></button>')
      .text(t.tabLogin || "Login").attr({ id: id + "-tab-login" });
    $tabs.append($indicator, $login);

    if (hasRegister) {
      var $register = $('<button type="button" class="mj-tabs__tab" role="tab" data-mode="register"></button>')
        .text(t.tabRegister || "Register").attr({ id: id + "-tab-register" });
      $tabs.append($register);
    } else {
      $tabs.addClass("mj-tabs--single");
    }

    var $head = $('<div class="mj-head"><h2 class="mj-head__title"></h2><p class="mj-head__subtitle"></p></div>');
    var $anchor = $c.children(".switcher").length ? $c.children(".switcher") : $c.children(".pepro-login-logo");
    if ($anchor.length) { $anchor.last().after($tabs, $head); } else { $c.prepend($tabs, $head); }

    // Mobile <-> email switch link: only when the admin enabled both methods.
    var $switcher = $c.children(".switcher");
    var $method = $();
    if ($switcher.find(".switch-mobile").length && $switcher.find(".switch-email").length) {
      $method = $('<div class="mj-method"><button type="button" class="mj-method__link"></button></div>');
      $c.children("form").last().after($method);
      $method.on("click", ".mj-method__link", function (e) {
        e.preventDefault();
        if ($(this).is("[aria-disabled=true]")) { return; }
        var mode = currentMode($c) === "register" ? "register" : "login";
        var target = currentVia($c) === "sms" ? "email" : "mobile";
        // The plugin's own switcher handler moves the container to the other method (login form).
        $switcher.find(".switch-" + target).first().trigger("click");
        if (mode === "register") { switchTo($c, "register"); }
        schedule();
        var $first = forms($c)[mode].find("input:visible, select:visible").first();
        if ($first.length) { $first.trigger("focus"); }
      });
    }

    $c.addClass("mj-login-ui" + (hasRegister ? " mj-has-tabs" : "") + ($method.length ? " mj-has-method" : ""));
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
      syncErrors($c);
      var mode = currentMode($c);
      var via = currentVia($c);
      var tab = mode === "register" ? "register" : "login";
      var $active = mode === "reset" ? $c.children("form.inline").first() : forms($c)[mode];
      var otp = $active.length ? isOtpStep($active) : false;
      var loading = $active.hasClass("loading");
      if (state.mode === mode && state.via === via && state.otp === otp && state.loading === loading) { return; }
      state = { mode: mode, via: via, otp: otp, loading: loading };

      $tabs.attr("data-active", tab).toggleClass("is-locked", otp || loading);
      $tabs.find(".mj-tabs__tab").each(function () {
        var $b = $(this);
        var on = $b.data("mode") === tab;
        var $panel = forms($c)[$b.data("mode")];
        $b.toggleClass("is-active", on)
          .attr({ "aria-selected": on ? "true" : "false", tabindex: on ? "0" : "-1", "aria-controls": $panel && $panel.length ? $panel.attr("id") : null })
          .attr("aria-disabled", !on && (otp || loading) ? "true" : "false");
      });
      $c.children("form").each(function () {
        var $f = $(this);
        $f.attr({ role: "tabpanel", "aria-labelledby": id + "-tab-" + ($f.hasClass("form-register") ? "register" : "login") });
      });

      if ($method.length) {
        $method.toggleClass("is-hidden", otp || loading)
          .find(".mj-method__link")
          .text(via === "sms" ? (t.switchToEmail || "Login/Register with email") : (t.switchToMobile || "Login/Register with mobile"))
          .attr("aria-disabled", otp || loading ? "true" : "false");
      }

      // Email variants have their own subtitle / OTP texts, falling back to the generic ones.
      var key = otp ? "otp" : mode;
      var suffix = via === "email" ? "Email" : "";
      $head.find(".mj-head__title").text(t[key + "Title" + suffix] || t[key + "Title"] || "");
      $head.find(".mj-head__subtitle").text(t[key + "Subtitle" + suffix] || t[key + "Subtitle"] || "");
      $c.toggleClass("mj-is-otp", otp).attr({ "data-mj-mode": mode, "data-mj-via": via });
    }

    function schedule() {
      if (pending) { return; }
      pending = true;
      (window.requestAnimationFrame || setTimeout)(sync);
    }

    if ("MutationObserver" in window) {
      new MutationObserver(schedule).observe($c[0], { subtree: true, childList: true, attributes: true, attributeFilter: ["class", "style"] });
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
