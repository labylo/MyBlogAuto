<?php
class AdminSQL extends SQL {

	/*
	public function getBugDefiEnDouble() {
		//A effacer après correction BUG
		$sql = "
		SELECT count(id_defieur) as cpt, id_defieur, id_defie, debut 
		FROM defi GROUP BY debut HAVING cpt>1 AND month(debut)=5 AND year(debut)=2020 ORDER BY debut DESC
		
		";
		return $this->query($sql);
	}
	
	
	public function getBugDefiFromId($id_defieur, $debut) {
		//A effacer après correction BUG
		$sql = "SELECT * FROM defi WHERE id_defieur=? AND debut=? ORDER BY id_d ASC";
		return $this->query($sql, $id_defieur, $debut);
	}
	
	public function corrigeBugDefiEnDouble($id_d, $id_u, $mise) {
		$sql = "UPDATE utilisateur SET miam=miam+".$mise." WHERE id_u=".$id_u." LIMIT 1";
		echo "<br>".$sql."<hr>";
	}
	

	*/
	
	
	
	/*
	LBI 13/10/2022 ; je supprimer les champs "nom_inv" et "tags_inv"
	plus besoin de cette fonction :
	
	public function makeRecInv() {
		
		
		$sql = "UPDATE fiche SET nom_inv=REVERSE(nom), tags_inv=REVERSE(tags)";
		$this->query($sql);
		/*
		$sql = "SELECT fiche_id, nom, tags FROM fiche";
		$info = $this->query($sql);
		
		foreach($info as $mr) {
			$nom_inv = trim(strrev($mr['nom']));
			$tags_inv = trim(strrev($mr['tags']));
			$sql = "UPDATE fiche SET nom_inv=? WHERE fiche_id=?";
			$this->query($sql, $nom_inv, $mr['fiche_id']);
		}
		

	}
	*/
	
	
	public function getFtMin() {
		$sql = "show variables like 'ft_min_word_len'";
		
		return $this->queryOne($sql);
	}
	
	public function getMysqlDate() {
		$sql = "SELECT NOW()";
		return $this->queryOne($sql);
	}

	private function getWhereByType($type, $motcle) {
		
	}
	
	public function testPaypalNotify($nb) {
		$sql = "INSERT INTO aaa_test(date, nb) VALUES(NOW(), ?)";
		$this->query($sql, $nb);
	}

	public function optimiseTable() {
		$sql = "OPTIMIZE TABLE message";
		$this->query($sql);
		
		$sql = "OPTIMIZE TABLE demande";
		$this->query($sql);
	}

	public function getUserNbByType($type, $motcle) {
		$where = $this->getWhereByType($type, $motcle);
		$sql = "SELECT COUNT(id_u) FROM utilisateur WHERE ".$where;
		return $this->queryOne($sql);
	}
	
	public function getUserInfoByType($type, $deb, $offset, $tri, $motcle) {
		$orderby = "id_u DESC";

		
		if ( empty($deb) ) $limit = $offset;
		else $limit = $deb.",".$offset;
		
		$where = $this->getWhereByType($type, $motcle);
	
		switch ($tri) {
			case "crediz_desc";$orderby = " crediz DESC";break;
			case "crediz_asc";$orderby = " crediz";break;
			case "crediz_achete_desc";$orderby = " crediz_achete DESC";break;
			case "crediz_achete_asc";$orderby = " crediz_achete";break;
			case "date_insc_desc";$orderby = " date_inscription DESC";break;
			case "date_insc_asc";$orderby = " date_inscription";break;
		}
		
		$sql = "SELECT * FROM utilisateur WHERE ".$where." ORDER BY ".$orderby." LIMIT ".$limit;
		return $this->query($sql);
	}
	
	
	public function getInfo() {
		$sql="SELECT * FROM admin";
		return $this->query($sql);
	}	
	
