<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Users as EntityUsers;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Random;
use Wdxr\Models\Repositories\UserAdmin as RepoUserAdmin;

class User extends Repositories
{

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;

    const PARTNER_IS = 1;
    const PARTNER_NO = 0;

    /**
     * @param $id
     * @return EntityUsers
     */
    static public function getUserById($id)
    {
        /**
         * @var $admin EntityUsers
         */
        $user = EntityUsers::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $user;
    }

    public function getById($id)
    {
        $user = EntityUsers::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $user;
    }

    static public function findFirstByName($name)
    {
        $user = EntityUsers::findFirst(['conditions' => 'name = :name:', 'bind' => ['name' => $name]]);
        return $user;
    }

    public function getLast()
    {
        return EntityUsers::query()
            ->orderBy('id DESC')
            ->execute();
    }

    public function getPartner()
    {
        return EntityUsers::query()
            ->where('is_partner = 1')
            ->orderBy('id DESC')
            ->execute();
    }

    public function addNew($data)
    {
        $user = new EntityUsers();
        $user->setName($data["name"]);
        $password = $user->getDI()->get('security')->hash($data["password"]);
        $user->setPassword($password);
        $user->setPhone($data["phone"]);
        $user->setEmail($data["email"]);
        $user->setLastLoginTime($data["last_login_time"]);
        $user->setLastLoginIp($data["last_login_ip"]);
        $user->setStatus($data["status"]);
        if (!$user->save()) {
            throw new InvalidRepositoryException($user->getMessages()[0]);
        }
        //返回刚添加信息的ID
        return $user->getWriteConnection()->lastInsertId($user->getSource());
    }

    /**
     * 添加默认账户
     * @param $company_id
     * @param int $type 缴费方式
     * @param int $level_id 企业级别
     * @return int
     * @throws InvalidRepositoryException
     */
    public static function addDefaultUser($company_id, $type, $level_id)
    {
        $user = self::getCompanyUser($company_id);
        if ($user !== false) {
            return $user;
        }
        $company_info = CompanyInfo::getByCompanyId($company_id);
        $is_partner = !($type == CompanyPayment::TYPE_LOAN);
        $number = self::generateUserNumber($level_id);
        $user = new EntityUsers();
        $user->setName($number);
        $password = $user->getDI()->get('security')->hash('000000');
        $user->setPassword($password);
        $user->setStatus(self::STATUS_ENABLE);
        $user->setPhone($company_info->getContactPhone());
        $user->setIsPartner($is_partner);
        $user->setNumber($number);
        if ($user->save() === false) {
            $message = isset($user->getMessages()[0]) ? $user->getMessages()[0] : '创建企业账号失败';
            throw new InvalidRepositoryException($message);
        }

        $company = Company::getCompanyById($company_id);
        $company->setUserId($user->getId());
        if ($company->save() === false) {
            throw new InvalidRepositoryException("保存企业信息失败");
        }

        RepoUserAdmin::addUserAdmin($user->getId(), RepoUserAdmin::TYPE_USER);
        return $user->getId();
    }

    public function edit($id, $data)
    {
        $user = User::getUserById($id);
        $user->setName($data["name"]);
        $user->setPassword($data["password"]);
        $user->setPhone($data["phone"]);
        $user->setEmail($data["email"]);
        $user->setLastLoginTime($data["last_login_time"]);
        $user->setLastLoginIp($data["last_login_ip"]);
        $user->setStatus($data["status"]);
        if (!$user->save()) {
            throw new InvalidRepositoryException($user->getMessages()[0]);
        }

        return true;
    }

    static public function deleteCompany($id)
    {
        $user = User::getUserById($id);
        if (!$user) {
            throw new InvalidRepositoryException("用户没有找到");
        }

        if (!$user->delete()) {
            throw new InvalidRepositoryException("用户删除失败");
        }

        return true;
    }


    /**
     * 修改用户密码
     * @param $id
     * @param $password
     * @return bool
     * @throws InvalidRepositoryException
     */
    public function changePassword($id, $password)
    {
        $user = self::getUserById($id);
        if(empty($user)) {
            throw new InvalidRepositoryException('该用户不存在');
        }
        $password = $user->getDI()->get('security')->hash($password);
        $user->setPassword($password);

        if (!$user->save()) {
            throw new InvalidRepositoryException($user->getMessages()[0]);
        }

        //生成修改密码日志
        CompanyPasswordLog::setRecord($id);

        return true;
    }

    /**
     * 判断用户状态，并获取用户信息
     * @param $id
     * @return bool
     * @throws InvalidRepositoryException
     */
    static public function getUserStatus($id)
    {
        $user = self::getUserById($id);
        if($user->getStatus() != self::STATUS_ENABLE) {
            return '该用户已经被锁定，当前不能登录';
        }

        return true;
    }

    /**
     * 生成一个用户编号
     * @param $level_id
     * @return null|string
     */
    public static function generateUserNumber($level_id)
    {
        $level = Level::getLevelAmount($level_id);
        $prefix = substr($level, 0, 1);
        $number = $prefix . date('ymd') . Random::random_numeric(0, 9999);
        if(EntityUsers::findFirst(['number = :number:', 'bind' => ['number' => $number]]) !== false) {
            $number = self::generateUserNumber($level_id);
        }

        return $number;
    }

    /**
     * 根据企业ID获取用户信息
     * @param $company_id
     * @return bool|EntityUsers
     */
    public static function getCompanyUser($company_id)
    {
        $company = Company::getCompanyById($company_id);
        $user_id = $company->getUserId();

        if ($user_id) {
            $user = self::getUserById($user_id);
            if ($user === false) {
                return false;
            }
            return $user;
        }

        return false;
    }



}