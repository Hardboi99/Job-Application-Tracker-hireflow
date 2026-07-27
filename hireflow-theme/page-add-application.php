<?php
/* Template Name: Add Application */
defined('ABSPATH') || exit;

if (!is_user_logged_in()) {
    auth_redirect();
}

get_header();
?>

<div class="hf-app-layout">
    <?php get_sidebar(); ?>

    <main class="hf-app-content">
        <header class="hf-page-header">
            <h1 class="hf-page-title">Add New Application</h1>
            <a href="<?= esc_url(site_url('/applications/')) ?>" class="hf-btn hf-btn-secondary">Cancel</a>
        </header>

        <div class="hf-card">
            <form id="hf-add-app-form" class="hf-form" enctype="multipart/form-data">
                <?php wp_nonce_field('hf_add_application_action', 'hf_add_application_nonce'); ?>
                <input type="hidden" name="action" value="hf_add_application">

                <div class="hf-form-grid-2">
                    <div class="hf-form-col">
                        <div class="hf-form-group">
                            <label for="company_name">Company Name <span class="hf-text-danger">*</span></label>
                            <input type="text" id="company_name" name="company_name" class="hf-input" required>
                        </div>

                        <div class="hf-form-group">
                            <label for="job_title">Job Title <span class="hf-text-danger">*</span></label>
                            <input type="text" id="job_title" name="job_title" class="hf-input" required>
                        </div>

                        <div class="hf-form-group">
                            <label for="job_location">Job Location</label>
                            <input type="text" id="job_location" name="job_location" class="hf-input">
                        </div>

                        <div class="hf-form-group">
                            <label for="work_mode">Work Mode</label>
                            <select id="work_mode" name="work_mode" class="hf-select">
                                <option value="Remote">Remote</option>
                                <option value="Hybrid">Hybrid</option>
                                <option value="Onsite">Onsite</option>
                            </select>
                        </div>

                        <div class="hf-form-group">
                            <label for="application_date">Application Date</label>
                            <input type="date" id="application_date" name="application_date" class="hf-input" value="<?= date('Y-m-d') ?>">
                        </div>
                        
                        <div class="hf-form-group">
                            <label for="interview_date">Interview Date</label>
                            <input type="datetime-local" id="interview_date" name="interview_date" class="hf-input">
                        </div>

                        <div class="hf-form-group">
                            <label for="job_link">Job Link (URL)</label>
                            <input type="url" id="job_link" name="job_link" class="hf-input" placeholder="https://...">
                        </div>
                    </div>

                    <div class="hf-form-col">
                        <div class="hf-form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="hf-select">
                                <?php
                                $terms = get_terms(['taxonomy' => 'application_status', 'hide_empty' => false]);
                                foreach ($terms as $t) {
                                    $sel = ($t->slug === 'applied') ? 'selected' : '';
                                    echo '<option value="' . esc_attr($t->term_id) . '" ' . $sel . '>' . esc_html($t->name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="hf-form-group">
                            <label for="company_logo">Company Logo URL</label>
                            <input type="url" id="company_logo" name="company_logo" class="hf-input" placeholder="https://...logo.png">
                        </div>

                        <div class="hf-form-group">
                            <label for="hr_name">HR/Recruiter Name</label>
                            <input type="text" id="hr_name" name="hr_name" class="hf-input">
                        </div>

                        <div class="hf-form-group">
                            <label for="hr_email">HR/Recruiter Email</label>
                            <input type="email" id="hr_email" name="hr_email" class="hf-input">
                        </div>

                        <div class="hf-form-group">
                            <label for="resume_file">Resume Upload (.pdf, .doc, .docx)</label>
                            <input type="file" id="resume_file" name="resume_file" class="hf-input hf-file-input" accept=".pdf,.doc,.docx">
                        </div>

                        <div class="hf-form-group">
                            <label for="personal_notes">Personal Notes</label>
                            <textarea id="personal_notes" name="personal_notes" class="hf-textarea" rows="4"></textarea>
                            <div class="hf-text-sm hf-text-muted hf-text-right hf-mt-1"><span id="notes_counter">0</span>/500</div>
                        </div>
                    </div>
                </div>

                <div class="hf-form-actions hf-mt-4 hf-pt-4 hf-border-top">
                    <button type="submit" class="hf-btn hf-btn-primary hf-btn-large" id="hf-submit-btn">
                        Save Application
                    </button>
                    <div id="hf-form-message" class="hf-mt-2"></div>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const notes = document.getElementById('personal_notes');
    const counter = document.getElementById('notes_counter');
    
    if (notes && counter) {
        notes.addEventListener('input', function() {
            counter.textContent = this.value.length;
            // maybe add some styling if it hits 500 later
        });
    }

    const form = document.getElementById('hf-add-app-form');
    if(form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const btn = document.getElementById('hf-submit-btn');
            btn.innerHTML = '<span class="dashicons dashicons-update hf-spin"></span> Saving...';
            btn.disabled = true;
            
            // just a mock for now till backend is ready
            setTimeout(() => {
                document.getElementById('hf-form-message').innerHTML = '<span class="hf-text-success">Application saved successfully! Redirecting...</span>';
                setTimeout(() => {
                    window.location.href = '<?= esc_url(site_url('/applications/')) ?>';
                }, 1000);
            }, 800);
        });
    }
});
</script>

<?php get_footer(); ?>