	public function validerCompte($id_u) {
		$sql = "UPDATE utilisateur SET valid=1, valid_chaine=0 WHERE id_u=? LIMIT 1";
		$this->query($sql, $id_u);
		
		//On supprime la demande
		$sql="DELETE FROM demande WHERE type='validation' AND id_u=? LIMIT 1";
		$this->query($sql, $id_u);
	
		//On renvoi le mail
		$sql="SELECT email FROM utilisateur WHERE id_u=? LIMIT 1";
		return $this->queryOne($sql, $id_u);
		
		
	}
	
	public function ajouterPseudoBanni($pseudo) {
		$sql = "SELECT COUNT(id_u) FROM utilisateur WHERE pseudo=?";
		$nb = $this->queryOne($sql, $pseudo);
		
		if ( ! $nb ) {
			$sql="INSERT INTO utilisateur(pseudo, valid_chaine, banni) VALUES(?, 'reserve_admin', 1)";
			$this->query($sql, $pseudo);
		}
	}
	
	
	public function ajouterIpBanni($adip) {
		//On controle que l'IP n'est pas déjà dans la base
		$sql = "SELECT COUNT(id_i) FROM ip_banni WHERE adip=?";
		$nb = $this->queryOne($sql, $adip);

		if ( ! $nb ) {
			$sql="INSERT INTO ip_banni(adip, ma_date) VALUES(?, NOW())";
			$this->query($sql, $adip);
		}
	}
	
	public function supprimerMessagePlainte($id_m) {
		$sql = "DELETE FROM msg_plainte WHERE id_m=? LIMIT 1";	
		$this->query($sql, $id_m);
	}
	
	public function getDetectDoubleCompte() {
		$sql = "SELECT adip, COUNT(id_u) as nb_compte, SUM(crediz_achete) as crediz_achete, date(date_derniere) as date_last 
		FROM utilisateur GROUP BY adip, date(date_derniere) HAVING nb_compte > 1 and adip != '' 
		ORDER BY date_derniere desc";
		return $this->query($sql);
	}
	
	public function getMsgRapporte() {
		$sql="
		SELECT msg_plainte.*, u1.pseudo AS pseudo_emetteur, u2.pseudo AS pseudo_recepteur FROM msg_plainte 
		JOIN utilisateur u1 ON msg_plainte.id_u_emetteur = u1.id_u 
		JOIN utilisateur u2 ON msg_plainte.id_u_recepteur = u2.id_u 
		ORDER BY date_envoi DESC";
		return $this->query($sql);
	}
	
	public function libererPseudoBanni($id_u) {
		$sql="DELETE FROM utilisateur WHERE id_u=? LIMIT 1";
		$this->query($sql, $id_u);
	}
		
	public function libererIpBanni($id_i) {
		$sql="DELETE FROM ip_banni WHERE id_i=? LIMIT 1";
		$this->query($sql, $id_i);
	}	
	

	
	
	public function getPseudoBanni() {
		$sql="SELECT id_u, pseudo FROM utilisateur WHERE valid_chaine='reserve_admin' ORDER BY pseudo";
		return $this->query($sql);
	}
	
	public function getIpBanni() {
		$sql="SELECT * FROM ip_banni ORDER BY ma_date DESC";
		return $this->query($sql);
	}


	
	public function supprimerDemande($id_d) {
		$sql="DELETE FROM demande WHERE id_d=? LIMIT 1";
		$this->query($sql, $id_d);
	}
	

	public function getInfoFromId($id_admin) {
		$sql="SELECT * FROM admin WHERE id_admin=?";
		return $this->queryOne($sql, $id_admin);
	}	
	
	public function getInfoFromLogin($login) {
		$sql="SELECT * FROM admin WHERE login=?";
		return $this->queryOne($sql, $login);
	}	
	
	
	public function initEchecConnexion($id_admin) {
		$sql = "UPDATE admin SET nb_tentative=0 WHERE id_admin=? LIMIT 1";
		$this->queryOne($sql, $id_admin);
	}
	
	
	public function verifConnexionPwd($id_admin, $password, $ip) {
		
		$sql = "UPDATE admin SET ip=? WHERE id_admin=? LIMIT 1";
		$this->queryOne($sql, $ip, $id_admin);
		
		$sql = "SELECT password FROM admin WHERE id_admin=?";
		$crypted = $this->queryOne($sql, $id_admin);


		if ( ! $crypted ) {
			return false;
		}	
		
		if ( crypt($password, SALT) != $crypted ) {
			return false;
		}
		
		return true;
		
	}
	
