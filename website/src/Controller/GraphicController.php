<?php

namespace LucStr\Controller;

use LucStr\MessageHandler;

class GraphicController extends BaseController
{
	public function Index($name) {
		if(strpos($name, "..")){
			return;
		}
		render("\..\web\Graphics\\" . $name);
	}
}
