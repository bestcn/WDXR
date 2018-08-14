<?php
namespace Wdxr\Models\Services;

use Wdxr\Models\Entities\Users as EntityUser;
use Wdxr\Models\Entities\Companys as EntityCompany;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Models\Entities\CompanyPayment as EntityCompanyPayment;
use Wdxr\Models\Repositories\CompanyPayment as RepoCompanyPayment;
use Wdxr\Models\Repositories\CompanyVerify as RepoCompanyVerify;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;

class CompanyPayment extends Services
{

    /**
     * 获取缴费列表（普惠生成的缴费除外）
     * @param $device_id
     * @param int $page
     * @param $where
     * @param $sort
     * @return array
     */
    static public function getPaymentList($device_id, $page = 1,$where,$sort)
    {
        $payments = Services::getStaticModelsManager()->createBuilder()
            ->where('device_id = :device_id: and status <> :status:', ['device_id' => $device_id, 'status' => RepoCompanyPayment::STATUS_LOAN])
            ->andWhere($where)
            ->from('Wdxr\Models\Entities\CompanyPayment')
            ->orderBy($sort)
            ->limit(10, $page * 10 - 10)
            ->getQuery()
            ->execute();

        if($payments->count() === 0) {
            return [];
        }

        $list = [];
        /**
         * @var $payment \Wdxr\Models\Entities\CompanyPayment
         */
        foreach ($payments as $payment)
        {
            if($payment->companys == false) {
                continue;
            }
            $list[] = [
                'company_id' => $payment->getCompanyId(),
                'payment_id' => $payment->getId(),
                'level_id'=>$payment->getLevelId(),
                'company_name' => $payment->companys->name,
                'apply_time' => date('Y/m/d H:i:s', $payment->getTime()),
                'status' => $payment->getStatus(),
                'status_name' => self::getStatusName($payment->getStatus())
            ];
        }

        return $list;
    }

    static public function getStatusName($status)
    {
        switch ($status)
        {
            case RepoCompanyPayment::STATUS_NOT:
                return '未申请';
            case RepoCompanyPayment::STATUS_APPLY:
                return '已申请';
            case RepoCompanyPayment::STATUS_OK:
                return '已核实';
            case RepoCompanyPayment::STATUS_FAIL:
                return '未通过';
            case RepoCompanyPayment::STATUS_CANCEL;
                return '已撤销';
            case RepoCompanyPayment::STATUS_LOAN:
                return '普惠申请';
            default:
                return '错误';
        }
    }

    /**
     * 撤销缴费信息
     * @param $payment_id
     * @return bool
     * @throws InvalidServiceException
     */
    static public function cancelPayment($payment_id)
    {
        $payment = EntityCompanyPayment::findFirst("id = $payment_id");
        if($payment === false) {
            throw new InvalidServiceException('请求的缴费信息不存在');
        }
        if($payment->getStatus() == RepoCompanyPayment::STATUS_CANCEL) {
            throw new InvalidServiceException("该缴费信息已经被撤销");
        }
        /**
         * @var $company \Wdxr\Models\Entities\Companys
         */
        if(($company = $payment->companys) === false) {
            throw new InvalidServiceException('缴费的公司信息错误');
        }
        if($payment->getStatus() != RepoCompanyPayment::STATUS_APPLY) {
            throw new InvalidServiceException('该缴费信息不可撤销');
        }
        $payment->companys->setPayment(RepoCompany::PAYMENT_CANCEL);
        $verify = RepoCompanyVerify::getVerifyInfoByDataId($payment_id, RepoCompanyVerify::TYPE_PAYMENT);
        $verify->setStatus(RepoCompanyVerify::STATUS_CANCEL);
        $payment->setStatus(RepoCompanyPayment::STATUS_CANCEL);

        return $payment->save();
    }

    /**
     * 修复手动关闭企业造成的财务数据错误
     * @return bool
     */
    public function repair_finance_data()
    {
        $companies = \Wdxr\Models\Entities\Companys::find([
            'conditions' => 'status <> :status:',
            'bind' => ['status' => 1]
        ]);

        $this->db->begin();
        foreach ($companies as $company) {
                $company_payments = \Wdxr\Models\Entities\CompanyPayment::find([
                    'conditions' => 'company_id = :company_id: and status = :status:',
                    'bind' => ['company_id' => $company->getId(), 'status' => \Wdxr\Models\Repositories\CompanyPayment::STATUS_OK],
                ]);
                foreach ($company_payments as $company_payment) {
                    $company_payment->setStatus(5);
                    if($company_payment->save() === false) {
                        $this->db->rollback();
                        return false;
                    }
                }
                $achievements = \Wdxr\Models\Entities\Achievement::find([
                    'conditions' => 'company_name = ?0',
                    'bind' => [$company->getName()]
                ]);
                foreach ($achievements as $achievement) {
                    if($achievement->delete() === false) {
                        $this->db->rollback();
                        return false;
                    }
                }
        }
        $this->db->commit();
        return true;
    }


}