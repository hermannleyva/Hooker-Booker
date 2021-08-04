<?php

/**

 * @package WordPress

 * @subpackage Traveler

 * @since 1.0

 *

 * User create tours

 *

 * Created by ShineTheme

 *

 */

$tour_add_fields = ST_Config::get('partner.add.tour');

$post_id = STInput::request('id', '');

$data_status = 'new';

if(!empty($post_id)) {

    $data_status = 'edit';

}

?>

<div class="st-create-service">

    <h2 class="st-header">

        <?php

        //OPTIMA - REMOVE PACKAGES FROM ARRAY THAT POPULATES TABS/FIELDS
        array_splice($tour_add_fields['content'], -2, 1);

        if(!empty($post_id)){

            echo __('Edit Trip', 'traveler');

        }else {

            echo __('Add Trip', 'traveler');

        }

        ?>

    </h2>

    <?php echo STTemplate::message(); ?>

    <div class="st-create-service-content <?php echo (empty($post_id) ? ' add' : 'edit')?>">

        <div>

            <!-- Nav tabs -->

            <ul class="nav nav-tabs" role="tablist">

                <?php

                if(!empty($tour_add_fields['tabs'])){

                    foreach ($tour_add_fields['tabs'] as $k => $v){

                        $class_active = '';

                        if($k == 0)

                            $class_active = 'active';

                        $icon = '<img src="'. get_template_directory_uri() . '/v2/images/svg/ico_check_badge.svg' .'" />';

                        echo '<li role="presentation" class="'. esc_attr($class_active) .'" data-obj="tab"><a href="#'. esc_attr($v['name']) .'" aria-controls="'. esc_attr($v['name']) .'" role="tab" data-toggle="tab">'. balanceTags($v['label'] . $icon) .'</a></li>';

                    }

                }

                ?>

            </ul>



            <!-- Tab panes -->

            <div class="tab-content tab-content-parent">

                <?php

                if(!empty($tour_add_fields['content'])){

                    $ic = 0;

                    foreach ($tour_add_fields['content'] as $k => $v){

                        $class_active = '';

                        if($ic == 0)

                            $class_active = 'active';

                        ?>

                        <div role="tabpanel" class="tab-pane <?php echo esc_attr($class_active); ?>" id="<?php echo esc_attr($k); ?>" data-order="<?php echo esc_attr($ic); ?>">

                            <div class="row">

                                <form method="post" action="" class="st-partner-create-form">

                                    <?php

                                    $fields = $tour_add_fields['content'][$k];

                                    if(!empty($fields)){

                                        foreach ($fields as $kk => $vv){

                                            $class_col = 'col-lg-12';

                                            if(!empty($vv['col']))

                                                $class_col = 'col-lg-' . $vv['col'];



                                            if(isset($vv['clear'])){

                                                $class_col .= ' st-clear-fix';

                                            }

                                            $class_item ='';

                                            if(isset($vv['class'])){

                                                $class_item = $vv['class'];

                                            }

                                            ?>

                                            <div style="<?php 

                                            //OPTIMA - HIDE FIELDS

                                            if ($vv['label']=='Trip type') { echo 'display: none;'; } 

                                            if ($vv['label']=='Booking Options') { echo 'display: none;'; } 

                                            if ($vv['label']=='Show price by') { echo 'display: none;'; } 

                                            if ($vv['label']=='Deposit payment options') { echo 'display: none;'; } 

                                            ?>" class="<?php echo esc_attr($class_col); ?> st-partner-field-item <?php echo esc_attr($class_item);?>">


                                                <?php 

                                                    // <!-- OPTIMA - ADDED HINTS WHILE CREATING TRIPS -->

                                                    if ($vv['label'] == 'BASIC INFORMATION')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        The Trip Name is what customers will see while searching the website.</br>
                                                        Please be sure to include a detailed cancellation policy in the description, or else your trip will not be approved by The Hooker Booker team.
                                                        
                                                        </p>';

                                                    }   

                                                    if ($vv['label'] == 'Trip Category')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        What kind of fishing trip are you offering?
                                                        
                                                        </p>';

                                                    }

                                                    if ($vv['label'] == 'Durations')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        Rough estimate of how long your fishing trip will be.  You will be able to specify a more precise amount below in this page.
                                                        
                                                        </p>';

                                                    }

                                                    if ($vv['label'] == 'Languages')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        Select the language(s) that you are able to communicate effectively with.</br>
                                                        If you do not see your language here, please e-mail <a href="mailto:tech@thehookerbooker.com">tech@thehookerbooker.com</a> with the language you need added.</br>
                                                        Even if your language is not present, you may continue with the submission of this trip.
                                                        
                                                        </p>';

                                                    }

                                                    if ($vv['label'] == 'Trip type')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        This is set to Specific Date as default and cannot be changed.</br>
                                                        You may ignore this field.
                                                        
                                                        </p>';

                                                    }


                                                    if ($vv['label'] == 'Duration (in hours)')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        Please specify how many hours your trip is estimated to take.</br>
                                                        This field is required.
                                                        
                                                        </p>';

                                                    }

                                                    if ($vv['label'] == 'Booking period')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        How many days in advanced you require to accept a booking.</br>
                                                        This field is required.
                                                        
                                                        </p>';

                                                    }               


                                                    if ($vv['label'] == 'Minimum # of people')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        The minimum number of people you are willing to take.</br>
                                                        This field is required.
                                                        
                                                        </p>';

                                                    }  

                                                   if ($vv['label'] == 'Maximum # of people')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        The maximum number of people you are able to safely take.</br>
                                                        This field is required.
                                                        
                                                        </p>';

                                                    }  

                                                   if ($vv['label'] == 'Booking Options')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        This is set to Instant Booking as default and cannot be changed.</br>
                                                        You may ignore this field
                                                        
                                                        </p>';

                                                    }

                                                   if ($vv['label'] == 'Layout Style')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        Select the layout style you would like to use for your trip.  You may change this at any time.
                                                        
                                                        </p>';

                                                    }


                                                   if ($vv['label'] == 'Trip Includes')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        What equipment/items/services are included with your trip?  Bait? Rods?</br>
                                                        Do you clean the fish for the customer?</br> 
                                                        List anything you think will help your booking chances!
                                                        
                                                        </p>';

                                                    }

                                                   if ($vv['label'] == 'Trip Excludes')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        What are some things you want to indicate to the customer that they need to bring on their own?</br>
                                                        Food, drinks, sun-tan lotion, etc.</br>
                                                        Please be sure to include anything that is critical for the enjoyment of your trip.
                                                        
                                                        </p>';

                                                    }       

                                                   if ($vv['label'] == 'Trip Highlights')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        What are some unique or extraordinary qualities that your trip offers that makes you different from the rest?</br>
                                                        Top-of-the-line sound system, etc.
                                                        
                                                        </p>';

                                                    }

                                                   if ($vv['label'] == 'Trip Images')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        Select a primary image that will be the showcase image for your trip on the website while customers are searching.</br>
                                                        Primary Image is required.</br>
                                                        </br>
                                                        You may add additional images that can be viewed from within your trip post, but this is not required.
                                                    
                                                        </p>';

                                                    }


                                                   if ($vv['label'] == 'Show price by')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        This is set to Price by Fixed as default and cannot be changed.</br>
                                                        You may ignore this field
                                                        
                                                        </p>';
                                                    

                                                    }

                                                   if ($vv['label'] == 'Base price')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        This is the total price of your trip.  Remember that the Hooker Booker will take 20% from this value.  It will be your responsibility to collect the rest from the customer at the day of the trip.
                                                        
                                                        </p>';
                                                    

                                                    }

                                                   if ($vv['label'] == 'Deposit payment options')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        This is set to Deposit by Percent as default and cannot be changed.</br>
                                                        You may ignore this field
                                                        
                                                        </p>';
                                                    

                                                    }

                                                   // if ($vv['label'] == 'Deposit amount')  {

                                                   //       echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                   //      The minimum value is 30%.  This is required.</br>
                                                   //      </br>
                                                   //      Do not set over 30%, as this has no added benefits (yet).
                                                        
                                                   //      </p>';
                                                    

                                                   //  }

                                                   if ($vv['label'] == 'What state and city is your trip in?')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        Select your state AND city from the list below. </br>
                                                        If you do not see your state or city, please email <a href="mailto:tech@thehookerbooker.com">tech@thehookerbooker.com</a> with the state and/or city that you need added.  </br>

                                                        </br>

                                                        If this is your case, simply select the state of Florida for now.  We will change it to your needed city/state once we process your request.
                                                        
                                                        </p>';
                                                    

                                                    }

                                                   if ($vv['label'] == 'Actual Physical Address')  {

                                                         echo '<p style="color: gray; background-color: #fffbd5; padding: 5px; border-radius: 15px; text-align: center; margin-bottom: 15px;">

                                                        Please enter the exact physical address where the customer will be meeting you for the day of the trip.
                                                        </br>
                                                        </br>
                                                        Underneath the map just below this, the only option you need to worry about is the zoom level.  Select the zoom level of the map that you want to appear on your trip.</br>
                                                        </br>
                                                        You can do this simply by moving your pointer over the map and using the scroll wheel on your mouse.
                                                        
                                                        </p>';
                                                    

                                                    }


                                                ?>


                                                <?php echo st()->load_template('fields/' . esc_html($vv['type']), '', array('data' => $vv, 'post_id' => $post_id, 'label' =>$vv['label'] )); ?>

                                                

                                            </div>

                                            <?php

                                        }

                                    }

                                    if($ic < count($tour_add_fields['content']) - 1) {

                                        echo '<input type="hidden" name="step" value="' . ($ic + 1) . '"/>';

                                    }else{

                                        echo '<input type="hidden" name="step" value="final"/>';

                                    }

                                    echo '<input type="hidden" name="step_name" value="'. esc_attr($k) .'"/>';

                                    if(isset($_GET['id'])&& !empty($_GET['id'])){

                                        echo '<input type="hidden" name="btn_update_post_type_tour" value="1"/>';

                                        echo '<input type="hidden" name="post_id" value="'. esc_attr($_GET['id']) .'"/>';

                                    }else{

                                        echo '<input type="hidden" name="btn_insert_post_type_tour" value="1"/>';

                                        echo '<input type="hidden" class="st-partner-input-post-id" name="post_id" value=""/>';

                                    }

                                    ?>

                                    <input type="hidden" name="action" value="st_partner_create_service_tour"/>

                                </form>

                            </div>

                        </div>

                        <?php

                        $ic++;

                    }

                }

                ?>

            </div>



            <div class="st-partner-action">

                <input type="submit" class="form-control btn btn-primary st-btn-back" value="<?php echo __('BACK', 'traveler'); ?>"/>

                <button type="submit" class="form-control btn btn-primary st-btn-continue" data-obj="button" data-status="<?php echo esc_attr($data_status); ?>"><span><?php echo __('CONTINUE', 'traveler'); ?></span> <i class="fa fa-spin fa-spinner"></i></button>

            </div>

        </div>

    </div>

</div>

