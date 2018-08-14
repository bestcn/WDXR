<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/20
 * Time: 10:31
 */
namespace Wdxr\Models\Services;

use Wdxr\Models\Repositories\Repositories;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class Salesman extends Services
{


    static public function getBranchListPagintor($parameters, $numberPage)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->from('Wdxr\Models\Entities\Salesmans')
            ->orderBy('id');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

}
