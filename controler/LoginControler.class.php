<?php
class LoginControler extends ApplicationControler {

	public function indexAction() {
		
		$this->meta_canon = "/login";
		$this->meta_robot = "noindex";
		
		$this->render_template = "page";
		$this->h1 = "Connexion";
		$this->meta_title = "Connexion";
		
	}
	
	public function doLoginAction() {
		$email = $this->Recuperateur->get('email');
		$password = $this->Recuperateur->get('password');
		$referer = $this->Recuperateur->get('referer');

		$infoUser = $this->UtilisateurSQL->getInfoFromEmail($email);

		if ( ! $infoUser ) {
			$this->LastMessage->setLastError("Impossible de vous identifier, merci de vérifier que vos identifiants ont été correctement saisis.");
			$this->redirect("/login");
			exit;
		}
		
		$utilisateur_id = $infoUser['utilisateur_id'];

		$connexion_ok = $this->UtilisateurSQL->verifConnexionPwd($utilisateur_id, $password);
		

		if ( ! $connexion_ok ) {
			$this->LastMessage->setLastError("Impossible de vous identifier, merci de vérifier que vos identifiants ont été correctement saisis.");
			$this->redirect("/login");
			exit;
		}
	
	
		//Si le user n'a pas activé son compte via EMAIL :
		if ( ! $infoUser['valid'] ) {
			$this->LastMessage->setLastError("Vous devez d'abord activer votre compte en indiquant le code que vous avez reçu par mail.");
			$this->redirect("/utilisateur/activation/".$utilisateur_id);
			exit;
		}

		$adip = $this->FancyUtil->getIp();
		$this->UtilisateurSQL->setDerniereConnexion($utilisateur_id, $adip);
		
		$tab = array(
			"connected" => true,
			"pseudo" => $infoUser['pseudo'],
			"utilisateur_id" => $infoUser['utilisateur_id'],
			"secure" => $infoUser['secure'],
			"email" => $infoUser['email'],
			"droit" => $infoUser['droit'],
		);	
		
		$this->Connexion->setConnexion($tab);
		$this->LastMessage->setLastMessage("Vous êtes connecté à votre compte sur ".SITE_NAME.".", 1, 1, 1);
		
		if ( strpos($referer, "/article/actu/") !== false ) {
			$referer = str_replace(SITE_URL, '', $referer);
			$this->redirect($referer);
			exit;
		}

		$this->redirect();
		exit;
		
	}
	
	
	public function logoutAction() {
		$this->Connexion->deconnexion();
		$this->LastMessage->setLastMessage("Vous avez été correctement déconnecté.", 1, 0, 0);
		$this->redirect("/login");
		exit;
	}
	
	
}
