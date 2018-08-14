<?php
namespace Wdxr\Modules\Admin\Controllers;

use Phalcon\Exception;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\CompanyPayment;
use Wdxr\Models\Repositories\CompanyService;
use Wdxr\Models\Repositories\CompanyVerify;
use Wdxr\Models\Repositories\Repositories;
use Wdxr\Models\Repositories\LoansInfo;
use Wdxr\Models\Repositories\Loan as RepoLoan;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Phalcon\Mvc\Model\Criteria;
use Wdxr\Models\Services\PushService;
use Wdxr\Models\Services\Excel;
use Wdxr\Models\Services\Loan;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Modules\Admin\Forms\LoanForm;
use Wdxr\Modules\Admin\Forms\PresentationForm;

class LoanController extends ControllerBase
{

    //普惠审核首页
    public function indexAction()
    {
        $hidden = $this->request->getQuery('hidden', 'int', 0) ? 1 : 0;
        $status = $this->request->getQuery('status', 'int');
        $name = $this->request->getQuery('name', 'trim');

        $parameters = ['verify.is_hidden = :hidden:', ['hidden' => $hidden]];
        $name_condition = $name ? " and (company.name like '%".$name."%' or info.name like '%".$name."%')" : '';
        $status_condition = $status ? " and verify.status = ".$status : '';
        $parameters[0] = $parameters[0].$name_condition.$status_condition;

        //获取所有普惠申请分页信息
        $numberPage = $this->request->getQuery("page", "int", 1);
        $paginator = Loan::getLoanListPagintor($parameters, $numberPage);

        $this->view->setVar('status_names', CompanyVerify::getVerifyStatusName());
        $this->view->setVar('name', $this->request->get('name'));
        $this->view->setVar('page', $paginator->getPaginate());
        $this->view->setVar('hidden', $hidden);
    }


    //企业待审核列表
    public function auditingAction()
    {
        //传递搜索条件
        $parameters = null;
        if($this->request->get('name')) {
            $parameters = $this->request->get('name');
        }
        $numberPage = $this->request->getQuery("page", "int") ? : 1;
        //获取普惠待审核信息分页列表
        $paginator = Loan::getUnLoanListPagintor($parameters, $numberPage);
        $this->view->setVar('page', $paginator->getPaginate());
        $this->view->setVar('name', $this->request->get('name'));
    }

