
/******************************************************************  Tests **********************************************************************/
function searchTest(val){
	jQuery.ajax({
	  	type:"GET",
	  	url: ajaxurl,
	  	data: {
		    action: "search_tests",
		    value: encodeURIComponent(val)
		}, 
		success: function(result) {
			var tests=jQuery.parseJSON(result);
			jQuery('#test_suggestions').html('');
			jQuery('#test_suggestions').fadeIn();		
			jQuery.each(tests, function(k, v) {
				jQuery('#test_suggestions').append("<li><a href=\"javascript:addTest("+v.ID+", '"+v.post_title+"');\">"+v.post_title+"</a></li>");
			});
		}
	});
}	
function addTest(id, title ){
	var template=jQuery("#test_to_group").html();
	var output=template
	 	.replace(/{test_id}/g, id)
	 	.replace(/{test_name}/g, title)
	jQuery('#selected_tests tbody').append(output);
	jQuery('#test_suggestions').fadeOut();
}

function removeTest(id){
	if(jQuery("#group_id").val()>0){	
		jQuery.ajax({
		  	type:"GET",
		  	url: ajaxurl,
		  	data: {
			    action: "remove_test",
			    value: encodeURIComponent(id),
			    group: encodeURIComponent(jQuery("#group_id").val())
			}, 
			success: function(result) {
				var results=jQuery.parseJSON(result);
				if(results.result=='success')
					jQuery('#selected_tests tbody').find('#tests-'+id).remove();
				else
					alert("Error: Can't remove this Test!!!");	
			}
		});
	}else{
		jQuery('#selected_tests tbody').find('#tests-'+id).remove();
	}
}
/******************************************************************  Tests **********************************************************************/




/****************************************************************** Users **********************************************************************/
function searchUser(val){
	jQuery.ajax({
	  	type:"GET",
	  	url: ajaxurl,
	  	data: {
		    action: "search_users",
		    value: encodeURIComponent(val)
		}, 
		success: function(result) {
			var users=jQuery.parseJSON(result);
			jQuery('#user_suggestions').html('');
			jQuery('#user_suggestions').fadeIn();		
			jQuery.each(users, function(k, v) {
				jQuery('#user_suggestions').append("<li><a href=\"javascript:addUser("+v.ID+", '"+v.user_login+"');\">"+v.user_login+"</a></li>");
			});
		}
	});
}	
function addUser(id, name ){
	var template=jQuery("#user_to_group").html();
	var output=template
	 	.replace(/{user_id}/g, id)
	 	.replace(/{username}/g, name)
	jQuery('#selected_users tbody').append(output);
	jQuery('#user_suggestions').fadeOut();
}

function removeUser(id){
	if(jQuery("#group_id").val()>0){
		jQuery.ajax({
		  	type:"GET",
		  	url: ajaxurl,
		  	data: {
			    action: "remove_user",
			    value: encodeURIComponent(id),
			    group: encodeURIComponent(jQuery("#group_id").val())
			}, 
			success: function(result) {
				var results=jQuery.parseJSON(result);
				if(results.result=='success')
					jQuery('#selected_users tbody').find('#users-'+id).remove();
				else
					alert("This User is owner this Group!!!");	
			}
		});	
	}else{
		jQuery('#selected_users tbody').find('#users-'+id).remove();
	}
}

/****************************************************************** Users **********************************************************************/




/***************************************************************** Courses *********************************************************************/
function searchCourses(val){
	jQuery.ajax({
	  	type:"GET",
	  	url: ajaxurl,
	  	data: {
		    action: "search_courses",
		    value: encodeURIComponent(val)
		}, 
		success: function(result) {
			var users=jQuery.parseJSON(result);
			jQuery('#courses_suggestions').html('');
			jQuery('#courses_suggestions').fadeIn();		
			jQuery.each(users, function(k, v) {
				jQuery('#courses_suggestions').append("<li><a href=\"javascript:addCourse("+v.ID+", '"+v.post_title+"');\">"+v.post_title+"</a></li>");
			});
		}
	});
}	
function addCourse(id, name ){
	var template=jQuery("#course_to_group").html();
	var output=template
	 	.replace(/{courseid}/g, id)
	 	.replace(/{course-name}/g, name)
	jQuery('#selected_courses tbody').append(output);
	jQuery('#courses_suggestions').fadeOut();
}
function removeCourse(id){
	if(jQuery("#group_id").val()>0){
		jQuery.ajax({
		  	type:"GET",
		  	url: ajaxurl,
		  	data: {
			    action: "remove_courses",
			    value: encodeURIComponent(id),
			    group: encodeURIComponent(jQuery("#group_id").val())
			}, 
			success: function(result) {
				var results=jQuery.parseJSON(result);
				if(results.result=='success')
					jQuery('#selected_courses tbody').find('#courses-'+id).remove();
				else
					alert("Error: Can't remove this course!!!");	
			}
		});
	}else{
		jQuery('#selected_courses tbody').find('#courses-'+id).remove();	
	}
}
/***************************************************************** Courses *********************************************************************/




