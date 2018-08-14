<?php
namespace Wdxr\Models\Repositories;

use Phalcon\Exception;
use Wdxr\Models\Entities\Companys;
use Wdxr\Models\Entities\UserAdmin as EntityUserAdmin;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class UserAdmin extends Repositories
{

    const TYPE_ADMIN = 1;
    const TYPE_USER = 2;

    /**
     * @param $user_id
     * @param $type
     * @return bool
     * @throws InvalidRepositoryException
     */
    static public function addUserAdmin($user_id, $type)
    {
        $user_admin = new EntityUserAdmin();
        $user_admin->setUserId($user_id);
        $user_admin->setType($type);

        if(!$user_admin->save()) {
            throw new InvalidRepositoryException("创建用户关系数据失败");
        }

        return true;
    }

    /**
     * @param $user_id
     * @param $type
     * @return int
     * @throws InvalidRepositoryException
     */
    static public function getDeviceId($user_id, $type)
    {
        $user = EntityUserAdmin::findFirst(["user_id = :user_id: and type = :type:", 'bind' => ['user_id' => $user_id, 'type' => $type]]);
        if($user === false) {
            throw new InvalidRepositoryException("获取用户唯一标志失败");
        }
        return $user->getId();
    }

    public function getDevice($user_id, $type)
    {
        $user = EntityUserAdmin::findFirst(["user_id = :user_id: and type = :type:", 'bind' => ['user_id' => $user_id, 'type' => $type]]);
        if($user === false) {
            throw new InvalidRepositoryException("获取用户唯一标志失败");
        }
        return $user;
    }

    static public function getUser($device_id)
    {
        $user = EntityUserAdmin::findFirst(["id = :id:", 'bind' => ['id' => $device_id]]);
        if($user === false) {
            throw new InvalidRepositoryException("获取用户唯一标志失败");
        }
        return $user;
    }

    public function getByDeviceId($id,$type = 1)
    {
        $user = EntityUserAdmin::findFirst(["id = :id:", 'bind' => ['id' => $id]]);
        if($user->getType() != $type || $user == false){
            return false;
        }
        return $user;
    }

    /**
     * 获取管理员ID
     * @param $device_id
     * @return bool|int
     */
    public static function getAdminId($device_id)
    {
        $user = self::getUser($device_id);
        if ($user->getType() == UserAdmin::TYPE_ADMIN) {
            return $user->getUserId();
        } elseif ($user->getType() == UserAdmin::TYPE_USER) {
            $user_id = $user->getUserId();
            $company = Companys::findFirst(['user_id = :user_id:', 'bind' => ['user_id' => $user_id]]);
            return $company->getAdminId();
        } else {
            return false;
        }
    }

    public static function getNameByDeviceId($device_id)
    {
        try {
            $user = self::getUser($device_id);
        } catch (Exception $exception) {
            return '';
        }
        if ($user->getType() == UserAdmin::TYPE_ADMIN) {
            /**
             * @var $admin Admin
             */
            $admin = Repositories::getRepository('Admin');
            $info = $admin->getAdminsById($user->getUserId());
            return $info === false ? false : $info->getName();
        } elseif ($user->getType() == UserAdmin::TYPE_USER) {
            $user_id = $user->getUserId();
            $company = Companys::findFirst(['user_id = :user_id:', 'bind' => ['user_id' => $user_id]]);
            if ($company === false) {
                return '无';
            }
            $company_info = (new CompanyInfo())->getCompanyInfo($company->getInfoId());
            return $company_info->getLegalName();
        } else {
            return '无';
        }
    }

}