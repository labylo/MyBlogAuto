<div class="grid-x grid-margin-x">
	<div class="cell large-8 medium-8 small-12">
	
		<div class="bloc">
			<div class="bloc_titre">
				<h1><span><?php echo $h1 ?></span></h1>
			</div>
			
			<div class="bloc_content">
				<br><br>
				<div class="text-center">
					<p style="padding:10px;" class="bold">Oups ! la page que vous avez demandée est introuvable.</p>
					<p style="padding:10px;">Si vous pensez qu'il s'agit d'une erreur, merci de <a href="/contact">contacter l'administrateur</a>.</p>
					<br>
					<a class="btn" href="/"><i class="fa fa-home"></i>Accueil <?php echo SITE_NAME ?></a>
					<br><br>
				</div>
				
				<hr>
		
		
				<h2 class="solo">Catégories populaires</h2>
	
				<div class="grid-x grid-margin-x">
					<div class="cell small-12">
						<ul class="liste_lien">
						<?php foreach($infoCategorieAccueil as $cat) : ?>
							<li><a href="/categorie/theme/<?php echo $cat['motcle'] ?>/<?php echo $cat['categorie_id'] ?>"><?php echo $cat['categorie'] ?></a></li>
						<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>
