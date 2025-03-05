<?php
//Controle TOKEN : terminé

class MailControler extends ApplicationControler {

	private function getDateLib() {
		$month=date('m');
		$year=date('Y');
		if($month==1) $lastmonth=12;
		else $lastmonth=$month-1;
		
		if ( $lastmonth == 12 ) $year=$year-1;
		return $this->FancyDate->getMonthString($lastmonth). " " .$year;
	}
	
	

	
	public function sendMailCronCategorieAlertWebmaster($html, $titre) {
		$this->FancyMail->doSendAlertWebmaster($html, $titre);
	}
	
	public function sendMailCronAlertWebmaster($log, $lib_duree, $lib_cron) {
		
		$html = "";
		$html .= "Le ".$lib_cron." du ".date('d/m/Y H:i')." a duré ".$lib_duree.".<br/>";
		$html .= $log;

		$titre =  SITE_NAME." - ".$lib_cron;
		
		$this->FancyMail->doSendAlertWebmaster($html, $titre);

	}
	
	
	
}
