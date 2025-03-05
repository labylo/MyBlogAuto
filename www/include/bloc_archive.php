<?php
$mois_deb = 3;
$annee_deb = 2023;

$mois_now = intval(date('m'));
$annee_now = intval(date('Y'));
?>
<div class="bloc">
	<div class="bloc_titre">
		<h2><span>Archives</span></h2>
	</div>
	<div class="bloc_content">
		<?php 
		for ($an = $annee_deb ; $an <= $annee_now ; $an++) {
			?>
			<h3 style="margin:5px 0 0 0">Articles <?php echo $an ?></h3>
			<?php
				$li = "";
				?>
				<ul class="liste_lien">
				<?php
				$mois_max = $mois_now;
				
				if ( $an == $annee_deb ) {
					$mois_min = $mois_deb;
					$mois_max = 12;
				}else{
					$mois_min = 1;
				}
				
				
				
				for ($j = $mois_min ; $j <= $mois_max ; $j++) {
					$li .= '<li><a href="/article/archive/'.$j.'/'.$an.'">'.$this->FancyDate->getMonthString($j)." ".$an."</a></li>";
				}
				echo $li;
				?>
				</ul>
			<?php
		}
		?>
	</div>
</div>