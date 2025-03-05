<?php 
$anim_confirm = "bounceInDown";
$anim_error = "bounceInUp"; 

if ( $float ) $class_box="box_fixed";
else $class_box="box_msg";
?>
<div class="grid-x">
<?php if ( $message_type==LastMessage::MESSAGE ) : ?>
	<div class="<?php echo $class_box ?> box_confirm <?php echo $anim_confirm ?> animated">
		<i class="fa-solid fa-circle-check"></i>&nbsp;<?php echo $message ?>
	</div>
<?php else: ?>
	<div class="<?php echo $class_box ?> box_error <?php echo $anim_error ?> animated">
		<i class="fa-solid fa-circle-exclamation"></i>&nbsp;<?php echo $message ?>
	</div>
<?php endif; ?>
</div>

