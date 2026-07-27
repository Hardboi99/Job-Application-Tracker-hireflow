<?php
/**
 * Search Results Template
 * 
 * @package HireFlow
 */

defined('ABSPATH') || exit;

get_header(); ?>

<div class="hf-container mt-5 pt-4">
    <header class="page-header mb-5 hf-card">
        <h1 class="page-title mb-3">
            <?php
            /* translators: %s: search query. */
            printf(esc_html__('Search Results for: %s', 'hireflow'), '<span>' . get_search_query() . '</span>');
            ?>
        </h1>
        <div class="search-form-wrapper" style="max-width: 500px;">
            <?php get_search_form(); ?>
        </div>
    </header>

    <div class="hf-row hf-grid hf-grid-3">
        <div class="hf-main-content col-span-2" style="grid-column: span 2;">
            <?php if (have_posts()) : ?>
                <div class="hf-posts-wrapper d-flex flex-column gap-4">
                    <?php while (have_posts()) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('hf-card'); ?>>
                            <header class="entry-header mb-2">
                                <?php the_title('<h3 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>'); ?>
                            </header>
                            <div class="entry-summary text-muted">
                                <?php the_excerpt(); ?>
                            </div>
                        </article>
                    <?php endwhile; ?>
                    
                    <div class="hf-pagination mt-4">
                        <?php the_posts_pagination(); ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="hf-card text-center py-5">
                    <h3 class="mb-3">No results found</h3>
                    <p class="text-muted">Sorry, but nothing matched your search terms. Please try again with some different keywords.</p>
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
