<?php
use \App\Service\{PermessionService};
use Response;
class AdminController extends BaseController
{
	const ZoneId = 1;
	protected $permession_service = null;
	public function init()
	{
		parent::init();
		$this->permession_service = new PermessionService();
		$ssid = sprintf('%s/%s/%s',$this->getRequest()->module,$this->getRequest()->controller,$this->getRequest()->action);
		if ($this->uid && !$this->permession_service->check($this->uid,AdminController::ZoneId,$ssid) ) {
			return Response::error('未有权限');
		}
	}

}
