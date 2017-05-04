<?php

namespace LucStr\Service\Village;
use LucStr\Service\Village\VillageService;

class VillagePdoService implements VillageService
{
	private $pdo;
	public function __construct(\Pdo $pdo)
	{
		$this->pdo = $pdo;
	}
	
	public function getVillagesByUser($userId){
		$stmt = $this->pdo->prepare("SELECT villageId, name FROM village WHERE userId=?");
		$stmt->bindValue(1, $userId);
		$stmt->execute();
		$data = array();
		while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
			$data[] = $row;
		}
		return $data;
	}
	
	public function getVillageById($villageId){
		$stmt = $this->pdo->prepare("SELECT * FROM village WHERE villageId=?");
		$stmt->bindValue(1, $villageId);
		$stmt->execute();
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}
	
	public function updatevillageById($villageId){
		$village = $this->getVillageById($villageId);
		$lastUpdate = strtotime($village["lastUpdate"]);
		$now = time();
		$secondOffset = $now - $lastUpdate;
		$woodproduction = ($village["woodlvl"]) ? 25 * (1.2 ^ $village["woodlvl"]): 5;
		$stoneproduction = ($village["stonelvl"]) ? 25 * (1.2 ^ $village["stonelvl"]): 5;
		
		$wood = $village["wood"] + $secondOffset * $woodproduction / 3600;
		$stone = $village["stone"] + $secondOffset * $woodproduction / 3600;
		
		$stmt = $this->pdo->prepare("UPDATE village SET wood=?, stone=?, lastUpdate=? WHERE villageId=?");
		$stmt->bindValue(1, $wood);
		$stmt->bindValue(2, $stone);
		$stmt->bindValue(3, date("Y-m-d H:i:s", $now));
		$stmt->bindValue(4, $villageId);		
		$stmt->execute();		
	}
}