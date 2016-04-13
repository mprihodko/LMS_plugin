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

(function($) {
    $(document).ready(function(){
      var _cart = {

        ajaxUrl: '/wp-admin/admin-ajax.php',

        addToCart:function(){
          $('.addToCart').on("click", function(e){
            e.preventDefault();            
            var product_id = $(this).data('product_id');
            var views = $("#lms_test_views-"+product_id).val();            
              $.ajax({
                type:"POST",
                url: _cart.ajaxUrl,
                data:  {
                  action: "add_to_cart",
                  product_id: product_id,
                  views: views,                  
                },
                success:function(json){                
                  _cart.getCartItems();
                  $("#succeses_add_to_cart").show();
                  $("#succeses_add_to_cart" ).dialog({
                    autoOpen: false,
                    modal: true,  
                    dialogClass: "succeses_add_to_cart fixed", 
                    title: 'Course succses added to Cart',  
                    position: { my: "center"},
                    width: 400,
                    close: function( event, ui ) {},        
                    show: {
                      effect: "blind",
                        duration: 1000
                    },
                    hide: {
                        effect: "explode",
                        duration: 1000
                    }
                  });
                  $("#succeses_add_to_cart").dialog( "open" );                  
                }
              });
            
          });
        },        

        getCartItems:function(){
          if(window.location.pathname=='/checkout/') return false;   
          if(window.location.pathname=='/cart/') return false;      
          if($("#cart-icon").hasClass("active")){
              var active = "active";
          }else{
              var active = null;
          }          
          $.ajax({
              type:"POST",
              url: _cart.ajaxUrl,
              data:  {
                action: "get_cart_items",
                active: active               
              },
              success:function(html){               
                if($("body").find("#_lms_cartWrapper").length>0)            
                  $("#_lms_cartWrapper").replaceWith(html);               
                else
                  $("body").append(html);
              }
            });
        },

        deleteCartItem:function(){
          $(document).on("click", '.delete_item', function(){
            var product_id = $(this).data('product_id');            
            $(this).parent().remove();
            $.ajax({
              type:"POST",
              url: _cart.ajaxUrl,
              data:  {
                action: "delete_cart_item",
                product_id: product_id
              },
              success:function(json){
                var response = $.parseJSON(json)
                if(response.items==0){
                  $('.cart-totals').before("<h2>Your Cart is empty</h2>");
                  $("#checkout").remove();
                }
                $(".cart-totals .product_price").html("$ "+response.success)

              }
            });
          });
        },

        showCart:function(){
          $("#cart-icon").live("click", function(){  
              $(this).toggleClass("active");        
              $(".lms_cart").toggleClass("active");
          })
        },

        init:function(){
          _cart.addToCart();
          _cart.getCartItems();
          _cart.showCart();         
          _cart.deleteCartItem();                
        },




      }
      _cart.init();
  })
})(jQuery);

