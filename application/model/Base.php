<?php 
namespace App\Model;
use RainSunshineCloud\Model;
use Log;

class Base extends Model 
{
    protected $error = '';
    protected $err_code_arr = [];
    public function __construct()
    {
        $db_config = \Yaf\Application::app()->getConfig()->db;
        self::setConfig([
            'db'        => $db_config->name,
            'host'      => $db_config->host,
            'port'      => $db_config->post,
            'user'      => $db_config->user,
            'password'  => $db_config->password,
        ]);

        self::table($this->table);
        $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function getError()
    {
        return $this->error;
    }

    public function getTable()
    {
        return $this->table;
    } 

    public function getTotalPage(array $where = [])
    {
        return intval($this->buildWhere($this,$where)
                            ->count('*','total')
                            ->find()['total']);
    }

    public function getList(int $page,int $page_size,string $field,array $where)
    {

        $res =  $this->buildWhere($this,$where)
                    ->field($field)
                    ->limit($page_size,$page * ($page - 1))
                    ->select();

        return $res;
    }

    protected function buildWhere($obj,array $where)
    {
        return $obj->where($where);
    }

    public static function tableName()
    {
        $model_name = get_called_class();
        $model = new $model_name;
        $table = $model->getTable();
        unset($model);
        return $table;
    }

}