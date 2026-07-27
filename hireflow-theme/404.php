<?php
/**
 * 404 Error Template
 * 
 * @package HireFlow
 */

defined('ABSPATH') || exit;

get_header(); ?>

<div class="hf-container my-5 py-5 text-center">
    <div class="hf-404-wrapper mx-auto" style="max-width: 600px;">
        <h1 class="display-1 fw-bold mb-4" style="color: var(--hf-accent); font-size: 8rem;">404</h1>
        <h2 class="mb-4">Page Not Found</h2>
        <p class="text-muted mb-5">Oops! The page you are looking for does not exist. It might have been moved or deleted.</p>
        
        <a href="<?php echo esc_url(home_url('/')); ?>" class="hf-btn hf-btn-primary px-4 py-2">
            <i class="fas fa-home me-2"></i> Back to Home
        </a>
    </div>
</div>

<?php
get_footer();
