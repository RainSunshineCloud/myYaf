<?php 
namespace App\Model;
use RainSunshineCloud\Model;

class Base extends Model 
{
	public function __construct()
	{
		self::setConfig([
			'db'  		=> 'kuaisan',
			'host' 		=> '127.0.0.1',
			'port'		=> '3306',
			'user'		=> 'root',
			'password' 	=> '12345678',
		]);
	}
}