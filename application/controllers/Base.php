<?php

/**
 * 基类通用控制器
 *
 * @author falcom520@gmail.com
 */
use RainSunshineCloud\Request;
use RainSunshineCloud\JWT;
class BaseController extends \Yaf\Controller_Abstract
{

	protected $login = false;
	protected $user_id = null;
    /**
     * 初始化
     * 
     * @return [type] [description]
     */
    public function init()
    {
        $this->optionsResponse();
        if (!$this->login) {
            $this->checkLogin();
        }
    }

    /**
     * 请求数据
     * @return [type] [description]
     */
    protected function optionsResponse()
    {
    	if($this->getRequest()->isOptions()) {
            $origin = \Yaf\Application::app()->getConfig()->access['origin'];
            $origin = $origin ? $origin: '*';
            header('Access-Control-Allow-Headers:Content-type,X-Auth-Token,X-Auth-UA,Cookies');
            header('Access-Control-Request-Method:POST,GET');
            header('Access-Control-Allow-Origin:'.$origin);
            exit;
        }
        Log::setFilePath(sprintf('%s/%s/%s/%s/%s.log',APP_PATH,'logs',$this->getRequest()->module,$this->getRequest()->controller,date("Ymd",$_SERVER['REQUEST_TIME'])));
        $request = Request::instance();
        $loger = [
            'POST'  =>  $request->post(),
            'GET'   =>  $request->get(),
            'TOKEN' =>  $request->serv('HTTP_TOKEN')['HTTP_TOKEN'],
        ];

        Log::record('[首次请求，记录请求日志]',$loger);
    }

    protected function checkLogin()
    {
        $token = Request::instance()->serv(['HTTP_TOKEN'=>''])['HTTP_TOKEN'];
        $this->uid = Response::instance()->decode($token)['user_id'];
        Response::token(['user_id' => $this->uid]);
    }
}
