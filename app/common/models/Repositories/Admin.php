<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Auth\Auth;
use Wdxr\Models\Entities\Admins as EntityAdmin;
use Wdxr\Models\Entities\PasswordChanges;
use Wdxr\Models\Entities\ResetPasswords;
use Wdxr\Models\Entities\SuccessLogins;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class Admin extends Repositories
{

    const ENABLE = 1;
    const DISABLE = 0;


    private static $_instance = null;

    /**
     * @param $id
     * @return EntityAdmin
     */
    static public function  getAdminById($id)
    {
        if(isset(self::$_instance[$id]) === false) {
            /**
             * @var $admin EntityAdmin
             */
            self::$_instance[$id] = EntityAdmin::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        }
        return self::$_instance[$id];
    }

    public function getAdminsById($id)
    {
        return EntityAdmin::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
    }

    public function getLast()
    {
        return EntityAdmin::query()
            ->orderBy('id DESC')
            ->execute();
    }

    //获取所有管理员的ID与名字
    public function getAll()
    {
        return EntityAdmin::query()//->where("position_id = 6")//待定
            ->columns(" id, name")->execute();
    }

    public function addNew($data)
    {
        $created_by = (new Auth())->getIdentity()['id'];
        $admin = new EntityAdmin();
        $admin->setName($data["name"]);
        $admin->setEmail($data["email"]);
        $admin->setPhone($data["phone"]);
        $admin->setPositionId($data["position_id"]);
        $admin->setStatus($data["status"]);
        $admin->setIsProbation($data["is_probation"]);
        $admin->setOnJob($data["on_job"]);
        $admin->setIsLock($data["is_lock"]);
        $admin->setCreatedBy($created_by);
        empty($data['entry_time']) || $admin->setEntryTime($data["entry_time"]);
        $admin->setCreatedAt(date('Y-m-d H:i:s', time()));
        $admin->setBranchId($data['branch_id']);
        if ($data["is_probation"] == 0) {
            $admin->setFormalTime(date('Y-m-d H:i:s', time()));
        }
        $password = $admin->getDI()->get('security')->hash($data["password"]);
        $admin->setPassword($password);

        if (!$admin->save()) {
            throw new InvalidRepositoryException($admin->getMessages()[0]);
        }
        $user_admin  = new \Wdxr\Models\Entities\UserAdmin();
        $user_admin->setType(UserAdmin::TYPE_ADMIN);
        $user_admin->setUserId($admin->getId());
        if (!$user_admin->save()) {
            throw new InvalidRepositoryException("管理员关系记录添加失败");
        }
        $Salesman = new Salesman();
        $Salesman_data['branch_id'] = $data['branch_id'];
        $Salesman_data['admin_id'] = $admin->getId();
        $Salesman->addNew($Salesman_data);
        //添加管理员的业绩提成比率数据
        $device_id = UserAdmin::getDeviceId($admin->getId(), UserAdmin::TYPE_ADMIN);
        $data['name'] = $admin->getName();
        if ($data["is_probation"] == 1) {
            $probation = Probation::getProbationByBranchsId($data['branch_id']);
            if ($probation === false) {
                throw new InvalidRepositoryException("查询不到当前分公司试用期设置");
            }
            $data['ratio'] = $probation->getRatio();
        } else {
            $Commission = Commission::getCommissionByAmount($data['branch_id'], 0);
            if ($Commission === false) {
                $data['ratio'] = Commission::DEFULT_RATIO;
            } else {
                $data['ratio']=$Commission->getRatio();
            }
        }
        $data['device_id'] = $device_id;
        $data['status'] = $data["is_probation"];
        $data['type'] = UserAdmin::TYPE_ADMIN;
        $data['admin_id'] = $admin->getId();
        (new CommissionList())->addNew($data);
        return $admin->getId();
    }

    public function edit($id, $data)
    {
        $admin = Admin::getAdminById($id);
        $admin->setName($data["name"]);
        $admin->setEmail($data["email"]);
        $admin->setPhone($data["phone"]);
        $admin->setPositionId($data["position_id"]);
        $admin->setStatus($data["status"]);
        $admin->setIsProbation($data["is_probation"]);
        $admin->setOnJob($data["on_job"]);
        $admin->setIsLock($data["is_lock"]);
        $admin->setEntryTime($data["entry_time"]);
        if (!$admin->save()) {
            throw new InvalidRepositoryException($admin->getMessages()[0]);
        }

        return true;
    }

    /**
     * 修改管理员密码
     * @param $id
     * @param $password
     * @return bool
     * @throws InvalidRepositoryException
     */
    public function changePassword($id, $password)
    {
        $admin = self::getAdminById($id);
        if (empty($admin)) {
            throw new InvalidRepositoryException('该管理员不存在');
        }
        $password = $admin->getDI()->get('security')->hash($password);
        $admin->setPassword($password);

        if (!$admin->save()) {
            throw new InvalidRepositoryException($admin->getMessages()[0]);
        }

        PasswordChange::setRecord($id);

        return true;
    }

    /**
     * 删除管理员
     * @param $id
     * @return bool
     * @throws InvalidRepositoryException
     */
    static public function deleteAdmin($id)
    {
        $admin = Admin::getAdminById($id);
        if (!$admin) {
            throw new InvalidRepositoryException("管理员没有找到");
        }
        if($admin->getPositionId() == 1) {
            throw new InvalidRepositoryException("超级管理员不可删除");
        }

        $success_logins = SuccessLogins::find(["usersId = :id:", "bind" => ['id' => $id]]);
        foreach ($success_logins as $success_login) {
            $success_login->delete();
        }

        $password_changes = PasswordChanges::find(['usersId = :id:', 'bind' => ['id' => $id]]);
        foreach ($password_changes as $password_change) {
            $password_change->delete();
        }

        $reset_passwords = ResetPasswords::find(['usersId = :id:', 'bind' => ['id' => $id]]);
        foreach ($reset_passwords as $reset_password) {
            $reset_password->delete();
        }

        if (!$admin->delete()) {
            $messages = $admin->getMessages();
            foreach ($messages as $message) {
                throw new InvalidRepositoryException($message->getMessage());
            }
            throw new InvalidRepositoryException("管理员删除失败");
        }

        return true;
    }

    /**
     * 获取管理员状态
     * @param $id
     * @return bool
     */
    static public function getAdminStatus($id)
    {
        $admin = Admin::getAdminById($id);
        if ($admin->getStatus() === 0) {
            return ('该用户尚未激活');
        }

        if ($admin->getIsLock() === 1) {
            return ('该用户已被锁定');
        }

        if ($admin->getOnJob() === 0) {
            return ('该用户已离职');
        }
        return true;
    }

    /**
     * 获取业务员列表
     * @return mixed
     */
    public function getAdminsCompanyList()
    {
        $list = $this->modelsManager->createBuilder()
            ->from(['user_admin' => 'Wdxr\Models\Entities\UserAdmin'])
            ->join('Wdxr\Models\Entities\Admins', "user_admin.type = 1 and user_admin.user_id = admin.id", 'admin')
            ->columns('admin.name, user_admin.id as device_id, admin.id as admin_id, admin.status')
            ->getQuery()
            ->execute();

        return $list;
    }


    public function getAdminName($admin_id)
    {
        $admin_name = EntityAdmin::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $admin_id],
            'columns' => 'name'
        ]);
        return $admin_name === false ? '无' : $admin_name->name;
    }

    public function getAdminList()
    {
        return EntityAdmin::find([
            'conditions' => 'status = :status: and on_job = :on_job:',
            'bind' => ['status' => self::ENABLE, 'on_job' => self::ENABLE],
        ]);
    }

}
