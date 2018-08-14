<?php
namespace Wdxr\Models\Services;

use Lcobucci\JWT\JWT;
use Phalcon\Exception;
use Wdxr\Models\Entities\Companys as EntityCompany;
use Wdxr\Models\Entities\CompanyInfo as EntityCompanyInfo;
use Wdxr\Models\Repositories\Attachment;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Models\Repositories\CompanyBill as RepoCompanyBill;
use Wdxr\Models\Repositories\CompanyReport as RepoCompanyReport;
use Wdxr\Models\Repositories\CompanyInfo as RepoCompanyInfo;
use Wdxr\Models\Repositories\CompanyReport;
use Wdxr\Models\Repositories\CompanyService;
use Wdxr\Models\Repositories\CompanyVerify as RepoCompanyVerify;
use Wdxr\Models\Repositories\CompanyService as RepoCompanyService;
use Wdxr\Models\Repositories\Follow;
use Wdxr\Models\Repositories\Level as RepoLevel;
use Wdxr\Models\Repositories\CompanyRecommend as RepoCompanyRecommend;
use Wdxr\Models\Repositories\Contract as RepoContract;
use Wdxr\Models\Repositories\CompanyPayment as RepoCompanyPayment;
use Wdxr\Models\Repositories\Regions;
use Wdxr\Models\Repositories\Repositories;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Services\CompanyVerify as ServiceCompanyVerify;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Wdxr\Time;

class Company extends Services
{
    const MAIN_OK  = 1;//正常
    const MAIN_BILL = 2;//票据异常
    const MAIN_REPORT = 3;//征信异常
    const MAIN_BAD = 4;//企业信息异常
    const MAIN_SERVICE= 5;//未在服务期

    public static function getMainStatusName($status)
    {
        switch ($status) {
            case self::MAIN_OK:
                return '正常';
                break;
            case self::MAIN_BILL:
                return '票据异常';
                break;
            case self::MAIN_REPORT:
                return '征信异常';
                break;
            case self::MAIN_BAD:
                return '企业信息异常';
                break;
            case self::MAIN_SERVICE:
                return '企业服务期限异常';
                break;
            default:
                return '数据异常';
        }
    }

    static public function getCompanyBenefits(EntityCompany $company)
    {
        if($company->getAuditing() != RepoCompany::AUDIT_OK) {
            throw new InvalidServiceException("该企业尚未通过审核");
        }
        if($company->getStatus() != RepoCompany::STATUS_ENABLE){
            throw new InvalidServiceException("该企业状态未开启");
        }

//        $service = RepoCompanyService::getCompanyService($company->getId());
        $service = (new CompanyService())->getCompanyServiceByCompanyId($company->getId());
        $start_time = $service->getStartTime();
        $end_time = $service->getEndTime();
        $time = time();
        if($time < $start_time || $time > $end_time){
            //DH20170601修改
            $company_data = (new \Wdxr\Models\Repositories\Company())->getById($company->getId());
            $company_data->setStatus(\Wdxr\Models\Repositories\Company::STATUS_DISABLE);
            $company_data->save();
            throw new InvalidServiceException("该企业未在服务时间内");
        }

        //$info = RepoCompanyInfo::getCompanyinfoById($company->getInfoId());
        $info_verify = RepoCompanyVerify::getVerifyInfoByDataId($company->getInfoId(), RepoCompanyVerify::TYPE_DOCUMENTS);
        if($info_verify->getStatus() != RepoCompanyVerify::STATUS_OK) {
            throw new InvalidServiceException("该企业证件信息尚未通过审核");
        }

        if($service->getReportStatus() === CompanyService::REPORT_STATUS_FAILED) {
            throw new InvalidServiceException("征信已过期或未通过审核");
        }
        if($service->getBillStatus() === CompanyService::BILL_STATUS_FAILED) {
            throw new InvalidServiceException("票据已过期或未通过审核");
        }

        $payment = \Wdxr\Models\Repositories\CompanyPayment::getPaymentByCompanyId($company->getId(), \Wdxr\Models\Repositories\CompanyPayment::STATUS_OK);
        if($payment) {
            return $payment->getAmount();
        } else {

            throw new InvalidServiceException("获取企业报表信息失败");
        }
    }


