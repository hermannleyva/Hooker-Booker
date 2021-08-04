jQuery(document).ready(function() {

	jQuery('.btnDelete').click(function() {

		//get the post id from the button clicked
		var postID = jQuery(this).siblings('input').val();
		var block = this;

		//send the post id to php to delete
		jQuery.ajax({
	      type: "POST",
	      url: "../wp-content/themes/traveler/st_templates/user/verify/dashboardOps.php",
	      data: {

	      	'postID'  : postID,
	      	'func': 'delete'

	      },
	      dataType: "html"

	    }).done(function( result ) {
	    // "result" will contain whatever comes back from our PHP script
	    // so we'll use jQuery to put the result inside our results <div>.
	    	if (result = 'delete') {
	    		jQuery(block).parent().parent().siblings('.statusAlert').html('Post successfully deleted!');
	    		jQuery(block).parent().parent().hide();
	    	} else {
	    		jQuery(block).parent().parent().siblings('.statusAlert').html('Error processing request, please contact us.');
	    	}
	    	
		});		
	});

	jQuery('.btnDeactivate').click(function() {

		var postID = jQuery(this).siblings('input').val();
		var block = this;

		//send the post id to php to delete
		jQuery.ajax({
	      type: "POST",
	      url: "../wp-content/themes/traveler/st_templates/user/verify/dashboardOps.php",
	      data: {

	      	'postID'  : postID,
	      	'func': 'deactivate'

	      },
	      dataType: "html"

	    }).done(function( result ) {
	    // "result" will contain whatever comes back from our PHP script
	    // so we'll use jQuery to put the result inside our results <div>.
	    	if (result = 'deactivated') {
	    		jQuery(block).parent().parent().siblings('.statusAlert').html('Post successfully deactivated!');
	    		jQuery(block).html('Activate');
	    		jQuery(block).hide();

	    		var newDiv = jQuery(block).parent().parent().parent();

	    		jQuery(newDiv).prependTo('#addDeactivated');

	    	} else {

	    		jQuery(block).parent().parent().siblings('.statusAlert').html('Error processing request, please contact us.');

	    	}
	    	
		});			
	});

jQuery('.btnActivate').click(function() {

		var postID = jQuery(this).siblings('input').val();
		var block = this;

		//send the post id to php to delete
		jQuery.ajax({
	      type: "POST",
	      url: "../wp-content/themes/traveler/st_templates/user/verify/dashboardOps.php",
	      data: {

	      	'postID'  : postID,
	      	'func': 'activate'

	      },
	      dataType: "html"

	    }).done(function( result ) {
	    // "result" will contain whatever comes back from our PHP script
	    // so we'll use jQuery to put the result inside our results <div>.
	    	if (result = 'activated') {
	    		jQuery(block).parent().parent().siblings('.statusAlert').html('Post successfully Activated!');
	    		jQuery(block).hide();

	    		var newDiv = jQuery(block).parent().parent().parent();

	    		jQuery(newDiv).prependTo('#addActivated');


	    	} else {

	    		jQuery(block).parent().parent().siblings('.statusAlert').html('Error processing request, please contact us.');

	    	}
	    	
		});			
	});


})