<?php 

error_reporting(E_ALL);
date_default_timezone_set('America/New_York');

$user_id = get_current_user_id();
// $user_id = 71;

//possible verification statuses
$verifStatus = [

	0 => 'Not Submitted',
	1 => 'Submitted, Pending Review',
	2 => 'Needs Review',
	3 => 'Verified'

];

//random string generator for file name encryption
function random_string($length) {
	$key = '';
	$keys = array_merge(range(0, 9), range('a', 'z'));

	for ($i = 0; $i < $length; $i++) {
		$key .= $keys[array_rand($keys)];
	}
	return $key;
}

function processBoth($user_id) {

	if (isset($_FILES["uploadLicense"])) {

		$server_root = $_SERVER['DOCUMENT_ROOT'];

		$randomStringLicense = strval(random_string(20));

	    $filenameLicense = $_FILES["uploadLicense"]["name"];
	    $tempnameLicense = $_FILES["uploadLicense"]["tmp_name"];

	    $allowed = array('gif', 'png', 'jpg', 'jpeg');
		$ext = strval(strtolower(pathinfo($filenameLicense, PATHINFO_EXTENSION)));

		if ($server_root == 'C:/wamp64/www' ||  $_SERVER['DOCUMENT_ROOT'] == '/home/oun3sjtyi7cs/public_html') {
			 $folderLicense = $_SERVER['DOCUMENT_ROOT'] . '/hookerbooker/wp-content/themes/traveler/st_templates/user/verifs/' . $randomStringLicense .'.'.$ext;
		} else {
			$folderLicense = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/traveler/st_templates/user/verifs/' . $randomStringLicense .'.'.$ext;
		}

		if (!$ext) {

		} else {

			if (!in_array($ext, $allowed)) {
			    echo 'File type not supported, please upload the pictures in one of the following formats: <br>';
			    echo '.gif, .png, .jpg, .jpeg';
			} else {

		    $licenseMetas = array( 
			    'captainLicenseStatus' => 1,
			    'captainLicenseNotes'  => '', 
			    'captainLicenseURL'    => $randomStringLicense.'.'.$ext,	
			    'captainLicenseSubDt'  => date("F j, Y, g:i a")
			);

		    // Now let's move the uploaded image into the folder: image

		    	if (move_uploaded_file($tempnameLicense, $folderLicense)) {
			    foreach($licenseMetas as $key => $value) {
					update_user_meta( $user_id, $key, $value, false);
					}
				}	

			}
		}

	}


	if (isset($_FILES["uploadInsurance"])) {

		$server_root = $_SERVER['DOCUMENT_ROOT'];

		$randomStringInsurance = strval(random_string(20));

	    $filenameInsurance = $_FILES["uploadInsurance"]["name"];
	    $tempnameInsurance = $_FILES["uploadInsurance"]["tmp_name"];

	    $allowed = array('gif', 'png', 'jpg', 'jpeg');
		$ext = strval(strtolower(pathinfo($filenameInsurance, PATHINFO_EXTENSION)));

		if ($server_root == 'C:/wamp64/www' || $_SERVER['DOCUMENT_ROOT'] == '/home/oun3sjtyi7cs/public_html') {
			 $folderInsurance = $_SERVER['DOCUMENT_ROOT'] . '/hookerbooker/wp-content/themes/traveler/st_templates/user/verifs/' . $randomStringInsurance .'.'.$ext;
		} else {
			$folderInsurance = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/traveler/st_templates/user/verifs/' . $randomStringInsurance .'.'.$ext;
		}

		if (!$ext) {

		} else {

			if (!in_array($ext, $allowed)) {
			    echo 'File type not supported, please upload the pictures in one of the following formats: <br>';
			    echo '.gif, .png, .jpg, .jpeg';
			} else {

		    $insuranceMetas = array( 
			    'boatInsuranceStatus' => 1,
			    'boatInsuranceNotes'  => '', 
			    'boatInsuranceURL'    => $randomStringInsurance.'.'.$ext,	
			    'boatInsuranceSubDt'  => date("F j, Y, g:i a")
			);

		    // Now let's move the uploaded image into the folder: image

		    	if (move_uploaded_file($tempnameInsurance, $folderInsurance)) {
			    foreach($insuranceMetas as $key => $value) {
					update_user_meta( $user_id, $key, $value, false);
					}
				}	

			}

		}
	}
}


