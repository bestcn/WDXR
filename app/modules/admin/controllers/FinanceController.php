<?php
namespace Wdxr\Modules\Admin\Controllers;

use Lcobucci\JWT\JWT;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Exception;
use Wdxr\Models\Entities\Companys;
use Wdxr\Models\Repositories\Account;
use Wdxr\Models\Repositories\Achievement;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Models\Repositories\Area;
use Wdxr\Models\Repositories\Attachment;
use Wdxr\Models\Repositories\BillTerm;
use Wdxr\Models\Repositories\BonusList;
use Wdxr\Models\Repositories\BonusSystem;
use Wdxr\Models\Repositories\Branch;
use Wdxr\Models\Repositories\Citie;
use Wdxr\Models\Repositories\CommissionList;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\CompanyBank;
use Wdxr\Models\Repositories\CompanyBill;
use Wdxr\Models\Repositories\CompanyInfo;
use Wdxr\Models\Repositories\CompanyPayment;
use Wdxr\Models\Repositories\CompanyRecommend;
use Wdxr\Models\Repositories\CompanyReport;
use Wdxr\Models\Repositories\CompanyService;
use Wdxr\Models\Repositories\CompanyVerify;
use Wdxr\Models\Repositories\Contract;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Repositories\Finance;
use Wdxr\Models\Repositories\Level;
use Wdxr\Models\Repositories\Manage;
use Wdxr\Models\Repositories\Messages;
use Wdxr\Models\Repositories\Province;
use Wdxr\Models\Repositories\Recommend;
use Wdxr\Models\Repositories\Regions;
use Wdxr\Models\Repositories\ReportTerm;
use Wdxr\Models\Repositories\Repositories;
use Wdxr\Models\Repositories\Rterm;
use Wdxr\Models\Repositories\Salesman;
use Wdxr\Models\Repositories\Statistics;
use Wdxr\Models\Repositories\Temp;
use Wdxr\Models\Repositories\Term;
use Wdxr\Models\Repositories\User;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Services\Cos;
use Wdxr\Models\Services\Excel;
use Wdxr\Models\Services\SMS;
use Wdxr\Models\Services\PushService;

class FinanceController extends ControllerBase
{

    public function indexAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Finances', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        //传递搜索条件
        $this->view->setVar('company_id', $_REQUEST['company_id']);

        if($_GET['company_id']){
            $data['company_id'] = $_GET['company_id'];
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Finances', $data);
            $numberPage = $_GET['page'];
            $parameters = $query->getParams();
        }

        $paginator = \Wdxr\Models\Services\Finance::getFinanceListPagintor($parameters, $numberPage);

