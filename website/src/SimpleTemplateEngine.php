<?php

namespace LucStr;

/**
 * Simple Template-Engine which provides arguments into a template,
 * captures the output and returns it to the caller.
 */
class SimpleTemplateEngine 
{
  /**
   * @var string Location of the directory containing the templates
   */
  private $templatePath;
  
  /**
   * @param string $templatePath 
   */
  public function __construct($templatePath) 
  {
    $this->templatePath = $templatePath;
  }
  
  /**
   * Renders a *.html.php file inside the template path
   * @return string
   */
  public function render($view, array $arguments = []) 
  {
  	var_dump($arguments);
  	$arguments["view"] = $this->templatePath . $view;
  	var_dump($arguments);
  	die();
    extract($arguments);    
    require("../web/layout.php");
  }
}
