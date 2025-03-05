<?php 
//$this->PasswordGenerator->getPassword(10);

class PasswordGenerator {
	
	const NB_SIGNE_DEFAULT = 10;

	const SIGNE = "123456789abcdefghjkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ";


	private $signe;
	private $lengthSigne;
	
	public function __construct(){
		$this->setSigne(self::SIGNE);
	}
	
	public function setSigne($signe){
		$this->signe = $signe;
		$this->lengthSigne = strlen($this->signe);	
	}
	
	public function getPassword($nb_signe = 0){
		if ($nb_signe == 0){
			$nb_signe = self::NB_SIGNE_DEFAULT;
		}
		$password = "";
		for($i=0; $i<$nb_signe; $i++){
			$password.= $this->getLettre();
		}
		return $password;
	}
	
	private function getLettre(){
		return $this->signe[rand(0,$this->lengthSigne - 1)];		
	}	
}
