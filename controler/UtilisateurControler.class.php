<?php
class UtilisateurControler extends ApplicationControler {

	/*
	public function indexAction() {
		$this->render_template = "page";
		$this->h1 = "Connexion";
		$this->meta_title = "Connexion";
	}
	*/
	
	public function doActivationAction() {
		$tentative_max = 4;
		
		$utilisateur_id = $this->Recuperateur->getInt('utilisateur_id');
		$code = $this->Recuperateur->get('code');
		
		if ( ! $utilisateur_id || ! is_numeric($utilisateur_id) || ! $code ) {
			$this->redirect();
			exit;
		}
		
		$infoUser = $this->UtilisateurSQL->getInfoFromId($utilisateur_id);
		if ( ! $infoUser ) {
			$this->redirect();
			exit;
		}
		
		$info = $this->UtilisateurSQL->getInfoFromActivationCode($utilisateur_id, $code);
		
		if ( ! $info ) {
			
			$code_nb = $infoUser['code_nb'];
			$tentative_restante = $tentative_max - $code_nb;
			
			if ( $tentative_restante < 1 ) {
				//on regarde que c'est le meme jour
				$is_today = $this->UtilisateurSQL->getTentativeToday($utilisateur_id);
				if ( $is_today ) {
					$this->LastMessage->setLastError("L'activation de votre compte a été bloquée durant 24H suite à un trop grand nombre d'échecs.", true);
					$this->redirect("/utilisateur/activation/".$utilisateur_id);
					exit;
				}else{
					//On re-initialise le NB de tentatives
					$tentative_restante = $tentative_max;
					$this->UtilisateurSQL->initTentative($utilisateur_id);
				}
			}
			
			$this->UtilisateurSQL->setIncorrectCode($utilisateur_id);
			$this->LastMessage->setLastError("Nous sommes désolés mais le code d'activation indiqué semble incorrect !<br>".$tentative_restante." tentative(s) restante(s) avant blocage", true);
			$this->redirect("/utilisateur/activation/".$utilisateur_id);
			exit;
		}
		
		$this->UtilisateurSQL->activerCompte($utilisateur_id);
		$this->LastMessage->setLastMessage("Votre compte a été activé avec succès, vous pouvez à présent vous connecter !", true);
		$this->redirect("/login");
		exit;
	}
	
	public function codeResendAction() {
		$utilisateur_id = $this->Recuperateur->getInt('utilisateur_id');
		
		if ( ! $utilisateur_id || ! is_numeric($utilisateur_id) ) {
			$this->redirect();
			exit;
		}
		
		$infoUser = $this->UtilisateurSQL->getInfoFromId($utilisateur_id);
		
		if ( ! $infoUser ) {
			$this->redirect();
			exit;
		}
		
		if ( ! $infoUser['valid'] && $infoUser['code'] ) {
			$email = $infoUser['email'];
			$pseudo = $infoUser['pseudo'];
			$code = $infoUser['code'];
			$this->FancyMail->doSendInscriptionCode($pseudo, $email, $code);
		}
		
		$this->LastMessage->setLastMessage("Un email contenant votre code d'activation vous a été envoyé !", true);
		$this->redirect("/utilisateur/activation/".$utilisateur_id);
		exit;
		
	}
	
	public function inscriptionAction() {
		$this->render_template = "page";
		
		$this->h1 = "Créer un compte gratuit sur ".SITE_NAME;
		$this->meta_title = "Inscription sur ".SITE_NAME.". Création d'un compte gratuit afin de pouvoir commenter les articles et participer à la vie du blog.";
		
	}
	
