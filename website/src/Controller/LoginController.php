<?php

namespace LucStr\Controller;

use LucStr\SimpleTemplateEngine;
use LucStr\Service\User\UserService;

class LoginController 
{
  /**
   * @var LucStr\SimpleTemplateEngine Template engines to render output
   */
  private $template;
  private $userService;
  /**
   * @param LucStr\SimpleTemplateEngine
   */
  public function __construct(SimpleTemplateEngine $template, UserService $userService)
  {
     $this->template = $template;
     $this->userService = $userService;
  }

  public function showLogin()
  {
  	echo $this->template->render("login.html.php");
  }
  
  public function login(array $data)
  {
  	if(!array_key_exists("username", $data) OR !array_key_exists("password", $data)){
  		$this->showLogin();
  		return;
  	}
  	if($this->userService->authenticate($data["username"], $data["password"])){
  		$_SESSION["username"] = $data["username"];
  	} else{
  		echo $this->template->render("login.html.php", [
  				"username" => $data["username"]  				
  		]);
  		echo "Login failed";
  	}

  	return;
  }
}
