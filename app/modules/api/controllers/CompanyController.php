<?php
namespace Wdxr\Modules\Api\Controllers;

use Lcobucci\JWT\JWT;
use Phalcon\Exception;
use Wdxr\Auth\Auth;
use Wdxr\Auth\Exception as AuthException;
use Wdxr\Auth\UserAuth;
use Wdxr\Models\Entities\Companys;
use Wdxr\Models\Exception\ModelException;
use Wdxr\Models\Repositories\Achievement;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Models\Repositories\CommissionList;
use Wdxr\Models\Repositories\CompanyBank;
use Wdxr\Models\Repositories\CompanyInfo;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Models\Repositories\CompanyPayment;
use Wdxr\Models\Repositories\CompanyRecommend;
use Wdxr\Models\Repositories\CompanyRecommends;
use Wdxr\Models\Repositories\CompanyReport;
use Wdxr\Models\Repositories\ContractLog;
use Wdxr\Models\Repositories\Devices;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Repositories\Finance;
use Wdxr\Models\Repositories\Level;
use Wdxr\Models\Repositories\Loan;
use Wdxr\Models\Repositories\Manage;
use Wdxr\Models\Repositories\Messages;
use Wdxr\Models\Repositories\Recommend;
use Wdxr\Models\Repositories\Regions;
use Wdxr\Models\Repositories\Repositories;
use Wdxr\Models\Repositories\Statistics;
use Wdxr\Models\Repositories\User;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Services\CompanyBenefit;
use Wdxr\Models\Repositories\CompanyService;
use Wdxr\Models\Services\Company;
use Wdxr\Models\Repositories\CompanyBill as RepoCompanyBill;
use Wdxr\Models\Entities\CompanyBill as EntityCompanyBill;
use Wdxr\Models\Services\CompanyBill;
use Wdxr\Models\Services\CompanyVerify;
use Wdxr\Models\Services\Contract as ServiceContract;
use Wdxr\Models\Services\Contract;
use Wdxr\Models\Services\Services;
use Wdxr\Models\Services\UploadService;
use Wdxr\Request;

class CompanyController extends ControllerBase
{

    /**
     * 企业查询
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function searchAction()
    {
        $name = $this->request->getPost('name');
        $page = $this->request->getPost('page') ? : 1;
        try {
            $data = Company::searchCompany(JWT::getUid(), $name, $page);
            return $this->json(self::RESPONSE_OK, $data, '获取公司列表成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 获取企业基本信息
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function infoAction()
    {
        $id = $this->request->getPost('company_id');
        try {
            $service = CompanyService::getCompanyService($id);
            $benefit = CompanyBenefit::getCurrentCompanyBenefit($id);
            $company = \Wdxr\Models\Repositories\Company::getCompanyById($id);
            $info = CompanyInfo::getCompanyInfoById($company->getInfoId());
            $address = Regions::getRegionName($info->getProvince())->name.
                    Regions::getRegionName($info->getCity())->name.
                    Regions::getRegionName($info->getDistrict())->name.
                    $info->getAddress();
            $start_time = $service === false ? "---" : date("Y/m/d", $service->getStartTime());
            $end_time = $service === false ? "---" : date("Y/m/d", $service->getEndTime());

            //报销状态
            $status = \Wdxr\Models\Repositories\CompanyBill::getBillVerifyStatus($id);

            $data = [
                'name' => $company->getName(),
                'address' => $address,
                'benefit' => $benefit,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'status' => $status['status'],
                'status_name' => $status['status_name']
            ];
            return $this->json(self::RESPONSE_OK, $data, '获取公司列表成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 推荐的企业列表
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function recommendAction()
    {
        try {
            $company_id = $this->request->getPost("company_id");
            $recommends = Repositories::getRepository('CompanyRecommend')->getRecommends($company_id);
            return $this->json(self::RESPONSE_OK, $recommends, '获取推荐的企业列表成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_OK, null, '没有推荐企业');
        }
    }

    /**
     * 补交房租票据
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function rentDataAction()
    {
        $company_id = $this->request->getPost('company_id');
        $rent = trim($this->request->getPost("rent"), ',');
        $rent_receipt = trim($this->request->getPost("rent_receipt"), ',');
        $rent_contract = trim($this->request->getPost("rent_contract"), ',');
        $amount = $this->request->getPost('amount');
        if (empty($rent)) {
            return $this->json(self::RESPONSE_FAILED, null, "请上传房租打款凭证");
        }
        if(empty($amount) || $amount == 0){
            return $this->json(self::RESPONSE_FAILED, null, "请填写发票金额");
        }
        try {
            CompanyBill::addCompanyBill($company_id, RepoCompanyBill::TYPE_RENT, [$rent, $rent_receipt, $rent_contract], $amount, JWT::getUid());
            return $this->json(self::RESPONSE_OK, null, '补交房屋租赁票据成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 补交电费发票
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function electricityDataAction()
    {
        try {
            $company_id = $this->request->getPost('company_id');
            $electricity = trim($this->request->getPost("electricity"), ',');
            $amount = $this->request->getPost('amount');
            if(empty($electricity)) {
                throw new Exception("请上传电费发票");
            }
            if(empty($amount) || $amount == 0){
                throw new Exception("请填写发票金额");
            }

            CompanyBill::addCompanyBill($company_id, RepoCompanyBill::TYPE_ELECTRICITY, $electricity, $amount, JWT::getUid());

            return $this->json(self::RESPONSE_OK, null, '补交电费票据成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 补交水费发票
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function waterDataAction()
    {
        try {
            $company_id = $this->request->getPost('company_id');
            $water_fee = trim($this->request->getPost("water_fee"), ',');
            $amount = $this->request->getPost('amount');
            if(empty($water_fee)) {
                throw new Exception("请上传水费发票");
            }
            if(empty($amount) || $amount == 0){
                throw new Exception("请填写发票金额");
            }

            CompanyBill::addCompanyBill($company_id, RepoCompanyBill::TYPE_WATER_FEE, $water_fee, $amount, JWT::getUid());

            return $this->json(self::RESPONSE_OK, null, '补交水费票据成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 补交物业费发票
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function propertyDataAction()
    {
        try {
            $company_id = $this->request->getPost('company_id');
            $property_fee = trim($this->request->getPost("property_fee"), ',');
            $amount = $this->request->getPost('amount');
            if(empty($property_fee)) {
                throw new Exception("请上传物业费发票");
            }
            if(empty($amount) || $amount == 0){
                throw new Exception("请填写发票金额");
            }

            CompanyBill::addCompanyBill($company_id, RepoCompanyBill::TYPE_PROPERTY_FEE, $property_fee, $amount, JWT::getUid());

            return $this->json(self::RESPONSE_OK, null, '补交物业费票据成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 补交征信报告
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function reportDataAction()
    {
        try {
            $company_id = $this->request->getPost("company_id");
            $report = trim($this->request->getPost("report"), ',');
            $type = $this->request->getPost("type");
            if (empty($report)) {
                throw new Exception("请上传征信报告");
            }
//            if(empty($type)){
//                throw new Exception("请提交征信类别");
//            }
            $this->db->begin();
            CompanyReport::newReport($company_id, JWT::getUid(), $report,$type);
            $this->db->commit();

            return $this->json(self::RESPONSE_OK, null, '补交征信报告成功');
        } catch (InvalidRepositoryException $exception) {
            $this->db->rollback();
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 补交记录
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function getVerifyListAction()
    {
        $company_id = $this->request->getPost('company_id');
        $page = $this->request->getPost("page") ? : 1;

        //排序
        $orderby = 'verify_time desc';
        if($this->request->getPost('sort')){
            if($this->request->getPost('sort') == 1){
                $orderby = "amount asc";
            }elseif($this->request->getPost('sort') == 2){
                $orderby = "amount desc";
            }elseif($this->request->getPost('sort') == 3){
                $orderby = "apply_time asc";
            }elseif($this->request->getPost('sort') == 4){
                $orderby = "apply_time desc";
            }
        }

        try {
            $list = CompanyVerify::getCompanyVerifyBillList($company_id, $page,$orderby);
            return $this->json(self::RESPONSE_OK, $list, '获取补交记录成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 合同查询
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function getCompanyContractAction()
    {
        $id = $this->request->getPost('company_id');
        try {
            $company = RepoCompany::getCompanyById($id);
            if($company->getStatus() != RepoCompany::STATUS_ENABLE) {
                throw new Exception("当前企业已经被锁定，无法查看合同");
            }
            if($company->getAuditing() != RepoCompany::AUDIT_OK) {
                throw new Exception("当前企业尚未通过审核，无法查看合同");
            }
            $url = $this->url->get(Request::getDomain().'/company/contract', [
                'company_id' => $id,
                'token' => $this->request->getPost('token')
            ], false);
            return $this->json(self::RESPONSE_OK, $url, '获取合同成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 获取合同地址
     * @return mixed
     */
    public function getContractAction()
    {
        $company_id = $this->request->getPost('company_id');
        $service = Services::Hprose('contract');
        $result = $service->getNewContract($company_id);
        if($result === false){
            return $this->json(self::RESPONSE_FAILED, null, $service->getError());
        }
        if($service->setContractLog($result['id'], JWT::getUid(), ContractLog::TYPE_VIEW) !== true) {
            return $this->json(self::RESPONSE_FAILED, null, $service->getError());
        }
        return $this->json(self::RESPONSE_OK, $result, '获取合同信息成功');
    }

