<?php
// Register the hireflow_application CPT
defined('ABSPATH') || exit;

function hf_register_application_cpt() {
    $labels = [
        'name'                  => 'Applications',
        'singular_name'         => 'Application',
        'menu_name'             => 'Applications',
        'name_admin_bar'        => 'Application',
        'all_items'             => 'All Applications',
        'add_new_item'          => 'Add New Application',
        'add_new'               => 'Add New',
        'new_item'              => 'New Application',
        'edit_item'             => 'Edit Application',
        'update_item'           => 'Update Application',
        'view_item'             => 'View Application',
        'search_items'          => 'Search Application',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in Trash',
    ];

    $args = [
        'label'                 => 'Application',
        'description'           => 'Job Applications Tracker',
        'labels'                => $labels,
        'supports'              => ['title', 'author', 'thumbnail', 'custom-fields'],
        'taxonomies'            => ['application_status'],
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-clipboard',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'rewrite'               => false,
        'capability_type'       => 'post',
        'map_meta_cap'          => true,
        'show_in_rest'          => true,
    ];

    register_post_type('hireflow_application', $args);
}
add_action('init', 'hf_register_application_cpt');

function hf_manage_application_posts_columns($columns) {
    return [
        'cb' => $columns['cb'],
        'title' => $columns['title'],
        'company' => 'Company',
        'job_title' => 'Job Title',
        'status' => 'Status',
        'date_applied' => 'Date Applied',
        'interview_date' => 'Interview Date',
        'author' => $columns['author'],
        'date' => $columns['date']
    ];
}
add_filter('manage_hireflow_application_posts_columns', 'hf_manage_application_posts_columns');

function hf_manage_application_posts_custom_column($column, $post_id) {
    switch ($column) {
        case 'company':
            echo esc_html(get_post_meta($post_id, 'hf_company_name', true));
            break;
        case 'job_title':
            echo esc_html(get_post_meta($post_id, 'hf_job_title', true));
            break;
        case 'status':
            $terms = get_the_terms($post_id, 'application_status');
            if ($terms && !is_wp_error($terms)) {
                $names = wp_list_pluck($terms, 'name');
                echo esc_html(implode(', ', $names));
            } else {
                echo '—';
            }
            break;
        case 'date_applied':
            $date = get_post_meta($post_id, 'hf_application_date', true);
            echo $date ? esc_html($date) : '—';
            break;
        case 'interview_date':
            $date = get_post_meta($post_id, 'hf_interview_date', true);
            echo $date ? esc_html($date) : '—';
            break;
    }
}
add_action('manage_hireflow_application_posts_custom_column', 'hf_manage_application_posts_custom_column', 10, 2);

function hf_manage_application_sortable_columns($columns) {
    $columns['status'] = 'status';
    $columns['date_applied'] = 'date_applied';
    $columns['interview_date'] = 'interview_date';
    return $columns;
}
add_filter('manage_edit-hireflow_application_sortable_columns', 'hf_manage_application_sortable_columns');
