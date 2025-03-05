<?php $this->LastMessage->display(); ?>
<div class="bloc">
		<div class="bloc_titre">
			<div class="grid-x grid-margin-x">
				<div class="cell small-6">
					<h1><span><?php echo $h1 ?></span></h1>
				</div>
				<div class="cell small-6 text-right">
					<a href="/sitemap.xml">Voir le sitemap.xml en ligne</a>
					&nbsp;&nbsp;|&nbsp;&nbsp;
					<a href="/seoSitemap/generer/<?php echo TOKEN ?>" class="btn">Générer XML</a>
				</div>
			</div>
		</div>
	
		<div class="bloc_content">
		<table>
			<tr>
				<th>Loc</th>
				<th>Lastmod</th>
				<th>Priority</th>
				<th>Changefreq</th>
			</tr>
			<?php foreach($infoSitemap as $k=>$value) : ?>
				<tr>
				<td>
					<?php 
					if ( ! $value['loc'] ) echo "--ACCUEIL--";
					else echo '<a href="'.$value['loc'].'">'.$value['loc'].'</a>' ;
					?>
				</td>
				<td><?php echo $value['lastmod'] ?></td>
				<td><?php echo $value['priority'] ?></td>
				<td><?php echo $value['changefreq'] ?></td>
				</tr>
			<?php endforeach; ?>
		</table>	

		
	</div>
</div>



