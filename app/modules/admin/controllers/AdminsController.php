<?php
namespace Wdxr\Modules\Admin\Controllers;

use Phalcon\Exception;
use Wdxr\Auth\Auth;
use Wdxr\Models\Repositories\Achievement;
use Wdxr\Models\Repositories\Branch;
use Wdxr\Models\Repositories\Commission;
use Wdxr\Models\Repositories\Probation;
use Wdxr\Models\Repositories\Salesman;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Repositories\CommissionList;
use Wdxr\Modules\Admin\Forms\AdminPasswordForm;
use Wdxr\Modules\Admin\Forms\AdminsForm;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Models\Services\AdminLogs;
use Phalcon\Mvc\Model\Criteria;
use Wdxr\Modules\Admin\Forms\PasswordValidation;


class AdminsController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->tag->prependTitle("人员管理");
    }

    /**
     * index action
     */
    public function indexAction()
    {
        $form = new AdminsForm(null, ['search' => true]);
        $this->view->setVar('form', $form);
    }

    /**
     * Searches for admins
     */
    public function searchAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Admins', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        //如果是分站管理员,只能查看所属分站的人员
        if($_SESSION["auth-identity"]['position'] == "分站管理员"){
            $branch = new Branch();
            $branch_data = $branch->getBranchByAdminId($_SESSION["auth-identity"]['id']);//分站信息

            //查询该分站分配的管理员
            $salesman = new Salesman();
            $salesman_data = $salesman->get_salesman($branch_data->getId());//分站的业务员信息
            if($salesman_data){
                $admin_data = array_column($salesman_data->toArray(),'admin_id');
                $admin_data = "id in (".implode($admin_data,',').")";
            }else{
                $admin_data = "id = ''";
            }
        }else{
            $admin_data = "1=1";
        }

        $paginator = \Wdxr\Models\Services\Admin::getAdminListPagintor($parameters, $numberPage,$admin_data);

        $this->view->setVar('page', $paginator->getPaginate());
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {
        $form = new AdminsForm();
        try {
            if($this->request->isPost()) {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $this->db->begin();
                    $success = (new Admin)->addNew($this->request->getPost());
                    $this->db->commit();
                    if (!$success) {
                        $this->flash->error('新建失败');
                        $this->dispatcher->forward([
                            'controller' => "admins",
                            'action' => 'new'
                        ]);
                        return false;
                    }
                    $this->flash->success('新建管理员成功');
                    $this->logger_operation_set('添加后台管理员','Admins','new');
                    $this->dispatcher->forward([
                        'controller' => "admins",
                        'action' => 'search'
                    ]);
                    return true;
                }
            }
        } catch (InvalidRepositoryException $exception) {
            $this->db->rollback();
            $this->flash->error($exception->getMessage());
        }
        $this->view->setVar('form', $form);
    }

    /**
     * Edits a admin
     *
     * @param string $id
     * @return bool
     */
    public function editAction($id)
    {
        if (($admin = Admin::getAdminById($id)) === false) {
            $this->flash->error("没有找到管理员数据");
            $this->dispatcher->forward([
                'action' => 'search'
            ]);
            return false;
        }
        $branch_id = $admin->getBranchId();
        $is_probation = $admin->getIsProbation();
        $form = new AdminsForm($admin, ['edit' => true]);
        if ($this->request->isPost()) {
            if (empty($this->request->getPost('entry_time'))) {
                $_POST['entry_time'] = null;
            }
            $form->bind($this->request->getPost(), $admin);
            try {
                if ($form->isValid($this->request->getPost(), $admin) == false) {
                    throw new Exception($form->getMessages()[0]);
                } else {
                    $data = $this->request->getPost();
                    if (strcmp($branch_id, $data['branch_id']) !== 0 ||
                        strcmp($is_probation, $data['is_probation']) !== 0
                    ) {
                        (new \Wdxr\Models\Services\Admin())->editAdminCommission($id, $data);
                    }
                    if (!$admin->save()) {
                        throw new Exception($admin->getMessages()[0]);
                    }
                    $this->flash->success('修改管理员成功');
                }
            } catch (InvalidServiceException $exception) {
                $this->flash->error($exception->getMessage());
            } catch (Exception $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $this->view->setVar('id', $id);
        $this->view->setVar('form', $form);
    }

    /**
     * 修改管理员密码
     * @param $id
     */
    public function passwordAction($id)
    {
        if($this->request->isPost()) {
            $data = $this->request->getPost();
            try {
                $validation = new PasswordValidation();
                $messages = $validation->validate($data);
                if(count($messages)) {
                    foreach ($messages as $message) {
                        throw new Exception($message);
                    }
                }

                $admin = new Admin();
                $admin->changePassword($id, $this->request->getPost('password'));
                $this->flash->success('修改管理员密码成功');
            } catch (Exception $exception) {
                $this->flash->error($exception->getMessage());
            }
        }

        $numberPage = $this->request->getQuery("page", "int", 1);
        $this->view->setVars([
            'page' => AdminLogs::getAdminPasswordLogs($id, $numberPage)->getPaginate(),
            'id' => $id
        ]);
    }

    /**
     * 成功登录日志
     * @param $id
     */
    public function logAction($id)
    {
        $numberPage = 1;
        if(!$this->request->isPost()) {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $paginator = AdminLogs::getAdminLoginSuccessLogs($id, $numberPage);

        $this->view->setVar('page', $paginator->getPaginate());
        $this->view->setVar('id', $id);
    }

    /**
     * 失败登录日志
     * @param $id
     */
    public function login_failedAction($id)
    {
        $numberPage = 1;
        if(!$this->request->isPost()) {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $paginator = AdminLogs::getAdminLoginFailedLogs($id, $numberPage);

        $this->view->setVar('page', $paginator->getPaginate());
        $this->view->setVar('id', $id);
    }

    /**
     * 删除指定管理员
     */
    public function deleteAction()
    {
        $this->view->disable();
        if(!$this->request->isPost()) {
            $this->flash->error('非法访问');
            $this->dispatcher->forward([
                'action' => 'search'
            ]);
            return false;
        }

        try {
            Admin::deleteAdmin($this->request->getPost('id'));
            $this->flash->success("管理员删除成功");
            $this->logger_operation_set('删除后台管理员','Admins','delete',$this->request->getPost('id'));
            return $this->response->setJsonContent(['status' => 1, 'info' => '管理员删除成功']);
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
            return $this->response->setJsonContent(['status' => 0, 'info' => $exception->getMessage()]);
        }
    }

}
