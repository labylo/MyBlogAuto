<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once ('api/PHPMailer/Exception.php');
require_once ('api/PHPMailer/PHPMailer.php');
require_once ('api/PHPMailer/SMTP.php');

class FancyMail extends PHPMailer {


	const LOGO_BASE_64 = false; //true = envoi le logo en base 64 
	const DEFAULT_CHARSET = 'utf-8';
	const MAIL_PATH = "mail/";
	const DISPLAY_TEMPLATE = false; //true pour voir les templates avant envoi (ne fonctionne pas en PROD)

	//DKIM LeBlogAuto
	const DKIM_SIGNATURE = false; //true pour signer DKIM
	const DKIM_PRIVATE_PATH = ""; //RACINE_PATH."/lib/key/dkim.private";
	const DKIM_SELECTOR = ""; //"1588350044.yams";
	
	
	public static function isValidMail($mail) {
		return filter_var($mail,FILTER_VALIDATE_EMAIL);
	}
	
	
	public function sendMailTest($tab_var, $email, $sujet, $log){

		$tab_email = array(
			"template_html" => "mail_maj_fiche.php",
			"template_text" => "mail_maj_fiche_text.php",
			"to_email" => $email,
			
			"from_email" => SITE_MAIL_REPLYTO,
			"replyto_email" => SITE_MAIL_REPLYTO,
			
			"sujet" => SITE_NAME." ".$sujet,
			"titre" => $sujet." (test)",
			"tab_var" => $tab_var,
			"noreply_text" => true,
			"desabo_text" => false,
			"desabo_link" => false,
		);

		
		$test = 1;
		if ( $log ) $test = 2;
		
		return $this->sendMail($tab_email, $test);
		
	}
	
	
	private static function getMailText($template_text, $sujet, $titre, $message, $noreply_text, $desabo_link, $desabo_text, $signature_text, $tab_var) {
		$chemin = str_replace('lib', self::MAIL_PATH , __DIR__);
		
		if ( $template_text ) {
		
			ob_start();	
				include($chemin.$template_text);
				$mail_text = ob_get_contents();
			ob_end_clean();

					
		}else{
			$mail_text = html_entity_decode(trim(strip_tags(preg_replace('/<(head|title|style|script)[^>]*>.*?<\\/\\1>/si', '', $html))), ENT_QUOTES, self::DEFAULT_CHARSET);
			$mail_text = preg_replace('/\t+/', '', $mail_text);
			$mail_text = preg_replace("/[\r\n]+/", "\n", $mail_text);
		}
		
		return $mail_text;

	}

	
	private function getMailHtml($template_html, $sujet, $titre, $message, $noreply_text, $desabo_link, $desabo_text, $signature_text, $tab_var) {
		$chemin = str_replace('lib', self::MAIL_PATH , __DIR__);

		$logo_base_64 = self::LOGO_BASE_64;
		$site_desabo_link = "";
		
		ob_start();	
			include($chemin.$template_html);
			$mail_html = ob_get_contents();
		ob_end_clean();

		return $mail_html;
		
	}
	
	public function sendMail($tab_email, $test=0) {
		
		if ( !array_key_exists('titre', $tab_email ) ) $tab_email['titre']=$tab_email['sujet'];
		if ( !array_key_exists('to_name', $tab_email ) ) $tab_email['to_name']=false;
		
		if ( !array_key_exists('html_uniquement', $tab_email ) ) $tab_email['html_uniquement']=false;
		if ( !array_key_exists('log_sendmail', $tab_email ) ) $tab_email['log_sendmail']=false;
		
		
		if ( !array_key_exists('from_email', $tab_email ) ) $tab_email['from_email']=SITE_MAIL_REPLYTO;
		if ( !array_key_exists('replyto_email', $tab_email ) ) $tab_email['replyto_email']=SITE_MAIL_NOREPLY;
		
		if ( !array_key_exists('from_name', $tab_email ) ) $tab_email['from_name']=SITE_NAME;
		if ( !array_key_exists('replyto_name', $tab_email ) ) $tab_email['replyto_name']=SITE_NAME;
	
		if ( !array_key_exists('cc', $tab_email ) ) $tab_email['cc']=false;
		
		if ( !array_key_exists('template_html', $tab_email ) ) $tab_email['template_html']="mail_html.php";
		if ( !array_key_exists('template_text', $tab_email ) ) $tab_email['template_text']="mail_html_text.php";
		
		if ( !array_key_exists('message', $tab_email ) ) $tab_email['message']="";
		
		if ( !array_key_exists('noreply_text', $tab_email ) ) $tab_email['noreply_text']=true;
		if ( !array_key_exists('desabo_link', $tab_email ) ) $tab_email['desabo_link']=true;
		if ( !array_key_exists('desabo_text', $tab_email ) ) $tab_email['desabo_text']=true;
		if ( !array_key_exists('signature_text', $tab_email ) ) $tab_email['signature_text']=true;
		
		if ( !array_key_exists('format', $tab_email ) ) $tab_email['format']="html";
		
		if ( !array_key_exists('tab_var', $tab_email ) ) $tab_email['tab_var']=false;
					
		//Création des messages
		$message_html = $this->getMailHtml(
			$tab_email['template_html'], $tab_email['sujet'], $tab_email['titre'], $tab_email['message'], 
			$tab_email['noreply_text'], $tab_email['desabo_link'], $tab_email['desabo_text'], $tab_email['signature_text'], 
			$tab_email['tab_var']
		);
		
		$message_text = $this->getMailText(
			$tab_email['template_text'], $tab_email['sujet'], $tab_email['titre'], $tab_email['message'], 
			$tab_email['noreply_text'], $tab_email['desabo_link'], $tab_email['desabo_text'], $tab_email['signature_text'], 
			$tab_email['tab_var']
		);
		
		if ( ! PRODUCTION && self::DISPLAY_TEMPLATE ) {
			echo $message_text."<hr>";
			echo $message_html."<hr>";
			exit;
		}
		
		return $this->sendViaPhpMailer($tab_email, $message_html, $message_text, $test);
	}
	

