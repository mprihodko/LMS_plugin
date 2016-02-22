<?php if($_GET['part']=="before"){ ?>
	<h2>Media Attached Files</h2>
	<?php foreach ($media as $key => $value) { ?>
		<?php if($value['pos']=='before'){ ?>
			<a href="<?=$value['url']?>"><?=substr($value['file'], strripos($value['file'], '/')+1)?></a>
		<?php }else{
			$after[]=$value;
			} ?>
	<?php } ?>
	<div class="next_step">
		<?php if($segments=='on') { ?>
		<a href="<?=the_permalink()?>?part=interaction">Next Part</a>
		<?php }elseif($GLOBALS['tests']->the_video()){ ?>
		<a href="<?=the_permalink()?>?part=video">Next Part</a>
		<?php }elseif(isset($after)){ ?>
		<a href="<?=the_permalink()?>?part=after">Next Part</a>
		<?php } ?>
	</div>
<?php } ?>
<?php if($_GET['part']=="after"){ ?>
	<h2>Media Attached Files</h2>
	<?php foreach ($media as $key => $value) { ?>
		<?php if($value['pos']=='after'){ ?>
			<a href="<?=$value['url']?>"><?=substr($value['file'], strripos($value['file'], '/')+1)?></a>		
		<?php } ?>
	<?php } ?>
	<div class="next_step">
		<a href="<?=the_permalink()?>?part=questions">Next Part</a>
	</div>
<?php } ?>
