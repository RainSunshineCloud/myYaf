<?php 
namespace App\Model;
use RainSunshineCloud\Model;

class Base extends Model 
{
	public function __construct()
	{
		self::setConfig([
			'db'  		=> 'test',
			'host' 		=> '192.168.31.99',
			'port'		=> '3306',
			'user'		=> 'wangdu',
			'password' 	=> 'wangdu',
		]);
	}
}