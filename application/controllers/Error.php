<?php

use RainSunshineCloud\JWTException;
use RainSunshineCloud\RequestException;
use RainSunshineCloud\CaptchaException;

class ErrorController extends \Yaf\Controller_Abstract
{
	public function errorAction($exception) 
	{
		switch (true) {
			case $exception instanceof RequestException:
				Response::error($exception->getMessage());
				break;
			case $exception instanceof JWTException:
				Response::instance()->data('',$exception->getMessage(),600)->send(false);
				break;
			case $exception instanceof CaptchaException:
				Response::instance()->data('','验证码错误',601)->send(false);
				break;
			default:
				printf("文件名：%s <br> 行数:%d <br> 错误信息:%s",$exception->getFile(),$exception->getLine(),$exception->getMessage());
		}
	}
}
