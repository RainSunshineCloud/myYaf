<?php
namespace App\Model;
use RainSunshineCloud\ModelException;

/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author Administrator
 */

class User extends Base
{
 	protected $table = 'kuaisan_config';

    /**
     * 获取昵称
     * @param  string $nick_name [description]
     * @param  string $field     [description]
     * @return [type]            [description]
     */
    public function list(array $search,string $page = 1,string $page_size = 10)
    {
    	return $this->limit($page,$page_size)->field("id,name,url,flag")->select();
    }
}
