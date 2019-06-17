<?php 
namespace App\Service;

use Log;
use RainSunshineCloud\ModelException;
use App\Model\{Permession,PermessionZone,PermessionGroup,PermessionRole,Role,UserRole,User};

class PermessionService extends BaseService
{

	/**
	 * 添加权限
	 * @param string $name     [名称]
	 * @param string $ssid     [标识符]
	 * @param int    $zone_id [组别id]
	 * @param int    $default  [默认值]
	 */
	public function addPermession(string $name,string $ssid,int $zone_id,int $default ,int $other) 
	{
		try {
			$permession_model = new Permession();
			$ssid = strtolower($ssid);
			if (empty($permession_model->defaultPermessionArr[$default])) {
				$this->error = 'invalid params';
				return false;
			}

			if (empty($permession_model->otherPermessionArr[$other])) {
				$this->error = 'invalid params';
				return false;
			}

			$permession_zone_model = new PermessionZone();
			//查看group_id是否存在
			if (!$permession_zone_model->getInfo($zone_id,'id')) {
				$this->error = "未有该区域";
				return false;
			}
			//查看权限是否已添加
			if ($permession_model->getInfoBySsidAndZoneId($ssid,$zone_id)) {
				$this->error = "已有该权限";
				return false;
			}
			// 添加该权限
			$res = $permession_model->add($name,$ssid,$zone_id,$default,$other);
			if (!$res) {
				$this->error = $permession_model->getError();
				return false;
			}

			return true;
		} catch (ModelException $e) {
			$this->error = '添加失败';
            Log::errors('addPermession',['msg' => $e->getMessage(),'code' => $e->getCode()]);
            return false;
        }
	}

	public function modifyPermession ($array)
	{
		try {
			$permession_model = new Permession();
			$res = $permession_model->updateInfo($array['id'],$array['name'],$array['default_permession'],$array['other_permession']);

			if ($res) {
				return true;
			}

			$this->error = '修改失败';
			return false;
		} catch (ModelException $e) {
			$this->error = '添加失败';
            Log::errors('addPermession',['msg' => $e->getMessage(),'code' => $e->getCode()]);
            return false;
		}
	}

	/**
	 * 添加作用域
	 * @param string $name [description]
	 */
	public function addZone(string $name)
	{
		try {
			$permession_zone = new PermessionZone();

			$res = $permession_zone->getInfoByName($name,'id');
            if ($res) {
                $this->error = '已有该作用域';
                return false;
            }
			
			$res = $permession_zone->add($name);
			if (!$res) {
				$this->error = $permession_zone->getError();
				return false;
			}
			return true;
		} catch (ModelException $e) {
			$this->error = '添加失败';
            Log::errors('addZone',['msg' => $e->getMessage(),'code' => $e->getCode()]);
            return false;
        }

	}

	public function modifyZone(int $id ,string $name)
	{
		try {
			$permession_zone = new PermessionZone();

			$res = $permession_zone->getInfoByName($name,'id');
            if ($res && $res['id'] != $id) {
                $this->error = '已有该作用域';
                return false;
            }
			
			$res = $permession_zone->modify($id,$name);
			if (!$res) {
				$this->error = '修改失败';
				return false;
			}
			return true;
		} catch (ModelException $e) {
			$this->error = '修改失败';
            Log::errors('modifyZone',['msg' => $e->getMessage(),'code' => $e->getCode()]);
            return false;
        }
	}

	/**
	 * 添加角色
	 * @param string $title [角色名]
	 */
	public function addRole(string $name)
	{
		try {
			$role_model =new Role();
			$res = $role_model->getInfoByName($name,'id');
			if ($res) {
				$this->error = '已有该角色';
				return false;
			}

			$res = $role_model->add($name);
			if (!$res) {
				$this->error = $role_model->getError();
				return false;
			}
			return true;
		} catch (ModelException $e) {
			$this->error = '添加失败';
            Log::errors('addPermession',['msg' => $e->getMessage(),'code' => $e->getCode()]);
            return false;
        }
		
	}

