<?php
class RedacteurSQL extends SQL {
	
	public function getInfo() {
		$sql = "SELECT * FROM redacteur ORDER BY prenom";
		return $this->query($sql);	
	}
	
	
	public function getInfoFromId($redacteur_id) {
		$sql = "SELECT * FROM redacteur WHERE redacteur_id=?";
		return $this->queryOne($sql, $redacteur_id);	
	}

	
	public function getRandom($categorie_id) {
		$sql = "SELECT * FROM idee WHERE categorie_id=? ORDER BY RAND() LIMIT 1";
		return $this->queryOne($sql, $categorie_id);
	}
	

	

}
