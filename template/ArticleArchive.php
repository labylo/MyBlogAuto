<?php $this->LastMessage->display(); ?>

<div class="grid-x grid-margin-x">
	<div class="cell large-8 medium-8 small-12">
		<div class="bloc">
			<div class="bloc_titre">
				<h1><span><i class="fa-solid fa-box-archive fa-lg"></i>&nbsp;<?php echo $h1 ?></span></h1>
			</div>
			
			
			<?php if ( ! $infoArticles ) : ?>
				<div class="bloc_content">
				<br><br>
				<div class="text-center">
				Désolé, mais il n'y a aucun article à présenter pour la période <?php echo $periode ?><br>
				</div>
				
				<br><br>
				</div>
			<?php else: ?>
			
				<?php 
				$liste_url = "";
				foreach($infoArticles as $mr) : 
				
					$image_path = $this->FancyUtil->getImagePath($mr['article_id'], true);
					$url = "/article/actu/".$mr['motcle']."/".$mr['article_id'];
					
					$liste_url .= SITE_URL.$url."\n";
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
								
									<p class="categorie"><i class="<?php echo $mr['fa_icone'] ?>"></i>&nbsp;<?php echo $mr['categorie'] ?></p>
										
									<h2><a href="<?php echo $url ?>"><?php echo $mr['h1'] ?></a></h2>
									<p class="date"><i class="fa-regular fa-calendar"></i>&nbsp;<?php echo $this->FancyDate->getAsFacebook($mr['date_creation']) ?></p>
									<p class="phrase"><?php echo $mr['meta_description'] ?></p>
								</div>
							</div>
						</div>
					</div>
					
				<?php endforeach; ?>
				
				<?php if ( $this->Connexion->isAdmin() ) : ?>
					<div style="background:silver;padding:10px;">
						<pre><?php echo $liste_url ?></pre>
					</div>
				<?php endif; ?>
				
			<?php endif; ?>
			
			
		</div>
	</div>
	<div class="cell large-4 medium-5 small-12">
		<?php
		include("include/bloc_recherche.php");
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
