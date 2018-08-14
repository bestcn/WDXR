<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Companys as EntityCompany;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Models\Entities\CompanyBill as EntityCompanyBill;
use Wdxr\Models\Repositories\Level as RepoLevel;
use Wdxr\Models\Repositories\CompanyBill as RepoCompanyBill;
use Wdxr\Models\Entities\CompanyVerify as EntityCompanyVerify;
use Wdxr\Models\Repositories\CompanyVerify as RepoCompanyVerify;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Entities\CompanyBillLog as EntityCompanyBillLog;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Models\Services\Services;
use Wdxr\Modules\Admin\Controllers\SettingController;
use Wdxr\Time;

class CompanyBill extends Repositories
{
    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;
    const STATUS_OVERDUE = 2;

    const TYPE_RENT = 1;//房租
    const TYPE_PROPERTY_FEE = 4;//物业
    const TYPE_WATER_FEE = 2;//水费
    const TYPE_ELECTRICITY = 3;//电费

    static public function getTypeName($type)
    {
        switch ($type)
        {
            case self::TYPE_RENT:
                return "房租发票";
            case self::TYPE_WATER_FEE:
                return "水费发票";
            case self::TYPE_PROPERTY_FEE:
                return "物业费";
            case self::TYPE_ELECTRICITY:
                return "电费发票";
            default:
                return "其他票据";
        }
    }