	public function modifyRole(int $id,string $name)
	{
		try {
			$role_model =new Role();
			$res = $role_model->getInfoByName($name,'id');
			if ($res && $res['id'] != $id) {
				$this->error = '已有该角色名';
				return false;
			}

			$res = $role_model->modify($id,$name);
			if (!$res) {
				$this->error = "修改失败";
				return false;
			}
			return true;
		} catch (ModelException $e) {
			$this->error = '修改失败';
            Log::errors('modifyRole',['msg' => $e->getMessage(),'code' => $e->getCode()]);
            return false;
        }
	}

	/**
	 * 角色权限
	 * @param int $role_id       [角色id]
	 * @param int $permession_id [权限id]
	 */
	public function addPermessionRole(int $role_id,int $permession_id)
	{
		try {
			//查看角色id是否存在
			$permession_model = new Permession();
			$info = $permession_model->getInfo($permession_id,'id');
			if (!$info) {
				$this->error = '未有该权限id';
				return false;
			}

			$role_model = new Role();
			$info = $role_model->getInfo($role_id,'id');
			if (!$info) {
				$this->error = "未有该角色";
				return false;
			}
			//添加
			$permession_role_model = new PermessionRole();
			$res = $permession_role_model->add($role_id,$permession_id);
			if (!$res) {
				$this->error = $permession_role_model->getError();
				return false;
			}

			return true;
		} catch (ModelException $e) {
			$this->error = '添加失败';
            Log::errors('addPermessionRole',['msg' => $e->getMessage(),'code' => $e->getCode()]);
            return false;
        }
		
	}

	/**
	 * 添加用户角色
	 * @param int $role_id [角色 id]
	 * @param int $user_id [用户 id]
	 */
	public function addUserRole(int $role_id, int $user_id)
	{
		try {
			$role_model = new Role();
			$res = $role_model->getInfo($role_id,'id');
			if (!$res) {
				$this->error = '该角色不存在';
				return false;
			}

			$user_model = new User();
			if (!$user_model->getInfo($user_id)) {
				$this->error = '该用户不存在';
				return false;
			}

			$user_role_model = new UserRole();
			$res = $user_role_model->add($role_id,$user_id);
			if (!$res) {
				$this->error = $user_role_model->getError();
				return false;
			}
		} catch (ModelException $e) {
			$this->error = '添加失败';
            Log::errors('addUserRole',['msg' => $e->getMessage(),'code' => $e->getCode()]);
            return false;
        }
	}

	public function modifyUserRole($data)
	{
		try {
			$user_role_model = new UserRole();
			$res = $user_role_model->modifyUserRole($data['user_id'],$data['role_id'],$data['status']);

			if ($res) {
				return true;
			} 

			$this->error = "修改失败";
			return false;
		} catch (ModelException $e) {
			$this->error = '修改失败[1]';
            Log::errors('modifyUserRole',['msg' => $e->getMessage(),'code' => $e->getCode()]);
            return false;
        }
	}

	/**
	 * 权限列表
	 * @param int $page     [description]
	 * @param int $pageSize [description]
	 */
	public function permsessionList(int $page,int $pageSize,array $search)
	{
		try {
			$model = new Permession();
			$list = $model->getList($page,$pageSize,'*',$search);
			$total = $model->getTotalPage();
			return $this->returnList($page,$pageSize,$list,$total);
		} catch (ModelException $e) {
            Log::errors('permsessionList',['msg' => $e->getMessage(),'code' => $e->getCode()]);
            return $this->returnList($page,$pageSize,[],0);
		}
	}

	/**
	 * 通过用户获取权限列表
	 * @param int   $page     [页码]
	 * @param int   $pageSize [每页数量]
	 * @param int   $user_id  [用户id]
	 * @param array $search   [其他搜索条件]
	 */
	public function PermsessionListByUser(int $page,int $pageSize,int $user_id,array $search)
	{
		try {
			$search['status'] = 1;
			$model = new Permession();
			$list = $model->getListByUser($user_id,$search);
			$total = $model->getTotalPageByUser($user_id,$search);
			return $this->returnList($page,$pageSize,$list,$total);
		} catch (ModelException $e) {
            Log::errors('PermsessionListByUser',['msg' => $e->getMessage(),'code' => $e->getCode()]);
            return $this->returnList($page,$pageSize,[],0);
		}
	}