    //execl导出申请表
    public function exportApplyAction()
    {
        $this->view->disable();
        if($this->request->getPost("data_id")) {
            try {
                //获取待导出信息
                $data = Loan::export($this->request->getPost("data_id"));
                if (!$data) {
                    throw new Exception("找不到您要导出的信息");
                }
                //导出普惠申请表
               Excel::loanApply($data);

            } catch (Exception $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
    }

    //execl导出调查报告
    public function exportPresentationAction()
    {
        $this->view->disable();
        if ($this->request->getPost("data_id")) {
            try {
                //获取待导出信息
                $data = Loan::export($this->request->getPost("data_id"));
                if (!$data) {
                    throw new Exception("找不到您要导出的信息");
                }
                //导出普惠调查报告
                Excel::loanPresentation($data);
            } catch (Exception $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
    }

    //银行审核信息
    public function bankLoanAction()
    {
        $this->view->disable();
        if (!$this->request->isAjax()) {
            return $this->response->redirect('admin/loan/index');
        }
        try {
            $verify_id = $this->request->getPost('verify_id');
            $verify_status = $this->request->getPost('status');

            $bank = [];
            $bank['bankcard_photo'] = $this->request->getPost('card');
            $bank['voucher'] = $voucher = $this->request->getPost('voucher');
            $bank['account']=$this->request->getPost("bank_account");
            $bank['bankcard']=$this->request->getPost("bankcard");
            $bank['address'] = $this->request->getPost("address");

            $loan_id = $this->request->getPost('loan_id');

            $this->db->begin();

            if ($verify_status == CompanyVerify::STATUS_LOAN_OK) {
                if (empty($this->request->getPost("bank_account")) || empty($this->request->getPost("bankcard"))) {
                    throw new Exception('银行卡信息不能为空');
                }
            } elseif ($verify_status == CompanyVerify::STATUS_LOAN_FAIL) {
                if (empty($this->request->getPost('remark'))) {
                    throw new Exception('关闭该普惠申请的原因不能为空');
                }
            }

            /**
             * 更新普惠信息
             * @var $repo_loan \Wdxr\Models\Repositories\Loan
             */
            $repo_loan = Repositories::getRepository('Loan');
            $loan = $repo_loan->getById($loan_id);
            $repo_loan->setLoanStatus($loan, $verify_status);

            //审核信息
            CompanyVerify::verifyCompany(
                $this->request->getPost('verify_id'),
                $this->session->get("auth-identity")['id'],
                $verify_status,
                $this->request->getPost('remark', 'string', '')
            );

            /**
             * 缴费信息
             * @var $company_payment CompanyPayment
             */
            $company_payment = Repositories::getRepository('CompanyPayment');
            if (($payment = $company_payment->getPaymentById2($loan->getPaymentId())) === false) {
                throw new Exception('获取企业缴费信息失败');
            }
            $company_payment->setPaymentStatus($payment, $verify_status, $voucher);

            if ($verify_status == CompanyVerify::STATUS_LOAN_OK) {
                $company = Company::getCompanyById($loan->getCompanyId());
                //企业信息
                Company::enableCompany($company->getId());
                //生效普惠信息
                Loan::enableLoan($company, $payment, $bank);
            }

            $this->db->commit();
        } catch (Exception $exception) {
            $error_message  = $exception->getMessage() ? : "企业申请审核失败!";
            return $this->response->setJsonContent(['status' => 0, 'info' => $error_message]);
        }

        $this->logger_operation_set('审核企业普惠银行回单', 'Loan', 'bankLoan', $verify_id);
        return $this->response->setJsonContent(['status' => 1]);
    }

    public function uploadAction()
    {
        $device_id = CompanyVerify::getCompanyVerifyById($_REQUEST['id'])->getDeviceId();
        $files = (new ToolsController())->upload('payment', $device_id);
        if($files){
            return $this->response->setJsonContent(['status' => 1,'info' => implode(',', $files)]);
        }else{
            return $this->response->setJsonContent(['status' => 0,'info' => '上传失败']);
        }
    }


    //普惠审核
    public function editAction($id)
    {
        try {
            //获取普惠信息
            $info = Loan::getInfo($id);
            $form=new PresentationForm();
            $this->view->setVar('form', $form);
            $this->view->setVar("info", $info);

            return true;
        } catch (InvalidServiceException $exception) {
            $this->flash->error($exception->getMessage());
            return $this->response->redirect('admin/loan/index');
        }
    }

    public function editAuditAction()
    {
        $this->view->disable();
        if (!$this->request->isAjax()) {
            return $this->response->setJsonContent(['status' => 0, 'info' => '非法请求']);
        }

        $data = $this->request->getPost();
        $verify_status = $this->request->getPost('status');
        $info_id = $this->request->getPost('data_id');
        $loan_id = $this->request->getPost('u_id');

        try {
            $this->db->begin();
            $form = new PresentationForm(null, ['status' => $verify_status]);
            if ($form->isValid($data) == false) {
                $message = isset($form->getMessages()[0]) ? $form->getMessages()[0] : '普惠信息错误';
                throw new Exception($message);
            }

            $loan = \Wdxr\Models\Repositories\Loan::getLoanById($loan_id);
            $company = Company::getCompanyById($loan->getCompanyId());

            if ($company->getAuditing() != Company::AUDIT_OK) {
                throw new Exception('请先审核该企业的工商信息');
            }

            //审核记录
            CompanyVerify::verifyCompany(
                $this->request->getPost('id'),
                $this->session->get("auth-identity")['id'],
                $verify_status,
                $this->request->getPost('remark', 'string', '')
            );

            if ($verify_status == CompanyVerify::STATUS_OK) {
                Loan::doAgreeLoan($company, $loan, $info_id, $data);
            }
            if ($verify_status == CompanyVerify::STATUS_FAIL) {
                $data["state"] = Loan::STATUS_REJECT;
                Loan::editLoanInfo($info_id, $data);
                if (\Wdxr\Models\Repositories\Loan::Presentation($loan->getId(), $data) === false) {
                    throw new InvalidServiceException("普惠审核信息提交失败");
                }
            }

            $this->db->commit();

            //审核结果通知用户（推送消息/短信/首页消息）
            (new PushService())->noticeVerify(
                $company->getDeviceId(),
                $company->getId(),
                $verify_status,
                CompanyService::TYPE_ORDINARY
            );
        } catch (Exception $exception) {
            $error_message  = $exception->getMessage() ? : "企业普惠审核失败!";
            return $this->response->setJsonContent(['status' => 0, 'info' => $error_message]);
        }

        $this->logger_operation_set('审核企业普惠信息', 'Loan', 'editAudit', $loan_id);
        return $this->response->setJsonContent(['status' => 1]);
    }

    public function edit_listAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\LoansInfo', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        $paginator = Loan::getLoanVerifyListInfo($parameters, $numberPage);
        $this->view->setVar('page', $paginator);
    }

    public function edit_infoAction($id)
    {
        try{
            $verify=CompanyVerify::getCompanyVerifyById($id);
            if(!$verify){
                throw new Exception("查询的当前信息不存在");
            }
            $payment = (new CompanyPayment())->getRPaymentByCompanyIdStatus($verify->getCompanyId());
            $loan_state = \Wdxr\Models\Repositories\Loan::getByCompanyIdStatus($verify->getCompanyId());
            if($payment !== false && $loan_state !== false){
                throw new Exception("当前企业已有缴费申请待审核");
            }
            $info=LoansInfo::getLoanInfoById($verify->getDataId());
            if($info === false){
                throw new Exception("查询的当前普惠信息不存在");
            }
            $loan=(new RepoLoan())->getById($info->getUId());
            if($loan === false){
                throw new Exception("查询的当前普惠信息不存在");
            }
            $admin = Admin::getAdminById($info->getUserId());
            if($admin === false){
                throw new Exception("查询的当前管理员信息不存在");
            }
            $company = (new Company())->Byid($verify->getCompanyId());
            if($company === false){
                throw new Exception("查询的当前企业信息不存在");
            }
            $form = new LoanForm($info,['edit'=>true]);
            $this->view->setVars(['form'=>$form,'verify'=>$verify,'loan'=>$loan,'admin'=>$admin,'company'=>$company]);
            if($this->request->isPost()) {
                $data = $this->request->getPost();
                $data['admin_id'] =$admin->getId();
                $data['tel'] =$loan->getTel();
                if($form->isValid($data) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new Exception($message);
                    }
                } else {
                    $SerLoan = new Loan();
                    $this->db->begin();
                    $SerLoan->editInfo($verify->getCompanyId(),$data);
                    $this->db->commit();
                    $this->flash->success('普惠补录成功');
                    $this->dispatcher->forward([
                        'controller' => "loan",
                        'action' => 'index'
                    ]);
                }

            }
        }catch (InvalidRepositoryException $exception) {
            $this->db->rollback();
            $this->flash->error($exception->getMessage());
            $this->response->redirect('admin/loan/edit_list');
        }catch (InvalidServiceException $exception) {
            $this->db->rollback();
            $this->flash->error($exception->getMessage());
            $this->response->redirect('admin/loan/edit_list');
        }catch (Exception $exception) {
            $this->flash->error($exception->getMessage());
            $this->response->redirect('admin/loan/edit_list');
        }

    }


//后台建立新的普惠申请
    public function newAction($id)
    {
//        $this->view->disable();
        $form=new LoanForm();
        try{
            $payment = (new CompanyPayment())->getRPaymentByCompanyIdStatus($id);
            $loan_state = \Wdxr\Models\Repositories\Loan::getByCompanyIdStatus($id);
            if($payment !== false && $loan_state !== false){
                throw new Exception("当前企业已有缴费申请待审核");
            }
            $company = (new Company())->Byid($id);
            if($company === false){
                throw new Exception("查询不到企业信息");
            }
            $info = (new \Wdxr\Models\Repositories\CompanyInfo)->getCompanyInfo($company->getInfoId());
            if($info === false){
                throw new Exception("查询不到企业信息");
            }
            $data['company_name'] = $company->getName();
            $data['licence_num'] =$info->getLicenceNum();
            $this->view->setVar('form', $form);
            $this->view->setVar('data', $data);
            $this->view->setVar('id', $id);
            if($this->request->isPost()) {
                $loan = new Loan();
                $loan_info = $this->request->getPost();
                $loan_info['licence'] = $data['licence_num'];
                $loan_info['company_id'] = $id;
                if($form->isValid($loan_info) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new Exception($message);
                    }
                } else {
                    $this->db->begin();
                    $loan->addLoan($loan_info);
                    $this->db->commit();
                    $this->flash->success('新建普惠申请成功');
                    $this->dispatcher->forward([
                        'controller' => "loan",
                        'action' => 'index'
                    ]);
                }
            }

        } catch (InvalidRepositoryException $exception) {
            $this->db->rollback();
            $this->flash->error($exception->getMessage());
        }catch (Exception $exception) {
            $this->flash->error($exception->getMessage());
            $this->dispatcher->forward([
                'controller' => "companys",
                'action' => 'new_list'
            ]);
        }
    }
}