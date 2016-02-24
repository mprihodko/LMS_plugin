function removeGroup(id){
	jQuery.ajax({
		type:"GET",
	  	url: '/wp-admin/admin-ajax.php',
	  	data: {
	  		action: "remove_group", 
		    value: encodeURIComponent(id)
		},
		success: function(result) { 
			jQuery("#group-"+id).addClass('removed');
      jQuery("#delete-"+id).attr("href", "javascript: deleteGroup("+id+")");			
			jQuery("#restore-"+id).attr("style", "display: inline");
		}
	});
}

function deleteGroup(id){
	jQuery.ajax({
		type:"GET",
	  	url: '/wp-admin/admin-ajax.php',
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
	  	url: '/wp-admin/admin-ajax.php',
	  	data: {
	  		action: "restore_group",
		    value: encodeURIComponent(id)
		},
		success: function(result) { 
			jQuery("#group-"+id).removeClass('removed');
			jQuery("#delete-"+id).attr('href', 'javascript: removeGroup("'+id+'")');
			jQuery("#restore-"+id).attr("style", "display: none");
		}
	});
}

function videoEnded(){
	
		var countVideo=jQuery("#video_count").val();
		var test=jQuery("#post_id").val();
		var user=jQuery("#user_id").val();
		jQuery.cookie('video_end', "false");		
      	jQuery(document).ready(function($){        
            $("#the_video_"+countVideo+"_html5_api").on('ended',function(){  
            	if(jQuery.cookie('video_end')=="false"){             	
            	   jQuery.cookie('video_end', "true");            	   
            	}
            	if(jQuery.cookie('video_end')=="true"){ 	 
	            	jQuery.ajax({
						type:"GET",
					  	url: '/wp-admin/admin-ajax.php',
					  	data: {
					  		action: "hits_add",
						    test_id: test,
						    user_id: user
						},
						success: function(result) {
              var success=jQuery.parseJSON(result);
              if(success.success=='success'){
                jQuery.cookie('video_end', "stop");
                jQuery.cookie('test_access', "true");
                window.location=jQuery("#redirect_link").val(); 
              }else{                
                console.log('success ' , success);
              }											
						}
					});
				}
            });
            $("#the_video_"+countVideo).on('ended',function(){
             	if(jQuery.cookie('video_end')=="false"){ 
            	   jQuery.cookie('video_end', "true");            	   
            	}
            	if(jQuery.cookie('video_end')=="true"){ 	 
	            	jQuery.ajax({
						type:"GET",
					  	url: '/wp-admin/admin-ajax.php',
					  	data: {
					  		action: "hits_add",
						    test_id: test,
						    user_id: user
						},
						success: function(result) {							
							var success=jQuery.parseJSON(result);
              if(success.success=='success'){
                jQuery.cookie('video_end', "stop");
                jQuery.cookie('test_access', "true");
                window.location=jQuery("#redirect_link").val(); 
              }else{                
                console.log('success ' , success);
              }                     
						}
					});
				}                
            });        
      	}); 
    // setInterval(function(){
    // },1000);
}
videoEnded();

