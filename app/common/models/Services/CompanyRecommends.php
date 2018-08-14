<?php

namespace Wdxr\Models\Services;

use Lcobucci\JWT\JWT;
use Wdxr\Auth\UserAuth;
use Wdxr\Models\Entities\Companys as EntityCompany;
use Wdxr\Models\Entities\CompanyRecommend as EntityCompanyRecommend;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;

class CompanyRecommends extends Services
{

    /**
     * 获取企业的推荐人
     * @param $company_id
     * @param null $recommend_id
     * @param null $device_id
     * @return array|bool
     * @throws InvalidServiceException
     */
    static public function getRecommendId($company_id, $recommend_id, $device_id = null)
    {
        $device_id = is_null($device_id) ? JWT::getUid() : $device_id;
        $admin_id = UserAdmin::getAdminId($device_id);
        if(!$recommend_id) {
            return false;
        }
        $recommend = EntityCompany::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $recommend_id]]);
        if($recommend === false) {
            throw new InvalidServiceException("该推荐企业不存在");
        }
        if($recommend->getAdminId() != $admin_id) {
            throw new InvalidServiceException("该推荐企业不是当前客户经理的客户");
        }
        if($recommend->getId() == $company_id) {
            throw new InvalidServiceException("推荐企业不能是当前企业");
        }
        if($recommend->getStatus() != RepoCompany::STATUS_ENABLE) {
            throw new InvalidServiceException("推荐企业尚未通过审核或者已经被禁用");
        }

        return $recommend->getId();
    }

    static public function getCompanyRecommendsListPagintor($parameters, $numberPage)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->from('Wdxr\Models\Entities\CompanyRecommend')
            ->orderBy('id');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

}