<?php
require_once( __DIR__ . "/../init.php");
$recuperateur = new Recuperateur($_REQUEST);

$action = $recuperateur->get('action');
$article_id = $recuperateur->getInt('article_id');


switch ($action) {

case "set_article_champ":
	$champ = $recuperateur->get('champ');
	$objectInstancier->AdminControler->ajaxSetArticleChamp($champ, $article_id);
	break;
case "admin_tri_idee":
	$categorie_id = $recuperateur->getInt('categorie_id');
	$values = $recuperateur->get('values');
	$objectInstancier->IdeeControler->setOrder($values, $categorie_id);
	break;	
}