        //获取所有企业账户
        $account = new Account();
        $account_data = $account->getLast();
        $this->view->setVar('account_data', $account_data);
        $this->view->setVar('page', $paginator->getPaginate());
    }

    public function exportAction($type = null)
    {
        if($type == 'recommend'){
            $finance = new Recommend();
            $filename = '推荐报表';
        } elseif ($type == 'manage'){
            $finance = new Manage();
            $filename = '管理报表';
        } else {
            $finance = new Finance();
            $filename = '垫付报表';
        }


            if($this->request->getPost('start_time') && $this->request->getPost('end_time')) {
                $start_time = strtotime($this->request->getPost('start_time')); $end_time = strtotime($this->request->getPost('end_time'))+86400;
                $data = $finance->getExportBetweenList(date('Y-m-d',$start_time), date('Y-m-d',$end_time))->toArray();
                $filename = $this->request->getPost('start_time') .'至'.$this->request->getPost('end_time').$filename;
            } else {
                $data = $finance->getExportList()->toArray();
                $filename = '至'.date('Y-m-d',time()).$filename;
            }

        //计算总计
        $push_array['company_id'] = '总计';
        $push_array['makecoll'] = $filename.'总额';
        $push_array['bank_name'] = '';

        $money = 0;
        if($data){
            foreach($data as $key=>$val){
                $data[$key]['status'] = \Wdxr\Models\Services\Company::getMainStatusName($val['status']);
                $money += $val['money'];
            }
        }

        $push_array['money'] = $money;
        $push_array['phone'] = '';
        $push_array['start_time'] = '';
        $push_array['end_time'] = '';
        $push_array['day_count'] = '';
        $push_array['remark'] = $filename.'总额';
        $push_array['status'] = '';
        $push_array['info'] = '';
        array_push($data,$push_array);

            Excel::create()->title($filename)->header(['收款账户名称','收款账号','开户行','金额','电话','起始时间','截止日期','已报天数', '摘要用途','状态','备注'])
                ->value($data)->sheetTitle($filename)->output($filename);

/*
        if($this->request->getPost('start_time') && $this->request->getPost('end_time')) {
            $start_time = strtotime($this->request->getPost('start_time')); $end_time = strtotime($this->request->getPost('end_time'))+86400;
            $data = $finance->getExportBetweenList($start_time, $end_time)->toArray();
            $filename = $this->request->getPost('start_time') .'至'.$this->request->getPost('end_time').$filename;
        } else {
            $data = $finance->getExportList()->toArray();
            $filename = '至'.date('Y-m-d',time()).$filename;
        }
        Excel::create()->header(['', '收款账号', '收款账户名称', '金额', '摘要用途'])
            ->value($data)->sheetTitle($filename)->output($filename);

*/

    }

    public function recommendAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Recommends', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        //传递搜索条件
        $this->view->setVar('company_id', $_REQUEST['company_id']);

        if($_GET['company_id']){
            $data['company_id'] = $_GET['company_id'];
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Recommends', $data);
            $numberPage = $_GET['page'];
            $parameters = $query->getParams();
        }

        $paginator = \Wdxr\Models\Services\Recommend::getRecommendListPagintor($parameters, $numberPage);
        //获取所有企业账户
        $account = new Account();
        $account_data = $account->getLast();
        $this->view->setVar('account_data', $account_data);
        $this->view->setVar('page', $paginator->getPaginate());
    }


    //缴费审核
    public function paymentAction()
    {
        $numberPage = $this->request->getQuery("page", "int") ? : 1;
        $hidden = $this->request->getQuery('hidden', 'int', 0) ? 1 : 0;
        $parameters = ['verify.is_hidden = :hidden:', ['hidden' => $hidden]];
        if ($name = $this->request->getQuery('name', 'trim')) {
            $parameters = [
                "verify.is_hidden = :hidden: and (company.name like '%".$name."%' or company_info.legal_name like '%".$name."%')",
                ['hidden' => $hidden]
            ];
        }

        $payment = \Wdxr\Models\Services\CompanyVerify::getCompanyVerify(
            CompanyVerify::TYPE_PAYMENT,
            $numberPage,
            $parameters
        );
        $this->view->setVar('page', $payment->getPaginate());
        $this->view->setVar('hidden', $hidden);
    }

    public function edit_paymentAction($verify_id)
    {
        $payments = \Wdxr\Models\Services\CompanyVerify::getPaymentVerifyInfo($verify_id);
        $company_id = $payments[0]->company_id;
        $company_data = Company::getCompanyById($company_id);

        if ($company_data->getAuditing() != Company::AUDIT_OK) {
            $this->flash->error("请先审核通过企业申请审核");
            return $this->response->redirect("admin/finance/payment");
        }
        if ($payments) {
            $payment = $payments[0];
            $data['company_id'] = $company_data->getId();
            $data['type'] = CompanyPayment::getTypeName($payment->payment->getType());
            $data['name'] = $payment->company_name;
            //获取对应图片信息
            $data['voucher'] = \Wdxr\Models\Services\UploadService::getAttachmentsUrl(
                Attachment::getAttachmentById(explode(',', $payment->payment->voucher))->toArray()
            );
            $data['amount'] = $payment->payment->getAmount();
            $data['time'] = $payment->apply_time;
            $data['status'] = $payment->payment->status;
            $data['admin'] = $payment->admin;
            $data['partner'] = $payment->partner;
            $data['id'] = $payment->payment->getId();
            $data['verify_id'] = $payment->verify_id;
            $this->view->setVar('payment_data', $data);
            //银行卡信息
            $company_bank = (new CompanyBank())->getBankcard($payments[0]->company_id, CompanyBank::CATEGORY_MASTER);
            if ($company_bank) {
                $bank['bank_type'] = $company_bank->getBankType();
                $bank['bank'] = $company_bank->getBank();
                $bank['account'] = $company_bank->getAccount();
                $bank['address'] = $company_bank->getAddress();
                $bank['number'] = $company_bank->getNumber();
                $bank['province'] = Regions::getRegionName($company_bank->getProvince())->name;
                $bank['city'] = Regions::getRegionName($company_bank->getCity())->name;
                $bank['bankcard_photo'] = Attachment::getAttachmentUrl($company_bank->getBankcardPhoto());
            } else {
                $bank['number'] = false;
            }
            //绩效银行卡信息
            $work_company_bank = (new CompanyBank())->getBankcard($payments[0]->company_id, CompanyBank::CATEGORY_WORK);
            if ($work_company_bank) {
                $bank['work_bank'] = $work_company_bank->getBank();
                $bank['work_account'] = $work_company_bank->getAccount();
                $bank['work_address'] = $work_company_bank->getAddress();
                $bank['work_number'] = $work_company_bank->getNumber();
                $bank['work_province'] = Regions::getRegionName($company_bank->getProvince())->name;
                $bank['work_city'] = Regions::getRegionName($company_bank->getCity())->name;
                $bank['work_bankcard_photo'] = Attachment::getAttachmentUrl($work_company_bank->getBankcardPhoto());
            } else {
                $bank['work_number'] = false;
            }
            $this->view->setVar('bank', $bank);
            return true;
        } else {
            $this->flash->error("没有找到缴费信息");
            return $this->response->redirect("admin/finance/payment");
        }
    }

    public function save_paymentAction()
    {
        $this->view->disable();
        if (!$this->request->isAjax()) {
            return $this->response->setJsonContent(['status' => 0, 'info' => '非法请求']);
        }

        $payment_id = $this->request->getPost('payment_id');
        $verify_status = $this->request->getPost('status');

        try {
            $this->db->begin();

            //审核记录
            CompanyVerify::verifyCompany(
                $this->request->getPost('verify_id'),
                $this->session->get("auth-identity")['id'],
                $verify_status,
                $this->request->getPost('remark', 'string', '')
            );

            /**
             * @var $company_payment CompanyPayment
             */
            $company_payment = Repositories::getRepository('CompanyPayment');
            if (($payment = $company_payment->getPaymentById2($payment_id)) === false) {
                throw new Exception('获取企业缴费信息失败');
            }
            $company_payment->setPaymentStatus($payment, $verify_status);

            $company = Company::getCompanyById($payment->getCompanyId());
            if ($verify_status == CompanyVerify::STATUS_OK) {
                $company_payment->doAgreePayment($payment, $company);
            }

            $this->db->commit();

            //审核结果通知用户（推送消息/短信/首页消息）
            (new PushService())->noticeVerify(
                $company->getDeviceId(),
                $company->getId(),
                $verify_status,
                CompanyService::TYPE_PARTNER
            );
        } catch (Exception $e) {
            $this->db->rollback();
            return $this->response->setJsonContent(['status' => 0, 'info' => $e->getMessage()]);
        }
        //操作日志
        $this->logger_operation_set('审核企业缴费信息', 'Finance', 'save_payment', $company->getId());
        return $this->response->setJsonContent(['status' => 1]);
    }

    //设置业绩信息
    public function setAchievementAction(\Wdxr\Models\Entities\CompanyService $company_service, $payment_id)
    {
        //企业信息
        $company_data = (new Company())->getById($company_service->getCompanyId());
        $company_payment_data = CompanyPayment::getPaymentById($payment_id);

            //业绩
            $ach_data = array();
            $contract = (new Contract)->getServiceContract($company_service->getId());
            if($contract === false) {
                throw new InvalidRepositoryException('获取合同信息失败');
            }
            $ach_data['contract_num'] = $contract->getContractNum();//合同编号
            $admin_data = Admin::getAdminById($company_data->getAdminId());
            $ach_data['admin_name'] = $admin_data->getName();//业务员名字
            $ach_data['admin_id'] = $company_data->getAdminId();
            $sales = new Salesman();
            $sales_data = $sales->getSalesmanByAdminId($admin_data->getId());
            if ($sales_data == false) {
                $branch_id = 0;
            } else {
                $branch_id = $sales_data->getBranchId();
            }
            $ach_data['branch_id'] = $branch_id;//分站ID
            $level = new Level();
            $level_data = $level->getLevelById($company_service->getLevelId());
            $ach_data['money'] = $level_data->getLevelMoney();//金额
            $ach_data['time'] = time();
            $ach_data['company_name'] = $company_data->getName();
//            //合伙人推荐奖金
//            if ($company_payment_data->getType() != CompanyPayment::TYPE_LOAN) {
//                $bonus = $level_data->getLevelMoney() * 0.05;
//            } else {
//                $bonus = $level_data->getLevelMoney() * 0.05 / 2;
//            }
            //推荐人
            if (!empty($company_data->getRecommendId())) {
                //如果有推荐企业,查看企业的缴费类型
                $company_payment = new CompanyPayment();
                $Recommend_payment_data = $company_payment->getPaymentByCompanyId($company_data->getRecommendId(), CompanyPayment::STATUS_OK);
                //推荐企业的公司信息
                $Recommend_company_data = (new Company)->getById($company_data->getRecommendId());
//                $Recommend_company_info_data = (new CompanyInfo())->getCompanyinfoByCompanyId($company_data->getRecommendId());
                if ($Recommend_payment_data->getType() != CompanyPayment::TYPE_LOAN) {
                    $ach_data['recommender'] = $Recommend_company_data->getName()."(合伙人)";
                    //获取合伙人奖金
                    $bonus = (new CommissionList())->getRatio($company_payment_data->getType(),$level_data->getLevelMoney(),$Recommend_company_data->getUserId(),UserAdmin::TYPE_USER);
                    //添加合伙人奖金
                    $temp = new Temp();
                    $temp->addNew(array('company_name' => $company_data->getRecommendId(), 'money' => $bonus));//改为存企业ID20171013
                } else {
                    $ach_data['recommender'] = $Recommend_company_data->getName()."(普惠)";
                }

                //管理人
                if (!empty($Recommend_company_data->getRecommendId()) && $Recommend_company_data->getRecommendId() == $company_data->getManagerId()) {
                    $R_Recommend_payment_data = $company_payment->getPaymentByCompanyId($Recommend_company_data->getRecommendId(), CompanyPayment::STATUS_OK);
                    if ($R_Recommend_payment_data->getType() != CompanyPayment::TYPE_LOAN && $Recommend_payment_data->getType() != CompanyPayment::TYPE_LOAN) {
                        $R_Recommend_company_data = (new Company)->getById($Recommend_company_data->getRecommendId());
                        $ach_data['administrator'] = $R_Recommend_company_data->getName()."(合伙人)";
                        //获取合伙人奖金
                        $bonus = (new CommissionList())->getRatio($company_payment_data->getType(),$level_data->getLevelMoney(),$R_Recommend_company_data->getUserId(),UserAdmin::TYPE_USER);
                        //添加合伙人奖金
                        $temp = new Temp();
                        $temp->addNew(array('company_name' => $Recommend_company_data->getRecommendId(), 'money' => $bonus));//改为存企业ID20171013
                    } else {
                        $R_Recommend_company_data = (new Company)->getById($Recommend_company_data->getRecommendId());
                        $ach_data['administrator'] = $R_Recommend_company_data->getName()."(普惠)";
                    }
                } else {
                    $ach_data['administrator'] = null;
                    $ach_data['commission'] = (new CommissionList())->getRatio($company_payment_data->getType(),$level_data->getLevelMoney(),$company_data->getAdminId(),UserAdmin::TYPE_ADMIN);
                }
            } else {
                $ach_data['recommender'] = null;
                $ach_data['administrator'] = null;
                $ach_data['commission'] = (new CommissionList())->getRatio($company_payment_data->getType(),$level_data->getLevelMoney(),$company_data->getAdminId(),UserAdmin::TYPE_ADMIN);
            }
                $achievement = new Achievement();
                $achievement->addNew($ach_data);


    }

    //新设置业绩信息
    public function setAchievementNewAction( $payment_id)
    {
        //企业信息
        $company_payment_data = CompanyPayment::getPaymentById($payment_id);
        $company_id = $company_payment_data->getCompanyId();
        $company_data = (new Company())->getById($company_payment_data->getCompanyId());
        //业绩
        $ach_data = array();
//        $contract = (new Contract)->getServiceContract($company_service->getId());
//        if($contract === false){
//            throw new InvalidRepositoryException('获取合同信息失败');
//        }
        $ach_data['contract_num'] = '暂无';//合同编号
        $admin_data = Admin::getAdminById($company_data->getAdminId());
        $ach_data['admin_name'] = $admin_data->getName();//业务员名字
        $ach_data['admin_id'] = $company_data->getAdminId();
        $sales = new Salesman();
        $sales_data = $sales->getSalesmanByAdminId($admin_data->getId());
        if ($sales_data == false) {
            $branch_id = 0;
        } else {
            $branch_id = $sales_data->getBranchId();
        }
        $ach_data['branch_id'] = $branch_id;//分站ID
        $level = new Level();
        $level_data = $level->getLevelById($company_payment_data->getLevelId());
        $ach_data['money'] = $level_data->getLevelMoney();//金额
        $ach_data['time'] = time();
        $ach_data['company_name'] = $company_data->getName();
        $ach_data['company_id'] = $company_id;
//            //合伙人推荐奖金
//            if ($company_payment_data->getType() != CompanyPayment::TYPE_LOAN) {
//                $bonus = $level_data->getLevelMoney() * 0.05;
//            } else {
//                $bonus = $level_data->getLevelMoney() * 0.05 / 2;
//            }
        //推荐人
        if (!empty($company_data->getRecommendId()) && $service = CompanyService::getCompanyService($company_data->getRecommendId())) {
            //如果有推荐企业,查看企业的缴费类型
            $company_payment = new CompanyPayment();
            $Recommend_payment_data = $company_payment->getPaymentByCompanyId($company_data->getRecommendId(), CompanyPayment::STATUS_OK);
            $company_payment_data = $company_payment->getPaymentById2($payment_id);
            //新客户类别
            if ($company_payment_data->getType() != CompanyPayment::TYPE_LOAN) {
                $ach_data['recommender'] = $company_data->getName()."(合伙人)";
                $customer_type = BonusSystem::SHIYE;
            } else {
                $ach_data['recommender'] = $company_data->getName()."(普惠)";
                $customer_type = BonusSystem::PUHUI;
            }
            //推荐企业的公司信息
            $Recommend_company_data = (new Company)->getById($company_data->getRecommendId());

            //推荐人类别
            if ($Recommend_payment_data->getType() != CompanyPayment::TYPE_LOAN) {
                $ach_data['recommender'] = $Recommend_company_data->getName()."(合伙人)";
                $recommend_type = BonusSystem::SHIYE;
            } else {
                $ach_data['recommender'] = $Recommend_company_data->getName()."(普惠)";
                $recommend_type = BonusSystem::PUHUI;
            }
            $count = Company::getCountByCompanyId($company_data->getRecommendId());
            $ach_data['bonus'] = BonusSystem::getBonusByType($recommend_type,$customer_type,$count);

        } else {
            $ach_data['bonus'] = 0;
            $ach_data['recommender'] = '';
            $ach_data['commission'] = (new CommissionList())->getRatio($company_payment_data->getType(),$level_data->getLevelMoney(),$company_data->getAdminId(),UserAdmin::TYPE_ADMIN);
        }
        $achievement = new BonusList();
        $achievement->addNew($ach_data);

    }

    //设置服务时间
    public function setServiceAction($company_id)
    {
        //企业信息
        $company = (new Company())->getById($company_id);
        $company_info_data = (new CompanyInfo())->getCompanyInfo($company->getInfoId());
        $company_bank = (new CompanyBank())->getBankcard($company_id,CompanyBank::CATEGORY_MASTER);
        $service_data = CompanyService::getCompanyService($company_id);//服务期限
        $company_payment_data = CompanyPayment::getPaymentByCompanyId($company_id);
            //增加企业服务时间
            $period = $this->setServiceTime($company_id);
            if ($period == false) {
                throw new Exception('企业服务期限设置失败');
            }
//            $service_data = CompanyService::getCompanyService($company_id);
            //添加票据征信审核期限列表
            $payment_type = $company_payment_data->getType();//缴费类型
            //票据
            $this->setBillTime($payment_type, $company_id, $company->getName(), $service_data->getStartTime());
            //征信
            $this->setReportTime($payment_type, $company_id, $company->getName(), $service_data->getStartTime());
            //添加合伙人提成比率
            if($company_bank->getBankType() == CompanyInfo::BANK_TYPE_PUBLIC){
                $comm['name'] = $company->getName();
            }else{
                $comm['name'] = $company_info_data->getLegalName();
            }
            $comm['ratio'] = 0.05;
            $comm['status'] = 0;
            $comm['type'] = UserAdmin::TYPE_USER;
            $user_admin = (new UserAdmin())->getDevice($company->getUserId(),UserAdmin::TYPE_USER);
            $comm['device_id'] = $user_admin->getId();
            (new CommissionList())->addNew($comm);

            //服务期限通知
//            Mns::periodSMS((int)$company_info_data->getContactPhone(),$company_info_data->getContacts(),date('Y年m月d日',$period));
    }

    private function setServiceTime($id)
    {
        //服务开始时间
        $time = strtotime(date('Y-m-d',strtotime('+1 day')));
        //服务结束时间
        $Year = strtotime('+365 days',$time)-1;
        $service = new \Wdxr\Models\Entities\CompanyService();
        $service->setCompanyId($id);
        $service->setStartTime($time);
        $service->setEndTime($Year);
        if($service->save()){
            return $Year;
        }else{
            return false;
        }
    }

    private function setBillTime($payment_type,$company_id,$company_name,$service_start_time)
    {
        //票据
        $term = new Term();
        $term_data = $term->getTermByPayment($payment_type);//期限数据

        if($term_data != false){
            $array = array();
            $array['company_name'] = $company_name;//公司名称
            $array['company_id'] = $company_id;
            $array['payment'] = $payment_type;
            $array['term'] = $term_data->getTerm();
            $array['type'] = $term_data->getType();
            $array['time'] = $service_start_time;
            $bill_term = new BillTerm();
            $bill_term->addNew($array);
        }else{
            $array = array();
            $array['company_name'] = $company_name;//公司名称
            $array['company_id'] = $company_id;
            $array['payment'] = $payment_type;
            $array['term'] = SettingController::DATE_DEFAULT;
            $array['type'] = SettingController::DATE_TYPE_MONTH;
            $array['time'] = $service_start_time;
            $bill_term = new BillTerm();
            $bill_term->addNew($array);
        }
        //票据截止时间
        $bill_end_time = \Wdxr\Models\Services\Company::BillEndTime($array['term'],$array['type'], $service_start_time);

        //设置票据截止日期
        $bill_data = CompanyBill::getCurrentCompanyBill($company_id);
        $bill_data->setEndTime($bill_end_time);
        if ($bill_data->save() == false) {
            throw new Exception('企业票据审核设置失败');
        }
    }

    private function setReportTime($payment_type,$company_id,$company_name,$service_start_time,$report_id = 0)
    {
        $term = new Rterm();
        $term_data = $term->getTermByPayment($payment_type);//期限数据
        if($term_data != false){
            $array = array();
            $array['company_name'] = $company_name;//公司名称
            $array['company_id'] = $company_id;
            $array['payment'] = $payment_type;
            $array['term'] = $term_data->getTerm();
            $array['type'] = $term_data->getType();
            $array['time'] = $service_start_time;
            $report_term = new ReportTerm();
            $report_term->addNew($array);
        }else{
            $array = array();
            $array['company_name'] = $company_name;//公司名称
            $array['company_id'] = $company_id;
            $array['payment'] = $payment_type;
            $array['term'] = SettingController::DATE_DEFAULT;
            $array['type'] = SettingController::DATE_TYPE_MONTH;
            $array['time'] = $service_start_time;
            $report_term = new ReportTerm();
            $report_term->addNew($array);
        }

        //征信截止时间
        $report_end_time = CompanyReport::setReportEndTime($array['term'],$array['type'], $service_start_time);

        //设置征信截止日期**dh20170909修改*不生成新的征信信息*修改已有的征信信息
        /*$report = CompanyReport::getCompanyReportById($report_id);
        $report->setEndTime($report_end_time);
        if ($report->save() === false) {
            $error = $report->getMessages()[0] ? : "企业征信报告审核设置失败";
            throw new Exception($error);
        }*/
    }


    //管理费
    public function manageAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Manages', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        //传递搜索条件
        $this->view->setVar('company_id', $_REQUEST['company_id']);

        if($_GET['company_id']){
            $data['company_id'] = $_GET['company_id'];
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Manages', $data);
            $numberPage = $_GET['page'];
            $parameters = $query->getParams();
        }

        $paginator = \Wdxr\Models\Services\Manage::getManageListPagintor($parameters, $numberPage);
        //获取所有企业账户
        $account = new Account();
        $account_data = $account->getLast();
        $this->view->setVar('account_data', $account_data);
        $this->view->setVar('page', $paginator->getPaginate());
    }


    //导出今天的报表
    public function today_exportAction()
    {
        $type = $this->request->getPost('type');
        if($type == 'recommend'){
            $finance = new Recommend();
            $filename = '推荐费财务报表';
        } elseif ($type == 'manage'){
            $finance = new Manage();
            $filename = '管理报表';
        } else {
            $finance = new Finance();
            $filename = '垫付报表';
        }
            $account = $this->request->getPost('account');
        //识别报表种类
        switch ($account){
            case 0 :
                $file_type = '全部';
                break;
            case 3 :
                $file_type = '合伙人';
                break;
            case 4 :
                $file_type = '普惠';
                break;
            default :
                $file_type = '全部';
                break;
        }
            $start_time = mktime(0, 0 ,0 , date('m'), date('d'), date('Y'));
            $end_time = $start_time + 86400;
            $data = $finance->getExportBetweenList(date('Y-m-d H:i:s',$start_time), date('Y-m-d H:i:s',$end_time),$account)->toArray();
            $filename = date('Y-m-d').$file_type.$filename;
            //计算总计
            $push_array['company_id'] = '总计';
            $push_array['makecoll'] = $filename.'总额';
            $push_array['bank_name'] = '';

            $money = 0;
            if($data){
                foreach($data as $key=>$val){
                    $data[$key]['status'] = \Wdxr\Models\Services\Company::getMainStatusName($val['status']);
                    $money += $val['money'];
                }
            }

            $push_array['money'] = $money;
            $push_array['phone'] = '';
            $push_array['start_time'] = '';
            $push_array['end_time'] = '';
            $push_array['day_count'] = '';
            $push_array['remark'] = $filename.'总额';
            array_push($data,$push_array);
        Excel::create()->title($filename)->header(['收款账户名称','收款账号','开户行','金额','电话','起始时间','结束时间','已报天数', '摘要用途','状态','备注'])
                ->value($data)->sheetTitle($filename)->output($filename);
        /*
        $start_time = mktime(0, 0 ,0 , date('m'), date('d'), date('Y'));
        $end_time = $start_time + 86400;
        $data = $finance->getExportBetweenList($start_time, $end_time)->toArray();
        $filename = date('Y-m-d').$filename;

        Excel::create()->header(['', '收款账号', '收款账户名称', '金额', '摘要用途'])
            ->value($data)->sheetTitle($filename)->output($filename);
        */
    }


    //业务员业绩列表
    public function achievementAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Achievement', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        //传递搜索条件
        $this->view->setVar('admin_name', $this->request->get('admin_name'));

        if( $this->request->get('admin_name')){
            $data['admin_name'] = $this->request->get('admin_name');
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Achievement', $data);
            $numberPage = $this->request->get('page');
            $parameters = $query->getParams();
        }
        $branch_id = $this->subBranchAch() ?: "1=1";//判断是否为分站管理

        $paginator = \Wdxr\Models\Services\Achievement::getAchievementListPagintor($parameters, $numberPage,$branch_id);

        $this->view->setVar('page', $paginator->getPaginate());

    }

    //新奖金列表
    public function bonusAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\BonusList', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        //传递搜索条件
        $this->view->setVar('admin_name', $this->request->get('admin_name'));

        if( $this->request->get('admin_name')){
            $data['admin_name'] = $this->request->get('admin_name');
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\BonusList', $data);
            $numberPage = $this->request->get('page');
            $parameters = $query->getParams();
        }
        $branch_id = $this->subBranchAch() ?: "1=1";//判断是否为分站管理

        $paginator = \Wdxr\Models\Services\BonusList::getBonusListPagintor($parameters, $numberPage,$branch_id);

        $this->view->setVar('page', $paginator->getPaginate());

    }

    //导出业务员业绩列表
    public function achievement_exportAction()
    {
        $branch_id = $this->subBranchAch() ?: '1=1';//判断是否为分站管理

        $achievement = new Achievement();
        $filename = '业务员业绩列表';

        if($this->request->getPost('start_time') && $this->request->getPost('end_time')) {
            $start_time = strtotime($this->request->getPost('start_time')); $end_time = strtotime($this->request->getPost('end_time'))+86400;
            $data = $achievement->getExportBetweenList($start_time, $end_time,$branch_id)->toArray();
            $filename = $this->request->getPost('start_time') .'至'.$this->request->getPost('end_time').$filename;
        } else {
            $data = $achievement->getExportList($branch_id)->toArray();
            $filename = '至'.date('Y-m-d',time()).$filename;
        }
        if($data){
            foreach($data as $key=>$val) {
                $data[$key]['time'] = date('Y-m-d H:i:s',$val['time']);
                $contract = Contract::getContractByNum($val['contract_num']);
                $company = (new Company())->getById($contract->getCompanyId());
                $data[$key]['company_type'] = $company->users->IsPartner ? '合伙人' : '普惠';
            }
        }

        Excel::create()->header(['编号', '业务员', '企业名称', '推荐人', '管理人','合同编号','成交金额','业务员提成','签订时间','客户类型'])
            ->value($data)->sheetTitle($filename)->output($filename);
    }

    //导出奖金列表
    public function bonus_exportAction()
    {
        $branch_id = $this->subBranchAch() ?: '1=1';//判断是否为分站管理


        $achievement = new BonusList();
        $filename = '奖金列表';

        if($this->request->getPost('start_time') && $this->request->getPost('end_time')) {
            $start_time = strtotime($this->request->getPost('start_time')); $end_time = strtotime($this->request->getPost('end_time'))+86400;
            $data = $achievement->getExportBetweenList($start_time, $end_time,$branch_id)->toArray();
            $filename = $this->request->getPost('start_time') .'至'.$this->request->getPost('end_time').$filename;
        } else {
            $data = $achievement->getExportList($branch_id)->toArray();
            $filename = '至'.date('Y-m-d',time()).$filename;
        }
        if($data){
            foreach($data as $key=>$val){
                $data[$key]['time'] = date('Y-m-d H:i:s',$val['time']);
                $company = (new Company())->getById($val['company_id']);
                unset($data[$key]['company_id']);
                $data[$key]['company_type'] = $company->users->IsPartner ? '合伙人' : '普惠';
            }
        }
        Excel::create()->header(['编号', '业务员', '企业名称', '推荐人','推荐奖金','成交金额','业务员提成','签订时间','客户类型'])
            ->value($data)->sheetTitle($filename)->output($filename);
    }

    //导出指定分站的业务员列表
    public function branch_achievement_exportAction($branch_id)
    {

        $achievement = new Achievement();
        $filename = '业务员业绩列表';

        if($this->request->getPost('start_time') && $this->request->getPost('end_time')) {
            $start_time = strtotime($this->request->getPost('start_time')); $end_time = strtotime($this->request->getPost('end_time'))+86400;
            $data = $achievement->getExportBetweenListBybranch($start_time, $end_time,$branch_id)->toArray();
            $filename = $this->request->getPost('start_time') .'至'.$this->request->getPost('end_time').$filename;
        } else {
            $data = $achievement->getExportListBybranch($branch_id)->toArray();
            $filename = '至'.date('Y-m-d',time()).$filename;
        }
        if($data){
            foreach($data as $key=>$val){
                $data[$key]['time'] = date('Y-m-d H:i:s',$val['time']);
                $contract = Contract::getContractByNum($val['contract_num']);
                $company = (new Company())->getById($contract->getCompanyId());
                $data[$key]['company_type'] = $company->users->IsPartner ? '合伙人' : '普惠';
            }
        }
        Excel::create()->header(['编号', '业务员', '企业名称', '推荐人', '管理人','合同编号','成交金额','业务员提成','签订时间','客户类型'])
            ->value($data)->sheetTitle($filename)->output($filename);
    }

    //导出上个月业务员列表
    public function achievement_export_monthAction()
    {
        $branch_id = $this->subBranchAch() ?: '1=1';//判断是否为分站管理

        $achievement = new Achievement();
        $filename = '业务员业绩列表';
        $m = date('Y-m-d', mktime(0,0,0,date('m')-1,1,date('Y')));
        $t = date('t',strtotime($m)); //上个月共多少天

        $start = date('Y-m-d', mktime(0,0,0,date('m')-1,1,date('Y')));//上个月的开始日期
        $end = date('Y-m-d', mktime(0,0,0,date('m')-1,$t+1,date('Y'))); //上个月的结束日期

        $data = $achievement->getExportBetweenList(strtotime($start), strtotime($end),$branch_id)->toArray();
        $filename = date('Y-m').$filename;
        if($data){
            foreach($data as $key=>$val){
                $data[$key]['time'] = date('Y-m-d H:i:s',$val['time']);
                $contract = Contract::getContractByNum($val['contract_num']);
                $company = (new Company())->getById($contract->getCompanyId());
                $data[$key]['company_type'] = $company->users->IsPartner ? '合伙人' : '普惠';
            }
        }
        Excel::create()->header(['编号', '业务员', '企业名称', '推荐人', '管理人','合同编号','成交金额','业务员提成','签订时间','客户类型'])
            ->value($data)->sheetTitle($filename)->output($filename);
    }

    //导出上个月奖金列表
    public function bonus_export_monthAction()
    {
        $branch_id = $this->subBranchAch() ?: '1=1';//判断是否为分站管理

        $achievement = new BonusList();
        $filename = '业务员业绩列表';
        $m = date('Y-m-d', mktime(0,0,0,date('m')-1,1,date('Y')));
        $t = date('t',strtotime($m)); //上个月共多少天

        $start = date('Y-m-d', mktime(0,0,0,date('m')-1,1,date('Y')));//上个月的开始日期
        $end = date('Y-m-d', mktime(0,0,0,date('m')-1,$t+1,date('Y'))); //上个月的结束日期

        $data = $achievement->getExportBetweenList(strtotime($start), strtotime($end),$branch_id)->toArray();
        $filename = date('Y-m').$filename;
        if($data){
            foreach($data as $key=>$val){
                $data[$key]['time'] = date('Y-m-d H:i:s',$val['time']);
                $company = (new Company())->getById($val['company_id']);
                unset($data[$key]['company_id']);
                $data[$key]['company_type'] = $company->users->IsPartner ? '合伙人' : '普惠';
            }
        }
        Excel::create()->header(['编号', '业务员', '企业名称', '推荐人','推荐奖金','成交金额','业务员提成','签订时间','客户类型'])
            ->value($data)->sheetTitle($filename)->output($filename);
    }

