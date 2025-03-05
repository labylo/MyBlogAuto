<?php
class IdeeSQL extends SQL {

	public function getInfo() {
		$sql = "SELECT idee.* FROM idee ORDER BY categorie_id, ordre";
		return $this->query($sql);	
	}


	public function getInfoFromId($idee_id) {
		$sql = "SELECT * FROM idee WHERE idee_id=?";
		return $this->queryOne($sql, $idee_id);	
	}
	
	public function getNbFromCategorieId($categorie_id) {
		$sql = "SELECT count(idee_id) FROM idee WHERE categorie_id=?";
		return $this->queryOne($sql, $categorie_id);	
	}
	
	public function getInfoFromCategorieId($categorie_id) {
		$sql = "SELECT * FROM idee WHERE categorie_id=? ORDER BY ordre";
		return $this->query($sql, $categorie_id);	
	}
	
	public function supprimerFromId($idee_id) {
		$sql = "DELETE FROM idee WHERE idee_id=?";
		return $this->queryOne($sql, $idee_id);	
	}
	
	public function create($idee, $categorie_id) {
		$date_creation = date(DATE_ISO);
		$sql = "INSERT INTO idee (categorie_id, idee, date_creation) VALUES(?,?,?)";
		$this->query($sql, $categorie_id, $idee, $date_creation);
	}
	
	public function modifierIdeeMotcleImage($idee, $idee_id) {
		$sql = "UPDATE idee SET idee=? WHERE idee_id=?";
		$this->query($sql, $idee, $idee_id);
	}
	
	public function modifierCategorie($categorie_id, $idee_id) {
		$sql = "UPDATE idee SET categorie_id=? WHERE idee_id=?";
		$this->query($sql, $categorie_id, $idee_id);
	}
	
	public function getRandom($categorie_id) {
		$sql = "SELECT * FROM idee WHERE categorie_id=? ORDER BY RAND() LIMIT 1";
		return $this->queryOne($sql, $categorie_id);
	}
	
	public function getTestRandom($limit) {
		$sql = "SELECT * FROM idee ORDER BY RAND() LIMIT ".$limit;
		return $this->query($sql);
	}
	
	public function getProchaineIdee($categorie_id) {
		$sql = "SELECT * FROM idee WHERE categorie_id=? ORDER BY ordre LIMIT 1";
		return $this->queryOne($sql, $categorie_id);
	}
	
	public function setOrder($idee_id, $categorie_id, $ordre) {
		$sql = "UPDATE idee SET ordre=? WHERE idee_id=? AND categorie_id=? LIMIT 1";
		$this->query($sql, $ordre, $idee_id, $categorie_id);
	}
	
	/*
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
