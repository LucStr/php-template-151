<?php

namespace LucStr\Controller;
use LucStr\Business\User;

class IndexController extends BaseController
{
  public function Index() {  	
	return $this->view();
  } 
}
