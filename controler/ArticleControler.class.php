<?php
//Controle TOKEN : terminé

set_time_limit(600);

class ArticleControler extends ApplicationControler {

	/*
	public function indexAction() {
		$this->render_template = "page";
		$this->h1 = "Derniers article";
		$this->meta_title = "TODO";
		$this->infoArticle = $this->ArticleSQL->getInfo();
	}
	*/

	public function archiveAction($mois, $annee) {
		$mois_deb = 3;
		$annee_deb = 2023;
		
		$mois_now = intval(date('m'));
		$annee_now = intval(date('Y'));

		if ( ! is_numeric($mois) || ! is_numeric($annee) ) {
			$this->redirect();
			exit;
		}
		
		if ( $mois < 1 || $mois > 12 ) {
			$this->redirect();
			exit;
		}
		
		if ( $annee < $annee_deb || $annee > $annee_now  ) {
			$this->redirect();
			exit;
		}
		
		$lib_mois = $this->FancyDate->getMonthString($mois);
		
		$this->h1 = "Liste des articles pour ".$lib_mois." ".$annee;
		$this->meta_canon = "/article/archive/".$mois."/".$annee;
		$this->meta_title = "Archive ".$lib_mois." ".$annee." : liste des articles publiés";
		$this->meta_description = "Liste de l'ensemble des articles publiés sur ".SITE_NAME." pour la période de ".$lib_mois." ".$annee;
		
		$this->infoArticles = $this->ArticleSQL->getInfoByMoisAnnee($mois, $annee);
		$this->periode = $lib_mois."&nbsp;".$annee;
	}
	
	public function actuAction($motcle="", $article_id=0, $lien_interne=false) {

		if ( ! $motcle || strlen($motcle)<5 || !$article_id || !is_numeric($article_id) || $article_id < 1 ) {
			$this->redirect();
			exit;
		}

		$this->render_template = "page";
		
		$infoArticle = $this->ArticleSQL->getInfoFromId($article_id);
		$infoCategorie = $this->CategorieSQL->getInfoFromId($infoArticle['categorie_id']);
		
		$infoArticlePrec = $this->ArticleSQL->getInfoArticlePrec($article_id);
		$infoArticleSuiv = $this->ArticleSQL->getInfoArticleSuiv($article_id);
		$infoArticles = $this->ArticleSQL->getInfoFromCategorieId($infoArticle['categorie_id'], $article_id, 5, 0);
		
		$this->h1 = $infoArticle['h1'];
		$this->meta_canon = "/article/actu/".$infoArticle['motcle']."/".$infoArticle['article_id'];
		$this->meta_title = $infoArticle['meta_title'];
		$this->meta_description = $infoArticle['meta_description'];
		
		
		$adip = $this->FancyUtil->getIp();
	
		$this->ArticleSQL->setStatFromId($article_id, $adip);
		
		$this->infoArticle = $infoArticle;
		$this->infoCategorie = $infoCategorie;
		$this->infoArticles = $infoArticles;
		
		$this->infoArticlePrec = $infoArticlePrec;
		$this->infoArticleSuiv = $infoArticleSuiv;
		
		$this->categorie_fa_icone = $this->infoCategorie['fa_icone'];
		$this->categorie_lib = $this->infoCategorie['categorie'];
		$this->categorie_motcle = $this->infoCategorie['motcle'];
		$this->categorie_id = $this->infoCategorie['categorie_id'];
		$this->infoRedacteur = $this->RedacteurSQL->getInfoFromId($infoArticle['redacteur_id']);
		$this->infoDernierArticle = $this->ArticleSQL->getInfoDernierArticleFromRedacteur($infoArticle['redacteur_id'], $article_id);
		$this->infoCommentaire = $this->CommentaireSQL->getInfoFromArticleId($article_id);
		$this->lien_interne = $lien_interne;
		
		/*
		$this->ariane = array(
			"Accueil"=>"/",
			$infoCategorie['categorie']=>"/categorie/theme/".$infoCategorie['motcle']."/".$infoCategorie['categorie_id'],
			$infoArticle['h1']=>false
		);
		*/
		$this->ariane = array(
			"Accueil"=>"/",
			$infoCategorie['categorie']=>"/categorie/theme/".$infoCategorie['motcle']."/".$infoCategorie['categorie_id'],
		);
	}
	 
