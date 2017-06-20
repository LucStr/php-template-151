<?php

namespace LucStr\Controller;

use LucStr\Factory;
use LucStr\HtmlHelper;

class BaseController
{
	protected $factory;
	
	function __construct()
	{
		$this->factory = Factory::crateFromInitFile(__DIR__ . "/../../config.ini");
	}
	
	function executeAction($action){
		$GLOBALS['action'] = $action;
		$data = $_REQUEST;
		$reflector = new \ReflectionMethod(get_class($this), $action);
		$comment = $reflector->getDocComment();
		if(preg_match_all('%^\s*\*\s*@HTTP\s+(?P<route>/?(?:[a-z0-9]+/?)+)\s*$%im', $comment, $result, PREG_PATTERN_ORDER)){
			switch ($result[1][0]){
				case "GET":
					$data = $_GET;
					break;
				case "POST":
					$data = $_POST;
					break;
			}
		}
		if(preg_match_all('%^\s*\*\s*@CSRF\s+(?P<route>/?(?:[a-z0-9]+/?)+)\s*$%im', $comment, $result, PREG_PATTERN_ORDER)){
			if($result[1][0] == "ON" && !$this->CheckCSRF($data)){
				var_dump($_SESSION["CSRF"], $data["csrf"], $this->CheckCSRF($data));
				throw new \Exception("CSRF nicht gültig");
			}
		}
		$params = $reflector->getParameters();
		$values = array();
		foreach ($params as $param) {
			$name = $param->getName();
			$isArgumentGiven = array_key_exists($name, $data);
			if (!$isArgumentGiven && !$param->isDefaultValueAvailable()) {
				die ("Parameter $name is mandatory but was not provided");
			}
			$values[$name] = $isArgumentGiven ? $data[$name] : $param->getDefaultValue();
		}
		call_user_func_array(array($this, $action), $values);
	}

	function redirectToAction($controller, $action, $args = array()){
		$this->host= $_SERVER['HTTP_HOST'];
		$this->uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$locationString = "Location: http://$this->host$this->uri/$controller/$action?";
		foreach ($args as $key => $value) {
			$locationString = $locationString . '&' . $key . '=' . $value;
		}
		header($locationString);
	}
	
	function partialView0Arg(){
		$this->partialView3Arg($GLOBALS["controllername"], $GLOBALS["actionname"]);		
	}
	
	function partialView1Arg($modelView){
		if(is_string ($modelView)){
			$this->partialView3Arg($GLOBALS["controllername"], $modelView);
		}else{
			$this->partialView3Arg($GLOBALS["controllername"], $modelView, $modelView);
		}
	}
	
	function partialView3Arg($controller, $view, $viewModel = array()){
		$view = __DIR__ . "/../Views/" . $controller . "/" . $view . ".php";
		$viewModel["viewLocation"] = $view;
		extract($viewModel);
		require($view);
	}

	function view0Arg(){
		$this->view3Arg($GLOBALS["controllername"], $GLOBALS["actionname"]);
	}

	function view1Arg($modelView){
		if(is_string ($modelView)){
			$this->view3Arg($GLOBALS["controllername"], $modelView);
		}else{
			$this->view3Arg($GLOBALS["controllername"], $GLOBALS["actionname"], $modelView);
		}
	}
	
	function view3Arg($controller, $view, $viewModel = array()){
		$view = __DIR__ . "/../Views/" . $controller . "/" . $view . ".php";
		$viewModel["viewLocation"] = $view;
		$viewModel["html"] = new HtmlHelper();
		extract($viewModel);
		require("../web/layout.php");
	}

	function __call($name, $arguments){
		if(method_exists($this, $name)){
			call_user_func_array(array($this, $name), $arguments);
		} else{
			$functionname = $name . count($arguments) . "Arg";
			if(method_exists($this, $functionname)){
				call_user_func_array(array($this, $functionname), $arguments);
			}else{
				$this->index();				
			}
		}
	}
	function CreateCSRF(){
		$_SESSION["CSRF"] = $this->gen_uuid();
		return $_SESSION["CSRF"];
	}
	
	function CheckCSRF($data){
		return $data["csrf"] == $_SESSION["CSRF"];
	}
	
	function gen_uuid() {
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