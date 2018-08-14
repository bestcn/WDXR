<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\BranchsCommission as EntityAdmin;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class BranchsCommission
{
    //无设置默认提成
    const DEFULT_RATIO=0.05;

    /**
     * @param $id
     * @return EntityAdmin
     */
    static public function getCommissionById($id)
    {
        /**
         * @var $admin EntityAdmin
         */
        $Commission = EntityAdmin::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);

        return $Commission;
    }

    /**
     * @param $level
     * @return EntityAdmin
     */
    static public function getCommissionByLevel($level)
    {
        /**
         * @var $admin EntityAdmin
         */
        $Commission = EntityAdmin::find(['conditions' => 'level = :level:', 'bind' => ['level' => $level]]);

        return $Commission;
    }

    public function getLast()
    {
        return EntityAdmin::query()
            ->orderBy('id asc')
            ->execute();
    }


    public function addNew($data)
    {
        $Commission = new EntityAdmin();
        $Commission->setAmount($data["amount"]);
        $Commission->setRatio($data["ratio"]);
        $Commission->setTime(date('Y-m-d H:i:s',time()));
        $Commission->setLevel($data['level']);
        if (!$Commission->save()) {
            throw new InvalidRepositoryException($Commission->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $Commission = BranchsCommission::getCommissionById($id);
        $Commission->setAmount($data["amount"]);
        $Commission->setRatio($data["ratio"]);
        $Commission->setLevel($data["level"]);
        if (!$Commission->save()) {
            throw new InvalidRepositoryException($Commission->getMessages()[0]);
        }
        return true;
    }

    public function selectAmout($level,$amount)
    {
        $amoutCommission  = EntityAdmin::findFirst(['conditions' => 'level = :level: and  amount = :amount:', 'bind' => ['level' => $level,'amount' => $amount]]);
        if($amoutCommission !== false)
        {
            throw new InvalidRepositoryException('当前等级已有相同设置，请重新设置!');
        }
        return true;
    }

    public function selectEditAmout($id,$level,$amount)
    {
        $amoutCommission  = EntityAdmin::findFirst(['conditions' => 'id <> :id: and level = :level: and amount = :amount: ', 'bind' => ['id'=>$id,'level' => $level,'amount' => $amount]]);
        if($amoutCommission !== false)
        {
            throw new InvalidRepositoryException('当前等级已有相同设置，请重新设置!');
        }
        return true;
    }

    static public function getRatio($level,$amount)
    {
        $ratioCommission = EntityAdmin::findFirst(['conditions' => 'level = :level: and  amount <= :amount: ', 'bind' => ['level' => $level,'amount' => $amount],'order'=>'amount desc']);
        return $ratioCommission;
    }

    static public function deleteCommission($id)
    {
        $Commission = BranchsCommission::getCommissionById($id);
        if (!$Commission) {
            throw new InvalidRepositoryException("未找到设置");
        }

        if (!$Commission->delete()) {
            throw new InvalidRepositoryException("设置删除失败");
        }

        return true;
    }

}