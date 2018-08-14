<?php
namespace Wdxr\Modules\Admin\Controllers;

use Phalcon\Exception;
use Phalcon\Mvc\Model\Criteria;
use Wdxr\Models\Entities\Departments;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Models\Services\Position;
use Wdxr\Models\Repositories\Position as PositionRepositories;
use Wdxr\Modules\Admin\Forms\PositionsForm;

class PositionsController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->tag->prependTitle("职能管理");
    }

    public function indexAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Positions', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $paginator = Position::getPositionListPagintor($parameters, $numberPage);

        $this->view->setVar('page', $paginator->getPaginate());
    }

    public function newAction()
    {
        $form = new PositionsForm();
        try {
            if($this->request->isPost()) {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $position = new Position();
                    $position->newPosition($this->request->getPost());
                    $this->flash->success('添加职位成功');
                    $this->dispatcher->forward([
                        'controller' => "positions",
                        'action' => 'index'
                    ]);
                    return;
                }
            }
        } catch (InvalidServiceException $exception) {
            $this->flash->error($exception->getMessage());
        }
        $this->view->setVar('form', $form);
        $this->view->setVar('roles', $this->acl->getRoles());
    }

    public function editAction($id)
    {
        $position = PositionRepositories::getPositionById($id);
        if (!$position) {
            $this->flash->error("没有找到该职位信息");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        $form = new PositionsForm($position, ['edit' => true]);
        if($this->request->isPost()) {
            try {
                if($form->isValid($this->request->getPost(), $position) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new Exception($message);
                    }
                } else {
                    if((new Position)->editPosition($position, $this->request->getPost()) === false) {
                        foreach ($position->getMessages() as $message) {
                            throw new Exception($message);
                        }
                    }
                    $this->flash->success('修改职位成功');

                }
            } catch (Exception $exception) {
                $this->flash->error($exception->getMessage());
            }
        }

        $this->view->setVar('form', $form);
        $this->view->setVar('roles', (new Position())->getPositionActiveRoleName($id));
    }

    public function departmentAction($id)
    {
        $position = PositionRepositories::getPositionById($id);
        $form = new PositionsForm($position, ['edit' => true]);

        $departments = Departments::find();
        $this->view->setVar('departments', $departments);
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
            (new Position)->deletePosition($this->request->getPost('id'));
            $this->flash->success("职位删除成功");
            return $this->response->setJsonContent(['status' => 1, 'info' => '职位删除成功']);
        } catch (InvalidServiceException $exception) {
            $this->flash->error($exception->getMessage());
            return $this->response->setJsonContent(['status' => 0, 'info' => $exception->getMessage()]);
        }
    }


}