<?php
/*
Plugin Name: Disable Gutenberg
Plugin URI: https://www.littlebizzy.com/plugins/disable-gutenberg
Description: Completely disables the Gutenberg block editor and enables the classic WordPress post editor (TinyMCE aka WYSIWYG) for lighter coding and simplicity.
Version: 1.1.0
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
PBP Version: 1.2.0
WC requires at least: 3.3
WC tested up to: 3.5
Prefix: DSBGTB
*/

// Plugin namespace
namespace LittleBizzy\DisableGutenberg;

// Plugin constants
const FILE = __FILE__;
const PREFIX = 'dsbgtb';
const VERSION = '1.1.0';
const REPO = 'littlebizzy/disable-gutenberg';

// Boot
require_once dirname(FILE).'/helpers/boot.php';
Helpers\Boot::instance(FILE);