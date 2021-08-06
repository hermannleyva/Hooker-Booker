<?php

wp_enqueue_script( 'bootstrap-datepicker.js' ); wp_enqueue_script( 'bootstrap-datepicker-lang.js' );



$current_user = wp_get_current_user();
$user_id = $current_user->ID;


//$info = STUser_f::st_get_data_reports_partner(array('st_cars','st_hotel'),'10-9-2015','20-9-2015');

$_custom_date = STUser_f::get_custom_date_reports_partner();

$request_custom_date = STUser_f::get_request_custom_date_partner();

$custom_layout = st()->get_option('partner_custom_layout','off');

$custom_layout_total_earning = st()->get_option('partner_custom_layout_total_earning','on');

$custom_layout_service = st()->get_option('partner_custom_layout_service_earning','on');

$custom_layout_chart_info = st()->get_option('partner_custom_layout_chart_info','on');

if($custom_layout == "off"){

    $custom_layout_total_earning = $custom_layout_service = $custom_layout_chart_info = "on";

}

$total_earning = STUser_f::st_get_data_reports_total_all_time_partner($user_id);

$total_price_payout = STAdminWithdrawal::_get_total_price_payout($user_id);

$your_balance = $total_earning['average_total'] - $total_price_payout;

$currency = TravelHelper::get_current_currency('symbol');

$webURL = get_site_url();


//availbility
function checkAvailability($user_id) {

    global $wpdb;
    date_default_timezone_set('America/New_York');


    $webURL = get_site_url();

    $counter = 0;
    $currentDate = date("Y-m-d");
    $currentDateUnix = strtotime($currentDate);
    $minusOneDay = date("Y-m-d", strtotime($currentDate .'-1 day'));

    $expMonths = 6;
    $expiring = strtotime('+'.$expMonths.' month'); 

    $query = $wpdb->prepare("SELECT `id` FROM `wp_opt_tour_availability` WHERE `userid` = %d AND `status` = 'available' AND `check_in` BETWEEN %d AND %d", $user_id, $currentDateUnix, $expiring);
    $days = $wpdb->get_results($query, ARRAY_N);

    foreach ($days as $day) {
        $counter++;
    }

    if ($counter > 0) {
        $results = '<img style="float:left;" width="50px" height="50px" src="'.$webURL.'/wp-content/themes/traveler/img/green.png">
        <p style="text-align:center;">You have '.$counter.' available day(s) in the next 6 months.</p>';
    } else {
        $results = '<img style="float:left;" width="50px" height="50px" src="'.$webURL.'/wp-content/themes/traveler/img/red.png"><p style="text-align:center;">You need to add availability!</p>';
    }

    return $results;

} //end function checkAvailability


function checkVerifications($user_id) {

    $boatInsuranceStatus  = get_user_meta($user_id,'boatInsuranceStatus',true);
    $captainLicenseStatus = get_user_meta($user_id,'captainLicenseStatus',true);

    $webURL = get_site_url();

    $verifStatus = [

    0 => 'Not Submitted',
    1 => 'Submitted, pending our team to review',
    2 => 'We need you to review, please go to verifications',
    3 => 'Verified'

    ];

    $resultsArr = array();

    //boat insurance
    if (!$boatInsuranceStatus) {
        array_push($resultsArr, 0);
    } else {
        array_push($resultsArr, $boatInsuranceStatus);
    }

    //captain license
    if (!$captainLicenseStatus) {
        array_push($resultsArr, 0);
    } else {
        array_push($resultsArr, $captainLicenseStatus);
    }

    

    // $results = '<div><p><b>Boat Insurance Status:</b><br> '.$resultsArr[0].'</p></div><div><p><b>Captain License Status:</b><br> '.$resultsArr[1].'</p></div>';

    // return print_r($resultsArr);

    //take results array, organize them from lowest number to highest in one array index [x,x]
    sort($resultsArr);
    $results = '';

    //debgger
    // echo $resultsArr[0].','.$resultsArr[1];

    //then use the below guideline for colors and statuses
    // 00
    // 01
    // 02
    // 03 = you need to submit one or more documents! (red)
    
    // 12
    // 22 = you need to review one or more documents (red)
    // 23

    // 11 = pending our review, (yellow)
    // 13

    // 33 = verified, green

    if ($resultsArr[0] == 0 && $resultsArr[1] == 0 || $resultsArr[0] == 0 && $resultsArr[1] == 1 || $resultsArr[0] == 0 && $resultsArr[1] == 2 || $resultsArr[0] == 0 && $resultsArr[1] == 3 ) {
        $results = '<img style="float:left;" width="50px" height="50px" src="'.$webURL.'/wp-content/themes/traveler/img/red.png"><div><p style="text-align:center;">You need to submit one or more documents.</p></div>';
    } else if ($resultsArr[0] == 1 && $resultsArr[1] == 2 || $resultsArr[0] == 2 && $resultsArr[1] == 2 || $resultsArr[0] == 2 && $resultsArr[1] == 3) {
        $results = '<img style="float:left;" width="50px" height="50px" src="'.$webURL.'/wp-content/themes/traveler/img/red.png"><div><p style="text-align:center;">You need to review one or more documents.</p></div>';
    } else if ($resultsArr[0] == 1 && $resultsArr[1] == 1 || $resultsArr[0] == 1 && $resultsArr[1] == 3 ) {
        $results = '<img style="float:left;" width="50px" height="50px" src="'.$webURL.'/wp-content/themes/traveler/img/yellow.png"><div><p style="text-align:center;">Your documents are under review.</p></div>';
    } else if ($resultsArr[0] == 3 && $resultsArr[1] == 3) {
        $results = '<img style="float:left;" width="50px" height="50px" src="'.$webURL.'/wp-content/themes/traveler/img/green.png"><div><p style="text-align:center;">Verified.</p></div>';
    }

    return $results;


} // end function checkVerifications


