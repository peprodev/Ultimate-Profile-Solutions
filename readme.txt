=== PeproDev Ultimate Profile Solutions ===
Contributors: amirhpcom, peprodev, blackswanlab
Donate link: https://peprodev.com/donate/
Tags: profile, dashboard, login-registration
Version: 8.2.0
Stable tag: 8.2.0
Requires at least: 5.0
Tested up to: 7.1
Requires PHP: 7.2
WC tested up to: 9.8
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Profile builder, user dashboard and OTP/SMS login and registration for WordPress, with WooCommerce, LearnDash and WPML support.

== Description ==

The most powerful and feature-rich profile builder and user management solution for WordPress.
-----------------------------------------------------------------------------

🎉 Thank you for supporting PeproDev Ultimate Profile Solutions since its first private release in 2019!
Your support and feedback have been key in shaping this plugin into a reliable and feature-rich solution for WordPress user profiles.

* FREE OF ANY CHARGE, UNLIMITED, and OPEN-SOURCE FOREVER!
* Ajaxified Popup Login/Register form
* Login by Username/Password | Email/Password | Mobile OTP | Email OTP | (social login soon)
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
* Optional Modern UI (off by default): modern login/register form with OTP code boxes and a modern user dashboard
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
* SMS Providers: SMS.ir (v1, v2), FarazSMS, IPPanel (Normal, Pattern), Kavehnegar (Normal, Pattern), ParsGreen, with options to add more using hooks
* Fully compatible with Elementor, Zephyr theme, Woodmart theme, Visual Composer, LearnDash, WooWallet, PeproDev Ticketing, WooCommerce, and more

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/peprodev-ups` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the "PeproDev Profile" admin menu to configure the plugin
4. Navigate to `/wp-admin/?page=peprodev-ups&section=loginregister#tab_samrt_button` and copy Magical Button shortcode
5. Add this shortcode to your header or next to your menu bar, so users could use popup login/register
6. Also, check shortcodes panel from your sidebar while you're in Plugin's custom setting page
7. This Plugin has 100% compatibility with Zephyr theme and could be used with any other themes
8. Optional: enable the Modern UI under PeproDev Profile > Dashboard Texts > Modern UI

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

= Will updating to 8.1.0 change the look of my site? =
No. The new Modern UI is off by default. Enable it under PeproDev Profile > Dashboard Texts > Modern UI, or with the `PEPRODEV_UPS_UI_LOGIN` / `PEPRODEV_UPS_UI_DASHBOARD` constants in wp-config.php.

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

= 8.1.0 =
Security and stability release. The new Modern UI is off by default. After login, visitors without a redirect rule now land on the profile dashboard instead of the previous page; review your redirection rules after updating.

= 7.4.0 =
After updating to version 7.4.0, check the login section in the plugin settings and double-check that all configurations are intact.
