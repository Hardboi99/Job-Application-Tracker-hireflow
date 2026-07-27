<?php
/**
 * Register Roles
 * 
 * @package HireFlow
 */

defined('ABSPATH') || exit;

function hf_register_roles() {
    $role_name = 'hireflow_user';
    $display_name = 'HireFlow User';
    $capabilities = array(
        'read'         => true,
        'edit_posts'   => false, // Can't edit standard posts
        'delete_posts' => false,
    );

    // Check if role exists before adding
    if (!get_role($role_name)) {
        add_role($role_name, $display_name, $capabilities);
    }
}
add_action('init', 'hf_register_roles');

// Trigger on activation if possible
register_activation_hook(__FILE__, 'hf_register_roles');
