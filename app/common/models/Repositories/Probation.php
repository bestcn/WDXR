<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Probation as EntityAdmin;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class Probation
{

    /**
     * @param $id
     * @return EntityAdmin
     */
    static public function getProbationById($id)
    {
        /**
         * @var $admin EntityAdmin
         */
        $Probation = EntityAdmin::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);

        return $Probation;
    }

    /**
     * @param $id
     * @return EntityAdmin
     */
    static public function getProbationByBranchsId($branchs_id)
    {
        /**
         * @var $admin EntityAdmin
         */
        $Probation = EntityAdmin::findFirst(['conditions' => 'branchs_id = :branchs_id:', 'bind' => ['branchs_id' => $branchs_id]]);

        return $Probation;
    }

    public function getLast()
    {
        return EntityAdmin::query()
            ->orderBy('id asc')
            ->execute();
    }


    public function addNew($data)
    {
        $Probation = new EntityAdmin();
        $Probation->setRatio($data["ratio"]);
        $Probation->setTime(date('Y-m-d H:i:s',time()));
        $Probation->setDeviceId($data["device_id"]);
        $Probation->setBranchsId($data["branchs_id"]);
        if (!$Probation->save()) {
            throw new InvalidRepositoryException($Probation->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $Probation = Probation::getProbationById($id);
        $Probation->setRatio($data["ratio"]);
        if (!$Probation->save()) {
            throw new InvalidRepositoryException($Probation->getMessages()[0]);
        }

        return true;
    }

    static public function deleteProbation($id)
    {
        $Probation = Probation::getProbationById($id);
        if (!$Probation) {
            throw new InvalidRepositoryException("未找到设置");
        }
        if (!$Probation->delete()) {
            throw new InvalidRepositoryException("设置删除失败");
        }

        return true;
    }

}