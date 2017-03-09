<?php

namespace LucStr\Controller;

use LucStr\SimpleTemplateEngine;

class IndexController 
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

  public function homepage() {
    echo "INDEX";
  }

  public function greet($name) {
  	echo $this->template->render("hello.html.php", ["name" => $name]);
  }
}
