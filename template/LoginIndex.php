<div class="grid-x grid-margin-x">
	<div class="cell large-8 medium-7 small-12">
		
		<?php $this->LastMessage->display(); ?>

		<div class="bloc">
			<div class="bloc_titre">
				<h1><span><i class="fa fa-unlock"></i><?php echo $h1 ?></span></h1>
			</div>
			<div class="bloc_content text-center">
				
				<form action="/" method="post">
					<?php $this->Connexion->displayTokenField();?>
					<input type="hidden" name="path_info" value="/login/doLogin">
					<input type="hidden" name="referer" value="<?php echo $_SERVER['HTTP_REFERER'] ?>">

					<div class="grid-x grid-margin-x">
						<div class="cell small-6 text-right">
							<label for="lbl_email" class="middle">Email</label>
						</div>
						<div class="cell small-6">
							<input type="text" name="email" id="lbl_email" value="">
						</div>
					</div>
			
					<div class="grid-x grid-margin-x">
						<div class="cell small-6 text-right">
							<label for="lbl_password"class="middle">Mot de passe</label>
						</div>
						<div class="cell small-6">
							<input type="password" name="password" id="lbl_password" value="">
						</div>
					</div>
	
					<div class="text-right">
						<button type="submit" class="btn"><i class="fa fa-check"></i>Connexion</button>
					</div>

				</form>	
				
				
				
			</div>
		</div>
	</div>
	<div class="cell large-4 medium-5 small-12">
		<?php if ( COMMENTAIRE_USER ) : ?>
			<div class="bloc">
				<div class="bloc_titre">
					<h2><span>Inscription</span></h2>
				</div>
				<div class="bloc_content text-center">
					Pas encore inscrit ?
					<br>
					Créez votre compte gratuit maintenant : 
					<br><br>
					<a class="btn" href="/utilisateur/inscription"><i class="fa-solid fa-circle-arrow-right"></i>Créer un compte gratuit</a>
					<br><br>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
