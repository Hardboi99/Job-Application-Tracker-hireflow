<?php
// Frontend AJAX handlers for HireFlow
defined('ABSPATH') || exit;

function hf_ajax_search_applications() {
    check_ajax_referer('hf_ajax_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error('Not logged in');
    
    $search = isset($_POST['s']) ? sanitize_text_field($_POST['s']) : '';
    
    $query = new WP_Query([
        'post_type' => 'hireflow_application',
        'author' => get_current_user_id(),
        's' => $search,
        'posts_per_page' => -1
    ]);
    
    $apps = [];
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();
            $apps[] = [
                'id' => $id,
                'title' => get_the_title(),
                'company' => get_post_meta($id, 'hf_company_name', true),
                'job_title' => get_post_meta($id, 'hf_job_title', true),
                'status' => wp_get_post_terms($id, 'application_status', ['fields' => 'names']),
            ];
        }
    }
    wp_reset_postdata();
    wp_send_json_success($apps);
}
add_action('wp_ajax_hf_search_applications', 'hf_ajax_search_applications');

function hf_ajax_filter_by_status() {
    check_ajax_referer('hf_ajax_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error('Not logged in');

    $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
    $args = [
        'post_type' => 'hireflow_application',
        'author' => get_current_user_id(),
        'posts_per_page' => -1
    ];

    if ($status && $status !== 'all') {
        $args['tax_query'] = [
            [
                'taxonomy' => 'application_status',
                'field' => 'slug',
                'terms' => $status
            ]
        ];
    }

    $query = new WP_Query($args);
    $apps = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();
            $apps[] = [
                'id' => $id,
                'title' => get_the_title(),
                'company' => get_post_meta($id, 'hf_company_name', true),
                'job_title' => get_post_meta($id, 'hf_job_title', true),
                'status' => wp_get_post_terms($id, 'application_status', ['fields' => 'names']),
            ];
        }
    }
    wp_reset_postdata();
    wp_send_json_success($apps);
}
add_action('wp_ajax_hf_filter_by_status', 'hf_ajax_filter_by_status');

function hf_ajax_delete_application() {
    check_ajax_referer('hf_ajax_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error('Not logged in');

    $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
    
    if (!$post_id || !hf_is_application_owner($post_id)) {
        wp_send_json_error('Permission denied or invalid post');
    }

    $deleted = wp_delete_post($post_id, false); // false = move to trash
    
    if ($deleted) wp_send_json_success('Deleted');
    else wp_send_json_error('Failed to delete');
}
add_action('wp_ajax_hf_delete_application', 'hf_ajax_delete_application');

function hf_ajax_get_stats() {
    if (!is_user_logged_in()) wp_send_json_error('Not logged in');

    $stats = [
        'total' => 0, 'applied' => 0, 'interview' => 0,
        'rejected' => 0, 'offer' => 0, 'accepted' => 0
    ];

    $query = new WP_Query([
        'post_type' => 'hireflow_application',
        'author' => get_current_user_id(),
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ]);
    
    if ($query->have_posts()) {
        $stats['total'] = $query->found_posts;
        while ($query->have_posts()) {
            $query->the_post();
            $terms = wp_get_post_terms(get_the_ID(), 'application_status', ['fields' => 'slugs']);
            
            if (!empty($terms) && !is_wp_error($terms)) {
                $status = $terms[0];
                if ($status === 'interview-scheduled') $stats['interview']++;
                elseif ($status === 'offer-received') $stats['offer']++;
                elseif (isset($stats[$status])) $stats[$status]++;
            }
        }
    }
    wp_reset_postdata();
    wp_send_json_success($stats);
}
add_action('wp_ajax_hf_get_stats', 'hf_ajax_get_stats');

function hf_ajax_add_application() {
    check_ajax_referer('hf_ajax_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error('Not logged in');

    $job_title = isset($_POST['hf_job_title']) ? sanitize_text_field($_POST['hf_job_title']) : 'New App';
    $company_name = isset($_POST['hf_company_name']) ? sanitize_text_field($_POST['hf_company_name']) : 'Unknown';
    
    $post_id = wp_insert_post([
        'post_title' => "$job_title at $company_name",
        'post_type' => 'hireflow_application',
        'post_status' => 'publish',
        'post_author' => get_current_user_id()
    ]);

    if (is_wp_error($post_id)) wp_send_json_error('Failed to create');

    $fields = [
        'hf_company_name' => 'sanitize_text_field',
        'hf_company_logo_url' => 'esc_url_raw',
        'hf_job_title' => 'sanitize_text_field',
        'hf_job_location' => 'sanitize_text_field',
        'hf_work_mode' => 'sanitize_text_field',
        'hf_application_date' => 'sanitize_text_field',
        'hf_interview_date' => 'sanitize_text_field',
        'hf_job_link' => 'esc_url_raw',
        'hf_personal_notes' => 'sanitize_textarea_field',
        'hf_hr_name' => 'sanitize_text_field',
        'hf_hr_email' => 'sanitize_email'
    ];

    foreach ($fields as $field => $func) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, $func($_POST[$field]));
        }
    }

    $status = isset($_POST['application_status']) ? sanitize_text_field($_POST['application_status']) : 'applied';
    wp_set_object_terms($post_id, $status, 'application_status');

    // handle resume upload if present
    if (!empty($_FILES['hf_resume']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attach_id = media_handle_upload('hf_resume', $post_id);
        if (!is_wp_error($attach_id)) {
            update_post_meta($post_id, 'hf_resume_attachment_id', $attach_id);
            update_post_meta($post_id, 'hf_resume_filename', sanitize_file_name($_FILES['hf_resume']['name']));
        }
    }

    wp_send_json_success(['post_id' => $post_id]);
}
add_action('wp_ajax_hf_add_application', 'hf_ajax_add_application');