//分站业绩列表
    public function branch_achievementAction()
    {
        $branch = new Branch();
        //如果是分站管理员,只能查看所属分站的人员
        if($_SESSION["auth-identity"]['position'] == "分站管理员") {
            $branch_data[0] = $branch->getBranchByAdminId($_SESSION["auth-identity"]['id'])->toArray();//分站信息
        }else{
            $branch_data = $branch->getLast()->toArray();
        }

        $data = array();
        foreach($branch_data as $key=>$val){
            $data[$key]['id'] = $val['id'];
            $data[$key]['branch_name'] = $val['branch_name'];
            $data[$key]['provinces'] = Regions::getRegionName($val['getProvince'])->name;
            $data[$key]['cities'] = Regions::getRegionName($val['cities'])->name;
            $data[$key]['areas'] = Regions::getRegionName($val['areas'])->name;
            $data[$key]['branch_admin'] = $val['branch_admin'];
            $data[$key]['amount'] = Achievement::getBranchAmount($val['id'])?:0;
            $data[$key]['month_amount'] = Achievement::getBranchMonthAmount($val['id'])?:0;
        }
        $this->view->setVar('data', $data);
    }

    //分站业务员列表
    public function branch_achievement_allAction($branch_id)
    {
        $numberPage = 1; $parameters = [];
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Achievement',array("branch_id" => $branch_id));
            $parameters = $query->getParams();

        //传递搜索条件
        $this->view->setVar('branch_id', $branch_id);

        if($_GET['branch_id']){
            $data['branch_id'] = $_GET['branch_id'];
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Achievement', $data);
            $numberPage = $_GET['page'];
            $parameters = $query->getParams();
            $this->view->setVar('branch_id',$_GET['branch_id']);
        }else{
            $this->view->setVar('branch_id',$branch_id);
        }

        $paginator = \Wdxr\Models\Services\Achievement::getAchievementListPagintorOld($parameters, $numberPage);

        $this->view->setVar('page', $paginator->getPaginate());
    }


    //分站业务员业绩统计
    public function branch_admin_achievementAction($branch_id)
    {
        $achievement =  new Achievement();
        $achievement_data = $achievement->getBranchAdminAchievement($branch_id);
        $achievement_data_month = $achievement->getBranchAdminAchievementMonth($branch_id);
        if($achievement_data != false){
            $array = $achievement_data->toArray();
            if($achievement_data_month != false){
                $merge = $achievement_data_month->toArray();
                foreach($array as $key=>$val){
                    if($val['admin_name'] == $merge[$key]['admin_name']){
                        $array[$key]['month_money'] = $merge[$key]['month_money'];
                        $array[$key]['month_commission'] = $merge[$key]['month_commission'];
                    }else{
                        $array[$key]['month_money'] = 0;
                        $array[$key]['month_commission'] = 0;
                    }
                }
            }
            $this->view->setVar('data', $array);
        }else{
            $this->flash->error('没有业绩数据');
            $this->dispatcher->forward([
                'action' => 'branch_achievement'
            ]);
            return;
        }
    }


    //业务员业绩详情
    public function admin_achievement_infoAction($admin_name)
    {
        $numberPage = 1; $parameters = [];

            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Achievement', array('admin_name' => $admin_name));
            $parameters = $query->getParams();

        //传递搜索条件
        if($_REQUEST['admin_name']){
            $this->view->setVar('admin_name', $_REQUEST['admin_name']);
        }else{
            $this->view->setVar('admin_name', $admin_name);
        }
        if($_GET['admin_name']){
            $data['admin_name'] = $_GET['admin_name'];
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Achievement', $data);
            $numberPage = $_GET['page'];
            $parameters = $query->getParams();
        }

        $paginator = \Wdxr\Models\Services\Achievement::getAchievementListPagintorOld($parameters, $numberPage);
        $this->view->setVar('page', $paginator->getPaginate());
    }


    //全部业绩统计列表
    public function statisticsAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Statistics', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        //传递搜索条件
        $this->view->setVar('company_name', $_REQUEST['company_name']);

        if($_GET['company_name']){
            $data['company_name'] = $_GET['company_name'];
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Statistics', $data);
            $numberPage = $_GET['page'];
            $parameters = $query->getParams();
        }

        $company_data = $this->subBranch();//判断是否分站管理员

        $paginator = \Wdxr\Models\Services\Statistics::getStatisticsListPagintor($parameters, $numberPage,$company_data);
        $this->view->setVar('page', $paginator->getPaginate());
    }

    //导出业绩统计列表
    public function statistics_exportAction()
    {
            $statistics = new Statistics();
            $filename = '统计报表';


//        $company_data = $this->subBranch();//判断是否分站管理员
//        if($company_data == ''){
            $company_data = '1 = 1';
//        }

        if($this->request->getPost('start_time') && $this->request->getPost('end_time')) {

            $start_time = date('Y-m-d H:i:s',strtotime($this->request->getPost('start_time')));
            $end_time = date('Y-m-d H:i:s',strtotime($this->request->getPost('end_time'))+86400);

            $data = $statistics->getExportBetweenList($start_time, $end_time,$company_data)->toArray();
            $data = $this->export_sumAction($data);
            $filename = $this->request->getPost('start_time') .'至'.$this->request->getPost('end_time').$filename;
        } else {
            $data = $statistics->getExportList($company_data)->toArray();
            $data = $this->export_sumAction($data);
            $filename = '至'.date('Y-m-d',time()).$filename;
        }
        //var_dump($data);exit;
        Excel::create()->header(['企业名称', '银行名称', '收款账户', '每日报销', '推荐奖励','管理奖励','事业合伙人奖励'])
            ->value($data)->sheetTitle($filename)->output($filename);
    }

    //导出昨天的统计列表
    public function today_statistics_exportAction()
    {
//        $company_data = $this->subBranch();//判断是否分站管理员
//        if($company_data == ''){
            $company_data = '1 = 1';
//        }
        $statistics = new Statistics();
        $filename = '统计报表';
        $start_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d",strtotime("-1 day"))));
        $end_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d",strtotime("-1 day"))) + 86400);
            $data = $statistics->getExportBetweenList($start_time, $end_time,$company_data)->toArray();
            $data = $this->export_sumAction($data);
            $filename = date("Y-m-d",strtotime("-1 day")).$filename;
        Excel::create()->header(['企业名称', '银行名称', '收款账户', '每日报销', '推荐奖励','管理奖励','事业合伙人奖励'])
            ->value($data)->sheetTitle($filename)->output($filename);
    }

    //合计计算
    public function export_sumAction($export_data)
    {
        $data = array();
        $data['company_name'] = '总计:';
        $data['bank_name'] = '';
        $data['bank_card'] = '';
        $data['bank_name'] = '';
        $data['fee'] = 0;
        $data['recommends_fee'] = 0;
        $data['manages_fee'] = 0;
        $data['bonus'] = 0;
        foreach ($export_data as $key=>$val){
            $data['fee'] += $val['fee'];
            $data['recommends_fee'] += $val['recommends_fee'];
            $data['manages_fee'] += $val['manages_fee'];
            $data['bonus'] += $val['bonus'];
        }

        $data['all'] = $data['fee'] + $data['recommends_fee'] + $data['manages_fee'] + $data['bonus'];
        $export_data[count($export_data)] = $data;
        return $export_data;
    }

    //分站管理员限制
    public function subBranch()
    {
        //如果是分站管理员,获取所属分站业务员下的合伙人信息
        if($_SESSION["auth-identity"]['position'] == "分站管理员") {
            $branch = new Branch();
            $branch_data = $branch->getBranchByAdminId($_SESSION["auth-identity"]['id']);//分站信息

            //查询该分站分配的管理员
            $salesman = new Salesman();
            $salesman_data = $salesman->get_salesman($branch_data->getId());//分站的业务员信息

            if($salesman_data){
                $admin_data = array_column($salesman_data->toArray(),'admin_id');
                $admin_data = "device_id in (".implode($admin_data,',').")";
                $company_data = (new Company())->getCompanyByDeviceId($admin_data);

                if($company_data){
                    $company_data = $company_data->toArray();
                    $company_data = "company_name in ('".implode(array_column($company_data,'name'),"','")."')";
                }else{
                    $company_data = "company_name  = '' ";
                }
            }else{
                $company_data = "company_name  = '' ";
            }
        }else{
            $company_data = "";
        }

        return $company_data;
    }

    //分站业务员的业绩
    public function subBranchAch()
    {
//        //如果是分站管理员,只能查看所属分站的业务员业绩信息
//        if($_SESSION["auth-identity"]['position'] == "分站管理员"){
//            $branch = new Branch();
//            $branch_data = $branch->getBranchByAdminId($_SESSION["auth-identity"]['id']);//分站信息
//            if($branch_data){
//                $branch_id = "branch_id = ".$branch_data->getId();
//            }else{
//                $branch_id = "branch_id = ''";
//            }
//        }else{
//            $branch_id = null;
//        }
//
//        return $branch_id;
        return null;
    }


    public function recommend_infoAction()
    {
        $id = $this->request->getPost('id');
        //查询推荐人的信息
        $company_data = (new Company())->getById($id);
        $company_recommend = new CompanyRecommend();
        $list = $company_recommend->getRecommendId($id);
        $option = '';
        foreach($list as $k=>$v){
            $option .= "<p><a target='_blank' href='/admin/companys/view/".$v['id']."'>".$v['name']." </a><code>".$v['recommend_name']."　".$v['type']."</code></p>";
        }
        $array['recommend'] = $company_data->getName()."<code>".$company_data->company_info->getLegalName()."</code>";
        $array['data'] = $option;
        return $this->response->setJsonContent($array);
    }

    public function manage_infoAction()
    {
        $id = $this->request->getPost('id');
        //查询管理人的信息
        $company_data = (new Company())->getById($id);
        $company_recommend = new CompanyRecommend();
        $list = $company_recommend->getRecommendId($id);
        $option = '';
        foreach($list as $k=>$v){
            $manages = $company_recommend->getRecommendId($v['id']);
            foreach($manages as $key=>$val){
                $option .= "<p><a target='_blank' href='/admin/companys/view/".$v['id']."'>".$v['name']." </a><code>".$v['recommend_name']."　".$v['type']."</code>　→　<a target='_blank' href='/admin/companys/view/".$val['id']."'>".$val['name']." </a><code>".$val['recommend_name']."　".$val['type']."</code></p>";
            }
        }
        $array['recommend'] = $company_data->getName()."<code>".$company_data->company_info->getLegalName()."</code>";
        $array['data'] = $option;
        return $this->response->setJsonContent($array);
    }


    /**
     * 导出所有的推荐关系
     */
    public function all_recommendAction()
    {
        $filename = date('Y-m-d').'推荐关系表';
        $recommend_data = Company::getRecommendCompany()->toArray();
        $data = [];
        foreach($recommend_data as $key=>$val){
            $company_data = (new Company())->getById($val['company_id']);
            $company_recommend = new CompanyRecommend();
            $list = $company_recommend->getRecommendId($val['company_id']);
            $option = '';
            foreach($list as $k=>$v){
                $service_data = (new CompanyService())->getCompanyServiceByCompanyId($v['id']);
                $option .= $v['recommend_name']."(".$v['type'].date('m.d',$service_data->getStartTime()).")\n";
            }
            if($option){
                $data[$key]['company'] = $company_data->company_info->getLegalName();
                $data[$key]['recommend'] = rtrim($option,"\n");
            }
        }

        Excel::create()->title($filename)->header(['推荐人','被推荐人'])
            ->value($data)->sheetTitle($filename)->output($filename);
    }

    public function all_manageAction()
    {
        $filename = date('Y-m-d').'管理关系表';
        $manage_data = Company::getManageCompany()->toArray();
        $data = [];
        foreach($manage_data as $key=>$val){
            $company_data = (new Company())->getById($val['company_id']);
            $company_recommend = new CompanyRecommend();
            $list = $company_recommend->getRecommendId($val['company_id']);
            $option = '';
            foreach($list as $k=>$v){
                $recommend_service_data = (new CompanyService())->getCompanyServiceByCompanyId($v['id']);
                $manages = $company_recommend->getRecommendId($v['id']);
                foreach($manages as $a=>$b){
                    $manage_service_data = (new CompanyService())->getCompanyServiceByCompanyId($b['id']);
                    $option .= $v['recommend_name']."(".$v['type'].date('m.d',$recommend_service_data->getStartTime()).")　→　".$b['recommend_name']."(".$b['type'].date('m.d',$manage_service_data->getStartTime()).")\n";
                }
            }
            if($option){
                $data[$key]['company'] = $company_data->company_info->getLegalName();
                $data[$key]['recommend'] = rtrim($option,"\n");
            }

        }

        Excel::create()->title($filename)->header(['管理人','推荐人　→　被推荐人'])
            ->value($data)->sheetTitle($filename)->output($filename);
    }

}