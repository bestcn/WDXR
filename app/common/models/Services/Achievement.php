<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/7/05
 * Time: 10:31
 */
namespace Wdxr\Models\Services;

use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class Achievement extends Services
{


    static public function getAchievementListPagintor($parameters, $numberPage,$branch_id = null)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->andWhere("$branch_id")
            ->from('Wdxr\Models\Entities\Achievement')
            ->orderBy('id');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

    static public function getAchievementListPagintorOld($parameters, $numberPage)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->from('Wdxr\Models\Entities\Achievement')
            ->orderBy('id');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

}