    /**
     * 查看合同
     * @return mixed
     */
    public function contractAction()
    {
        $company_id = $this->request->getQuery('company_id');
        $view = Contract::getContractView($company_id);
        return $this->response->setContent($view);
    }

    /*
     * 企业列表
     */
    public function companyListAction()
    {
        $page = $this->request->getPost('page', 'int', 1);
        $limit = ($page-1)*10;
        $user_id = JWT::getUid();
        //判断当前登陆的是否为合伙人
        $device_data = UserAdmin::getUser($user_id);
        if ($device_data->getType() == UserAdmin::TYPE_USER) {
            $device_company = (new \Wdxr\Models\Repositories\Company())->getCompanyByUserId($device_data->getUserId());
            if ($device_company) {
                $service = CompanyService::getCompanyService($device_company->getId());
                $data['id'] = $device_company->getId();
                $data['name'] = $device_company->getName();
                $data['status'] = $device_company->getStatus();
                $data['payment'] = $service->getPaymentStatus();
                $data['auditing'] = $device_company->getAuditing();
                $data['company_status'] = $this->getCompanyStatusAction($data['payment'], $data['auditing'], $data['status']);
                if ($data['auditing'] == 1 || $data['auditing'] == 2) {
                    $data['jump'] = 1;
                } else {
                    $data['jump'] = 0;
                }
            } else {
                $data = null;
            }
        } else {
            $data = null;
        }
        //企业列表
        $company = new \Wdxr\Models\Repositories\Company();
        if ($device_data->getType() == UserAdmin::TYPE_USER) {
            $company_data = $company->getCompanyByDevice($user_id, $limit);
        } else {
            $company_data = $company->getCompanyByAdminId($device_data->getUserId(), $limit);
        }
        if ($company_data->toArray()) {
            $company_data = $company_data->toArray();
            foreach ($company_data as $key => $val) {
                $company_service = CompanyService::getCompanyService($val['id']);
                if ($company_service === false) {
                    $company_data[$key]['company_status'] = '未知状态';
                } else {
                    $company_data[$key]['company_status'] = $this->getCompanyStatusAction($company_service->getPaymentStatus(), $val['auditing'], $val['status']);
                }
                if ($val['auditing'] == 1 || $val['auditing'] == 2) {
                    $company_data[$key]['jump'] = 1;
                } else {
                    $company_data[$key]['jump'] = 0;
                }
            }
            return $this->json(self::RESPONSE_OK, array('partner' => $data, 'list' => $company_data), '获取企业成功');
        } else {
            return $this->json(self::RESPONSE_OK, array('partner' => $data,'list' => []), '没有企业');
        }
    }


    /*
     * 搜索企业列表
     */
    public function selectCompanyListAction()
    {
        if($this->request->isPost()){
            $where = $this->request->getPost('where');
            if(!$where){
                return $this->json(self::RESPONSE_FAILED, null, '请输入搜索参数');
            }
            $user_id = JWT::getUid();
            try{
                $company = new \Wdxr\Models\Repositories\Company();
                $company_data = $company->getSelectCompany($user_id,$where);
                $company_data_array = $company_data->toArray();
                return $this->json(self::RESPONSE_OK, $company_data_array, '获取企业成功');
            }catch (InvalidRepositoryException $exception){
                return $this->json(self::RESPONSE_OK, null, $exception->getMessage());
            }
        }
        return $this->json(self::RESPONSE_FAILED, null, '非法访问');
    }


    /*
     * 判断企业状态
     */
    private function getCompanyStatusAction($payment, $auditing, $status)
    {
        switch($payment) {
            case 0:
                return '未支付';
                break;
            case 1:
                switch ($auditing) {
                    case 0:
                        return '未申请';
                        break;
                    case 1:
                        return '已申请';
                        break;
                    case 2:
                        switch($status) {
                            case 0:
                                return '锁定';
                                break;
                            case 1:
                                return '正常';
                                break;
                            default:
                                return '未知状态';
                                break;
                        }
                        break;
                    case 3:
                        return '申请已撤销';
                        break;
                    default:
                        return '未知状态';
                        break;
                }
                break;
            case 2:
                return '缴费待核实';
                break;
            case 3:
                return '缴费已撤销';
                break;
            case 3:
                return '缴费未通过';
                break;
            default:
                return '未知状态';
                break;
        }
    }