	public function setEchecConnexion($id_admin, $ip, $max) {
		$sql = "UPDATE admin SET nb_tentative=nb_tentative+1 WHERE ip=? AND id_admin=? LIMIT 1";
		$this->queryOne($sql, $ip, $id_admin);

		$sql = "SELECT nb_tentative FROM admin WHERE ip=? AND id_admin=? LIMIT 1";
		$nb_tentative = $this->queryOne($sql, $ip, $id_admin);
		
		if ( $nb_tentative == $max ) {
			$sql = "UPDATE admin SET date_der_connexion=NOW() WHERE id_admin=? LIMIT 1";
			$this->queryOne($sql, $id_admin);
		}
		
		return $nb_tentative;
	}
	
	public function setUserSuivi($id_u, $suivi) {
		$sql = "UPDATE utilisateur SET suivi=? WHERE id_u=? LIMIT 1";
		$this->query($sql, $suivi, $id_u);	
	}
	

	
	public function setUserCaptcha($id_u, $captcha_status) {
		$sql = "UPDATE utilisateur SET captcha_status=? WHERE id_u=? LIMIT 1";
		$this->query($sql, $captcha_status, $id_u);
	}
	
	
    public function setUserDroit($id_u, $niveau_admin) {
		$sql = "UPDATE utilisateur SET niveau_admin=? WHERE id_u=? LIMIT 1";
		$this->query($sql, $niveau_admin, $id_u);
	}
  
	
	
	public function setUserJoker($id_u, $joker) {
		$sql = "UPDATE utilisateur SET joker=? WHERE id_u=? LIMIT 1";
		$this->query($sql, $joker, $id_u);
	}
	
	public function setUserCrediz($id_u, $crediz) {
		$sql = "UPDATE utilisateur SET crediz=? WHERE id_u=? LIMIT 1";
		$this->query($sql, $crediz, $id_u);
	}
		
	public function setUserGold($id_u, $gold) {
				
		$sql = "SELECT nb_gold, nb_gold_total FROM utilisateur WHERE id_u=? LIMIT 1";
		$info = $this->queryOne($sql, $id_u);

		
		$nb_gold = $info['nb_gold'];
		$nb_gold_total = $info['nb_gold_total'];
		$diff = $nb_gold_total-$nb_gold;
		
		$new_nb_gold_total = $gold+$diff;
		
		$sql = "UPDATE utilisateur SET nb_gold=?, nb_gold_total=? WHERE id_u=? LIMIT 1";

		
		$this->query($sql, $gold, $new_nb_gold_total, $id_u);
		
	}
	
	public function setUserMiam($id_u, $miam) {
		$sql = "UPDATE utilisateur SET miam=? WHERE id_u=? LIMIT 1";
		$this->query($sql, $miam, $id_u);
	}
	
	
	public function setDerniereConnexion($id_admin){
		$sql = "UPDATE admin SET date_der_connexion=NOW(), nb_connexion=nb_connexion+1, nb_tentative=0 
		WHERE id_admin=? LIMIT 1";
		$this->queryOne($sql, $id_admin);
	}
	
	public function setUserCommentaire($id_u, $commentaire_modo) {
		$sql = "UPDATE utilisateur SET commentaire_modo=? WHERE id_u=? LIMIT 1";
		$this->query($sql, $commentaire_modo, $id_u);
	}
	
