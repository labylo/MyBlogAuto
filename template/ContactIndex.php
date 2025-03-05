<?php
function simple_captcha(){

	$tchiffres = array(0,1,2,3,4,5,6,7,8,9,10);
	$tlettres = array("zéro","un","deux","trois","quatre","cinq","six","sept","huit","neuf","dix");
	
	$premier = rand(0, count($tchiffres)-1);
	$second = rand(0, count($tchiffres)-1);
	$choixsigne = rand(0,1);
	
	$chiffre_enplus = 789; //LBI c'est moi qui met ca histoire de personnaliser le MD5
	
	if( $second<=$premier && $choixsigne==1 ) {
		$resultat=md5($tchiffres[$premier]-$tchiffres[$second]+$chiffre_enplus);
		$operation="Combien font ".$tlettres[$premier]." retranché de ".$tlettres[$second]." ?";
	}else if( $second<=$premier && $choixsigne==0 ){
		$resultat=md5($tchiffres[$premier]-$tchiffres[$second]+$chiffre_enplus);
		$operation="Combien font ".$tlettres[$premier]." moins ".$tlettres[$second]." ?";
	}else if ( $second>$premier && $choixsigne==1 )	{
		$resultat=md5($tchiffres[$premier]+$tchiffres[$second]+$chiffre_enplus);
		$operation="Combien font ".$tlettres[$premier]." ajouté à ".$tlettres[$second]." ?";
	}else{
		$resultat=md5($tchiffres[$premier]+$tchiffres[$second]+$chiffre_enplus);
		$operation="Combien font ".$tlettres[$premier]." plus ".$tlettres[$second]." ?";
	}
	
	
	$question="";
	foreach (str_split(utf8_decode($operation)) as $obj) {
		$question .= "&#".ord($obj).";";
	}
		
	return array("question"=>$question, "resultat"=>$resultat);
}
?>
<div class="grid-x grid-margin-x">
	<div class="cell large-8 medium-8 small-12">

		<div class="bloc">
			<div class="bloc_titre">
				<h1><span><?php echo $this->h1 ?></span></h1>
			</div>
			<div class="bloc_content">
			<?php $this->LastMessage->display(); ?>

			<?php
			$sujet_temp = "";
			$message_temp = "";
			?>

			<?php if ( MAINTENANCE_MAIL ) : ?>
				<div style="border:1px solid #FED444;background:#FEF9E8;padding:20px;">
					<b><?php echo SITE_NAME ?> rencontre actuellement des soucis avec son service de MAIL.</b>
					<br/><br/>
					Toutes vos demandes adressées par EMAIL depuis le <?php echo MAINTENANCE_MAIL ?> ne nous parviennent plus (et nous ne parviendront pas).
					<br/><br/>
					Il est également possible que vous ne receviez plus les alertes MAIL habituelles provenant de <?php echo SITE_NAME ?>.
					<br/><br/>
					Si votre demande n'est pas urgente, nous vous invitons à attendre que le service soit à nouveau disponible.
					<br/><br/>
					En cas d'urgence, merci de nous contacter sur l'adresse ci-dessous :<br>
					<b>myblogauto24 AT gmail.com</b><br>
					- Remplacer "AT" par "@"<br>
					- Merci d'indiquer dans le sujet de votre mail, le nom du site : <?php echo SITE_NAME ?>.
					<br/><br/>
					Nous espérons que le service de MAIL soit à nouveau opérationnel prochainement.
					<br><br/>
					Veuillez accepter nos excuses pour la gêne occasionée.
				</div>
			<?php else: ?>
					
			
				<div class="box_msg box_focus">
				Vous souhaitez contacter l'équipe de <?php echo SITE_NAME ?>, nous parler d'un article en particulier ou nous proposer la publication d'un nouveau contenu ?
				<br>
				Vous souhaitez nous présenter une nouveau produit/service en rapport avec le mondre de l'automobile ou tout simplement nous faire part d'une remarque ou d'une demande de renseignement ?
				<br>
				Merci de remplir le formulaire ci-dessous et nous répondrons à votre demande le plus rapidement possible !
				</div>

				<form action="/" method="post">
					<?php $this->Connexion->displayTokenField();?>
					<input type="hidden" name="path_info" value="/contact/doContacter">

					<?php
					$col_gauche = 5;
					$champ_lib = "Votre email";
					$champ_name = "email";
					$champ_css = "w100pc";
					$oblig = true;
					
					$champ_value = $this->Form->getVal($champ_name);
					$champ_value = htmlentities($champ_value, ENT_QUOTES, 'UTF-8');
					?>

					<?php if ( $this->Form->getErr($champ_name)  ) : ?>
					<div class="form_error">
						<p class="text-rouge"><?php echo $this->Form->getErr($champ_name) ?></p>
					<?php endif; ?>

						<div class="grid-x grid-margin-x">
							<div class="cell large-<?php echo $col_gauche ?> small-12 text-right"><label for="lbl_<?php echo $champ_name ?>"><?php echo $champ_lib ?>
							<?php if ( $oblig ) : ?><span class="obligatoire">*</span><?php endif; ?></label></div>
							<div class="cell large-<?php echo (12-$col_gauche) ?> small-10">
								<input class="<?php echo $champ_css ?>" type="text" name="<?php echo $champ_name ?>" id="lbl_<?php echo $champ_name ?>" value="<?php echo $champ_value ?>">
							</div>
						</div>

					<?php if ( $this->Form->getErr($champ_name)  ) : ?>
					</div>
					<?php endif; ?>

		

			<?php
			$col_gauche = 5;
			$champ_lib = "Sujet de votre message";
			$champ_name = "sujet";
			$champ_css = "w100pc";
			$oblig = true;

			$champ_value = $this->Form->getVal($champ_name);
			$champ_value = htmlentities($champ_value, ENT_QUOTES, 'UTF-8');
			
			?>

			<?php if ( $this->Form->getErr($champ_name)  ) : ?>
			<div class="form_error">
				<p class="text-rouge"><?php echo $this->Form->getErr($champ_name) ?></p>
			<?php endif; ?>

				<div class="grid-x grid-margin-x">
					<div class="cell large-<?php echo $col_gauche ?> small-12 text-right"><label for="lbl_<?php echo $champ_name ?>"><?php echo $champ_lib ?>
					<?php if ( $oblig ) : ?><span class="obligatoire">*</span><?php endif; ?></label></div>
					<div class="cell large-<?php echo (12-$col_gauche) ?> small-10">
						<input class="<?php echo $champ_css ?>" type="text" name="<?php echo $champ_name ?>" id="lbl_<?php echo $champ_name ?>" value="<?php echo $champ_value ?>">
					</div>
				</div>

			<?php if ( $this->Form->getErr($champ_name)  ) : ?>
			</div>
			<?php endif; ?>
					

			<?php
			$col_gauche = 5;
			$champ_lib = "Message";
			$champ_name = "message";
			$champ_css = "w100pc";
			$champ_value = $this->Form->getVal($champ_name);
			$oblig = true;
			?>

			<?php if ( $this->Form->getErr($champ_name)  ) : ?>
			<div class="form_error">
				<p class="text-rouge"><?php echo $this->Form->getErr($champ_name) ?></p>
			<?php endif; ?>

				<div class="grid-x grid-margin-x">
					<div class="cell large-<?php echo $col_gauche ?> small-12 text-right"><label for="lbl_<?php echo $champ_name ?>"><?php echo $champ_lib ?>
					<?php if ( $oblig ) : ?><span class="obligatoire">*</span><?php endif; ?></label></div>
					<div class="cell large-<?php echo (12-$col_gauche) ?> small-10">
						<textarea onkeyup="countChar(this)" rows="8" name="<?php echo $champ_name ?>" id="lbl_<?php echo $champ_name ?>"><?php echo $champ_value ?></textarea>
					</div>
				</div>

			<?php if ( $this->Form->getErr($champ_name)  ) : ?>
			</div>
			<?php endif; ?>
					
					
					
			<?php
			$col_gauche = 5;
			$tab_captcha = simple_captcha();
			$champ_lib = $tab_captcha['question'];
			$champ_name = "captcha_reponse";
			$champ_css = "w25pc";
			$oblig = true;			
			?>
			<?php if ( $this->Form->getErr($champ_name)  ) : ?>
			<div class="form_error">
				<p class="text-rouge"><?php echo $this->Form->getErr($champ_name) ?></p>
			<?php endif; ?>
					<div class="grid-x grid-margin-x">
						<div class="cell large-<?php echo $col_gauche ?> small-12 text-right">
						<label for="lbl_<?php echo $champ_name ?>"><?php echo $champ_lib ?>
						<?php if ( $oblig ) : ?><span class="obligatoire">*</span><?php endif; ?></label></div>
						<div class="cell large-<?php echo (12-$col_gauche) ?> small-10">
							<input class="<?php echo $champ_css ?>" type="text" name="<?php echo $champ_name ?>" id="lbl_<?php echo $champ_name ?>">
							<span class="discret">Ecrire le résultat en chiffre.<span>
							<input type="hidden" name="captcha_resultat" value="<?php echo $tab_captcha['resultat'] ?>">
						</div>
					</div>
			
				<?php if ( $this->Form->getErr($champ_name)  ) : ?>
				</div>
				<?php endif; ?>
		

							<br>
							<div class="grid-x grid-margin-x">
								<div class="cell large-6 small-12">
									<span class="txt_rouge">*</span> Champs obligatoires.
								</div>
								<div class="cell large-6 small-12 text-right">
									<button class="btn" type="submit"><i class="fa-solid fa-circle-arrow-right"></i>Envoyer le message</button>
								</div>
							</div>
						</form>
		

				<?php endif; ?>


			</div>
		</div>

	</div>
	<div class="cell large-4 medium-4 small-12">
		<?php
		$this->ArticleControler->RenderArticleLesPlusLus(4);
		?>
	</div>
</div>

<?php
$this->Form->setReset();
?>
