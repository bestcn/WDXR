<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 17:01
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Temp as EntityAdmin;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
class Temp
{

    /**
     * @param $id
     * @return EntityAdmin
     */
    static public function getTempByCompanyName($name)
    {
        /**
         * @var $admin EntityAdmin
         */
        $Temp = EntityAdmin::findFirst(['conditions' => 'company_name = :company_name:', 'bind' => ['company_name' => $name]]);
        return $Temp;
    }

    public function getYesterdayList()
    {
        return EntityAdmin::query()
            ->groupBy("company_name")
            ->columns(['company_name','sum(money) as bonus'])
            ->execute();
    }

    public function getLast()
    {
        return EntityAdmin::query()
            ->orderBy('id asc')
            ->execute();
    }


    public function addNew($data)
    {
        $Temp = new EntityAdmin();
        $Temp->setCompanyName($data["company_name"]);
        $Temp->setMoney($data["money"]);
        if (!$Temp->save()) {
            throw new InvalidRepositoryException($Temp->getMessages()[0]);
        }
        return true;
    }

    static public function delete()
    {
        $Temp = new Temp();
        $Temp_data = $Temp->getLast();
        if (!$Temp_data->delete()) {
            throw new InvalidRepositoryException("未找到设置");
        }
        return true;
    }

}