# PeproDev Ultimate Profile Solutions

Profile builder, user dashboard and OTP/SMS/Google login and registration for WordPress, with WooCommerce, LearnDash and WPML support. Free and open source (GPLv2 or later).

[![WordPress.org](https://img.shields.io/badge/WordPress.org-peprodev--ups-21759b)](https://wordpress.org/plugins/peprodev-ups/)
![Version](https://img.shields.io/badge/version-8.2.5-blue)
![WordPress](https://img.shields.io/badge/WordPress-5.0%2B%20%7C%20tested%207.1-21759b)
![PHP](https://img.shields.io/badge/PHP-7.2%2B-777bb4)
![License](https://img.shields.io/badge/license-GPLv2%2B-green)

|![Banner](https://ps.w.org/peprodev-ups/assets/banner-772x250.png)|![Banner RTL](https://ps.w.org/peprodev-ups/assets/banner-772x250-rtl.png)|
|--|--|

Developed by **[Pepro Development Group](https://pepro.dev/)** / Lead Developer: **[AmirhpCom](https://amirhp.com/)**

| | |
|--|--|
| **Stable version** | 8.2.5 |
| **Requires WordPress** | 5.0 or later (tested up to 7.1) |
| **Requires PHP** | 7.2 or later |
| **WooCommerce** | optional, tested up to 9.8 |
| **Text domain** | `peprodev-ups` (full Persian translation included) |

## Features

- **Ajax login and registration** in a page or popup: username/password, email/password, mobile OTP and email OTP, with toast/popup notices.
- **Sign in with Google** (new in 8.2): OAuth 2.0 / OpenID Connect without an SDK, optional account creation, `[pepro-google-login]` shortcode.
- **Unlimited registration fields**: text, number, email, mobile, reCAPTCHA, select, multiple choice, WooCommerce fields, TinyMCE editor and developer-hooked fields.
- **Redirection rules** after login, registration and logout, per user role.
- **Login hardening and branding**: change the login address, hide `wp-login.php`, theme the WordPress login screen with a built-in CSS editor.
- **User dashboard** with unlimited custom sections (each with its own CSS/JS), role or LearnDash course based access rules, WooCommerce orders, downloads and addresses.
- **Notifications and announcements** from admin to selected or all users.
- **SMS gateways** for OTP: SMS.ir (v1, v2), FarazSMS / IranPayamak (new in 8.2), IPPanel (legacy FarazSMS API), Kavenegar, ParsGreen, the WP SMS and Persian WooCommerce SMS plugins (new in 8.2), and more through hooks.
- **Mobile newsletter** subscription with CSV export.
- **Verification emails** with a configurable subject, HTML template, placeholders, "Reset to default" and "Send test email".
- **Modern UI** (new in 8.1.0, on by default since 8.2.0), see below.
- **WPML / Polylang** translation of every admin-defined front-end text (new in 8.1.0), see below.
- Works with Elementor, WPBakery, Zephyr and Woodmart themes, LearnDash, WooWallet, YITH plugins, PeproDev Ticketing and WooCommerce (HPOS compatible).

## Modern UI

Version 8.1.0 added a modern presentation layer; since **8.2.0 it is on by default**, also on existing sites that never saved the setting (a site that saved it as off keeps it off).

| Setting | Where | What it changes |
|--|--|--|
| Modern login/register form | **PeproDev Profile > Login/Register > Login & Registration** (`admin.php?page=peprodev-ups&section=loginregister#tab_registration`) | Login/Register tabs, OTP code boxes (paste, autofill, auto-submit), a "Login/Register with email / with mobile" switch when both methods are enabled, styled registration fields |
| Modern user dashboard, learning button and "My courses" texts | **PeproDev Profile > Profile** (`admin.php?page=peprodev-ups&section=profile`) | Restyled dashboard, new Edit profile view (inline WooCommerce address editing, avatar removal, password strength), redesigned order and course views |

These settings are stored in the `modern_ui` key of the `peprodev_ups_profile` option; values from the 8.1 `peprodev_ups_ui_texts` option are copied there once (the old option is left untouched for downgrades).

A constant in `wp-config.php` always wins over the setting:

```php
define( 'PEPRODEV_UPS_UI_LOGIN', true );      // or false to force the classic form
define( 'PEPRODEV_UPS_UI_DASHBOARD', true );  // or false to force the classic dashboard
```

The modern dashboard templates live in `profile/libs/templates/modern/`. The design tokens (`ui/assets/css/tokens.css`) are CSS custom properties (`--mj-primary`, `--mj-radius`, ...) that a theme can override.

## Shortcodes

| Shortcode | Description |
|--|--|
| `[pepro-smart-btn]` | Login/register button for headers and menus; shows the user avatar and a dashboard link when logged in |
| `[pepro-login]` / `[pepro-login-form]` | Inline login/register form |
| `[pepro-login-popup]` | Popup login/register form opened by a trigger element |
| `[pepro-profile]` | The user dashboard |
| `[pepro-profile-url]` | URL of the user dashboard |
| `[logout-url]` | Logout link |
| `[user]` | A field of the current user |
| `[verified-mobile]` / `[verified-email]` | Verification status of the current user |
| `[loggedin]...[/loggedin]` | Content for logged-in users only |
| `[guest]...[/guest]` / `[loggedout]...[/loggedout]` | Content for visitors only |
| `[current_url]` | URL of the current page |
| `[pepro-sms-subscription]` | Mobile newsletter subscription form |
| `[profile-card-1]` ... `[profile-card-4]` | Profile summary cards |
| `[profile-wc-stats]`, `[profile-wc-orders]`, `[profile-wc-downloads]` | WooCommerce statistics, orders and downloads of the current user |
| `[profile-ld-enrolled]` | LearnDash courses of the current user |
| `[peprodev_learning_button class=""]` | "Continue / Start learning" button (LearnDash); texts and links are set in PeproDev Profile > Profile. New in 8.1.0 |
| `[pepro-google-login text="" redirect_to=""]` | "Sign in with Google" button. New in 8.2 |
| `[peprodev_my_courses layout="home\|full"]` | "Continue learning" card and the user's LearnDash courses (`full` also lists expired courses). New in 8.1.0 |

Browse all shortcodes with examples in **PeproDev Profile > Shortcodes** (`wp-admin/admin.php?page=peprodev-ups&section=shortcodes`).

Link placeholders for the learning button: `{my_courses}`, `{courses_page}`, `{profile}`, `{shop}`, `{home}`, and `{continue_learning}` (requires the separate PeproDev WP Tweaker plugin).

With LearnDash active, logged-in users get a `mj-has-courses` or `mj-no-courses` body class. Add `mj-has-courses-only` or `mj-no-courses-only` to any element to show it only to one group (the helper CSS loads with the Modern UI or the learning shortcodes, or when `peprodev_ui_load_global_css` returns true).

## WPML and Polylang

With WPML String Translation or Polylang active, these texts are registered under the `peprodev-ups` context and translated on output:

- registration field labels, placeholders, error texts and select options
- redirection rule URLs and popup button texts
- login form header/footer HTML, WordPress login logo title and link
- verification email template, subject and sender name
- SMS message templates
- dashboard title, dashboard custom HTML and custom dashboard sections
- learning button and "My courses" texts
- Sign in with Google button text

`wpml-config.xml` declares the option keys as admin texts, and the settings screens show a "Translate texts with WPML" link.

## Sign in with Google

Settings: **PeproDev Profile > Login/Register > Social Login** (`admin.php?page=peprodev-ups&section=loginregister#tab_social`).

1. Create an OAuth client ID of type *Web application* in the [Google Cloud Console](https://console.cloud.google.com/apis/credentials).
2. Add the Authorized redirect URI shown on the settings tab: `home_url('/?pepro_social=google')`.
3. Paste the Client ID and Client Secret (the secret is never printed back), enable the option and save.

Existing users (matched by their verified Google email) are logged in; new emails get an account only when registration is open and "Create new users when registration is open" is enabled. The email is marked verified (`pepro_user_is_email_verified`) and the Google account id is stored in `pepro_google_sub`. The redirect follows the plugin's rules. The button is added under the plugin's login/register forms, on `wp-login.php`, and with `[pepro-google-login]`.

## Hooks added in 8.2

| Hook | Type | Purpose |
|--|--|--|
| `pepro_reglogin_form_top` / `pepro_reglogin_form_bottom` | action | Output above / under the login and register forms (`array $args` with `redirect_to`, `active`) |
| `pepro_reglogin_settings_tabs_nav` / `pepro_reglogin_settings_tabs_content` | action | Add a tab to the Login/Register settings screen |
| `pepro_reglogin_save_settings` | action | `(array $dparam)` save handler of that screen (nonce and capability already checked) |
| `pepro_reglogin_social_login` | action | `(int $user_id, string $provider, bool $new_user)` after a social login |
| `pepro_reglogin_sms_last_error` | filter | Reason of the last failed SMS send, shown by "Send a Test SMS" |

## Hooks added in 8.1.0

| Hook | Type | Purpose |
|--|--|--|
| `peprodev_ui_module_enabled` | filter | `(bool $on, string $module)` enable/disable the `login` or `dashboard` Modern UI module |
| `peprodev_ui_load_global_css` | filter | Load the design tokens and visibility helper CSS on every page |
| `peprodev_ui_texts_fields` | filter | Add or change the Modern UI / learning button / My courses fields |
| `peprodev_ui_text` / `peprodev_ui_text_url` | filter | Final value of one of those texts / resolved link |
| `peprodev_ui_courses_page_url` | filter | Target of the `{courses_page}` placeholder |
| `peprodev_ui_course_url` | filter | Dashboard URL of a course |
| `peprodev_ui_continue_url` | filter | Target of the "continue learning" card |
| `peprodev_ui_login_strings` / `peprodev_ui_dashboard_strings` | filter | Front-end strings of the Modern UI scripts |
| `peprodev_ui_dashboard_template_map` | filter | Classic template => modern template map |
| `peprodev_ui_dashboard_is_profile_page` | filter | Whether the current page is the user dashboard |
| `peprodev_ui_dashboard_dequeue_font_awesome` | filter | Keep Font Awesome on the modern dashboard (return false) |
| `pepro_reglogin_form_redirect_to` | filter | Redirect target printed into the login/register forms |
| `pepro_reglogin_default_login_redirect` | filter | Fallback after login/register (default: user dashboard; return `true` to reload the page) |
| `pepro_reglogin_otp_send_limits` | filter | `cooldown`, `per_hour`, `per_hour_identifier` limits for sending OTP codes |
| `pepro_reglogin_otp_max_verify_attempts` | filter | Wrong codes allowed per 15 minutes |
| `pepro_reglogin_verification_email_subject` | filter | Final verification email subject |
| `pepro_reglogin_default_mail_template` | filter | Built-in default verification email template |
| `pepro_reglogin_upgrade_legacy_mail_template` | filter | Return `true` to replace an untouched pre-8.1 default template with the new one |
| `peprodev-ups/wpml/strings` | filter | Strings registered with WPML/Polylang |

## Installation

1. Install from **Plugins > Add New** (search for "PeproDev Ultimate Profile Solutions"), or upload the `peprodev-ups` folder to `/wp-content/plugins/`.
2. Activate the plugin.
3. Configure it from the **PeproDev Profile** admin menu.
4. Put `[pepro-smart-btn]` in your header or menu for the popup login/register.
5. The Modern UI is on by default; turn it off in **Login/Register > Login & Registration** (form) or **Profile** (dashboard).
6. Optional: set up **Login/Register > Social Login** (Google) and pick an SMS provider in **Login/Register > Verification**.

### Building a release zip

From the repository root:

```sh
bin/build-release.sh
```

creates `dist/peprodev-ups-<version>.zip` without development files.

## Upgrading to 8.2

- The Modern UI is now **on by default**. Sites that never saved the 8.1 "Dashboard Texts" page (or saved it with the modules on) get the modern login form and dashboard after updating; sites that saved a module as off keep it off. Constants in `wp-config.php` still win.
- The "Dashboard Texts" page is gone; its values are migrated into the main option and edited on the Login/Register and Profile screens. Old links to `page=peprodev-ups-ui-texts` redirect to the Profile screen.
- The old "FarazSMS" / "FarazSMS Pattern" gateways (ippanel.com API) are now labelled "IPPanel / FarazSMS (legacy API)"; their ids and settings are unchanged. The new "FarazSMS (IranPayamak)" gateway uses a separate id (`farazsms`) and settings.

## Upgrading to 8.1.0

- The Modern UI is off by default; nothing visual changes until you enable it.
- After login or registration, visitors now go to the explicit `redirect_to` target, then to a matching redirection rule, otherwise to the user dashboard (previously: the page they came from). Review **Login/Register > Redirection** rules, or use the `pepro_reglogin_default_login_redirect` filter.
- Stored verification email templates are kept unchanged. Use "Reset to default" to switch to the new template.

## Changelog

See [changelog.md](changelog.md) for the full history.

## Contributing and security

Fork the [GitHub repository](https://github.com/peprodev/Ultimate-Profile-Solutions) and open a pull request. Please report security issues privately through Patchstack or [support@pepro.dev](mailto:support@pepro.dev) rather than in public issues.

## Privacy

The plugin does not collect or transmit any data to its authors. SMS and email are sent only through the gateways you configure.

## License

GPLv2 or later. See <https://www.gnu.org/licenses/gpl-2.0.html>.

This plugin is provided "as is", without warranty of any kind.
