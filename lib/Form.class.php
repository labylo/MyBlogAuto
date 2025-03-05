<?php
class Form {
	

	static function isReCaptchaValid($response, $remoteip = null) {
		if (empty($response)) { return false; }
		

		$api_url = "https://www.google.com/recaptcha/api/siteverify?secret=".GOOGLE_CAPTCHA_SECRET."&response=".$response."&remoteip=".$remoteip;
		
		$decode = json_decode(file_get_contents($api_url), true);
		if ($decode['success'] == true) { return true; } else {	return false;}
	}	
	
	//Gère le système d'erreur sur les formulaires
	
	private function getInfo($champ){

		if ( ! isset ( $_SESSION[PREF_SESS]['form'][$champ] ) ) {
			return false;
		} else {
			return $_SESSION[PREF_SESS]['form'][$champ];
		}
	}
	

	
	public function existErr() {
		if ( isset ( $_SESSION[PREF_SESS]['form'] ) ) {
			return true;
		}else{
			return false;
		}
	}
	
	public function setErr($champ, $err) {
		$_SESSION[PREF_SESS]['form']['err_'.$champ] = $err;
	}
	
	public function setVal($champ, $val) {
		$_SESSION[PREF_SESS]['form']['val_'.$champ] = $val;
	}
	
	public function getErr($champ) {
		return $this->getInfo('err_'.$champ);
	}
	
	public function getVal($champ) {
		return $this->getInfo('val_'.$champ);
	}
	
	public function setReset() {
		unset ($_SESSION[PREF_SESS]['form']); 
	}
	

}
