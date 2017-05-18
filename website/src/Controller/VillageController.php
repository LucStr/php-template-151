<?php

namespace LucStr\Controller;

use LucStr\MessageHandler;

class VillageController extends BaseController
{
  

  public function Index()
  {
	$villageService = $this->factory->getVillageService();
	$villages = $villageService->getVillagesByUser($_SESSION["userId"]);	
  	return $this->view(["villages" => $villages]);
  }
  
  public function Overview($villageId){
  	$villageService = $this->factory->getVillageService();
  	$villageService->updateVillageById($villageId);
  	$village = $villageService->getVillageById($villageId);
	if($village["userId"] != $_SESSION["userId"]){
		echo "Du hast keine Berechtigung für dieses Dorf!";
		return;
	}
	return $this->view(["village" => $village]);
  }
  
  public function Main($villageId){
  	$villageService = $this->factory->getVillageService();
  	$villageService->updateVillageById($villageId);
  	$village = $villageService->getVillageById($villageId);
  	if($village["userId"] != $_SESSION["userId"]){
  		echo "Du hast keine Berechtigung für dieses Dorf!";
  		return;
  	}
  	return $this->view(["village" => $village, "buildings" => $villageService->buildings]);
  }
  public function Build($villageId, $building){
  	$villageService = $this->factory->getVillageService();
  	$result = $villageService->build($villageId, $building);
  	MessageHandler::object($result);
	$this->redirectToAction("Village", "Main", ["villageId" => $villageId]);
  }
}
