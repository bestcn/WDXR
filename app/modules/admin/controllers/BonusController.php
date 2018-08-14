<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 13:40
 */
namespace Wdxr\Modules\Admin\Controllers;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Phalcon\Mvc\Model\Criteria;
use Wdxr\Models\Repositories\BonusSystem;
use Wdxr\Modules\Admin\Forms\BonusForm;

class BonusController extends ControllerBase
{

    public function indexAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\BonusSystem', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $paginator = \Wdxr\Models\Services\BonusSystem::getBonusSystemListPagintor($parameters, $numberPage);
        $this->view->setVar('page', $paginator->getPaginate());
    }

    public function newAction()
    {
        $form = new BonusForm();
        try {
            if($this->request->isPost()) {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $branch = new BonusSystem();
                    $branch->addNew($this->request->getPost());
                    $this->flash->success('增加制度成功');
                    $this->dispatcher->forward([
                        'controller' => "bonus",
                        'action' => 'index'
                    ]);
                    return;
                }
            }
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
        }catch (InvalidServiceException $exception) {
            $this->flash->error($exception->getMessage());
        }
        $this->view->setVar('form', $form);
    }

    public function deleteAction()
    {
        $this->view->disable();
        if(!$this->request->isPost()) {
            $this->flash->error('非法访问');
            return $this->dispatcher->forward([
                'action' => 'search'
            ]);
        }
        try {
            BonusSystem::delete($this->request->getPost('id'));
            $this->flash->success("信息删除成功");
            return $this->response->setJsonContent(['status' => 1, 'info' => '信息删除成功']);
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
            return $this->response->setJsonContent(['status' => 0, 'info' => $exception->getMessage()]);
        }
    }

    public function editAction($id)
    {
        $bonus = BonusSystem::getBonusById($id);
        if (!$bonus) {
            $this->flash->error("没有找到相关信息");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        $form = new BonusForm($bonus, ['edit' => true]);
        if ($this->request->isPost()) {
            try {
                if($form->isValid($this->request->getPost(),$bonus) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new InvalidRepositoryException($message);
                    }
                } else {
                    $data= $this->request->getPost();

                    if (!$bonus->save()) {
                        foreach ($bonus->getMessages() as $message) {
                            throw new InvalidRepositoryException($message);
                        }
                    }
                    $this->flash->success('修改信息成功');
                }
            } catch (InvalidRepositoryException $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $this->view->setVar('id', $id);
        $this->view->setVar('form', $form);
    }

}