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

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Disable WordPress.org updates for this plugin.
add_filter( 'gu_override_dot_org', function ( $overrides ) {
    $overrides[] = 'disable-gutenberg/disable-gutenberg.php';
    return $overrides;
});

// Disable Gutenberg editor globally for all post types, terms, and widgets.
add_filter( 'use_block_editor_for_post', '__return_false', 10 );
add_filter( 'use_block_editor_for_post_type', '__return_false', 10 );
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false', 10 );
add_filter( 'use_widgets_block_editor', '__return_false', 10 );
add_filter( 'use_block_editor_for_terms', '__return_false', 10 );

// Dequeue Gutenberg-related scripts and styles from both frontend and admin.
add_action( 'wp_enqueue_scripts', function() {
    wp_dequeue_style( 'wp-block-library' );         // WordPress core block styles.
    wp_dequeue_style( 'wp-block-library-theme' );   // WordPress core block theme styles.
    remove_action( 'wp_enqueue_scripts', 'wp_common_block_scripts_and_styles' ); // Block editor inline styles/scripts.
    wp_dequeue_script( 'wp-blocks' );               // Blocks script.
    wp_dequeue_script( 'wp-dom-ready' );            // DOM-ready script.
    wp_dequeue_script( 'wp-edit-post' );            // Edit post script.
}, 20 );

// Conditionally disable WooCommerce block styles and scripts (if WooCommerce is installed).
if ( class_exists( 'WooCommerce' ) ) {
    add_action( 'wp_enqueue_scripts', function() {
        wp_dequeue_style( 'wc-block-style' );    // WooCommerce block styles (frontend).
        wp_dequeue_script( 'wc-blocks' );        // WooCommerce block scripts (frontend).
    }, 100 );

    // Disable WooCommerce block editor assets in the admin area.
    add_action( 'admin_enqueue_scripts', function() {
        wp_dequeue_style( 'wc-block-editor' );   // WooCommerce block editor styles (backend).
        wp_dequeue_script( 'wc-blocks-editor' ); // WooCommerce block editor scripts (backend).
    }, 100 );
}

// Ensure Gutenberg editor assets are dequeued in the admin.
add_action( 'admin_enqueue_scripts', function() {
    wp_dequeue_style( 'wp-block-library' );         // WordPress core block styles in admin.
    wp_dequeue_script( 'wp-editor' );               // Remove Gutenberg block editor scripts.
}, 100 );

// Prevent Gutenberg block editor scripts from loading on post-editing pages.
add_action( 'enqueue_block_editor_assets', function() {
    wp_dequeue_script( 'wp-block-editor' );         // Dequeue block editor scripts.
    wp_dequeue_style( 'wp-block-editor' );          // Dequeue block editor styles.
}, 100 );

// Disable block editor settings in Classic Widgets screen.
add_action( 'admin_enqueue_scripts', function() {
    if ( is_admin() && isset( $_GET['page'] ) && 'widgets.php' === $_GET['page'] ) {
        wp_dequeue_script( 'wp-editor' );  // Dequeue block editor scripts on the widgets screen.
    }
}, 100 );

// Disable Gutenberg in the media library.
add_action( 'wp_enqueue_media', function() {
    wp_dequeue_script( 'wp-edit-post' );  // Ensure no Gutenberg in media modals.
});

// Remove any Gutenberg block-related dashboard widgets and admin menu items.
add_action( 'wp_dashboard_setup', function() {
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );  // Gutenberg news widget.
    remove_menu_page( 'gutenberg' );  // Remove any Gutenberg-related admin menu items.
});

// Disable REST API Gutenberg-related endpoints.
add_filter( 'rest_endpoints', function( $endpoints ) {
    if ( isset( $endpoints['/wp/v2/block-renderer'] ) ) {
        unset( $endpoints['/wp/v2/block-renderer'] );  // Remove block renderer endpoint.
    }
    return $endpoints;
});

