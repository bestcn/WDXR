<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 13:40
 */
namespace Wdxr\Modules\Admin\Controllers;
use Wdxr\Models\Repositories\Achievement;
use Wdxr\Models\Repositories\BranchsCommission;
use Wdxr\Models\Repositories\BranchsCommissionList;
use Wdxr\Models\Repositories\Salesman;
use Wdxr\Models\Repositories\Branch;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Models\Services\BranchsLevel;
use Wdxr\Models\Repositories\BranchsLevels as RepBranchsLevels;
use Wdxr\Modules\Admin\Forms\BranchsForm;
use Phalcon\Mvc\Model\Criteria;
use Wdxr\Models\Entities\Branchs as EntityAdmin;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Modules\Admin\Forms\BranchsLevelForm;

class BranchsController extends ControllerBase
{

    public function indexAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Branchs', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $paginator = \Wdxr\Models\Services\Branch::getBranchListPagintor($parameters, $numberPage);

        $this->view->setVar('page', $paginator->getPaginate());
    }

    public function newAction()
    {
        $form = new BranchsForm();
        try {
            if($this->request->isPost()) {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $branch = new \Wdxr\Models\Services\Branch();
                    $branch->addBranchs($this->request->getPost());
                    $this->flash->success('新建分公司成功');
                    $this->dispatcher->forward([
                        'controller' => "branchs",
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

    /**
     * Edits a admin
     *
     * @param string $id
     */
    public function editAction($id)
    {
        $branch = Branch::getBranchById($id);
        if (!$branch) {
            $this->flash->error("没有找到分公司数据");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        $form = new BranchsForm($branch, ['edit' => true]);
        $BranchLevel = $branch->getBranchLevel();
        if ($this->request->isPost()) {
            try {
                if($form->isValid($this->request->getPost(),$branch) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new InvalidRepositoryException($message);
                    }
                } else {
                    $data= $this->request->getPost();
                    if(strcmp($BranchLevel,$data['branch_level']) !== 0){
                        $Money =  Achievement::getMoneyByBranchId($id);
                        if($Money === false){
                            $amount = 0;
                        }else{
                            $amount = $Money->toArray()['amount'];
                        }
                        $ratio = BranchsCommission::getRatio($data['branch_level'],$amount);
                        if($ratio === false){
                            throw new InvalidRepositoryException('查找不到当前等级分公司提成设置');
                        }
                    }
                    if (!$branch->save()) {
                        foreach ($branch->getMessages() as $message) {
                            throw new InvalidRepositoryException($message);
                        }
                    }
                    $this->flash->success('修改分公司成功');
                    return $this->response->redirect('admin/branchs/index');
                }
            } catch (InvalidRepositoryException $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $this->view->setVar('id', $id);
        $this->view->setVar('form', $form);
    }



    /**
     * 删除指定管理员
     */
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
            Branch::deleteBranch($this->request->getPost('id'));
            $this->flash->success("分公司删除成功");
            return $this->response->setJsonContent(['status' => 1, 'info' => '分公司删除成功']);
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
            return $this->response->setJsonContent(['status' => 0, 'info' => $exception->getMessage()]);
        }
    }

    /**
     * 人员分配
     */
    public function salesmansAction($edit_id)
    {
        $form = new BranchsForm();
        //获取人员列表
        $all = new Admin();
        $admins = $all->getAll()->toArray();

        //获取已分配人员列表
        $salesman = new Salesman();
        $result = $salesman->get_salesman($edit_id)->toArray();

        if($result){
            foreach($result as $k=>$v){
                foreach($admins as $key=>$val){
                    if(!isset($val['selected']) || $val['selected'] == 2){
                        if($val['id'] == $v['admin_id']){
                            $admins[$key]['selected'] = 1;
                        }else{
                            $admins[$key]['selected'] = 2;
                        }
                    }
                }
            }
        }else{
            foreach($admins as $key=>$val){
                $admins[$key]['selected'] = 2;
            }
        }
        $un_data = $salesman->get_unsalesman($edit_id);
        if($un_data){
            $un_data = $un_data->toArray();
            foreach($un_data as $key=>$val){
                foreach($admins as $k=>$v){
                    if($v['id'] == $val['admin_id']){
                        unset($admins[$k]);
                    }
                }
            }
        }
        $this->view->setVar('admins',$admins);
        $this->view->setVar('id', $edit_id);
        $this->view->setVar('form', $form);
    }

    /**
     * 更新分公司人员
     */

    public function salesmans_updateAction()
    {
        $Salesman = new Salesman();
        if($_REQUEST['status'] == 1){
            unset($_REQUEST['_url']);
            unset($_REQUEST['status']);
            $Salesman->addNew($_REQUEST);
        }else{
            unset($_REQUEST['_url']);
            unset($_REQUEST['status']);
            $Salesman->delete($_REQUEST);
        }
    }


    //客户等级设置
    public function levelAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\BranchsLevels', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        $paginator = BranchsLevel::getLevelListPagintor($parameters, $numberPage);
        $this->view->setVar('page', $paginator->getPaginate());
    }

    //添加级别
    public function add_levelAction()
    {
        $form = new BranchsLevelForm();
        try {
            if($this->request->isPost()) {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $level = new RepBranchsLevels();
                    $level->addNew($this->request->getPost());
                    $this->flash->success('添加级别成功');
                    $this->dispatcher->forward([
                        'controller' => "branchs",
                        'action' => 'level'
                    ]);
                    return;
                }
            }
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
        }

        $this->view->setVar('form', $form);
    }

    //查看修改级别
    public function level_editAction($id)
    {
        $level = RepBranchsLevels::getLevelById($id);
        if (!$level) {
            $this->flash->error("没有找级别信息");
            return $this->dispatcher->forward([
                'action' => 'level'
            ]);
        }
        $form = new BranchsLevelForm($level, ['edit' => true]);

        if ($this->request->isPost()) {
            try {
                if($form->isValid($this->request->getPost(), $level) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new InvalidRepositoryException($message);
                    }
                } else {

                    if (!$level->save()) {
                        foreach ($level->getMessages() as $message) {
                            throw new InvalidRepositoryException($message);
                        }
                    }
                    $this->flash->success('修改级别信息成功');
                    $this->dispatcher->forward([
                        'controller' => "branchs",
                        'action' => 'level'
                    ]);
                    return;
                }
            } catch (InvalidRepositoryException $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $this->view->setVar('id', $id);
        $this->view->setVar('form', $form);
    }

    //删除级别信息
    public function  level_deleteAction()
    {
        $this->view->disable();
        if(!$this->request->isPost()) {
            $this->flash->error('非法访问');
            return $this->dispatcher->forward([
                'action' => 'level'
            ]);
        }
        try {
            RepBranchsLevels::deleteLevel($this->request->getPost('id'));
            $this->flash->success("删除成功");
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
        }
    }
}