<?php
namespace Wdxr\Models\Services;

use Phalcon\Exception;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Wdxr\Models\Entities\Admins;
use Wdxr\Models\Entities\Positions;
use Wdxr\Models\Entities\RolePosition;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Models\Repositories\Position as PositionRepositories;

class Position extends Services
{

    /**
     * 添加新职位
     * @param $data
     * @return bool
     * @throws InvalidServiceException
     */
    public function newPosition($data)
    {
        try {
            $this->db->begin();
            $id = \Wdxr\Models\Repositories\Position::addNew($data);
            foreach ($data['role'] as $role)
            {
                $role_position = new RolePosition();
                $role_position->setPositionId($id);
                $role_position->setRoleName($role);
                if(!$role_position->save()) {
                    throw new InvalidServiceException("角色设置失败");
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $exception) {
            $this->db->rollback();
            throw new InvalidServiceException($exception->getMessage());
        }
    }

    /**
     * 修改职位数据
     * @param Positions $positions
     * @param $data
     * @return bool
     * @throws InvalidServiceException
     */
    public function editPosition(Positions $positions, $data)
    {
        try {
            $this->db->begin();
            if(!$positions->save()) {
                throw new InvalidServiceException('职位保存失败');
            }
            $role_positions = RolePosition::find(['conditions' => 'position_id = :position_id:', 'bind' => ['position_id' => $positions->getId()]]);
            foreach ($role_positions as $role_position) {
                $role_position->delete();
            }
            foreach ($data['role'] as $role)
            {
                $role_position = new RolePosition();
                $role_position->setPositionId($positions->getId());
                $role_position->setRoleName($role);
                if(!$role_position->save()) {
                    throw new InvalidServiceException("角色修改失败");
                }
            }
            $this->db->commit();
            return true;
        } catch (Exception $exception) {
            $this->db->rollback();
            throw new InvalidServiceException($exception->getMessage());
        }
    }

    /**
     * 获取职位列表
     * @param $parameters
     * @param $numberPage
     * @return PaginatorQueryBuilder
     */
    static public function getPositionListPagintor($parameters, $numberPage)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->from(['positions'=>'Wdxr\Models\Entities\Positions'])
            ->leftJoin('Wdxr\Models\Entities\Departments','positions.department_id = department.id', 'department')
            ->columns(["positions.id","department.name as department_name","positions.name","positions.status","positions.description"])
            ->orderBy('positions.orderby');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

    /**
     * 删除职位
     * @param $position_id
     * @return bool
     * @throws InvalidServiceException
     */
    public function deletePosition($position_id)
    {
        try {
            $this->db->begin();
            $admin = Admins::findFirst(['conditions' => 'position_id = :position_id:', 'bind' => ['position_id' => $position_id]]);
            if($admin !== false) {
                throw new InvalidServiceException('该职位下还有用户，无法删除');
            }

            $role_positions = RolePosition::find(['conditions' => 'position_id = :position_id:', 'bind' => ['position_id' => $position_id]]);
            foreach ($role_positions as $role_position) {
                $role_position->delete();
            }

            $positions = Positions::find(['conditions' => 'id = :id:', 'bind' => ['id' => $position_id]]);
            foreach ($positions as $position) {
                $position->delete();
            }

            $this->db->commit();
            return true;
        } catch (Exception $exception) {
            $this->db->rollback();
            throw new InvalidServiceException($exception->getMessage());
        }
    }

    /**
     * 在所有角色中标识该职位当前使用的角色列表
     * @param $position_id
     * @return array
     */
    public function getPositionActiveRoleName($position_id)
    {
        $active_roles = PositionRepositories::getPositionRoleName($position_id);
        $all_roles = \Phalcon\Di::getDefault()->get('acl')->getRoles();
        $roles = []; $i = 0;
        foreach ($all_roles as $role) {
            $roles[$i]['name'] = $role->getName();
            if(in_array($role->getName(), $active_roles)) {
                $roles[$i]['is_check'] = 1;
            } else {
                $roles[$i]['is_check'] = 0;
            }
            $i++;
        }
        return $roles;
    }

}