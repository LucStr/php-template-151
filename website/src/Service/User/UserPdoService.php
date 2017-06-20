<?php

namespace LucStr\Service\User;
use LucStr\Service\User\UserService;

class UserPdoService implements UserService
{
	private $pdo;
	private $salt = "kuul";
	public function __construct(\Pdo $pdo)
	{
		$this->pdo = $pdo;
	}
	
	public function checkCredentials($user, $password){
		return password_verify($password, $user["password"]);
	}
	
	public function register($username, $email, $password){
		$stmt = $this->pdo->prepare("INSERT INTO user VALUES(NULL, ?, ?, ?, 0, 0, ?)");
		$stmt->bindValue(1, $username);
		$stmt->bindValue(2, $email);
		$stmt->bindValue(3, $this->hashpass($password));
		$stmt->bindValue(4, $this->gen_uuid());
		$stmt->execute();
		return $this->pdo->lastInsertId();
	}	
	
	public function checkUsername($username){
		$stmt = $this->pdo->prepare("SELECT * FROM user WHERE username=?");
		$stmt->bindValue(1, $username);
		$stmt->execute();
		return $stmt->rowCount();
	}
	
	public function updatePoints($userId){
		$stmt = $this->pdo->prepare("SELECT SUM(points) AS userPoints FROM village WHERE userId=?");
		$stmt->bindValue(1, $userId);
		$stmt->execute();
		$points = $stmt->fetch(\PDO::FETCH_ASSOC)["userPoints"];
		$stmt = $this->pdo->prepare("UPDATE user SET points=? WHERE userId=?");
		$stmt->bindValue(1,$points);
		$stmt->bindValue(2, $userId);
		$stmt->execute();
		return $stmt->rowCount();
	}
	
	public function getRanking(){
		$stmt = $this->pdo->prepare("SELECT username, points FROM user ORDER BY points DESC");
		$stmt->execute();
		$users = array();
		while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
			$users[] = $row;
		}
		return $users;
	}
	
	public function getUserById($userId){
		$stmt = $this->pdo->prepare("SELECT userId, username, password, email, points, activated, confirmationUUID FROM user WHERE userId=?");
		$stmt->bindValue(1, $userId);
		$stmt->execute();
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}
	
	public function getUserByUsername($username){
		$stmt = $this->pdo->prepare("SELECT userId, username, password, email, points, activated, confirmationUUID FROM user WHERE username=?");
		$stmt->bindValue(1, $username);
		$stmt->execute();
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}
	
	public function activate($userId){
		$stmt = $this->pdo->prepare("UPDATE user SET activated = 1 WHERE userId=?");
		$stmt->bindValue(1, $userId);
		$stmt->execute();
		return $stmt->rowCount();
	}
	
	public function updatePassword($userId, $newPassword){
		$stmt = $this->pdo->prepare("UPDATE user SET password=? WHERE userId=?");
		$stmt->bindValue(1, $this->hashpass($newPassword));
		$stmt->bindValue(2, $userId);
		$stmt->execute();
		return $stmt->rowCount();
	}
	
	public function renewConfirmationUUIDByUsername($username){
		$stmt = $this->pdo->prepare("UPDATE user SET confirmationUUID=? WHERE username=?");
		$stmt->bindValue(1, $this->gen_uuid());
		$stmt->bindValue(2, $username);
		$stmt->execute();
	}
	
	
	private function hashpass($password){
		return password_hash($password, PASSWORD_DEFAULT);
	}
	
	private function gen_uuid() {
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
				// 32 bits for "time_low"
				mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
	
				// 16 bits for "time_mid"
				mt_rand( 0, 0xffff ),
	
				// 16 bits for "time_hi_and_version",
				// four most significant bits holds version number 4
				mt_rand( 0, 0x0fff ) | 0x4000,
	
				// 16 bits, 8 bits for "clk_seq_hi_res",
				// 8 bits for "clk_seq_low",
				// two most significant bits holds zero and one for variant DCE1.1
				mt_rand( 0, 0x3fff ) | 0x8000,
	
				// 48 bits for "node"
				mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
				);
	}
}