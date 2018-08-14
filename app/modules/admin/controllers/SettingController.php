<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 13:40
 */
namespace Wdxr\Modules\Admin\Controllers;

use Wdxr\Auth\Auth;
use Wdxr\Models\Repositories\Account;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Models\Repositories\Attachment;
use Wdxr\Models\Repositories\BillTerm;
use Wdxr\Models\Repositories\BranchsCommission;
use Wdxr\Models\Repositories\BranchsCommissionList;
use Wdxr\Models\Repositories\Commission;
use Wdxr\Models\Repositories\CommissionList;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\CompanyBill;
use Wdxr\Models\Repositories\CompanyInfo;
use Wdxr\Models\Repositories\CompanyReport;
use Wdxr\Models\Repositories\CompanyService;
use Wdxr\Models\Repositories\Feedback;
use Wdxr\Models\Repositories\Probation;
use Wdxr\Models\Repositories\ReportTerm;
use Wdxr\Models\Repositories\Rterm;
use Wdxr\Models\Repositories\Salesman;
use Wdxr\Models\Repositories\Term;
use Wdxr\Models\Repositories\User;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Repositories\Version;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Phalcon\Exception;
use Phalcon\Mvc\Model\Criteria;
use Wdxr\Models\Entities\Version as EntityAdmin;
use Wdxr\Models\Services\Cos;
use Wdxr\Models\Services\PushService;
use Wdxr\Modules\Admin\Forms\AccountForm;
use Wdxr\Modules\Admin\Forms\BranchsCommissionForm;
use Wdxr\Modules\Admin\Forms\CommissionForm;
use Wdxr\Modules\Admin\Forms\ProbationForm;
use Wdxr\Modules\Admin\Forms\TermForm;

class SettingController extends ControllerBase
{
    //票据征信默认期限
    const DATE_DEFAULT = 3;
    //日期类型
    const DATE_TYPE_DAY = 0;
    const DATE_TYPE_MONTH = 1;
    const DATE_TYPE_YEAR = 2;


    public function indexAction()
    {

        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Version', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $paginator = \Wdxr\Models\Services\Version::getVersionListPagintor($parameters, $numberPage);

        $this->view->setVar('page', $paginator->getPaginate());
    }

    public function newAction()
    {
        try {
            if($this->request->isPost()) {
                    $branch = new Version();
                    $branch->addNew($this->request->getPost());
                    $this->flash->success('新版本添加成功');
                    $this->dispatcher->forward([
                        'controller' => "setting",
                        'action' => 'index'
                    ]);
                    return;

            }
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
        }
    }

    /**
     * Edits a admin
     *
     * @param string $id
     */
    public function editAction($id)
    {
        $Version = Version::getVersionById($id);
        if (!$Version) {
            $this->flash->error("没有找到版本信息");
            return $this->dispatcher->forward([
                'action' => 'search'
            ]);
        }

        if ($this->request->isPost()) {
            try {
                $data = $this->request->getPost();
                    if ((new Version())->edit($id,$data)) {
                        foreach ($Version->getMessages() as $message) {
                            throw new InvalidRepositoryException($message);
                        }
                    }
                    $this->flash->success('修改版本信息成功');

            } catch (InvalidRepositoryException $exception) {
                $this->flash->error($exception->getMessage());
            }
        }

        $this->view->setVar('data', $Version);
    }


