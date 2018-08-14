<?php
namespace Wdxr\Models\Services;

use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class Companylogs extends Services
{

    static public function getCompanyPasswordLogs($user_id, $numberPage)
    {
        return new PaginatorQueryBuilder([
            'builder' => Services::getStaticModelsManager()->createBuilder()
                ->columns('createdAt, ipAddress, userAgent')
                ->where("usersId = :id:", ['id' => $user_id])
                ->from('Wdxr\Models\Entities\CompanyPasswordLogs')
                ->orderBy('createdAt desc'),
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

}