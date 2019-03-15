<?php
use RainSunshineCloud\Request;
use RainSunshineCloud\JWT;
use App\Model\User;

class PassportController extends AdminController 
{
	protected $login = true;
	public function loginAction() 
	{
		//获取参数
		$params = Request::instance()->check('nick_name','string','请填写昵称,最少2个字符',['min' => 2])
									 ->check('password','string','请填写密码',['min' => 1,'max' => 255])
									 ->check('code','string','请填写验证码')
									 ->check('code','string','验证码错误',['min'=>4,'max'=>4])
									 ->post(['nick_name','password','code']);
		//数据查询
		$user_model = new User();
		$info = $user_model->getInfoByNickName($params['nick_name'],'nick_name,password,salt,id,moble');

		//登录
		$info && Util::checkPassword($info['password'],$info['salt'],$params['password']) 
		&& Response::instance()->token(['user_id' => $info['id']])->data([
									'openid' 		=> Util::encodeUid($info['id']),
									'nick_name' 	=> $info['nick_name']
								])->send(true,true);
		
		//返回错误
		Response::instance()->data('','用户名或密码错误',600)->send(false);
	}


}
