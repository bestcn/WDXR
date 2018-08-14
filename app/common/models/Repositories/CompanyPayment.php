<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\CompanyPayment as EntityCompanyPayment;
use Wdxr\Models\Entities\Companys;
use Wdxr\Models\Repositories\CompanyVerify as RepoCompanyVerify;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class CompanyPayment extends Repositories
{

    const STATUS_NOT = 0;
    const STATUS_APPLY = 1;
    const STATUS_OK = 2;
    const STATUS_FAIL = 3;
    const STATUS_CANCEL = 4;
    /**
     * 普惠申请时特别标注的状态
     */
    const STATUS_LOAN = 5;

    const TYPE_TRANSFER = 1;
    const TYPE_CASH = 2;
    const TYPE_POS = 3;
    const TYPE_LOAN = 4;
    const TYPE_REFUND = 5;

    public static function getTypeName($type)
    {
        switch ($type) {
            case self::TYPE_TRANSFER:
                return "转账";
            case self::TYPE_CASH:
                return "现金";
            case self::TYPE_POS:
                return "POS";
            case self::TYPE_LOAN:
                return "贷款";
            case self::TYPE_REFUND:
                return "退费";
            default:
                return "错误";
        }
    }

    static public function getStatusName($status)
    {
        switch ($status) {
            case self::STATUS_APPLY:
                return "已申请";
            case self::STATUS_OK:
                return "正常";
            case self::STATUS_FAIL:
                return "被驳回";
            case self::STATUS_CANCEL:
                return "申请撤销";
            case self::STATUS_LOAN:
                return "普惠申请中";
            case self::STATUS_NOT:
                return "未申请";
            default:
                return "错误";
        }
    }

    /**
     * 添加缴费信息
     * @param $company_id
     * @param $amount
     * @param $device_id
     * @param $voucher
     * @param $type
     * @param int $status
     * @param $service_id
     * @return int
     * @throws InvalidRepositoryException
     */
    public static function addPayment($company_id, $amount, $device_id, $voucher, $type, $status = self::STATUS_APPLY, $service_id)
    {
        if (empty($voucher)) {
            throw new InvalidRepositoryException('请上传缴费凭证');
        }
        //创建核实缴费信息申请
        $payment = new EntityCompanyPayment();
        $payment->setCompanyId($company_id);
        $payment->setAmount($amount);
        $payment->setDeviceId($device_id);
        $payment->setVoucher($voucher);
        $payment->setTime(time());
        $payment->setStatus($status);
        $payment->setType($type);
        $payment->setLevelId(1);
        $payment->setServiceId($service_id);
        if ($status == self::STATUS_OK) {
            $payment->setVerifyTime(time());
        }
        if (!$payment->save()) {
            foreach ($payment->getMessages() as $message) {
                throw new InvalidRepositoryException($message->getMessage());
            }
            throw new InvalidRepositoryException('添加缴费信息失败');
        }
        if ($type != self::TYPE_LOAN) {
            $verify_status = $status == self::STATUS_OK ? CompanyVerify::STATUS_OK : CompanyVerify::STATUS_NOT;
            CompanyVerify::newVerify(
                $company_id,
                $device_id,
                RepoCompanyVerify::TYPE_PAYMENT,
                $payment->getId(),
                $verify_status
            );
        }
        return $payment->getId();
    }

    /**
     * 添加缴费信息
     * @param $company_id
     * @param $amount
     * @param $device_id
     * @param $voucher
     * @param $type
     * @return int
     * @throws InvalidRepositoryException
     */
    static public function addPaymentInfo($company_id, $amount, $device_id, $voucher, $type,$level_id,$status=self::STATUS_APPLY)
    {
        if(empty($voucher)) {
            throw new InvalidRepositoryException('请上传缴费凭证');
        }
        //创建核实缴费信息申请
        $payment = new EntityCompanyPayment();
        $payment->setCompanyId($company_id);
        $payment->setAmount($amount);
        $payment->setDeviceId($device_id);
        $payment->setVoucher($voucher);
        $payment->setTime(time());
        $payment->setStatus($status);
        $payment->setType($type);
        $payment->setLevelId($level_id);
        if(!$payment->save()) {
            foreach ($payment->getMessages() as $message) {
                throw new InvalidRepositoryException($message->getMessage());
            }
            throw new InvalidRepositoryException('添加缴费信息失败');
        }
        if($type!=self::TYPE_LOAN) {
            CompanyVerify::newVerify($company_id, $device_id, RepoCompanyVerify::TYPE_PAYMENT, $payment->getId());
        }
        return $payment->getId();
    }

    /**
     * 根据公司ID获取缴费信息
     * @param $id
     * @param null $status
     * @return bool|EntityCompanyPayment
     */
    public static function getPaymentByCompanyId($id, $status = null)
    {
        $company_service = CompanyService::getCompanyService($id);
        if ($company_service === false) {
            return false;
        }

        $status = is_null($status) ? '' : " and status = {$status}";
        $payments = EntityCompanyPayment::findFirst([
            'conditions' => 'service_id = ?0 and type <> ?1'.$status,
            'bind' => [$company_service->getId(), self::TYPE_REFUND],
            'order' => 'time asc'
        ]);

        return $payments;
    }

    public static function getCompanyPaymentByServiceId($service_id, $status = null)
    {
        $status = is_null($status) ? '' : " and status = {$status}";
        $payments = EntityCompanyPayment::findFirst([
            'conditions' => 'service_id = ?0 and type <> ?1'.$status,
            'bind' => [$service_id, self::TYPE_REFUND],
            'order' => 'time desc'
        ]);

        return $payments;
    }

    //获取企业缴费信息
    public function getPaymentByCompany($id)
    {
        return EntityCompanyPayment::findFirst([
            'conditions' => 'company_id = :company_id: and (status = :status: or status = :ok_status:)',
            'bind' => ['company_id' => $id, 'status' => self::STATUS_APPLY, 'ok_status' => self::STATUS_OK],
            'order' => 'time desc'
        ]);
    }

    public function getPaymentById2($id)
    {
        return EntityCompanyPayment::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    static public function getApplyCompanyPayment($id)
    {
        return EntityCompanyPayment::findFirst([
            'conditions' => 'company_id = :company_id: and (status = :status: or status = :ok_status:)',
            'bind' => ['company_id' => $id, 'status' => self::STATUS_APPLY, 'ok_status' => self::STATUS_OK],
            'order' => 'time desc'
        ]);
    }

    /**
     * 是否贷款
     * @param $company_id
     * @return int
     */
    static public function isPaymentLoan($company_id)
    {
        $payment = self::getPaymentByCompanyId($company_id, CompanyPayment::STATUS_OK);
        if($payment->getType() == self::TYPE_LOAN) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function getPaymentById($id)
    {
        return EntityCompanyPayment::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    static public function getPaymentByVerifyId($verify_id)
    {
        return EntityCompanyPayment::findFirst(['conditions' => 'verify_id = :verify_id:', 'bind' => ['verify_id' => $verify_id],'order' => 'id desc']);
    }

    static public function getPaymentByLoanId($id)
    {
        return EntityCompanyPayment::findFirst(['conditions' => 'company_id = :company_id:', 'bind' => ['company_id' => $id]]);
    }

    public function getRPaymentByCompanyId($id)
    {
        return EntityCompanyPayment::findFirst(['conditions' => 'company_id = :company_id:', 'bind' => ['company_id' => $id],'order' => 'time desc']);
    }

    public function getRPaymentByCompanyIdStatus($id)
    {
        return EntityCompanyPayment::findFirst(['conditions' => 'company_id = :company_id: and ( status = :status_apply: or  status = :status_ok: )', 'bind' => ['company_id' => $id,'status_apply'=>self::STATUS_APPLY,'status_ok'=>self::STATUS_OK],'order' => 'time desc']);
    }

    //统计
    public function getSumAmount()
    {
        return EntityCompanyPayment::sum(array(
            "column"     => "amount",
            "conditions" => "status = 2"
        ));
    }

    /**
     * 指定月份的营收
     * @param null $month
     * @param null $day
     * @param null $year
     * @return mixed
     */
    public function getSumAmountByMonth($month = null, $day = null, $year = null)
    {
        $month = is_null($month) ? date('m') : $month;
        $year = is_null($year) ? date('Y') : $year;
        $day = is_null($day) ? date('t') : $day;
        $start_time = mktime(0,0,0, $month, 1, $year);
        $end_time = mktime(23,59,59, $month, $day, $year);
        return EntityCompanyPayment::sum(array(
            "column"     => "amount",
            "conditions" => "status = 2 and (time between $start_time and $end_time)"
        ));
    }

    /*
    public function getSumAmountByToday()
    {
        $start_time = strtotime(date("Y-m-d"),time());
        $end_time = $start_time + 86400;
        return EntityCompanyPayment::sum(array(
            "column"     => "amount",
            "conditions" => "status = 2 and (time between $start_time and $end_time)"
        ));
    }
    */


    //获取不同缴费类型的比例
    public function getPaymentPro($type)
    {
        return EntityCompanyPayment::count(array(
            "conditions" => "type = $type and status = ".self::STATUS_OK
        ));
    }

    /**
     * 获取企业缴费列表
     * @param $company_id
     * @return EntityCompanyPayment|EntityCompanyPayment[]
     */
    public function getCompanyPaymentList($company_id)
    {
        return EntityCompanyPayment::find([
            'conditions' => 'company_id = :company_id:',
            'bind' => ['company_id' => $company_id],
            'order' => 'time desc'
        ]);
    }

    /**
     * 根据订阅服务获取一个有效的缴费信息
     * @param $service_id
     * @return EntityCompanyPayment
     */
    public function getServicePayment($service_id)
    {
        $payment = EntityCompanyPayment::findFirst([
            'conditions' => 'service_id = ?0 and status = ?1 and type <> ?2',
            'bind' => [$service_id, self::STATUS_OK, self::TYPE_REFUND],
        ]);

        return $payment;
    }

    public function setPaymentStatus(\Wdxr\Models\Entities\CompanyPayment $payment, $verify_status, $voucher = null)
    {
        $payment->setVerifyTime(time());
        if ($verify_status == CompanyVerify::STATUS_FAIL) {
            $payment->setStatus(CompanyPayment::STATUS_FAIL);
        } elseif ($verify_status == CompanyVerify::STATUS_OK) {
            $payment->setStatus(CompanyPayment::STATUS_OK);
        } elseif ($verify_status == CompanyVerify::STATUS_LOAN_FAIL) {
            $payment->setStatus(CompanyPayment::STATUS_FAIL);
        } elseif ($verify_status == CompanyVerify::STATUS_LOAN_OK) {
            $payment->setStatus(CompanyPayment::STATUS_OK);
            $payment->setVoucher($voucher);
        } else {
            throw new InvalidRepositoryException("缴费状态参数错误");
        }
        if ($payment->save() == false) {
            throw new InvalidRepositoryException("企业缴费审核失败!");
        }

        return true;
    }

    /**
     * 通过缴费审核
     * @param EntityCompanyPayment $payment
     * @param Companys $company
     * @return bool
     * @throws \Phalcon\Exception
     */
    public function doAgreePayment(\Wdxr\Models\Entities\CompanyPayment $payment, Companys $company)
    {
        //启用这个企业
        Company::enableCompany($company->getId());

        //创建一个新企业及其账号
        \Wdxr\Models\Repositories\User::addDefaultUser(
            $payment->getCompanyId(),
            $payment->getType(),
            $payment->getLevelId()
        );

        /**
         * 添加一条服务信息
         * @var $company_service CompanyService
         */
        $company_service = Repositories::getRepository('CompanyService');
        $service = $company_service->addService(
            $company->getId(),
            $payment->getId(),
            CompanyService::TYPE_PARTNER
        );

        //征信票据期限
        (new CompanyBill())->setBillTime($payment->getType(), $company->getId(), $service->getStartTime());
        (new CompanyReport())->setReportTime($payment->getType(), $service->getId());

        /**
         * 绑定合同
         * @var $contract Contract
         */
        $contract = Repositories::getRepository('Contract');
        $_contract = $contract->getLastContractNum($company->getDeviceId(), $company->getId(), $service->getId());

        //业绩添加
        BonusList::newAchievement($payment->getId(), $_contract->getId(), $service->getId());

        return true;
    }

}