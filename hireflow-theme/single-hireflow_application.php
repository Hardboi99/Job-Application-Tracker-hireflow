<?php
// Single Application View
defined('ABSPATH') || exit;

if (!is_user_logged_in()) {
    auth_redirect();
}

get_header();

while (have_posts()) : the_post();

// check if user actually owns this app
if (get_the_author_meta('ID') != get_current_user_id()) {
    wp_die('You do not have permission to view this application.');
}

$id = get_the_ID();
$company = get_post_meta($id, '_hf_company_name', true);
$location = get_post_meta($id, '_hf_job_location', true);
$mode = get_post_meta($id, '_hf_work_mode', true);
$applied = get_post_meta($id, '_hf_application_date', true);
$interview = get_post_meta($id, '_hf_interview_date', true);
$job_link = get_post_meta($id, '_hf_job_link', true);
$logo = get_post_meta($id, '_hf_company_logo', true);
$hr_name = get_post_meta($id, '_hf_hr_name', true);
$hr_email = get_post_meta($id, '_hf_hr_email', true);
$notes = get_post_meta($id, '_hf_notes', true);
$resume = get_post_meta($id, '_hf_resume_url', true);

$terms = wp_get_post_terms($id, 'application_status');
$status = !empty($terms) ? $terms[0]->name : 'Unknown';

// duplicated this helper, maybe move it to functions.php later
function hf_single_get_status_color($status_name) {
    $name = strtolower($status_name);
    if (strpos($name, 'applied') !== false) return '#3B82F6';
    if (strpos($name, 'interview') !== false) return '#F59E0B';
    if (strpos($name, 'offer') !== false) return '#10B981';
    if (strpos($name, 'reject') !== false) return '#EF4444';
    if (strpos($name, 'accept') !== false) return '#00C9A7';
    return '#8BA3C7';
}

$color = hf_single_get_status_color($status);
?>

<div class="hf-app-layout">
    <?php get_sidebar(); ?>

    <main class="hf-app-content">
        <header class="hf-page-header">
            <div>
                <a href="<?= esc_url(site_url('/applications/')) ?>" class="hf-text-link hf-text-sm">&larr; Back to Applications</a>
                <h1 class="hf-page-title hf-mt-2">Application Details</h1>
            </div>
            <div class="hf-page-actions">
                <a href="<?= esc_url(add_query_arg('id', $id, site_url('/edit-application/'))) ?>" class="hf-btn hf-btn-secondary">
                    <span class="dashicons dashicons-edit"></span> Edit
                </a>
            </div>
        </header>

        <div class="hf-grid-layout" style="grid-template-columns: 2fr 1fr; gap: 24px;">
            
            <div class="hf-flex-column" style="gap: 24px;">
                <div class="hf-card hf-detail-header">
                    <div class="hf-flex" style="gap: 24px; align-items: flex-start;">
                        <?php if ($logo): ?>
                            <img src="<?= esc_url($logo) ?>" alt="Company Logo" class="hf-company-logo-large" style="width: 80px; height: 80px; object-fit: contain; background: #fff; border-radius: 8px; padding: 5px;">
                        <?php else: ?>
                            <div class="hf-company-logo-placeholder" style="width: 80px; height: 80px; font-size: 32px; display: flex; align-items: center; justify-content: center; background: #1E3A5F; border-radius: 8px; color: #00C9A7; font-weight: bold;">
                                <?= esc_html(substr($company, 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div style="flex: 1;">
                            <h2 style="margin: 0 0 5px 0; font-size: 1.8rem;"><?php the_title(); ?></h2>
                            <h3 style="margin: 0 0 15px 0; color: #8BA3C7; font-weight: normal;"><?= esc_html($company) ?></h3>
                            
                            <div class="hf-flex" style="gap: 15px; flex-wrap: wrap;">
                                <span class="hf-badge" style="background-color: <?= $color ?>20; color: <?= $color ?>; border: 1px solid <?= $color ?>40; padding: 6px 12px; font-size: 14px;">
                                    <?= esc_html($status) ?>
                                </span>
                                <?php if ($location): ?>
                                    <span class="hf-text-muted hf-flex" style="align-items: center; gap: 5px;"><span class="dashicons dashicons-location"></span> <?= esc_html($location) ?></span>
                                <?php endif; ?>
                                <?php if ($mode): ?>
                                    <span class="hf-text-muted hf-flex" style="align-items: center; gap: 5px;"><span class="dashicons dashicons-admin-home"></span> <?= esc_html($mode) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hf-card">
                    <h3 class="hf-card-title hf-mb-4">Application Information</h3>
                    <div class="hf-grid-layout" style="grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <div class="hf-text-sm hf-text-muted hf-mb-1">Applied Date</div>
                            <div class="hf-text-lg"><?= $applied ? esc_html($applied) : '-' ?></div>
                        </div>
                        <div>
                            <div class="hf-text-sm hf-text-muted hf-mb-1">Interview Date</div>
                            <div class="hf-text-lg"><?= $interview ? esc_html(date('F j, Y g:i a', strtotime($interview))) : 'Not scheduled' ?></div>
                        </div>
                        <div style="grid-column: span 2;">
                            <div class="hf-text-sm hf-text-muted hf-mb-1">Job Link</div>
                            <div>
                                <?php if ($job_link): ?>
                                    <a href="<?= esc_url($job_link) ?>" target="_blank" class="hf-text-link"><?= esc_html($job_link) ?></a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- sidebar details -->
            <div class="hf-flex-column" style="gap: 24px;">
                <div class="hf-card">
                    <h3 class="hf-card-title hf-mb-4">HR / Recruiter</h3>
                    <div class="hf-flex-column" style="gap: 15px;">
                        <div>
                            <div class="hf-text-sm hf-text-muted hf-mb-1">Name</div>
                            <div><?= $hr_name ? esc_html($hr_name) : 'Not provided' ?></div>
                        </div>
                        <div>
                            <div class="hf-text-sm hf-text-muted hf-mb-1">Email</div>
                            <div>
                                <?php if ($hr_email): ?>
                                    <a href="mailto:<?= esc_attr($hr_email) ?>" class="hf-text-link"><?= esc_html($hr_email) ?></a>
                                <?php else: ?>
                                    Not provided
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hf-card">
                    <h3 class="hf-card-title hf-mb-4">Resume</h3>
                    <?php if ($resume): ?>
                        <div class="hf-flex" style="align-items: center; gap: 10px; background: #0D1B2A; padding: 15px; border-radius: 8px;">
                            <span class="dashicons dashicons-media-document hf-text-accent" style="font-size: 24px; width: 24px; height: 24px;"></span>
                            <div style="flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <a href="<?= esc_url($resume) ?>" target="_blank" class="hf-text-link">View Uploaded Resume</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="hf-text-muted">No resume uploaded.</p>
                    <?php endif; ?>
                </div>

                <div class="hf-card">
                    <h3 class="hf-card-title hf-mb-4">Personal Notes</h3>
                    <?php if ($notes): ?>
                        <div class="hf-text-content" style="white-space: pre-wrap; line-height: 1.6; color: #E8F0FE;"><?= esc_html($notes) ?></div>
                    <?php else: ?>
                        <p class="hf-text-muted">No notes added.</p>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </main>
</div>

<style>
.hf-flex { display: flex; }
.hf-flex-column { display: flex; flex-direction: column; }
.hf-mb-1 { margin-bottom: 4px; }
.hf-mb-4 { margin-bottom: 16px; }
.hf-mt-2 { margin-top: 8px; }
.hf-text-lg { font-size: 1.1rem; }
</style>

<?php 
endwhile;
get_footer(); 
?>
