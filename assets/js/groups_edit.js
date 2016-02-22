
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
			jQuery(this).attr('href', 'javascript:deleteGroup("'+id+'")');
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

jQuery(document).ready(function(){
	jQuery(document).click(function(){
		jQuery('#test_suggestions').fadeOut();
		jQuery('#user_suggestions').fadeOut();
		jQuery('#courses_suggestions').fadeOut();
		jQuery('#group_suggestions').fadeOut();
	})
});