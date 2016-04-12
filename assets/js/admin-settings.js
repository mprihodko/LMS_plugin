(function($){
	var _this={


		getUserByName:function(){
			$("#user_field").on("keyup",function(){
				var search = $(this).val();
				console.log('search ' , search);
				$.ajax({
					type:"GET",
					url: ajaxurl,
				  	data: {
					    action: "search_users_report",
					    value: encodeURIComponent(search)
					}, 
					success: function(result) {
						if(result==null) return	
						var users=$.parseJSON(result);
						if(users==null) return	
						$('#user_suggestions').html('');
						$('#user_suggestions').fadeIn();
						if(users.length>1){		
							$.each(users, function(k, v) {
								$('#user_suggestions').append("<li><a href=\"javascript:autoUser("+v.ID+", '"+v.user_login+"');\">"+v.user_login+"</a></li>");
							});
						}else if(users.length==1){
							$.each(users, function(k, v) {
								$("#user_field").attr("value", v.user_login);
								$("#user_id").attr("value", v.ID);
							});
							$('#user_suggestions').fadeOut();
							_this.getUserGroups()
						}		
					}
				})
			})
		},

		getUserGroups:function(){
			var user_id = $("#user_id").val();
			console.log('user_id ' , user_id);
			if(user_id!=0){
				$.ajax({
					type:"GET",
					url: ajaxurl,
				  	data: {
					    action: "search_user_groups",
					    value: encodeURIComponent(user_id)
					}, 
					success: function(json) {
						var resp=$.parseJSON(json);
						var output='<option value="0">Select Group</option>';
						$("#group_field").html('')
						$("#group_field").append(output);			
						$.each(resp, function(index, data){
							var output='<option value="'+data.group_id+'">'+data.name+'</option>';
							$("#group_field").append(output);				
						})
						$("#group_field").attr('disabled', false);					
					}
				})
			}
		},

		getTests:function(){
			$("#group_field").on("change", function(){
				var group_id = $(this).val();
				$.ajax({
					type:"GET",
					url: ajaxurl,
				  	data: {
					    action: "search_user_group_test",
					    value: encodeURIComponent(group_id)
					}, 
					success: function(json) {
						var resp=$.parseJSON(json);
						var output='<option value="0">Select Test</option>';
						$("#test_field").html('')
						$("#test_field").append(output);			
						$.each(resp, function(index, data){
							var output='<option value="'+data.ID+'">'+data.post_title+'</option>';
							$("#test_field").append(output);				
						})
						$("#test_field").attr('disabled', false);					
					}
				})
			});
		},

		writeUser:function(){
			$("#assign").on("click", function(){
				var user_id = $("#user_id").val();
				var group_id = $("#group_field").val();
				var test_id = $("#test_field").val();
				var date = $("#date_field").val();
				if(_this.validate(user_id) && _this.validate(group_id) && _this.validate(test_id) && _this.validate(date)){
					$.ajax({
					type:"GET",
					url: ajaxurl,
				  	data: {
					    action: "write_user_test",
					    user: 	encodeURIComponent(user_id),
					    group: 	encodeURIComponent(group_id),
					    test: 	encodeURIComponent(test_id),
					    date: 	encodeURIComponent(date)
					}, 
					success: function(json) {
										
					}
				})
				}else{
					alert("Error: Please check fields data!!!");
				}
			});
		},

		validate:function(item){
			if(item==0) return false;
			if(item=="") return false;
			if(item==null) return false;
			return true;
		},

		init:function(){
			_this.getUserByName();	
			_this.getTests();
			_this.writeUser();					
			
		}
	}

	_this.init();

})(jQuery)

function autoUser(id, login){
	jQuery("#user_field").attr("value", login);
	jQuery("#user_id").attr("value", id);
	getUserGroups(id);
}
function getUserGroups(user_id){	
	jQuery.ajax({
		type:"GET",
		url: ajaxurl,
	  	data: {
		    action: "search_user_groups",
		    value: encodeURIComponent(user_id)
		}, 
		success: function(json) {
			var resp=jQuery.parseJSON(json);
			var output='<option value="0">Select Group</option>';
			jQuery("#group_field").html('')
			jQuery("#group_field").append(output);			
			jQuery.each(resp, function(index, data){
				var output='<option value="'+data.group_id+'">'+data.name+'</option>';
				jQuery("#group_field").append(output);				
			})
			jQuery("#group_field").attr('disabled', false);
		}
	})
}		