    /*
     * 企业详细信息
     */
    public function getCompanyInfoAction()
    {

        $company_id = $this->request->getPost('company_id');
        //企业信息
        $company_data = \Wdxr\Models\Repositories\Company::getCompanyById($company_id);
        $company_info_data = CompanyInfo::getCompanyInfoById($company_data->getInfoId());
        if($company_data == false){
            return $this->json(self::RESPONSE_FAILED, null, '企业不存在');
        }
        //企业级别
        $level_data = Level::getLevelByCompanyId($company_id);
        $data['level_name'] = $level_data->getLevelName();

        //企业合同号
        $contract = \Wdxr\Models\Repositories\Contract::getInUseContractNum($company_id);
        $data['contract_num'] = $contract ? $contract->getContractNum() : '无';

        //企业登陆账号
        $user = (new User())->getById($company_data->getUserId());
        $data['account'] = $user->getName();
        //每日应报销
        $data['finance'] = '无';
        $start_time = mktime(0, 0 ,0, date('m'), date('d'), date('Y'));
        $end_time = $start_time + 86400;
        $finance_data = Finance::getFinanceByCompanyId($company_info_data->getLegalName(),date('Y-m-d H:i:s',$start_time),date('Y-m-d H:i:s',$end_time));
        $recommend_data = Recommend::getRecommendByCompanyId($company_info_data->getLegalName(),date('Y-m-d H:i:s',$start_time),date('Y-m-d H:i:s',$end_time));
        $manage_data = Manage::getManageByCompanyId($company_info_data->getLegalName(),date('Y-m-d H:i:s',$start_time),date('Y-m-d H:i:s',$end_time));

        if($finance_data->toArray() != []){
            $finance_money = 0;
            foreach($finance_data as $key=>$val){
                $finance_money += $val->getMoney();
            }
            $data['finance'] = '';
            $data['finance'] .= '￥'.$finance_money;
        }

        if($recommend_data ->toArray() != []){
            $recommend_money = 0;
            foreach($recommend_data as $key=>$val){
                $recommend_money += $val->getMoney();
            }
            $data['finance'] .= '+￥'.$recommend_money;
        }

        if($manage_data ->toArray() != []){
            $manage_money = 0;
            foreach($manage_data as $key=>$val){
                $manage_money += $val->getMoney();
            }
            $data['finance'] .= '+￥'.$manage_money;
        }

        //共计报销金额
        $finance = new Finance();
        $data['finance_amount'] = $finance->getFinanceByMakecoll($company_id)?:0;
        //共计推荐奖金
        $recommend = new Recommend();
        $data['recommend_amount'] = $recommend->getRecommendByMakecoll($company_id)?:0;
        //共计管理奖金
        $manage = new Manage();
        $data['manage_amount'] = $manage->getManageByMakecoll($company_id)?:0;

        //是否事业合伙人
        $user_data = User::getUserById($company_data->getUserId());
        if($user_data->getIsPartner() == 1){
            $data['partner'] = '是';
        }else{
            $data['partner'] = '否';
        }
        //合伙人奖金
        $statistic = new Statistics();
        $data['partner_amount'] = $statistic->getStatisticsByCompanyId($company_id)?:0;

        //共计盈利
        $data['amount'] = $data['finance_amount']+$data['recommend_amount']+$data['manage_amount']+$data['partner_amount'];

        //发票总额
        $bill = new \Wdxr\Models\Repositories\CompanyBill();
        $bill_data = $bill->getCompanyBillByCompanyId($company_id);
        $data['bill_total'] = '￥'.$bill_data->getTotal();

        //服务期限
        $service_data = CompanyService::getCompanyService($company_id);
        $data['service_time'] = date('Y/m/d',$service_data->getStartTime()).'-'.date('Y/m/d',$service_data->getEndTime());

        //征信状态
        if($service_data->getReportStatus()){
            if($service_data->getReportStatus() == CompanyReport::STATUS_DISABLE){
                $data['report_status'] = '未上传';
            }else{
                $data['report_status'] = '正常';
            }
        }else{
            $data['report_status'] = '未上传';
        }

        //报销状态
        $status = \Wdxr\Models\Repositories\CompanyBill::getBillVerifyStatus($company_id);
        $data['status'] = $status['status_name'];

        //推荐人
        $companyRecommend = (new \Wdxr\Models\Repositories\Company())->Byid($company_data->getRecommendId());
        if($companyRecommend){
            $data['recommend'] = $companyRecommend->getName();

            //管理人
            $companyManage  = (new \Wdxr\Models\Repositories\Company())->Byid($companyRecommend->getRecommendId());
            if($companyManage){
                $data['manage'] = $companyManage->getName();
            }else{
                $data['manage'] = '无';
            }

        }else{
            $data['recommend'] = '无';
            $data['manage'] = '无';
        }



        return $this->json(self::RESPONSE_OK, $data, '获取详细信息成功');
    }

    /*
     * 明细
     */
    public function detailAction()
    {
        if($this->request->isPost()){
            $page = $this->request->getPost('page');
            if (!$page) {
                $page = 1;
            }
            $limit = ($page - 1) * 10;
            $company_id = $this->request->getPost('company_id');
            $company_info = CompanyInfo::getByCompanyId($company_id);
            if($company_info == false){
                return $this->json(self::RESPONSE_OK, '', '没有明细列表');
            }
            $bank = CompanyBank::getBankcard($company_id,CompanyBank::CATEGORY_MASTER);
            if($bank === false){
                return $this->json(self::RESPONSE_FAILED, '', '获取不到银行卡信息');
            }
            $finance = new Finance();
            $finance_data = $finance->getFinanceBybyid($bank->getNumber());
            if($finance_data->toArray() != []){
                $finance_data_array = $finance_data->toArray();
                foreach($finance_data_array as $key=>$val){
                    $finance_data_array[$key]['type'] = '每日补助';
                }

                $recommend = new Recommend();
                $recommend_data = $recommend->getRecommendBybyid($bank->getNumber());
                if($recommend_data->toArray() != []){
                    $recommend_data_array = $recommend_data->toArray();
                    foreach($recommend_data_array as $key=>$val){
                        $recommend_data_array[$key]['type'] = '推荐奖金';
                    }
                }else{
                    $recommend_data_array = [];
                }

                $manage = new Manage();
                $manage_data = $manage->getManageBybyid($bank->getNumber());
                if($manage_data->toArray() != []){
                    $manage_data_array = $manage_data->toArray();
                    foreach($manage_data_array as $key=>$val){
                        $manage_data_array[$key]['type'] = '管理奖金';
                    }
                }else{
                    $manage_data_array = [];
                }
                $array = array_merge_recursive($finance_data_array,$recommend_data_array,$manage_data_array);

                //排序
                $sort_str = $this->request->getPost('sort') ?: 4;
                    if($sort_str == 1){
                        $type = 'money';
                        $sort_type = SORT_ASC;
                    }elseif($sort_str == 2){
                        $type = 'money';
                        $sort_type = SORT_DESC;
                    }elseif($sort_str == 3){
                        $type = 'time';
                        $sort_type = SORT_ASC;
                    }elseif($sort_str == 4){
                        $type = 'time';
                        $sort_type = SORT_DESC;
                    }
                    $sort=array();
                    foreach($array as $val){
                        $sort[]=$val["$type"];
                    }
                    array_multisort($sort, $sort_type, $array);
                //排序
                $array = array_slice($array,$limit,10);

                foreach ($array as $key=>$val){
                    $array[$key]['time'] = date('Y-m-d',strtotime($val['time']));
                }

                return $this->json(self::RESPONSE_OK, $array, '获取明细列表成功');
            }

            return $this->json(self::RESPONSE_OK, '', '没有明细列表');
        }
        return $this->json(self::RESPONSE_FAILED, '', '非法访问');
    }


