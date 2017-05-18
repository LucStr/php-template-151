<?php

namespace LucStr\Controller;

use LucStr\Factory;

class BaseController
{
	protected $factory;
	private $template;
	
	function __construct()
	{
		$this->factory = Factory::crateFromInitFile(__DIR__ . "/../../config.ini");
		$this->template = $this->factory->getTemplateEngine();
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
}