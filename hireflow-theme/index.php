<?php
/**
 * Main Index Template
 * 
 * @package HireFlow
 */

defined('ABSPATH') || exit;

get_header(); ?>

<div class="hf-container mt-5 pt-4">
    <div class="hf-row hf-grid hf-grid-3">
        <div class="hf-main-content col-span-2" style="grid-column: span 2;">
            <?php if (have_posts()) : ?>
                <div class="hf-posts-wrapper d-flex flex-column gap-4">
                    <?php while (have_posts()) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('hf-card'); ?>>
                            <header class="entry-header mb-3">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="entry-thumbnail mb-3">
                                        <?php the_post_thumbnail('large', ['class' => 'img-fluid rounded']); ?>
                                    </div>
                                <?php endif; ?>
                                <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>
                                <div class="entry-meta text-muted small">
                                    <span><?php echo esc_html(get_the_date()); ?></span>
                                </div>
                            </header>
                            
                            <div class="entry-content text-muted">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <footer class="entry-footer mt-3">
                                <a href="<?php echo esc_url(get_permalink()); ?>" class="hf-btn hf-btn-secondary">Read More</a>
                            </footer>
                        </article>
                    <?php endwhile; ?>
                    
                    <div class="hf-pagination mt-4">
                        <?php 
                        the_posts_pagination(array(
                            'mid_size'  => 2,
                            'prev_text' => __('Back', 'hireflow'),
                            'next_text' => __('Next', 'hireflow'),
                        )); 
                        ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="hf-card text-center py-5">
                    <h3 class="mb-3">No content found</h3>
                    <p class="text-muted">It seems we can't find what you're looking for. Perhaps searching can help.</p>
                    <div class="mt-4 mx-auto" style="max-width: 400px;">
                        <?php get_search_form(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="hf-sidebar-wrapper">
            <?php get_sidebar('primary'); ?>
        </div>
    </div>
</div>

<?php
get_footer();