(function($) {


  var _checkout={
    
    ajaxUrl: '/wp-admin/admin-ajax.php',
    

    checkoutTabs:function(){
      $(".checkout_tabs h1 i").on("click", function(){
        if($(this).parent().hasClass("active")){
          $(this).removeClass("fa-caret-square-o-up").addClass("fa-caret-square-o-down");
          $(this).parent().removeClass("active");
          $(this).parent().next().slideToggle('slow');
        }else{
           $(this).removeClass("fa-caret-square-o-down").addClass("fa-caret-square-o-up");
           $(this).parent().addClass("active");           
           $(this).parent().next().slideToggle('slow');
        }
      })
    },

    updatePrice:function(){
      $(".checkout_views").on("change", function(){
        var product_id = $(this).data("product_id");
        var views = $(this).val();
        $.ajax({
          type:"POST",
          url: _checkout.ajaxUrl,
          data:  {
            action: "update_cart",
            product_id: product_id,
            views: views
          },
          success:function(json){
            var response = $.parseJSON(json);           
            $("#price-"+product_id).html("$ "+response.price)
            $("#lms_total").html("$ "+response.total)
          }
        });
      });
      $(".checkout_views").on("click", function(){
        var product_id = $(this).data("product_id");
        var views = $(this).val();
        $.ajax({
          type:"POST",
          url: _checkout.ajaxUrl,
          data:  {
            action: "update_cart",
            product_id: product_id,
            views: views
          },
          success:function(json){
            var response = $.parseJSON(json);           
            $("#price-"+product_id).html("$ "+response.price)
            $("#lms_total").html("$ "+response.total)
          }
        });
      });
      $(".checkout_views").on("keyup", function(){
        var product_id = $(this).data("product_id");
        var views = $(this).val();
        $.ajax({
          type:"POST",
          url: _checkout.ajaxUrl,
          data:  {
            action: "update_cart",
            product_id: product_id,
            views: views
          },
          success:function(json){
            var response = $.parseJSON(json);           
            $("#price-"+product_id).html("$ "+response.price)
            $("#lms_total").html("$ "+response.total)
          }
        });
      });
    },

    checkoutAjaxRegistrantion:function(){
      $(".checkout_wrapper form#registerUser").on("submit", function(e){
        e.preventDefault();
        $.each($(".checkout_wrapper form#registerUser input"), function(){
          $(this).removeClass('error');
        })
        $.ajax({
            type: 'POST',            
            url: _checkout.ajaxUrl,
            data: { 
                'action': 'ajax_register', //calls wp_ajax_nopriv_ajaxlogin
                'reg_name': $('form#registerUser #reg-name').val(), 
                'reg_email': $('form#registerUser #reg-email').val(),
                'reg_password': $('form#registerUser #reg-pass').val(),
                'confirm_reg_password': $('form#registerUser #confirm-reg-pass').val(),
                'reg_fname': $('form#registerUser #reg-fname').val(), 
                'reg_lname': $('form#registerUser #reg-lname').val(),
                'group_selected' : $('form#registerUser #group_selected').val(),
                'captcha': $('form#registerUser #captcha').val()
                },
            success: function(data){           
              response = $.parseJSON(data);
              if(response.success=="success"){
                $.ajax({
                    type: 'POST',            
                    url: _checkout.ajaxUrl,
                    data: { 
                        'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                        'username': $('form#registerUser #reg-name').val(), 
                        'password': $('form#registerUser #reg-pass').val(),
                        'remember': true
                         },
                    success: function(data){           
                      response = $.parseJSON(data);
                      if(response.loggedin==true){
                        // _checkout.getUserDetail(); 
                        window.location.reload();               
                      }else{
                        $("#login_message").html(response.message);
                      }              
                    }
                });                
              }else{
                $("#register_message").html('');
                $.each(response, function(k, v){
                  $("#register_message").append(v);
                  $("#"+k).addClass("error");
                })
              }              
            }
        });
      });
    },

    checkoutAjaxLogin:function(){
      $('.checkout_wrapper form#loginform').on('submit', function(e){ 
        e.preventDefault();
        $.ajax({
            type: 'POST',            
            url: _checkout.ajaxUrl,
            data: { 
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#loginform #user_login').val(), 
                'password': $('form#loginform #user_pass').val(),
                'remember': $('form#loginform #rememberme').prop('checked') 
                 },
            success: function(data){           
              response = $.parseJSON(data);
              if(response.loggedin==true){
                _checkout.getUserDetail();                
              }else{
                $("#login_message").html(response.message);
              }              
            }
        });        
      });
    },

    getUserDetail:function(){
        $.ajax({
            type: 'POST',           
            url: _checkout.ajaxUrl,
            data: { 
                'action': 'get_user_checkout_detail'                
                },
            success: function(html){ 
              $(".checkout_tab_content.user").replaceWith(html);
            }
        });
    },

    userValidInfo:function(){
      var error=[];
      $.each($(".user_billing_info input"), function(){                    
        if($(this).val().length==0 ){
          error.push($(this).attr('id'));
        }else{
          $(this).removeClass("error");
        }                 
      });
      return error;        
    },

    nextStep:function(){     
      $(".next_btn").on("click", function(){      
        button = $(this)
        $("#alreadyExist").remove();
        $.ajax({
        type: 'POST',           
        url: _checkout.ajaxUrl,
        data: { 
            'action': 'if_group_exists',                
            'ajax_text_id': $(".group_selected").serialize()
            },
        success: function(json){ 
          var response = $.parseJSON(json);
          if(response.exist=="fail"){
            $.each(response.groups, function(i, v){
              $("#group_selected-"+v).attr('value', '');  
              $("#group_selected-"+v).after("<span class='error_message' id='alreadyExist'>Group is already exist</span>");    
            });
          }else{
            $("#alreadyExist").remove();            
          }
          var validArray = _checkout.userValidInfo();         
          if(validArray.length==0){
            button.parent().slideToggle('slow');
            button.parent().parent().next().find('.checkout_tab_content').slideToggle('slow');
            button.parent().parent().next().find('h1 i').removeClass("fa-caret-square-o-up").addClass("fa-caret-square-o-down");
            button.parent().parent().next().find('h1').removeClass("not-active");
            button.parent().prev().find('i').removeClass("fa-caret-square-o-down").addClass("fa-caret-square-o-up");
          }else{
            $.each(validArray, function(k, v){
              $("#"+v).addClass("error");            
            })
          }      
        }
        });
      });
    },

    prevStep:function(){
      $(".prev_btn").on("click", function(){
        $(this).parent().slideToggle('slow');
        $(this).parent().parent().prev().find('.checkout_tab_content').slideToggle('slow');
        $(this).parent().parent().prev().find('h1 i').removeClass("fa-caret-square-o-up").addClass("fa-caret-square-o-down");
        $(this).parent().parent().prev().find('h1').removeClass("not-active");
        $(this).parent().prev().find('i').removeClass("fa-caret-square-o-down").addClass("fa-caret-square-o-up");
      });
    },

    payTest:function(){
      $('.pay_btn').on("click", function(){
        var products        = [];
        var views           = [];
        var user_id         =$("#order_user_id").val()
        var user_login      =$("#order_user_login").val()
        var user_email      =$("#order_user_email").val()
        var user_fname      =$("#order_user_fname").val()
        var user_lname      =$("#order_user_lname").val()
        var group_template  =$(".group_template").serialize();
        var group_custom    =$(".group_custom").serialize();
        var type_pay        =$("#payment_sys").val()
        var payment         =$("#"+type_pay+" input").serialize();
       
        
        $.each($(".products_input"), function(){
            products.push($(this).val())            
        })
        $.each($(".checkout_views"), function(){
            views.push($(this).val())            
        })
        $("body").waiting({ fixed: true });
        $.ajax({
          type: 'POST',           
          url: _checkout.ajaxUrl,
          data: { 
            'action': 'save_order',                
            'order_user_id': user_id,
            'order_user_login': user_login,
            'order_user_email': user_email,
            'order_user_fname': user_fname,
            'order_user_lname': user_lname,
            'group_id': group_template,
            'group_custom': group_custom,
            'product_id': products,           
            'product_views': views,
            'order_status': "pending",
            'payment': payment
          },
          success: function(json){
            $(".waiting-container.overlay.fixed").remove();
            $("body").removeClass("waiting");
            result=$.parseJSON(json);
            if(result.status==1){
              if($("body").find("#thank_you").length>0){
                $("#thank_you").html(result.success);
              }else{
                $("body").append("<div id='thank_you'></div>");
                $("#thank_you").html(result.success);
              }
              $("#thank_you" ).dialog({
                  autoOpen: false,
                  modal: true, 
                  dialogClass: "succeses_add_order fixed",              
                  show: {
                    effect: "blind",
                      duration: 1000
                  },
                  hide: {
                      effect: "explode",
                      duration: 1000
                  }
              });
              $("#thank_you").dialog( "open" );
            }else{
              if($("body").find("#thank_you").length>0){
                $("#thank_you").html(result.error);               
              }else{
                $("body").append("<div id='thank_you'></div>");
                $("#thank_you").html(result.error);
              }
            }
            $(".succeses_add_order .ui-dialog-titlebar-close").live("click", function(){
              window.location=location.protocol + "//" + location.host
            })
          } 
        });
      });
    },

    init:function(){
      _checkout.checkoutTabs();
      _checkout.updatePrice();
      _checkout.checkoutAjaxLogin();
      _checkout.checkoutAjaxRegistrantion();
      _checkout.nextStep();
      _checkout.prevStep();
      _checkout.payTest();
    }

  }

  _checkout.init()

})(jQuery);
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
                    $(this).parent().parent().find(".select_true").removeAttr("required");
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

