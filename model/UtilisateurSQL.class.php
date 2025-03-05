<?php
class UtilisateurSQL extends SQL {


	public function getInfo($offset, $deb=0) {
		$limit = $offset.", ".$deb;
		$sql = "SELECT * FROM utilisateur ORDER BY utilisateur_id DESC LIMIT ".$limit;
		return $this->query($sql);
	}
	
	public function getInfoFromId($utilisateur_id, $secure="") {
		if ( $secure ) {
			$sql = "SELECT * FROM utilisateur WHERE utilisateur_id=? AND secure=? LIMIT 1";
			return $this->queryOne($sql, $utilisateur_id, $secure);
		}else{
			$sql = "SELECT * FROM utilisateur WHERE utilisateur_id=? LIMIT 1";
			return $this->queryOne($sql, $utilisateur_id);
		}
	}

	public function setCommentaireStatFromUtilisateur($utilisateur_id) {
		$sql = "SELECT COUNT(commentaire_id) FROM commentaire WHERE utilisateur_id=?";
		$nb_commentaire = $this->queryOne($sql, $utilisateur_id);

		$sql = "UPDATE utilisateur SET nb_commentaire=? WHERE utilisateur_id=?";
		$this->queryOne($sql, $nb_commentaire, $utilisateur_id);
	}
	
	public function getInfoRandom($limit) {
		$sql = "SELECT * FROM utilisateur WHERE champion=1 ORDER BY RAND() LIMIT ".$limit;
		return $this->query($sql);
	}
	
	public function existPseudo($pseudo) {
		$sql = "SELECT COUNT(*) AS nb FROM utilisateur WHERE pseudo=?";
		return $this->queryOne($sql, $pseudo);
	}
	
	public function existEmail($email) {
		$sql = "SELECT COUNT(*) AS nb FROM utilisateur WHERE email=?";
		return $this->queryOne($sql, $email);
	}
	
	public function getLastId() {
		$sql = "SELECT utilisateur_id FROM utilisateur ORDER BY utilisateur_id DESC LIMIT 1";
		return $this->queryOne($sql);
	}	
	
	public function initTentative($utilisateur_id) {
		$sql = "UPDATE utilisateur SET code_nb=0 WHERE utilisateur_id=?";
		$this->queryOne($sql, $utilisateur_id);
	}
	
	public function getTentativeToday($utilisateur_id) {
		$sql = "SELECT COUNT(*) FROM utilisateur WHERE code_nb>1 AND date(date_tentative) = CURDATE() AND utilisateur_id=?";
		return $this->queryOne($sql, $utilisateur_id);

	}
	
	public function majOne($tab, $utilisateur_id, $secure) {
		foreach ($tab as $name=>$value) {
			$sql = "UPDATE utilisateur SET ".$name."=? WHERE utilisateur_id=? AND secure=? LIMIT 1";
			$this->queryOne($sql, $value, $utilisateur_id, $secure);
		}
	}
	
	public function verifConnexionPwd($utilisateur_id, $password_post) {
		$sql = "SELECT password FROM utilisateur WHERE utilisateur_id=?";
		$password_crypted = $this->queryOne($sql, $utilisateur_id);
		
		$connexion_ok = password_verify($password_post, $password_crypted);

		if ( ! $connexion_ok ){
			return false;
		}	
		return true;
		
	}
	
	public function setDerniereConnexion($utilisateur_id, $adip) {
		$date_derniere = date(DATE_ISO);
		$sql = "UPDATE utilisateur SET date_derniere=?, adip=? WHERE utilisateur_id=?";
		$this->query($sql, $date_derniere, $adip, $utilisateur_id);
	}
	
	public function setIncorrectCode($utilisateur_id) {
		$date_tentative = date(DATE_ISO);
		$sql = "UPDATE utilisateur SET code_nb=code_nb+1, date_tentative=? WHERE utilisateur_id=?";
		$this->query($sql, $date_tentative, $utilisateur_id);
	}
	