	public function addUserCommentaire($id_u, $commentaire_new) {
		$sql = "SELECT commentaire_modo FROM utilisateur WHERE id_u=? LIMIT 1";
		$commentaire_temp = $this->queryOne($sql, $id_u);
		
		$commentaire_modo = $commentaire_new."\n".$commentaire_temp;
		$this->setUserCommentaire($id_u, $commentaire_modo);
	}
	
	
	public function setUserValider($id_u) {
		$sql = "UPDATE utilisateur SET valid=1, valid_chaine='' WHERE id_u=? LIMIT 1";
		$this->query($sql, $id_u);
	}
	
	public function majOne($tab, $id_admin) {
		foreach ($tab as $name=>$value) {
			$sql = "UPDATE admin SET ".$name."=? WHERE id_admin=? LIMIT 1";
			$this->queryOne($sql, $value, $id_admin);
		}
	}
	
	
	
	public function getUserByEmail($email) {
		$sql = "SELECT COUNT(id_u) FROM utilisateur WHERE email=?";
		return $this->queryOne($sql, $email);
	}
	
	public function setUserNewEmail($id_u, $email) {
		$sql = "UPDATE utilisateur SET email=? WHERE id_u=? LIMIT 1";
		$this->query($sql, $email, $id_u);
	}
	
	
	public function getUserByPseudo($pseudo) {
		$sql = "SELECT COUNT(id_u) FROM utilisateur WHERE pseudo=?";
		return $this->queryOne($sql, $pseudo);
	}
	
	public function setNewDemandeValidation($id_u, $email) {
        $message = "A demandé à recevoir à nouveau le code d'activation de compte par mini-message";
		$sql = "INSERT INTO demande(id_u, email, message, type, date_demande) VALUES(?,?,?,'validation', NOW())";
        $this->query($sql, $id_u, $email, $message);
	}
	
	public function setUserNewPseudo($id_u, $pseudo) {
		$sql = "UPDATE utilisateur SET pseudo=? WHERE id_u=? LIMIT 1";
		$this->query($sql, $pseudo, $id_u);
	}
	
	public function bannirUser($id_u) {

		$sql = "SELECT email, commentaire_modo FROM utilisateur WHERE id_u=?";
		$info = $this->queryOne($sql, $id_u);
		
		$email_temp = $info['email'];
		
		//Si le membre a créé des groupes, on les désactives
		$sql = "UPDATE groupes SET actif=0 WHERE id_u_createur=?";
		$this->query($sql, $id_u);
		
		//Ensuite on regarde les groupes auxquels il appartient
		$sql = "SELECT id_g FROM groupes_utilisateurs WHERE id_u=?";
		$info = $this->query($sql, $id_u);
		

		$chaine = "(";
		foreach($info as $mr){
			$chaine .= $mr['id_g'].",";
		}
		$chaine .= "0)";
			
		//On modifie alors le compteur de ces groupes
		$sql = "UPDATE groupes SET nb=nb-1 WHERE id_u_createur<>? AND id_g IN ".$chaine;
		$this->query($sql, $id_u);
		
		//On supprime les plaintes éventuelles reçues à son sujet
		$sql = "UPDATE msg_plainte SET valid=0 WHERE id_u_emetteur=?";
		$this->query($sql, $id_u);
		
		//Enfin, on banni le membre
		$email_new = "banni@".SITE_URL_COURT;
		$sql = "UPDATE utilisateur SET email_temp=?, email=?, banni=1, valid_chaine='membre_banni', date_derniere=NOW() WHERE id_u=? LIMIT 1";
		$this->query($sql, $email_temp, $email_new, $id_u);

	}
	
	public function getMessageFromIdU($id_u, $limit, $recu) {
		if ( $recu ) {
			$sql = "SELECT * FROM message WHERE id_u_recepteur=? ORDER BY date_envoi DESC LIMIT ".$limit;
		}else{
			$sql = "SELECT * FROM message WHERE id_u_emetteur=? ORDER BY date_envoi DESC LIMIT ".$limit;
		}
		return $this->query($sql, $id_u);
	}
	
	
	public function getNbDemandeValidation() {
		$sql = "SELECT COUNT(id_d) AS nb FROM demande WHERE type='validation'";
		return $this->queryOne($sql);
	}
	
