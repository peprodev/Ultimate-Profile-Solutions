=== PeproDev Ultimate Profile Solutions ===
Contributors: amirhpcom, peprodev, blackswanlab
Donate link: https://peprodev.com/donate/
Tags: profile, dashboard, login-registration
Version: 8.2.37
Stable tag: 8.2.37
Requires at least: 5.0
Tested up to: 7.1
Requires PHP: 7.2
WC tested up to: 9.8
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Profile builder, user dashboard and OTP/SMS/Google login and registration for WordPress, with WooCommerce, LearnDash and WPML support.

== Description ==

The most powerful and feature-rich profile builder and user management solution for WordPress.
-----------------------------------------------------------------------------

🎉 Thank you for supporting PeproDev Ultimate Profile Solutions since its first private release in 2019!
Your support and feedback have been key in shaping this plugin into a reliable and feature-rich solution for WordPress user profiles.

* FREE OF ANY CHARGE, UNLIMITED, and OPEN-SOURCE FOREVER!
* Ajaxified Popup Login/Register form
* Login by Username/Password | Email/Password | Mobile OTP | Email OTP | Sign in with Google
* Show Popup/Toast Notification after Login/Register
* Unlimited User Customized Registration Fields:
    * Text Field
    * Number Field
    * Email Field
    * Mobile Number Field
    * reCAPTCHA Field
    * Select Dropdown Field
    * Multiple-choice Field
    * WooCommerce Based fields
    * TinyMCE Editor
    * DEV: Hooked Customized Fields
