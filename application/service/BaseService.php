<?php 
namespace App\Service;

class BaseService
{
	protected $error = '';
	protected $err_code_arr = [];

	protected $default_error = 'invalid params';

	public function getError() 
	{
		return  $this->error;
	}

	public function returnList($page,$pageSize,$list,$total)
	{
		return [
				'list' => $list,
				'total' => $total,
				'page' => $page,
				'pageSize' => $pageSize,
			];
	}
}