	public function create($tab, $secure) {
		$sql = "INSERT INTO utilisateur(secure) VALUES(?)";
		$this->query($sql, $secure);
		
		$utilisateur_id = $this->getLastId();
	
		$this->majOne($tab, $utilisateur_id, $secure);
		return $utilisateur_id;
	}
	
	public function getInfoFromEmail($email) {
		$sql = "SELECT * FROM utilisateur WHERE email=?";
		return $this->queryOne($sql, $email);
	}

	public function getInfoFromActivationCode($utilisateur_id, $code) {
		$sql = "SELECT * FROM utilisateur WHERE utilisateur_id=? AND code=?";
		return $this->queryOne($sql, $utilisateur_id, $code);
	}
	
	
	public function activerCompte($utilisateur_id) {
		$sql = "UPDATE utilisateur SET code='', valid=1 WHERE utilisateur_id=?";
		$this->query($sql, $utilisateur_id);
	}

/*
	
	public function setNewEmail($secure, $valid_chaine) {
		$sql = "SELECT id_u, email_temp FROM utilisateur WHERE secure=? AND valid_chaine=? LIMIT 1";
		$info = $this->queryOne($sql, $secure, $valid_chaine);
		
		$sql = "UPDATE utilisateur SET email=?, email_temp='', valid_chaine='', bounced=0 WHERE id_u=? AND secure=?";
		$this->query($sql, $info['email_temp'], $info['id_u'], $secure);

	}
	
	public function getValidSuppression($id_u, $secure, $valid_chaine) {
		$sql = "SELECT COUNT(id_u) FROM utilisateur WHERE id_u=? AND secure=? AND valid_chaine=? LIMIT 1";
		return $this->queryOne($sql, $id_u, $secure, $valid_chaine);
	}
	
	public function getExistEmailExceptMe($email, $id_u) {
		$sql = "SELECT COUNT(id_u) FROM utilisateur WHERE email=? AND id_u<>? LIMIT 1";
		return $this->queryOne($sql, $email, $id_u);
	}
	


	
	public function getInfoFromSecureAndValid($secure, $valid_chaine) {
		$sql = "SELECT id_u, email, pseudo, banni, email_temp FROM utilisateur WHERE secure=? AND valid_chaine=? LIMIT 1";
		return $this->queryOne($sql, $secure, $valid_chaine);
	}
	
	public function getInfoAboMiniMessage($id_u) {
		$sql = "SELECT pseudo, email, abo_mini_message, banni, bounced, valid, champion, date_derniere FROM utilisateur WHERE id_u=? LIMIT 1";
		return $this->queryOne($sql, $id_u);
	}
	


	public function getInfoFromPseudo($pseudo) {
		$sql="SELECT * FROM utilisateur WHERE pseudo=?";
		return $this->queryOne($sql, $pseudo);
	}
	
	public function getInfoFromRecherchePseudo($pseudo, $limit) {
		$sql="SELECT * FROM utilisateur WHERE pseudo LIKE '%".$pseudo."%' LIMIT ".$limit;
		return $this->query($sql);
	}	
	
	public function getInfoFromEmail($email) {
		$sql="SELECT * FROM utilisateur WHERE email=?";
		return $this->queryOne($sql, $email);
	}	
	
	public function setPassword($id_u, $pwd, $secure) {
		$crypted = password_hash($pwd, PASSWORD_DEFAULT);
		$sql = "UPDATE utilisateur SET crypted=? WHERE id_u=? AND secure=? LIMIT 1";
		$this->query($sql, $crypted, $id_u, $secure);
	}
	
	public function setPreference($id_u, $secure, $abo_mini_message, $abo_newsletter, $abo_partenaire, $abo_defi) {
		$sql = "UPDATE utilisateur SET abo_mini_message=?, abo_newsletter=?, abo_partenaire=?, abo_defi=? WHERE id_u=? AND secure=? LIMIT 1";
		$this->query($sql, $abo_mini_message, $abo_newsletter, $abo_partenaire, $abo_defi, $id_u, $secure);
	}
	
	public function setAvatar($avatar, $id_u) {
		$sql = "UPDATE utilisateur SET avatar=? WHERE id_u=? LIMIT 1";
		$this->query($sql, $avatar, $id_u);
	}
	
	public function setBounced($id_u, $bounced){
		$sql = "UPDATE utilisateur SET bounced=? WHERE id_u=? LIMIT 1";
		$this->query($sql, $bounced, $id_u);
	}
	
	
	public function verifConnexionPwd($id_u, $pwd, $secure="") {
			
		if ( $secure ) {
			$sql = "SELECT crypted FROM utilisateur WHERE id_u=? AND secure=?";
			$crypted = $this->queryOne($sql, $id_u, $secure);
			$connexion_ok = password_verify($pwd, $crypted);
		}else{
			$sql = "SELECT crypted FROM utilisateur WHERE id_u=?";
			$crypted = $this->queryOne($sql, $id_u);
			$connexion_ok = password_verify($pwd, $crypted);
		}


		if ( ! $connexion_ok ){
			return false;
		}	

		return true;
		
	}
	

	
	public function existValidChaine($valid_chaine) {
		$sql = "SELECT COUNT(*) AS nb FROM utilisateur WHERE valid_chaine=?";
		return $this->queryOne($sql, $valid_chaine);
	}	
	
	public function existEmail($email) {
		$sql = "SELECT COUNT(*) AS nb FROM utilisateur WHERE email=?";
		return $this->queryOne($sql,$email);
	}
	
	public function isBanni($id_u) {
		$sql = "SELECT banni FROM utilisateur WHERE id_u=? LIMIT 1";
		return $this->queryOne($sql,$id_u);
	}
	
	public function getInfoMultiCompteConnexion($adip) {
		$sql = "SELECT id_u, pseudo, DATE(date_derniere) AS date_derniere FROM utilisateur WHERE  niveau_admin=0 AND adip=? ORDER BY date_derniere DESC";
		return $this->query($sql, $adip);
	}
	
	
	
	public function supprimerCompte($id_u, $from) {
		
		$infoUser = $this->getInfoFromId($id_u);
		$email_supprime = $infoUser['email'];
		$commentaire_modo = $infoUser['commentaire_modo']."\n-Suppression : ".$infoUser['pseudo']."--".$email_supprime." - Le ".date("d-m-Y");
		$email_new = "suppression@".SITE_URL_COURT;

		//le gars a t-il créé des groupes ?
		$sql = "SELECT id_g FROM groupes WHERE id_u_createur=?";
		$infoGroupe = $this->query($sql, $id_u);

		if ( $infoGroupe ) {
			
			$chaine = "(";
			foreach($infoGroupe as $mr){
				$chaine .= $mr['id_g'].",";
			}
			$chaine .= "0)";	
			
			//On supprime alors les groupes qu'il gère :
			$sql = "DELETE FROM groupes WHERE id_g IN ".$chaine;
			$this->query($sql);

			//On supprime les USER qui sont dans ce groupe !
			$sql = "DELETE FROM groupes_utilisateurs WHERE id_g IN ".$chaine;
			$this->query($sql);
		}

		
		//Ensuite on regarde les groupes auxquels il appartient
		$sql = "SELECT id_g FROM groupes_utilisateurs WHERE id_u=?";
		$infoGroupeIn = $this->query($sql, $id_u);
		

		if ( $infoGroupeIn ) {
			$chaine = "(";
			foreach($infoGroupeIn as $mr){
				$chaine .= $mr['id_g'].",";
			}
			$chaine .= "0)";
				
			//On supprime le gars des groupes auxquels il avait adhéré
			$sql = "DELETE FROM groupes_utilisateurs WHERE id_u=?";
			$this->query($sql, $id_u);

			//On modifie alors le compteur de ces groupes
			$sql = "UPDATE groupes SET nb=nb-1 WHERE id_g IN ".$chaine;
			$this->query($sql, $id_u);

		}
		

		//Puis on l'efface de la table contacts
		$sql = "DELETE FROM contacts WHERE id_u_contact=?";
		$this->query($sql, $id_u);

		//Enfin, pour finir, on passe le membre en ANONYME
		//je rajoute champion=0 car si on supprime un champion il faut pas qu'il soit tiré dans les parties !!!
		//11-06-2020 je rajoute banni=1 comme ça il apparait plus non plus dans les classements.
		$sql = "UPDATE utilisateur SET pseudo='anonyme', avatar='0', email=?, valid=0, banni=1, champion=0, valid_chaine='demande_suppression', 
		commentaire_modo=? WHERE id_u=? LIMIT 1";
		$this->query($sql, $email_new, $commentaire_modo, $id_u);

		
	}
	
	

	
	public function testIpBanni($adip) {
		$sql = "SELECT COUNT(id_i) FROM ip_banni WHERE adip=?";
		return $this->queryOne($sql,$adip);
	}
	
	
	
	public function getIdUParrainFromPseudo($pseudo) {
		$sql = "SELECT id_u FROM utilisateur WHERE pseudo=? AND banni=0 AND valid=1";
		return $this->queryOne($sql, $pseudo);
	}
	
	public function activerFromValidChaine($valid_chaine) {
		$sql = "UPDATE utilisateur SET valid=1, valid_chaine=0 WHERE valid_chaine=? LIMIT 1";
		$this->query($sql, $valid_chaine);
	}
	
	
	public function setReinitpwd($id_u, $pwd) {
		$crypted = password_hash($pwd, PASSWORD_DEFAULT);
		$sql = "UPDATE utilisateur SET crypted=?, valid=1, valid_chaine='' WHERE id_u=? LIMIT 1";
		$this->query($sql, $crypted, $id_u);
	}
	
	public function setDemandeSuppression($id_u, $valid_chaine) {
		$sql = "UPDATE utilisateur SET valid_chaine=? WHERE id_u=? LIMIT 1";
		$this->query($sql, $valid_chaine, $id_u);
	}
	
	public function createUtilisateur($tab, $secure, $pwd) {
		$crypted = password_hash($pwd, PASSWORD_DEFAULT);
		
		$tab['crypted'] = $crypted;
		
		$sql = "INSERT INTO utilisateur(date_inscription, secure) VALUES(NOW(), ?)";
		$this->query($sql, $secure);
		
		$id_u = $this->getLastId();
	
		$this->majOne($tab, $id_u, $secure);
		return $id_u;
	}

	
	
	

	
	

	
	public function getRang($id_u) {
		$rang = 1;
		$sql = "SELECT miam,total_point_pool FROM utilisateur WHERE id_u=?";
		$info = $this->queryOne($sql, $id_u);
		
		$sql = "SELECT count(*) FROM utilisateur WHERE banni=0 AND miam > ?";
		$rang += $this->queryOne($sql, $info['miam']);

		$sql = "SELECT count(*) FROM utilisateur WHERE banni=0 AND miam=? AND total_point_pool > ? ";
		$rang += $this->queryOne($sql, $info['miam'], $info['total_point_pool']);
		
		
		return $rang;
		
	}
	
	public function getMiamFromIdU($id_u) {
		$sql = "SELECT miam FROM utilisateur WHERE id_u=?";
		return $this->queryOne($sql, $id_u);
	}
	
	public function getNbUtilisateur() {
		$sql = "SELECT COUNT(id_u) AS nb_user FROM utilisateur WHERE banni=0";
	    return $this->queryOne($sql);
	}

	public function setVisite($id_u, $adip) {
		
		$sql = "UPDATE utilisateur SET nb_visites_fiche=nb_visites_fiche+1, ip_visites_fiche=? WHERE id_u=? AND ip_visites_fiche<>? LIMIT 1";
		$this->query($sql, $adip, $id_u, $adip);
		
	}


	public function getInfoDefiFromIdU($id_u) {
		$sql = "SELECT nb_defi_recus, nb_defi_gagne, nb_defi_gagne_affilee, nb_defi_envoyes FROM utilisateur WHERE id_u=? LIMIT 1";
		return $this->queryOne($sql, $id_u);
	}
	
	
	public function getLastOperations($id_u) {
		$id_u = intval($id_u);
		$result=array();
		$sql = "SELECT pool_utilisateur.id_p,pool_utilisateur.debut,sum(nb_miam) as nb_miam,numero 
		FROM pool_utilisateur JOIN pool ON pool_utilisateur.id_p = pool.id_p where id_u=? AND nb_miam > 0 
		GROUP BY pool_utilisateur.id_p ORDER BY pool_utilisateur.fin DESC LIMIT ".self::NB_OPERATION;
		
		$r = $this->query($sql, $id_u);
		foreach($r as $res) {
			$result[$res['debut']] = array(
				'montant' => $res['nb_miam'],
				'type' => self::GAIN_POOL,
				'id_p' => $res['id_p'],
				'numero' => $res['numero'],		
				);
		}
				
		$sql = 	"SELECT * FROM defi JOIN utilisateur ON defi.id_defie = utilisateur.id_u 
		WHERE id_defieur=? AND termine=1 ORDER BY debut DESC LIMIT ".self::NB_OPERATION;
		$r = $this->query($sql, $id_u);
		
		foreach($r as $res) {
			$result[$res['debut']	] = array(						
						'type' => self::GAIN_DEFI_DEFIEUR,
						'id_defie' => $res['id_defie'],
						'pseudo' => $res['pseudo'],
						);
						
			$result[$res['debut']]['montant'] = ($res['point_defieur'] > $res['point_defie']) ? $res['mise']: -$res['mise'];  
		}
		
		
		$sql = "SELECT * FROM defi JOIN utilisateur ON defi.id_defieur = utilisateur.id_u 
		WHERE id_defie=? AND termine=1 ORDER BY debut DESC LIMIT ".self::NB_OPERATION;
		$r = $this->query($sql, $id_u);
		
		foreach($r as $res) {
				$result[$res['debut']	] = array(						
						'type' => self::GAIN_DEFI_DEFIE,
						'id_defieur' => $res['id_defieur'],
						'pseudo' => $res['pseudo'],
						);
						
			$result[$res['debut']]['montant'] = ($res['point_defie'] >= $res['point_defieur']) ? $res['mise']: -$res['mise'];  						
		}

		
		
		$sql = "SELECT * FROM cagnotte WHERE id_u=? ORDER BY date DESC LIMIT ".self::NB_OPERATION;
		$r = $this->query($sql, $id_u);
		
		foreach($r as $res) {
			$result[$res['date']] = array('type' => self::GAIN_CAGNOTTE, 'montant' => $res['miam']);
		}
		
		
		
		krsort($result);
		$result = array_chunk($result,self::NB_OPERATION,true);
		if ($result) {
			return $result[0];
		} 
		return array();

	}
	
	
	
	
	
	public function getAllMembre() {
		$sql = "SELECT COUNT(id_u) FROM utilisateur WHERE valid_chaine<>'reserve_admin' AND champion=0";
		return $this->queryOne($sql);
	}
	
	public function getAllFromJour($madate) {
		$sql = "SELECT COUNT(id_u) FROM utilisateur WHERE valid_chaine<>'reserve_admin' AND champion=0 AND date(date_inscription) = '".$madate."'";
		return $this->queryOne($sql);
	}

	
*/


	
}