    /*
     *推荐企业列表
     */
    public function recommendCompanyAction()
    {
        if($this->request->isPost()) {
            $page = $this->request->getPost('page', 'int', 1);
            $limit = ($page - 1) * 10;

            $company_id = $this->request->getPost('company_id');
            //获取所有企业关系列表
            $CompanyRecommends = new CompanyRecommends();
            $CompanyRecommends = $CompanyRecommends->getCompanyRecommendsByrecommend_id($company_id)->toArray();
            if($CompanyRecommends){
                $data = array();
                try{
                    foreach ($CompanyRecommends as $key=>$val){
                        $recommend_id_company = (new \Wdxr\Models\Repositories\Company())->getById($val['recommend_id']);
                        $data[$key]['id'] = $recommend_id_company->getId();
                        $data[$key]['recommend_id'] = $recommend_id_company->getName();
                        $service_data = (new CompanyService())->getCompanyServiceByCompanyId($recommend_id_company->getId());
                        if($service_data != false){
                            $data[$key]['start_time'] = date('Y/m/d',$service_data->getStartTime());
                            $data[$key]['end_time'] = date('Y/m/d',$service_data->getEndTime());
                        }else{
                            $data[$key]['start_time'] = '----';
                            $data[$key]['end_time'] = '----';
                        }
                        //推荐企业详细信息
                        $company_data = (new \Wdxr\Models\Repositories\Company())->getById($recommend_id_company->getId());
                        $company_info_data = CompanyInfo::getCompanyInfoById($recommend_id_company->getInfoId());

//                        $data[$key]['company_name'] = $company_data->getName();
//                        //服务时间
//                        $service_data = (new CompanyService())->getCompanyServiceByCompanyId($recommend_id_company->getId());
//                        $data[$key]['Rstart_time'] = date('Y/m/d',$service_data->getStartTime())?:'无';
//                        $data[$key]['Rend_time'] = date('Y/m/d',$service_data->getEndTime())?:'无';

                        //企业收入
                        $recommend = new Recommend();
                        $data[$key]['recommend_amount'] = $recommend->getRecommendByMakecoll($company_data->getId())?:0;
                        //管理收入
                        $manage = new Manage();
                        $data[$key]['manage_amount'] = $manage->getManageByMakecoll($company_data->getId())?:0;

                        $data[$key]['amount'] = $data[$key]['recommend_amount'] + $data[$key]['manage_amount'];
                    }
                }catch (InvalidRepositoryException $exception){
                    return $this->json(self::RESPONSE_OK, '', '没有推荐企业');
                }
                //分页
                $data = array_slice($data,$limit,10);
                return $this->json(self::RESPONSE_OK, $data, '获取推荐企业成功');
            }else{
                return $this->json(self::RESPONSE_OK, '', '没有推荐企业');
            }

        }
        return $this->json(self::RESPONSE_FAILED, '', '非法访问');
    }


    /*
     * 推荐企业详情
     */
    public function recommendCompanyInfoAction()
    {
        if($this->request->isPost()){
            try{
            $company_id = $this->request->getPost('company_id');
            //企业信息
            $company_data = (new \Wdxr\Models\Repositories\Company())->getById($company_id);
            $company_info_data = CompanyInfo::getCompanyInfoById($company_data->getInfoId());
            $data['company_name'] = $company_data->getName();
            //服务时间
            $service_data = (new CompanyService())->getCompanyServiceByCompanyId($company_id);
            $data['start_time'] = $service_data->getStartTime();
            $data['end_time'] = $service_data->getEndTime();

            //企业收入
            $recommend = new Recommend();
            $data['recommend_amount'] = $recommend->getRecommendByMakecoll($company_data->getId())?:0;
            //管理收入
            $manage = new Manage();
            $data['manage_amount'] = $manage->getManageByMakecoll($company_data->getId())?:0;

            $data['amount'] = $data['recommend_amount']+$data['manage_amount'];
            }
            catch (InvalidRepositoryException $exception){
                return $this->json(self::RESPONSE_FAILED, '', $exception->getMessage());
            }
            return $this->json(self::RESPONSE_OK, $data, '获取企业信息成功');
        }
        return $this->json(self::RESPONSE_FAILED, '', '非法访问');
    }

    /*
     * 管理企业列表
     */
    public function manageCompanyAction()
    {
        if($this->request->isPost()) {
            $page = $this->request->getPost('page');
            if (!$page) {
                $page = 1;
            }
            $limit = ($page - 1) * 10;

            $company_id = $this->request->getPost('company_id');
            //获取推荐企业
            $CompanyRecommends = new CompanyRecommends();
            $CompanyRecommends_data = $CompanyRecommends->getCompanyRecommendsByrecommend_id($company_id)->toArray();
            if($CompanyRecommends_data){
                //获取被推荐企业集合
                $recommend_id_str = implode(array_column($CompanyRecommends_data,'recommend_id'),',');
                $string = "recommender in ($recommend_id_str)";
                $CompanyRecommends_R_data = $CompanyRecommends->getCompanyRecommendsBystring($string)->toArray();
                if($CompanyRecommends_R_data){
                    //获取列表的企业信息
                    $data = array();
                    try{
                        foreach ($CompanyRecommends_R_data as $key=>$val){
                            $recommend_id_company = (new \Wdxr\Models\Repositories\Company())->getById($val['recommend_id']);
                            $recommender_company = (new \Wdxr\Models\Repositories\Company())->getById($val['recommender']);
                            $data[$key]['recommend_id'] = $recommend_id_company->getName();
                            $data[$key]['recommender'] = $recommender_company->getName();
                            $service_data = (new CompanyService())->getCompanyServiceByCompanyId($recommend_id_company->getId());
                            if($service_data != false){
                                $data[$key]['start_time'] = date('Y/m/d',$service_data->getStartTime());
                                $data[$key]['end_time'] = date('Y/m/d',$service_data->getEndTime());
                                $data[$key]['time'] = date('Y/m/d',strtotime('-1 day',$service_data->getStartTime()));
                            }else{
                                $data[$key]['start_time'] = '无';
                                $data[$key]['end_time'] = '无';
                                $data[$key]['time'] = '无';
                            }
                        }
                    }catch (InvalidRepositoryException $exception){
                        return $this->json(self::RESPONSE_OK, '', '没有管理企业');
                    }

                    //排序
                    if($this->request->getPost('sort')){
                        if($this->request->getPost('sort') == 1){
                            $type = 'start_time';
                            $sort_type = SORT_ASC;
                        }elseif($this->request->getPost('sort') == 2){
                            $type = 'start_time';
                            $sort_type = SORT_DESC;
                        }
                        $sort=array();
                        foreach($data as $val){
                            $sort[]=$val["$type"];
                        }
                        array_multisort($sort, $sort_type, $data);
                    }//排序
                    $data = array_slice($data,$limit,10);
                    return $this->json(self::RESPONSE_OK, $data, '获取管理企业成功');
                }
                return $this->json(self::RESPONSE_OK, '', '没有管理企业');
            }else{
                return $this->json(self::RESPONSE_OK, '', '没有管理企业');
            }

        }
        return $this->json(self::RESPONSE_FAILED, '', '非法访问');
    }

    /*
     * 首页事业合伙人排名
     */
    public function partnerRankingAction()
    {
        if($this->request->isPost()) {
            $user_id = JWT::getUid();
            $page = $this->request->getPost('page');
            if (!$page) {
                $page = 1;
            }
            $limit = ($page - 1) * 10;
            $company = new \Wdxr\Models\Repositories\Company();
            $company_data = $company->getPartnerCompany($user_id);
            if ($company_data->toArray() != []) {
                $company_data_array = $company_data->toArray();
                foreach ($company_data_array as $key => $val) {
                    $company_data_array[$key]['money'] = 0;
                    //获取所有企业关系列表
                    $CompanyRecommends = new CompanyRecommends();
                    $CompanyRecommends = $CompanyRecommends->getCompanyRecommendsByrecommend_id($val['id'])->toArray();
                    if ($CompanyRecommends) {
                        foreach ($CompanyRecommends as $k => $v) {
                            $recommend_service = CompanyService::getCompanyService($v['recommend_id']);
                            if($recommend_service === false){
                                $company_data_array[$key]['money'] += 0;
                            }else{
                                $company_data_array[$key]['money'] += (new Level())->getLevelMoney($recommend_service->getLevelId());
                            }
                        }
                    }
                }
                //按金额排序
                foreach ($company_data_array as $a => $b) {
                    $sort[] = $b['money'];
                }
                array_multisort($sort, SORT_DESC, $company_data_array);
                $company_data_array = array_slice($company_data_array,$limit,10);

                return $this->json(self::RESPONSE_OK, $company_data_array, '获取事业合伙人排行成功');
            } else {
                return $this->json(self::RESPONSE_FAILED, '', '暂无事业合伙人,请继续努力');
            }
        }
        return $this->json(self::RESPONSE_FAILED, '', '非法访问');
    }

