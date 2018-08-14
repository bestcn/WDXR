<?php
namespace Wdxr\Models\Services;

use Wdxr\Models\Entities\Attachments as EntityAttachment;
use Wdxr\Models\Repositories\Attachment as RepoAttachment;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;

class UploadService extends Services
{

    public static function getAttachmentUrl($attachment_id)
    {
        $attachment = RepoAttachment::getLastAttachmentById($attachment_id);
        if ($attachment === false) {
            return false;
        }
        $object_id = $attachment->getObjectId();

        $client = (new Cos())->private_url($object_id);//\OSS\Common::getOssClient();
//        return $client->signUrl($bucket, $object_id, $expire_time);
        return $client;
    }


    public static function getAttachmentsUrl($object_ids)
    {
        $array = array();
        foreach ($object_ids as $val) {
            $array[] =  (new Cos())->private_url($val['object_id']);//\OSS\Common::getOSSUrl($val['object_id']);
        }
        return $array;
    }

}