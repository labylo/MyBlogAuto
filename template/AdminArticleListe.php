<div class="bloc">
	<div class="bloc_titre">
		<div class="grid-x grid-margin-x">
			<div class="cell small-6">
				<h1><?php echo $h1 ?></h1>
			</div>
			<div class="cell small-6">
			&nbsp;
			</div>
		</div>
		<div class="bloc_content">		
			<?php if ( ! $infoArticle ) : ?>
				<div class="text-center">
				Désolé, mais aucun article fiche ne correspond à votre demande.<br>
				Merci de renouveler votre recherche en choisissant d'autres mot-clés.
				</div>
			<?php else: ?>

				<table>
					<tr>
					<th>Titre H1</th>
					<th>Infos</th>
					<th>Cout</th>
					<th>&nbsp;</th>
					</tr>
				<?php foreach($infoArticle as $champ=>$mr) : ?>
					
					<?php
					$words = str_word_count($mr['article']);
					$token = ($mr['token']/1000);
					$cout = round($token * OPENAI_PRICE, 3);
					?>
					<tr>
					<td>
						<b><?php echo $this->FancyDate->affiche_dh($mr['date_creation']) ?></b><br>
						<?php echo $mr['h1'] ?><br><b><?php echo $this->FancyUtil->getNbLettre($mr['h1']) ?> lettres | <?php echo $this->FancyUtil->getNbMot($mr['h1']) ?> mots</b><br>
						<?php if ( $mr['prompt']=="manuel" ) : ?>
						<span style="color:blue">(Ecrit manuellement)</span>
						<?php endif; ?>
					
					</td>
					<td>
						<?php
						$meta_title_nb_car = $this->FancyUtil->getNbLettre($mr['meta_title']);
						if ( $meta_title_nb_car > 60 ) {
							$meta_title_nb_car = '<span style="color:red">'.$meta_title_nb_car.' lettres</span>';
						}else{
							$meta_title_nb_car = $meta_title_nb_car.' lettres';
						}
						?>
						meta_title : <b><?php echo $meta_title_nb_car ?> | <?php echo $this->FancyUtil->getNbMot($mr['meta_title']) ?> mots</b><br>
						meta_description : <b><?php echo $this->FancyUtil->getNbLettre($mr['meta_description']) ?> | <?php echo $this->FancyUtil->getNbMot($mr['meta_description']) ?> mots</b><br>
						phrase : <b><?php echo $this->FancyUtil->getNbLettre($mr['phrase']) ?> lettres | <?php echo $this->FancyUtil->getNbMot($mr['phrase']) ?> mots</b><br>
						chapeau : <b><?php echo $this->FancyUtil->getNbLettre($mr['chapeau']) ?> lettres | <?php echo $this->FancyUtil->getNbMot($mr['chapeau']) ?> mots</b><br>
						article : <b><?php echo $this->FancyUtil->getNbLettre($mr['article']) ?> lettres | <?php echo $this->FancyUtil->getNbMot($mr['article']) ?> mots</b><br>
					</td>
					<td><?php echo $cout ?> $</td>
					<td><a href="/admin/articleModifier/<?php echo $mr['article_id'] ?>" class="btn">MODIFIER</a></td>
					</tr>
				<?php endforeach; ?>
				</table>
					
				
			<?php endif; ?>
		</div>
	</div>
</div>