(function($) {
    $(document).ready(function(){
        var _test = {
       
            createPostMatrix:function () {
                alert(JSON.stringify($('.question-space').serialize()));
            },

            /* QUESTIONS */
            add_rem_quests: function(){
                $(function() {
                    $('.add-question').live("click", function() {
                        var counter = parseInt($('.question:last-child').attr('rel-id')) + 1;
                        var clone = $('#question-template').clone().attr('id', '').css('display', 'block').appendTo('.question-space');
                        clone.attr('rel-id', counter);
                        clone.find('input.question-answer-radio').attr('name', 'answers[' + counter + ']').val('0');
                        clone.find('input.question-answer').attr('name', 'questions[' + counter + '][0]');
                        clone.find('input.question-title').attr('name', 'titles[' + counter + ']');
                        return false;
                    });
                    $('.remove-question').live("click", function() {
                        $(this).parent().remove();
                        return false;
                    });
                    $('.repeatable-add').live("click", function() {
                        var lastNumb = $(this).parent().find('ul li:last-child').attr('rel-id');
                        counter = Number(lastNumb) + 1;
                        var clone = $(this).parent().find('ul li.original').clone().removeClass('original').appendTo($(this).parent().find('ul'));
                        clone.attr('rel-id', counter);
                        clone.find('input.question-answer-radio').attr('name', 'answers[' + $(this).parent().attr('rel-id') + ']').val(counter);
                        clone.find('input.question-answer').val('').attr('name', 'questions[' + $(this).parent().attr('rel-id') + '][' + counter + ']');

                        return false;
                    });
                    $('.repeatable-remove').live("click", function() {
                        $(this).parent().remove();
                        return false;
                    });
                });
            },

            /* ADD VIDEO */
            videoAdd:function(){                
                $(".add-attach-video").live("click", function(e) {
                    e.preventDefault();
                    var counter = parseInt($(".add-attach-video").data('num'));
                    var nextNum = counter + 1;
                    $(this).remove(); 
                    var tpl=$("#attach_video_add_tpl").html();
                    var output = tpl
                        .replace(/{numb}/g, counter)
                        .replace(/{next}/g, nextNum);
                    $(".video_inputs").append(output); 
                });
            },

            /*Change Video Type*/
            videoTypeChange:function(){
                $(".select_type_video div").live("click", function(e) {
                    e.preventDefault();
                    var type=$(this).data("type");
                    if(type=="url"){            
                        $(this).parent().next().find(".lms_attach_video").attr("type", "url"); 
                        $(this).addClass("active");        
                        $(this).parent().find("div.file").removeClass("active");
                    }else{            
                        $(this).parent().next().find(".lms_attach_video").attr("type", "file"); 
                        $(this).addClass("active");                 
                        $(this).parent().find("div.url").removeClass("active");
                    }
                 });
            },

            /* ADD MEDIA */
            mediaFieldAdd:function(){
                $(".add-attach-media").live("click", function(e) {
                    e.preventDefault();
                    var counter = parseInt($(".add-attach-media").data('num'));
                    var nextNum = counter + 1;
                    $(this).remove();
                    var tpl=$("#attach_media_add_tpl").html();
                    var output = tpl
                        .replace(/{numb}/g, counter)
                        .replace(/{next}/g, nextNum);
                    $("#media_section").append(output);
                });
            },

            /* Certificate */
            certificateField:function(){
                $('.upload_image_button').click(function(){
                    var send_attachment_bkp = wp.media.editor.send.attachment;
                    var button = $(this);
                    wp.media.editor.send.attachment = function(props, attachment) {
                        $(button).parent().prev().attr('src', attachment.url);
                        $(button).prev().val(attachment.id);
                        wp.media.editor.send.attachment = send_attachment_bkp;
                        $("#img_thumb").show();
                    }
                    wp.media.editor.open(button);
                    return false;    
                }); 
                $('.remove_image_button').click(function(){
                    var r = confirm("Delete this image?");
                    if (r == true) {
                        var src = $(this).parent().prev().attr('data-src');
                        $(this).parent().prev().attr('src', src);
                        $(this).prev().prev().val('');
                        $("#img_thumb").hide();
                    }
                    return false;
                });
             },

            /* THUMBNAIL */
            thumbnail:function(){
                $('.upload_thumbnail_button').click(function(){
                    var send_attachment_bkp = wp.media.editor.send.attachment;
                    var button = $(this);
                    wp.media.editor.send.attachment = function(props, attachment) {
                        $(button).parent().prev().attr('src', attachment.url);
                        $(button).prev().val(attachment.id);
                        wp.media.editor.send.attachment = send_attachment_bkp;
                        $("#image_thumb").show();
                    }
                    wp.media.editor.open(button);
                    return false;    
                });
                
                $('.remove_thumbnail_button').click(function(){
                    var r = confirm("Delete this image?");
                    if (r == true) {
                        var src = $(this).parent().prev().attr('data-src');
                        $(this).parent().prev().attr('src', src);
                        $(this).prev().prev().val('');
                        $("#image_thumb").hide();
                    }
                    return false;
                });
            },

            /*DELETE VIDEO*/
            deleteVideo:function(){
                $(".delete-attach-video").click(function(e) {
                    e.preventDefault();
                    var v_key = $(this).data('del');
                    $("#video-"+v_key).remove();
                    var videos=$("#wp_custom_attachment .attach").find('.video_space');        
                    if(videos.length==0){
                        var tpl=$("#attach_video_new_tpl").html();
                        var output = tpl
                        .replace(/{numb}/g, 0)
                        .replace(/{next}/g, 1);
                        $(".video_inputs .attach hr").before(output);
                        $(".add-attach-video").attr("data-num", "1");
                    }      
                });
            },

            /*DELETE ATTACH MEDIA*/
            deleteMedia:function(){
                $(".delete-attach-media").click(function(e) {
                    e.preventDefault();
                    var a_key = $(this).data('del');      
                    $("#attached-"+a_key).remove();
                    var attach=$("#media_section").find('.lms_attach_media_files_contain');        
                    if(attach.length==0){
                        var tpl=$("#attach_media_new_tpl").html();
                        var output = tpl
                        .replace(/{numb}/g, 0)
                        .replace(/{next}/g, 1);
                        $("#media_section hr").before(output);
                        $(".add-attach-media").attr("data-num", "1");            
                    }
                    
                });
            },
            /*init methods*/
            init:function(){
                // _test.createPostMatrix();
                _test.add_rem_quests();
                _test.videoAdd();
                _test.videoTypeChange();
                _test.mediaFieldAdd();
                _test.certificateField();
                _test.thumbnail();
                _test.deleteVideo();
                _test.deleteMedia();
            }
        }
        /*call init*/
        _test.init();
    });
})(jQuery);

