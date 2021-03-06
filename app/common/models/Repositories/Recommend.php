<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 17:01
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Recommends as EntityAdmin;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
class Recommend
{

    /**
     * @param $id
     * @return EntityAdmin
     */
    static public function getRecommendById($id)
    {
        /**
         * @var $admin EntityAdmin
         */
        $Finance = EntityAdmin::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $Finance;
    }

    /*
    * 根据企业ID查询报表信息
    */
    public function getRecommendBybyid($id)
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
     * @return \Wdxr\Models\Entities\Recommends[]
     */
    static public function getRecommendByCompanyId($company_id,$start_time,$end_time)
    {
        return EntityAdmin::find(["company_id = :company_id: and time between :start_time: and :end_time:",
            'bind' => ['company_id' => $company_id, 'start_time' => $start_time, 'end_time' => $end_time]]);
//        return EntityAdmin::query()
//            ->where("company_id = '{$company_id}'")
//            ->betweenWhere('time',$start_time,$end_time)
//            ->execute();
    }

    public function getRecommendByCompanyName($company_name)
    {
        return EntityAdmin::sum(
            array(
                "column"     => "money",
                "conditions" => "company_id = '{$company_name}'"
            )
        );
    }
    public function getRecommendByMakecoll($id)
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

    static public function getRecommendCount($id)
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
            ->columns(['company_id','sum(money) as recommend_money','byid'])
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
        $Recommend = new EntityAdmin();
        $Recommend->setMakecoll($data["makecoll"]);
        $Recommend->setCompanyId($data["company_id"]);
        $Recommend->setMoney($data["money"]);
        $Recommend->setRemark($data["remark"]);
        $Recommend->setBankName($data['bank_name']);
        $Recommend->setStartTime($data['start_time']);
        $Recommend->setDayCount($data['day_count']);
        $Recommend->setPhone($data['phone']);
        $Recommend->setByid($data['byid']);
        $Recommend->setAccountId($data['account_id']);
        $Recommend->setEndTime($data['end_time']);
        $Recommend->setStatus($data['status']);
        if (!$Recommend->save()) {
            throw new InvalidRepositoryException($Recommend->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $Recommend = Recommend::getRecommendById($id);
        $Recommend->setMakecoll($data["makecoll"]);
        $Recommend->setCompanyId($data["company_id"]);
        $Recommend->setMoney($data["money"]);
        $Recommend->setRemark($data["remark"]);

        if (!$Recommend->save()) {
            throw new InvalidRepositoryException($Recommend->getMessages()[0]);
        }

        return true;
    }

    static public function deleteRecommend($id)
    {
        $Recommend = Recommend::getRecommendById($id);
        if (!$Recommend) {
            throw new InvalidRepositoryException("报表没有找到");
        }

        if (!$Recommend->delete()) {
            throw new InvalidRepositoryException("报表删除失败");
        }

        return true;
    }

}