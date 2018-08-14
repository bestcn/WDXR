<?php
namespace Wdxr\Modules\Cli\Tasks;

use Phalcon\Exception;
use Phalcon\Queue\Beanstalk;
use Phalcon\Queue\Beanstalk\Job;
use Wdxr\Models\Entities\Companys;
use Wdxr\Models\Repositories\BillTerm;
use Wdxr\Models\Repositories\CompanyBank;
use Wdxr\Models\Repositories\CompanyBill;
use Wdxr\Models\Repositories\CompanyPayment;
use Wdxr\Models\Repositories\CompanyReport;
use Wdxr\Models\Repositories\CompanyService;
use Wdxr\Models\Repositories\Level;
use Wdxr\Models\Repositories\Repositories;
use Wdxr\Models\Repositories\Statistics;
use Wdxr\Models\Repositories\Temp;
use Wdxr\Models\Repositories\Term;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\CompanyInfo;
use Wdxr\Models\Repositories\CompanyRecommends;
use Wdxr\Models\Repositories\Finance;
use Wdxr\Models\Repositories\Recommend;
use Wdxr\Models\Repositories\Manage;
use Phalcon\logger\Adapter\File as FileAdapter;
use Wdxr\Models\Services\SMS;
use Wdxr\Models\Services\PushService;
use Wdxr\Time;

/**
 * Class MainTask
 * @package Wdxr\Modules\Cli\Tasks
 */
class MainTask extends \Phalcon\Cli\Task
{

    static private $_logger;

    public function logger()
    {
        if (is_null(self::$_logger)) {
            $name = BASE_PATH."/cache/logs/".date("Ymd").".log";
            self::$_logger = new FileAdapter($name);
        }
        return self::$_logger;
    }

    public function mainAction()
    {
        $company_id = 1;
//        echo date('Y-m-d H:i:s', '1537804800')."\n\n";

        /**
         * @var $bill_term BillTerm
         */
        $bill_term = Repositories::getRepository('BillTerm');
        $previous_deadline = $bill_term->getPreviousDeadline($company_id);
        $deadline = $bill_term->getCompanyBillDeadline($company_id);

        /**
         * @var $company_bill CompanyBill
         */
        $company_bill = Repositories::getRepository('CompanyBill');
        $min_amount = $company_bill->getDeadlineAmount($company_id, $previous_deadline);
        $amount = $company_bill->getDeadlineAmount($company_id, $deadline);

        echo $min_amount."\n";
        echo $amount."\n";
    }