/************************************************************* GROUP LIST AJAX *****************************************************************/
function removeGroup(id){
	jQuery.ajax({
		type:"GET",
	  	url: ajaxurl,
	  	data: {
	  		action: "remove_group",
		    value: encodeURIComponent(id)
		},
		success: function(result) { 
			jQuery("#group-"+id).addClass('removed');
			jQuery("#delete-"+id).attr('href', 'javascript: deleteGroup("'+id+'")');			
			jQuery("#restore-"+id).attr("style", "display: inline");
		}
	});
}

function deleteGroup(id){
	jQuery.ajax({
		type:"GET",
	  	url: ajaxurl,
	  	data: {
	  		action: "delete_group",
		    value: encodeURIComponent(id)
		},
		success: function(result) { 
			jQuery("#group-"+id).remove();
		}
	});
}

function restoreGroup(id){
	jQuery.ajax({
		type:"GET",
	  	url: ajaxurl,
	  	data: {
	  		action: "restore_group",
		    value: encodeURIComponent(id)
		},
		success: function(result) { 
			jQuery("#group-"+id).removeClass('removed');
			jQuery("#delete-"+id).attr('href', 'javascript:removeGroup("'+id+'")');
			jQuery("#restore-"+id).attr("style", "display: none");
		}
	});
}

function addGroup(id, name){
	var template=jQuery("#group_add").html();
	var output=template
	 	.replace(/{group_id}/g, id)
	 	.replace(/{groupname}/g, name)
	jQuery('#selected_groups tbody').append(output);
	jQuery('#group_suggestions').fadeOut();
}

function delGroup(id){
	jQuery('#selected_groups tbody').find('#groups-'+id).remove();
}

jQuery(document).ready(function($){
	jQuery(document).click(function(){
		jQuery('#test_suggestions').fadeOut();
		jQuery('#user_suggestions').fadeOut();
		jQuery('#courses_suggestions').fadeOut();
		jQuery('#group_suggestions').fadeOut();
	})
	jQuery(".copy_group").live("click", function(e){
		e.preventDefault();
		var group = $(this).data('id');
		$.ajax({
		  	type:"GET",
		  	url: ajaxurl,
		  	data: {
			    action: "copy_group",
			    value: encodeURIComponent(group)			   
			}, 
			success: function(result) {
				var results=jQuery.parseJSON(result);
				if(results.result=='success')
					alert("Copied!!!");
				else
					alert("Error: Can't copy this group!!!");	
			}
		});
	});
  
	$(".view_group_reports").live("click", function(e){
		e.preventDefault();
		var group = [$(this).data('id')];
		$( "#report_modal" ).dialog({
	                autoOpen: false,
	            	modal: true,
	          	    dialogClass: 'fixed-dialog',
	    		    show: {
	                	effect: "blind",
	                    duration: 1000
	      	        },
		  	        hide: {
	                    effect: "explode",
	                    duration: 1000
	                }
	            });
        $("#report_modal").dialog( "open" );
        $.ajax({
			type:"POST",
		  	url: ajaxurl, 
		  	data: {
			    action: "get_reports_group_report",						    
			    data_value: group,
			    attempts: "false",
			    hits: "false",
			    day_from: "0001-01-01",
			    day_to: "9999-01-01"
			}, 
			success: function(json) {
				$("#results_table").html('');
				var template = $("#results_template").html();							
				var resp=$.parseJSON(json);
				if(resp){
					$.each( resp, function (index, value){
						$.each( value, function(k, v){

							var row = template
				              .replace(/{num}/g,					((v.num)?v.num:'-'))							             
				              .replace(/{first_name}/g,				((v.first_name)?v.first_name:'-'))
				              .replace(/{last_name}/g,				((v.last_name)?v.last_name:''))
				              .replace(/{post_title}/g,				((v.post_title)?v.post_title:'no-name'))
				              .replace(/{score}/g,					((v.score)?v.score:'-'))
				              .replace(/{symbol}/g, 				((v.symbol)?v.symbol:'-'))										
				              .replace(/{time}/g, 					((v.time)?v.time:'-'))	
				              .replace(/{date_hits}/g,  			((v.date_hits)?v.date_hits:v.time))
				              .replace(/{attempts}/g,				((v.attempts)?v.attempts:'0'))
				              .replace(/{attempts_limit}/g,			((v.attempts_limit)?v.attempts_limit:'0'))
				              .replace(/{hits}/g,					((v.hits)?v.hits:v.attempts))
				              .replace(/{hits_limit}/g,				((v.hits_limit)?v.hits_limit:'0'))
				              .replace(/{lms_interaction_date}/g,	((v.lms_interaction_date)?v.lms_interaction_date:'-'))						           
			  				  .replace(/{due}/g,					((v.due)?v.due:'-'));		
							  $("#results_table").append(row);
						});
					});		  			
				}
			}
		});
	});
});

