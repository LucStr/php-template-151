<?php
namespace LucStr\Service\Village;

interface VillageService
{
	public function getVillagesByUser($userId);
	public function getVillageById($villageId);
	public function updatevillageById($villageId);	
	public function createVillage($userId);
	public function updateName($villageId, $villagename);
}