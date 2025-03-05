<?php $this->LastMessage->display(); ?>


<div class="bloc">
	<div class="bloc_titre">
		<h1><span><?php echo $h1 ?></span></h1>
	</div>
	<div class="bloc_content">
		<form action="/" method="post">
			<?php $this->Connexion->displayTokenField();?>
			<input type="hidden" name="path_info" value="/admin/articleDoGenerer">
			
			
			<div class="grid-x grid-margin-x">
				<div class="cell small-4 text-right">
					<label for="lbl_idee" class="middle">Idée H1 de l'article</label>
				</div>
				<div class="cell small-8 text-left">
					<input type="text" name="idee" id="lbl_idee" value="">
				</div>
			</div>
		
			<div class="grid-x grid-margin-x">
				<div class="cell small-4 text-right">
					<label for="lbl_categorie" class="middle">Catégorie</label>
				</div>
				<div class="cell small-8 text-left">
					<select name="categorie_id" id="lbl_categorie" style="max-width:400px;">
						<option value="0">Merci de choisir</option>
						<?php foreach ( $infoCategorie as $mr ) : ?>
							<option value="<?php echo $mr['categorie_id'] ?>"><?php echo $mr['categorie'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="text-right">
				<button type="submit" class="btn"><i class="fa fa-check"></i>Générer</button>
			</div>
		</form>
	</div>
</div>

					
					

