<?php
/*
Controle TOKEN : terminé
*/

class CronControler extends ApplicationControler {

	const ALERT_MAIL_MS = 120000; //Au dela de 2 min, je m'envoi un mail
	const CRON_VERSION =  "V.03-05-23";

	public function protectionToken($token) {
		if ( $token != TOKEN ) {
			$url = "URL = ".$_SERVER['REQUEST_URI'];
			$this->LogCronSQL->saveLogCron(1, "protect", "TOKEN Refusé", $url);
			exit;
		}
	}


	public function protectionScript() {

		if ( PRODUCTION && $this->getRemoteAddr() ){
			$html = "Passage via URL : ".$_SERVER['REQUEST_URI'];
			$this->LogCronSQL->saveLogCron(1, "Protection CRON", "Script non executé - protectionScript()", $html);
			exit();
		}
	}
	
	private function getRemoteAddr(){
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		
		if (isset($_SERVER['REMOTE_ADDR'])){
			return $_SERVER['REMOTE_ADDR'];
		}
		
		return false;
	}
	
	
	private function getLogTime($debut) {
		
		$duree = round((microtime(true) - $debut) * 10000) / 10;
		return $duree;
	}
	
		
	
	public function setErreur($html, $msg_error, $type_cron) {
		$html .= '<span style="color:red">'.$msg_error.'</span><br>';
		
		//Puis on termine l'execution du CRON
		$error = 1;
		$rapport_libelle = "Cron Jour ERREUR";
		$duree = 0;
		$this->createRapportCron($error, $type_cron, $duree, $rapport_libelle, $html);
		exit;
	}
	
	
	public function controleCategorie($token) {
		$this->protectionScript();
		$this->protectionToken($token);
		$alert = false;
		$html = "ATTENTION<br><br>";
		$titre = "Catégorie bientôt épuisée";
		$infoCategorie = $this->CategorieSQL->getInfo();
		
		foreach($infoCategorie as $mr) {
			
			$nb_idee = $this->IdeeSQL->getNbFromCategorieId($mr['categorie_id']);
			if ( $nb_idee < 5 ) {
				$alert = true;
				$categorie = $mr['categorie'];
				$html .= "La catégorie -<b>".$categorie."</b>- ne dispose plus que de <b>".$nb_idee."</b> idées d'articles<br>";
			}
		}
		
		
		if ( $alert ) {
			$this->MailControler->sendMailCronCategorieAlertWebmaster($html, $titre);
		}
		
		exit;
	}
	
	
	private function savePortion($h1, $champ, $article_id) {
		$texte = $this->ArticleControler->makePortion($h1, $champ);
		$this->ArticleSQL->savePortion($texte, $champ, $article_id);
		return "Champ manquant (".$champ.") corrigé dans l'article ID : ".$article_id."<br>";
	}
	