    public function termAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Term', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $paginator = \Wdxr\Models\Services\Term::getTermListPagintor($parameters, $numberPage);
        $this->view->setVar('page', $paginator->getPaginate());
    }

    public function deletetermAction()
    {
        $this->view->disable();
        if(!$this->request->isPost()) {
            $this->flash->error('非法访问');
            return $this->dispatcher->forward([
                'action' => 'term'
            ]);
        }

        try {
            Term::deleteTerm($this->request->getPost('id'));
            $this->flash->success("删除成功");
            return $this->dispatcher->forward([
                'action' => 'term'
            ]);
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
            return $this->dispatcher->forward([
                'action' => 'term'
            ]);
        }
    }

    public function newtermAction()
    {
        $form = new TermForm();
        try {
            if($this->request->isPost()) {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $term = new Term();
                    $new_payment = $this->request->getPost('payment');
                    if($term->seletcPayment($new_payment)){
                        $this->flash->error('该缴费类型已经设置');
                        $this->dispatcher->forward([
                            'action' => 'term'
                        ]);
                        return;
                    }
                    $term->addNew($this->request->getPost());
                    $this->flash->success('自定义期限设置成功');
                    $this->dispatcher->forward([
                        'action' => 'term'
                    ]);
                    return;
                }
            }
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
        }

        $this->view->setVar('form', $form);
    }

    public function edittermAction($id)
    {
        $term = Term::getTermById($id);
        if (!$term) {
            $this->flash->error("没有找到该配置");
            return $this->dispatcher->forward([
                'action' => 'term'
            ]);
        }
        $form = new TermForm($term, ['edit' => true]);

        if ($this->request->isPost()) {
            try {
                if($form->isValid($this->request->getPost(), $term) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new InvalidRepositoryException($message);
                    }
                } else {

                    if (!$term->save()) {
                        foreach ($term->getMessages() as $message) {
                            throw new InvalidRepositoryException($message);
                        }
                    }
                    $this->flash->success('修改设置成功');
                }
            } catch (InvalidRepositoryException $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $this->view->setVar('id', $id);
        $this->view->setVar('form', $form);
    }

    public function rtermAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Rterm', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $paginator = \Wdxr\Models\Services\Rterm::getTermListPagintor($parameters, $numberPage);
        $this->view->setVar('page', $paginator->getPaginate());
    }

    public function newrtermAction()
    {
        $form = new TermForm();
        try {
            if($this->request->isPost()) {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $term = new Rterm();
                    $new_payment = $this->request->getPost('payment');
                    if($term->seletcPayment($new_payment)){
                        $this->flash->error('该缴费类型已经设置');
                        $this->dispatcher->forward([
                            'action' => 'rterm'
                        ]);
                        return;
                    }
                    $term->addNew($this->request->getPost());
                    $this->flash->success('自定义期限设置成功');
                    $this->dispatcher->forward([
                        'action' => 'rterm'
                    ]);
                    return;
                }
            }
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
        }

        $this->view->setVar('form', $form);
    }

    public function editrtermAction($id)
    {
        $term = Rterm::getTermById($id);
        if (!$term) {
            $this->flash->error("没有找到该配置");
            return $this->dispatcher->forward([
                'action' => 'rterm'
            ]);
        }
        $form = new TermForm($term, ['edit' => true]);

        if ($this->request->isPost()) {
            try {
                if($form->isValid($this->request->getPost(), $term) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new InvalidRepositoryException($message);
                    }
                } else {

                    if (!$term->save()) {
                        foreach ($term->getMessages() as $message) {
                            throw new InvalidRepositoryException($message);
                        }
                    }
                    $this->flash->success('修改设置成功');
                }
            } catch (InvalidRepositoryException $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $this->view->setVar('id', $id);
        $this->view->setVar('form', $form);
    }

    public function deletertermAction()
    {
        $this->view->disable();
        if(!$this->request->isPost()) {
            $this->flash->error('非法访问');
            return $this->dispatcher->forward([
                'action' => 'rterm'
            ]);
        }

        try {
            Rterm::deleteTerm($this->request->getPost('id'));
            $this->flash->success("删除成功");
            return $this->dispatcher->forward([
                'action' => 'rterm'
            ]);
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
            return $this->dispatcher->forward([
                'action' => 'rterm'
            ]);
        }
    }

    //企业票据期限列表
    public function bill_termAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\BillTerm', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $paginator = \Wdxr\Models\Services\BillTerm::getBillTermListPagintor($parameters, $numberPage);
        $this->view->setVar('page', $paginator->getPaginate());
    }

    //修改企业票据期限列表
    public function edit_bill_termAction($id)
    {
        $bill_term = BillTerm::getBillTermById($id);
        if (!$bill_term) {
            $this->flash->error("没有找到信息");
            return $this->dispatcher->forward([
                'action' => 'bill_term'
            ]);
        }
        $form = new TermForm($bill_term, ['edit' => true]);

        if ($this->request->isPost()) {
            try {
                if($form->isValid($this->request->getPost(), $bill_term) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new InvalidRepositoryException($message);
                    }
                } else {

                    if (!$bill_term->save()) {
                        foreach ($bill_term->getMessages() as $message) {
                            throw new InvalidRepositoryException($message);
                        }
                    }
                    $this->flash->success('修改成功');


                    //修改企业的截止时间
                    $service_data = CompanyService::getCompanyService($bill_term->getCompanyId());
                    $bill_end_time = CompanyReport::setReportEndTime($bill_term->getTerm(),$bill_term->getType(), $service_data->getStartTime());

                    $bill_data = CompanyBill::getCurrentCompanyBill($bill_term->getCompanyId());
                    $bill_data->setEndTime($bill_end_time);
                    $bill_data->save();

                }
            } catch (InvalidRepositoryException $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $this->view->setVar('id', $id);
        $this->view->setVar('form', $form);
    }

    //企业征信审核期限列表
    public function report_termAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\ReportTerm', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $paginator = \Wdxr\Models\Services\ReportTerm::getReportTermListPagintor($parameters, $numberPage);
        $this->view->setVar('page', $paginator->getPaginate());
    }

    public function edit_report_termAction($id)
    {
        $report_term = ReportTerm::getReportTermById($id);
        if (!$report_term) {
            $this->flash->error("没有找到信息");
            return $this->dispatcher->forward([
                'action' => 'bill_term'
            ]);
        }
        $form = new TermForm($report_term, ['edit' => true]);



        if ($this->request->isPost()) {
            try {
                if ($form->isValid($this->request->getPost(), $report_term) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new InvalidRepositoryException($message);
                    }
                } else {
                    if (!$report_term->save()) {
                        foreach ($report_term->getMessages() as $message) {
                            throw new InvalidRepositoryException($message);
                        }
                    }
                    $this->flash->success('修改成功');


                    //修改企业的截止时间
                    $service_data = CompanyService::getCompanyService($report_term->getCompanyId());
                    $report_end_time = CompanyReport::setReportEndTime($report_term->getTerm(), $report_term->getType(), $service_data->getStartTime());

                    $repo_company_report = new CompanyReport();
                    $company_reports = $repo_company_report->getCompanyReportByCompanyId($report_term->getCompanyId());
                    foreach ($company_reports as $company_report) {
                        $company_report->setEndTime($report_end_time);
                        if ($company_report->save() === false) {
                            throw new InvalidRepositoryException("征信期限保存失败");
                        }
                    }
                }
            } catch (InvalidRepositoryException $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $this->view->setVar('id', $id);
        $this->view->setVar('form', $form);
    }




        //企业账户管理
    public function accountAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Account', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $paginator = \Wdxr\Models\Services\Account::getAccountListPagintor($parameters, $numberPage);
        $this->view->setVar('page', $paginator->getPaginate());
    }

    public function account_newAction()
    {
        $form = new AccountForm();
        try {
            if($this->request->isPost()) {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $account = new Account();
                    $account->addNew($this->request->getPost());
                    $this->flash->success('添加账户成功');
                    $this->dispatcher->forward([
                        'controller' => "setting",
                        'action' => 'account'
                    ]);
                    return;
                }
            }
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
        }

        $this->view->setVar('form', $form);
    }

    public function account_deleteAction()
    {
        $this->view->disable();
        if(!$this->request->isPost()) {
            $this->flash->error('非法访问');
            return $this->dispatcher->forward([
                'action' => 'account'
            ]);
        }

        try {
            Account::delete($this->request->getPost('id'));
            $this->flash->success("账户删除成功");
            return $this->response->setJsonContent(['status' => 1, 'info' => '账户删除成功']);
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
            return $this->response->setJsonContent(['status' => 0, 'info' => $exception->getMessage()]);
        }
    }

    public function account_editAction($id)
    {
        $account = Account::getAccountById($id);
        if (!$account) {
            $this->flash->error("没有找到账户数据");
            return $this->dispatcher->forward([
                'action' => 'search'
            ]);
        }
        $form = new AccountForm($account, ['edit' => true]);

        if ($this->request->isPost()) {
            try {
                if($form->isValid($this->request->getPost(), $account) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new InvalidRepositoryException($message);
                    }
                } else {

                    if (!$account->save()) {
                        foreach ($account->getMessages() as $message) {
                            throw new InvalidRepositoryException($message);
                        }
                    }
                    $this->flash->success('修改账户成功');
                }
            } catch (InvalidRepositoryException $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $this->view->setVar('id', $id);
        $this->view->setVar('form', $form);
    }

    /*
     * 业务员提成设置
     */

    public function commissionAction($branch_id)
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Commission', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        try{
            $paginator = \Wdxr\Models\Services\Commission::getCommissionListPagintor($branch_id,$parameters, $numberPage);
            $this->view->setVar('branch_id', $branch_id);
            $this->view->setVar('page', $paginator->getPaginate());
        }catch (InvalidServiceException $exception) {
            $this->flash->error($exception->getMessage());
        }

    }

    /**
     * 添加业务员提成设置
     */
    public function new_commissionAction($branch_id)
    {
        try {
            $form = new CommissionForm();
            if($this->request->isPost()) {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $commission = new Commission();
                    $data = $this->request->getPost();
                    $data['admin_id'] = (new Auth())->getIdentity()['id'];
                    $data['branch_id'] = $branch_id;
                    $commission->selectAmout($data['branch_id'],$data['amount'],$data['ratio']);
                    $commission->addNew($data);
                    $this->flash->success('自定义提成设置成功');
                    $this->dispatcher->forward([
                        'action' => 'commission'
                    ]);
                }
            }
            $this->view->setVar('branch_id', $branch_id);
            $this->view->setVar('form', $form);
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
        }catch (Exception $exception) {
            $this->flash->error($exception->getMessage());
        }
    }



    /**
     * 添加试用期提成设置
     */
    public function probation_commissionAction($branch_id)
    {
        try {
            $device_id = (new Auth())->getIdentity()['id'];
            $Probation = Probation::getProbationByBranchsId($branch_id);
            if($Probation === false){
                $form = new ProbationForm();
            }else{
                $form = new ProbationForm($Probation, ['edit' => true]);
            }
            if($this->request->isPost()) {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $data = $this->request->getPost();
                    $data['device_id']=$device_id;
                    $data['branchs_id']=$branch_id;
                    if($Probation === false){
                        (new Probation())->addNew($data);
                    }else{
                        (new Probation())->edit($Probation->getId(),$data);
                    }
                    $this->flash->success('自定义试用期提成设置成功');
                }
            }
            $this->view->setVar('branch_id', $branch_id);
            $this->view->setVar('form', $form);
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
        }catch (Exception $exception) {
            $this->flash->error($exception->getMessage());
        }
    }

    /**
     * 修改业绩提成设置
     */
    public function edit_commissionAction($id)
    {
        $commission = Commission::getCommissionById($id);
        if (!$commission) {
            $this->flash->error("没有找到该设置");
            return $this->dispatcher->forward([
                'action' => 'commission'
            ]);
        }
        $form = new CommissionForm($commission, ['edit' => true]);
        if ($this->request->isPost()) {
            try {
                if($form->isValid($this->request->getPost(), $commission) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new InvalidRepositoryException($message);
                    }
                } else {
                    $data = $this->request->getPost();
                    (new Commission())->selectEditAmout($id,$commission->getBanchId(),$data['amount'],$data['ratio']);
                    if (!$commission->save()) {
                        foreach ($commission->getMessages() as $message) {
                            throw new InvalidRepositoryException($message);
                        }
                    }
                    $this->flash->success('修改提成设置成功');
                }
            } catch (InvalidRepositoryException $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $this->view->setVar('id', $id);
        $this->view->setVar('form', $form);
    }

    public function delete_commissionAction()
    {
        $this->view->disable();
        if(!$this->request->isPost()) {
            $this->flash->error('非法访问');
            return $this->dispatcher->forward([
                'action' => 'commission'
            ]);
        }

        try {
            commission::deleteCommission($this->request->getPost('id'));
            $this->flash->success("删除成功");
            return $this->dispatcher->forward([
                'action' => 'commission'
            ]);
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
            return $this->dispatcher->forward([
                'action' => 'commission'
            ]);
        }
    }

    /**
     * 业务员人员业绩比率列表
     */
    public function commission_listAction($branch_id)
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $_POST['type'] = UserAdmin::TYPE_ADMIN;
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\CommissionList', $_POST);
            $parameters = $query->getParams();
        } else {
            $data['type'] = UserAdmin::TYPE_ADMIN;
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\CommissionList', $data);
            $parameters = $query->getParams();
        }
        //传递搜索条件
        $this->view->setVar('name', $this->request->get('name'));

        if($data['name'] = $this->request->get('name')){
            $data['type'] = UserAdmin::TYPE_ADMIN;
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\CommissionList', $data);
            $numberPage = $this->request->get('page');
            $parameters = $query->getParams();
        }

        $paginator = \Wdxr\Models\Services\CommissionList::getCommissionListPagintor($branch_id,$parameters, $numberPage);
        $this->view->setVar('branch_id', $branch_id);
        $this->view->setVar('page', $paginator->getPaginate());
    }

    /**
     *合伙人业绩比率列表
     */
    public function partner_commission_listAction($branch_id)
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $_POST['type'] = UserAdmin::TYPE_USER;
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\CommissionList', $_POST);
            $parameters = $query->getParams();
        } else {
            $data['type'] = UserAdmin::TYPE_USER;
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\CommissionList', $data);
            $parameters = $query->getParams();
        }
        //传递搜索条件
        $this->view->setVar('name', $this->request->get('name'));

        if($data['name'] = $this->request->get('name')){
            $data['type'] = UserAdmin::TYPE_USER;
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\CommissionList', $data);
            $numberPage = $this->request->get('page');
            $parameters = $query->getParams();
        }
        $paginator = \Wdxr\Models\Services\CommissionList::getCommissionListPagintor($branch_id,$parameters, $numberPage);
        $this->view->setVar('branch_id', $branch_id);
        $this->view->setVar('page', $paginator->getPaginate());
    }

    /**
     * 修改人员业绩比率
     */
    public function edit_commission_listAction($id)
    {
        $CommissionList = CommissionList::getCommissionListById($id);
        if (!$CommissionList) {
            $this->flash->error("没有找到信息");
            return $this->dispatcher->forward([
                'action' => 'commission_list'
            ]);
        }
        if ($this->request->isPost()) {
            if(!$this->request->getPost('ratio')){
                $this->flash->error("请输入提成比率");
                return $this->dispatcher->forward([
                    'action' => 'commission_list'
                ]);
            }
            try {
                $CommissionList->setRatio($this->request->getPost('ratio'));
                    if (!$CommissionList->save()) {
                        foreach ($CommissionList->getMessages() as $message) {
                            throw new InvalidRepositoryException($message);
                        }
                    }
                    $this->flash->success('修改成功');
            } catch (InvalidRepositoryException $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $this->view->setVar('id', $id);
        $this->view->setVar('data', $CommissionList);
    }



    /**
     * 反馈列表
     */
    public function feedBackAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Feedback', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $paginator = \Wdxr\Models\Services\Feedback::getFeedbackListPagintor($parameters, $numberPage);

        $this->view->setVar('page', $paginator->getPaginate());
    }

    /**
     * 获取图片
     * @param $data
     * @return array
     */
    private function each_att($data)
    {
        $array = array();
        foreach($data as $val){
            $array[] =  (new Cos())->private_url($val['object_id']);//\OSS\Common::getOSSUrl($val['object_id']);
        }
        return $array;
    }

    /**
     * 反馈详情
     */
    public function feedBackContentAction($id)
    {
        $feed_back_data = Feedback::getFeedbackById($id);
        if($feed_back_array = $feed_back_data->toArray()){
            try{
                    $feed_back_array['pic'] = $this->each_att(Attachment::getAttachmentById(explode(',',$feed_back_array['img']))->toArray());
                    $user_admin = UserAdmin::getUser($feed_back_array['device_id']);
                    if($user_admin->getType() == UserAdmin::TYPE_ADMIN){
                        $admin = (new Admin())->getAdminsById($user_admin->getUserId());
                        $device = $admin->getName();
                    }else{
                        $company = (new Company())->getById($user_admin->getUserId());
                        $device = $company->getName();
                    }
                    $feed_back_array['device'] = $device;

            }
            catch (InvalidRepositoryException $exception){
                $this->flash->error($exception->getMessage());
            }
        }
        $this->view->setVar('data', $feed_back_array);
    }

    /**
     * 删除反馈
     */
    public function feedBackDeleteAction()
    {
        $this->view->disable();
        if(!$this->request->isPost()) {
            $this->flash->error('非法访问');
            return $this->dispatcher->forward([
                'action' => 'feedBack'
            ]);
        }

        try {
            Feedback::deleteFeedback($this->request->getPost('id'));
            $this->flash->success("删除成功");
            return $this->dispatcher->forward([
                'action' => 'feedBack'
            ]);
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
            return $this->dispatcher->forward([
                'action' => 'feedBack'
            ]);
        }
    }

    /**
     * 添加分公司提成设置
     */
    public function new_branchs_commissionAction()
    {
        try {
            $form = new BranchsCommissionForm();
            if($this->request->isPost()) {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $commission = new BranchsCommission();
                    $data = $this->request->getPost();
                    $commission->selectAmout($data['level'],$data["amount"]);
                    $commission->addNew($data);
                    $this->flash->success('自定义分站提成设置成功');
                    $this->dispatcher->forward([
                        'action' => 'branchs_commission'
                    ]);
                }
            }
            $this->view->setVar('form', $form);
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
        }catch (Exception $exception) {
            $this->flash->error($exception->getMessage());
        }
    }

    /*
     * 业务员提成设置
     */

    public function branchs_commissionAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\BranchsCommission', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        try{
            $paginator = \Wdxr\Models\Services\BranchsCommission::getCommissionListPagintor($parameters, $numberPage);
            $this->view->setVar('page', $paginator->getPaginate());
        }catch (InvalidServiceException $exception) {
            $this->flash->error($exception->getMessage());
        }

    }

    /**
     * 修改业绩提成设置
     */
    public function edit_branchs_commissionAction($id)
    {
        $commission = BranchsCommission::getCommissionById($id);
        if (!$commission) {
            $this->flash->error("没有找到该设置");
            return $this->dispatcher->forward([
                'action' => 'branchs_commission'
            ]);
        }
        $form = new BranchsCommissionForm($commission, ['edit' => true]);
        if ($this->request->isPost()) {
            try {
                if($form->isValid($this->request->getPost(), $commission) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new InvalidRepositoryException($message);
                    }
                } else {
                    $data = $this->request->getPost();
                    (new BranchsCommission())->selectEditAmout($id,$data['level'],$data["amount"]);
                    if (!$commission->save()) {
                        foreach ($commission->getMessages() as $message) {
                            throw new InvalidRepositoryException($message);
                        }
                    }
                    $this->flash->success('修改提成设置成功');
                }
            } catch (InvalidRepositoryException $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $this->view->setVar('id', $id);
        $this->view->setVar('form', $form);
    }

    /**
     * 修改分公司业绩比率
     */
    public function edit_branchs_commission_listAction($id)
    {
        $CommissionList = BranchsCommissionList::getCommissionListByBranchsId($id);
        if (!$CommissionList) {
            $this->flash->error("没有找到信息");
            return $this->dispatcher->forward([
                'controller'=>'branchs',
                'action' => 'index'
            ]);
        }
        if ($this->request->isPost()) {
            if(!$this->request->getPost('ratio')){
                $this->flash->error("请输入提成比率");
                return $this->dispatcher->forward([
                    'controller'=>'branchs',
                    'action' => 'index'
                ]);
            }
            try {
                $CommissionList->setRatio($this->request->getPost('ratio'));
                if (!$CommissionList->save()) {
                    foreach ($CommissionList->getMessages() as $message) {
                        throw new InvalidRepositoryException($message);
                    }
                }
                $this->flash->success('修改成功');
            } catch (InvalidRepositoryException $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $this->view->setVar('id', $id);
        $this->view->setVar('data', $CommissionList);
    }


    public function delete_branchs_commissionAction()
    {
        $this->view->disable();
        if(!$this->request->isPost()) {
            $this->flash->error('非法访问');
            return $this->dispatcher->forward([
                'action' => 'branchs_commission'
            ]);
        }

        try {
            BranchsCommission::deleteCommission($this->request->getPost('id'));
            $this->flash->success("删除成功");
            return $this->dispatcher->forward([
                'action' => 'branchs_commission'
            ]);
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
            return $this->dispatcher->forward([
                'action' => 'branchs_commission'
            ]);
        }
    }



}