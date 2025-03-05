<?php
class FancyUtil {

	static function displayNbMotLettre($string) {
		$nb_mot = str_word_count($string);
		$nb_lettre = strlen($string);
		
		echo '<br><span class="text-orange bold">'.$nb_mot.' mots | '.$nb_lettre.' car.</span>';
	}
	
	static function getNbLettre($string) {
		return strlen($string);
	}
	
	static function getNbMot($string) {
		return str_word_count($string);
	}
	
	static function getRewriteUrl($texte) {
		$texte = trim($texte);
		$texte = str_replace("jusqu'à", "jusqu-a", $texte);
		$texte = str_replace("Comment ", "", $texte);
		$texte = str_replace("Les ", "", $texte);
		$texte = str_replace("La ", "", $texte);
		$texte = str_replace(",", "", $texte);
		$texte = str_replace(":", "", $texte);
		$texte = str_replace("?", "", $texte);
		$texte = str_replace("!", "", $texte);
		$texte = str_replace(";", "", $texte);
		
		$long = strlen($texte);
		
		$unwanted_array = array(
			'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
			'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
			'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
			'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
			'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' , 'œ'=>'oe',
		);
		
		$url = strtr( $texte, $unwanted_array );
		$url = strtolower($url);
		
		$url = str_replace("qu'est ce que c'est", "", $url);
		
		
		if ( $long > 50 ) {
			//$url = str_replace(" de ", " ", $url);
			$url = str_replace(" comment ", " ", $url);
			$url = str_replace(" la ", " ", $url);
			$url = str_replace(" les ", " ", $url);
			$url = str_replace(" des ", " ", $url);
			$url = str_replace(" votre ", " ", $url);
			$url = str_replace(" vos ", " ", $url);
			//$url = str_replace(" en ", " ", $url);
			$url = str_replace(" sa ", " ", $url);
			$url = str_replace(" le ", " ", $url);
			//$url = str_replace(" a ", ", $url);
			$url = str_replace(" du ", " ", $url);
			$url = str_replace(" et ", " ", $url);
		}
		
		$url = str_replace("'", " ", $url);
		$url = str_replace(" l ", " ", $url);
		$url = str_replace(" d ", " ", $url);
		
		$url = trim($url);
		
		$url = str_replace("   ", "-", $url);
		$url = str_replace("  ", "-", $url);
		$url = str_replace(" ", "-", $url);

		return $url;
	}
	
	static function getImagePath($article_id, $thumb) {
		$image_path = "";

		if ( $thumb ) {
			$image = "1-".$article_id."-thumb.";
		}else{
			$image = "1-".$article_id.".";
		}

		$image_path_webp = "/img/ilu/".$image."webp";

		if ( ! file_exists( RACINE_PATH.'/www'.$image_path_webp ) ) {
			$image_path = "/img/ilu/".$image."jpg";
			if ( ! file_exists( RACINE_PATH.'/www'.$image_path ) ) {
				$image_path = "/img/ilu/none.png" ;
			}
		}else{
			$image_path = $image_path_webp;
		}

		return $image_path;
	}
	
	static function getErreurPseudo($pseudo){
		$chaine = "";
		
		$chaine .= strpos($pseudo, "?");
		$chaine .= strpos($pseudo, "'");
		$chaine .= strpos($pseudo, '"');
		$chaine .= strpos($pseudo, "#");
		$chaine .= strpos($pseudo, "&");
		$chaine .= strpos($pseudo, " ");
		$chaine .= strpos($pseudo, "<");
		$chaine .= strpos($pseudo, "/");
		$chaine .= strpos($pseudo, "%");
		$chaine .= strpos($pseudo, "*");
		$chaine .= strpos($pseudo, "|");
		$chaine .= strpos($pseudo, "`");
		$chaine .= strpos($pseudo, "\\");
		
		if(strlen($chaine)>0) return true;
		else return false;
	}
	
