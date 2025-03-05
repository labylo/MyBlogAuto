<?php 
class Recuperateur {
	
	private $tableauInput;
	
	public function __construct($tableauInput = null ){
		if (! $tableauInput){
			$tableauInput = $_REQUEST;
		}
		$this->tableauInput = $tableauInput;
	}
	
	public function getInt($name,$default = 0){
		return $this->doSomethingOnValueOrArray('intval',$this->get($name,$default));
	}
	
	public function getNoTrim($name, $default=""){
		if ( empty($this->tableauInput[$name])) {
			return $default;
		}
		
		$value = $this->tableauInput[$name];
		/*
		if (get_magic_quotes_gpc()){
			$value = $this->doSomethingOnValueOrArray("stripslashes",$value);
		}
		*/
		return $value;
	}
	
	public function get($name, $protect=true, $default="") {
		
		$value = $this->getNoTrim($name, $default);
		
		if ( $protect ) $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

		return $this->doSomethingOnValueOrArray("trim",$value);
	}
	

	
	private function doSomethingOnValueOrArray($something,$valueOrArray){
		if (is_array($valueOrArray)){
			return array_map($something,$valueOrArray);
		} 
		return $something($valueOrArray);
	}
	
	
	public function getFilePath($input_file_name){
		if (empty($_FILES[$input_file_name])){
			return false;
		}
		if ( ! $_FILES[$input_file_name]['tmp_name']){
			return false;
		}
		return $_FILES[$input_file_name]['tmp_name'];
	}
	
}