    /*
     * 首页广播通知消息
     */
    public function informAction()
    {
        $message = new Messages();
        $message_data = $message->getInformMessages();
        if($message_data){
            return $this->json(self::RESPONSE_OK, $message_data, '获取消息成功');
        }
        return $this->json(self::RESPONSE_FAILED, '', '暂无消息');
    }

    /*
     * 审核列表
     */
    public function getAuditListAction()
    {
        if($this->request->isPost()) {
            $user_id = JWT::getUid();
            $page = $this->request->getPost('page');
            if (!$page) {
                $page = 1;
            }
            $screen = $this->request->getPost('screen') ?: false;
            $limit = ($page - 1) * 10;
            $company = new \Wdxr\Models\Repositories\Company();
            $company_data = $company->getByDeviceId($user_id);
            if($company_data->toArray()){
                $company_data_array = $company_data->toArray();
                foreach ($company_data_array as $key=>$val){
                    $company_verify = new \Wdxr\Models\Repositories\CompanyVerify();
                    $company_verify_data = $company_verify->getCompanyVerifyByCompanyId($val['id'],\Wdxr\Models\Repositories\CompanyVerify::TYPE_DOCUMENTS,$screen);
                    if($company_verify_data){
                        $company_data_array[$key]['apply_time'] = date('Y/m/d H:i:s',$company_verify_data->getApplyTime());
                        $company_data_array[$key]['verify_time'] = $company_verify_data->getVerifyTime() ? date('Y/m/d H:i:s',$company_verify_data->getVerifyTime()) : '';
                        $company_data_array[$key]['status'] = $company_verify_data->getStatus();
                        $company_data_array[$key]['status_name'] = $company_verify->getStatusName($company_verify_data->getStatus());
                        $company_data_array[$key]['remark'] = $company_verify_data->getRemark() ?: '信息不完善';
                    }else{
                        unset($company_data_array[$key]);
                    }
                }
                if(!$company_data_array){
                    return $this->json(self::RESPONSE_OK, '', '没有企业信息');
                }
                //排序
                if(!$this->request->getPost('sort') || $this->request->getPost('sort') == 1){
                    $sort_sc = SORT_DESC;
                }else{
                    $sort_sc = SORT_ASC;
                }
                foreach($company_data_array as $k=>$v){
                    $sort[] = $v['apply_time'];
                }
                array_multisort($sort,$sort_sc,$company_data_array);
                $company_data_array = array_slice($company_data_array,$limit,10);
                return $this->json(self::RESPONSE_OK, $company_data_array, '获取消息成功');
            }
            return $this->json(self::RESPONSE_OK, '', '没有企业信息');
        }
        return $this->json(self::RESPONSE_FAILED, '', '非法访问');
    }

    /*
     * 我的客户
     */
    public function customerAction()
    {
        $page = $this->request->getPost('page');
        if(!$page){
            $page = 1;
        }
        $limit = ($page-1)*10;

        //获取所有企业
        try{
            $user_id = JWT::getUid();
            //获取用户唯一标识
            $user_admin_data = UserAdmin::getUser($user_id);
            $company = new \Wdxr\Models\Repositories\Company();
            if($user_admin_data->getType() == UserAdmin::TYPE_ADMIN){
                $company_data = $company->getCustomerByDevice($user_id,$user_admin_data->getUserId());
            }else{
                //获取合伙人企业ID
                $device_company_data = \Wdxr\Models\Repositories\Company::getCompanyByUserId($user_admin_data->getUserId());
                $company_data = $company->getCustomerByCompany($device_company_data->getId());
            }

        }catch (InvalidRepositoryException $exception){
            return $this->json(self::RESPONSE_OK, null, '没有企业');
        }
        if($company_data->toArray()){
            $company_data = $company_data->toArray();
            foreach ($company_data as $key=>$val){
                //客户状态
                $company_data[$key]['company_status'] = $this->getCompanyStatusAction($val['payment'],$val['auditing'],$val['status']);
                //服务期限
                $service = new CompanyService();
                $service_data = $service->getCompanyServiceByCompanyId($val['id']);
                $company_data[$key]['start_time'] = date('Y/m/d',$service_data->getStartTime());
                $company_data[$key]['end_time'] = date('Y/m/d',$service_data->getEndTime());
                //联系人及其联系方式
                $company_info_data = CompanyInfo::getByCompanyId($val['id']);
                $company_data[$key]['contacts'] = $company_info_data->getContacts();
                $company_data[$key]['phone'] = $company_info_data->getContactPhone();
            }
            //排序
            if(!$this->request->getPost('sort') || $this->request->getPost('sort') == 1){
                $sort_sc = SORT_DESC;
            }else{
                $sort_sc = SORT_ASC;
            }
            foreach($company_data as $k=>$v){
                $sort[] = $v['start_time'];
            }
            array_multisort($sort,$sort_sc,$company_data);
            $company_data = array_slice($company_data,$limit,10);
            return $this->json(self::RESPONSE_OK, $company_data, '获取企业成功');
        }else{
            return $this->json(self::RESPONSE_OK, null, '没有企业');
        }
    }

    /*
     * App首页
     */
    public function homePageAction()
    {
        $user_id = JWT::getUid();
        //获取用户唯一标识
        $user_admin_data = UserAdmin::getUser($user_id);
        $company = new \Wdxr\Models\Repositories\Company();
        //通知消息
        $message = new Messages();
        $message_data = $message->getInformMessages();
        $data['inform'] = $message_data ? $message_data->getBody() : '暂无通知消息';
        /*//合伙人与业务员区分获取
        if($user_admin_data->getType() == UserAdmin::TYPE_ADMIN){
            $company_button_data = $company->getCustomerByDevice($user_id,$user_admin_data->getUserId());
        }else{
            //获取合伙人企业ID
            $device_company_data = \Wdxr\Models\Repositories\Company::getCompanyByUserId($user_admin_data->getUserId());
            $company_button_data = $company->getCustomerByCompany($device_company_data->getId());
        }*/

        /*if($company_button_data_array = $company_button_data->toArray()){
            $company_report = new CompanyReport();
            $company_bill = new \Wdxr\Models\Repositories\CompanyBill();
            //征信   票据 待补录数量
            $data['button']['bill'] = 0;
            $data['button']['report'] = 0;
            foreach($company_button_data_array as $key=>$val){
                //征信
                $data['button']['report'] += $company_report->getCompanyReportStatus($val['report_id']);
                //票据
                $data['button']['bill'] += $company_bill->getBillStatus($val['id']);
            }


        }else{*/
            $data['button']['report'] = 0;
            $data['button']['bill'] = 0;
//            $data['button']['makeup'] = 0;
//            $data['button']['loan'] = 0;
//        }
        //补录数量
        $company_verify = new \Wdxr\Models\Services\CompanyVerify();
        $data['button']['makeup'] = $company_verify->getCompanyVerifyInfoCount($user_id);
        //普惠补录数量
            $data['button']['loan'] = Loan::unfinishenCount() ?: 0;
        //事业合伙人排行
        $page = $this->request->getPost('page');
        if (!$page) {
            $page = 1;
        }
        $limit = ($page - 1) * 5;
        $company_data = $company->getPartnerCompany($user_id);
        if ($company_data->toArray() != []) {
            $company_data_array = $company_data->toArray();
            foreach ($company_data_array as $key => $val) {
                $company_data_array[$key]['money'] = 0;
                //获取所有企业关系列表
                $CompanyRecommends = new CompanyRecommends();
                $CompanyRecommends = $CompanyRecommends->getCompanyRecommendsByrecommend_id($val['id'])->toArray();
                if ($CompanyRecommends) {
                    foreach ($CompanyRecommends as $k => $v) {
                        $payment = CompanyPayment::getPaymentByCompanyId($v['recommend_id']);
                        if($payment){
                            if($payment->getStatus() == \Wdxr\Models\Repositories\CompanyPayment::STATUS_OK){
                                $company_data_array[$key]['money'] += (new Level())->getLevelMoney($payment->getLevelId());
                            }else{
                                $company_data_array[$key]['money'] += 0;
                            }
                        }else{
                            $company_data_array[$key]['money'] += 0;
                        }
                    }
                }
            }
            //按金额排序
            foreach ($company_data_array as $a => $b) {
                $sort[] = $b['money'];
            }
            array_multisort($sort, SORT_DESC, $company_data_array);
            $company_data_array = array_slice($company_data_array,$limit,5);

            $data['partnerRanking'] = $company_data_array;
        } else {
            $data['partnerRanking'] = [];
        }

        return $this->json(self::RESPONSE_OK, $data, '获取主页信息成功');
    }

