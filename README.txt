=== WDD Marketing Automation ===
Contributors: ivan117 
Tags: marketing, automation, popup, banner, newsletter
Requires at least: 6.0 
Tested up to: 6.9 
Stable tag: 1.0.0 
Requires PHP: 8.2 
License: GPLv2 or later 
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight but powerful marketing automation plugin that tracks user
behavior and uses FCL rules to display dynamic modals or discount
banners.

== Description ==

WDD Marketing Dynamics is a lightweight but powerful WordPress plugin
that tracks user behavior and uses an FCL (Forms Computed Language) rule
engine to decide whether to display a newsletter modal or a discount
banner. It enables fully dynamic, condition-based marketing automation.

=== Features ===

= Behavior Tracking =

Tracks: * Time on page * Number of clicks * Number of visits (via
cookie) * Device type (mobile/desktop) * User country (via IP lookup)

= FCL Decision Engine =

Use FCL rules to decide what to show based on user behavior.

Available variables:

$time_on_page $clicks $visits $is_mobile $country

Return values: * show_newsletter_modal * show_discount_banner * none

The frontend evaluates rules every 5 seconds.

= Newsletter Modal =

-   Customizable title & text
-   Built-in email submission form
-   Submissions stored in custom DB table (wp_wddma_subscribers)
-   TTL-based control (show once every X days)

= Discount Banner =

-   Custom banner text & link
-   Auto-closes & respects TTL
-   Mobile-friendly responsive layout

= Admin Interface =

Inside Dashboard → WDD Marketing Automation, you can: * Set FCL rules *
Enable/disable modal or banner * Customize texts and links * View latest
newsletter subscribers

== Installation ==

1.  Upload the plugin to /wp-content/plugins/wdd-marketing-automation
2.  Activate via Plugins → Installed Plugins
3.  Go to WDD Marketing Automation in the admin menu
4.  Configure settings

== External services ==

This plugin uses the external service ipapi (https://ipapi.co/) to resolve the visitor's country
from their IP address. This is used to improve marketing automation behavior and defaults.

Data sent:
- The visitor's IP address when a request is made.

Purpose:
- To detect the visitor's country so the plugin can apply location-specific behavior.

Links:
- ipapi Terms of Service: https://ipapi.co/terms/
- ipapi Privacy Policy: https://ipapi.co/privacy/

== Technical details ==

This plugin uses the "infobipcth/forms-computed-language" library, which
requires PHP 8.2 or newer. For that reason, this plugin also requires PHP 8.2+.

== Frequently Asked Questions ==

= How often are rules evaluated? = Every 5 seconds in the frontend.

= Where are subscribers stored? = In the custom database table
wp_wddma_subscribers.

== Screenshots == 1. Admin interface 2. Newsletter modal 3. Discount
banner

== Changelog ==

= 1.0.0 = * Initial release.

== Upgrade Notice ==

= 1.0.0 = First public release.

== REST API ==

All routes prefixed with /wp-json/ma/v1

Endpoints: * POST /track — store user metrics * POST /evaluate —
evaluate FCL rules * POST /subscribe — store email

== Database ==

Creates table: wp_wddma_subscribers

Columns: * id BIGINT auto-increment * email VARCHAR * created_at
DATETIME

== Privacy ==

Plugin uses: * Metrics cookie * Visits cookie * LocalStorage for TTL *
Optional country lookup via IP

No external data sharing except IP geolocation.
