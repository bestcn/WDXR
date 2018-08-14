<?php
namespace Wdxr\Models\Services;

use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Wdxr\Models\Entities\Departments;
use Wdxr\Models\Entities\Positions;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;

class Department extends Services
{

    static public function getDepartmentListPagintor($parameters, $numberPage)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->from('Wdxr\Models\Entities\Departments')
            ->orderBy('orderby');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

    /**
     * 删除部门
     * @param $id
     * @return bool
     * @throws InvalidServiceException
     */
    public function deleteDepartment($id)
    {
        $department = Departments::findFirst($id);
        if(Positions::findFirstByDepartmentId($id)) {
            throw new InvalidServiceException('该部门还包含职位，无法删除');
        }
        return $department->delete();
    }
}