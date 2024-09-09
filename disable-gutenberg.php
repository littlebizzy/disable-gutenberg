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

// Disable Gutenberg editor site-wide
add_filter('use_block_editor_for_post', '__return_false', 10);

// Disable Gutenberg editor for post types
add_filter('use_block_editor_for_post_type', '__return_false', 10);

// Disable Gutenberg for widgets
add_filter('gutenberg_use_widgets_block_editor', '__return_false');
add_filter('use_widgets_block_editor', '__return_false');

// Optional: Hide the Gutenberg nag if you want a cleaner admin experience
remove_action('try_gutenberg_panel', 'wp_try_gutenberg_panel');

// Disable Gutenberg styles in the frontend
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('wp-block-library');       // WordPress core
    wp_dequeue_style('wp-block-library-theme'); // WordPress core
    wp_dequeue_style('wc-block-style');         // WooCommerce
}, 100);

// Disable Gutenberg dashboard widgets
add_action('wp_dashboard_setup', function() {
    remove_meta_box('dashboard_primary', 'dashboard', 'side'); // Gutenberg news
});

// Disable REST API Gutenberg endpoints
add_filter('rest_endpoints', function($endpoints) {
    if (isset($endpoints['/wp/v2/block-renderer'])) {
        unset($endpoints['/wp/v2/block-renderer']);
    }
    return $endpoints;
});

// Disable Gutenberg-related scripts in WP Admin
add_action('admin_enqueue_scripts', function() {
    wp_dequeue_script('wp-editor');  // Dequeue Gutenberg scripts
}, 100);

// Remove block patterns and block directory
remove_theme_support('core-block-patterns');
remove_theme_support('block-templates');

// Disable the block directory suggestions
add_filter('block_editor_settings_all', function($editor_settings) {
    $editor_settings['enableBlockDirectory'] = false;
    return $editor_settings;
});

// Ref: ChatGPT
