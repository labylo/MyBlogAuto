<?php echo $message ?>

<?php if ( $tab_var ) : ?>
	<?php foreach($tab_var as $key=>$val) :?>
		- <?php echo $key ?> : <?php echo $val ?>
	<?php endforeach; ?>
<?php endif; ?>

<?php include("_bas_text.php") ?>