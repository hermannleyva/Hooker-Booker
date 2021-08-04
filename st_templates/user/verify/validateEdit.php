<?php

	// error_reporting(E_ALL);

	if ($_SERVER['DOCUMENT_ROOT'] == 'C:/wamp64/www') {
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

	if (isset($_FILES["file"]["name"])) {
		$filename = $_FILES["file"]["name"];
		$tempname = $_FILES["file"]["tmp_name"];
	} else {
		$filename = $_POST['filename'];
	}

	//date
	$currentDate = date('Y-m-d H:i:s');

	//get the post id and user id
	$post_id = $_POST['post_id'];
	$user_id = $_POST['user_id'];
	$post_object = '';
	$meta_object = '';

	//check and make sure the userid matches the post id
	if ($post_id && $user_id) {

		$post_object = get_post($post_id);
		$meta_object = get_post_meta($post_id);

		if ($post_object->post_author != $user_id) {

			echo 'Unauthorized, please contact us';
			return;

		}

	} else {

		echo 'Error, please contact us';
		return;

	}

	//divy the info into meta and post
	$infoPost = [

	'post_title'   => $_POST["tripName"],
	'post_content'  => $_POST["description"]

	];


	$infoMeta = [

	'duration_day'  => $_POST["duration"],
	'max_people'    => $_POST["maxPpl"],
	'tours_include' => $_POST["tripIncludes"],
	'tours_exclude' => $_POST["pleaseBring"],
	'base_price'    => $_POST["price"],
	'min_price'     => $_POST['price'],
	'sale_price'    => $_POST['price'],
	'trip_type'     => $_POST["tripType"],
	'cancPolicy'    => $_POST["cancPolicy"],
	'address'       => $_POST["address"],
	'postImageUrl'  => $filename

	];

	//after gathering all the information, check to see what is different than what we have in the DB.
	//if we find a difference, add the meta key to an array ($updateList below).  the array will then iterate and update all the needed fields.
	$updateList = array();

	//first check wp_post
	foreach ($infoPost as $k => $v) { 

		if ($post_object->$k != $v) {

			$updateList[$k] = $v;

		}

	}

	//second check wp_postmeta
	foreach ($infoMeta as $k => $v) { 

		if ($meta_object[$k][0] != $v) {

			$updateList[$k] = $v;

		}
	}


	//process the update list
	//the only areas that need special attention are location and image. 
	//all others can easily just update via update_post_meta

	//if the list is empty, return that there was nothing to update!
	if (empty($updateList)) {

		echo 0;
		return;

	}

	foreach ($updateList as $k => $v) {

		if ($k === 'post_title' || $k === 'post_content') {

			$the_post = array(

				'ID' => $post_id,
				 $k => $v

			);


			wp_update_post($the_post);
		}

		//if duration
		if ($k === 'duration_day') {
			//find all the durations and their codes.
			//full day
			$fullQuery  = "SELECT `term_id` FROM `wp_terms` WHERE `slug` = 'full-day'";
			$fullResult = $wpdb->get_results($fullQuery, ARRAY_N);
			$fullID     = $fullResult[0][0];

			//half day
			$halfQuery  = "SELECT `term_id` FROM `wp_terms` WHERE `slug` = 'half-day'";
			$halfResult = $wpdb->get_results($halfQuery, ARRAY_N);
			$halfID     = $halfResult[0][0];

			//3/4 day
			$thrqtQuery  = "SELECT `term_id` FROM `wp_terms` WHERE `slug` = 'three-quarters-day'";
			$thrqtResult = $wpdb->get_results($thrqtQuery, ARRAY_N);
			$thrqtID     = $thrqtResult[0][0];

			//find the proper code for new duration
			$durQuery  = $wpdb->prepare("SELECT `term_id` FROM `wp_terms` WHERE `slug` = %s", $v);
			$durResult = $wpdb->get_results($durQuery, ARRAY_N);

			if (!$durResult) {
				echo 'Error, invalid duration.  Please contact us';
				return;
			}

			$newDurationID = $durResult[0][0];

			$q = $wpdb->prepare("SELECT `term_taxonomy_id` FROM `wp_term_relationships` WHERE `object_id` = %d AND `term_taxonomy_id` IN (%d,%d,%d)",$post_id, $fullID, $halfID, $thrqtID);
			$r = $wpdb->get_results($q, ARRAY_N);

			$table = 'wp_term_relationships';
			$data = [

				'term_taxonomy_id' => $newDurationID

			];
			$where = [

				'object_id' => $post_id,
				'term_taxonomy_id' => $r[0][0]

			];

			$wpdb->update($table, $data, $where);

			//find existing object on wp_term_relationships and update it 

	

			//update post meta	
			update_post_meta($post_id, $k, $v);

		} 

		//if location
		 else if ($k === 'address') {

			//get city and state
			$city  = $_POST['city'];
			$state = $_POST['state'];

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

				update_post_meta($post_id, 'multi_location', '_'.$parentLocationID.'_,_'.$cityLocationID.'_');

				//then add to wp_st_location_relationships
				$wpdb->insert('wp_st_location_relationships', array(

						'id' => 0,
						'post_id' => $post_id,
						'location_from' => $parentLocationID,
						'location_to' => 0,
						'post_type' => 'st_tours',
						'location_type' => 'multi_location'

				));

				$wpdb->insert('wp_st_location_relationships', array(

						'id' => 0,
						'post_id' => $post_id,
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
					$newCityLocationID = $post_id;

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
					$newCityLocationID = $post_id.'1';



					// $guidArray =  [

					// 	'ID' => $newLocationPostID,
					// 	'guid' => '.../?post_type=location&#038;p='.$newLocationPostID

					// ];
					
					// wp_update_post($guidArray);


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
				update_post_meta($post_id, 'multi_location', '_'.$newStateLocationPostID.'_,_'.$newLocationPostID.'_');

				//then add to wp_st_location_relationships
				$wpdb->insert('wp_st_location_relationships', array(

						'id' => 0,
						'post_id' => $post_id,
						'location_from' => $parentLocationID,
						'location_to' => 0,
						'post_type' => 'st_tours',
						'location_type' => 'multi_location'
				));

				$wpdb->insert('wp_st_location_relationships', array(

						'id' => 0,
						'post_id' => $post_id,
						'location_from' => $newCityLocationID,
						'location_to' => 0,
						'post_type' => 'st_tours',
						'location_type' => 'multi_location'
				));
			}

			//update post meta

			$mapArr = [

				'map_lng'  => $_POST['lat'],
				'map_lat'  => $_POST['lng'],
				'address' => $_POST["address"]

			];

			foreach ($mapArr as $k => $v) {
				update_post_meta($post_id, $k, $v);
			}

		//if duration	
		} else if ($k === 'trip_type') {

		if ($v == 'salt-water-trips') {
			$metaV = 'Salt Water';
		} else if ($v == 'fresh-water-trips') {
			$metaV = 'Fresh Water';
		} else if ($v == 'back-country-trips') {
			$metaV = 'Back Country';
		}

		update_post_meta($post_id, $k, $metaV);

			//find all the trip types and their codes.
			//full day
			$fullQuery  = "SELECT `term_id` FROM `wp_terms` WHERE `slug` = 'back-country-trips'";
			$fullResult = $wpdb->get_results($fullQuery, ARRAY_N);
			$fullID     = $fullResult[0][0];

			//half day
			$halfQuery  = "SELECT `term_id` FROM `wp_terms` WHERE `slug` = 'fresh-water-trips'";
			$halfResult = $wpdb->get_results($halfQuery, ARRAY_N);
			$halfID     = $halfResult[0][0];

			//3/4 day
			$thrqtQuery  = "SELECT `term_id` FROM `wp_terms` WHERE `slug` = 'salt-water-trips'";
			$thrqtResult = $wpdb->get_results($thrqtQuery, ARRAY_N);
			$thrqtID     = $thrqtResult[0][0];

			//find the proper code for new duration
			$durQuery  = $wpdb->prepare("SELECT `term_id` FROM `wp_terms` WHERE `slug` = %s", $v);
			$durResult = $wpdb->get_results($durQuery, ARRAY_N);

			if (!$durResult) {
				echo 'Error, invalid trip type.  Please contact us';
				return;
			}

			$newDurationID = $durResult[0][0];

			$q = $wpdb->prepare("SELECT `term_taxonomy_id` FROM `wp_term_relationships` WHERE `object_id` = %d AND `term_taxonomy_id` IN (%d,%d,%d)",$post_id, $fullID, $halfID, $thrqtID);
			$r = $wpdb->get_results($q, ARRAY_N);

			$table = 'wp_term_relationships';
			$data = [

				'term_taxonomy_id' => $newDurationID

			];
			$where = [

				'object_id' => $post_id,
				'term_taxonomy_id' => $r[0][0]

			];

			$wpdb->update($table, $data, $where);
		//if image
		} else if ($k === 'postImageUrl' ) {
			//create image (attachment) and hook to post
			//create folder in server to store all post images
			//store the urls in an array on postmeta (create new)
			//process captain license
			$randomString = strval(random_string(20));

			$allowed = array('gif', 'png', 'jpg', 'jpeg');
			$ext = strval(strtolower(pathinfo($filename, PATHINFO_EXTENSION)));
				
			if ($_SERVER['DOCUMENT_ROOT'] == 'C:/wamp64/www') {
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
					update_post_meta($post_id, 'postImageUrl', $newURL);
				}
			}

			} else {

			//update the keys regularly
			update_post_meta($post_id, $k, $v);
		}

	}

	echo 1;



?>