// Disable Gutenberg block editor settings globally in the backend.
add_filter( 'block_editor_settings_all', function( $settings ) {
    $settings['enableBlockDirectory']    = false;  // Disable block directory.
    $settings['disableCustomColors']     = true;   // Disable custom colors.
    $settings['disableCustomGradients']  = true;   // Disable custom gradients.
    $settings['disableCustomFontSizes']  = true;   // Disable custom font sizes.
    $settings['disableCustomLineHeight'] = true;   // Disable custom line heights.
    $settings['disableCustomUnits']      = true;   // Disable custom units.
    return $settings;
}, 10, 2 );

// Remove core block patterns and templates globally.
remove_theme_support( 'core-block-patterns' );
remove_theme_support( 'block-templates' );

// Disable the Gutenberg block editor welcome screen.
remove_action( 'welcome_panel', 'wp_welcome_panel' );

// Disable Gutenberg-related actions during admin initialization.
add_action( 'admin_init', function() {
    remove_action( 'admin_init', 'gutenberg_add_post_type_templates' );  // Prevent post-type templates.
    remove_action( 'admin_init', 'gutenberg_add_widgets_block_editor' ); // Prevent widgets block editor.
}, 10 );

// Disable block editor settings in REST API JSON responses.
add_filter( 'block_editor_rest_api_post_dispatch', '__return_false' );

// Disable Gutenberg in the Customizer.
add_action( 'customize_register', function() {
    remove_action( 'customize_controls_enqueue_scripts', 'wp_enqueue_block_editor_assets' );
});

// Disable reusable blocks functionality.
add_action( 'init', function() {
    unregister_post_type( 'wp_block' );  // Reusable blocks post type.
});

// Disable Gutenberg-related metaboxes.
add_action( 'add_meta_boxes', function() {
    remove_meta_box( 'block_editor_meta_box', null, 'normal' );  // Remove block editor metabox.
});

// Disable legacy widgets block editor.
add_filter( 'use_widgets_block_editor', '__return_false', 100 );

// Remove Gutenberg-related Site Health checks.
add_action( 'wp_site_health_scheduled_check', function() {
    remove_action( 'wp_site_health_scheduled_check', 'wp_block_editor_health_check', 10 );
});

// Force classic editor for all user roles and post types.
add_filter( 'use_classic_editor_for_post', '__return_true', 100 );

// Prevent block-based themes from enabling block editor functionality.
add_action( 'after_setup_theme', function() {
    remove_theme_support( 'block-editor' );
    remove_theme_support( 'block-template-parts' );  // For FSE (Full Site Editing) templates.
}, 10 );

// Disable block editor filters/actions in REST API requests.
add_action( 'rest_api_init', function() {
    remove_action( 'rest_api_init', 'gutenberg_register_rest_routes' );  // Remove Gutenberg REST routes.
    remove_filter( 'rest_preload_paths', 'gutenberg_preload_paths' );    // Prevent block preloading.
}, 10 );

// Disable Gutenberg's theme.json support.
add_filter( 'should_load_separate_core_block_assets', '__return_false' );

// Disable Full Site Editing (FSE) features.
add_action( 'after_setup_theme', function() {
    remove_theme_support( 'block-templates' );  // Disable FSE template support.
});

// Disable block editor shortcodes.
remove_shortcode( 'block' );
remove_shortcode( 'wp-block' );

// Remove block categories.
add_filter( 'block_categories_all', function( $categories ) {
    return [];  // Return an empty array to remove all block categories.
}, 10, 1 );

// Unregister all core blocks.
add_action( 'init', function() {
    foreach ( WP_Block_Type_Registry::get_instance()->get_all_registered() as $block_type => $block ) {
        unregister_block_type( $block_type );  // Unregister all core blocks.
    }
}, 20 );

// Prevent block editor assets from preloading in REST API requests.
add_filter( 'rest_preload_paths', function( $preload_paths ) {
    return array_filter( $preload_paths, function( $path ) {
        return false === strpos( $path, '/wp/v2/block-editor' );
    } );
}, 10, 1 );

// Disable the Global Styles interface.
remove_action( 'admin_init', 'gutenberg_add_global_styles_panel' );

// Disable Gutenberg for Customizer Selective Refresh.
add_filter( 'customize_selective_refresh_block_editor', '__return_false' );

// Ref: ChatGPT
