<?php
namespace Wdxr\Models\Services;

use Wdxr\Models\Repositories\CompanyService;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class CompanyBenefit extends Services
{

    static public function getCurrentCompanyBenefit($company_id)
    {
        if(($days = CompanyService::getServiceDays($company_id)) === false) {
            return 0;
//            throw new InvalidRepositoryException("该公司不在服务期内");
        }
        if(($company = \Wdxr\Models\Repositories\Company::getCompanyById($company_id)) === false) {
            return 0;
//            throw new InvalidRepositoryException("获取公司信息失败");
        }
        $company_payment = (new \Wdxr\Models\Repositories\CompanyPayment())->getRPaymentByCompanyId($company_id);
        if(($level = \Wdxr\Models\Repositories\Level::getLevelById($company_payment->getLevelId())) === false) {
            return 0;
//            throw new InvalidRepositoryException("错误的公司级别");
        }

        return $days * $level->getDayAmount();
    }

}