	static function afficheAriane($ariane) {
		$html = "";

		foreach($ariane AS $page=>$path) {
			if ( !$path ) {
				$html .= $page;
			}else{
				$html .= '<a href="'.$path.'">'.$page.'</a>&nbsp;/&nbsp;' ;
			}
		}
		return '<i class="fa fa-home"></i>'.$html;

	}
	
	
	static function formaterEtNettoyerArticle($article) {
		$contient_div_deb = false;
		$contient_div_fin = false;
		$conclusion = false;
		
		$make_div_possible = true;
		$pos = strpos($article, "<div style");
		if ($pos !== false) {
			$make_div_possible = false;
		}
		
		$article = str_replace("&lt;", "<", $article);
		$article = str_replace("&gt;", ">", $article);
		$article = str_replace("&#039;", "'", $article);
		
		$article = str_replace("<h2>Introduction</h2>", "", $article);
		
		
		//Detecte la présence d'une conclusion dans l'article
		$pos = strpos($article, "En conclusion, ");
		if ($pos !== false) {
			$conclusion = true;
		}
		$pos = strpos($article, "<h2>Conclusion</h2>");
		if ($pos !== false) {
			$conclusion = true;
		}
		
		
		//Supprime la chaine <h1> et son contenu si elle existe
		$pos = strpos($article, "<h1>");
		if ($pos !== false) {
			//La balise <h1> est présente dans le texte.";
			$pos = strpos($article, "</h1>");
			$article = substr($article, $pos + 5);
		} 



		//Ajoute un décalage sur la gauche si il y a un H2 et une conclusion
		$pos = strpos($article, "<h2>");
		if ($pos !== false && $conclusion && $make_div_possible) {
			$pattern = "/<h2>/";
			//$replacement = "<div style='margin-left:30px;'><h2>";
			$replacement = "<div class='article_margin'><h2>";
			$article = preg_replace($pattern, $replacement, $article, 1);
			$contient_div_deb = true;
		}
		
		
	
		$pos = strpos($article, "<div style");
		if ($pos !== false) {
			$contient_div_deb = true;
		}
		
		
		//Supprime le <h2>Conclusion</h2>
		if ( $contient_div_deb ) {
			$article = str_replace("<h2>Conclusion</h2>", "</div><br><br>", $article);
		}else{
			$article = str_replace("<h2>Conclusion</h2>", "<br><br>", $article);
		}
		
		
		$pos = strpos($article, "</div>");
		if ($pos !== false) {
			$contient_div_fin = true;
		}
		
		
		///Supprime la chaine "En conclusion, "et son contenu si elle existe et met la prochaine lettre en majuscule
		$chaine_a_sup = "En conclusion, ";
		$pos = strpos($article, $chaine_a_sup);
		if ($pos !== false) {
			
			$pos = strpos($article, $chaine_a_sup);
			$texte_recupere = substr($article, $pos, 16);
			$texte_modifie = substr_replace($texte_recupere, strtoupper(substr($texte_recupere, -1)), -1);
			$article = str_replace($texte_recupere, $texte_modifie, $article);
			
			if ( $contient_div_deb && ! $contient_div_fin ) {
				$article = str_replace($chaine_a_sup, "</div><br>", $article);
			}else{
				$article = str_replace($chaine_a_sup, "<br><br>", $article);
			}
		}
		
		
		$pos = strpos($article, "</div>");
		if ($pos !== false) {
			$contient_div_fin = true;
		}
	
		//Remplace les sauts de ligne
		$article = str_replace("\n", "###", $article);
		$article = str_replace("###\r###", "@@@", $article);
		$article = str_replace("@@@", "<br>", $article);
		$article = str_replace("###", "", $article);
		$article = str_replace("<h2>", "<br><h2>", $article);
		$article = str_replace("</h2>\r", "</h2>", $article);
		
		
		$article = ltrim($article, "\n");//Supprime les saut de ligne restant en début de chaine
		$article = ltrim($article, "\n");//Supprime les saut de ligne restant en début de chaine
		
		$article = ltrim($article, "\r");//Supprime les saut de ligne restant en début de chaine
		$article = ltrim($article, "\r");//Supprime les saut de ligne restant en début de chaine
		
		if (strpos($article, "<br>") === 0) $article = substr($article, 4); //Supprime les saut de ligne restant en début de chaine
		if (strpos($article, "<br>") === 0) $article = substr($article, 4); //Supprime les saut de ligne restant en début de chaine
				
		$article = str_replace("</h2><br>", "</h2>", $article);
		$article = str_replace("<br><br>", "<br>", $article);
		
		$article = str_replace("<br><div style", "<div style", $article);
		$article = str_replace("30px;'><br><h2>", "30px;'><h2>", $article);

		
		//Si il y a un DIV ouvrant quand meme, et pas de DIV fermant, alors on ferme
		if ( $contient_div_deb && ! $contient_div_fin ) {
			$article .= '</div>';
		}
		
		
		//BUG parfois il me bouffe le premier caractère du H2 ou du DIV...???
		if (substr($article, 0, 3) === "h2>") {
			$article = "<" . $article;
		}
		if (substr($article, 0, 9) === "div style") {
			$article = "<" . $article;
		}
		
		return $article;

	}
	
