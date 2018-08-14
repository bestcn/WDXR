<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/6/16
 * Time: 9:29
 */
namespace Wdxr\Models\Services;

use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class CommissionList extends Services
{


    static public function getCommissionListPagintor($branch_id,$parameters, $numberPage)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->andWhere("branchs_id = ".$branch_id)
            ->from('Wdxr\Models\Entities\CommissionList')
            ->orderBy('id desc');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

}