(function($) {
  $(document).ready(function(){
    var _tabs = {      
      
      /*
      *CONSTRUCT
      */
      _construct:function(){
        ajaxurl="/wp-admin/admin-ajax.php";
      },
      tab_content:function(){
        $("#interaction_tabs .parent .parent_link").click(function(e){
          e.preventDefault();          
          $('.testing_tab').hide();   
          var id=$(this).attr('href');
          $(id).show();
          $(".video-follow").hide();
          $(id+' .video-follow-1').show();              
          $(this).parent().prev("li").find('ul').addClass("partsHidden");
          $("#interaction_tabs li ul").addClass("partsHidden");
          $(this).parent().find('ul').removeClass("partsHidden");
          $(this).parent().find("ul").removeClass('not-active');
          $(this).parent().find("ul li:nth-child(1)").removeClass('not-active');
          $(this).parent().find("ul li:nth-child(1) a").removeClass('not-active');
          $(".vjs-playing").click();
        });
      },
      next_step:function(){
        $(".processed_next").click(function(e){
          e.preventDefault();
          $('.testing_tab').hide();   
          var id=$(this).attr('href');
          var step=$(this).data('step');
          $(id).show();
          $(".video-follow").hide();
          $(id+' .video-follow-1').show();
          $("#interaction_"+step+" .quest-part").show(); 
          $("#interaction_tabs .parent ul").addClass("partsHidden");
          $("#interaction_tabs .step-"+step+" ul").removeClass("partsHidden");
        });
      },
      /*
      *Child Tabs
      */
      child_tabs:function(){
        $("#interaction_tabs .parent ul li a").click(function(e){
           e.preventDefault();
           var id=$(this).attr('href');
           var step=$(this).data('step');
           $(".tab_lesson").hide();
           $("#step_"+step).show();           
           $(".video-follow").hide();
           $('.'+id).show(); 
           $(".vjs-playing").click();
        });        
      },

      /*
      *SEND ANSWERS AJAX
      */
      sendAnswers:function(){
        $(".submit_step").click(function(e){
          e.preventDefault();
          var step = $(this).data("step");
          var obj=$("#step_check_"+step+" .answers li input:checked").serializeArray();         
          var answers=[];                   
          $.ajax({
            type:"POST",
            url: ajaxurl,
            data: {
              action: "lms_steps_result",
              ans: obj,
              test_id: $("#course_id").val(),
              step: step
            },
            success:function(json){            
            var responce=JSON.parse(json);
              if(responce.count==0){
                var next=step+1;               
                $(".step-"+next).removeClass('not-active');
                $('.step-'+next+' .parent_link').removeClass('not-active');
                $('#interaction_tabs [href="#interaction_'+step+'"]').removeClass('not-active');               
                $('#interaction_tabs [href="#interaction_'+step+'"]').parent().removeClass('not-active');
                $('.step-'+step).next().find('.parent_link').removeClass('no-check');
                $('.step-'+step).next().find('.parent_link').addClass('check');
                $('.step-'+next+' .parent_link').removeClass('no-check');
                $('.step-'+next+' .parent_link').addClass('check');
                $('.step-'+next+' ul').removeClass('not-active');
                $(".step-"+next+" ul li:nth-child(1)").removeClass('not-active');
                $(".step-"+next+" ul li:nth-child(1) a").removeClass('not-active');
                $(".step-"+next+" ul li:nth-child(1) a").removeClass('no-check');
                $(".step-"+next+" ul li:nth-child(1) a").addClass('check');   
                $(".step-"+step+" ul li.interaction_link a").removeClass('no-check');
                $(".step-"+step+" ul li.interaction_link a").addClass('check');                
                $("#interaction_"+step+" .test_passed").hide();               
                $("#interaction_"+step+" .test_passed_"+step).show();
                $(".bar-step-"+step+"i").css("background", "rgba(100, 255, 100, 0.5)");
                $("#step_check_"+step).hide();
                $("#interaction_"+step+" .test_fail").hide();                
                $(".passed_message").show(); 
                $('.quest-part').hide();               
              }else{
                $('.quest-part').hide();
                var output=''+responce.count+' '+((responce.count>1)? 'errors!' : 'error!')+'';
                var retake=responce.error;
                var review=responce.review;
                $.each( responce.error, function( key, value ) { 
                  // $(".quest-"+value).show();
                });
                $('#interaction_tabs [href="#interaction_'+step+'"]').addClass('not-active');               
                $('#interaction_tabs [href="#interaction_'+step+'"]').parent().addClass('not-active');
                $('#interaction_tabs .step-'+step+' a').addClass('try_again');
                $("#interaction_"+step+" .error_count_"+step).html(output); 
                $("#interaction_"+step+" .retake_questions_"+step).attr("href", retake); 
                $("#interaction_"+step+" .review_train_"+step).attr("href", review);              
                $("#interaction_"+step+" .test_fail").show();
                $(".submit_step").hide(); 
              }              
              $('#step_check_'+step)[0].reset();
              $(".step_result_"+step).show();       
            }               
          });
          
          });
         
         
       
      },
      /*
      * IMAGE ANSWER CHANGE
      */
      imgAnswer:function(){
        $(".img-answer-cover").click(function(e){
         e.preventDefault();
         $(this).parent().parent().find('.answer-check').prop("checked", false);         
         $(this).next().find('.answer-check').prop("checked", true);
        });
             
      },
      /*
      * Retake quest quick
      */
      retake_quick:function(){
        $(".retake_questions").live("click", function(e){
          e.preventDefault();         
          var nums_q=$(this).attr("href").split(',');
          $.each(nums_q, function(key, value){
            $('.quest-'+value).show();
          });
          $(".submit_step").show();
          $(".test_fail").hide();
        });
      },
      /*
      * Review_quick quest quick
      */
      review_quick:function(){
        $(".review_train").live("click", function(e){
          e.preventDefault();         
          var nums_q=$(this).attr("href").split(',');
          var step=$(this).data("step");
          $(".testing_tab").hide();
          $("#step_"+step).show()
          $("#step_menu_"+step+" ul").removeClass("partsHidden");
          // $.each(nums_q, function(key, value){
            $('#step_'+step+' .v_part-'+nums_q[0]).show();
          // });
          $(".submit_step").show();
          $('#interaction_'+step+' .quest-part').show();
          $(".test_fail").hide();
        });
      },
      /*
      * TRY AGAIN
      */
      try_again:function(){
        $(".try_again").live("click", function(e){
          e.preventDefault();
          if($(this).parent().hasClass('parent')){
            $(this).removeClass('try_again');
            var step = $(this).data('step');            
            var form = $("#step_check_"+step);
          }else{
            var form = $(this).attr('href');
            var step = $(this).data('step');
          }

          $(".step_result_"+step).hide();
          $('#interaction_'+step).hide();
          $("#step_"+step).show();
          $('#interaction_'+step+' .quest-part').show();
          $(".step-"+step+' ul').removeClass('partsHidden');
          $(".step-"+step+' ul li').addClass('not-active');
          $(".step-"+step+' ul li a').addClass('not-active');
          $(".step-"+step+' ul li a').removeClass('check');
          $(".step-"+step+' ul li a').addClass('no-check');
          $(".step-"+step+' ul li:nth-child(1)').removeClass('not-active');
          $(".step-"+step+' ul li:nth-child(1) a').removeClass('not-active');
          $(".step-"+step+' ul li:nth-child(1) a').removeClass('no-check');
          $(".step-"+step+' ul li:nth-child(1) a').addClass('check');
          $("#step_"+step+" .video-follow-1").show();
          $(form).show();
          $(".submit_step").show();
        });
        $(".show-form").live("click", function(e){
          e.preventDefault();
          $(".passed_message").hide();
          $(".test_passed").hide();
          var form = $(this).data('step');
          $("#interaction_"+form+" .quest-part").show();
          $("#step_check_"+form)[0].reset();
          $("#step_check_"+form).show();

        });
      },
      review_interaction:function(){
        $(".review_test").live("click", function(e){
          e.preventDefault();
          var id = $(this).data('test');
          var user = $(this).data('user');
          $.ajax({
            type:"POST",
            url: ajaxurl,
            data: {
              action: "lms_steps_result",  
              test_id: id,
              user_id: user
            },
            success:function(html){
              location.reload();
            }
          });
        }); 
      },
      hamburger:function(){
        $(document).on("click", "#show_nav_ineractive", function(){
          if(!$("#interaction_tabs").hasClass("side-fade")){
            $("#interaction_tabs").show();
            $("#interaction_tabs").addClass("side-fade");
            $(this).addClass("bordered");
          }else{
            $("#interaction_tabs").hide();
            $("#interaction_tabs").removeClass("side-fade");
            $(this).removeClass("bordered");
          }
        });
      },
      /*
      *INIT Method
      */
      init: function(){        
        _tabs._construct();
        _tabs.next_step();
        _tabs.tab_content(); 
        _tabs.child_tabs();
        _tabs.sendAnswers();
        _tabs.try_again();
        _tabs.imgAnswer();
        _tabs.review_interaction(); 
        _tabs.retake_quick();
        _tabs.review_quick();
        _tabs.hamburger();
      },
    }

   /*
   *INIT OBJ
   */
    
      _tabs.init(); 
      
       
   


  });
})(jQuery);

