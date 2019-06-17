<?php
namespace App\Model;

use Log;

/**
 * @name Permession
 * @desc 权限表
 * @author Administrator
 */

class Permession extends Base
{
    protected $table = 'permession';
    // 默认权限
    public $defaultPermessionArr = [
        1 => '允许',
        2 => '禁止',
    ];

    public $otherPermessionArr = [
        1 => '允许',
        2 => '禁止',
    ];
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
     * [根据ssid和zone_id获取信息]
     * @param  string $ssid    [description]
     * @param  int    $zone_id [description]
     * @param  string $field   [description]
     * @return [type]          [description]
     */
    public function getInfoBySsidAndZoneId(string $ssid, int $zone_id,string $field = '*')
    {
        return $this->field($field)
                    ->where('ssid',$ssid)
                    ->where('zid',$zone_id)
                    ->find();
    }

    /**
     * 添加权限
     * @param string $name     [名称]
     * @param string $ssid     [标识符]
     * @param int    $zone_id [组别id]
     * @param int    $default  [权限：1:允许，2:禁止]
     * @param int    $other    [其他人权限：1:允许，2:禁止]
     */
    public function add(string $name,string $ssid,int $zone_id,int $default, int $other)
    {

        $res = $this->insert([
            'name'                  => $name,
            'ssid'                  => $ssid,
            'zid'                   => $zone_id,
            'default_permession'    => $default,
            'other_permession'      => $other,
            'update_time'           => $_SERVER['REQUEST_TIME'],
            'create_time'           => $_SERVER['REQUEST_TIME']
        ]);

        if ($res) {
            Log::sucSql('add',$this->getLastSql());
            return true;
        }

        Log::errorSql('add',$this->getLastSql());
        return false;
    }

    public function getListByUser(int $user_id,array $where = [],string $field = 'a.*,c.user_id,d.nickname as user_name,a.name as permession_name,e.name as role_name')
    {
        $model = new UserRole();
        $sqlModel = $model->field('user_id,role_id')
                            ->where('user_id',$user_id)
                            ->where('status',1)
                            ->getSqlModel();

        $res = $this->buildWheres($this,$where)
                    ->table($this->table,'a')
                    ->field($field)
                    ->join(PermessionRole::tableName(),'b.pid = a.id','b','right')
                    ->join($sqlModel,'c.role_id = b.rid','c')
                    ->join(User::tableName(),'c.user_id=d.id','d')
                    ->join(Role::tableName(),'e.id=c.role_id','e')
                    ->where('user_id',$user_id)
                    ->select();
        return $res;
    }



    /**
     * 构建where条件
     * @param  [type] $obj   [description]
     * @param  array  $where [description]
     * @return [type]        [description]
     */
    protected function buildWheres($obj,array $where)
    {
        foreach ($where as $k => $v) {

            if (is_null($v)) {
                continue;
            }
            
            switch ($k) {
                case 'zone_id':
                    $obj->where('a.zid','=',$v);
                    break;
                case 'permession_id':

                    $obj->where('a.id','=',$v);
                    break;
                // case 'status':
                //     $obj->where('a.status','=','1');
            }
        }

        return $obj;
    }

    protected function buildWhere($obj,array $where) 
    {
        foreach ($where as $k => $v) {
            if (is_null($v)) {
                continue;
            }

            switch ($k) {
                case 'zid':
                    $obj->where('zid','=',$v);
                    break;
                case 'name':
                    $obj->where('name','=',$v);
                    break;
            }
        }

        return $obj;
    }

    /**
     * 获取总条数
     * @param  [type] $user_id [description]
     * @param  array  $where   [description]
     * @return [type]          [description]
     */
    public function getTotalPageByUser($user_id,array $where = [])
    {
        $res = $this->buildWheres($this,$where)
                    ->table($this->table,'a')
                    ->join(PermessionRole::tableName(),'b.pid = a.id','b','right')
                    ->join(UserRole::tableName(),'c.role_id = b.rid','c')
                    ->where('user_id',$user_id)
                    ->count('*','total')
                    ->find();
        return $res['total'];
    }


    public function updateInfo(int $id, string $name, int $default_permession, string $other_permession)
    {

        $res = $this->where('id',$id)->update([
            'name'                  => $name,
            'default_permession'    => $default_permession,
            'other_permession'      => $other_permession,
            'update_time'           => $_SERVER['REQUEST_TIME']
        ]);

        if ($res) {
            Log::sucSql('updateInfo',$this->getLastSql());
            return true;
        }

        Log::errorSql('updateInfo',$this->getLastSql());
        return false;
    }
}
