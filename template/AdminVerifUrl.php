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
	</div>
	<div class="bloc_content">
		<?php foreach($infoArticle as $mr) : 
			$motcle = $mr['motcle'];
			$err = 0;
			$msg = "";
			
			if (strpos($motcle, " ") !== false) {
				$err++;
				$msg .= "-Espace détecté";
				$motcle = str_replace(' ', '<span style="color:red"><b>&nbsp;ESPACE&nbsp;</b></span>', $mr['motcle']);
			}
			if (strpos($motcle, "\r") !== false) {
				$err++;
				$msg .= "-Retour détecté";
			}
			if(strpos($motcle, "\n") !== FALSE) {
				$err++;
				$msg .= "-Retour détecté";
			}
			if(strpos($motcle, "(") !== FALSE || strpos($motcle, ")") !== FALSE) {
				$err++;
				$msg .= "-parenthèse détectée";
			}
			if(strlen($motcle)>50) {
				$err++;
				$msg .= "-Trop de caractères";
			}
			
			if ( $err ) : 
			?>
			<div style="padding:4px;margin-bottom:4px;border:1px solid red;">
			<?php
			echo '<span style="color:red">ERREUR article #'.$mr['article_id'].'</span><br>';
			echo nl2br($motcle)."&nbsp;&nbsp;(<span style='color:red'>".$msg."</span>)&nbsp;&nbsp;<a href='/admin/articleModifier/".$mr['article_id']."' class='btn'>MODIFIER</a><br>";
			?>
			</div>
			<?php
			endif; 
		endforeach;
		?>
	</div>
</div>

<div class="bloc">	
	<div class="bloc_titre">
		<div class="grid-x grid-margin-x">
			<div class="cell small-6">
				<h2>Liste des motclés</h2>
			</div>
			<div class="cell small-6">
			&nbsp;
			</div>
		</div>
	</div>
	<div class="bloc_content">
		<?php foreach($infoArticle as $mr) : ?>
			<div style="padding:4px;margin-bottom:4px">
			<?php
			$motcle = str_replace(' ', '<span style="color:red"><b>&nbsp;ESPACE&nbsp;</b></span>', $mr['motcle']);
			
			//$motcle = str_replace(array("\r", "\n"), '', $motcle);
			
			echo "<b>#".$mr['article_id']."</b> : ".nl2br($motcle)."&nbsp;&nbsp;<a href='/admin/articleModifier/".$mr['article_id']."'>MODIFIER</a><br>";
			?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
