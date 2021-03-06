<?php

namespace LucStr\Controller;

use LucStr\MessageHandler;

class LoginController extends BaseController
{
  public function Index()
  {
  	$this->CreateCSRF();
  	return $this->view();
  }
  
  /**
   * @HTTP POST
   * @CSRF ON
   */
  public function Authenticate($username, $password)
  {
  	$userService = $this->factory->getUserService();
  	$user = $userService->getUserByUsername($username);
  	if(!$user->getActivated()){  		
  		MessageHandler::danger("Please Confirm your Mail");
  		return $this->view("Login", "Index", [
  				"username" => $username  				
  		]);
  	}
  	if($userService->checkCredentials($user, $password)){
  		session_regenerate_id();
  		$_SESSION["userId"] = $user->getuserId();
  		$_SESSION["username"] = $user->getusername();
  		return $this->redirectToAction("Index", "Index");
  	} else{
  		MessageHandler::danger("Login failed!");
  		return $this->view("Login", "Index", [
  				"username" => $username  				
  		]);
  	}
  }
  
  public function Logout(){
  	session_destroy();
  	$this->redirectToAction("Index", "Index");
  }
}
