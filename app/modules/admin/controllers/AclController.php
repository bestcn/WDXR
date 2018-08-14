<?php
namespace Wdxr\Modules\Admin\Controllers;


use Phalcon\Acl\Resource;
use Phalcon\Exception;
use Wdxr\Models\Entities\Resources;
use Wdxr\Models\Entities\Roles;
use Wdxr\Models\Services\Acl;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Modules\Admin\Forms\ResourcesForm;
use Wdxr\Modules\Admin\Forms\RolesForm;

class AclController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
        $this->tag->prependTitle("权限控制管理");
    }

    public function indexAction()
    {
        $this->view->setVar('roles', $this->acl->getRoles());
    }

    public function resourceAction()
    {
        $this->view->setVar('resources', $this->acl->getResources());
    }

    public function new_roleAction()
    {
        $form = new RolesForm();
        try {
            if($this->request->isPost()) {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $name = $this->request->getPost('name');
                    $description = $this->request->getPost('description');
                    $acl = $this->request->getPost('acl');
                    if((new Acl())->addRole($name, $description, $acl)) {
                        $this->flash->success('添加新角色成功');
                        $this->dispatcher->forward([
                            'action' => 'index'
                        ]);
                    }
                    return;
                }
            }
        } catch (InvalidServiceException $exception) {
            $this->flash->error($exception->getMessage());
        }
        $this->view->setVar('resources', $this->acl->getAllResourceAccess());
        $this->view->setVar('form', $form);
    }

    public function edit_roleAction($name)
    {
        $role = Roles::findFirstByName($name);
        if (!$role) {
            $this->flash->error("没有找到该角色信息");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        $form = new RolesForm($role, ['edit' => true]);
        if($this->request->isPost()) {
            try {
                if($form->isValid($this->request->getPost(), $role) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new Exception($message);
                    }
                } else {
                    if(!(new Acl())->editRole($role, $this->request->getPost('acl'))) {
                        throw new Exception('修改角色失败');
                    }
                    $this->flash->success('修改角色成功');
                }
            } catch (Exception $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $resources_access = [];
        foreach ($this->acl->getAllResourceAccess() as $key => $access) {
            $resources_access[$key]['resource_name'] = $access['resource_name'];
            $resources_access[$key]['access_name'] = $access['access_name'];
            $resources_access[$key]['is_check'] = $this->acl->isAllowed($name, $access['resource_name'], $access['access_name']) ? 1 : 0;
        }
        $this->view->setVar('resources', $resources_access);
        $this->view->setVar('form', $form);
    }


    public function delete_roleAction()
    {
        $this->view->disable();
        if($this->request->isPost()) {
            try {
                $name = $this->request->getPost('name');
                if((new Acl())->deleteRole($name) == false) {
                    throw new Exception('角色删除失败');
                }
                $this->flash->success('角色删除成功');
            } catch (Exception $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
    }


    public function new_resourceAction()
    {
        $form = new ResourcesForm();
        try {
            if($this->request->isPost()) {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $name = $this->request->getPost('name');
                    $description = $this->request->getPost('description');
                    if($this->acl->addResource(new Resource($name, $description))) {
                        $this->flash->success('添加新权限资源成功');
                        $this->dispatcher->forward([
                            'action' => 'index'
                        ]);
                    }
                    return;
                }
            }
        } catch (InvalidServiceException $exception) {
            $this->flash->error($exception->getMessage());
        }
        $this->view->setVar('form', $form);
    }

    public function edit_resourceAction($name)
    {
        $resource = Resources::findFirstByName($name);
        if (!$resource) {
            $this->flash->error("没有找到该权限资源信息");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        $form = new ResourcesForm($resource, ['edit' => true]);
        if($this->request->isPost()) {
            try {
                if ($form->isValid($this->request->getPost(), $resource) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new Exception($message);
                    }
                } else {
                    if (!$resource->save()) {
                        throw new Exception('修改权限资源失败');
                    }
                    $this->flash->success('修改权限资源成功');
                }
            } catch (Exception $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $this->view->setVar('name', $name);
        $this->view->setVar('form', $form);
    }

    public function accessAction($name)
    {
        if($this->request->isPost()) {
            try {
                $access = $this->request->getPost('access');
                $access = explode('|', $access);
                if(empty($access[0])) {
                    throw new Exception('权限操作不能为空');
                }
                foreach ($access as $item) {
                    if(!$this->acl->addResourceAccess($name, $item)) {
                        throw new Exception('添加权限操作失败');
                    }
                }
                $this->flash->success('添加权限操作成功');
            } catch (Exception $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $this->view->setVar('name', $name);
        $acceses = $this->acl->getResourceAccess($name);

        $this->view->setVar('accesses', $acceses);
    }

    public function delete_accessAction()
    {
        $this->view->disable();
        if($this->request->isPost()) {
            $resource = $this->request->getPost('resource');
            $access = $this->request->getPost('access');
            try {
                if((new Acl())->deleteAccess($resource, $access)) {
                    $this->flash->success('权限操作删除成功');
                }
            } catch (Exception $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
    }

    public function delete_resourceAction()
    {
        $this->view->disable();
        if($this->request->isPost()) {
            $resource = $this->request->getPost('resource');
            try {
                if((new Acl())->deleteResource($resource)) {
                    $this->flash->success('权限资源删除成功');
                }
            } catch (Exception $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
    }



}