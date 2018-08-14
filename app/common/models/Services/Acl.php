<?php
namespace Wdxr\Models\Services;

use Phalcon\Acl\Role;
use Phalcon\Exception;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Wdxr\Models\Entities\AccessList;
use Wdxr\Models\Entities\Resources;
use Wdxr\Models\Entities\ResourcesAccesses;
use Wdxr\Models\Entities\RolePosition;
use Wdxr\Models\Entities\Roles;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;

class Acl extends Services
{

    static public function getRoleListPagintor($parameters, $numberPage)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->from('Wdxr\Models\Entities\Roles')
            ->orderBy('name');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

    public function addRole($name, $description, $resources_access)
    {
        $result = \Phalcon\Di::getDefault()->get('acl')->addRole(new Role($name, $description));
        if($result !== true) {
            throw new InvalidServiceException('添加新角色失败');
        }
        array_walk($resources_access, function(&$item) use($name) {
            $item = explode('||', $item);
            if(is_array($item)) \Phalcon\Di::getDefault()->get('acl')->allow($name, ucfirst($item[0]), $item[1]);
        });
        return true;
    }

    public function editRole(Roles $role, $resources_access)
    {
        try {
            $this->db->begin();
            if(!$role->save()) {
                throw new InvalidServiceException('修改角色失败');
            }
            $name = $role->getName();
            $accesses = AccessList::findByRolesName($role->getName());
            foreach ($accesses as $access)
            {
                $access->delete();
            }
            array_walk($resources_access, function(&$item) use($name) {
                $item = explode('||', $item);
                if(is_array($item)) \Phalcon\Di::getDefault()->get('acl')->allow($name, ucfirst($item[0]), $item[1]);
            });

            $this->db->commit();
            return true;
        } catch (Exception $exception) {
            $this->db->rollback();
            throw new InvalidServiceException($exception->getMessage());
        }
    }

    /**
     * 删除权限操作
     * @param $resource
     * @param $access
     * @return bool
     * @throws InvalidServiceException
     */
    public function deleteAccess($resource, $access)
    {
        $resource_access = ResourcesAccesses::findFirst(['conditions' => 'resources_name = :resources_name: AND access_name = :access_name:', 'bind' => ['resources_name' => $resource, 'access_name' => $access]]);
        if($resource_access === false) {
            throw new InvalidServiceException('该权限操作不存在');
        }
        if($resource_access->delete()) {
            return true;
        }
        return false;
    }

    /**
     * 删除角色
     * @param $role_name
     * @return bool
     * @throws InvalidServiceException
     */
    public function deleteRole($role_name)
    {
        $role = Roles::findFirst(['conditions' => 'name = :name:', 'bind' => ['name' => $role_name]]);
        if($role === false) {
            throw new InvalidServiceException('该角色已经不存在');
        }

        if(RolePosition::findFirst(['conditions' => 'role_name = :role_name:', 'bind' => ['role_name' => $role_name]]) !== false) {
            throw new InvalidServiceException('还有职位使用该角色');
        }
        $access_list = AccessList::find(['conditions' => 'roles_name = :roles_name:', 'bind' => ['roles_name' => $role_name]]);
        foreach ($access_list as $access) {
            $access->delete();
        }

        return $role->delete();
    }

    /**
     * 删除权限资源
     * @param $resource
     * @return bool
     * @throws InvalidServiceException
     */
    public function deleteResource($resource)
    {
        $_resource = Resources::findFirst(['conditions' => 'name = :name:', 'bind' => ['name' => $resource]]);
        if($_resource === false) {
            throw new InvalidServiceException('该权限资源不存在');
        }
        $_resource_access = ResourcesAccesses::findFirst(['conditions' => 'resources_name = :resources_name:', 'bind' => ['resources_name' => $resource]]);
        if($_resource_access !== false) {
            throw new InvalidServiceException('该权限资源还包含权限操作');
        }

        if($_resource->delete()) {
            return true;
        }
        return false;
    }

}