<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 17:01
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Statistics as EntityAdmin;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
class Statistics
{

    /**
     * @param $id
     * @return EntityAdmin
     */
    static public function getStatisticsById($id)
    {
        /**
         * @var $Statistics EntityAdmin
         */
        $Statistics = EntityAdmin::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $Statistics;
    }

    public function getStatisticsByCompanyName($company_name)
    {
        return EntityAdmin::sum(
            array(
                "column"     => "bonus",
                "conditions" => "company_name = '{$company_name}'"
            )
        );
    }

    public function getStatisticsByBankCard($bankcard)
    {
        return EntityAdmin::sum(
            array(
                "column"     => "bonus",
                "conditions" => "bank_card = '{$bankcard}'"
            )
        );
    }

    public function getStatisticsByCompanyId($id)
    {
        return EntityAdmin::sum(
            array(
                "column"     => "bonus",
                "conditions" => "company_id = '{$id}'"
            )
        );
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
    public function getExportList($where = null)
    {
        return EntityAdmin::query()
            ->where("$where")
            ->orderBy('id asc')
            ->columns(['company_name', 'bank_name', 'bank_card', 'fee', 'recommends_fee','manages_fee','bonus'])
            ->execute();
    }

    /**
     * 按照导出报表格式，根据具体的的时间从数据库取相关数据
     * @param $start_time
     * @param $end_time
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public function getExportBetweenList($start_time,$end_time,$where = null)
    {
        return EntityAdmin::query()
            ->where("$where")
            ->betweenWhere('time', $start_time, $end_time)
            ->orderBy('id asc')
            ->columns(['company_name', 'bank_name', 'bank_card', 'fee', 'recommends_fee','manages_fee','bonus'])
            ->execute();
    }

    public function find($start_time,$end_time)
    {
        return EntityAdmin::query()->where("time between $start_time and $end_time")
            ->orderBy('id asc')
            ->execute();
    }

    public function checkOnly($start_time,$end_time,$id)
    {
        return EntityAdmin::query()->where("company_id = '{$id}'")->andWhere("time between $start_time and $end_time")
            ->orderBy('id asc')
            ->execute();

    }

    public function addNew($data)
    {
        $Statistics = new EntityAdmin();
        $Statistics->setCompanyName($data["company_id"]);
        $Statistics->setCompanyId($data['byid']);
        $Statistics->setBankName($data['bank_name']);
        $Statistics->setBankCard($data["makecoll"]);
        $Statistics->setFee($data["finance_money"]);
        $Statistics->setRecommendsFee($data["recommend_money"]?:0);
        $Statistics->setManagesFee($data["manage_money"]?:0);
        $Statistics->setBonus($data["bonus"]?:0);
        $Statistics->setTime($data["time"]);
        if (!$Statistics->save()) {
            throw new InvalidRepositoryException($Statistics->getMessages()[0]);
        }
        return true;
    }

    /*
    public function edit($id, $data)
    {
        $Statistics = Statistics::getStatisticsById($id);
        $Statistics->setCompanyName($data["company_name"]);
        $Statistics->setCompanyId($data["company_id"]);
        $Statistics->setBankName($data["bank_name"]);
        $Statistics->setBankCard($data["bank_card"]);
        $Statistics->setFee($data["fee"]);
        $Statistics->setRecommendsFee($data["recommends_fee"]);
        $Statistics->setManagesFee($data["manages_fee"]);
        $Statistics->setBonus($data["bonus"]);
        $Statistics->setTime($data["time"]);

        if (!$Statistics->save()) {
            throw new InvalidRepositoryException($Statistics->getMessages()[0]);
        }

        return true;
    }

    static public function deleteStatistics($id)
    {
        $Statistics = Statistics::getStatisticsById($id);
        if (!$Statistics) {
            throw new InvalidRepositoryException("报表没有找到");
        }

        if (!$Statistics->delete()) {
            throw new InvalidRepositoryException("报表删除失败");
        }

        return true;
    }
    */

}