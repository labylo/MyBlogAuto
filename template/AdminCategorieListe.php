<div class="grid-x grid-margin-x">
	<div class="cell small-12">
		<div class="bloc">
			<div class="bloc_titre">
				<h1><span><?php echo $h1 ?></span></h1>
			</div>
			<div class="bloc_content">
			
				<?php if ( ! $infoCategorie ) : ?>
					<div class="text-center">
					Désolé, mais il n'existe aucune catégorie
					</div>
				<?php else: ?>

					<table>
						<tr>
						<th>ID</th>
						<th>&nbsp;</th>
						<th>Libellé</th>
						<th>NB article</th>
						<th>NB idée</th>
						<th>Résumé</th>
						<th>Mot-clé</th>
						<th>Image mot-clé</th>
						<th>Meta Title</th>
						<th>Meta Desc</th>
						<th>&nbsp;</th>
						</tr>
					<?php foreach($infoCategorie as $champ=>$mr) : ?>
						
						<tr>
						<td><?php echo $mr['categorie_id'] ?></td>
						<td><i class="<?php echo $mr['fa_icone'] ?>" style="font-size:200%;"></i></td>
						<td><?php echo $mr['categorie'] ?></td>
						<td><?php echo $mr['article_nb'] ?></td>
						<td><?php echo $mr['idee_nb'] ?></td>
						<td><?php echo $mr['resume'] ?></td>
						<td><?php echo $mr['motcle'] ?></td>
						<td><?php echo $mr['image_motcle'] ?></td>
						<td><?php echo $mr['meta_title'] ?></td>
						<td><?php echo $mr['meta_description'] ?></td>
						<td>&nbsp;</td>
						</tr>
					<?php endforeach; ?>
					</table>
						
					
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

