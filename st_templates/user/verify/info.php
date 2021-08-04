<?php

error_reporting(E_ALL);

$check_email = true;

$check_phone=  true;

$check_passport = true;

$check_cer = true;

$check_social = true;



if(empty($data['user_email'])){

    $check_email = false;

}

if(empty($data['st_phone'])){

	$check_email = false;

}



$data_check_passport = array(

	$data['passport_name'],

	$data['passport_id'],

	$data['passport_birthday'],

	$data['passport_photos']

);



$data_check_cer = array(

	$data['business_c_name'],

	$data['business_c_email'],

	$data['business_c_address'],

	$data['business_c_phone'],

	$data['business_r_name'],

	$data['business_r_position'],

	$data['business_r_passport_id'],

	$data['business_r_issue_date']

);



if(empty($data['social_facebook_uid']) || empty($data['social_facebook_name'])){

	$check_social = false;

}



$check_passport = st_check_user_verify_empty($data_check_passport);

$check_cer = st_check_user_verify_empty($data_check_cer);



$user_verify_all = st_check_user_verify('', $data['user_id']);

?>

<div class="verify-box">

	<h3><?php echo __('Verifications Info', 'traveler'); ?>

        <?php

            if($check_email && $check_phone && $check_passport && $check_cer && $check_social){

        ?>

			<button class="btn-verify-all" data-nonce="<?php echo wp_create_nonce( 'user_verifications' ); ?>" data-user_id="<?php echo esc_attr($data['user_id']); ?>" ><?php echo __('Verify all', 'traveler');  ?></button>

        <?php } ?>

		<?php echo STUser::verify_status($data['user_id'])['html']; ?><span class="status-text"><?php echo __('Status: ', 'traveler'); ?></span>

	</h3>



	<div class="verify-item">

		<div class="verify-label">

			<b><?php echo __('Email', 'traveler'); ?></b>

            <?php if(empty($data['user_email'])){ ?>

			<button class="btn-verify-invalid" data-user_id="<?php echo esc_attr($data['user_id']); ?>" data-criteria="email"><?php echo __('Send notice', 'traveler'); ?></button>

                <textarea rows="3" class="invalid-reason"></textarea>

            <?php }else{ ?>

                <?php echo STUser::verify_status_by_key($data['user_id'], 'email', true); ?>

                <button class="btn-verify-invalid" data-user_id="<?php echo esc_attr($data['user_id']); ?>" data-criteria="email"><?php echo __('Send notice', 'traveler'); ?></button>

			<button class="btn-verify-single" data-user_id="<?php echo esc_attr($data['user_id']); ?>" data-criteria="email"><?php echo __('Verify', 'traveler'); ?></button>

                <textarea rows="3" class="invalid-reason"></textarea>

            <?php } ?>

		</div>

		<div class="verify-value">

			<?php

				if(!empty($data['user_email'])){

					echo esc_attr($data['user_email']);

				}else{

					echo '<span class="empty-info">-------- ' . __('Empty', 'traveler') . ' --------</span>';

				}

				?>

		</div>

	</div>



	<div class="verify-item">

		<div class="verify-label">

			<b><?php echo __('Phone', 'traveler'); ?></b>

			<?php if(empty($data['st_phone'])){ ?>

			<button class="btn-verify-invalid" data-user_id="<?php echo esc_attr($data['user_id']); ?>" data-criteria="phone"><?php echo __('Send notice', 'traveler'); ?></button>

                <textarea rows="3" class="invalid-reason"></textarea>

			<?php }else{ ?>

				<?php echo STUser::verify_status_by_key($data['user_id'], 'phone', true); ?>

                <button class="btn-verify-invalid" data-user_id="<?php echo esc_attr($data['user_id']); ?>" data-criteria="phone"><?php echo __('Send notice', 'traveler'); ?></button>

			<button class="btn-verify-single" data-user_id="<?php echo esc_attr($data['user_id']); ?>" data-criteria="phone"><?php echo __('Verify', 'traveler'); ?></button>

                <textarea rows="3" class="invalid-reason"></textarea>

            <?php } ?>

		</div>

		<div class="verify-value">

			<?php

			if(!empty($data['st_phone'])){

				echo esc_html($data['st_phone']);

			}else{

				echo '<span class="empty-info">-------- ' . __('Empty', 'traveler') . ' --------</span>';

			}

			?>

		</div>

	</div>



	<div class="verify-item idcard">

		<div class="verify-label">

			<b><?php echo __("Captain's License", 'traveler'); ?></b>

            <?php

            	// $captainLicenseStatus = get_user_meta($data['user_id'],'captainLicenseStatus', true);
                 //possible verification statuses


            if(!$check_passport) {


				$captainLicenseStatus = get_user_meta($data['user_id'],'captainLicenseStatus', true);
				if (empty($captainLicenseStatus)) {
					$captainLicenseStatus = 0;
				}

				//badges
	            if ($captainLicenseStatus == 0) {
					echo '<div class="verify-status-item icon none"><span class="optimaCL dashicons dashicons-yes"></span><span class="dashicons dashicons-minus"></span></div>';
				} else if ($captainLicenseStatus == 1) {
					echo '<div class="verify-status-item icon none"><span class="optimaCL dashicons dashicons-warning"><span class="dashicons dashicons-minus"></span></span></div>';
				} else if ($captainLicenseStatus == 2) {
					echo '<div class="verify-status-item icon none"><span class="optimaCL dashicons dashicons-no"><span class="dashicons dashicons-minus"></span></span></div>';
				} else if ($captainLicenseStatus == 3) {
					echo '<div class="verify-status-item icon all"><span class="optimaCL dashicons dashicons-yes"><span class="dashicons dashicons-minus"></span></span></div>';
				}


				$verifStatus = [

					0 => 'Not Submitted',
					1 => 'Submitted, Pending our Review',
					2 => 'Captain Needs to Review',
					3 => 'Verified'

				];

				echo '<br><p class="captainLicenseStatus" style="display: inline-block;margin-block-start:  0em;margin-block-end: 0em;">' . $verifStatus[$captainLicenseStatus] . '</p>';


				//notes for the captain

				$notes = get_user_meta($data['user_id'], 'captainLicenseNotes', true);	

				if ($notes) {
					echo '<textarea id="CLdeny_rsn" name="CLdeny_rsn" rows="2" style="display:  inline-block;  margin-left: 20px;   float: right;">'.$notes.'</textarea>';
				} else {
					echo '<textarea id="CLdeny_rsn" name="CLdeny_rsn" rows="2" style="display:  inline-block;  margin-left: 20px;   float: right;"></textarea>';
				}




				?>


                <button class="btn-verify-single" data-user_id="<?php echo esc_attr($data['user_id']); ?>" data-criteria="passport"><?php echo __( 'Verify', 'traveler' ); ?></button>
                <button class="btn-verify-single" data-user_id="<?php echo esc_attr($data['user_id']); ?>" data-criteria="_deny"><?php echo __( 'Deny', 'traveler' ); ?></button>

	            <?php

            }else{

            ?>

            	<textarea rows="3" class="invalid-reason"></textarea>

                <button class="btn-verify-invalid" data-user_id="<?php echo esc_attr($data['user_id']); ?>"

                        data-criteria="passport"><?php echo __( 'Send notice', 'traveler' ); ?></button>

                <button class="btn-verify-single" data-user_id="<?php echo esc_attr($data['user_id']); ?>"

                        data-criteria="passport"><?php echo __( 'Verify', 'traveler' ); ?></button>



            <?php } ?>

		</div>

		<div class="verify-value">
			<?php 

	            if (get_user_meta($data['user_id'],'captainLicenseURL', true)) {
					$captainLicenseURL = get_user_meta($data['user_id'], 'captainLicenseURL', true);
					echo '<a target="_blank" href="../wp-content/themes/traveler/st_templates/user/verifs/'.$captainLicenseURL.'"><img style="height: 50px; width: 50px;" src="../wp-content/themes/traveler/st_templates/user/verifs/'.$captainLicenseURL.'"/></a>';
				}
			?>

		</div>

	</div>



	<div class="verify-item idcard">

		<div class="verify-label">

			<b><?php echo __('Boat Insurance', 'traveler'); ?></b>

				<?php

				$boatInsuranceStatus = get_user_meta($data['user_id'],'boatInsuranceStatus', true);
				if (empty($boatInsuranceStatus)) {
					$boatInsuranceStatus = 0;
				}

				//badges
	            if ($boatInsuranceStatus == 0) {
					echo '<div class="verify-status-item icon none"><span class="optimaBI dashicons dashicons-yes"></span><span class="dashicons dashicons-minus"></span></div>';
				} else if ($boatInsuranceStatus == 1) {
					echo '<div class="verify-status-item icon none"><span class="optimaBI dashicons dashicons-warning"><span class="dashicons dashicons-minus"></span></span></div>';
				} else if ($boatInsuranceStatus == 2) {
					echo '<div class="verify-status-item icon none"><span class="optimaBI dashicons dashicons-no"><span class="dashicons dashicons-minus"></span></span></div>';
				} else if ($boatInsuranceStatus == 3) {
					echo '<div class="verify-status-item icon all"><span class="optimaBI dashicons dashicons-yes"><span class="dashicons dashicons-minus"></span></span></div>';
				}


				$verifStatus = [

					0 => 'Not Submitted',
					1 => 'Submitted, Pending our Review',
					2 => 'Captain Needs to Review',
					3 => 'Verified'

				];

				echo '<br><p class="boatInsuranceStatus" style="display: inline-block;margin-block-start:  0em;margin-block-end: 0em;">' . $verifStatus[$boatInsuranceStatus] . '</p>';

				//notes for the captain

				$notes = get_user_meta($data['user_id'], 'boatInsuranceNotes', true);	

				if ($notes) {
					echo '<textarea id="BIdeny_rsn" name="BIdeny_rsn" rows="2" style="display:  inline-block;  margin-left: 20px;   float: right;">'.$notes.'</textarea>';
				} else {
					echo '<textarea id="BIdeny_rsn" name="BIdeny_rsn" rows="2" style="display:  inline-block;  margin-left: 20px;   float: right;"></textarea>';
				}




				?>


                <button class="btn-verify-single" data-user_id="<?php echo esc_attr($data['user_id']); ?>" data-criteria="travel_certificate"><?php echo __( 'Verify', 'traveler' ); ?></button>
                <button class="btn-verify-single" data-user_id="<?php echo esc_attr($data['user_id']); ?>" data-criteria="_denyBI"><?php echo __( 'Deny', 'traveler' ); ?></button>

			

		</div>

		<div class="verify-value">

			<?php 

	            if (get_user_meta($data['user_id'],'boatInsuranceURL', true)) {
					$boatInsuranceURL = get_user_meta($data['user_id'], 'boatInsuranceURL', true);
					echo '<a target="_blank" href="../wp-content/themes/traveler/st_templates/user/verifs/'.$boatInsuranceURL.'"><img style="height: 50px; width: 50px;" src="../wp-content/themes/traveler/st_templates/user/verifs/'.$boatInsuranceURL.'"/></a>';
				}
			?>

		</div>

	</div>



    <div class="verify-item certificate idcard">

        <div class="verify-label">

            <b><?php echo __('Social', 'traveler'); ?></b>

			<?php if(empty($data['social_facebook_uid']) && empty('social_facebook_name')){ ?>

                <button class="btn-verify-invalid" data-user_id="<?php echo esc_attr($data['user_id']); ?>" data-criteria="social"><?php echo __('Send notice', 'traveler'); ?></button>

                <textarea rows="3" class="invalid-reason"></textarea>

			<?php }else{ ?>

				<?php echo STUser::verify_status_by_key($data['user_id'], 'social', true); ?>

                <button class="btn-verify-invalid" data-user_id="<?php echo esc_attr($data['user_id']); ?>" data-criteria="social"><?php echo __('Send notice', 'traveler'); ?></button>

                <button class="btn-verify-single" data-user_id="<?php echo esc_attr($data['user_id']); ?>" data-criteria="social"><?php echo __('Verify', 'traveler'); ?></button>

                <textarea rows="3" class="invalid-reason"></textarea>

			<?php } ?>

        </div>

        <div class="verify-value">

            <?php if(!empty($data['social_facebook_uid']) && !empty($data['social_facebook_name'])) { ?>

            <ul>

                <?php if ( ! empty( $data['social_facebook_uid'] ) ) { ?>

                <li>

                    <span class="card-label"><?php echo __('ID', 'traveler') ?></span>

                    <span class="card-value">

							<?php

								echo esc_html($data['social_facebook_uid']);

							?>

						</span>

                </li>

                <?php } ?>

	            <?php if ( ! empty( $data['social_facebook_name'] ) ) { ?>

                    <li>

                        <span class="card-label"><?php echo __('Name', 'traveler') ?></span>

                        <span class="card-value">

							<?php

							echo esc_html($data['social_facebook_name']);

							?>

						</span>

                    </li>

	            <?php } ?>

            </ul>

            <?php

            }else{

	            echo '<span class="empty-info">-------- ' . __('Empty', 'traveler') . ' --------</span>';

            }

            ?>

        </div>

    </div>

</div>

