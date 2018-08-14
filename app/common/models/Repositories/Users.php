<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Users as EntityUser;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class Users extends Repositories
{

    static public function addNew($data)
    {
        $user = new EntityUser();
        $user->setName($data['name']);
        $password = $user->getDI()->get('security')->hash('000000');
        $user->setPassword($password);
        $user->setStatus(User::STATUS_ENABLE);

        if(!$user->save()) {
            $messages = $user->getMessages();
            foreach ($messages as $message) {
                throw new InvalidRepositoryException($message->getMessage());
            }
            throw new InvalidRepositoryException('添加用户失败');
        }
        return $user->getId();
    }


}