	private function toAscii($str, $replace=array(), $delimiter='-') {
		$speciaux=array("ê","é","è","à","ù","î","ï","ô","î","É","ç","â","ò","ì");
		$nouveaux=array("e","e","e","a","u","i","i","o","i","e","c","a","o","i");
		$str = strtolower(str_replace($speciaux, $nouveaux, trim($str)));
		
		if( !empty($replace) ) {
			$str = str_replace((array)$replace, ' ', $str);
		}
		
		//$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = $str;
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
		
		//Si ca commence par un tiret, on supprime le tiret du début
		if ( substr($clean,0,1) == $delimiter ) $clean = substr($clean,1);
		
		return $clean;
	}
	
	
		
	static function getExtrait($chaine, $nb) {
		$texte = strip_tags($chaine);

		if (strlen( $texte ) > $nb) {
			$texte = substr($texte,0,$nb);
			$espace = strrpos($texte, " ");
			$texte = substr($texte, 0, $espace)."..."; 
		}
		return $texte;
	}
	
	
	
	public function wrap($text, $long = 50) {
		return wordwrap($text , $long, "<br>", true);
	}
	
	
	static function transformerEnMotCle($string) {
		$string = str_replace('"', '', $string);
		$string = str_replace(",", " ", $string);
		$string = str_replace("'", "", $string);
		$string = str_replace(':', '', $string);
		$string = str_replace('.', '', $string);
		$string = str_replace('(', '', $string);
		$string = str_replace(')', '', $string);
		$string = str_replace('[', '', $string);
		$string = str_replace(']', '', $string);
		$string = str_replace("'", "", $string);
		$string = str_replace(' ', '-', $string);
		$string = str_replace('----', '-', $string);
		$string = str_replace('---', '-', $string);
		$string = str_replace('--', '-', $string);
		
		$search  = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
		$string = str_replace($search, "", $string);

		$search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
		$replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
		
		$string = str_replace(' ', '-', $string);
		$string = str_replace('----', '-', $string);
		$string = str_replace('---', '-', $string);
		$string = str_replace('--', '-', $string);
		
		$string = str_replace($search, $replace, $string);
		$string = strtolower($string);
		
		return $string;
	}
	
