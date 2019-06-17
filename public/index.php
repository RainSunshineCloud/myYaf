<?php

try {
	define('APP_PATH', dirname(dirname(__FILE__)));
	define('LogFilePath',APP_PATH.'/logs/');
	//捕获最终异常
	register_shutdown_function(function () {
		if ($error = error_get_last()) {
			$error['time'] = date("Y-m-d H:i:s",time());
			$str = sprintf("type => %s, file => %s, line => %s, time => %s, message => %s \n",$error['type'],$error['file'],$error['line'],$error['time'],$error['message']);
			file_put_contents(LogFilePath.'/register_shutdown_error.log',$str,FILE_APPEND);
		}
	});

	include APP_PATH.'/vendor/autoload.php';
	$application = new Yaf\Application( APP_PATH . "/conf/application.ini");
	$application->bootstrap()->run();
}catch (\Exception $e) {
	var_dump($e);exit;
	echo $e->getMessage();
}



