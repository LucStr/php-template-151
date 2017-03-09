<?php

namespace LucStr\Controller;

use LucStr\SimpleTemplateEngine;

class LoginController 
{
  /**
   * @var LucStr\SimpleTemplateEngine Template engines to render output
   */
  private $template;
  
  /**
   * @param LucStr\SimpleTemplateEngine
   */
  public function __construct(SimpleTemplateEngine $template)
  {
     $this->template = $template;
  }

  public function showLogin()
  {
  	echo $this->template->render("login.html.php");
  }
}