	static function makeMotcle($string) {
		
		//On supprime tous les retours chariots éventuels
		$string = str_replace(array("\r", "\n"), '', $string);
		$string = self::transformerEnMotCle($string);
		$string = self::nettoyerTiret($string);

		$motcles = "";

		$nb_sep = substr_count($string, '-');
		
		if ( $nb_sep > 5 ) { //Si ya trop de motclés on raccourcit
			$tab = explode("-", $string);
			$cpt = 0;
			foreach($tab as $string) {
	
				if ( $string ) {
					$valeur = self::nettoyerTiret($string);

					
					if ( $cpt < 5 ) { //5 grand momt clé  max
						if ( strlen($valeur) > 3 ) { 
							$cpt++;
							$motcles .= $valeur."-";
						}elseif( strlen($valeur) == 3 || strlen($valeur) == 2 ) {
							$motcles .= $valeur."-";
						}
					}

				}
			}
		}else{
			$motcles = $string;
		}
		

		if (substr($motcles, -1) === "-") $motcles = substr($motcles, 0, -1); 
		
		
		
		return $motcles;
	}
	
	
	
	
	static function getIdeeListeArray($liste) {

		$liste = str_replace("&#039;", "'", $liste);
		$tab_temp = explode("#", $liste);
		$tab = array();
		
		foreach($tab_temp as $string) {
			$idee = self::nettoyerIdeeString($string);
			if ( strlen($idee)>25 && strlen($idee)<100  ) {
				$tab[] = trim($idee);
			}
		}
		
		return $tab;
	}
	
	static function nettoyerTiret($string) {
		$string = trim($string);
		if ( substr($string,0,1) == "-" ) $string = substr($string,1); //Si ca commence par un tiret, on supprime le tiret du début
		if (substr($string, -1) === "-") $string = substr($string, 0, -1); //On retire le tiret de la fin
		return $string;
	}
	
	static function nettoyerIdeeString($string) {
		$string = self::nettoyerTiret($string);
		
		$asup = array();
		for ($i = 1; $i <= 60; $i++) {
			$asup[] = $i."-";
		}
		
		for ($i = 1; $i <= 60; $i++) {
			$asup[] = $i.".";
		}

		$string = str_replace($asup, "", trim($string));

		return $string;
	}
	
	
	/*
	public function getSubText($text, $nbcar=200, $sep='.', $miniscule=false) {
		$s = substr($text, 0, $nbcar);
		$result = substr($s, 0, strrpos($s, $sep));
		
		if ( $miniscule ) $result = mb_convert_case($result, MB_CASE_LOWER, "UTF-8");
		
		return $result;
	}
	*/
	
