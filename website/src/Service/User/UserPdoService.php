<?php

namespace LucStr\Service\User;
use LucStr\Service\User\UserService;

class UserPdoService implements UserService
{
	private $pdo;
	public function __construct(\Pdo $pdo)
	{
		$this->pdo = $pdo;
	}
	public function authenticate($username, $password)
	{
		$stmt = $this->pdo->prepare("SELECT * FROM user WHERE username=? AND password=?");
		$stmt->bindValue(1, $username);
		$stmt->bindValue(2, $password);
		$stmt->execute();
		return $stmt;
	}
	public function register($username, $password){
		$stmt = $this->pdo->prepare("INSERT INTO user VALUES(NULL, ?, ?, 0)");
		$stmt->bindValue(1, $username);
		$stmt->bindValue(2, $password);
		$stmt->execute();
		return $stmt->rowCount();
	}	
	public function checkUsername($username){
		$stmt = $this->pdo->prepare("SELECT * FROM user WHERE username=?");
		$stmt->bindValue(1, $username);
		$stmt->execute();
		return $stmt->rowCount();
	}
}