<?php header("Content-type: text/html; charset=utf-8"); ?>
<!DOCTYPE html>
<html lang="fr">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta http-equiv="content-language" content="fr">

<title><?php echo $meta_title ?></title>
<meta name="description" content="<?php echo str_replace('"', '', $meta_description) ?>">

<?php if (isset($meta_robot) ) : ?>
<meta name="robots" content="<?php echo $meta_robot ?>">
<?php endif; ?>

<?php if (isset($meta_canon) ) : ?>
<?php 
if ( $meta_canon == "/" ) $meta_canon="";
?>
<link rel="canonical" href="<?php echo SITE_URL ?><?php echo $meta_canon ?>">
<?php endif; ?>

<link rel="apple-touch-icon" sizes="57x57" href="/img/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/img/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/img/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/img/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/img/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/img/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/img/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/img/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/img/favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/img/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
<link rel="manifest" href="/img/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/img/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

<link rel="stylesheet" type="text/css" href="/css/fontawesome-6/css/all.min.css">
<link rel="stylesheet" type="text/css" href="/css/foundation-6.7.5/css/foundation.min.css" media="screen">
<link rel="stylesheet" type="text/css" href="/css/style.css?v=<?php echo REFRESH_VERSION ?>" media="screen">

<?php if ($this->Connexion->isAdmin()) : ?>
<script src="/js/jquery-1.12.1.min.js"></script>
<script src="/js/jquery-ui-1.12.1.min.js"></script>
<?php
// CKE configurator : https://ckeditor.com/latest/samples/toolbarconfigurator/#advanced
?>
<script src="/js/ckeditor/ckeditor.js"></script>
<script>	
CKEDITOR.on('instanceReady', function(e) {
	e.editor.document.getBody().setStyle('background-color', '#eee');
	e.editor.document.getBody().setStyle('padding', '0');

});
</script>

<?php else: ?>
<script src="/js/jquery-3.6.3.min.js"></script>
<?php endif; ?>
<script src="/js/app.js?v=<?php echo REFRESH_VERSION ?>"></script>

<?php if ( PRODUCTION ) : ?>
<script>
  /* MyBlogAuto */
  var _paq = window._paq = window._paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//www.gojeu.com/";
	_paq.push(['setRequestMethod', 'POST']);
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '5']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<?php endif; ?>

</head>
<body>

<div class="menu_adw">
<div class="menu_adw">
	<ul>
	<li><a href="/accessibilite" accesskey="0">Aide à la navigation</a></li>
	<li><a href="#zone_nav" accesskey="3">Menu</a></li>
	<li><a href="#zone_contenu" accesskey="4">Contenu</a></li>
	</ul>
</div>
</div>

