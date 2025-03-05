<?php
class CommentaireSQL extends SQL {
	
	public function getInfo() {
		$sql = "SELECT * FROM commentaire";
		return $this->query($sql);	
	}
	
	public function getInfoFromArticleId($article_id) {
		$sql = "SELECT commentaire.*, utilisateur.pseudo 
		FROM commentaire 
		JOIN utilisateur ON utilisateur.utilisateur_id = commentaire.utilisateur_id 
		WHERE commentaire.article_id=? ORDER BY commentaire.date_creation DESC";
		return $this->query($sql, $article_id);	
	}
	
	public function getInfoFromId($commentaire_id) {
		$sql = "SELECT * FROM commentaire WHERE commentaire_id=?";
		return $this->queryOne($sql, $commentaire_id);	
	}
	
	public function supprimer($article_id, $commentaire_id) {
		$sql = "DELETE FROM commentaire WHERE commentaire_id=?";
		$this->query($sql, $commentaire_id);
		
		$this->setArticleCommentaireNb($article_id);
	}
	
	public function getNbFromArticle($article_id) {
		$sql = "SELECT COUNT(commentaire_id) FROM commentaire WHERE article_id=? AND visible=1 AND date_creation <= NOW()";
		return $this->queryOne($sql, $article_id);
	}

	
	public function createFromCron($utilisateur_id, $article_id, $date_creation, $commentaire, $ton, $longueur) {
		$sql = "INSERT INTO commentaire(utilisateur_id, article_id, date_creation, commentaire, ton, longueur) VALUES(?,?,?,?,?,?)";
		$this->query($sql, $utilisateur_id, $article_id, $date_creation, $commentaire, $ton, $longueur);
	}

	
	public function create($commentaire, $article_id, $utilisateur_id) {
		$date_creation = date(DATE_ISO);
		$sql = "INSERT INTO commentaire(utilisateur_id, article_id, date_creation, commentaire) VALUES(?,?,?,?)";
		$this->query($sql, $utilisateur_id, $article_id, $date_creation, $commentaire);
		
		$this->setArticleCommentaireNb($article_id);
	}

	public function setArticleCommentaireNb($article_id) {
		$sql = "SELECT COUNT(commentaire_id) FROM commentaire WHERE article_id=?";
		$commentaire_nb = $this->queryOne($sql, $article_id);
		
		$sql = "UPDATE article SET commentaire_nb=? WHERE article_id=?";
		$this->query($sql, $commentaire_nb, $article_id);
	}


}
