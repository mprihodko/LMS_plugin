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