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
	<div class="cell large-8 medium-7 small-12">
		
		<?php $this->LastMessage->display(); ?>

		<div class="bloc">
			<div class="bloc_titre">
				<h1><span><?php echo $h1 ?></span></h1>
			</div>
			<div class="bloc_content text-center">
				
				<div class="box_msg box_info">
				Merci de compléter le formulaire ci-dessous afin de créer un compte gratuit sur <?php echo SITE_NAME ?>
				</div>
				
				<form action="/" method="post">
					<?php $this->Connexion->displayTokenField();?>
					<input type="hidden" name="path_info" value="/utilisateur/doInscription">
					
					<?php
					$col_gauche = 5;
					$champ_lib = "Pseudo (surnom)";
					$champ_name = "pseudo";
					$champ_css = "w50pc";
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
					$champ_lib = "Adresse email";
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
					$champ_lib = "Mot de passe";
					$champ_name = "password";
					$champ_css = "w50pc";
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
								<input class="<?php echo $champ_css ?>" type="password" name="<?php echo $champ_name ?>" id="lbl_<?php echo $champ_name ?>" value="<?php echo $champ_value ?>">
								<p class="discret text-left">Au moins 8 caractères<p>
							</div>
						</div>

					<?php if ( $this->Form->getErr($champ_name)  ) : ?>
					</div>
					<?php endif; ?>
					
				
					<?php
					$col_gauche = 5;
					$champ_lib = "Confirmer mot de passe";
					$champ_name = "password2";
					$champ_css = "w50pc";
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
								<input class="<?php echo $champ_css ?>" type="password" name="<?php echo $champ_name ?>" id="lbl_<?php echo $champ_name ?>" value="<?php echo $champ_value ?>">
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
								<div class="cell large-<?php echo (12-$col_gauche) ?> small-12">
									<input class="<?php echo $champ_css ?>" type="text" name="<?php echo $champ_name ?>" id="lbl_<?php echo $champ_name ?>">
									<p class="discret text-left">Ecrire le résultat en chiffre.<p>
									<input type="hidden" name="captcha_resultat" value="<?php echo $tab_captcha['resultat'] ?>">
								</div>
							</div>
					
						<?php if ( $this->Form->getErr($champ_name)  ) : ?>
						</div>
						<?php endif; ?>

					<div class="text-right">
						<button type="submit" class="btn"><i class="fa-solid fa-circle-arrow-right"></i>Créer le compte</button>
					</div>

				</form>	
				
			
			</div>
		</div>
	</div>
	<div class="cell large-4 medium-5 small-12">
		<?php if ( COMMENTAIRE_USER ) : ?>
			<div class="bloc">
				<div class="bloc_titre">
					<h2><span>Déjà inscrit ?</span></h2>
				</div>
				<div class="bloc_content text-center">
					Vous avez déjà un compte sur <?php echo SITE_NAME ?> ?
					<br><br>
					Connectez-vous !
					<br><br>
					<a class="btn" href="/login"><i class="fa-solid fa-arrow-right-to-bracket"></i>S'identifier</a>
					<br><br>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
$this->Form->setReset();
?>