//if both images are set:
if  ( isset($_POST['uploadDocs']))  {
	processBoth($user_id);
} 

?>



<div class="container">

	<div class="row">
		<div class="col-lg-12">
			<h3>Verifications</h3>
		</div>
	</div>
	
	<div class="row">
		
		<div class="col-lg-4">



			<?php 

			//get meta key value for statuses
			$st_verify_captainLicense = get_user_meta($user_id, 'captainLicenseStatus', true);
			$st_verify_boatInsurance = get_user_meta($user_id, 'boatInsuranceStatus', true);


			//check to see if the meta key exists for user, if not, assign a fake 0
			if (!$st_verify_boatInsurance) {
				$st_verify_boatInsurance = 0;
			}

			if (!$st_verify_captainLicense) {
				$st_verify_captainLicense = 0;
			}

			?>
			
			<h1>Captain's License</h1>
			<p>Status: <?php echo $verifStatus[$st_verify_captainLicense]; ?> </p>
			<?php

				if ($st_verify_captainLicense == 2) {
					echo '<p><strong>Comments from the team:</strong> '.get_user_meta($user_id, 'captainLicenseNotes', true);
				}


				if (get_user_meta($user_id, 'captainLicenseSubDt', true)) {
					$captainLicenseSubDt = get_user_meta($user_id, 'captainLicenseSubDt', true);
					echo '<p>Submission Date: ' . $captainLicenseSubDt  . '</p>';

				}



			?>

			<br>

			<?php 

				//if there is already an image uploaded, display that
				if (get_user_meta($user_id,'captainLicenseURL', true)) {
					$captainLicenseURL = get_user_meta($user_id, 'captainLicenseURL', true);
					echo '<img style="height: 300px; width: 300px;" src="../wp-content/themes/traveler/st_templates/user/verifs/'.$captainLicenseURL.'"/>';
				}
			 ?>

			<form method="POST" action="" enctype="multipart/form-data">
	            <input style="padding-top:10px;" type="file" name="uploadLicense" value="" />

		</div>

		<div class="col-lg-4">
			
			<h1>Boat Insurance</h1>
			<p>Status: <?php echo $verifStatus[$st_verify_boatInsurance]; ?> </p>

			<?php

				if ($st_verify_boatInsurance == 2) {
					echo '<p><strong>Comments from the team:</strong> '.get_user_meta($user_id, 'boatInsuranceNotes', true);
				}


				if (get_user_meta($user_id, 'boatInsuranceSubDt', true)) {
					$boatInsuranceSubDt = get_user_meta($user_id, 'boatInsuranceSubDt', true);
					echo '<p>Submission Date: ' . $boatInsuranceSubDt  . '</p>';

				}



			?>

			<br>

			<?php 

				//if there is already an image uploaded, display that
				if (get_user_meta($user_id,'boatInsuranceURL', true)) {
					$boatInsuranceURL = get_user_meta($user_id, 'boatInsuranceURL', true);
					echo '<img style="height: 300px; width: 300px;" src="../wp-content/themes/traveler/st_templates/user/verifs/'.$boatInsuranceURL.'"/>';
				}
			 ?>

			 
	            <input style="padding-top:10px;" type="file" name="uploadInsurance" value="" />


		</div>

	</div>

	<div class="row">
		<div class="col-lg-8">
		    <div style="padding-top:20px; text-align:center">
                <button class="btn btn-primary btn-file" type="submit" name="uploadDocs">
                  Upload Document(s)
                </button>
	            </div>
        	</form>
		</div>
	</div>

</div>