	/**
	 * 区域列表
	 * @param  [type] $field    [description]
	 * @param  [type] $page     [description]
	 * @param  [type] $pageSize [description]
	 * @return [type]           [description]
	 */
	public function zoneList($field,$page, $pageSize)
	{
		try {
			$model = new PermessionZone();
			$list = $model->getList($page,$pageSize,$field,[]);
			$total = $model->getTotalPage();
			return $this->returnList($page,$pageSize,$list,$total);
		} catch (ModelException $e) {
            Log::errors('PermsessionListByUser',['msg' => $e->getMessage(),'code' => $e->getCode()]);
            return $this->returnList($page,$pageSize,[],0);
		}
	}

	/**
	 * 角色列表
	 * @param  int    $page     [description]
	 * @param  int    $pageSize [description]
	 * @return [type]           [description]
	 */
	public function roleList(int $page, int $pageSize,array $search = [])
	{
		try {
			$model = new Role();
			$list = $model->getList($page,$pageSize,'*',$search);
			$total = $model->getTotalPage($search);
			return $this->returnList($page,$pageSize,$list,$total);
		} catch (ModelException $e) {
            Log::errors('PermsessionListByUser',['msg' => $e->getMessage(),'code' => $e->getCode()]);
            return $this->returnList($page,$pageSize,[],0);
		}
	}

	/**
	 * 获取用户角色列表
	 * @param  int    $page     [description]
	 * @param  int    $pageSize [description]
	 * @return [type]           [description]
	 */
	public function userRoleList(int $page, int $pageSize,array $search)
	{
		try {
			$model = new UserRole();
			$field = 'b.nickname as user_name,c.name as role_name,a.status,a.role_id,a.update_time,a.create_time,a.user_id';
			$list = $model->getList($page,$pageSize,$field,$search);
			$total = $model->getTotalPage($search);

			return $this->returnList($page,$pageSize,$list,$total);
		} catch (ModelException $e) {
            Log::errors('PermsessionListByUser',['msg' => $e->getMessage(),'code' => $e->getCode()]);
			return $this->returnList($page,$pageSize,[],0);
		}
	}

	/**
	 * 获取角色权限列表
	 * @param int $page     [description]
	 * @param int $pageSize [description]
	 */
	public function PermessionRoleList(int $page,int $pageSize,array $search ,$sort)
	{
		try {

			$model = new PermessionRole();
			// 请求数据
			$field = 'a.*,b.name as permession_name,c.name as zone_name,d.name as role_name,b.default_permession,b.other_permession';
			$sort = str_replace(['zone_name,update_time'], ['c.name','a.update_time'], $sort);

			$list = $model->getList($page,$pageSize,$field,$search,$sort);
			$total = $model->getTotalPage($search);
			return $this->returnList($page,$pageSize,$list,$total);
		} catch (ModelException $e) {
			Log::errors('PermsessionListByUser',[
				'msg' => $e->getMessage(),
				'code' => $e->getCode()
			]);
            return $this->returnList($page,$pageSize,[],0);
		}
	}

	public function modifyPermessionRole($data)
	{
		try {
			$user_role_model = new PermessionRole();
			$res = $user_role_model->modifyPermessionRole($data['rid'],$data['pid'],$data['status']);

			if ($res) {
				return true;
			} 

			$this->error = "修改失败";
			return false;
		} catch (ModelException $e) {
			$this->error = '修改失败[1]';
            Log::errors('modifyUserRole',['msg' => $e->getMessage(),'code' => $e->getCode()]);
            return false;
        }
	}

	/**
	 * 判断是否有权限
	 * @param  [type] $user_id [description]
	 * @param  [type] $zone    [description]
	 * @param  [type] $ssid    [description]
	 * @return [type]          [description]
	 */
	public static function check(int $user_id,int $zone_id,string $ssid)
	{
		$model = new Permession();
		$ssid = strtolower($ssid);
		$res = $model->checkHasPermession($user_id,$zone_id,$ssid);
		return $res;
	}

	public function getPage(int $user_id)
	{
		try {
			$model = new Permession();
			$res = $model->getPage($user_id);

			return $res;
		} catch (ModelException $e) {
			var_dump($e);exit;
            return false;
        }
		
	}

}