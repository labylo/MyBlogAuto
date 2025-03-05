<?php
class FancyDate {

	public function getJourRestant($date){
		
		if ( ! $this->isValidDate($date) ) return false;
		
		$result = date('d/m/Y',strtotime($date));
		$nb_jour = floor((strtotime($date) - strtotime(date("Y-m-d"))) / 86400);
		/*
		if ($nb_jour > 1){
			$result.=" (dans $nb_jour jours)";
		}else{
			$result.=" (Aujourd'hui)";
		}
		return $result;
		*/
		return $nb_jour;
	}	
	
	static function getJourNum() {
		return date('w');
	}
	
	static function getJourId($lib="lundi") {
		
		switch ($lib) {
			case "dimanche":return 0;break;
			case "lundi":return 1;break;
			case "mardi":return 2;break;
			case "mercredi":return 3;break;
			case "jeudi":return 4;break;
			case "vendredi":return 5;break;
			case "samedi":return 6;break;
			default:return 0;break;
		}
	}
	
	
	static function getJourLibelle($d=1) {
		
		//TODO a supprimer !!!!
		//return "mardi";
		//exit;
		
		
		switch ($d) {
			case 0:return "dimanche";break;
			case 1:return "lundi";break;
			case 2:return "mardi";break;
			case 3:return "mercredi";break;
			case 4:return "jeudi";break;
			case 5:return "vendredi";break;
			case 6:return "samedi";break;
			default:return false;break;
		}
	}

	static function getGtNumJour() {
		//lundi 1, 	mardi 2, mercredi 3, jeudi 4, vendredi 5, samedi 6, dimanche 0
		$jour = date("w");	
		if ($jour == 1) $jour = 0; //Le lundi est traité comme le dimanche : inscription autorisée !
		//if ( ! PRODUCTION ) $jour = 0; //TODO_TRICHE : EN DEV : Permet de tester le GT selon le jour de la semaine
		
		return $jour;
	}
	
	static function getNumWeek() {
		$date = new DateTime(date(DATE_ISO));
		return $date->format("W");;
	}