<header id="main_top">
	<div id="zone_date" class="hide-for-small-only">
		<div class="wrap">

			<div class="grid-x grid-margin-x">
				<div class="cell small-6">
				<?php 
				$date_lib = $this->FancyDate->affiche_date_JMA(date(DATE_ISO)); 
				?>
				</div>
				<div class="cell small-6 text-right">
					<?php if ($this->Connexion->isAdmin()) : ?>
						<a href="/admin"><i class="fa fa-cog"></i>Admin</a>&nbsp;&nbsp;|&nbsp;&nbsp;
					<?php endif; ?>
					
					<?php if ($this->Connexion->isConnected()) : ?>
						<a href="/utilisateur/compte"><i class="fa fa-user"></i><?php echo $this->Connexion->getPseudo(); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
						<a href="/login/logout" rel="nofollow"><i class="fa fa-sign-out-alt"></i>Déconnexion</a>
					<?php else: ?>
						<a href="/login" ><span class="web">Connexion</span></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<div id="zone_nav">
		<div class="wrap">
			<div class="grid-x">
				<div class="cell small-2 medium-1 mobile">
					<div id="btn_menu"><i class="fa-solid fa-bars"></i></div>
				</div>
				<div class="cell large-3 medium-2 small-8 text-left">
					<div class="logo">
						<a href="/" accesskey="1"><img src="/img/logo.png" alt="<?php echo SITE_NAME ?>"></a>
					</div>
				</div>
				<div class="cell small-2 mobile">
					&nbsp;
				</div>
				<div class="cell large-8 medium-12 small-12">
					<nav>
						<?php
						$class_accueil = ""; if ( isset($page_accueil) && $page_accueil ) $class_accueil = "class='select'";
						$class_4 = ""; if ( isset($categorie_id_menu) && $categorie_id_menu==4 ) $class_4 = "class='select'";
						$class_1 = ""; if ( isset($categorie_id_menu) && $categorie_id_menu==1 ) $class_1 = "class='select'";
						$class_8  = ""; if ( isset($categorie_id_menu) && $categorie_id_menu==8 ) $class_8 = "class='select'";
						$class_11 = ""; if ( isset($categorie_id_menu) && $categorie_id_menu==11 ) $class_11 = "class='select'";
						$class_7 = ""; if ( isset($categorie_id_menu) && $categorie_id_menu==74 ) $class_7 = "class='select'";
						?>
						<ul>
						<li><a <?php echo $class_accueil ?> href="/"><i class="fa fa-home"></i>Accueil</a></li>
						<li><a <?php echo $class_4 ?> href="/categorie/theme/competitions-sport-auto/4"><i class="fa-solid fa-stopwatch"></i>Sport auto</a></li>
						<li><a <?php echo $class_1 ?> href="/categorie/theme/biocarburant/1"><i class="fa-solid fa-leaf"></i>Biocarburant</a></li>
						<li><a <?php echo $class_8 ?> href="/categorie/theme/moto-deux-roues/8"><i class="fa-solid fa-motorcycle"></i>Moto</a></li>
						<li><a <?php echo $class_11 ?> href="/categorie/theme/tuning/11"><i class="fa-solid fa-bolt-lightning"></i>Tuning</a></li>
						<li><a <?php echo $class_7 ?> href="/categorie/theme/mecanique-technologie/7"><i class="fas fa-hand-point-right"></i>Technologie</a></li>
						</ul>
					</nav>
				</div>
				<div class="cell large-1">
					<div class="social_top">
						<a href="https://twitter.com/MyBlogAuto" target="_blank" style="color:#1D9BF0;"><i class="fa-brands fa-square-twitter fa-2xl"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>

<main id="main">
	<div class="wrap">
		<?php if ( isset($ariane) ) : ?>
		<div class="ariane">
			<div class="grid-x grid-margin-x">
				<div class="cell small-12">
					<?php echo $this->FancyUtil->afficheAriane($ariane); ?>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<?php if ( isset($page_accueil) && $page_accueil ) : ?>
			<h1 class="visual_hidden"><?php echo $h1 ?></h1>
		<?php endif; ?>

		<?php if ($this->Connexion->isAdmin()) : ?>
		<div class="admin_menu">
			
				<div class="bloc">
					<div class="bloc_titre" style="background-color:#767676;">
						<h2 style="padding:8px;color:#fff;">ADMINISTRATION</h2>
					</div>
					<div class="bloc_content" style="background-color:#FDFDD5;border:2px solid #767676;">
					
						<ul>
							<li><a href="/admin/articleEcrire">Ecrire un article</a></li>
						</ul>
						<hr>
						<ul>
							<li><a href="/admin/articleGenerer">Générer un article</a></li>
							<li><a href="/admin/articleListe">Liste des articles</a></li>
							<li><a href="/admin/categorieListe">Liste des Catégories</a></li>
							<li><a href="/admin/ideeListe">Liste des idées</a></li>
							<li><a href="/admin/cronLog">Voir les logs du CRON</a></li>
							<li><a href="/admin/checkChatGpt">Tester CHAT GPT</a></li>
						</ul>
						<hr>
						<ul>
							<li><a href="/admin/verifUrl">Vérif Mot-clé URL</a></li>
						</ul>
						<hr>
						<ul>
							<li><a href="/admin/seoSitemap">Voir le sitemap</a></li>
						</ul>
						<hr>
						<ul>
							<li><a href="/admin/test">Tester une fonction</a></li>
						</ul>
					</div>
				</div>
		</div>
		<?php endif; ?>
		
		<div id="zone_contenu">
		<?php $this->render($template_milieu); ?>
		</div>
	</div>
