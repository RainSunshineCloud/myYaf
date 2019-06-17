<?php 
use RainSunshineCloud\RequestException;
use RainSunshineCloud\CaptchaException;
use RainSunshineCloud\Captcha;

class Validate
{
	public static function checkPicCode ($code,$message) {
	    $res = Captcha::verify($code,'captcha_code');

	    if (!$res) {
	        throw new CaptchaException($message);
	    }
     }
}