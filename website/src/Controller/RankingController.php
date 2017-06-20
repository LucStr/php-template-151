<?php

namespace LucStr\Controller;

class RankingController extends BaseController
{
  

  public function Index()
  {
  	$userService = $this->factory->getUserService();
  	if(isset($_SESSION["userId"])){
  		$userService->updatePoints($_SESSION["userId"]);
  	}
	$users = $userService->getRanking();
  	return $this->view(["users" => $users]);
  }
}
