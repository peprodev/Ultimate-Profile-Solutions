/**
 * PeproDev UPS: Alwan color picker for the admin color settings (input.pd-color-picker).
 *
 * Progressive enhancement: the text input stays the form value store (it is what the
 * settings screens save, empty = theme colors) and keeps working on its own. A swatch
 * button next to it opens a minimal Alwan popover (picker, hue slider, hex field). Picks
 * are written back to the input with 'input'/'change' events. When the library fails
 * to load, the plain input is left as it is. Self-hosted assets/vendor/alwan (v2.3.1).
 */
(function () {
  "use strict";

  function normHex(v) {
    v = (v || "").trim();
    if (v && v[0] !== "#") v = "#" + v;
    if (/^#[0-9a-fA-F]{3}$/.test(v)) v = "#" + v[1] + v[1] + v[2] + v[2] + v[3] + v[3];
    return /^#[0-9a-fA-F]{6}$/.test(v) ? v.toLowerCase() : "";
  }

  function eventHex(ev) {
    if (!ev) return "";
    if (typeof ev.hex === "string") return ev.hex;
    if (typeof ev.hex === "function") { try { return ev.hex(); } catch (e) {} }
    if (typeof ev.value === "string") return ev.value;
    return "";
  }

  function fire(input) {
    try { input.dispatchEvent(new Event("input", { bubbles: true })); } catch (e) {}
    try { input.dispatchEvent(new Event("change", { bubbles: true })); } catch (e) {}
  }

  function attach(input) {
    if (!window.Alwan || input._pdAlwanDone) return;
    input._pdAlwanDone = true;

    var start = normHex(input.value);
    var btn = document.createElement("button");
    btn.type = "button";
    btn.className = "pd-alwan-swatch";
    btn.setAttribute("aria-label", input.getAttribute("placeholder") ? "Pick a color" : "Color");
    input.insertAdjacentElement("afterend", btn);

    function paint(hex) {
      btn.style.background = hex || "";
      btn.classList.toggle("is-empty", !hex);
    }
    paint(start);

    var picker;
    try {
      picker = new Alwan(btn, {
        theme: document.body.classList.contains("dark-edition") ? "dark" : "light",
        format: "hex",
        opacity: false,
        preview: false,
        copy: false,
        swatches: [],
        inputs: { hex: true },
        color: start || normHex(input.getAttribute("placeholder")) || "#28504f",
        position: "bottom-start",
        margin: 6,
      });
    } catch (e) {
      btn.remove();
      return;
    }
    paint(start); // Alwan paints its reference with the start color; keep the "empty" look

    picker.on("color", function (ev) {
      var hex = normHex(eventHex(ev));
      if (!hex) return;
      paint(hex);
      if ((input.value || "").toLowerCase() === hex) return;
      input.value = hex;
      fire(input);
    });

    // typed values, "Theme colors" reset and imports keep the swatch and picker in sync
    function sync() {
      var hex = normHex(input.value);
      paint(hex);
      if (hex) { try { picker.setColor(hex); } catch (e) {} }
    }
    input.addEventListener("change", sync);
    input.addEventListener("input", sync);
  }

  function scan(root) {
    (root || document).querySelectorAll("input.pd-color-picker").forEach(attach);
  }
  window.PdAlwan = { scan: scan };

  document.addEventListener("click", function (e) {
    var reset = e.target.closest ? e.target.closest(".pd-color-reset") : null;
    if (!reset) return;
    e.preventDefault();
    var input = document.querySelector(reset.getAttribute("data-target"));
    if (!input) return;
    input.value = "";
    fire(input);
  });

  if ("loading" === document.readyState) document.addEventListener("DOMContentLoaded", function () { scan(document); });
  else scan(document);
})();
