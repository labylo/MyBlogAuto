<?php
/*
Controle TOKEN : terminé
*/

set_time_limit(600);

class IdeeControler extends ApplicationControler {
	
	
	public function createAndGetNouvellesIdees($categorie_id) {
		$this->generer($categorie_id, false);
		$infoIdee = $this->IdeeSQL->getRandom($categorie_id);
	}
	
	public function getProchaineIdee($categorie_id) {
		$infoIdee = $this->IdeeSQL->getProchaineIdee($categorie_id);
		return $infoIdee;
	}
	
	public function getRandom($categorie_id) {
		$infoIdee = $this->IdeeSQL->getRandom($categorie_id);
		return $infoIdee;
	}
	
	
	public function getListeFromCategorieId($categorie_id) {
		$infoIdee = $this->IdeeSQL->getInfoFromCategorieId($categorie_id);
		return $infoIdee;
	}


	public function setOrder($values, $categorie_id) {
		$tab = array();
		$tab = explode(",", $values);
		$ordre = 0;
		
		foreach ( $tab as $idee_id ) {
			$ordre++; 
			//echo $idee_id." (".$categorie_id.")<br>";
			$this->IdeeSQL->setOrder($idee_id, $categorie_id, $ordre);
		}
	}
	
	
	public function generer($categorie_id, $redirect=false) {
		$infoCategorie = $this->CategorieSQL->getInfoFromId($categorie_id);
		
		if ( $infoCategorie ) {
		
			$categorie = $infoCategorie['categorie']. "(".MOTCLE_PRINCIPALE.")";
	
			$this->makeIdee($categorie, $categorie_id);
			
			if ( $redirect ) $this->LastMessage->setLastMessage("Une liste de ".NB_NOUVELLES_IDEES." idées a été générée !", 1, 1, 1);
		}else{
			if ( $redirect ) $this->LastMessage->setLastError("Aucune idée générée", 1, 1, 1);
		}
		
		if ( $redirect ) {
			$this->redirect("/admin/ideeListe");
			exit;
		}
	}
	
	private function makeIdee($categorie, $categorie_id) {

		$prompt = "rédige une liste de ".NB_NOUVELLES_IDEES." sujet d'article variés différents et originaux sans description se terminant par # et que tu pourrais rédiger sur le thème : ".$categorie;
		
		$tab = $this->getChatGpt($prompt);
		$liste = $tab->choices[0]->message->content;
		
		$tab = $this->FancyUtil->getIdeeListeArray($liste);
		
		foreach($tab as $idee) {
			//echo $idee."<br>";
			$this->IdeeSQL->create($idee, $categorie_id);
		}
	}

}
