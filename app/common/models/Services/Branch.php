<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/20
 * Time: 10:31
 */
namespace Wdxr\Models\Services;

use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Wdxr\Models\Entities\BranchsCommissionList;
use Wdxr\Models\Entities\BranchsLevels;
use Wdxr\Models\Repositories\Branch as RepBranch;
use Wdxr\Models\Repositories\Achievement;
use Wdxr\Models\Repositories\BranchsCommission;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;

class Branch extends Services
{


    static public function getBranchListPagintor($parameters, $numberPage)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->from(['branchs'=>'Wdxr\Models\Entities\Branchs'])
            ->leftJoin('Wdxr\Models\Entities\BranchsLevels','level.id = branchs.branch_level', 'level')
            ->columns(['branchs.id','branchs.branch_name','branchs.branch_area','branchs.branch_admin','level.level_name','branchs.branch_status'])
            ->orderBy('branchs.id asc');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }


    public function addBranchs($data)
    {
        $branch = new RepBranch();
        $data['branchs_id'] = $branch->addNew($data);
        $BranchsCommission =BranchsCommission::getCommissionByLevel($data['level']);
        if($BranchsCommission === false){
            throw new InvalidServiceException("当前等级暂无提成设置!");
        }
        $ratio_info = BranchsCommission::getRatio($data['level'],0);
        if($ratio_info === false){
            $data['ratio'] = BranchsCommission::DEFULT_RATIO;
        }else{
            $data['ratio'] = $ratio_info->getRatio();
        }
        $BranchsCommissionList = new \Wdxr\Models\Repositories\BranchsCommissionList();
        $BranchsCommissionList->addNew($data);

    }

}
