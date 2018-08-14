<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Achievement as EntityAchievement;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class Achievement extends Repositories
{

    const STATUS_DISABLE = 0;
    const STATUS_ENABLE = 1;

    /**
     * @param $id
     * @return EntityAchievement
     */
    static public function getAchievementById($id)
    {
        /**
         * @var $admin EntityAchievement
         */
        $Achievement = EntityAchievement::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $Achievement;
    }


    public function getAchievementByBranchId($id)
    {
        $Achievement = EntityAchievement::find(['conditions' => 'branch_id = :branch_id:', 'bind' => ['branch_id' => $id]]);
        return $Achievement;
    }

    /**
     * @param $branch_id
     * @return EntityAchievement
     */
    static public function getMoneyByBranchId($branch_id)
    {
        /**
         * @var $admin EntityAchievement
         */
        return EntityAchievement::findFirst(array(
            "columns"     => "sum(money) as amount",
            "conditions" => "status = 0 and branch_id = $branch_id",
        ));

    }

    /**
     * @param $admin_id
     * @return EntityAchievement
     */
    static public function getMoneyByAdminId($admin_id)
    {
        /**
         * @var $admin EntityAchievement
         */
        return EntityAchievement::findFirst(array(
            "columns"     => "sum(money) as amount",
            "conditions" => "status = 0 and admin_id = $admin_id",
        ));

    }

    static public function findFirstByName($name)
    {
        $Achievement = EntityAchievement::findFirst(['conditions' => 'admin_name = :name:', 'bind' => ['name' => $name]]);
        return $Achievement;
    }

    public function getLast()
    {
        return EntityAchievement::query()
            ->orderBy('id DESC')
            ->execute();
    }

    public function addNew($data)
    {
        $Achievement = new EntityAchievement();
        $Achievement->setAdminName($data["admin_name"]);
        $Achievement->setTime($data["time"]);
        $Achievement->setContractNum($data["contract_num"]);
        $Achievement->setBranchId($data["branch_id"]);
        $Achievement->setMoney($data["money"]);
        $Achievement->setCompanyName($data["company_name"]);
        $Achievement->setRecommender($data["recommender"]);
        $Achievement->setAdministrator($data["administrator"]);
        $Achievement->setCommission($data["commission"]);
        $Achievement->setAdminId($data["admin_id"]);
        if (!$Achievement->save()) {
            throw new InvalidRepositoryException($Achievement->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $Achievement = Achievement::getAchievementById($id);
        $Achievement->setAdminName($data["admin_name"]);
        $Achievement->setTime($data["time"]);
        $Achievement->setContractNum($data["contract_num"]);
        $Achievement->setBranchId($data["branch_id"]);
        $Achievement->setMoney($data["money"]);
        $Achievement->setCompanyName($data["company_name"]);
        $Achievement->setRecommender($data["recommender"]);
        $Achievement->setAdministrator($data["administrator"]);
        $Achievement->setCommission($data["commission"]);
        if (!$Achievement->save()) {
            throw new InvalidRepositoryException($Achievement->getMessages()[0]);
        }

        return true;
    }

    static public function deleteAchievement($id)
    {
        $Achievement = Achievement::getAchievementById($id);
        if (!$Achievement) {
            throw new InvalidRepositoryException("信息没有找到");
        }

        if (!$Achievement->delete()) {
            throw new InvalidRepositoryException("信息删除失败");
        }

        return true;
    }

    public function getExportBetweenList($start_time,$end_time,$branch_id = null)
    {
        return EntityAchievement::query()
            ->where("$branch_id")
            ->betweenWhere('time', $start_time, $end_time)
            ->orderBy('id asc')
            ->columns(['id','admin_name', 'company_name', 'recommender', 'administrator', 'contract_num','money','commission','time'])
            ->execute();
    }

    public function getExportBetweenListBybranch($start_time,$end_time,$branch_id)
    {
        return EntityAchievement::query()
            ->where("branch_id = $branch_id")
            ->betweenWhere('time', $start_time, $end_time)
            ->orderBy('id asc')
            ->columns(['id','admin_name', 'company_name', 'recommender', 'administrator', 'contract_num','money','commission'])
            ->execute();
    }

    public function getExportList($branch_id = null)
    {
        return EntityAchievement::query()
            ->where("$branch_id")
            ->orderBy('id asc')
            ->columns(['id','admin_name', 'company_name', 'recommender', 'administrator', 'contract_num','money','commission'])
            ->execute();
    }

    public function getMoneyList($branch_id = null)
    {
        return EntityAchievement::query()
            ->where("branch_id = $branch_id")
            ->orderBy('id asc')
            ->columns(['money'])
            ->execute()
            ->toArray();
    }

    public function getExportListBybranch($branch_id)
    {
        return EntityAchievement::query()
            ->where("branch_id = $branch_id")
            ->orderBy('id asc')
            ->columns(['id','admin_name', 'company_name', 'recommender', 'administrator', 'contract_num','money','commission'])
            ->execute();
    }

    //获取分公司的总业绩
    static public function getBranchAmount($branch_id)
    {
        return EntityAchievement::sum(array(
            "column"     => "money",
            "conditions" => "branch_id = '{$branch_id}'"
        ));

    }

    //获取分公司的本月业绩
    static public function getBranchMonthAmount($branch_id)
    {
        $start_time=mktime(0,0,0,date('m'),1,date('Y'));
        $end_time=mktime(23,59,59,date('m'),date('t'),date('Y'));

        return EntityAchievement::sum(array(
            "column"     => "money",
            "conditions" => "branch_id = '{$branch_id}' and (time between $start_time and $end_time)"
        ));
    }

    //获取指定时间的业绩统计
    public function getAmount($start_time,$end_time)
    {
        return EntityAchievement::sum(array(
            "column"     => "money",
            "conditions" => "time between $start_time and $end_time"
        ));
    }

    /**
     * 客户数量统计
     * @param $start_time
     * @param $end_time
     * @return mixed
     */
    public function getCount($start_time, $end_time)
    {
        return EntityAchievement::count(array(
            "column"     => "money",
            "conditions" => "time between $start_time and $end_time"
        ));
    }

    public function getBranchAdminAchievement($branch_id)
    {
        return EntityAchievement::query()
            ->where("branch_id = $branch_id")
            ->groupBy("admin_name")
            ->columns(['admin_name','sum(money) as money',"sum(commission) as commission"])
            ->execute();
    }

    public function getBranchAdminAchievementMonth($branch_id)
    {
        $start_time=mktime(0,0,0,date('m'),1,date('Y'));
        $end_time=mktime(23,59,59,date('m'),date('t'),date('Y'));

        return EntityAchievement::query()
            ->where("branch_id = $branch_id")
            ->betweenWhere('time', $start_time, $end_time)
            ->groupBy("admin_name")
            ->columns(['admin_name','sum(money) as month_money',"sum(commission) as month_commission"])
            ->execute();
    }

    /**
     * 业务员总业绩
     * @param $admin_id
     * @return mixed
     */
    public function getAdminAmount($admin_id)
    {
        $sum = EntityAchievement::sum(array(
            "column"     => "money",
            "conditions" => "admin_id = ?0",
            'bind' => [$admin_id]
        )) ? : 0;

        return $sum;
    }

    public function stopAchievementByServiceId($service_id)
    {
        $achievements = EntityAchievement::find([
            'conditions' => 'service_id = ?0',
            'bind' => [$service_id]
        ]);

        return $achievements->update([
            'status' => self::STATUS_DISABLE
        ]);
    }
}