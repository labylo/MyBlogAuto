<?php include("_haut.php") ?>

<?php echo $message ?>

<?php if ( $tab_var ) : ?>
<ul><?php foreach($tab_var as $key=>$val) { echo "<li>" . $key . " : ".$val."</li>"; } ?></ul>
<?php endif; ?>

<?php include("_bas.php") ?>