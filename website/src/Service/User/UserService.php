<?php
namespace LucStr\Service\User;

interface UserService
{
	public function authenticate($username, $password);
	public function register($username, $password);
}