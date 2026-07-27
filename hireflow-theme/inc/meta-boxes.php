<?php
// Custom Fields & Meta Boxes for Applications
defined('ABSPATH') || exit;

function hf_add_application_meta_boxes() {
    add_meta_box('hf_application_details', 'Application Details', 'hf_render_application_details_meta_box', 'hireflow_application', 'normal', 'high');
    add_meta_box('hf_hr_contact', 'HR Contact', 'hf_render_hr_contact_meta_box', 'hireflow_application', 'normal', 'default');
}
add_action('add_meta_boxes', 'hf_add_application_meta_boxes');

function hf_render_application_details_meta_box($post) {
    wp_nonce_field('hf_save_application_meta_data', 'hf_application_meta_nonce');

    $company_name = get_post_meta($post->ID, 'hf_company_name', true);
    $company_logo = get_post_meta($post->ID, 'hf_company_logo_url', true);
    $job_title = get_post_meta($post->ID, 'hf_job_title', true);
    $job_location = get_post_meta($post->ID, 'hf_job_location', true);
    $work_mode = get_post_meta($post->ID, 'hf_work_mode', true);
    $app_date = get_post_meta($post->ID, 'hf_application_date', true);
    $interview_date = get_post_meta($post->ID, 'hf_interview_date', true);
    $job_link = get_post_meta($post->ID, 'hf_job_link', true);
    $notes = get_post_meta($post->ID, 'hf_personal_notes', true);
    $resume_id = get_post_meta($post->ID, 'hf_resume_attachment_id', true);
    $resume_filename = get_post_meta($post->ID, 'hf_resume_filename', true);
    ?>
    <style>
        .hf-meta-group { margin-bottom: 15px; font-family: Inter, sans-serif; }
        .hf-meta-group label { display: block; font-weight: 600; margin-bottom: 5px; color: #1E3A5F; }
        .hf-meta-group input[type="text"], .hf-meta-group input[type="url"], .hf-meta-group input[type="date"], .hf-meta-group select, .hf-meta-group textarea { width: 100%; max-width: 400px; padding: 6px; border: 1px solid #8BA3C7; border-radius: 6px; }
        .hf-meta-group textarea { height: 100px; }
        .hf-btn { margin-top: 5px; background: #00C9A7; color: #fff; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; }
        .hf-btn:hover { background: #00A589; }
    </style>
    
    <div class="hf-meta-group">
        <label>Company Name</label>
        <input type="text" name="hf_company_name" value="<?= esc_attr($company_name) ?>">
    </div>
    
    <div class="hf-meta-group">
        <label>Company Logo URL</label>
        <input type="url" id="hf_company_logo_url" name="hf_company_logo_url" value="<?= esc_attr($company_logo) ?>">
        <button type="button" class="hf-btn hf-media-upload" data-target="#hf_company_logo_url">Choose Logo</button>
    </div>
    
    <div class="hf-meta-group">
        <label>Job Title</label>
        <input type="text" name="hf_job_title" value="<?= esc_attr($job_title) ?>">
    </div>
    
    <div class="hf-meta-group">
        <label>Job Location</label>
        <input type="text" name="hf_job_location" value="<?= esc_attr($job_location) ?>">
    </div>
    
    <div class="hf-meta-group">
        <label>Work Mode</label>
        <select name="hf_work_mode">
            <option value="Onsite" <?php selected($work_mode, 'Onsite') ?>>Onsite</option>
            <option value="Hybrid" <?php selected($work_mode, 'Hybrid') ?>>Hybrid</option>
            <option value="Remote" <?php selected($work_mode, 'Remote') ?>>Remote</option>
        </select>
    </div>
    
    <div class="hf-meta-group">
        <label>Application Date</label>
        <input type="date" name="hf_application_date" value="<?= esc_attr($app_date) ?>">
    </div>
    
    <div class="hf-meta-group">
        <label>Interview Date</label>
        <input type="date" name="hf_interview_date" value="<?= esc_attr($interview_date) ?>">
    </div>
    
    <div class="hf-meta-group">
        <label>Job Link</label>
        <input type="url" name="hf_job_link" value="<?= esc_url($job_link) ?>">
    </div>
    
    <div class="hf-meta-group">
        <label>Personal Notes</label>
        <textarea name="hf_personal_notes"><?= esc_textarea($notes) ?></textarea>
    </div>
    
    <div class="hf-meta-group">
        <label>Resume</label>
        <input type="hidden" id="hf_resume_attachment_id" name="hf_resume_attachment_id" value="<?= esc_attr($resume_id) ?>">
        <input type="text" id="hf_resume_filename" name="hf_resume_filename" value="<?= esc_attr($resume_filename) ?>" readonly placeholder="No resume selected">
        <button type="button" class="hf-btn hf-resume-upload">Choose Resume</button>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        let uploader;
        $('.hf-media-upload').on('click', function(e) {
            e.preventDefault();
            let target = $(this).data('target');
            if (uploader) { uploader.open(); return; }
            uploader = wp.media({ title: 'Choose Logo', button: { text: 'Select' }, multiple: false });
            uploader.on('select', function() {
                let attachment = uploader.state().get('selection').first().toJSON();
                $(target).val(attachment.url);
            });
            uploader.open();
        });
        
        let resumeUploader;
        $('.hf-resume-upload').on('click', function(e) {
            e.preventDefault();
            if (resumeUploader) { resumeUploader.open(); return; }
            resumeUploader = wp.media({ title: 'Choose Resume', button: { text: 'Select' }, multiple: false });
            resumeUploader.on('select', function() {
                let file = resumeUploader.state().get('selection').first().toJSON();
                $('#hf_resume_attachment_id').val(file.id);
                $('#hf_resume_filename').val(file.filename);
            });
            resumeUploader.open();
        });
    });
    </script>
    <?php
}

function hf_render_hr_contact_meta_box($post) {
    $hr_name = get_post_meta($post->ID, 'hf_hr_name', true);
    $hr_email = get_post_meta($post->ID, 'hf_hr_email', true);
    ?>
    <style>
        .hf-meta-group { margin-bottom: 15px; font-family: Inter, sans-serif; }
        .hf-meta-group label { display: block; font-weight: 600; margin-bottom: 5px; color: #1E3A5F; }
        .hf-meta-group input[type="text"], .hf-meta-group input[type="email"] { width: 100%; max-width: 400px; padding: 6px; border: 1px solid #8BA3C7; border-radius: 6px; }
    </style>
    
    <div class="hf-meta-group">
        <label>HR Name</label>
        <input type="text" name="hf_hr_name" value="<?= esc_attr($hr_name) ?>">
    </div>
    
    <div class="hf-meta-group">
        <label>HR Email</label>
        <input type="email" name="hf_hr_email" value="<?= esc_attr($hr_email) ?>">
    </div>
    <?php
}

function hf_save_application_meta($post_id) {
    if (!isset($_POST['hf_application_meta_nonce']) || !wp_verify_nonce($_POST['hf_application_meta_nonce'], 'hf_save_application_meta_data')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

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
        'hf_resume_attachment_id' => 'absint',
        'hf_resume_filename' => 'sanitize_text_field',
        'hf_hr_name' => 'sanitize_text_field',
        'hf_hr_email' => 'sanitize_email'
    ];

    // this is a bit verbose but does the job safely
    foreach ($fields as $field => $func) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, $func($_POST[$field]));
        } else {
            delete_post_meta($post_id, $field);
        }
    }
}
add_action('save_post_hireflow_application', 'hf_save_application_meta');
