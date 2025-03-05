<?php
/* ===============================================================
ne plus mettre de CODE/SECRET ici, tout déplacer dans localsetting
=================================================================*/

define("REFRESH_VERSION","0.2");
define("MAINTENANCE_MAIL", false); //Mettre FALSE ou mettre la date de début du problème

/*
Je possède aussi myblogauto24@gmail.com / labyhot@MBA
*/


define("SITE_NAME","MyBlogAuto");
define("SITE_MAIL_REPLYTO", "contact@".SITE_DOMAINE); //contact@myblogauto.com
define("SITE_MAIL_NOREPLY", "noreply@".SITE_DOMAINE);
	

define("NB_NOUVELLES_IDEES","10");  //augmenter ce chiffre si GTP-4 est plus performant

define("RESULT_RECHERCHE_MAX", 30); //n'affiche pas au delà

define("MOTCLE_PRINCIPALE","automobile");

define("U_ADMIN", 8);
define("U_SUPER", 10);

define("PREF_SESS","myblogauto");
define("LOG_PAGE_TIME","3000");
define("THEME_ARTICLE_NB", 20); // NB articles à afficher dans les pages "thème"
define("RACINE_PATH", __DIR__);
define("DATE_ISO","Y-m-d H:i:s");



