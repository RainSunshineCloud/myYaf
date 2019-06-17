<?php
namespace App\Model;
use Log;
/**
 * @name User
 * @desc 用户列表
 * @author Administrator
 */

class UserRole extends Base
{
 	protected $table = 'user_role';

    /**
     * 获取用户信息
     * @param  int    $id 	   [id]
     * @param  string $field   [字段]
     * @return array
     */
    public function getInfo(int $id,string $field = '*')
    {
        return $this->where('id',$user_id)->field($field)->find();
    }

    /**
     * 通过角色id和用户id获取信息
     * @param  int    $role_id [角色id]
     * @param  int    $user_id [用户id]
     * @param  string $field   [字段]
     * @return array
     */
    public function getInfoByRoleAndUser(int $role_id,int $user_id,string $field = '*')
    {
    	return $this->field($field)->where('user_id',$user_id)->where('role_id',$role_id)->find();
    }

    /**
     * 添加角色
     * @param int         $role_id [角色id]
     * @param int         $user_id [用户id]
     * @param int|integer $status  [状态]
     */
    public function add(int $role_id,int $user_id,int $status = 1)
    {
    	$info = $this->getInfoByRoleAndUser($role_id,$user_id,'status');
    	if ($info) {
    		$this->error = $info['status'] == 1 ? '该用户已有该角色' : '该用户已有该角色，但被禁用';

    		return false;
    	}
        $res = $this->insert([
            'user_id'       => $user_id,
            'role_id'		=> $role_id,
            'status'		=> $status,
            'update_time'   => $_SERVER['REQUEST_TIME'],
            'create_time'   => $_SERVER['REQUEST_TIME']
        ]);

        if ($res) {
        	Log::sucSql('success',$this->getLastSql());
            return true;
        }

        $this->error = '添加失败';
        Log::errorSql('add',$this->getLastSql());
        return false;
    }

    public function getList(int $page,int $pageSize,string $field ,array $where = [],$sort = 'a.update_time desc')
    {
        $res = $this->buildWhere($this,$where)
                    ->table($this->table,'a')
                    ->order($sort)
                    ->field($field)
                    ->join(User::tableName(),'a.user_id = b.id','b')
                    ->join(Role::tableName(),'a.role_id=c.id','c')
                    ->select();
        return $res;
    }

    public function getTotalPage(array $where = [])
    {
        return intval($this->buildWhere($this,$where)
                            ->table($this->table,'a')
                            ->join(User::tableName(),'a.user_id = b.id','b')
                            ->join(Role::tableName(),'a.role_id=c.id','c')
                            ->count('*','total')
                            ->find()['total']);
    }

    public function buildWhere($obj, array $where) 
    {
        foreach ($where as $k => $v) {
            if (is_null($v)) {
                continue;
            }

            switch ($k) {
                case 'user_name':
                    $obj->where('b.nickname',$v);
                    break;
                case 'role_name':
                    $obj->where('c.name','=',$v);
                    break;
            }
        }

        return $obj;
    }

    public function modifyUserRole(int $user_id,int $role_id,int $status)
    {
        $res = $this->where('role_id',$role_id)
            ->where('user_id',$user_id)
            ->update([
                'status' => $status,
                'update_time' => $_SERVER['REQUEST_TIME'],
            ]);

        return $res;
    }
}
