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