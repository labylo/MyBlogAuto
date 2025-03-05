<?php
class ArticleSQL extends SQL {
	
	public function checkInfoManquante($champ) {
		$sql = "SELECT article_id, h1 FROM article WHERE ".$champ." IS NULL OR ".$champ."=''";
		return $this->query($sql);
	}
	
	public function savePortion($texte, $champ, $article_id) {
		$sql = "UPDATE article SET ".$champ."=? WHERE article_id=?";
		$this->query($sql, $texte, $article_id);
	}
	
	public function getInfo($limit=0) {
		$limit_sql = "";
		if ( $limit ) $limit_sql = "LIMIT ".$limit;
		
		$sql = "SELECT * FROM article ORDER BY article_id DESC ".$limit_sql;
		return $this->query($sql);	
	}
	
	public function getInfoArticlePrec($article_id) {
		$sql = "SELECT A.*, C.fa_icone AS categorie_icon, C.categorie AS categorie_lib FROM article A
		JOIN categorie C ON C.categorie_id = A.categorie_id 
		WHERE A.article_id < ? ORDER BY A.article_id DESC LIMIT 1";
		return $this->queryOne($sql, $article_id);
	}
	
	public function getInfoArticleSuiv($article_id) {
		$sql = "SELECT A.*, C.fa_icone AS categorie_icon, C.categorie AS categorie_lib FROM article A
		JOIN categorie C ON C.categorie_id = A.categorie_id 
		WHERE A.article_id > ? ORDER BY A.article_id ASC LIMIT 1";
		return $this->queryOne($sql, $article_id);
	}
	
	public function getInfoRandom() {
		$sql = "SELECT article_id, h1 FROM article WHERE visible=1 ORDER BY RAND() LIMIT 1";
		return $this->queryOne($sql);	
	}
	
	public function getInfoPlusLus($limit) {
		$sql = "SELECT article.*, categorie.fa_icone, categorie.categorie 
		FROM article 
		JOIN categorie ON categorie.categorie_id = article.categorie_id 
		WHERE article.visible=1 
		ORDER BY article.visite_nb DESC LIMIT ".$limit;
		return $this->query($sql);	
	}
	

	public function getInfoPlusComm($limit) {
		$sql = "SELECT article.*, categorie.fa_icone, categorie.categorie 
		FROM article 
		JOIN categorie ON categorie.categorie_id = article.categorie_id 
		WHERE article.visible=1 
		ORDER BY article.commentaire_nb DESC LIMIT ".$limit;
		return $this->query($sql);	
	}
	
	public function getInfoByMoisAnnee($mois, $annee) {
		$sql = "SELECT article.*, categorie.fa_icone, categorie.categorie 
		FROM article 
		JOIN categorie ON categorie.categorie_id = article.categorie_id 
		WHERE article.visible=1 AND MONTH(article.date_creation)=? AND YEAR(article.date_creation)=?
		ORDER BY article.date_creation DESC";
		return $this->query($sql, $mois, $annee);
	}
	
	public function getInfoWithCategorie($limit=0) {
		$limit_sql = "";
		if ( $limit ) $limit_sql = "LIMIT ".$limit;
		
		$sql = "SELECT article.*, categorie.fa_icone, categorie.categorie 
		FROM article 
		JOIN categorie ON categorie.categorie_id = article.categorie_id 
		WHERE article.visible=1 
		ORDER BY article.article_id DESC ".$limit_sql;
		return $this->query($sql);	
	}	
	
	public function getRecherche($motcle) {
		$sql = "SELECT * FROM article WHERE h1 LIKE ? OR chapeau LIKE ? ORDER BY date_creation DESC LIMIT ".RESULT_RECHERCHE_MAX;
		return $this->query($sql, "%".$motcle."%", "%".$motcle."%");
	}

	public function getMetaTitleKo() {
		$sql = "SELECT article_id, h1, meta_title FROM article WHERE LENGTH(meta_title)>59";
		return $this->query($sql);	
	}

	
	public function getInfoDernierArticleFromRedacteur($redacteur_id, $ignore_article_id=0) {
		
		$notin = "";
		if ( $ignore_article_id ) $notin = " AND article_id NOT IN(".$ignore_article_id.")";
		
		$sql = "SELECT article_id, h1, motcle FROM article WHERE redacteur_id=? ".$notin." ORDER BY article_id DESC LIMIT 5";
		return $this->query($sql, $redacteur_id);	
	}
	
	
	public function getNbFromCategorieId($categorie_id) {
		$sql = "SELECT COUNT(article_id) FROM article WHERE visible=1 AND categorie_id=?";
		//$sql = "SELECT COUNT(article_id) FROM article";
		return $this->queryOne($sql, $categorie_id);	
	}
	