function checkTrips($user_id) {

    global $wpdb;

    $webURL = get_site_url();
    $counter = 0;

    $query = $wpdb->prepare("SELECT `id` FROM `wp_posts` WHERE `post_author` = %d AND `post_type` = 'st_tours' AND `post_status` = 'publish'", $user_id);
    $posts = $wpdb->get_results($query, ARRAY_N);

    foreach ($posts as $post) {
        $counter++;
    }

    if ($counter > 0) {
        $results = '<img style="float:left;" width="50px" height="50px" src="'.$webURL.'/wp-content/themes/traveler/img/green.png">
        <p style="text-align:center;">You have '.$counter.' active trip(s).</p>';
    } else {
        $results = '<img style="float:left;" width="50px" height="50px" src="'.$webURL.'/wp-content/themes/traveler/img/red.png"><p style="text-align:center;">If you have been verified and have availability, you should offer at least one fishing trip.</p>';
    }

    return $results;

} //end function checkTrips


function findActiveFishingTrips($user_id) {

    global $wpdb;

    //locate all the trips by user
    $query = $wpdb->prepare("SELECT `id`,`post_title`,`post_status` FROM `wp_posts` WHERE `post_author` = %d AND `post_type` = 'st_tours' AND `post_status` = 'publish'", $user_id);
    $posts = $wpdb->get_results($query, ARRAY_A);

    $results = '<br>';

    if (!$posts) {
        $results .= '<p>You have no active trips.<p>';
    }

    foreach ($posts as $post) {

        $postID = $post['id'];

        $base_price = get_post_meta($postID, 'base_price', true);
        $duration   = get_post_meta($postID, 'duration_day', true);
        $maxPpl     = get_post_meta($postID, 'max_people', true);
        $address    = get_post_meta($postID, 'address', true);
        $tripType   = get_post_meta($postID, 'trip_type', true);
        $webURL     = get_site_url();

        if (get_post_meta($postID, 'postImageUrl', true)) {

            $imageURL   = get_post_meta($postID, 'postImageUrl', true);
            $fullImgURL = '<img style="height: 150px; width: 150px;" src="'.$webURL.'/wp-content/themes/traveler/st_templates/user/postimgs/'.$imageURL.'"/>';

        } else if (has_post_thumbnail($postID)) {

            $fullImgURL = get_the_post_thumbnail($postID, array(150, 150), array('class' => 'img-responsive media-object'));

        }


 
        

        // $imageCreate = imagecreatefromstring($fullImgURL);

        if ($duration == 'half-day') {
            $duration = 'Half Day';
        } else if ($duration == 'full-day') {
            $duration = 'Full Day';
        } else if ($duration == 'three-quarters-day') {
            $duration = "Three Quarter's Day";
        }

        // $status     = $post['post_status'];

        // if ($status == 'publish') {
        //     $status = 'Active';
        // } else if ($status == 'draft') {
        //     $status = 'Inactive/Draft';
        // }

        // <p>'.$status.'</p>

        ////div 1
        //image

        $results .= '
                    <div class="bigContainer" style="position: relative;">
                        <div class="statusAlert" name="statusAlert"></div>
                        <div class="tripContainer" name="tripContainer">
                            <div style="float:left; padding: 10px;">
                                '.$fullImgURL.'
                            </div>

                            <div style="padding: 10px;">
                                <p style="text-transform: uppercase;"><b>'.$post['post_title'].'</b></p>
                                <p style="display:inline-block;">Address: '.$address.'<br>
                                Trip Type: '.$tripType.'<br>
                                Maximum people: '.$maxPpl.'<br>
                                Duration: '.$duration.'<br>
                                Price: <b>$'.$base_price.'</b></p>
                            </div>

                            <br>

                            <div style="position: absolute; right: 0px; bottom: 0px;">
                                <input type="hidden" name="postID" id="postID" value="'.$postID.'">


                                <a class="btn btn-primary" href="'.$webURL.'/page-user-setting/?sc=edit-trip&id='.$postID.'">Edit</a>
                                                                 
                                <a class="btnDelete btn btn-primary">Delete</a>

                                <a class="btnDeactivate btn btn-primary">Deactivate</a>

                            </div>
                        </div>
                    </div><!-- main div -->
                    <br>';



    }


    return $results;


} //end function findFishingTrips


