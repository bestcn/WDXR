<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Auth\UserAuth;
use Wdxr\Models\Entities\VerifyMessages as EntitiesVerifyMessages;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class VerifyMessages extends Repositories
{

//是否已读
    const READ_IS = 2;  //是
    const READ_NOT = 1; //否

    static private $_instance = null;

//通过ID查找
    static public function getVerifyMessagesById($id)
    {
        if(is_null(self::$_instance)) {
            self::$_instance = EntitiesVerifyMessages::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        }

        return self::$_instance;
    }

//    查找所有未读
    static public function getUnreadVerify()
    {
        $verify = EntitiesVerifyMessages::find(['conditions' => 'status = :status:',
            'bind' => ['status' => self::READ_NOT],
            'order' => 'id desc']);

        return $verify;
    }

    //查找前5未读审核
    static public function getUnread5Verify()
    {
        $verify = EntitiesVerifyMessages::find(['conditions' => 'status = :status:',
            'bind' => ['status' => self::READ_NOT],
            'limit' => 5,
            'order' => 'id desc']);

        return $verify;
    }




    //通过申请ID设置为已读
    static public function setVerifyMessagesById($id)
    {
        $VerifyMessages = EntitiesVerifyMessages::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        $VerifyMessages->setSelectTime(time());
        $VerifyMessages->setStatus(self::READ_IS);
        $VerifyMessages->setSelectId($_SESSION["wdxr-app#auth-identity"]["id"]);
        if($VerifyMessages->save() === false){
            foreach ($VerifyMessages->getMessages() as $message) {
                throw new InvalidRepositoryException($message->getMessage());
            }
            throw new InvalidRepositoryException('修改审核消息失败');
        }
        return $VerifyMessages->getId();
    }


//建立新的记录
    static public function newMessages($title,$content)
    {

        $verify_message = new EntitiesVerifyMessages();
        $verify_message->setTitle($title);
        $verify_message->setContent($content);
        $verify_message->setStatus(self::READ_NOT);
        $verify_message->setCreateTime(time());
        if($verify_message->save() === false) {
            foreach ($verify_message->getMessages() as $message) {
                throw new InvalidRepositoryException($message->getMessage());
            }
            throw new InvalidRepositoryException('添加审核消息失败');
        }
        return $verify_message->getId();
    }

    //通过ID 查找删除
    static public function deleteVerifyMessagesById($id)
    {
        $result = EntitiesVerifyMessages::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);

        if(!$result){
            throw new InvalidRepositoryException("没有查找到当前消息");
        }
        if(!$result->delete()){
            throw new InvalidRepositoryException("当前消息删除失败");
        }

        return true;
    }



}