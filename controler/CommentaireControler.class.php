<?php
//Controle TOKEN : terminé
class CommentaireControler extends ApplicationControler {
	
	private $ton_tab = array('sarcastique', 'humoristique', 'satirique', 'pessimiste', 'enthousiaste', 'informel', 'sérieux');
	private $phrase_tab = array('une seule phrase', 'deux phrases', 'trois phrases', 'quatre phrases', '8 mots', '16 mots');
		
		
	public function getRandomTon() {
		$ton_key = array_rand($this->ton_tab, 1);
		return $this->ton_tab[$ton_key];
	}
	
	public function getRandomLongueur() {
		$phrase_key = array_rand($this->phrase_tab, 1);
		return $this->phrase_tab[$phrase_key];
	}
	
	public function getPrompt($ton, $longueur, $article) {
		return "écris un commentaire ".$ton." en ".$longueur." sans emoji et sans répéter les mots du sujet sur le sujet : ".$article;
	}
	
	public function genererCommentaire($token, $prompt) {
		$this->verifToken($token);
		
		
		$tab = $this->getChatGPT($prompt);
		$commentaire = @$tab->choices[0]->message->content;

		if ( ! $commentaire ) {
			var_dump($tab);
			$error_msg = $tab->error->type;
			$commentaire = "error";
		}else{
			$commentaire = str_replace('"', '', $commentaire);
		}
		
		return $commentaire;
	}
	
	public function postAction() {
		$this->verifMembre();
		$this->render_template = "page";
		$commentaire = $this->Recuperateur->get('commentaire', false);
		$commentaire = strip_tags($commentaire);
		$article_id = $this->Recuperateur->getInt('article_id');
		
		$url = "";
		if ( is_numeric($article_id) ) {
			$infoArticle = $this->ArticleSQL->getInfoFromId($article_id);
			
			if ( $infoArticle ) {
				$url = "/article/actu/".$infoArticle['motcle']."/".$article_id;
			}
		}


		if ( strlen($commentaire)<10 ) {
			$url .= "/erreur/#poster";
		}else{
			$utilisateur_id = $this->Connexion->getUtilisateurId();
			$this->CommentaireSQL->create($commentaire, $article_id, $utilisateur_id);
			$url .= "/succes/#comment";
		}
		
		header("Location: ".$url);
		exit;
	}
	
	
	
}
