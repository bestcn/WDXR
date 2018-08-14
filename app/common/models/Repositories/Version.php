<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 17:01
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Version as EntityVersion;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
class Version
{

    /**
     * @param $id
     * @return EntityVersion
     */
    static public function getVersionById($id)
    {
        /**
         * @var $admin EntityVersion
         */
        $Version = EntityVersion::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $Version;
    }

    public function getLast()
    {
        return EntityVersion::query()
            ->orderBy('time DESC')
            ->execute();
    }

    public function getVersion()
    {
        $verify = EntityVersion::findFirst(['order' => 'time desc']);
        return $verify;
    }

    public function addNew($data)
    {
        $Version = new EntityVersion();
        $Version->setId($data["id"]);
        $Version->setTime(date('Y-m-d H:i:s',time()));
        $Version->setUrl($data["url"]);
        $Version->setLog($data["log"]);
        $Version->setAdminId($_SESSION['auth-identity']['name']);
        if (!$Version->save()) {
            throw new InvalidRepositoryException($user->getMessages()[0]);
        }
    }

    public function edit($id, $data)
    {
        $Version = Version::getVersionById($id);
        $Version->setTime(date('Y-m-d H:i:s',time()));
        $Version->setUrl($data["url"]);
        $Version->setLog($data["log"]);
        $Version->setAdminId($_SESSION['auth-identity']['name']);
        if (!$Version->save()) {
            throw new InvalidRepositoryException($user->getMessages()[0]);
        }

        return true;
    }


}