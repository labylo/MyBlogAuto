<?php $this->LastMessage->display(); ?>

<div class="bloc">
	<div class="bloc_titre">
		<div class="grid-x grid-margin-x">
			<div class="cell small-6">
				
				<h1><span><?php echo $h1 ?></span></h1>

			</div>
			<div class="cell small-6 text-right">

			</div>
		</div>
	</div>
			
	<div class="bloc_content">
		
					
		<form action="/" method="post">
			<?php $this->Connexion->displayTokenField();?>
			<input type="hidden" name="path_info" value="/admin/articleDoEcrire">
			<?php
			$col_gauche = 4;
			?>



			<?php
			$champ = "categorie_id";
			$libelle = "Catégorie";
			?>
			<div class="grid-x grid-margin-x">
				<div class="cell small-<?php echo $col_gauche ?> text-right">
					<label for="lbl_<?php echo $champ ?>" class="middle"><?php echo $libelle ?></label>
				</div>
				<div class="cell small-<?php echo 12-$col_gauche ?>">
					<select name="<?php echo $champ ?>">
					<?php foreach($infoCategorie as $mr) : ?>
						<option value="<?php echo $mr['categorie_id'] ?>"><?php echo $mr['categorie'] ?></option>
					<?php endforeach ?>
					</select>
				</div>
			</div>
			
			<?php
			$champ = "visible";
			$libelle = "Visible/publié";
			?>
			<div class="grid-x grid-margin-x">
				<div class="cell small-<?php echo $col_gauche ?> text-right">
					<label for="lbl_<?php echo $champ ?>" class="middle"><?php echo $libelle ?></label>
				</div>
				<div class="cell small-<?php echo 12-$col_gauche ?>">
					<select name="<?php echo $champ ?>">
						<option value="0">Non</option>
						<option value="1">Oui</option>
					</select>
				</div>
			</div>
			
			<?php
			$champ = "redacteur_id";
			$libelle = "Rédacteur";
			?>
			<div class="grid-x grid-margin-x">
				<div class="cell small-<?php echo $col_gauche ?> text-right">
					<label for="lbl_<?php echo $champ ?>" class="middle"><?php echo $libelle ?></label>
				</div>
				<div class="cell small-<?php echo 12-$col_gauche ?>">
					<select name="<?php echo $champ ?>">
					<?php foreach($infoRedacteur as $mr) : ?>
						<option value="<?php echo $mr['redacteur_id'] ?>"><?php echo $mr['prenom'] ?> <?php echo $mr['nom'] ?></option>
					<?php endforeach ?>
					</select>
				</div>
			</div>
			
			<?php
			$champ = "h1";
			$libelle = "H1";
			?>
			<div class="grid-x grid-margin-x">
				<div class="cell small-<?php echo $col_gauche ?> text-right">
					<label for="lbl_<?php echo $champ ?>" class="middle"><?php echo $libelle ?></label>
				</div>
				<div class="cell small-<?php echo 12-$col_gauche ?>">
					<input type="text" name="<?php echo $champ ?>">
				</div>
			</div>
			
			<?php
			$champ = "motcle";
			$libelle = "Mot-clé";
			?>
			<div class="grid-x grid-margin-x">
				<div class="cell small-<?php echo $col_gauche ?> text-right">
					<label for="lbl_<?php echo $champ ?>" class="middle"><?php echo $libelle ?></label>
				</div>
				<div class="cell small-<?php echo 12-$col_gauche ?>">
					<input type="text" name="<?php echo $champ ?>">
				</div>
			</div>
			
			
			<?php
			$champ = "meta_title";
			$libelle = "Meta TITLE";
			?>
			<div class="grid-x grid-margin-x">
				<div class="cell small-<?php echo $col_gauche ?> text-right">
					<label for="lbl_<?php echo $champ ?>" class="middle"><?php echo $libelle ?></label>
				</div>
				<div class="cell small-<?php echo 12-$col_gauche ?>">
					<input type="text" name="<?php echo $champ ?>">
				</div>
			</div>
			
			<?php
			$champ = "meta_description";
			$libelle = "Meta DESC";
			?>
			<div class="grid-x grid-margin-x">
				<div class="cell small-<?php echo $col_gauche ?> text-right">
					<label for="lbl_<?php echo $champ ?>" class="middle"><?php echo $libelle ?></label>
				</div>
				<div class="cell small-<?php echo 12-$col_gauche ?>">
					<input type="text" name="<?php echo $champ ?>">
				</div>
			</div>
			
			<?php
			$champ = "phrase";
			$libelle = "Phrase";
			$height = "100";
			?>
			<div class="grid-x grid-margin-x">
				<div class="cell small-<?php echo $col_gauche ?> text-right">
					<label for="lbl_<?php echo $champ ?>" class="middle"><?php echo $libelle ?></label>
				</div>
				<div class="cell small-<?php echo 12-$col_gauche ?>">
					<textarea name="<?php echo $champ ?>" id="lbl_<?php echo $champ ?>" style="height:<?php echo $height ?>px;"></textarea>
				</div>
			</div>
			
			<?php
			$champ = "chapeau";
			$libelle = "Chapeau";
			$height = "240";
			?>
			<div class="grid-x grid-margin-x">
				<div class="cell small-<?php echo $col_gauche ?> text-right">
					<label for="lbl_<?php echo $champ ?>" class="middle"><?php echo $libelle ?></label>
				</div>
				<div class="cell small-<?php echo 12-$col_gauche ?>">
					<textarea name="<?php echo $champ ?>" id="lbl_<?php echo $champ ?>" style="height:<?php echo $height ?>px;"></textarea>
				</div>
			</div>
			
			
			<?php
			$champ = "article";
			$libelle = "Article";
			$height = "800";
			?>
			<div class="grid-x grid-margin-x">
				<div class="cell small-<?php echo $col_gauche ?> text-right">
					<label for="lbl_<?php echo $champ ?>"><?php echo $libelle ?></label>
				</div>
				<div class="cell small-<?php echo 12-$col_gauche ?>">
					<textarea name="<?php echo $champ ?>" id="lbl_<?php echo $champ ?>" style="height:<?php echo $height ?>px;"></textarea>
			
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
			
			<br>
			<div class="grid-x grid-margin-x">
				<div class="cell small-12 text-right">
					<input class="btn" type="submit" value="Enregistrer">
				</div>
			</div>
		</form>
	</div>				
</div>