<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 17:01
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Branchs as EntityAdmin;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
class Branch
{

    /**
     * @param $id
     * @return EntityAdmin
     */
    static public function getBranchById($id)
    {
        /**
         * @var $admin EntityAdmin
         */
        $branch = EntityAdmin::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $branch;
    }

    public function getBranchByAdminId($id)
    {
        $branch = EntityAdmin::findFirst(['conditions' => 'branch_admin_id = :branch_admin_id:', 'bind' => ['branch_admin_id' => $id]]);
        return $branch;
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
        $branch->setbranchname($data["branch_name"]);
        $branch->setbranchlevel($data["branch_level"]);
        $branch->setbrancharea($data["branch_area"]);
        $branch->setbranchadmin($data["branch_admin"]);
        $branch->setBranchAdminId($data["branch_admin_id"]);
        $branch->setbranchstatus($data["branch_status"]);
        $branch->setbranchphone($data["branch_phone"]);
        $branch->setbranchaccount($data["branch_account"]);
        $branch->setbranchbank($data["branch_bank"]);
        $branch->setprovinces($data["provinces"]);
        $branch->setcities($data["cities"]);
        $branch->setareas($data["areas"]);

        //不能有重复的地区
        $areas = EntityAdmin::find(['conditions' => 'areas = :areas:', 'bind' => ['areas' => $data["areas"]]]);
        if($areas->toArray()){
            throw new InvalidRepositoryException("该地区已有分公司!");
            return;
        }
        //不能有重复的地区
        if (!$branch->save()) {
            throw new InvalidRepositoryException($branch->getMessages()[0]);
        }
        return $branch->getId();
    }

    public function edit($id, $data)
    {
        $branch = Branch::getBranchById($id);
        $branch->setbranchname($data["branch_name"]);
        $branch->setbranchlevel($data["branch_level"]);
        $branch->setbrancharea($data["branch_area"]);
        $branch->setbranchadmin($data["branch_admin"]);
        $branch->setBranchAdminId($data["branch_admin_id"]);
        $branch->setbranchstatus($data["branch_status"]);
        $branch->setbranchphone($data["branch_phone"]);
        $branch->setbranchaccount($data["branch_account"]);
        $branch->setbranchbank($data["branch_bank"]);
        $branch->setprovinces($data["provinces"]);
        $branch->setcities($data["cities"]);
        $branch->setareas($data["areas"]);

        if (!$branch->save()) {
            throw new InvalidRepositoryException($branch->getMessages()[0]);
        }

        return true;
    }

    static public function deleteBranch($id)
    {
        $branch = Branch::getBranchById($id);

        if (!$branch) {
            throw new InvalidRepositoryException("分公司没有找到");
        }
        if (!$branch->delete()) {
            throw new InvalidRepositoryException("分公司删除失败");
        }

        return true;
    }

}