    /**
     * 获取当期补交票据信息
     * @param $company_id
     * @return EntityCompanyBill
     * @throws InvalidRepositoryException
     */
    public static function getCurrentCompanyBill($company_id)
    {
        $company_service = (new CompanyService())->getCompanyServiceByCompanyId($company_id);
        $bill = EntityCompanyBill::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $company_service->getBillId()]
        ]);
        if ($bill === false) {
            throw new InvalidRepositoryException("企业票据信息获取失败");
        }

        return $bill;
    }

    public static function getCompanyBillByVerifyId($verify_id)
    {
        return EntityCompanyBill::findFirst([
            'conditions' => 'verify_id = :verify_id:',
            'bind' => ['verify_id' => $verify_id]
        ]);
    }

    /**
     * @param $id
     * @return EntityCompanyBill
     */
    public static function getCompanyBillById($id)
    {
        /**
         * @var $admin EntityCompanyBill
         */
        $company = EntityCompanyBill::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $company;
    }

    public function getCompanyBillByCompanyId($id)
    {
        return EntityCompanyBill::findFirst([
            'conditions' => 'company_id = :company_id:',
            'bind' => ['company_id' => $id],
            'order' => 'id desc'
        ]);
    }

    public function setBillTime($payment_type, $company_id, $service_start_time)
    {
        $company = Company::getCompanyById($company_id);
        //票据
        $term = new Term();
        $term_data = $term->getTermByPayment($payment_type);//期限数据
        if ($term_data != false) {
            $array = array();
            $array['company_name'] = $company->getName();//公司名称
            $array['company_id'] = $company_id;
            $array['payment'] = $payment_type;
            $array['term'] = $term_data->getTerm();
            $array['type'] = $term_data->getType();
            $array['time'] = $service_start_time;
            $bill_term = new BillTerm();
            $bill_term->addNew($array);
        } else {
            $array = array();
            $array['company_name'] = $company->getName();//公司名称
            $array['company_id'] = $company_id;
            $array['payment'] = $payment_type;
            $array['term'] = SettingController::DATE_DEFAULT;
            $array['type'] = SettingController::DATE_TYPE_MONTH;
            $array['time'] = $service_start_time;
            $bill_term = new BillTerm();
            $bill_term->addNew($array);
        }
        //票据截止时间
        $end_time = \Wdxr\Models\Services\Company::BillEndTime($array['term'], $array['type'], $service_start_time);

        //设置票据截止日期
        $bill_data = CompanyBill::getCurrentCompanyBill($company_id);
        $bill_data->setEndTime($end_time);
        if ($bill_data->save() == false) {
            throw new \Phalcon\Exception('企业票据审核设置失败');
        }
        return true;
    }


    //修改审核人ID
    public function save_auditing($id,$verify_id)
    {
        $company = self::getCompanyBillById($id);
        if (!$company->save()) {
            throw new InvalidRepositoryException($company->getMessages()[0]);
        }

        return true;
    }

    /**
     * 添加票据日志
     * @param $bill_id
     * @param $type
     * @param $deceive_id
     * @param $verify_id
     * @return int
     * @throws InvalidRepositoryException
     */
    static public function newCompanyBillLog($bill_id, $type, $deceive_id, $verify_id, $company_id)
    {
        $bill_log = new EntityCompanyBillLog();
        $bill_log->setType($type);
        $bill_log->setBillId($bill_id);
        $bill_log->setDeceiveId($deceive_id);
        $bill_log->setVerifyId($verify_id);
        $bill_log->setCompanyId($company_id);

        if(!$bill_log->save()) {
            $msg = $bill_log->getMessages()[0] ? : "添加票据记录失败";
            throw new InvalidRepositoryException($msg);
        }
        return $bill_log->getId();
    }

    /**
     * 添加新票据
     * @param $company_id
     * @return int
     * @throws InvalidRepositoryException
     */
    public static function addCompanyBill($company_id)
    {
        $company = new EntityCompanyBill();
        $company->setCompanyId($company_id);//企业ID
        $company->setStatus(self::STATUS_DISABLE);
        $company->setBeforePeriod(0);
        if (!$company->save()) {
            throw new InvalidRepositoryException($company->getMessages()[0]);
        }

        return $company->getId();
    }

    /**
     * 当前某一个企业应该缴纳的票据总额
     * @param $company_id
     * @return float
     * @throws InvalidServiceException
     */
    public static function getBillPayableAmount($company_id)
    {
        $service = CompanyService::getCompanyService($company_id);
        if ($service === false) {
            throw new InvalidServiceException("该企业服务尚未生效");
        }
        $day_amount = RepoLevel::getLevelDayAmount($service->getLevelId());

        $days = ($service->getEndTime() - $service->getStartTime())/86400;

        return floor($days) * $day_amount;
    }

    /**
     * 获取当前票据的状态
     * @param $company_id
     * @return array
     */
    public static function getBillVerifyStatus($company_id)
    {
        $payable = self::getBillPayableAmount($company_id);
        $bill = self::getCurrentCompanyBill($company_id);
        $status = $bill->getTotal() < $payable ? false : true;

        if ($status) {
            return ['status' => 1, 'status_name' => "正常"];
        }
        return ['status' => 0, 'status_name' => "待补交票据"];
    }

    public function getBillStatus($company_id)
    {
        $payable = self::getBillPayableAmount($company_id);
        $bill = self::getCurrentCompanyBill($company_id);
        $status = $bill->getTotal() < $payable ? false : true;

        if ($status) {
            return 0;
        }
        return 1;
    }

    /**
     * 获取企业在当前期限内应交票据金额
     * @param $company_id
     * @param $deadline
     * @return float|int
     */
    public function getDeadlineAmount($company_id, $deadline)
    {
        $service = CompanyService::getCompanyService($company_id);
        if ($deadline == $service->getStartTime()) {
            return 0;
        }

        $start_time = Time::getFloorTime($service->getStartTime());
        $days = ($deadline + 1 - $start_time) / 86400;

        return $days * Level::getLevelDayAmount($service->getLevelId());
    }

    /**
     * 获取实际应报票据金额
     * @param $company_id
     * @return float
     */
    public function getActuallyAmount($company_id)
    {
        $service_days = CompanyService::getServiceDays($company_id);

        $service = CompanyService::getCompanyService($company_id);
        $level_id = $service->getLevelId();
        return Level::getLevelDayAmount($level_id) * $service_days;
    }

    /**
     * 获取企业本期的最低票据金额
     * @param $company_id
     * @return float|int
     */
    public function getMinimumAmount($company_id)
    {
        /**
         * @var $bill_term BillTerm
         */
        $bill_term = Repositories::getRepository('BillTerm');
        $deadline = $bill_term->getCompanyBillDeadline($company_id);
        $previous_deadline = $bill_term->getPreviousDeadline($company_id, $deadline);
        $min_amount = $this->getDeadlineAmount($company_id, $previous_deadline);

        return $min_amount;
    }

    /**
     * 获取剩余票据金额
     * @param $company_id
     * @return float
     */
    public function getRemainAmount($company_id)
    {
        $service_days = CompanyService::getServiceDays($company_id);
        $service = CompanyService::getCompanyService($company_id);
        $day_amount = Level::getLevelDayAmount($service->getLevelId());

        $bill = $this->getCompanyBillByCompanyId($company_id);
        return $bill->getTotal() - $service_days * $day_amount;
    }

}