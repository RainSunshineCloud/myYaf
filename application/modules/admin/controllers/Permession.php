<?php 
use RainSunshineCloud\Request;
use \App\Model\Permession;

class PermessionController extends AdminController
{
    public function init()
    {
        parent::init();
    }

    /**
     * 权限列表
     * @return [type] [description]
     */
    public function permessionListAction()
    {
        $params = Request::instance()->check('page','int','请输入页数',['min' => 1])
                                    ->check('pageSize','int','请输入每页数量',['min' => 1])
                                    ->get(['page' => 1,'pageSize' => 10,'name','zid']);
        $res = $this->permession_service->PermsessionList($params['page'],$params['pageSize'],$params);

        Response::success($res);
    }

    /**
     * 添加
     */
    public function addPermessionAction()
    {
        $params = Request::instance()->check('name','string','请填写路由名称',['min' => 3])
                                    ->check('ssid','string','请填写标识符',['min' => 1])
                                    ->check('zid','int','请选择作用域',['min' => 1])
                                    ->check('default_permession','enumInt','请填写权限',[1,2])
                                    ->check('other_permession','enumInt','请填写权限',[1,2])
                                    ->post();

        
        $res = $this->permession_service->addPermession($params['name'],$params['ssid'],$params['zid'],$params['default_permession'], $params['other_permession']);
        if (!$res) {
            Response::error($this->permession_service->getError());
        }

        Response::success();
    }

    /**
     * 修改权限
     */
    public function modifyPermessionAction()
    {
        $input = Request::instance()->check('name','string','请填写路由名称',['min' => 3])
                                    ->check('id','string','invalid params',['min' => 1])
                                    ->check('default_permession','enumInt','请填写权限',[1,2])
                                    ->check('other_permession','enumInt','请填写权限',[1,2])
                                    ->post();

        $res = $this->permession_service->modifyPermession($input);
        if (!$res) {
            Response::error($this->permession_service->getError());
        }

        Response::success();
    }

    /**
     * 角色列表
     */
    public function roleListAction()
    {
        $params = Request::instance()->check('page','int','请输入页数',['min' => 1])
                                    ->check('pageSize','int','请输入每页数量',['min' => 1])
                                    ->get(['page' => 1,'pageSize' => 10,'name']);
        
        $res = $this->permession_service->roleList($params['page'],$params['pageSize'],$params);
        Response::success($res);
    }

    /**
     * 添加角色
     */
    public function addRoleAction()
    {
        $params = Request::instance()->check('name','string','请填写组名称',['min' => 3])->post();

        
        $res = $this->permession_service->addRole($params['name']);
        if (!$res) {
            Response::error($this->permession_service->getError());
        }

        Response::success();
    }

    /**
     * 修改角色
     * @return [type] [description]
     */
    public function modifyRoleAction()
    {
        $input = Request::instance()->check('name','string','请填写路由名称',['min' => 3])->check('id','int','invalid params',['min' => 1])->post(['name','id']);

        $res = $this->permession_service->modifyRole($input['id'],$input['name']);
        if (!$res) {
            Response::error($this->permession_service->getError());
        }

        Response::success();
    }

    /**
     * 作用域列表
     */
    public function zoneListAction()
    {
        $params = Request::instance()->check('field','string','field必须是字符串')
                                    ->check('page','int','请输入页数',['min' => 1])
                                    ->check('pageSize','int','请输入每页数量',['min' => 1])
                                    ->get(['field' => '*','page' => 1,'pageSize' => 10]);
        $res = $this->permession_service->zoneList($params['field'],$params['page'],$params['pageSize']);
        Response::success($res);
    }

    /**
     * 添加作用域
     */
    public function addZoneAction()
    {
        $params = Request::instance()->check('name','string','请填写作用域名称',['min' => 2])->post();

        
        $res = $this->permession_service->addZone($params['name']);
        if (!$res) {
            Response::error($this->permession_service->getError());
        }

        Response::success();
    }


