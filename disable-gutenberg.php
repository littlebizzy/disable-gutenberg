<?php
/*
Plugin Name: Disable Gutenberg
Plugin URI: https://www.littlebizzy.com/plugins/disable-gutenberg
Description: Completely disables the Gutenberg block editor and enables the classic WordPress post editor (TinyMCE aka WYSIWYG) for lighter coding and simplicity.
Version: 1.0.0
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
WC requires at least: 3.3
WC tested up to: 3.4
Prefix: DSBGTB
*/

// Plugin namespace
namespace LittleBizzy\DisableGutenberg;

// Aliased namespaces
use LittleBizzy\DisableGutenberg\Notices;

// Block direct calls
if (!function_exists('add_action')) {
	die;
}

// Plugin constants
const FILE = __FILE__;
const PREFIX = 'dsbgtb';
const VERSION = '1.0.0';

// Loader
require_once dirname(FILE).'/helpers/loader.php';

// Admin Notices
// Notices\Admin_Notices::instance(FILE);

/**
 * Admin Notices Multisite check
 * Uncomment "return;" to disable this plugin on Multisite installs
 */
if (false !== Notices\Admin_Notices_MS::instance(FILE)) { /* return; */ }

// Run the main class
Helpers\Runner::start('Core\Core', 'instance');
