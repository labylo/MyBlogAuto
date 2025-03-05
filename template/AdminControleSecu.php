<?php
function genererLien($controler, $tab) {
	$controler = strtolower($controler);
	echo '<hr><b>'.ucfirst($controler).'</b><br>';
	foreach($tab as $page) {
		echo '<a href="/'.$controler.'/'.$page.'" target="_blank">'.$page.'</a><br>';
	}
}
?>
<div class="bloc">
	<div class="bloc_titre">
		<div class="grid-x grid-margin-x">
			<div class="cell small-6">
				<h1><?php echo $h1 ?></h1>
			</div>
			<div class="cell small-6">
			&nbsp;
			</div>
		</div>
		<div class="bloc_content">
			<div class="grid-x grid-margin-x">
				<div class="cell small-3">
					<b>Administration</b><br>
					<a href="/admin/test">test</a><br>
					<a href="/admin/verifUrlAction">verifUrlAction</a><br>
					<a href="/admin/index">indexAction</a><br>
					<a href="/admin/articleNettoyer">articleNettoyer</a><br>
					<a href="/admin/checkChatGpt">checkChatGpt</a><br>
					<a href="/admin/articleEcrire">articleEcrire</a><br>
					<a href="/admin/articleDoEcrire">articleDoEcrire</a><br>
					<a href="/admin/articlePhoto/1">articlePhoto</a><br>
					<a href="/admin/articlePhotoRemplacer">articlePhotoRemplacer</a><br>
					<a href="/admin/articleModifier/1">articleModifier</a><br>
					<a href="/admin/doArticleModifier">doArticleModifier</a><br>
					<a href="/admin/articleGenerer">articleGenerer</a><br>
					<a href="/admin/publierTweet">publierTweet</a><br>
					<a href="/admin/articlePhotoWebP/1">articlePhotoWebP</a><br>
					<a href="/admin/articleRegenererVignette/1">articleRegenererVignette</a><br>
					<a href="/admin/articleGenererFromIdee/1">articleGenererFromIdee</a><br>
					<a href="/admin/articleDoGenerer">articleDoGenerer</a><br>
					<a href="/admin/articleListe">articleListe</a><br>
					<a href="/admin/commentaireSupprimer/1/1">commentaireSupprimer</a><br>
					<a href="/admin/redirectArticle">redirectArticle</a><br>
					<a href="/admin/categorieListe">categorieListe</a><br>
					<a href="/admin/ideeCategorieModifier">ideeCategorieModifier</a><br>
					<a href="/admin/ideeCategorie/1">ideeCategorie</a><br>
					<a href="/admin/ideeSupprimer/1/1/1">ideeSupprimer</a><br>
					<a href="/admin/ideeListe">ideeListe</a><br>
					<a href="/admin/ideeAjouter">ideeAjouter</a><br>
					<a href="/admin/ideeGenerer/1">ideeGenerer</a><br>
					<a href="/admin/cronLog">cronLog</a><br>
					<a href="/admin/supprimerCronLog/1">supprimerCronLog</a><br>
					<a href="/admin/doDeleteCronLog">doDeleteCronLog</a><br>
					<a href="/admin/seoSitemap">seoSitemap</a><br>
					<a href="/admin/setArticleChamp">setArticleChamp</a><br>
					<a href="/admin/ajaxSetArticleChamp">ajaxSetArticleChamp</a><br>
				</div>
				<div class="cell small-3">
					<?php
					$controler = "article";$tab = array("archive/1/1", "actu");genererLien($controler, $tab);
					$controler = "categorie";$tab = array("theme/1/1");genererLien($controler, $tab);
					$controler = "commentaire";$tab = array("post");genererLien($controler, $tab);
					$controler = "contact";$tab = array("doContacter");genererLien($controler, $tab);
					$controler = "default";$tab = array("recherche", "_404");genererLien($controler, $tab);
					$controler = "login";$tab = array("doLogin", "logout");genererLien($controler, $tab);
					$controler = "seoSitemap";$tab = array("generer/1");genererLien($controler, $tab);
					?>
				</div>
				<div class="cell small-3">
					<?php
					$controler = "utilisateur";$tab = array("doActivation", "codeResend", "doInscription", "activation/1");	genererLien($controler, $tab);
					?>
				</div>
			</div>
		</div>
	</div>
</div>
