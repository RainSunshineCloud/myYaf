<?php
use RainSunshineCloud\Request;

class IndexController extends AdminController 
{
	public function indexAction($name = "Stranger") {
		$params = Request::instance()->get();
	}
}