</main>

<footer>
	<div class="footer_menu">
		<div class="wrap">
			<div class="grid-x grid-margin-x">
				
				<div class="cell large-9 medium-6 small-12">
					<h3>Catégories populaires</h3>
										
					<div class="grid-x grid-margin-x">
					<?php
					$cpt=0;
					foreach($infoCategorieAccueil as $cat) : 
					$cpt++;
						if ( $cpt==1 ) : ?><div class="cell large-4 medium-12 small-12"><ul><?php endif;
						if ( $cpt==5 ) : ?></ul></div><div class="cell large-4 medium-12 small-12"><ul><?php endif;
						if ( $cpt==9 ) : ?></ul></div><div class="cell large-4 medium-12 small-12"><ul><?php endif;
						?>
						<li><a href="/categorie/theme/<?php echo $cat['motcle'] ?>/<?php echo $cat['categorie_id'] ?>"><i class="<?php echo $cat['fa_icone'] ?>"></i><?php echo $cat['categorie'] ?></a></li>
					<?php 
						if ( $cpt==12 ) : ?></ul></div><?php endif;
					endforeach; 
					?>
					</div>
				
				</div>
				<div class="cell large-3 medium-6 small-12">
					<a href="/" class="logo"><img src="/img/logo_footer.png" alt="Accueil"></a>
					<br><br>
					<ul>
						<li><a href="/"><i class="fa fa-home"></i>Accueil</a></li>
						<li><a href="/accessibilite"><i class="fa-solid fa-wheelchair-move"></i>Accessibilité</a></li>
						<li><a href="/contact" accesskey="2"><i class="fa-solid fa-envelope"></i>Contact</a></li>
						<li><a href="https://twitter.com/MyBlogAuto" target="_blank"><i class="fa-brands fa-twitter fa-xl" style="color:#1D9BF0;"></i>Suivez-nous</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	
	<div class="footer_copyright">
		<div class="wrap">
			<?php echo SITE_NAME ?> - &copy;<?php echo date('Y') ?>
			<?php if ($this->Connexion->isAdmin()) : ?>
			&nbsp;|&nbsp;Requètes SQL : <?php echo $this->SQLQuery->getNbReq() ?><?php endif; ?>
			&nbsp;|&nbsp;Page générée en <?php echo $this->Timer->getTime() ?>ms
		</div>
	</div>

</footer>


<div class="back_to_top">
<a href="#main_top" title="haut de page"><i class="fa fa-arrow-circle-up"></i></a>
</div>

<div id="ombre"></div>

<?php /*if ($this->Connexion->isAdmin()) : ?>
<script>

ClassicEditor
	.create( document.querySelector( '#editor' ), {
        
    } )
	.catch( error => {
		console.error( error );
	} );
	

	
</script>
<?php endif; */?>


<?php  if ( DEV && ! PRODUCTION ) : ?>
<div style="position:fixed;bottom:0;left:0;padding:16px;background:#6e267b;color:white;">
<b>Développement</b><br>
Connected : <?php echo $this->Connexion->isConnected() ?><br>
USER_ID : #<?php echo $this->Connexion->getUtilisateurId() ?> (<?php echo $this->Connexion->getSecure() ?>)<br>
Pseudo : <?php echo $this->Connexion->getPseudo() ?><br>
Droit : <?php echo $this->Connexion->getDroit() ?><br>
Email : <?php echo $this->Connexion->getEmail() ?>
</div>
<?php endif; ?>

</body>
</html>