<?php
/**
 * Security Helpers
 * 
 * @package HireFlow
 */

defined('ABSPATH') || exit;

function hf_sanitize_text($input) {
    return sanitize_text_field(trim($input));
}

function hf_sanitize_email($email) {
    return sanitize_email(trim($email));
}

function hf_sanitize_url($url) {
    return esc_url_raw(trim($url));
}

function hf_sanitize_int($num) {
    return absint($num);
}

function hf_verify_nonce($nonce, $action) {
    if (!isset($_REQUEST[$nonce]) || !wp_verify_nonce($_REQUEST[$nonce], $action)) {
        wp_die(__('Security check failed.', 'hireflow'));
    }
    return true;
}

function hf_check_user_can($cap) {
    if (!current_user_can($cap)) {
        wp_die(__('You do not have permission to access this page.', 'hireflow'));
    }
    return true;
}

function hf_is_application_owner($post_id) {
    $post = get_post($post_id);
    if (!$post) return false;
    
    $current_user_id = get_current_user_id();
    return (int) $post->post_author === (int) $current_user_id;
}

function hf_validate_file_upload($file) {
    $allowed_mimes = array(
        'pdf'  => 'application/pdf',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    );
    
    $file_info = wp_check_filetype(basename($file['name']), $allowed_mimes);
    
    if (empty($file_info['ext'])) {
        return new WP_Error('invalid_file_type', __('Invalid file type. Only PDF, DOC, and DOCX are allowed.', 'hireflow'));
    }
    
    $max_size = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $max_size) {
        return new WP_Error('file_too_large', __('File is too large. Maximum size is 5MB.', 'hireflow'));
    }
    
    return true;
}
