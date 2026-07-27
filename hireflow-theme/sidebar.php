<?php
/**
 * Sidebar Template (Dashboard)
 * 
 * @package HireFlow
 */

defined('ABSPATH') || exit;

if (!is_user_logged_in()) {
    return;
}

$current_user = wp_get_current_user();
?>
<aside class="hf-sidebar hf-card d-none d-lg-block">
    <div class="hf-sidebar-user text-center mb-4 pb-3 border-bottom border-secondary">
        <img src="<?php echo esc_url(get_avatar_url($current_user->ID, ['size' => 80])); ?>" alt="Avatar" class="rounded-circle mb-3 border border-2 border-primary">
        <h5 class="mb-1"><?php echo esc_html($current_user->display_name); ?></h5>
        <small class="text-muted"><?php echo esc_html($current_user->user_email); ?></small>
    </div>
    
    <nav class="hf-sidebar-nav">
        <ul class="list-unstyled p-0 m-0 d-flex flex-column gap-2">
            <li>
                <a href="<?php echo esc_url(site_url('/dashboard')); ?>" class="d-flex align-items-center gap-3 p-2 rounded hf-sidebar-link">
                    <i class="fas fa-chart-pie w-20px text-center"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?php echo esc_url(site_url('/dashboard/my-applications')); ?>" class="d-flex align-items-center gap-3 p-2 rounded hf-sidebar-link">
                    <i class="fas fa-briefcase w-20px text-center"></i> My Applications
                </a>
            </li>
            <li>
                <a href="<?php echo esc_url(site_url('/dashboard/add-application')); ?>" class="d-flex align-items-center gap-3 p-2 rounded hf-sidebar-link">
                    <i class="fas fa-plus w-20px text-center"></i> Add Application
                </a>
            </li>
            <li>
                <a href="<?php echo esc_url(site_url('/profile')); ?>" class="d-flex align-items-center gap-3 p-2 rounded hf-sidebar-link">
                    <i class="fas fa-user w-20px text-center"></i> Profile
                </a>
            </li>
            <li class="mt-4">
                <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="d-flex align-items-center gap-3 p-2 rounded text-danger hf-sidebar-link">
                    <i class="fas fa-sign-out-alt w-20px text-center"></i> Logout
                </a>
            </li>
        </ul>
    </nav>
</aside>
