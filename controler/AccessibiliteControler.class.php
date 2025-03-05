<?php
/*
Controle TOKEN : terminé
*/
class AccessibiliteControler extends ApplicationControler {
	
	public function indexAction(){
		$this->render_template = "page";
		
		$this->h1 = "Accessibilité numérique | Aide à la navigation";
		$this->meta_canon = "/accessibilite";
		$this->meta_title = $this->h1;
		$this->meta_description = "Politique d'accessibilité numérique du site ".SITE_DOMAINE;
		$this->ariane = array("Accueil"=>"/", "Accessibilité numérique"=>false);
	}
	
	
}
