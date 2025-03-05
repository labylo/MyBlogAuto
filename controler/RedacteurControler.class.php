<?php
//Controle TOKEN : terminé

class RedacteurControler extends ApplicationControler {
	
	public function detailAction($redacteur_id) {
		$this->render_template = "page";
		
		if ( !$redacteur_id || !is_numeric($redacteur_id) ) {
			$this->redirect();
		}
		
		$infoRedacteur = $this->RedacteurSQL->getInfoFromId($redacteur_id);
		
		$this->h1 = $infoRedacteur['prenom']." ".$infoRedacteur['nom'];
		$this->meta_canon = "/redacteur/detail/".$redacteur_id;
		
		if ( $infoRedacteur['homme'] ) $lib = "rédacteur";
		else  $lib = "rédactrice";
		
		$this->meta_title = $infoRedacteur['prenom']." ".$infoRedacteur['nom'].", ".$lib." pour ".SITE_NAME;
		$this->meta_description = "Découvrir la bio et lire les derniers articles d'actu automobile de ".$infoRedacteur['prenom']." ".$infoRedacteur['nom'];

		$this->infoRedacteur = $infoRedacteur;
		$this->infoDernierArticle = $this->ArticleSQL->getInfoDernierArticleFromRedacteur($redacteur_id, 0);
	}
}
