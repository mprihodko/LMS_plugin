<div class='test-video'>			
	<?php if(is_array($video)): ?>	
		<?php $i=0; ?>			
		<?php foreach($video as $k => $video_data): $i++; ?>
			<?php 	$pos = strrpos($video_data['url'], "."); ?>
      		<?php	$ext = substr($video_data['url'], $pos+1); ?>              		            		
			<center id="the_video_block_<?php if($ext == 'flv'): ?>flv<?php else: echo $ext; endif; ?>">
                <video id="the_video_<?=$i?>" class="video-js video-js-completion video-js-<?=$i?> vjs-default-skin" controls preload="auto" data-setup='{"controls": true, "autoplay": true, "preload": "false"}' <?=(($i>1)? 'style="display: none;"' : '' )?>>
                    <source src="<?=$video_data['url']?>" type="video/<?php echo (($ext == 'm4v') ? 'mp4' :  $ext ) ?>">
                    <p class="vjs-no-js">
                    	To view this video please enable JavaScript, and consider upgrading to a web browser that 
                    	<a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                    </p>
                </video>
            </center>
            <script type="text/javascript">
              setInterval(function(){ 
                jQuery(document).ready(function($){        
                  $("#the_video_<?=$i?>_html5_api").on('ended',function(){
                    var videoview<?=$i?>='true';      
                    jQuery(".video-js-<?=$i?>").hide(); 
                    jQuery(".video-js-<?=$i+1?>").show();
                    jQuery(".video-js-<?=$i+1?> video").show();
                    jQuery('.video-js-<?=$i+1?> .vjs-big-play-button').click(); 
                  });        
                });
              },1000);                      
    		</script>
    	<?php endforeach;?>
    	<input type="hidden" value="<?=count($video)?>" id="video_count">
    	<input type="hidden" value="<?=$post->ID?>" id="post_id">
    	<input type="hidden" value="<?=$user->ID?>" id="user_id">
    <?php endif; ?> 
</div>
<input type='hidden' value="<?=the_permalink()?>?part=<?=$GLOBALS['tests']->get_part_after_video(get_the_ID())?>" id="redirect_link">

   