(function($){
    $(document).ready(function(){


        var _order={

            ajaxUrl: '/wp-admin/admin-ajax.php',

            searchUser:function(){
                $("#order_user_login").on("keyup", function(){
                    var value = $(this).val();
                    if(value=='') return;
                    $.ajax({
                        type:"GET",
                        url: _order.ajaxUrl,
                        data: {
                            action: "search_users",
                            value: encodeURIComponent(value),
                            order: true
                        }, 
                        success: function(result) {
                            var users=$.parseJSON(result);
                            $('#user_suggestions').html('');
                            $('#user_suggestions').fadeIn();       
                            $.each(users, function(k, v) {
                                $('#user_suggestions').append("<li><a href=\"javascript:selectUser("+v.ID+", '"+v.user_login+"', '"+v.user_email+"', '"+v.first_name+"', '"+v.last_name+"');\">"+v.user_login+"</a></li>");
                            });
                        }
                    });
                });
            },

            searchProduct:function(){
                $("#productName").on("keyup", function(){
                    var value = $(this).val();
                    if(value=='') return;
                    $.ajax({
                        type:"GET",
                        url: _order.ajaxUrl,
                        data: {
                            action: "search_products",
                            value: encodeURIComponent(value),
                            order: true
                        }, 
                        success: function(result) {
                            var users=$.parseJSON(result);
                            $('#autocomplete').html('');
                            $('#autocomplete').fadeIn();       
                            $.each(users, function(k, v) {
                                $('#autocomplete').append("<li><a href=\"javascript:selectProduct("+v.ID+", '"+v.post_title+"', '"+v.price+"');\">"+v.post_title+"</a></li>");
                            });
                        }
                    });
                });
            },

            addNew:function(){
                $("#add_new_product").on("click", function(){
                    var tpl=$("#addItem").html();
                    var title = $("#productName").val()
                    if(title.length<1) return
                    var id = parseInt($("#productID").val())
                    if(id.length<1) return                    
                    var views = parseInt($("#productViews").val());
                    if(views.length<1) return
                    var price = parseFloat($("#priceContain").html())
                                  
                    var item = $(".order_product_list").find(".product-"+id);
                    if(item.length==0){
                        var subtotal = views*price
                        var output= tpl
                                    .replace(/{views}/g, views)
                                    .replace(/{id}/g, id)
                                    .replace(/{title}/g, title)
                                    .replace(/{price}/g, price.toFixed(2))
                                    .replace(/{subtotal}/g, subtotal.toFixed(2));
                        $("#addProduct").before(output);
                    }else{
                        var old_views = parseInt(item.find(".prod-view").val());
                        var new_views = old_views+views;
                        var subtotal=new_views*price;
                        item.find(".prod-view").val(new_views)
                        item.find(".ordered-views").html(new_views)
                        item.find(".product-subtotal-price-active").html(subtotal.toFixed(2))
                    }  
                    $("#productName").val('') 
                    $("#priceContain").html('0.00')  
                    $("#subtotalContain").html('0.00') 
                    $("#productViews").val("1")
                    $("#productID").val('')         
                });
            },

            updateSubtotal:function(){
                $("#productViews").on("change", function(){
                    var views = $(this).val();
                    var price = $("#priceContain").html()
                    var subtotal = views*price
                    $("#subtotalContain").html(subtotal.toFixed(2));
                })
            },

            removeProduct:function(){
                $(".fa-minus").live('click', function(){                    
                    $(this).parent().parent().remove();
                    var total=0;
                    $.each($(".product-subtotal-price-active"), function(){
                        total=total+parseFloat($(this).html())                        
                    })
                    $("#total_price").html(total.toFixed(2));
                })
            },

            updateTotal:function(){
                $("#add_new_product").on("click", function(){
                    var total=0;
                    $.each($(".product-subtotal-price-active"), function(){
                        total=total+parseFloat($(this).html())                        
                    })
                    $("#total_price").html(total.toFixed(2));
                });
            },

            showStatus:function(){
                $(".status_wrapper i").mouseover(function(){
                    $(this).parent().find("span").show();
                });
                $(".status_wrapper i").mouseout(function(){
                    $(this).parent().find("span").hide();
                });
            },

            init:function(){
                _order.searchUser();
                _order.searchProduct();
                _order.updateSubtotal();
                _order.removeProduct();
                _order.addNew();
                _order.updateTotal();
                _order.showStatus();
            }
        }

        _order.init();

    });

})(jQuery);

