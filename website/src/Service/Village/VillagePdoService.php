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
					"basepoints" => 2.5,
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
					"basepoints" => 1,
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
					"basepoints" => 1,
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
		
		$wood = $village["wood"] + $secondOffset * $woodproduction / 3600;
		$stone = $village["stone"] + $secondOffset * $stoneproduction / 3600;
		
		$this->updateQueue($village);
		$this->updatePoints($village);
		
		$query = "UPDATE village SET wood=?, stone=?, lastUpdate=? WHERE villageId=?";	

		$stmt = $this->pdo->prepare($query);
		$stmt->bindValue(1, $wood);
		$stmt->bindValue(2, $stone);
		$stmt->bindValue(3, date("Y-m-d H:i:s", $now));
		$stmt->bindValue(4, $villageId);		
		$stmt->execute();	
		
		return $village;
	}
	
	public function getDetailedVillageById($villageId){
		$village = $this->getVillageById($villageId);
		$village["buildings"] = $this->buildings;
		$village["queue"] = $this->getQueue($villageId);
		
		$highestLevel = array();
		foreach ($village["queue"] as $build){
			if(!isset($highestLevel[$build["building"]]) || $highestLevel[$build["building"]] < $build["level"]){
				$highestLevel[$build["building"]] = $build["level"];
			}
		}		
		
		$village["woodproduction"] = ($village["woodlvl"]) ? 25 * pow($this->buildings["wood"]["efficiencyMultiplier"], $village["woodlvl"]): 5;
		$village["stoneproduction"] = ($village["stonelvl"]) ? 25 * pow($this->buildings["stone"]["efficiencyMultiplier"], $village["stonelvl"]): 5;
		
		foreach ($village["buildings"] as $key => $building){
			if(isset($highestLevel[$key])){
				$village["buildings"][$key]["newlvl"] = $highestLevel[$key] + 1;
			} else{
				$village["buildings"][$key]["newlvl"] = $village[$key . "lvl"] + 1;
			}			
			$village["buildings"][$key]["woodcost"] = intval($building["woodcost"] * pow($building["costMultiplier"], $village["buildings"][$key]["newlvl"]));
			$village["buildings"][$key]["stonecost"] = intval($building["stonecost"] * pow($building["costMultiplier"], $village["buildings"][$key]["newlvl"]));
			$village["buildings"][$key]["goldcost"] = intval($building["goldcost"] * pow($building["costMultiplier"], $village["buildings"][$key]["newlvl"]));
			$village["buildings"][$key]["time"] = intval($building["time"] * pow($building["timeMultiplier"], $village["buildings"][$key]["newlvl"]) * 1 / pow($this->buildings["main"]["efficiencyMultiplier"], $village["mainlvl"]));
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
		
		$query = "UPDATE village SET wood=?, stone=? WHERE villageId=?;";
		$query .= "INSERT INTO `build` (`building`, `startTime`, `endTime`, `villageId`, `seconds`, `level`, `isDone`) VALUES (?, ?, DATE_ADD(?, INTERVAL ? SECOND), ?, ?, ?, 0);";
		
		$stmt = $this->pdo->prepare($query);
		
		$stmt->bindValue(1, $wood);
		$stmt->bindValue(2, $stone);
		$stmt->bindValue(3, $villageId);

		
		$stmt->bindValue(4, $building);
		$stmt->bindValue(5, date('Y-m-d H:i:s'));
		$stmt->bindValue(6, date('Y-m-d H:i:s'));
		$stmt->bindValue(7, $buildingobj["time"]);
		$stmt->bindValue(8, $villageId);
		$stmt->bindValue(9, $buildingobj["time"]);
		$stmt->bindValue(10, $buildingobj["newlvl"]);
		
		$stmt->execute();
		
		if($stmt->rowCount()){
			return array ("type" => "success", "message" => $buildingobj['name'] . " wurde Auf Stufe " . $buildingobj['newlvl'] . " ausgebaut!");
		} else {
			return array("type" => "danger", "message" => "Es ist ein unbekannter Fehler aufgetreten.");
		}
	}
	
	public function getQueue($villageId){
		$stmt = $this->pdo->prepare("SELECT * FROM build WHERE villageId =? AND isDone = 0 AND endTime > ?");
		$stmt->bindValue(1, $villageId);
		$stmt->bindValue(2, date('Y-m-d H:i:s'));
		$stmt->execute();
		
		$queue = array();
		while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
			$queue[] = $row;
		}
		return $queue;
	}
	
	public function createVillage($userId){
		$stmt = $this->pdo->prepare("INSERT INTO village(userId, name, mainlvl, lastUpdate, wood, stone) VALUES(?, ?, 1, ?, 500, 500)");
		$stmt->bindValue(1, $userId);
		$stmt->bindValue(2, "Neues Dorf");
		$stmt->bindValue(3, date('Y-m-d H:i:s'));
		$stmt->execute();
	}
	
	public function updateName($villageId, $villagename){
		$stmt = $this->pdo->prepare("UPDATE village SET name=? WHERE villageId=?");
		$stmt->bindValue(1, $villagename);
		$stmt->bindValue(2, $villageId);
		$stmt->execute();
		return $stmt->rowCount();
	}
	
	private function updateQueue($village){
		$query = "SELECT MAX(build.level) AS level, build.building FROM build LEFT JOIN village ON village.villageId = build.villageId WHERE village.villageId = ? AND build.isDone = 0 AND build.endTime < ? GROUP BY build.building;";
		$query .= "UPDATE build SET isDone=1 WHERE villageId = ? AND isDone = 0 AND endTime < ?;";
		$stmt = $this->pdo->prepare($query);
		$stmt->bindValue(1, $village["villageId"]);
		$stmt->bindValue(2, date('Y-m-d H:i:s'));
		$stmt->bindValue(3, $village["villageId"]);
		$stmt->bindValue(4, date('Y-m-d H:i:s'));
		
		
		$stmt->execute();
		
		$buildingsToUpdate = array();
		while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
			$buildingsToUpdate[] = $row;
		}	
		
		if(count($buildingsToUpdate) > 0){
			$query = "UPDATE village SET ";
			foreach($buildingsToUpdate as $building){
				$query .= $building["building"] . "lvl=" . $building["level"] . ", ";
			}
			$query = substr($query, 0, -2);
			$query .= " WHERE villageId=?";
			$stmt = $this->pdo->prepare($query);
			$stmt->bindValue(1, $village["villageId"]);			
			$stmt->execute();
		}
	}
	
	private function updatePoints($village){
		$villagepoints = 0;
		foreach ($this->buildings as $key => $building){
			$lvl = intval($village[$key . "lvl"]);
			while($lvl){
				$lvl--;
				$villagepoints += intval($building["basepoints"] * pow(1.2, $lvl));
			}
		}
		$stmt = $this->pdo->prepare("UPDATE village SET points=? WHERE villageId=?");
		$stmt->bindValue(1, $villagepoints);
		$stmt->bindValue(2, $village["villageId"]);
		$stmt->execute();
	}
	
}