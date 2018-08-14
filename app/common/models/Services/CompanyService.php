<?php
namespace Wdxr\Models\Services;

use Phalcon\Exception;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Models\Repositories\CompanyService as RepoCompanyService;
use Wdxr\Models\Repositories\CompanyPayment as RepoCompanyPayment;
use Wdxr\Models\Repositories\Repositories;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;

class CompanyService extends Services
{

    /**
     * 停止一个企业的订阅服务
     * @param $company_id
     * @return bool
     * @throws InvalidServiceException
     */
    public function stopCompanyService($company_id)
    {

        $company_service = RepoCompanyService::getCompanyService($company_id);
        if ($company_service === false) {
            throw new InvalidServiceException("获取企业服务信息失败");
        }

        //订阅服务状态
        $company_service->setServiceStatus(RepoCompanyService::SERVICE_REFUND);
        if ($company_service->save() === false) {
            throw new InvalidServiceException("修改企业服务状态失败");
        }

        //企业基本状态
        $company = RepoCompany::getCompanyById($company_id);
        $company->setStatus(RepoCompany::STATUS_DISABLE);
        if ($company->save() === false) {
            throw new InvalidServiceException("修改企业基本状态失败");
        }

        /**
         * 缴费信息
         * @var $company_payment \Wdxr\Models\Repositories\CompanyPayment
         */
        $company_payment = Repositories::getRepository('CompanyPayment');
        $payment = $company_payment->getServicePayment($company_service->getId());
        if ($payment === false) {
            throw new InvalidServiceException('获取企业缴费信息失败');
        }

        //退费记录，退费申请一定要业务员提出申请，审核人员进行操作
        RepoCompanyPayment::addPayment(
            $company_id,
            -$payment->getAmount(),
            $company->getDeviceId(),
            -1,
            RepoCompanyPayment::TYPE_REFUND,
            RepoCompanyPayment::STATUS_OK,
            $company_service->getId()
        );

        /**
         * 推荐关系
         * @var $company_recommend \Wdxr\Models\Repositories\CompanyRecommend
         */
        $company_recommend = Repositories::getRepository('CompanyRecommend');
        if (($result = $company_recommend->stopCompanyRecommend($company_id)) === false) {
            throw new InvalidServiceException("推荐关系修改失败");
        }
        if ($result) {
            /**
             * 业务员业绩状态
             * @var $achievement \Wdxr\Models\Repositories\Achievement
             */
            $achievement = Repositories::getRepository('Achievement');
            if (($result = $achievement->stopAchievementByServiceId($company_service->getId())) === false) {
                throw new InvalidServiceException("业务员业绩信息修改失败");
            }
        }
        if ($result) {
            /**
             * 合同作废
             * @var $contract \Wdxr\Models\Repositories\Contract
             */
            $contract = Repositories::getRepository('Contract');
            $company_contract = $contract->getServiceContract($company_service->getId());
            if ($company_contract) {
                $result = $company_contract->update([
                    'contract_status' => \Wdxr\Models\Repositories\Contract::STATUS_NOT
                ]);
                if ($result === false) {
                    throw new InvalidServiceException("合同信息修改失败");
                }
            }
        }

        return true;
    }

}