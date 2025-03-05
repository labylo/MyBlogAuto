<?php
class Path {
	
	private $site_index;
	private $ressource_url;
	private $echotest;
	
	public function getRessourceUrl($to) {
		return $this->ressource_url."/".$to;
	}
	
	public function __construct($site_index,$ressource_url){
		$this->site_index = trim($site_index,"/");
		$this->ressource_url = trim($ressource_url,"/");
		$this->echotest = false;
	}
	
	public function path($to = "",$encode=true,$send=true){
		if ( $send ) echo $this->getPath($to,$encode);
		else return $this->getPath($to,$encode);
	}
	
	public function getRessourcePath($absolute_path){
		return $this->ressource_url . $absolute_path;
	}
	
	public function echoRessourcePath($absolute_path){
		echo $this->getRessourcePath($absolute_path);
	}
	
	public function getPath($to = "/", $encode=true){
		if ($to == ""){
			$to = "/";
		}
		
		

		
		if ($encode){		
			$to = explode("/", $to);
			$to = array_map("rawurlencode",$to);
			$to = implode("/",$to);
		}
		
		return $this->getSiteIndex() . $to;
	}
		
	private function getSiteIndex(){
		return $this->site_index;
	}
	
	
	public function redirect($to = ""){
		$location = $this->getPath($to);
		header("Location: $location");
		exit;
	}
	
	
	public function getXmlRoute() {
		return __DIR__ ."/route/routes.xml";
	}
	
	
	public function setRoute($url, $to) {
		$dom = new DomDocument;
		$file = $this->getXmlRoute();
		$dom->load($file );
		$exist = false;
		
		//On regarde si la route na pas déjà été créé :
		$xpath = new DomXpath($dom);
		$rowNode = $xpath->query('//route[@url="'.$url.'"][1]')->item(0);

		if ($rowNode instanceof DomElement) {
			//echo $rowNode->nodeValue;
			$exist = true;
		}
		
		if ( ! $exist ) {
			$fragment = $dom->createDocumentFragment();
$node = '	<route url="'.$url.'">'.$to.'</route>
'; 
			$fragment->appendXML($node);
			$dom->documentElement->appendChild($fragment);
			$dom->save($file);
		}
	}
	
	
	public function getPathRoute($url) {

		
		$dom = new DomDocument;
		$file = $this->getXmlRoute();
		$dom->load($file);
		
		$xpath = new DomXpath($dom);
		$rowNode = $xpath->query('//route[@url="'.$url.'"][1]')->item(0);
		
		if ($rowNode instanceof DomElement) {
			$to = $rowNode->nodeValue;
			return $to;
		}
		
		return false;
		
		
	}
	
	public function route($url, $to = "", $encode=true, $send=true, $add_param=true, $lang="fr") {
		$url = "/".$url;
		
		$param = "";
		if ( $add_param ) {
			$tab = explode("/", $to);
			$nb = count($tab);
			if ( $nb > 3 ) {
				for ($i=3 ; $i < $nb ; $i++) { $param .= "-".$tab[$i]; }
			}
		}
		$url .= $param;
		
		if ( $send ) echo $this->getRoute($url,$to,$encode);
		else return $this->getRoute($url,$to,$encode);
		
	}	

	public function getRoute($url, $to = "/", $encode=true){
		if ($to == ""){
			$to = "/";
		}
		if ($encode){		
			$to = explode("/", $to);
			$to = array_map("rawurlencode",$to);
			$to = implode("/",$to);
		}
		
		$this->setRoute($url, $to);

		return $url;
	}	
	
		
	
	
}
