<?php $this->LastMessage->display(); ?>
<div class="bloc">
	<div class="grid-x grid-margin-x">
		<div class="cell large-6 medium-6 small-6">
			<div class="bloc_titre">
				<h1><span><?php echo $h1 ?></span> <a href="/admin/articlePhoto/<?php echo $infoArticle['article_id'] ?>">Gérer les photos</a></h1>
			</div>
		</div>
		<div class="cell large-6 mediam-6 small-6 text-right">
			<a href="/article/actu/<?php echo $infoArticle['motcle'] ?>/<?php echo $infoArticle['article_id'] ?>" class="btn">Retour Article</a>
		</div>
	</div>
	
	<div class="bloc_content">
	
		<?php if ( ! $infoArticle ) : ?>
			<div class="text-center">
			Désolé, mais il n'existe aucun article avec cet ID
			</div>
		<?php else: ?>
	
		<div class="grid-x">
			<div class="cell small-2">
				<?php
				$image = $this->FancyUtil->getImagePath($infoArticle['article_id'], false, "webp");
				$image_path = RACINE_PATH."/www".$image;
				
				
				$imageInfo = getimagesize($image_path); 
				$mime = $imageInfo['mime'];
				?>
				Image pleine (<?php echo $imageInfo[0] ?>x<?php echo $imageInfo[1] ?>) <br>
				<img style="border:1px solid silver;" src="<?php echo $image ?>?refresh=<?php echo rand(0,999) ?>" alt="<?php echo $infoArticle['motcle'] ?>">
				<br><span class="discret"><?php echo $image ?></span><br>
				<?php
				$image_vignette = $this->FancyUtil->getImagePath($infoArticle['article_id'], true, "webp");
				$image_vignette_path = RACINE_PATH."/www".$image_vignette;
				$imageInfo = getimagesize($image_vignette_path); 
				?>
				<hr>
				Vignette (<?php echo $imageInfo[0] ?>x<?php echo $imageInfo[1] ?>) <br>
				<img style="border:1px solid silver;" src="<?php echo $image_vignette ?>?refresh=<?php echo rand(0,999) ?>" alt="<?php echo $infoArticle['motcle'] ?>">
				<br><span class="discret"><?php echo $image ?></span><br>
				<hr>
				
				<?php if ( $mime=="image/webp" ) : ?>
				<a href="/admin/articleRegenererVignette/<?php echo $infoArticle['article_id'] ?>" class="btn">Regénérer les vignettes</a>
				<hr>
				<?php endif; ?>
				
				
				
				<?php if ( $mime=="image/jpeg" ) : ?>
					<span style="color:red">Image format : <?php echo $mime ?></span>
					<a href="/admin/articlePhotoWebP/<?php echo $infoArticle['article_id'] ?>" class="btn">Transformer en WEBP</a>
				<?php elseif ( $mime=="image/png" ) : ?>
					<span style="color:red">IMAGE ABSENTE</span>
				<?php else: ?>
					<span style="color:green">Image format WEBP : OK</span>
				<?php endif; ?>
				<hr>
				
				<?php if ( ! $infoArticle['image_id'] ) : ?>
					Aucune sauvegarde d'image.<br>
					<a href="<?php echo $infoArticle['image_url'] ?>">Voir image temp</a>
					<hr>
					<a href="/admin/articlePhoto/<?php echo $infoArticle['article_id'] ?>" class="btn">Gérer les photos</a>
				<?php else: ?>
					Image OK : <a href="<?php echo $infoArticle['image_url'] ?>"><?php echo $infoArticle['image_id'] ?></a>
				<?php endif; ?>
			</div>
			<div class="cell small-10">
				<form action="/" method="post">
					<?php $this->Connexion->displayTokenField();?>
					<input type="hidden" name="path_info" value="/admin/doArticleModifier">
					<input type="hidden" name="article_id" value="<?php echo $article_id ?>">
					
					<?php if ( ! $infoArticle['visible'] ) : ?>
						
						<div class="box_msg box_alert">Cet article n'est pas publié</div>
						<?php
						$champ = "visible";
						?>
						<div class="grid-x grid-margin-x">
							<div class="cell small-2">
								<label for="lbl_<?php echo $champ ?>"><?php echo $champ ?></label>
							</div>
							<div class="cell small-8">
								<select name="<?php echo $champ ?>" class="w25pc">
									<option value="0">Non</option>
									<option value="1">Oui</option>
								</select>
							</div>
						</div>
					<?php endif; ?>
					
					
		
					<?php
					$champ = "h1";
					$value = $infoArticle[$champ];
					?>
					<div class="grid-x grid-margin-x">
						<div class="cell small-2">
							<label for="lbl_<?php echo $champ ?>"><?php echo $champ ?></label>
						</div>
						<div class="cell small-8">
							<input type="text" name="<?php echo $champ ?>" id="lbl_<?php echo $champ ?>" value="<?php echo $value ?>">
						</div>
					</div>
					
					<?php
					$champ = "categorie_id";
					$value = $infoArticle[$champ];
					?>
					<div class="grid-x grid-margin-x">
						<div class="cell small-2">
							<label for="lbl_<?php echo $champ ?>"><?php echo $champ ?></label>
						</div>
						<div class="cell small-8">
							<select type="text" name="<?php echo $champ ?>" id="lbl_<?php echo $champ ?>">
								<?php foreach( $infoCategorie as $cat ) : ?>
								<option value="<?php echo $cat['categorie_id'] ?>"
									<?php if ( $cat['categorie_id'] == $value ) : ?> selected="selected"<?php endif; ?>
								><?php echo $cat['categorie'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="cell small-2">
							<a class="btn btn_renew" data-article_id="<?php echo $article_id ?>" data-champ="<?php echo $champ ?>">Recup <?php echo $champ ?></a>
						</div>
					</div>
					
					<?php
					$champ = "motcle";
					$value = $infoArticle[$champ];
					$value = str_replace(array("\r", "\n"), '', $value);
					?>
					<div class="grid-x grid-margin-x">
						<div class="cell small-2">
							<label for="lbl_<?php echo $champ ?>"><?php echo $champ ?></label>
						</div>
						<div class="cell small-8">
							<input type="text" name="<?php echo $champ ?>" id="lbl_<?php echo $champ ?>" value="<?php echo $value ?>">
						</div>
						<div class="cell small-2">
							<a class="btn btn_renew" data-article_id="<?php echo $article_id ?>" data-champ="<?php echo $champ ?>">Recup <?php echo $champ ?></a>
						</div>
					</div>
					
					<?php
					$champ = "meta_title";
					$value = $infoArticle[$champ];
					?>
					<div class="grid-x grid-margin-x">
						<div class="cell small-2">
							<label for="lbl_<?php echo $champ ?>"><?php echo $champ ?> (<?php echo strlen($value) ?> car.)</label>
						</div>
						<div class="cell small-8">
							<input type="text" name="<?php echo $champ ?>" id="lbl_<?php echo $champ ?>" value="<?php echo $value ?>">
						</div>
						<div class="cell small-2">
							<a class="btn btn_renew" data-article_id="<?php echo $article_id ?>" data-champ="<?php echo $champ ?>">Recup <?php echo $champ ?></a>
						</div>
					</div>
					
					<?php
					$champ = "meta_description";
					$height = "70";
					$value = $infoArticle[$champ];
					?>
					<div class="grid-x grid-margin-x">
						<div class="cell small-2">
							<label for="lbl_<?php echo $champ ?>"><?php echo $champ ?> (<?php echo strlen($value) ?> car.)</label>
							
							
						</div>
						<div class="cell small-8">
							<textarea style="height:<?php echo $height ?>px" name="<?php echo $champ ?>" id="lbl_<?php echo $champ ?>"><?php echo $value ?></textarea>
						</div>
						<div class="cell small-2">
							<a class="btn btn_renew" data-article_id="<?php echo $article_id ?>" data-champ="<?php echo $champ ?>">Recup <?php echo $champ ?></a>
						</div>
					</div>
					
					<?php
					$champ = "phrase";
					$height = "90";
					$value = $infoArticle[$champ];
					?>
					<div class="grid-x grid-margin-x">
						<div class="cell small-2">
							<label for="lbl_<?php echo $champ ?>"><?php echo $champ ?> (<?php echo strlen($value) ?> car.)</label>
						</div>
						<div class="cell small-8">
							<textarea style="height:<?php echo $height ?>px" name="<?php echo $champ ?>" id="lbl_<?php echo $champ ?>"><?php echo $value ?></textarea>
							
						</div>
						<div class="cell small-2">
							<a class="btn btn_renew" data-article_id="<?php echo $article_id ?>" data-champ="<?php echo $champ ?>">Recup <?php echo $champ ?></a>
						</div>
					</div>
					
					<?php
					$champ = "chapeau";
					$height = "140";
					$value = $infoArticle[$champ];
					?>
					<div class="grid-x grid-margin-x">
						<div class="cell small-2">
							<label for="lbl_<?php echo $champ ?>"><?php echo $champ ?> (<?php echo strlen($value) ?> car.)</label>
						</div>
						<div class="cell small-8">
							<textarea style="height:<?php echo $height ?>px" name="<?php echo $champ ?>" id="lbl_<?php echo $champ ?>"><?php echo $value ?></textarea>
						</div>
						<div class="cell small-2">
							<a class="btn btn_renew" data-article_id="<?php echo $article_id ?>" data-champ="<?php echo $champ ?>">Recup <?php echo $champ ?></a>
						</div>
					</div>
					
					<?php
					/*
					$champ = "article";
					$height = "500";
					$value = $infoArticle[$champ];
					?>
					<div class="grid-x grid-margin-x">
						<div class="cell small-2">
							<label for="lbl_<?php echo $champ ?>"><?php echo $champ ?> (<?php echo str_word_count($value) ?> mots)</label>
							<br>
							<a class="btn" href="/admin/articleNettoyer/<?php echo $article_id ?>">Nettoyer article</a>
						</div>
						<div class="cell small-8">
							<textarea style="height:<?php echo $height ?>px" name="<?php echo $champ ?>" id="lbl_<?php echo $champ ?>"><?php echo $value ?></textarea>
							
						</div>
					</div>
					<?php
					*/
					?>
					<hr>
					
					<?php
					$champ = "article";
					$height = "800";
					$value = $infoArticle['article'];
					?>
					<div class="grid-x grid-margin-x">
						<div class="cell small-2">
							<label for="lbl_<?php echo $champ ?>"><?php echo $champ ?> (<?php echo str_word_count($value) ?> mots)</label>
							<br>
							<a class="btn" href="/admin/articleNettoyer/<?php echo $article_id ?>">Nettoyer article</a>
						</div>
						<div class="cell small-8">
							<textarea name="<?php echo $champ ?>" id="lbl_<?php echo $champ ?>" style="height:<?php echo $height ?>px;"><?php echo $value ?></textarea>
					
							<script>
							$(function() {
								CKEDITOR.replace('lbl_<?php echo $champ ?>', {
									height: '<?php echo $height ?>px',
									toolbar : [
										{ name: 'basicstyles', items: [ 'Bold', 'Italic' ] },
										/*{ name: 'styles', items: [ 'Format' ] },*/
										{ name: 'styles', items: [ 'Outdent', 'Indent' ] }, 
										{ name: 'paragraph', items: ['BulletedList'] },
										{ name: 'aligns', items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
										{ name: 'links', items: [ 'Link', 'Unlink' ] },
										{ name: 'insert', items: [ 'SpecialChar'] },
										/*{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },*/
										{ name: 'basicstyles', items: [ 'RemoveFormat' ] },
										{ name: 'document', items: [ 'Source' ] }
									]	
								} );
								
								
								CKEDITOR.config.allowedContent = true;
								CKEDITOR.config.fillEmptyBlocks = false;					
								
								
								
							} );
							</script>
						</div>
					</div>
					
					
					
					<div class="text-right">
					<button type="submit" class="btn"><i class="fa fa-check"></i>Enregistrer</button>
					</div>
				
					
				</form>	
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<script>
$(function() {
	$(".btn_renew").click(function() {
		
		
		var champ = $(this).attr("data-champ");
		var article_id = $(this).attr("data-article_id");
		
		$('#lbl_'+champ).val("Merci de patienter");
		var myurl = '/ajax.php?action=set_article_champ&champ='+champ+'&article_id='+article_id;
		
		//alert(myurl);
		
		$.ajax({
			url:myurl,
			error: function (xhr, status, error) {alert(status+' : '+error);},				
			success: function(data) {
				contenu = $("<div/>").html( data ).text();
				//contenu = data;
				//alert(data);
				
				$('#lbl_'+champ).val(contenu);
			}
		});

	});
	

});

</script>
		
	


