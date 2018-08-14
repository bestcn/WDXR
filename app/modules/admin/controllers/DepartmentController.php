<?php
namespace Wdxr\Modules\Admin\Controllers;


use Phalcon\Exception;
use Phalcon\Mvc\Model\Criteria;
use Wdxr\Models\Entities\Departments;
use Wdxr\Models\Services\Department;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Modules\Admin\Forms\DepartmentsForm;

class DepartmentController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
        $this->tag->prependTitle("部门管理");
    }

    public function indexAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Departments', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $paginator = Department::getDepartmentListPagintor($parameters, $numberPage);

        $this->view->setVar('page', $paginator->getPaginate());
    }

    public function newAction()
    {
        $form = new DepartmentsForm();
        try {
            if($this->request->isPost()) {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $position = new Departments();
                    if(!$position->save($this->request->getPost())) {
                        throw new InvalidServiceException('添加新部门失败');
                    }
                    $this->flash->success('添加新部门成功');
                    $this->dispatcher->forward([
                        'controller' => "department",
                        'action' => 'index'
                    ]);
                    return;
                }
            }
        } catch (InvalidServiceException $exception) {
            $this->flash->error($exception->getMessage());
        }
        $this->view->setVar('form', $form);
    }

    public function editAction($id)
    {
        $department = Departments::findFirstById($id);
        if (!$department) {
            $this->flash->error("没有找到该部门信息");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        $form = new DepartmentsForm($department, ['edit' => true]);
        if($this->request->isPost()) {
            try {
                if($form->isValid($this->request->getPost(), $department) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new Exception($message);
                    }
                } else {
                    if(!$department->save()) {
                        throw new Exception('修改部门失败');
                    }
                    $this->flash->success('修改部门成功');
                }
            } catch (Exception $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $this->view->setVar('form', $form);
    }

    public function deleteAction()
    {
        $this->view->disable();
        if(!$this->request->isPost()) {
            $this->flash->error('非法访问');
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }

        try {
            if(!(new Department())->deleteDepartment($this->request->getPost('id'))) {
                throw new InvalidServiceException('部门删除失败');
            }
            $this->flash->success("部门删除成功");
            return $this->response->setJsonContent(['status' => 1, 'info' => '部门删除成功']);
        } catch (InvalidServiceException $exception) {
            $this->flash->error($exception->getMessage());
            return $this->response->setJsonContent(['status' => 0, 'info' => $exception->getMessage()]);
        }
    }
}