    /*
     * 中部按钮列表
     */
    public function getButtonListAction()
    {
        $type = $this->request->getPost('type');
        $user_id = JWT::getUid();
        //获取用户唯一标识
        $user_admin_data = UserAdmin::getUser($user_id);
        $company = new \Wdxr\Models\Repositories\Company();
        //合伙人与业务员区分获取
        if($user_admin_data->getType() == UserAdmin::TYPE_ADMIN){
            $company_button_data = $company->getCustomerByDevice($user_id,$user_admin_data->getUserId());
        }else{
            //获取合伙人企业ID
            $device_company_data = \Wdxr\Models\Repositories\Company::getCompanyByUserId($user_admin_data->getUserId());
            $company_button_data = $company->getCustomerByCompany($device_company_data->getId());
        }
        $data = array();
        try{
            switch ($type) {

            case 'report' :
                if ($company_button_data_array = $company_button_data->toArray()) {
                    $company_report = new CompanyReport();
                    foreach ($company_button_data_array as $key => $val) {
                        if ($company_report->getCompanyReportStatus($val['id'])) {
                            $data[$key]['id'] = $val['id'];
                            $data[$key]['name'] = $val['name'];
                            $data[$key]['apply_time'] = date('Y年m月d日',strtotime($val['time']));
                        }
                    }
                }
            break;

            case 'bill' :
                if ($company_button_data_array = $company_button_data->toArray()) {
                    $company_bill = new \Wdxr\Models\Repositories\CompanyBill();
                    foreach ($company_button_data_array as $key => $val) {
                        if ($company_bill->getBillStatus($val['id'])) {
                            $data[$key]['id'] = $val['id'];
                            $data[$key]['name'] = $val['name'];
                            $data[$key]['apply_time'] = date('Y年m月d日',strtotime($val['time']));
                        }
                    }
                }
            break;

            case 'info' :
                //补录数量
                $company_verify = new \Wdxr\Models\Services\CompanyVerify();
                $company_button_data_array = $company_verify->getCompanyVerifyInfoList($user_id);
                foreach($company_button_data_array as $key=>$val){
                    $data[$key]['id'] = $val['company_id'];
                    $data[$key]['name'] = $val['company_name'];
                    $data[$key]['apply_time'] = $val['apply_time'];
                }
            break;

            case 'loan' :
                $company_button_data_array = Loan::unfinishenList()->toArray();
                foreach($company_button_data_array as $key=>$val){
                    $data[$key]['id'] = $val['data_id'];
                    $company_data = (new \Wdxr\Models\Repositories\Company())->getById($val['company_id']);
                    $data[$key]['name'] = $company_data->getName();
                    $data[$key]['apply_time'] = date('Y年m月d日',$val['apply_time']);
                }
            break;

            default :
                $data = [];
            break;
        }
    }
    catch(InvalidRepositoryException $exception){
            return $this->json(self::RESPONSE_FAILED, [], $exception->getMessage());
        }
        //按时间排序
        foreach ($data as $a => $b) {
            $sort[] = $b['apply_time'];
        }
        array_multisort($sort, SORT_DESC, $data);
        return $this->json(self::RESPONSE_OK, $data ?: [], '获取列表成功');

    }