	public function genererArticle($token, $idee, $categorie_id, $redacteur_id) {
		
		/*
		$infoIdee = $this->IdeeSQL->getTestRandom(30);

		foreach($infoIdee as $mr) {
			$url = $this->FancyUtil->getRewriteUrl($mr['idee']);
			echo $url."<hr>";
		}
		exit;
		*/



		$this->verifToken($token);
		
		if ( strlen($idee) > 20 ) {
			$retour = $this->makeArticle($idee, $categorie_id, $redacteur_id);
			return $retour;
		}else{
			return "error";
		}
	}
	
	/*
	public function doGenererAction() {
		
		$h1 = $this->Recuperateur->get('h1');
		
		if ( strlen($h1) > 20 ) {
			$this->genererArticle(TOKEN, $h1, 0, 1);
			$this->LastMessage->setLastMessage("L'article a été correctement généré !", 1, 1, 1);
		}else{
			$this->LastMessage->setLastError("Aucun article généré", 1, 1, 1);
		}
		
		$this->redirect("/article/generer");
		exit;
	}
	*/
	
	public function makeImage($article_id, $categorie_id) {
		$infoCategorie = $this->CategorieSQL->getInfoFromId($categorie_id);
		
		$url_base = "https://pixabay.com/api/?key=".PIXABAY_API_KEY."&per_page=200&lang=fr&image_type=photo&orientation=horizontal&category=transportation&min_width=1024&min_height:760&safesearch=true";
		
		$motcle = str_replace("-", " ", $infoCategorie['image_motcle']);

		//Recup Image depuis PIXABAY
		$url = $url_base."&q=".$motcle;
		
		$result = file_get_contents($url);
		$result = json_decode($result, true);
		$nb_result = count($result['hits']);
		
		if ( ! $nb_result ) {
			$motcle = "automobile";
			$url = $url_base."&q=".$motcle;
			$result = file_get_contents($url);
			$result = json_decode($result, true);
			$nb_result = count($result['hits']);
		}
		
		$rand = rand(0, ($nb_result-1) );
		$img = $result['hits'][$rand]['largeImageURL'];

		$image_credit = $result['hits'][$rand]['user'];
		$image_userid = $result['hits'][$rand]['user_id'];

		$image_url = $result['hits'][$rand]['pageURL'];
		$image_id = $result['hits'][$rand]['id'];
		$this->ArticleSQL->setImage($image_url, $image_id, $image_credit, $image_userid, $article_id);
		
		$dst_dir = RACINE_PATH.'/www/img/ilu/';
		$image_nom = $article_id;

		$this->FancyImage->cropAndSaveImage("1-", "", 1280, 720, $img, $dst_dir, $image_nom);
		$this->FancyImage->cropAndSaveImage("1-", "-thumb", 480, 360, $img, $dst_dir, $image_nom);
		
		/*
		$img_folder = RACINE_PATH.'/www/img/ilu/1-'.$article_id.".png";
		file_put_contents($img_folder, file_get_contents($img));
		*/
	}
	
	public function makePortion($h1, $champ) {
		$retour = false;

		switch ($champ) {
			case "motcle";
				$prompt = "Trouve 3 mots uniques et pertinents dans : ".$h1;
				$tab = $this->getChatGPT($prompt);
				$motcle = $tab->choices[0]->message->content;
				$retour = $this->FancyUtil->makeMotcle($motcle);
			break;
			case "meta_title";
				//$prompt = "Fournis une balise meta title d'environ 50 caractères qui donnerait envie au utilisateur de cliquer pour un aticle dont le titre est : ".$h1;
				
				$prompt = "Ecris un balise TITLE de 6 mots maximum, pour un aticle sur : ".$h1;
							
				$tab = $this->getChatGPT($prompt);
				$meta_title = $tab->choices[0]->message->content;
				$meta_title = str_replace('"', '', $meta_title);
				$retour = str_replace('- Guide complet', '', $meta_title);
			break;
			case "meta_description";
				$prompt = "Pour un article dont le titre est \".$h1.\", fournis un texte attractif ayant entre 120 et 140 caractères pour une meta description.";
				$tab = $this->getChatGPT($prompt);
				$retour = $tab->choices[0]->message->content;
			break;		
			case "phrase";
				$prompt = "Rédige une phrase de 40 mots environ sur un article dont le titre est : ".$h1;
				$tab = $this->getChatGPT($prompt);
				$retour = $tab->choices[0]->message->content;
			break;
			case "chapeau";
				$prompt = "Rédige un paragraphe d'introduction pour un article dont le titre est : ".$h1;
				$tab = $this->getChatGPT($prompt);
				$retour = $tab->choices[0]->message->content;
			break;
		}
		
		return $retour;
	}
	
	
	
