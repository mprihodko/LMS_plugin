(function($) {
	$(document).ready(function(){
    	var _this = {

    		/* toggle steps*/
			toggleStep:function(){
				$(".step_numb i.hide").live("click", function(e){
					e.preventDefault();
					var step=$(this).data('step');
					$(".step-"+step).hide("slow");
					$(this).removeClass("hide").addClass('show');
				});
				$(".step_numb i.show").live("click", function(e){
					e.preventDefault();
					var step=$(this).data('step');
					$(".step-"+step).show("slow");
					$(this).removeClass("show").addClass('hide');
				});
                $(".questContent i.hide").live("click", function(e){
                    $(this).parent().next().hide("slow");
                    $(this).removeClass("hide").addClass('show');
                    e.preventDefault();
    		    });
                $(".questContent i.show").live("click", function(e){
                    $(this).parent().next().show("slow");
                    $(this).removeClass("show").addClass('hide');
                    e.preventDefault();
                });
                 $(".video_part_title i.hide").live("click", function(e){
                    $(this).parent().next().hide("slow");
                    $(this).removeClass("hide").addClass('show');
                    e.preventDefault();
                });
                $(".video_part_title i.show").live("click", function(e){
                    $(this).parent().next().show("slow");
                    $(this).removeClass("show").addClass('hide');
                    e.preventDefault();
                });
            },

    		/* add step */
    		addStep:function(){
    			$(".addStep").live("click", function(e){
    				e.preventDefault();
    				var step = parseInt($(this).data('step'));
    				var next_step=step+1;    				
    				var tpl = $("#interaction_step_tpl").html();
    				var output = tpl.replace(/{step}/g, next_step);	    				
	    			$(".course_steps").append(output);
	    			$(".editor_wrap_step_"+next_step).wp_editor();
	    			$(this).parent().remove();	    			
    			});
    		},

    		/* add step video*/
    		addVideoStep:function(){
    			$(".AddStepVideo").live("click", function(e){
    				e.preventDefault();
    				var step = parseInt($(this).data('step'));
    				var video = parseInt($(this).data('video'));
    				var new_video =video+1;
    				var tpl = $("#interaction_video_step").html();
    				var output = tpl
    					.replace(/{video}/g, new_video)	 
    					.replace(/{step}/g, step);	 
    				$(".video_data_wrapper_"+step).append(output);
    				$(".video_descr_"+step+"_"+new_video).wp_editor();
    				$(this).parent().remove();	    
    			});
    		},

            /* addQuest */
            addQuest:function(){
                var tpl='';
                $(".add_q").live("click",function(e){
                    e.preventDefault();
                    if($(this).hasClass("AddQuest")){
                        tpl=$("#true_false_quest").html();
                    }else if($(this).hasClass("AddMultiple")){
                        tpl=$("#multiple_quest").html();
                    }else if($(this).hasClass("AddImageQuest")){
                        tpl=$("#image_quest").html();
                    }
                    var step=parseInt($(this).data("step"));
                    var questnum=parseInt($(this).data("questnum"));
                    var new_quest=questnum+1;
                    var output = tpl
                        .replace(/{step}/g, step)
                        .replace(/{q_num}/g, new_quest)
                        .replace(/{a_num}/g, 1);
                    $(".questContent-"+step).append(output);
                    $(this).parent().find("a").data('questnum', new_quest).attr("data-questnum", new_quest);
                    $("#step-"+step+"-quest-"+new_quest).wp_editor();
                });
            },
            /*  delete video part*/
            deleteVideo:function(){
                $(".video_part_title i.delete").live("click", function(e){
                    e.preventDefault(); 
                    $(this).parent().next().remove();  
                    $(this).parent().remove();                  
                });
            },

            /* delete Quest in step*/
            deleteQuest:function(){
                $(".questContent i.delete").live("click", function(e){
                    e.preventDefault(); 
                    $(this).parent().parent().remove();
                });
            },

            /*add Answers*/
            addAnswers:function(){
                $(".addAnswer").live("click", function(e){
                    e.preventDefault(); 
                    var step =  parseInt($(this).data("step"));
                    var quest =  parseInt($(this).data("questnum"));
                    var answer =  parseInt($(this).data("answer"));
                    var next_answer = answer+1;
                    var tpl = $("#multiple_answer").html();
                    var output = tpl 
                        .replace(/{step}/g, step)
                        .replace(/{q_num}/g, quest)
                        .replace(/{a_num}/g, next_answer);
                    $(".step-"+step+"-quest-"+quest+"-answers").append(output);
                    $(this).data("answer", next_answer).attr("data-answer", next_answer);
                });
                $(".addAnswerImg").live("click", function(e){
                    var step =  parseInt($(this).data("step"));
                    var quest =  parseInt($(this).data("questnum"));
                    var answer =  parseInt($(this).data("answer"));
                    var next_answer = answer+1;
                    var tpl= $("#image_quest_answer").html();
                    var output = tpl 
                        .replace(/{step}/g, step)
                        .replace(/{q_num}/g, quest)
                        .replace(/{a_num}/g, next_answer);
                    $(".step-"+step+"-quest-"+quest+"-answers").append(output);
                    $(this).data("answer", next_answer).attr("data-answer", next_answer);
                });
            },
            /* delete Answers*/
            delAnswer:function(){
                $(".delAnswer").live("click", function(e){
                    e.preventDefault();
                    $(this).parent().remove(); 
                });
            },

            /* select true */
            selectTrue:function(){
                $(".select_true").live("click", function(){ 
                    $(this).parent().parent().find(".answer_hidden").attr("value", "false");
                    $(this).parent().find(".answer_hidden").attr("value", "true");                  
                    $(this).parent().parent().find(".select_true").removeAttr("checked");
                    $(this).attr("checked", true);
                })
            },

            /* init steps */
            initSteps:function(){
                $(".enable_disable .butt").live("click", function(){
                    $(".enable_disable .butt").removeClass("active");
                    $(this).addClass("active");
                    if($(this).hasClass("on")){
                        $(".init_interaction input").attr("checked", true);
                        $(".step_title").find('input').attr('required', true);
                    }else{
                        $(".init_interaction input").removeAttr("checked");
                        $(".step_title").find('input').removeAttr("required");
                    }
                });                
            },

            deleteStep: function(){
                $(".step_numb .delete").live("click", function(){
                    $(this).parent().parent().remove();
                });
            },

            /*init methods*/
    		init:function(){    			
    			_this.toggleStep(); 
    			_this.addStep();  
    			_this.addVideoStep();
                _this.addQuest(); 
                _this.deleteQuest();
                _this.deleteVideo();
                _this.addAnswers();	
                _this.delAnswer();
                _this.selectTrue();
                _this.initSteps(); 
                _this.deleteStep();   		  
            }
    	
    	}

    	/*call init*/
		_this.init();
	});
})(jQuery);
