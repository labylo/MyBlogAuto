<div class="grid-x grid-margin-x">
	<div class="cell large-8 medium-7 small-12">
		<div class="bloc">
			<div class="bloc_titre">
				<h1><span><?php echo $h1 ?></span></h1>
			</div>

			<div class="bloc_content">
				<div class="grid-x grid-margin-x">
					<div class="cell small-3">
						<img src="/img/redacteur/<?php echo$infoRedacteur['redacteur_id'] ?>.jpg" alt="<?php echo$infoRedacteur['prenom'] ?> <?php echo$infoRedacteur['nom'] ?>">
					</div>
					<div class="cell small-9">
						<?php 
						$bio = $infoRedacteur['bio'];
						$bio = str_replace(".", ".<br>", $bio);
						echo $bio ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="cell large-4 medium-5 small-12">
		<?php if ( $infoDernierArticle ) : ?>
		<div class="bloc">
			<div class="bloc_titre">
				<h2><span>Ses derniers articles</span></h2>
			</div>
			<div class="bloc_content">
				<ul class="liste_lien">
				<?php foreach($infoDernierArticle as $mr) : ?>
					<?php
					$url = "/article/actu/".$mr['motcle']."/".$mr['article_id'];
					?>
					<li><a href="<?php echo $url ?>"><?php echo $mr['h1'] ?></a></li>
				<?php endforeach; ?>
				</ul>
			
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>