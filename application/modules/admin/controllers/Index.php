<?php 

use RainSunshineCloud\Request;
use RainSunshineCloud\JWT;
use \App\Model\Config;

class UserController extends AdminController
{
	/**
	 * 修改密码
	 * @return [type] [description]
	 */
	public function listAction()
	{

		$params = Request::instance()->check('page','int')
									->check('pageSize','int')
									->post(['page','pageSize']);
		//数据查询
		$config_model = new Config();
		$config = $config_model->list();

		Response::success($info);
	}
}