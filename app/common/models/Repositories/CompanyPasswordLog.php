<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\CompanyPasswordLogs;
use Wdxr\Models\Entities\PasswordChanges;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class CompanyPasswordLog extends Repositories
{

    //企业密码修改类
    static public function setRecord($userId)
    {
        $entity = new CompanyPasswordLogs();
        $entity->setUsersId($userId);
        $entity->setCreatedAt(time());
        $ip = $entity->getDI()->get('request')->getClientAddress();
        $userAgent = $entity->getDI()->get('request')->getUserAgent();
        $entity->setIpAddress($ip);
        $entity->setUserAgent($userAgent);

        if($entity->save() == false) {
            foreach ($entity->getMessages() as $message)
            {
                throw new InvalidRepositoryException($message);
            }
            throw new InvalidRepositoryException('保存修改密码记录失败');
        }
        return true;
    }
}