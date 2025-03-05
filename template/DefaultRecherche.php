<?php $this->LastMessage->display(); ?>

<div class="grid-x grid-margin-x">
	<div class="cell large-8 medium-8 small-12">
		<div class="bloc">
			<div class="bloc_titre">
				<h1><span><?php echo $h1 ?></span></h1>
			</div>
			
			
			<?php if ( ! $infoArticle ) : ?>
				<div class="bloc_content text-center">
				<br><br>
				Désolé, mais il n'y a aucun article à présenter pour votre recherche
				<br>
				Veuillez renouveller votre demande avec d'autres mot-clés.
				<br><br>
				</div>
				<br><br>
			<?php else: ?>
				<div class="bloc_content text-center">
					Votre recherche sur <b><?php echo $motcle ?></b> a retourné <b><?php echo $nb_result ?></b> résultat(s).
				</div>
				<?php foreach($infoArticle as $mr) : ?>
						
					<?php
					$url = "/article/actu/".$mr['motcle']."/".$mr['article_id'];
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
									<h2><a href="<?php echo $url ?>"><?php echo $mr['h1'] ?></a></h2>
									<p class="date"><i class="fa-regular fa-calendar"></i>&nbsp;<?php echo $this->FancyDate->getAsFacebook($mr['date_creation']) ?></p>
									<p class="phrase"><?php echo $mr['phrase'] ?></p>
								</div>
							</div>
						</div>
					</div>
	
				<?php endforeach; ?>
			<?php endif; ?>
			
		</div>
	</div>
	<div class="cell large-4 medium-4 small-12">
		<?php
		include("include/bloc_recherche.php");
		//$this->CategorieControler->RenderCategorieListe();
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