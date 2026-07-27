<?php
/**
 * Footer Template
 * 
 * @package HireFlow
 */

defined('ABSPATH') || exit;
?>
</main><!-- #main-content -->

<footer class="hf-footer">
    <div class="hf-container">
        <div class="hf-grid hf-grid-3">
            <div class="hf-footer-brand">
                <h3 class="mb-3">HireFlow</h3>
                <p class="text-muted">The ultimate job application tracker. Stay organized and land your dream job.</p>
                <div class="hf-social-icons mt-3 d-flex gap-3">
                    <a href="#" class="text-muted"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-muted"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="text-muted"><i class="fab fa-github"></i></a>
                </div>
            </div>
            
            <div class="hf-footer-links">
                <h4 class="mb-3">Quick Links</h4>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'fallback_cb'    => false,
                    'container'      => false,
                    'menu_class'     => 'list-unstyled p-0 m-0'
                ));
                ?>
            </div>
            
            <div class="hf-footer-contact">
                <h4 class="mb-3">Contact</h4>
                <p class="text-muted mb-1"><i class="fas fa-envelope me-2"></i> hello@hireflow.app</p>
                <p class="text-muted"><i class="fas fa-location-dot me-2"></i> Remote, Worldwide</p>
            </div>
        </div>
        
        <div class="hf-footer-bottom mt-5 pt-4 border-top border-secondary text-center text-muted">
            <p class="mb-0">&copy; <?php echo esc_html(date('Y')); ?> HireFlow. All rights reserved.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
