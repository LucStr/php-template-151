<?php

namespace LucStr\Controller;

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
	
  }
}
