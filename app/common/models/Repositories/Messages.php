<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 17:01
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Messages as EntityMessages;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Services\PushService;

class Messages
{

    static public function findFirstById($id)
    {
        $Messages = EntityMessages::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $Messages;
    }

    public function getUnreadMessage($id,$limit,$type)
    {
        return EntityMessages::query()->where("user_id = '{$id}'")
            ->andWhere("status != ".PushService::DEL)
            ->andWhere("type = '".$type."'")
            ->limit('10',$limit)
            ->orderBy('status asc,id desc')
            ->execute();
    }

    public function getUnreadMessageUnread($id)
    {
        return EntityMessages::query()->where("user_id = '{$id}'")
            ->andWhere("status = ".PushService::UNREAD)
            ->andWhere("type != 3")
            ->orderBy('id asc')
            ->execute();
    }

    /*
     * 获取App首页通知消息
     */
    public function getInformMessages()
    {
        return $Messages = EntityMessages::findFirst(['conditions' => 'type = :type:', 'bind' => ['type' => 3],'order' => 'time desc']);
    }

    public function getLast()
    {
        return EntityMessages::query()
            ->orderBy('id DESC')
            ->execute();
    }

    public function addNew($data)
    {
        $Messages = new EntityMessages();
        $Messages->setTitle($data["title"]);
        $Messages->setBody($data["body"]);
        $Messages->setType($data["type"]);
        $Messages->setStatus($data['status']);
        $Messages->setUserId($data['user_id']);
        if (!$Messages->save()) {
            throw new InvalidRepositoryException($Messages->getMessages()[0]);
        }
        return true;
    }

    public function addNewMessage($data)
    {
        $Messages = new EntityMessages();
        $Messages->setTitle($data["title"]);
        $Messages->setBody($data["body"]);
        $Messages->setType(3);
        $Messages->setStatus(0);
        $Messages->setUserId(1);
        if (!$Messages->save()) {
            throw new InvalidRepositoryException($Messages->getMessages()[0]);
        }
        return true;
    }

    static public function deleteMessage($id)
    {
        $Messages = Messages::findFirstById($id);
        if (!$Messages) {
            throw new InvalidRepositoryException("没有找到消息");
        }
        $Messages->setStatus(PushService::DEL);
        if (!$Messages->save()) {
            throw new InvalidRepositoryException("删除失败");
        }

        return true;
    }

    public function delete($id)
    {
        $Messages = Messages::findFirstById($id);
        if (!$Messages) {
            throw new InvalidRepositoryException("消息没有找到");
        }

        if (!$Messages->delete()) {
            throw new InvalidRepositoryException("消息删除失败");
        }

        return true;
    }



}