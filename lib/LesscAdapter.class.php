<?php
require_once(__DIR__."/lessc.inc.php");

class LesscAdapter {
	
	/*
	private $enable_lessc;
	
	public function __construct($enable_lessc = false){
		$this->enable_lessc = $enable_lessc;
	}
	*/
	
	public function createCSS($less_file, $css_file){
		//if ($this->enable_lessc){
			$this->forceCreateCSS($less_file, $css_file);
		//}
	}
	
	public function forceCreateCSS($less_file,$css_file){
		lessc::ccompile($less_file, $css_file);
	}
	
}
