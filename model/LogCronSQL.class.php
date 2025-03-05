<?php
class LogCronSQL extends SQL {
	
	public function saveLogCron($erreur, $type, $lib, $html) {
		$sql="INSERT INTO log_cron(erreur, type, lib, html) VALUES (?,?,?,?)";
		$this->query($sql, $erreur, $type, $lib, $html);
	}
	
	public function listeLogCron($limit) {
		$sql = "SELECT * FROM log_cron ORDER BY id_log_cron DESC LIMIT ".$limit;
		return $this->query($sql);
	}
	

	public function deleteLogCron($garde_today) {
		if ( $garde_today ) {
			$sql = "DELETE FROM log_cron WHERE DATE(madate) < CURDATE()";
		}else{
			$sql = "DELETE FROM log_cron";
		}
		$this->query($sql);
		
		$sql = "OPTIMIZE TABLE log_cron";
		$this->query($sql);
	}
	
	public function supprimerLogCron($id_log_cron) {
		$sql="DELETE FROM log_cron WHERE id_log_cron=?";
		$this->query($sql, $id_log_cron);
	}
	
	
	
}
