<div class="bloc">
	<div class="bloc_titre">
		<h2><span>Articles par catégorie</span></h2>
	</div>
	<div class="bloc_content">
		<ul class="liste_lien">
		<?php foreach($infoCategorie as $mr) : ?>

			
			<?php if ( $exclude_categorie_id == $mr['categorie_id'] ) : ?>
				<li style="padding:4px;font-weight:bold;"><?php echo $mr['categorie'] ?> <span class="discret">(<?php echo $mr['article_nb'] ?>)</span></li>
			<?php else: ?>
				<?php
				$url = "/categorie/theme/".$mr['motcle']."/".$mr['categorie_id'];
				?>
				<li><a href="<?php echo $url ?>"><?php echo $mr['categorie'] ?> <span class="discret">(<?php echo $mr['article_nb'] ?>)</span></a></li>
			<?php endif; ?>
			
		<?php endforeach; ?>
		</ul>
	</div>
</div>
