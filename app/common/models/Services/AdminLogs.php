<?php
namespace Wdxr\Models\Services;

use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class AdminLogs extends Services
{

    static public function getAdminLoginSuccessLogs($user_id, $numberPage)
    {
        return new PaginatorQueryBuilder([
            'builder' => Services::getStaticModelsManager()->createBuilder()
            ->columns('loginTime, ipAddress, userAgent')
            ->where("usersId = :id:", ['id' => $user_id])
            ->from('Wdxr\Models\Entities\SuccessLogins')
            ->orderBy('loginTime desc'),
            'limit'=> 10,
            'page' => $numberPage
        ]);

    }

    static public function getAdminLoginFailedLogs($user_id, $numberPage)
    {
        return new PaginatorQueryBuilder([
            'builder' => Services::getStaticModelsManager()->createBuilder()
            ->columns('attempted, ipAddress, userAgent')
            ->where("usersId = :id:", ['id' => $user_id])
            ->from('Wdxr\Models\Entities\FailedLogins')
            ->orderBy('attempted desc'),
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

    static public function getAdminPasswordLogs($user_id, $numberPage)
    {
        return new PaginatorQueryBuilder([
            'builder' => Services::getStaticModelsManager()->createBuilder()
                ->columns('createdAt, ipAddress, userAgent')
                ->where("usersId = :id:", ['id' => $user_id])
                ->from('Wdxr\Models\Entities\PasswordChanges')
                ->orderBy('createdAt desc'),
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

}