function selectProduct(id, title, price){
    $=jQuery
    var views =$("#productViews").val();
    var subtotal =views*price
    $("#productName").val(title);
    $("#productID").val(id);
    // $("#productName").attr("data-product_id", id);
    $("#priceContain").html(price);
    $("#subtotalContain").html(subtotal.toFixed(2));
    $('#autocomplete').fadeOut(); 
    $('#autocomplete').html('');    
}
function selectUser(id, login, email, fname, lname){
console.log('id ' , id);
    $=jQuery;
        $('#user_suggestions').fadeOut();
        $('#user_suggestions').html('');

        $("#order_user_login").val(login);
        $("#order_user_id").val(id);

        $("#email").html(email);
        $("#order_user_email").val(email);
        
        $("#first_name").html(fname);
        $("#order_user_fname").val(fname);

        $("#last_name").html(lname);
        $("#order_user_lname").val(lname);
}

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

(function($){

	$(document).ready(function(){

		var _product= {

			button:$(".add-new-group.add"),	

			saveProduct:function(){				
				_product.button.on("click", function(){
					var data = $("#groupProduct").serialize();			
						$.ajax({
							type:"POST",
						  	url: ajaxurl, 
						  	data: {
							    action: "save_product_group",						    
							    data_value: data							   							    
							}, 
							success:function(id){
								window.location='admin.php?page=lms_product_groups_edit&group='+id;
							}
					});				
				});
			},

			deleteGroup:function(){
				$(".deleteProductGroup").on("click", function(){
					var id = parseInt($(this).data("id"));
					$(this).parents().eq(2).remove();
					$.ajax({						
						type:"POST",
						url: ajaxurl, 
						data:{
							action:"delete_product_group",
							id:id
						},
						success:function(response){
							console.log('response ' , response);
						}
					});
				});
			},

			init:function(){
				_product.saveProduct();
				_product.deleteGroup();
			}
		}

		_product.init()

	});
})(jQuery);
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

