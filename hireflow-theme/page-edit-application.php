<?php
/* Template Name: Edit Application */
defined('ABSPATH') || exit;

if (!is_user_logged_in()) {
    auth_redirect();
}

$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$post = get_post($post_id);

// quick sanity check
if (!$post || $post->post_type !== 'hireflow_application' || $post->post_author != get_current_user_id()) {
    wp_die('Invalid application or permission denied.');
}

// grab all meta fields
$company = get_post_meta($post_id, '_hf_company_name', true);
$location = get_post_meta($post_id, '_hf_job_location', true);
$mode = get_post_meta($post_id, '_hf_work_mode', true);
$app_date = get_post_meta($post_id, '_hf_application_date', true);
$int_date = get_post_meta($post_id, '_hf_interview_date', true);
$job_link = get_post_meta($post_id, '_hf_job_link', true);
$logo = get_post_meta($post_id, '_hf_company_logo', true);
$hr_name = get_post_meta($post_id, '_hf_hr_name', true);
$hr_email = get_post_meta($post_id, '_hf_hr_email', true);
$notes = get_post_meta($post_id, '_hf_notes', true);
$resume_url = get_post_meta($post_id, '_hf_resume_url', true);

$terms = wp_get_post_terms($post_id, 'application_status');
$current_status_id = !empty($terms) ? $terms[0]->term_id : 0;

get_header();
?>

<div class="hf-app-layout">
    <?php get_sidebar(); ?>

    <main class="hf-app-content">
        <header class="hf-page-header">
            <div>
                <h1 class="hf-page-title">Edit Application</h1>
                <div class="hf-text-sm hf-text-muted hf-mt-1">Last modified: <?= get_the_modified_date('F j, Y g:i a', $post_id) ?></div>
            </div>
            <a href="<?= esc_url(site_url('/applications/')) ?>" class="hf-btn hf-btn-secondary">Cancel</a>
        </header>

        <div class="hf-card">
            <form id="hf-edit-app-form" class="hf-form" enctype="multipart/form-data">
                <?php wp_nonce_field('hf_update_application_action', 'hf_update_application_nonce'); ?>
                <input type="hidden" name="action" value="hf_update_application">
                <input type="hidden" name="post_id" value="<?= esc_attr($post_id) ?>">

                <div class="hf-form-grid-2">
                    <div class="hf-form-col">
                        <div class="hf-form-group">
                            <label for="company_name">Company Name <span class="hf-text-danger">*</span></label>
                            <input type="text" id="company_name" name="company_name" class="hf-input" value="<?= esc_attr($company) ?>" required>
                        </div>

                        <div class="hf-form-group">
                            <label for="job_title">Job Title <span class="hf-text-danger">*</span></label>
                            <input type="text" id="job_title" name="job_title" class="hf-input" value="<?= esc_attr($post->post_title) ?>" required>
                        </div>

                        <div class="hf-form-group">
                            <label for="job_location">Job Location</label>
                            <input type="text" id="job_location" name="job_location" class="hf-input" value="<?= esc_attr($location) ?>">
                        </div>

                        <div class="hf-form-group">
                            <label for="work_mode">Work Mode</label>
                            <select id="work_mode" name="work_mode" class="hf-select">
                                <option value="Remote" <?php selected($mode, 'Remote'); ?>>Remote</option>
                                <option value="Hybrid" <?php selected($mode, 'Hybrid'); ?>>Hybrid</option>
                                <option value="Onsite" <?php selected($mode, 'Onsite'); ?>>Onsite</option>
                            </select>
                        </div>

                        <div class="hf-form-group">
                            <label for="application_date">Application Date</label>
                            <input type="date" id="application_date" name="application_date" class="hf-input" value="<?= esc_attr($app_date) ?>">
                        </div>
                        
                        <div class="hf-form-group">
                            <label for="interview_date">Interview Date</label>
                            <input type="datetime-local" id="interview_date" name="interview_date" class="hf-input" value="<?= esc_attr($int_date) ?>">
                        </div>

                        <div class="hf-form-group">
                            <label for="job_link">Job Link (URL)</label>
                            <input type="url" id="job_link" name="job_link" class="hf-input" value="<?= esc_url($job_link) ?>">
                        </div>
                    </div>

                    <div class="hf-form-col">
                        <div class="hf-form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="hf-select">
                                <?php
                                $all_terms = get_terms(['taxonomy' => 'application_status', 'hide_empty' => false]);
                                foreach ($all_terms as $t) {
                                    $sel = ($t->term_id == $current_status_id) ? 'selected' : '';
                                    echo '<option value="' . esc_attr($t->term_id) . '" ' . $sel . '>' . esc_html($t->name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="hf-form-group">
                            <label for="company_logo">Company Logo URL</label>
                            <input type="url" id="company_logo" name="company_logo" class="hf-input" value="<?= esc_url($logo) ?>">
                        </div>

                        <div class="hf-form-group">
                            <label for="hr_name">HR/Recruiter Name</label>
                            <input type="text" id="hr_name" name="hr_name" class="hf-input" value="<?= esc_attr($hr_name) ?>">
                        </div>

                        <div class="hf-form-group">
                            <label for="hr_email">HR/Recruiter Email</label>
                            <input type="email" id="hr_email" name="hr_email" class="hf-input" value="<?= esc_attr($hr_email) ?>">
                        </div>

                        <div class="hf-form-group">
                            <label for="resume_file">Resume Upload (.pdf, .doc, .docx)</label>
                            <?php if ($resume_url): ?>
                                <div class="hf-mb-2">
                                    <span class="hf-text-sm hf-text-muted">Current file: <a href="<?= esc_url($resume_url) ?>" target="_blank">View Resume</a></span>
                                </div>
                            <?php endif; ?>
                            <input type="file" id="resume_file" name="resume_file" class="hf-input hf-file-input" accept=".pdf,.doc,.docx">
                            <div class="hf-text-sm hf-text-muted hf-mt-1">Upload a new file to replace the current one.</div>
                        </div>

                        <div class="hf-form-group">
                            <label for="personal_notes">Personal Notes</label>
                            <textarea id="personal_notes" name="personal_notes" class="hf-textarea" rows="4"><?= esc_textarea($notes) ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="hf-form-actions hf-mt-4 hf-pt-4 hf-border-top">
                    <button type="submit" class="hf-btn hf-btn-primary hf-btn-large" id="hf-submit-btn">
                        Update Application
                    </button>
                    <div id="hf-form-message" class="hf-mt-2"></div>
                </div>
            </form>
        </div>
    </main>
</div>

<?php get_footer(); ?>
