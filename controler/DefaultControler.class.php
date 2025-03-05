<?php
/*
Controle TOKEN : terminé
*/

class DefaultControler extends ApplicationControler {

	public function indexAction() {
		$this->render_template = "page";
		$this->page_accueil = true;

		$this->meta_canon = "/";
		$this->h1 = "Blog auto moto, technologie électrique hybride et biocarburants";
		$this->meta_title = $this->h1; 
		$this->meta_description = "Toute l'actualité auto moto, véhicule électrique et hybride, biocarburant, tuning, technologie et mécanique, voitures anciennes et de collection";
		
		$this->infoArticles = $this->ArticleSQL->getInfoWithCategorie(7);

	}
	
	public function rechercheAction() {
		$this->render_template = "page";
		$motcle = $this->Recuperateur->get('motcle');
		$nb_result = 0;

		if ( strlen($motcle)>30 ) {
			$this->LastMessage->setLastError("Votre recherche contient trop de mots", 1, 0, 0);
		}
		
		if ( strlen($motcle)<4 ) {
			$this->LastMessage->setLastError("Votre recherche doit contenir au moins 3 caractères", 1, 0, 0);
		}
		
		$infoArticle = $this->ArticleSQL->getRecherche($motcle);

		if ( ! $infoArticle ) {
			$this->LastMessage->setLastError("Votre recherche n'a retourné aucun résultat.", 1, 0, 0);
		}
		
		$nb_result = count($infoArticle);
		if ( $nb_result == RESULT_RECHERCHE_MAX ) {
			$this->LastMessage->setLastError("Votre recherche a retourné trop de résultat, seuls les ".RESULT_RECHERCHE_MAX." premiers s'affichent.", 1, 0, 0);
		}
		

		$this->h1 = "Résultats de la recherche";
		$this->meta_title = "Résultat de votre recherche pour ".$motcle; 
		$this->meta_description = "";
		$this->motcle = $motcle;
		$this->nb_result = $nb_result;
		$this->infoArticle = $infoArticle;
	}
	
	public function _404Action() {
		$this->h1 = SITE_NAME." : Page introuvable";
		$this->title = $this->h1;
	
	}
	
}