    public static function getCompanyBenefitsForMainTask(EntityCompany $company)
    {
        $data['status'] = self::MAIN_OK;//状态正常
        if ($company->getAuditing() != RepoCompany::AUDIT_OK) {
            $data['status'] =  self::MAIN_BAD;
            $data['info'] = '企业申请未通过审核';
        }
        if ($company->getStatus() != RepoCompany::STATUS_ENABLE) {
            $data['status'] =  self::MAIN_BAD;
            $data['info'] = '企业基本状态未启用';
        }

        $service = RepoCompanyService::getCompanyService($company->getId());
        if ($service) {
            $start_time = $service->getStartTime();
            $end_time = $service->getEndTime();
            $time = time();
            if ($time < $start_time || $time > $end_time) {
                //DH20170601修改
                $company_data = (new \Wdxr\Models\Repositories\Company())->getById($company->getId());
                $company_data->setStatus(\Wdxr\Models\Repositories\Company::STATUS_DISABLE);
                $company_data->save();
                $data['status'] =  self::MAIN_SERVICE;
                $data['info'] = '该企业已不在服务期限内';
            }

            $info_verify = RepoCompanyVerify::isVerifyOkByDataId(
                $company->getInfoId(),
                RepoCompanyVerify::TYPE_DOCUMENTS
            );
            if ($info_verify === false) {
                $data['status'] = self::MAIN_BAD;
                $data['info'] = '企业信息未通过审核';
            }


            //判断征信
//            if($service->getReportStatus() != 1) {
//                $data['status'] = self::MAIN_REPORT;
//            }
//            //判断票据
//            if($service->getBillStatus() != 1) {
//                $data['status'] = self::MAIN_BILL;
//            }
        } else {
            $data['status'] = self::MAIN_SERVICE;
            $data['info'] = '该企业服务状态未生效';
        }
        /*$report = RepoCompanyReport::getCompanyReportById($company->getReportId());
        if($report && $report->getReport() != null){

            //服务时间信息
            $service_data = (new CompanyService)->getCompanyServiceByCompanyId($company->getId());
            //期限信息
            $report_term = new \Wdxr\Models\Repositories\ReportTerm();
            $report_term_data = $report_term->getReportTermByCompanyId($company->getId());
            if($report_term_data){
                $report_term_end_time = (new CompanyReport)->setReportEnd($report_term_data->getTerm(),$report_term_data->getType(), $service_data->getStartTime());
            }else{
                $data['status'] =  self::MAIN_REPORT;
            }
            if(time() > $report_term_end_time) {
                if($report->getStatus() == 0){
                    $data['status'] =  self::MAIN_REPORT;
                }
            }


        }else{
            //服务时间信息
            $service_data = (new CompanyService())->getCompanyServiceByCompanyId($company->getId());
            //期限信息
            $report_term = new \Wdxr\Models\Repositories\ReportTerm();
            $report_term_data = $report_term->getReportTermByCompanyId($company->getId());
            if($report_term_data){
                $report_term_end_time = (new CompanyReport)->setReportEnd($report_term_data->getTerm(),$report_term_data->getType(), $service_data->getStartTime());
            }else{
                $data['status'] =  self::MAIN_REPORT;
            }
            if(time() > $report_term_end_time) {
                $data['status'] =  self::MAIN_REPORT;
            }
        }*/

        if ($service) {
            $level = RepoLevel::getLevelById($service->getLevelId());
            if ($level) {
                //获取对应等级的每日报销金额
                if ($level_info = $level->getInfo()) {
                    $service_count = (time() - $service->getStartTime()) / 86400;
                    $level_info = explode('|', $level_info);
                    $level_info0 = explode(',', $level_info[0]);
                    $level_info1 = explode(',', $level_info[1]);
                    if ($service_count <= $level_info0[0]) {
                        $data['money'] = round($level_info0[1], 2);
                    } else {
                        $data['money'] = round($level_info1[1], 2);
                    }
                } else {
                    $data['money'] = $level->getDayAmount();
                }
            } else {
                $data['status'] = self::MAIN_BAD;
                $data['info'] = '企业级别信息错误';
                $data['money'] = 0;
            }
        } else {
            $data['status'] = self::MAIN_BAD;
            $data['info'] = '该企业服务状态未生效';
            $data['money'] = 0;
        }

        return $data;
    }

    public static function searchCompany($device_id, $name, $page = 1)
    {
        $company = Services::getStaticModelsManager()->createBuilder()
            ->where('device_id = :device_id:', ['device_id' => $device_id])
            ->andWhere('name like :name:', ['name' => "%".$name."%"])
            ->from('Wdxr\Models\Entities\Companys')
            ->columns(['id', 'name', 'time', 'status', 'auditing', 'bill_id', 'report_id', 'info_id', 'payment'])
            ->orderBy('time desc')
            ->limit(10, $page * 10 - 10)
            ->getQuery()
            ->execute();

        return $company;
    }

