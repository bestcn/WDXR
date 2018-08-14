<?php
namespace Wdxr\Modules\Admin\Controllers;

use Phalcon\Di;
use Phalcon\Exception;
use Phalcon\Mvc\View;
use Wdxr\Auth\UserAuth;
use Wdxr\Models\Repositories\CompanyVerify as RepoCompanyVerify;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Services\Devices;
use Wdxr\Models\Repositories\Devices as RepDevices;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;


class DevicesController extends ControllerBase
{


    public function indexAction()
    {
        $numberPage = 1; $parameters = [];
        if (!$this->request->isPost()) {
            $numberPage = $this->request->getQuery("page", "int");
        }
        //传递搜索条件
        $this->view->setVar('name', $this->request->get('name'));
        if($data['name'] = $this->request->get('name')){
            $parameters= $data['name'];
        }
        //获取所有消息分页信息
        $paginator = Devices::getDevicesListPagintor($parameters, $numberPage);
        $this->view->setVar('page', $paginator);
    }

    public function viewAction($id)
    {
        if($id){
            $devices = RepDevices::findFirstById($id);
            if($devices === false){
                $this->flash->error("查找不到您要查看的登录信息");
                $this->dispatcher->forward([
                    'action' => 'index'
                ]);
            }
            $data = $devices->toArray();
            $data['user_name'] = UserAdmin::getNameByDeviceId($data['user_id']);
            $this->view->setVar('data', $data);
        }else{
            $this->flash->error("请选择要查看的ID");
            $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }

    }

    public function downlineAction()
    {
        if (!$this->request->isPost()) {
            $this->flash->error("消息删除失败");
            return $this->response->redirect('admin/devices/index');
        }
        //删除设备列表数据
        $id = $this->request->getPost('id');
        $devices = RepDevices::findFirstById($id);
        if($devices === false){
            $this->flash->error("查找不到您要删除的登录信息");
            return $this->response->redirect('admin/devices/index');
        }
        $RepDevices = (new RepDevices())->deleteDevice($devices->getDeviceId());
        $result = (new UserAuth)->deleteDevicesToken($devices->getUserId(),$devices->getToken());
        if($RepDevices && $result){
            $this->flash->success("下线成功");
            return $this->response->redirect('admin/loan/index');
        }else{
            $this->flash->error("下线失败");
            return $this->response->redirect('admin/devices/view/'.$id);
        }
    }




}