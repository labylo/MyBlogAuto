<div class="bloc">
	<div class="bloc_titre">
		<h2><span>Articles les plus lus</span></h2>
	</div>
	<?php foreach($infoArticlePlusLus as $mr) : ?>
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
						<h3 style="margin:0 0 10px 0;"><a href="<?php echo $url ?>"><?php echo $mr['h1'] ?></a></h3>
						<p class="date">
							<i class="fa-regular fa-calendar"></i>&nbsp;<?php echo ucfirst($this->FancyDate->getAsFacebook($mr['date_creation'])) ?>
							<?php if ( COMMENTAIRE_USER ) : ?>
								&nbsp;&nbsp;<i class="fa-regular fa-comment"></i>&nbsp;<?php echo $mr['commentaire_nb'] ?>
							<?php endif; ?>	
						</p>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>