    public function modifyZoneAction()
    {
        $params = Request::instance()->check('name','string','请填写作用域名称',['min' => 2])->check('id','int','invalid params',['min' => 1])->post();

        
        $res = $this->permession_service->modifyZone($params['id'],$params['name']);
        if (!$res) {
            Response::error($this->permession_service->getError());
        }

        Response::success();
    }

    /**
     * 角色权限列表
     * @return [type] [description]
     */
    public function permessionRoleListAction()
    {
        $params = Request::instance()->check('page','int','请输入页数',['min' => 1])
                                    ->check('pageSize','int','请输入每页数量',['min' => 1])
                                    ->check('sort','enumString','invalid params',[
                                        'zone_name desc',
                                        'zone_name asc',
                                        'update_time desc',
                                    ])
                                    ->get([
                                        'page'      => 1,
                                        'pageSize'  => 10,
                                        'sort',
                                        'role_name',
                                        'permession_name',
                                        'status',
                                    ]);
        $res = $this->permession_service->permessionRoleList($params['page'],$params['pageSize'],$params,$params['sort']);
        Response::success($res);
    }

    /**
     * 添加角色权限关联表
     */
    public function addPermessionRoleAction()
    {
        $params = Request::instance()->check('role_id','int','请填写角色id',['min' => 1])
                                    ->check('permession_id','int','请填写权限id',['min' => 1])->post();

        
        $res = $this->permession_service->addPermessionRole($params['role_id'],$params['permession_id']);
        if (!$res) {
            Response::error($this->permession_service->getError());
        }

        Response::success();
    }

    /**
     * 修改角色前线关联表
     * @return [type] [description]
     */
    public function modifyPermessionRoleAction()
    {
        $input = Request::instance()->check('rid','int','invalid params',['min' => 1])
                        ->check('pid','int','invalid params',['min' => 1])->check('status','enumInt','invalid params',[1,2])->post(['rid','pid','status']);
        
        $res = $this->permession_service->modifyPermessionRole($input);
        if ($res) {
            Response::success();
        } 

        Response::error($this->permession_service->getError());
    }

    /**
     * 用户角色列表
     * @return [type] [description]
     */
    public function userRoleListAction()  
    {
        $params = Request::instance()->check('page','int','请输入页数',['min' => 1])
                                    ->check('pageSize','int','请输入每页数量',['min' => 1])
                                    ->get(['page' => 1,'pageSize' => 10,'user_name','role_name']);
        $res = $this->permession_service->userRoleList($params['page'],$params['pageSize'],$params);
        Response::success($res);
    }

    /**
     * 添加用户角色关联表
     */
    public function addUserRoleAction()
    {
        $params = Request::instance()->check('role_id','int','请填写角色id',['min' => 1])->check('user_id','int','请填写用户id',['min' => 1])->post();

        $res = $this->permession_service->addUserRole($params['role_id'],$params['user_id']);
        if (!$res) {
            Response::error($this->permession_service->getError());
        }

        Response::success();
    }

    /**
     * 修改角色关联表
     * @return [type] [description]
     */
    public function modifyUserRoleAction()
    {
        $input = Request::instance()->check('role_id','int','invalid params',['min' => 1])
                        ->check('user_id','int','invalid params',['min' => 1])->check('status','enumInt','invalid params',[1,2])->post(['role_id','user_id','status']);
        
        $res = $this->permession_service->modifyUserRole($input);
        if ($res) {
            Response::success();
        } 

        Response::error($this->permession_service->getError());
    }

    /**
     * 指定用户权限列表
     * @return [type] [description]
     */
    public function userPermessionListAction()
    {
        $params = Request::instance()->check('page','int','请输入页数',['min' => 1])
                                    ->check('pageSize','int','请输入每页数量',['min' => 1])
                                    ->check('user_id','int','请输入用户id')
                                    ->get(['page' => 1,'pageSize' => 10,'user_id','zone_id','permession_name']);

        $res = $this->permession_service->PermsessionListByUser($params['page'],$params['pageSize'],$params['user_id'],$params);
        Response::success($res);
    }


    public function getPageAction()
    {
        $res = $this->permession_service->getPage($this->uid);
        Response::success($res);
    }
}