<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\PasswordChanges;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class PasswordChange extends Repositories
{

    static public function setRecord($userId)
    {
        $entity = new PasswordChanges();
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