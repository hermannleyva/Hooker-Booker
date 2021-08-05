<?php

	error_reporting(E_ALL);

	

	if ($_SERVER['DOCUMENT_ROOT'] == 'C:/wamp64/www' || $_SERVER['DOCUMENT_ROOT'] == '/home/oun3sjtyi7cs/public_html') {
		require_once $_SERVER['DOCUMENT_ROOT'].'/hookerbooker/wp-load.php';
	} else {
		require_once $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';
	}



	//random string generator for file name encryption
	function random_string($length) {
		$key = '';
		$keys = array_merge(range(0, 9), range('a', 'z'));

		for ($i = 0; $i < $length; $i++) {
			$key .= $keys[array_rand($keys)];
		}
		return $key;
	}

	//collect information
	$user_id      = $_POST["user_id"];
	$tripName     = $_POST["tripName"];
	$description  = $_POST["description"];
	$duration     = $_POST["duration"];
	$maxPpl       = $_POST["maxPpl"];
	$tripIncludes = $_POST["tripIncludes"];
	$pleaseBring  = $_POST["pleaseBring"];
	$price        = $_POST["price"];
	$tripType     = $_POST["tripType"];
	$cancPolicy   = $_POST["cancPolicy"];
	$fullAddress  = $_POST["address"];
	$city         = $_POST['city'];
	$state        = $_POST['state'];
	$lat          = $_POST['lat'];
	$lng          = $_POST['lng'];
	$filename 	  = $_FILES["file"]["name"];
	$tempname     = $_FILES["file"]["tmp_name"];
	$post_status  = '';

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


	if (!$captainLicenseStatus || !$boatInsuranceStatus) {
		$post_status = 'draft';
	} else if ($captainLicenseStatus[0] == '3' && $boatInsuranceStatus[0] == '3' && $days) {
		$post_status = 'publish';
	} else {
		$post_status = 'draft';
	}


	//////1. create new post (wp_posts)	
	////process trip name to create url friendly name (post_name)
	//remove spaces and replace with hyphens
	$post_name = str_replace("‐"," ", $tripName);
	$currentDate = date('Y-m-d H:i:s');

	////create post array
	//id, post_author, post_date, post_date_gmt, post_content (description), post_title(tripName), post_status(draft if not verified), comment status(open), ping_status(closed), post_name(lower case all and add hyphen to words),post_parent(0), menu_order(0), post_type(st_tours)
	$newPost = [
		'post_author'    => $user_id,
		'post_date'      => $currentDate,
		'post_date_gmt'  => $currentDate,
		'post_content'   => $description,
		'post_title'     => strip_tags($tripName),
		'post_status'    => $post_status,
		'comment_status' => 'open',
		'ping_status'    => 'closed',
		'post_name'      => $post_name,
		'post_parent'    => 0,
		'menu_order'     => 0,
		'post_type'      => 'st_tours'
	];


	

	//store the new post_id for all the other tables needed below
	$newPostID = wp_insert_post($newPost);

	//make sure the post was inserted successfully, if not return and spit out error.
	if ($newPostID == 0) {
		echo 'Error, please contact us.';
		return;
	}

	//////2. create post meta and hook to post
	////create post meta array
	//min_price, rate_review(0), is_featured(off), type_tour(specific_date), duration_day(half/full day), tours_booking_period(1), max_people,st_booking_option_type(instant), min_people(1), st_tour_external_booking(off), tours_include, tours_exclude, st_custom_layout_new(1/2/3), tour_price_by, base_price, hide_adult_in_booking_form(off), hide_children_in_booking_form(off), hide_infant_in_booking_form(on), discount_by_people_type(percent), discount_type(percent), discount(0), is_sale_schedule(off), sale_price_from & sale_price_to(1970-01-01),	deposit_payment_status(percent), deposit_payment_amount(20), st_allow_cancel(off), st_cancel_percent(0), address, map_lat, map_lng, map_zoom(13), map_type(roadmap), st_google_map(??), enable_street_views_google_map(on), _thumbnail_id(??), is_iframe(off), calculator_discount_by_people_type(total), disable_adult_name(off), disable_children_name(off), disable_infant_name(off), tours_program_style(style 1), is_meta_payment_gateway_st_submit_form(on), is_meta_payment_gateway_st_paypal(on), is_meta_payment_gateway_vina_stripe(on)

	$cleanTripType = '';
	//trip type filter
	if ($tripType == "fresh-water-trips") {
		$cleanTripType = "Fresh Water";
	} else if ($tripType == "salt-water-trips") {
		$cleanTripType = "Salt Water";
	} else if ($tripType == "back-country-trips"){
		$cleanTripType = "Back Country Fishing";
	}	


	$newPostMeta = [

		'min_price' 								=> $price,
		'rate_review' 								=> 0,
		'is_featured' 								=> 'off',
		'type_tour' 								=> 'specific_date',
		'duration_day' 								=> $duration,
		'tours_booking_period' 						=> 1,
		'max_people' 								=> $maxPpl,
		'st_booking_option_type' 					=> 'instant',
		'min_people' 								=> 1,
		'st_tour_external_booking' 					=> 'off',
		'tours_include' 							=> $tripIncludes,
		'tours_exclude' 							=> $pleaseBring,
		'st_custom_layout_new' 						=> 1,
		'tour_price_by' 							=> 'fixed',
		'base_price' 								=> $price,
		'hide_adult_in_booking_form' 				=> 'off',
		'hide_children_in_booking_form'				=> 'off',
		'hide_infant_in_booking_form' 				=> 'on',
		'discount_by_people_type' 					=> 'percent',
		'discount_type' 							=> 'percent',
		'discount' 									=> 0,
		'is_sale_schedule' 							=> 'off',
		'sale_price_from' 							=> '1970-01-01',
		'sale_price_to'								=> '1970-01-01',
		'deposit_payment_status' 					=> 'percent',
		'deposit_payment_amount' 					=> 10,
		'st_allow_cancel' 							=> 'off',
		'st_cancel_percent' 						=> 0,
		'address' 									=> $fullAddress,
		'map_lat' 									=> $lat,
		'map_lng'									=> $lng,
		'map_zoom' 									=> 12,
		'map_type' 									=> 'roadmap',
		//'st_google_map' 							=> //'a:4{s:3:"lat";s:XX:"$lat";s:3:"lng";s:XX:"$lng";s:4:"zoom";s:2:"12";s:4:"type";s:7:"roadmap";}'
		'enable_street_views_google_map' 			=> 'on',
		//'_thumbnail_id' 							=> //cannot find this either,
		'is_iframe' 								=> 'off',
		'calculator_discount_by_people_type' 		=> 'total',
		'disable_adult_name' 						=> 'off',
		'disable_children_name' 					=> 'off',
		'disable_infant_name' 						=> 'off',
		'tours_program_style' 					 	=> 'style 1',
		'is_meta_payment_gateway_st_submit_form'	=> 'on',
		'is_meta_payment_gateway_st_paypal'     	=> 'on',
		'is_meta_payment_gateway_vina_stripe'   	=> 'on',
		'trip_type'									=> $cleanTripType,
		'cancPolicy'                                => $cancPolicy,
		'sale_price'								=> $price
	];

	//insert into post meta table
	foreach($newPostMeta as $metaKey => $value) {
		update_post_meta( $newPostID, $metaKey, $value);
	}


	////add duration objects
	//find the durations so we don't have to hard code the ID's
	$durQuery  = $wpdb->prepare("SELECT `term_id` FROM `wp_terms` WHERE `slug` = %s", $duration);
	$durResult = $wpdb->get_results($durQuery, ARRAY_N);

	if (!$durResult) {
		echo 'Error, invalid duration.  Please contact us';
		return;
	}

	//insert into database
	$wpdb->insert('wp_term_relationships', array(

		'object_id' => $newPostID,
		'term_taxonomy_id' => $durResult[0][0]

	));

	////add trip type objects
	$typeQuery = $wpdb->prepare("SELECT `term_id` FROM `wp_terms` WHERE `slug` = %s", $tripType);
	$typeResult = $wpdb->get_results($typeQuery, ARRAY_N);

	if (!$typeQuery) {
		echo 'Error, invalid trip type.  Please contact us';
		return;
	}

	$wpdb->insert('wp_term_relationships', array(

		'object_id' => $newPostID,
		'term_taxonomy_id' => $typeResult[0][0]

	));

	//create/add location
	//check to see if location already exist, checks city AND state

	$query  = $wpdb->prepare("SELECT `location_id`,`parent_id` FROM `wp_st_location_nested` WHERE `fullname` = %s", $city.', '.$state);
	$result = $wpdb->get_results($query, ARRAY_N);

	//If there is a result, take the location_id and lookup the location_id of the parent to add both to update_post_meta
	//else if there is no result, then we must determine if at least hte parent exists
	if ($result) {


		$cityLocationID = $result[0][0];
		$parentID = $result[0][1];

		$q = $wpdb->prepare("SELECT `location_id` FROM `wp_st_location_nested` WHERE `id` = %s", $parentID);
		$r = $wpdb->get_results($q, ARRAY_N);

		$parentLocationID = $r[0][0];

		update_post_meta($newPostID, 'multi_location', '_'.$parentLocationID.'_,_'.$cityLocationID.'_');

		//then add to wp_st_location_relationships
		$wpdb->insert('wp_st_location_relationships', array(

				'id' => 0,
				'post_id' => $newPostID,
				'location_from' => $parentLocationID,
				'location_to' => 0,
				'post_type' => 'st_tours',
				'location_type' => 'multi_location'
		));

		$wpdb->insert('wp_st_location_relationships', array(

				'id' => 0,
				'post_id' => $newPostID,
				'location_from' => $cityLocationID,
				'location_to' => 0,
				'post_type' => 'st_tours',
				'location_type' => 'multi_location'
		));



	} else {
	//determine if parent/state exists
		
		$q = $wpdb->prepare("SELECT `location_id`,`id` FROM `wp_st_location_nested` WHERE `name` = %s", $state);
		$r = $wpdb->get_results($q, ARRAY_N);

		if ($r) {
		//if the state exists, add the id to variable to use later
			//id
			$lastid = $r[0][1];
			//location_id
			$parentLocationID = $r[0][0];
			//create new id for city
			$newCityLocationID = $newPostID;

			$newStateLocationPostID = $parentLocationID;

		} else {
			//generate entirely new parent/state.
			//create new post for state
			//id($newPostID), post_author(1), post_date(date('Y-m-d H:i:s')), post_date_gmt(same), pst_title($city), post_status(publish), comment_status(closed), ping_status(closed), post_name(str_replace("‐"," ", $tripName)),post_modified(date),post_modified_gmt(date), post_parent($parentLocationID),  guid(.../?post_type=location&#038;p=(id from this new post), menu_order(0), post_type(location), comment_count(0)

			$newLocationArray = [

				'post_author' => 1,
				'post_date' => $currentDate,
				'post_date_gmt' => $currentDate,
				'post_title' => $state,
				'post_status' => 'publish',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_name' => str_replace("‐"," ", $state),
				'post_modified' => $currentDate,
				'post_modified_gtm' => $currentDate,
				'post_parent' => 0,
				'menu_order' => 0,
				'post_type' => 'location',
				'comment_count' => 0		

			];


			$newStateLocationPostID = wp_insert_post($newLocationArray);

			//now that the post has been created, insert into nested
			//id(generate), location_id(generate), location_country(US), parent_id(generate), right_key/left_key(0), name($state), fullname($state), language(en), status(publish)
			$wpdb->insert('wp_st_location_nested', array(

				'id' 				=> 0,
				'location_id' 		=> $newStateLocationPostID,
				'location_country' 	=> 'US',
				'parent_id' 		=> 1,
				'right_key' 		=> 0,
				'left_key'			=> 0,
				'name' 				=> $state,
				'fullname' 			=> $state,
				'language' 			=> 'en',
				'status' 			=> 'publish'

			));

			$lastid = $wpdb->insert_id;
			$parentLocationID = $newStateLocationPostID;		
			$newCityLocationID = $newPostID.'1';



	


		}

		//then create new post for city
		//id($newPostID), post_author(1), post_date(date('Y-m-d H:i:s')), post_date_gmt(same), pst_title($city), post_status(publish), comment_status(closed), ping_status(closed), post_name(str_replace("‐"," ", $tripName)),post_modified(date),post_modified_gmt(date), post_parent($parentLocationID),  guid(.../?post_type=location&#038;p=(id from this new post), menu_order(0), post_type(location), comment_count(0)

		$newLocationArray = [

			'id' => 0,
			'post_author' => 1,
			'post_date' => $currentDate,
			'post_date_gmt' => $currentDate,
			'post_title' => $city,
			'post_status' => 'publish',
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_name' => str_replace("‐"," ", $city),
			'post_modified' => $currentDate,
			'post_modified_gtm' => $currentDate,
			'post_parent' => $parentLocationID,
			'menu_order' => 0,
			'post_type' => 'location',
			'comment_count' => 0		

		];

		$newLocationPostID = wp_insert_post($newLocationArray);

		

		//then add the city to nested
		$wpdb->insert('wp_st_location_nested', array(

				'id' 				=> 0,
				'location_id' 		=> $newLocationPostID,
				'location_country' 	=> 'US',
				'parent_id' 		=> $lastid,
				'right_key' 		=> 0,
				'left_key'			=> 0,
				'name' 				=> $city,
				'fullname' 			=> $city.', '.$state,
				'language' 			=> 'en',
				'status' 			=> 'publish'

		));

		//then add the location id's to post meta
		update_post_meta($newPostID, 'multi_location', '_'.$newStateLocationPostID.'_,_'.$newLocationPostID.'_');

		//then add to wp_st_location_relationships
		$wpdb->insert('wp_st_location_relationships', array(

				'id' => 0,
				'post_id' => $newPostID,
				'location_from' => $parentLocationID,
				'location_to' => 0,
				'post_type' => 'st_tours',
				'location_type' => 'multi_location'
		));

		$wpdb->insert('wp_st_location_relationships', array(

				'id' => 0,
				'post_id' => $newPostID,
				'location_from' => $newCityLocationID,
				'location_to' => 0,
				'post_type' => 'st_tours',
				'location_type' => 'multi_location'
		));
	}

	//create entry in wp_st_tours
	//post_id, multi_location, address, price, min_price, max_people, type_tour (specific date), rate_review(0), duration_day (half day/full day), 
	//tours_booking_period(1), is_sale_schedule(off), discount(0), sale price_from and sale_price_to(1970-01-01), price_type(fixed),
	//is_featured(off), discount_type(percent)
	$multiLoc = get_post_meta($newPostID, 'multi_location');


	// $tourArr = [

	// 	'post_id' => $newPostID,
	// 	// 'multi_location' => $multiLoc,
	// 	'address' => $fullAddress,
	// 	'price' => $price,
	// 	'min_price' => $price,
	// 	'max_people' => $maxPpl,
	// 	'type_tour' => 'specific_date',
	// 	'rate_review' => 0,
	// 	'duration_day' => $duration,
	// 	'tours_booking_period' => 1,
	// 	'is_sale_schedule' => 'off',
	// 	'discount' => 0,
	// 	'sale_price_from' => '1970-01-01',
	// 	'sale_price_to' => '1970-01-01',
	// 	'price_type' => 'fixed',
	// 	'is_featured' => 'off',
	// 	'discount_type' => 'percent'

	// ];

	// $wpdb->insert('wp_st_tours', $tourArr);




	
	//create image (attachment) and hook to post
	//create folder in server to store all post images
	//store the urls in an array on postmeta (create new)
	//process captain license
	$randomString = strval(random_string(20));

	$allowed = array('gif', 'png', 'jpg', 'jpeg');
	$ext = strval(strtolower(pathinfo($filename, PATHINFO_EXTENSION)));
	
	if ($_SERVER['DOCUMENT_ROOT'] == 'C:/wamp64/www' || $_SERVER['DOCUMENT_ROOT'] == '/home/oun3sjtyi7cs/public_html') {
		$folder = $_SERVER['DOCUMENT_ROOT'] . '/hookerbooker/wp-content/themes/traveler/st_templates/user/postimgs/' . $randomString .'.'.$ext;
	} else {
		$folder = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/traveler/st_templates/user/postimgs/' . $randomString .'.'.$ext;
	}

	

	if (!in_array($ext, $allowed)) {
		echo 'File type not supported, please upload the pictures in one of the following formats: <br>';
		echo '.gif, .png, .jpg, .jpeg';
		return;
	} else {

		if (move_uploaded_file($tempname, $folder)) {
			$newURL = $randomString.'.'.$ext;
			update_post_meta($newPostID, 'postImageUrl', $newURL);
		}
	}

	echo 'success';

?>