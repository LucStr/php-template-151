<?php

namespace LucStr\Service\Build;
use LucStr\Service\Village\BuildService;

class BuildPdoService implements BuildService
{
	private $pdo;
	public function __construct(\Pdo $pdo)
	{
		$this->pdo = $pdo;
	}
	
	public function getVillageQueue($villageId){
		
	}
	
	
}