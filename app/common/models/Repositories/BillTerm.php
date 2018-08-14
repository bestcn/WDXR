<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\BillTerm as EntityBillTerm;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Modules\Admin\Controllers\SettingController;
use Wdxr\Time;

class BillTerm extends Repositories
{

    private static $deadline = [];

    /**
     * @param $id
     * @return EntityBillTerm
     */
    public static function getBillTermById($id)
    {
        /**
         * @var $admin EntityBillTerm
         */
        $BillTerm = EntityBillTerm::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $BillTerm;
    }

    public function getBillTermByCompanyId($company_id)
    {
        $term = EntityBillTerm::findFirst([
            'conditions' => 'company_id = :company_id:',
            'bind' => ['company_id' => $company_id]
        ]);
        if ($term === false) {
            if (($term = (new Term())->getTermByCompanyId($company_id)) === false) {
                return false;
            }
        }

        return $term;
    }

    public function getLast()
    {
        return EntityBillTerm::query()
            ->orderBy('id asc')
            ->execute();
    }


    public function addNew($data)
    {
        $BillTerm = new EntityBillTerm();
        $BillTerm->setPayment($data["payment"]);
        $BillTerm->setCompanyId($data["company_id"]);
        $BillTerm->setCompanyName($data['company_name']);
        $BillTerm->setTerm($data["term"]);
        $BillTerm->setType($data["type"]);
        $BillTerm->setTime(time());
        if (!$BillTerm->save()) {
            throw new InvalidRepositoryException($BillTerm->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $BillTerm = BillTerm::getBillTermById($id);
        $BillTerm->setPayment($data["payment"]);
        $BillTerm->setTerm($data["term"]);
        $BillTerm->setType($data["type"]);
        if (!$BillTerm->save()) {
            throw new InvalidRepositoryException($BillTerm->getMessages()[0]);
        }

        return true;
    }

    public static function deleteTerm($id)
    {
        $BillTerm = BillTerm::getBillTermById($id);
        if (!$BillTerm) {
            throw new InvalidRepositoryException("未找到信息");
        }

        if (!$BillTerm->delete()) {
            throw new InvalidRepositoryException("信息删除失败");
        }

        return true;
    }

    /**
     * 获取当前的票据期限截止日期
     * @param $company_id
     * @return bool|int
     */
    public function getCompanyBillDeadline($company_id)
    {
        if (isset(self::$deadline[$company_id])) {
            return self::$deadline[$company_id];
        }
        /**
         * @var $company_bill CompanyBill
         */
        $company_bill = Repositories::getRepository('CompanyBill');
        $bill = $company_bill->getCompanyBillByCompanyId($company_id);
        if (($service = CompanyService::getCompanyService($company_id)) === false) {
            return false;
        }

        $end_time = $bill->getEndTime() ? : ($service->getStartTime() - 86400);
        if ($bill->getEndTime() < time()) {
            if (($term = self::getBillTermByCompanyId($company_id)) === false) {
                return false;
            }
            $bill_end_time = \Wdxr\Models\Services\Company::BillEndTime(
                $term->getTerm(),
                $term->getType(),
                $end_time
            );
            //服务期限的截止日期可能不是23:59:59
            $service_end_time = Time::getEndTime($service->getEndTime());
            if ($bill_end_time > $service_end_time) {
                $bill_end_time  = $service->getEndTime();
            }
            $bill->setEndTime($bill_end_time);
            if ($bill->save() === false) {
                return false;
            }
            return self::$deadline[$company_id] = $this->getCompanyBillDeadline($company_id);
        }
        return self::$deadline[$company_id] = $bill->getEndTime();
    }

    /**
     * 获取企业上一期的票据截止时间
     * @param $company_id
     * @param $deadline
     * @return false|int
     */
    public function getPreviousDeadline($company_id, $deadline)
    {
        if (($term = self::getBillTermByCompanyId($company_id)) === false) {
            return false;
        }
        switch ($term->type) {
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
        $previous_time = strtotime("-{$term->term} $data", $deadline);

        $service = CompanyService::getCompanyService($company_id);
        if ($service->getStartTime() > $previous_time) {
            return $service->getStartTime();
        }

        return Time::getEndTime($previous_time);
    }

}