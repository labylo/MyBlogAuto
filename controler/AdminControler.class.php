<?php
/*
Controle TOKEN : terminé
*/
class AdminControler extends ApplicationControler {
	
	
	public function controleSecuAction() {
		if ( DEV ) {
			$this->render_template = "page";
			$this->h1 = "Vérification des mot-clé dans URL";
			$this->meta_title = $this->h1;
		}else{
			$this->redirect();
		}
	}
	
	
	public function testAction() {
		$this->verifAdmin();
		
		/*
		$lib_cron = "CRON DU JOUR";
		$duree = "788000";
		$html = "";
		$html .= "Le ".$lib_cron." du ".date('d/m/Y H:i')." a duré ".round(($duree/1000),2)." secondes. <br/>";

		
		echo $html;
		exit;
		*/
		/*
		//TEST Twitter
		$articleInfo = $this->ArticleSQL->getLastArticle();
		$article_text = $articleInfo['h1'].". ".$articleInfo['meta_description'];
		$url = $articleInfo['motcle']."/".$articleInfo['article_id'];
		echo $this->publierTweet($article_text, "Actualité auto", $url) ;
		*/
		
		
		echo "TODO";
		echo "<hr>FIN";
		exit;
	}

	public function commentaireGenererAction($article_id="") {
		$this->verifInt($article_id);
		$this->verifAdmin();
		
		$infoArticle = $this->ArticleSQL->getInfoFromId($article_id);
		$infoUtilisateur = $this->UtilisateurSQL->getInfoRandom(1);

		foreach($infoUtilisateur as $mr) {
			$ton_rand = $this->CommentaireControler->getRandomTon();
			$longueur_rand = 	$this->CommentaireControler->getRandomLongueur();
			$date_now = time();
			$date_temp = $date_now - rand(1200, 7200);//On retire entre 20min et 120min
			$date_creation = date('Y-m-d H:i:s', $date_temp);
			
			$utilisateur_id =  $mr['utilisateur_id'];
			$prompt = $this->CommentaireControler->getPrompt($ton_rand, $longueur_rand, $infoArticle['h1']);

			$commentaire = $this->CommentaireControler->genererCommentaire(TOKEN, $prompt);
			
			if ( strlen($commentaire) > 5 ) {
				$this->CommentaireSQL->createFromCron($utilisateur_id, $article_id, $date_creation, $commentaire, $ton_rand, $longueur_rand);
				$this->UtilisateurSQL->setCommentaireStatFromUtilisateur($utilisateur_id);
				$this->ArticleSQL->setStatFromChampion($date_creation, $article_id);
			}
		}
		
		$this->LastMessage->setLastMessage("Commentaire correctement généré !", 1, 1, 1);
		$this->redirect("/article/actu/".$infoArticle['motcle']."/".$article_id);
		exit;
		
	}

	public function verifUrlAction() {
		$this->verifAdmin();
		$this->render_template = "page";
		$this->h1 = "Vérification des mot-clé dans URL";
		$this->meta_title = $this->h1;
		$this->infoArticle = $this->ArticleSQL->getInfo();
	}
	
	public function indexAction() {
		$this->verifAdmin();
		$this->render_template = "page";
		$this->h1 = "Administration ACCUEIL";
		$this->meta_title = $this->h1;
		
		$this->meta_title_ko = $this->ArticleSQL->getMetaTitleKo();
	}
	
	
	public function articleNettoyerAction($article_id) {
		$this->verifAdmin();
		
		$articleInfo = $this->ArticleSQL->getInfoFromId($article_id);
		$article = $this->FancyUtil->formaterEtNettoyerArticle($articleInfo['article']);

		//echo htmlentities($article);
		//echo $article;
		//exit;
		
		$this->ArticleSQL->saveArticle($article, $article_id);
		
		$this->LastMessage->setLastMessage("Article correctement modifié !", 1, 1, 1);
		$this->redirect("/admin/articleModifier/".$article_id);
		exit;
	}
	
