<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 17:01
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Manages as EntityAdmin;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
class Manage
{

    /**
     * @param $id
     * @return EntityAdmin
     */
    static public function getManageById($id)
    {
        /**
         * @var $admin EntityAdmin
         */
        $Manage = EntityAdmin::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $Manage;
    }

    /*
    * 根据企业ID查询报表信息
    */
    public function getManageBybyid($id)
    {
        return EntityAdmin::query()
            ->where("makecoll = '{$id}'")
            ->columns(['money', 'remark','time'])
            ->execute();
    }

    /**
     * @param $company_id
     * @param $start_time
     * @param $end_time
     * @return \Wdxr\Models\Entities\Manages
     */
    static public function getManageByCompanyId($company_id,$start_time,$end_time)
    {
//        return EntityAdmin::query()
//            ->where("company_id = '{$company_id}'")
//            ->betweenWhere('time',$start_time,$end_time)
//            ->execute();
        return EntityAdmin::find(["company_id = :company_id: and time between :start_time: and :end_time:",
            'bind' => ['company_id' => $company_id, 'start_time' => $start_time, 'end_time' => $end_time]]);
    }

    public function getManageByCompanyName($company_name)
    {
        return EntityAdmin::sum(
            array(
                "column"     => "money",
                "conditions" => "company_id = '{$company_name}'"
            )
        );
    }
    public function getManageByMakecoll($id)
    {
        return EntityAdmin::sum(
            array(
                "column"     => "money",
                "conditions" => "byid = '{$id}'"
            )
        );
    }

    public function getLast()
    {
        return EntityAdmin::query()
            ->orderBy('id asc')
            ->execute();
    }

    static public function getManageCount($id)
    {
        return EntityAdmin::count(['byid = ?0', 'bind' => [$id]]);
    }
    /**
     * 按照导出报表格式从数据库取相关数据
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public function getExportList()
    {
        return EntityAdmin::query()
            ->orderBy('id asc')
            ->columns(['company_id','makecoll','bank_name', 'money','phone','start_time','day_count', 'remark','status','info'])
            ->execute();
    }

    //获取昨天的数据
    public function getYesterdayList()
    {
        $start_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d",strtotime("-1 day"))));
        $end_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d",time())));
        return EntityAdmin::query()
            ->betweenWhere('time', $start_time, $end_time)
            ->groupBy("byid")
            ->columns(['company_id','sum(money) as manage_money','byid'])
            ->execute();
    }

    /**
     * 按照导出报表格式，根据具体的的时间从数据库取相关数据
     * @param $start_time
     * @param $end_time
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public function getExportBetweenList($start_time,$end_time,$account = 0)
    {
        if($account){
            return EntityAdmin::query()
                ->where("account_id = $account")
                ->betweenWhere('time', $start_time, $end_time)
                ->orderBy('start_time asc')
                ->columns(['company_id','makecoll','bank_name', 'money','phone','start_time','end_time','day_count', 'remark','status','info'])
                ->execute();
        }else{
            return EntityAdmin::query()
                ->betweenWhere('time', $start_time, $end_time)
                ->orderBy('start_time asc')
                ->columns(['company_id','makecoll','bank_name', 'money','phone','start_time','end_time','day_count', 'remark','status','info'])
                ->execute();
        }
    }

    public function find($start_time,$end_time)
    {
        return EntityAdmin::query()->where("time between $start_time and $end_time")
            ->orderBy('id asc')
            ->execute();
    }

    static public function checkOnly($id)
    {
        $start_time = date('Y-m-d H:i:s', strtotime(date('Y-m-d', time())));
        $end_time = date('Y-m-d H:i:s', strtotime(date('Y-m-d', time())) + 86400);
        return EntityAdmin::count(['byid = ?0 and time between ?1 and ?2', 'bind' => [$id, $start_time, $end_time]]) == 0;
    }

    static public function addNew($data)
    {
        $Manage = new EntityAdmin();
        $Manage->setMakecoll($data["makecoll"]);
        $Manage->setCompanyId($data["company_id"]);
        $Manage->setMoney($data["money"]);
        $Manage->setRemark($data["remark"]);
        $Manage->setBankName($data['bank_name']);
        $Manage->setStartTime($data['start_time']);
        $Manage->setDayCount($data['day_count']);
        $Manage->setPhone($data['phone']);
        $Manage->setByid($data['byid']);
        $Manage->setAccountId($data['account_id']);
        $Manage->setEndTime($data['end_time']);
        $Manage->setStatus($data['status']);
        if (!$Manage->save()) {
            throw new InvalidRepositoryException($Manage->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $Manage = Manage::getManageById($id);
        $Manage->setMakecoll($data["makecoll"]);
        $Manage->setCompanyId($data["company_id"]);
        $Manage->setMoney($data["money"]);
        $Manage->setRemark($data["remark"]);

        if (!$Manage->save()) {
            throw new InvalidRepositoryException($Manage->getMessages()[0]);
        }

        return true;
    }

    static public function deleteManage($id)
    {
        $Manage = Manage::getManageById($id);
        if (!$Manage) {
            throw new InvalidRepositoryException("报表没有找到");
        }

        if (!$Manage->delete()) {
            throw new InvalidRepositoryException("报表删除失败");
        }

        return true;
    }

}