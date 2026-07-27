<?php
/* Template Name: Contact */
defined('ABSPATH') || exit;
get_header();
?>

<main class="hf-contact-page">
    <section class="hf-hero hf-hero-sm hf-bg-primary">
        <div class="hf-container">
            <h1 class="hf-hero-title">Get in Touch</h1>
            <p class="hf-hero-subtitle">Have questions, feedback, or need support? We're here to help.</p>
        </div>
    </section>

    <section class="hf-section-padding">
        <div class="hf-container">
            <div class="hf-grid-layout" style="grid-template-columns: 1fr 1fr; gap: 40px;">
                
                <div class="hf-card">
                    <h2 class="hf-mb-4">Send us a Message</h2>
                    <!-- using a standard form element, will hook up ajax later -->
                    <form id="hf-contact-form" class="hf-form">
                        <div class="hf-form-group">
                            <label for="contact_name">Your Name</label>
                            <input type="text" id="contact_name" name="contact_name" class="hf-input" required>
                        </div>
                        <div class="hf-form-group">
                            <label for="contact_email">Email Address</label>
                            <input type="email" id="contact_email" name="contact_email" class="hf-input" required>
                        </div>
                        <div class="hf-form-group">
                            <label for="contact_subject">Subject</label>
                            <input type="text" id="contact_subject" name="contact_subject" class="hf-input" required>
                        </div>
                        <div class="hf-form-group">
                            <label for="contact_message">Message</label>
                            <textarea id="contact_message" name="contact_message" class="hf-textarea" rows="6" required></textarea>
                        </div>
                        <button type="submit" class="hf-btn hf-btn-primary">Send Message</button>
                    </form>
                </div>

                <div>
                    <div class="hf-grid-layout" style="grid-template-columns: 1fr; gap: 20px; margin-bottom: 40px;">
                        <div class="hf-card hf-flex" style="align-items: center; gap: 15px; padding: 20px;">
                            <span class="dashicons dashicons-email hf-text-accent" style="font-size: 32px; width: 32px; height: 32px;"></span>
                            <div>
                                <h4 class="hf-mb-0">Email Us</h4>
                                <a href="mailto:support@hireflow.com" class="hf-text-muted">support@hireflow.com</a>
                            </div>
                        </div>
                        <div class="hf-card hf-flex" style="align-items: center; gap: 15px; padding: 20px;">
                            <span class="dashicons dashicons-location hf-text-accent" style="font-size: 32px; width: 32px; height: 32px;"></span>
                            <div>
                                <h4 class="hf-mb-0">Our Location</h4>
                                <span class="hf-text-muted">San Francisco, CA (Remote Team)</span>
                            </div>
                        </div>
                    </div>

                    <h3>Frequently Asked Questions</h3>
                    <div class="hf-accordion hf-mt-3">
                        <div class="hf-accordion-item hf-card hf-mb-2">
                            <div class="hf-accordion-header hf-p-3" style="cursor: pointer; font-weight: bold; border-bottom: 1px solid #1E3A5F;">
                                Is HireFlow really free?
                            </div>
                            <div class="hf-accordion-body hf-p-3 hf-text-muted">
                                Yes, the core application tracking features are 100% free for all job seekers.
                            </div>
                        </div>
                        <div class="hf-accordion-item hf-card hf-mb-2">
                            <div class="hf-accordion-header hf-p-3" style="cursor: pointer; font-weight: bold; border-bottom: 1px solid #1E3A5F;">
                                Can I export my data?
                            </div>
                            <div class="hf-accordion-body hf-p-3 hf-text-muted">
                                Currently, we don't have a direct export feature, but it is on our roadmap for Q4.
                            </div>
                        </div>
                        <div class="hf-accordion-item hf-card hf-mb-2">
                            <div class="hf-accordion-header hf-p-3" style="cursor: pointer; font-weight: bold; border-bottom: 1px solid #1E3A5F;">
                                How do I report a bug?
                            </div>
                            <div class="hf-accordion-body hf-p-3 hf-text-muted">
                                Use the contact form on this page with the subject "Bug Report" and provide as much detail as possible.
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>

<style>
/* some quick styling for contact page layout */
.hf-hero-sm { padding: 60px 0; }
.hf-flex { display: flex; }
.hf-mb-0 { margin-bottom: 0; }
.hf-mb-2 { margin-bottom: 8px; }
.hf-mb-4 { margin-bottom: 24px; }
.hf-mt-3 { margin-top: 16px; }
.hf-p-3 { padding: 16px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // block default submit for now
    const form = document.getElementById('hf-contact-form');
    if(form) {
        form.addEventListener('submit', e => {
            e.preventDefault();
            alert('Thank you for your message! We will get back to you soon.');
            form.reset();
        });
    }

    // basic accordion logic
    document.querySelectorAll('.hf-accordion-header').forEach(header => {
        header.addEventListener('click', () => {
            const body = header.nextElementSibling;
            body.style.display = (body.style.display === 'none' || !body.style.display) ? 'block' : 'none';
        });
    });
});
</script>

<?php get_footer(); ?>