    /**
     *票据定时验证
     */
    public function checkBillAction()
    {
        /**
         * @var $company_service CompanyService
         */
        $company_service = Repositories::getRepository('CompanyService');
        $companys = $company_service->getServiceCompany()->getQuery()->execute();
        /**
         * @var $company_bill CompanyBill
         */
        $company_bill = Repositories::getRepository('CompanyBill');
        /**
         * @var $bill_term BillTerm
         */
        $bill_term = Repositories::getRepository('BillTerm');

        foreach ($companys as $key => $company) {
            $company_id = $company->id;
            $service = CompanyService::getCompanyService($company_id);
            echo "key: " . $key . "\t\t company id: " . $company_id."\n";

            $deadline = $bill_term->getCompanyBillDeadline($company_id);

            //当前剩余票据金额
            $remain_amount = $company_bill->getRemainAmount($company_id);
            $bill = $company_bill->getCompanyBillByCompanyId($company_id);
            $bill->setAmount($remain_amount);

            //本期应交票据金额
            $amount = $company_bill->getDeadlineAmount($company_id, $deadline);
            if ($bill->getTotal() > $amount || $bill->getTotal() == $amount) {
                $bill->setStatus(CompanyBill::STATUS_ENABLE);
                $bill->setBeforePeriod(0);
                $service->setBillStatus(CompanyBill::STATUS_ENABLE);
            }

            //本期最低票据金额
            $min_amount = $company_bill->getMinimumAmount($company_id);
            if ($bill->getTotal() < $min_amount) {
                $bill->setStatus(CompanyBill::STATUS_DISABLE);
                $bill->setBeforePeriod(1);
                $service->setBillStatus(CompanyBill::STATUS_DISABLE);
            }

            if ($bill->getTotal() > $min_amount && $bill->getTotal() < $amount) {
                $bill->setStatus(CompanyBill::STATUS_DISABLE);
                $bill->setBeforePeriod(0);
                $service->setBillStatus(CompanyBill::STATUS_DISABLE);
            }

//            if (floor(($bill->getEndTime() - $time) / 86400) <= 7 && $bill->getAmount() <= $company->day_amount * 7) {
//                SMS::BillSMS(
//                    $company->contact_phone,
//                    $company->name,
//                    date('Y年m月d日', $bill->getEndTime())
//                );
//                //推送用户消息
//                $push = new PushService();
//                $content['title'] = "(".$company->legal_name.")票据金额不足";
//                $content['body'] = '您的客户('.$company->legal_name.')的企业票据金额不足!';
//                $content['type'] = PushService::PUSH_TYPE_WARN;
//                $push->newPushResult($content, $company->device_id);
//            }
            if ($bill->save() === false || $service->save() === false) {
                echo $company_id." failed!\n";
            }
        }
    }

    public function makeAction()
    {
        //获取已经审核成功的企业基本信息
        $companys = Company::getTrueCompany();
        /**
         * 查询企业其他信息
         * @var $company        Companys
         * @var $infos          \Wdxr\Models\Entities\CompanyInfo
         */
        foreach ($companys as $key => $company) {
            try {
                //判断是否重复
                if (Finance::checkOnly($company->getId()) === false) {
                    continue;
                }
                $data = array();
                $benefits = \Wdxr\Models\Services\Company::getCompanyBenefitsForMainTask($company);
                $data['money'] = $benefits['money'] ?: 0;
                $data['status'] = $benefits['status'];
                $data['info'] = $benefits['info'];
                $company_bank = (new CompanyBank())->getBank($company->getId(), CompanyBank::CATEGORY_MASTER);//银行卡信息
                $infos = $company->company_info;
                $data['byid'] = $company->getId();
                $data['account_id'] = $company->getAccountId();
                $data['makecoll'] = $company_bank->getNumber();
                $data['phone'] = $infos->getContactPhone();
                $data['company_id'] = $company->getId();
                $data['name'] = $company_bank->getAccount();
                $data['bank_name'] = CompanyBank::getBankAddress($company_bank->getId());
                $data['remark'] = '房租物业水电费';
                //获取已报天数
                $data['day_count'] = Finance::getFinanceCount($company->getId());
                //获取起始时间
                $service_data = (new CompanyService())->getCompanyServiceByCompanyId($company->getId());
                $data['start_time'] = date('Y-m-d H:i:s', $service_data->getStartTime());
                $data['end_time'] = date('Y-m-d H:i:s', $service_data->getEndTime());
                Finance::addNew($data);
            } catch (InvalidServiceException $exception) {
                $this->logger()->error("[finance-".$company->getId()."]".$exception->getMessage());
            } catch (InvalidRepositoryException $exception) {
                echo $exception->getMessage()."\n";
                $this->logger()->error("[finance-".$company->getId()."]".$exception->getMessage());
            }
        }
    }

