<?php
namespace LucStr\Service\Village;

interface VillageService
{
	public function getVillagesByUser($userId);
	public function getVillageById($villageId);
	public function updatevillageById($villageId);	
}