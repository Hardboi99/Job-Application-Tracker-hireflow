<?php
/* Template Name: HireFlow Dashboard */
defined('ABSPATH') || exit;

if (!is_user_logged_in()) {
    auth_redirect();
}

get_header();

$user = wp_get_current_user();
$first_name = $user->user_firstname ?: $user->display_name;
?>

<div class="hf-app-layout">
    <?php get_sidebar(); // layout needs the sidebar here ?>

    <main class="hf-app-content">
        <header class="hf-page-header">
            <h1 class="hf-page-title">Welcome back, <?= esc_html($first_name) ?>!</h1>
            <div class="hf-page-actions">
                <a href="<?= esc_url(site_url('/add-application/')) ?>" class="hf-btn hf-btn-primary">
                    <span class="dashicons dashicons-plus"></span> New Application
                </a>
            </div>
        </header>

        <div class="hf-dashboard-grid">
            <!-- stats row -->
            <div class="hf-stat-cards">
                <div class="hf-card hf-stat-card hf-border-top-blue">
                    <div class="hf-stat-icon"><span class="dashicons dashicons-portfolio"></span></div>
                    <div class="hf-stat-content">
                        <span class="hf-stat-value" id="hf-stat-total">0</span>
                        <span class="hf-stat-label">Total Applications</span>
                    </div>
                </div>
                <div class="hf-card hf-stat-card hf-border-top-amber">
                    <div class="hf-stat-icon"><span class="dashicons dashicons-calendar-alt"></span></div>
                    <div class="hf-stat-content">
                        <span class="hf-stat-value" id="hf-stat-interviews">0</span>
                        <span class="hf-stat-label">Interviews Scheduled</span>
                    </div>
                </div>
                <div class="hf-card hf-stat-card hf-border-top-green">
                    <div class="hf-stat-icon"><span class="dashicons dashicons-awards"></span></div>
                    <div class="hf-stat-content">
                        <span class="hf-stat-value" id="hf-stat-offers">0</span>
                        <span class="hf-stat-label">Offers Received</span>
                    </div>
                </div>
                <div class="hf-card hf-stat-card hf-border-top-red">
                    <div class="hf-stat-icon"><span class="dashicons dashicons-dismiss"></span></div>
                    <div class="hf-stat-content">
                        <span class="hf-stat-value" id="hf-stat-rejections">0</span>
                        <span class="hf-stat-label">Rejections</span>
                    </div>
                </div>
            </div>

            <!-- charts block -->
            <div class="hf-charts-container">
                <div class="hf-card hf-chart-card">
                    <h3 class="hf-card-title">Status Distribution</h3>
                    <div class="hf-chart-wrapper" id="hf-status-chart-container">
                        <!-- JS handles the canvas inject -->
                    </div>
                </div>
                <div class="hf-card hf-chart-card">
                    <h3 class="hf-card-title">Applications (Last 6 Months)</h3>
                    <div class="hf-chart-wrapper" id="hf-timeline-chart-container">
                    </div>
                </div>
            </div>

            <div class="hf-dashboard-bottom-grid">
                <div class="hf-card hf-upcoming-interviews">
                    <h3 class="hf-card-title">Upcoming Interviews</h3>
                    <div class="hf-interviews-list" id="hf-upcoming-interviews-list">
                        <p class="hf-text-muted hf-text-center hf-py-4">Loading...</p>
                    </div>
                </div>

                <div class="hf-card hf-recent-applications">
                    <div class="hf-card-header hf-flex-between">
                        <h3 class="hf-card-title">Recent Applications</h3>
                        <a href="<?= esc_url(site_url('/applications/')) ?>" class="hf-text-link">View All</a>
                    </div>
                    <div class="hf-table-responsive" id="hf-recent-applications-table">
                        <p class="hf-text-muted hf-text-center hf-py-4">Loading...</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php get_footer(); ?>