	public function activationAction($utilisateur_id) {
		$this->render_template = "page";
		
		if ( ! $utilisateur_id || ! is_numeric($utilisateur_id) ) {
			$this->redirect();
			exit;
		}
		
		$infoUser = $this->UtilisateurSQL->getInfoFromId($utilisateur_id);
		
		if ( ! $infoUser ) {
			$this->redirect();
			exit;
		}
		
		if ( $infoUser['valid'] ) {
			$this->redirect("/login");
			exit;
		}
		
		$this->h1 = "Activation de votre compte sur ".SITE_NAME;
		$this->meta_title = $this->h1;
		$this->utilisateur_id = $utilisateur_id;
		
	}
	
	
	public function doInscriptionAction() {
		$err = false;
		$msg_liste = "";


		$pseudo = $this->Recuperateur->get('pseudo');
		$email = $this->Recuperateur->get('email');
		$password = $this->Recuperateur->get('password');
		$password2 = $this->Recuperateur->get('password2');
		$captcha_reponse = $this->Recuperateur->getInt('captcha_reponse');
		$captcha_resultat = $this->Recuperateur->get('captcha_resultat');
		

		//$accept_cgu = $this->Recuperateur->get('accept_cgu')?1:0;
		
		if ( ! $pseudo || strlen($pseudo) < 4 ) {
			$err = true;
			$msg = "Merci de choisir un pseudo valide";
			$msg_liste .= "<li>".$msg."</li>";
			$this->Form->setErr("pseudo", $msg);
		}else{
			$existePseudo = $this->UtilisateurSQL->existPseudo($pseudo);
			if ( $existePseudo ) {
				$err = true;
				$msg = "Le pseudo que vous avez choisi n'est pas disponible.";
				$msg_liste .= "<li>".$msg."</li>";
				$this->Form->setErr("pseudo", $msg);
			}
		}
		
		if ( $this->FancyUtil->getErreurPseudo($pseudo) ) {
			$err = true;
			$msg = "Votre pseudo contient des caractères interdits";
			$msg_liste .= "<li>".$msg."</li>";
			$this->Form->setErr("pseudo", $msg);
		}
		
		
		
		if ( ! filter_var($email,FILTER_VALIDATE_EMAIL) ) {
			$err = true;
			$msg = "Merci de nous communiquer une adresse Email valide !";
			$msg_liste .= "<li>".$msg."</li>";
			$this->Form->setErr("email", $msg);
		}else{
			$existeEmail = $this->UtilisateurSQL->existEmail($email);
			if ( $existeEmail ) {
				$err = true;
				$msg = "Cette adresse email appartient déjà à un utilisateur.";
				$msg_liste .= "<li>".$msg."</li>";
				$this->Form->setErr("email", $msg);
			}
		}
			
		if ( ! $password ) {
			$err = true;
			$msg = "Le mot de passe est obligatoire";
			$msg_liste .= "<li>".$msg."</li>";
			$this->Form->setErr("password", $msg);
		}elseif ( strlen($password)<8 ) {
			$err = true;
			$msg = "Votre mot de passe doit contenir au moins 8 caractères";
			$msg_liste .= "<li>".$msg."</li>";
			$this->Form->setErr("password", $msg);
		}
		
		
		
		if ( $password != $password2 ) {
			$err = true;
			$msg = "Les mots de passe ne correspondent pas.";
			$msg_liste .= "<li>".$msg."</li>";
			$this->Form->setErr("password2", $msg);
			$this->Form->setVal("password", $password);
		}	
		
		if (md5(htmlspecialchars($captcha_reponse+789)) != htmlspecialchars($captcha_resultat)) { 
			$err = true;
			$msg = "Le test de sécurité a échoué";
			$msg_liste .= "<li>".$msg."</li>";
			$this->Form->setErr("captcha_reponse", $msg);
		}
			
			

		if ( $err ) {
			$this->Form->setVal("pseudo", $pseudo);
			$this->Form->setVal("email", $email);
			$this->Form->setVal("password", $password);
			$this->Form->setVal("password2", $password2);

			
			/*
			$msg_deb = 'Le formulaire contient une ou plusieurs erreurs : <ul>';
			$msg_fin = '</ul>';
			$this->LastMessage->setLastError($msg_deb.$msg_liste.$msg_fin, true);
			*/
			$this->LastMessage->setLastError("Le formulaire contient une ou plusieurs erreurs", true);
			$this->redirect("/utilisateur/inscription");
			exit;
		}
		
		//tout est OK
		$code = rand(100000, 999999);
		$secure = $this->PasswordGenerator->getPassword(10);
		$crypted = password_hash($password, PASSWORD_DEFAULT);
		
		$tab = array(
			"pseudo"=>$pseudo,
			"password"=>$crypted,
			"email"=>$email,
			"pseudo"=>$pseudo,
			"adip"=>$this->FancyUtil->getIp(),
			"date_creation" => date(DATE_ISO),
			"code"=>$code,
			);
		
		$utilisateur_id = $this->UtilisateurSQL->create($tab, $secure);

		$this->FancyMail->doSendInscriptionCode($pseudo, $email, $code);
		
		//$this->LastMessage->setLastMessage("Votre message a été correctement envoyé, nous vous répondrons très bientôt !", 1, 0, 0);
		$this->redirect("/utilisateur/activation/".$utilisateur_id);
		exit;
	}
	
	
	
}
