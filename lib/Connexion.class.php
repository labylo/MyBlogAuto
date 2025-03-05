<?php
class Connexion {
	
	
	
	public function setConnexion(array $user){
		$_SESSION[PREF_SESS]['connexion']['connected'] = true;
		$_SESSION[PREF_SESS]['connexion']['pseudo'] = $user['pseudo'];
		$_SESSION[PREF_SESS]['connexion']['utilisateur_id'] = $user['utilisateur_id'];
		$_SESSION[PREF_SESS]['connexion']['secure'] = $user['secure'];
		$_SESSION[PREF_SESS]['connexion']['email'] = $user['email'];
		$_SESSION[PREF_SESS]['connexion']['droit'] = $user['droit'];

	}
	
	public function setInfo($info, $value) {
		if ( $this->isConnected() ) {
			$_SESSION[PREF_SESS]['connexion'][$info] = $value;
		}
	}
		
	public function isConnected(){
		return isset($_SESSION[PREF_SESS]['connexion']['connected']);
	}
	
	public function isAdmin(){
		if ( $this->getDroit() >= U_ADMIN ) {
			return true;
		}else{
			return false;
		}
	}
	
	public function getPseudo() {
		return $this->getInfo("pseudo");
	}
	
	public function getAdminId() {
		return $this->getInfo("id_admin");
	}
	
	public function getUtilisateurId(){
		return $this->getInfo("utilisateur_id");
	}
	
	public function getSecure(){
		return $this->getInfo("secure");
	}	
	
	public function getEmail(){
		return $this->getInfo("email");
	}
	
	
	public function getDroit(){
		return $this->getInfo("droit");
	}	
	
	
	
	private function getInfo($info){
		
		if (! $this->isConnected()){
			return false;
		}
		
		if ( ! isset ( $_SESSION[PREF_SESS]['connexion'][$info] ) ) {
			return 0;
		} else {
			return $_SESSION[PREF_SESS]['connexion'][$info];
		}
	}
	

	public function deconnexion(){
		unset($_SESSION[PREF_SESS]['connexion']);
		unset($_SESSION[PREF_SESS]['azure_ad']);
	}
	

	
	public function getToken(){

		if (empty($_SESSION[PREF_SESS]['token'])){
			$_SESSION[PREF_SESS]['token'] = sha1(mt_rand(0,mt_getrandmax()));
		}
		return $_SESSION[PREF_SESS]['token'];
	
	
	}
	
	public function displayTokenField(){
		?>
		<input type="hidden" name="token" value="<?php hecho($this->getToken()) ?>">
		<?php 
	}
	
	public function verifToken($token = false){
		if (! $token && isset($_POST['token'])){
			$token = $_POST['token'];
		}
		return  $token == $this->getToken();
	}
}
