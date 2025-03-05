<h1><?php echo $h1 ?></h1>



<?php if ( ! $infoCategorie ) : ?>
	<div class="text-center">
	Désolé, mais il n'y a aucun thème à afficher
	</div>
<?php else: ?>

	
	<?php
	foreach($infoCategorie as $mr) {
		
		
		$url = "/categorie/theme/".$mr['motcle']."/".$mr['categorie_id'];
		?>
		<div class="bloc">
		<h2><a href="<?php echo $url ?>"><?php echo $mr['categorie'] ?></a></h2>
		<?php echo $mr['resume'] ?>
		<br>
		<a href="<?php echo $url ?>">Lire les  <?php echo $mr['article_nb'] ?> article de cette catégorie</a>
		</div>
		<?php
	}
	?>
		
	
<?php endif; ?>
