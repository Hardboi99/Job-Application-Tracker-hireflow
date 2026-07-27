<?php
/**
 * HireFlow Theme Functions
 * 
 * @package HireFlow
 */

defined('ABSPATH') || exit;

/**
 * Theme Setup
 */
function hf_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('custom-logo');

    register_nav_menus(array(
        'primary' => __('Primary Menu', 'hireflow'),
        'footer'  => __('Footer Menu', 'hireflow'),
    ));
}
add_action('after_setup_theme', 'hf_setup');

/**
 * Register Widget Areas
 */
function hf_widgets_init() {
    register_sidebar(array(
        'name'          => __('Primary Sidebar', 'hireflow'),
        'id'            => 'primary-sidebar',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Dashboard Sidebar', 'hireflow'),
        'id'            => 'dashboard-sidebar',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'hf_widgets_init');

/**
 * Require core files
 */
$hf_includes = array(
    'inc/enqueue.php',
    'inc/cpt.php',
    'inc/taxonomy.php',
    'inc/meta-boxes.php',
    'inc/roles.php',
    'inc/ajax.php',
    'inc/security.php'
);

foreach ($hf_includes as $file) {
    $filepath = get_template_directory() . '/' . $file;
    if (file_exists($filepath)) {
        require_once $filepath;
    }
}
