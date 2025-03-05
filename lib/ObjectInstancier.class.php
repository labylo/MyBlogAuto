<?php
class ObjectInstancier {

	private $objects;
	
	public function __construct(){		
		$this->objects = array('ObjectInstancier' => $this);
	}
	
	public function __get($name){
		if (! isset($this->objects[$name])){
			$this->objects[$name] =  $this->newInstance($name);	
		}
		return $this->objects[$name];
	}
	
	public function __set($name,$value){
		$this->objects[$name] = $value;
	}

	private function newInstance($className){
		$reflexionClass = new ReflectionClass($className);
		if (! $reflexionClass->hasMethod('__construct')){
			return $reflexionClass->newInstance();
		}
		$constructor = $reflexionClass->getMethod('__construct');
        $allParameters = $constructor->getParameters();
        $param = $this->bindParameters($allParameters);        
        return $reflexionClass->newInstanceArgs($param);
	}
	
	private function bindParameters(array $allParameters){
		$param = array();
		
		foreach($allParameters as $parameters){  
        	
			/*	==== Ancien code deprecié avec PHP8 ===
			
				$param_name = $parameters->getClass() ? $parameters->getClass()->name : $parameters->name;

				if ( $parameters->getClass() ) {
					
					$param_name = $parameters->getClass()->name;
				}else{
					$param_name =  $parameters->name;
				}
				
				if ( $parameters->getType() && !$parameters->getType()->isBuiltin() ) {
					$param_name2 = $parameters->getType()->getName();
				}else{
					$param_name2 = $parameters->getName();
				}
			*/
		
			if (PHP_MAJOR_VERSION < 8) {
				//pour que ca fonctionne sur le serveur de démo qui est en PHP 5.6
				$param_name = $parameters->getClass() ? $parameters->getClass()->name : $parameters->name;
			}else{
				//Nouveau CODE qui fonctionne avec PHP8
				if ( $parameters->getType() && !$parameters->getType()->isBuiltin() ) {
					$param_name = $parameters->getType()->getName();
				}else{
					$param_name = $parameters->getName();
				}
			}
				
        	try {
        		$bind_value = $this->$param_name;
        	} catch (Exception $e){
        		//On a pas trouvé le paramètre...
        	}
        	
        	if (! isset($bind_value) ) {
        		
        		if ($parameters->isOptional()){
        			return $param;
        		}
        		throw new Exception("Impossible d'instancier ".$className." car le parametre ".$parameters->name." est manquant");
        	}
        	$param[] = $bind_value;
        }
        return $param;
	}
}
