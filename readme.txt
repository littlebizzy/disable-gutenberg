=== Disable Gutenberg ===

Contributors: littlebizzy
Donate link: https://www.patreon.com/littlebizzy
Tags: disable, remove, gutenberg, block, editor
Requires at least: 4.4
Tested up to: 5.0
Requires PHP: 7.2
Multisite support: No
Stable tag: 1.1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Prefix: DSBGTB

Completely disables the Gutenberg block editor and enables the classic WordPress post editor (TinyMCE aka WYSIWYG) for lighter coding and simplicity.

#### Current Features ####

* 100% disables the Gutenberg block editor in one click
* hides the Gutenberg admin notice (nag)
* no settings page or settings are required
* no database queries
* PHP-based only for faster performance (use PHP Opcache)
* unlike other plugins, users cannot toggle Gutenberg "on" for certain themes, posts, pages, or post types (it is 100% gone)
* unlike other plugins, the "Classic" editor code is not re-bundled (pointless, until WP Core ever deletes it completely which is not happening for a long time, so lighter code and less security concerns this way)
* can be used as a Must-Use plugin too to force users/clients to not see the Gutenberg editor

== Changelog ==

= 1.1.0 =
* PBP v1.2.0
* disabled `wp-block-library` CSS stylesheet from frontend context
* `DISABLE_GUTENBERG` defined constant (default = true)
* tested with WP 5.1

= 1.0.0 =
* initial release
* tested with PHP 5.6 (for fatal errors only)
* tested with PHP 7.0
* tested with PHP 7.1
* tested with PHP 7.2
* uses PHP namespaces
* object-oriented codebase
