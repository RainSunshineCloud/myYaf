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
        $params = Request::instance()->check('moble','string','请填写名称',['min' => 2])
                                     ->check('password','string','请填写密码',['min' => 1,'max' => 255])
                                     // ->check('code','string','请填写验证码')
                                     // ->check('code','string','验证码错误',['min'=>4,'max'=>4])
                                     ->post(['moble','password','code']);
        //数据查询
        $user_model = new User();
        $info = $user_model->getInfoByMoble($params['moble'],'password,id,moble');

        //登录
        $info && Util::checkPassword($info['password'],"acyz",$params['password']) 
        && Response::instance()->token(['user_id' => $info['id']])
                                ->data([
                                    'openid' => Util::encodeUid($info['id']),
                                ])->send(true,true);
        
        //返回错误
        Response::instance()->data('','用户名或密码错误',600)->send(false);
    }
}
