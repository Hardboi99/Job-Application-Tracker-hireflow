<?php
// Front Page Template
defined('ABSPATH') || exit;
get_header(); 
?>

<main class="hf-front-page">
    <section class="hf-hero">
        <div class="hf-hero-bg">
            <div class="hf-orb hf-orb-1"></div>
            <div class="hf-orb hf-orb-2"></div>
        </div>
        <div class="hf-container">
            <h1 class="hf-hero-title hf-animate-fade-in">Track Your Job Search. <br><span class="hf-gradient-text">Land Your Dream Job.</span></h1>
            <p class="hf-hero-subtitle hf-animate-fade-in delay-1">The all-in-one platform to organize applications, prepare for interviews, and accelerate your career growth.</p>
            <div class="hf-hero-cta hf-animate-fade-in delay-2">
                <a href="<?= esc_url(wp_registration_url()) ?>" class="hf-btn hf-btn-primary">Get Started Free</a>
                <a href="#features" class="hf-btn hf-btn-secondary">View Demo</a>
            </div>
        </div>
    </section>

    <section class="hf-stats">
        <div class="hf-container hf-stats-grid">
            <div class="hf-stat-item hf-animate-fade-in">
                <h3 class="hf-stat-number">500+</h3>
                <p class="hf-stat-label">Job Seekers</p>
            </div>
            <div class="hf-stat-item hf-animate-fade-in delay-1">
                <h3 class="hf-stat-number">2000+</h3>
                <p class="hf-stat-label">Applications Tracked</p>
            </div>
            <div class="hf-stat-item hf-animate-fade-in delay-2">
                <h3 class="hf-stat-number">89%</h3>
                <p class="hf-stat-label">Success Rate</p>
            </div>
        </div>
    </section>

    <section id="features" class="hf-features hf-section-padding">
        <div class="hf-container">
            <h2 class="hf-section-title hf-animate-fade-in">Everything you need to succeed</h2>
            <div class="hf-features-grid">
                <?php
                // Just hardcoding these for now, maybe move to ACF later?
                $features = [
                    ['icon' => 'dashicons-dashboard', 'title' => 'Smart Dashboard', 'desc' => 'Get a bird\'s-eye view of your entire job search journey in one place.'],
                    ['icon' => 'dashicons-portfolio', 'title' => 'Application Tracking', 'desc' => 'Keep tabs on every job you apply for, with complete historical data.'],
                    ['icon' => 'dashicons-calendar-alt', 'title' => 'Interview Management', 'desc' => 'Never miss an interview with built-in scheduling and reminders.'],
                    ['icon' => 'dashicons-chart-pie', 'title' => 'Status Tracking', 'desc' => 'Easily move applications through customized stages from applied to hired.'],
                    ['icon' => 'dashicons-media-document', 'title' => 'Resume Upload', 'desc' => 'Attach specific resumes and cover letters to individual applications.'],
                    ['icon' => 'dashicons-search', 'title' => 'Search & Filter', 'desc' => 'Quickly find past applications using our advanced search system.']
                ];

                foreach ($features as $i => $feat) :
                ?>
                <div class="hf-feature-card hf-card hf-animate-fade-in delay-<?= esc_attr($i % 3) ?>">
                    <div class="hf-feature-icon">
                        <span class="dashicons <?= esc_attr($feat['icon']) ?>"></span>
                    </div>
                    <h3 class="hf-feature-title"><?= esc_html($feat['title']) ?></h3>
                    <p class="hf-feature-desc"><?= esc_html($feat['desc']) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="hf-how-it-works hf-section-padding hf-bg-alt">
        <div class="hf-container">
            <h2 class="hf-section-title hf-animate-fade-in">How HireFlow Works</h2>
            <div class="hf-steps-container">
                <div class="hf-step hf-animate-fade-in">
                    <div class="hf-step-number">1</div>
                    <div class="hf-step-content">
                        <h3>Add Applications</h3>
                        <p>Log details of every job you apply for, including company, role, and the resume you used.</p>
                    </div>
                </div>
                <div class="hf-step hf-animate-fade-in delay-1">
                    <div class="hf-step-number">2</div>
                    <div class="hf-step-content">
                        <h3>Update Statuses</h3>
                        <p>Move your applications through pipeline stages as you progress in the interview process.</p>
                    </div>
                </div>
                <div class="hf-step hf-animate-fade-in delay-2">
                    <div class="hf-step-number">3</div>
                    <div class="hf-step-content">
                        <h3>Land Offers</h3>
                        <p>Analyze your success rate, compare offers, and accept your dream job with confidence.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hf-testimonials hf-section-padding">
        <div class="hf-container">
            <h2 class="hf-section-title hf-animate-fade-in">Loved by Job Seekers</h2>
            <div class="hf-testimonials-grid">
                <?php
                $testimonials = [
                    ['name' => 'Sarah J.', 'role' => 'Software Engineer', 'quote' => 'HireFlow completely transformed my job search. No more messy spreadsheets!', 'initials' => 'SJ'],
                    ['name' => 'Michael T.', 'role' => 'Product Manager', 'quote' => 'The dashboard gives me perfect visibility into my pipeline. Highly recommended.', 'initials' => 'MT'],
                    ['name' => 'Elena R.', 'role' => 'UX Designer', 'quote' => 'Keeping track of different resumes for different roles is finally easy.', 'initials' => 'ER']
                ];
                foreach ($testimonials as $idx => $t) :
                ?>
                <div class="hf-testimonial-card hf-card hf-animate-fade-in delay-<?= esc_attr($idx) ?>">
                    <div class="hf-testimonial-content">
                        <p>"<?= esc_html($t['quote']) ?>"</p>
                    </div>
                    <div class="hf-testimonial-author">
                        <div class="hf-avatar"><?= esc_html($t['initials']) ?></div>
                        <div class="hf-author-info">
                            <h4><?= esc_html($t['name']) ?></h4>
                            <span><?= esc_html($t['role']) ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Bottom CTA -->
    <section class="hf-bottom-cta hf-section-padding hf-bg-primary">
        <div class="hf-container hf-text-center hf-animate-fade-in">
            <h2>Ready to organize your job search?</h2>
            <p>Join hundreds of job seekers who use HireFlow to land their dream roles.</p>
            <a href="<?= esc_url(wp_registration_url()) ?>" class="hf-btn hf-btn-accent hf-btn-large">Start Tracking Today</a>
        </div>
    </section>
