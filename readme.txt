=== PeproDev Ultimate Profile Solutions ===
Contributors: amirhpcom, peprodev, blackswanlab
Donate link: https://pepro.dev/donate/
Tags: profile-builder, user-dashboard, login-registration, otp-login
Version: 7.4.6
Stable tag: 7.4.6
Requires at least: 5.0
Tested up to: 6.6.2
Requires PHP: 7.2
WC tested up to: 9.3.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The Ultimate WordPress Profile Builder & User Management Plugin

== Description ==

The most powerful and feature-rich profile builder and user management solution for WordPress.
-----------------------------------------------------------------------------

* FREE OF ANY CHARGE! UNLIMITED! OPEN-SOURCE FOREVER!
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

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Plugin Name screen to configure the plugin
4. Navigate to `/wp-admin/?page=peprodev-ups&section=loginregister#tab_samrt_button` and copy Magical Button shortcode
5. Add this shortcode to your header or next to your menu bar, so users could use popup login/register
6. Also, check shortcodes panel from your sidebar while you're in Plugin's custom setting page
7. This Plugin has 100% compatibility with Zephyr theme and could be used with any other themes

== Upgrade Notice ==
After updating to version 7.4.0, it is recommended to check the login section in the plugin settings via `wp-admin/?page=peprodev-ups&section=loginregister#tab_registration` and double-check everything to ensure all configurations are intact.

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
* Regenerate the plugin's database structure by visiting `wp-admin/?pepro_ups_force_db_create=1`.

== Frequently Asked Questions ==

= How can I contribute to this plugin? =
You can help us improve our works by committing your changes to [GitHub/Ultimate-Profile-Solutions repository](https://github.com/peprodev/Ultimate-Profile-Solutions)

= How can I Order a Customized version of this plugin? =
Our professional development team is here to offer you a fully Customized-Pro version of this plugin to fulfill your request. Contact us at [support@peprodev.com](mailto:support@pepro.dev)

= Where can I find the full changelog? =
The full changelog is available in our [GitHub repository](https://github.com/peprodev/Ultimate-Profile-Solutions/blob/main/changelog.md).

== Screenshots ==
1. by Pepro Dev. Group

== Changelog ==

The full changelog is available in our [GitHub repository](https://github.com/peprodev/Ultimate-Profile-Solutions/blob/main/changelog.md).

= Ver. 7.4.6 =
- Set URL as section slug, to make it external link

= Ver. 7.4.5 =
- Fixed Change Email on Registeration not worked

= Ver. 7.4.4 =
- Fixed Name & Last Name fields not showing
- Fixed SMS.ir API v2 guiding links to new sms.ir panel
- Fixed Function is_single was called incorrectly
- Fixed Shortcode not returned content if user was Logged-in
- Fixed SMS.ir API v2 guiding links to new sms.ir panel
- Fixed Function is_single was called incorrectly

== Upgrade Notice ==

= Ver. 7.4.6 =
- Set URL as section slug, to make it external link

= Ver. 7.4.5 =
- Fixed Change Email on Registeration not worked

= Ver. 7.4.4 =
- Fixed Name & Last Name fields not showing
- Fixed SMS.ir API v2 guiding links to new sms.ir panel
- Fixed Function is_single was called incorrectly
- Fixed Shortcode not returned content if user was Logged-in
- Fixed SMS.ir API v2 guiding links to new sms.ir panel
- Fixed Function is_single was called incorrectly

Please also note that the full changelog for this version and previous versions can be found in the [GitHub repository](https://github.com/peprodev/Ultimate-Profile-Solutions/blob/master/changelog.md).