<?php 

use RainSunshineCloud\{Upload,Request};
use \App\Model\User;

class UserController extends AdminController
{
	/**
	 * 修改密码
	 * @return [type] [description]
	 */
	public function modifyPasswordAction()
	{
		$params = Request::instance()->check('password','string','请填写密码',['min' => 6,'max' => 255])
									->check('id','int','invalid params',['min' => 1])
									->post(['password','id']);
		//数据查询
		$user_model = new User();
		$info = $user_model->getInfo($params['id'],'password');
		$salt = "acyz";
		$info = $user_model->updatePassword($params['id'],$info['password'],Util::encodePassword($params['password'],$salt));
		Response::success($info);
	}

	/**
	 * 添加账户
	 */
	public function addAction()
	{

		$params = Request::instance()->check('password','string','请填写密码',['min' => 6,'max' => 255])
									->check('header','string','头像上传失败',['min' => 3])
									->check('moble','string','请输入手机号',['min' => 2])
									->post(['password','moble','header']);

		$user_model = new User();
		$salt = "acyz";
		$res = $user_model->add($params['moble'],Util::encodePassword($params['password'],$salt),$params['header']);

		if (!$res) {
			Response::error($user_model->error);
		}

		Response::success();
	}

	/**
	 * 列表
	 * @return [type] [description]
	 */
	public function listAction()
	{
		$params = Request::instance()->check('page','int','invalid params',['min' => 1])
									->check('pageSize','int','invalid params',['min' => 1])
									->post(['page' => 1,'pageSize' => 10]);

		$user_model = new User();
		$list = $user_model->list('id,moble,create_time,update_time,header',$params['page'],$params['pageSize']);
		$res = $user_model->count('id','total')->find();
		response::success([
			'list' => $list,
			'total' => $res['total'],
			'page' => $params['page'],
			'pageSize'	=> $params['pageSize'],
		]);
	}

	/**
	 * 上传图片
	 * @return [type] [description]
	 */
	public function uploadImgAction()
	{	
		Request::setDataType(1);
		$type = Request::instance()->check('type','string','invalid params',['min' => 3])->post(['type'])['type'];
	    $file = new Upload();
		Upload::setBasePath(APP_PATH.'/public/upload/');
	    $res = $file->setDir($type)->setValidType(['jpg','png'])->setMaxSize(100000)->upload('file')->getFilePath();
	    response::success('upload/'.$res);
	}

	/**
	 * 修改信息
	 * @return [type] [description]
	 */
	public function modifyAction()
	{
		$params = Request::instance()->check('id','int','invalid params',['min' => 1])
									 ->check('moble','string','手机号错误',['min' => 3])
									 ->check('header','string','头像上传失败',['min' => 3])
									 ->post(['id','moble','header']);

		$user_model = new User();
		$res = $user_model->updateInfo($params['id'],$params['moble'],$params['header']);
		if (!$res) {
			response::error("修改失败");
		}

		response::success();
	}
}