<?php 

error_reporting(E_ALL);
date_default_timezone_set('America/New_York');

global $wpdb;

$user_id = get_current_user_id();

$webURL = get_site_url();

//////check if the user is verified to automatically publish post
//check boat insurance and captain license, then check availability.  only if they are all good will the post be in published state, otherwise it will be in draft state.
$captainLicenseStatus = get_user_meta($user_id, 'captainLicenseStatus');
$boatInsuranceStatus = get_user_meta($user_id, 'boatInsuranceStatus');

//check available days.  if at least 1 available day, publish.  if not , draft.
$currentDate = date("Y-m-d");
$currentDateUnix = strtotime($currentDate);
$minusOneDay = date("Y-m-d", strtotime($currentDate .'-1 day'));

$expMonths = 6;
$expiring = strtotime('+'.$expMonths.' month'); 

$query = $wpdb->prepare("SELECT `id` FROM `wp_opt_tour_availability` WHERE `userid` = %d AND `status` = 'available' AND `check_in` BETWEEN %d AND %d", $user_id, $currentDateUnix, $expiring);
$days = $wpdb->get_results($query, ARRAY_N);

$post_status = '';

if (!$captainLicenseStatus || !$boatInsuranceStatus) {
	$post_status = 'draft';
} else if ($captainLicenseStatus[0] == '3' && $boatInsuranceStatus[0] == '3' && $days) {
	$post_status = 'publish';
} else {
	$post_status = 'draft';
}

?>
  
<script type="text/javascript" src="../wp-content/themes/traveler/js/validateOpt.js"></script>

<div id="dialog"></div>

<style type="text/css">

	.Err {
		color: red;
		font-weight: bold;
		font-size: 11px;
		text-transform: uppercase;
	}

	.content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            height: 200px;
            text-align: center;
            background-color: #e8eae6;
            box-sizing: border-box;
            padding: 10px;
            z-index: 100;
            display: none;
            /*to hide popup initially*/
        }
          
    .close-btn {
        position: absolute;
        right: 20px;
        top: 15px;
        background-color: black;
        color: white;
        border-radius: 50%;
        padding: 4px;
    }
	
</style>

<div class="container">

	<div class="row">
		
		<div class="col-lg-12">
			
			<h1>Fishing Trips Offered</h1>
			<p>If you have multiple trips of different types (location, price, etc) you will have to offer a new trip for each type. <br>
			   Please <a href="<?php echo $webURL.'/contact/' ?>">contact us</a> if you have any issues or questions, we are always happy to help.</p>

			<div style="color: red;" id="verifMessage"><?php if ($post_status != 'publish') { echo "This post will not be made public until you complete your verifications and add availability.  Please check the Captain's Dashboard for more information."; } ?></div>

		</div>

	</div>

	<hr>

	<p>All fields are required.</p>

	<div class="row">
		<div class="col-lg-12">
			<div id="tripStatus"></div>
		</div>
	</div>

	<div class="row">
		<div class="content">
	        <div onclick="togglePopup()" class="close-btn">
	            ×
	        </div>
	        <h3>Fishing trip successfully created!</h3>
	  
	        <p>If you want to duplicate this trip but just change a few things, simply click on the submit button again after changing what you need to change!</p>
	        <p>Otherwise, you may return to the dashboard.</p>


    	</div>
	</div>

	<div class="row">

		<div class="col-lg-12">

			<form method="POST" action="requestRecord();">

			<input type="hidden" value="<?php echo $user_id ?>" id="user_id">

			<label for="tripName">Trip Name:</label>
			<span id="tripNameErr" class="Err"></span>
			<textarea id="tripName" name="tripName" rows="1" value="" style="width: 100%;" /></textarea>

			<label for="tripDescription">Description:</label>
			<span id="descriptionErr" class="Err"></span>
			<textarea id="tripDescription" name="tripDescription" rows="4" value="" style="width:100%;"></textarea>

		</div>
		
	</div>

	<div class="row" style="margin-top: 20px;">
		
		<div class="col-lg-4">

			<label for="defaultCanc">Cancellation Policy</label>
			<p>You can choose to use the default cancellation policy, found <a href="#">here</a>, or use your own custom policy.</p>
			
			<fieldset id="checkArray">
				<input onClick="validateDefaultPolicy();" type="radio" name="cancPolicy"  value="defaultCanc" id="defaultCanc" style="margin-bottom: 0px;" checked>
				<span>Default Cancellation Policy</span>
				<br>
				<input onClick="validateCustomPolicy();" type="radio" name="cancPolicy" id="customCanc"  value="customCanc">
				<span>Custom Cancellation Policy</span>
				<textarea name="customDescrip" id="customDescrip" rows="5" placeholder="Please enter your custom policy here" style="display: none; width: 100%;"></textarea>
			</fieldset>

		</div>

		<div class="col-lg-4">
			
			<label for="duration">Duration</label>
			<p>How long is the trip?</p>
			<select id="duration" name="duration">
				<option value="half-day">Half Day : 4 hours</option>
				<option value="three-quarters-day">Three Quarter's Day : 6 hours</option>
				<option value="full-day">Full Day : 8 hours or more</option>
			</select>

		</div>

		<div class="col-lg-4">
			
			<label for="num_ppl">Maximum number of passengers</label>
			<span id="maxPplErr" class="Err"></span>
			<p>How many people can you safely carry?</p>
			<input type="input" name="maxPpl" id="maxPpl">

		</div>


	</div>

	<div class="row" style="margin-top: 20px;">
		
		<div class="col-lg-4">
			
			<label for="tripIncludes">Included with Trip</label>
			<span id="tripIncludesErr" class="Err"></span>
			<p>What are some things that you will supply that the customers do not need to worry about.  (bait, rods, etc)</p>
			<textarea id="tripIncludes" rows="1" name="tripIncludes" style="width: 100%;"></textarea>


		</div>

		<div class="col-lg-4">
			
			<label for="pleaseBring">Please Bring</label>
			<span id="pleaseBringErr" class="Err"></span>
			<p>What are some things you recommend the customers bring for their trip? (sunscreen, water, sunglasses, hat, snacks, etc)</p>
			<textarea id="pleaseBring" rows="1" name="pleaseBring" style="width: 100%;"></textarea>

		</div>

		<div class="col-lg-4">
			
			<label for="price">Price</label>
			<span  id="priceErr" class="Err"></span>
			<p>Price of your trip.</p>
			<input id="price" name="price">

			<br>

			<label for="tripType" style="margin-top:10px;">Trip Type</label>
			<select id="tripType" name="tripType">
				<option value="fresh-water-trips">Fresh Water</option>
				<option value="salt-water-trips">Salt Water</option>
				<option value="back-country-trips">Back Country Fishing</option>
			</select>

		</div>
	</div>

	<div class="row" style="margin-top: 20px;">
		
		<div class="col-lg-4">
			
			<label for"uploadFile">Trip Image</label>
			<span id="imageErr" class="Err"></span>
			<input type="file" name="uploadFile" id="uploadFile" />

		</div>

		<div class="col-lg-4">

			<label for="searchTextField">Where will customers meet you?</label>
			<span id="fullAddressErr" class="Err"></span>
			<input style="width:100%;" id="searchTextField" type="text" size="50">


		</div>

	</div>

	<div class="row" style="margin-top:60px;">
		
		<div class="col-lg-12" style="text-align:center;">
			<input type="button" onClick="requestRecord();" value="Submit">
		</form>


		</div>

	</div>




</div>




