<div <?php post_class('blog-item') ?>>

    <div class="blog-media">

        <?php echo st_hotel_alone_load_view('blog/blog-content/format/format',get_post_format()) ?>

    </div>

    <div class="blog-meta display_table">

        <div class="display_tab_cell">

            <div class="blog-item-meta-date text-center">

                <span><?php echo get_the_date(get_option('date_format')); ?></span>

            </div>

            <h2 class="blog-item-title text-center">

                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

            </h2>

            <div class="blog-item-meta-category text-center">

                <?php the_category(' '); ?>

            </div>



            <div class="blog-item-meta-desc">

               <?php echo wp_trim_words(get_the_excerpt(get_the_ID()),60,' ...')?>

            </div>

            <div class="blog-item-meta-footer">

                <div class="blog-item-link">

                    <a href="<?php the_permalink(); ?>" class="btn-bg-black btn-size-0"><?php esc_html_e('CONTINUE READING','traveler');?></a>

                </div>

                <div class="blog-item-meta">

                    <?php echo st_hotel_alone_load_view('blog/social-share') ?>

                    <span class="separator"></span>

                    <span><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">

                    <?php esc_html_e("By",'traveler') ?>

                            <span class="text-up"> <?php echo get_the_author(); ?> </span>

                </a>

            </span>

                    <span class="separator"></span>

                    <span class="text-up">

                <a href="<?php echo esc_url( get_comments_link() ); ?>">

                    <?php echo get_comments_number(); ?>

                    <?php echo _n('Comment', 'Comments', get_comments_number(), 'traveler' ); ?>

                </a>

            </span>

                </div>

            </div>

        </div>

    </div>

</div>