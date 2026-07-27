<?php
/* Template Name: My Applications */
defined('ABSPATH') || exit;

if (!is_user_logged_in()) {
    auth_redirect();
}

get_header();

$paged = get_query_var('paged') ?: 1;

$apps_query = new WP_Query([
    'post_type' => 'hireflow_application',
    'author' => get_current_user_id(),
    'posts_per_page' => 10,
    'paged' => $paged,
    'post_status' => 'publish',
]);

// helper for badge colors based on status name
function hf_get_status_color($status_name) {
    $name = strtolower($status_name);
    if (strpos($name, 'applied') !== false) return '#3B82F6';
    if (strpos($name, 'interview') !== false) return '#F59E0B';
    if (strpos($name, 'offer') !== false) return '#10B981';
    if (strpos($name, 'reject') !== false) return '#EF4444';
    if (strpos($name, 'accept') !== false) return '#00C9A7';
    return '#8BA3C7'; // fallback gray
}
?>

<div class="hf-app-layout">
    <?php get_sidebar(); ?>

    <main class="hf-app-content">
        <header class="hf-page-header">
            <h1 class="hf-page-title">My Applications</h1>
            <div class="hf-page-actions">
                <a href="<?= esc_url(site_url('/add-application/')) ?>" class="hf-btn hf-btn-primary">
                    <span class="dashicons dashicons-plus"></span> Add New
                </a>
            </div>
        </header>

        <!-- Filters -->
        <div class="hf-card hf-filter-bar">
            <form action="" method="GET" class="hf-filter-form">
                <div class="hf-filter-group hf-search-group">
                    <span class="dashicons dashicons-search"></span>
                    <input type="text" name="s" placeholder="Search by company or job title..." class="hf-input" value="<?= get_search_query() ?>">
                </div>
                
                <div class="hf-filter-group">
                    <select name="status" class="hf-select">
                        <option value="">All Statuses</option>
                        <?php
                        $terms = get_terms(['taxonomy' => 'application_status', 'hide_empty' => false]);
                        foreach ($terms as $term) {
                            $sel = isset($_GET['status']) && $_GET['status'] == $term->slug ? 'selected' : '';
                            echo '<option value="' . esc_attr($term->slug) . '" ' . $sel . '>' . esc_html($term->name) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="hf-filter-group">
                    <select name="sort" class="hf-select">
                        <option value="date_desc">Latest First</option>
                        <option value="date_asc">Oldest First</option>
                        <option value="title_asc">Company A-Z</option>
                    </select>
                </div>
                
                <div class="hf-view-toggles">
                    <button type="button" class="hf-view-btn active" data-view="table"><span class="dashicons dashicons-list-view"></span></button>
                    <button type="button" class="hf-view-btn" data-view="grid"><span class="dashicons dashicons-grid-view"></span></button>
                </div>
            </form>
        </div>

        <?php if ($apps_query->have_posts()) : ?>
            
            <!-- Table View -->
            <div class="hf-applications-view hf-view-table active" id="view-table">
                <div class="hf-card">
                    <div class="hf-table-responsive">
                        <table class="hf-table">
                            <thead>
                                <tr>
                                    <th>Company & Role</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Applied Date</th>
                                    <th>Interview</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($apps_query->have_posts()) : $apps_query->the_post(); 
                                    $id = get_the_ID();
                                    $company = get_post_meta($id, '_hf_company_name', true);
                                    $location = get_post_meta($id, '_hf_job_location', true);
                                    $mode = get_post_meta($id, '_hf_work_mode', true);
                                    $applied = get_post_meta($id, '_hf_application_date', true);
                                    $interview = get_post_meta($id, '_hf_interview_date', true);
                                    $logo = get_post_meta($id, '_hf_company_logo', true);
                                    
                                    // WP makes fetching terms a bit messy
                                    $terms = wp_get_post_terms($id, 'application_status');
                                    $status = !empty($terms) ? $terms[0]->name : 'Unknown';
                                    $color = hf_get_status_color($status);
                                ?>
                                <tr>
                                    <td>
                                        <div class="hf-table-cell-company">
                                            <?php if ($logo): ?>
                                                <img src="<?= esc_url($logo) ?>" alt="Logo" class="hf-company-logo-mini">
                                            <?php else: ?>
                                                <div class="hf-company-logo-placeholder"><?= esc_html(substr($company, 0, 1)) ?></div>
                                            <?php endif; ?>
                                            <div>
                                                <strong><?= esc_html($company) ?></strong>
                                                <div class="hf-text-sm hf-text-muted"><?php the_title(); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div><?= esc_html($location) ?></div>
                                        <div class="hf-text-sm hf-text-muted"><?= esc_html($mode) ?></div>
                                    </td>
                                    <td>
                                        <span class="hf-badge" style="background-color: <?= $color ?>20; color: <?= $color ?>; border: 1px solid <?= $color ?>40;">
                                            <?= esc_html($status) ?>
                                        </span>
                                    </td>
                                    <td><?= esc_html($applied) ?></td>
                                    <td><?= $interview ? esc_html($interview) : '-' ?></td>
                                    <td>
                                        <div class="hf-action-buttons">
                                            <a href="<?php the_permalink(); ?>" class="hf-btn-icon" title="View"><span class="dashicons dashicons-visibility"></span></a>
                                            <a href="<?= esc_url(add_query_arg('id', $id, site_url('/edit-application/'))) ?>" class="hf-btn-icon" title="Edit"><span class="dashicons dashicons-edit"></span></a>
                                            <button type="button" class="hf-btn-icon hf-text-danger hf-delete-app" data-id="<?= $id ?>" data-nonce="<?= wp_create_nonce('hf_delete_' . $id) ?>" title="Delete"><span class="dashicons dashicons-trash"></span></button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Grid View (hidden initally) -->
            <div class="hf-applications-view hf-view-grid" id="view-grid" style="display: none;">
                <div class="hf-grid-layout">
                    <?php 
                    // Rewind and reuse query
                    $apps_query->rewind_posts();
                    while ($apps_query->have_posts()) : $apps_query->the_post(); 
                        $id = get_the_ID();
                        $company = get_post_meta($id, '_hf_company_name', true);
                        $location = get_post_meta($id, '_hf_job_location', true);
                        $applied = get_post_meta($id, '_hf_application_date', true);
                        $logo = get_post_meta($id, '_hf_company_logo', true);
                        
                        $terms = wp_get_post_terms($id, 'application_status');
                        $status = !empty($terms) ? $terms[0]->name : 'Unknown';
                        $color = hf_get_status_color($status);
                    ?>
                    <div class="hf-card hf-app-card">
                        <div class="hf-app-card-header">
                            <div class="hf-app-card-company">
                                <?php if ($logo): ?>
                                    <img src="<?= esc_url($logo) ?>" alt="Logo" class="hf-company-logo-small">
                                <?php else: ?>
                                    <div class="hf-company-logo-placeholder"><?= esc_html(substr($company, 0, 1)) ?></div>
                                <?php endif; ?>
                                <div>
                                    <h3 class="hf-app-card-title"><?php the_title(); ?></h3>
                                    <p class="hf-app-card-subtitle"><?= esc_html($company) ?></p>
                                </div>
                            </div>
                            <div class="hf-app-card-actions dropdown">
                                <span class="dashicons dashicons-ellipsis"></span>
                            </div>
                        </div>
                        <div class="hf-app-card-body">
                            <div class="hf-app-card-meta">
                                <span class="dashicons dashicons-location"></span> <?= esc_html($location) ?>
                            </div>
                            <div class="hf-app-card-meta">
                                <span class="dashicons dashicons-calendar"></span> Applied: <?= esc_html($applied) ?>
                            </div>
                        </div>
                        <div class="hf-app-card-footer">
                            <span class="hf-badge" style="background-color: <?= $color ?>20; color: <?= $color ?>; border: 1px solid <?= $color ?>40;">
                                <?= esc_html($status) ?>
                            </span>
                            <a href="<?php the_permalink(); ?>" class="hf-text-link">View Details</a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="hf-pagination">
                <?php
                echo paginate_links([
                    'total' => $apps_query->max_num_pages,
                    'current' => $paged,
                    'prev_text' => '&laquo; Prev',
                    'next_text' => 'Next &raquo;',
                ]);
                ?>
            </div>

        <?php else : ?>
            <div class="hf-empty-state hf-card hf-text-center">
                <div class="hf-empty-icon">
                    <span class="dashicons dashicons-portfolio" style="font-size: 64px; width: 64px; height: 64px; color: #1E3A5F;"></span>
                </div>
                <h2>No applications yet!</h2>
                <p class="hf-text-muted">Start tracking your job search by adding your first application.</p>
                <a href="<?= esc_url(site_url('/add-application/')) ?>" class="hf-btn hf-btn-primary hf-mt-4">Add First Application</a>
            </div>
        <?php endif; wp_reset_postdata(); ?>
    </main>
</div>

<script>
// TODO: move to a separate JS file
document.addEventListener('DOMContentLoaded', () => {
    const btns = document.querySelectorAll('.hf-view-btn');
    const tb = document.getElementById('view-table');
    const gd = document.getElementById('view-grid');

    btns.forEach(btn => {
        btn.addEventListener('click', function() {
            btns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            if (this.dataset.view === 'table') {
                if(tb) tb.style.display = 'block';
                if(gd) gd.style.display = 'none';
            } else {
                if(tb) tb.style.display = 'none';
                if(gd) gd.style.display = 'block';
            }
        });
    });

    document.querySelectorAll('.hf-delete-app').forEach(btn => {
        btn.addEventListener('click', function() {
            if(confirm('Are you sure you want to delete this application?')) {
                // TODO: hook up real ajax deletion
                console.log('Delete app', this.dataset.id);
            }
        });
    });
});
</script>

<?php get_footer(); ?>
