<?php

namespace LucStr;

class Factory 
{
	private $config;
	
	public static function crateFromInitFile($source){
		return new Factory(parse_ini_file($source));
	}
	
	public function __construct(array $config){
		$this->config = $config;
	}
	function getTemplateEngine() 
	{
		return new SimpleTemplateEngine(__DIR__ . "/../templates/");
	}
	
	function getIndexController()
	{
		return new Controller\IndexController($this->getTemplateEngine());
	}
	
	function getPdo()
	{
		return new \PDO(
			"mysql:host=mariadb;dbname=browsergame;charset=utf8",
			$this->config['user'],
			"my-secret-pw",
			[\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
		);
	}
	
	function getLoginService()
	{
		return new Service\Login\LoginPdoService($this->getPdo());
	}
	
	function getLoginController()
	{
		return new Controller\LoginController($this->getTemplateEngine(), $this->getUserService());
	}
	function getUserService()
	{
		return new Service\User\UserPdoService($this->getPdo());
	}	
	function getRegisterController(){
		return new Controller\RegistrationController($this->getTemplateEngine(), $this->getUserService());	
	}
}