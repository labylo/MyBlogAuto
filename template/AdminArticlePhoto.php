<div class="grid-x grid-margin-x">
	<div class="cell large-12">
		
		
		<div class="bloc">
			<div class="bloc_titre">
				<h1><span><?php echo $h1 ?></span> <a href="/admin/articleModifier/<?php echo $article_id ?>" class="btn">Retour article</a></h1>
			</div>
			<div class="bloc_content">
				<div class="grid-x grid-margin-x">
					<div class="cell large-3 small-4">
						H1 : <br>
						<?php echo $infoArticle['h1'] ?>
						<hr>
						Mot-clé : <b><?php echo $motcle ?></b>
						
						<form action="/" method="post">
							<?php $this->Connexion->displayTokenField();?>
							<input type="hidden" name="path_info" value="/admin/ArticlePhoto/<?php echo $article_id ?>">

							<input type="text" name="motcle" value="">
							<input type="submit" value="Relancer">
						</form>
			
						
						<br>
						<?php
						$image_path = $this->FancyUtil->getImagePath($infoArticle['article_id'], false);
						?>
						<img style="border:1px solid silver;width:300px;" src="<?php echo $image_path ?>" alt="<?php echo $infoArticle['motcle'] ?>"><br>
						Auteur : <?php echo $infoArticle['image_credit'] ?>
					</div>
					<div class="cell large-9 small-8">
						Proposition  : <br>
						<div class="grid-x">
						<?php
						//var_dump($tab_image); 
						?>
						<?php for ($i = 1; $i <= 15; $i++) : ?>
							<div class="cell large-4 small-12 text-center" style="border:2px solid silver;padding:20px">
								<?php
								$rand = rand(0, ($nb_result-1) );
								$img_thumb = $tab_image['hits'][$rand]['webformatURL'];
								$image = $tab_image['hits'][$rand]['largeImageURL'];
								$image_url = $tab_image['hits'][$rand]['pageURL'];
								$image_id = $tab_image['hits'][$rand]['id'];
								
								$tags = $tab_image['hits'][$rand]['tags'];
								$image_credit = $tab_image['hits'][$rand]['user'];
								$image_userid = $tab_image['hits'][$rand]['user_id'];
								?>
								<p class="discret"><?php echo $tags ?></p>

								<img src="<?php echo $img_thumb ?>" alt="" style="margin-bottom:10px;border:1px solid silver;">
								<br>Crédit : <?php echo $image_credit ?>
								<form action="/" method="post">
									<?php $this->Connexion->displayTokenField();?>
									<input type="hidden" name="path_info" value="/admin/articlePhotoRemplacer">
									<input type="hidden" name="article_id" value="<?php echo $infoArticle['article_id'] ?>">
									<input type="hidden" name="motcle" value="<?php echo $infoArticle['motcle'] ?>">
									
									<input type="hidden" name="image_credit" value="<?php echo $image_credit ?>">
									<input type="hidden" name="image_userid" value="<?php echo $image_userid ?>">

									<input type="hidden" name="image" value="<?php echo $image ?>">
									<input type="hidden" name="image_url" value="<?php echo $image_url ?>">
									<input type="hidden" name="image_id" value="<?php echo $image_id ?>">
									
									<button type="submit" class="btn">Utiliser cette image</button>
								</form>
							</div>
						<?php endfor; ?>
						</div>
					</div>
				</div>
			</div>
		</div>

	
	</div>
	
</div>