	public function getDemandeValidation() {
		$sql="SELECT demande.*, utilisateur.id_u, utilisateur.pseudo, utilisateur.valid, utilisateur.valid_chaine
		FROM demande 
		JOIN utilisateur ON demande.email = utilisateur.email 
		WHERE type='validation' ORDER BY date_demande";

		return $this->query($sql);
	}
	
	
	
	
	public function getNbPlainte() {
		$sql = "SELECT COUNT(id_m) AS nb FROM msg_plainte";
		return $this->queryOne($sql);
	}	
	
	
	
	public function debannirUser($id_u) {
		$sql = "SELECT email_temp FROM utilisateur WHERE id_u=? LIMIT 1";
		$email = $this->queryOne($sql, $id_u);
		
		//Si le membre a créé un groupe, on le re-active
		$sql = "UPDATE groupes SET actif=1 WHERE id_u_createur=?";
		$this->query($sql, $id_u);
		
		
		//Ensuite on regarde les groupes auxquels il appartient
		$sql = "SELECT id_g FROM groupes_utilisateurs WHERE id_u=?";
		$info = $this->query($sql, $id_u);
		
		$chaine = "(";
		foreach($info as $mr){
			$chaine .= $mr['id_g'].",";
		}
		$chaine .= "0)";
		
		//On modifie alors le compteur de ces groupes
		$sql = "UPDATE groupes SET nb=nb+1 WHERE id_u_createur<>? AND id_g IN ".$chaine;
		$this->query($sql, $id_u);
		
		//Enfin, on debannit le membre
		$sql = "UPDATE utilisateur SET email=?, banni=0, valid_chaine='' WHERE id_u=? LIMIT 1";
		$this->query($sql, $email, $id_u);
		
	}
	
	public function deteleRobotUser($id_u) {
		$sql = "SELECT * FROM utilisateur WHERE id_u=? LIMIT 1";
		$info = $this->queryOne($sql, $id_u);
		
		if ( $info['champion'] == 5 ) {
			$this->supprimerAllInfoFromIdU($id_u);
		}
		
		$sql = "OPTIMIZE TABLE contacts";$this->query($sql);
		$sql = "OPTIMIZE TABLE groupes_utilisateurs";$this->query($sql);
		$sql = "OPTIMIZE TABLE defi";$this->query($sql);
		
		$sql = "OPTIMIZE TABLE message";$this->query($sql);
		$sql = "OPTIMIZE TABLE classement";$this->query($sql);
		$sql = "OPTIMIZE TABLE utilisateur";$this->query($sql);
		$sql = "OPTIMIZE TABLE pool";$this->query($sql);
		
		
	}
	
