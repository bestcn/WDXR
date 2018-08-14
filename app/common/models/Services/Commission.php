<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/20
 * Time: 10:31
 */
namespace Wdxr\Models\Services;

use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Models\Repositories\Salesman;
use Wdxr\Auth\Auth;

class Commission extends Services
{


    static public function getCommissionListPagintor($branch_id,$parameters, $numberPage)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->andWhere('branch_id = '.$branch_id)
            ->from('Wdxr\Models\Entities\Commission')
            ->orderBy('id desc');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

}
