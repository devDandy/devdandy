<?php
	class Email{
		
			private $recipient; //send to email address
			private $sender;  //from email address
			private $subject;  //content for subject
			private $message;  //content
		
		//constructor method goes here
		public function  __construct($inRecipient){
		// the following brakes. Use below instead		setRecipient($inRecipient);  
			$this->recipient = $inRecipient; //set property value
		}
		
		
		
		//sets and gets
		public function setRecipient ($inRecipient){
			$this->recipient = $inRecipient;
		}
		
		public function getRecipient (){
			return $this->recipient;
		}
		
		public function setSender ($inSender){
			$this->sender = $inSender;
		}
		
		public function getSender (){
			return $this->sender;
		}
		
		public function setSubject ($inSubject){
			$this->subject = $inSubject;
		}
		
		public function getSubject (){
			return $this->subject;
		}
		
		public function setMessage ($inMessage){
			$this->message = $inMessage;
		}
		
		public function getMessage (){
			return $this->message;
		}
		
		public function sendMail(){
		
			$to = $this->getRecipient();
			$subject = $this->getSubject();
			$messageText = wordwrap($this->getMessage(), 65, "/n", FALSE);
			$header = 'From: ' . $this->getSender();
			
			return mail($to, $subject, $messageText, $header);
			
		}
	}
?>