function findInactiveFishingTrips($user_id) {

    global $wpdb;

    //check statuses to determine whether or not to show activate button.
    $currentDate = date("Y-m-d");
    $currentDateUnix = strtotime($currentDate);
    $minusOneDay = date("Y-m-d", strtotime($currentDate .'-1 day'));

    $captainLicenseStatus = get_user_meta($user_id, 'captainLicenseStatus');
    $boatInsuranceStatus = get_user_meta($user_id, 'boatInsuranceStatus');

    $expMonths = 6;
    $expiring = strtotime('+'.$expMonths.' month'); 

    $q = $wpdb->prepare("SELECT `id` FROM `wp_opt_tour_availability` WHERE `userid` = %d AND `status` = 'available' AND `check_in` BETWEEN %d AND %d", $user_id, $currentDateUnix, $expiring);
    $days = $wpdb->get_results($q, ARRAY_N);

    $post_status = '';

    if (!$captainLicenseStatus || !$boatInsuranceStatus) {
        $post_status = 'draft';
    } else if ($captainLicenseStatus[0] == '3' && $boatInsuranceStatus[0] == '3' && $days) {
        $post_status = 'publish';
    } else {
        $post_status = 'draft';
    }


    //locate all the trips by user
    $query = $wpdb->prepare("SELECT `id`,`post_title`,`post_status` FROM `wp_posts` WHERE `post_author` = %d AND `post_type` = 'st_tours' AND `post_status` = 'draft'", $user_id);
    $posts = $wpdb->get_results($query, ARRAY_A);

    $results = '<br>';

    if (!$posts) {
        $results .= '<p>You have no inactive trips.<p>';
    }

    foreach ($posts as $post) {

        $postID = $post['id'];

        $base_price = get_post_meta($postID, 'base_price', true);
        $duration   = get_post_meta($postID, 'duration_day', true);
        $maxPpl     = get_post_meta($postID, 'max_people', true);
        $address    = get_post_meta($postID, 'address', true);
        $tripType   = get_post_meta($postID, 'trip_type', true);
 
        $imageURL   = get_post_meta($postID, 'postImageUrl', true);
        $webURL     = get_site_url();

        if (get_post_meta($postID, 'postImageUrl', true)) {

            $imageURL   = get_post_meta($postID, 'postImageUrl', true);
            $fullImgURL = '<img style="height: 150px; width: 150px;" src="'.$webURL.'/wp-content/themes/traveler/st_templates/user/postimgs/'.$imageURL.'"/>';

        } else if (has_post_thumbnail($postID)) {

            $fullImgURL = get_the_post_thumbnail($postID, array(150, 150), array('class' => 'img-responsive media-object'));

        }



        if ($duration == 'half-day') {
            $duration = 'Half Day';
        } else if ($duration == 'full-day') {
            $duration = 'Full Day';
        } else if ($duration == 'three-quarters-day') {
            $duration = "Three Quarter's Day";
        }


        



        $results .= '   <div class="bigContainer" style="position: relative;">
                        <div class="statusAlert" name="statusAlert"></div>
                        <div class="tripContainer" name="tripContainer">
                            <div style="float:left; padding: 10px;">
                                '.$fullImgURL.'
                            </div>

                            <div style="padding: 10px;">
                                <p style="text-transform: uppercase;"><b>'.$post['post_title'].'</b></p>
                                <p style="display:inline-block;">Address: '.$address.'<br>
                                Trip Type: '.$tripType.'<br>
                                Maximum people: '.$maxPpl.'<br>
                                Duration: '.$duration.'<br>
                                Price: <b>$'.$base_price.'</b></p>
                            </div>

                            <br>

                            <div style="position: absolute; right: 0px; bottom: 0px;">
                                <input type="hidden" name="postID" id="postID" value="'.$postID.'">

                                <a href="'.$webURL.'/page-user-setting/?sc=edit-trip&id='.$postID.'" class="btn btn-primary">Edit</a>
                                          
                                <a class="btnDelete btn btn-primary">Delete</a>';

                                    if ($post_status == 'publish') {

                                        $results.= '<a style="margin-left: 3px;" class="btnActivate btn btn-primary">Activate</a>';
                                    }

                            $results .= '</div></div></div><!-- main div --><br>';



    }


    return $results;


}