	static function isValidDate($date, $format = 'Y-m-d H:i:s') {
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	
	public function isSameDay($date1,$date2){
			if (!$date1 || ! $date2){
			return false;
		}
		return date('Y-m-d',strtotime($date1)) == date('Y-m-d',strtotime($date2));
	}
	
	public function isSameMonth($date1,$date2){
		if (!$date1 || ! $date2){
			return false;
		}
		return date('Y-m',strtotime($date1)) == date('Y-m',strtotime($date2));
	}
	
	public function isSameYear($date1,$date2){
		if (!$date1 || ! $date2){
			return false;
		}
		return date('Y',strtotime($date1)) == date('Y',strtotime($date2));
	}
	
		
	public function getDay($date_iso){
		$time = strtotime($date_iso);
		$now = time();
		
		$date = date('Y-m-d',$time); 
		$nb_jour = (strtotime($date) - strtotime(date("Y-m-d"))) / 86400;
		if ($nb_jour == 0){
			return "Aujourd'hui";
		}
		
		if ($nb_jour == 1){
			return "Demain";
		}
		
		return $this->getFormatedDate($date_iso, "l d");
	}
	
	public function hasTime($date){
		return (date('H:i',strtotime($date)) != '00:00');
	}

	
	private function getFormatedDate($date, $format) {
		$english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		$french_days = array('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche');
		$english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$french_months = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');

		return str_replace($english_months, $french_months, str_replace($english_days, $french_days, date($format, strtotime($date) ) ) );
	}

	
	public function getMySqlDate($date_iso) {
		return date("Y-m-d H:i:s",strtotime(str_replace('/','-',$date_iso)));
	}
	
	public function getFrenchDay($date_iso) {
		return $this->getFormatedDate($date_iso,"l");
	}
	
		
	static function get_dernier_jour_du_mois($mois=0){
		if($mois==0) $mois= mktime(0,0,0,date('m'),1,date('y'));
		else $mois= mktime(0,0,0,intval($mois),1,date('y'));
		return date("t",$mois);
	}

	
	public function affiche_dhs($date_iso) {
		if ( $this->isValidDate($date_iso) ) {
			return $this->getFormatedDate($date_iso,"d/m/Y h:i:s");
		}else{
			return "-";
		}
	}
	
	
	public function affiche_dh($date_iso) {
		if ( $this->isValidDate($date_iso) ) {
			return $this->getFormatedDate($date_iso,"d/m/Y h\hi");
		}else{
			return "-";
		}
	}
	
	public function affiche_diff($date1,$date2){
		$s = strtotime($date2) - strtotime($date1);
		return $this->affiche_minute($s);
	}
	



	function getYearDiff($date_old, $date_recente=false) {
		
		if ( ! $date_recente ) $date_recente=date('Y-m-d');
		
		$d1 = new DateTime($date_recente);
		$d2 = new DateTime($date_old);

		$diff = $d2->diff($d1);

		return $diff->y;
	}
		
	function affiche_diff_jour($date_old, $date_recente){
		$diff = (strtotime($date_recente) - strtotime($date_old));
		$jour = (($diff / 60) / 60) / 24;
		return round($jour,0);
	}
	
	
	
	public function affiche_minute($s){
		return sprintf("%d:%02d",floor($s/60),$s%60);
	} 
	
	public function getMinute($second){
		$s = $second % 60;
		$m = intval($second / 60);
		return  sprintf("%02d:%02d",$m,$s); 
	}
	
	public function getHMS($second){
		$s = $second % 60;
		$minute = intval($second / 60);
		$m = $minute % 60;
		$h = intval($minute/60);
		return sprintf("%02d:%02d:%02d",$h,$m,$s); 
	}
	

	public function affiche_date_simple($date){
		return $this->getFormatedDate($date,"d/m/Y");
	}

	static function affiche_heure($date) {
		/*
		if (defined('LANG') && LANG=='en_US'){
			$format = "h:i:s a" ;
		} else {
			$format = "H:i:s" ;
		}
		*/
		$format = "H:i:s";
		return date($format,strtotime($date));
	}
	
	public function getAsFacebook($date_iso){
		
		if ( ! $this->isValidDate($date_iso) ) 	return "Date non renseignée";

		$time = strtotime($date_iso);
		$now = time();
		
		$nb_jour = (strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",$time))) / 86400;
		
		
		if ($nb_jour == 1 ){
			return "hier &agrave; ". date("H:m",$time);
		}
		if ($nb_jour == 2 ){
			return "avant-hier &agrave; ". date("H:m",$time);
		}
	
		if ($nb_jour > 6){
			return $this->getFrenchDay($date_iso)." ".date("j",$time)." ".$this->getMonthString(date("n",$time));
			//return date("j",$time)." ".$this->getMonthString(date("n",$time))." ".date("Y, h:i",$time);
		}
		
		if ($nb_jour > 1){
			//return $this->getDayString(date("N",$time)).", ".date("h:i",$time); 
			return $this->getDayString(date("N",$time))." dernier"; 
		}
		
		
		$interval = $now - $time;
		
		if ($interval < 60){
			return "Il y a $interval secondes";
		}
		
		$minute = (int) ($interval / 60);
		
		if ( $minute == '1'){
			return "Il y a environ une minute";
		}
		if ($minute < 60){
			return "Il y a $minute minutes";
		}
		
		$heure = (int) ($minute / 60);
		if ( $heure == '1'){
			return "Il y a environ une heure";
		}
	
		return "Il y a $heure heures";
		
	}
	
		
	public function getDateSitemap($date_iso) {
		return $date_iso;
	}
	
	public function getDayString($day_num, $lang="fr"){
		$day_libelle = array('dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche');
		return $day_libelle[$day_num];
	}
	
	public function getMonthString($month_num, $lang="fr"){
		$mois_libelle = array(1=> 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
		return $mois_libelle[$month_num];
	}
	
	
	
	function affiche_date_JMA($date_iso) {
		/*
		$timestamp = strtotime($date_iso);
		$lib_mois = array(1 => "Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
		return date("d")." ".$lib_mois[date("n")]." ".date("Y", $timestamp);
		*/
		echo $this->getFormatedDate($date_iso, "l d F Y");
	}
		
	
	public function getDatePlusUnAn($date_mysql) {
		$future_date = date('d/m/Y', strtotime('+1 year', strtotime($date_mysql)) );
		return $future_date;
		
	}
	
	function calc_seconde($sec, $type="max"){
		$secondes = ($sec % 60); // On determine le nombre de secondes
		$sec = $sec - $secondes; // on eneleve le nombre de secondes de la duree
		$sec = ($sec / 60); // On convertit le tout en minute
		$minutes = ($sec % 60); // on determine le nombre de minutes
		$sec = $sec - $minutes; // On enleve le nombre de minutes de la duree
		$sec = ($sec / 60); // On convertit le tout en heure
		$heures = ($sec % 60); // On determine le nombre d'heure
		
		if($heures>0){
			$petite = $heures."h ".$minutes."m ".$secondes."s";
			$chaine = $heures. "heures ".$minutes." minutes et ".$secondes." secondes";
		}elseif($minutes>0){
			$petite = $minutes."m ".$secondes."s";
			$chaine = $minutes." minutes et ".$secondes." secondes";
		}else{
			$petite = $secondes."s";
			$chaine = $secondes." secondes";
		}
		
		if($type=="max")
			return $chaine;
		elseif($type=="mini"){
			return $petite;
		}else{
			return $chaine."(".$petite.")";
		}
		
	}

}
