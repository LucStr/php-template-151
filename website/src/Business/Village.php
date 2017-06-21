<?php 
namespace LucStr\Business;

class Village{
	public function __construct($arr){
		$this->villageId = $arr["villageId"];
		$this->name = $arr["name"];
		$this->mainlvl = $arr["mainlvl"];
		$this->stonelvl = $arr["stonelvl"];
		$this->shipyardlvl = $arr["shipyardlvl"];
		$this->farmlvl = $arr["farmlbl"];
		$this->towerlvl = $arr["towerlvl"];
		$this->wood = $arr["wood"];
		$this->stone = $arr["stone"];
		$this->gold = $arr["gold"];
		$this->lastUpdate = $arr["lastUpdate"];
		$this->userId = $arr["userId"];
		$this->coordY = $arr["coordY"];
		$this->coordX = $arr["coordX"];
		$this->points = $arr["points"];
	}
	private $villageId;
	private $name;
	private $mainlvl;
	private $stonelvl;
	private $shipyardlvl;
	private $farmlvl;
	private $towerlvl;
	private $wood;
	private $stone;
	private $gold;
	private $lastUpdate;
	private $userId;
	private $coordY;
	private $coordX;
	private $points;
	
	public function getVillageId(){
		return $this->villageId;
	}
	public function getName(){
		return $this->name;
	}
	public function getMainlvl(){
		return $this->mainlvl;
	}
	public function getStonelvl(){
		return $this->stonelvl;
	}
	public function getShipyardlvl(){
		return $this->shipyardlvl;
	}
	public function getFarmlvl(){
		return $this->farmlvl;
	}
	public function getTowerlvl(){
		return $this->towerlvl;
	}
	public function getWood(){
		return $this->wood;
	}
	public function getStone(){
		return $this->stone;
	}
	public function getGold(){
		return $this->gold;
	}
	public function getLastUpdate(){
		return $this->lastUpdate;
	}
	public function getUserId(){
		return $this->userId;
	}
	public function getCoordY(){
		return $this->coordY;
	}
	public function getCoordX(){
		return $this->coordX;
	}
	public function getPoints(){
		return $this->points;
	}
	
	public function setVillageId($value){
		$this->villageId = $value;
	}
	public function setName($value){
		$this->name = $value;
	}
	public function setMainlvl($value){
		$this->mainlvl = $value;
	}
	public function setStonelvl($value){
		$this->stonelvl = $value;
	}
	public function setShipyardlvl($value){
		$this->shipyardlvl = $value;
	}
	public function setFarmlvl($value){
		$this->farmlvl = $value;
	}
	public function setTowerlvl($value){
		$this->towerlvl = $value;
	}
	public function setWood($value){
		$this->wood = $value;
	}
	public function setStone($value){
		$this->stone = $value;
	}
	public function setGold($value){
		$this->gold = $value;
	}
	public function setLastUpdate($value){
		$this->lastUpdate = $value;
	}
	public function setUserId($value){
		$this->userId = $value;
	}
	public function setCoordY($value){
		$this->coordY = $value;
	}
	public function setCoordX($value){
		$this->coordX = $value;
	}
	public function setPoints($value){
		$this->points = $value;
	}
}