<div class="bloc">
	<div class="bloc_titre">
		<h2><span>Rechercher un article</span></h2>
	</div>
	<div class="bloc_content">
		<form action="/" method="post" class="form_recherche">
			<?php $this->Connexion->displayTokenField();?>
			<input type="hidden" name="path_info" value="/default/recherche">
			
			<div class="grid-x">
				<div class="cell small-10">
					<label for="lbl_motcle" class="visual_hidden">Chercher un article</label>
					<input type="text" name="motcle" id="lbl_motcle" placeholder="Chercher un article">
				</div>
				<div class="cell small-2">
					<button type="submit" class="btn" aria-label="Lancer la recherche"><i class="fa-solid fa-magnifying-glass"></i></button>
				</div>
			</div>
		</form>	
	</div>
</div>