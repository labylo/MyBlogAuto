<?php
/*
Controle TOKEN : terminé
*/


class ContactControler extends ApplicationControler {

	public function indexAction() {
		$this->render_template = "page";
		
		$this->h1 = "Contacter ".SITE_NAME;
		$this->meta_canon = "/contact";
		$this->meta_title = $this->h1;
		$this->meta_description = "Pour toutes questions et pour toutes demandes, merci d'utiliser cette page pour contacter l'équipe de ". SITE_NAME;
		$this->ariane = array("Accueil"=>"/", "Contact"=>false);
	}
	
	
	public function doContacterAction() {
		$err = false;
		$msg_liste = "";


		$sujet = $this->Recuperateur->get('sujet');
		$message = $this->Recuperateur->get('message');
		$email = $this->Recuperateur->get('email');
		
		if ( ! $sujet ) {
			$err = true;
			$msg = "Merci de définir un sujet";
			$msg_liste .= "<li>".$msg."</li>";
			$this->Form->setErr("sujet", $msg);
		}
		
		if ( ! filter_var($email,FILTER_VALIDATE_EMAIL) ) {
			$err = true;
			$msg = "Merci de nous communiquer une adresse Email valide !";
			$msg_liste .= "<li>".$msg."</li>";
			$this->Form->setErr("email", $msg);
		}
			
		if ( strlen($message) < 10 ) {
			$err = true;
			$msg = "Votre message n'est pas assez explicite !";
			$msg_liste .= "<li>".$msg."</li>";
			$this->Form->setErr("message", $msg);
		}
		
		

	
		$captcha_reponse = $this->Recuperateur->getInt('captcha_reponse');
		$captcha_resultat = $this->Recuperateur->get('captcha_resultat');
	
		/*
		echo "captcha_reponse = ".$captcha_reponse."<hr>";
		echo "captcha_resultat = ".$captcha_resultat."<hr>";
		exit;
		*/
		
		if (md5(htmlspecialchars($captcha_reponse+789)) != htmlspecialchars($captcha_resultat)) { 
			$err = true;
			$msg = "Le test de sécurité a échoué";
			$msg_liste .= "<li>".$msg."</li>";
			$this->Form->setErr("captcha_reponse", $msg);
		}
			
			

		if ( $err ) {
			$this->Form->setVal("sujet", $sujet);
			$this->Form->setVal("email", $email);
			$this->Form->setVal("message", $message);

			
			$msg_deb = 'Le formulaire contient une ou plusieurs erreurs : <ul>';
			$msg_fin = '</ul>';
			$this->LastMessage->setLastError($msg_deb.$msg_liste.$msg_fin, true);
			$this->redirect("/contact/index");
			exit;
		}
		
		$this->sendMailContactSite($sujet, $message, $email);
		
		$this->LastMessage->setLastMessage("Votre message a été correctement envoyé, nous vous répondrons très bientôt !", 1, 0, 0);
		$this->redirect("/contact");
		exit;
	}
	
	

	private function sendMailContactSite($sujet, $message, $replyto_email) {
		$message = str_replace('\\','',$message);
		$this->FancyMail->doSendContactSite($replyto_email, $sujet, $message);

	}
	

}
