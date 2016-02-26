(function($){
	$(document).ready(function(){

	var _this={

		changeReportType:function(){
			$(".selector_item").on("click", function(){
				var type = $(this).data('type');
				$(".selector_item").removeClass("active");
				$(this).addClass("active");
				$(".report-type").removeClass("active");
				$("#"+type).addClass("active");
				$("#current_report").attr("value", type);
			});
		},

		searchGroupReports:function(){
			$("#groups_search").on("keyup", function(){
				var val=$('#groups_search').val();
				$.ajax({
					type:"GET",
				  	url: ajaxurl,
				  	data: {
					    action: "search_groups_report",
					    value: encodeURIComponent(val)
					}, 
					success: function(result) {
						if(result==null) return	
						var groups=$.parseJSON(result);
						if(groups==null) return
						$('#group_suggestions').html('');
						$('#group_suggestions').fadeIn();		
						$.each(groups, function(k, v) {
							$('#group_suggestions').append("<li><a href=\"javascript:addGroup("+v.group_id+", '"+v.name+"');\">"+v.name+"</a></li>");
						});
						
					}
				});
				
			});			
		},

		searchTestReports:function(){
			$("#tests_search").on("keyup", function(){
				var val=$('#tests_search').val();	
				$.ajax({
					type:"GET",
				  	url: ajaxurl,
				  	data: {
					    action: "search_tests_report",
					    value: encodeURIComponent(val)
					}, 
					success: function(result) {	
						if(result==null) return					
						var tests=$.parseJSON(result);
						if(tests==null) return	
						$('#test_suggestions').html('');
						$('#test_suggestions').fadeIn();		
						$.each(tests, function(k, v) {
							$('#test_suggestions').append("<li><a href=\"javascript:addTest("+v.ID+", '"+v.post_title+"');\">"+v.post_title+"</a></li>");
						});						
					}
				});
			});			
		},

		searchUserReports:function(){
			$("#users_search").on("keyup", function(){
				var val=$('#users_search').val();	
				$.ajax({
					type:"GET",
				  	url: ajaxurl,
				  	data: {
					    action: "search_users_report",
					    value: encodeURIComponent(val)
					}, 
					success: function(result) {
						if(result==null) return	
						var users=$.parseJSON(result);
						if(users==null) return	
						$('#user_suggestions').html('');
						$('#user_suggestions').fadeIn();		
						$.each(users, function(k, v) {
							$('#user_suggestions').append("<li><a href=\"javascript:addUser("+v.ID+", '"+v.user_login+"');\">"+v.user_login+"</a></li>");
						});						
					}
				});
			});
		},

		getReports:function(){
			$("#generate_reports").on("click", function(){
				var indentificators=[];
				var data='';
				var report_type=$("#current_report").val();

				/*apply filters*/
				if($("#attempts").prop('checked'))	var attempts="true"; else var attempts="false";
				if($("#hits").prop('checked')) var hits="true"; else var hits="false";
				var day_from = $("#day_from").val(); 
				var day_to = $("#day_to").val(); 
				if(day_from=='') day_from="0001-01-01";
				if(day_to=='') day_to="9999-01-01";


				/*change report type*/
				switch(report_type){
					case 'group_report':
						data=$("#selected_groups tbody").find('tr');
					break; 
					case 'user_report':
						data=$("#selected_users tbody").find('tr');
					break; 
					case 'test_report':
						data=$("#selected_tests tbody").find('tr');
					break;
					default:
						return;
						break;
				}	

				/*create array id*/
				if(data.length>0){
					$.each(data, function( index, value ) {
					  indentificators.push(data.eq(index).data('id'));
					});
				}

				/*send ajax query*/				
					$.ajax({
						type:"POST",
					  	url: ajaxurl,
					  	data: {
						    action: "get_reports_"+report_type,						    
						    data_value: indentificators,
						    attempts: attempts,
						    hits: hits,
						    day_from: day_from,
						    day_to: day_to
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
						  					// $("#downloadCsv").attr('href', v.filename+'.csv');
											$("#results_table").append(row);
									});
								});
					  			// $("#downloadCsv").show();
							}
						}
					});
				
				
			});
		},
		getUserReports:function(){
			$("#generate_user_reports").on("click", function(){
				var indentificators=[$(this).data('user')];

				/*apply filters*/
				if($("#attempts").prop('checked'))	var attempts="true"; else var attempts="false";
				if($("#hits").prop('checked')) var hits="true"; else var hits="false";
				var day_from = $("#day_from").val(); 
				var day_to = $("#day_to").val(); 
				if(day_from=='') day_from="0001-01-01";
				if(day_to=='') day_to="9999-01-01";				

				/*send ajax query*/				
				$.ajax({
					type:"POST",
				  	url: ajaxurl,
				  	data: {
					    action: "get_reports_user_report",						    
					    data_value: indentificators,
					    attempts: attempts,
					    hits: hits,
					    day_from: day_from,
					    day_to: day_to
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
					  					// $("#downloadCsv").attr('href', v.filename+'.csv');
										$("#results_table").append(row);
								});
							});
				  			// $("#downloadCsv").show();
						}
					}
				});
			});
		},
		/*init methods*/
		init:function(){
			_this.changeReportType();
			_this.searchGroupReports();
			_this.searchTestReports();
			_this.searchUserReports();
			_this.getReports();	
			_this.getUserReports();		
		}
	}


	/*init script*/
	_this.init();
	
	

	});	
})(jQuery);

