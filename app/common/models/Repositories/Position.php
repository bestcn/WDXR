<?php
namespace Wdxr\Models\Repositories;


use Wdxr\Models\Entities\Positions;
use Wdxr\Models\Entities\RolePosition;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class Position extends Repositories
{

    static public function addNew($data)
    {
        $position = new Positions();
        $position->setName($data['name']);
        $position->setStatus($data['status']);
        $position->setDescription($data['description']);
        $position->setOrderby($data['orderBy']);
        $position->setDepartmentId($data['department_id']);

        if (!$position->save()) {
            throw new InvalidRepositoryException($position->getMessages()[0]);
        }
        return $position->getId();
    }

    /**
     * @param $id
     * @return Positions
     */
    static public function getPositionById($id)
    {
        return Positions::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
    }

    /**
     * 获取职位的所有角色的名称
     * @param $id
     * @return array
     */
    static public function getPositionRoleName($id)
    {
        $role_names = [];
        $role_position =  RolePosition::find(['columns' => 'role_name', 'conditions' => 'position_id = :position_id:', 'bind' => ['position_id' => $id]])->toArray();
        array_walk($role_position, function($item) use (&$role_names)  {
            array_push($role_names, $item['role_name']);
        });
        return $role_names;
    }
}