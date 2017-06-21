<?php 
namespace LucStr\Business;

class User {
	public  function __construct($arr){
		$this->userId = $arr["userId"];
		$this->username = $arr["username"];
		$this->email = $arr["email"];
		$this->password = $arr["password"];
		$this->points = $arr["points"];
		$this->activated = $arr["activated"];
		$this->confirmationUUID = $arr["confirmationUUID"];		
	}
	public $userId;
	public $username;
	public $email;
	public $password;
	public $points;
	public $activated;
	public $confirmationUUID;
}