?>

<?php if($custom_layout_total_earning == "on"){ ?>

<!--     <div class="row div-partner-page-title">

        <div class="col-md-7">

            <h3 class="partner-page-title">

                <?php _e('Dashboard','traveler') ?>



            </h3>

        </div>



    </div> -->

    <script type="text/javascript" src="../wp-content/themes/traveler/js/dashboard.js"></script>

    <div class="row" style="margin-top: 30px;">

        <div style="position:relative;" class="col-md-4 item-st-month">

            <?php

            $start  = $_custom_date['y'].'-'.$_custom_date['m'].'-1';

            $end  = $_custom_date['y'].'-'.$_custom_date['m'].'-31';



            $this_month = STUser_f::st_get_data_reports_partner('all','custom_date',$start,$end);

            ?>

            <div class="st-dashboard-stat st-month-madison st-dashboard-new st-month-1">

                <h4>Verifications</h4>

                <?php 

                    echo checkVerifications($user_id);

                ?>

                <br>

                <?php 
                
                echo '<a style="text-decoration: underline; position:absolute; bottom:10px;right:10px;" href="'.$webURL.'/page-user-setting/?sc=verifications&type=st_tours">Go to Verifications</a>';

                ?>
                

                <div class="st-wrap-box">


                    <div class="details">
                        
                        

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-4 item-st-month">

            <div style="position:relative;" class="st-dashboard-stat st-month-madison st-dashboard-new st-month-2">

                <h4>Availability</h4>

                <?php 

                    echo checkAvailability($user_id);

                ?>

                <br>

                <?php 
                
                echo '<a style="text-decoration: underline; position:absolute; bottom:10px;right:10px;" href="'.$webURL.'/page-user-setting/?sc=availability&type=st_tours">Go to Availability</a>';

                ?>

            </div>

        </div>

        <div class="col-md-4 item-st-month">


            <div class="st-dashboard-stat st-month-madison st-dashboard-new st-month-3">

                <h4>Active Offered Trips</h4>

                <?php 

                    echo checkTrips($user_id);

                ?>

                <br>

                <?php 
                
                echo '<a style="text-decoration: underline; position:absolute; bottom:10px;right:10px;" href="'.$webURL.'/page-user-setting/?sc=fishing-trip&type=st_tours">Go to Offer a fishing trip</a>';

                ?>

            </div>

        </div>

    </div>

<?php }?>

<?php if($custom_layout_service == "on"){ ?>

    <?php

    if($request_custom_date['type'] == 'all_time'){

        $this_data_custom = $total_earning;

    }else{

        $this_data_custom = STUser_f::st_get_data_reports_partner('all','custom_date',$request_custom_date['start'],$request_custom_date['end']);

    }

    ?>

    <div class="row" style="margin-top: 30px;">

        <div class="col-md-6 item-st-month">

            <div class="st-dashboard-stat head_reports bg-warning">

                <div class="head_control">

                    <div class="head_time">

                        <span><?php

                            echo sprintf( __('Your Active Fishing Trips','traveler') ,$request_custom_date['title'])

                            ?></span>

                    </div>

                </div>

                <div id="addActivated" style="position: relative;"></div>

                <?php 

                    echo findActiveFishingTrips($user_id);

                ?>

            </div>

        </div>

        <div class="col-md-6 item-st-month">

            <div class="st-dashboard-stat head_reports bg-warning">

                <div class="head_control">

                    <div class="head_time">

                        <span><?php

                            echo sprintf( __('Inactive/Saved Fishing Trips','traveler') ,$request_custom_date['title'])

                            ?></span>

                    </div>

                </div>

                <div id="addDeactivated" style="position: relative;"></div>

                <?php

                echo findInactiveFishingTrips($user_id);

                ?>

            </div>
        </div>
    </div>

<?php } ?>



