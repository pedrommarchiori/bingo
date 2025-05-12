<?php
/**
 * Bingo: Festa da Padroeira - WordPress Embed Helper
 * 
 * This file can be included in a WordPress page or post using:
 * [bingo_app] shortcode or PHP include()/require()
 */

// Ensure we're running within WordPress
if (!defined('ABSPATH')) {
    die('Direct access not allowed.');
}

// Create shortcode for WordPress
if (!function_exists('bingo_app_shortcode')) {
    function bingo_app_shortcode($atts) {
        // Parse attributes with defaults
        $atts = shortcode_atts(array(
            'height' => '800px',
            'width' => '100%',
        ), $atts);
        
        // Buffer the output
        ob_start();
        
        // Include the main app, it will detect WordPress
        include_once 'index.php';
        
        // Return the buffered content
        return ob_get_clean();
    }
    
    // Register the shortcode
    add_shortcode('bingo_app', 'bingo_app_shortcode');
}

// Enqueue necessary scripts for WordPress
if (!function_exists('bingo_app_enqueue_scripts')) {
    function bingo_app_enqueue_scripts() {
        // Check if current page contains the shortcode
        global $post;
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'bingo_app')) {
            // Register custom scripts as needed
            wp_enqueue_script('bingo-app-integration', plugin_dir_url(__FILE__) . 'wp-integration.js', array('jquery'), '1.0.0', true);
            
            // Add fullscreen toggle handlers
            wp_add_inline_script('bingo-app-integration', '
                jQuery(document).ready(function($) {
                    // Listen for fullscreen toggle events
                    $(document).on("bingo-toggle-fullscreen", function() {
                        $("body").toggleClass("bingo-fullscreen");
                    });
                });
            ');
        }
    }
    
    // Hook into WordPress
    add_action('wp_enqueue_scripts', 'bingo_app_enqueue_scripts');
}

// Add necessary styles
if (!function_exists('bingo_app_add_styles')) {
    function bingo_app_add_styles() {
        // Check if current page contains the shortcode
        global $post;
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'bingo_app')) {
            echo '<style>
                body.bingo-fullscreen #wpadminbar,
                body.bingo-fullscreen #masthead,
                body.bingo-fullscreen #colophon,
                body.bingo-fullscreen .site-header,
                body.bingo-fullscreen .site-footer,
                body.bingo-fullscreen aside,
                body.bingo-fullscreen .sidebar {
                    display: none !important;
                }
                body.bingo-fullscreen .bingo-app-container {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100vw;
                    height: 100vh;
                    z-index: 999999;
                }
                .bingo-app-container {
                    width: 100%;
                    margin: 20px 0;
                }
            </style>';
        }
    }
    
    // Hook into WordPress
    add_action('wp_head', 'bingo_app_add_styles');
}