	private function verifInfoArticle() {
		$cpt = 0;
		$champ = "motcle";
		$infoPhrase = $this->ArticleSQL->checkInfoManquante($champ);
		if ( $infoPhrase ) {
			foreach( $infoPhrase as $mr ) {
				$cpt++;
				$this->savePortion($mr['h1'], $champ, $mr['article_id']);
			}
		}
		
		$champ = "meta_title";
		$infoPhrase = $this->ArticleSQL->checkInfoManquante($champ);
		if ( $infoPhrase ) {
			foreach( $infoPhrase as $mr ) {
				$cpt++;
				$this->savePortion($mr['h1'], $champ, $mr['article_id']);
			}
		}
		
		$champ = "meta_description";
		$infoPhrase = $this->ArticleSQL->checkInfoManquante($champ);
		if ( $infoPhrase ) {
			foreach( $infoPhrase as $mr ) {
				$cpt++;
				$this->savePortion($mr['h1'], $champ, $mr['article_id']);
			}
		}
		
		$champ = "phrase";
		$infoPhrase = $this->ArticleSQL->checkInfoManquante($champ);
		if ( $infoPhrase ) {
			foreach( $infoPhrase as $mr ) {
				$cpt++;
				$this->savePortion($mr['h1'], $champ, $mr['article_id']);
			}
		}

		$champ = "chapeau";
		$infoPhrase = $this->ArticleSQL->checkInfoManquante($champ);
		if ( $infoPhrase ) {
			foreach( $infoPhrase as $mr ) {
				$cpt++;
				$this->savePortion($mr['h1'], $champ, $mr['article_id']);
			}
		}
		
		if ( $cpt > 0 ) {
			$html = $cpt . " champ(s) manquant()s corrigé(s)";
		}else{
			$html = "Aucun champ manquant détécté";
		}
		
		return $html;
	}
	
	
	public function nouveauCommentaireAction($token) {
		$this->nouveauCommentaire($token, $log);
	}
	
	
	public function nouveauCommentaire($token="") {
		$this->protectionScript();
		$this->protectionToken($token);
		$deb = microtime(true);
		$type_cron = "Génération commentaire";
		$html = "";
		$t1 = microtime(true);
		
		//On controle qu'il ne manque aucune info dans les précédents articles :
		$html .= "<hr>Controle des champs manquants dans certains articles : <br>";
		$html .= $this->verifInfoArticle();
		$html .= "<hr>";
		
		//On récupère un utilisateur :
		$limit = rand(0,3);
		
		if ( $limit > 0 ) {
			$infoUtilisateur = $this->UtilisateurSQL->getInfoRandom($limit);

			$nb_comm=0;
			foreach($infoUtilisateur as $mr) {
				$html_user = "";
				$ton_rand = $this->CommentaireControler->getRandomTon();
				$longueur_rand = 	$this->CommentaireControler->getRandomLongueur();

				$date_now = time();
				$date_temp = $date_now - rand(1440, 66000);//CRON lancé à 3h15, on retire donc 240 minutes (1440 sec) minimum et 1100 minutes (66000 sec) MAXIMUM
				$date_creation = date('Y-m-d H:i:s', $date_temp);
				
				$infoArticle = $this->ArticleSQL->getInfoRandom();
				$article_id = $infoArticle['article_id'];
				$nb_comm++;
				$utilisateur_id =  $mr['utilisateur_id'];
				$html_user .= "Ajout d'un commentaire<br>";
				$html_user .= "Utilisateur : ".$mr['pseudo']." (#".$utilisateur_id.")<br>";
				$html_user .= "Ton : ".$ton_rand."<br>";
				$html_user .= "Longueur : ".$longueur_rand."<br>";
				$html_user .= "Date : ".$date_creation."<br>";
				$html_user .= "Article : ".$infoArticle['h1']." (#".$article_id.")<br>";
				
				$prompt = $this->CommentaireControler->getPrompt($ton_rand, $longueur_rand, $infoArticle['h1']);

				$commentaire = $this->CommentaireControler->genererCommentaire($token, $prompt);
				
				if ( $commentaire == "error" ) {
					$html_user .= "ERREUR durant la génération du commentaire";
				}else{
					$this->CommentaireSQL->createFromCron($utilisateur_id, $article_id, $date_creation, $commentaire, $ton_rand, $longueur_rand);
					$this->UtilisateurSQL->setCommentaireStatFromUtilisateur($utilisateur_id);
					$this->ArticleSQL->setStatFromChampion($date_creation, $article_id);
					$html_user .= "Commentaire : ".$commentaire."<br>";
				}
				$html .= $html_user."<hr>";
			}
		}else{
			$html .= "Aucun commentaire pour aujourd'hui<hr>";
		}
		
		$nb_req = $this->SQLQuery->getNbReq();
		$duree = round((microtime(true) - $deb) * 10000) / 10;
		$actual_page = "<br>CRON génération commentaire - ".date("d/m/Y H:i");
		
		if ( $duree > 10000 ) {
			$duree = $duree / 1000;
			$duree = number_format($duree, 2)." sec";
		}else{
			$duree = $duree . " ms";
		}
		
		$html .= "<b>FIN Cron</b> : " . $duree . " - ".$nb_req." req";
		
		$error = 0;
		$rapport_libelle = "Rapport Cron Commentaire";
		$this->createRapportCron($error, $type_cron, $duree, $rapport_libelle, $html);
		exit;
	
	}
	