	public function supprimerAllInfoFromIdU($id_u) {
		
		
		
		$sql = "SELECT COUNT(id_c) FROM cadeaux WHERE id_u_gagnant=?";
		$nb = $this->queryOne($sql, $id_u);
		
		if ( $nb ) {//ON efface pas un membre qui a gagné un cadeau !
			echo "On n'efface pas un membre qui a gagné un cadea<br>";

		}else{

			
			//On efface sa liste de contact
			$sql = "DELETE FROM contacts WHERE id_u=?";
			$this->query($sql, $id_u);
			
			//On l'efface des liste de contact des autres USERS
			$sql = "DELETE FROM contacts WHERE id_u_contact=?";
			$this->query($sql, $id_u);		
			
			//On supprime les user des groupes qu'il gère
			$sql = "SELECT * FROM groupes WHERE id_u_createur=?";
			$info = $this->query($sql, $id_u);
			if ( $info ) {
				foreach($info as $mr) {
					$sql = "DELETE FROM groupes_utilisateurs WHERE id_g=?";
					$this->query($sql, $mr['id_g']);
				}
			}
			
			//On supprime le groupe qu'il gère
			$sql = "DELETE FROM groupes WHERE id_u_createur=?";
			$this->query($sql, $id_u);
			
			
			//On regarde les groupes dans lesquel il est :
			$sql = "SELECT id_g FROM groupes_utilisateurs WHERE id_u=?";
			$info = $this->query($sql, $id_u);
			if ( $info ) {
				foreach($info as $mr) {
					//On l'efface du groupe
					$sql = "DELETE FROM groupes_utilisateurs WHERE id_g=?";
					$this->query($sql, $mr['id_g']);
					
					//On met à jour les stats du groupe
					$sql = "UPDATE groupes SET nb=nb-1 WHERE id_g=?";
					$this->query($sql, $mr['id_g']);
				}
			}
			
			
			//On efface les stats CREDIZ
			$sql = "DELETE FROM paiement_audiotel WHERE id_u=?";
			$this->query($sql, $id_u);
			
			//On efface tous ses défis
			$sql = "DELETE FROM defi WHERE id_defieur=? OR id_defie=?";
			$this->query($sql, $id_u, $id_u);
			
			//On efface tous ses demandes
			$sql = "DELETE FROM demande WHERE id_u=?";
			$this->query($sql, $id_u);			
			
			//On efface tous ses plaintes
			$sql = "DELETE FROM msg_plainte WHERE id_u_emetteur=? OR id_u_recepteur=?";
			$this->query($sql, $id_u, $id_u);

			//On efface tous ses succes
			$sql = "DELETE FROM succes WHERE id_u=?";
			$this->query($sql, $id_u);	
			
			//On efface ses medailles
			$sql = "DELETE FROM medaille WHERE id_u=?";
			$this->query($sql, $id_u);	
			
			//On efface tous ses messages reçus
			$sql = "DELETE FROM message WHERE id_u_recepteur=? OR id_u_emetteur=?";
			$this->query($sql, $id_u, $id_u);	
			
			
			//On le remplace en tant que gagnant de pool dans les pool qu'il a gagné par OBVedette
			$id_ob_vedette = 18721;
			$sql = "UPDATE pool SET gagnant_id_u=? WHERE gagnant_id_u=?";
			$this->query($sql, $id_ob_vedette, $id_u);
			
			//On efface ses succes dans pool_utilisateur
			$sql = "DELETE FROM pool_utilisateur WHERE id_u=?";
			$this->query($sql, $id_u);	
			
			//On l'efface des classements :
			$sql = "DELETE FROM classement WHERE id_u=?";
			$this->query($sql, $id_u);	
			
			//ENFIN l'efface de la table utilisateur
			$sql = "DELETE FROM utilisateur WHERE id_u=? LIMIT 1";
			$this->query($sql, $id_u);	
		}
		
		
	}
	

	
	
	/*
	public function getInfo() {
		$sql = "SELECT * FROM client ORDER BY date_creation";
		return $this->query($sql);
	}
	

	public function getInfoFromId($id_client) {
		$sql="SELECT * FROM client WHERE client.id_client=?";
		return $this->queryOne($sql, $id_client);
	}	
	

	public function existEmail($email){
		$sql = "SELECT COUNT(id_admin) FROM admin WHERE email=?";
		return $this->queryOne($sql, $email);
	}	
	


	
		
	public function create($tab, $secure) {
		$sql = "INSERT INTO client(date_creation, secure) VALUES(NOW(), ?)";
		$this->queryOne($sql, $secure);
		
		$id_client = $this->getLastId();
	
		$this->majOne($tab, $id_client, $secure);
		return $id_client;
	}

	
	private function majOne($tab, $id_client, $secure) {
		foreach ($tab as $name=>$value) {
			$sql = "UPDATE client SET ".$name."=? WHERE id_client=? AND secure=? LIMIT 1";
			$this->queryOne($sql, $value, $id_client, $secure);
		}
	}
	
	public function getLastId() {
		$sql = "SELECT id_client FROM client ORDER BY id_client DESC LIMIT 1";
		return $this->queryOne($sql);
	}
	
	



	

	*/
	
}
