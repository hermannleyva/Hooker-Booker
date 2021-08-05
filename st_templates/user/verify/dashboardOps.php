<?php

error_reporting(E_ALL);

if ($_SERVER['DOCUMENT_ROOT'] == 'C:/wamp64/www' || $_SERVER['DOCUMENT_ROOT' == '/home/oun3sjtyi7cs/public_html') {
	require_once $_SERVER['DOCUMENT_ROOT'].'/hookerbooker/wp-load.php';
} else {
	require_once $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';
}


//get the post ID to be deleted
$post_id      = $_POST["postID"];
$function     = $_POST['func'];

if ($function == 'delete') {
	//delete base post
	wp_delete_post($post_id, true);

	//collect all post meta data and delete those too
	$metas = get_post_meta($post_id);

	foreach($metas as $key => $val) {
		delete_post_meta($post_id, $key, $val);
	}

	echo 'delete';

} else if ($function == 'deactivate') {

	$post = array('ID' => $post_id, 'post_status' => 'draft');
	
	wp_update_post($post);

	echo 'deactivated';

} else if ($function == 'activate') {

	$post = array('ID' => $post_id, 'post_status' => 'publish');
	
	wp_update_post($post);

	echo 'activated';

}



?>