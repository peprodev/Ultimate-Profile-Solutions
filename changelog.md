# Changelog

## Table of Contents

- [Version 7.4.5](#version-745--06-october-2024--1403-07-15)
- [Version 7.4.4](#version-744--03-october-2024--1403-07-12)
- [Version 7.4.3](#version-743--20-september-2024--1403-06-30)
- [Version 7.4.0](#version-740--21-august-2024--1403-05-31)
- [Version 7.3.3](#version-733--17-august-2024--1403-05-27)
- [Version 7.3.0](#version-730--12-august-2024--1403-05-22)
- [Version 7.2.4](#version-724--07-august-2024--1403-05-17)
- [Version 7.1.9](#version-719--03-july-2024--1403-04-13)
- [Version 7.1.8](#version-718--25-june-2024--1403-04-05)
- [Version 7.1.7](#version-717--21-june-2024--1403-04-01)
- [Version 7.1.6](#version-716--21-june-2024--1403-04-01)
- [Version 7.1.5](#version-715--16-june-2024--1403-03-27)
- [Version 7.1.2](#version-712--29-may-2024--1403-03-09)
- [Version 7.0.6](#version-706--17-may-2024--1403-02-28)
- [Notice](#notice)
- [Version 2.5.0](#version-250--20-february-2022--1400-12-01)
- [Version 2.4.4](#version-244--11-february-2022--1400-11-22)
- [Version 2.4.0](#version-240--18-january-2022--1400-10-28)
- [Version 2.3.6](#version-236--11-january-2022--1400-10-21)
- [Version 2.3.5](#version-235--09-january-2022--1400-10-19)
- [Version 2.3.4](#version-234--03-january-2022--1400-10-13)
- [Version 2.3.3](#version-233--01-january-2022--1400-10-11)
- [Version 2.3.0](#version-230--30-december-2021--1400-10-09)
- [Version 1.9.2](#version-192--22-december-2021--1400-10-01)
- [Version 1.9.1](#version-191--11-december-2021--1400-09-20)
- [Version 1.8.9](#version-189--24-november-2021--1400-09-03)
- [Version 1.8.7](#version-187--13-november-2021--1400-08-22)
- [Version 1.8.6](#version-186--08-november-2021--1400-08-17)
- [Version 1.8.5](#version-185--05-november-2021--1400-08-14)
- [Version 1.8.2](#version-182--29-august-2021--1400-06-07)
- [Version 1.0.0](#version-100--29-august-2021--1400-06-07)
- [Version 0.0.1](#version-001--01-january-2020--1398-10-11)

---

## Version 7.4.5 | 06 October 2024 | 1403-07-15
- **Fixed** Change Email button on Registeration not worked

## Version 7.4.4 | 03 October 2024 | 1403-07-12
- **Fixed** SMS.ir API v2 guiding links to new sms.ir panel
- **Fixed** Function is_single was called incorrectly
- **Fixed** Shortcode not returned content if user was Logged-in

## Version 7.4.3 | 20 September 2024 | 1403-06-30
- **Fixed** SMS.ir API v2 guiding links to new sms.ir panel
- **Fixed** Function is_single was called incorrectly
- **Fixed** Shortcode not returned content if user was Logged-in

## Version 7.4.0 | 21 August 2024 | 1403-05-31

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

## Version 7.3.3 | 17 August 2024 | 1403-05-27

- **Fixed**: User access to orders.
- **Fixed**: Redirect to current page not working.
- **Added**: Support for `[pepro-profile redirect_to="$url"]` and other login shortcode attributes.

## Version 7.3.0 | 12 August 2024 | 1403-05-22

- **Security**: Major security and performance enhancement.
- **Added**: Compatibility with Ticketing plugin.
- **Removed**: Old redundant options from wp_options.
- **Changed**: Better options handling with non-autoloading them.
- **Changed**: View order default URL to point to profile.
- **Fixed**: Applied notification and announcement UI fixes.
- **Development**: Added `peprodev/profile/helper/add_private_notification` hook for other plugins to create personal and global notifications.

## Version 7.2.4 | 07 August 2024 | 1403-05-17

- **Fixed**: Layout of user notification chat.
- **Fixed**: Layout of user announcement chat.
- **Changed**: Notification/Announcement icon.
- **Fixed**: Some CSS issues.
- **Changed**: Make GUEST OTP records non-autoload.
- **Development**: Added current section name to page wrapper class list.

## Version 7.1.9 | 03 July 2024 | 1403-04-13

- **Fixed**: Layout break of login page while loading.
- **Fixed**: Showing back "Register" link when the user is not registered.

## Version 7.1.8 | 25 June 2024 | 1403-04-05

- **Fixed**: Failed to send OTP if username is the same as mobile and the user is found by username rather than mobile.

## Version 7.1.7 | 21 June 2024 | 1403-04-01

- **Fixed**: Login shortcode layout break before full page load.

## Version 7.1.6 | 21 June 2024 | 1403-04-01

- **Fixed**: `[pepro-profile]` shortcode breaks Elementor layout.

## Version 7.1.5 | 16 June 2024 | 1403-03-27

- **Fixed**: Pages custom JS not added via jQuery.
- **Fixed**: Verifying email error when it already exists.
- **Fixed**: Profile fields not shown on edit account if not shown on registration.
- **Added**: (*) to required fields and additional HTML class.

## Version 7.1.2 | 29 May 2024 | 1403-03-09

- **Fixed**: Endpoint parsing.
- **Fixed**: Redirection and referring for login.
- **Fixed**: Download icon.
- **Fixed**: Built-in items not deactivating.
- **Added**: Hook for nav menu before profile icon.

## Version 7.0.6 | 17 May 2024 | 1403-02-28

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

## Version 2.5.0 | 20 February 2022 | 1400-12-01

- **Fixed**: Showing email login when SMS OTP is turned on.
- **Fixed**: Adding email field to SMS OTP registration (optional, required).
- **Fixed**: Asking for current password when there’s none.
- **Fixed**: Not

 showing OTP input when changing mobile/email after request.
- **Fixed**: Always showing edit email section in profile edit.
- **Fixed**: Profile font and changed to IranYekan.
- **Fixed**: Generating username from Email, Mobile, “Dear User” when name field does not exist.
- **Fixed**: Some translation changes.

## Version 2.4.4 | 11 February 2022 | 1400-11-22

- **Fixed**: Keep user logged in forever.
- **Fixed**: Persian translation.
- **Fixed**: Keep new lines on copy shortcode.
- **Added**: Welcome page after activating the plugin.
- **Development**: Enhancement for creating a profile page on first-use.
- **Development**: Added placeholder to test-mobile-otp field.
- **Development**: Enhanced function `::get_profile_page`.

## Version 2.4.0 | 18 January 2022 | 1400-10-28

- **Changed**: Backend UX improvement.
- **Changed**: Backend UI improvement.
- **Changed**: Translation.
- **Added**: Notice after installation.
- **Development**: Removed redundant lines.
- **Development**: Improved toast on the profile panel.
- **Development**: Fixed some CSS.

## Version 2.3.6 | 11 January 2022 | 1400-10-21

- **Added**: New admin dashboard UX widget.
- **Added**: Expire authentication option.
- **Changed**: Backend UX improvement.

## Version 2.3.5 | 09 January 2022 | 1400-10-19

- **Changed**: Enhanced admin dashboard UX.
- **Added**: ReadMe to GitHub & WordPress.

## Version 2.3.4 | 03 January 2022 | 1400-10-13

- **Fixed**: Popup login/registration form.
- **Fixed**: Toast notification coloring errors.
- **Fixed**: Admin user creation, now auto verifies user.
- **Fixed**: Arabic/Persian numbers in inputs/verification.
- **Fixed**: Registration without saving user first name.
- **Fixed**: Duplicate user first name/last name on admin-new user panel.
- **Changed**: Enhanced Kavenegar SMS Gateway.

## Version 2.3.3 | 01 January 2022 | 1400-10-11

- **Added**: Floating form labels.
- **Changed**: Popup login/register style.
- **Added**: Option to use message-box/toast notification.

## Version 2.3.0 | 30 December 2021 | 1400-10-09

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

## Version 1.9.2 | 22 December 2021 | 1400-10-01

- **Changed**: Verification enhancement.

## Version 1.9.1 | 11 December 2021 | 1400-09-20

- **Fixed**: CSS issues.
- **Changed**: Enhancement.
- **Changed**: Responsive reCaptcha.

## Version 1.8.9 | 24 November 2021 | 1400-09-03

- **Fixed**: Wrong date/time on OTP timeout timer.
- **Fixed**: No creating user using Email OTP method.
- **Fixed**: Translation.

## Version 1.8.7 | 13 November 2021 | 1400-08-22

- **Fixed**: SMS verification timeout not showing countdown.

## Version 1.8.6 | 08 November 2021 | 1400-08-17

- **Fixed**: Profile notification not accepting HTML.
- **Fixed**: Profile dashboard logo size.
- **Added**: Option to let use WordPress login/register URL structure.

## Version 1.8.5 | 05 November 2021 | 1400-08-14

- **Fixed**: Verification issues.
- **Added**: Bulk approve user emails (`/wp-admin/?bulk_useremail_approve=1`).

## Version 1.8.2 | 29 August 2021 | 1400-06-07

- **Added**: WPML compatibility.
- **Changed**: Make smaller version of avatar on upload.

## Version 1.0.0 | 29 August 2021 | 1400-06-07

- **Added**: Unified all-in-one plugin.
- **Added**: Translation.

## Version 0.0.1 | 01 January 2020 | 1398-10-11

- **Added**: Initial release.