<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;
use Monolog\Formatter\LineFormatter;

class Log extends Logger
{
	const SQL = 700;
	const SUCCESS_SQL = 800;
	const ERROR_SQL = 900;
	protected static $log = null;
	protected static $file_path = null;
	protected static $self_levels = [
		700 => 'SQL',
		800 => 'SUCCESS_SQL',
		900 => 'ERROR_SQL',
	];

	protected static function addLevel()
	{
		parent::$levels += self::$self_levels;
	}

	public static function setFilePath($file_path)
	{
		self::$file_path = $file_path;
	}

	/**
	 * 记录日志
	 * @param  string $msg     [description]
	 * @param  array  $content [description]
	 * @param  string $level   [description]
	 * @return [type]          [description]
	 */
	public static function record($msg = "msg",array $content = [],$level = Log::INFO ) 
	{

		if (!self::$file_path) {
			throw new \Yaf\Exception('请填写文件路径');
		}

		if (!self::$log) {
			$log = new Log('web');
			$log->pushProcessor(new UidProcessor());
			$stream = new StreamHandler(self::$file_path);
			$formatter = new LineFormatter();
			$stream->setFormatter($formatter);
			$log->pushHandler($stream);
			self::addLevel();
		}

        return $log->log($level,$msg,$content);
	}
}