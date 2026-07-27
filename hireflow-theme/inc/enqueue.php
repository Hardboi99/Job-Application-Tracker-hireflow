<?php
/**
 * Enqueue scripts and styles
 * 
 * @package HireFlow
 */

defined('ABSPATH') || exit;

function hf_enqueue_assets() {
    $version = wp_get_theme()->get('Version');

    // Font Awesome CDN
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');

    // Main Style
    wp_enqueue_style('hf-style', get_stylesheet_uri(), array(), $version);

    // Component CSS
    wp_enqueue_style('hf-components', get_template_directory_uri() . '/assets/css/hireflow.css', array('hf-style'), $version);

    // Bootstrap JS CDN
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array(), '5.3.0', true);

    // Main JS
    wp_enqueue_script('hf-main-js', get_template_directory_uri() . '/assets/js/hireflow.js', array(), $version, true);

    // Localize main script
    wp_localize_script('hf-main-js', 'hfData', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('hireflow_ajax_nonce'),
    ));

    // Dashboard specific assets
    if (is_page_template('page-dashboard.php') || is_page('dashboard')) {
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '4.0.0', true);
        wp_enqueue_script('hf-dashboard-js', get_template_directory_uri() . '/assets/js/dashboard.js', array('chart-js', 'hf-main-js'), $version, true);
    }
}
add_action('wp_enqueue_scripts', 'hf_enqueue_assets');
