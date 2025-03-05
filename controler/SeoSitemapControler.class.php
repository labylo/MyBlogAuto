<?php
//Controle TOKEN : terminé

class SeoSitemapControler extends ApplicationControler {
	
	const SITE_RACINE = "https://www.myblogauto.com";
	
	public function getSitemapArray() {
		$tab = array();
		
		/*
		$date_old = "2023-03-05 12:00:00";
		$datetime = new DateTime($date_old);
		$date_sitemap = $datetime->format('Y-m-d\TH:i:sP');
		echo $date_sitemap;
		exit;
		*/
		
		$date_sitemap_debut = "2023-03-05T12:00:00+01:00";
		
		//Priorité 1
		$changefreq = "daily";
		$datetime = new DateTime(date(DATE_ISO));
		$date_sitemap = $datetime->format('Y-m-d\TH:i:sP');
		$tab[] = array("loc" => "", "lastmod" => $date_sitemap, "changefreq" => $changefreq, "priority" => "1.0");
		
		
		
		//Priorité 0.8
		$priority = "0.8";
		$infoCategorie = $this->CategorieSQL->getInfo();
		foreach($infoCategorie as $mr) {
			$datetime = new DateTime($mr['date_modification']);
			$date_sitemap = $datetime->format('Y-m-d\TH:i:sP');
			$tab[] = array("loc" => "/categorie/theme/".$mr['motcle']."/".$mr['categorie_id'], "lastmod" => $date_sitemap, "changefreq" => "weekly", "priority" => $priority);
		
		
			//Calcul les pages des thèmes avec suivants/precendents
			$nb_article = $this->ArticleSQL->getNbFromCategorieId($mr['categorie_id']);
			$nb_page = ceil($nb_article/THEME_ARTICLE_NB); //On arrondit au chiffre supérieur

			if ( $nb_page > 1 ) {
				for ($i = 1; $i < $nb_page; $i++) {
					$url = "/categorie/theme/".$mr['motcle']."/".$mr['categorie_id']."/".($i+1);
					$tab[] = array("loc" => $url, "lastmod" => $date_sitemap, "changefreq" => "weekly", "priority" => $priority);
				}
			}
		
		
		}
		
		//Priorité 0.6
		$priority = "0.6";
		
		$infoArticle = $this->ArticleSQL->getInfo();
		foreach($infoArticle as $mr) {

			$date_modification = $mr['date_modification'];
			if ( $this->FancyDate->isValidDate($date_modification) ) {
				$datetime = new DateTime($date_modification);
			}else{
				$datetime = new DateTime($mr['date_creation']);
			}
			
			$date_sitemap = $datetime->format('Y-m-d\TH:i:sP');
			$tab[] = array("loc" => "/article/actu/".$mr['motcle']."/".$mr['article_id'], "lastmod" => $date_sitemap, "changefreq" => "weekly", "priority" => $priority);
		}
		
		$infoRedacteur = $this->RedacteurSQL->getInfo();
		foreach($infoRedacteur as $mr) {
			$tab[] = array("loc" => "/redacteur/detail/".$mr['redacteur_id'], "lastmod" => $date_sitemap_debut, "changefreq" => "monthly", "priority" => $priority);
		}
		

		$mois_deb = 3; //debut myblogauto
		$annee_deb = 2023;
		$mois_now = intval(date('m'));
		$annee_now = intval(date('Y'));
		
		for ($an = $annee_deb; $an <= $annee_now; $an++) {
			
			if ( $an == $annee_deb ) {
				for ($i = $mois_deb; $i < $mois_now ; $i++) {
					$mois_ok = $this->getGmtMois($i);
					$date_generation = $an."-".$mois_ok."-01T12:00:00+01:00";
					$tab[] = array("loc" => "/article/archive/".$i."/".$an, "lastmod" => $date_generation, "changefreq" => "yearly", "priority" => $priority);
				}
			}elseif ( $an == $annee_now ) {
				for ($i = 1; $i < $mois_now ; $i++) {
					$mois_ok = $this->getGmtMois($i);
					$date_generation = $an."-".$mois_ok."-01T12:00:00+01:00";
					$tab[] = array("loc" => "/article/archive/".$i."/".$an, "lastmod" => $date_generation, "changefreq" => "yearly", "priority" => $priority);
				}
			}else{
				for ($i = 1; $i <= 12; $i++) {
					$mois_ok = $this->getGmtMois($i);
					$date_generation = $an."-".$mois_ok."-01T12:00:00+01:00";
					$tab[] = array("loc" => "/article/archive/".$i."/".$an, "lastmod" => $date_generation, "changefreq" => "yearly", "priority" => $priority);
				}
			}
		}
			
		
		//Priorité 0.2
		$priority = "0.2";
		$tab[] = array("loc" => "/accessibilite", "lastmod" => $date_sitemap_debut, "changefreq" => "yearly", "priority" => $priority);
		$tab[] = array("loc" => "/contact", "lastmod" => $date_sitemap_debut, "changefreq" => "yearly", "priority" => $priority);

		return $tab;
	}
	
	private function getGmtMois($mois) {
		if ( $mois < 10 ) {
			$mois = "0".$mois;
		}
		return $mois;
	}
	
	public function genererAction($token, $origine=true) {

		$this->verifToken($token);
		
		$tab = $this->getSitemapArray();	
		
		$this->makeXML($token, $tab);

		if ( $origine ) {
			$this->LastMessage->setLastMessage("Fichier sitemap.xml généré", true);
			$this->redirect("/admin/seoSitemap");
			exit;
		}

	}
	
	private function makeXML($token, $data) {
		
		$this->verifToken($token);
		
		$url = '';
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
			<urlset
			  xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
			  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			  xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
					http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
			
		foreach($data as $tab) {
			$value = self::SITE_RACINE.$tab['loc']; 
			$url .= '<url>';
			$url .= '<loc>'.$value.'</loc>';
			$url .= '<lastmod>'.$tab['lastmod'].'</lastmod>';
			$url .= '<changefreq>'.$tab['changefreq'].'</changefreq>';
			$url .= '<priority>'.$tab['priority'].'</priority>';
			$url .= '</url>';
		}
		
		$xml .= $url;
			
		$xml .= '</urlset>';

		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xml);

		$filename = 'sitemap.xml';
		$dom->save( RACINE_PATH.'/www/'.$filename);
	}

		
}
