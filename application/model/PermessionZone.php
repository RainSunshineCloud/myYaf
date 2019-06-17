<?php
namespace App\Model;

use RainSunshineCloud\ModelException;
use Log;

/**
 * @name Role
 * @desc 权限作用区域
 * @author Administrator
 */

class PermessionZone extends Base
{
    protected $table = 'permession_Zone';

    /**
     * 获取信息
     * @param  int    $id      [description]
     * @param  string $field   [description]
     * @return [type]          [description]
     */
    public function getInfo(int $id,string $field = '*')
    {
        try {
            return $this->where('id',$id)->field($field)->find();
        } catch (ModelException $e) {
            Log::errors('getInfo',['msg' => $e->getMessage(),'code' => $e->getCode()]);
            return [];
        }
    }

    public function getInfoByName(string $name ,string $field = "*")
    {
        return $this->where('name',$name)->field($field)->find();
        
    }

    /**
     * 添加用户
     * @param string $moble    [description]
     * @param string $password [description]
     */
    public function add(string $name,int $fid = 0)
    {
        $res = $this->insert([
            'name'          => $name,
            'update_time'   => $_SERVER['REQUEST_TIME'],
            'create_time'   => $_SERVER['REQUEST_TIME']
        ]);

        if ($res) {
            Log::sucSql('add',$this->getLastSql());
            return true;
        }
        $this->error = '添加失败';
        Log::errorSql('add',$this->getLastSql());
        
        return false;
    }

    public function modify(int $id,string $name)
    {
        $res = $this->where('id',$id)->update([
            'name' => $name,
            'update_time' => $_SERVER['REQUEST_TIME'],
        ]);

        return $res;
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
}
