<?php
class Gabarit {
	
	private $viewParameter;
	private $objectInstancier;
	
	public function __construct(ObjectInstancier $objectInstancier){
		$this->viewParameter = array();
		$this->objectInstancier = $objectInstancier;
	}
	
	public function __set($key,$value){
		$this->viewParameter[$key] = $value;
	}
	
	public function setViewParameter($key,$value){
		$this->viewParameter[$key] = $value;
	}
	
	public function setParameters(array $parameter){
		$this->viewParameter = array_merge($this->viewParameter,$parameter); 
	}
	
	public function render($template){	
					
		//echo "3 - ".$template;exit;
		
		foreach($this->viewParameter as $key => $value){
			$$key = $value;
		}		

		$template_path = $this->getTemplatePath($template);
		
		include($template_path);
	}
	
	public function getTemplatePath($template){
		if (is_array($this->template_path)){ 
			$template_path = $this->template_path;
		} else {
			$template_path = array($this->template_path);
		}
		
		foreach($template_path as $path){
			if (file_exists($path."/$template.php")){
				return $path . "/$template.php";
			}
		}
		
		return false;
	}
	
	
	public function __get($key){
		if (isset($this->viewParameter[$key])){
			return $this->viewParameter[$key];
		}
		return $this->objectInstancier->$key;
	}
}
