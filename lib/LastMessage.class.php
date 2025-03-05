<?php
class LastMessage {
	
	const ERROR = "error";
	const MESSAGE = "message";
	
	private $lastMessage;
	private $lastInputError;
	
	public function __construct(){
		$this->lastMessage = false;
		$this->lastInputError = false;
		$this->setTemplate(__DIR__."/../template/LastMessage.php");
	}
	
	public function setTemplate($template_path){
		$this->template_path = $template_path;
	}
	
	public function setLastMessage($message, $secure=1, $float=0, $toplink=0){
		$this->setMessage(self::MESSAGE, $message, $secure, $float, $toplink);
	}
	
	public function setLastError($message,$secure=1, $float=0, $toplink=0){
		$this->setMessage(self::ERROR,$message,$secure,$float,$toplink);
	}

	
	private function setMessage($type, $message, $secure, $float, $toplink){
		$_SESSION['last_message'] = array($type, $message, $secure, $float, $toplink);
	}
	
	private function getMessage(){

		if (! $this->lastMessage){
			$this->lastMessage = array(false,"",true, false, false);
			if (isset($_SESSION['last_message'])){
				$this->lastMessage = $_SESSION['last_message'];
				unset($_SESSION['last_message']);
			}
		}
		return $this->lastMessage;
	}
	
	
	
	
	public function getLastMessagePart($part){
		$lm = $this->getMessage();
		return $lm[$part];
	}
	
	public function getLastMessageType(){
		return $this->getLastMessagePart(0);
	}
	
	public function getLastMessage(){
		return $this->getLastMessagePart(1);
	}
	
	public function getLastMessageSecure(){
		return $this->getLastMessagePart(2);
	}
	
	public function getLastMessageFloat(){
		return $this->getLastMessagePart(3);
	}
	
	public function getLastMessageToplink(){
		return $this->getLastMessagePart(4);
	}
		
	
		
	public function display(){
		if ( ! $this->getLastMessage()){
			return;
		}		
		

		$message_type = $this->getLastMessageType();		
		$message = $this->getLastMessage();
		$secure = $this->getLastMessageSecure();
		$float = $this->getLastMessageFloat();
		$toplink = $this->getLastMessageToplink();
		
		if ( ! $secure ){
			$message = gethecho($message);
		} 
		
		include($this->template_path);
	}
	
}
