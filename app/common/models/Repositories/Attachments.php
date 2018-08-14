<?php
namespace Wdxr\Models\Repositories;

use Lcobucci\JWT\JWT;
use Wdxr\Models\Entities\Attachments as EntityAttachment;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class Attachments extends Repositories
{


    static public function newAttachment($name, $size, $path, $object_id = null, $device_id = null)
    {
        $device_id = is_null($device_id) ? JWT::getUid() : $device_id;
        $attachment = new EntityAttachment();
        $attachment->setName($name);
        $attachment->setSize($size);
        $attachment->setPath($path);
        $attachment->setUploadTime(time());
        $attachment->setObjectId($object_id);
        $attachment->setAdminId($device_id);
        if ($attachment->save() === false) {
            throw new InvalidRepositoryException('文件附件保存失败');
        }
        return $attachment->getId();
    }

    static public function addAttachment($object_id, $device_id = null)
    {
        $device_id = is_null($device_id) ? JWT::getUid() : $device_id;
        $attachment = new EntityAttachment();
        $attachment->setName($object_id);
        $attachment->setUploadTime(time());
        $attachment->setObjectId($object_id);
        $attachment->setAdminId($device_id);
        if($attachment->save()) {
            return $attachment->getId();
        }
        return false;
    }

}