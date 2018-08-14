<?php
namespace Wdxr\Modules\Company\Controllers;

use Phalcon\Cache\Backend\Redis;
use Phalcon\Di;
use Wdxr\Models\Repositories\Attachment;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\CompanyBill;
use Wdxr\Models\Repositories\CompanyInfo;
use Wdxr\Models\Repositories\CompanyRecommend;
use Wdxr\Models\Repositories\CompanyRecommends;
use Wdxr\Models\Repositories\CompanyReport;
use Wdxr\Models\Repositories\CompanyService;
use Wdxr\Models\Repositories\CompanyVerify;
use Wdxr\Models\Entities\Users;
use Wdxr\Models\Repositories\Feedback;
use Wdxr\Models\Services\SMS;
use Wdxr\Models\Repositories\Finance;
use Wdxr\Models\Repositories\Recommend;
use Wdxr\Models\Repositories\User;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class IndexController extends ControllerBase
{
    const NOT= 0;
    const STATUS_NOT = 1;
    const STATUS_FAIL = 2;
    const STATUS_OK = 3;
    const STATUS_CANCEL = 4;

    public function indexAction()
    {
        $user = $this->session->get('auth-company');
        $company = new Company();

        if(Company::getCompanyByUserId($user['id'])){

            //企业基本信息
            $company_data = Company::getCompanyByUserId($user['id']);
            if($company_data->getStatus() != 1){
                $this->audiflaseAction($company_data->getId());
            }
            $this->view->setVar('company_data', $company_data);

            //获取企业收益
            $num = 0;
            $finance = new Finance();
            $finance_data = $finance->getFinanceByCompanyName($company_data->getName());
            if($finance_data){
                $num += $finance_data;
            }
            $recommends = new Recommend();
            $recommends_data = $recommends->getRecommendByCompanyName($company_data->getName());
            if($recommends_data){
                $num += $recommends_data;
            }
            $this->view->setVar('num', $num);

            if($company_info_data = CompanyInfo::getByCompanyId($company_data->getId())) {
                $this->view->setVar('company_info_data', $company_info_data);
            } else {
                $this->resultAction(0,'企业未通过审核');
            }

            //企业服务时间
            $service = new CompanyService();
            if($service->getCompanyService($company_data->getId())){
                $service_data = $service->getCompanyService($company_data->getId());
                $this->view->setVar('service_data', $service_data);
            }else{
                $this->resultAction(0,'企业未通过审核');
            }

            //获取票据审核信息
            $company_v = new CompanyVerify();
            $company_bill = new CompanyBill();
            if($company_bill_data = $company_bill->getCompanyBillById($company_data->getBillId())){
                if($company_v_data = $company_v->getCompanyVerifyById($company_bill_data->getVerifyId())){
                    $bill_status = $company_v_data->getStatus();
                }else{
                    $bill_status = self::NOT;
                }
            }else{
                $bill_status = self::NOT;
            }
            $this->view->setVar('bill_status', $bill_status);

            //获取征信审核信息
            $company_repor = new CompanyReport();
            if($company_repor_data = $company_repor->getCompanyReportById($company_data->getReportId())){
                if($company_v_data = $company_v->getCompanyVerifyById($company_repor_data->getVerifyId()) ){
                    $repor_status = $company_v_data->getStatus();
                }else{
                    $repor_status = self::NOT;
                }
            }else{
                $repor_status = self::NOT;
            }
            $this->view->setVar('repor_status', $repor_status);

        }else{
            $this->resultAction(0,'未找到企业');
        }


    }

    //修改密码
    public function passwordAction()
    {
        if($this->request->getPost()){
            $user_info = $this->session->get('auth-company');
            $user = new User();
            $user_data = $user->getUserById($user_info['id']);
            if($this->security->checkHash($this->request->getPost('oldPassword'), $user_data->getPassword())){
                try{
                    $user->changePassword($user_info['id'], $this->request->getPost('Password'));
                    $this->resultAction(1,'修改成功');
                }
                catch (InvalidRepositoryException $exception) {
                    $this->resultAction(0,$exception->getMessage(),"/company/index/password");
                }
            }else{
                $this->resultAction(0,'原密码错误',"/company/index/password");
            }

        }
    }


    //企业反馈
    public function feedbackAction()
    {
        try{
            $feedback = new Feedback();
            $company = new Company();
            $user_info = $this->session->get('auth-company');
            $company_data = Company::getCompanyByUserId($user_info['id']);
            $data['time'] = time();
            $data['content'] = $this->request->getPost('feedback');
            $data['company_id'] = $company_data->getId();
            $feedback->addNew($data);
            $this->resultAction(1,'提交成功');
        }
        catch(InvalidRepositoryException $e){
            $this->resultAction(0,$e->getMessage());
        }
    }


    //提示方法,0 失败,1成功
    public function resultAction($status,$content,$url="/company/index/index")
    {
        $this->view->setVar('status', $status);
        $this->view->setVar('content', $content);
        $this->view->setVar('url', $url);
        $this->view->pick('index/result');
    }

//查看下级推荐企业列表
    public function recommendAction($company_id)
    {
        $recommend = new CompanyRecommend();
        try{
            $recommend_data = $recommend->getRecommends($company_id);
            $this->view->setVar('reommend_data', $recommend_data);
        }
        catch(InvalidRepositoryException $e){
            $this->resultAction(0,'暂无推荐企业');
        }

    }

    //未审核通过
    public function audiflaseAction($company_id)
    {
        $company = new Company();
        $company_data = $company->getCompanyById($company_id);
        if($company_data->getInfoId()){
            $company_info = new CompanyInfo();
            $company_info_data = $company_info->getCompanyInfoById($company_data->getInfoId());
            if($company_info_data){
                $company_verif = new CompanyVerify();
                $company_verif_data = $company_verif->getCompanyVerifyById($company_info_data->getVerifyId());
                $this->view->setVar('content', $company_verif_data->getRemark());
                $this->view->pick('index/audiflase');
            }else{
                $this->view->setVar('content', '您的证件信息不完整');
                $this->view->pick('index/audiflase');
            }
        }else{
            $this->view->setVar('content', '您的证件信息不完整');
            $this->view->pick('index/audiflase');
        }
        $this->view->setVar('content', '您的证件信息不完整');
        $this->view->pick('index/audiflase');
    }


    //修改联系人
    public function newtelAction ()
    {
        if($this->request->getPost()){

            //短信验证码部分待修改
            $redis = $this->redis->get($this->request->getPost('new_tel'));
            if(!$redis['status']){
                $this->resultAction(0,'验证码已失效',"/company/index/newtel");
            }
            if($this->request->getpost('code') == $redis['num']){
                $user_info = $this->session->get('auth-company');
                $company = new Company();
                $company_data = Company::getCompanyByUserId($user_info['id']);
                $company_info = new CompanyInfo();
                $company_info_data = $company_info->getCompanyInfoById($company_data->getInfoId());
                $company_info_data->setContract($this->request->getPost('new_user'));
                $company_info_data->setContactTitle($this->request->getPost('new_job'));
                $company_info_data->setContactPhone($this->request->getPost('new_tel'));
                if($company_info_data->save()){
                    $this->resultAction(1,'修改成功');
                }else{
                    $this->resultAction(0,'修改失败',"/company/index/newtel");
                }
            }else{
                $this->resultAction(0,'验证码已失效',"/company/index/newtel");
            }
        }
    }

    //获取短信验证码
    public function getcodeAction($phone)
    {
        if($phone){
            $num = rand(1000,9999);
            $SMS_data = SMS::verifyCodeSMS($phone,(string)$num);
            //发送成功存入redis
            $data = array();
            $data[$phone] = array('num' => $num,'status' => 1);
            return $this->response->setContent($SMS_data);
        }else{
            return $this->response->setContent(false);
        }
    }


    //征信
    public function reportAction()
    {
        //判断是否存在上传图片
        if ($this->request->hasFiles()) {
            //获取企业ID
            $company_id = $this->request->getPost('id');
            $att = new Attachment();
            //判断企业目录是否存在,不存在则创建
            $path = BASE_PATH . "/files/company/" . $company_id . "/";
            if (file_exists($path) === false) {
                mkdir($path, 0755);
            }
            $companyreport_data = array();//征信数据
            //开始上传
            $files = $this->request->getUploadedFiles();
            $data = array();//图片表数组
            $report = array();//征信表ID
            foreach ($files as $key=>$file) {

                if(preg_match("/report\.[0-9]/",$file->getKey())){
                    //生成图片名
                    $file_name = date('Ymd').time().$key.".".$file->getExtension();
                    $file->moveTo($path.$file_name);
                    //存入图片记录
                    $data['name'] = $file->getName();
                    $data['size'] = $file->getSize();
                    $data['upload_time'] = time();
                    $data['path'] = $path.$file_name;
                    $report[] = $att->addNew($data);
                }else{
                    $this->flash->error("提交失败,图片信息错误");
                    $this->dispatcher->forward([
                        'controller' => "companys",
                        'action' => 'index'
                    ]);
                    return;
                }
            }
            $report = implode(',',$report);
            $companyreport_data['report'] = $report;
            $companyreport_data['company_id'] = $company_id;
            $companyreport_data['verify_id'] = 0;
            //将图片资料信息ID存入票据表
            $companyreport = new CompanyReport();
            $companyreport_id = $companyreport->addNew($companyreport_data);//获取票据表数据ID

            //存入reportID
            $company = new Company();
            $EntityCompany = $company->getCompanyById($company_id);
            $EntityCompany->setReportId($companyreport_id);
            $EntityCompany->save();

            //添加审核记录
            $verify = new CompanyVerify();
            $verify_data['company_id'] = $company_id;
            $verify_data['auditor_id'] = 0;
            $verify_data['company_auditing'] = 1;
            $verify_data['type'] = 3;
            $verify_data['data_id'] = $companyreport_id;
            $verify_data['remark'] = '';
            $verify_id = $verify->addNew($verify_data);
            $companyreport_data = $companyreport->getCompanyReportById($companyreport_id);
            $companyreport_data->setVerifyId($verify_id);
            $companyreport_data->save();
        }else{
            $user = $this->session->get('auth-company');
            $company = new Company();
            $company_data = Company::getCompanyByUserId($user['id']);
            $this->view->setVar('id', $company_data->getId());
        }

    }


    //票据
    public function billAction()
    {


        //判断是否存在上传图片
        if ($this->request->hasFiles()) {

            //获取企业ID
            $company_id = $this->request->getPost('id');
            $att = new Attachment();
            //判断企业目录是否存在,不存在则创建
            $path = BASE_PATH."/files/company/" . $company_id . "/";
            if(file_exists($path) === false) {
                mkdir($path, 0755);
            }
            $companybill_data = array();//票据表数据
            //开始上传
            $files = $this->request->getUploadedFiles();
            $data = array();//图片表数组
            $bill_rent = array();//票据表ID
            $bill_rent_receipt = array();//票据表ID
            $bill_rent_contract = array();//票据表ID
            $bill_property_fee = array();//票据表ID
            $bill_water_fee = array();//票据表ID
            $bill_electricity = array();//票据表ID
            foreach ($files as $key=>$file) {

                if(preg_match("/rent\.[0-9]/",$file->getKey())){
                    //生成图片名
                    $file_name = date('Ymd').time().$key.".".$file->getExtension();
                    $file->moveTo($path.$file_name);
                    //存入图片记录
                    $data['name'] = $file->getName();
                    $data['size'] = $file->getSize();
                    $data['upload_time'] = time();
                    $data['path'] = $path.$file_name;
                    $bill_rent[] = $att->addNew($data);
                }elseif (preg_match("/rentreceipt\.[0-9]/",$file->getKey())){
                    //生成图片名
                    $file_name = date('Ymd').time().$key.".".$file->getExtension();
                    $file->moveTo($path.$file_name);
                    //存入图片记录
                    $data['name'] = $file->getName();
                    $data['size'] = $file->getSize();
                    $data['upload_time'] = time();
                    $data['path'] = $path.$file_name;
                    $bill_rent_receipt[] = $att->addNew($data);
                }elseif (preg_match("/rentcontract\.[0-9]/",$file->getKey())){
                    //生成图片名
                    $file_name = date('Ymd').time().$key.".".$file->getExtension();
                    $file->moveTo($path.$file_name);
                    //存入图片记录
                    $data['name'] = $file->getName();
                    $data['size'] = $file->getSize();
                    $data['upload_time'] = time();
                    $data['path'] = $path.$file_name;
                    $bill_rent_contract[] = $att->addNew($data);
                }elseif (preg_match("/propertyfee\.[0-9]/",$file->getKey())){
                    //生成图片名
                    $file_name = date('Ymd').time().$key.".".$file->getExtension();
                    $file->moveTo($path.$file_name);
                    //存入图片记录
                    $data['name'] = $file->getName();
                    $data['size'] = $file->getSize();
                    $data['upload_time'] = time();
                    $data['path'] = $path.$file_name;
                    $bill_property_fee[] = $att->addNew($data);
                }elseif (preg_match("/waterfee\.[0-9]/",$file->getKey())){
                    //生成图片名
                    $file_name = date('Ymd').time().$key.".".$file->getExtension();
                    $file->moveTo($path.$file_name);
                    //存入图片记录
                    $data['name'] = $file->getName();
                    $data['size'] = $file->getSize();
                    $data['upload_time'] = time();
                    $data['path'] = $path.$file_name;
                    $bill_water_fee[] = $att->addNew($data);
                }elseif (preg_match("/electricity\.[0-9]/",$file->getKey())){
                    //生成图片名
                    $file_name = date('Ymd').time().$key.".".$file->getExtension();
                    $file->moveTo($path.$file_name);
                    //存入图片记录
                    $data['name'] = $file->getName();
                    $data['size'] = $file->getSize();
                    $data['upload_time'] = time();
                    $data['path'] = $path.$file_name;
                    $bill_electricity[] = $att->addNew($data);
                }else{
                    $this->flash->error("提交失败,图片信息错误");
                    $this->dispatcher->forward([
                        'controller' => "companys",
                        'action' => 'index'
                    ]);
                    return;
                }
            }
            //获取企业票据信息
            $company = new Company();
            $company_bill = new CompanyBill();
            $company_data = $company->getCompanyById($company_id);
            $company_bill_data = $company_bill->getCompanyBillById($company_data->getBillId());

            if(!empty($bill_rent)){
                $bill_rent = implode(',',$bill_rent);
                //$companybill_data['rent'] = $bill_rent;
                $company_bill_data->setRent($bill_rent);
            }
            if(!empty($bill_rent_receipt)) {
                $bill_rent_receipt = implode(',', $bill_rent_receipt);
                //$companybill_data['rent_receipt'] = $bill_rent_receipt;
                $company_bill_data->setRentReceipt($bill_rent_receipt);
            }
            if(!empty($bill_rent_contract)) {
                $bill_rent_contract = implode(',', $bill_rent_contract);
                //$companybill_data['rent_contract'] = $bill_rent_contract;
                $company_bill_data->setRentContract($bill_rent_contract);
            }
            if(!empty($bill_property_fee)) {
                $bill_property_fee = implode(',', $bill_property_fee);
                //$companybill_data['property_fee'] = $bill_property_fee;
                $company_bill_data->setPropertyFee($bill_property_fee);
            }
            if(!empty($bill_water_fee)) {
                $bill_water_fee = implode(',', $bill_water_fee);
                //$companybill_data['water_fee'] = $bill_water_fee;
                $company_bill_data->setWaterFee($bill_water_fee);
            }
            if(!empty($bill_electricity)) {
                $bill_electricity = implode(',', $bill_electricity);
                //$companybill_data['electricity'] = $bill_electricity;
                $company_bill_data->setElectricity($bill_electricity);
            }

            //添加审核记录
            $verify = new CompanyVerify();
            $verify_data['company_id'] = $company_id;
            $verify_data['auditor_id'] = 0;
            $verify_data['company_auditing'] = 1;
            $verify_data['type'] = 2;
            $verify_data['data_id'] = $company_data->getBillId();
            $verify_data['remark'] = '';
            $verify_id = $verify->addNew($verify_data);
            $company_bill_data->setVerifyId($verify_id);

            $company_bill_data->save();
//            $companybill_data['company_id'] = $company_id;
//            $companybill_data['verify_id'] = 0;
//            //将图片资料信息ID存入票据表
//            $companybill = new CompanyBil();
//            $companybill_id = $companybill->addNew($companybill_data);//获取票据表数据ID

//            //存入billID
//            $company = new Company();
//            $EntityCompany = $company->getCompanyById($company_id);
//            $EntityCompany->setBillId($companybill_id);
//            $EntityCompany->save();



        }else{
            $user = $this->session->get('auth-company');
            $company = new Company();
            $company_data = Company::getCompanyByUserId($user['id']);
            $this->view->setVar('id', $company_data->getId());
        }

    }




}