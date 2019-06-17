<?php
namespace App\Model;

/**
 * @name User
 * @desc 用户列表
 * @author Administrator
 */

class User extends Base
{
 	protected $table = 'user';

    /**
     * 获取昵称
     * @param  string $nick_name [description]
     * @param  string $field     [description]
     * @return [type]            [description]
     */
    public function getInfoByMoble(string $moble,string $field = '*')
    {
    	return $this->where('moble',$moble)->field($field)->find();
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
    public function updatePassword(string $user_id,string $old_password,string $password)
    {

        $res = $this->where('id',$user_id)->where('password',$old_password)->update([
            'password' => $password,
            'update_time' => $_SERVER['REQUEST_TIME'],
        ]);
        return $res;
    }

    /**
     * 添加用户
     * @param string $moble    [description]
     * @param string $password [description]
     */
    public function add(string $moble,string $password,string $header)
    {
        $res = $this->insert([
            'moble'         => $moble,
            'password'      => $password,
            'header'        => $header,
            'update_time'   => $_SERVER['REQUEST_TIME'],
            'create_time'   => $_SERVER['REQUEST_TIME']
        ]);

        if ($res) {
            return true;
        }
        $this->error = $this->getLastSql(true);
        return false;
    }

    /**
     * 用户列表
     * @param  string $field [description]
     * @return [type]        [description]
     */
    public function list(string $field = "*", int $page = 1, int $pageSize = 10)
    {
        return $this->field($field)->limit($pageSize,($page - 1) * $pageSize)->select();
    }

    /**
     * 更新用户信息
     * @param  int    $id     [description]
     * @param  string $moble  [description]
     * @param  string $header [description]
     * @return [type]         [description]
     */
    public function updateInfo(int $id, string $moble, string $header)
    {
        return $this->where('id',$id)->update(['moble' => $moble,'header' => $header,'update_time' => $_SERVER['REQUEST_TIME']]);
    }
}
