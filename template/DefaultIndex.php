<?php $this->LastMessage->display(); ?>

<div class="grid-x grid-margin-x">
	<div class="cell large-8 medium-7 small-12">
		<div class="bloc">
			<div class="bloc_titre">
				<h2><span>Derniers Articles XXX22 33</span></h2>
			</div>
			
			<?php 
			$cpt = 0;
			foreach($infoArticles as $mr) : 
				$cpt++;
				$url = "/article/actu/".$mr['motcle']."/".$mr['article_id'];
				if ( $cpt==1 ) :
					$image_path = $this->FancyUtil->getImagePath($mr['article_id'], false);
					$image_path_mobile = $this->FancyUtil->getImagePath($mr['article_id'], true);
					?>
					<div class="bloc_article_hero">
						<div class="bloc_clic" data-url="<?php echo $url ?>">
							<div class="image">
								<picture>
									<source media="(max-width: 480px)" srcset="<?php echo $image_path_mobile ?>">
									<img src="<?php echo $image_path ?>" alt="<?php echo $mr['meta_title'] ?>">
								</picture>
							</div>
						
							<div class="cont_header_left">
								<span class="categorie"><i class="<?php echo $mr['fa_icone'] ?>"></i>&nbsp;<?php echo $mr['categorie'] ?></span>
							</div>
							<div class="cont_header_right">
								<span class="date">
									<i class="fa-regular fa-calendar"></i>&nbsp;<?php echo $this->FancyDate->getAsFacebook($mr['date_creation']) ?>
									<?php if ( COMMENTAIRE_USER ) : ?>
										&nbsp;|&nbsp;<i class="fa-regular fa-comment"></i>&nbsp;<?php echo $mr['commentaire_nb'] ?>
									<?php endif; ?>
								</span>
							</div>
							
							<div class="cont_footer">
								<h3><a href="<?php echo $url ?>"><?php echo $mr['h1'] ?></a></h3>
								<hr>
								<p class="phrase"><?php echo $mr['meta_description'] ?></p>
							</div>
						</div>
					</div>
				<?php else : ?>
					<?php
					$image_path = $this->FancyUtil->getImagePath($mr['article_id'], true);
					?>
					<div class="bloc_article bloc_clic" data-url="<?php echo $url ?>">
						<div class="grid-x">
							<div class="cell large-4 medium-4 small-12">
								<div class="image">
									<img src="<?php echo $image_path ?>" alt="<?php echo $mr['meta_title'] ?>">
								</div>
							</div>
							<div class="cell large-8 medium-8 small-12">
								<div class="cont">
									<p class="categorie"><i class="<?php echo $mr['fa_icone'] ?>"></i>&nbsp;<?php echo $mr['categorie'] ?></p>
									<h3><a href="<?php echo $url ?>"><?php echo $mr['h1'] ?></a></h3>
									<p class="date">
										<i class="fa-regular fa-calendar"></i>&nbsp;<?php echo $this->FancyDate->getAsFacebook($mr['date_creation']) ?>
										<?php if ( COMMENTAIRE_USER ) : ?>
											&nbsp;|&nbsp;<i class="fa-regular fa-comment"></i></i>&nbsp;<?php echo $mr['commentaire_nb'] ?>
										<?php endif; ?>
									</p>
									<p class="phrase"><?php echo $mr['meta_description'] ?></p>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
					
			<?php endforeach; ?>
		</div>
	</div>
	<div class="cell large-4 medium-5 small-12">
		<?php
		include("include/bloc_recherche.php");
		$this->CategorieControler->renderCategorieListe();
		$this->ArticleControler->renderArticleLesPlusComm(4);
		//$this->ArticleControler->renderArticleLesPlusLus(4);
		include("include/bloc_archive.php");
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