	public function checkChatGptAction() {
		$this->verifAdmin();
		
		/*
		$prompt = "Donne les 3 mots uniques qui sont sémantiquement les plus pertinents dans : Les avantages et les inconvénients de la conduite d'une moto ou d'un 2 roues électrique. Ne donne pas de description et termine chaque mot par #";
		
		$tab = $this->getChatGPT($prompt);

		$motcle = $tab->choices[0]->message->content;
	
		var_dump($tab);
		echo $motcle;
		
		exit;
		*/
		
		$prompt = "Donne les 3 mots uniques pertinents pour un titre dans le domaine de l'automobile";
		$tab = $this->getChatGPT($prompt);

		$texte = $tab->choices[0]->message->content;
		echo $texte;
		exit;
		
		/*
		$articleInfo = $this->ArticleSQL->getInfoFromId(19);
		$article = $articleInfo['article'];
		$article = $this->FancyUtil->formaterEtNettoyerArticle($article);
		
		$this->article = $article;

		$this->render_template = "page";
		$this->h1 = "Administration - Check ChatGPT un article";
		$this->meta_title = "Administration";
		$this->meta_description = "";
		*/
	}
	
	
	public function articleEcrireAction() {
		$this->verifAdmin();
		
				
		$this->render_template = "page";
		$this->h1 = "Ecrire un article les photos";
		$this->meta_title = $this->h1;
		$this->meta_description = "";
		
		$this->infoCategorie = $this->CategorieSQL->getInfo();
		$this->infoRedacteur = $this->RedacteurSQL->getInfo();
		
	}

	public function articleDoEcrireAction() {
		$this->verifAdmin();
		
		$visible = $this->Recuperateur->getInt('visible', false);
		$categorie_id = $this->Recuperateur->getInt('categorie_id', false);
		$redacteur_id = $this->Recuperateur->getInt('redacteur_id', false);
		
		$h1 = $this->Recuperateur->get('h1', false);
		$motcle = $this->Recuperateur->get('motcle', false);
		$meta_title = $this->Recuperateur->get('meta_title', false);
		$meta_description = $this->Recuperateur->get('meta_description', false);
		$phrase = $this->Recuperateur->get('phrase', false);
		$chapeau = $this->Recuperateur->get('chapeau', false);
		$article = $this->Recuperateur->get('article', false);
		
		$motcle = $this->FancyUtil->transformerEnMotCle($motcle);
		
		$prompt = "manuel";
		$token = 0;
		$article_id = $this->ArticleSQL->create($categorie_id, $redacteur_id, $prompt, $token, $h1, $motcle, $meta_title, $meta_description, $phrase, $chapeau, $article, $visible);
		
		$this->LastMessage->setLastMessage("Article correctement créé !", 1, 0, 0);
		$this->redirect("/admin/articleModifier/".$article_id);
		exit;
	}
	
	
	public function articlePhotoAction($article_id) {
		$this->verifAdmin();
		
		$motcle_form = $this->Recuperateur->get('motcle', false);
		
		$this->render_template = "page";
		$this->h1 = "Gérer les photos";
		$this->meta_title = $this->h1;
		$this->meta_description = "";
		
		$infoArticle = $this->ArticleSQL->getInfoFromId($article_id);

		$this->infoArticle = $infoArticle;
		$this->article_id = $article_id;
		
		$infoCategorie = $this->CategorieSQL->getInfoFromId($infoArticle['categorie_id']);
		
		$url_base = "https://pixabay.com/api/?key=".PIXABAY_API_KEY."&per_page=200&lang=fr&image_type=photo&orientation=horizontal&min_width=1024&min_height:760&safesearch=true";
		
		if ( $motcle_form ) {
			$motcle = $motcle_form;
		}else{
			$motcle = str_replace("-", " ", $infoCategorie['image_motcle']);
		}
		
		
		//Recup Image depuis PIXABAY
		$url = $url_base."&q=".$motcle;
		
		$result = file_get_contents($url);
		$result = json_decode($result, true);
		$nb_result = count($result['hits']);
		
		/*
		if ( $nb_result ) {
			$motcle = "automobile";
			$url = $url_base."&q=".$motcle;
			$result = file_get_contents($url);
			$result = json_decode($result, true);
			$nb_result = count($result['hits']);
		}
		*/
		$this->motcle = $motcle;
		$this->tab_image = $result;
		$this->nb_result = $nb_result;
		$this->article_id = $article_id;
		$this->motcle_form = $motcle_form;
		/*
	
		$img_folder = RACINE_PATH.'/www/img/ilu/1-'.$article_id.".png";
		file_put_contents($img_folder, file_get_contents($img));
		*/

		
	}
	
