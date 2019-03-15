<?php
use RainSunshineCloud\Request;

class IndexController extends AdminController 
{
	public function indexAction($name = "Stranger") 
	{
		$params = Request::instance()->get();
	}

	public function adminAction($name = '')
	{
		$params = Request::instance()->post();
	}
}
