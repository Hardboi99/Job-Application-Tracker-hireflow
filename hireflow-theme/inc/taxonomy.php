<?php
// Register application status taxonomy and seed default terms
defined('ABSPATH') || exit;

function hf_register_status_taxonomy() {
    $labels = [
        'name'                       => 'Application Statuses',
        'singular_name'              => 'Application Status',
        'menu_name'                  => 'Statuses',
        'all_items'                  => 'All Statuses',
        'new_item_name'              => 'New Status Name',
        'add_new_item'               => 'Add New Status',
        'edit_item'                  => 'Edit Status',
        'update_item'                => 'Update Status',
        'view_item'                  => 'View Status',
        'search_items'               => 'Search Statuses',
        'not_found'                  => 'Not Found',
        'no_terms'                   => 'No statuses',
    ];
    
    register_taxonomy('application_status', ['hireflow_application'], [
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => false,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => false,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
        'rewrite'                    => false,
    ]);
}
add_action('init', 'hf_register_status_taxonomy');

// seed some default terms if they don't exist
function hf_seed_default_statuses() {
    $statuses = [
        'applied'              => 'Applied',
        'interview-scheduled'  => 'Interview Scheduled',
        'rejected'             => 'Rejected',
        'offer-received'       => 'Offer Received',
        'accepted'             => 'Accepted',
    ];
    
    foreach ($statuses as $slug => $name) {
        if (!term_exists($slug, 'application_status')) {
            wp_insert_term($name, 'application_status', ['slug' => $slug]);
        }
    }
}
add_action('init', 'hf_seed_default_statuses', 20); // run late to ensure tax is registered
