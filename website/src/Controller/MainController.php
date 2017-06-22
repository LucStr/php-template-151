<?php

namespace LucStr\Controller;

use LucStr\MessageHandler;

class MainController extends BaseController
{
  

  public function Index($villageId)
  {
  	if(!isset($_SESSION["userId"])){
  		MessageHandler::info("Please login first!");
  		return $this->redirectToAction("Index", "Index");
  	}
  	$villageService = $this->factory->getVillageService();
  	$villageService->updateVillageById($villageId);
  	$village = $villageService->getDetailedVillageById($villageId);
  	if($village["userId"] != $_SESSION["userId"]){
  		MessageHandler::danger("You dont have the permission to visit this village");
  		return $this->redirectToAction("Index", "Index");
  	}
  	return $this->view(["village" => $village]);
  }
  
 
  public function Build($villageId, $building){
  	if(!isset($_SESSION["userId"])){
  		MessageHandler::info("Please login first!");
  		return $this->redirectToAction("Index", "Index");
  	}
  	$villageService = $this->factory->getVillageService();
  	$result = $villageService->build($villageId, $building);
  	MessageHandler::object($result);
	$this->redirectToAction("Main", "Index", ["villageId" => $villageId]);
  }
}
