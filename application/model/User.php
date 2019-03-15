<?php
namespace App\Model;
use RainSunshineCloud\ModelException;
use BaseModel;

/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author Administrator
 */

class User extends Base
{
 	protected $table = 'test';

    /**
     * 获取昵称
     * @param  string $nick_name [description]
     * @param  string $field     [description]
     * @return [type]            [description]
     */
    public function getInfoByNickName(string $nick_name,string $field = '*')
    {
    	return $this->where('nick_name',$nick_name)->field($field)->find();
    }

    /**
     * 获取用户信息
     * @param  int    $user_id [description]
     * @param  string $field   [description]
     * @return [type]          [description]
     */
    public function getInfo(int $user_id,string $field = '*')
    {
        return $this->where('id',$user_id)->field($field)->find();
    }

    /**
     * 更新密码
     * @param  string $user_id      [description]
     * @param  string $old_password [description]
     * @param  string $new_password [description]
     * @return [type]               [description]
     */
    public function updatePassword(string $user_id,string $old_password,string $new_password)
    {
        $res = $this->where('id','=',$user_id)->where('password','=',$old_password)->update([
            'password' => $new_password
        ]);

        return $res;
    }   
}
