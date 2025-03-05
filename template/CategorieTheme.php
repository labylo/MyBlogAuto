<?php $this->LastMessage->display(); ?>

<div class="grid-x grid-margin-x">
	<div class="cell large-8 medium-7 small-12">
		<div class="bloc">
			<div class="bloc_titre">
				<h1><span><i class="<?php echo $fa_icone ?> fa-lg"></i>&nbsp;<?php echo $h1 ?></span></h1>
			</div>
			
			<?php
			$libelle = "Articles";
			$title = $infoPage['h1'];
			$url_base = "/categorie/theme/".$infoPage['motcle']."/".$infoPage['categorie_id'];
			?>
			
			<?php 
			if ( $infoArticles && $article_nb > THEME_ARTICLE_NB ) {
				$this->FancyUtil->getSuivPrec($libelle, $title, $url_base, $page, THEME_ARTICLE_NB, $article_nb, false);
			}
			?>
			
			<?php if ( ! $infoArticles ) : ?>
				<br><br>
				<div class="text-center">
				Désolé, mais il n'y a aucun article à présenter pour cette catégorie<br>
				</div>
				<br><br>
			<?php else: ?>

				<?php foreach($infoArticles as $mr) : ?>
						
					<?php
					$image_path = $this->FancyUtil->getImagePath($mr['article_id'], true);
					$url = "/article/actu/".$mr['motcle']."/".$mr['article_id'];
					?>
					<div class="bloc_article bloc_clic" data-url="<?php echo $url ?>">
						<div class="grid-x">
							<div class="cell large-4 medium-4 small-12">
								<div class="image">
									<a href="<?php echo $url ?>"><img src="<?php echo $image_path ?>" alt="<?php echo $mr['meta_title'] ?>"></a>
								</div>
							</div>
							<div class="cell large-8 medium-8 small-12">
								<div class="cont">
									<h2><a href="<?php echo $url ?>"><?php echo $mr['h1'] ?></a></h2>
									<p class="date"><i class="fa-regular fa-calendar"></i>&nbsp;<?php echo $this->FancyDate->getAsFacebook($mr['date_creation']) ?>
									<?php if ( COMMENTAIRE_USER ) : ?>
										&nbsp;&nbsp;<i class="fa-regular fa-comment"></i>&nbsp;<?php echo $mr['commentaire_nb'] ?>
									<?php endif; ?>
									</p>
									<p class="phrase"><?php echo $mr['phrase'] ?></p>
								</div>
							</div>
						</div>
					</div>
	
				<?php endforeach; ?>
			<?php endif; ?>
			
			<?php if ( $infoArticles && $article_nb > THEME_ARTICLE_NB ) : ?>
				<?php
				$this->FancyUtil->getSuivPrec($libelle, $title, $url_base, $page, THEME_ARTICLE_NB, $article_nb, true);
				?>
				<br>
			<?php endif; ?>
			
		</div>
	</div>
	<div class="cell large-4 medium-5 small-12">
		<?php
		$this->CategorieControler->RenderCategorieDescription($categorie_id);
		$this->CategorieControler->RenderCategorieListe($categorie_id);
		?>
	</div>
</div>


<script>
$(function() {
	$(".bloc_clic").click(function() {
		var url = $(this).attr("data-url");
		$(location).attr('href',url);
	});
});
</script>