	public function getInfoFromCategorieId($categorie_id, $ignore_article_id, $limit, $offset) {
		$notin = "";
		if ( $ignore_article_id ) $notin = " AND article_id NOT IN(".$ignore_article_id.")";

		$sql = "SELECT * FROM article WHERE categorie_id=? ".$notin." AND visible=1 ORDER BY date_creation DESC LIMIT ".$offset.", ".$limit;
		//$sql = "SELECT * FROM article ORDER BY date_creation DESC LIMIT ".$offset.", ".$limit;
		
		return $this->query($sql, $categorie_id);	
	}
	
	public function getInfoFromId($article_id) {
		$sql = "SELECT * FROM article WHERE article_id=?";
		return $this->queryOne($sql, $article_id);	
	}
	
	public function create($categorie_id, $redacteur_id, $prompt, $token, $h1, $motcle, $meta_title, $meta_description, $phrase, $chapeau, $article, $visible) {
		$now = date(DATE_ISO);
		$date = new DateTime($now);
		$duree = 'PT'.rand(1000, 4000).'S';
		$date->sub(new DateInterval($duree));
		$date_creation = $date->format('Y-m-d H:i:s');
		
		$sql = "INSERT INTO article (date_creation, categorie_id, redacteur_id, prompt, token, h1, motcle, meta_title, meta_description, phrase, chapeau, article, visible) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$this->query($sql, $date_creation, $categorie_id, $redacteur_id, $prompt, $token, $h1, $motcle, $meta_title, $meta_description, $phrase, $chapeau, $article, $visible);
		
		if ( $visible == 1 ) {
			//Mise à jour des stats de la catégorie
			$this->setArticleCategorieStats($categorie_id);
		}

		return $this->getLastId();
	}
	
	public function setArticleCategorieStats($categorie_id) {
		$sql = "SELECT COUNT(article_id) FROM article WHERE categorie_id=?";
		$article_nb = $this->queryOne($sql, $categorie_id);
		
		$date_modification = date(DATE_ISO);
		$sql = "UPDATE categorie SET article_nb=?, date_modification=? WHERE categorie_id=?";
		$this->query($sql, $article_nb, $date_modification, $categorie_id);	
	}
	
	public function setVisible($article_id, $categorie_id) {
		$sql = "UPDATE article SET visible=1 WHERE article_id=?";
		$this->query($sql, $article_id);
		
		$this->	setArticleCategorieStats($categorie_id);
		
	}
	
	public function setChamp($champ, $contenu, $article_id) {
		$sql = "UPDATE article SET ".$champ."=? WHERE article_id=?";
		$this->query($sql, $contenu, $article_id);	
	}
	
	public function setImage($image_url, $image_id, $image_credit, $image_userid, $article_id) {
		$sql = "UPDATE article SET image_url=?, image_id=?, image_credit=?, image_userid=? WHERE article_id=?";
		$this->query($sql, $image_url, $image_id, $image_credit, $image_userid, $article_id);
	}
	
	public function setStatFromChampion($date_modifiation, $article_id) {
		$sql = "SELECT COUNT(commentaire_id) FROM commentaire WHERE article_id=? AND visible=1";
		$commentaire_nb = $this->queryOne($sql, $article_id);
		
		$sql = "UPDATE article SET visite_nb=visite_nb+1, commentaire_nb=?, date_modification=? WHERE article_id=?";
		$this->query($sql, $commentaire_nb, $date_modifiation, $article_id);
	}
	
	public function setStatFromId($article_id, $visite_adip) {
		$sql = "SELECT COUNT(article_id) FROM article WHERE visite_adip=? AND article_id=?";
		$nb =  $this->queryOne($sql, $visite_adip, $article_id);
		
		if ( ! $nb || $nb < 1 ) {
			$sql = "UPDATE article SET visite_nb=visite_nb+1, visite_adip=? WHERE article_id=?";
			$this->query($sql, $visite_adip, $article_id);
		}
	}
	
	public function getChamp($champ, $article_id) {
		$sql = "SELECT ".$champ." AS contenu FROM article WHERE article_id=?";
		return $this->queryOne($sql, $article_id);
	}
	
	public function saveArticle($article, $article_id) {
		$sql = "UPDATE article SET article=? WHERE article_id=? LIMIT 1";
		$this->query($sql, $article, $article_id);
	}
	

	
	public function majOne($tab, $article_id) {
		foreach ($tab as $name=>$value) {
			$sql = "UPDATE article SET ".$name."=? WHERE article_id=? LIMIT 1";
			$this->query($sql, $value, $article_id);
		}
	}
	
	public function getLastId() {
		$sql = "SELECT article_id FROM article ORDER BY article_id DESC LIMIT 1";
		return $this->queryOne($sql);
	}
	
	public function getLastArticle() {
		$article_id = $this->getLastId();
		return $this->getInfoFromId($article_id);
	}
}
