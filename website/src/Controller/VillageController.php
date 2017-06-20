<?php

namespace LucStr\Controller;

use LucStr\MessageHandler;

class VillageController extends BaseController
{
  

  public function Index()
  {
  	if(!isset($_SESSION["userId"])){
  		MessageHandler::info("Bitte logge dich zuerst ein!");
  		return $this->redirectToAction("Index", "Index");
  	}
	$villageService = $this->factory->getVillageService();
	$villages = $villageService->getVillagesByUser($_SESSION["userId"]);	
  	return $this->view(["villages" => $villages]);
  }
  
  public function Overview($villageId){
  	if(!isset($_SESSION["userId"])){
  		MessageHandler::info("Bitte logge dich zuerst ein!");
  		return $this->redirectToAction("Index", "Index");
  	}
  	$villageService = $this->factory->getVillageService();
  	$villageService->updateVillageById($villageId);
  	$village = $villageService->getDetailedVillageById($villageId);
	if($village["userId"] != $_SESSION["userId"]){
		MessageHandler::danger("Du hast keine Berechtigung für dieses Dorf!");
		return $this->redirectToAction("Index", "Index");
	}
	return $this->view(["village" => $village]);
  }
  
  public function Main($villageId){
  	if(!isset($_SESSION["userId"])){
  		MessageHandler::info("Bitte logge dich zuerst ein!");
  		return $this->redirectToAction("Index", "Index");
  	}
  	$villageService = $this->factory->getVillageService();
  	$villageService->updateVillageById($villageId);
  	$village = $villageService->getDetailedVillageById($villageId);
  	if($village["userId"] != $_SESSION["userId"]){
  		MessageHandler::danger("Du hast keine Berechtigung für dieses Dorf!");
  		return $this->redirectToAction("Index", "Index");
  	}
  	return $this->view(["village" => $village]);
  }
  
  public function Build($villageId, $building){
  	if(!isset($_SESSION["userId"])){
  		MessageHandler::info("Bitte logge dich zuerst ein!");
  		return $this->redirectToAction("Index", "Index");
  	}
  	$villageService = $this->factory->getVillageService();
  	$result = $villageService->build($villageId, $building);
  	MessageHandler::object($result);
	$this->redirectToAction("Village", "Main", ["villageId" => $villageId]);
  }
  
  public function Create(){
  	$userId = $_SESSION["userId"];
  	if(!isset($userId)){
  		MessageHandler::info("Bitte logge dich zuerst ein!");
  		return $this->redirectToAction("Index", "Index");
  	}
  	$villageService = $this->factory->getVillageService();
  	$villages = $villageService->getVillagesByUser($userId);
  	if(count($villages) == 0){
  		$villageService->createVillage($userId);
  	} else{
  		MessageHandler::info("Du hast bereits ein Dorf!");
  	}
  	return $this->redirectToAction("Village", "Index");
  }
  
  /**
   * @HTTP POST
   * @CSRF ON
   */
  public function ChangeVillageName($villageId, $villagename){
  	if(!isset($_SESSION["userId"])){
  		MessageHandler::info("Bitte logge dich zuerst ein!");
  		return $this->redirectToAction("Index", "Index");
  	}
  	$villageService = $this->factory->getVillageService();
  	$village = $villageService->getDetailedVillageById($villageId);
  	if($village["userId"] != $_SESSION["userId"]){
  		MessageHandler::danger("Du hast keine Berechtigung für dieses Dorf!");
  		return $this->redirectToAction("Index", "Index");
  	}
  	$villageService->updateName($villageId, $villagename);
  	return $this->redirectToAction("Village", "Overview", ["villageId" => $villageId]);
  }
}
