<?php
/* Template Name: About */
defined('ABSPATH') || exit;
get_header();
?>

<main class="hf-about-page">
    <section class="hf-hero hf-hero-sm hf-bg-primary">
        <div class="hf-container">
            <h1 class="hf-hero-title">About HireFlow</h1>
            <p class="hf-hero-subtitle">Empowering job seekers to manage their career journey with clarity and confidence.</p>
        </div>
    </section>

    <section class="hf-section-padding">
        <div class="hf-container hf-text-center" style="max-width: 800px;">
            <h2 class="hf-section-title">Our Mission</h2>
            <p class="hf-text-lg hf-text-muted" style="line-height: 1.8;">
                The job search process is often chaotic, involving dozens of tabs, scattered spreadsheets, and missed follow-ups. We built HireFlow to solve this problem. Our mission is to provide a clean, intuitive platform that brings order to the chaos, helping you focus on what really matters: landing your dream job.
            </p>
        </div>
    </section>

    <!-- features block -->
    <section class="hf-section-padding hf-bg-alt">
        <div class="hf-container">
            <h2 class="hf-section-title">Why Choose HireFlow?</h2>
            <div class="hf-grid-layout" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
                <div class="hf-card hf-text-center hf-p-4">
                    <span class="dashicons dashicons-shield hf-text-accent" style="font-size: 40px; width: 40px; height: 40px; margin-bottom: 20px;"></span>
                    <h3>Privacy First</h3>
                    <p class="hf-text-muted hf-mt-2">Your data belongs to you. We don't sell your application data to third parties or recruiters.</p>
                </div>
                <div class="hf-card hf-text-center hf-p-4">
                    <span class="dashicons dashicons-performance hf-text-accent" style="font-size: 40px; width: 40px; height: 40px; margin-bottom: 20px;"></span>
                    <h3>Fast & Intuitive</h3>
                    <p class="hf-text-muted hf-mt-2">Built with modern tech to ensure a snappy experience without unnecessary bloat.</p>
                </div>
                <div class="hf-card hf-text-center hf-p-4">
                    <span class="dashicons dashicons-chart-area hf-text-accent" style="font-size: 40px; width: 40px; height: 40px; margin-bottom: 20px;"></span>
                    <h3>Actionable Insights</h3>
                    <p class="hf-text-muted hf-mt-2">Visual analytics help you understand your conversion rates and identify bottlenecks.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- team members -->
    <section class="hf-section-padding">
        <div class="hf-container">
            <h2 class="hf-section-title">Meet the Team</h2>
            <div class="hf-grid-layout" style="grid-template-columns: repeat(3, 1fr);">
                <div class="hf-card hf-text-center hf-p-4">
                    <div class="hf-avatar hf-avatar-large hf-mx-auto hf-mb-3">NS</div>
                    <h3>Naman Shetty</h3>
                    <div class="hf-text-accent hf-mb-2">Founder & Lead Developer</div>
                    <p class="hf-text-sm hf-text-muted">Passionate about building tools that solve real-world problems.</p>
                </div>
                <!-- TODO: populate with actual members if we add more to the team -->
                <div class="hf-card hf-text-center hf-p-4">
                    <div class="hf-avatar hf-avatar-large hf-mx-auto hf-mb-3">JD</div>
                    <h3>Jane Doe</h3>
                    <div class="hf-text-accent hf-mb-2">UX Designer</div>
                    <p class="hf-text-sm hf-text-muted">Obsessed with creating seamless and beautiful user experiences.</p>
                </div>
                <div class="hf-card hf-text-center hf-p-4">
                    <div class="hf-avatar hf-avatar-large hf-mx-auto hf-mb-3">JS</div>
                    <h3>John Smith</h3>
                    <div class="hf-text-accent hf-mb-2">Product Manager</div>
                    <p class="hf-text-sm hf-text-muted">Ensuring we build features that our users actually need.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- tech stack -->
    <section class="hf-section-padding hf-bg-alt">
        <div class="hf-container hf-text-center">
            <h2 class="hf-section-title">Built With Modern Tech</h2>
            <p class="hf-text-muted hf-mb-4">HireFlow leverages the power of WordPress combined with modern frontend technologies.</p>
            <div class="hf-flex-center" style="gap: 20px; flex-wrap: wrap;">
                <span class="hf-badge hf-badge-large">WordPress</span>
                <span class="hf-badge hf-badge-large">PHP 8</span>
                <span class="hf-badge hf-badge-large">Vanilla JS</span>
                <span class="hf-badge hf-badge-large">CSS3</span>
                <span class="hf-badge hf-badge-large">Chart.js</span>
            </div>
        </div>
    </section>

    <!-- final call to action -->
    <section class="hf-section-padding">
        <div class="hf-container hf-text-center">
            <h2>Ready to take control of your career?</h2>
            <div class="hf-mt-4">
                <a href="<?= esc_url(wp_registration_url()) ?>" class="hf-btn hf-btn-primary hf-btn-large">Start Tracking Today</a>
            </div>
        </div>
    </section>
</main>

<style>
.hf-hero-sm { padding: 60px 0; }
.hf-avatar-large { width: 80px; height: 80px; font-size: 24px; border-radius: 50%; background: #1E3A5F; color: #00C9A7; display: flex; align-items: center; justify-content: center; }
.hf-badge-large { padding: 8px 16px; font-size: 1rem; background: #112236; border: 1px solid #1E3A5F; color: #E8F0FE; }
.hf-p-4 { padding: 24px; }
.hf-mx-auto { margin-left: auto; margin-right: auto; }
.hf-mb-2 { margin-bottom: 8px; }
.hf-mb-3 { margin-bottom: 16px; }
.hf-mb-4 { margin-bottom: 24px; }
.hf-flex-center { display: flex; justify-content: center; align-items: center; }
</style>

<?php get_footer(); ?>
