<?php
namespace Wdxr\Models\Services;

use Phalcon\Security\Random;
use Wdxr\Models\Entities\CompanyBillInfo as EntityCompanyBillInfo;
use Wdxr\Models\Entities\CompanyBill as EntityCompanyBill;
use Wdxr\Models\Exception\ModelException;
use Wdxr\Models\Repositories\CompanyBill as RepoCompanyBill;
use Wdxr\Models\Repositories\Company as RepoCompany;
use \Wdxr\Models\Repositories\CompanyVerify as RepoCompanyVerify;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

class CompanyBill extends Services
{

    static public function addCompanyBill($company_id, $type, $attachment_ids, $amount, $device_id)
    {
        $company = RepoCompany::getCompanyById($company_id);
        if($company->getAuditing() == RepoCompany::AUDIT_NOT) {
            throw new InvalidServiceException("该企业尚未提交申请，请在申请之后再补交票据");
        }
        if($company->getAdminId() != UserAdmin::getAdminId($device_id)) {
            throw new InvalidRepositoryException("该企业不是您的客户，不能使用当前账号操作该企业");
        }
       $service =  \Wdxr\Models\Repositories\CompanyService::getCompanyService($company_id);
        $bill_id = $service->getBillId();

        $info = new EntityCompanyBillInfo();
        $info->setId((new Random())->uuid());
        $info->setCompanyId($company_id);
        $info->setType($type);
        $info->setAmount($amount);
        $info->setBillId($bill_id);
        $info->setDeviceId($device_id);
        $info->setUserSubmit(false);
        switch ($type) {
            case RepoCompanyBill::TYPE_RENT:
                $info->setRent($attachment_ids[0]);
                $info->setRentReceipt($attachment_ids[1]);
                $info->setRentContract($attachment_ids[2]);
                break;
            case RepoCompanyBill::TYPE_ELECTRICITY:
                $info->setElectricity($attachment_ids);
                break;
            case RepoCompanyBill::TYPE_PROPERTY_FEE:
                $info->setPropertyFee($attachment_ids);
                break;
            case RepoCompanyBill::TYPE_WATER_FEE:
                $info->setWaterFee($attachment_ids);
                break;
            default:
                throw new InvalidServiceException("票据类型错误");
        }
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $info->setTransaction($transaction);
            if (!$info->save()) {
                throw new InvalidServiceException($info->getMessages()[0]);
            }
            RepoCompanyVerify::newVerify($company_id, $device_id, RepoCompanyVerify::TYPE_BILL, $info->getId());
            $transaction->commit();
        } catch (ModelException $e) {
            $transaction->rollback();
            throw $e;
        }

        return true;
    }



}