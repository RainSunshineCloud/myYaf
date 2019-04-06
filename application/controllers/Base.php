<?php

/**
 * 基类通用控制器
 *
 * @author falcom520@gmail.com
 */
use RainSunshineCloud\JWT;
use RainSunshineCloud\Request;
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
        Request::setDataType(2);
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

        $origin = \Yaf\Application::app()->getConfig()->access['origin'];
        $origin = $origin ? $origin: $this->getRequest()->getServer("HTTP_ORIGIN");
        header('Access-Control-Allow-Headers:Content-type,Token,X-Auth-UA,Cookies');
        header('Access-Control-Request-Method:POST,GET');
        header('Access-Control-Allow-Origin:'.$origin);
        header('Access-Control-Allow-Credentials:true');
        header("Access-Control-Expose-Headers:Token");
        if ($this->getRequest()->isOptions()) {
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
