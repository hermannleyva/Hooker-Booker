<?php 

// error_reporting(E_ALL);
date_default_timezone_set('America/New_York');

$user_id = get_current_user_id();
$post_id = $_GET['id'];
$imageDir = get_site_url().'/wp-content/themes/traveler/st_templates/user/postimgs/';

$webURL = get_site_url();

$post_title = '';

//take post_id, look up the author id.   make sure author id = user id, security, else return error
if ($getPost = get_post($post_id)) {

	$post_author = $getPost->post_author;
	$post_title = $getPost->post_title;
	$post_description = $getPost->post_content;

	if ($user_id == $post_author) {
		//take all the editable things from the post and post meta, fill them into the fields.
		//cancellation policy, duration, max ppl, inc, exc, price, trip type, image, address.
		//gather meta key values
		$cancPolicy = get_post_meta($post_id,'cancPolicy', true);
		$duration   = get_post_meta($post_id,'duration_day', true);
		$max_people = get_post_meta($post_id,'max_people', true);
		$inc        = get_post_meta($post_id,'tours_include', true);
		$exc        = get_post_meta($post_id,'tours_exclude', true);
		$price      = get_post_meta($post_id,'base_price', true);
		$tripType   = get_post_meta($post_id,'trip_type', true);
		$imageURL   = get_post_meta($post_id,'postImageUrl', true);
		$address    = get_post_meta($post_id,'address', true);

	} else {
		echo 'Unauthorized Access';
		return;
	}

} else {
	echo 'Error, please contact us.';
	return;
}





//validate fields

//update all the fields that were edited




