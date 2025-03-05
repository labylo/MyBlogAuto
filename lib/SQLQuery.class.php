<?php
class SQLQuery {
	
	const DATABASE_TYPE = "mysql";
	const DEFAULT_HOST = "localhost";
	const SLOW_QUERY_IN_MS = 2000;
	
	private $databaseName;
	private $host;
	private $login;
	private $password;
	private $nbreq = 0;
	private $pdo;
	
	public function __construct($databaseName){
		$this->databaseName = $databaseName;
		$this->setDatabaseHost(self::DEFAULT_HOST);
	}

	public function getNbReq() {
		return $this->nbreq;
	}	
	
	public function disconnect(){
		$this->pdo = null;
	}
	
	public function sleep($time_in_second){
		$this->disconnect();
		sleep($time_in_second);
	}
	
	public function setDatabaseHost($host){
		$this->host = $host;
	}
	
	public function setCredential($login,$password){
		$this->login = $login;
		$this->password = $password;
	}
	
	private function getPdo(){
		if ( ! $this->pdo){
			$dsn = self::DATABASE_TYPE . ":host=".$this->host;
			if ($this->databaseName){
				$dsn .= ";dbname=".$this->databaseName;
			}
			$this->pdo = new PDO($dsn,$this->login,$this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 
			
		}
		return $this->pdo;
	}
	
	public function query($query,$param = false){
		$start = microtime(true);
		if ( ! is_array($param)){
			$param = func_get_args();
			array_shift($param);
    	}
		
    	try {
    		$pdoStatement = $this->getPdo()->prepare($query);
    	} catch (Exception $e) {	
    		throw new Exception($e->getMessage() . " - " .$query);
		}	
		
		try {
			$pdoStatement->execute($param);
		} catch (Exception $e) {
			throw new Exception( $e->getMessage() ." - ". $pdoStatement->queryString . "|" .implode(",",$param));	
		}
		$result = array();
		if ($pdoStatement->columnCount()){
			$result = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
		} 
		
		$duration = microtime(true) - $start;
		if ($duration > self::SLOW_QUERY_IN_MS ){
			$requete =  $pdoStatement->queryString . "|" .implode(",",$param);
			trigger_error("Requete lente ({$duration}ms): $requete",E_USER_WARNING);
		}
		
		$this->nbreq++;
		return $result;
	}
	
	public function queryOne($query,$param = false){
		if ( ! is_array($param)){
			$param = func_get_args();
			array_shift($param);
    	}
		$result = $this->query($query,$param);
		if (! $result){
			return false;
		}
		
		$result = $result[0];
		if (count($result) == 1){
			return reset($result);
		}
		
		$this->nbreq++;
		return $result;
	}
	
	public function queryOneCol($query,$param = false){
		if ( ! is_array($param)){
			$param = func_get_args();
			array_shift($param);
    	}
    	$result = $this->query($query,$param);
		if (! $result){
			return array();
		}
		$r = array();
		foreach($result as $line){
			$line = array_values($line);
			$r[] = $line[0];
		}
		return $r;
	}
}
