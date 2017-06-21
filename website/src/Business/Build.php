<?php 
namespace LucStr\Business;


class Build {
	 function  __construct($arr){
	 	$this->buildId = $arr["buildId"]; 
	 	$this->building = $arr["buildId"];
	 	$this->startTime = $arr["startTime"];
	 	$this->endTime = $arr["endTime"];
	 	$this->villageId = $arr["villageId"];
	 	$this->seconds = $arr["seconds"];
	 	$this->isDone = $arr["isDone"];
	 	$this->level = $arr["level"];	 	 
	 }
	public $buildId;
	public $building;
	public $startTime;
	public $endTime;
	public $villageId;
	public $seconds;
	public $isDone;
	public $level;	
}