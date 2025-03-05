<?php 
/**
 * Affiche un texte après protection des charactères HTML
 * Permet d'afficher du texte dans les attributs des balises HTML
 * ET permet d'échaper les textes pour l'affichage en évitant les faille XSS 
 * @param string $texte
 */
function hecho($texte){
	echo gethecho($texte);
}

function gethecho($texte){
	return htmlentities($texte,ENT_QUOTES,ini_get('default_charset'));
}

function hechoText($texte){
	echo nl2br(gethecho($texte));
}