	public function articlePhotoRemplacerAction() {
		$this->verifAdmin();
		$article_id = $this->Recuperateur->getInt('article_id', false);
		$motcle = $this->Recuperateur->get('motcle', false);
		$image = $this->Recuperateur->get('image', false);
		
		$image_url = $this->Recuperateur->get('image_url', false);
		$image_id = $this->Recuperateur->getInt('image_id', false);
		
		$image_credit = $this->Recuperateur->get('image_credit', false);
		$image_userid = $this->Recuperateur->get('image_userid', false);
	
		$dst_dir = RACINE_PATH.'/www/img/ilu/';
		$image_nom = $article_id;
		
		/*
		$this->FancyImage->cropAndSaveImage("1-", "", 1280, 720, $image, $dst_dir, $image_nom);
		$this->FancyImage->cropAndSaveImage("1-", "-thumb", 480, 360, $image, $dst_dir, $image_nom);
		*/
		
		$this->FancyImage->cropAndSaveImage("1-", "", 1280, 720, $image, $dst_dir, $image_nom);
		$this->FancyImage->cropAndSaveImage("1-", "-thumb", 480, 360, $image, $dst_dir, $image_nom);
		
		$this->ArticleSQL->setImage($image_url, $image_id, $image_credit, $image_userid, $article_id);
			
		$this->LastMessage->setLastMessage("Photo correctement modifiée !", 1, 1, 1);
		$this->redirect("/admin/articleModifier/".$article_id);
		exit;

	}
	
	
	public function articleModifierAction($article_id) {
		$this->verifAdmin();
		
		$this->render_template = "page";
		$this->h1 = "Administration - Modifier un article";
		$this->meta_title = "Administration";
		$this->meta_description = "";
		
		$this->article_id = $article_id;
		$this->infoArticle = $this->ArticleSQL->getInfoFromId($article_id);
		$this->infoCategorie = $this->CategorieSQL->getInfo();
	}
	
	public function doArticleModifierAction() {
		$this->verifAdmin();
		
		$visible = $this->Recuperateur->getInt('visible');
		$article_id = $this->Recuperateur->getInt('article_id');
		$h1 = $this->Recuperateur->get('h1', false);
		$categorie_id = $this->Recuperateur->getInt('categorie_id');
		$motcle = $this->Recuperateur->get('motcle', false);
		$meta_title = $this->Recuperateur->get('meta_title', false);
		$meta_description = $this->Recuperateur->get('meta_description', false);
		$phrase = $this->Recuperateur->get('phrase', false);
		$chapeau = $this->Recuperateur->get('chapeau', false);
		$article = $this->Recuperateur->get('article', false);

		$tab = array(
			"categorie_id"=>$categorie_id,
			"h1"=>$h1,
			"motcle"=>$motcle,
			"meta_title"=>$meta_title,
			"meta_description"=>$meta_description,
			"phrase"=>$phrase,
			"chapeau"=>$chapeau,
			"article"=>$article,
		);
		$this->ArticleSQL->majOne($tab, $article_id);

		if ( $visible==1 ) {
			$this->ArticleSQL->setVisible($article_id, $categorie_id);
		}
	
		$this->LastMessage->setLastMessage("Article correctement modifié !", 1, 1, 1);
		$this->redirect("/admin/articleModifier/".$article_id);
		exit;
	}
	
	
	
	public function articleGenererAction() {
		$this->verifAdmin();
		
		$this->render_template = "page";
	
		$this->h1 = "Administration : Générer un article";
		$this->meta_title = $this->h1; 
		$this->meta_description = "";
		
		$this->infoCategorie = $this->CategorieSQL->getInfo();
	}
	
	
	private function publierTweet($article, $categorie, $url) {
		$this->verifAdmin();
		
		if ( TWITTER_PUBLISH ) {
			$return_tweeter = $this->FancyTwitter->tweet($article, $categorie, $url);
			if ( $return_tweeter == "ok") {
				$retour = "<span>Génération du tweet sur Twitter : <b>OK</b></span><br>";
			}else{
				$retour = "<span>Erreur durant la génération du tweet : ".$return_tweeter."</span><br>";
			}
			return $retour;
		}else{
			return "";
		}
	}
	
