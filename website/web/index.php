<?php

error_reporting(E_ALL);
session_start();

require_once("../vendor/autoload.php");
$tmpl = new LucStr\SimpleTemplateEngine(__DIR__ . "/../templates/");
$pdo = new \PDO(
	"mysql:host=mariadb;dbname=app;charset=utf8",
	"root",
	"my-secret-pw",
	[\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
);

switch($_SERVER["REQUEST_URI"]) {
	case "/":
		(new LucStr\Controller\IndexController($tmpl))->homepage();
		break;
	case "/login":
		$controller = (new LucStr\Controller\LoginController($tmpl, $pdo));
		if($_SERVER["REQUEST_METHOD"] === "GET"){
			$controller->showLogin();
		} else{
			$controller->login($_POST);
		}
		break;
	default:
		$matches = [];
		if(preg_match("|^/hello/(.+)$|", $_SERVER["REQUEST_URI"], $matches)) {
			(new LucStr\Controller\IndexController($tmpl))->greet($matches[1]);
			break;
		}
		echo "Not Found";
}

