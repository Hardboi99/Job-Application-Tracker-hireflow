<?php
/*
 * Admin dashboard view
 * Shows some stats and recent apps
 */
defined('ABSPATH') || exit;

// get count of all apps
$app_query = new WP_Query([
    'post_type' => 'hireflow_application',
    'posts_per_page' => -1,
    'post_status' => 'publish'
]);
$total_apps = $app_query->found_posts;

// get users who can actually do stuff
$users = get_users(['role__in' => ['subscriber', 'contributor', 'author', 'editor', 'administrator']]);
$user_count = count($users);

// recent ones
$recent = new WP_Query([
    'post_type' => 'hireflow_application',
    'posts_per_page' => 10,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
]);
?>

<div class="wrap hf-admin-wrap">
    <h1 class="wp-heading-inline">HireFlow Dashboard</h1>
    <hr class="wp-header-end">

    <div class="hf-welcome-banner">
        <h2>Welcome to HireFlow</h2>
        <p>Version <?php echo esc_html(HF_VERSION); ?></p>
    </div>

    <div class="hf-stat-cards">
        <div class="hf-stat-card">
            <div class="hf-stat-icon">📄</div>
            <div class="hf-stat-details">
                <span class="hf-stat-label">Total Applications</span>
                <span class="hf-stat-number"><?php echo esc_html($total_apps); ?></span>
            </div>
        </div>
        <div class="hf-stat-card">
            <div class="hf-stat-icon">👥</div>
            <div class="hf-stat-details">
                <span class="hf-stat-label">Active Users</span>
                <span class="hf-stat-number"><?php echo esc_html($user_count); ?></span>
            </div>
        </div>
        <div class="hf-stat-card">
            <div class="hf-stat-icon">📅</div>
            <div class="hf-stat-details">
                <span class="hf-stat-label">Interviews</span>
                <span class="hf-stat-number">--</span> <!-- TODO: count interviews -->
            </div>
        </div>
        <div class="hf-stat-card">
            <div class="hf-stat-icon">🎉</div>
            <div class="hf-stat-details">
                <span class="hf-stat-label">Offers</span>
                <span class="hf-stat-number">--</span> <!-- TODO: count offers -->
            </div>
        </div>
        <div class="hf-stat-card">
            <div class="hf-stat-icon">📈</div>
            <div class="hf-stat-details">
                <span class="hf-stat-label">Avg. Apps/User</span>
                <span class="hf-stat-number"><?php echo $user_count ? number_format($total_apps / $user_count, 1) : 0; ?></span>
            </div>
        </div>
    </div>

    <div class="hf-dashboard-content">
        <div class="hf-recent-apps">
            <h3>Recent Applications</h3>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Company</th>
                        <th>Job Title</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($recent->have_posts()) : ?>
                        <?php while ($recent->have_posts()) : $recent->the_post(); 
                            $company = get_post_meta(get_the_ID(), '_hf_company', true);
                            $author = get_userdata(get_post_field('post_author', get_the_ID()));
                            
                            // this is a bit annoying but we need the first term
                            $terms = wp_get_post_terms(get_the_ID(), 'application_status');
                            $status = !empty($terms) ? $terms[0]->name : 'Applied';
                        ?>
                            <tr>
                                <td><?php echo esc_html($author ? $author->display_name : 'Unknown'); ?></td>
                                <td><?php echo esc_html($company); ?></td>
                                <td><a href="<?php echo esc_url(get_edit_post_link()); ?>"><?php the_title(); ?></a></td>
                                <td><span class="hf-badge hf-badge-<?php echo esc_attr(sanitize_title($status)); ?>"><?php echo esc_html($status); ?></span></td>
                                <td><?php echo get_the_date(); ?></td>
                            </tr>
                        <?php endwhile; wp_reset_postdata(); ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5">No applications found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="hf-sidebar">
            <div class="hf-quick-actions">
                <h3>Quick Actions</h3>
                <a href="<?php echo esc_url(admin_url('edit.php?post_type=hireflow_application')); ?>" class="button button-primary button-large">View All</a>
                <a href="#" class="button button-secondary button-large" onclick="alert('Coming soon!')">Export</a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=hireflow-settings')); ?>" class="button button-secondary button-large">Settings</a>
            </div>

            <div class="hf-system-info">
                <h3>System Info</h3>
                <ul>
                    <li><strong>WordPress:</strong> <?php bloginfo('version'); ?></li>
                    <li><strong>PHP:</strong> <?php echo phpversion(); ?></li>
                    <li><strong>Plugin:</strong> <?php echo esc_html(HF_VERSION); ?></li>
                    <li><strong>Posts:</strong> <?php echo wp_count_posts()->publish; ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
