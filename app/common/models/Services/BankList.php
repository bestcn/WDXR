<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/20
 * Time: 10:31
 */
namespace Wdxr\Models\Services;

use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class BankList extends Services
{


    public static function getBankListPagintor($parameters, $numberPage)
    {
        $conditions = '';
        $bind = [];
        if (!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->from('Wdxr\Models\Entities\BankList')
            ->orderBy('id');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 20,
            'page' => $numberPage
        ]);
    }

}
