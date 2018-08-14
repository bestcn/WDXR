<?php
namespace Wdxr\Models\Services;

use Wdxr\Models\Repositories\Repositories;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class Province extends Services
{


    static public function getAdminListPagintor($parameters, $numberPage)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->from('Wdxr\Models\Entities\Provinces')
            ->orderBy('id');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

}