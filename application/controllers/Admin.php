<?php

use RainSunshineCloud\JWT;
use RainSunshineCloud\JWTException;
use RainSunshineCloud\Request;
use RainSunshineCloud\RequestException;

class AdminController extends BaseController
{
	public function indexAction($name = "Stranger") {
		$id = Request::instance()->get('id');
	}
}
