<?php

namespace LucStr\Controller;

class IndexController extends BaseController
{
  public function Index() {  	
  	// Create the message
  	$message =  \Swift_Message::newInstance()
  	
  	// Give the message a subject
  	->setSubject('Your subject')
  	
  	// Set the From address with an associative array
  	->setFrom(array('luca.strebel@gmx.ch' => 'Luca Strebel'))
  	
  	// Set the To addresses with an associative array
  	->setTo(array('luca.cubus@gmail.com' => 'A name'))
  	
  	// Give it a body
  	->setBody('Here is the message itself', 'text/html')
  	
  	// And optionally an alternative body
  	->addPart('<q>Here is the message itself alt.</q>', 'text/html')
  	;
  	
  	//$result = $this->factory->getMailer()->send($message);
  	
  	return $this->view();
  } 
}