	public function articlePhotoWebPAction($article_id) {
		$this->verifAdmin();
		$image_url = "/img/ilu/1-".$article_id.".jpg";
		$dst_dir = RACINE_PATH.'/www'.$image_url;
		$this->FancyImage->saveToWebP($dst_dir);
		
		$image_url = "/img/ilu/1-".$article_id."-thumb.jpg";
		$dst_dir = RACINE_PATH.'/www'.$image_url;
		$this->FancyImage->saveToWebP($dst_dir);
		
		$this->LastMessage->setLastMessage("Format image modifié !", 1, 0, 0);
		$this->redirect("/admin/articleModifier/".$article_id);
		exit;
		
	}
	
	public function articleRegenererVignetteAction($article_id) {
		$this->verifAdmin();
		
		$image_url_webp = RACINE_PATH."/www/img/ilu/1-".$article_id.".webp ";
		$image_url_jpg = RACINE_PATH."/www/img/ilu/1-".$article_id.".jpg";
		
		if ( file_exists( $image_url_webp ) ) {
			$image_url = $image_url_webp;
		}else{
			$image_url = $image_url_jpg;
		}

		$dst_dir = RACINE_PATH.'/www/img/ilu/';
		$image_nom = $article_id;
		
		$this->FancyImage->cropAndSaveImage("1-", "-thumb", 480, 360, $image_url, $dst_dir, $image_nom);
		
		$this->LastMessage->setLastMessage("Vignette correctement générée", 1, 0, 0);
		$this->redirect("/admin/articleModifier/".$article_id);
		exit;
	}
	
	public function articleGenererFromIdeeAction($idee_id, $origine="") {
		$this->verifAdmin();
		
		$infoIdee = $this->IdeeSQL->getInfoFromId($idee_id);

		$categorie_id = $infoIdee['categorie_id'];
		$idee = $infoIdee['idee'];
	
		//On selectionne un auteur
		$infoCategorie = $this->CategorieSQL->getInfoFromId($categorie_id);
		
		$redacteur_rand = rand(1,4);
		if ( $redacteur_rand < 4 ) {
			$redacteur_id = $infoCategorie['redacteur_id'];
		}else{
			$redacteur_id = $redacteur_rand;
		}


		//On génère l'article
		$return = $this->ArticleControler->genererArticle(TOKEN, $idee, $categorie_id, $redacteur_id);
		
		if ( $return=="error" ) {
			$this->LastMessage->setLastError("Erreur durant la génération d'article !", 1, 0, 0);
			if ( $origine == "idee_categorie" ) {
				$this->redirect("/admin/ideeCategorie/".$categorie_id);
			}else{
				$this->redirect("/admin/ideeListe");
			}
			exit;
		}else{
			//On supprime l'idée :
			$this->IdeeSQL->supprimerFromId($idee_id);
			$articleInfo = $this->ArticleSQL->getLastArticle();

			//On publie sur twitter
			$article_text = $articleInfo['h1'].". ".$articleInfo['meta_description'];
			$url = $articleInfo['motcle']."/".$articleInfo['article_id'];
			$tweet_text = $this->publierTweet($article_text, $infoCategorie['categorie'], $url) ;
			
			$this->LastMessage->setLastMessage("L'article a été correctement généré ".$tweet_text." !", 1, 0, 0);
			$this->redirect("/article/actu/".$articleInfo['motcle']."/".$articleInfo['article_id']);
			exit;
		}
	}
	
	
	public function articleDoGenererAction() {
		$this->verifAdmin();
		
		$idee = $this->Recuperateur->get('idee');
		$categorie_id = $this->Recuperateur->getInt('categorie_id');
	
		
		if ( ! $categorie_id ) {
			$this->LastMessage->setLastError("La catégorie est obligatoire !", 1, 0, 0);
			$this->redirect("/admin/articleGenerer");
			exit;
		}
		
		//On selectionne un auteur
		$infoCategorie = $this->CategorieSQL->getInfoFromId($categorie_id);
		
		$redacteur_rand = rand(1,4);
		if ( $redacteur_rand < 4 ) {
			$redacteur_id = $infoCategorie['redacteur_id'];
		}else{
			$redacteur_id = $redacteur_rand;
		}
		
		//On génère l'article
		$return = $this->ArticleControler->genererArticle(TOKEN, $idee, $categorie_id, $redacteur_id);
		
		if ( $return=="error" ) {
			$this->LastMessage->setLastError("Erreur durant la génération d'article !", 1, 0, 0);
			$this->redirect("/admin/articleGenerer");
			exit;
		}else{
			$articleInfo = $this->ArticleSQL->getLastArticle();
			
			$article_text = $articleInfo['h1'].". ".$articleInfo['meta_description'];
			$url = $articleInfo['motcle']."/".$articleInfo['article_id'];
			$tweet_text = $this->publierTweet($article_text, $infoCategorie['categorie'], $url) ;
		
			$this->LastMessage->setLastMessage("L'article a été correctement généré ".$tweet_text." !", 1, 0, 0);
			$this->redirect("/article/actu/".$articleInfo['motcle']."/".$articleInfo['article_id']);
			exit;
		}
	}
	
	
	public function articleListeAction(){
		$this->verifAdmin();
		$this->render_template = "page";

		$this->h1 = "Administration - Liste des articles";
		$this->meta_title = "Administration";
		$this->meta_description = "";
		
		$this->infoArticle = $this->ArticleSQL->getInfo();
	}
	