	public function jourAction($token, $log=false) {
		$this->jour($token, $log);
	}
	
	
	public function jour($token="", $log=false) {
		$this->protectionScript();
		$this->protectionToken($token);

		$deb = microtime(true);
		$type_cron = "Génération Article";
		
		$html = "";
		$t1 = microtime(true);
		
		//on met à jour les stats du idee_nb sur chaque catégorie
		$this->CategorieSQL->setNbIdeeRestante();
		
		
		$infoCategorie = $this->CategorieControler->getRandomExistante();

		
		if ( ! $infoCategorie ) {
			$this->setErreur($html, "Aucune catégorie trouvée", $type_cron);
		}
		
		$categorie_id = $infoCategorie['categorie_id'];
		$categorie = $infoCategorie['categorie'];
		
		$html .= "<span>Catégorie choisie : <br><b>".$categorie."</b> (ID ".$categorie_id.")</span><br><br>";
		
		$redacteur_rand = rand(1,4);
		if ( $redacteur_rand < 4 ) {
			$redacteur_id = $infoCategorie['redacteur_id'];
		}else{
			$redacteur_id = $redacteur_rand;
		}
		
		$infoRedacteur = $this->RedacteurSQL->getInfoFromId($redacteur_id);
		$html .= "<span>Redacteur choisi : <br><b>".$infoRedacteur['prenom']." ".$infoRedacteur['nom']."</b> (ID ".$infoRedacteur['redacteur_id'].")</span><br>";
		
		
		//On recup regarde si une idée de thème est dispo dans la categorie
		$infoIdee = $this->IdeeControler->getProchaineIdee($categorie_id);
		
		if ( ! $infoIdee ) {
			$html .= "<span>Aucune idée d'article trouvé. Génération de nouvelle idée pour la catégorie <b>".$categorie."</b></span><br>";
			$this->IdeeControler->createAndGetNouvellesIdees($categorie_id);
			$html .= "<span>".NB_NOUVELLES_IDEES." idées ont été créées. Sélection d'un article parmis ceux-ci.</span><br>";
			$infoIdee = $this->IdeeControler->getRandom($categorie_id);
		}
		
		if ( ! $infoIdee ) {
			$this->setErreur($html, "Aucune idée d'article trouvée.", $type_cron);
		}else{
			$idee_id = $infoIdee['idee_id'];
			$idee = $infoIdee['idee'];
			$html .= "<span>Idée d'article choisi :<br><b>".$idee."</b></span><br>";
		}
			
		
		$html .= "<span>Génération d'un article sur le thème choisi</span><br>";
		$retour = $this->ArticleControler->genererArticle(TOKEN, $idee, $categorie_id, $redacteur_id);
		
		if ( $retour == "error" ) {
			$this->setErreur($html, "Erreur durant la génération de l'article.", $type_cron);
		}
		
		$html .= "<span>Article créé : <b>OK</b></span><br>";
		
		$this->IdeeSQL->supprimerFromId($idee_id);
		$html .= "<span>Suppression de l'idée : <b>OK</b></span><br>";
		
		$this->SeoSitemapControler->genererAction(TOKEN, false);
		$html .= "<span>Génération du SiteMAP.xml : <b>terminé</b></span><br>";


		//On publie sur twitter
		if ( TWITTER_PUBLISH ) {
			$articleInfo = $this->ArticleSQL->getLastArticle();
			
			$url = $articleInfo['motcle']."/".$articleInfo['article_id'];
			$article_text = $articleInfo['h1']." ".$articleInfo['meta_description'];
			$return_tweeter = $this->FancyTwitter->tweet($article_text, $categorie, $url);
			if ( $return_tweeter == "ok") {
				$html .= "<span>Génération du tweet sur TWITTER : <b>OK</b></span><br>";
			}else{
				$html .= "<span style='color:red;'>Erreur durant la génération du tweet : ".$return_tweeter."</span><br>";
			}
		}
		
		$nb_req = $this->SQLQuery->getNbReq();
		$duree = round((microtime(true) - $deb) * 10000) / 10;
				
		$actual_page = "<br>CRON génération article - ".date("d/m/Y H:i");
		
		if ( $duree > 10000 ) {
			$duree = $duree / 1000;
			$duree = number_format($duree, 2)." sec";
		}else{
			$duree = $duree . " ms";
		}
		
		$html .= "<b>FIN Cron</b> : " . $duree . " - ".$nb_req." req";
		
		if ( $log ) {
			echo $html;
		}
		
		$error = 0;
		$rapport_libelle = "Rapport Cron Jour";
		$this->createRapportCron($error, $type_cron, $duree, $rapport_libelle, $html);
		exit;
	}
	
	private function createRapportCron($error, $type_cron, $duree, $rapport_libelle, $html) {
		//Chaque jour on stocke dans LOG CRON le rapport !
		$this->LogCronSQL->saveLogCron($error, $type_cron, $rapport_libelle, $html);
		
		//je m'envoie un mail quoiqu'il arrive
		$this->MailControler->sendMailCronAlertWebmaster($html, $duree, $rapport_libelle);
	}
}

