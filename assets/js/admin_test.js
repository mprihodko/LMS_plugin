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