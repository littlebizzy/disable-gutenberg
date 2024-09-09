<?php
/*
Plugin Name: Disable Gutenberg
Plugin URI: https://www.littlebizzy.com/plugins/disable-gutenberg
Description: Disables block editor entirely
Version: 2.0.0
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
GitHub Plugin URI: littlebizzy/disable-gutenberg
Primary Branch: master
Prefix: DSBGTB
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Disable WordPress.org updates for this plugin
add_filter('gu_override_dot_org', function ($overrides) {
    $overrides[] = 'disable-gutenberg/disable-gutenberg.php';
    return $overrides;
});

// Disable Gutenberg editor globally for all content
add_filter('use_block_editor_for_post', '__return_false', 10);
add_filter('use_block_editor_for_post_type', '__return_false', 10);
add_filter('gutenberg_use_widgets_block_editor', '__return_false');
add_filter('use_widgets_block_editor', '__return_false');
add_filter('use_block_editor_for_terms', '__return_false');

// Disable all Gutenberg scripts and styles (frontend and backend)
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('wp-block-library');       // Disable core block styles
    wp_dequeue_style('wp-block-library-theme'); // Disable block theme styles
    wp_dequeue_style('wc-block-style');         // Disable WooCommerce block styles
    remove_action('wp_enqueue_scripts', 'wp_common_block_scripts_and_styles'); // Remove inline block scripts/styles
}, 20);

add_action('admin_enqueue_scripts', function() {
    wp_dequeue_script('wp-editor');  // Disable Gutenberg block editor scripts
}, 100);

// Remove Gutenberg-related dashboard widgets and admin UI elements
add_action('wp_dashboard_setup', function() {
    remove_meta_box('dashboard_primary', 'dashboard', 'side'); // Disable Gutenberg news widget
    remove_menu_page('gutenberg'); // Remove any Gutenberg-related menu items
});

// Disable all Gutenberg REST API endpoints
add_filter('rest_endpoints', function($endpoints) {
    if (isset($endpoints['/wp/v2/block-renderer'])) {
        unset($endpoints['/wp/v2/block-renderer']); // Remove block renderer API
    }
    return $endpoints;
});

// Remove block editor settings globally
add_filter('block_editor_settings_all', function($settings, $post) {
    $settings['enableBlockDirectory'] = false;     // Disable block directory
    $settings['disableCustomColors'] = true;       // Disable custom colors
    $settings['disableCustomGradients'] = true;    // Disable custom gradients
    $settings['disableCustomFontSizes'] = true;    // Disable custom font sizes
    $settings['disableCustomLineHeight'] = true;   // Disable custom line heights
    $settings['disableCustomUnits'] = true;        // Disable custom units
    return $settings;
}, 10, 2);

// Remove block patterns and block templates
remove_theme_support('core-block-patterns');
remove_theme_support('block-templates');

// Disable block editor settings from appearing in REST API JSON responses
add_filter('block_editor_rest_api_post_dispatch', '__return_false');

// Disable Gutenberg welcome panel in WP Admin
remove_action('welcome_panel', 'wp_welcome_panel');

// Remove Gutenberg initialization from admin
add_action('admin_init', function() {
    remove_action('admin_init', 'gutenberg_add_post_type_templates');
    remove_action('admin_init', 'gutenberg_add_widgets_block_editor');
}, 10);

// Conditionally disable WooCommerce block styles (if WooCommerce is installed)
if (class_exists('WooCommerce')) {
    add_action('wp_enqueue_scripts', function() {
        wp_dequeue_style('wc-block-style'); // Disable WooCommerce block styles
    }, 100);
}

// Ref: ChatGPT