* Unlimited User Customized Login Redirection rules (based on User Role)
* Unlimited User Customized Logout Redirection rules (based on User Role)
* Unlimited User Customized Registration Redirection rules (based on User Role)
* Hide wp-login.php and Change Login address
* Customized/Themed wp-login.php login screen
* Built-in CSS Editor for Login screen
* Built-in Dashboard with Responsive Design compatible with WooCommerce
* Unlimited User Customized Profile sections
* Built-in Individual CSS Editor for Each Profile Section
* Built-in Individual JS Editor for Each Profile Section
* Apply Restriction rules for Profile Section based on User Role or LearnDash Course Access
* Built-in Admin-User Notification system, announcement functionality
* Easily Integrate your SMS Provider with OTP System
* Newsletter Mobile-based Subscription (Export to Excel CSV)
* Compatible with WooCommerce, LearnDash, WooWallet, Wishlist, YITH Plugins
* Modern UI (on by default, can be turned off): modern login/register form with Login/Register tabs, OTP code boxes and a mobile/email switch, and a modern user dashboard
* WPML String Translation and Polylang support for all admin-defined front-end texts
* Made by Developers for the Developers! [Source code in GitHub](https://github.com/peprodev/Ultimate-Profile-Solutions)

== Plugin Features ==
* Custom Profile Creation with multiple sections
* Ability to display shortcodes within profile sections
* Add custom CSS and JavaScript to profile pages
* Editable profile with custom fields
* Customizable profile avatars
* View WooCommerce orders within profile
* Send notifications to selected or all users
* Popup login/register forms
* Custom redirection after login/register/logout based on user role
* Migration from Digits plugin
* Responsive and clean design
* Change default login URL instead of wp-login.php
* Add reCAPTCHA for enhanced security
* Mobile OTP-based subscription list for users
* Modify default WordPress login design and behavior
* SMS Providers: SMS.ir (v1, v2), FarazSMS / IranPayamak (Normal, Pattern), IPPanel (Normal, Pattern), Kavehnegar (Normal, Pattern), ParsGreen, and any provider of the WP SMS or Persian WooCommerce SMS plugins, with options to add more using hooks
* Sign in with Google (OAuth 2.0 / OpenID Connect) with optional account creation
* Fully compatible with Elementor, Zephyr theme, Woodmart theme, Visual Composer, LearnDash, WooWallet, PeproDev Ticketing, WooCommerce, and more

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/peprodev-ups` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the "PeproDev Profile" admin menu to configure the plugin
4. Navigate to `/wp-admin/?page=peprodev-ups&section=loginregister#tab_samrt_button` and copy Magical Button shortcode
5. Add this shortcode to your header or next to your menu bar, so users could use popup login/register
6. Also, check shortcodes panel from your sidebar while you're in Plugin's custom setting page
7. This Plugin has 100% compatibility with Zephyr theme and could be used with any other themes
8. The Modern UI is on by default. Turn the modern login form off under PeproDev Profile > Login/Register > Login & Registration, and the modern dashboard under PeproDev Profile > Profile
9. Optional: set up Sign in with Google under PeproDev Profile > Login/Register > Social Login

== How to Use ==
Place the shortcode `[pepro-smart-btn]` in your page header or view `wp-admin/?page=peprodev-ups&section=loginregister` for more advanced shortcodes. Explore `wp-admin/?page=peprodev-ups&section=shortcodes` to browse all available shortcodes provided by the plugin.

== About Us ==
PeproDev is a premium supplier of quality WordPress plugins, services, and support. We are Pepro Dev. Group [peprodev.com], and we make premium WordPress stuff, plugins, and contribute to FOSS. Proudly made in Iran for all web users to use freely, without any commercial influence or support from SMS providers listed in the plugin.

== Maintenance & Warranty ==
This plugin is provided "as is," with no warranty of any kind. We do not guarantee the plugin's performance or suitability for any specific purpose. Updates are pushed through our GitHub channel.

== How to Contribute ==
You can help us improve this plugin by forking it on GitHub and submitting your contributions. Visit the [GitHub repository](https://github.com/peprodev/Ultimate-Profile-Solutions) to get started.

== Legal Disclaimer ==
PeproDev is not liable for any data breaches, hacks, or other security-related issues that may occur as a result of using this plugin. Please ensure that your website is secure and that you follow best practices for security.

**Data Privacy Notice:** We do not collect any data from you. Your usage of this plugin is completely private, and no information is transmitted or stored by us.

== Security and Bug Reporting ==
Our plugin is submitted through Patchstack, and any bugs or security vulnerabilities are promptly addressed. Please report any issues through our GitHub repository or contact us directly.

Security fixes: 8.2.29 fixes CVE-2026-4791 (stored cross-site scripting through the `[logout-url]` shortcode attributes, Contributor and above) and the same kind of issue in the other shortcodes after a full review: attributes of `[pepro-login-popup]`, `[pepro-login-form]`, `[pepro-smart-btn]`, `[pepro-profile-url]`, `[user]`, `[profile-card-1..4]`, `[profile-ld-enrolled]`, `[profile-wc-orders]` and `[pepro-sms-subscription]` are escaped or filtered, and `[user meta=""]` only prints public profile fields. Please update.

== Customization Services ==
We offer customization services for this plugin. If you need specific features added or changes made, our team is available to assist you, either freely or for a fee. Contact us at [support@peprodev.com](mailto:support@pepro.dev).

== Pro Version ==
We are working on a new pro version of the plugin with refactored code and enhanced standards, which will be available soon.

== Tips & Tricks ==
* View the changelog at `wp-admin/admin.php?page=peprodev-ups&section=home&welcome=true`.
* Regenerate the plugin's database structure by visiting `wp-admin/?peprodevups_force_db_create=1`.

== Frequently Asked Questions ==

= How can I contribute to this plugin? =
You can help us improve our works by committing your changes to [GitHub/Ultimate-Profile-Solutions repository](https://github.com/peprodev/Ultimate-Profile-Solutions)

= How can I Order a Customized version of this plugin? =
Our professional development team is here to offer you a fully Customized-Pro version of this plugin to fulfill your request. Contact us at [support@peprodev.com](mailto:support@pepro.dev)

= Will updating to 8.2 change the look of my site? =
Yes, unless you turned it off before. Since 8.2.0 the Modern UI (login/register form and user dashboard) is on by default, also for existing sites that never saved the setting. Sites that saved "off" on the 8.1 Dashboard Texts page keep it off. Turn it off under PeproDev Profile > Login/Register > Login & Registration (login form) and PeproDev Profile > Profile (dashboard), or with `define( 'PEPRODEV_UPS_UI_LOGIN', false );` / `define( 'PEPRODEV_UPS_UI_DASHBOARD', false );` in wp-config.php.

= Where did the "Dashboard Texts" page go? =
Its settings moved: the modern login form switch is in PeproDev Profile > Login/Register > Login & Registration, the modern dashboard switch, learning button and "My courses" texts are in PeproDev Profile > Profile. Saved values are migrated automatically.

= How do I set up Sign in with Google? =
Create an OAuth client ID (Web application) in the Google Cloud Console, add the Authorized redirect URI shown in PeproDev Profile > Login/Register > Social Login, then paste the Client ID and Client Secret there and enable the option. New Google users get an account only when registration is open and "Create new users" is enabled. Use `[pepro-google-login]` to place the button anywhere.

= Can I send the OTP through the WP SMS or Persian WooCommerce SMS plugins? =
Yes. Choose "WP SMS plugin" or "Persian WooCommerce SMS plugin" as SMS Provider in Login/Register > SMS Verification. The code is sent through the provider configured in that plugin; the gateway shows a notice and sends nothing while the plugin is not active.

= Can I translate texts I entered in the plugin settings? =
Yes. With WPML String Translation or Polylang active, registration fields, redirect rules, login header/footer HTML, email and SMS templates, dashboard texts and custom dashboard sections can be translated (context: peprodev-ups).

= Where can I find the full changelog? =
The full changelog is available in our [GitHub repository](https://github.com/peprodev/Ultimate-Profile-Solutions/blob/master/changelog.md).

== Screenshots ==
1. by Pepro Dev. Group

== Changelog ==

The full changelog is available in our [GitHub repository](https://github.com/peprodev/Ultimate-Profile-Solutions/blob/master/changelog.md).

🎉 Thank you for supporting PeproDev Ultimate Profile Solutions since its first private release in 2019!
Your support and feedback have been key in shaping this plugin into a reliable and feature-rich solution for WordPress user profiles.

🎂 Also, a big congratulations to WordPress on its 22nd birthday 🥳🍾!
We're proud to be part of this amazing journey with the WordPress community 💙
Here's to many more years of innovation, freedom, and open-source collaboration 😍!

= 8.2.37 =
Release date: 2026-09-29

* Changed: "Translate & Replace" has its own menu item with text translation (gettext), page text replace (HTML) and import/export.

= 8.2.36 =
Release date: 2026-09-29

* Fixed: a horizontal scrollbar under some settings cards of the admin panel.

= 8.2.35 =
Release date: 2026-09-29

* New: text replacement rules (gettext) on the LearnDash screen, e.g. change "Course" to "Class".

= 8.2.34 =
Release date: 2026-09-29

* Changed: learning button and My courses settings moved to a new "LearnDash" screen.

= 8.2.33 =
Release date: 2026-09-29

* New: font size settings for the modern login form and dashboard.

= 8.2.32 =
Release date: 2026-09-29

* New: "Modern UI Design" screen with colors for buttons, tabs, links, sidebar menu, tables, cards and fields of the modern login form and dashboard.

= 8.2.31 =
Release date: 2026-09-29

* Fixed: the verification tabs of the modern dashboard match the address tabs, and the card title follows the forms shown.

= 8.2.30 =
Release date: 2026-09-29

* Improved: the dashboard page address is shown under its select. Fixed: an existing page with [pepro-profile] is reused instead of creating a new dashboard page.

= 8.2.29 =
Release date: 2026-09-29

* Security: fixes CVE-2026-4791 (stored XSS through [logout-url] shortcode attributes, Contributor+) and the same kind of issue in the other shortcodes. [user meta=""] now prints only public profile fields. Please update.

= 8.2.28 =
Release date: 2026-09-29

* New: "Button accent color" setting for the modern login form and dashboard.

= 8.2.27 =
Release date: 2026-09-29

* Fixed: the "Resend code" timer of the OTP forms counts down and becomes clickable when a new code can be requested.

= 8.2.26 =
Release date: 2026-09-29

* Fixed: FarazSMS codes that were sent could be reported as "not sent" on the login form.

= 8.2.25 =
Release date: 2026-09-29

* Changed: FarazSMS "Create an OTP pattern" has its own section below the settings.

= 8.2.24 =
Release date: 2026-09-29

* Improved: FarazSMS settings are grouped (Account, Sending the code) with a clearer Api-Key link and balance box.

= 8.2.23 =
Release date: 2026-09-29

* Fixed: the duplicate / move / delete buttons of registration fields and redirect rules no longer overflow their box.

= 8.2.22 =
Release date: 2026-09-29

* Fixed: after updating, the Login/Register settings could load old cached styles and scripts, so new layouts and buttons looked broken.

= 8.2.21 =
Release date: 2026-09-29

* Improved: the default registration fields panel is wider and cleaner (one row per field with toggle and Required checkbox).

= 8.2.20 =
Release date: 2026-09-29

* Changed: on/off settings use toggle switches instead of eye icons on every plugin admin page.

= 8.2.19 =
Release date: 2026-09-29

* Improved: WordPress Built-in Login settings are grouped (Appearance, Logo, Form elements, Background) with short explanations.

= 8.2.18 =
Release date: 2026-09-29

* Improved: redirect macros are shown as clickable chips under "Redirect to", with their description as a tooltip.

= 8.2.17 =
Release date: 2026-09-29

* New: Code / Preview tabs on the verification email template editor.

= 8.2.16 =
Release date: 2026-09-29

* Changed: the default verification email subject is "[site_name] | Verify Email", and a "Restore default" button next to the subject field puts it back.

= 8.2.15 =
Release date: 2026-09-29

* Improved: FarazSMS has a "Sending method" setting (Pattern or Normal SMS) and shows only the fields of the chosen method.

= 8.2.14 =
Release date: 2026-09-29

* Fixed: the "Send a Test SMS" and "Send test email" buttons show their full label.

= 8.2.13 =
Release date: 2026-09-29

* Changed: Login & Registration settings are shown in two columns, and the registration fields (default and additional) have their own full-width "Registration Fields" tab.

= 8.2.12 =
Release date: 2026-09-29

* Improved: the Smart Button tab shows six copy-ready samples in two columns, a full attribute reference, related shortcodes and tips.

= 8.2.11 =
Release date: 2026-09-29

* Changed: the Redirection settings tab uses the full page width.

= 8.2.10 =
Release date: 2026-09-29

* Changed: "Auth. Expiration" moved from the SMS verification tab to the Login & Registration settings, next to the other login options.

= 8.2.9 =
Release date: 2026-09-29

* Changed: SMS verification and Email verification settings now have separate tabs in Login/Register settings.

= 8.2.8 =
Release date: 2026-09-29

* Fixed: FarazSMS (IranPayamak) errors now show the real reason from FarazSMS (for example a wrong sender number or pattern variable) instead of only "request failed (HTTP 422)". The sender number is sent as plain digits (Persian digits, spaces and + are cleaned).

= 8.2.7 =
Release date: 2026-09-27

* Fixed: Persian translations that went missing in 8.1/8.2 (dashboard menu items such as Dashboard, Edit Profile and Logout, WooCommerce order texts, pagination and several admin labels) are restored.

= 8.2.6 =
Release date: 2026-09-27

* Fixed: modern login form headings and the mobile/email switch link now follow the fields the form really shows. With "Force Mobile Registration form" (or "Force Email") both register forms use the same method, so the switch link is hidden on the Register tab and the heading no longer says "email" above a mobile form.

= 8.2.5 =
Release date: 2026-09-27 (includes 8.2.0 to 8.2.4)

* Changed: the Modern UI (login/register form and user dashboard) is now on by default, also on existing sites that never saved the setting; a saved "off" or the PEPRODEV_UPS_UI_LOGIN / PEPRODEV_UPS_UI_DASHBOARD constants still turn it off.
* Changed: the "Dashboard Texts" page was merged into the existing screens: the modern login switch is in Login/Register > Login & Registration, the modern dashboard switch, learning button and "My courses" texts are in Profile. Saved values are migrated automatically and the old page link redirects.
* New: modern login form switch between mobile and email. When both forms are enabled, a "Login/Register with email / with mobile" link moves the Login and Register tabs between the two methods; the first method follows your "Make Mobile Login/Registration Activated by Default" setting.
* New: Sign in with Google (Login/Register > Social Login): log in existing users by their Google email, optionally create new users when registration is open, button under the login/register forms, on wp-login.php and via [pepro-google-login].
* New: SMS gateways FarazSMS / IranPayamak (pattern and normal SMS, pattern list, balance and pattern creation helpers), WP SMS plugin and Persian WooCommerce SMS plugin integrations.
* Improved: every registration field type (text, number, email, date, select, checkbox, textarea, editor, WooCommerce address, reCAPTCHA) is styled in the modern register forms, with required markers and error messages under the fields.
* Improved: "Send a Test SMS" shows why sending failed. The older ippanel.com gateways are labelled "IPPanel / FarazSMS (legacy API)"; their settings are unchanged.
* Fixed: the email login form was visible under the mobile form in the modern UI.
* Fixed: registration was blocked by a reCAPTCHA field whose script was not loaded when "Use in Login form" was off.
* Fixed: WooCommerce country/state/city were always required and not saved to new users.
* Developers: new hooks pepro_reglogin_form_top, pepro_reglogin_form_bottom, pepro_reglogin_settings_tabs_nav, pepro_reglogin_settings_tabs_content, pepro_reglogin_save_settings, pepro_reglogin_social_login and pepro_reglogin_sms_last_error.

= 8.1.0 =
Release date: 2026-09-27

* New: optional Modern UI, off by default. A modern login/register form (Login/Register tabs, OTP code boxes with paste and autofill) and a modern user dashboard (edit profile with inline address editing and avatar removal, redesigned order and course views). Enable it under PeproDev Profile > Dashboard Texts > Modern UI.
* New: [peprodev_learning_button] and [peprodev_my_courses] shortcodes for LearnDash sites, with editable texts and links on the Dashboard Texts page.
* New: WPML String Translation and Polylang support for admin-defined front-end texts (registration fields, redirect rules, login header/footer HTML, email and SMS templates, dashboard texts, custom dashboard sections), including wpml-config.xml.
* New: configurable verification email subject and new email placeholders [site_name], [site_logo], [expire_minutes] and [year].
* New: modern default verification email template (English and Persian) with "Reset to default" and "Send test email" buttons. Existing templates are kept unchanged.
* Improved: verification emails include a plain-text part and start with the code, for better code detection in mail apps.
* Changed: after login or registration, visitors go to the explicit redirect target, then to a matching redirection rule, otherwise to the profile dashboard (instead of the previous page). Login and registration rules are now evaluated separately.
* Fixed: OTP registration when automatic login after registration is disabled.
* Fixed: profile custom content not saved while the editor was on the Text tab.
* Fixed: PNG avatar uploads.
* Fixed: profile logo preview and removal; the logo is now shown above the front-end login/register form.
* Security: hardened AJAX actions and admin tools, stricter validation of user profile updates and redirects, OTP rate limiting and stronger code generation, more output escaping.
* Performance: moment.js is no longer loaded with the login form, no duplicate stylesheet downloads, lighter database queries.
* Compatibility: tested with WordPress 7.1; PHP 7.2+.

= Version 8.0.4 | 2025-05-31 | 1404-03-10 =
- Fixed some Backend Setting UI issues
- Fixed Custom text on dashboard not rendered properly
- Fixed Removed setting upon upgrade

= Version 8.0.2 | 2025-05-28 | 1404-03-07 =
- Fixed issue with login slug not functioning correctly
- Fixed issue with login page style not showing correctly

= Version 8.0.0 | 2025-05-27 | 1404-03-06 =
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

== Upgrade Notice ==

= 8.2.37 =
Security release: fixes CVE-2026-4791 (stored XSS through shortcode attributes) and hardens all shortcodes. Update as soon as possible.

= 8.2.5 =
The Modern UI is now on by default, also on existing sites that did not turn it off before. Its settings moved from "Dashboard Texts" to the Login/Register and Profile screens (values are migrated). New: Sign in with Google, FarazSMS / WP SMS / Persian WooCommerce SMS gateways.

= 8.1.0 =
Security and stability release. The new Modern UI is off by default. After login, visitors without a redirect rule now land on the profile dashboard instead of the previous page; review your redirection rules after updating.

= 7.4.0 =
After updating to version 7.4.0, check the login section in the plugin settings and double-check that all configurations are intact.
