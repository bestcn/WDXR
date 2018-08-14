<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Commission as EntityAdmin;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class Commission
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
     * @param $branch_id,$amount
     * @return EntityAdmin
     */
    static public function getCommissionByAmount($branch_id,$amount)
    {
        /**
         * @var $admin EntityAdmin
         */
        $Commission = EntityAdmin::findFirst(['conditions' => 'branch_id = :branch_id: and amount <= :amount: ', 'bind' => ['branch_id' => $branch_id,'amount'=>$amount],'order'=>'amount desc']);
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
        $Commission->setType($data["type"]);
        $Commission->setTime(date('Y-m-d H:i:s',time()));
        $Commission->setAdminId($data["admin_id"]);
        $Commission->setBanchId($data["branch_id"]);
        if (!$Commission->save()) {
            throw new InvalidRepositoryException($Commission->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $Commission = Commission::getCommissionById($id);
        $Commission->setAmount($data["amount"]);
        $Commission->setRatio($data["ratio"]);
        $Commission->setType($data["type"]);
        if (!$Commission->save()) {
            throw new InvalidRepositoryException($Commission->getMessages()[0]);
        }

        return true;
    }

    public function selectAmout($branch_id,$amount,$ratio)
    {
        $amoutCommission  = EntityAdmin::findFirst(['conditions' => 'branch_id = :branch_id: and ( amount = :amount: or ratio = :ratio:)', 'bind' => ['branch_id' => $branch_id,'amount' => $amount,'ratio' => $ratio]]);
        if($amoutCommission !== false)
        {
            throw new InvalidRepositoryException('当前分公司已有相同设置，请重新设置!');
        }
        return true;
    }

    public function selectEditAmout($id,$branch_id,$amount,$ratio)
    {
        $amoutCommission  = EntityAdmin::findFirst(['conditions' => 'id <> :id: and branch_id = :branch_id: and ( amount = :amount: or ratio = :ratio:)', 'bind' => ['id'=>$id,'branch_id' => $branch_id,'amount' => $amount,'ratio' => $ratio]]);
        if($amoutCommission !== false)
        {
            throw new InvalidRepositoryException('当前分公司已有相同设置，请重新设置!');
        }
        return true;
    }

    static public function deleteCommission($id)
    {
        $Commission = Commission::getCommissionById($id);
        if (!$Commission) {
            throw new InvalidRepositoryException("未找到设置");
        }

        if (!$Commission->delete()) {
            throw new InvalidRepositoryException("设置删除失败");
        }

        return true;
    }

}