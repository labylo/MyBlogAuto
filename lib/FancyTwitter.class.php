<?php
require_once RACINE_PATH."/lib/api/TwitterOAuth/vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

class FancyTwitter {
	private function troncate($texte, $nb) {

		if (strlen( $texte ) > $nb) {
			$texte = substr($texte,0,$nb);
			$espace = strrpos($texte, " ");
			$texte = substr($texte, 0, $espace)."..."; 
		}
		return $texte;
	}
	
	
	public function tweet($article, $categorie, $url) {
		
		$url = SITE_URL."/article/actu/".$url;
		
		$texte = $categorie." : ".$article;
		
		$long_texte = strlen($texte);
		$long_url = strlen($url);
		$long_total = $long_texte+$long_url;
		
		if ( $long_total >= 280 ) {
			$long_troncate = (278 - $long_url);
			$texte = $this->troncate($texte, $long_troncate);
		}

		$status = $texte." ".$url;
		
		
		$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, TWITTER_ACCESS_TOKEN, TWITTER_ACCESS_TOKEN_SECRET);
		  
		$result = $connection->post("statuses/update", ["status" => $status]);
		//$connection->setApiVersion('2');
		
		if ($connection->getLastHttpCode() == 200) {
			return "ok";
		} else {
			return $result->errors[0]->message;
		}
	}

}
