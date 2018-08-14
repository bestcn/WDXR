<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Auth\UserAuth;
use Wdxr\Models\Entities\CompanyBillInfo as EntityCompanyBillInfo;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Services\Services;

class CompanyBillInfo extends Repositories
{
    const TYPE_RENT = 1;//房租
    const TYPE_WATER = 2;//水费
    const TYPE_ELECTRICITY = 3;//电费
    const TYPE_PROPERTY = 4;//物业费

    static private $_instance = null;

    public function getTypeName($type)
    {
        switch ($type)
        {
            case self::TYPE_RENT:
                return "房租发票";
            case self::TYPE_WATER:
                return "水费发票";
            case self::TYPE_ELECTRICITY:
                return "电费发票";
            case self::TYPE_PROPERTY:
                return "物业发票";
            default:
                return "错误";
        }
    }

    public function getBillInfoByCompanyId($company_id)
    {
        $result = EntityCompanyBillInfo::find([
            'conditions' => 'company_id = :company_id:',
            'bind' => ['company_id' => $company_id]
        ]);
        if($result){
            return $result;
        }
        return false;
    }

    public function getLast()
    {
        return EntityCompanyBillInfo::query()
            ->orderBy('id DESC')
            ->execute();
    }

    public function getOkBillInfo($company_id)
    {
        return EntityCompanyBillInfo::query()
            ->where("company_id = $company_id")
            ->execute();
    }

    public function getBillInfoByBillId($bill_id)
    {
        return EntityCompanyBillInfo::find([
            'conditions' => 'bill_id = :bill_id:',
            'bind' => ['bill_id' => $bill_id]
        ]);
    }

}