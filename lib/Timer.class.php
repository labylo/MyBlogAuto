<?php
class Timer {
	
	const DEFAULT_SCRIPT_DURATION = 60;
	
	public function __construct($debut = false){
		if (!$debut){
			$debut = microtime(true);
		}
		$this->debut = $debut;
	}
	
	public function getTime(){
		return round((microtime(true) - $this->debut) * 10000) / 10; 
	}
	
	public function ensureScriptDuration($nb_second = false){
		if (! $nb_second){
			$nb_second = self::DEFAULT_SCRIPT_DURATION;
		}

		$sleep = round($nb_second - (microtime(true) - $this->debut));
		if ($sleep > 0){
			echo "Arret du script : $sleep";
			sleep($sleep);
		}
	}
	
}