</main>

<style>
/* TODO: move to a proper stylesheet once we setup webpack */
.hf-front-page { color: #E8F0FE; background-color: #06101A; }
.hf-container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
.hf-section-padding { padding: 80px 0; }
.hf-text-center { text-align: center; }
.hf-bg-alt { background-color: #0D1B2A; }
.hf-bg-primary { background-color: #1E3A5F; }
.hf-card { background-color: #112236; border-radius: 12px; padding: 24px; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s ease; }
.hf-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
.hf-btn { display: inline-block; padding: 12px 24px; border-radius: 6px; font-weight: 600; text-decoration: none; transition: all 0.3s ease; }
.hf-btn-primary { background-color: #00C9A7; color: #06101A; }
.hf-btn-primary:hover { background-color: #00A589; }
.hf-btn-secondary { background-color: transparent; border: 2px solid #00C9A7; color: #00C9A7; }
.hf-btn-secondary:hover { background-color: rgba(0,201,167,0.1); }
.hf-btn-accent { background-color: #00C9A7; color: #06101A; }
.hf-hero { position: relative; padding: 120px 0 80px; text-align: center; overflow: hidden; }
.hf-hero-title { font-size: 3.5rem; line-height: 1.2; margin-bottom: 20px; font-weight: 800; }
.hf-gradient-text { background: linear-gradient(90deg, #00C9A7, #3B82F6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.hf-hero-subtitle { font-size: 1.2rem; color: #8BA3C7; max-width: 600px; margin: 0 auto 40px; }
.hf-hero-cta { display: flex; gap: 16px; justify-content: center; }
.hf-stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; text-align: center; padding: 40px 0; border-top: 1px solid #1E3A5F; border-bottom: 1px solid #1E3A5F; }
.hf-stat-number { font-size: 2.5rem; color: #00C9A7; margin: 0 0 10px; }
.hf-section-title { text-align: center; font-size: 2.5rem; margin-bottom: 50px; }
.hf-features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
.hf-feature-icon { font-size: 2rem; color: #00C9A7; margin-bottom: 20px; }
.hf-feature-title { margin-bottom: 15px; font-size: 1.2rem; }
.hf-feature-desc { color: #8BA3C7; line-height: 1.6; }
.hf-steps-container { display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; position: relative; }
.hf-step { text-align: center; }
.hf-step-number { width: 50px; height: 50px; background-color: #00C9A7; color: #06101A; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin: 0 auto 20px; }
.hf-step-content h3 { margin-bottom: 15px; }
.hf-step-content p { color: #8BA3C7; }
.hf-testimonials-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
.hf-testimonial-content { font-style: italic; color: #8BA3C7; margin-bottom: 20px; }
.hf-testimonial-author { display: flex; align-items: center; gap: 15px; }
.hf-avatar { width: 40px; height: 40px; background-color: #1E3A5F; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #00C9A7; }
.hf-author-info h4 { margin: 0; font-size: 1rem; }
.hf-author-info span { font-size: 0.8rem; color: #8BA3C7; }

/* scroll reveal stuff */
.hf-animate-fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.8s ease, transform 0.8s ease; }
.hf-animate-fade-in.is-visible { opacity: 1; transform: translateY(0); }
.delay-1 { transition-delay: 0.2s; }
.delay-2 { transition-delay: 0.4s; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // simple intersection observer for fade in
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('is-visible');
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.hf-animate-fade-in').forEach(el => obs.observe(el));
});
</script>

<?php get_footer(); ?>
