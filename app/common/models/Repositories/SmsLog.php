<?php
namespace Wdxr\Models\Repositories;

use Phalcon\Security\Random;
use Wdxr\Models\Entities\SmsLog as EntitySmsLog;

class SmsLog
{

    static public function add(\AliyunMNS\Responses\PublishMessageResponse $res, $phone)
    {
        $sms_log = new EntitySmsLog();
        $random = new Random();
        $sms_log->setId($random->uuid());
        $sms_log->setIsSuccess((int)$res->isSucceed());
        $sms_log->setStatus($res->getStatusCode());
        $sms_log->setMessageId($res->getMessageId());
        $sms_log->setMessageBody($res->getMessageBodyMD5());
        $sms_log->setPhone($phone);
        if(!$sms_log->save()) {
            return false;
        }
        return true;
    }

    static public function getPhone($phone)
    {
        return EntitySmsLog::find((['conditions' => 'phone = :phone:', 'bind' => ['phone' => $phone]]));
    }

    static public function getSmsLogById($id)
    {
        return EntitySmsLog::findFirst((['conditions' => 'id = :id:', 'bind' => ['id' => $id]]));
    }




}