function hf_ajax_update_application() {
    check_ajax_referer('hf_ajax_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error('Not logged in');

    $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
    if (!$post_id || !hf_is_application_owner($post_id)) {
        wp_send_json_error('Permission denied');
    }

    $job_title = isset($_POST['hf_job_title']) ? sanitize_text_field($_POST['hf_job_title']) : get_post_meta($post_id, 'hf_job_title', true);
    $company_name = isset($_POST['hf_company_name']) ? sanitize_text_field($_POST['hf_company_name']) : get_post_meta($post_id, 'hf_company_name', true);
    
    wp_update_post([
        'ID' => $post_id,
        'post_title' => "$job_title at $company_name"
    ]);

    $fields = [
        'hf_company_name' => 'sanitize_text_field',
        'hf_company_logo_url' => 'esc_url_raw',
        'hf_job_title' => 'sanitize_text_field',
        'hf_job_location' => 'sanitize_text_field',
        'hf_work_mode' => 'sanitize_text_field',
        'hf_application_date' => 'sanitize_text_field',
        'hf_interview_date' => 'sanitize_text_field',
        'hf_job_link' => 'esc_url_raw',
        'hf_personal_notes' => 'sanitize_textarea_field',
        'hf_hr_name' => 'sanitize_text_field',
        'hf_hr_email' => 'sanitize_email'
    ];

    foreach ($fields as $field => $func) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, $func($_POST[$field]));
        }
    }

    if (isset($_POST['application_status'])) {
        wp_set_object_terms($post_id, sanitize_text_field($_POST['application_status']), 'application_status');
    }

    // handle new resume upload
    if (!empty($_FILES['hf_resume']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attach_id = media_handle_upload('hf_resume', $post_id);
        if (!is_wp_error($attach_id)) {
            update_post_meta($post_id, 'hf_resume_attachment_id', $attach_id);
            update_post_meta($post_id, 'hf_resume_filename', sanitize_file_name($_FILES['hf_resume']['name']));
        }
    }

    wp_send_json_success(['post_id' => $post_id]);
}
add_action('wp_ajax_hf_update_application', 'hf_ajax_update_application');
