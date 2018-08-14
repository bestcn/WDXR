<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 17:01
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Finances as EntityAdmin;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
class Finance
{

    /**
     * @param $id
     * @return EntityAdmin
     */
    static public function getFinanceById($id)
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
    public function getFinanceBybyid($id)
    {
        return EntityAdmin::query()
            ->where("makecoll = '{$id}'")
            //->orderBy('money asc')
            ->columns(['money', 'remark','time'])
            ->execute();
    }
    /**
     * @param $company_id
     * @param $start_time
     * @param $end_time
     * @return \Wdxr\Models\Entities\Finances[]
     */
    static public function getFinanceByCompanyId($company_id,$start_time,$end_time)
    {
        return EntityAdmin::find(["company_id = :company_id: and time between :start_time: and :end_time:",
            'bind' => ['company_id' => $company_id, 'start_time' => $start_time, 'end_time' => $end_time]]);
//        return EntityAdmin::query()
//            ->where("company_id = '{$company_id}'")
//            ->betweenWhere('time',$start_time,$end_time)
//            ->execute();
    }

    public function getFinanceByCompanyName($company_name)
    {
       return EntityAdmin::sum(
            array(
                "column"     => "money",
                "conditions" => "company_id = '{$company_name}'"
            )
        );
    }

    public function getFinanceByMakecoll($id)
    {
        return EntityAdmin::sum(
            array(
                "column"     => "money",
                "conditions" => "byid = '{$id}'"
            )
        );
    }

    static public function getFinanceCount($id)
    {
        return EntityAdmin::count(['byid = ?0', 'bind' => [$id]]);
    }

    public function getLast()
    {
        return EntityAdmin::query()
            ->orderBy('id asc')
            ->execute();
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

    //获取昨天的数据
    public function getYesterdayList()
    {
        $start_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d",strtotime("-1 day"))));
        $end_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d",time())));

        return EntityAdmin::query()
            ->betweenWhere('time', $start_time, $end_time)
            ->groupBy("byid")
            ->columns(['company_id','makecoll','sum(money) as finance_money','time','bank_name','byid'])
            ->execute();
    }

    public function find($start_time,$end_time)
    {
        return EntityAdmin::query()->where("time between $start_time and $end_time")
            ->orderBy('id asc')
            ->execute();
    }

    /**
     * 财务记录是否未生成
     * @param $id
     * @return bool
     */
    static public function checkOnly($id)
    {
        $start_time = date('Y-m-d H:i:s', strtotime(date('Y-m-d', time())));
        $end_time = date('Y-m-d H:i:s', strtotime(date('Y-m-d', time())) + 86400);
        return EntityAdmin::count(['byid = ?0 and time between ?1 and ?2', 'bind' => [$id, $start_time, $end_time]]) == 0;
    }

    public static function addNew($data)
    {
        $finance = new EntityAdmin();
        $finance->setMakecoll($data["makecoll"]);
        $finance->setCompanyId($data["company_id"]);
        $finance->setMoney($data["money"]);
        $finance->setRemark($data["remark"]);
        $finance->setBankName($data['bank_name']);
        $finance->setStartTime($data['start_time']);
        $finance->setDayCount($data['day_count']);
        $finance->setPhone($data['phone']);
        $finance->setByid($data['byid'] ?: '');
        $finance->setAccountId($data['account_id']);
        $finance->setEndTime($data['end_time']);
        $finance->setStatus($data['status']);
        $finance->setInfo($data['info']);
        $finance->setName($data['name']);

        if (!$finance->save()) {
            throw new InvalidRepositoryException($finance->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $Finance = Finance::getFinanceById($id);
        $Finance->setMakecoll($data["makecoll"]);
        $Finance->setCompanyId($data["company_id"]);
        $Finance->setMoney($data["money"]);
        $Finance->setRemark($data["remark"]);

        if (!$Finance->save()) {
            throw new InvalidRepositoryException($Finance->getMessages()[0]);
        }

        return true;
    }

    static public function deleteFinance($id)
    {
        $Finance = Finance::getFinanceById($id);
        if (!$Finance) {
            throw new InvalidRepositoryException("报表没有找到");
        }

        if (!$Finance->delete()) {
            throw new InvalidRepositoryException("报表删除失败");
        }

        return true;
    }

}