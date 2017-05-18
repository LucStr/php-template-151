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
	
	public $buildings = array(
			"main" => array(
					"name" => "Hauptgebäude",
					"woodcost" => 90,
					"stonecost" => 80,
					"goldcost" => 0,
					"time" => 10,
					"costMultiplier" => 1.25,
					"efficiencyMultiplier" => 1.05,
					"timeMultiplier" => 1.15,
			),
			"wood" => array(
					"name" => "Holzfäller",
					"woodcost" => 50,
					"stonecost" => 65,
					"goldcost" => 0,
					"time" => 10,
					"costMultiplier" => 1.25,
					"efficiencyMultiplier" => 1.2,
					"timeMultiplier" => 1.15,
			),
			"stone" => array(
					"name" => "Steingrube",
					"woodcost" => 65,
					"stonecost" => 50,
					"goldcost" => 0,
					"time" => 10,
					"costMultiplier" => 1.25,
					"efficiencyMultiplier" => 1.2,
					"timeMultiplier" => 1.15,
			)
	);
	
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
		$woodproduction = ($village["woodlvl"]) ? 25 * pow($this->buildings["wood"]["efficiencyMultiplier"], $village["woodlvl"]): 5;
		$stoneproduction = ($village["stonelvl"]) ? 25 * pow($this->buildings["stone"]["efficiencyMultiplier"], $village["stonelvl"]): 5;
		
		$wood = $village["wood"] + $secondOffset * $woodproduction / 1;
		$stone = $village["stone"] + $secondOffset * $stoneproduction / 1;
		
		$this->updateQueue($villageId);
		
		$query = "UPDATE village SET wood=?, stone=?, lastUpdate=? WHERE villageId=?";	

		$stmt = $this->pdo->prepare($query);
		$stmt->bindValue(1, $wood);
		$stmt->bindValue(2, $stone);
		$stmt->bindValue(3, date("Y-m-d H:i:s", $now));
		$stmt->bindValue(4, $villageId);		
		$stmt->execute();	
	}
	
	public function getDetailedVillageById($villageId){
		$village = $this->getVillageById($villageId);
		$village["buildings"] = $this->buildings;
		
		$village["woodproduction"] = ($village["woodlvl"]) ? 25 * pow($this->buildings["wood"]["efficiencyMultiplier"], $village["woodlvl"]): 5;
		$village["stoneproduction"] = ($village["stonelvl"]) ? 25 * pow($this->buildings["stone"]["efficiencyMultiplier"], $village["stonelvl"]): 5;
		
		foreach ($village["buildings"] as $key => $building){
			$village["buildings"][$key]["woodcost"] = intval($building["woodcost"] * pow($building["costMultiplier"], $village[$key . "lvl"]));
			$village["buildings"][$key]["stonecost"] = intval($building["stonecost"] * pow($building["costMultiplier"], $village[$key . "lvl"]));
			$village["buildings"][$key]["goldcost"] = intval($building["goldcost"] * pow($building["costMultiplier"], $village[$key . "lvl"]));
			$village["buildings"][$key]["time"] = intval($building["time"] * pow($building["timeMultiplier"], $village["mainlvl"]));
			$village["buildings"][$key]["newlvl"] = $village[$key . "lvl"] + 1;
		}
		return $village;
	}
	
	public function build($villageId, $building){
		$this->updateVillageById($villageId);
		$village = $this->getDetailedVillageById($villageId);
		$buildingobj = $village["buildings"][$building];
		if($buildingobj["woodcost"] > $village["wood"] || $buildingobj["stonecost"] > $village["stone"] || $buildingobj["goldcost"] > $village["gold"]){
			return ["type" => "danger", "message" => "Du hast nicht genügend Rohstoffe!"];
		}
		
		$wood = $village["wood"] - $buildingobj["woodcost"];
		$stone = $village["stone"] - $buildingobj["stonecost"];
		$gold = $village["gold"] - $buildingobj["goldcost"];
		
		$string = "UPDATE village SET wood=?, stone=? WHERE villageId=?;";
		$string .= "INSERT INTO `build` (`building`, `startTime`, `endTime`, `villageId`, `seconds`) VALUES (?, now(), NULL, ?, ?);";
		
		$stmt = $this->pdo->prepare($string);
		
		$stmt->bindValue(1, $wood);
		$stmt->bindValue(2, $stone);
		$stmt->bindValue(3, $villageId);

		
		$stmt->bindValue(4, $building);
		$stmt->bindValue(5, $villageId);
		$stmt->bindValue(6, $buildingobj["time"]);
		
		$stmt->execute();
		
		if($stmt->rowCount()){
			return array ("type" => "success", "message" => $buildingobj['name'] . " wurde Auf Stufe " . $buildingobj['newlvl'] . " ausgebaut!");
		} else {
			return array("type" => "danger", "message" => "Es ist ein unbekannter Fehler aufgetreten.");
		}
	}
	
	public function updateQueue($village){
		$query = "SELECT COUNT(build.buildId) AS levels, build.building FROM build LEFT JOIN village ON village.villageId = build.villageId WHERE village.villageId = ? AND build.endTime IS NULL AND DATE_ADD(build.startTime, INTERVAL build.seconds SECOND) < now() GROUP BY build.building;";
		$query .= "UPDATE build SET endTime=now() WHERE villageId = ? AND endTime IS NULL AND DATE_ADD(startTime, INTERVAL build.seconds SECOND) < now();";
		$stmt = $this->pdo->prepare($query);
		$stmt->bindValue(1, $village["villageId"]);
		$stmt->bindValue(2, $village["villageId"]);
		
		$stmt->execute();
		
		$buildingsToUpdate = array();
		while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
			$buildingsToUpdate[] = $row;
		}	
		
		if(count($buildingsToUpdate) > 0){
			$query = "UPDATE village SET ";
			foreach($buildingsToUpdate as $building){
				$query .= $building["building"] . "lvl=" . ($village[$building["building"] . "lvl"] + $building["levels"]);
			}
			$query .= " WHERE villageId=?";
			$stmt = $this->pdo->prepare($query);
			$stmt->bindValue(1, $villageId);
			echo $query;
			die();
			
			$stmt->execute();

		}
	}
	
}