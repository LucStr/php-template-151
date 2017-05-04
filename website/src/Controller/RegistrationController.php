<?php

namespace LucStr\Controller;


class RegistrationController extends BaseController
{
  public function Index()
  {
  	return $this->view();
  }
  
  /**
   * @HTTP POST
   */
  public function Register($username, $password)
  {
  	$userService = $this->factory->getUserService();
  	if($userService->register($username, $password)){
  		return $this->view("Login", "Index", [
  				"username" => $username
  		]);
  	} else{
  		return $this->view("Registration", "Index", [
  				"username" => $username
  		]);
  	}
  }
  
  public function CheckUsername($username){
  	$userService = $this->factory->getUserService();
  	echo $userService->checkUsername($username);
  }
}
