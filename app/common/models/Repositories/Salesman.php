<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 17:01
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Salesmans as EntityAdmin;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
class Salesman
{

    public function getLast()
    {
        return EntityAdmin::query()
            ->orderBy('id DESC')
            ->execute();
    }

    public function addNew($data)
    {
        $salesman = new EntityAdmin();
        $salesman->setBranchId($data["branch_id"]);
        $salesman->setAdminId($data["admin_id"]);
        if (!$salesman->save()) {
            throw new InvalidRepositoryException($salesman->getMessages()[0]);
        }
        return true;
    }

    static public function delete($result)
    {
        $admin_id = $result['admin_id'];
        $branch_id = $result['branch_id'];
        EntityAdmin::find(['conditions' => 'branch_id = :branch_id: and admin_id = :admin_id:', 'bind' => ['branch_id' =>$branch_id ,'admin_id'=>$admin_id]])->delete();
        return true;
    }

    public function get_salesman($id)
    {
        return EntityAdmin::find(['conditions' => 'branch_id = :branch_id:', 'bind' => ['branch_id' => $id]]);
    }

    public function get_unsalesman($id)
    {
        return EntityAdmin::find(['conditions' => 'branch_id != :branch_id:', 'bind' => ['branch_id' => $id]]);
    }

    public function getSalesmanByAdminId($id)
    {
        return EntityAdmin::findFirst(['conditions' => 'admin_id = :admin_id:', 'bind' => ['admin_id' => $id]]);
    }

    public function getAdminBranchId($admin_id)
    {
        $sales_data = $this->getSalesmanByAdminId($admin_id);
        $branch_id = $sales_data === false ? 0 : $sales_data->getBranchId();

        return $branch_id;
    }

}
