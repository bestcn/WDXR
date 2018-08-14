<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 17:01
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Levels as EntityLevels;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Repositories\Company as RepoCompany;

class Level extends Repositories
{
    const DEFAULT_NO = 1;
    const DEFAULT_YES = 1;

    /**
     * @param $id
     * @return EntityLevels
     */
    static public function getLevelById($id)
    {
        /**
         * @var $admin EntityLevels
         */
        $level = EntityLevels::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $level;
    }

    /**
     *
     * @return EntityLevels
     */
    static public function getLevelByDefault()
    {
        /**
         * @var $admin EntityLevels
         */
        $level = EntityLevels::findFirst(['conditions' => 'is_default = :is_default:', 'bind' => ['is_default' => self::DEFAULT_YES]]);
        return $level;
    }

    static public function getLevelByCompanyId($company_id)
    {
        $company_payment = CompanyPayment::getPaymentByCompanyId($company_id);
        if(($level = self::getLevelById($company_payment->getLevelId())) === false) {
            throw new InvalidRepositoryException('企业级别信息获取失败');
        }
        return $level;
    }

    public function getLevelByCompany($company_id)
    {
        $company_payment = CompanyPayment::getPaymentByCompanyId($company_id);
        if(($level = $this->getLevel($company_payment->getLevelId())) === false) {
            throw new InvalidRepositoryException('企业级别信息获取失败');
        }
        return $level;
    }

    public function getLevel($id)
    {
        $level = EntityLevels::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $level;
    }

    public function get_level()
    {
        $Level = new Level();
        $Level_array = array();
        foreach($Level->getLast()->toArray() as $k=>$v){
            $Level_array[$v['id']] = $v['level_name'];
        }
        return $Level_array;
    }


    public function getLast()
    {
        return EntityLevels::query()
            ->where('level_status = 1')
            ->orderBy('id DESC')
            ->execute();
    }

    public function addNew($data)
    {
        $level = new EntityLevels();
        $level->setLevelName($data["level_name"]);
        $level->setLevelMoney($data["level_money"]);
        $level->setLevelStatus($data["level_status"]);
        $level->setDayAmount($data["day_amount"]);
        $level->setIsDefault($data["is_default"]);
        if (!$level->save()) {
            throw new InvalidRepositoryException($level->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $level = Level::getLevelById($id);
        $level->setLevelName($data["level_name"]);
        $level->setLevelMoney($data["level_money"]);
        $level->setLevelStatus($data["level_status"]);
        $level->setDayAmount($data["day_amount"]);
        $level->setIsDefault($data["is_default"]);
        if (!$level->save()) {
            throw new InvalidRepositoryException($level->getMessages()[0]);
        }

        return true;
    }

    static public function deleteLevel($id)
    {
        $level = Level::getLevelById($id);
        if (!$level) {
            throw new InvalidRepositoryException("级别信息未找到");
        }

        if (!$level->delete()) {
            throw new InvalidRepositoryException("级别信息删除失败");
        }

        return true;
    }

    static public function getLevelAmount($id)
    {
        $level = Level::getLevelById($id);
        if($level == false) {
            throw new InvalidRepositoryException('企业级别获取失败');
        }
        return $level->getLevelMoney();
    }

    public function getLevelMoney($id)
    {
        $level = Level::getLevelById($id);
        if($level == false) {
            throw new InvalidRepositoryException('企业级别获取失败');
        }
        return $level->getLevelMoney();
    }

    static public function getLevelDayAmount($id)
    {
        $level = Level::getLevelById($id);
        if($level == false) {
            throw new InvalidRepositoryException('企业级别获取失败');
        }
        return $level->getDayAmount();
    }

    static public function getLevelName($id)
    {
        $level = Level::getLevelById($id);
        if($level == false) {
            throw new InvalidRepositoryException('企业级别获取失败');
        }
        return $level->getLevelName();
    }
}