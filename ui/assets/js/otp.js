/**
 * Split OTP boxes for the PeproDev UPS code inputs.
 *
 * The plugin's own input stays in the form (name, pattern, handlers) but is
 * visually hidden; the boxes write the joined code back into it and fire
 * input/change/keyup so the plugin reacts exactly as if it was typed there.
 * Typing, deleting, arrow keys, pasting or SMS autofill into any box spread
 * the digits across the following boxes. A full code submits the form.
 */
(function ($) {
  "use strict";

  var SELECTOR = "[data-pepro-reglogin] input.otp-verification, [data-pepro-reglogin] input.code-verification";

  function toLatinDigits(value) {
    return String(value || "")
      .replace(/[۰-۹]/g, function (d) { return d.charCodeAt(0) - 0x06f0; })
      .replace(/[٠-٩]/g, function (d) { return d.charCodeAt(0) - 0x0660; })
      .replace(/\D+/g, "");
  }

  function codeLength(input) {
    var max = parseInt(input.getAttribute("maxlength"), 10);
    if (max > 0 && max <= 10) { return max; }
    var m = /\\d\{(\d+)\}/.exec(input.getAttribute("pattern") || "");
    return m ? parseInt(m[1], 10) : 0;
  }

  function build(source) {
    if (source.getAttribute("data-mj-otp")) { return; }
    source.setAttribute("data-mj-otp", "1");

    var length = codeLength(source);
    if (length < 3) { return; }
    var label = $(source).siblings("label").first().text() || "";
    var wrap = document.createElement("div");
    wrap.className = "mj-otp";
    wrap.setAttribute("dir", "ltr");
    wrap.setAttribute("role", "group");
    if (label) { wrap.setAttribute("aria-label", label); }

    var boxes = [];
    for (var i = 0; i < length; i++) {
      var box = document.createElement("input");
      box.type = "text";
      box.className = "mj-otp__box";
      box.inputMode = "numeric";
      box.setAttribute("autocomplete", i === 0 ? "one-time-code" : "off");
      box.setAttribute("aria-label", (label ? label + " " : "") + (i + 1));
      box.setAttribute("maxlength", String(length));
      box.setAttribute("dir", "ltr");
      wrap.appendChild(box);
      boxes.push(box);
    }

    source.classList.add("mj-otp-source");
    source.setAttribute("tabindex", "-1");
    source.setAttribute("aria-hidden", "true");
    source.parentNode.insertBefore(wrap, source.nextSibling);

    var syncing = false;
    var submitted = "";

    function current() {
      return boxes.map(function (b) { return b.value; }).join("");
    }

    function paint(code) {
      boxes.forEach(function (b, idx) {
        b.value = code.charAt(idx) || "";
        b.classList.toggle("is-filled", !!b.value);
      });
    }

    function commit(fromUser) {
      var code = current();
      syncing = true;
      $(source).val(code).trigger("input").trigger("change").trigger("keyup");
      syncing = false;
      wrap.classList.toggle("is-complete", code.length === length);
      if (code.length < length) { submitted = ""; }
      if (fromUser && code.length === length && code !== submitted) {
        submitted = code;
        var $form = $(source).closest("form");
        if (!$form.hasClass("loading")) {
          var btn = $form.find(".submit-wrap #submit[type=submit], .submit-wrap button[type=submit]").get(0);
          if (btn && !btn.disabled) { setTimeout(function () { btn.click(); }, 120); }
        }
      }
    }

    function fill(start, digits) {
      var idx = start;
      for (var d = 0; d < digits.length && idx < length; d++, idx++) {
        boxes[idx].value = digits.charAt(d);
        boxes[idx].classList.add("is-filled");
      }
      boxes[Math.min(idx, length - 1)].focus();
    }

    boxes.forEach(function (box, idx) {
      box.addEventListener("input", function () {
        var digits = toLatinDigits(box.value);
        if (!digits) {
          box.value = "";
          box.classList.remove("is-filled");
          commit(false);
          return;
        }
        box.value = "";
        fill(digits.length >= length ? 0 : idx, digits.length >= length ? digits.slice(0, length) : digits);
        commit(true);
      });

      box.addEventListener("paste", function (e) {
        var text = (e.clipboardData || window.clipboardData);
        text = text ? text.getData("text") : "";
        var digits = toLatinDigits(text);
        if (!digits) { return; }
        e.preventDefault();
        if (digits.length >= length) {
          paint("");
          fill(0, digits.slice(0, length));
        } else {
          fill(idx, digits);
        }
        commit(true);
      });

      box.addEventListener("keydown", function (e) {
        if (e.key === "Backspace" && !box.value && idx > 0) {
          e.preventDefault();
          boxes[idx - 1].value = "";
          boxes[idx - 1].classList.remove("is-filled");
          boxes[idx - 1].focus();
          commit(false);
        } else if (e.key === "ArrowLeft" && idx > 0) {
          e.preventDefault();
          boxes[idx - 1].focus();
        } else if (e.key === "ArrowRight" && idx < length - 1) {
          e.preventDefault();
          boxes[idx + 1].focus();
        } else if (e.key === "Enter") {
          e.preventDefault();
          commit(current().length === length);
        }
      });

      box.addEventListener("focus", function () {
        var firstEmpty = boxes.findIndex(function (b) { return !b.value; });
        if (firstEmpty !== -1 && firstEmpty < idx) { boxes[firstEmpty].focus(); return; }
        box.select();
      });
    });

    $(source).on("change.mjOtp input.mjOtp", function () {
      if (syncing) { return; }
      var code = toLatinDigits(source.value).slice(0, length);
      paint(code);
      wrap.classList.toggle("is-complete", code.length === length);
    });

    $(source).on("focus.mjOtp", function () {
      var firstEmpty = boxes.findIndex(function (b) { return !b.value; });
      boxes[firstEmpty === -1 ? length - 1 : firstEmpty].focus();
    });

    $(source).closest(".pepro-login-reg-field").on("click.mjOtp", "label", function (e) {
      e.preventDefault();
      $(source).trigger("focus");
    });

    paint(toLatinDigits(source.value).slice(0, length));
  }

  function init(root) {
    $(root || document).find(SELECTOR).each(function () { build(this); });
  }

  $(function () {
    init();
    if ("MutationObserver" in window) {
      var pending = false;
      new MutationObserver(function () {
        if (pending) { return; }
        pending = true;
        setTimeout(function () { pending = false; init(); }, 60);
      }).observe(document.body, { childList: true, subtree: true });
    }
    $(document).on("click", ".otp-changenum, .otp-resend", function () {
      setTimeout(function () {
        $(".mj-otp__box").val("").removeClass("is-filled");
        $(".mj-otp").removeClass("is-complete");
      }, 0);
    });
  });

  window.mjOtpInit = init;
})(jQuery);
