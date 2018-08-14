<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 17:01
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\BonusSystem as EntityAdmin;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
class BonusSystem
{

    const SHIYE = 1;
    const PUHUI = 2;

    /**
     * @param $id
     * @return EntityAdmin
     */
    static public function getBonusById($id)
    {
        /**
         * @var $admin EntityAdmin
         */
        $branch = EntityAdmin::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $branch;
    }

    static public function getBonusByType($recommend,$customer,$count)
    {
        $branch = EntityAdmin::findFirst(['conditions' => 'recommend = :recommend: and customer = :customer:', 'bind' => ['recommend' => $recommend , 'customer' => $customer]]);
        if($branch === false){
            return 0;
        }
        if($count < 13) {
            switch ($count) {
                case 1:
                    return $branch->getFirst();
                    break;
                case 2:
                    return $branch->getSecond();
                    break;
                case 3:
                    return $branch->getThird();
                    break;
                case 4:
                    return $branch->getFourth();
                    break;
                case 5:
                    return $branch->getFifth();
                    break;
                case 6:
                    return $branch->getSixth();
                    break;
                case 7:
                    return $branch->getSeventh();
                    break;
                case 8:
                    return $branch->getEighth();
                    break;
                case 9:
                    return $branch->getNinth();
                    break;
                case 10:
                    return $branch->getTenth();
                    break;
                case 11:
                    return $branch->getEleventh();
                    break;
                default :
                    return $branch->getTwelfth();
                    break;
            }
        }else if($count>=13 && $count < 131 && $recommend != self::PUHUI){
            return 1500;
        }else if($count>=131 && $recommend != self::PUHUI){
            return 3000;
        }else{
            return 0;
        }
    }


    public function getLast()
    {
        return EntityAdmin::query()
            ->orderBy('id DESC')
            ->execute();
    }

    public function addNew($data)
    {
        $branch = new EntityAdmin();
        $branch->setRecommend($data["recommend"]);
        $branch->setCustomer($data["customer"]);
        $branch->setFirst($data["first"]);
        $branch->setSecond($data["second"]);
        $branch->setThird($data["third"]);
        $branch->setFourth($data["fourth"]);
        $branch->setFifth($data["fifth"]);
        $branch->setSixth($data["sixth"]);
        $branch->setSeventh($data["seventh"]);
        $branch->setEighth($data["eighth"]);
        $branch->setNinth($data["ninth"]);
        $branch->setTenth($data["tenth"]);
        $branch->setEleventh($data['eleventh']);
        $branch->setTwelfth($data['twelfth']);
        if (!$branch->save()) {
            throw new InvalidRepositoryException($branch->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $branch = BonusSystem::getBonusById($id);
        $branch->setRecommend($data["recommend"]);
        $branch->setCustomer($data["customer"]);
        $branch->setFirst($data["first"]);
        $branch->setSecond($data["second"]);
        $branch->setThird($data["third"]);
        $branch->setFourth($data["fourth"]);
        $branch->setFifth($data["fifth"]);
        $branch->setSixth($data["sixth"]);
        $branch->setSeventh($data["seventh"]);
        $branch->setEighth($data["eighth"]);
        $branch->setNinth($data["ninth"]);
        $branch->setTenth($data["tenth"]);
        $branch->setEleventh($data['eleventh']);
        $branch->setTwelfth($data['twelfth']);

        if (!$branch->save()) {
            throw new InvalidRepositoryException($branch->getMessages()[0]);
        }

        return true;
    }

    static public function delete($id)
    {
        $branch = BonusSystem::getBonusById($id);
        if (!$branch) {
            throw new InvalidRepositoryException("信息没有找到");
        }
        if (!$branch->delete()) {
            throw new InvalidRepositoryException("信息删除失败");
        }

        return true;
    }

}