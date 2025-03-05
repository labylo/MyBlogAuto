<?php
class Controler {

	private $objectInstancier;
	
	private $selectedView;
	private $viewParameter;
	
	public function __construct(){
		global $objectInstancier;
		$this->objectInstancier = $objectInstancier;
		$this->viewParameter = array();
	}

	//Appeler avant l'action
	public function _before($controler,$action,array $param){}
	
	//Appeler après l'action
	public function _after($controler,$action,array $param){}
	
	public function __get($key){
		if (isset($this->viewParameter[$key])){
			return $this->viewParameter[$key];
		}
		return $this->objectInstancier->$key;
	}

	public function __set($key,$value){
		$this->setViewParameter($key, $value);
	}
	
	public function isViewParameter($key){
		return isset($this->viewParameter[$key]);
	}
	
	public function setViewParameter($key,$value){
		$this->viewParameter[$key] = $value;
	}
	
	public function getViewParameter(){
		return $this->viewParameter;
	}
	
	public function selectView($view){
		$this->selectedView = $view;
	}
	
	public function getSelectedView(){
		return $this->selectedView;
	}
	

	public function redirect($to = ""){
		$this->Path->redirect($to);
	} 

	
}
