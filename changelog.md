# Changelog
Here is a full changelog of [PeproDev Ultimate Profile](https://github.com/peprodev/Ultimate-Profile-Solutions).

Developed by [Pepro Development Group](https://pepro.dev/), Lead Developer: [Amirhp.Com](https://amirhp.com/landing)

## Table of Contents
<details open>
<summary><strong>Version 8.x.x</strong></summary>
&nbsp;

- [Version 8.2.33](#version-8233)
- [Version 8.2.32](#version-8232)
- [Version 8.2.31](#version-8231)
- [Version 8.2.30](#version-8230)
- [Version 8.2.29](#version-8229)
- [Version 8.2.28](#version-8228)
- [Version 8.2.27](#version-8227)
- [Version 8.2.26](#version-8226)
- [Version 8.2.25](#version-8225)
- [Version 8.2.24](#version-8224)
- [Version 8.2.23](#version-8223)
- [Version 8.2.22](#version-8222)
- [Version 8.2.21](#version-8221)
- [Version 8.2.20](#version-8220)
- [Version 8.2.19](#version-8219)
- [Version 8.2.18](#version-8218)
- [Version 8.2.17](#version-8217)
- [Version 8.2.16](#version-8216)
- [Version 8.2.15](#version-8215)
- [Version 8.2.14](#version-8214)
- [Version 8.2.13](#version-8213)
- [Version 8.2.12](#version-8212)
- [Version 8.2.11](#version-8211)
- [Version 8.2.10](#version-8210)
- [Version 8.2.9](#version-829)
- [Version 8.2.8](#version-828)
- [Version 8.2.7](#version-827)
- [Version 8.2.6](#version-826)
- [Version 8.2.5](#version-825)
- [Version 8.1.0](#version-810)
- [Version 8.0.4](#version-804)
- [Version 8.0.3](#version-803)
- [Version 8.0.2](#version-802)
- [Version 8.0.1](#version-801)
- [Version 8.0.0](#version-800)

</details>

<details>

  <summary><strong>Version 7.x.x</strong></summary>
  &nbsp;

  - [Version 7.5.1](#version-751)
  - [Version 7.5.0](#version-750)
  - [Version 7.4.9](#version-749)
  - [Version 7.4.8](#version-748)
  - [Version 7.4.7](#version-747)
  - [Version 7.4.6](#version-746)
  - [Version 7.4.5](#version-745)
  - [Version 7.4.4](#version-744)
  - [Version 7.4.3](#version-743)
  - [Version 7.4.0](#version-740)
  - [Version 7.3.3](#version-733)
  - [Version 7.3.0](#version-730)
  - [Version 7.2.4](#version-724)
  - [Version 7.1.9](#version-719)
  - [Version 7.1.8](#version-718)
  - [Version 7.1.7](#version-717)
  - [Version 7.1.6](#version-716)
  - [Version 7.1.5](#version-715)
  - [Version 7.1.2](#version-712)
  - [Version 7.0.6](#version-706)

</details>


<details>
  <summary>Older Versions</summary>
  &nbsp;

  - [Notice](#notice)
  - [Version 2.5.0](#version-250)
  - [Version 2.4.4](#version-244)
  - [Version 2.4.0](#version-240)
  - [Version 2.3.6](#version-236)
  - [Version 2.3.5](#version-235)
  - [Version 2.3.4](#version-234)
  - [Version 2.3.3](#version-233)
  - [Version 2.3.0](#version-230)
  - [Version 1.9.2](#version-192)
  - [Version 1.9.1](#version-191)
  - [Version 1.8.9](#version-189)
  - [Version 1.8.7](#version-187)
  - [Version 1.8.6](#version-186)
  - [Version 1.8.5](#version-185)
  - [Version 1.8.2](#version-182)
  - [Version 1.0.0](#version-100)
  - [Version 0.0.1](#version-001)
</details>

---

## Version 8.2.33
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **New** Font sizes on the "Modern UI Design" screen (in px): dashboard text, page titles, login form title, card titles, descriptions, buttons, tabs, sidebar menu, table text and header, field labels, field text and login form links; the stylesheets read them as `--mj-fs-*` custom properties with the built-in sizes as fallback

## Version 8.2.32
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **New** "Modern UI Design" admin screen: colors of the modern login form and dashboard in one place (accent, titles, cards; button background/text and hover; tab background, text and active tab; links and hover; sidebar background, items, hover, active item and marker; table header, text, borders and row hover; field labels, background, border and focus). Empty fields keep the built-in design or the theme's tokens; the stylesheets read the new `--mj-*` custom properties with fallbacks. The accent color moved here from Login/Register settings

## Version 8.2.31
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Fixed** Modern dashboard: the Verify Email / Verify Mobile tabs use the same segmented style as the address tabs, and the verification card title follows the forms shown (email, mobile or both) instead of always saying "Verify your mobile number"

## Version 8.2.30
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Improved** Profile settings: the address of the selected dashboard page is shown under the page select and follows the selection. **Fixed** the dashboard page is no longer created again when the plugin setting was lost: the saved page, or an existing page with the `[pepro-profile]` shortcode, is reused before a new "User Dashboard" page is made

## Version 8.2.29
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Security** CVE-2026-4791 (CVSS 6.4): stored XSS by Contributor+ through `[logout-url button=""]`; fixed, plus a review of every shortcode for the same issue: `[pepro-login-popup]` / `[pepro-login-form]` (`button`, `extras`, `before`, `after`, `before_popup`, `after_popup`, `trigger`), `[pepro-smart-btn]` (texts, `trigger`, `{field}` placeholders), `[pepro-profile-url]` (`button`, `extras`, `section`, content), `[user]` (`default`; `meta` limited to public profile fields, it could print the viewer's password hash, OTP codes or contact data), `[profile-card-1..4]` (`style`, `padding`, `bg_color`, content no longer unescaped with stripcslashes), `[profile-ld-enrolled]` (`user_id` of others only for users who can list users), `[profile-wc-orders]` (`limit`), `[pepro-sms-subscription]`, `[current_url]`. The front-end script queries `trigger` as a selector only. New filter `peprodev_ups_public_user_fields`

## Version 8.2.28
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **New** Modern UI "Button accent color" setting (Login/Register > Login & Registration and Profile > Modern UI) with the Alwan color picker; sets the buttons, focus rings and links of the modern login form and dashboard (text color on the buttons is chosen for contrast). Empty keeps the theme colors

## Version 8.2.27
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Fixed** OTP "Resend code" timer: it only started for one exact response shape, never for the "one code every N seconds" reply, and a 0 timer left the "(60)" label forever. The timer now starts for every response that shows the resend link, shows the remaining time right away, restarts cleanly on each send, shows "Resend OTP Code" when the code can be requested again, and ignores clicks while counting down

## Version 8.2.26
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Fixed** FarazSMS: a sent code could be reported as "not sent" (the API success reply carries an empty message, which the forms read as a failure, so the error and no resend timer were shown). A sent message now always returns a success value; status is compared case-insensitively and a 2xx reply without JSON counts as sent

## Version 8.2.25
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Changed** FarazSMS "Create an OTP pattern" moved out of the settings rows into its own section below them, with a short explanation of the review step

## Version 8.2.24
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Improved** FarazSMS settings: grouped into "Account" and "Sending the code"; the Api-Key has a plain "Get the Api-Key from your FarazSMS panel" link instead of a pill button, and the balance is a status box with an inline reload icon

## Version 8.2.23
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Fixed** Additional registration fields and redirect rules: the duplicate / move / delete buttons overflowed out of the box; they are now compact icon buttons inside the title bar (with tooltips, delete highlighted in red) and long titles are truncated

## Version 8.2.22
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Fixed** Login/Register settings loaded an old cached register.css / register.js after updates (their version used the login module's fixed 8.0.0 instead of the plugin version), so the new layouts, Code/Preview tabs, macro chips and buttons appeared unstyled or did not work. Assets now use the plugin version; the subject "Restore default" button uses the info style

## Version 8.2.21
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Improved** Registration Fields tab: the default fields panel is wider and shows one clean row per field (toggle and name, Required checkbox under a column header, no inner scrollbar, short help); the additional fields toolbar keeps its buttons on one line

## Version 8.2.20
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Changed** All on/off settings that used an eye (show/hide) icon are drawn as toggle switches with the state text beside them, on every plugin admin page (same colors as the other toggles, keyboard accessible). Saved values are unchanged

## Version 8.2.19
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Improved** WordPress Built-in Login settings grouped into Appearance, Logo, Form elements and Background with short explanations; "WordPress Style" renamed to "Use only the theme styles" with a clear on/off description; logo inputs use the same style as the other fields

## Version 8.2.18
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Improved** Redirection rules: the macros ({home}, {profile}, {profile_edit}, {admin}, {profile}?section=courses, #page_id, @page_slug, https://) are shown as chips under "Redirect to" with their description as tooltip; clicking a chip fills the field. The help popup is removed

## Version 8.2.17
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **New** Verification email template editor has Code / Preview tabs; the preview is rendered by the server like the real email (sample code, your account data, unsaved changes included) and shows the resulting subject

## Version 8.2.16
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Changed** Default verification email subject is now "[site_name] | Verify Email" and is filled into the field; new "Restore default" button next to the subject puts the default back. "Reset to default" of the template also restores this subject

## Version 8.2.15
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Improved** FarazSMS (IranPayamak): new "Sending method of the code" setting, Pattern (template) or Normal SMS (message text); only the fields of the chosen method are shown. Existing sites keep their behaviour (pattern when a pattern code is saved). Pattern list shows the code first with the description kept in order, and LTR select values no longer run under the RTL arrow

## Version 8.2.14
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Fixed** "Send a Test SMS" / "Send test email" buttons cut their label; the address field now grows and the button keeps its full text

## Version 8.2.13
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Changed** Login & Registration tab shows its settings in two columns (registration type / verification form / register form, and login form options / extras / Auth. Expiration / Modern UI); registration fields (default and additional) moved to a new full-width "Registration Fields" tab

## Version 8.2.12
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Improved** Smart Button tab: six ready-to-copy samples in two columns (each with its own Copy button and a short explanation), a full attribute reference with defaults, related shortcodes ([loggedin], [guest], [current_url], [logout-url], [pepro-login-popup], [pepro-login-form], [verified-mobile], [verified-email]) and tips

## Version 8.2.11
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Changed** Redirection settings tab uses the full page width

## Version 8.2.10
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Changed** "Auth. Expiration" (how long users stay logged in) moved from the SMS verification tab to the Login & Registration settings, under Extras

## Version 8.2.9
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Changed** Login/Register settings: SMS and Email verification have their own tabs (SMS: provider, code, test and gateway settings side by side; Email: sender settings next to subject/template editor)

## Version 8.2.8
- Release date: 2026-09-29 | 1405-07-07  [&uarr;](#table-of-contents)
- **Fixed** FarazSMS (IranPayamak): API errors show the reason sent in the `messages` field (e.g. HTTP 422 validation errors) instead of a generic "request failed (HTTP 422)"; sender number is normalized to digits and required before sending

## Version 8.2.7
- Release date: 2026-09-27 | 1405-07-05  [&uarr;](#table-of-contents)
- **Fixed** Persian translations lost in 8.1/8.2 (strings using the `menu` context and `$this->td`, e.g. dashboard menu Dashboard / Edit Profile / Logout, WooCommerce order texts, pagination) restored from 8.0.4

## Version 8.2.6
- Release date: 2026-09-27 | 1405-07-05  [&uarr;](#table-of-contents)
- **Fixed** Modern login: headings and the mobile/email switch link follow the fields the active form really has. With "Force Mobile/Email Registration form" both register forms use the same method, so the switch link is hidden on the Register tab and the heading matches the form

## Version 8.2.5
- Release date: 2026-09-27 | 1405-07-05  [&uarr;](#table-of-contents)
- Includes the development versions 8.2.0 to 8.2.4 (one commit each in the repository).
- **Changed** (8.2.0) Modern UI **on by default**, also on existing sites that never saved the setting; a saved "off" or the `PEPRODEV_UPS_UI_LOGIN` / `PEPRODEV_UPS_UI_DASHBOARD` constants still turn it off
- **Changed** (8.2.0) The "Dashboard Texts" page was merged into the existing screens: modern login switch in Login/Register > Login & Registration, modern dashboard switch, learning button and "My courses" texts in Profile. Values are stored in the `modern_ui` key of `peprodev_ups_profile`; the 8.1 option `peprodev_ups_ui_texts` is migrated once (and left in place); the old page URL redirects to the Profile screen. `peprodev_ui_text()`, `peprodev_ui_text_on()` and `peprodev_ui_text_url()` keep working
- **New** (8.2.1) Modern login: "Login/Register with email / with mobile" link switches the Login and Register tabs between the mobile and email forms when both are enabled (reusing the plugin's switcher); the first method follows "Make Mobile Login/Registration Activated by Default"; email-specific heading and OTP texts; the Lost password view works with the tabs
- **Fixed** (8.2.1) Modern UI showed the email login form under the mobile one
- **Improved** (8.2.2) Registration fields in the modern register forms: textarea, number, email, date, select, checkbox, TinyMCE editor, WooCommerce country/state/city and reCAPTCHA styled; first/last name side by side; required markers; error messages under the invalid fields
- **Fixed** (8.2.2) reCAPTCHA script not loaded for the register forms when "Use in Login form" was off (registration was blocked); WooCommerce country/state/city always required and not saved to new users; extra builder "mobile" fields relabelled as the main mobile field
- **New** (8.2.3) SMS gateway FarazSMS / IranPayamak (`farazsms`): pattern OTP or message template, pattern list, balance and "Create pattern" helpers
- **New** (8.2.3) SMS gateways for the WP SMS plugin (`wpsms`, `wp_sms_send()`, optional template id) and the Persian WooCommerce SMS plugin (`pwsms`, `PWSMS()->send_sms()`, pattern blocks); both show a notice and never send while the host plugin is inactive
- **Improved** (8.2.3) "Send a Test SMS" reports why a send failed; legacy ippanel.com gateways labelled "IPPanel / FarazSMS (legacy API)" (ids and settings unchanged)
- **New** (8.2.4) Sign in with Google (Login/Register > Social Login): OAuth 2.0 / OpenID Connect via `wp_remote_*`, state in a transient bound to a cookie, OIDC nonce, ID token and verified-email checks; existing users log in, new users are created only when registration is open and allowed; plugin redirect rules; button under the login/register forms (classic and modern), on wp-login.php and `[pepro-google-login]`
- **Developers** New hooks: `pepro_reglogin_form_top`, `pepro_reglogin_form_bottom`, `pepro_reglogin_settings_tabs_nav`, `pepro_reglogin_settings_tabs_content`, `pepro_reglogin_save_settings`, `pepro_reglogin_social_login`, `pepro_reglogin_sms_last_error`
- **Translations** Persian (fa_IR) strings for all new texts

## Version 8.1.0
- Release date: 2026-09-27 | 1405-07-05  [&uarr;](#table-of-contents)
- Includes all changes of the unreleased 8.0.5 development version.
- **New** Optional Modern UI, **off by default** (PeproDev Profile > Dashboard Texts > Modern UI, or the `PEPRODEV_UPS_UI_LOGIN` / `PEPRODEV_UPS_UI_DASHBOARD` constants): modern login/register form with Login/Register tabs and OTP code boxes (paste, autofill, auto-submit); modern user dashboard with a new Edit profile view (inline AJAX WooCommerce addresses, avatar removal, password strength), redesigned order and course views
- **New** `[peprodev_learning_button]` and `[peprodev_my_courses layout="home|full"]` shortcodes (LearnDash), `mj-has-courses` / `mj-no-courses` body classes, and a "Dashboard Texts" settings page for their texts and links (placeholders `{my_courses}`, `{courses_page}`, `{profile}`, `{shop}`, `{home}`, and `{continue_learning}` which requires PeproDev WP Tweaker)
- **New** WPML String Translation and Polylang support for admin-defined front-end texts: registration field labels/placeholders/error texts/options, redirection URLs and popup button texts, login header/footer HTML, wp-login logo title/link, verification email template/subject/sender name, SMS templates, dashboard title and custom HTML, custom dashboard sections; `wpml-config.xml` and "Translate texts with WPML" links
- **New** Configurable verification email subject (`[OTP]`, `[site_name]`, `[request_email]`, `[first_name]`, `[last_name]`, `[display_name]`, `[username]`); empty = translatable built-in default
- **New** Modern default verification email templates (`login/assets/mail-template-default.html`, Persian: `mail-template-default-fa_IR.html`) with `[site_name]`, `[site_logo]`, `[expire_minutes]`, `[year]` tags, plus "Reset to default" and "Send test email" buttons. Stored templates are kept unchanged (opt in to upgrading an untouched pre-8.1 default with the `pepro_reglogin_upgrade_legacy_mail_template` filter)
- **Improved** OTP emails: plain-text alternative part, code at the start of the subject and body, auto-generated headers, sanitized From header (a bare local part such as `noreply` is completed with the site host)
- **Changed** Redirect after login/register: validated explicit `redirect_to` first, then the matching redirection rule (login and register rules are evaluated separately), otherwise the profile dashboard; filter `pepro_reglogin_default_login_redirect` to change the fallback
- **Fixed** OTP registration when auto-login after registration is disabled
- **Fixed** Login/register success popup now always performs the redirect
- **Fixed** PNG avatar uploads
- **Fixed** Profile settings custom content (wp_editor) not saved while on the Text tab
- **Fixed** Profile logo setting (preview, remove button); logo shown at the top of the front-end login/register form; wp-login logo can be removed
- **Security** Hardened AJAX actions and admin tools (capability and nonce checks), stricter validation of profile and user-meta updates, redirect validation, OTP send/verify rate limiting and stronger code generation, more output escaping, direct-access guards on PHP files
- **Performance** moment.js/moment-timezone no longer loaded with the login form, no duplicate stylesheet downloads, country/city data printed once per page, stable asset versions instead of `time()`, lighter queries on `init`
- **Compatibility** Fallbacks for WordPress versions before 5.3 (`wp_date`, `wp_timezone_string`); tested with WordPress 7.1; PHP 7.2+
- **Developers** New filters: `peprodev_ui_module_enabled`, `peprodev_ui_load_global_css`, `peprodev_ui_texts_fields`, `peprodev_ui_text`, `peprodev_ui_text_url`, `peprodev_ui_courses_page_url`, `peprodev_ui_course_url`, `peprodev_ui_continue_url`, `peprodev_ui_login_strings`, `peprodev_ui_dashboard_strings`, `peprodev_ui_dashboard_template_map`, `peprodev_ui_dashboard_is_profile_page`, `peprodev_ui_dashboard_dequeue_font_awesome`, `pepro_reglogin_form_redirect_to`, `pepro_reglogin_default_login_redirect`, `pepro_reglogin_otp_send_limits`, `pepro_reglogin_otp_max_verify_attempts`, `pepro_reglogin_verification_email_subject`, `pepro_reglogin_default_mail_template`, `pepro_reglogin_upgrade_legacy_mail_template`, `peprodev-ups/wpml/strings`

## Version 8.0.4
- Release date: 2025-05-31 | 1404-03-10  [&uarr;](#table-of-contents)
- **Fixed** Removed setting upon upgrade

## Version 8.0.3
- Release date: 2025-05-31 | 1404-03-10  [&uarr;](#table-of-contents)
- **Fixed** some Backend Setting UI issues
- **Fixed** Custom text on dashboard not rendered properly

## Version 8.0.2
- Release date: 2025-05-28 | 1404-03-07  [&uarr;](#table-of-contents)
- **Fixed** issue with login page style not showing correctly

## Version 8.0.1
- Release date: 2025-05-28 | 1404-03-07  [&uarr;](#table-of-contents)
- **Fixed** issue with login slug not functioning correctly

## Version 8.0.0
- Release date: 2025-05-27 | 1404-03-06  [&uarr;](#table-of-contents)
- 🛡️ Resolved security vulnerabilities detected by Wordfence
- 🔧 Major core update with enhanced architecture
- 🚀 Improved performance and loading efficiency
- 🗃️ Refactored and optimized database structure
- ⚙️ Added unified settings panel for easier configuration
- 🧹 Removed redundant database entries from wp-options
- 🔄 Improved upgrade process with data migration and backward compatibility
- 📝 Added option to edit and verify user email and SMS on Edit User screen
- 🧩 Unified settings slug with consistent read/write functions and hooks
- 🐞 Fixed LearnDash issue with incorrect date display

## Version 7.5.1
- Release date: 2025-04-06 | 1404-01-17  [&uarr;](#table-of-contents)
- **Fixed** User mobile field not shown on Edit User Screen
- **Added** Verify User Email and Mobile on Edit User Screen

## Version 7.5.0
- Release date: 2025-02-18 | 1403-11-30  [&uarr;](#table-of-contents)
- **Fixed** Learndash Integration > Course Section Enhanced

## Version 7.4.9
- Release date: 2025-02-11 | 1403-11-23  [&uarr;](#table-of-contents)
- **Fixed** Elementor Pro 3.27.x Compatibility

## Version 7.4.8
- Release date: 2024-11-24 | 1403-09-04  [&uarr;](#table-of-contents)
- **Fixed** Fatal Error get_current_screen on ACF pages

## Version 7.4.7
- Release date: 2024-11-18 | 1403-08-28  [&uarr;](#table-of-contents)
- **Fixed** Translation load
- **Fixed** Database Creation issue
- **Fixed** WooCommerce Not Activated Issue
- **Fixed** Redirection to URL with #hash appended
- **Fixed** Redirection to Same page on Login/Registeration

## Version 7.4.6
- Release date: 06 October 2024 | 1403-07-15  [&uarr;](#table-of-contents)
- **Added** Set URL as section slug, to make it external link

## Version 7.4.5
- Release date: 06 October 2024 | 1403-07-15  [&uarr;](#table-of-contents)
- **Fixed** Change Email button on Registeration not worked

## Version 7.4.4
- Release date: 03 October 2024 | 1403-07-12  [&uarr;](#table-of-contents)
- **Fixed** SMS.ir API v2 guiding links to new sms.ir panel
- **Fixed** Function is_single was called incorrectly
- **Fixed** Shortcode not returned content if user was Logged-in

## Version 7.4.3
- Release date: 20 September 2024 | 1403-06-30  [&uarr;](#table-of-contents)
- **Fixed** SMS.ir API v2 guiding links to new sms.ir panel
- **Fixed** Function is_single was called incorrectly
- **Fixed** Shortcode not returned content if user was Logged-in

## Version 7.4.0
- Release date: 21 August 2024 | 1403-05-31  [&uarr;](#table-of-contents)
- **Added**: Option to change login/registration flow.
- **Added**: Option to change login/registration active tab.
- **Added**: Option to change login/registration forms type.
- **Added**: Option to change registration method.
- **Added**: Option to force the same registration method for both types.
- **Added**: Option to change verification flow.
- **Added**: Option to set section slug to @page_slug / #page_id.
- **Fixed**: Registration form not showing correct fields on changing email/mobile.
- **Fixed**: User redirection after login to the wrong address.
- **Fixed**: Popup login/registration form.
- **Fixed**: Not enqueuing reCaptcha if not used on login/registration form.
- **Development**: Cached form styles to avoid layout breaks.
- **Development**: Fixed database generating on page load.
- **Development**: Fixed options auto-load to "no."
- **Development**: Automatically disable cache for profile page.
- **Development**: Automatically set profile page as no-index & no-follow.
- **Development**: Added compatibility with WP Rocket.
- **Development**: Added compatibility with Yoast SEO.
- **Updated**: Translation.

## Version 7.3.3
- Release date: 17 August 2024 | 1403-05-27  [&uarr;](#table-of-contents)
- **Fixed**: User access to orders.
- **Fixed**: Redirect to current page not working.
- **Added**: Support for `[pepro-profile redirect_to="$url"]` and other login shortcode attributes.

## Version 7.3.0
- Release date: 12 August 2024 | 1403-05-22  [&uarr;](#table-of-contents)
- **Security**: Major security and performance enhancement.
- **Added**: Compatibility with Ticketing plugin.
- **Removed**: Old redundant options from wp_options.
- **Changed**: Better options handling with non-autoloading them.
- **Changed**: View order default URL to point to profile.
- **Fixed**: Applied notification and announcement UI fixes.
- **Development**: Added `peprodev/profile/helper/add_private_notification` hook for other plugins to create personal and global notifications.

## Version 7.2.4
- Release date: 07 August 2024 | 1403-05-17  [&uarr;](#table-of-contents)
- **Fixed**: Layout of user notification chat.
- **Fixed**: Layout of user announcement chat.
- **Changed**: Notification/Announcement icon.
- **Fixed**: Some CSS issues.
- **Changed**: Make GUEST OTP records non-autoload.
- **Development**: Added current section name to page wrapper class list.

## Version 7.1.9
- Release date: 03 July 2024 | 1403-04-13  [&uarr;](#table-of-contents)
- **Fixed**: Layout break of login page while loading.
- **Fixed**: Showing back "Register" link when the user is not registered.

## Version 7.1.8
- Release date: 25 June 2024 | 1403-04-05  [&uarr;](#table-of-contents)
- **Fixed**: Failed to send OTP if username is the same as mobile and the user is found by username rather than mobile.

## Version 7.1.7
- Release date: 21 June 2024 | 1403-04-01  [&uarr;](#table-of-contents)
- **Fixed**: Login shortcode layout break before full page load.

## Version 7.1.6
- Release date: 21 June 2024 | 1403-04-01  [&uarr;](#table-of-contents)
- **Fixed**: `[pepro-profile]` shortcode breaks Elementor layout.

## Version 7.1.5
- Release date: 16 June 2024 | 1403-03-27  [&uarr;](#table-of-contents)
- **Fixed**: Pages custom JS not added via jQuery.
- **Fixed**: Verifying email error when it already exists.
- **Fixed**: Profile fields not shown on edit account if not shown on registration.
- **Added**: (*) to required fields and additional HTML class.

## Version 7.1.2
- Release date: 29 May 2024 | 1403-03-09  [&uarr;](#table-of-contents)
- **Fixed**: Endpoint parsing.
- **Fixed**: Redirection and referring for login.
- **Fixed**: Download icon.
- **Fixed**: Built-in items not deactivating.
- **Added**: Hook for nav menu before profile icon.

## Version 7.0.6
- Release date: 17 May 2024 | 1403-02-28  [&uarr;](#table-of-contents)
- **Added**: Woodmart login popup compatibility.
- **Changed**: Login/registration form fields priority.
- **Added**: Option to change WC address on profile.
- **Fixed**: IPPanel SMS Gateway.
- **Fixed**: SMS timeout not applied via time zone difference.
- **Changed**: Better compatibility with WooCommerce.
- **Security**: Security and bug fixes.
- **Changed**: UI/UX enhancement.
- **Changed**: Major update in code and resources.

## Notice

After version 2.5.0, the plugin underwent a significant upgrade to version 7.0.0. During this time, the codebase was overhauled, and substantial changes were made to both the UI and backend code to improve performance, security, and user experience.

## Version 2.5.0
- Release date: 20 February 2022 | 1400-12-01  [&uarr;](#table-of-contents)
- **Fixed**: Showing email login when SMS OTP is turned on.
- **Fixed**: Adding email field to SMS OTP registration (optional, required).
- **Fixed**: Asking for current password when there’s none.
- **Fixed**: Not showing OTP input when changing mobile/email after request.
- **Fixed**: Always showing edit email section in profile edit.
- **Fixed**: Profile font and changed to IranYekan.
- **Fixed**: Generating username from Email, Mobile, “Dear User” when name field does not exist.
- **Fixed**: Some translation changes.

## Version 2.4.4
- Release date: 11 February 2022 | 1400-11-22  [&uarr;](#table-of-contents)
- **Fixed**: Keep user logged in forever.
- **Fixed**: Persian translation.
- **Fixed**: Keep new lines on copy shortcode.
- **Added**: Welcome page after activating the plugin.
- **Development**: Enhancement for creating a profile page on first-use.
- **Development**: Added placeholder to test-mobile-otp field.
- **Development**: Enhanced function `::get_profile_page`.

## Version 2.4.0
- Release date: 18 January 2022 | 1400-10-28  [&uarr;](#table-of-contents)
- **Changed**: Backend UX improvement.
- **Changed**: Backend UI improvement.
- **Changed**: Translation.
- **Added**: Notice after installation.
- **Development**: Removed redundant lines.
- **Development**: Improved toast on the profile panel.
- **Development**: Fixed some CSS.

## Version 2.3.6
- Release date: 11 January 2022 | 1400-10-21  [&uarr;](#table-of-contents)
- **Added**: New admin dashboard UX widget.
- **Added**: Expire authentication option.
- **Changed**: Backend UX improvement.

## Version 2.3.5
- Release date: 09 January 2022 | 1400-10-19  [&uarr;](#table-of-contents)
- **Changed**: Enhanced admin dashboard UX.
- **Added**: ReadMe to GitHub & WordPress.

## Version 2.3.4
- Release date: 03 January 2022 | 1400-10-13  [&uarr;](#table-of-contents)
- **Fixed**: Popup login/registration form.
- **Fixed**: Toast notification coloring errors.
- **Fixed**: Admin user creation, now auto verifies user.
- **Fixed**: Arabic/Persian numbers in inputs/verification.
- **Fixed**: Registration without saving user first name.
- **Fixed**: Duplicate user first name/last name on admin-new user panel.
- **Changed**: Enhanced Kavenegar SMS Gateway.

## Version 2.3.3
- Release date: 01 January 2022 | 1400-10-11  [&uarr;](#table-of-contents)

- **Added**: Floating form labels.
- **Changed**: Popup login/register style.
- **Added**: Option to use message-box/toast notification.

## Version 2.3.0
- Release date: 30 December 2021 | 1400-10-09  [&uarr;](#table-of-contents)
- **Added**: Multiple SMS providers.
- **Added**: Mobile newsletter subscription via number verify (SMS OTP).
- **Added**: Each SMS provider has its own sending function.
- **Added**: Each SMS provider has its own setting panel.
- **Added**: Live-test your SMS OTP code.
- **Changed**: Change mobile after OTP sent.
- **Changed**: OTP login enhancement.
- **Changed**: OTP registration enhancement.
- **Changed**: Login/registration clears stored OTP in the database.
- **Changed**: Popup form design.
- **Changed**: Smart button now receives ‘trigger’ argument to let other elements trigger it.
- **Changed**: ‘Trigger’ argument element could have classes to activate popup form (active-register|active-login).
- **Changed**: ‘Trigger’ could also be used with multiple selectors, e.g., ‘.openlogin, .openregister, .openpup, #login_btn’.
- **Added**: Shortcode `[pepro-sms-subscription]`.
- **Added**: Newsletter section in the setting for managing users.
- **Added**: Option to export newsletter users as CSV.

## Version 1.9.2
- Release date: 22 December 2021 | 1400-10-01  [&uarr;](#table-of-contents)
- **Changed**: Verification enhancement.

## Version 1.9.1
- Release date: 11 December 2021 | 1400-09-20  [&uarr;](#table-of-contents)
- **Fixed**: CSS issues.
- **Changed**: Enhancement.
- **Changed**: Responsive reCaptcha.

## Version 1.8.9
- Release date: 24 November 2021 | 1400-09-03  [&uarr;](#table-of-contents)
- **Fixed**: Wrong date/time on OTP timeout timer.
- **Fixed**: No creating user using Email OTP method.
- **Fixed**: Translation.

## Version 1.8.7
- Release date: 13 November 2021 | 1400-08-22  [&uarr;](#table-of-contents)
- **Fixed**: SMS verification timeout not showing countdown.

## Version 1.8.6
- Release date: 08 November 2021 | 1400-08-17  [&uarr;](#table-of-contents)
- **Fixed**: Profile notification not accepting HTML.
- **Fixed**: Profile dashboard logo size.
- **Added**: Option to let use WordPress login/register URL structure.

## Version 1.8.5
- Release date: 05 November 2021 | 1400-08-14  [&uarr;](#table-of-contents)
- **Fixed**: Verification issues.
- **Added**: Bulk approve user emails (`/wp-admin/?bulk_useremail_approve=1`).

## Version 1.8.2
- Release date: 29 August 2021 | 1400-06-07  [&uarr;](#table-of-contents)
- **Added**: WPML compatibility.
- **Changed**: Make smaller version of avatar on upload.

## Version 1.0.0
- Release date: 29 August 2021 | 1400-06-07  [&uarr;](#table-of-contents)
- **Added**: Unified all-in-one plugin.
- **Added**: Translation.

## Version 0.0.1
- Release date: 01 January 2020 | 1398-10-11  [&uarr;](#table-of-contents)
- **Added**: Initial release.