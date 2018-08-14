<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Attachments as EntityAttachment;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Services\Cos;

class Attachment extends Repositories
{

    public static function getLastAttachmentById($id)
    {
        return EntityAttachment::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
    }

    /**
     * @param $id
     * @return EntityAttachment[]
     */
    public static function getAttachmentById($id)
    {
        /**
         * @var $admin EntityAttachment
         */
        $company = EntityAttachment::find(['conditions' => 'id in ({id:array})', 'bind' => ['id' => $id]]);
        return $company;
    }


    static public function getById($id)
    {
        /**
         * @var $admin EntityAttachment
         */
        $company = EntityAttachment::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $company;
    }


    public function getLast()
    {
        return EntityAttachment::query()
            ->orderBy('id DESC')
            ->execute();
    }

    public function addNew($data)
    {
        $Attachment = new EntityAttachment();
        $Attachment->setName($data["name"]);
        $Attachment->setUploadTime($data["upload_time"]);
        $Attachment->setSize($data["size"]);
        $Attachment->setPath($data["path"]);
        if (!$Attachment->save()) {
            throw new InvalidRepositoryException($Attachment->getMessages()[0]);
        }
        return $Attachment -> getWriteConnection() -> lastInsertId($Attachment -> getSource());
    }

    public static function getAttachmentUrl($attachment_id)
    {
        $attachment_ids = explode(',', $attachment_id);
        $attachments = self::getAttachmentById($attachment_ids);
        $array = array();
        foreach ($attachments as $attachment) {
            $array[] =  (new Cos())->private_url($attachment->getObjectId());
        }
        return $array;
    }

}