<div class="bloc">
<?php if ( ! $infoLogCron ) : ?>
<b>Aucun CRON</b><br>
<?php else: ?>

	<?php $this->LastMessage->display(); ?>

	<div class="grid-x grid-margin-x">
		<div class="cell small-6">
		<p>
		Heure serveur : <?php echo  $this->FancyDate->affiche_dhs(date(DATE_ISO)) ?>
		</p>
		</div>
		<div class="cell small-6 text-right">
			<a class="btn" href="/admin/doDeleteCronLog/1"><i class="fa fa-trash"></i>Supprimer sauf AUJOURD'HUI</a>
			&nbsp;
			<a class="btn btn_rouge" href="/admin/doDeleteCronLog/0"><i class="fa fa-trash"></i>Supprimer tous les LOG</a>
		</div>
	</div>


	<div class="bloc_titre">
	<h2><span>Log enregistrés - (Affichage MAX : <?php echo $limit ?>)</span></h2>
	</div>
	
	<div class="bloc_content">
	<table>
	<tr>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th>Infos</th>
		<th>Rapport</th>
		<th>&nbsp;</th>
		
	</tr>
	<?php foreach ( $infoLogCron as $mr ) : ?>
	<tr>
		<td><?php echo $mr['id_log_cron'] ?></td>
		<td><?php if ($mr['erreur']) echo "ERREUR" ?></td>
		<td>
			Date : <b><?php echo $this->FancyDate->affiche_dhs($mr['madate']) ?></b>
			<hr>
			Type : <b><?php echo $mr['type'] ?></b>
			<hr>
			Libelle : <b><?php echo $mr['lib'] ?></b>
		</td>
		<td>
			<div style="max-height:400px;overflow:auto;">
			<?php echo $mr['html'] ?>
			</div>
		</td>
		
		<td><br><a class="btn btn_rouge" href="/admin/supprimerCronLog/<?php echo $mr['id_log_cron'] ?>">Supprimer ce log</a></td>
	</tr>	
	<?php endforeach; ?>
	
	</table>
	</div>


<?php endif; ?>		
</div>
