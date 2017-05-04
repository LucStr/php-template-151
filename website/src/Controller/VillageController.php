<?php

namespace LucStr\Controller;

use LucStr\SimpleTemplateEngine;
use LucStr\Service\User\UserService;

class RegistrationController 
{
  /**
   * @var LucStr\SimpleTemplateEngine Template engines to render output
   */
  private $template;
  private $userService;
  /**
   * @param LucStr\SimpleTemplateEngine
   */
  public function __construct(SimpleTemplateEngine $template, UserService $userService)
  {
     $this->template = $template;
     $this->userService = $userService;
  }
  
  public function overview()
  {
  	echo $this->template->render("overview.html.php");
  }
}
