<?php
namespace Wdxr\Modules\Admin\Controllers;

use Phalcon\Di;
use Phalcon\Exception;
use Phalcon\Mvc\View;
use Wdxr\Models\Entities\CompanyBillInfo;
use Wdxr\Models\Entities\Companys;
use Wdxr\Models\Entities\CompanyReport as EntityCompanyReport;
use Wdxr\Models\Entities\LoansInfo;
use Wdxr\Models\Repositories\Account;
use Wdxr\Models\Repositories\Achievement;
use Wdxr\Models\Repositories\Attachment;
use Wdxr\Models\Repositories\BankList;
use Wdxr\Models\Repositories\BillTerm;
use Wdxr\Models\Repositories\CommissionList;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\CompanyBank;
use Wdxr\Models\Repositories\CompanyInfo;
use Wdxr\Models\Repositories\CompanyBill;
use Wdxr\Models\Repositories\CompanyPayment;
use Wdxr\Models\Repositories\CompanyRecommend;
use Wdxr\Models\Repositories\CompanyRecommends;
use Wdxr\Models\Repositories\CompanyReport;
use Wdxr\Models\Repositories\CompanyService;
use Wdxr\Models\Repositories\CompanyVerify;
use Wdxr\Models\Repositories\CompanyVerify as RepoCompanyVerify;
use Wdxr\Models\Repositories\Contract;
use Wdxr\Models\Repositories\Finance;
use Wdxr\Models\Repositories\Level;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Phalcon\Mvc\Model\Criteria;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Models\Entities\Term as EntityTerm;
use Wdxr\Models\Repositories\Manage;
use Wdxr\Models\Repositories\Messages;
use Wdxr\Models\Repositories\Recommend;
use Wdxr\Models\Repositories\BlackList;
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
use Wdxr\Models\Services\Companylogs;
use Wdxr\Models\Services\Company as SerCompany;
use Wdxr\Models\Services\CompanyVerify as ServiceCompanyVerify;
use Wdxr\Models\Services\Cos;
use Wdxr\Models\Services\Excel;
use Wdxr\Models\Services\Loan;
use Wdxr\Models\Services\SMS;
use Wdxr\Models\Services\PushService;
use Wdxr\Models\Services\Services;
use Wdxr\Modules\Admin\Forms\ApplyForm;
use Wdxr\Modules\Admin\Forms\AuditForm;
use Wdxr\Modules\Admin\Forms\CompanybillForm;
use Wdxr\Modules\Admin\Forms\CompanysForm;
use Wdxr\Modules\Admin\Forms\CompanyNewForm;
use Wdxr\Modules\Admin\Forms\CompanyinfForm;
use Wdxr\Modules\Admin\Forms\AdminPasswordForm;
use Wdxr\Modules\Admin\Forms\LevelForm;
use Wdxr\Models\Services\Contract as ServiceContract;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class CompanysController extends ControllerBase
{

    /**
     * 未入驻企业列表
     */
    public function new_company_listAction()
    {
        $search = $this->request->getQuery('search', 'trim', '');
        $numberPage = $this->request->get('page') ? : 1;

        /**
         * @var $company RepoCompany
         */
        $company = Repositories::getRepository('Company');
        $companies = $company->getNewCompanyList($search, $numberPage);

        $this->view->setVars([
            'page' => $companies->getPaginate()
        ]);
    }

    /**
     * 入驻企业列表
     */
    public function indexAction()
    {
        $search = [
            'name' => $this->request->getQuery('search', 'trim', ''),
            'type' => $this->request->getQuery('type', 'trim', ''),
            'time' => $this->request->getQuery('time', 'trim', ''),
            'city' => $this->request->getQuery('city', 'trim', ''),
        ];

        /**
         * @var $company_service CompanyService
         */
        $company_service = Repositories::getRepository('CompanyService');
        $builder = $company_service->getServiceCompany($search);

        $numberPage = $this->request->get('page') ? : 1;
        $companies = new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 20,
            'page' => $numberPage
        ]);

        $this->view->setVars([
            'page' => $companies->getPaginate(),
            'cities' => Regions::getSubRegions('130000'),
        ]);
    }

    //导出客户信息
    public function export_companyAction()
    {
        $this->view->disable();
        if ($this->request->isAjax() === false) {
            return $this->response->setJsonContent(['status' => 0, 'info' => '非法请求']);
        }

        $search = [
            'name' => $this->request->getPost('search', 'trim', ''),
            'type' => $this->request->getPost('type', 'trim', ''),
            'time' => $this->request->getPost('time', 'trim', ''),
            'city' => $this->request->getPost('city', 'trim', ''),
        ];

        /**
         * @var $company_service CompanyService
         */
        $company_service = Repositories::getRepository('CompanyService');
        $builder = $company_service->getServiceCompany($search);
        $companies = $builder->getQuery()->execute();

        $data = [];
        foreach ($companies as $company) {
            $city = Regions::getRegionName($company->city);
            $district = Regions::getRegionName($company->district);
            $city = $city ? $city->name : '';
            $district = $district ? $district->name : '';
            array_push($data, [
                $company->is_partner == 1 ? '事业合伙人' : '普惠客户',
                $company->name,
                $company->legal_name,
                $company->contact_phone,
                $city.$district,
                $company->admin_name
            ]);
        }

        try {
            $filename = 'CustomerList';
            $id = Excel::create()->title($filename)->header(['客户类型', '企业名称', '法人代表', '联系方式', '所在地区', '客户经理'])
                ->value($data)->sheetTitle($filename)->download($filename);

            $url = Attachment::getAttachmentUrl($id);
        } catch (Exception $exception) {
            return $this->response->setJsonContent(['status' => 0, 'info' => $exception->getMessage()]);
        }
        return $this->response->setJsonContent(['status' => 1, 'info' => '下载客户名单成功', 'data' => $url]);
    }

    public function disabled_company_listAction()
    {

    }

    public function exportCompanyAction()
    {
        $this->view->disable();
        $data = [];
        /**
         * @var $companies Companys[]
         */
        $companies = RepoCompany::getTrueCompany();
        foreach ($companies as $company) {
            /**
             * @var $info \Wdxr\Models\Entities\CompanyInfo
             */
            $info =  $company->company_info;
            $service = Services::Hprose('Category');
            $sub_category = $service->getByCode($company->getCategory());//当前最下级
            $top_category = $service->getByCode($sub_category['top_category']);//当前最高级
            $sub_category_str = $sub_category ? $sub_category['name'] : '暂无';
            $top_category_str = $top_category ? $top_category['name'] : '暂无';
            /**
             * @var $payment \Wdxr\Models\Entities\CompanyPayment
             */
            $payment = $company->company_payment;
            $payment_type = $payment->getType() == CompanyPayment::TYPE_LOAN ? '普惠' : '合伙人';
            $address = Regions::getAddress(
                $info->getProvince(),
                $info->getCity(),
                $info->getDistrict(),
                $info->getAddress()
            );

            array_push($data, [
                $company->getName(),
                $info->getLegalName(),
                $payment_type,
                $info->getContactPhone(),
                $company->admin->getName(),
                $address,$top_category_str,
                $sub_category_str,
                $info->getScope()
            ]);
        }
        $filename = "全部企业列表".date('YmdHis', time());
        Excel::create()->title($filename)->header(['企业名称','法人', '客户类型','联系人电话', '业务员', '地址','行业主分类','行业子分类','经营范围'])
            ->value($data)->sheetTitle($filename)->output($filename);
    }

    public function exportLoanCompanyAction()
    {
        $this->view->disable();
        $data = [];
        $companies = Loan::getLoanVerify();
        foreach ($companies as $company) {
            if (!empty($company['district'])) {
                $address_name = Regions::getRegionName($company['district']);
            } else {
                $address_name= Regions::getRegionName($company['city']);
            }
            if ($address_name === false) {
                $regions = $company['district'];
            } else {
                $regions=$address_name->toArray()['name'];
            }
            array_push($data, [$company['name'],$company['legal_name'],$regions]);
        }
        $filename = "普惠未完成列表".date('YmdHis', time());
        Excel::create()->title($filename)->header(['企业名称','法人', '所在地区'])
            ->value($data)->sheetTitle($filename)->output($filename);
    }


    //保存返回条件到redis
    private function redisNodeAction($url)
    {
        if (strstr($url, '&page=')) {
            $url = str_replace("_url=", "", $url);
            $this->redis->save('Admin_'.$this->session->get("auth-identity")['id'], $url, -1);
        } else {
            $url = str_replace("_url=", "", $url);
            $url = $url."&page=1&name=".$this->request->get('name')."&start_time=".$this->request->get('start_time')."&end_time=".$this->request->get('end_time');
            $this->redis->save('Admin_'.$this->session->get("auth-identity")['id'], $url, -1);
        }
    }

    //所有返回方法
    public function goBackAction()
    {
        return $this->response->redirect($this->redis->get('Admin_'.$this->session->get("auth-identity")['id']));
    }

    public function CompanyNewAction()
    {
        $form= new CompanyNewForm();
        try {
            if($this->request->isPost()) {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $data = $this->request->getPost();
                    //判断企业是否存在
                    $is_company_info = (new \Wdxr\Models\Repositories\CompanyInfo())->getCompanyInfoByLicenceNum($data['licence_num']);
                    if($is_company_info){
                        $this->flash->error('当前公司已存在');
                        return;
                    }
                    $admin_id = $this->session->get("auth-identity")['id'];
                    $data['add_people'] =UserAdmin::getDeviceId($admin_id,UserAdmin::TYPE_ADMIN);
                    $company_id = SerCompany::manualAddCompanyInfo($data);
                    $this->flash->success('添加企业成功');
                    $this->dispatcher->forward([
                        'controller' => "apply",
                        'action' => 'list'
                    ]);
                }
            }
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
        } catch (Exception $exception) {
            $this->flash->error($exception->getMessage());
        }
        $this->view->setVar('form', $form);
    }

    //后台待申请列表
    public function new_listAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Companys', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int")?:1;
        }
        $paginator = SerCompany::getUnPaymentListPagintor($parameters,$numberPage);
        $this->view->setVar('page', $paginator);
    }



    //添加企业
    public function newAction()
    {
        $form = new CompanysForm();
        try {
            if($this->request->isPost()) {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $Company = new RepoCompany();
                    //将账号密码信息添加到用户表
                    $user = new User();
                    $data = $this->request->getPost();
                    $array['name'] = $data['user_name'];
                    $array['password'] = $data['user_password'];
                    $array['phone'] = '';
                    $array['email'] = '';
                    $array['last_login_time'] = '';
                    $array['last_login_ip'] = '';
                    $array['status'] = 0;
                    $data['user_id'] = $user->addNew($array);

                    //添加企业信息
                    $Company->addNew($data);
                    $this->flash->success('添加企业成功');
                    $this->dispatcher->forward([
                        'controller' => "companys",
                        'action' => 'index'
                    ]);
                    return;
                }
            }
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
        }
        $this->view->setVar('form', $form);
    }

    //修改企业信息(todo 废弃)
    public function editAction($id)
    {
        $company = RepoCompany::getCompanyById($id);
        if (!$company) {
            $this->flash->error("没有找企业数据");
            $this->goBackAction();
        }
        $form = new CompanysForm($company, ['edit' => true]);
        if ($this->request->isPost()) {
            try {
                if($form->isValid($this->request->getPost(), $company) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new InvalidRepositoryException($message);
                    }
                } else {
                    $company->setCategory($this->request->getPost('sub_category'));
                    if (!$company->save()) {
                        foreach ($company->getMessages() as $message) {
                            throw new InvalidRepositoryException($message);
                        }
                    }
                    //修改企业服务期限
                    $service_data = (new CompanyService())->getCompanyServiceByCompanyId($id);
                    if($service_data){
                        $service_data->setStartTime(strtotime($this->request->getPost('start_time')));
                        $service_data->setEndTime(strtotime($this->request->getPost('end_time')));
                        if (!$service_data->save()) {
                            foreach ($service_data->getMessages() as $message) {
                                throw new InvalidRepositoryException($message);
                            }
                        }
                    }
                    if($company->users){
                        $users = (new User())->getById($company->getUserId());
                        $users->setStatus($this->request->getPost('login_status'));
                        if(!$users->save()){
                            foreach ($users->getMessages() as $message) {
                                throw new InvalidRepositoryException($message);
                            }
                        }
                    }
                    $this->flash->success('修改企业成功');
                    $this->logger_operation_set('修改企业基本信息','Company','edit',$id);
                    $this->goBackAction();
                }
            } catch (InvalidRepositoryException $exception) {
                $this->flash->error($exception->getMessage());
            }
        }

        //获取企业服务期限截止时间
        $service_data = (new CompanyService())->getCompanyServiceByCompanyId($id);
        if($service_data){
            $start_time = $service_data->getStartTime();
            $end_time = $service_data->getEndTime();
            $this->view->setVar('time_status', 1);
            $this->view->setVar('start_time', date('Y-m-d',$start_time));
            $this->view->setVar('end_time', date('Y-m-d',$end_time));
        }else{
            $this->view->setVar('time_status', 0);
        }
        //获取企业行业信息
        $service = Services::Hprose('Category');
        $sub_category = $service->getByCode($company->getCategory());//当前最下级
        $top_category = $service->getByCode($sub_category['top_category']);//当前最高级
        $all_top = $service->getAllTop();//所有最高级分类
        $all_sub = $service->getSub($top_category['code'] ?: 'A');//所有下级
        $this->view->setVar('top_category', $top_category);
        $this->view->setVar('sub_category', $sub_category);
        $this->view->setVar('all_top', $all_top);
        $this->view->setVar('all_sub', $all_sub);

        //获取企业缴费类型
        $company_payment_data = CompanyPayment::getPaymentById($company->getPaymentId());
        $this->view->setVar('company_payment', $company_payment_data ? CompanyPayment::getTypeName($company_payment_data->getType()) : '未缴费');//缴费类型
        //是否为合伙人
        if($company->getUserId()){
            $user_data = User::getUserById($company->getUserId());
            $this->view->setVar('is_partner', $user_data->getIsPartner() ? '合伙人' : '普惠');
        }else{
            $this->view->setVar('is_partner', '暂无');
        }
        //获取公司类别
        $this->view->setVar('auditing', $company->getAuditing());
        $this->view->setVar('login_status', $company->users->status ? : 0);
        $this->view->setVar('username', $company->users->name ? : '无');
        $this->view->setVar('type', $company->getType());
        $this->view->setVar('id', $id);
        $this->view->setVar('form', $form);
    }

    //todo 废弃
    public function edit_infoAction($verify_id)
    {
        try{
            if(empty($verify_id)) {
                $this->flash->error("无效的企业标志");
                return $this->response->redirect('admin/companys/edit_list');
            }
            $verify = CompanyVerify::getCompanyVerifyById($verify_id);
            if($verify === false){
                $this->flash->error("查找不到审核信息");
                return $this->response->redirect('admin/companys/edit_list');
            }
            $company_id = $verify->getCompanyId();

            $company = RepoCompany::getCompanyById($company_id);
            if($company->getAuditing() == RepoCompany::AUDIT_OK) {
                $this->flash->error("该企业已经通过审核，无需补录");
                return $this->response->redirect('admin/companys/edit_list');
            }
            $company_info = CompanyInfo::getCompanyInfoById($company->getInfoId());
            $address['province']=Regions::getRegionName($company_info->getProvince())->name;
            $address['city']=Regions::getRegionName($company_info->getCity())->name;
            $address['district']=Regions::getRegionName($company_info->getDistrict())->name;
            $form = new ApplyForm($company_info,['edit'=>true]);
            $picture['licence']= $this->each_att(Attachment::getAttachmentById(explode(',',$company_info->getLicence()))->toArray());
            $picture['credit_code']= $this->each_att(Attachment::getAttachmentById(explode(',',$company_info->getCreditCode()))->toArray());
            $picture['account_permit']= $this->each_att(Attachment::getAttachmentById(explode(',',$company_info->getAccountPermit()))->toArray());
            $picture['photo']= $this->each_att(Attachment::getAttachmentById(explode(',',$company_info->getPhoto()))->toArray());
            $picture['idcard_up']= $this->each_att(Attachment::getAttachmentById(explode(',',$company_info->getIdcardUp()))->toArray());
            $picture['idcard_down']= $this->each_att(Attachment::getAttachmentById(explode(',',$company_info->getIdcardDown()))->toArray());
            $this->view->setVars([
                'company_id' => $company_id,
                'info' => $company_info,
                'address' => $address,
                'form' => $form,
                'company' => $company,
                'verify' => $verify,
                'picture'=>$picture
            ]);
            if($this->request->isPost()) {
                $device_id = $company->getDeviceId();
                $files = (new ToolsController())->upload('apply', $device_id);
                $data = array_merge($this->request->getPost(), $files);
                $data['company_id'] = $company_id;
                $data['device_id'] = $device_id;
                $data['type'] = $company_info->getType();
                $data['licence_num'] =  $company_info->getLicenceNum();
                $data['legal_name'] = $company_info->getLegalName();
                $data['province'] = $company_info->getProvince();
                $data['city'] = $company_info->getCity();
                $data['district'] = $company_info->getDistrict();
                $data['period'] = $company_info->getPeriod();
                $data['scope'] = $company_info->getScope();
                if($form->isValid($data) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new Exception($message);
                    }
                } else {
                    $this->db->begin();
                    CompanyInfo::reApplyInfo($company,$data);
                    //修改上一个驳回的企业的状态dh20170922
                    $last_verify = CompanyVerify::getLastCompanyVerify($company_id, CompanyVerify::TYPE_DOCUMENTS ,CompanyVerify::STATUS_FAIL);
                    if($last_verify) {
                        $last_verify->setStatus(CompanyVerify::STATUS_RE_APPLY);
                        $last_verify->save();
                    }
                    $this->db->commit();
                    $this->flash->success("企业申请成功");
                    return $this->response->redirect('admin/companys/auditing');
                }
            }
        }catch (InvalidServiceException $exception) {
            $this->db->rollback();
            $this->flash->error($exception->getMessage());
        }catch (InvalidRepositoryException $exception) {
            $this->db->rollback();
            $this->flash->error($exception->getMessage());
        }catch (Exception $exception) {
            $this->flash->error($exception->getMessage());
        }

    }

    public function edit_listAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Companys', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        $paginator = ServiceCompanyVerify::getCompnayVerifyList($parameters, $numberPage);
        $this->view->setVar('page', $paginator->getPaginate());
    }

    public function verify_listAction()
    {

        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Companys', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        $admin_id = $this->session->get("auth-identity")['id'];
        $device_id =UserAdmin::getDeviceId($admin_id,UserAdmin::TYPE_ADMIN);
        $paginator = ServiceCompanyVerify::getCompnayVerifyInfoList($parameters, $numberPage,$device_id);

        $this->view->setVar('page', $paginator->getPaginate());
    }

    /**
     * 删除企业
     * @return bool|\Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function deleteAction()
    {
        $this->view->disable();
        if($this->request->isAjax() === false) {
            return $this->response->setJsonContent(['status' => 0, 'info' => '非法访问']);
        }
        try {
            $id = $this->request->getPost('id');
            $this->db->begin();
            RepoCompany::deleteCompany($id);
            $this->logger_operation_set('彻底删除企业','Company','delete', $id, "彻底删除ID为{$id}的企业");
            $this->db->commit();
            return $this->response->setJsonContent(['status' => 1, 'info' => '企业删除成功']);
        } catch (Exception $exception) {
            $this->db->rollback();
            return $this->response->setJsonContent(['status' => 0, 'info' => $exception->getMessage()]);
        }
    }

    /**
     * 企业工商信息
     * @param $company_id
     */
    public function infoAction($company_id)
    {
        $this->view->setVar('id', $company_id);

        /**
         * @var $company \Wdxr\Models\Services\Company
         */
        $company = Services::getService('Company');
        list($company_data, $info, $bank) = $company->getCompanyData($company_id);

        /**
         * @var $company_verify CompanyVerify
         */
        $company_verify = RepoCompanyVerify::getRepository('CompanyVerify');
        $verify = $company_verify->getCompanyVerifyByDataId($info['id'], CompanyVerify::TYPE_DOCUMENTS);

        $this->view->setVars([
            'verify' => $verify,
            'company' => $company_data,
            'info' => $info,
        ]);
    }

    /**
     * 查看缴费信息
     * @param $company_id
     */
    public function paymentAction($company_id)
    {
        $this->view->setVar('id', $company_id);

        /**
         * @var $company_payment \Wdxr\Models\Repositories\CompanyPayment
         */
        $company_payment = Repositories::getRepository('CompanyPayment');
        $payments = $company_payment->getCompanyPaymentList($company_id);
        $list = [];
        foreach ($payments as $key => $payment)
        {
            $list[$payment->getId()]['id'] = $payment->getId();
            $list[$payment->getId()]['time'] = $payment->getTime();
            $list[$payment->getId()]['status'] = $payment->getStatus();
            $list[$payment->getId()]['status_name'] = CompanyPayment::getStatusName($payment->getStatus());
            $list[$payment->getId()]['type'] = CompanyPayment::getTypeName($payment->getType());
            $list[$payment->getId()]['amount'] = $payment->getAmount();
            $list[$payment->getId()]['verify_time'] = $payment->getVerifyTime();
            $list[$payment->getId()]['voucher'] = Attachment::getAttachmentUrl($payment->getVoucher());

            /**
             * @var $repo_loan \Wdxr\Models\Repositories\Loan
             */
            $repo_loan = Repositories::getRepository('Loan');
            $loan = $repo_loan->getLoanByPaymentId($payment->getId());
            if ($payment->getType() == CompanyPayment::TYPE_LOAN && $loan !== false) {
                $list[$payment->getId()]['loan'] = $loan;
                $loan_info = $repo_loan->getLoanInfoByLoanId($loan->getId());
                $list[$payment->getId()]['loan_info'] = $loan_info;
                $list[$payment->getId()]['loan_info']->term_name = \Wdxr\Models\Repositories\LoansInfo::getTerm($loan_info->getTerm());
                $list[$payment->getId()]['loan']->status_name = $repo_loan->getStatusName($loan->getState());
                $verify = RepoCompanyVerify::getRepository('CompanyVerify')->getCompanyVerifyByDataId($loan_info->getId(), CompanyVerify::TYPE_LOAN);
            } else {
                /**
                 * @var $company_verify \Wdxr\Models\Repositories\CompanyVerify
                 */
                $company_verify = RepoCompanyVerify::getRepository('CompanyVerify');
                $verify = $company_verify->getCompanyVerifyByDataId($payment->getId(), CompanyVerify::TYPE_PAYMENT);
            }

            if($verify !== false) {
                $list[$payment->getId()]['device_id'] = $verify->getDeviceId();
                $list[$payment->getId()]['device_name'] = $verify->device_name;
                $list[$payment->getId()]['admin_name'] = $verify->admin_name;

                $list[$payment->getId()]['remark'] = $verify->getRemark();
                $list[$payment->getId()]['auditor'] = $verify->auditor;
            }
        }

        /**
         * @var $company_bank CompanyBank
         */
        $company_bank = Repositories::getRepository('CompanyBank');
        $bankcards = $company_bank->getBankCards($company_id);
        $this->view->setVar('banks', $bankcards);
        $this->view->setVar('payments', $list);
    }

    /**
     * 企业票据信息
     * @param $company_id
     */
    public function billAction($company_id)
    {
        $this->view->setVar('id', $company_id);

        /**
         * @var $services \Wdxr\Models\Entities\CompanyService[]
         */
        $services = Repositories::getRepository('CompanyService')->getCompanyServices($company_id);
        $data = [];
        foreach ($services as $service)
        {
            $bill_id = $service->getBillId();
            $bill = CompanyBill::getCompanyBillById($bill_id);
            $data[$service->getId()]['amount'] = $bill === false ? 0 : $bill->getAmount();
            $data[$service->getId()]['id'] = $service->getId();
            $data[$service->getId()]['start_time'] = $service->getStartTime();
            $data[$service->getId()]['end_time'] = $service->getEndTime();
            $data[$service->getId()]['bill_status'] = $service->getBillStatus() == 1 ? '正常' : '待交票据';
            /**
             * @var $bill_info CompanyBillInfo[]
             */
            $bill_info = Repositories::getRepository('CompanyBillInfo')->getBillInfoByBillId($bill_id);
            foreach($bill_info as $key => $val)
            {
                $type_name = Repositories::getRepository('CompanyBillInfo')->getTypeName($val->getType());
                $data[$service->getId()]['info'][$key]['type'] = $type_name ? : '未知发票';
                $data[$service->getId()]['info'][$key]['amount'] = $val->getAmount();
                $data[$service->getId()]['info'][$key]['time'] = $val->getCreateAt();

                $rent = $val->getRent() ? explode(',',$val->getRent()) : [];
                $rent_receipt = $val->getRentReceipt() ? explode(',',$val->getRentReceipt()) : [];
                $rent_contract = $val->getRentContract() ? explode(',',$val->getRentContract()) : [];
                $property_fee = $val->getPropertyFee() ? explode(',',$val->getPropertyFee()) : [];
                $water_fee = $val->getWaterFee() ? explode(',',$val->getWaterFee()) : [];
                $electricity = $val->getElectricity() ? explode(',',$val->getElectricity()) : [];

                $array = array_merge($rent, $rent_receipt, $rent_contract, $property_fee, $water_fee, $electricity);
                //获取对应图片信息
                $data[$service->getId()]['info'][$key]['pic'] = $this->each_att(Attachment::getAttachmentById($array)->toArray());
                //查询对应的状态
                $verify = RepoCompanyVerify::getVerifyInfoByDataId($val->getId(), RepoCompanyVerify::TYPE_BILL);
                $data[$service->getId()]['info'][$key]['status'] = RepoCompanyVerify::getStatusName($verify->getStatus());
            }
        }

        $this->view->setVars([
            'services' => $data
        ]);
    }

    /**
     * 查看企业征信报告
     * @param $company_id
     */
    public function reportAction($company_id)
    {
        $this->view->setVar('id', $company_id);

        /**
         * @var $services \Wdxr\Models\Entities\CompanyService[]
         */
        $services = Repositories::getRepository('CompanyService')->getCompanyServices($company_id);
        $data = [];
        foreach ($services as $service)
        {
            $data[$service->getId()]['id'] = $service->getId();
            $data[$service->getId()]['start_time'] = $service->getStartTime();
            $data[$service->getId()]['end_time'] = $service->getEndTime();
            $data[$service->getId()]['report_status'] = $service->getReportStatus() == 1 ? '正常' : '待交征信';
            if(isset($service->company_report)) {
                /**
                 * @var $reports \Wdxr\Models\Entities\CompanyReport[]
                 */
                $reports = $service->company_report;
                foreach ($reports as $key => $report) {
                    $data[$service->getId()]['info'][$key]['status'] = $report->getStatus();
                    $data[$service->getId()]['info'][$key]['device_name'] = UserAdmin::getNameByDeviceId($report->getDeviceId());
                    $data[$service->getId()]['info'][$key]['createAt'] = $report->getCreateAt();
                    $data[$service->getId()]['info'][$key]['report'] = $this->each_att(Attachment::getAttachmentById(explode(',',$report->getReport()))->toArray());
                }
            }
        }

        $this->view->setVars([
            'services' => $data
        ]);
    }

    /**
     * 企业业务信息
     * 企业续费条件：当期满12家可免费续费；否则需要重新缴费
     * @param $company_id
     */
    public function businessAction($company_id)
    {
        $this->view->setVar('id', $company_id);

        /**
         * @var $company_service \Wdxr\Models\Repositories\CompanyService
         */
        $company_service = Repositories::getRepository('CompanyService');
        $services = $company_service->getCompanyServices($company_id);
        $service_status = $company_service->getCompanyService($company_id);

        /**
         * @var $company_recommend \Wdxr\Models\Repositories\CompanyRecommend
         */
        $company_recommend = Repositories::getRepository('CompanyRecommend');
        $recommendeds = $company_recommend->getRecommendedCompany($company_id);
        $company = RepoCompany::getCompanyById($company_id);
        $recommend = is_null($company->getRecommendId()) ? [] : RepoCompany::getCompanyById($company->getRecommendId());
        $manager = is_null($company->getManagerId()) ? [] : RepoCompany::getCompanyById($company->getManagerId());

        //当期内推荐的客户
        $recommends = $company_recommend->getCurrentRecommend($company_id);

        $this->view->setVars([
            'services' => $services,
            'service_status' => $service_status,
            'recommendeds' => $recommendeds,
            'recommend' => $recommend,
            'recommend_count' => count($recommends),
            'manager' => $manager
        ]);
    }

    /**
     * 企业用户账号信息
     * @param $company_id
     */
    public function userAction($company_id)
    {
        $this->view->setVar('id', $company_id);

        $form = new AdminPasswordForm;
        $this->view->setVar('form', $form);

        $company = RepoCompany::getCompanyById($company_id);
        if($company !== false) {
            $user_id = $company->getUserId();
            $user = User::getUserById($user_id);

            $info = [];
            if ($user !== false) {
                $info['id'] = $user->getId();
                $info['name'] = $user->getName();
                $info['phone'] = $user->getPhone() ?: '无';
                $info['email'] = $user->getEmail() ?: '无';
                $info['status'] = $user->getStatus() == 1 ? '正常' : '禁用';
                $info['number'] = $user->getNumber();
                $info['pic'] = $user->getPic() ? Attachment::getAttachmentUrl($user->getPic())[0] : '';
            }

            $numberPage = $this->request->getQuery("page", "int", 1);
            $this->view->setVar('page', Companylogs::getCompanyPasswordLogs($user_id, $numberPage)->getPaginate());

            if ($this->request->isPost()) {
                try {
                    if ($form->isValid($this->request->getPost()) == false) {
                        foreach ($form->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    } else {
                        Repositories::getRepository('User')->changePassword($user->getId(), $this->request->getPost('password'));
                        $this->flash->success('修改用户密码成功');
                    }
                } catch (InvalidRepositoryException $exception) {
                    $this->flash->error($exception->getMessage());
                }
            }

            $this->view->setVars([
                'user' => $info
            ]);
        }
    }

    //企业设置
    public function settingAction($company_id)
    {
        $this->view->setVar('id', $company_id);

        $company = Company::getCompanyById($company_id);
        $this->view->setVar('company', $company);

        /**
         * @var $company_bill CompanyBill
         */
        $company_bill = Repositories::getRepository('CompanyBill');
        $bill = $company_bill->getCompanyBillByCompanyId($company_id);
        $bill_term = (new BillTerm())->getBillTermByCompanyId($company_id);
        $this->view->setVars([
            'bill_end_time' => $bill ? $bill->getEndTime() : '',
            'bill_term' => $bill_term
        ]);

        /**
         * @var $services_company \Wdxr\Models\Services\Company
         */
        $services_company = Services::getService('Company');
        $admin_name = $services_company->getCompanyAdmin($company_id)['name'];
        /**
         * @var $admin Admin
         */
        $admin = Admin::getRepository('Admin');
        $this->view->setVar('admin_name', $admin_name);
        $this->view->setVar('admin_list', $admin->getAdminList());

    }

    //所属业务员流转
    public function transfer_adminAction()
    {
        $this->view->disable();
        if ($this->request->isAjax()) {
            $company_id = $this->request->getPost('company_id');
            $admin_id = $this->request->getPost('admin_id');

            /**
             * @var $service_company \Wdxr\Models\Services\Company
             */
            $service_company = Services::getService('Company');
            try {
                //判断业务员状态
                if (($title = Admin::getAdminStatus($admin_id)) !== true) {
                    throw new Exception($title);
                }
                //获取旧业务员ID
                $company = RepoCompany::getCompanyById($company_id);
                $old_admin_id = $company->getAdminId();
                //流转所属业务员
                $service_company->transferCompany($company_id, $admin_id);

                //操作日志
                $this->logger_operation_set(
                    '企业业务关系流转',
                    'Company',
                    'transfer_admin',
                    ['company_id' => $company_id, 'admin_id' => $admin_id, 'old_admin_id' => $old_admin_id],
                    '将所属业务员ID为'.$old_admin_id.'的企业流转给ID为'.$admin_id.'的业务员'
                );
            } catch (Exception $exception) {
                return $this->response->setJsonContent(['status' => '0', 'info' => $exception->getMessage()]);
            }
            return $this->response->setJsonContent(['status' => '1']);
        }
        return $this->response->setJsonContent(['status' => '0', 'info' => '非法请求']);
    }

    /**
     * 修改企业信息
     * @return string
     */
    public function edit_companyAction()
    {
        $this->view->disable();
        if($this->request->isAjax()) {
            $value = $this->request->getPost('value');
            $name = $this->request->getPost('name');
            list($table, $filed, $id) = explode('-', $name);

            $result = $this->db->update($table, [$filed], [$value], [
                'conditions' => 'id = ?',
                'bind'       => $id,
            ]);
            if($result === true) {
                return $this->response->setJsonContent(['status' => '1']);
            }
        }
        return $this->response->setJsonContent(['status' => '0']);
    }

    /**
     * 获取企业类型
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface|string
     */
    public function get_company_typeAction()
    {
        $this->view->disable();
        if($this->request->isAjax()) {
            return $this->response->setJsonContent([
                ['id' => '1', 'name' => '非个体工商户'],
                ['id' => '2', 'name' => '个体工商户']
            ]);
        }
        return '';
    }

    /**
     * 获取一个企业可能有的推荐企业
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface|string
     */
    public function get_recommend_company_listAction()
    {
        $this->view->disable();
        if($this->request->isAjax()) {
            $company_id = $this->request->getPost('recommended_id');
            /**
             * @var $company_recommend CompanyRecommend
             */
            $company_recommend = Repositories::getRepository('CompanyRecommend');
            $recommenders = $company_recommend->getProbablyRecommend($company_id);
            if(count($recommenders) === 0) {
                $recommenders = [];
            } else {
                $recommenders = $recommenders->toArray();
            }

            return $this->response->setJsonContent($recommenders);
        }
        return '';
    }

    /**
     * 修改企业推荐人
     */
    public function save_company_recommendAction()
    {
        $this->view->disable();

        if($this->request->isAjax()) {
            $recommender = $this->request->getPost('value');
            $old_recommender = $this->request->getPost('old_recommender');
            $recommend_id = $this->request->getPost('recommended_id');

            /**
             * @var $company_recommend CompanyRecommend
             */
            $company_recommend = Repositories::getRepository('CompanyRecommend');

            try {
                $company_recommend->changeRecommend($recommender, $old_recommender, $recommend_id);
            } catch (Exception $exception) {
                return $this->response->setJsonContent(['status' => '0', 'info' => $exception->getMessage()]);
            }
        }

        return $this->response->setJsonContent(['status' => '1']);
    }

    //提交法人证件信息
    //todo
    public function add_infoAction()
    {
        //获取企业ID
        $company_id = $this->request->getPost('id');
        //判断是否存在上传图片
        if ($this->request->hasFiles()) {
            //获取图片信息
            $files = $this->request->getUploadedFiles();
            //判断企业目录是否存在,不存在则创建
            $path = BASE_PATH."/files/company/" . $company_id . "/";
            if(file_exists($path) === false) {
                mkdir($path, 0755);
            }
            $data = array();
            $info = $this->request->getPost();
            $att = new Attachment();

            // 开始上传图片
            foreach ($files as $key=>$file) {
                //生成图片名
                $file_name = date('Ymd').time().$key.".".$file->getExtension();
                $file->moveTo($path.$file_name);

                //存入图片记录
                $data['name'] = $file->getName();
                $data['size'] = $file->getSize();
                $data['upload_time'] = time();
                $data['path'] = $path.$file_name;
                $info[$file->getKey()] = $att->addNew($data);
            }
            $info['company_id'] = $company_id;
            $info['bank_province'] = $info['province'];
            $info['bank_city'] = $info['city'];
            $info['district'] = $info['area'];
            $info['verify_id'] = 0;
                //存入证件信息记录
            $companyinfo = new CompanyInfo();
            $companyinfo_id = $companyinfo->addNew($info);

            //存入infoID
            $company = new RepoCompany();
            $EntityCompany = $company->getCompanyById($company_id);
            $EntityCompany->setInfoId($companyinfo_id);
            $EntityCompany->save();

            //添加审核记录
            $verify = new CompanyVerify();
            $verify_data['company_id'] = $company_id;
            $verify_data['auditor_id'] = 0;
            $verify_data['status'] = 0;
            $verify_data['type'] = 1;
            $verify_data['data_id'] = $companyinfo_id;
            $verify_data['remark'] = '';
            $verify->addNew($verify_data);
        }else{
            $this->flash->error("提交失败,图片信息错误");
            $this->dispatcher->forward([
                'controller' => "companys",
                'action' => 'index'
            ]);
            return;
        }
        $this->flash->success("保存成功,请等待审核");
        $this->dispatcher->forward([
            'controller' => "companys",
            'action' => 'index'
        ]);
        return;
    }


    //票据信息提交页面
    public function bill_infoAction($edit_id,$type)
    {
        $this->view->setVar('type',$type);
        $this->view->setVar('id', $edit_id);

        //获取企业信息
//        $company = new Company();
//        $company_data = $company->getCompanyByID($edit_id);
        /**
         * @var $company_bill CompanyBill
         */
        $company_bill = Repositories::getRepository('CompanyBill');
        $bill = $company_bill->getCompanyBillByCompanyId($edit_id);
        $companybill_data = (new \Wdxr\Models\Repositories\CompanyBillInfo())->getBillInfoByCompanyId($edit_id);
        if($companybill_data->toArray()){
            $data = array();
            foreach($companybill_data->toArray() as $key=>$val){
                $type_name = (new \Wdxr\Models\Repositories\CompanyBillInfo())->getTypeName($val['type']);
                $data[$key]['type'] = $type_name ? : '未知发票';
                $data[$key]['amount'] = $val['amount'];
                $data[$key]['time'] = $val['createAt'];
                $rent = $val['rent'] ? explode(',',$val['rent']) : [];
                $rent_receipt = $val['rent_receipt'] ? explode(',',$val['rent_receipt']) : [];
                $rent_contract = $val['rent_contract'] ? explode(',',$val['rent_contract']) : [];
                $property_fee = $val['property_fee'] ? explode(',',$val['property_fee']) : [];
                $water_fee = $val['water_fee'] ? explode(',',$val['water_fee']) : [];
                $electricity = $val['electricity'] ? explode(',',$val['electricity']) : [];
                $array = array_merge($rent,$rent_receipt,$rent_contract,$property_fee,$water_fee,$electricity);
                //获取对应图片信息
                $data[$key]['pic'] = $this->each_att(Attachment::getAttachmentById($array)->toArray());
                //查询对应的状态
                $verify = RepoCompanyVerify::getVerifyInfoByDataId($val['id'], RepoCompanyVerify::TYPE_BILL);
                $data[$key]['status'] = RepoCompanyVerify::getStatusName($verify->getStatus());
            }

            $this->view->setVar('bill_data',$data);
            $this->view->setVar('all_amount',$bill->getAmount());
        }else{
            $this->flash->error("没有票据信息");
            $this->dispatcher->forward([
                'controller' => "companys",
                'action' => 'bill_list'
            ]);
            return;
        }

    }

    //修改企业密码
    public function edit_passwordAction($id,$company_type)
    {
        //获取账号密码信息
        $user = new User();
        $company = new RepoCompany();
        $company_data = $company->getCompanyById($id)->toArray();
        if($user->getUserById($company_data['user_id']) != false){
            $user_data = $user->getUserById($company_data['user_id'])->toArray();
        } else {
            $this->flash->error('未找到用户信息');
            $this->dispatcher->forward([
                'action' => 'index'
            ]);
            return true;
        }
        $form = new AdminPasswordForm;
        if($this->request->isPost()) {
            try {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {

                    $user->changePassword($user_data['id'], $this->request->getPost('password'));

                    $this->flash->success('修改企业密码成功');
                }
            } catch (InvalidRepositoryException $exception) {
                $this->flash->error($exception->getMessage());
            }
        }
        $numberPage = $this->request->getQuery("page", "int", '1');
        $this->view->setVar('form', $form);
        $this->view->setVar('user_name', $user_data['name']);
        $this->view->setVar('page', Companylogs::getCompanyPasswordLogs($user_data['id'], $numberPage)->getPaginate());
        $this->view->setVar('id', $id);
        $this->view->setVar('company_type', $company_type);
    }

    //企业审核列表
    public function auditingAction()
    {
        $numberPage = $this->request->getQuery("page", "trim", '1');
        $name = $this->request->getQuery('name', 'trim', '');
        $hidden = $this->request->getQuery('hidden', 'int', 0) ? 1 : 0;

        if ($name) {
            $where = "(company.name like '%".$name."%' or info.legal_name like '%".$name."%') and verify.type = 1 and verify.is_hidden = ".$hidden;
        } else {
            $where = "1=1 and verify.type = 1 and verify.is_hidden = ".$hidden;
        }

        $paginator = ServiceCompanyVerify::getCompanyVerify2(CompanyVerify::TYPE_DOCUMENTS, $numberPage, $where);

        $this->view->setVar('page', $paginator->getPaginate());
        $this->view->setVar('name', $name);
        $this->view->setVar('hidden', $hidden);
    }

    public function billVerifyAction()
    {
        $numberPage = $this->request->getQuery("page", "int") ? : 1;
        $page = ServiceCompanyVerify::getCompanyVerifyList(CompanyVerify::TYPE_BILL, $numberPage);
        $this->view->setVar('page',$page->getPaginate());
    }

    public function reportVerifyAction()
    {
        $numberPage = $this->request->getQuery("page", "int");
        $page = ServiceCompanyVerify::getCompanyVerifyList(CompanyVerify::TYPE_CREDIT, $numberPage);
        $this->view->setVar('page',$page->getPaginate());
    }

    //获取证件待审核信息
    public function edit_auditingAction($verify_id)
    {
//        $verify = \Wdxr\Models\Services\CompanyVerify::getCompanyVerifyInfo($verify_id);
//        $company_panment_data = CompanyPayment::getPaymentByLoanId($company->company_id);
//        if($company_panment_data->getStatus() != CompanyPayment::STATUS_LOAN){
            //不再判断企业缴费信息,审核没有先后顺序20171030修改
//            //判断企业缴费信息
//            if($company->payment != RepoCompany::PAYMENT_OK) {
//                $this->flash->error("该企业的缴费信息尚未核实，无法审核企业信息");
//                return $this->response->redirect('admin/companys/auditing');
//            }
//        }
        $verify = CompanyVerify::getCompanyVerifyBuIdAndType($verify_id,RepoCompanyVerify::TYPE_DOCUMENTS);
        /**
         * @var $company_service \Wdxr\Models\Services\Company
         */
        $company_service = Services::getService('Company');
        list($company, $info,$bank) = $company_service->getCompanyData($verify->getCompanyId());
        if(empty($info)) {
            $this->flash->error("没有找企业数据");
            $this->dispatcher->forward([
                'action' => 'auditing'
            ]);
            return;
        }

        //获取企业签订地址
//        $contract = Contract::getInUseContractNum($company['id']);
//        $this->view->setVar('contract', $contract);
        $form = new AuditForm();
        $this->view->setVar('form', $form);
        $this->view->setVar('v_status', $verify->getStatus());
        $this->view->setVar('verify_id', $verify_id);
        $this->view->setVar('company_id', $verify->getCompanyId());
        $this->view->setVar('company', $company);
        $this->view->setVar('info', $info);
        $this->view->setVar('bank', $bank);
    }

    private function each_att($data)
    {
        $array = array();
        foreach($data as $val){
            $array[] =  (new Cos())->private_url($val['object_id']);//\OSS\Common::getOSSUrl($val['object_id']);
        }
        return $array;
    }

    /**
     * 查看企业合同
     * @param $company_id
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function contractAction($company_id)
    {
        $this->view->setVar('id', $company_id);

        $company = RepoCompany::getCompanyById($company_id);
        $service = Services::Hprose('contract');
        $list = $service->getContractList($company_id);
        $this->view->setVar('list',$list);
        $this->view->setVar('name', $company->getName());
    }

    /**
     * 生成合同
     * @param $contract_id
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function generateAction($contract_id, $company_id)
    {
        $contract = Contract::getContractById($contract_id);
        if($contract === false) {
            $this->flash->error("该合同不存在");
            return $this->response->redirect("admin/companys/contract/".$company_id);
        }
        $company_id = $contract->getCompanyId();
        try {
//            $company = RepoCompany::getCompanyById($company_id);
//            if($company->getAuditing() != RepoCompany::AUDIT_OK) {
//                throw new Exception("当前企业尚未通过审核，无法生成合同");
//            }
//            $payment = CompanyPayment::getPaymentByCompanyId($company_id, CompanyPayment::STATUS_OK);
//            if($payment === false) {
//                throw new Exception("该企业尚未提交缴费信息");
//            }
            $user_id = $this->auth->getIdentity()['id'];
            $device_id = UserAdmin::getDeviceId($user_id,UserAdmin::TYPE_ADMIN);
            $service = Services::Hprose('contract');
            $service->generateContract($contract_id, $device_id);
            $this->response->redirect('admin/companys/contract/'.$company_id);
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
            return $this->response->redirect("admin/companys/contract/".$company_id);
        } catch (Exception $exception) {
            $this->flash->error($exception->getMessage());
            return $this->response->redirect("admin/companys/contract/".$company_id);
        }
    }

    /**
     * 下载企业合同
     * @return mixed
     */
    public function downloadContractAction()
    {
        $dst = $this->request->getPost('dst');
        $service = Services::Hprose('contract');
        $location = $service->contract_url($dst);
        return $location;
    }

    /**
     * 合同下载日志
     * @return string
     */
    public function downloadLogAction()
    {
        $file_id = $this->request->getPost('fileId');
        $user_id = $this->auth->getIdentity()['id'];
        $device_id = UserAdmin::getDeviceId($user_id,UserAdmin::TYPE_ADMIN);

        $service = Services::Hprose('contract');
        if(($result = $service->setDownloadLog($file_id, $device_id)) === true) {
            return 'SUCCESS';
        }
        return $service->getError();
    }

    /**
     * 获取企业合同日志
     * @param $id
     */
    public function contractLogAction($id)
    {
        $service = Services::Hprose('contract');
        $log = $service->getContractFileLog($id);
        $this->view->setVar('log', $log);
    }

    /**
     * 下载合同pdf
     * @param $company_id
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function pdfAction($company_id = null)
    {
        $this->view->disable();
        $this->view->disableLevel([
            View::LEVEL_MAIN_LAYOUT => true,
            View::LEVEL_AFTER_TEMPLATE => true,
            View::LEVEL_LAYOUT      => true,
            View::LEVEL_BEFORE_TEMPLATE => true,
        ]);
        if($company_id) {
            $title = RepoCompany::getCompanyById($company_id)->getName()."企业服务协议书";
            try {
                $view = \Wdxr\Models\Services\Contract::getContractView($company_id);
            } catch (Exception $exception) {
                $this->flash->error($exception->getMessage());
                return $this->response->redirect("admin/companys/edit/".$company_id);
            }
        } else {
            $title = "企业服务协议书";
            $view = \Wdxr\Models\Services\Contract::generateEmptyContractView();
        }
        \Wdxr\Models\Services\Contract::setContractPdf($title, $view);
    }

    /**
     * 获取企业合同
     * @param null $company_id
     */
    public function getContractAction($company_id = null)
    {
        $this->view->disable();
        $this->view->disableLevel([
            View::LEVEL_MAIN_LAYOUT => true,
            View::LEVEL_AFTER_TEMPLATE => true,
            View::LEVEL_LAYOUT      => true,
            View::LEVEL_BEFORE_TEMPLATE => true,
        ]);
        if(empty($company_id)) {
            $view = \Wdxr\Models\Services\Contract::generateEmptyContractView();
        } else {
            $view = \Wdxr\Models\Services\Contract::getContractView($company_id);
        }

        echo $view;
    }

    public function auditAction()
    {
        $this->view->disable();
        if(!$this->request->isAjax()) {
            return $this->response->setJsonContent(['status' => 0, 'info' => '非法请求']);
        }
        $data = $this->request->getPost();
        try {
            $this->db->begin();
            $form = new AuditForm(null, ['status' => $data['status']]);
            if($form->isValid($data) === false) {
                $message = $form->getMessages()->current();
                throw new Exception($message->getMessage());
            }
            $company = RepoCompany::getCompanyById($data['company_id']);
            /**
             * @var $company_info \Wdxr\Models\Entities\CompanyInfo
             */
            $company_info = $company->company_info;
            \Wdxr\Models\Services\Company::companyAudit($data['company_id'], $data['status'], $data);
            $auditor_id = $this->auth->getIdentity()['id'];//审核人ID
            CompanyVerify::verifyCompany($data['verify_id'], $auditor_id, $data['status'], $data['remark']);
            if ($data['status'] == CompanyVerify::STATUS_OK) {
                \Wdxr\Models\Services\User::updateUserPhone($company->getUserId(), $company_info->getContactPhone());
                SMS::successSMS($company_info->getContactPhone(),$company_info->getContacts(),SMS::TYPE_APPLY);
                PushService::companyApply($company->getDeviceId(), $company_info->getLegalName(), $data['status']);
                $this->flash->success('企业信息申请已经通过');
            } elseif($data['status'] == CompanyVerify::STATUS_FAIL) {
                SMS::failedSMS($company_info->getContactPhone(),$company_info->getContacts(),SMS::TYPE_APPLY);
                PushService::companyApply($company->getDeviceId(), $company_info->getLegalName(), $data['status']);
                $this->flash->success('企业信息申请已经驳回');
            } else {
                throw new Exception("非法操作");
            }
            //执行审核
            $this->db->commit();
        } catch (Exception $exception) {
            $this->db->rollback();
            $error_message  = $exception->getMessage() ? : "企业申请审核失败!";
            return $this->response->setJsonContent(['status' => 0, 'info' => $error_message]);
        }
        $this->logger_operation_set('审核企业证件信息','Company','audit',$data['company_id']);
        return $this->response->setJsonContent(['status' => 1]);
    }

    //执行审核
    public function edit_auditing_saveAction()
    {
        if(!$this->request->isPost()) {
            return $this->response->redirect('admin/companys/auditing');
        }

        $company_id = $this->request->getPost('company_id');
        $info_id = $this->request->getPost('info_id');
        //获取企业的信息
        $company_info_data = CompanyInfo::getCompanyInfoById($info_id);

        $this->db->begin();
        $data = $this->request->getPost();
        if ($this->request->getPost('status') == \Wdxr\Models\Repositories\CompanyVerify::STATUS_OK) {
            try {
                $company = RepoCompany::getCompanyById($company_id);
//                $company_payment_data = CompanyPayment::getPaymentById($company->getPaymentId());//企业缴费信息
                $company->setAuditing(RepoCompany::AUDIT_OK);
                $company->setAccountId($this->request->getPost('account'));
                $company->setStatus(RepoCompany::STATUS_DISABLE);

//                $company->users->setStatus(User::STATUS_ENABLE);
                if ($company->save() == false) {
                    throw new Exception('企业状态审核失败');
                }
                //修改企业用户信息
                $user_data = $company->getUserId() ? User::getUserById($company->getUserId()) : false;
                if($user_data){
                    $user_data->setPhone($company_info_data->getContactPhone());
                    $user_data->save();
                }

//                if($company_payment_data->getType() != CompanyPayment::TYPE_LOAN) {
//                    //增加企业服务时间
//                    $period = $this->setService($company_id);
//                    if ($period == false) {
//                        throw new Exception('企业服务期限设置失败');
//                    }
//                    $service_data = CompanyService::getCompanyService($company_id);
//                    //添加票据征信审核期限列表
//                    $payment_type = $company_payment_data->getType();//缴费类型
//                    //票据
//                    $this->setBillTime($payment_type, $company_id, $company->getName(), $service_data->getStartTime());
//                    //征信
//                    $this->setReportTime($payment_type, $company_id, $company->getName(), $company->getReportId(), $service_data->getStartTime());
//                    //添加合伙人提成比率
//                    if($company_info_data->getBankType() == CompanyInfo::BANK_TYPE_PUBLIC){
//                        $comm['name'] = $company->getName();
//                    }else{
//                        $comm['name'] = $company_info_data->getLegalName();
//                    }
//                    $comm['ratio'] = 0.05;
//                    $comm['type'] = UserAdmin::TYPE_USER;
//                    $comm['device_id'] = UserAdmin::getDeviceId($company->getUserId(),UserAdmin::TYPE_USER);
//                    (new CommissionList())->addNew($comm);
//
//                    //服务期限通知
//                    Mns::periodSMS((int)$company_info_data->getContactPhone(),$company_info_data->getContacts(),date('Y年m月d日',$period));
//                }

                //推送用户消息
                $company_data = RepoCompany::getCompanyById($company_id);
                $push = new PushService();
                $content['title'] = "(".$company_info_data->getLegalName().")审核通过通知";
                $content['body'] = $company_info_data->getLegalName().'的企业信息已经审核通过!';
                $content['type'] = PushService::PUSH_TYPE_WARN;
                $push->newPushResult($content,$company_data->getDeviceId());

                /*
                //只有合伙人审核时才生成业绩数据
                if($company_payment_data->getType() != CompanyPayment::TYPE_LOAN) {
                    //业绩
                    $ach_data = array();

                    $ach_data['contract_num'] = $company_info_data->getContractNum();//合同编号
                    $admin_data = Admin::getAdminById($company_data->getAdminId());
                    $ach_data['admin_name'] = $admin_data->getName();//业务员名字
                    $sales = new Salesman();
                    $sales_data = $sales->getSalesmanByAdminId($admin_data->getId());
                    if ($sales_data == false) {
                        $branch_id = 0;
                    } else {
                        $branch_id = $sales_data->getBranchId();
                    }
                    $ach_data['branch_id'] = $branch_id;//分站ID
                    $level = new Level();
                    $level_data = $level->getLevelById($company_data->getLevelId());
                    $ach_data['money'] = $level_data->getLevelMoney();//金额
                    $ach_data['time'] = time();
                    $ach_data['company_name'] = $company_data->getName();
                    //合伙人推荐奖金
                    if ($company_payment_data->getType() != CompanyPayment::TYPE_LOAN) {
                        $bonus = $level_data->getLevelMoney() * 0.05;
                    } else {
                        $bonus = $level_data->getLevelMoney() * 0.05 / 2;
                    }
                    //推荐人
                    if (!empty($company_data->getRecommendId())) {
                        //如果有推荐企业,查看企业的缴费类型
                        $company_payment = new CompanyPayment();
                        $Recommend_payment_data = $company_payment->getPaymentByCompanyId($company_data->getRecommendId());
                        //推荐企业的公司信息
                        $Recommend_company_data = (new RepoCompany)->getById($company_data->getRecommendId());
                        $Recommend_company_info_data = (new CompanyInfo())->getCompanyinfoByCompanyId($company_data->getRecommendId());
                        if ($Recommend_payment_data->getType() != CompanyPayment::TYPE_LOAN) {
                            $ach_data['recommender'] = $Recommend_company_data->getName();
                            //获取合伙人奖金
                            $bonus = (new CommissionList())->getRatio($company_payment_data->getType(),$level_data->getLevelMoney(),$Recommend_company_data->getUserId(),UserAdmin::TYPE_USER);
                            //添加合伙人奖金
                            $temp = new Temp();
                            $temp->addNew(array('company_name' => $Recommend_company_info_data->getBankcard(), 'money' => $bonus));
                        } else {
                            $ach_data['recommender'] = null;
                        }

                        //管理人
                        if (!empty($Recommend_company_data->getRecommendId())) {
                            $R_Recommend_payment_data = $company_payment->getPaymentByCompanyId($Recommend_company_data->getRecommendId());
                            if ($R_Recommend_payment_data->getType() != CompanyPayment::TYPE_LOAN) {
                                $R_Recommend_company_data = (new RepoCompany)->getById($Recommend_company_data->getRecommendId());
                                $R_Recommend_company_info_data = (new CompanyInfo())->getCompanyinfoByCompanyId($Recommend_company_data->getRecommendId());
                                $ach_data['administrator'] = $R_Recommend_company_data->getName();
                                //获取合伙人奖金
                                $bonus = (new CommissionList())->getRatio($company_payment_data->getType(),$level_data->getLevelMoney(),$R_Recommend_company_data->getUserId(),UserAdmin::TYPE_USER);
                                //添加合伙人奖金
                                $temp = new Temp();
                                $temp->addNew(array('company_name' => $R_Recommend_company_info_data->getBankcard(), 'money' => $bonus));
                            } else {
                                $ach_data['administrator'] = null;
                            }
                        } else {
                            $ach_data['administrator'] = null;
                            $ach_data['commission'] = (new CommissionList())->getRatio($company_payment_data->getType(),$level_data->getLevelMoney(),$company->getAdminId(),UserAdmin::TYPE_ADMIN);
                        }

                    } else {
                        $ach_data['recommender'] = null;
                        $ach_data['administrator'] = null;
                        $ach_data['commission'] = (new CommissionList())->getRatio($company_payment_data->getType(),$level_data->getLevelMoney(),$company->getAdminId(),UserAdmin::TYPE_ADMIN);;
                    }

                    try {
                        $achievement = new Achievement();
                        $achievement->addNew($ach_data);
                    } catch (InvalidRepositoryException $e) {
                        $this->db->rollback();
                        $this->flash->error($e->getMessage());
                        return $this->response->redirect('admin/companys/auditing');
                    }

                }

                */

                //发送审核成功短信
                SMS::successSMS((int)$company_info_data->getContactPhone(),$company_info_data->getContacts(),SMS::TYPE_APPLY);
                //Mns::deadlineSMS((int)$company_info_data->getContactPhone(),$company_info_data->getContacts(),date('Y年m月d日',$bill_end_time));
                $this->flash->success('企业信息审核通过');

            } catch (Exception $exception) {
                $this->db->rollback();
                $this->flash->error($exception->getMessage());
                return $this->response->redirect('admin/companys/auditing');
            }
        } else {
            try {
                $company = RepoCompany::getCompanyById($company_id);
                $company->setAuditing(RepoCompany::AUDIT_REVOKED);
                if ($company->save() == false) {
                    throw new Exception('企业状态审核失败');
                }
                //dh20170922修改
            } catch (Exception $exception) {
                $this->db->rollback();
                $this->flash->error($exception->getMessage());
                return $this->response->redirect('admin/companys/auditing');
            }
            //发送驳回通知
            SMS::failedSMS((int)$company_info_data->getContactPhone(),$company_info_data->getContacts(),SMS::TYPE_APPLY);
            //推送用户消息
            $company_data = RepoCompany::getCompanyById($company_id);
            $push = new PushService();
            $content['title'] = "(".$company_info_data->getLegalName().")审核驳回通知";
            $content['body'] = $company_info_data->getLegalName().'的企业信息已被驳回,请完善企业证件信息,再提交审核!';
            $content['type'] = PushService::PUSH_TYPE_WARN;
            $push->newPushResult($content,$company_data->getDeviceId());
            $this->flash->success('企业信息审核驳回成功');
        }
        //执行审核
        $data['auditor'] = $this->session->get("auth-identity")['id'];//审核人ID

        if((new CompanyVerify())->editVerify($this->request->getPost('verify_id'), $data) == false) {
            $this->flash->error('企业审核失败');
            $this->db->rollback();
            return $this->response->redirect('admin/companys/auditing');
        }
        $this->db->commit();
        $this->logger_operation_set('审核企业证件信息','Company','edit_auditind_save',$company_id);
        return $this->response->redirect('admin/companys/auditing');
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

    private function setReportTime($payment_type,$company_id,$company_name,$report_id,$service_start_time)
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
        $report = CompanyReport::getCompanyReportById($report_id);
        $report->setEndTime($report_end_time);
        if ($report->save() === false) {
            $error = $report->getMessages()[0] ? : "企业征信报告审核设置失败";
            throw new Exception($error);
        }
    }

    /*//获取企业证件图片(废弃)
    public function get_imageAction($company_id,$where)
    {
        $this->view->disable();
        $Attachment = new Attachment();
        $Attachment = $Attachment->getById($where);
        return \OSS\Common::getOSSUrl($Attachment->getObjectId());
    }*/


    //基本设置页面
    public function basicAction()
    {
        $numberPage = $this->request->getQuery("page", "int", 1);
        $contract_num =  $this->request->getQuery('contract_num', 'trim');
        $parameters = [
            'conditions' => "contract.contract_num like '%".$contract_num."%'",
        ];

        $paginator = \Wdxr\Models\Services\Contract::getContractListPagintor($parameters, $numberPage);

        $this->view->setVar('contract_num', $this->request->getQuery('contract_num', 'trim'));
        $this->view->setVar('page', $paginator->getPaginate());
    }

    //删除合同编号
    public function contract_deleteAction()
    {
        $this->view->disable();
        if(!$this->request->isPost()) {
            $this->flash->error('非法访问');
            $this->dispatcher->forward([
                'action' => 'basic'
            ]);
            return false;
        }
        try {
            Contract::deleteContract($this->request->getPost('id'));
            $this->flash->success("编号删除成功");
            return $this->response->setJsonContent(['status' => 1, 'info' => '编号删除成功']);
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
            return $this->response->setJsonContent(['status' => 0, 'info' => $exception->getMessage()]);
        }
    }

    //客户等级设置
    public function levelAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Levels', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $paginator = \Wdxr\Models\Services\Level::getLevelListPagintor($parameters, $numberPage);
        $this->view->setVar('page', $paginator->getPaginate());
    }

    //添加级别
    public function add_levelAction()
    {
        $form = new LevelForm();
        try {
            if($this->request->isPost()) {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $level = new Level();
                    if($this->request->getPost('is_default') == Level::DEFAULT_YES){
                        if(Level::getLevelByDefault() !== false){
                            $this->flash->error('已有默认级别设置');
                        }
                    }
                    $level->addNew($this->request->getPost());
                    $this->flash->success('添加级别成功');
                    $this->dispatcher->forward([
                        'controller' => "companys",
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

        $level = Level::getLevelById($id);
        if (!$level) {
            $this->flash->error("没有找级别信息");
            return $this->dispatcher->forward([
                'action' => 'level'
            ]);
        }
        $form = new LevelForm($level, ['edit' => true]);

        if ($this->request->isPost()) {
            try {
                if($form->isValid($this->request->getPost(), $level) == false) {
                    foreach ($form->getMessages() as $message) {
                        throw new InvalidRepositoryException($message);
                    }
                } else {
                    if($this->request->getPost('is_default') == Level::DEFAULT_YES){
                        if(Level::getLevelByDefault() !== false){
                            throw new InvalidRepositoryException('已有默认级别设置');
                        }
                    }
                    if (!$level->save()) {
                        foreach ($level->getMessages() as $message) {
                            throw new InvalidRepositoryException($message);
                        }
                    }
                    $this->flash->success('修改级别信息成功');
                    $this->dispatcher->forward([
                        'controller' => "companys",
                        'action' => 'level'
                    ]);
                    return false;
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
            Level::deleteLevel($this->request->getPost('id'));
            $this->flash->success("删除成功");
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
        }
    }

    /**
     * todo 票据审核
     * @param $data_id
     */
    public function bill_verifyAction($data_id)
    {
        if($data_id) {
            //获取票据信息
            $companybill_data = CompanyBillInfo::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $data_id]]);
            if($companybill_data){
                $companybill_data = $companybill_data->toArray();
            }else{
                $this->flash->error("没有找票据数据");
                return $this->dispatcher->forward([
                    'action' => 'billVerify'
                ]);
            }

            //判断是否审核
            $Verify_data = RepoCompanyVerify::getVerifyInfoByDataId($data_id, RepoCompanyVerify::TYPE_BILL);
            if($Verify_data){
                $v = $Verify_data->toArray();
            }

        }else{
            $this->flash->error("没有找票据数据");
            return $this->dispatcher->forward([
                'action' => 'billVerify'
            ]);
        }

        if($companybill_data){
            //获取对应图片信息
            $att = new Attachment();
            if(!empty($companybill_data['rent'])){
                $companybill_data['rent'] = $this->each_att($att->getAttachmentById(explode(',',$companybill_data['rent']))->toArray());
            }
            if(!empty($companybill_data['rent_receipt'])) {
                $companybill_data['rent_receipt'] = $this->each_att($att->getAttachmentById(explode(',', $companybill_data['rent_receipt']))->toArray());
            }
            if(!empty($companybill_data['rent_contract'])) {
                $companybill_data['rent_contract'] = $this->each_att($att->getAttachmentById(explode(',', $companybill_data['rent_contract']))->toArray());
            }
            if(!empty($companybill_data['property_fee'])) {
                $companybill_data['property_fee'] = $this->each_att($att->getAttachmentById(explode(',', $companybill_data['property_fee']))->toArray());
            }
            if(!empty($companybill_data['water_fee'])) {
                $companybill_data['water_fee'] = $this->each_att($att->getAttachmentById(explode(',', $companybill_data['water_fee']))->toArray());
            }
            if(!empty($companybill_data['electricity'])) {
                $companybill_data['electricity'] = $this->each_att($att->getAttachmentById(explode(',', $companybill_data['electricity']))->toArray());
            }
            $this->view->setVar('v_status',$v['status']);
            //$this->view->setVar('company_data',$company_data);
            $this->view->setVar('companybill_data',$companybill_data);
            $this->view->setVar('data_id',$data_id);
        }else{
            $this->flash->error("没有找票据数据");
            return $this->dispatcher->forward([
                'action' => 'billVerify'
            ]);
        }
    }


    //票据审核执行
    public function edit_billAction()
    {
        $this->db->begin();
        //修改下次提交时间
        $Verify = new CompanyVerify();
        $Verify_data = RepoCompanyVerify::getVerifyInfoByDataId($this->request->getPost('data_id'), RepoCompanyVerify::TYPE_BILL);
        //获取企业信息
        $company_info_data = CompanyInfo::getByCompanyId($Verify_data->getCompanyId());
        $data = $this->request->getPost();

        //推送用户消息
        $company_data = RepoCompany::getCompanyById($Verify_data->getCompanyId());
        $push = new PushService();
        if($this->request->getPost('status') == 3){
            $content['body'] = $company_data->getName().'的票据信息已经审核通过,请及时查看!';
        }else{
            $content['body'] = $company_data->getName().'的票据信息审核被驳回,请及时查看并修改!';
        }
        $content['title'] = "(".$company_info_data->getLegalName().")票据通知";
        $content['type'] = PushService::PUSH_TYPE_WARN;
        $push->newPushResult($content,$company_data->getDeviceId());

        if($Verify->edit($Verify_data->getId(),$data) == false){
            $this->db->rollback();
            return $this->response->setJsonContent(['status' => 0, 'info' => '数据错误']);
        }

        if($this->request->getPost('status') == 3) {
            $bill = new CompanyBill();
            $bill_data = $bill->getCompanyBillByCompanyId($Verify_data->getCompanyId());
            $bill_info_data = CompanyBillInfo::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $Verify_data->getDataId()]]);
            $bill_data->setAmount($bill_data->getAmount() + $bill_info_data->getAmount());
            $bill_data->setTotal($bill_data->getTotal() + $bill_info_data->getAmount());
            if ($bill_data->save() == false) {
                $this->db->rollback();
                return $this->response->setJsonContent(['status' => 0, 'info' => '数据错误']);
            }
            //发送短信通知
            SMS::apply_success((int)$company_info_data->getContactPhone(),'票据');
        }else{
            //发送短信通知
            SMS::apply_failed((int)$company_info_data->getContactPhone(),'票据');
        }

        $this->db->commit();
        $this->logger_operation_set('审核企业票据','Companys','edit_bill',$Verify_data->getCompanyId());
        return $this->response->setJsonContent(['status' => 1]);
    }



    //获取征信审核信息
    public function report_verifyAction($verify_id)
    {
        //获取企业信息
        $verify = CompanyVerify::getCompanyVerifyBuIdAndType($verify_id,CompanyVerify::TYPE_CREDIT);

//        $company_data = RepoCompany::getCompanyById($company_id);

        if($verify !== false) {
            //获取征信报告
            $report = CompanyReport::getCompanyReportById($verify->getDataId());
            $company_data =Company::getCompanyById($verify->getCompanyId());
            $companyreport_data = $report->toArray();

        } else {
            $this->flash->error("没有征信报告");
            return $this->dispatcher->forward([
                'action' => 'reportVerify'
            ]);
        }

        if($companyreport_data) {
            //获取对应图片信息
            $companyreport_data['report'] = $this->each_att(Attachment::getAttachmentById(explode(',',$companyreport_data['report']))->toArray());

            $this->view->setVar('report_id', $report->getId());
            $this->view->setVar('v_status', $verify->getStatus());
            $this->view->setVar('verify_id', $verify->getId());
            $this->view->setVar('company_data', $company_data);
            $this->view->setVar('companyreport_data', $companyreport_data);
        } else {
            $this->flash->error("没有征信报告");
            return $this->dispatcher->forward([
                'action' => 'reportVerify'
            ]);
        }
    }

    //征信审核执行
    public function edit_reportAction()
    {
        $data = $this->request->getPost();
        try {
            $status = $this->request->getPost('status'); $verify_id = $this->request->getPost('verify_id');
            $data['auditor'] = $this->session->get("auth-identity")['id'];//审核人ID
            $company_verify = CompanyVerify::getCompanyVerifyById($verify_id);
            //获取企业信息
            $company_info = CompanyInfo::getByCompanyId($company_verify->getCompanyId());
            //推送用户消息
            $company_data = RepoCompany::getCompanyById($company_verify->getCompanyId());
        } catch (Exception $exception) {
            return $this->response->setJsonContent(['status' => 0, 'info' => $exception->getMessage()]);
        }
        try {
            $this->db->begin();
            (new CompanyVerify())->edit($verify_id, $data);
            if($status == CompanyVerify::STATUS_OK) {
                if(CompanyReport::updateReportStatus($company_verify->getDataId(), CompanyReport::STATUS_ENABLE) === false) {
                    throw new Exception("征信报告状态修改失败");
                }
                //发送短信通知
                SMS::apply_success((int)$company_info->getContactPhone(),'征信');
            }else{
                //发送短信通知
                SMS::apply_failed((int)$company_info->getContactPhone(),'征信');
            }
            $this->db->commit();
            $this->logger_operation_set('审核企业征信','Companys','edit_report',$company_verify->getCompanyId());
            //推送
            PushService::pushReport($company_data->getDeviceId(), $company_info->getLegalName(), $status);
            return $this->response->setJsonContent(['status' => 1]);
        } catch (Exception $exception) {
            $this->db->rollback();
            return $this->response->setJsonContent(['status' => 0, 'info' => $exception->getMessage()]);
        }
    }


    private function setService($id)
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
    
    /*
     * 银行列表
     */
    public function bankListAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\BankList', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        //传递搜索条件
        $this->view->setVar('name', $_REQUEST['name']);

        if($_GET['name']){
            $data['name'] = $_GET['name'];
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\BankList', $data);
            $numberPage = $_GET['page'];
            $parameters = $query->getParams();
        }

        $paginator = \Wdxr\Models\Services\BankList::getBankListPagintor($parameters, $numberPage);
        $this->view->setVar('page', $paginator->getPaginate());


    }

    public function bankListEditAction($id)
    {
        $BankList = BankList::getBankListById($id);
        if (!$BankList) {
            $this->flash->error("没有找银行数据");
            $this->dispatcher->forward([
                'action' => 'bankList'
            ]);
            return false;
        }

        if ($this->request->isPost()) {
            try {
                $data = $this->request->getPost();
                unset($data['id']);
                    if (!(new BankList())->edit($this->request->isPost('id'),$data)) {
                        foreach ($BankList->getMessages() as $message) {
                            throw new InvalidRepositoryException($message);
                        }
                    }
                    $this->flash->success('修改企业成功');
                    $this->dispatcher->forward([
                        'action' => 'bankList'
                    ]);
                    return true;
            } catch (InvalidRepositoryException $exception) {
                $this->flash->error($exception->getMessage());
            }
        }

        $this->view->setVar('bank', $BankList);
    }

    public function bankListAddAction()
    {
        try {
            if($this->request->isPost()) {

                    $banklist = new BankList();
                    $data = $this->request->getPost();
                    $array['bank_name'] = $data['bank_name'];
                    $array['bank_status'] = $data['bank_status'];
                    $banklist->addNew($data);
                    $this->flash->success('添加银行成功');
                    $this->dispatcher->forward([
                        'controller' => "companys",
                        'action' => 'bankList'
                    ]);
                    return;
            }
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
        }

    }

    public function bankListDelAction()
    {
        if($id = $this->request->getPost('id')){
            try{
                (new BankList())->deleteBankList($id);
                $this->flash->success('删除成功');
                exit;
            }
            catch(InvalidRepositoryException $e){
                $this->flash->error($e->getMessage());
                exit;
            }
        }else{
            $this->flash->error('非法请求');
        }
    }

    public function updateCompanyInfoPicAction()
    {
        if($this->request->isPost()) {
            if ($this->request->getPost('company_id') && $this->request->getPost('info_id')) {
                $company_id = $this->request->getPost('company_id');
                $company = RepoCompany::getCompanyById($company_id);
                $files = (new ToolsController())->upload('apply', $company->getDeviceId());
                if ($files) {
                    $key = array_keys($files)[0];
                    $company_info = (new CompanyInfo())->getCompanyInfo($this->request->getPost('info_id'));
                    switch ($key) {
                        case 'licence':
                            $company_info->setLicence($files[$key]);
                            break;
                        case 'account_permit':
                            $company_info->setAccountPermit($files[$key]);
                            break;
                        case 'credit_code':
                            $company_info->setCreditCode($files[$key]);
                            break;
                        case 'photo':
                            $company_info->setPhoto($files[$key]);
                            break;
                        case 'idcard_up':
                            $company_info->setIdcardUp($files[$key]);
                            break;
                        case 'idcard_down':
                            $company_info->setIdcardDown($files[$key]);
                            break;
                        default:
                            $this->flash->error("修改失败");
                            break;
                    }
                    if($company_info->save()){
                        $this->flash->success('修改成功');
                        return;
                    }
                }
            } else {
                $this->flash->error("请求失败");
            }
        }
    }

    public function updateBankPicAction()
    {
        if($this->request->isPost()) {
            if($this->request->getPost('company_id') && $this->request->getPost('category')){
                $company_id = $this->request->getPost('company_id');
                $category = $this->request->getPost('category');
                $company = RepoCompany::getCompanyById($company_id);
                $files = (new ToolsController())->upload('apply', $company->getDeviceId());
                if($files){
                    $key = array_keys($files)[0];
                    $company_bank = CompanyBank::getBankcard($company_id,$category);
                    $company_bank->setBankcardPhoto($files[$key]);
                    if($company_bank->save()){
                        $this->flash->success('修改成功');
                        return;
                    }

                }
            }else{
                $this->flash->error("请求失败");
            }
        }
    }

    /**
     * 企业票据列表
     */
    public function bill_listAction()
    {
        $name = $this->request->getQuery('name', 'trim');
        $this->view->setVar('name', $name);

        $numberPage = $this->request->getQuery("page", "int", 1);
        $builder = \Wdxr\Models\Services\Company::getCompanyBillList($name);
        $paginator = new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 20,
            'page' => $numberPage
        ]);
        $this->view->setVar('page', $paginator->getPaginate());
    }

    public function owe_bill_listAction()
    {
        $this->view->disable();
        $time = time();
        $filename = date('Y-m-d').'欠费列表';
        $bill_data= \Wdxr\Models\Services\Company::getCompanyOweBillList()->toArray();
        $data = array();
        foreach($bill_data as $key=>$val){
            $company_data = (new RepoCompany())->getById($val['company_id']);
            $company_info_data = (new CompanyInfo())->getCompanyInfo2($company_data->getInfoId());
            $admin_data = (new Admin())->getAdminsById($company_data->getAdminId());
            //级别信息
            $level_data = (new Level())->getLevel(1);
            //服务时间信息
            $service_data = (new CompanyService())->getCompanyServiceByCompanyId($val['company_id']);
            if(!$service_data){
                continue;
            }
            //第一期结束时间
            $bill_term = (new BillTerm())->getBillTermByCompanyId($val['company_id']);
            if($bill_term) {
                $bill_end_time1 = \Wdxr\Models\Services\Company::BillEndTime($bill_term->getTerm(), $bill_term->getType(), $service_data->getStartTime());

                if ($bill_end_time1 < $time) {
                    $q = $bill_end_time1 - $service_data->getStartTime();
                    $i = $bill_end_time1;
                    $k = 0;
                    while ($i < $time) {
                        $k++;
                        $i = \Wdxr\Models\Services\Company::BillEndTime($bill_term->getTerm(), $bill_term->getType(), $i);
                    }
                    $get_amount = ($i - $service_data->getStartTime() - $q) / 86400 * $level_data->getDayAmount();

                    if ($val['total'] < $get_amount) {
                        if ($company_data && $company_info_data && $admin_data) {
                            $data[$key]['name'] = $company_data->getName();
                            $data[$key]['type'] = $company_data->users->IsPartner ? '合伙人' : '普惠';
                            $data[$key]['legal_name'] = $company_info_data->getLegalName();
                            $data[$key]['phone'] = $company_info_data->getContactPhone();
                            $data[$key]['amount'] = $val['amount'];
                            $data[$key]['key'] = $k;
                            $data[$key]['admin'] = $admin_data->getName();
                        }
                    }
                }
            }

        }
        Excel::create()->title($filename)->header(['公司名称','类型','法人姓名','联系方式','亏欠金额','欠费期数','业务员'])
            ->value($data)->sheetTitle($filename)->output($filename);
    }

    /**
     * 企业退费并停止服务
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function refundAction()
    {
        if($this->request->isAjax()) {
            $company_id = $this->request->getPost('id');

            try {
                $this->db->begin();
                /**
                 * @var $company_service \Wdxr\Models\Services\CompanyService
                 */
                $company_service = Services::getService('CompanyService');
                $company_service->stopCompanyService($company_id);
                $this->db->commit();
                return $this->response->setJsonContent(['status' => 1, 'info' => '企业退费成功']);
            } catch (Exception $exception) {
                $this->db->rollback();
                $message = $exception->getMessage() ? : '企业退费失败';
                return $this->response->setJsonContent(['status' => 0, 'info' => $message]);
            }

        }
        return $this->response->setJsonContent(['status' => 0, 'info' => '异常错误']);
    }

    /**
     * 客户黑名单列表
     */
    public function black_listAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\BlackList', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        $paginator = \Wdxr\Models\Services\BlackList::getBlackListPagintor($parameters, $numberPage);
        $this->view->setVar('page', $paginator->getPaginate());
    }

    /**
     * 在黑名单恢复企业正常状态
     */
    public function refund_regainAction()
    {
        $this->view->disable();
        if(!$this->request->isPost()) {
            $this->flash->error('非法访问');
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        try {
            BlackList::delete($this->request->getPost('id'));
            $this->logger_operation_set('恢复黑名单企业正常状态','Company','refund_regain',$this->request->getPost('id'));
            $this->flash->success("企业恢复成功");
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
        }
    }

    /**
     * @param $company_id
     * 加入黑名单
     */
    public function refund_addAction($company_id)
    {
        try {
            $company_data = RepoCompany::getCompanyById($company_id);
            $this->view->setVar('company_id', $company_data->getId());
            $this->view->setVar('company_name', $company_data->getName());
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
    }

    /**
     * 保存黑名单信息
     */
    public function refund_saveAction()
    {
        $this->view->disable();
        if(!$this->request->isPost()) {
            $this->flash->error('非法访问');
            $this->dispatcher->forward([
                'action' => 'index'
            ]);
            return false;
        }
        try {
            $this->db->begin();
            (new BlackList())->addNew($this->request->getPost());

            /**加入黑名单并退费
             * @var $company_service \Wdxr\Models\Services\CompanyService
             */
            $company_service = Services::getService('CompanyService');
            $company_service->stopCompanyService($this->request->getPost('company_id'));

            $this->db->commit();

            $this->flash->success("企业已成功加入黑名单");
            $this->goBackAction();
        } catch (Exception $exception) {
            $this->db->rollback();
            $this->flash->error($exception->getMessage());
            $this->goBackAction();
        }
    }

    public function hidden_verifyAction()
    {
        $this->view->disable();
        $verify_id = $this->request->getPost('verify_id');
        $status = $this->request->getPost('status', 'int') == 1 ? 1 : 0;
        $status_name = $status == 1 ? '忽略' : '找回';

        $result = CompanyVerify::hiddenVerify($verify_id, $status);
        if($result) {
            return $this->response->setJsonContent(['status' => 1, 'info' => '该审核信息已经被'.$status_name]);
        } else {
            return $this->response->setJsonContent(['status' => 0, 'info' => $status_name.'该审核信息失败']);
        }
    }


}