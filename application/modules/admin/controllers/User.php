<?php 

use RainSunshineCloud\Request;
use RainSunshineCloud\JWT;
use \App\Model\User;

class UserController extends AdminController
{
	/**
	 * 修改密码
	 * @return [type] [description]
	 */
	public function modifyPasswordAction()
	{
		$params = Request::instance()->check('password','string','请填写旧密码',['min' => 6,'max' => 255])
									->check('new_password','string','请填写新密码,最小长度为8',['min' => 8,'max' => 255])
									->post(['password','new_password']);
		//数据查询
		$user_model = new User();
		$info = $user_model->getInfo($this->uid,'password,salt');

		if (!$info || !Util::checkPassword($info['password'],$info['salt'],$params['password'])) {
			Response::error('用户名密码错误');
		}

		$info = $user_model->updatePassword($this->uid,$info['password'],Util::encodePassword($params['new_password'],$info['salt']));
		Response::success($info);
	}
}