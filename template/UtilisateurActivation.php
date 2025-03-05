<div class="grid-x grid-margin-x">
	<div class="cell large-8 medium-7 small-12">
		
		

		<div class="bloc">
			<div class="bloc_titre">
				<h1><span><?php echo $h1 ?></span></h1>
			</div>
			<div class="bloc_content text-center">

				<div class="box_msg box_info text-left">
				Votre inscription a été correctement prise en compte.
				<br>
				Afin d'activer votre compte, veuillez indiquer le code d'activation que vous avez reçu par email.
				</div>
				<?php $this->LastMessage->display(); ?>
				<form action="/" method="post">
					<?php $this->Connexion->displayTokenField();?>
					<input type="hidden" name="path_info" value="/utilisateur/doActivation">
					<input type="hidden" name="utilisateur_id" value="<?php echo $utilisateur_id ?>">
					
					<label for="lbl_code" class="bold">Merci de saisir votre code d'activation</label>
					<input type="text" name="code" class="w25pc" id="lbl_code" style="margin:0 auto;">
					<button type="submit" class="btn"><i class="fa-solid fa-circle-check"></i>Activer mon compte</button>

				</form>

			</div>
		</div>
		<div class="bloc">
			<div class="bloc_titre">
				<h2><span>Vous n'avez pas reçu votre code ?</span></h2>
			</div>
			<div class="bloc_content text-center">
				Vous n'avez pas reçu votre code ?
				<br>
				Avez-vous pensé à vérifier votre dossier SPAM / Courrier indésirable ?
				<br>
				Sinon, vous pouvez à nouveau demander à recevoir votre code d'activation :
				<br>
				<form action="/" method="post">
					<?php $this->Connexion->displayTokenField();?>
					<input type="hidden" name="path_info" value="/utilisateur/codeResend">
					<input type="hidden" name="utilisateur_id" value="<?php echo $utilisateur_id ?>">
					
					
					<button type="submit" class="btn"><i class="fa-solid fa-circle-arrow-right"></i>Renvoyer le code</button>

				</form>
			</div>
		</div>

		
		
	</div>
	<div class="cell large-4 medium-5 small-12">
	&nbsp;
	</div>
</div>
