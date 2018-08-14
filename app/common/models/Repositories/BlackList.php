<?php
namespace Wdxr\Models\Repositories;

use Lcobucci\JWT\JWT;
use Wdxr\Models\Entities\Companys as EntityCompany;
use Wdxr\Models\Entities\BlackList as EntityRefund;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class BlackList
{
    static public function getRefundById($id)
    {
        $data = EntityRefund::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        if($data === false) {
            throw new InvalidRepositoryException("黑名单信息获取失败");
        }else{
            return $data;
        }
    }

    public function getLast()
    {
        return EntityCompany::query()
            ->orderBy('id DESC')
            ->execute();
    }

    public function getOkCompany()
    {
        return EntityCompany::find(['conditions' => 'status = :status: and auditing = :auditing:',
            'bind' => ['status' => RepoCompany::STATUS_ENABLE , 'auditing' => RepoCompany::AUDIT_OK ]
        ]);
    }

    public function addNew($data)
    {
        $company_data = Company::getCompanyById($data['company_id']);
        $company_data->setStatus(Company::STATUS_BLACK_LIST);
        if ($company_data->save() === false) {
            throw new InvalidRepositoryException('企业状态更新失败');
        }

        $refund = new EntityRefund();
        $refund->setInfo($data['info']);
        $refund->setAdminId(JWT::getUid());
        $refund->setCompanyId($data['company_id']);
        $refund->setCompanyName($data['company_name']);
        $refund->setType(RepoCompany::TYPE_COMPANY);
        if ($refund->save() === false) {
            throw new InvalidRepositoryException($refund->getMessages()[0]);
        }
        return true;
    }

    static public function delete($id)
    {
        $refund = BlackList::getRefundById($id);

        if (!$refund) {
            throw new InvalidRepositoryException("企业黑名单信息没有找到");
        }
        $company_data = RepoCompany::getCompanyById($refund->getCompanyId());
        $company_data->setStatus(RepoCompany::STATUS_ENABLE);
        if(!$company_data->save() || !$refund->delete()){
            throw new InvalidRepositoryException("黑名单企业恢复失败");
        }
        return true;

    }

}