    //废弃
//    public function recommendAction()
//    {
//        //获取所有推荐列表
//        $recommends = Company::getRecommendCompany();
//        foreach ($recommends as $recommend) {
//            try {
//                //判断是否重复
//                if(Recommend::checkOnly($recommend['company_id']) === false){
//                    continue;
//                }
//                $company = Company::getCompanyById($recommend['company_id']);
//                $Benefits = \Wdxr\Models\Services\Company::getCompanyBenefitsForMainTask($company);
//
//                $info = $company->company_info;
//                $company_bank = CompanyBank::getWorkBankcard($company->getId());
//                $data = array();
//                $data['status'] = $Benefits['status'];
//                $data['byid'] = $company->getId();
//                $data['account_id'] = $company->getAccountId();
//                $data['makecoll'] = $company_bank->getNumber();
//                $data['phone'] = $info->getContactPhone();
//                $data['company_id'] = $company_bank->getAccount();
//                $data['bank_name'] = CompanyBank::getBankAddress($company_bank->getId());
//                $data['money'] = Company::getRecommendMoney($company->getId());
//                if($data['money'] == 0){
//                    continue;
//                }
//                //获取已报天数
//                $data['day_count'] = Recommend::getRecommendCount($company->getId());
//                //获取起始时间
//                $service_data = (new CompanyService())->getCompanyServiceById($company->getId());
//                $data['start_time'] = date('Y-m-d H:i:s', $service_data->getStartTime());
//                $data['end_time'] = date('Y-m-d H:i:s', $service_data->getEndTime());
//                $data['remark'] = '推荐奖励';
//                $data['time'] = time();
//                Recommend::addNew($data);
//            } catch(InvalidServiceException $Exception){
//                $this->logger()->error($Exception->getMessage().'--'.$recommend['company_id'].'--recommend');
//            } catch(InvalidRepositoryException $Exception){
//                $this->logger()->error($Exception->getMessage().'--'.$recommend['company_id'].'--recommend');
//            }
//        }
//    }

    //废弃
//    public function manageAction()
//    {
//        //获取所有推荐列表
//        $managers = Company::getManageCompany();
//        foreach ($managers as $manager) {
//            try {
//                //判断是否重复
//                if(Manage::checkOnly($manager['company_id']) === false) {
//                    continue;
//                }
//                $company = Company::getCompanyById($manager['company_id']);
//                $Benefits = \Wdxr\Models\Services\Company::getCompanyBenefitsForMainTask($company);
//                if($Benefits['status'] != 1){
//                    continue;
//                }
//                $info = $company->company_info;
//                $company_bank = CompanyBank::getWorkBankcard($company->getId());
//                $data = array();
//                $data['status'] = $Benefits['status'];
//                $data['byid'] = $company->getId();
//                $data['account_id'] = $company->getAccountId();
//                $data['makecoll'] = $company_bank->getNumber();
//                $data['phone'] = $info->getContactPhone();
//                $data['company_id'] = $company_bank->getAccount();
//                $data['bank_name'] = CompanyBank::getBankAddress($company_bank->getId());
//                $data['money'] = Company::getManageMoney($company->getId());
//                if($data['money'] == 0){
//                    continue;
//                }
//                //获取已报天数
//                $data['day_count'] = Manage::getManageCount($company->getId());
//                //获取起始时间
//                $service_data = (new CompanyService())->getCompanyServiceById($company->getId());
//                $data['start_time'] = date('Y-m-d H:i:s', $service_data->getStartTime());
//                $data['end_time'] = date('Y-m-d H:i:s', $service_data->getEndTime());
//                $data['remark'] = '管理奖金';
//                $data['time'] = time();
//                Manage::addNew($data);
//            } catch(InvalidServiceException $Exception){
//                $this->logger()->error($Exception->getMessage().'--'.$manager['company_id'].'--Manage');
//            } catch(InvalidRepositoryException $Exception){
//                $this->logger()->error($Exception->getMessage().'--'.$manager['company_id'].'--Manage');
//            }
//        }
//    }


