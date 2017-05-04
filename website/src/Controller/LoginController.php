<?php

namespace LucStr\Controller;

class LoginController extends BaseController
{
  public function Index()
  {
  	return $this->view();
  }
  
  /**
   * @HTTP POST
   */
  public function Authenticate($username, $password)
  {
  	$userService = $this->factory->getUserService();
  	$stmt = $userService->authenticate($username, $password);
  	if($stmt->rowCount() == 1){
  		$stmt->execute();
  		$_SESSION["userId"] = $stmt->fetch(\PDO::FETCH_ASSOC)["userId"];
  		$_SESSION["username"] = $username;
  		$this->redirectToAction("Index", "Index");
  	} else{
  		$this->view("Login", "Index", [
  				"username" => $username  				
  		]);
  		echo "login failed!";
  	}
  }
  
  public function Logout(){
  	unset($_SESSION["username"]);
  	$this->redirectToAction("Index", "Index");
  }
}
