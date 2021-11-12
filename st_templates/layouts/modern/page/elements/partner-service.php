<?php
if (!empty($arr_service)) {
    $active_tab = STInput::get('service', $arr_service[0]);
}
if (!empty($arr_service)) { ?>
    <ul class="nav nav-tabs" id="">
        <?php
        foreach ($arr_service as $k => $v) {
            if (STUser_f::_check_service_available_partner('st_'.$v, $current_user_upage->ID)) {
                $get = $_GET;
                $get['service'] = $v;
                unset($get['pages']);
                $author_link = esc_url(get_author_posts_url($current_user_upage->ID));
                $url = esc_url(add_query_arg($get, $author_link));
                ?>
                <li class="<?php echo ($active_tab == $v) ? 'active' : ''; ?>"><a
                        href="<?php echo esc_url($url); ?>"
                        aria-expanded="true"><?php
                        switch ($v) {
                            case "hotel":
                                echo __('Hotels', 'traveler');
                                break;
                            case "tours":
                                echo __('Tours', 'traveler');
                                break;
                            case "activity":
                                echo __('Activity', 'traveler');
                                break;
                            case "cars":
                                echo __('Car', 'traveler');
                                break;
                            case "rental":
                                echo __('Rental', 'traveler');
                                break;
                            case "flight":
                                echo __('Flight', 'traveler');
                                break;
                        }

                        ?></a></li>
                <?php
            }
        }
        ?>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade active in author-sv-list" id="tab-all">
            <?php
//     laksh - start
    remove_filter( 'posts_groupby', 'my_posts_groupby' );
//     laksh - end
            $service = STInput::get('service', $arr_service[0]);
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $author = $current_user_upage->ID;
//     laksh - start
echo $location_name = $_GET['location_name'];
    echo "<br>";
echo $location_id = $_GET['location_id'];
    echo "<br>";
echo $start = $_GET['start'];
    echo "<br>";
echo $end = $_GET['end'];
    echo "<br>";
echo $date = $_GET['date'];
    echo "<br>";
echo $adult_number = $_GET['adult_number'];
    echo "<br>";
echo $child_number = $_GET['child_number'];
    echo "<br>";
            $args = array(
                'post_type' => 'st_' . esc_attr($service),
                'post_status' => 'publish',
                'author' => $author,
                'posts_per_page' => 6,
                'paged' => $paged,
                'meta_query' => array(
                    array(
                        'key' => 'address',
                        'value' => $location_name,
                        'compare' => 'LIKE'
                    )
                ),
            );
//     laksh - end
            $query = new WP_Query($args);

            if ($query->have_posts()) {
                switch ($service) {
                    case "hotel":
                        echo '<div class="search-result-page"><div class="st-hotel-result"><div class="row row-wrapper">';
                        break;
                    case "tours":
                        echo '<div class="search-result-page st-tours"><div class="st-hotel-result"><div class="row row-wrapper">';
                        break;
                    case "activity":
                        echo '<div class="search-result-page st-tours st-activity"><div class="st-hotel-result"><div class="row row-wrapper">';
                        break;
                    case "cars":
                        echo '<div class="search-result-page st-tours"><div class="st-hotel-result"><div class="row row-wrapper">';
                        break;
                    case "rental":
                        echo '<div class="search-result-page st-rental "><div class="st-hotel-result"><div class="row row-wrapper">';
                        break;
                    // case "flight":
                    //     echo '<ul class="booking-list loop-rental style_list">';
                    //     break;
                }
                while ($query->have_posts()) {
//                     laksh - start
                    $query->the_post();
                    $start_timestamp = strtotime($start);
                    global $wpdb;
                    $post_id = get_the_ID();
                    $result = $wpdb->get_results( "SELECT * FROM wp_st_tour_availability WHERE post_id = $post_id");
                    if( !in_array($start_timestamp, $result) ) {
                        switch ($service) {
                            case "hotel":
                                echo st()->load_template('layouts/modern/hotel/elements/loop/normal', 'grid');
                                break;
                            case "tours":
                                echo st()->load_template('layouts/modern/tour/elements/loop/grid');
                                break;
                            case "activity":
                                echo st()->load_template('layouts/modern/activity/elements/loop/grid');
                                break;
                            case "cars":
                                echo st()->load_template('layouts/modern/car/elements/loop/grid');
                                break;
                            case "rental":
                                echo '<div class="col-lg-4 col-md-6 col-xs-6">';
                                echo st()->load_template('layouts/modern/rental/elements/loop/grid');
                                echo '</div>';
                                break;
                            case "flight":
                                echo st()->load_template('user/loop/loop', 'flight-upage');
                                break;
                        }
                    }
//                     laksh - end
                }
                echo "</div></div></div>";
            } else {
                echo '<h5>' . __('No data', 'traveler') . '</h5>';
            }
            wp_reset_postdata();
            ?>
            <br/>
            <div class="pull-left author-pag">
                <?php st_paging_nav(null, $query) ?>
            </div>
        </div>
    </div>

    <div class="st-review-new">
        <h5><?php echo __('Review', 'traveler'); ?></h5>
        <?php
        echo st()->load_template('layouts/modern/page/elements/partner', 'review', array(
            'current_user_upage' => $current_user_upage,
            'arr_service' => $arr_service,
            'post_per_page_review' => 5
        ));
        ?>
    </div>

    <?php
} else {
    echo __('No partner services!', 'traveler');
}
?>