	private function makeArticle($h1, $categorie_id, $redacteur_id) {
		$article_id = 0;
		$token = 0;
		$mot = rand(600, 800);
		//$prompt = "Écris un article de blog de ".$mot." mots sur ".$h1." en titrant chaque paragraphe dans une balise h2, Cet article de blog sera optimisé pour le SEO et sans contenu dupliqué.";
		$prompt = "Écris un article de blog sans contenu dupliqué de ".$mot." mots sur ".$h1.". Le titre de chaque paragraphe doit etre dans une balise <h2>.";
		$prompt_question = str_replace('<h2>', 'H2', $prompt);
		
		$dev_test = false  ;
		if ( $dev_test ) {
			$token = 1234;
			$article = "DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, DEV Voici mon article, ";
			$chapeau = "DEV Voici mon chapeau, DEV Voici mon chapeau, DEV Voici mon chapeau, DEV Voici mon chapeau, DEV Voici mon chapeau, ";
			$phrase = "DEV Voici mon phrase, DEV Voici mon phrase, DEV Voici mon phrase, DEV Voici mon phrase, ";
			$meta_title = "DEV voici mon meta_title";
			$meta_description = "DEV Voici mon meta_description";
			$motcle_val = "DEV# motclé1# moclés#";

		}else{
		
			$tab = $this->getChatGPT($prompt);
			$token += $tab->usage->total_tokens;
			$article = $tab->choices[0]->message->content;
			$article  = $this->FancyUtil->formaterEtNettoyerArticle($article);
			
			$prompt = "Rédige un paragraphe d'introduction pour un article dont le titre est : ".$h1;
			$tab = $this->getChatGPT($prompt);
			$token += $tab->usage->total_tokens;
			$chapeau = $tab->choices[0]->message->content;
			$chapeau = str_replace('"', '', $chapeau);
			
			$prompt = "Rédige une phrase d'introduction pour un article dont le titre est : ".$h1;
			$tab = $this->getChatGPT($prompt);
			$token += $tab->usage->total_tokens;
			$phrase = $tab->choices[0]->message->content;
			$phrase = str_replace('"', '', $phrase);
			
			//$prompt = "Fournis une balise meta title d'environ 50 caractères qui donnerait envie au utilisateur de cliquer pour un aticle dont le titre est : ".$h1;
			$prompt = "Ecris un balise TITLE de 6 mots maximum, pour un aticle sur : ".$h1;
			$tab = $this->getChatGPT($prompt);
			$token += $tab->usage->total_tokens;
			$meta_title = $tab->choices[0]->message->content;
			$meta_title = str_replace('"', '', $meta_title);
			$meta_title = str_replace('- Guide complet', '', $meta_title);		
					 
			$prompt = "Pour un article dont le titre est \".$h1.\", fournis un texte attractif ayant entre 120 et 140 caractères pour une meta description.";
			$tab = $this->getChatGPT($prompt);
			$token += $tab->usage->total_tokens;
			$meta_description = $tab->choices[0]->message->content;
			$meta_description = str_replace('"', '', $meta_description);
			
			/*
			$prompt = "Trouve les 3 mots uniques pertinents dans : ".$h1;
			$tab = $this->getChatGPT($prompt);
			$token += $tab->usage->total_tokens;
			$motcle_val = $tab->choices[0]->message->content;
			$motcle_val = str_replace('"', '', $motcle_val);
			*/
		}
		
		/*
		$motcle = $this->FancyUtil->makeMotcle($motcle_val);
		
		if ( strlen($motcle) < 5 ) {
			$motcle = $this->FancyUtil->makeMotcle($h1);
		}
		*/
		
		$motcle = $this->FancyUtil->getRewriteUrl($h1);
			
		$visible = 1;
		$article_id = $this->ArticleSQL->create($categorie_id, $redacteur_id, $prompt_question, $token, $h1, $motcle, $meta_title, $meta_description, $phrase, $chapeau, $article, $visible);
		
		if ( ! $article_id ) {
			return "error";
		}else{
			$this->makeImage($article_id, $categorie_id);
			return "ok";
		}
	}
	
	public function renderArticleLesPlusLus($limit=5) {
		$this->Gabarit->infoArticlePlusLus = $this->ArticleSQL->getInfoPlusComm($limit);
		$this->Gabarit->render("RenderArticleLesPlusLus");
	}
	
	public function renderArticleLesPlusComm($limit=3) {
		$this->Gabarit->infoArticlePlusComm = $this->ArticleSQL->getInfoPlusComm($limit);
		$this->Gabarit->render("RenderArticleLesPlusComm");
	}

}
