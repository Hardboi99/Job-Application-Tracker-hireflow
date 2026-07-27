<?php
/**
 * Header Template
 * 
 * @package HireFlow
 */

defined('ABSPATH') || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="hf-navbar">
    <div class="hf-container d-flex justify-content-between align-items-center">
        <div class="hf-brand">
            <?php
            if (has_custom_logo()) {
                the_custom_logo();
            } else {
                echo '<a href="' . esc_url(home_url('/')) . '" class="hf-brand-link">HireFlow</a>';
            }
            ?>
        </div>
        
        <button class="hf-mobile-toggle d-md-none" aria-label="Toggle Menu">
            <i class="fas fa-bars"></i>
        </button>

        <nav class="hf-nav-wrapper d-none d-md-flex align-items-center gap-4">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'fallback_cb'    => false,
                'container'      => false,
                'menu_class'     => 'hf-nav-links d-flex gap-3 m-0 p-0 list-unstyled'
            ));
            ?>
            
            <div class="hf-auth-area">
                <?php if (is_user_logged_in()) : 
                    $current_user = wp_get_current_user();
                ?>
                    <div class="hf-avatar-dropdown position-relative">
                        <img src="<?php echo esc_url(get_avatar_url($current_user->ID)); ?>" alt="<?php echo esc_attr($current_user->display_name); ?>" class="hf-avatar rounded-circle" width="40" height="40" style="cursor: pointer;">
                        <div class="hf-dropdown-menu position-absolute end-0 mt-2 d-none hf-card">
                            <a href="<?php echo esc_url(site_url('/dashboard/my-applications')); ?>" class="d-block py-2">My Applications</a>
                            <a href="<?php echo esc_url(site_url('/profile')); ?>" class="d-block py-2">Profile</a>
                            <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="d-block py-2 text-danger">Logout</a>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="d-flex gap-2">
                        <a href="<?php echo esc_url(wp_login_url()); ?>" class="hf-btn hf-btn-secondary">Login</a>
                        <a href="<?php echo esc_url(wp_registration_url()); ?>" class="hf-btn hf-btn-primary">Register</a>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>

<main id="main-content">
