<?php
namespace App\Model;

/**
 * @name Role
 * @desc 角色表 
 * @author Administrator
 */

class Role extends Base
{
    protected $table = 'role';

    /**
     * 获取用户信息
     * @param  int    $id      [description]
     * @param  string $field   [description]
     * @return [type]          [description]
     */
    public function getInfo(int $id,string $field = '*')
    {
        return $this->where('id',$id)->field($field)->find();
    }

    /**
     * 添加用户
     * @param string $moble    [description]
     * @param string $password [description]
     */
    public function add(string $name)
    {
        $res = $this->insert([
            'name'          => $name,
            'update_time'   => $_SERVER['REQUEST_TIME'],
            'create_time'   => $_SERVER['REQUEST_TIME']
        ]);

        if ($res) {
            return true;
        }

        $this->error = '添加失败';
        return false;

    }

    public function isExists(string $val,string $field = 'id')
    {
        try {
            $res = $this->field($field)->where($field,$val)->find();
            return boolval($res);
        } catch (ModelException $e) {
            return false;
        }
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
     * 获取信息
     * @param  string $name [description]
     * @return [type]       [description]
     */
    public function getInfoByName(string $name,string $field = "*") 
    {
        return $this->where('name',$name)->field($field)->find();
    }


    public function modify(int $id, string $name)
    {
        return $this->where('id',$id)
                    ->update([
                        'name'          => $name,
                        'update_time'   => $_SERVER['REQUEST_TIME']
                    ]);
    }

    protected function buildWhere($obj,array $where)
    {
        foreach ($where as $k => $v) {
            if  (is_null($v)) {
                continue;
            }

            switch ($k) {
                case 'name':
                    $obj->where('name','=',$v);
            }
        }

        return $obj;
    }
}
