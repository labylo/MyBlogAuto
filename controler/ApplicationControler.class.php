<?php
/*
Controle TOKEN : terminé
*/

set_time_limit(600);

class ApplicationControler extends Controler {
	
	public function getChatGpt($query, $model="gpt-3.5-turbo") {
		$ch = curl_init();

		$url = 'https://api.openai.com/v1/chat/completions';


		$post_fields = array(
			"model" => $model,
			"messages" => array(
				array(
					"role" => "user",
					"content" => $query
				)
			),
			"max_tokens" => 2000,
			"temperature" => 0.5
		);


		$header  = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . OPENAI_API_KEY
		];

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		$result = curl_exec($ch);
		
		if (curl_errno($ch)) {
			echo 'Error: ' . curl_error($ch);
		}
		curl_close($ch);

		$tab_reponse = json_decode($result);
			
		return $tab_reponse;
	}
	
	public function verifInt($integer) { // valeur integer obligatoire
		if (! is_numeric($integer) || empty($integer) || $integer < 0 ) {
			$this->redirect();
			exit;
		}
		return true;
	}
	
	public function verifToken($token, $redirect=true) {
		//if ( PRODUCTION && $token != TOKEN ) {	
		if ( !$token || $token != TOKEN ) {
			if ( $redirect ) {
				$this->redirect("");
				exit;
			}else{
				return false;
			}
		}
	}
	
	public function verifMembre() {
		if (  ! $this->Connexion->isConnected() ) {	
			$this->LastMessage->setLastError("Vous devez vous identifier pour accéder à cette page.", 1, 0, 1);
			$this->redirect("/login");
			exit;
		}
	}
	
	public function verifAdmin() {
		if ( ! $this->Connexion->isAdmin() || ! $this->Connexion->isConnected() ) {	
			$this->LastMessage->setLastError("Désolé mais vous devez être connecté sur l'application pour accéder à cette page.", 1, 0, 1);
			$this->redirect("/login");
			exit;
		}
	}
	
	/*
	public function verifAcces( $tab=0 ) {
		if ( ! $this->Connexion->isConnected() ) {	
			$this->LastMessage->setLastError("Désolé mais vous devez être connecté sur l'application pour accéder à cette page.", 1, 0, 1);
			$this->redirect("/login/");
			exit;
		
		}
		
		if ( $tab ) {
			$droit = $this->Connexion->getDroit();
		
			if ( ! in_array($droit, $tab)) {
				//$this->LastMessage->setLastError("Désolé mais vous n'avez pas les autorisations nécessaires pour accéder à cette page.", 1, 0, 1);
				$this->redirect("/static/droit");
				exit;
			}
			
		}
		
		return false;
	}
	*/
	
	
	
	
	public function _before($controler,$action,array $param){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			if ( ! $this->Connexion->verifToken() ){
				$this->redirect();
				exit;
			}
		}

		$defaultView = $controler.ucfirst($action);
		$this->selectView($defaultView);
	}
	

	public function _after($controler, $action, array $param){
		$this->renderDefault();
	}
	
	
	public function LogReqTropLongue($nb_req, $tps_exec, $actual_page, $log_cron="") {
		if ( $log_cron ) $adip="IP_SERVEUR";
		else $adip = $this->FancyUtil->getIp();
		$this->LogPageSQL->add($nb_req, $tps_exec, $actual_page, $adip, $log_cron);
	}
	
	
	public function renderDefault(){
		$template_milieu = $this->getSelectedView();

		if (! $this->Gabarit->getTemplatePath($template_milieu)){
			header("HTTP/1.0 404 Not Found");
			$this->h1 = SITE_NAME." : Page introuvable";
			$this->title = $this->h1;
			$template_milieu = "Default_404";
		} 

		$this->template_milieu = $template_milieu;
		
		if (! $this->isViewParameter("meta_title")) $this->meta_title = "TODO_META_TITLE";
		if (! $this->isViewParameter("meta_description")) $this->meta_description = "";
		
		$this->Gabarit->setParameters($this->getViewParameter());
		$this->Gabarit->infoCategorieAccueil = $this->CategorieSQL->getInfo();
		
		
		/*
		if ( $this->render_template == "iframe" ) {
			$this->Gabarit->render("_Iframe");
		} else {
			$this->Gabarit->render("_Page");
		}
		*/
		$this->Gabarit->render("_Page");
		
	}
	
}
