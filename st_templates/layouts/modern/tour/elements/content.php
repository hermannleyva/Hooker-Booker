<!-- laksh - start -->
<style type="text/css">
    li.layout {
        display: none;
    }
</style>
<!-- laksh - end -->
<?php
$style = get_post_meta(get_the_ID(), 'rs_style_tour', true);
// laksh - start
if (empty($style))
    $style = 'list';
else
    $style = 'list';
// laksh - end

global $wp_query, $st_search_query;
if ($st_search_query) {
    $query = $st_search_query;
    var_dump($query);
} else
    $query = $wp_query;

if (empty($format))
    $format = '';

if (empty($layout))
    $layout = '';
?>
<div class="col-lg-9 col-md-9">
    <?php echo st()->load_template('layouts/modern/hotel/elements/toolbar', '', array('style' => $style, 'format' => $format, 'layout' => $layout, 'service_text' => __('New tour', 'traveler'), 'post_type' => 'st_tours')); ?>
    <div id="modern-search-result" class="modern-search-result" data-layout="1">
        <?php echo st()->load_template('layouts/modern/common/loader', 'content'); ?>
        <?php
        if ($style == 'grid') {
            echo '<div class="row row-wrapper">';
        } else {
            echo '<div class="style-list">';
        }
        ?>
        <?php
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
//                 laksh - start
//                 echo get_the_author_meta('ID');
//                 laksh - end
                echo st()->load_template('layouts/modern/tour/elements/loop/' . esc_attr($style));
            }
        } else {
            echo ($style == 'grid') ? '<div class="col-xs-12">' : '';
            echo st()->load_template('layouts/modern/tour/elements/loop/none');
            echo ($style == 'grid') ? '</div>' : '';
        }
        wp_reset_query();
        ?>
    </div>
</div>

<div class="pagination moderm-pagination" id="moderm-pagination" data-layout="normal">
    <?php TravelHelper::paging(false, false); ?>
    <span class="count-string">
        <?php
        if (!empty($st_search_query)) {
            $query = $st_search_query;
        }
        if ($query->found_posts):
            $page = get_query_var('paged');
            $posts_per_page = st()->get_option('tour_posts_per_page', 12);
            if (!$page)
                $page = 1;
            $last = (int) $posts_per_page * ((int) $page);
            if ($last > $query->found_posts)
                $last = $query->found_posts;
            echo sprintf(__('%d - %d of %d ', 'traveler'), (int) $posts_per_page * ((int) $page - 1) + 1, $last, $query->found_posts);
            echo ( $query->found_posts == 1 ) ? __('Tour', 'traveler') : __('Tours', 'traveler');
        endif;
        ?>
    </span>
</div>
</div>
