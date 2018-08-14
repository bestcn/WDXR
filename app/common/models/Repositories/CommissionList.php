<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/6/16
 * Time: 9:23
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\CommissionList as EntityAdmin;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
class CommissionList
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

    public function getCommissionListByDeviceId($device_id)
    {
        $CommissionList = EntityAdmin::findFirst(['conditions' => 'device_id = :device_id:', 'bind' => ['device_id' => $device_id]]);
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
        $CommissionList->setName($data["name"]);
        $CommissionList->setRatio($data["ratio"]);
        $CommissionList->setType($data["type"]);
        $CommissionList->setTime(date('Y-m-d H:i:s',time()));
        $CommissionList->setDeviceId($data['device_id']);
        $CommissionList->setStatus($data['status']);
        $CommissionList->setBranchsId($data['branch_id']);
        if (!$CommissionList->save()) {
            throw new InvalidRepositoryException($CommissionList->getMessages()[0]);
        }
        return true;
    }



    public function edit($id, $data)
    {
        $CommissionList = CommissionList::getCommissionListById($id);
//        $CommissionList->setName($data["name"]);
        $CommissionList->setRatio($data["ratio"]);
        $CommissionList->setStatus($data["status"]);
//        $CommissionList->setType($data["type"]);
        if (!$CommissionList->save()) {
            throw new InvalidRepositoryException($CommissionList->getMessages()[0]);
        }

        return true;
    }

    static public function deleteCommissionList($id)
    {
        $CommissionList = CommissionList::getCommissionListById($id);
        if (!$CommissionList) {
            throw new InvalidRepositoryException("未找到信息");
        }

        if (!$CommissionList->delete()) {
            throw new InvalidRepositoryException("信息删除失败");
        }

        return true;
    }

    public function getRatio($payment_type,$level_money,$user_id,$user_type)
    {
        //合伙人奖金
        $Recommend_user = (new UserAdmin())->getDevice($user_id,$user_type);
        $comm_data = (new CommissionList())->getCommissionListByDeviceId($Recommend_user->getId());
        if($comm_data){
            if ($payment_type != CompanyPayment::TYPE_LOAN) {
                $bonus = $level_money * $comm_data->getRatio();
            } else {
                $bonus = $level_money * $comm_data->getRatio() / 2;
            }
        }else{
            if ($payment_type != CompanyPayment::TYPE_LOAN) {
                $bonus = $level_money * 0.05;
            } else {
                $bonus = $level_money * 0.05 / 2;
            }
        }
        return $bonus;
        //合伙人奖金
    }

}