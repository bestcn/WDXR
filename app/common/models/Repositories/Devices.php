<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 17:01
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Auth\UserAuth;
use Wdxr\Models\Entities\Devices as EntityDevices;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
class Devices
{

    /**
     * @param $id
     * @return EntityDevices
     */
    static public function getDevicesByUserId($user_id)
    {
        /**
         * @var $admin EntityDevices
         */
        $Devices = EntityDevices::findFirst(['conditions' => 'user_id = :user_id:', 'bind' => ['user_id' => $user_id]]);
        return $Devices;
    }

    static public function getByToken($token)
    {
        /**
         * @var $admin EntityDevices
         */
        //当前登录设备token
        $thistoken = (new UserAuth())->getToken();
        $Devices = EntityDevices::findFirst(['conditions' => 'token = :token:', 'bind' => ['token' => $token]]);
        if($Devices === false || $thistoken == $Devices->getToken()){
            return true;
        }
        return false;
    }

    static public function getDevicesByUserIdAndToken($user_id)
    {
        /**
         * @var $admin EntityDevices
         */
//        $Devices = EntityDevices::find(
//            [
//            'conditions' => 'user_id = :user_id:',
//            'bind' => ['user_id' => $user_id],
//            'order'=>['']
//            ]
//        );
        return EntityDevices::query()
            ->where("user_id = $user_id")
            ->orderBy(' time desc')
            ->execute();
//        return $Devices;
    }

    static public function findFirstById($id)
    {
        $Devices = EntityDevices::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $Devices;
    }

    static public function findFirstByToken($token)
    {
        $Devices = EntityDevices::findFirst(['conditions' => 'token = :token:', 'bind' => ['token' => $token]]);
        return $Devices;
    }

    public function getLast()
    {
        return EntityDevices::query()
            ->orderBy('id DESC')
            ->execute();
    }

    public function addNew($data)
    {
        $Devices = new EntityDevices();
        $Devices->setUserId($data["user_id"]);
        $Devices->setDeviceId($data["device_id"]);
        $Devices->setType($data["type"]);
        $Devices->setName($data['name'] ?: '');
        $Devices->setDeviceName($data['device_name'] ?: '');
        $Devices->setToken($data['token'] ?: '');
        if (!$Devices->save()) {
            throw new InvalidRepositoryException($Devices->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $Devices = Devices::getDevicesByUserId($id);
        $Devices->setUserId($data["user_id"]);
        $Devices->setDeviceId($data["device_id"]);
        $Devices->setType($data["type"]);
        $Devices->setName($data['name'] ?: '');
        $Devices->setDeviceName($data['device_name'] ?: '');
        $Devices->setToken($data['token'] ?: '');
        if (!$Devices->save()) {
            throw new InvalidRepositoryException($Devices->getMessages()[0]);
        }
        return true;
    }

    static public function getDeviceByDeviceId($device_id)
    {
        $Devices = EntityDevices::findFirst(['conditions' => 'device_id = :device_id:', 'bind' => ['device_id' => $device_id]]);
        return $Devices;
    }

    static public function deleteDeviceByToken($token)
    {
        $Devices = EntityDevices::find(['conditions' => 'token = :token:', 'bind' => ['token' => $token]]);
        if($Devices){
            $Devices->delete();
        }else{
            return true;
        }
        return true;
    }

    public function deleteDevice($device_id)
    {
        $Devices = Devices::getDeviceByDeviceId($device_id);
        if ($Devices !== false){
            if ($Devices->delete()){
                return true;
            }
        }
        return true;
    }

    public function deleteByToken($token)
    {
        $Devices = EntityDevices::findFirst(['conditions' => 'token = :token:', 'bind' => ['token' => $token]]);
        if ($Devices !== false){
            if ($Devices->delete()){
                return true;
            }
        }
        return true;
    }

    public function deleteByUserId($user_id)
    {
        $Devices = Devices::getDevicesByUserId($user_id);
        if ($Devices != false){
            if ($Devices->delete()){
                return true;
            }
        }
        return true;
    }



}