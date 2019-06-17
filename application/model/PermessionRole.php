<?php
namespace App\Model;
use Log;
/**
 * @name Role
 * @desc 角色权限关联表
 * @author Administrator
 */

class PermessionRole extends Base
{
    protected $table = 'permession_role';

    /**
     * 获取用户信息
     * @param  int    $id      [description]
     * @param  string $field   [description]
     * @return [type]          [description]
     */
    public function getInfo(int $role_id,int $permession_id,string $field = '*')
    {
        return $this->where('rid',$role_id)
                    ->where('pid',$permession_id)
                    ->field($field)
                    ->find();
    }

    /**
     * 添加用户
     * @param string $moble    [description]
     * @param string $password [description]
     */
    public function add(int $role_id,int $permession_id)
    {
        $info = $this->getInfo($role_id,$permession_id,'status');

        if ($info) {
            $this->error = $info['status'] == 2 ? "该权限已添加，但被置为无效" : "该权限已添加";
            return false;
        }

        $res = $this->insert([
            'rid'           => $role_id,
            'pid'           => $permession_id,
            'status'        => 1,
            'update_time'   => $_SERVER['REQUEST_TIME'],
            'create_time'   => $_SERVER['REQUEST_TIME']
        ]);

        if ($res) {
            Log::sucSql('add',$this->getLastSql());
            return true;
        }

        Log::errorSql('add',$this->getLastSql());
        $this->error = "添加失败";
        return false;
    }

    /**
     * 用户列表
     * @param  string $field [description]
     * @return [type]        [description]
     */
    public function getList(int $page,int $pageSize,string $field, array $where = [] ,$sort = 'a.update_time desc')
    {
        return $this->buildWhere($this,$where)
                    ->table($this->table,'a')
                    ->order($sort)
                    ->join(Permession::tableName(),'a.pid=b.id','b')
                    ->join(PermessionZone::tableName(),'c.id=b.zid','c')
                    ->join(Role::tableName(),'d.id=a.rid','d')
                    ->field($field)
                    ->limit($pageSize,($page - 1) * $pageSize)
                    ->select();
    }

    protected function buildWhere($obj,array $where)
    {
        foreach ($where as $k => $v) {
            if (is_null($v)) {
                continue;
            }

            switch ($k) {
                case 'permession_name':
                    $obj->where('b.name',$v);
                    break;
                case 'role_name':
                    $obj->where('d.name','=',$v);
                    break;
                case 'status':
                    $obj->where('a.status','=',$v);
                    break;
            }
        }

        return $obj;
    }

    public function getTotalPage(array $where = [])
    {
        return $this->buildWhere($this,$where)
                    ->table($this->table,'a')
                    ->join(Permession::tableName(),'a.pid=b.id','b')
                    ->join(Role::tableName(),'d.id=a.rid','d')
                    ->count('*','total')
                    ->find()['total'];
    }

    public function modifyPermessionRole(int $role_id,int $permession_id,int $status) {
        return $this->where('rid',$role_id)->where('pid',$permession_id)->update(['status' => $status,'update_time' => $_SERVER['REQUEST_TIME']]);
    }
}