    /*
     * 业绩列表
     */
    public function achievementListAction()
    {

        if($this->request->isPost()){
            //获取列表类型
            $type = $this->request->getPost('type') ?: 'day';
            //获取身份
            $user_id = JWT::getUid();
            $user_id_data = UserAdmin::getUser($user_id);
            //获取时间
            if($type == 'day'){
                $start_time = date('Y-m-01', strtotime(date("Y-m-d")));
                $end_time   = date('Y-m-d', strtotime("$start_time +1 month -1 day"));
                $start_time = $this->request->getPost('start_time') ?: $start_time;
                $end_time = $this->request->getPost('end_time') ? date('Y-m-d', strtotime($this->request->getPost('end_time')." +1 day")-1) : $end_time;
            }elseif($type == 'month'){
                $start_time = date('Y-01-d', strtotime(date("Y-m")));
                $end_time   = date('Y-m-d', strtotime("$start_time +12 month -1 day"));
                $start_time = $this->request->getPost('start_time') ? date('Y-m-01', strtotime($this->request->getPost('start_time'))) : $start_time;
                $end_time = $this->request->getPost('end_time') ? date('Y-m-01', strtotime($this->request->getPost('end_time'))): $end_time;
            }else{
                $start_time = date('Y-01-01', strtotime(date("Y-m-d")));
                $end_time   = date('Y-m-d', strtotime("$start_time +1 year")-1);
                $start_time = $this->request->getPost('start_time') ? date('Y-01-01', strtotime($this->request->getPost('start_time'))) : $start_time;
                $end_time = $this->request->getPost('end_time') ? date('Y-12-31', strtotime($this->request->getPost('end_time')."")) : $end_time;
            }
            $i = 0;
            $data_key = 0;
            $start = date('Y-m-d', strtotime("$start_time +$i $type"));
            //主数组
            $data = array('all_amount'=>0,'achievement'=>[]);

            while (strtotime($start) <= strtotime($end_time)){
                //每一项的结束时间
                $end = date('Y-m-d', strtotime("$start +1 $type"));
                //每一项的企业集合
                $company = new \Wdxr\Models\Repositories\Company();
                if($user_id_data->getType() == UserAdmin::TYPE_ADMIN){
                    $company_data = $company->getCompanyByTime("admin_id = ".$user_id_data->getUserId()." and status=1 and auditing=2" ,$start,$end);
                }else{
                    $user_company_data = $company->getCompanyByUserId($user_id_data->getUserId());
                    $company_data = $company->getCompanyByTime(" ( device_id = ".$user_id." or recommend_id = ".$user_company_data->getId().") and status=1 and auditing=2" ,$start,$end);
                }

                //企业集合数组
                $company_array = array();
                //集合总业绩
                $amount = 0;
                //获取集合所有企业数据
                if($company_data->toArray()){
                    $company_data = $company_data->toArray();
                    foreach ($company_data as $key=>$val){
                        //企业名称
                        $company_array[$key]['company_name'] = $val['name'];
                        //企业级别
                        $company_array[$key]['company_level'] = 'V1';
                        //提成及业绩总额
                        $company_payment = new CompanyPayment();
                        $company_payment_data = $company_payment->getPaymentByCompany($val['id']);
                        //业绩总额
                        $amount += $company_payment_data->getAmount();
                        //企业贷款状态的业绩
                        if($company_payment_data->getType() == CompanyPayment::TYPE_LOAN){
                            $company_array[$key]['money'] = ($company_payment_data->getAmount()*0.05)/2;
                        }else{
                            $company_array[$key]['money'] = $company_payment_data->getAmount()*0.05;
                        }
                        //获取业绩提成
                        $company_array[$key]['money'] = (new CommissionList())->getRatio($company_payment_data->getType(),$company_payment_data->getAmount(),$val['admin_id'],UserAdmin::TYPE_ADMIN);
                        if($user_id_data->getType() == UserAdmin::TYPE_ADMIN) {
                            if ($val['recommend_id'] != null) {
                                $recommend_company_payment_data = $company_payment->getPaymentByCompany($val['recommend_id']);
                                if ($recommend_company_payment_data->getType() == CompanyPayment::TYPE_LOAN) {
                                    $company_array[$key]['money'] = 0;
                                }
                            }
                            if ($val['manager_id'] != null) {
                                $company_array[$key]['money'] = 0;
                            }
                        }
                    }
                    //判断时间计量来显示日期格式
                    if($type == 'day'){
                        $data['achievement'][$data_key]['date'] = $start;//日期
                    }elseif($type == 'month'){
                        $data['achievement'][$data_key]['date'] = date('n月', strtotime($start));//日期
                    }else{
                        $data['achievement'][$data_key]['date'] = date('Y年', strtotime($start));//日期
                    }

                    $data['achievement'][$data_key]['amount'] = $amount;//该日期业绩金额
                    $data['achievement'][$data_key]['company'] = $company_array;//企业信息
                    $data_key ++;
                }

                $i++;
                $start = date('Y-m-d', strtotime("$start_time +$i $type"));
            }
            foreach ($data['achievement'] as $k=>$v){
                $data['all_amount'] += $v['amount'];
            }

            $data['start_time'] = $start_time;
            $data['end_time'] = $end_time;
            
            if($data['achievement'] == []){
                return $this->json(self::RESPONSE_OK, array('achievement' => [] ,'all_amount' => 0 ,'start_time' => $start_time , 'end_time' => $end_time), '没有业绩列表,请继续努力');
            }
            return $this->json(self::RESPONSE_OK, $data, '获取业绩列表成功');
        }
        return $this->json(self::RESPONSE_FAILED, null, '非法访问');
    }

