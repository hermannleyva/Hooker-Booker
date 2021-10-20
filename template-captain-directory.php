<?php
/*
  Template Name: Captain Directory
 */

get_header();

$class = 'search-result-page search-result-page--custom';
$menu_style = st()->get_option('menu_style_modern', '');
switch ($menu_style) {
    case 8: //solo layout
        $class = 'search-result-page search-result-page--custom st-content-wrapper--solo';
        break;
    default :
        break;
}
?>
<div id="st-content-wrapper" class="<?php echo $class; ?>">

    <div class="container">

    	<?php 
		

		$args = array(
		    'role'    => 'Partner',
		    'orderby' => 'user_nicename',
		    'order'   => 'ASC'
		);

		$users = get_users( $args );


		echo '<div class="row">';

		$loopCounter = 0;

		foreach ( $users as $user ) {



			$userId = $user->ID;

			$postCount = count_user_posts($userId, 'st_tours', true);

			$authorLink = get_author_posts_url($userId); 

			echo '<div class="col-lg-6">';

			echo '<div class="captainContainer">';

			echo '<div class="col-3 col-lg-3">';

			echo '<a href="'.$authorLink.'"><div class="captainAvatar">'.st_get_profile_avatar($userId, 100).'</div></a>';

            $arr_full_service = [];
            if (!empty($arr_service)) {
                foreach ($arr_service as $kkk => $vvv) {
                    array_push($arr_full_service, 'st_' . $vvv);
                }
            }
            $author_query_id = array(
                'author' => $userId,
                'post_type' => 'st_tours',
                'posts_per_page' => '-1',
                'post_status' => 'publish'
            );

            $a_query = new WP_Query($author_query_id);
            $arr_id = [];
            while ($a_query->have_posts()) {
                $a_query->the_post();
                array_push($arr_id, get_the_ID());
            }
            wp_reset_postdata();

            $review_data = STReview::data_comment_author_page($arr_id, 'st_reviews');
            $total_review_core = 0;
            $arr_c_rate = [];
            if (!empty($review_data)) {
                foreach ($review_data as $kkk => $vvv) {
                    $comment_rate = get_comment_meta($vvv['comment_ID'], 'comment_rate', true);
                    array_push($arr_c_rate, $comment_rate);
                    $total_review_core = $total_review_core + $comment_rate;
                }

                foreach ($arr_c_rate as $k => $v) {
                    if ($v == 0 || $v == '') {
                        unset($arr_c_rate[$k]);
                    }
                }

                $avg_rating = round(array_sum($arr_c_rate) / count($arr_c_rate), 1);
            }

            if (!$review_data) {

            	echo '<p class="noMargin textCenter">No Reviews</p>';

            } else {

            	printf(__('<p class="noMargin textCenter">%s Reviews</p>', 'traveler'), count($review_data));
            }


		    // echo  comments_number(__('0 reviews', 'traveler'), __('1 Review', 'traveler'), __('% Reviews', 'traveler'));

	        $arr_full_service = [];
	        if (!empty($arr_service)) {
	            foreach ($arr_service as $kkk => $vvv) {
	                array_push($arr_full_service, 'st_' . $vvv);
	            }
	        }
	        $author_query_id = array(
	            'author' => $user->ID,
	            'post_type' => 'st_tours',
	            'posts_per_page' => '-1',
	            'post_status' => 'publish'
	        );

	        $a_query = new WP_Query($author_query_id);
	        $arr_id = [];
	        while ($a_query->have_posts()) {
	            $a_query->the_post();
	            array_push($arr_id, get_the_ID());
	        }

	        wp_reset_postdata();

	        $review_data = STReview::data_comment_author_page($arr_id, 'st_reviews');
	        $total_review_core = 0;
	        $arr_c_rate = [];

	        if (!empty($review_data)) {
	            foreach ($review_data as $kkk => $vvv) {
	                $comment_rate = get_comment_meta($vvv['comment_ID'], 'comment_rate', true);
	                array_push($arr_c_rate, $comment_rate);
	                $total_review_core = $total_review_core + $comment_rate;
	            }

	            foreach ($arr_c_rate as $k => $v) {
	                if ($v == 0 || $v == '') {
	                    unset($arr_c_rate[$k]);
	                }
	            }

	            $avg_rating = round(array_sum($arr_c_rate) / count($arr_c_rate), 1);

	            echo '<ul class="width100 textCenter icon-group text-color booking-item-rating-stars captainDirectoryReview">'.TravelHelper::rate_to_string($avg_rating).'</ul>';

	        } else {

	        	echo '<ul class="width100 textCenter icon-group text-color booking-item-rating-stars captainDirectoryReview">'.TravelHelper::rate_to_string(0).'</ul>';

	        }

			echo '</div>';

			echo '<div class="col-9 col-lg-9">';

			echo '<a href="'.$authorLink.'"><h3>'.$user->display_name.'</h3></a>';

		    echo '<strong>'.$postCount.' active trips</strong><br>';

		   	echo '<p>Test Charter<br>';

		    echo '<br>';

			echo '</div></div></div>';



			$loopCounter++;

			if ($loopCounter == 2) { 


				$loopCounter = 0;

				echo '</div>';

				echo '<div class="row">';


			}

		}

		echo '</div>';

		var_dump($users);

    	?>

    </div>
</div>

<?php
get_footer();