	public function commentaireSupprimerAction($article_id, $commentaire_id) {
		$this->verifAdmin();
		$this->CommentaireSQL->supprimer($article_id, $commentaire_id);
		
		$this->redirectArticle($article_id);
		
	}
	
	private function redirectArticle($article_id) {
		$infoArticle = $this->ArticleSQL->getInfoFromId($article_id);
		$url = "/article/actu/".$mr['motcle']."/".$article_id;
		$this->redirect($url);
		exit;
	}
	
	public function ideeModifierMotcleAction() {
		$this->verifAdmin();
		$this->render_template = "page";
		
		$categorie_id = $this->Recuperateur->getInt('categorie_id');
		$idee_id = $this->Recuperateur->getInt('idee_id');
		$idee = $this->Recuperateur->get('idee', false);
		$this->IdeeSQL->modifierIdeeMotcleImage($idee, $idee_id);
		
		$this->LastMessage->setLastMessage("Motclé image modifié !", 1, 1, 1);
		$this->redirect("/admin/ideeCategorie/".$categorie_id);
		exit;
	}
	
	public function categorieListeAction(){
		$this->verifAdmin();
		$this->render_template = "page";
		
		$this->h1 = "Administration - Liste des catégories";
		$this->meta_title = "Administration";
		$this->meta_description = "";
		
		$this->infoCategorie = $this->CategorieSQL->getInfo();
	}	
	
	
	public function ideeCategorieModifierAction() {
		$this->verifAdmin();
		$categorie_id_source = $this->Recuperateur->getInt('categorie_id_source');
		$categorie_id = $this->Recuperateur->getInt('categorie_id');
		$idee_id = $this->Recuperateur->getInt('idee_id');
		
		$this->IdeeSQL->modifierCategorie($categorie_id, $idee_id);
		
		$this->LastMessage->setLastMessage("Catégorie modifiée !", 1, 1, 1);
		$this->redirect("/admin/ideeCategorie/".$categorie_id_source);
		
		exit;
	}
	
	
	public function ideeCategorieAction($categorie_id){
		$this->verifAdmin();
		$this->render_template = "page";
		
		$infoCategorie = $this->CategorieSQL->getInfoFromId($categorie_id);
		
		$this->h1 = "Liste des idées catégorie : ".$infoCategorie['categorie'];
		$this->meta_title = "Administration";
		$this->meta_description = "";
		
		$this->infoIdee = $this->IdeeSQL->getInfoFromCategorieId($categorie_id);
		$this->infoCategorie = $infoCategorie;
		$this->infoCategorieListe = $this->CategorieSQL->getInfo();
		
	}	
	
