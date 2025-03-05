<div class="grid-x grid-margin-x">
	<div class="cell large-8 medium-7 small-12">
	
		<div class="bloc">
			<div class="bloc_titre">
				<h1><span><?php echo $h1 ?></span></h1>
			</div>
			<div class="bloc_article_content">
				<?php if ( $this->Connexion->isAdmin() ) : ?>
					<?php $this->LastMessage->display(); ?>
					<a class="btn" href="/admin/articleModifier/<?php echo $infoArticle['article_id'] ?>">Modifier</a>&nbsp;
					<a class="btn" href="/admin/articlePhoto/<?php echo $infoArticle['article_id'] ?>">Gérer photos</a><br>
				<?php endif; ?>
				
				<div class="grid-x">
					<div class="cell large-6 medium-12 small-12">
						<p class="discret" style="line-height:30px;">
							<i class="fa-regular fa-calendar"></i>&nbsp;Publié <?php echo $this->FancyDate->getAsFacebook($infoArticle['date_creation']) ?>
							<?php if ( COMMENTAIRE_USER ) : ?>
							&nbsp;&nbsp;<i class="fa-regular fa-comment"></i>&nbsp;<?php echo $infoArticle['commentaire_nb'] ?>
							<?php endif; ?>
						</p>
					</div>
					<div class="cell large-6 medium-12 small-12 large-text-right">
						<span class="categorie"><a href="/categorie/theme/<?php echo $categorie_motcle ?>/<?php echo $categorie_id ?>"><i class="<?php echo $categorie_fa_icone ?>"></i>&nbsp;<?php echo $categorie_lib ?></a></span>
					</div>
				</div>
				<br>
				<?php
				$chapeau = $infoArticle['chapeau'];
				$chapeau = str_replace(".", ".<br>", $chapeau);
				$chapeau = str_replace("?", "?<br>", $chapeau);
				echo $chapeau;
				?>
				<br>
				<?php
				$image_path = $this->FancyUtil->getImagePath($infoArticle['article_id'], false);
				$image_path_mobile = $this->FancyUtil->getImagePath($infoArticle['article_id'], true);
				?>
				<picture>
					<source media="(max-width: 480px)" srcset="<?php echo $image_path_mobile ?>">
					<img src="<?php echo $image_path ?>" alt="<?php echo $infoArticle['meta_title'] ?>">
				</picture>
				<p class="image_copyright">Crédits photo : Pixabay | <?php echo $infoArticle['image_credit'] ?></p>
				
				<?php
				$article = $infoArticle['article'];
				?>
		
				<?php echo $article ?>
				<br><br>
			</div>
		</div>

		<?php
		// début bloc Article Précedent / Article Suivant
		$col_large = 6;
		if ( !$infoArticlePrec || !$infoArticleSuiv ) $col_large = 12;
		?>
		<div class="grid-x">
			<div class="cell large-<?php echo $col_large ?> medium-<?php echo $col_large ?> small-12">
				<?php if ( $infoArticlePrec ) : ?>
					<?php
					//$image_path = $this->FancyUtil->getImagePath($infoArticlePrec['article_id'], true);
					$url = "/article/actu/".$infoArticlePrec['motcle']."/".$infoArticlePrec['article_id'];
					?>
					<div class="bloc">
						<div class="bloc_titre">
							<h2><span><i class="fa-regular fa-circle-left"></i>&nbsp;Article précédent</span></h2>
						</div>
						<div class="bloc_article">
							<div style="min-height:154px;">
								<p class="categorie categorie_<?php echo $infoArticlePrec['categorie_id']; ?>" style="margin-bottom:8px;">
								<i class="<?php echo $infoArticlePrec['categorie_icon'] ?>"></i>&nbsp;<?php echo $infoArticlePrec['categorie_lib'] ?></a>
								<?php if ( COMMENTAIRE_USER ) : ?>
									&nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa-regular fa-comment"></i></i>&nbsp;<?php echo $infoArticlePrec['commentaire_nb'] ?>
								<?php endif; ?>	
								</p>
										
								<p><a href="<?php echo $url ?>"><strong><?php echo $infoArticlePrec['h1'] ?></strong></a></p>
								<p class="phrase"><?php echo $infoArticlePrec['meta_description'] ?>...</p>
								<?php
								/*
								<p class="date">
									<i class="fa-regular fa-calendar"></i>&nbsp;<?php echo ucfirst($this->FancyDate->getAsFacebook($infoArticlePrec['date_creation'])) ?>
									<?php if ( COMMENTAIRE_USER ) : ?>
										&nbsp;|&nbsp;<i class="fa-regular fa-comment"></i></i>&nbsp;<?php echo $infoArticlePrec['commentaire_nb'] ?>
									<?php endif; ?>
								</p>
								*/
								?>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<div class="cell large-<?php echo $col_large ?> medium-<?php echo $col_large ?> small-12">
			<?php if ( $infoArticleSuiv ) : ?>
				<?php
				//$image_path = $this->FancyUtil->getImagePath($infoArticleSuiv['article_id'], true);
				$url = "/article/actu/".$infoArticleSuiv['motcle']."/".$infoArticleSuiv['article_id'];
				?>
				<div class="bloc">
					<div class="bloc_titre text-right">
						<h2><span>Article suivant&nbsp;<i class="fa-regular fa-circle-right"></i></span></h2>
					</div>
					<div class="bloc_article">
						<div style="min-height:154px;">
							<p class="categorie categorie_<?php echo $infoArticleSuiv['categorie_id']; ?>" style="margin-bottom:8px;">
							<i class="<?php echo $infoArticleSuiv['categorie_icon'] ?>"></i>&nbsp;<?php echo $infoArticleSuiv['categorie_lib'] ?></a>
							<?php if ( COMMENTAIRE_USER ) : ?>
								&nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa-regular fa-comment"></i></i>&nbsp;<?php echo $infoArticlePrec['commentaire_nb'] ?>
							<?php endif; ?>	
							</p>
								
							<p><a href="<?php echo $url ?>"><strong><?php echo $infoArticleSuiv['h1'] ?></strong></a></p>
							<p class="phrase"><?php echo $infoArticleSuiv['meta_description'] ?>...</p>
							<?php
							/*
							<p class="date">
								<i class="fa-regular fa-calendar"></i>&nbsp;<?php echo ucfirst($this->FancyDate->getAsFacebook($infoArticleSuiv['date_creation'])) ?>
								<?php if ( COMMENTAIRE_USER ) : ?>
									&nbsp;|&nbsp;<i class="fa-regular fa-comment"></i></i>&nbsp;<?php echo $infoArticleSuiv['commentaire_nb'] ?>
								<?php endif; ?>
							</p>
							*/
							?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			</div>
		</div>
		
		
		<div class="bloc">
			<div class="bloc_titre">
				<h2><span>L'auteur</span></h2>
			</div>
			<div class="bloc_content">
				<div class="grid-x grid-margin-x">
					<div class="cell large-3 medium-12 small-12 text-center bloc_auteur_photo">
						<a href="/redacteur/detail/<?php echo $infoRedacteur['redacteur_id'] ?>">
							<img src="/img/redacteur/<?php echo $infoRedacteur['redacteur_id'] ?>.jpg" alt="<?php echo $infoRedacteur['prenom'] ?> <?php echo$infoRedacteur['nom'] ?>">
							<p style="margin-top:8px;"><i class="fa-solid fa-circle-arrow-right"></i>&nbsp;<?php echo $infoRedacteur['prenom'] ?>&nbsp;<?php echo$infoRedacteur['nom'] ?></p>
						</a>
						
						<?php //echo $this->FancyDate->getAsFacebook($infoArticle['date_creation']) ?>
						
					</div>
					<div class="cell large-9 medium-12 small-12">
					
						<?php if ( $infoDernierArticle ) : ?>
							<b>Ses derniers articles</b>
							<ul class="liste_lien">
							<?php foreach($infoDernierArticle as $mr) : ?>
								<?php
								$url = "/article/actu/".$mr['motcle']."/".$mr['article_id'];
								?>
								<li><a href="<?php echo $url ?>"><?php echo $mr['h1'] ?></a></li>
							<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	
		<?php if ( COMMENTAIRE_USER ) : ?>
		
		<div class="bloc" id="comment">
			<div class="bloc_titre">
				<div class="grid-x commentaires">
					<div class="cell large-6 medium-6 small-12">
						<h2><span>Commentaires</span></h2>
					</div>
					<div class="cell large-6 medium-6 small-12 text-right">
						<?php if ( $this->Connexion->isAdmin() ) : ?>
							<a class="btn" href="/admin/commentaireGenerer/<?php echo $infoArticle['article_id'] ?>">Générer un commentaire</a>&nbsp;
						<?php endif; ?>
					</div>
				</div>
				
			</div>
			<div class="bloc_content">
				<?php if ( $lien_interne == "succes" ) : ?>
				<div class="box_msg box_confirm">
					Votre message a été posté !
				</div>
				<?php endif; ?>
				
				<?php if ( $infoCommentaire ) : ?>
					<?php foreach($infoCommentaire as $mr) : ?>
						<div class="grid-x commentaires">
							<div class="cell large-2 medium-3 small-12 utilisateur">
								<br>
								<i class="fa-solid fa-user-large"></i>
								<br>
								<?php echo $mr['pseudo'] ?>
							</div>
							<div class="cell large-10 medium-9 small-12 commentaire">
								<p class="date" style="margin-bottom:10px;">
									<?php echo $this->FancyDate->getAsFacebook($mr['date_creation']) ?>
									<?php if ( $this->Connexion->isAdmin() ) : ?>
										&nbsp;<a href="/admin/commentaireSupprimer/<?php echo $mr['article_id'] ?>/<?php echo $mr['commentaire_id'] ?>">[Supprimer]</a>
										&nbsp;(<i><?php echo $mr['ton'] ?>, <?php echo $mr['longueur'] ?></i>)
									<?php endif; ?>
								</p>
								<?php echo nl2br($mr['commentaire']); ?>
							</div>
						</div>
						
						
						
					<?php endforeach; ?>
				<?php else: ?>
					<br>
					<p class="text-center">Il n'y a encore aucun commentaire sur cet article, soyez le premier à vous exprimer !</p>
					<br>
				<?php endif; ?>
			</div>
		</div>
		
		<div class="bloc" id="poster">
			<div class="bloc_titre">
				<h2><span>Laisser un commentaire</span></h2>
			</div>
			<div class="bloc_content text-center">
				<?php if ( $this->Connexion->isConnected() ) : ?>
					<?php $this->LastMessage->display(); ?>
				
					
					<?php if ( $lien_interne == "connexion" ) : ?>
					<div class="box_msg box_confirm">
						Vous êtes connecté !<br>
						Vous pouvez écrire votre commentaire.
					</div>
					<?php endif; ?>
					
					<?php if ( $lien_interne == "erreur" ) : ?>
					<div class="box_msg box_error">
						Impossible de poster votre commentaire, car celui-ci n'est pas assez explicite !
					</div>
					<?php endif; ?>
					<form action="/" method="post">
						<?php $this->Connexion->displayTokenField();?>
						<input type="hidden" name="path_info" value="/commentaire/post">
						<input type="hidden" name="article_id" value="<?php echo $infoArticle['article_id'] ?>">
	
						<div class="grid-x">
							<div class="cell large-2 medium-3 small-12 text-center">
								<label for="lbl_commentaire"class="middle">Votre commentaire</label>
							</div>
							<div class="cell large-10 medium-9 small-12 text-right">
								<textarea name="commentaire" style="height:260px;" id="lbl_commentaire"></textarea>
							</div>
						</div>
		
						<div class="text-right">
							<button type="submit" class="btn"><i class="fa fa-check"></i>Envoyer</button>
						</div>

					</form>	
				
				
				<?php else : ?>
					<br>
					Merci de vous connecter à votre compte pour pouvoir poster un commentaire.
					<br><br>
					<a href="/login" class="btn"><i class="fa-solid fa-right-to-bracket"></i>Connexion</a>
					<br><br>
					
				<?php endif; ?>
			</div>
		</div>
		<?php endif; //COMMENTAIRE_USER ?>
	</div>
	<div class="cell large-4 medium-5 small-12">
		<div class="bloc">
			<div class="bloc_titre">
				<h2><span>Article sur le même sujet</span></h2>
			</div>
			<?php foreach($infoArticles as $mr) : ?>
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
								<p class="date" style="margin:0;">
									<i class="fa-regular fa-calendar"></i>&nbsp;<?php echo ucfirst($this->FancyDate->getAsFacebook($mr['date_creation'])) ?>
									<?php if ( COMMENTAIRE_USER ) : ?>
										&nbsp;&nbsp;<i class="fa-regular fa-comment"></i>&nbsp;<?php echo $mr['commentaire_nb'] ?>
									<?php endif; ?>
								</p>
								<h3 style="font-size:90%;font-weight:normal;"><a href="<?php echo $url ?>"><?php echo $mr['h1'] ?></a></h3>
							</div>
						</div>
						
					</div>
				</div>
			<?php endforeach; ?>
		
		</div>
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