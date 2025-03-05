<?php
class FrontControler {

	const DEFAULT_CONTROLER = "Default";
	const DEFAULT_ACTION = "index";
	
	private $path;
	
	public function __construct(Path $path){
		$this->path = $path;
	}
	
	public function go(){			
		$path_info = $this->getPathInfo();
		$path_token = $this->getPathToken($path_info);		
		$controler = $this->getControler($path_token);
		
		$controler->_before($path_token['controler'], $path_token['action'], $path_token['param']);
		
		$method_name = $path_token['action']."Action";
		if (method_exists($controler,$method_name)){
			call_user_func_array(array($controler, $method_name), $path_token['param']);
		} 
		
		$controler->_after($path_token['controler'], $path_token['action'], $path_token['param']);
	}
	
	private function getPathInfo(){
		if ($_SERVER['REQUEST_METHOD'] != 'POST'){
			if (isset($_SERVER['REDIRECT_QUERY_STRING'])){
	
				$r = str_replace("page=", "/", $_SERVER['REDIRECT_QUERY_STRING']);
				if ($r == "/"){
					return "";
				}
				return $r;
	
			}
			return isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:"";
		}
			
		if (empty($_POST['path_info'])){
			return false;
		}
		return $_POST['path_info'];
	}
	
	
	
	private function getControler($path_token){
		$controler_class ="{$path_token['controler']}Controler"; 
		$class_filename = "$controler_class.class.php";
		
		if (stream_resolve_include_path($class_filename)){
			$controler_name = $controler_class;
		}  else {
			$controler_name = "DefaultControler";
		}
			
		$controler = new $controler_name();
		return $controler;
	}
	
	
	public function getPathToken($pathInfo){
		$controler = self::DEFAULT_CONTROLER;
		$action = self::DEFAULT_ACTION;
		$param = array();
	
		$token = explode("/",$pathInfo);
	
		if (! empty($token[1])){
			$controler = ucfirst($token[1]);
		}
	
		if (! empty($token[2])){
			$action = $token[2];
		}
	
		if (count($token) > 3) {
			$param = array_slice($token,3);
		}
		return array("controler"=>$controler,"action" => $action,"param"=>$param);
	}
	
}
