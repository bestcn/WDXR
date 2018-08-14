<?php
namespace Wdxr\Models\Services;

use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Models\Repositories\User as RepoUser;

class User extends Services
{


    public static function getUserListPagintor($parameters, $numberPage)
    {
        $conditions = '';
        $bind = [];
        if (!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->from('Wdxr\Models\Entities\Users')
            ->orderBy('id');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

    /**
     * 修改用户密码
     * @param $user_id
     * @param $old_password
     * @param $password
     * @return bool
     * @throws InvalidServiceException
     */
    public static function changePassword($user_id, $old_password, $password)
    {
        $user = \Wdxr\Models\Repositories\User::getUserById($user_id);
        if ($user === false) {
            throw new InvalidServiceException("获取用户账号信息失败");
        }

        $is_right = $user->getDI()->get('security')->checkHash($old_password, $user->getPassword());
        if ($is_right === false) {
            throw new InvalidServiceException("旧密码错误");
        }

        (new \Wdxr\Models\Repositories\User)->changePassword($user_id, $password);

        return true;
    }

    public static function updateUserPhone($user_id, $phone)
    {
        $user = RepoUser::getUserById($user_id);
        if ($user) {
            $user->setPhone($phone);
            return $user->save();
        }
        return false;
    }

}
