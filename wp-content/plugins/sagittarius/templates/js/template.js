jQuery(document).ready(function(){
  jQuery("#get-sync-data").bind("click", function(e) {
    	e.preventDefault();
    	jQuery.ajax({
      	type:'POST',
        	url: sagittarius_ajax.ajax_url,
        	data: {
          	action:'get_api_data'
        	},
        	beforeSend: function() {
            console.log('beforeSend...........');
              jQuery('.ajax-spinner').show();
              jQuery('#get-sync-dataa').attr("disabled", "disabled");;
         	},
          complete: function() {
            console.log('complete...........');
             jQuery('.ajax-spinner').hide();
             jQuery('#get-sync-data').removeAttr("disabled");
          },
        	success: function(data) {
            console.log('success...........');
          	jQuery('.sync-report').html('<p><strong>Complete, with the following errors (if any):</strong></p>');
          	jQuery('.sync-report p').after(data+'<br />');
          	jQuery('#get-sync-data').hide();
          	jQuery('.make-live').fadeIn();
        	},
          error: function(jqXHR, textStatus, errorThrown) {
            console.log('error...........');
            jQuery('.sync-report').html('<p>There was an error processing your request.</p>');
            console.log('jqXHR:');
            console.log(jqXHR);
            console.log('textStatus:');
            console.log(textStatus);
            console.log('errorThrown:');
            console.log(errorThrown);
          }
      });
  });
  jQuery("#make-live").bind("click", function(e) {
    	e.preventDefault();
    	jQuery.ajax({
      	type:'POST',
        	url: sagittarius_ajax.ajax_url,
        	data: {
          	action:'push_data_live'
        	},
        	beforeSend: function() {
              jQuery('.ajax-spinner-live').show();
              jQuery('#make-live').attr("disabled", "disabled");;
         	},
          complete: function() {
             jQuery('.ajax-spinner-live').hide();
             jQuery('#make-live').removeAttr("disabled");
          },
        	success: function(data) {
          	jQuery('.sync-report').html('<p><strong>Data moved live: </strong></p>');
          	jQuery('.sync-report p').after(data);
          	jQuery('#get-sync-data').hide();
          	jQuery('.make-live').fadeIn();
          	jQuery('.make-live #make-live').fadeOut();
          	jQuery("html, body").animate({ scrollTop: 0 }, "fast");
        	},
          error: function(jqXHR, textStatus, errorThrown) {
            jQuery('.sync-report').html('<p>There was an error processing your request.</p>');
            console.log('jqXHR:');
            console.log(jqXHR);
            console.log('textStatus:');
            console.log(textStatus);
            console.log('errorThrown:');
            console.log(errorThrown);
          }
      });
  });
  jQuery("#check-tour").bind("click", function(e) {
      e.preventDefault();
      centaur_id = jQuery('input[id=sagittarius_check_tour_id]').val();
      console.log('centaur_id: '+centaur_id);
      if( !centaur_id ) {
        jQuery('.check-tour-report').html('<p><strong>Please enter a Centaur ID</strong></p>');
      } else {
        jQuery.ajax({
          type:'POST',
            url: sagittarius_ajax.ajax_url,
            dataType: 'json',
            data: {
              action: 'check_for_tour',
              centaurid: centaur_id
            },
            beforeSend: function() {
                console.log('before....');
                jQuery('.ajax-spinner').show();
                jQuery('#check-tour').attr("disabled", "disabled");;
            },
            complete: function() {
                console.log('complete....');
                jQuery('.ajax-spinner').hide();
                jQuery('#check-tour').removeAttr("disabled");
            },
            success: function(data) {
              console.log('success....');
              jQuery('.check-tour-report').html('<p>Tour found? <strong>'+data.status+'</strong></p>');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                jQuery('.check-tour-report').html('<p>There was an error processing your request.</p>');
                console.log('jqXHR:');
                console.log(jqXHR);
                console.log('textStatus:');
                console.log(textStatus);
                console.log('errorThrown:');
                console.log(errorThrown);
            }
        });
     }
  });
  jQuery("#restore").bind("click", function(e) {
    	e.preventDefault();
    	file_to_restore = jQuery('input[name=restore-file]:checked').val();
    	if( !file_to_restore ) {
    		jQuery('.restore-report').html('<p><strong>Please select a backup to restore</strong></p>');
    	} else {
    		jQuery.ajax({
        	type:'POST',
          	url: sagittarius_ajax.ajax_url,
          	data: {
          		action: 'restore_tour_data',
          		restorefile: file_to_restore
          	},
          	beforeSend: function() {
                jQuery('.ajax-spinner').show();
              	jQuery('#restore').attr("disabled", "disabled");;
           	},
            complete: function() {
               	jQuery('.ajax-spinner').hide();
             		jQuery('#restore').removeAttr("disabled");
            },
          	success: function(data) {
            	jQuery('.restore-report').html('<p><strong>'+data+'</strong></p><p><a href="/wp-admin/admin.php?page=sagittarius/templates/sagitarrius-restore.php">Refresh file list</a></p>');
          	},
            error: function(jqXHR, textStatus, errorThrown) {
              jQuery('.restore-report').html('<p>There was an error processing your request.</p>');
              console.log('jqXHR:');
              console.log(jqXHR);
              console.log('textStatus:');
              console.log(textStatus);
              console.log('errorThrown:');
              console.log(errorThrown);
            }
        });
  	 }
  });
  jQuery("#remove-files").bind("click", function(e) {
    	e.preventDefault();
  		jQuery.ajax({
      	type:'POST',
        	url: sagittarius_ajax.ajax_url,
        	data: {
        		action: 'remove_backups'
        	},
        	beforeSend: function() {
              jQuery('.ajax-spinner-remove').show();
              jQuery('#remove-files').attr("disabled", "disabled");;
         	},
          complete: function() {
          	jQuery('.ajax-spinner-remove').hide();
             	jQuery('#remove-files').removeAttr("disabled");
          },
        	success: function(data) {
          	jQuery('.clear-report').html('<p><strong>'+data+'</strong></p>');
          	jQuery('.restore-report').html('<p><strong>No backup files to restore.</strong></p>');
          	jQuery('#restore-form').hide();
        	},
          error: function(jqXHR, textStatus, errorThrown) {
            jQuery('.restore-report').html('<p>There was an error processing your request.</p>');
            console.log('jqXHR:');
            console.log(jqXHR);
            console.log('textStatus:');
            console.log(textStatus);
            console.log('errorThrown:');
            console.log(errorThrown);
          }
      });
  });
});




