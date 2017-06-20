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
	
	function getVillageService()
	{
		return new Service\Village\VillagePdoService($this->getPdo());
	}
	
	function getUserService()
	{
		return new Service\User\UserPdoService($this->getPdo());
	}
	
	public function getMailer()
	{
		return \Swift_Mailer::newInstance(
				\Swift_SmtpTransport::newInstance("smtp.gmail.com", 465, "ssl")
				->setUsername("gibz.module.151@gmail.com")
				->setPassword("Pe$6A+aprunu")
				);
	}
}