    public static function BillEndTime($term, $type, $start_time)
    {
        switch ($type) {
            case 0:
                $data = 'day';
                break;
            case 1:
                $data = 'month';
                break;
            case 2:
                $data = 'year';
                break;
            default:
                $data = 'month';
                break;
        }
        return Time::getFloorTime(strtotime("+{$term} {$data}", $start_time) + 86400) - 1;
    }

    static public function getCompanyListPagintor($parameters, $numberPage)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->from('Wdxr\Models\Entities\Companys')
            ->orderBy('id desc');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

    /**
     * 多条件搜索
     */
    static public function getCompanyListPagintorForm($name, $numberPage, $time=null)
    {
        if($name){
            if($time){
                $builder = Services::getStaticModelsManager()->createBuilder()
                    ->where("company.name like '%".$name."%' ")
                    ->orWhere("company_info.legal_name like '%".$name."%' ")
                    ->orWhere("company_info.contact_phone like '%".$name."%' ")
                    ->orWhere("company_info.contract_num like '%".$name."%' ")
                    ->betweenWhere("company.time",$time['start_time'],$time['end_time'])
                    ->from(['company' => 'Wdxr\Models\Entities\Companys'])
                    ->join('Wdxr\Models\Entities\CompanyInfo', 'company_info.id = company.info_id', 'company_info')
                    ->orderBy('company.id desc');
            }else{
                $builder = Services::getStaticModelsManager()->createBuilder()
                    ->where("company.name like '%".$name."%' ")
                    ->orWhere("company_info.legal_name like '%".$name."%' ")
                    ->orWhere("company_info.contact_phone like '%".$name."%' ")
                    ->orWhere("company_info.contract_num like '%".$name."%' ")
                    ->from(['company' => 'Wdxr\Models\Entities\Companys'])
                    ->join('Wdxr\Models\Entities\CompanyInfo', 'company_info.id = company.info_id', 'company_info')
                    ->orderBy('company.id desc');
            }
        }else{
            if($time){
                $builder = Services::getStaticModelsManager()->createBuilder()
                    ->where("1=1")
                    ->betweenWhere("company.time",$time['start_time'],$time['end_time'])
                    ->from(['company' => 'Wdxr\Models\Entities\Companys'])
                    ->join('Wdxr\Models\Entities\CompanyInfo', 'company_info.id = company.info_id', 'company_info')
                    ->orderBy('company.id desc');
            }else{
                $builder = Services::getStaticModelsManager()->createBuilder()
                    ->where("1=1")
                    ->from(['company' => 'Wdxr\Models\Entities\Companys'])
                    ->join('Wdxr\Models\Entities\CompanyInfo', 'company_info.id = company.info_id', 'company_info')
                    ->orderBy('company.id desc');
            }
        }
        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);

    }


    public static function getCompanyBillList($name)
    {
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->from(['company' => 'Wdxr\Models\Entities\Companys'])
            ->leftJoin('Wdxr\Models\Entities\CompanyService', 'service.company_id = company.id', 'service')
            ->leftJoin('Wdxr\Models\Entities\CompanyInfo', 'company_info.id = company.info_id', 'company_info')
            ->leftJoin('Wdxr\Models\Entities\CompanyBill','company_bill.id = service.id', 'company_bill')
            ->columns([
                'company.id as id', 'company.name as name', 'company.status as status', 'company_info.legal_name as legal_name',
                'company_bill.amount as amount','company_bill.createAt as time','company.time as company_time'
            ]);
        if ($name) {
            $builder->where("company.name like '%".$name."%' ")
                ->orWhere("company_info.legal_name like '%".$name."%' ")
                ->orWhere("company_info.contact_phone like '%".$name."%' ");
        }
        $builder->orderBy('company_bill.amount asc , time asc');

        return $builder;
    }

    static public function getCompanyOweBillList()
    {
        return \Wdxr\Models\Entities\CompanyBill::query()
            ->where("amount < 0")
            ->orderBy('amount asc')
            ->execute();
    }

    static public function getVerifyCompanyListPagintor($parameters, $numberPage)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        if($conditions) {
            $conditions = $conditions . ' and auditing = :audit:';
            $bind = array_merge($bind, ['audit' => RepoCompany::AUDIT_APPLY]);
        } else {
            $conditions = 'auditing = :audit:';
            $bind = ['audit' => RepoCompany::AUDIT_APPLY];
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->from('Wdxr\Models\Entities\Companys')
            ->orderBy('id desc');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

    /**
     * 根据经纬度获取详细地址
     * @param $longitude float 经度
     * @param $latitude  float 纬度
     * @return bool
     * @throws InvalidServiceException
     */
    static public function getAddressFromGeo($longitude, $latitude)
    {
        $key = Services::getStaticDi()->get('config')->get('amap')->get('key');
        $result = Services::getStaticDi()->get('guzzle')->get("http://restapi.amap.com/v3/geocode/regeo", [
            'query' => [
                'key' => $key,
                'location' => $longitude.','.$latitude,
                'extensions' => 'base',
                'homeorcorp' => 2,
            ]
        ]);
        $geo = \GuzzleHttp\json_decode($result->getBody(), true);
        if($geo['status'] == 1) {
            return $geo['regeocode'];
        } else {
            throw new InvalidServiceException("当前位置获取失败，需要获取地理位置信息的权限");
        }
    }

    static public function apply($company_id, $data)
    {
        $company = RepoCompany::getCompanyById($company_id);
//        $contract = RepoContract::getLastContractNum($company->getDeviceId(), $company_id, 114.475785, 38.0308436);
//        if($data['location']) {
//            $contract->setLocation($data['location']);
//            if($contract->save() === false) {
//                throw new Exception("合同签订位置更新失败");
//            }
//        }

//        $data['contract_num'] = $contract->getContractNum();
        //添加企业详细信息
        $info_id = RepoCompanyInfo::add($data);
        //添加推荐记录
        if($data['recommend_id']) {
            $recommend_id = intval($data['recommend_id']);
            RepoCompanyRecommend::addNew($recommend_id, $company_id, $company->getDeviceId());
            $company->setRecommendId($recommend_id);
            if($data['manager_id']) {
                $company->setManagerId($data['manager_id']);
            }
        }
        //添加票据、征信记录
        $bill_id = RepoCompanyBill::addCompanyBill($company_id);
        $report_id = RepoCompanyReport::addCompanyReport($company_id);
        //修改企业信息
//        $company->setBillId($bill_id);
//        $company->setReportId($report_id);
        $company->setAuditing(RepoCompany::AUDIT_APPLY);
//        $company->setType($data['type']);
        $company->setInfoId($info_id);
        if(!$company->save()) {
            throw new InvalidServiceException("企业信息保存失败");
        }
        //添加审核信息
        return RepoCompanyVerify::newVerify($company_id, $company->getDeviceId(), RepoCompanyVerify::TYPE_DOCUMENTS, $info_id);
    }

    /**
     * 获取不同付款方式的企业的数量（事业合伙人或普惠客户的数量）
     * @param bool $is_partner
     * @return mixed|\Phalcon\Mvc\Model\Query\BuilderInterface
     */
    public function getPartnerCompanies($is_partner = true)
    {
        $service = Services::getStaticModelsManager()->createBuilder()
            ->from(['company_service' => 'Wdxr\Models\Entities\CompanyService'])
            ->join('Wdxr\Models\Entities\Companys', 'company.id = company_service.company_id', 'company')
            ->where("company_service.service_status = :status:", ['status' => RepoCompanyService::SERVICE_ENABLE]);
        if($is_partner) {
            $service->andWhere('company_service.type = :type:', ['type' => RepoCompanyService::TYPE_PARTNER]);
        } else {
            $service->andWhere('company_service.type = :type:', ['type' => RepoCompanyService::TYPE_ORDINARY]);
        }
        $company = $service->getQuery()->execute();

        return $company;
    }

    public function getMonthSumCompanies($is_partner = 1 , $month = null, $day = null, $year = null)
    {
        $month = is_null($month) ? date('m') : $month;
        $year = is_null($year) ? date('Y') : $year;
        $day = is_null($day) ? date('t') : $day;
        $start_time = mktime(0,0,0, $month, 1, $year);
        $end_time = mktime(23,59,59, $month, $day, $year);

        $options = [
            'time' => date('Y-m-d', $start_time).' - '.date('Y-m-d', $end_time),
            'type' => $is_partner ? CompanyService::TYPE_PARTNER : CompanyService::TYPE_ORDINARY
        ];
        /**
         * @var $company_service CompanyService
         */
        $company_service = Repositories::getRepository('CompanyService');
        $builder = $company_service->getServiceCompany($options);

        return count($builder->getQuery()->execute());
    }

    static public function httpcode($url){
        $ch = curl_init();
        $timeout = 3;
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_exec($ch);
        $code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $code;
    }

    static public function addCompanyFromUrl($url)
    {
        if(!strpos($url,'hebscztxyxx')) {
            throw new Exception("当前仅支持识别河北省的企业");
        }
        $client = new \GuzzleHttp\Client();
        $source = $client->get($url);

        if(!$source){
            throw new Exception("扫描失败,识别不到二维码信息");
        }

        if($source->getStatusCode() != 200) {
            throw new Exception("识别失败,请手动输入");
        }

        $body = $source->getBody()->getContents();

        $isMatched = preg_match_all('/·&nbsp;(.+)：<i>(.+)<\/i>/', $body, $matches);

        if($isMatched) {
            $info = new EntityCompanyInfo();
            $company = new EntityCompany();
            $names = $matches[1]; $values = $matches[2];
            $data = array();
            foreach ($names as $key => $name) {
                if($name == '统一社会信用代码' || $name == '注册号') {
                    $info->setLicenceNum($values[$key]);
                    $data['licence_num'] = $values[$key];
                } elseif($name == '企业名称') {
                    $data['name'] = $values[$key];
                } elseif($name == '经营者' || $name == '法定代表人' || $name == '负责人' || $name == '投资人') {
                    $info->setLegalName($values[$key]);
                    $data['legal_name'] = $values[$key];
                } elseif($name == '成立日期') {
                    if(strstr($values[$key + 2],'年')){
                        $period = str_replace("年","/",$values[$key] . "-" . $values[$key + 2]);
                        $period = str_replace("月","/",$period);
                        $period = str_replace("日","",$period);
                    }else{
                        $period = str_replace("年","/",$values[$key]);
                        $period = str_replace("月","/",$period);
                        $period = str_replace("日","",$period);
                    }
                    $info->setPeriod($period);
                    $data['period'] = $period;
                } elseif ($name == '注册日期') {
                    $period = str_replace("年","/",$values[$key]);
                    $period = str_replace("月","/",$period);
                    $period = str_replace("日","",$period);
                    $info->setPeriod($period);
                    $data['period'] = $period;
                } elseif ($name == '住所' || $name == '经营场所' || $name == '营业场所') {
                    $service = Services::Hprose('Words');
                    $address = $service->address($values[$key]);
                    if($address){
                    //设置地区
                    $info->setProvince($address[0]['id']);
                    $info->setCity($address[1]['id']);
                    $info->setDistrict($address[2]['id']);
                    $info->setAddress($address[2]['address']);
                    //返回信息
                    $data['province'] = $address[0]['id'];
                    $data['city'] = $address[1]['id'];
                    $data['district'] = $address[2]['id'];
                    $data['address'] = $address[2]['address'];
                    $data['address_all'] = Regions::getAddress($data['province'],$data['city'],$data['district'],'');
                    }else{
                        $info->setAddress($values[$key]);
                        $data['province'] = '';
                        $data['city'] = '';
                        $data['district'] = '';
                        $data['address'] = $values[$key];
                        $data['address_all'] = '';
                    }
                } elseif($name == '经营范围') {
                    $info->setScope($values[$key]);
                    $data['scope'] = $values[$key];
                } elseif($name == '类型') {
                    if($values[$key] == '个体') {
                        $info->setType(RepoCompany::TYPE_SELF_EMPLOYED);
                        $data['type'] = RepoCompany::TYPE_SELF_EMPLOYED;
                    } else {
                        $info->setType(RepoCompany::TYPE_COMPANY);
                        $data['type'] = RepoCompany::TYPE_COMPANY;
                    }
                }
                //URL
                $info->setUrl($url);
                $data['url'] = $url;
            }
            //判断企业是否为手动添加
            $company_info = (new \Wdxr\Models\Repositories\CompanyInfo())->getCompanyInfoByLicenceNum($data['licence_num']);
            if($company_info){
                //保存url
                $company_info->setUrl($url);
                if(!$company_info->save()) {
                    foreach ($company_info->getMessages() as $message) {
                        throw new Exception($message);
                    }
                }
                $company_data = (new \Wdxr\Models\Repositories\Company())->ByInfoId($company_info->getId());
                Follow::follow($company_data->getId());
                $data['company_id'] = $company_data->getId();
                $data['is_follow'] = Follow::checkFollow($company_data->getId());
                $data['is_settled'] = $company_data->getAuditing() ? '是' : '否';
                $data['admin'] = $company_data->getDeviceId() ? UserAdmin::getNameByDeviceId($company_data->getDeviceId()) : '无';
                return $data;
            }
            //保存企业信息
            if(strcmp($data['name'], '***') === 0) {
                $company->setName($data['name'].$data['legal_name']);
            } else {
                $company->setName($data['name']);
            }
            $company->setStatus(\Wdxr\Models\Repositories\Company::STATUS_DISABLE);
            $company->setAuditing(\Wdxr\Models\Repositories\Company::AUDIT_NOT);
            $company->setTime(date('Y-m-d H:i:s',time()));
            $company->setDeviceId(0);
//            $company->setPayment(\Wdxr\Models\Repositories\Company::PAYMENT_NOT);
            $company->setAddPeople(JWT::getUid());
            if(!$company->save()) {
                foreach ($company->getMessages() as $message) {
                    throw new Exception($message);
                }
            }

            //企业ID
//            $info->setCompanyId($company->getId());
            $data['company_id'] = $company->getId();
            //保存证件信息
            if(!$info->save()) {
                foreach ($info->getMessages() as $message) {
                    throw new Exception($message);
                }
            }
            //证件ID
            $company->setInfoId($info->getId());
            if(!$company->save()) {
                foreach ($company->getMessages() as $message) {
                    throw new Exception($message);
                }
            }
            Follow::follow($data['company_id']);
            $data['is_follow'] = Follow::checkFollow($data['company_id']);
            $data['is_settled'] = '否';
            $data['admin'] = '无';
            return $data;
        }
        throw new Exception("扫描失败");
    }

    static function manualAddCompanyForm($data)
    {
        try {
            $manager = new TxManager();
            $transaction = $manager->get();

            $info = new EntityCompanyInfo();
            $info->setTransaction($transaction);
            $info->setLicenceNum($data['licence_num']);
            $info->setLegalName($data['legal_name']);
            $info->setPeriod($data['period']);
            $info->setAddress($data['address']);
            $info->setProvince($data['province']);
            $info->setCity($data['city']);
            $info->setDistrict($data['district']);
            $info->setScope($data['scope']);
            $info->setType($data['type']);
            $info->setUrl($data['url']);
            if($info->save() === false) {
                $transaction->rollback($info->getMessages()[0]);
            }

            $company = new EntityCompany();
            $company->setTransaction($transaction);
            $company->setInfoId($info->getId());
            $company->setName($data['name']);
            $company->setStatus(\Wdxr\Models\Repositories\Company::STATUS_DISABLE);
            $company->setAuditing(\Wdxr\Models\Repositories\Company::AUDIT_NOT);
            $company->setTime(date('Y-m-d H:i:s',time()));
            $company->setDeviceId(0);
            $company->setAddPeople(JWT::getUid());
            if($company->save() == false) {
                $transaction->rollback($company->getMessages()[0]);
            }
            $transaction->commit();
            return $company->getId();
        } catch (TxFailed $e) {
            throw new InvalidServiceException($e->getMessage());
        }
    }

    //获取所有缴费待申请企业列表
    static public function getUnPaymentListPagintor($parameters,$numberPage)
    {
        $conditions = '1=1';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->from(['company' => 'Wdxr\Models\Entities\Companys'])
            ->where($conditions,$bind)
            ->andWhere("company.status <> 3 ")
            ->andWhere(" ISNULL(service.id) ")
            ->leftJoin('Wdxr\Models\Entities\CompanyService','company.id = service.company_id', 'service')
            ->leftJoin('Wdxr\Models\Entities\CompanyInfo','info.id = company.info_id', 'info')
            ->columns(["company.id","company.name","info.licence_num","company.time"])
            ->orderBy("company.id desc")
            ->getQuery()
            ->execute()
            ->toArray();
        $data=[];
        foreach ($builder as $key=>$val)
        {
            $payment = (new RepoCompanyPayment())->getRPaymentByCompanyIdStatus($val['id']);
            $loan = \Wdxr\Models\Repositories\Loan::getByCompanyIdStatus($val['id']);
            if($payment === false && $loan === false){
                $data[] = $val;
            }
        }
        $numberPage= empty($numberPage)?1:$numberPage;
        for ($i=($numberPage-1)*10;$i<= $numberPage*10;$i++){
            if(!empty($data[$i])){
                $info['data'][]=$data[$i];
            }
        }
        $info['current']=$numberPage;
        $info['total_pages']=ceil(count($data)/10);
        if($numberPage-1<1){
            $info['before'] = 1;
        }else{
            $info['before']=$numberPage-1;
        }
        if($numberPage+1>$info['total_pages']){
            $info['next'] = $info['total_pages'];
        }else{
            $info['next']=$numberPage+1;
        }
        return $info;
    }

    //获取所有待申请企业列表
    static public function getUnApplyListPagintor($parameters,$numberPage)
    {
        $conditions = '1=1';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->from(['company' => 'Wdxr\Models\Entities\Companys'])
            ->andWhere($conditions,$bind)
            ->andWhere("company.status <> 3 ")
            ->andWhere("company.auditing = 0 ")
            ->leftJoin('Wdxr\Models\Entities\CompanyInfo','info.id = company.info_id', 'info')
            ->columns(["company.id","company.name","info.licence_num","company.time"])
            ->orderBy("company.id desc");
        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);

    }


    static function manualAddCompanyInfo($data)
    {
        $info = new EntityCompanyInfo();
        $company = new EntityCompany();
        $period= date('Y/m/d',strtotime($data['period_start']));
        if(!empty($data['period_end'])){
            $period.= '-'.date('Y/m/d',strtotime($data['period_end']));
        }
        $info->setLicenceNum($data['licence_num']);
        $info->setLegalName($data['legal_name']);
        $info->setPeriod($period);
        $info->setAddress($data['address']);
        $info->setProvince($data['provinces']);
        $info->setCity($data['cities']);
        $info->setDistrict($data['areas']);
        $info->setScope($data['scope']);
        $info->setType($data['type']);
        $info->setUrl($data['url']);
        $info->setType($data['type']);
        $info->setCreateAt(date('Y-m-d H:i:s',time()));
        $company->setName($data['name']);
        $company->setAuditing(\Wdxr\Models\Repositories\Company::AUDIT_NOT);
        $company->setTime(date('Y-m-d H:i:s',time()));
        $company->setDeviceId(0);
        $company->setStatus(\Wdxr\Models\Repositories\Company::STATUS_DISABLE);
        $company->setAddPeople($data['add_people']);
        if(!$company->save()) {
            foreach ($company->getMessages() as $message) {
                throw new Exception($message);
            }
        }
        //保存证件信息
        if(!$info->save()) {
            foreach ($info->getMessages() as $message) {
                throw new Exception($message);
            }
        }
        //证件ID
        $company->setInfoId($info->getId());
        if(!$company->save()) {
            foreach ($company->getMessages() as $message) {
                throw new Exception($message);
            }
        }
        return $company->getId();
    }

    /**
     * 企业审核
     * @param int $company_id
     * @param $status
     * @param null $data
     * @return bool
     * @throws InvalidServiceException
     * @internal param null $account
     */
    static public function companyAudit($company_id, $status, $data = null)
    {
        $company = RepoCompany::getCompanyById($company_id);
        if($status == RepoCompanyVerify::STATUS_OK) {
            $company->setAuditing(RepoCompany::AUDIT_OK);
            $company->setAccountId($data['account']);
            $company->setStatus(RepoCompany::STATUS_DISABLE);
        } elseif($status == RepoCompanyVerify::STATUS_FAIL) {
            $company->setAuditing(RepoCompany::AUDIT_REVOKED);
        } else {
            return false;
        }
        if ($company->save() === false) {
            $messages = $company->getMessages();
            foreach ($messages as $message) {
                throw new InvalidServiceException($message->getMessage() ? : '企业状态审核失败');
            }
        }

        return true;
    }

    /**
     * 获取企业基本信息及详细信息
     * @param $company_id
     * @return array|bool
     */
    public function getCompanyData($company_id)
    {
        $company = RepoCompany::getCompanyById($company_id);

        $info = [];
        if(isset($company->company_info)) {
            //获取法人营业执照信息
            $info = $company->company_info->toArray();
            //获取对应图片信息
            $info['licence'] = Attachment::getAttachmentUrl($info['licence']);
            $info['account_permit'] = Attachment::getAttachmentUrl($info['account_permit']);
            $info['credit_code'] = Attachment::getAttachmentUrl($info['credit_code']);
            $info['idcard_up'] = Attachment::getAttachmentUrl($info['idcard_up']);
            $info['idcard_down'] = Attachment::getAttachmentUrl($info['idcard_down']);
            $info['photo'] = Attachment::getAttachmentUrl($info['photo']);
            $info['shop_img'] = Attachment::getAttachmentUrl($info['shop_img']);
            $info['full_address'] = Regions::getAddress($info['province'], $info['city'], $info['district'], $info['address']);
            if($company->getUserId()) {
                if(isset($company->users)) {
                    $info['account'] = $company->users->getName();
                } else {
                    $info['account'] = '暂无';
                }
            } else {
                $info['account'] = '暂无';
            }
        }

        $service = Services::Hprose('Category');
        $sub_category = $service->getByCode($company->getCategory());//当前最下级
        $top_category = $service->getByCode($sub_category['top_category']);//当前最高级
        $info['sub_category'] = $sub_category ? $sub_category['name'] : '暂无';
        $info['top_category'] = $top_category ? $top_category['name'] : '暂无';
        $info['audit_name'] = RepoCompany::getAuditName($company->getAuditing());

        $bank = [];
        //银行卡信息
        $companyBank = \Wdxr\Models\Repositories\CompanyBank::getBankcard($company_id,\Wdxr\Models\Repositories\CompanyBank::CATEGORY_MASTER);
        //绩效银行卡信息
        $workCompanyBank = \Wdxr\Models\Repositories\CompanyBank::getBankcard($company_id,\Wdxr\Models\Repositories\CompanyBank::CATEGORY_WORK);
        if($companyBank) {
            $bank['bank_type'] =  $companyBank->getBankType();
            $bank['bank'] = $companyBank->getBank();
            $bank['account'] = $companyBank->getAccount();
            $bank['number'] = $companyBank->getNumber();
            $bank['province'] = Regions::getRegionName($companyBank->getProvince())->name;
            $bank['city'] = Regions::getRegionName($companyBank->getCity())->name;
            $bank['bankcard_photo'] = Attachment::getAttachmentUrl($companyBank->getBankcardPhoto());
            $bank['address'] = $companyBank->getAddress();
            if($workCompanyBank){
                $bank['work_bank_type'] =  $workCompanyBank->getBankType();
                $bank['work_bank'] = $workCompanyBank->getBank();
                $bank['work_account'] = $workCompanyBank->getAccount();
                $bank['work_number'] = $workCompanyBank->getNumber();
                $bank['work_province'] = Regions::getRegionName($companyBank->getProvince())->name;
                $bank['work_city'] = Regions::getRegionName($companyBank->getCity())->name;
                $bank['work_bankcard_photo'] = Attachment::getAttachmentUrl($workCompanyBank->getBankcardPhoto());
                $bank['work_address'] = $workCompanyBank->getAddress();
            } else {
                $bank['work_number'] = false;
            }
        } else {
            $bank['number'] = false;
            $bank['work_number'] = false;
        }

        //显示企业的推荐人与管理人
        $recommend = is_null($company->getRecommendId()) ? "无" : RepoCompany::getCompanyById($company->getRecommendId())->getName();
        $manager = is_null($company->getManagerId()) ? "无" : RepoCompany::getCompanyById($company->getManagerId())->getName();
        $company = $company->toArray();
        $company['recommend'] = $recommend;
        $company['manager'] = $manager;
        $company['admin_name'] = $this->getCompanyAdmin($company_id)['name'];

        return [$company, $info, $bank];
    }

    /**
     * 转移客户关系(包含该企业的所有下级)
     * @param $company_id
     * @param $admin_id
     * @return bool
     * @throws InvalidServiceException
     */
    public function transferCompany($company_id, $admin_id)
    {
        $device_id = UserAdmin::getDeviceId($admin_id, UserAdmin::TYPE_ADMIN);

        $company = RepoCompany::getCompanyById($company_id);
        $old_device_id = UserAdmin::getDeviceId($company->getAdminId(), UserAdmin::TYPE_ADMIN);

        $this->db->begin();
        $company->setAdminId($admin_id);
        if($company->getDeviceId() == $old_device_id) {
            $company->setDeviceId($device_id);
        }
        if($company->save() === false) {
            $this->db->rollback();
            throw new InvalidServiceException("企业所属关系修改失败");
        }

        $recommends = \Wdxr\Models\Entities\CompanyRecommend::find(['conditions' => 'recommender = :id:',
            'bind' => ['id' => $company->getId()],
            'order' => 'time desc']);
        foreach($recommends as $recommend) {
            if($recommend->getDeviceId() == $old_device_id) {
                $recommend->setDeviceId($device_id);
                if($recommend->save() === false) {
                    $this->db->rollback();
                    throw new InvalidServiceException("推荐的企业所属关系修改失败");
                }
            }
            if($this->transferCompany($recommend->getRecommendId(), $admin_id) === false) {
                $this->db->rollback();
                throw new InvalidServiceException("下级推荐的企业所属关系修改失败");
            }
        }

        $this->db->commit();
        return true;
    }

    /**
     * 根据企业ID获取所属业务员
     * @param $company_id
     * @return array
     */
    public function getCompanyAdmin($company_id)
    {
        $company = \Wdxr\Models\Repositories\Company::getCompanyById($company_id);
        /**
         * @var $admin \Wdxr\Models\Repositories\Admin
         */
        $admin = Repositories::getRepository('Admin');
        $name = $admin->getAdminName($company->getAdminId());

        return ['id' => $company->getAdminId(), 'name' => $name];
    }

}
