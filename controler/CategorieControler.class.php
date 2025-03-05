<?php
/*
Controle TOKEN : terminé
*/

class CategorieControler extends ApplicationControler {
	
	public function indexAction() {
		$this->render_template = "page";
	
		$this->h1 = "Liste des catégories et des thématiques";
		$this->meta_title = "Actualité automobile, biocarburant, écologie, toutes los catégories"; 
		$this->meta_canon = "/categorie";
		$this->meta_description = "Liste des toutes les catégories et thèmes abordés sur ".SITE_NAME." autour des sujets de l'automobile, des deux-rous, des carburants.";
		
		$this->infoCategorie = $this->CategorieSQL->getInfo();
	}
	
	public function themeAction($motcle, $categorie_id, $page=0) {
		$this->render_template = "page";
		$limit = THEME_ARTICLE_NB;
		if ( ! is_numeric($page) || $page<1 ) $page = 0;
		
		$article_nb = $this->ArticleSQL->getNbFromCategorieId($categorie_id);

		if ( $page<2 ) $offset = 0;
		else $offset = ($page-1)*$limit;
		
		if ( $offset >= $article_nb ) {
			$page = 0;
			$offset = 0;
		}
		
		if ( empty($motcle) || !is_numeric($categorie_id) ) {
			$this->redirect('/categorie');
		}
		
		$infoPage = $this->CategorieSQL->getInfoFromId($categorie_id);
		
		$h1 = $infoPage['h1'];
		$meta_title = $infoPage['meta_title'];
		$meta_description = $infoPage['meta_description'];
		if ( $page > 1 ) {
			$h1 .= " | page ".$page;
			$meta_title .= " | page ".$page;
			$meta_description.= " (Page ".$page.")";
			$meta_canon = "/categorie/theme/".$infoPage['motcle']."/".$infoPage['categorie_id']."/".$page;
		}else{
			$meta_canon = "/categorie/theme/".$infoPage['motcle']."/".$infoPage['categorie_id'];
		}
			
		$this->h1 = $h1;
		$this->meta_canon = $meta_canon;
		$this->meta_title = $meta_title;
		$this->meta_description = $meta_description;
		

		$ignore_article_id = 0;
		$this->page = $page;
		$this->offset = $offset;
		$this->infoPage = $infoPage;
		$this->article_nb = $article_nb;
		$this->infoArticles = $this->ArticleSQL->getInfoFromCategorieId($categorie_id, $ignore_article_id, $limit, $offset);
		$this->categorie_id = $categorie_id;
		$this->categorie_id_menu = $categorie_id;
		$this->fa_icone = $infoPage['fa_icone'];
		$this->infoCategorie = $this->CategorieSQL->getInfo();
		
		$this->ariane = array("Accueil"=>"/",$infoPage['categorie']=>false);
		
	}
	
	public function getRandomExistante() {
		$infoCategorie = $this->CategorieSQL->getRandomExistante();
		return $infoCategorie;
	}
	
	
	public function getRandom() {
		$infoCategorie = $this->CategorieSQL->getRandom();
		return $infoCategorie;
	}
	
	public function renderCategorieListe($exclude_categorie_id = 0) {
		$this->Gabarit->infoCategorie = $this->CategorieSQL->getInfo();
		$this->Gabarit->exclude_categorie_id = $exclude_categorie_id;
		$this->Gabarit->render("RenderCategorieListe");
	}
	
	public function renderCategorieDescription($categorie_id) {
		$this->Gabarit->infoCategorieDescription = $this->CategorieSQL->getInfoFromId($categorie_id);
		$this->Gabarit->render("RenderCategorieDescription");
	}
	
}
