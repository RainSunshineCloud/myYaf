<?php

use RainSunshineCloud\JWTException;
use RainSunshineCloud\RequestException;

class ErrorController extends \Yaf\Controller_Abstract
{
	public function errorAction($exception) 
	{
		switch (true) {
			case $exception instanceof JWTException:
				echo $exception->getMessage();
				break;
			case $exception instanceof RequestException:
				echo $exception->getMessage();
				break;
			default:
				printf("文件名：%s <br> 行数:%d <br> 错误信息:%s",$exception->getFile(),$exception->getLine(),$exception->getMessage());
		}
	}
}
