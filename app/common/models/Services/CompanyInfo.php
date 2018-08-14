<?php
namespace Wdxr\Models\Services;

use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Wdxr\Models\Repositories\CompanyInfo as RepoCompanyInfo;

class CompanyInfo extends Services
{

    static public function view($company_id)
    {
        return $builder = Services::getStaticModelsManager()->createBuilder()
            ->where("company_id = :company_id:", ['company_id' => $company_id])
            ->from('Wdxr\Models\Entities\CompanyInfo')
            ->orderBy('id')->getQuery()->execute();
    }

    static public function getCompanyInfoListPagintor($parameters, $numberPage)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->from('Wdxr\Models\Entities\CompanyInfo')
            ->orderBy('id');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

}