	private function sendViaPhpMailer($tab, $message_html, $message_text) {
		
	
		$html_uniquement = $tab['html_uniquement'];

		if ( $tab['format'] == "html" ) {
			$this->isHTML(true);
			$this->Body = $message_html;
			if ( ! $html_uniquement ) {
				$this->AltBody = $message_text;
			}
		}else{
			$this->ContentType = 'text/plain'; 
			$this->isHTML(false);
			//$this->Body = $tab['message'];
			$this->Body = $message_text;
		}

		/*
		$mail->Host = "smtp.gmail.com";
		$mail->SMTPAuth = true;
		$mail->Username = 'atalanth@gmail.com';
		$mail->Password = '25sata11 ';
		*/


		/*
		echo "from_email=".$from_email."<br>";
		echo "replyto_email=".$replyto_email."<br>";
		echo "to_email=".$to_email."<br>";
		echo "sujet=".$sujet."<br>";
		echo "message=".$message."<br>";
		exit;
		*/
		
		$this->addAddress($tab['to_email'], $tab['to_name']); 
		$this->setFrom($tab['from_email'], $tab['from_name']);
		$this->addReplyTo($tab['replyto_email'], $tab['replyto_name']);
		

		$this->Subject = $tab['sujet'];
		$this->CharSet = self::DEFAULT_CHARSET;

		if ( self::DKIM_SIGNATURE ) {
			$this->DKIM_domain = SITE_URL_COURT;
			$this->DKIM_private = self::DKIM_PRIVATE_PATH;
			$this->DKIM_selector = self::DKIM_SELECTOR;
			$this->DKIM_passphrase = '';
			$this->DKIM_identity = $tab['from_email'];
		}
	
		if($this->send()){
			if ( $tab['log_sendmail'] ) $this->saveLogMail($tab['template_html']);
			$msg = "Message envoyé avec PHPMailer : OK";
		}else{
			$msg = "Message non envoyé. PHPMailer Error : " . $this->ErrorInfo;
		}

		$this->clearAllRecipients();
		$this->clearAddresses();
		$this->clearAttachments();
		
		return $msg;
	}
	
	
	public function doSendInscriptionCode($pseudo, $to_email, $code) {
		$tab_var = array(
			"pseudo" => $pseudo,
			"code" => $code,
		);
		
		$tab_email = array(
			"template_html" => "mail_inscription.php",
			"template_text" => "mail_inscription_text.php",
			"to_email" => $to_email,
			"from_email" => SITE_MAIL_REPLYTO,
			"replyto_email" => SITE_MAIL_NOREPLY,
			"sujet" => "Votre inscription sur ".SITE_NAME,
			"titre" => "Merci de valider votre inscription",
			"tab_var" => $tab_var,
			"noreply_text" => true,
			"desabo_text" => false,
			"desabo_link" => false,
		);
		
		return $this->sendMail($tab_email);
	}
	
	
	public function doSendContactSite($replyto_email, $sujet, $message) {
		$tab_email = array(
			"to_email" => SITE_MAIL_REPLYTO,
			"from_email" => $replyto_email,
			"replyto_email" => $replyto_email,
			"format" => "text",
			"sujet" => $sujet,
			"message" => $message,
			"signature_text" => false,
			"noreply_text" => false,
			"desabo_text" => false,
			"desabo_link" => false,
		);

		return $this->sendMail($tab_email);
	}
	

	public function doSendAlertWebmaster($message, $sujet) {
		$tab_email = array(
			"to_email" => SITE_MAIL_REPLYTO,
			"from_email" => SITE_MAIL_REPLYTO,
			"replyto_email" => SITE_MAIL_REPLYTO,
			"format" => "html",
			"sujet" => $sujet,
			"message" => $message,
			"noreply_text" => false,
			"desabo_text" => false,
			"desabo_link" => false,
		);

		$this->sendMail($tab_email);
	}
	
	/*
	public function doSendAlertCadeau($message, $sujet) {
		$tab_email = array(
			"to_email" => SITE_MAIL_REPLYTO,
			"from_email" => SITE_MAIL_REPLYTO,
			"replyto_email" => SITE_MAIL_REPLYTO,
			"format" => "html",
			"sujet" => $sujet,
			"message" => $message,
			"noreply_text" => false,
			"desabo_text" => false,
			"desabo_link" => false,
		);

		$this->sendMail($tab_email);
	}
	*/
	
	/*
	public function doSendSimpleMailAlert($message, $sujet) {
		$tab_email = array(
			"to_email" => SITE_MAIL_REPLYTO,
			"from_email" => SITE_MAIL_REPLYTO,
			"replyto_email" => SITE_MAIL_REPLYTO,
			"format" => "html",
			"sujet" => $sujet,
			"message" => $message,
			"noreply_text" => false,
			"desabo_text" => false,
			"desabo_link" => false,
		);

		$this->sendMail($tab_email);
	}
	*/
}