    /*
     * 业绩排行列表
     */
    public function achievementRankingAction()
    {
        if($this->request->isPost()){
            //获取身份
            $user_id = JWT::getUid();
            $user = UserAdmin::getUser($user_id);
            $type = $user->getType();
            if($type == UserAdmin::TYPE_USER){
                return $this->json(self::RESPONSE_FAILED, '功能暂未开放', '功能暂未开放');
            }
            //排行区间
            $date_type = $this->request->getPost('type') ?: 'day';
            if($date_type == 'day'){
                $start_time=date('Y-m-d',mktime(0,0,0,date('m'),1,date('Y')));
                $end_time=date('Y-m-d',mktime(23,59,59,date('m'),date('t'),date('Y')));
            }elseif($date_type == 'month'){
                $start_time=date('Y-m-d',mktime(0,0,0,date('1'),1,date('Y')));
                $end_time=(date('Y',time())+1).'-01-01';
            }else{
                $start_time=date('Y-m-d',mktime(0,0,0,date('1'),1,date('Y')));
                $end_time=date('Y-m-d',time());
            }
            $company = new \Wdxr\Models\Repositories\Company();
            //todo bug 条件判断错误
            $company_data = $company->getCompanyByTime("status = 1 and auditing = 2",$start_time,$end_time);
            if($company_data_array = $company_data->toArray()){
                $data = array();
                //自己的排行信息
                foreach($company_data_array as $key=>$val) {
                    if ($type == UserAdmin::TYPE_ADMIN) {
                        if ($val['admin_id'] == $user->getUserId()) {//$val['device_id'] == $user_id ||
//                            $device_data = (new UserAdmin())->getByDeviceId($val['device_id'], $type);
//                            $admin_data = (new Admin())->getAdminsById($device_data->getUserId());
                            $admin_data = (new Admin())->getAdminsById($val['admin_id']);
                            $data['my'][$val['admin_id']]['pic'] = $admin_data->getAvatar() ? UploadService::getAttachmentUrl($admin_data->getAvatar()) : '';
                            $data['my'][$val['admin_id']]['name'] = $admin_data->getName();
                            //todo bug 未定义的索引；业绩不一定是20000;下同
                            $data['my'][$val['admin_id']]['amount'] += 20000;
                            //todo bug 未定义的索引
                            $data['my'][$val['admin_id']]['count'] += 1;
                        }
                    } else {
                        if ($val['recommend_id'] != null) {
                            $recommend_data = $company->getById($val['recommend_id']);
                            $recommend_device_data = (new UserAdmin())->getDevice($recommend_data->getUserId(), UserAdmin::TYPE_USER);
                            if ($recommend_device_data->getId() == $user_id) {
                                $admin_data = (new user())->getById($recommend_device_data->getUserId());
                                $data['my'][$recommend_device_data]['pic'] = $admin_data->getPic() ? UploadService::getAttachmentUrl($admin_data->getPic()) : '';
                                $data['my'][$recommend_device_data->getId()]['name'] = $recommend_data->getName();
                                $data['my'][$recommend_device_data->getId()]['amount'] += 20000;
                                $data['my'][$recommend_device_data->getId()]['count'] += 1;
                            }
                        }
                        if ($val['manager_id'] != null) {
                            $manager_data = $company->getById($val['manager_id']);
                            $manager_device_data = (new UserAdmin())->getDevice($manager_data->getUserId(), UserAdmin::TYPE_USER);
                            if ($manager_device_data->getId()) {
                                $admin_data = (new user())->getById($manager_device_data->getUserId());
                                $data['my'][$manager_device_data->getId()]['pic'] = $admin_data->getPic() ? UploadService::getAttachmentUrl($admin_data->getPic()) : '';
                                $data['my'][$manager_device_data->getId()]['name'] = $manager_data->getName();
                                $data['my'][$manager_device_data->getId()]['amount'] += 20000;
                                $data['my'][$manager_device_data->getId()]['count'] += 1;
                            }
                        }
                    }
                }

                //如果没有进入排行榜
                if($type == UserAdmin::TYPE_ADMIN) {
                    $admin_data = (new Admin())->getAdminsById($user->getUserId());
                    $avatar = $admin_data->getAvatar();
                } else {
                    $admin_data = (new user())->getById($user->getUserId());
                    $avatar = $admin_data->getPic();
                }
                if($data['my'] == null) {
                    $data['my'][0]['pic'] = $avatar ? UploadService::getAttachmentUrl($avatar) : '';
                    $data['my'][0]['name'] = $admin_data->getName();
                    $data['my'][0]['amount'] = 0;
                    $data['my'][0]['count'] = 0;
                }
                //全部的排行信息
                foreach($company_data_array as $key => $val) {
                    if($type == UserAdmin::TYPE_ADMIN) {
//                            if($val['device_id']){
//                                $device_data = (new UserAdmin())->getByDeviceId($val['device_id'],$type);
//                                if($device_data){
//                                    $admin_data = (new Admin())->getAdminsById($device_data->getUserId());
                        $amount = CompanyPayment::getPaymentByCompanyId($val['id']);
                        $admin_data = (new Admin())->getAdminsById($val['admin_id']);
                        if($admin_data) {
                            $data['list'][$val['admin_id']]['pic'] =  $admin_data->getAvatar() ? UploadService::getAttachmentUrl($admin_data->getAvatar()) : '';
                            $data['list'][$val['admin_id']]['name'] = $admin_data->getName();
                            $data['list'][$val['admin_id']]['amount'] += $amount->getAmount();
                            $data['list'][$val['admin_id']]['count'] += 1;
                            $data['list'][$val['admin_id']]['is_my'] = 0;
                            if($val['admin_id'] == $user->getUserId()){
                                $data['list'][$val['admin_id']]['is_my'] = 1;
                            }
                        }
//                                }
//                            }
                    }else{
                        if($val['recommend_id'] != null){
                            $recommend_data = $company->getById($val['recommend_id']);
                            $recommend_device_data = (new UserAdmin())->getDevice($recommend_data->getUserId(),UserAdmin::TYPE_USER);
                            $admin_data = (new user())->getById($recommend_device_data->getUserId());
                            $data['list'][$recommend_device_data->getId()]['pic'] =  $admin_data->getPic() ? UploadService::getAttachmentUrl($admin_data->getPic()) : '';
                            $data['list'][$recommend_device_data->getId()]['name'] = $recommend_data->getName();
                            $data['list'][$recommend_device_data->getId()]['amount'] += 20000;
                            $data['list'][$recommend_device_data->getId()]['count'] += 1;
                            $data['list'][$recommend_device_data->getId()]['is_my'] = 0;
                            if($recommend_device_data->getId() == $user_id){
                                $data['list'][$recommend_device_data->getId()]['is_my'] = 1;
                            }
                        }
                        if($val['manager_id'] != null){
                            $manager_data = $company->getById($val['manager_id']);
                            $manager_device_data = (new UserAdmin())->getDevice($manager_data->getUserId(),UserAdmin::TYPE_USER);
                            $admin_data = (new user())->getById($manager_device_data->getUserId());
                            $data['list'][$manager_device_data->getId()]['pic'] =  $admin_data->getPic() ? UploadService::getAttachmentUrl($admin_data->getPic()) : '';
                            $data['list'][$manager_device_data->getId()]['name'] = $manager_data->getName();
                            $data['list'][$manager_device_data->getId()]['amount'] += 20000;
                            $data['list'][$manager_device_data->getId()]['count'] += 1;
                            $data['list'][$manager_device_data->getId()]['is_my'] = 0;
                            if($manager_device_data->getId() == $user_id){
                                $data['list'][$manager_device_data->getId()]['is_my'] = 1;
                            }
                        }
                    }
                }
                //排序
                $sort=array();
                foreach($data['list'] as $key=>$val){
                    $sort[]=$val["amount"];
                }
                array_multisort($sort,SORT_DESC, $data['list']);

                $sort=array();
                foreach($data['my'] as $val){
                    $sort[]=$val["amount"];
                }
                array_multisort($sort,SORT_DESC, $data['my']);

                //名次
                $ranking = 1;
                foreach($data['list'] as $val) {
                    if($val['is_my']) {
                        $data_ranking = $ranking;
                    }
                    $ranking ++;
                }
                $data['my'][0]['ranking'] = isset($data_ranking) ? '第'.$data_ranking.'名' : '未上榜';

                return $this->json(self::RESPONSE_OK, $data, '获取排行记录成功');
            }
            //如果没有进入排行榜
            $data = array();
            if($type == UserAdmin::TYPE_ADMIN) {
                $admin_data = (new Admin())->getAdminsById($user->getUserId());
                $avatar = $admin_data->getAvatar();
            } else {
                $admin_data = (new user())->getById($user->getUserId());
                $avatar = $admin_data->getPic();
            }
            if($data['my'] == null) {
                $data['my'][0]['pic'] = $avatar ? UploadService::getAttachmentUrl($avatar) : '';
                $data['my'][0]['name'] = $admin_data->getName();
                $data['my'][0]['amount'] = 0;
                $data['my'][0]['count'] = 0;
                $data['my'][0]['ranking'] = '未上榜';
            }
            $data['list'] = [];
            return $this->json(self::RESPONSE_OK, $data, '暂无排行记录');
        }
        return $this->json(self::RESPONSE_FAILED, null, '非法访问');
    }


    public function getDeviceListAction()
    {
        //设备列表不显示自己
        $token = $this->request->isPost() ? $this->request->getPost('token') : $this->request->get('token');
        $user_id = JWT::getUid();
        $device = Devices::getDevicesByUserIdAndToken($user_id);
        if($device_array = $device->toArray()){
            foreach($device_array as $key=>$val){
                $device_array[$key]['time'] = date('m-d H:i',strtotime($val['time']));
                if($val['token'] == $token){
                    $device_array[$key]['is_self'] = 1;
                }else{
                    $device_array[$key]['is_self'] = 0;
                }
            }
            //按金额排序
            foreach ($device_array as $a => $b) {
                $sort[] = $b['is_self'];
            }
            array_multisort($sort, SORT_DESC, $device_array);
            return $this->json(self::RESPONSE_OK, $device_array, '获取成功');
        }else{
            return $this->json(self::RESPONSE_FAILED, [], '没有其他用户');
        }
    }

    public function deleteDeviceAction()
    {
        if(!$this->checkPasswordAction()){
            return $this->json(self::RESPONSE_FAILED, null, '密码错误');
        }
        //删除设备列表数据
        $device_token = $this->request->isPost() ? $this->request->getPost('device_token') : $this->request->get('device_token');
        $device = Devices::deleteDeviceByToken($device_token);
        $user_id = JWT::getUid();
        $result = (new UserAuth)->deleteDeviceToken($user_id);
        if($result && $device){
            return $this->json(self::RESPONSE_OK, null, '下线成功');
        }else{
            return $this->json(self::RESPONSE_FAILED, null, '下线失败');
        }
    }

    protected function checkPasswordAction()
    {
        try{
            $user_id = JWT::getUid();
            $device = UserAdmin::getUser($user_id);
            if($device->getType() == UserAuth::AUTH_TYPE_ADMIN){
                $admin = Admin::getAdminById($device->getUserId());
                $user = (new Auth())->check([
                    'username' => $admin->getName(),
                    'password' => $this->request->getPost('password'),
                ]);
                return true;
            }elseif($device->getType() == UserAuth::AUTH_TYPE_USER){
                $company_user = User::getUserById($device->getUserId());
                $user = (new UserAuth())->check([
                    'username' => $company_user->getName(),
                    'password' => $this->request->getPost('password'),
                ]);
                return true;
            }else{
                return false;
            }
        }catch (AuthException $exception){
            return false;
        }
    }

    public function testAction()
    {
        $tokens = $this->redis->get('token_'.JWT::getUid());
        $tokens = is_array($tokens) ? $tokens : (array)$tokens;

        return $this->json(self::RESPONSE_FAILED, $tokens, '测试');
    }


}