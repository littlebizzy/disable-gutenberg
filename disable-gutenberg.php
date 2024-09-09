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

// Disable Gutenberg editor for all post types, terms, and widgets globally
add_filter('use_block_editor_for_post', '__return_false', 10);
add_filter('use_block_editor_for_post_type', '__return_false', 10);
add_filter('gutenberg_use_widgets_block_editor', '__return_false');
add_filter('use_widgets_block_editor', '__return_false');
add_filter('use_block_editor_for_terms', '__return_false');

// Dequeue all Gutenberg-related scripts and styles from frontend and admin
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('wp-block-library');       // WordPress core block styles
    wp_dequeue_style('wp-block-library-theme'); // WordPress core block theme styles
    wp_dequeue_style('wc-block-style');         // WooCommerce block styles (if WooCommerce is installed)
    remove_action('wp_enqueue_scripts', 'wp_common_block_scripts_and_styles'); // Block editor styles/scripts
}, 20);

add_action('admin_enqueue_scripts', function() {
    wp_dequeue_script('wp-editor');  // Remove Gutenberg block editor scripts
}, 100);

// Remove any Gutenberg block-related dashboard widgets and admin menu items
add_action('wp_dashboard_setup', function() {
    remove_meta_box('dashboard_primary', 'dashboard', 'side'); // Gutenberg news widget
    remove_menu_page('gutenberg'); // Remove any Gutenberg-related admin menu items
});

// Disable REST API Gutenberg-related endpoints and JSON responses
add_filter('rest_endpoints', function($endpoints) {
    if (isset($endpoints['/wp/v2/block-renderer'])) {
        unset($endpoints['/wp/v2/block-renderer']); // Remove block renderer endpoint
    }
    return $endpoints;
});

// Disable block editor settings and assets globally in the backend
add_filter('block_editor_settings_all', function($settings, $post) {
    $settings['enableBlockDirectory'] = false;  // Disable block directory
    $settings['disableCustomColors'] = true;    // Disable custom colors
    $settings['disableCustomGradients'] = true; // Disable custom gradients
    $settings['disableCustomFontSizes'] = true; // Disable custom font sizes
    $settings['disableCustomLineHeight'] = true; // Disable custom line heights
    $settings['disableCustomUnits'] = true;     // Disable custom units
    return $settings;
}, 10, 2);

// Remove core block patterns and block templates globally
remove_theme_support('core-block-patterns');
remove_theme_support('block-templates');

// Disable Gutenberg block editor welcome screen
remove_action('welcome_panel', 'wp_welcome_panel');

// Completely disable any Gutenberg-related actions on admin init
add_action('admin_init', function() {
    remove_action('admin_init', 'gutenberg_add_post_type_templates'); // Prevent post-type templates
    remove_action('admin_init', 'gutenberg_add_widgets_block_editor'); // Prevent widgets block editor
}, 10);

// Conditionally disable WooCommerce block styles (if WooCommerce is installed)
if (class_exists('WooCommerce')) {
    add_action('wp_enqueue_scripts', function() {
        wp_dequeue_style('wc-block-style'); // WooCommerce block styles
    }, 100);
}

// Disable block editor settings in REST API JSON responses
add_filter('block_editor_rest_api_post_dispatch', '__return_false');

// Disable Gutenberg in the Customizer
add_action('customize_register', function() {
    remove_action('customize_controls_enqueue_scripts', 'wp_enqueue_block_editor_assets');
});

// Safeguard to dequeue any lingering Gutenberg-related assets from plugins
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_script('wp-blocks');      // Blocks script
    wp_dequeue_script('wp-dom-ready');   // DOM-ready script
    wp_dequeue_script('wp-edit-post');   // Edit post script
}, 100);

// Disable reusable blocks functionality
add_action('init', function() {
    unregister_post_type('wp_block');  // Reusable blocks post type
});

// Disable block-related metaboxes
add_action('add_meta_boxes', function() {
    remove_meta_box('block_editor_meta_box', null, 'normal');  // Remove any block editor metabox
});

// Disable Gutenberg in the media library
add_action('wp_enqueue_media', function() {
    wp_dequeue_script('wp-edit-post');  // Ensure no Gutenberg in media modals
});

// Disable legacy widgets block editor
add_filter('use_widgets_block_editor', '__return_false', 100);

// Remove Gutenberg-related Site Health checks
add_action('wp_site_health_scheduled_check', function() {
    remove_action('wp_site_health_scheduled_check', 'wp_block_editor_health_check', 10);
});

// Force classic editor for all user roles and post types
add_filter('use_classic_editor_for_post', '__return_true', 100);

// Ref: ChatGPT