    //企业的全部统计
    public function statisticsAction()
    {
        $finance = new Finance();
        $recommends = new Recommend();
        $manages = new Manage();
        $statistics = new Statistics();
        $temp = new Temp();
        //每日
        $finance_data = $finance->getYesterdayList();
        if ($finance_data->toArray()) {
            $finance_data_array = $finance_data->toArray();
        }
        //推荐
//        $recommends_data = $recommends->getYesterdayList();
//        if($recommends_data->toArray()){
//            $recommends_data_array = $recommends_data->toArray();
//        }
        //管理
//        $manages_data = $manages->getYesterdayList();
//        if($manages_data->toArray()){
//            $manages_data_array = $manages_data->toArray();
//        }
        //合伙人奖金
        $temp_data = $temp->getYesterdayList();
        if ($temp_data->toArray()) {
            $temp_data_array = $temp_data->toArray();
        }
        try {
            if (isset($finance_data_array)) {
                //推荐奖金
//                foreach($finance_data_array as $key=>$val){
//                    if(isset($recommends_data_array)){
//                        foreach($recommends_data_array as $k=>$v){
//                            if($val['byid'] == $v['byid']){
//                                $finance_data_array[$key]['recommend_money'] = $v['recommend_money'];
//                            }
//                        }
//                    }
//                    if(!isset($finance_data_array[$key]['recommend_money'])){
//                        $finance_data_array[$key]['recommend_money'] = 0;
//                    }
//                }
                //管理奖金
//                foreach($finance_data_array as $key=>$val){
//                    if(isset($manages_data_array)){
//                        foreach($manages_data_array as $k=>$v){
//                            if($val['byid'] == $v['byid']){
//                                $finance_data_array[$key]['manage_money'] = $v['manage_money'];
//                            }
//                        }
//                    }
//                    if(!isset($finance_data_array[$key]['manage_money'])){
//                        $finance_data_array[$key]['manage_money'] = 0;
//                    }
//                }
                //合伙人奖金
                foreach ($finance_data_array as $key => $val) {
                    if (isset($temp_data_array)) {
                        foreach ($temp_data_array as $k => $v) {
                            if ($val['byid'] == $v['company_name']) {
                                $finance_data_array[$key]['bonus'] = $v['bonus'];
                            }
                        }
                    }
                    if (!isset($finance_data_array[$key]['bonus'])) {
                        $finance_data_array[$key]['bonus'] = 0;
                    }
                }

                //添加之前查看是否已经添加
                //添加到统计列表
                foreach ($finance_data_array as $key => $val) {
                    $statistics->addNew($val);
                }
                //添加完成之后,删除临时表数据
                $temp->delete();
            }
        } catch (InvalidRepositoryException $exception) {
            $this->logger()->error($exception->getMessage());
        }
    }



    public function BillAndReportAction()
    {
        $time = time();//当前时间
        $report = new CompanyReport();
        $report_data = $report->getLast()->toArray();
        foreach($report_data as $key=>$val){
            $company = new Company();
            $company_data = $company->getById($val['company_id']);
            if($company_data->getAuditing() == Company::AUDIT_OK && $company_data->getStatus() == Company::STATUS_ENABLE && $company_data->getReportId() == $val['id']) {
                if ($val['end_time'] != null) {
                    if (floor(($val['end_time'] - $time) / 86400) <= 7 && $val['status'] != 1 ) {
                        $company_info_data = CompanyInfo::getCompanyInfoById($company_data->getInfoId());
                        SMS::ReportSMS((int)$company_info_data->getContactPhone(), $company_info_data->getLegalName(), date('Y年m月d日', $val['end_time']));
                        //推送用户消息
                        $company_data = Company::getCompanyById($val['company_id']);
                        $push = new PushService();
                        $content['title'] = "(".$company_info_data->getLegalName().")征信待提交";
                        $content['body'] = '您的客户('.$company_info_data->getLegalName().')的企业征信信息尚未提交!';
                        $content['type'] = PushService::PUSH_TYPE_WARN;
                        $push->newPushResult($content,$company_data->getDeviceId());
                    }
                }
            }
        }
    }


}
