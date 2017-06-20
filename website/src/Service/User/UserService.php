<?php
namespace LucStr\Service\User;

interface UserService
{
	public function checkCredentials($user, $password);
	public function register($username, $email, $password);
	public function checkUsername($username);
	public function updatePoints($userId);
	public function getRanking();
	public function activate($userId);
	public function getUserById($userId);
	public function getUserByUsername($username);
	public function updatePassword($userId, $newPassword);
	public function renewConfirmationUUIDByUsername($username);
}