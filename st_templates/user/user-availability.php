<?php 

error_reporting(E_ALL);

wp_enqueue_script('Chart.min2.js', get_template_directory_uri() . '/v2/js/Chart.min.js', ['jquery'], null, true);

wp_register_style('availability_partner', get_template_directory_uri() . '/css/availability_partner.css');

wp_enqueue_script('select2.js');

wp_enqueue_script('select2-lang');

wp_enqueue_style('st-select2');

wp_enqueue_script('fullcalendar');

wp_enqueue_script('fullcalendar-lang');

wp_enqueue_style('fullcalendar-css');

wp_enqueue_script('tour_availability_partner', get_template_directory_uri() . '/js/availability_tour_partner.js', ['jquery'], NULL, TRUE);

wp_enqueue_style('availability_partner');

wp_enqueue_script( 'bootstrap-datepicker.js' ); wp_enqueue_script( 'bootstrap-datepicker-lang.js' );


global $wpdb;

date_default_timezone_set('America/New_York');

$post_id = 9475;
$post_type = 'st_tours';


//OPTIMA - AVAILABILITY DEFAULT DATE FIX
//OPTIMA - 1.0 ENHANCEMENT AVAILABILITY
$counter = 0;
$wpuser = get_current_user_id();

$currentDate = date("Y-m-d");
$currentDateUnix = strtotime($currentDate);
$minusOneDay = date("Y-m-d", strtotime($currentDate .'-1 day'));

$expMonths = 6;
$expiring = strtotime('+'.$expMonths.' month'); 

$query = $wpdb->prepare("SELECT `id` FROM `wp_opt_tour_availability` WHERE `userid` = %d AND `status` = 'available' AND `check_in` BETWEEN %d AND %d", $wpuser, $currentDateUnix, $expiring);
$days = $wpdb->get_results($query, ARRAY_N);

foreach ($days as $day) {
	$counter++;
}

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}	






?>

<span class="hidden st_partner_avaiablity edit-tours"></span>

<div class="row calendar-wrapper template-user" data-post-id="<?php echo esc_html($post_id); ?>">

    <div class="col-xs-12 col-md-12">

        <div class="calendar-form">

            <?php 

            ?>

            <input value="<?php echo $minusOneDay; ?>" readonly="readonly" type="hidden" class="form-control date-picker" name="calendar_check_in" id="calendar_check_in">
            <input value="<?php echo $minusOneDay; ?>"readonly="readonly" type="hidden" class="form-control date-picker" name="calendar_check_out" id="calendar_check_out">



            <?php do_action('st_after_day_tour_calendar_frontend'); ?>

            <?php if($post_type == 'st_tours'){ ?>

            <div class="row tour-calendar-price-fixed">

 

                <div class="col-xs-4" style="display:none;">

                    <div class="form-group">

                        <label for="calendar_base_price"><?php echo __('Base Price', 'traveler'); ?></label>

                    <!-- OPTIMA // BASE PRICE AVAILIABILITY FIX -->
                        <?php 

                        $base_price = (float) get_post_meta($post_id, 'base_price', true);

                        echo '<input value="'.$base_price.'" type="text" name="calendar_base_price" id="calendar_base_price" class="form-control number">'

                        ?>

                    </div>

                </div>

            </div>

            <?php } ?>



            <?php if($post_type == 'st_tours'){ ?>

                <input type="hidden" name="calendar_price_type" id="calendar_price_type" value="<?php echo STTour::get_price_type($post_id); ?>"/>

            <?php } ?>


            <div class="row">	

            	<div class="col-lg-6">

            		<h1>Availability</h1>
            		<p>This availability will be used across <strong>all</strong> of your offered fishing trips.</p>

                    <?php 

                        if(isMobile()){
                            echo '<p style="color:red;">The below calendar may not work on mobile devices.  If you are having issues, please visit this page on your computer.  We are working to resolve this problem with mobile in the near future.  Apologies for any inconvenience.</p>';
                        }


                    ?>

            		<br>

            	</div>

            	<div class="col-lg-6">


            		
            		<h3 style="display: inline;">Current Status:</h3> 

            		<?php 

            			if ($counter > 0) {
            				echo '<img style="display:inline; vertical-align: top;" width="25px" height="25px" src="'.get_site_url().'/wp-content/themes/traveler/img/green.png">';
            				echo '<br><p>You have '.$counter.' available day(s) in the next 6 months.</p>';
            			} else {
            				echo '<img style="display:inline; vertical-align: top;" width="25px" height="25px" src="'.get_site_url().'/wp-content/themes/traveler/img/red.png">';
            				echo '<br><p>You need to add availability!</p>';
            			}
            		?>

            		
            		

            	</div>

            </div>	

            <div class="row">

                <div class="col-lg-3">

           		 <p><strong>Step 1:</strong> I want to set a day or days as...</p>

                    <div class="form-group ">

                        <select name="calendar_status" id="calendar_status" class="form-control">

                            <option value="available"><?php echo __('Available', 'traveler'); ?></option>

                            <option value="unavailable"><?php echo __('Unavailable', 'traveler'); ?></option>

                        </select>

                    </div>

                    <br>

                    <p><strong>Step 2:</strong> Click on the day or days you want to update/add/remove availability from.  You can update multiple days at once by click and dragging.</p>

                    <br>

                    <p><strong>Step 3:</strong> Click update below to update your availbility with your desired status and selected dates.</p>

                    <br>


                            <div style="text-align: center;"class="form-group">

                <input type="hidden" name="calendar_post_id" value="<?php echo esc_attr($post_id); ?>">

                <input type="submit" id="calendar_submit" class="btn btn-primary" name="calendar_submit" value="<?php echo __('Update Calendar', 'traveler'); ?>">

                <?php do_action('traveler_after_form_submit_tour_calendar'); ?>

            </div>

 


                </div>




                <?php

                    if($post_type== 'st_tours'){

                        $type = get_post_meta($post_id,'type_tour',true);

                    }elseif($post_type == 'st_activity'){

                         $type = get_post_meta($post_id,'type_activity',true);

                    }

                ?>



    <div class="col-xs-6 col-md-6 calendar-wrapper-inner">

        <div class="overlay-form"><i class="fa fa-refresh text-color"></i></div>

        <div id="calendar-content"

             data-hide_adult="<?php echo get_post_meta($post_id,'hide_adult_in_booking_form',true) ?>"

             data-hide_children="<?php echo get_post_meta($post_id,'hide_children_in_booking_form',true) ?>"

             data-hide_infant="<?php echo get_post_meta($post_id,'hide_infant_in_booking_form',true) ?>"

            >

        </div>

    </div>


            </div>




        </div>

    </div>



                    <?php do_action('traveler_after_form_submit_tour_calendar'); ?>
</div>

