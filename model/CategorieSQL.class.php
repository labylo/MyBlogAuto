<?php
class CategorieSQL extends SQL {

	public function getInfo() {
		$sql = "SELECT * FROM categorie ORDER BY categorie";
		return $this->query($sql);	
	}

	public function getInfoFromId($categorie_id) {
		$sql = "SELECT * FROM categorie WHERE categorie_id=?";
		return $this->queryOne($sql, $categorie_id);	
	}
	
	public function getRandom() {
		$sql = "SELECT * FROM categorie ORDER BY RAND() LIMIT 1";
		return $this->queryOne($sql);
	}
	
	public function setNbIdeeRestante() {
		$infoCategorie = $this->getInfo();
		foreach($infoCategorie as $mr) {
			$categorie_id = $mr['categorie_id'];
			$sql = "SELECT COUNT(idee_id) FROM idee WHERE categorie_id=?";
			$idee_nb = $this->queryOne($sql, $categorie_id);

			$this->setNbIdeeFromCategorieId($idee_nb, $categorie_id);
		}
	}
	
	public function setNbIdeeFromCategorieId($idee_nb, $categorie_id) {
		$sql = "UPDATE categorie SET idee_nb=? WHERE categorie_id=?";
		$this->query($sql, $idee_nb, $categorie_id);	
	}
	
	public function getRandomExistante() {
		$sql = "SELECT categorie_id FROM categorie WHERE idee_nb > 0 ORDER BY article_nb ASC LIMIT 3";
		$info = $this->query($sql);

		$tab=array();
		foreach($info as $mr) {
			$tab[] = $mr['categorie_id'];
		}
		
		$categorie_id = $tab[array_rand($tab, 1)];
		$infoCategorie = $this->getInfoFromId($categorie_id);
		return  $infoCategorie;
	}
	
	
	public function setStat($categorie_id) {
		$sql = "SELECT COUNT(article_id) FROM article WHERE categorie_id=?";
		$nb = $this->queryOne($sql, $categorie_id);
		
		$sql = "UPDATE categorie SET article_nb=? WHERE categorie_id=?";
		$this->query($sql, $nb, $categorie_id);	
	}
	
	
	
	
	/*
	
	public function create($prompt, $token, $h1, $meta_title, $meta_description, $phrase, $chapeau, $article) {
		$date_creation = date(DATE_ISO);
		$sql = "INSERT INTO article (date_creation, prompt, token, h1, meta_title, meta_description, phrase, chapeau, article) VALUES(?,?,?,?,?,?,?,?,?)";
		$this->query($sql, $date_creation, $prompt, $token, $h1, $meta_title, $meta_description, $phrase, $chapeau, $article);	
	}
	
	public function setChamp($champ, $contenu, $article_id) {
		$sql = "UPDATE article SET ".$champ."=? WHERE article_id=?";
		$this->query($sql, $contenu, $article_id);	
	}
	
	public function getChamp($champ, $article_id) {
		$sql = "SELECT ".$champ." AS contenu FROM article WHERE article_id=?";
		return $this->queryOne($sql, $article_id);
	}
	
	*/
	

}
