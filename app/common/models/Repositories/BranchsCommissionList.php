<?php

namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\BranchsCommissionList as EntityAdmin;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
class BranchsCommissionList
{

    /**
     * @param $id
     * @return EntityAdmin
     */
    static public function getCommissionListById($id)
    {
        /**
         * @var $admin EntityAdmin
         */
        $CommissionList = EntityAdmin::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $CommissionList;
    }

    /**
     * @param $branchs_id
     * @return EntityAdmin
     */
    static public function getCommissionListByBranchsId($branchs_id)
    {
        /**
         * @var $admin EntityAdmin
         */
        $CommissionList = EntityAdmin::findFirst(['conditions' => 'branchs_id = :branchs_id:', 'bind' => ['branchs_id' => $branchs_id]]);
        return $CommissionList;
    }


    public function getLast()
    {
        return EntityAdmin::query()
            ->orderBy('id asc')
            ->execute();
    }


    public function addNew($data)
    {
        $CommissionList = new EntityAdmin();
        $CommissionList->setName($data["branch_name"]);
        $CommissionList->setRatio($data["ratio"]);
        $CommissionList->setType($data["branch_status"]);
        $CommissionList->setTime(date('Y-m-d H:i:s',time()));
        $CommissionList->setBranchsId($data['branchs_id']);
        if (!$CommissionList->save()) {
            throw new InvalidRepositoryException($CommissionList->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $CommissionList = BranchsCommissionList::getCommissionListById($id);
//        $CommissionList->setName($data["name"]);
        $CommissionList->setRatio($data["ratio"]);
//        $CommissionList->setType($data["type"]);
        if (!$CommissionList->save()) {
            throw new InvalidRepositoryException($CommissionList->getMessages()[0]);
        }
        return true;
    }

    static public function deleteCommissionList($id)
    {
        $CommissionList = BranchsCommissionList::getCommissionListById($id);
        if (!$CommissionList) {
            throw new InvalidRepositoryException("未找到信息");
        }

        if (!$CommissionList->delete()) {
            throw new InvalidRepositoryException("信息删除失败");
        }

        return true;
    }


}