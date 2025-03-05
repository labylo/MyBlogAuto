<div class="bloc_central">
	<div class="contenu">
		<div class="row">
			<div class="columns small-12">

				<h1 class="text-center">Une erreur a été rencontrée</h1>
			
				<div class="bloc_cont text-center">
					
					<div class="box_msg box_error bold">
					La page à laquelle vous tentez d'accéder n'est pas disponible.<br>
					Code erreur : <?php echo $code ?>
					</div>
					<br>
					Si vous pensez qu'il s'agit d'une erreur, merci de <a href="/info/contact/<?php echo $code ?>">nous contacter</a>,
					<br>
					en nous précisant le code erreur suivant : <b><?php echo $code ?></b>.
					
					
					<br><br><br>
					<a class="btn" href="/"><i class="fa fa-home"></i>Accueil <?php echo SITE_NAME ?></a>
					
				</div>
		
			</div>
		</div>
	</div>

</div>