	static function getSimpleDomain() {
		$domain = $_SERVER['HTTP_HOST'];

		if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,9})$/i', $domain, $regs)) {
			return $regs['domain'];
		}else{
			return "webcorse.com"; //en cas d'erreur on retourne un domaine de chez nous
		}
	}
	
	static function getIp() {
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		}elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {// IP derrière un proxy
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else {	// Sinon : IP normale
			return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
		}
	}
	
	static function getSuivPrec($libelle, $title, $url_base, $page, $limit, $nb_total, $pagination) {
		$lib_prec = '<i class="fa-solid fa-circle-arrow-left"></i>&nbsp;'.$limit.' Précedents';
		$lib_suiv = $limit.' Suivants&nbsp;<i class="fa-solid fa-circle-arrow-right"></i>';
		
		if( $page < 1 ) $page = 1;
		
		if ( $page==1) {
			$url_prec = $url_base;
			$title_prec = $title;
		}else{
			$url_prec = $url_base."/".($page-1);
			$title_prec = $title." - page ".($page-1);
		}
		
		$url_suiv = $url_base."/".($page+1);
		$title_suiv = $title." - page ".($page+1);
		?>

		<?php if ( $pagination ) : ?>
			<div class="box_suiv_prec">
				<div class="text-center">
	
					<div class="pages">
						
						<div class="page_text">
							<?php
							if ( $page < 1 ) $cpt_from = 1;
							else $cpt_from = (($page-1) * $limit)+1;
						
							$cpt_to = ($cpt_from+$limit)-1;
							if ( $cpt_to > $nb_total ) $cpt_to = $nb_total;
							?>
							Page <?php echo $page ?> | <?php echo $libelle ?> <?php echo $cpt_from ?> à <?php echo $cpt_to ?> sur <?php echo $nb_total ?>
						</div>
						<?php
						$page_nb = ceil($nb_total / $limit);
						$page_courante = "";
						?>
						
						<?php if ( $page > 1 ) : ?>
							<div class="page">
								<a href="<?php echo $url_prec ?>" title="<?php echo $title ?> - page précédente"><i class="fas fa-arrow-circle-left"></i></a>
							</div>
						<?php endif; ?>
						
						
						<?php
						for ( $i=1; $i<=$page_nb; $i++) :
							$class_select="";
							$link_display = true;
							$link_url = $url_base."/".$i;
				
							if ( $i==$page ) {
								$class_select=" page_selected";
								$link_display = false;
							}
							?>
							<div class="page<?php echo $class_select ?>">
								<?php if ( $link_display ) : ?>
									<a href="<?php echo $link_url ?>" title="<?php echo $title ?> - page <?php echo $i ?>"><?php echo $i ?></a>
								<?php else: ?>
									<?php echo $i ?>
								<?php endif; ?>
							</div>
							<?php
						endfor;
						?>
						
						
						<?php if ( ($page * $limit) < $nb_total  ) : ?>
							<div class="page">
								<a href="<?php echo $url_suiv ?>" title="<?php echo $title ?> - page suivante"><i class="fas fa-arrow-circle-right"></i></a>
							</div>
						<?php endif; ?>

					</div>

				</div>
			</div>
		<?php else: ?>
			<div class="box_suiv_prec">
				<div class="grid-x"> 
					<div class="cell small-3 col_left text-left">
						<?php if ( $page > 1 ) : ?>
						<a href="<?php echo $url_prec ?>" title="<?php echo $title_prec ?>"><i class="fa fa-left-circled"></i><?php echo $lib_prec ?></a>
						<?php else: ?>
						&nbsp;
						<?php endif; ?>
					</div>
					<div class="cell small-6 text-center">
						<div class="col_page">
						<?php
						if ( $page < 1 ) $cpt_from = 1;
						else $cpt_from = (($page-1) * $limit)+1;
						
						$cpt_to = ($cpt_from+$limit)-1;
						if ( $cpt_to > $nb_total ) $cpt_to = $nb_total;
						?>
						
						<?php
						/*
						Page <?php echo $page ?> | <?php echo $libelle ?> <?php echo $cpt_from ?> à <?php echo $cpt_to ?> sur <?php echo $nb_total ?>
						*/
						?>
						Page <?php echo $page ?> | <?php echo $libelle ?> <?php echo $cpt_from ?> à <?php echo $cpt_to ?>
						</div>
					</div>
					<div class="cell small-3 col_right text-right">
						<?php if ( ($page * $limit) < $nb_total  ) : ?>
						<a href="<?php echo $url_suiv ?>" title="<?php echo $title_suiv ?>"><?php echo $lib_suiv ?><i class="fa fa-right-circled"></i></a>
						<?php else: ?>
						&nbsp;
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php
	}
	
	//raccourcir_texte
	static function format_texte_taille_car($libelle, $taille) {
		//$this->FancyUtil->format_texte_taille_car
		return substr($libelle, 0, $taille);
	}
	
	static function getTxt($txt) {
		return htmlentities($txt, ENT_QUOTES, 'UTF-8');
	}
	
	
	

	
	static function format_big_number($num) {
		return number_format( $num, 0, ',', ' ' );
	}
	
	
	
	
	static function getFormatTexte($chaine){
		$chaine = str_replace('"', '-', $chaine);
		$chaine = str_replace('&', ' et ', $chaine);
		$chaine = str_replace('<', '-', $chaine);
		$chaine = str_replace('/', '-', $chaine);
				
		return $chaine;
	}
	
	

	
	
	

}