	public function ideeSupprimerAction($idee_id, $categorie_id, $origine) {
		$this->verifAdmin();
		$this->IdeeSQL->supprimerFromId($idee_id);
		
		$this->LastMessage->setLastMessage("Idée supprimée !", 1, 1, 1);
		if ( $origine == "idee_categorie" ) {
			$this->redirect("/admin/ideeCategorie/".$categorie_id);
		}else{
			$this->redirect("/admin/ideeListe");
		}
		exit;
	}
	
	public function ideeListeAction(){
		$this->verifAdmin();
		$this->render_template = "page";
		
		$this->h1 = "Administration - Liste des idées";
		$this->meta_title = "Administration";
		$this->meta_description = "";
		
		$this->infoIdee = $this->IdeeSQL->getInfo();
		
		$this->infoCategorie = $this->CategorieSQL->getInfo();
		
	}	
	
	public function ideeAjouterAction() {
		$this->verifAdmin();
		
		$categorie_id = $this->Recuperateur->getInt('categorie_id');
		$liste = $this->Recuperateur->get('liste');
		$origine = $this->Recuperateur->get('origine');
		
		
		$tab = $this->FancyUtil->getIdeeListeArray($liste);

		foreach($tab as $idee) {
			$this->IdeeSQL->create($idee, $categorie_id);
		}
		
		$this->LastMessage->setLastMessage("Liste d'idées manuelles correctement ajoutée !", 1, 1, 1);
		if ( $origine == "idee_categorie" ) {
			$this->redirect("/admin/ideeCategorie/".$categorie_id);
		}else{
			$this->redirect("/admin/ideeListe");
		}
		exit;
	}
	
	
	public function ideeGenererAction($categorie_id) {
		$this->verifAdmin();
		$this->IdeeControler->generer($categorie_id, true);
	}	
	
	public function cronLogAction() {
		$this->verifAdmin();
		$this->render_template = "page";
		$this->h1 = "ADMIN | Rapport des CRONS";
		$this->meta_title = $this->h1;
		$this->meta_description = "";

		$limit = 500;
		$this->limit = $limit;
		$this->infoLogCron = $this->LogCronSQL->listeLogCron($limit);
	}
	
	public function supprimerCronLogAction($id_log_cron) {
		$this->verifAdmin();
		$this->LogCronSQL->supprimerLogCron($id_log_cron);
		$this->LastMessage->setLastMessage("Log effacé !", true);
		$this->redirect("/admin/cronLog/");
		exit;
	}
	
	public function doDeleteCronLogAction($garde_today=false) {
		$this->verifAdmin();
		$this->LogCronSQL->deleteLogCron($garde_today);
		$this->LastMessage->setLastMessage("Logs effacés !", true);
		$this->redirect("/admin/cronLog/");
		exit;
	}
	
	public function seoSitemapAction() {
		$this->verifAdmin();
		$this->render_template = "page";
		
		$this->h1 = "Vue du Sitemap";
		$this->meta_title = $this->h1;
		$this->meta_description = "";
		
		$this->infoSitemap = $this->SeoSitemapControler->getSitemapArray();
		
	}
	
	public function setArticleChampAction() {
		$this->verifAdmin();
		$article_id = $this->Recuperateur->getInt('article_id');
		$champ = $this->Recuperateur->get('champ');
		$contenu = $this->Recuperateur->get('contenu');
		
		$this->ArticleSQL->setChamp($champ, $contenu, $article_id);
		
		$this->LastMessage->setLastMessage("Le champ a été correctement modifié !", 1, 1, 1);
		$this->redirect("/admin/articleListe");
		exit;
	}
	

	public function ajaxSetArticleChamp($champ, $article_id) {
		
		session_start();
		$this->verifAdmin();
		
		$infoArticle = $this->ArticleSQL->getInfoFromId($article_id);
		$h1 = $infoArticle['h1'];
		
		
		$data = $this->ArticleControler->makePortion($h1, $champ);

		echo $data;
		exit;
	}
	
	
	
}
