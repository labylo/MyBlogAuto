<?php $this->LastMessage->display(); ?>


<div class="bloc">
	<div class="bloc_titre">
		<h1 class="solo"><?php echo $h1 ?></h1>
	</div>
</div>

<?php foreach($infoCategorie as $mr) : ?>
	<?php
	$categorie_id = $mr['categorie_id'];
	$categorie = $mr['categorie'];
	?>
	<div class="bloc">
		
		<div class="bloc_titre">
			<div class="grid-x grid-margin-x">
				<div class="cell small-6">
					<h2><span><?php echo $categorie ?></span>&nbsp;<a href="/admin/ideeCategorie/<?php echo $categorie_id ?>">Gérer les idées</a></h2>
					
				
				</div>
				<div class="cell small-6 text-right">
					<a class="btn" href="/admin/ideeGenerer/<?php echo $mr['categorie_id'] ?>">Générer <?php echo NB_NOUVELLES_IDEES ?> Idées (pas top avec GPT 3.5)</a>
					<a class="btn btn_add_idee" data-categorie_id="<?php echo $mr['categorie_id'] ?>" data-theme="<?php echo $categorie ?>">Saisir une liste manuellement</a>
				</div>
			</div>
		</div>
	
		<div class="bloc_content">
			<?php 
			$infoIdee = $this->IdeeControler->getListeFromCategorieId($categorie_id);
			
			if ( ! $infoIdee ) : ?>
			
				<div class="box_msg box_info">
					Aucun idée dans la catégorie <b><?php echo $categorie ?>.</b>
				</div>
				
			<?php else: ?>
				<?php
				$nb = count($infoIdee);
				?>
				<table class="mini">
					<thead>
					<tr>
					<th>&nbsp;</th>
					<th>Ordre</th>
					<th>ID</th>
					<th>Idées (<?php echo $nb ?>)</th>
					</tr>
					</thead>
					<tbody id="table_sortable_idee_<?php echo $categorie_id ?>">
					<?php foreach($infoIdee as $idee) : ?>
						<tr id="<?php echo $idee['idee_id'] ?>">
						<td class="move_x"><i class="fa fa-bars"></i></td>
						<td><?php echo $idee['ordre'] ?></td>
						<td><?php echo $idee['idee_id'] ?></td>
						<td>
							<?php echo $idee['idee'] ?> &nbsp;&nbsp;|&nbsp;&nbsp;
							<a style="padding:1px 4px;font-size:90%;" class="btn" href="/admin/articleGenererFromIdee/<?php echo $idee['idee_id'] ?>">Générer cet article</a>
						</td>
						</tr>
					<?php endforeach;  ?>
					</body>
				</table>
			<?php endif; ?>
		</div>
	</div>
<?php endforeach; ?>


	
	
	

<?php
$popin_id = "for_idee_";
$col_gauche = 3;
?>
<div class="popin" id="<?php echo $popin_id ?>">
	<form action="/" method="post">
		<?php $this->Connexion->displayTokenField();?>
		<input type="hidden" name="path_info" value="/admin/ideeAjouter">
		<input type="hidden" name="categorie_id" id="<?php echo $popin_id ?>categorie_id" value="">
	
		
		<div class="titre"><span id="<?php echo $popin_id ?>span_titre">Ajouter une liste d'idées</span><i class="btn_close_popin fa fa-times-circle"></i></div>
		
		<div class="contenu">
			<div class="grid-x grid-margin-x">
				<div class="cell small-12">
			
					<div class="grid-x grid-margin-x">
						<div class="cell small-<?php echo $col_gauche ?> text-right">
							<label for="<?php echo $popin_id ?>liste" class="middle">Liste d'idées<br>(Se terminent par #)</label>
							
							<hr>
							<div class="text-left">
							<b>Prompt : </b><br>
							<p>
							Rédige une liste de 50 sujets d'article variés différents et originaux sans description se terminant par # et que tu pourrais rédiger sur le thème : <span id="<?php echo $popin_id ?>theme">THEME</span> (<?php echo MOTCLE_PRINCIPALE ?>)
							</p>
							</div>
						</div>
						<div class="cell small-<?php echo 12-$col_gauche ?>">
							<textarea type="text" name="liste" id="<?php echo $popin_id ?>liste" style="height:600px;"></textarea>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		
		<div class="close">
			<button type="submit" class="btn"><i class="fa fa-check"></i>Enregistrer</button>
			<a class="btn btn_gris btn_close_popin"><i class="fa fa-times-circle"></i>Annuler</a>
		</div>		
	</form>	
</div>

<script>
$(function() {
	
	var DIV_ID = "for_idee_";

	$(".btn_add_idee").click(function() {
	

		var categorie_id = $(this).attr("data-categorie_id");
		var theme = $(this).attr("data-theme");

		$('#'+DIV_ID+'categorie_id').val(categorie_id);
		$('#'+DIV_ID+'theme').html(theme);
		
		popinOpen(DIV_ID);
	});
	
	
	<?php foreach($infoCategorie as $mr) : ?>
	$("#table_sortable_idee_<?php echo $mr['categorie_id'] ?>").sortable({
		
		axis: 'y',
		cancel : '.not_dragable',
		handler: '.move_x',
		update: function() {
			var values = $(this).sortable('toArray').toString();
			
			var categorie_id = <?php echo $mr['categorie_id'] ?>;
			
			var myurl = '/ajax.php?action=admin_tri_idee&categorie_id='+categorie_id+'&values='+values;

			$.ajax({
				url:myurl,
				error: function (xhr, status, error) {
					alert(status+' : '+error);
				},				
				success: function(data) {
					//$("#toto").html(data);
					location.reload();
				}
			});
			
		}
	}).disableSelection();	
	<?php endforeach; ?>
});

</script>
	


