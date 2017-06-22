<?php 
namespace LucStr\Business;

class User {
	public  function __construct($arr){
		$this->userId = $arr["userId"];
		$this->username = \htmlentities($arr["username"]);
		$this->email = $arr["email"];
		$this->password = $arr["password"];
		$this->points = $arr["points"];
		$this->activated = $arr["activated"];
		$this->confirmationUUID = $arr["confirmationUUID"];		
	}
	private $userId;
	private $username;
	private $email;
	private $password;
	private $points;
	private $activated;
	private $confirmationUUID;
	
	public function getUserId(){
		return $this->userId;
	}
	public function getUsername(){
		return $this->username;
	}
	public function getEmail(){
		return $this->email;
	}
	public function getPassword(){
		return $this->password;
	}
	public function getPoints(){
		return $this->points;
	}
	public function getActivated(){
		return $this->activated;
	}
	public function getConfirmationUUID(){
		return $this->confirmationUUID;
	}
	
	public function setUserId($value){
		$this->userId = $value;
	}
	public function setUsername($value){
		$this->username = $value;
	}
	public function setEmail($value){
		$this->email = $value;
	}
	public function setPassword($value){
		$this->Password = $value;
	}
	public function setPoints($value){
		$this->points = $value;
	}
	public function setActivated($value){
		$this->activated = $value;
	}
	public function setConfirmationUUID($value){
		$this->confirmationUUID = $value;
	}
	
	
}