?>
<script type="text/javascript" src="../wp-content/themes/traveler/js/validateEdit.js"></script>

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
            width: 100%;
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
			
			<h1>Edit a Fishing Trip</h1>
			<p>If you have multiple trips of different types (location, price, etc) you will have to offer a new trip for each type. <br>
			   Please <a target="_blank" href="<?php echo $webURL ?>/contact/">contact us</a> if you are having any problems, we are always happy to help.</p>

		</div>

	</div>

	<div class="row">
		<div class="content">
	        <div onclick="togglePopup()" class="close-btn">
	            ×
	        </div>

	        <h3>Fishing trip successfully updated!</h3>
	  
	        <button onClick="togglePopup()">Close this window</button>

	        <form action="<?php echo $webURL.'/page-user-setting/?sc=dashboard'; ?>">
	        	<input type="submit" value="Return to the Dashboard"/>
	        </form>
	</div>

	<hr>

	<p>All fields are required.</p>

	<div class="row">
		<div class="col-lg-12">
			<div id="tripStatus"></div>
		</div>
	</div>

	<div class="row">

		<div class="col-lg-12">

			<form method="POST" action="requestRecord();">

			<input type="hidden" value="<?php echo $user_id ?>" id="user_id">
			<input type="hidden" value="<?php echo $post_id ?>" id="post_id">

			<label for="tripName">Trip Name:</label>
			<span id="tripNameErr" class="Err"></span>
			<textarea id="tripName" name="tripName" rows="1" style="width: 100%;" /><?php echo $post_title; ?></textarea>

			<label for="tripDescription">Description:</label>
			<span id="descriptionErr" class="Err"></span>
			<textarea id="tripDescription" name="tripDescription" rows="4" value="" style="width:100%;"><?php echo $post_description; ?></textarea>

		</div>
		
	</div>

	<div class="row" style="margin-top: 20px;">
		
		<div class="col-lg-4">

			<label for="defaultCanc">Cancellation Policy</label>
			<p>You can choose to use the default cancellation policy, found <a target="_blank" href="<?php echo $webURL.'/cancellation-policy/'; ?>">here</a>, or use your own custom policy.</p>
			
			<fieldset id="checkArray">
				<input onClick="validateDefaultPolicy();" type="radio" name="cancPolicy"  value="defaultCanc" id="defaultCanc" style="margin-bottom: 0px;" <?php if ($cancPolicy === 'default' || !$cancPolicy) {
					echo 'checked';
				}  ?>
				>
				<span>Default Cancellation Policy</span>
				<br>
				<input onClick="validateCustomPolicy();" type="radio" name="cancPolicy" id="customCanc"  value="customCanc" <?php if ($cancPolicy === 'custom') {
					echo 'checked';
				} ?>>
				<span>Custom Cancellation Policy</span>
				<textarea name="customDescrip" id="customDescrip" rows="5" placeholder="Please enter your custom policy here" style="display: none; width: 100%;"></textarea>
			</fieldset>

		</div>

		<div class="col-lg-4">
			
			<label for="duration">Duration</label>
			<p>How long is the trip?</p>
			<select id="duration" name="duration">
				<option value="half-day" <?php if ($duration === 'half-day') {
					echo 'selected';
				}?>>Half Day : 4 hours</option>
				<option value="three-quarters-day" <?php if ($duration === 'three-quarters-day') {
					echo 'selected';
				}?>>Three Quarter's Day : 6 hours</option>
				<option value="full-day" <?php if ($duration === 'full-day') {
					echo 'selected';
				}?>>Full Day : 8 hours or more</option>
			</select>

		</div>

		<div class="col-lg-4">
			
			<label for="num_ppl">Maximum number of passengers</label>
			<span id="maxPplErr" class="Err"></span>
			<p>How many people can you safely carry?</p>
			<input type="input" name="maxPpl" id="maxPpl" value="<?php echo $max_people; ?>">

		</div>


	</div>

	<div class="row" style="margin-top: 20px;">
		
		<div class="col-lg-4">
			
			<label for="tripIncludes">Included with Trip</label>
			<span id="tripIncludesErr" class="Err"></span>
			<p>What are some things that you will supply that the customers do not need to worry about.  (bait, rods, etc)</p>
			<textarea id="tripIncludes" rows="1" name="tripIncludes" style="width: 100%;"><?php echo $inc; ?></textarea>


		</div>

		<div class="col-lg-4">
			
			<label for="pleaseBring">Please Bring</label>
			<span id="pleaseBringErr" class="Err"></span>
			<p>What are some things you recommend the customers bring for their trip? (sunscreen, water, sunglasses, hat, snacks, etc)</p>
			<textarea id="pleaseBring" rows="1" name="pleaseBring" style="width: 100%;"><?php echo $exc; ?></textarea>

		</div>

		<div class="col-lg-4">
			
			<label for="price">Price</label>
			<span  id="priceErr" class="Err"></span>
			<p>Price of your trip.</p>
			<input id="price" name="price" value="<?php echo $price; ?>">

			<br>

			<label for="tripType" style="margin-top:10px;">Trip Type</label>
			<select id="tripType" name="tripType">
				<option value="fresh-water-trips" <?php if ($tripType === 'Fresh Water') {
					echo 'selected';
				}?>>Fresh Water</option>
				<option value="salt-water-trips" <?php if ($tripType === 'Salt Water') {
					echo 'selected';
				}?>>Salt Water</option>
				<option value="back-country-trips" <?php if ($tripType === 'Back Country Fishing') {
					echo '"selected';
				}?>>Back Country Fishing</option>
			</select>

		</div>
	</div>

	<div class="row" style="margin-top: 20px;">
		
		<div class="col-lg-4">
			
			<label for="uploadFile" >Trip Image</label>
			<span id="imageErr" class="Err"></span>
			<input type="file" name="uploadFile" id="uploadFile" />
			<div id="imageThumbnail">
				<?php

					if ($imageURL) {
						echo '<img id="imgSrc" style="height: 100px; width: 100px; margin-top: 10px;" src="'.$imageDir.$imageURL.'">';
					}
				?>
			</div>

		</div>

		<div class="col-lg-4">

			<label for="searchTextField">Where will customers meet you?</label>
			<span id="fullAddressErr" class="Err"></span>
			<input style="width:100%;" id="searchTextField" type="text" size="50" value="<?php echo $address; ?>">


		</div>

	</div>

	<div class="row" style="margin-top:60px;">
		
		<div class="col-lg-12" style="text-align:center;">
			<input class="btn btn-primary" type="button" onClick="requestRecord();" value="Submit">
		</form>


		</div>

	</div>




</div>