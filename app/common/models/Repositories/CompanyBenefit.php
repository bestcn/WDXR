<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Levels as EntityLevels;
use Wdxr\Models\Entities\CompanyInfo;
use Wdxr\Models\Entities\CompanyBenefit as EntityCompanyBenefit;
use Wdxr\Models\Entities\CompanyService as EntityCompanyService;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class CompanyBenefit extends Repositories
{

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;

    static private $_instance = null;

    static public function getCompanyBenefit($benefit_id)
    {
        if(is_null(self::$_instance)) {
            self::$_instance = EntityCompanyBenefit::findFirst(["conditions" =>  "id = :id:", 'bind' => ['id' => $benefit_id]]);
            if(self::$_instance === false) {
                throw new InvalidRepositoryException("获取公司收益记录失败");
            }
        }
        return self::$_instance;
    }


    static public function addCompanyBenefit($data)
    {
        $benefit = new EntityCompanyBenefit();
        $benefit->setCompanyId($data['company_id']);
        $benefit->setAmount($data['amount']);
        $benefit->setStatus(self::STATUS_ENABLE);
        if(!$benefit->save()) {
            throw new InvalidRepositoryException($benefit->getMessages()[0]);
        }
        return true;
    }

    /**
     * 作废一条收益记录
     * @param $benefit_id
     * @return bool
     * @throws InvalidRepositoryException
     */
    static public function setBenefitDisable($benefit_id)
    {
        $benefit = self::getCompanyBenefit($benefit_id);
        $benefit->setStatus(self::STATUS_DISABLE);

        if(!$benefit->save()) {
            throw new InvalidRepositoryException("公司收益记录作废失败");
        }
        return true;
    }

    static public function getCompanyBenefits($company_id)
    {
        $service = CompanyService::getCompanyService($company_id);
        return EntityCompanyBenefit::sum(['conditions' => 'company_id = :company_id: and createAt > :start: and createAt < :end:', 'bind' => ['company_id' => $company_id, 'start' => $service->getStartTime(), 'end' => $service->getEndTime()], 'columns' => 'amount']);
    }

}