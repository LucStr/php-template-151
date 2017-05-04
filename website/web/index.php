<?php

use LucStr\Factory;

error_reporting(E_ALL);
session_start();

require_once("../vendor/autoload.php");
$factory = Factory::crateFromInitFile(__DIR__ . "/../config.ini");


$userService = $factory->getUserService();


switch($_SERVER["REQUEST_URI"]) {
	case "/":
		$factory->getIndexController()->homepage();
		break;
	case "/login":
		$controller = $factory->getLoginController();
		if($_SERVER["REQUEST_METHOD"] === "GET"){
			$controller->showLogin();
		} else{
			$controller->login($_POST);
		}
		break;
	case "/register":
		$controller = $factory->getRegisterController();
		if($_SERVER["REQUEST_METHOD"] === "GET"){
			$controller->showRegister();
		} else{
			$controller->register($_POST);
		}
		break;
	default:
		$matches = [];
		if(preg_match("|^/hello/(.+)$|", $_SERVER["REQUEST_URI"], $matches)) {
			$factory->getIndexController()->greet($matches[1]);
			break;
		}
		echo "Not Found";
}

