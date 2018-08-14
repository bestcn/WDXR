<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 17:01
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\BranchsLevels as EntityBranchsLevels;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class BranchsLevels
{

    /**
     * @param $id
     * @return EntityLevels
     */
    static public function getLevelById($id)
    {
        /**
         * @var $admin EntityLevels
         */
        $level = EntityBranchsLevels::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $level;
    }



    public function getLevel($id)
    {
        $level = EntityBranchsLevels::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $level;
    }

    public function get_level()
    {
        $Level = new BranchsLevels();
        $Level_array = array();
        foreach($Level->getLast()->toArray() as $k=>$v){
            $Level_array[$v['id']] = $v['level_name'];
        }
        return $Level_array;
    }


    public function getLast()
    {
        return EntityBranchsLevels::query()
            ->where('level_status = 1')
            ->orderBy('id DESC')
            ->execute();
    }

    public function addNew($data)
    {
        $level = new EntityBranchsLevels();
        $level->setLevelName($data["level_name"]);
        $level->setLevelStatus($data["level_status"]);
        if (!$level->save()) {
            throw new InvalidRepositoryException($level->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $level = BranchsLevels::getLevelById($id);
        $level->setLevelName($data["level_name"]);
        $level->setLevelStatus($data["level_status"]);
        if (!$level->save()) {
            throw new InvalidRepositoryException($level->getMessages()[0]);
        }

        return true;
    }

    static public function deleteLevel($id)
    {
        $level = BranchsLevels::getLevelById($id);
        if (!$level) {
            throw new InvalidRepositoryException("级别信息未找到");
        }

        if (!$level->delete()) {
            throw new InvalidRepositoryException("级别信息删除失败");
        }

        return true;
    }

    static public function getLevelName($id)
    {
        $level = BranchsLevels::getLevelById($id);
        if($level == false) {
            throw new InvalidRepositoryException('分公司级别获取失败');
        }
        return $level->getLevelName();
    }
}