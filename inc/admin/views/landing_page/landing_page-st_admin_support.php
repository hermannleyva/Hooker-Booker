

<?php 

    $check_registered = false ; 

?>

<?php if (!$check_registered){?>

<div class="traveler-important-notice">

        <p class="about-description"><?php echo __("To access our support forum and resources, you first must register your purchase.", 'traveler') ; ?><br>

<?php echo __("See the",'traveler' ) ; ?> <a href="<?php echo admin_url('/admin.php?page=st_admin_registration');?>"> <?php echo __("Product Registration" , 'traveler' ); ?></a> <?php echo __("tab for instructions on how to complete registration." , 'traveler' ); ?></p>

    </div>

<?php }?>

<div class="traveler-registration-steps">

    	<div class="feature-section st_admin_support">

            <?php 

                $submit_a_ticket = "https://shinehelp.shinetheme.com/";

                $document = "http://guide.travelerwp.com/";

                $knowledgebase = "http://guide.travelerwp.com/";

                $video = "https://www.youtube.com/watch?v=rrTRHxAsr6E&list=PLKwVkOFkT-MaejfBfTO3TduavDRCbbmmx" ;

                $forum  = "https://shinehelp.shinetheme.com/";



            ?>

        	<div class='st_col_4'>

				<h4><span class="dashicons dashicons-sos"></span><?php echo __("Submit A Ticket",'traveler' ) ; ?></h4>

				<p><?php echo __("We offer excellent support through our advanced ticket system. Make sure to register your purchase first to access our support services and other resources.",'traveler' ) ; ?></p>

                <a href="<?php echo esc_url($submit_a_ticket) ; ?>" class="button button-large button-primary traveler-large-button" target="_blank"><?php echo __("Submit A Ticket",'traveler' ) ; ?></a>            </div>

            <div class='st_col_4'>

				<h4><span class="dashicons dashicons-book"></span><?php echo __("Documentation",'traveler' ) ; ?></h4>

				<p><?php echo __("This is the place to go to reference different aspects of the theme. Our online documentation is an incredible resource for learning the ins and outs of using traveler.",'traveler' ) ; ?></p>

                <a href="<?php echo esc_url($document);?>" class="button button-large button-primary traveler-large-button" target="_blank"><?php echo __("Documentation",'traveler' ) ; ?></a>            </div>

        	<div class="last-feature st_col_4">

				<h4><span class="dashicons dashicons-portfolio"></span><?php echo __("Knowledgebase",'traveler' ) ; ?></h4>

				<p><?php echo __("Our knowledgebase contains additional content that is not inside of our documentation. This information is more specific and unique to various versions or aspects of traveler.",'traveler' ) ; ?></p>

                <a href="<?php echo esc_url($knowledgebase);?>" class="button button-large button-primary traveler-large-button" target="_blank"><?php echo __("Knowledgebase",'traveler' ) ; ?></a>            </div>

            <div class='st_col_4'>

            	<h4><span class="dashicons dashicons-format-video"></span><?php echo __("Video Tutorials",'traveler' ) ; ?></h4>

				<p><?php echo __("Nothing is better than watching a video to learn. We have a growing library of high-definition, narrated video tutorials to help teach you the different aspects of using traveler.",'traveler' ) ; ?></p>

                <a href="<?php echo esc_url($video);?>" class="button button-large button-primary traveler-large-button" target="_blank"><?php echo __("Watch Videos",'traveler' ) ; ?></a>            </div>

            <div class='st_col_4'>

				<h4><span class="dashicons dashicons-groups"></span><?php echo __("Community Forum",'traveler' ) ; ?></h4>

				<p><?php echo __("We also have a community forum for user to user interactions. Ask another traveler user! Please note that ThemeFusion does not provide product support here.",'traveler' ) ; ?></p>

                <a href="<?php echo esc_url($forum);?>" class="button button-large button-primary traveler-large-button" target="_blank"><?php echo __("Community Forum",'traveler' ) ; ?></a>            </div>            

        </div>

    </div>